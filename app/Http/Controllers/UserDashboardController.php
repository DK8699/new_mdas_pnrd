<?php

namespace App\Http\Controllers;

use App\Osr\OsrMasterFyYear;
use Illuminate\Http\Request;
use Auth;

class UserDashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
       
    }

    public function index(Request $request){
       
		$users=Auth::user();

        if($users->mdas_master_role_id==2) { //DISTRICT ADMIN
            $level="ZP";
        } elseif($users->mdas_master_role_id==3) { //AP ADMIN
            $level="AP";
        } elseif($users->mdas_master_role_id==4) { //GP ADMIN
            $level="GP";
        }elseif($users->mdas_master_role_id==5) { //COURT CASE ADMIN
            $level="CC";
        }
        elseif($users->mdas_master_role_id==6){
             $level="EX";
        }
		elseif($users->mdas_master_role_id==7){
             $level="DCA";
        }
	    elseif($users->mdas_master_role_id==8){
             $level="BCA";
        }
	    elseif($users->mdas_master_role_id==9){
             $level="GCA";
        }
        else{
            $level="NA";
        }

        $max_fy_id=OsrMasterFyYear::getMaxFyYear();

        $data=[
            'fy_id'=>$max_fy_id,
			'level'=>$level
        ];
		
        return view('dashboard', compact('data'));
    }
}
