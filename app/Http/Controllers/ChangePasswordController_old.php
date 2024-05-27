<?php

namespace App\Http\Controllers;

use App\Osr\OsrMasterFyYear;
use Illuminate\Http\Request;
use App\UsersManagement\MdasUser;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
use App\ConfigMdas;
use Illuminate\Support\Facades\Hash;
class ChangePasswordController extends Controller
{
//********************Update Password for Both User and Admin***********************************************************
    public function updatePassword(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        DB::beginTransaction();
        try {

            $messages = [
                'existing_password.required' => 'This field is required',
                'new_password.required' => 'This field is required',
                'conform_password.required' => 'This field is required'
            ];

            $validatorArray = [
                'existing_password' => 'required',
                'new_password' => 'required',
                'conform_password' => 'required'
            ];

            $validator = Validator::make($request->all(), $validatorArray, $messages);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $returnData['msg'] = "VE";
                $returnData['errors'] = $errors;
                return response()->json($returnData);
            }
            $existing_password = $request->input('existing_password');
            $new_password = $request->input('new_password');
            $conform_password = $request->input('conform_password');
            $user_id= Auth::user()->id;
            $user_db = MdasUser::select('password')->where('id','=', $user_id)->first();
            $password_db = $user_db->password;
            if(!$new_password==$conform_password){
                $returnData['msgType'] = false;
                $returnData['msg'] = "Password does not match";
                return response()->json($returnData);
            }
            if(password_verify($existing_password, $password_db)){
                $newPasswordSave = MdasUser::where('id',$user_id)->first();
                $newPasswordSave->password = password_hash($new_password, PASSWORD_DEFAULT);
                if (!$newPasswordSave->save()) {
                    return response()->json($returnData);
                }
            }else{
                $returnData['msgType'] = false;
                $returnData['msg'] = "Password is Wrong";
                return response()->json($returnData);
            }

        }catch (\Exception $e){
            DB::rollback();
            $returnData['msg'] = "Server Exception.".$e->getMessage();
            return response()->json($returnData);
        }
        DB::commit();
        $returnData['msgType'] = true;
        $returnData['msg'] = "Successfully added";
        return response()->json($returnData);
    }
