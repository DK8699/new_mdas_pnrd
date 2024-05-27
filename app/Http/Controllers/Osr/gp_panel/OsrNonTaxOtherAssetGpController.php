<?php

namespace App\Http\Controllers\Osr\gp_panel;

use App\CommonModels\District;
use App\Osr\OsrMasterFyYear;
use App\CommonModels\Village;
use App\Osr\OsrNonTaxMasterAssetCategory;
use App\Osr\OsrNonTaxOtherAssetEntry;
use App\CommonModels\ZilaParishad;
use App\CommonModels\AnchalikParishad;
use App\CommonModels\GramPanchyat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OsrNonTaxOtherAssetGpController extends Controller
{
    public function gp_other_asset_collection($fy_id){
        
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);
        $users=Auth::user();

        $assetCategory = OsrNonTaxMasterAssetCategory::getAssetCategory();
        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);
        $gpData = GramPanchyat::getGPName($users->gp_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'gpData'=>$gpData,
            'assetCategory'=>$assetCategory,
        ];
             
        return view('Osr.non_tax.other_asset.gp.gp_other_asset_collection',compact('data'));
        
    }
    
    
    public function gp_other_asset_share($fy_id){
        
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);
        $users=Auth::user();

        $assetCategory = OsrNonTaxMasterAssetCategory::getAssetCategory();
        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);
        $gpData = GramPanchyat::getGPName($users->gp_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'gpData'=>$gpData,
            'assetCategory'=>$assetCategory,
        ];
        return view('Osr.non_tax.other_asset.gp.gp_other_asset_share',compact('data'));
        
    }
}
