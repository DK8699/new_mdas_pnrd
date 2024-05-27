<?php

namespace App\Http\Controllers\Osr;

use App\CommonModels\District;
use App\ConfigMdas;
use App\Osr\OsrMasterFyYear;
use App\Osr\OsrNonTaxAssetEntry;
use App\Osr\OsrNonTaxAssetShortlist;
use App\Osr\OsrNonTaxBiddingAttachmentUpload;
use App\Osr\OsrNonTaxBiddingBiddersDetail;
use App\Osr\OsrNonTaxBiddingGeneralDetail;
use App\Osr\OsrNonTaxBiddingSettlementDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PDF;

class OsrNonTaxBiddingDownloadController extends Controller
{
    public function comparativeBiddingReport(Request $request, $asset_code, $fy_id){

        $asset_code=base64_decode(base64_decode(base64_decode($asset_code)));
        $fy_id=base64_decode(base64_decode(base64_decode($fy_id)));
		
		
        $data = [];
        $imgUrl=ConfigMdas::allActiveList()->imgUrl;

        $users = Auth::user();
        if ($users->mdas_master_role_id == 2) {//ZP ADMIN
            $level = "ZP";
            $id = $users->zp_id;
        } elseif ($users->mdas_master_role_id == 3) {//AP ADMIN
            $level = "AP";
            $id = $users->ap_id;
        } elseif ($users->mdas_master_role_id == 4) {//GP ADMIN
            $level = "GP";
            $id = $users->gp_id;
        } else {
            return redirect(route('dashboard'));
        }

        $checkIsInShortlist = OsrNonTaxAssetShortlist::isInShortlist($asset_code, $fy_id, $level, $id);

        if (!$checkIsInShortlist) {
            return redirect(route('dashboard'));
        }

        $zpData=District::getZilaByDistrictId($users->district_code);
        $assetData=OsrNonTaxAssetShortlist::getAssetEntryByIdJoinedData($asset_code);
        $osrFyYear= OsrMasterFyYear::getFyYear($fy_id);

        if(!$zpData || !$assetData || !$osrFyYear){
            return redirect()->route('dashboard');
        }

        $generalDetail= OsrNonTaxBiddingGeneralDetail::getEntryByCodeAndFyYr($asset_code, $fy_id);
        if(!$generalDetail){
            return redirect(route('dashboard'));
        }

        $bidderDetail= OsrNonTaxBiddingBiddersDetail::getAllBiddersByGeneralIdArrangeBiddingAmt($generalDetail->id);
        $uploadedDoc= OsrNonTaxBiddingAttachmentUpload::getUploadedAttachments($generalDetail->id);

        $totalBidder= OsrNonTaxBiddingBiddersDetail::totalBiddersCount($generalDetail->id);
        $acceptedBidderData= OsrNonTaxBiddingBiddersDetail::acceptedBidder($generalDetail->id);
        
        if($acceptedBidderData)
        {
            $forfeitedBidderData = OsrNonTaxBiddingBiddersDetail::totalForfeitedBiddersCount($generalDetail->id,$acceptedBidderData->bidding_amt);
        }
        else
        {
            $forfeitedBidderData=[];
            $acceptedBidderData=NULL;
        }
        
        $settlementData= OsrNonTaxBiddingSettlementDetail::getSettlementInfo($generalDetail->id);

        if(count($bidderDetail) < 1 || count($uploadedDoc) < 1 || $totalBidder < 1 || !$acceptedBidderData || !$settlementData){
            return redirect(route('dashboard'));
        }

        $data=[
            "imgUrl"=>$imgUrl,
            "users"=>$users,
            "zpData"=>$zpData,
            "osrFyYear"=>$osrFyYear,
            "assetData"=>$assetData,
            "generalDetail"=>$generalDetail,
            "bidderDetail"=>$bidderDetail,
            "totalBidder"=>$totalBidder,
            "acceptedBidderData"=>$acceptedBidderData,
            "uploadedDoc"=>$uploadedDoc,
            "forfeitedBidderData"=>$forfeitedBidderData,
            "settlementData"=>$settlementData,
        ];

        $pdf = PDF::loadView('Osr.non_tax.template.ComparativeBiddingReport', $data);

        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //================================DOC NAME===============================================
        $docName = "ComparativeBiddingReport";
        //========================================================================================
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

        return $pdf->stream($docName.'.pdf');
    }

