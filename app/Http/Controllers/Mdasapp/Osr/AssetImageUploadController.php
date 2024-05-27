<?php

namespace App\Http\Controllers\Mdasapp\Osr;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssetImageUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['assetGeoTag']]);
    }

    public function assetGeoTag(Request $request){
        $returnData['msgType']=false;
        $returnData['data']=[];

        $imag_path=NULL;
		
        try{
			$image = base64_decode($request->input('img'));

			$safeName = 'osr/asset/geotag/'.md5(uniqid()).'.'.'jpg';
		
			if(Storage::disk('public')->put($safeName, $image)){
				$returnData['msgType']=true;
				$returnData['data']=["img_path"=> "http://pnrdassam.org/SPIRDMDAS/storage/app/public/".$safeName];
				$returnData['msg']= "Successfully done the task.";
			}
			
        }catch (\Exception $e){
            $returnData['msg']= "Oops! Server exception.".$e->getMessage();
            return response()->json($returnData);
        }

        
        return response()->json($returnData);
    }
}
