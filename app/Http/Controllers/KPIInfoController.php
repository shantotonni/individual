<?php

namespace App\Http\Controllers;

use App\Model\Employee;
use App\Model\FactoryBusiness;
use App\Model\KPIList;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KPIInfoController extends Controller
{
    public function kpiList(){
        $kpi_list = KPIList::where('Active','Y')->orderBy('KPICode','desc')->paginate(15);
        return view('admin.kpi.list',compact('kpi_list'));
    }

    public function kpiCreate(){
        //$business = DB::connection('FactoryDashBoard')->table('Business')->get();
        $business = DB::connection('FactoryDashBoard')->select("SELECT DISTINCT BusinessName FROM FactoryKPI order by BusinessName desc");
        $UnitOfMeasure = DB::connection('FactoryDashBoard')->table('UnitOfMeasure')->get();
        return view('admin.kpi.kpi_info_create',compact('business','UnitOfMeasure'));
    }

    public function kpiStore(Request $request){
        $this->validate($request,[
            'Business'=>'required',
            'UnitOfMeasure'=>'required',
            'GoodnessIndictaor'=>'required',
            'KPIName'=>'required',
            'Weightage'=>'required',
            'Active'=>'required',
        ]);
        try {
            $BusinessCode = $request->Business;
            $generate_kpi_code = DB::connection('FactoryDashBoard')->select("SELECT '$BusinessCode'+FORMAT(CONVERT(INT,ISNULL(MAX(RIGHT(KPICode,3)),0))+1,'000') AS KPICode FROM FactoryKPI WHERE BusiCode='$BusinessCode'");
            $finalKpiCode = $generate_kpi_code[0]->KPICode;
            $business = FactoryBusiness::where('Business',$request->Business)->first();
            $EmpName = Employee::where('EmpCode',Auth::user()->EmpCode)->first()->Name;

            $kpi_info = new KPIList();
            $kpi_info->KPICode = $finalKpiCode;
            $kpi_info->KPIName = $request->KPIName;
            $kpi_info->BusinessName = $business->BusinessName;
            $kpi_info->ImpactArea = '';
            $kpi_info->UnitOfMeasure = $request->UnitOfMeasure;
            $kpi_info->GoodnessIndictaor = $request->GoodnessIndictaor;
            $kpi_info->Weightage = $request->Weightage;
            $kpi_info->Active = $request->Active;
            $kpi_info->ResponsiblePerson = $EmpName;
            $kpi_info->BusiCode = $BusinessCode;
            $kpi_info->save();

            Toastr::success('User Created Successfully', 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->route('kpiData.list');

        }catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong! '.$exception->getMessage()
            ],500);
        }
    }

    public function kpiEdit($KPICode){
        //$business = DB::connection('FactoryDashBoard')->table('Business')->get();
        $business = DB::connection('FactoryDashBoard')->select("SELECT DISTINCT BusinessName FROM FactoryKPI order by BusinessName desc");
        $UnitOfMeasure = DB::connection('FactoryDashBoard')->table('UnitOfMeasure')->get();
        $kpi_info = KPIList::where('KPICode',$KPICode)->first();
        return view('admin.kpi.kpi_info_edit',compact('kpi_info','business','UnitOfMeasure'));
    }

    public function kpiUpdate(Request $request, $KPICode){

        $this->validate($request,[
            'UnitOfMeasure'=>'required',
            'GoodnessIndictaor'=>'required',
            'KPIName'=>'required',
            'Weightage'=>'required',
            'Active'=>'required',
        ]);
        //dd($request->all());
        $kpi_info = KPIList::where('KPICode',$KPICode)->first();
        $kpi_info->KPIName = $request->KPIName;
        $kpi_info->ImpactArea = '';
        $kpi_info->UnitOfMeasure = $request->UnitOfMeasure;
        $kpi_info->GoodnessIndictaor = $request->GoodnessIndictaor;
        $kpi_info->Weightage = $request->Weightage;
        $kpi_info->Active = $request->Active;
        $kpi_info->save();

        Toastr::success('User Created Successfully', 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('kpiData.list');
    }

}
