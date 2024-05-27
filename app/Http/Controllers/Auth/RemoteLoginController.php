<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use Crypt;
class RemoteLoginController extends Controller
{
    use AuthenticatesUsers;
 
    protected $redirectTo = '/dashboard';

    public function loginWithUsername(Request $request){

        $returnData['msgType']=false;
        $returnData['msg']= "Sorry! the credentials donot match our records";

        $data= $request->input('userData');

        try{
            $username=base64_decode($data['loginkey']);
        }catch(\Exception $e){
            $this->sendFailedLoginResponse($request);
            return $returnData;
        };

        $user = DB::table('users')->where('username', '=', $username)->first();

        if ($user) {
            if ($this->attemptLogin_custom($user->id)) {

                $this->sendLoginResponseCustom($request);

                if (isset($data['data'])) {
                    $data['data']['role'] = isset($data['roll']) ? $data['roll'] : [];
                } else {
                    $data['data']['employee_code'] = $username;
                    $data['data']['role'] = isset($data['roll']) ? $data['roll'] : [];
                }

                $userData= (object)$data['data'];

                //echo json_encode($data['data']);

                $request->session()->put('users', $userData);

                $returnData['msgType'] = true;
                $returnData['msg'] = "Successfully logged in!";
                return $returnData;
            }
        }

        $this->sendFailedLoginResponse($request);
        return $returnData;
    } 
    
    protected function attemptLogin_custom($user){
         /*return $this->guard()->login($user, true);;*/
        
       /* return $this->guard()->attempt(['username'=>$request['username'],'password'=>'']);*/
       return Auth::loginUsingId($user, true);
    }
    
}
