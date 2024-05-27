<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;


class UcCustomAuthenticateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
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
    
    private function track_user_activities(Request $request, $users){
        DB::table("track_user_activities")->insert(
            ['ip_add' => $request->ip(), 'act_time' => now(), 'act_url'=> url()->full(), 'emp_code'=>$users->employee_code]
        );
    }
    
    public function handle($request, Closure $next)
    {
        $users=Auth::user();
        
        if($users->mdas_master_role_id == 2 || $users->mdas_master_role_id == 6){
            return $next($request);
        }
         return $this->logout($request);
    }
}