    public function detailBiddingReport(Request $request,$asset_code, $fy_id){

        $asset_code=base64_decode(base64_decode(base64_decode($asset_code)));
        $fy_id=base64_decode(base64_decode(base64_decode($fy_id)));

        $data = [];
        $imgUrl=ConfigMdas::allActiveList()->imgUrl;

        $users = Auth::user();
        if ($users->mdas_master_role_id == 2) {//ZP ADMIN
            $level = "ZP";
            $id = $users->zp_id;
        } elseif ($users->mdas_master_role_id == 3) {//AP ADMIN
            $level = "AP";
            $id = $users->ap_id;
        } elseif ($users->mdas_master_role_id == 4) {//GP ADMIN
            $level = "GP";
            $id = $users->gp_id;
        } else {
            return redirect(route('dashboard'));
        }

        $checkIsInShortlist = OsrNonTaxAssetShortlist::isInShortlist($asset_code, $fy_id, $level, $id);

        if (!$checkIsInShortlist) {
            return redirect(route('dashboard'));
        }


        $zpData=District::getZilaByDistrictId($users->district_code);
        if(!$zpData){
            return redirect()->route('dashboard');
        }

        $assetData=OsrNonTaxAssetShortlist::getAssetEntryByIdJoinedData($asset_code);

        $osrFyYear= OsrMasterFyYear::getFyYear($fy_id);

        $generalDetail= OsrNonTaxBiddingGeneralDetail::getEntryByCodeAndFyYr($asset_code, $fy_id);

        if(!$assetData || !$osrFyYear || !$generalDetail){
            return redirect(route('dashboard'));
        }

        $bidderDetail= OsrNonTaxBiddingBiddersDetail::getAllBiddersByGeneralId($generalDetail->id);
        $uploadedDoc= OsrNonTaxBiddingAttachmentUpload::getUploadedAttachments($generalDetail->id);

        $totalBidder= OsrNonTaxBiddingBiddersDetail::totalBiddersCount($generalDetail->id);
        $acceptedBidderData= OsrNonTaxBiddingBiddersDetail::acceptedBidder($generalDetail->id);

        if($acceptedBidderData)
        {
            $forfeitedBidderData = OsrNonTaxBiddingBiddersDetail::totalForfeitedBiddersCount($generalDetail->id,$acceptedBidderData->bidding_amt);
        }
        else
        {
            $forfeitedBidderData=[];
            $acceptedBidderData=NULL;
        }
        
        $settlementData= OsrNonTaxBiddingSettlementDetail::getSettlementInfo($generalDetail->id);

        if(count($bidderDetail) < 1 || count($uploadedDoc) < 1 || $totalBidder < 1 || !$acceptedBidderData || !$settlementData){
            return redirect(route('dashboard'));
        }

        $data=[
            "imgUrl"=>$imgUrl,
            "users"=>$users,
            "zpData"=>$zpData,
            "osrFyYear"=>$osrFyYear,
            "assetData"=>$assetData,
            "generalDetail"=>$generalDetail,
            "bidderDetail"=>$bidderDetail,
            "totalBidder"=>$totalBidder,
            "acceptedBidderData"=>$acceptedBidderData,
            "uploadedDoc"=>$uploadedDoc,
            "forfeitedBidderData"=>$forfeitedBidderData,
            "settlementData"=>$settlementData,
        ];

        $pdf = PDF::loadView('Osr.non_tax.template.DetailBiddingReport', $data);

        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //================================DOC NAME===============================================
        $docName = "DetailBiddingReport";
        //========================================================================================
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

        return $pdf->stream($docName.'.pdf');
    }
}
