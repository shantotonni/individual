<?php

namespace App\Http\Controllers;

use App\Model\UserMenu;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [];
        $data['OperationType']      = 'data_list';
        add_log($data);

        return view('admin.user.index');
    }

    public function getUser(Request $request){
        $users = User::query();
        return Datatables::eloquent($users)
            ->addColumn('action', function ($data){
                $buttons='';
                $buttons .= '<a href="'.route('user.edit',$data->EmpCode).'" class="btn btn-info btn-sm"> Edit</a>';
                $buttons .= '<a href="'.route('permission.list',$data->EmpCode).'" class="btn btn-success btn-sm" style="margin-left: 5px"> Menu Permission</a>';
               // $buttons .= '<a href="'.route('permission.list',$data->EmpCode).'" class="btn btn-danger btn-sm" style="margin-left: 5px"> Delete</a>';
                return $buttons;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create(){
        return view('admin.user.create');
    }

    public function store(Request $request){

        $this->validate($request,[
           'EmpCode' =>'required|unique:UserManager',
           'Password'=>'required|min:6',
           'status'  =>'required',
        ]);

        $user = new User();
        $user->EmpCode      = $request->EmpCode;
        $user->Password     = $request->Password;
        $user->PasswordNew  = $request->PasswordNew;
        $user->status       = $request->status;
        $user->UserType     = 0;
        $user->save();

        Toastr::success('User Created Successfully', 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('user.list');
    }

    public function edit($EmpCode){
        $user = User::where('EmpCode',$EmpCode)->first();
        return view('admin.user.edit',compact('user'));
    }

    public function update(Request $request,$EmpCode){
        $this->validate($request,[
            'EmpCode' =>"required",
            'status'  =>'required',
        ]);

        $user               = User::where('EmpCode',$EmpCode)->first();
        $user->EmpCode      = $request->EmpCode;
        $user->status       = $request->status;
        $user->save();

        Toastr::success('User Updated Successfully', 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('user.list');
    }

    public function permissionList($EmpCode){
        $sql = "SELECT
                M.MenuId,
                M.MenuName,
                M.MenuActiveLink,
                M.SubMenuName,
                M.Link,
                M.Icon,
                CASE WHEN UM.UserID IS NOT NULL THEN 'Selected' ELSE 'Not Selected' END MenuStatus
                FROM ManagementDashBoard.dbo.Menu M
                LEFT JOIN ManagementDashBoard..UserMenu UM
                    ON M.MenuID = UM.MenuID  AND UM.UserID = '$EmpCode'
                WHERE M.Active = 'Y'
                ORDER BY MenuOrder";
        $all_menu = DB::select($sql);
        $all_menu = collect($all_menu);
        return view('admin.user.permission',compact('all_menu','EmpCode'));
    }

    public function permissionStore(Request $request){
        $data = [];
        $data['OperationType']      = 'store';
        add_log($data);

       $result =  $this->doInsertMenuPermission($request->MenuId, $request->permission_user_id);
       if ($result == true) {
           Toastr::success('Permission Updated Successfully', 'Success', ["positionClass" => "toast-top-right"]);
           return redirect()->back();
       }
    }

    public function doInsertMenuPermission($MenuIds, $permission_user_id){

        $user_menu = UserMenu::where('UserId',$permission_user_id)->get();

        if ($user_menu) {
            foreach ($user_menu as $value){
                $value->delete();
            }

            foreach ($MenuIds as $MenuId){
                $usermenu = new UserMenu();
                $usermenu->MenuId = $MenuId;
                $usermenu->UserId = $permission_user_id;
                $usermenu->save();
            }
        }else{
            foreach ($MenuIds as $MenuId){
                $usermenu = new UserMenu();
                $usermenu->MenuId = $MenuId;
                $usermenu->UserId = $permission_user_id;
                $usermenu->save();
            }
        }

        return true;
    }

    public function changePassword()
    {
        $emp_code = Auth::user()->EmpCode;
        return view('admin.user.change_password',compact('emp_code'));
    }

    public function changePasswordStore(Request $request)
    {
        $this->validate($request,[
            'EmpCode' =>'required',
            'old_password' =>'required',
            'new_password' =>'required',
        ]);

        $data = [];
        $data['OperationType']      = 'store';
        add_log($data);

        $old_password_exist = User::where('EmpCode',$request->EmpCode)->where('Password',$request->old_password)->exists();

        if ($old_password_exist) {
            $already_exist_password = User::where('EmpCode',$request->EmpCode)->where('Password',$request->new_password)->exists();
            if ($already_exist_password) {
                Toastr::error('Old Password and New Password Same', 'Error', ["positionClass" => "toast-top-right"]);
                return redirect()->back();
            }else{
                $change_password = User::where('EmpCode',$request->EmpCode)->first();
                $change_password->Password = $request->new_password;
                $change_password->save();

                Toastr::success('Password Changed Successfully', 'Success', ["positionClass" => "toast-top-right"]);
                return redirect()->back();
            }
        }else{
            Toastr::error('Old Password Not Match', 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        //
    }
}
