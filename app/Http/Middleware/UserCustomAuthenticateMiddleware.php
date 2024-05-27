<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class UserCustomAuthenticateMiddleware
{
    //***************************************  Auth Files  *************************************************************

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/login');
    }

    protected function loggedOut(Request $request)
    {
        //
    }

    protected function guard()
    {
        return Auth::guard();
    }

    //******************************************************************************************************************


    private function track_user_activities(Request $request)
    {

        $users = Auth::user();

        DB::table("track_user_activities")->insert(
            ['ip_add' => $request->ip(), 'act_time' => now(), 'act_url' => url()->full(), 'emp_code' => $users->username]
        );
    }

    public function handle($request, Closure $next)
    {

        $users = Auth::user();

        if (!$users) {
            return $this->logout($request);
        }

        try {
            $this->track_user_activities($request, $users);

            if ($users->mdas_master_role_id == 2) {
                return $next($request);
            } elseif ($users->mdas_master_role_id == 3) {
                return $next($request);
            } elseif ($users->mdas_master_role_id == 4) {
                return $next($request);
            } else {
                return $this->logout($request);
            }
        } catch (\Exception $e) {
            return $this->logout($request);
        }

        /*if(!$request->session()->exists('users')){
        return $this->logout($request);
        }
        $users=$request->session()->get('users');
        try{
        if(!isset($users->role)){
        return $this->logout($request);
        }
        $this->track_user_activities($request, $users);
        if (in_array(4, $users->role)) { //DISTRICT ADMIN
        return $next($request);
        }elseif(in_array(5, $users->role)) { //BLOCK ADMIN
        return $next($request);
        } elseif(in_array(3, $users->role)) { //STATE ADMIN
        return $next($request);
        }elseif(in_array(2, $users->role)) { //USER
        return $next($request);
        }else{
        return $this->logout($request);
        }
        }catch(\Exception $e){
        return $this->logout($request);
        }*/

    }
}