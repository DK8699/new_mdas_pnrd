<?php

namespace App\Http\Controllers\Admin\Osr;

use App\Osr\OsrNonTaxBidderEntry;
use App\Osr\OsrNonTaxBiddingAttachmentUpload;
use App\Osr\OsrNonTaxBiddingSettlementDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Crypt;
use App\Osr\OsrMasterFyYear;
use App\Osr\OsrMasterNonTaxBranch;
// use App\app\OsrNonTaxAssetEntry;
use App\Osr\OsrMasterBidderRemark;
use App\Osr\OsrNonTaxBiddingGeneralDetail;
use App\Osr\OsrNonTaxBiddingBiddersDetail;
use App\Osr\OsrNonTaxBidderAttachmentUpload;
use App\Osr\OsrNonTaxAssetFinalRecord;
use App\Osr\OsrNonTaxBiddingAttachment;
use App\CommonModels\ZilaParishad;

use App\CommonModels\Village;
use App\CommonModels\GramPanchyat;
use App\CommonModels\AnchalikParishad;
use App\CommonModels\District;
use App\Pris\PriMemberMainRecord;
use App\ConfigMdas;
use App\Osr\OsrNonTaxAssetEntry;
use App\Osr\OsrNonTaxAssetShortlist;
use App\Master\MasterAnnualIncome;
use App\Master\MasterBloodGroup;
use App\Master\MasterCaste;
use App\Master\MasterGender;
use App\Master\MasterHighestQualification;
use App\Master\MasterMaritalStatus;
use App\Master\MasterPriPoliticalParty;
use App\Master\MasterPrisReserveSeat;
use App\Master\MasterReligion;
use App\Master\MasterWard;
use App\Pris\PriMasterDesignation;
use App\Pris\PriMemberTermHistory;
use App\survey\six_finance\SixFinanceGpSelectionList;
use DB;

class AdminOsrNonTaxAssetsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin_mdas']);
    }
    public function non_tax_assets(Request $request) {
        $fy = OsrMasterFyYear::all();
        $non_tax_branch = OsrMasterNonTaxBranch::all();
        $zilas = ZilaParishad::all();

        return view('admin.Osr.non_tax_assets', compact('fy', 'non_tax_branch', 'zilas'));
    }

    public function get_non_tax_assets_shortlisted(Request $request) {
        $this->middleware('auth');
        if(Auth::check() && $request->ajax())
        {
          $filter_financial_year = $request->input('filter_financial_year');
          $filter_branch = $request->input('filter_branch');
          $filter_zila_id = $request->input('filter_zila_id');
          $filter_anchalik_id = $request->input('filter_anchalik_id');
          $filter_gram_panchyat_id = $request->input('filter_gram_panchyat_id');

          if( $filter_anchalik_id == "" && $filter_gram_panchyat_id == "" )
          {
            $result = DB::select('Select * from osr_non_tax_asset_entries a, osr_non_tax_asset_shortlists b where a.asset_code=b.asset_code
            and a.osr_asset_branch_id = ? and b.osr_master_fy_year_id = ? and a.zila_id = ? order by b.created_at desc',
            [$filter_branch, $filter_financial_year, $filter_zila_id]);
          }
          else if( $filter_anchalik_id != "" && $filter_gram_panchyat_id == "" )
          {
            $result = DB::select('Select * from osr_non_tax_asset_entries a, osr_non_tax_asset_shortlists b where a.asset_code=b.asset_code
            and a.osr_asset_branch_id = ? and b.osr_master_fy_year_id = ? and a.zila_id = ? and a.anchalik_id = ? order by b.created_at desc',
            [$filter_branch, $filter_financial_year, $filter_zila_id, $filter_anchalik_id]);
          }
          else {
            $result = DB::select('Select * from osr_non_tax_asset_entries a, osr_non_tax_asset_shortlists b where a.asset_code=b.asset_code
            and a.osr_asset_branch_id = ? and b.osr_master_fy_year_id = ? and a.zila_id = ? and a.anchalik_id = ? and a.gram_panchayat_id=? order by b.created_at desc',
            [$filter_branch, $filter_financial_year, $filter_zila_id, $filter_anchalik_id, $filter_gram_panchyat_id]);
          }

          for($i=0;$i<sizeof($result);$i++)
          {
            $result[0]->id = Crypt::encrypt($result[0]->id);
          }

          return json_encode($result);
        }
        else
        {
          Auth::logout();
          return redirect('/');
        }
    }
    
	public function show_non_tax_assets_shortlisted(Request $request) {
      $this->middleware('auth');
      if(Auth::check())
      {
        $id = Crypt::decrypt($request->route('id'));
        $result = DB::select('Select * from osr_non_tax_asset_entries a, osr_non_tax_asset_shortlists b where a.asset_code=b.asset_code
        and b.id=? order by b.created_at desc', [$id]);

        $fy_years = DB::select('Select * from osr_master_fy_years where id=?', [$result[0]->osr_master_fy_year_id]);
        $branches = DB::select('Select * from osr_master_non_tax_branches where id=?', [$result[0]->osr_asset_branch_id]);
        $zila = DB::select('Select * from zila_parishads where id=?', [$result[0]->zila_id]);
        $anchalik = DB::select('Select * from anchalik_parishads where id=?', [$result[0]->anchalik_id]);
        $gram = DB::select('Select * from gram_panchyats where gram_panchyat_id=?', [$result[0]->gram_panchayat_id]);
        $villages = DB::select('Select * from villages where id=?', [$result[0]->village_id]);

        $zpData=District::getZilaByDistrictId($zila[0]->district_id);
        // $genderAll = MasterGender::all();
        // if(!$zpData){
        //     return redirect()->route('osr.dashboard');
        // }

        $asset_id=$result[0]->id;
        $osr_fy_id=$fy_years[0]->id;
        $branchData=OsrMasterNonTaxBranch::getBranchByAssetId($asset_id);
        $assetData=OsrNonTaxAssetEntry::getAssetByAssetId($asset_id);
        $osrFyYear=OsrMasterFyYear::getFyYear($osr_fy_id);

        //----------ZILA MISMATCH ----------------------------------
        $checkMisMatch=OsrNonTaxAssetEntry::checkZilaMismatch($zpData->id, $asset_id);

        // if(!$assetData || !$osrFyYear || !$checkMisMatch['msgType']){
        //     return redirect(route('osr.dashboard'));
        // }
        $bidderRemarks=OsrMasterBidderRemark::getActiveList();
        $imgUrl=ConfigMdas::allActiveList()->imgUrl;
        $generalDetail= OsrNonTaxBiddingGeneralDetail::getEntryByIdAndFyYr($asset_id, $osr_fy_id);
        $activeDocs=OsrNonTaxBiddingAttachment::getAllActiveDoc();

        if($generalDetail){
          $bidderDetail= OsrNonTaxBiddingBiddersDetail::getAllBiddersByGeneralId($generalDetail->id);
          $uploadedDoc= OsrNonTaxBiddingAttachmentUpload::getUploadedAttachments($generalDetail->id);
          $acceptedBidderData= OsrNonTaxBiddingBiddersDetail::acceptedBidder($generalDetail->id);
          $totalBidder= OsrNonTaxBiddingBiddersDetail::totalBiddersCount($generalDetail->id);
          $totalWithdrawnBidder = OsrNonTaxBiddingBiddersDetail::totalWithdrawnBiddersCount($generalDetail->id);
          
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
      }
      else{
          $totalWithdrawnBidder=NULL;
          $forfeitedBidderData=[];
          $bidderDetail=[];
          $uploadedDoc=NULL;
          $totalBidder=NULL;
          $acceptedBidderData=NULL;
          $settlementData=NULL;
      }
      
        return view('Admin.Osr.show_non_tax_assets_shortlisted', compact('result', 'fy_years', 'branches', 'zila', 'anchalik', 'gram', 'villages','branchData', 'assetData', 'osrFyYear', 'generalDetail', 'bidderDetail', 'bidderRemarks', 'totalBidder','forfeitedBidderData','totalWithdrawnBidder', 'acceptedBidderData', 'activeDocs', 'uploadedDoc', 'settlementData', 'imgUrl'));
      }
      else
      {
        Auth::logout();
        return redirect('/');
      }
    }

    
    
   //AssetsSettlement   
    
    public function assetInformation ($id,$fy_id,$branch_id,$asset_id,$ap_id=NULL,$gp_id=NULL) {
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;
        $ap_name=NULL;
        $gp_name=NULL;

        if($ap_id==NULL){
          $query=[
            'zp_id'=>$id,
            'osr_master_non_tax_branch_id'=>$branch_id,
            'level'=>'ZP',
            'osr_non_tax_asset_shortlists.id'=>$asset_id
          ];
        }else if($gp_id==NULL){
          $ap_data=AnchalikParishad::getAPName($ap_id);
          $ap_name=$ap_data->anchalik_parishad_name;
          $query=[
            'zp_id'=>$id,
            'ap_id'=>$ap_id,
            'osr_master_non_tax_branch_id'=>$branch_id,
            'level'=>'AP',
            'osr_non_tax_asset_shortlists.id'=>$asset_id
          ];
        }else{
          $ap_data=AnchalikParishad::getAPName($ap_id);
          $ap_name=$ap_data->anchalik_parishad_name;
          $gp_data=GramPanchyat::getGPName($gp_id);
          $gp_name=$gp_data->gram_panchayat_name;  
          $query=[
            'zp_id'=>$id,
            'ap_id'=>$ap_id,
            'gp_id'=>$gp_id,
            'osr_master_non_tax_branch_id'=>$branch_id,
            'level'=>'GP',
            'osr_non_tax_asset_shortlists.id'=>$asset_id
          ];
        }
       
        // asset Details
        $assetData=OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries','a_entries.asset_code','=','osr_non_tax_asset_shortlists.asset_code')
            ->where($query)
            ->select('a_entries.asset_name','a_entries.b_desc','osr_non_tax_asset_shortlists.*')->first();
        
        if(!$assetData){
            return redirect()->route('admin.dashboard');
        }

		$zpData=ZilaParishad::where('id',$assetData->zp_id)->first();
		$apData=AnchalikParishad::where('id',$assetData->ap_id)->first();
		$gpData=GramPanchyat::where('gram_panchyat_id',$assetData->gp_id)->first();
		$villData=Village::where('id',$assetData->village_id)->first();
		$branchData =OsrMasterNonTaxBranch::getBranchById($assetData->osr_master_non_tax_branch_id);

		$asset_zp_name =$zpData->zila_parishad_name;
		$asset_ap_name =$apData->anchalik_parishad_name;
		$asset_gp_name =$gpData->gram_panchayat_name;
		//$asset_vill_name =$villData->village_name;
        $asset_code =$assetData->asset_code;

        // bidding details

        $generalDetail=NULL;
        $acceptedBidderData=NULL;
        $settlementData=NULL;
        $finalRecordData = NULL;
        $uploadedDoc=[];

        $generalDetail = OsrNonTaxBiddingGeneralDetail::getEntryByCodeAndFyYr($asset_code, $fy_id);

        $finalRecordData = OsrNonTaxAssetFinalRecord::getFinalRecord($asset_code, $fy_id);

        if ($generalDetail && $finalRecordData && $finalRecordData->bidding_status==1) {

            $settlementData = OsrNonTaxBiddingSettlementDetail::getSettlementInfo($generalDetail->id);
            $uploadedDoc = OsrNonTaxBiddingAttachmentUpload::getOnlyUploadedAttachments($generalDetail->id);

            $bidderDetail = OsrNonTaxBiddingBiddersDetail::getAllBiddersByGeneralId($generalDetail->id);

            
            foreach($bidderDetail AS $bidder){
                $uploadAttachCount[$bidder->id] = OsrNonTaxBidderAttachmentUpload::attachmentUploadCount($bidder->id);
            }
            
            $acceptedBidderData = OsrNonTaxBiddingBiddersDetail::acceptedBidder($generalDetail->id);

            $totalBidder = OsrNonTaxBiddingBiddersDetail::totalBiddersCount($generalDetail->id);
            $totalWithdrawnBidder = OsrNonTaxBiddingBiddersDetail::totalWithdrawnBiddersCount($generalDetail->id);

            if ($acceptedBidderData) {
                $forfeitedBidderData = OsrNonTaxBiddingBiddersDetail::totalForfeitedBiddersCount($generalDetail->id, $acceptedBidderData->bidding_amt);
            }

        }

        $imgUrl =$imgUrl=ConfigMdas::allActiveList()->imgUrl;

        $data=[
            "assetData"=>$assetData,
            "branchData"=>$branchData,
            "asset_zp_name"=>$asset_zp_name,
            "asset_ap_name"=>$asset_ap_name,
            "asset_gp_name"=>$asset_gp_name,
            /*"asset_vill_name"=>$asset_vill_name,*/

            "generalDetail"=>$generalDetail,
            "finalRecordData"=>$finalRecordData,
            "settlementData"=>$settlementData,
            "acceptedBidderData"=>$acceptedBidderData,
            "uploadedDoc"=>$uploadedDoc,
            "imgUrl"=>$imgUrl,
        ];

      return view('admin.Osr.AssetsSettlement.assetInformation',compact('data', 'id','fy_id','district_name','fy_years','branch_id','ap_id','gp_id','imgUrl'));
    }
    
   //End AssetsSettlement  


  //Defaulters
    
    //public function defaulterAssetInformation($id,$fy_id,$branch_id,$asset_id,$ap_id=NULL,$gp_id=NULL) {
     //   $district_data=ZilaParishad::getZPName($id);
     //   $district_name=$district_data->zila_parishad_name;
     //   $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;
     // return view('admin.Osr.Defaulters.defaulterAssetInformation',compact('id','fy_id','district_name','fy_years','branch_id','ap_id','gp_id'));
    //}
	
	public function defaulterAssetInformation ($id,$fy_id,$branch_id,$asset_id,$ap_id=NULL,$gp_id=NULL) {
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;
        $ap_name=NULL;
        $gp_name=NULL;
        if($ap_id==NULL){
          $query=[
            'zila_id'=>$id,
            'osr_asset_branch_id'=>$branch_id,
            'asset_under'=>'ZP',
            'id'=>$asset_id
          ];
        }else if($gp_id==NULL){
          $ap_data=AnchalikParishad::getAPName($ap_id);
          $ap_name=$ap_data->anchalik_parishad_name;
          $query=[
            'zila_id'=>$id,
            'anchalik_id'=>$ap_id,
            'osr_asset_branch_id'=>$branch_id,
            'asset_under'=>'AP','id'=>$asset_id
          ];
        }else{
          $ap_data=AnchalikParishad::getAPName($ap_id);
          $ap_name=$ap_data->anchalik_parishad_name;
          $gp_data=GramPanchyat::getGPName($gp_id);
          $gp_name=$gp_data->gram_panchayat_name;  
          $query=[
            'zila_id'=>$id,
            'anchalik_id'=>$ap_id,
            'gram_panchayat_id'=>$gp_id,
            'osr_asset_branch_id'=>$branch_id,
            'asset_under'=>'GP','id'=>$asset_id
          ];
        }
        $bidding_settlement_details = [];
        $bidderInfo = [];
        $generalDetails = [];
        $agreement = [];

        $nonTaxAssets=OsrNonTaxAssetEntry::where($query)->first();
        $select_asset_code =$nonTaxAssets->asset_code;
        $generalDetails=OsrNonTaxBiddingGeneralDetail::where('asset_code',$select_asset_code)->first();
        if($generalDetails!=NULL) {
        $select_general_id = $generalDetails->id;
        $bidding_settlement_details = OsrNonTaxBiddingSettlementDetail::where('osr_non_tax_bidding_general_detail_id',$select_general_id)->first();
        $bidder_id = $bidding_settlement_details->osr_non_tax_bidder_entry_id;
        $bidderInfo=OsrNonTaxBidderEntry::where('id',$bidder_id)->first();
        
        $agreement=OsrNonTaxBiddingAttachmentUpload::where([
          ['osr_non_tax_bidding_attachment_id',4],
          ['osr_non_tax_bidding_general_detail_id',$select_general_id],
        ])->first();

        }
        
        $imgUrl =$imgUrl=ConfigMdas::allActiveList()->imgUrl;

      return view('admin.Osr.Defaulters.defaulterAssetInformation',compact('id','fy_id','district_name','fy_years','branch_id','ap_id','gp_id','nonTaxAssets','imgUrl','generalDetails','bidding_settlement_details','bidderInfo','agreement','ap_name','gp_name'));
    }
  //endDefaulters
    
    
  //Revenue
    
    //public function revenueAssetInformation($id,$fy_id,$branch_id,$asset_id,$ap_id=NULL,$gp_id=NULL) {
    //    $district_data=ZilaParishad::getZPName($id);
    //    $district_name=$district_data->zila_parishad_name;
    //    $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;
    //  return view('admin.Osr.Revenue.revenueAssetInformation',compact('id','fy_id','district_name','fy_years','branch_id','ap_id','gp_id'));
    //}
	
	public function revenueAssetInformation ($id,$fy_id,$branch_id,$asset_id,$ap_id=NULL,$gp_id=NULL) {
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;
        $ap_name=NULL;
        $gp_name=NULL;
        if($ap_id==NULL){
          $query=[
            'zila_id'=>$id,
            'osr_asset_branch_id'=>$branch_id,
            'asset_under'=>'ZP',
            'id'=>$asset_id
          ];
        }else if($gp_id==NULL){
          $ap_data=AnchalikParishad::getAPName($ap_id);
          $ap_name=$ap_data->anchalik_parishad_name;
          $query=[
            'zila_id'=>$id,
            'anchalik_id'=>$ap_id,
            'osr_asset_branch_id'=>$branch_id,
            'asset_under'=>'AP','id'=>$asset_id
          ];
        }else{
          $ap_data=AnchalikParishad::getAPName($ap_id);
          $ap_name=$ap_data->anchalik_parishad_name;
          $gp_data=GramPanchyat::getGPName($gp_id);
          $gp_name=$gp_data->gram_panchayat_name;  
          $query=[
            'zila_id'=>$id,
            'anchalik_id'=>$ap_id,
            'gram_panchayat_id'=>$gp_id,
            'osr_asset_branch_id'=>$branch_id,
            'asset_under'=>'GP','id'=>$asset_id
          ];
        }
        $bidding_settlement_details = [];
        $bidderInfo = [];
        $generalDetails = [];
        $agreement = [];

        $nonTaxAssets=OsrNonTaxAssetEntry::where($query)->first();
        $select_asset_code =$nonTaxAssets->asset_code;
        $generalDetails=OsrNonTaxBiddingGeneralDetail::where('asset_code',$select_asset_code)->first();
        if($generalDetails!=NULL) {
        $select_general_id = $generalDetails->id;
        $bidding_settlement_details = OsrNonTaxBiddingSettlementDetail::where('osr_non_tax_bidding_general_detail_id',$select_general_id)->first();
        $bidder_id = $bidding_settlement_details->osr_non_tax_bidder_entry_id;
        $bidderInfo=OsrNonTaxBidderEntry::where('id',$bidder_id)->first();
        
        $agreement=OsrNonTaxBiddingAttachmentUpload::where([
          ['osr_non_tax_bidding_attachment_id',4],
          ['osr_non_tax_bidding_general_detail_id',$select_general_id],
        ])->first();

        }
        
        $imgUrl =$imgUrl=ConfigMdas::allActiveList()->imgUrl;

      return view('admin.Osr.Revenue.revenueAssetInformation',compact('id','fy_id','district_name','fy_years','branch_id','ap_id','gp_id','nonTaxAssets','imgUrl','generalDetails','bidding_settlement_details','bidderInfo','agreement','ap_name','gp_name'));
    }
  //End Revenue 
    
    
 //Revenue
    
    //public function shareAssetInformation($id,$fy_id,$branch_id,$asset_id,$ap_id=NULL,$gp_id=NULL) {
     //   $district_data=ZilaParishad::getZPName($id);
     //   $district_name=$district_data->zila_parishad_name;
     //   $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;
     // return view('admin.Osr.Share.shareAssetInformation',compact('id','fy_id','district_name','fy_years','branch_id','ap_id','gp_id'));
   // }
	
	public function shareAssetInformation ($id,$fy_id,$branch_id,$asset_id,$ap_id=NULL,$gp_id=NULL) {
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;
        $ap_name=NULL;
        $gp_name=NULL;
        if($ap_id==NULL){
          $query=[
            'zila_id'=>$id,
            'osr_asset_branch_id'=>$branch_id,
            'asset_under'=>'ZP',
            'id'=>$asset_id
          ];
        }else if($gp_id==NULL){
          $ap_data=AnchalikParishad::getAPName($ap_id);
          $ap_name=$ap_data->anchalik_parishad_name;
          $query=[
            'zila_id'=>$id,
            'anchalik_id'=>$ap_id,
            'osr_asset_branch_id'=>$branch_id,
            'asset_under'=>'AP','id'=>$asset_id
          ];
        }else{
          $ap_data=AnchalikParishad::getAPName($ap_id);
          $ap_name=$ap_data->anchalik_parishad_name;
          $gp_data=GramPanchyat::getGPName($gp_id);
          $gp_name=$gp_data->gram_panchayat_name;  
          $query=[
            'zila_id'=>$id,
            'anchalik_id'=>$ap_id,
            'gram_panchayat_id'=>$gp_id,
            'osr_asset_branch_id'=>$branch_id,
            'asset_under'=>'GP','id'=>$asset_id
          ];
        }
        $bidding_settlement_details = [];
        $bidderInfo = [];
        $generalDetails = [];
        $agreement = [];

        $nonTaxAssets=OsrNonTaxAssetEntry::where($query)->first();
        $select_asset_code =$nonTaxAssets->asset_code;
        $generalDetails=OsrNonTaxBiddingGeneralDetail::where('asset_code',$select_asset_code)->first();
        if($generalDetails!=NULL) {
        $select_general_id = $generalDetails->id;
        $bidding_settlement_details = OsrNonTaxBiddingSettlementDetail::where('osr_non_tax_bidding_general_detail_id',$select_general_id)->first();
        $bidder_id = $bidding_settlement_details->osr_non_tax_bidder_entry_id;
        $bidderInfo=OsrNonTaxBidderEntry::where('id',$bidder_id)->first();
        
        $agreement=OsrNonTaxBiddingAttachmentUpload::where([
          ['osr_non_tax_bidding_attachment_id',4],
          ['osr_non_tax_bidding_general_detail_id',$select_general_id],
        ])->first();

        }
        
        $imgUrl =$imgUrl=ConfigMdas::allActiveList()->imgUrl;

      return view('admin.Osr.Share.shareAssetInformation',compact('id','fy_id','district_name','fy_years','branch_id','ap_id','gp_id','nonTaxAssets','imgUrl','generalDetails','bidding_settlement_details','bidderInfo','agreement','ap_name','gp_name'));
    }
  //End Revenue 
    
    
}
