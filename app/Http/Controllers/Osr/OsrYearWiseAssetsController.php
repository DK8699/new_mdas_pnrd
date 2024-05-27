<?php

namespace App\Http\Controllers\Osr;

use App\CommonModels\ZilaParishad;
use App\Osr\OsrMasterFyYear;
use App\Osr\OsrMasterNonTaxBranch;
use App\Osr\OsrNonTaxAssetEntry;
use App\Osr\OsrNonTaxAssetShortlist;
use App\Osr\OsrNonTaxAssetFinalRecord;
use App\Osr\OsrNonTaxAssetConfirmation;
use App\Osr\OsrNonTaxSignedAssetReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\ConfigMdas;
use DB;
use Validator;


class OsrYearWiseAssetsController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'user_mdas']);
    }

    public function index(Request $request){

        $cats=OsrMasterNonTaxBranch::get_branches();
        $max_fy_id=OsrMasterFyYear::getMaxFyYear();
        $fyList=OsrMasterFyYear::getAllYears();

        $users=Auth::user();
        $totData=[];

        if($users->mdas_master_role_id<>2){

        }

        $zp_asset=OsrNonTaxAssetShortlist::select(DB::raw('count(*) AS count, osr_master_fy_year_id AS fy_id, zp_id, osr_master_non_tax_branch_id AS cat_id'))
            ->where([
                ["zp_id", "=", $users->zp_id],
                ["level", "=", "ZP"],
            ])->groupBy('osr_master_fy_year_id', 'zp_id', 'osr_master_non_tax_branch_id')
            ->get();

        $ap_asset=OsrNonTaxAssetShortlist::select(DB::raw('count(*) AS count, osr_master_fy_year_id AS fy_id, zp_id, osr_master_non_tax_branch_id AS cat_id'))
            ->where([
                ["zp_id", "=", $users->zp_id],
                ["level", "=", "AP"],
            ])->groupBy('osr_master_fy_year_id', 'zp_id', 'osr_master_non_tax_branch_id')
            ->get();

        $gp_asset=OsrNonTaxAssetShortlist::select(DB::raw('count(*) AS count, osr_master_fy_year_id AS fy_id, zp_id, osr_master_non_tax_branch_id AS cat_id'))
            ->where([
                ["zp_id", "=", $users->zp_id],
                ["level", "=", "GP"],
            ])->groupBy('osr_master_fy_year_id', 'zp_id', 'osr_master_non_tax_branch_id')
            ->get();

        $notselected=OsrNonTaxAssetShortlist::select(DB::raw('count(*) AS count, osr_master_fy_year_id AS fy_id, zp_id, osr_master_non_tax_branch_id AS cat_id'))
            ->where([
                ["zp_id", "=", $users->zp_id],
                ["level", "=", "NA"],
            ])->groupBy('osr_master_fy_year_id', 'zp_id', 'osr_master_non_tax_branch_id')
            ->get();
			
			

        foreach ($fyList AS $fyData){
            $fyWiseTotalAsset=OsrNonTaxAssetEntry::select(DB::raw('count(*) AS count, osr_asset_branch_id AS cat_id'))
                ->where([
                    ["zila_id", "=", $users->zp_id],
                    ['asset_listing_date','<',$fyData->fy_to]
                ])->groupBy('osr_asset_branch_id')
                ->get();

            $totData[$fyData->id]=$fyWiseTotalAsset;
        }
	
        $data=[
            'fy_id'=>$max_fy_id,
            'fyList'=>$fyList,
            'cats'=>$cats,
            'zp_asset'=>$zp_asset,
            'ap_asset'=>$ap_asset,
            'gp_asset'=>$gp_asset,
            'notselected'=>$notselected,
            'totData'=>$totData,
        ];

        return view('Osr.non_tax.asset.year_wise_assets', compact('data'));
    }

    public function year_wise_asset_shortlist(Request $request, $fy_id, $cat_id){
        $users=Auth::user();
        $assetShortList=[];
        $levelCount=[];
        $assignedAssets=0;

        $fy_id=decrypt($fy_id);
        $cat_id=decrypt($cat_id);

        $cats=OsrMasterNonTaxBranch::get_branches();
        $catData=OsrMasterNonTaxBranch::getBranchById($cat_id);
        $max_fy_id=OsrMasterFyYear::getMaxFyYear();
        $fyList=OsrMasterFyYear::getAllYears();
        $fyData=OsrMasterFyYear::getFyYear($fy_id);

        $zpData=ZilaParishad::getZPName($users->zp_id);

        $assetList= OsrNonTaxAssetEntry::join('zila_parishads AS z', 'osr_non_tax_asset_entries.zila_id', '=', 'z.id')
            ->join('anchalik_parishads AS a', 'osr_non_tax_asset_entries.anchalik_id', '=', 'a.id')
            ->join('gram_panchyats AS g', 'osr_non_tax_asset_entries.gram_panchayat_id', '=', 'g.gram_panchyat_id')
            ->where([
                ["osr_non_tax_asset_entries.zila_id", "=", $users->zp_id],
                ["osr_non_tax_asset_entries.osr_asset_branch_id", "=", $cat_id],
                ['osr_non_tax_asset_entries.asset_listing_date','<',$fyData->fy_to]
            ])->select('osr_non_tax_asset_entries.*', 'z.zila_parishad_name', 'a.anchalik_parishad_name', 'g.gram_panchayat_name')
            ->orderBy('a.anchalik_parishad_name')->get();


        $shortList= OsrNonTaxAssetShortlist::where([
            ['zp_id', '=', $users->zp_id],
            ["osr_master_non_tax_branch_id", "=", $cat_id],
            ["osr_master_fy_year_id", "=", $fy_id],
        ])->select('asset_code', 'level', 'reason', "created_at")->get();

        foreach ($shortList AS $li){
            $assetShortList[$li->asset_code]=["asset_code"=>$li->asset_code, "level"=>$li->level, "reason"=>$li->reason, "created_at"=>$li->created_at];
        }

        //------------------ COUNT SECTION -----------------------------------------------------------------------------

        $totalAssets= OsrNonTaxAssetEntry::join('zila_parishads AS z', 'osr_non_tax_asset_entries.zila_id', '=', 'z.id')
            ->join('anchalik_parishads AS a', 'osr_non_tax_asset_entries.anchalik_id', '=', 'a.id')
            ->join('gram_panchyats AS g', 'osr_non_tax_asset_entries.gram_panchayat_id', '=', 'g.gram_panchyat_id')
            ->where([
                ["osr_non_tax_asset_entries.zila_id", "=", $users->zp_id],
                ["osr_non_tax_asset_entries.osr_asset_branch_id", "=", $cat_id],
                ['osr_non_tax_asset_entries.asset_listing_date','<',$fyData->fy_to]
            ])->count();

        $levelCountList= OsrNonTaxAssetShortlist::where([
            ['zp_id', '=', $users->zp_id],
            ["osr_master_non_tax_branch_id", "=", $cat_id],
            ["osr_master_fy_year_id", "=", $fy_id],
        ])->select(DB::raw('count(*) AS total, level'))->groupBy('level')->get();

        foreach ($levelCountList AS $li){
            $levelCount[$li->level]=$li->total;
            $assignedAssets= $assignedAssets+$li->total;
        }

        $pendingCount= $totalAssets - $assignedAssets;

        //------------------ COUNT SECTION ENDED -----------------------------------------------------------------------

        $data=[
            'fy_id'=>$max_fy_id,
            'fyList'=>$fyList,
            'cats'=>$cats,
            'catData'=>$catData,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'assetList'=>$assetList,
            'assetShortList'=>$assetShortList,
            'levelCount'=>$levelCount,
            'pendingCount'=>$pendingCount,
        ];

        return view('Osr.non_tax.asset.year_wise_asset_shortlist', compact('data'));
    }

    public function year_wise_asset_shortlist_save(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $users=Auth::user();

        $id= decrypt($request->input('id'));
        $cat_id= decrypt($request->input('cat_id'));
        $fy_id= decrypt($request->input('fy_id'));
        $assetCode= decrypt($request->input('assetCode'));
        $zp_id= decrypt($request->input('zp_id'));
        $ap_id= decrypt($request->input('ap_id'));
        $gp_id= decrypt($request->input('gp_id'));
        $level= $request->input('level_'.$id);

        $messages = [
            'level_'.$id.'.required' => 'This is required.',
            'level_'.$id.'.in' => 'Invalid data.',
            'reason_'.$id.'.required_if' => 'This is required.',
            'reason_'.$id.'.string' => 'Must be a string.',
            'reason_'.$id.'.max' => 'Maximum 250 characters are allowed.',
        ];

        $validatorArray = [
            'level_'.$id => 'required|in:ZP,AP,GP,NA',
            'reason_'.$id => 'required_if:level_'.$id.',NA|string|max:250|nullable',
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);

        }

        if($level=="NA"){
            $reason= $request->input('reason_'.$id);
            $status=1;
        }else{
            $reason= NULL;
            $status=0;
        }

        $assetData= OsrNonTaxAssetEntry::getAssetByAssetCode($assetCode);

        if(!$assetData){
            $returnData['msg'] = "Asset not found";
            return response()->json($returnData);
        }

        $sortListData= OsrNonTaxAssetShortlist::where([
            ['asset_code', '=', $assetCode],
            ['osr_master_fy_year_id', '=', $fy_id],
        ])->first();

        if($sortListData){
            $returnData['msg'] = "Asset already shortlisted";
            return response()->json($returnData);
        }

        $newEntry= new OsrNonTaxAssetShortlist();
        $newEntry->osr_master_non_tax_branch_id= $cat_id;
        $newEntry->asset_code= $assetCode;
        $newEntry->osr_master_fy_year_id= $fy_id;
        $newEntry->level= $level;
        $newEntry->zp_id= $zp_id;
        $newEntry->ap_id= $ap_id;
        $newEntry->gp_id= $gp_id;
        $newEntry->reason= $reason;
        $newEntry->created_by= $users->username;

        if(!$newEntry->save()){
            $returnData['msg'] = "Asset already shortlisted";
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = [];
        $returnData['msg'] = "Successfully shortlisted the asset";
        return response()->json($returnData);
    }
	
	 public function asset_attachment_upload(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $attachment_path = NULL;

        $fy_id = $request->input('fy_id');

        $users = Auth::user();
       
        /*$checkIsInShortlist = OsrNonTaxAssetShortlist::isInShortlist($asset_code, $fy_id, $level, $id);

        if (!$checkIsInShortlist) {
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }*/

        //---------------------VALIDATION---------------------------------------------

        $messages = [
            'attachment.required' => 'This is required!',
            'attachment.mimes' => 'Document must be in pdf format.',
            'attachment.min' => 'Document size must not be less than 10 KB.',
            'attachment.max' => 'Document size must not exceed 400 KB.',
        ];

        $validatorArray = [
            'attachment' => 'required|mimes:pdf|max:400|min:10',
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);

        }

        //---------------------VALIDATION ENDED----------------------------------------


        /*$generalDetail = OsrNonTaxBiddingGeneralDetail::getEntryByCodeAndFyYr($asset_code, $fy_id);
        if (!$generalDetail) {
            $returnData['msg'] = "Oops! Something went wrong!";
            return response()->json($returnData);
        }*/
        $asset_signed_report = OsrNonTaxSignedAssetReport::getreportByfIdzId($fy_id,$users->zp_id);
        
        if(isset($asset_signed_report->attachment_path))
                        {
                            if( $asset_signed_report->attachment_path != NULL || $query[0]->document != "") {
                                $file = fopen(storage_path('app/'.$asset_signed_report->attachment_path), 'w') or die("can't open file");
                                fclose($file);
                                unlink(storage_path('app/'.$asset_signed_report->attachment_path));
                            }
                            if ($request->file('attachment')) {
                            $attachment_path = $request->file('attachment')->store('osr/non_tax/asset/shortlist_report/attachment/' .$fy_id. '/'. $users->zp_id);
                        } else {
                            $returnData['msg'] = "Upload attachment.";
                            return response()->json($returnData);
                        }
         }
        
        if ($request->file('attachment')) {
            $attachment_path = $request->file('attachment')->store('osr/non_tax/asset/shortlist_report/attachment/' .$fy_id. '/'. $users->zp_id);
        } else {
            $returnData['msg'] = "Upload attachment.";
            return response()->json($returnData);
        }

        $alreadyExists = OsrNonTaxSignedAssetReport::alreadyExist($fy_id,$users->zp_id);

        if ($alreadyExists) {
            $updateData = OsrNonTaxSignedAssetReport::where([
                ['osr_fy_year_id', '=', $fy_id],
                ['zila_id', '=', $users->zp_id],
            ])->update([
                'attachment_path' => $attachment_path,
                'updated_by' => $users->username,
                'updated_at' => Carbon::now(),
            ]);

            if (!$updateData) {
                $returnData['msg'] = "Oops! Something went wrong!4";
                return response()->json($returnData);
            }
        } else {
            $insertData = new OsrNonTaxSignedAssetReport();
            $insertData->osr_fy_year_id = $fy_id;
            
            $insertData->zila_id = $users->zp_id;
            $insertData->attachment_path = $attachment_path;
            $insertData->created_by = $users->username;
            $insertData->created_at = Carbon::now();

            if (!$insertData->save()) {
                $returnData['msg'] = "Oops! Something went wrong!4";
                return response()->json($returnData);
            }
        }

        $imgUrl = ConfigMdas::allActiveList()->imgUrl;

        $returnData['msgType'] = true;
        $returnData['msg'] = "Uploaded successfully!";
        $returnData['data'] = ['imgUrl' => $imgUrl, 'attachment_path' => $attachment_path, 'fy_id' => $fy_id];
        return response()->json($returnData);
    }

	//=================New Confirmation for 2020-21============================
	
	public function asset_confirmation(Request $request){
		
	   $users=Auth::user();
        $assetShortList=[];
        $levelCount=[];
	   $assetSettledData=[];
	   $assetCorfirmStatus = [];
	   $assetConfirmData = [];
		
	   $max_fy_id=OsrMasterFyYear::getMaxFyYear();
	   $fyData=OsrMasterFyYear::getFyYear($max_fy_id);
		
        $fyList=OsrMasterFyYear::getAllYears();

        $zpData=ZilaParishad::getZPName($users->zp_id);

        $assetList= OsrNonTaxAssetShortlist::leftJoin('osr_non_tax_asset_entries as e','e.asset_code','osr_non_tax_asset_shortlists.asset_code')
		   ->leftJoin('osr_non_tax_asset_final_records as f','f.asset_code','=','osr_non_tax_asset_shortlists.asset_code')
		   ->join('zila_parishads AS z', 'osr_non_tax_asset_shortlists.zp_id', '=', 'z.id')
            ->join('anchalik_parishads AS a', 'osr_non_tax_asset_shortlists.ap_id', '=', 'a.id')
            ->join('gram_panchyats AS g', 'osr_non_tax_asset_shortlists.gp_id', '=', 'g.gram_panchyat_id')
		  ->where([
			  ['osr_non_tax_asset_shortlists.zp_id','=',$users->zp_id],
			  ['osr_non_tax_asset_shortlists.osr_master_fy_year_id','=',4],
			  ['f.fy_id','=',4],
			  ['f.bidding_status','=',1],
			  ['osr_non_tax_asset_shortlists.level','!=','NA'],
		  ])->select('e.asset_name','e.asset_listing_date','osr_non_tax_asset_shortlists.*','z.zila_parishad_name', 'a.anchalik_parishad_name', 'g.gram_panchayat_name')
		   ->orderBy('e.asset_name')->get();
		
		
        $totAsset= OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short','a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
		   ->where([
            ['a_short.zp_id', '=', $users->zp_id],
	       ['osr_non_tax_asset_final_records.bidding_status','=',1],
            ['a_short.osr_master_fy_year_id','=',4],
		  ['osr_non_tax_asset_final_records.fy_id','=',4],	   
	       ['osr_non_tax_asset_final_records.bidding_status','=',1],
        ])->count();
		
	
	   $settlementData = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short','a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
		   ->join('osr_non_tax_bidding_general_details as g','g.asset_code','osr_non_tax_asset_final_records.asset_code')
		   ->leftJoin('osr_non_tax_bidding_bidders_details as bb','bb.osr_non_tax_bidding_general_detail_id','g.id')
		   ->leftJoin('osr_non_tax_bidding_settlement_details as bs','bs.osr_non_tax_bidding_general_detail_id','=','g.id')
		   ->leftJoin('osr_non_tax_bidder_entries as be','be.id','bb.osr_master_bidder_entry_id')
		   ->where([
			   ['a_short.zp_id','=',$users->zp_id],
			   ['osr_non_tax_asset_final_records.bidding_status','=',1],
			   ['a_short.osr_master_fy_year_id','=',4],
			   ['osr_non_tax_asset_final_records.fy_id','=',4],
			   ['g.osr_fy_year_id','=',4],
			   ['bb.bidder_status','=',1]
		   ])->select('osr_non_tax_asset_final_records.asset_code','be.b_f_name','be.b_m_name','be.b_l_name','osr_non_tax_asset_final_records.settlement_amt','osr_non_tax_asset_final_records.security_deposit_amt','a_short.level','bs.id as bidding_settlement_id')
		   ->get();


        foreach ($settlementData AS $li){
            $assetSettledData[$li->asset_code]=["asset_code"=>$li->asset_code, "bidder_name"=>$li->b_f_name." ".$li->b_m_name." ".$li->b_l_name, "security_deposit_amt"=>$li->security_deposit_amt, "settlement_amt"=>$li->settlement_amt,"bidding_settlement_id"=>$li->bidding_settlement_id];
        }
		
		$assetConfirmData = OsrNonTaxAssetConfirmation::getData();
	
		foreach($assetConfirmData as $data){
			$assetCorfirmStatus[$data->asset_code] = ['settlement_amt_changed'=>$data->settlement_amt_changed,
											  'security_money_changed'=>$data->security_money_changed,
											  'confirmation_status'=>$data->confirmation_status,
											  'confirmation_date'=>$data->confirmation_date,];
		}
		
		
		$assetConfirmCount = OsrNonTaxAssetConfirmation::where([
			['zp_id','=',$users->zp_id],
			['confirmation_status','=',1],
		])->count();
		
		$assetAuctionedCount = OsrNonTaxAssetConfirmation::where([
			['zp_id','=',$users->zp_id],
			['confirmation_status','=',2],
		])->count();
		
		
		$pending = $totAsset-$assetConfirmCount-$assetAuctionedCount;
		//echo json_encode($assetCorfirmStatus); 

        $data=[
		  'fy_id'=>$max_fy_id,
            'fyList'=>$fyList,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'assetList'=>$assetList,
            'totAsset'=>$totAsset,
            'assetSettledData'=>$assetSettledData,
            'assetCorfirmStatus'=>$assetCorfirmStatus,
            'assetConfirmCount'=>$assetConfirmCount,
            'assetAuctionedCount'=>$assetAuctionedCount,
            'pending'=>$pending,
        ];

        return view('Osr.non_tax.asset.asset_confirmation', compact('data'));
		
	}
	
	
	public function asset_confirmation_save(Request $request){
		
	   $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $users=Auth::user();
		
	   $cur_date=Carbon::now()->toDateString();

        $id= decrypt($request->input('id'));
        $assetCode= decrypt($request->input('assetCode'));
        $zp_id= decrypt($request->input('zp_id'));
        $ap_id= decrypt($request->input('ap_id'));
        $gp_id= decrypt($request->input('gp_id'));
        $bidder_name= decrypt($request->input('bidder_name'));
        $settlement_amt= decrypt($request->input('settlement_amt'));
        $security_amt= decrypt($request->input('security_amt'));
	   $bidding_settlement_id = decrypt($request->input('bidding_settlement_id'));
	   $level= $request->input('level_'.$id);
		
	    $messages = [
            'level_'.$id.'.required' => 'This is required.',
            'level_'.$id.'.in' => 'Invalid data.',
        ];

        $validatorArray = [
            'level_'.$id => 'required|in:0,1,2',
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);

        }	
		
        $assetData= OsrNonTaxAssetEntry::getAssetByAssetCode($assetCode);

        if(!$assetData){
            $returnData['msg'] = "Asset not found";
            return response()->json($returnData);
        }

        $newConfirmEntry= new OsrNonTaxAssetConfirmation();
        $newConfirmEntry->asset_code= $assetCode;
        $newConfirmEntry->confirmation_status= $level;
        $newConfirmEntry->confirmation_date= $cur_date;
        $newConfirmEntry->zp_id= $assetData->zila_id;
        $newConfirmEntry->ap_id= $assetData->anchalik_id ;
        $newConfirmEntry->gp_id= $assetData->gram_panchayat_id ;
        $newConfirmEntry->settlement_amt= $settlement_amt;
        $newConfirmEntry->security_money= $security_amt;
        $newConfirmEntry->bidder_name= $bidder_name;
        $newConfirmEntry->bidding_settlement_id= $bidding_settlement_id;
        $newConfirmEntry->created_by= $users->username;

        if(!$newConfirmEntry->save()){
            $returnData['msg'] = "Asset already shortlisted";
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = [];
        $returnData['msg'] = "Successfully Updated";
        return response()->json($returnData);
		
	}

}
