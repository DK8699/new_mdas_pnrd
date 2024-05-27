<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCustomAuthenticateMiddleware
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


    public function handle($request, Closure $next){

        $users=Auth::user();

        if($users->mdas_master_role_id <> 1){
            return $this->logout($request);
        }

        return $next($request);
    }
}
