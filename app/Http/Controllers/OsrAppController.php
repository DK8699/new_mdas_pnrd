<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use DB;

class OsrAppController extends Controller
{
     public function login(Request $request){
		 
      $username=$request->input('username');
      $password=$request->input('password');
 
      if(!empty($username) && !empty($password)){
	
          $count_user=DB::table('mdas_users')->where('username','=',$username)->count();
		   
          if($count_user==1){
			
              $fetch_user=DB::table('mdas_users')->where('username','=',$username)->first();
			 
              if (Hash::check($password,$fetch_user->password)) {
                  return [

                        'message'=>'success',
                        'response'=>Response::HTTP_OK
                ];
              }else{
                  return [

                      'message'=>'failed',
                      'response'=>Response::HTTP_OK
                  ];
              }
          }else{
              return [

                  'message'=>'failed',
                  'response'=>Response::HTTP_OK
              ];
          }



      }else{
			  return [
				'message'=>'empty',
				'response'=>Response::HTTP_OK
			  ];
		  }
    }
}
