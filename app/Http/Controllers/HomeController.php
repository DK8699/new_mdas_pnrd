<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request){

        $users= Auth::user();

        $username=$users->username;

        $users->employee_code=$username;

        $request->session()->put('users', $users);

        if($users->status==0){
            return $this->logout($request);
        }

        if($users->mdas_master_role_id==1){
            return redirect()->route('admin.dashboard');
        }elseif($users->mdas_master_role_id==2) { //DISTRICT ADMIN
            return redirect()->route('dashboard');
        } elseif($users->mdas_master_role_id==3) { //AP ADMIN
            return redirect()->route('dashboard');
        } elseif($users->mdas_master_role_id==4) { //GP ADMIN
            return redirect()->route('dashboard');
        }elseif($users->mdas_master_role_id==5) { //COURT CASE ADMIN
            return redirect()->route('admin.courtCases.dashboard');
        }elseif($users->mdas_master_role_id==6) { //EXTENSION CENTRE ADMIN
            return redirect()->route('dashboard');
        }
        elseif($users->mdas_master_role_id==7) { //DISTRICT COUNCIL ADMIN
            return redirect()->route('dashboard');
	   }
	    elseif($users->mdas_master_role_id==8) { //BLOCK COUNCIL ADMIN
            return redirect()->route('dashboard');
	   }
	    elseif($users->mdas_master_role_id==9) { //VCDC/VDC ADMIN
            return redirect()->route('dashboard');
	   }
        else{
            return $this->logout($request);
        }


    }

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


}
