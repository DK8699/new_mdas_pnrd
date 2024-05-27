<?php

namespace App\Http\Controllers\Common;

use App\CommonModels\Village;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommonController extends Controller
{
    public function getVillagesByGP(Request $request){
         $returnData['msgType']=false;
         $returnData['data']=[];
         $returnData['msg']="Sorry! Something went wrong";

         $gp_id=$request->input('gp_id');

         if(!$gp_id){
             return $returnData;
         }

         $results= Village::getVillagesByGP($gp_id);

         $returnData['msgType']=true;
         $returnData['data']=$results;
         $returnData['msg']="Success";
         return $returnData;
    }
}
