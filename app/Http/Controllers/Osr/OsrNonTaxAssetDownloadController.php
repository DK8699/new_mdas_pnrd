<?php

namespace App\Http\Controllers\Osr;

use App\CommonModels\District;
use App\CommonModels\ZilaParishad;
use App\ConfigMdas;
use App\Osr\OsrMasterFyYear;
use App\Osr\OsrNonTaxAssetEntry;
use App\Osr\OsrNonTaxAssetShortlist;
use App\Osr\OsrMasterNonTaxBranch;
use App\Osr\OsrNonTaxBiddingAttachmentUpload;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;
use Crypt;
use App;

class OsrNonTaxAssetDownloadController extends Controller
{
    public function assetReport(Request $request, $fy_id,$z_id){

        
        $fy_id=Crypt::decrypt($fy_id);
        $z_id=Crypt::decrypt($z_id);
        
        $data = [];
        $imgUrl=ConfigMdas::allActiveList()->imgUrl;
        $users=Auth::user();
        $branch = OsrMasterNonTaxBranch::all();
        $zpData=District::getZilaByDistrictId($users->district_code);
        $osrFyYear= OsrMasterFyYear::getFyDataId($fy_id);
        
        $tot_asset = OsrNonTaxAssetEntry::ZilaWiseAssetCount($osrFyYear->fy_to,$users->zp_id);
        
        $tot_asset_short = OsrNonTaxAssetShortlist::where([
            ['zp_id','=',$users->zp_id],
            ['osr_master_fy_year_id','=',$fy_id],
        ])->count();
        
        $level_wise_short = OsrNonTaxAssetShortlist::levelWiseShortlistedCount($fy_id,$users->zp_id);
        
        //echo json_encode($level_wise_short);
        //ZP Assets
        $assetShortlistListUnderZP = OsrNonTaxAssetShortlist::ZPshortlistedList($fy_id,$users->zp_id);
        
        //AP Assets
        $assetShortlistListUnderAP = OsrNonTaxAssetShortlist::APshortlistedList($fy_id,$users->zp_id);
        
        //GP Assets
        $assetShortlistListUnderGP = OsrNonTaxAssetShortlist::GPshortlistedList($fy_id,$users->zp_id);
        
        //GP Assets
        $assetShortlistListUnderNA = OsrNonTaxAssetShortlist::NAshortlistedList($fy_id,$users->zp_id);
       
       $date = Carbon::now()->format('d M Y');
        
        $data=[
           'branch'=>$branch,
           'assetShortlistListUnderZP'=>$assetShortlistListUnderZP,
           'assetShortlistListUnderAP'=>$assetShortlistListUnderAP,
           'assetShortlistListUnderGP'=>$assetShortlistListUnderGP,
           'assetShortlistListUnderNA'=>$assetShortlistListUnderNA,
           'zpData'=>$zpData,
           'osrFyYear'=>$osrFyYear,
           'tot_asset'=>$tot_asset,
           'tot_asset_short'=>$tot_asset_short,
           'level_wise_short'=>$level_wise_short,
           'date'=>$date,
        ];


        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //================================DOC NAME===============================================
        $docName = "Asset_Report";
        //========================================================================================
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

        
        $pdf = PDF::loadView('Osr.non_tax.template.assetShortlistReport', $data);
		
        return $pdf->stream($docName.'.pdf');
        
        //$pdf->loadHTML($pdf);
            
        //return $pdf->download($docName.'.pdf');
    }

}
