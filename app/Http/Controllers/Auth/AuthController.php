<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['loginForm','login']);
    }

    public function loginForm(){
        return view('auth.login');
    }

    public function login(Request $request){
        $user = User::where('EmpCode',$request->EmpCode)->where('Password',$request->Password)->first();
        if ($user) {
            Auth::login($user);
           // $usermenu = $this->doLoadMenu($request->EmpCode);

            //$data = [];
            //$data['OperationType']      = 'login';
            //add_log($data);

            //file_put_contents(public_path('/')."/assets/temp/usermenu_" . $request->EmpCode . ".tmp", serialize($usermenu));
            return redirect('/dashboard');
        }else{
            Toastr::success('Username or Password Wrong', 'Title', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
    }

    public function doLoadMenu($userid){

        $sql = "SELECT
                    M.MenuId,
                    M.MenuName,
                    M.MenuActiveLink,
                    M.SubMenuName,
                    M.Link,
                    M.Icon,
                    U.UserId
                FROM ManagementDashBoard.dbo.Menu M
                    INNER JOIN ManagementDashBoard.dbo.UserMenu U
                        ON M.MenuId = U.MenuId
                WHERE M.Active = 'Y'
                    AND U.UserId = '$userid'
                ORDER BY MenuOrder
                ";
        $all_menu = DB::select($sql);
        $all_menu = collect($all_menu);
        return $all_menu;
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect()->route('login');
    }

}
