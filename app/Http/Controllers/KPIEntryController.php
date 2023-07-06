<?php

namespace App\Http\Controllers;

use App\Http\Resources\KPIEntryDataCollection;
use App\Imports\KPIEntryImport;
use App\Model\FactoryBusiness;
use App\Model\KPIEntry;
use App\Model\KPIEntryDetails;
use App\Model\KPIList;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class KPIEntryController extends Controller
{
    public function kpiEntryList(){
        $kpi_entry_list = KPIEntry::with('kpi_details','user','user.employee','kpi_details.kpi')->get();
        $result = [];
        foreach ($kpi_entry_list as $value){
            $result [] = [
                'KPIEntryMasterCode'=>$value->KPIEntryMasterCode,
                'EmpCode'=>$value->EmpCode,
                'EmpName'=>isset($value->user->employee) ? $value->user->employee->Name:'',
                'Period'=>$value->Period,
                'Business'=>$value->Business,
                'KPICode'=>isset($value->kpi_details) ? $value->kpi_details->KPICode:'',
                'KPIName'=>isset($value->kpi_details->kpi) ? $value->kpi_details->kpi->KPIName:'',
                'Value'=>isset($value->kpi_details) ? $value->kpi_details->Value:'',
            ];
        }
        return view('admin.kpi_entry.kpi_entry_list',compact('result'));
    }

    public function kpiEntryCreate(){
        $kpi_list = KPIList::all();
        //$businesses = DB::connection('FactoryDashBoard')->table('Business')->get();
        $businesses = DB::connection('FactoryDashBoard')->select("SELECT DISTINCT BusinessName FROM FactoryKPI order by BusinessName desc");
        return view('admin.kpi_entry.kpi_entry_create',compact('kpi_list','businesses'));
    }

    public function kpiEntryStore(Request $request){
        $this->validate($request,[
            'Business'=>'required',
            'KPICode'=>'required',
            'Period'=>'required',
        ]);

        $exist = KPIEntry::join('KPIEntryMasterDetails','KPIEntryMasterDetails.KPIEntryMasterCode','=','KPIEntryMaster.KPIEntryMasterCode')
            ->where('KPIEntryMaster.EmpCode',Auth::user()->EmpCode)
            ->where('KPIEntryMasterDetails.KPICode',$request->KPICode)
            ->where('KPIEntryMaster.Period',$request->Period)->exists();
        if ($exist){
            Toastr::error('User Created Successfully', 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $exist = KPIEntry::orderBy('KPIEntryMasterCode','desc')->first();
            if ($exist){
                $KPIEntryMasterCode = $exist->KPIEntryMasterCode + 1;
            }else{
                $KPIEntryMasterCode = 1;
            }
            $kpiEntry = new KPIEntry();
            $kpiEntry->KPIEntryMasterCode = $KPIEntryMasterCode;
            $kpiEntry->EmpCode            = Auth::user()->EmpCode;
            $kpiEntry->Period             = $request->Period;
            $kpiEntry->Business           = $request->Business;

            $kpiEntry->EntryBy            = Auth::user()->EmpCode;
            $kpiEntry->EntryDate          = Carbon::now();
            $kpiEntry->EntrySource         = 'WEB';

            if ($kpiEntry->save()){
                $kpiEntryDetails = new KPIEntryDetails();
                $kpiEntryDetails->KPIEntryMasterCode = $KPIEntryMasterCode;
                $kpiEntryDetails->KPICode            = $request->KPICode;
                $kpiEntryDetails->Value              = $request->Value;
                $kpiEntryDetails->save();

                DB::commit();
                Toastr::success('User Created Successfully', 'Success', ["positionClass" => "toast-top-right"]);
                return redirect()->route('kpi.entry.list');
            }
        }catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong! '.$exception->getMessage()
            ],500);
        }

    }

    public function kpiEntryEdit($KPIEntryMasterCode){
        $kpi_list = KPIList::all();
        //$businesses = DB::connection('FactoryDashBoard')->select("SELECT DISTINCT BusinessName FROM FactoryKPI order by BusinessName desc");
        $businesses = DB::connection('FactoryDashBoard')->select("SELECT DISTINCT BusinessName FROM FactoryKPI order by BusinessName desc");
        $kpi_entry_data = KPIEntry::where('KPIEntryMasterCode',$KPIEntryMasterCode)->with('kpi_details')->first();
        return view('admin.kpi_entry.kpi_entry_edit',compact('kpi_entry_data','kpi_list','businesses'));
    }

    public function kpiEntryUpdate(Request $request, $KPIEntryMasterCode){
        DB::beginTransaction();
        try {
            $kpiEntry = KPIEntry::where('KPIEntryMasterCode',$KPIEntryMasterCode)->with('kpi_details')->first();
            $kpiEntry->Period             = $request->Period;
            $kpiEntry->Business           = $request->Business;

            $kpiEntry->EntryBy            = Auth::user()->EmpCode;
            $kpiEntry->EntryDate          = Carbon::now();
            $kpiEntry->EntrySource         = 'WEB';

            if ($kpiEntry->save()){
                $kpiEntryDetails = KPIEntryDetails::where('KPIEntryMasterCode',$KPIEntryMasterCode)->first();
                $kpiEntryDetails->KPICode            = $request->KPICode;
                $kpiEntryDetails->Value              = $request->Value;
                $kpiEntryDetails->save();

                DB::commit();
                Toastr::success('User Created Successfully', 'Success', ["positionClass" => "toast-top-right"]);
                return redirect()->route('kpi.entry.list');
            }
        }catch (\Exception $exception) {
            return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong! '.$exception->getMessage()
            ],500);
        }
    }

    public function kpiDataImport(Request $request){
        Excel::import(new KPIEntryImport(), $request->file('file'));
        return redirect()->back();
    }
}