//***********************************User's Change Password Page********************************************************
     //********************Update Profile for Both User and Admin***********************************************************
    public function updateProfile(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        DB::beginTransaction();
        try {

            $messages = [
                'f_name.required' => 'This field is required',
                'l_name.required' => 'This field is required',
                'mobile.required' => 'This field is required',
                'email.required' => 'This field is required',
                'designation.required' => 'This field is required',
                'address.required' => 'This field is required'
            ];

            $validatorArray = [
                'f_name' => 'required',
                'l_name' => 'required',
                'mobile' => 'required',
                'email' => 'required',
                'designation' => 'required',
                'address' => 'required'
            ];

            $validator = Validator::make($request->all(), $validatorArray, $messages);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $returnData['msg'] = "VE";
                $returnData['errors'] = $errors;
                return response()->json($returnData);
            }
            $f_name = $request->input('f_name');
            $m_name = $request->input('m_name');
            $l_name = $request->input('l_name');
            $mobile = $request->input('mobile');
            $email = $request->input('email');
            $designation = $request->input('designation');
            $address = $request->input('address');
            $user_id= Auth::user()->id;
            
            $editProfileSave = MdasUser::where('id',$user_id)->first();
            $editProfileSave->f_name =  $f_name;
            $editProfileSave->m_name =  $m_name;
            $editProfileSave->l_name =  $l_name;
            $editProfileSave->mobile =  $mobile;
            $editProfileSave->email =  $email;
            $editProfileSave->designation =  $designation;
            $editProfileSave->address =  $address;
            if (!$editProfileSave->save()) {
                    return response()->json($returnData);
            }
            

        }catch (\Exception $e){
            DB::rollback();
            $returnData['msg'] = "Server Exception.".$e->getMessage();
            return response()->json($returnData);
        }
        DB::commit();
        $returnData['msgType'] = true;
        $returnData['msg'] = "Successfully Updated";
        return response()->json($returnData);
    }

    //********************Update Profile Picture for Both User and Admin***********************************************************
    public function updateProfilePic(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        DB::beginTransaction();
        try {

            $messages = [
                'image.required' => 'This field is required'
            ];

            $validatorArray = [
                'image' => 'required'
            ];

            $validator = Validator::make($request->all(), $validatorArray, $messages);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $returnData['msg'] = "VE";
                $returnData['errors'] = $errors;
                return response()->json($returnData);
            }
            $image_path= NULL;
            if ($request->file('image')) {
                $image_path = $request->file('image')->store('users');
            }
            $user_id= Auth::user()->id;
            
            $editProfilePicSave = MdasUser::where('id',$user_id)->first();
            $editProfilePicSave->image =  $image_path;
            if (!$editProfilePicSave->save()) {
                    return response()->json($returnData);
            }
            

        }catch (\Exception $e){
            DB::rollback();
            $returnData['msg'] = "Server Exception.".$e->getMessage();
            return response()->json($returnData);
        }
        DB::commit();
        $returnData['msgType'] = true;
        $returnData['msg'] = "Successfully Updated";
        return response()->json($returnData);
    }   
	public function ChangePassword() {
        $max_fy_id=OsrMasterFyYear::getMaxFyYear();
        $data=[
            'fy_id'=>$max_fy_id
        ];
        return view('change_password', compact('data'));
    }
    public function user_management (Request $request)
    {
        $users = Auth::user();
        $district_code = $users->district_code;
        $zp_code = $users->zp_id;
        $ap_code = $users->ap_id;
        $gp_code = $users->gp_id;
        $master_level = $users->mdas_master_level_id;
        $imgUrl=ConfigMdas::allActiveList()->imgUrl;
        if($master_level==2){
            $levelArry = [3,4];
            $mdasUserList = MdasUser::leftJoin('mdas_master_levels AS l','l.id','=','mdas_users.mdas_master_level_id')
                ->leftJoin('mdas_master_roles AS r','r.id','=','mdas_users.mdas_master_role_id')
                ->leftJoin('zila_parishads AS zp','zp.id','=','mdas_users.zp_id')
                ->leftJoin('anchalik_parishads AS ap','ap.id','=','mdas_users.ap_id')
                ->leftJoin('gram_panchyats AS gp','gp.gram_panchyat_id','=','mdas_users.gp_id')
                ->where([
                    ['mdas_users.zp_id',$zp_code],
                    ['district_code',$district_code]
                    ])
                ->whereIn('mdas_users.mdas_master_level_id',$levelArry)
                ->select('mdas_users.id AS mdas_id','username','designation','employee_code','f_name','m_name','l_name','image','mobile','email','status','password','l.level_name','r.role_name','zp.zila_parishad_name','ap.anchalik_parishad_name','gp.gram_panchayat_name')->get();
        }elseif ($master_level==3) {
            $levelArry = [4];
            $mdasUserList = MdasUser::leftJoin('mdas_master_levels AS l','l.id','=','mdas_users.mdas_master_level_id')
                ->leftJoin('mdas_master_roles AS r','r.id','=','mdas_users.mdas_master_role_id')
                ->leftJoin('zila_parishads AS zp','zp.id','=','mdas_users.zp_id')
                ->leftJoin('anchalik_parishads AS ap','ap.id','=','mdas_users.ap_id')
                ->leftJoin('gram_panchyats AS gp','gp.gram_panchyat_id','=','mdas_users.gp_id')
                ->where([
                    ['mdas_users.zp_id',$zp_code],
                    ['mdas_users.ap_id',$ap_code],
                    ['district_code',$district_code]
                ])
                ->whereIn('mdas_users.mdas_master_level_id',$levelArry)
                ->select('mdas_users.id AS mdas_id','username','designation','employee_code','f_name','m_name','l_name','image','mobile','email','status','password','l.level_name','r.role_name','zp.zila_parishad_name','ap.anchalik_parishad_name','gp.gram_panchayat_name')->get();
        }else {
            $mdasUserList = [];
        }

        $max_fy_id=OsrMasterFyYear::getMaxFyYear();
        $data=[
            'fy_id'=>$max_fy_id
        ];

        return view ('user_management', compact('mdasUserList','imgUrl', 'data'));
    }
    public function statusUser (Request $request) {
        $user = MdasUser::find($request->user_id);
        $user->status = $request->status;
        $user->save();
        return response()->json(['success'=>'Status change successfully.']);
    }
	//***********************************User's Profile********************************************************
    public function profile() {
        $users = Auth::user();
        $user_id = $users->id;
        $imgUrl=ConfigMdas::allActiveList()->imgUrl;
            $mdasUser = MdasUser::leftJoin('mdas_master_levels AS l','l.id','=','mdas_users.mdas_master_level_id')
                ->leftJoin('mdas_master_roles AS r','r.id','=','mdas_users.mdas_master_role_id')
                ->where('mdas_users.id',$user_id)
                ->select('mdas_users.id AS mdas_id','username','designation','employee_code','f_name','m_name','l_name','image','mobile','email','address','status','password','l.level_name','r.role_name')->first();
                // dd($mdasUser);

                $data=['fy_id'=>NULL];
        return view('profile', compact('mdasUser','imgUrl', 'data'));
    }

}
