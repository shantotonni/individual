<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\FactoryBusiness;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard(){
        return view('admin.dashboard');
    }

    public function individualDashboard(){
        //$businesses = DB::connection('FactoryDashBoard')->select("SELECT DISTINCT BusinessName FROM FactoryKPI order by BusinessName desc");
        $EmpCode = Auth::user()->EmpCode;
        $businesses = DB::connection('FactoryDashBoard')->select("SELECT * from vw_ResponsiblePerson where StaffId='$EmpCode' order by BusinessName desc");
       return view('admin.individual_dashboard',compact('businesses'));
    }

    public function getIndividualDashboard(Request $request){
        $EmpCode = Auth::user()->EmpCode;
        $business = $request->business;
        $user = DB::connection('FactoryDashBoard')->select("SELECT distinct(StaffId),BusinessName,ResponsiblePerson from vw_ResponsiblePerson where StaffId='$EmpCode' and BusinessName='$business'");

        $conn = DB::connection('FactoryDashBoard');
        $sql = "EXEC SP_PWCDashboradNewFormat1 '$business'";
        $pdo = $conn->getPdo()->prepare($sql);
        $pdo->execute();
        $res = array();
        do {
            $rows = $pdo->fetchAll(\PDO::FETCH_ASSOC);
            $res[] = $rows;
        } while ($pdo->nextRowset());
        return response()->json([
            'user'=>$user[0],
            'record_one'=>$res,
        ]);

    }

}
