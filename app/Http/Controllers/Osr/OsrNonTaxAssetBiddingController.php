<?php

namespace App\Http\Controllers\Osr;

use App\CommonModels\District;
use App\ConfigMdas;
use App\Master\MasterGender;
use App\Osr\OsrMasterBidderRemark;
use App\Osr\OsrMasterFyYear;
use App\Osr\OsrMasterNonTaxBranch;
use App\Osr\OsrNonTaxAssetEntry;
use App\Osr\OsrNonTaxAssetFinalRecord;
use App\Osr\OsrNonTaxAssetShortlist;
use App\Osr\OsrNonTaxBiddingAttachment;
use App\Osr\OsrNonTaxBiddingAttachmentUpload;
use App\Osr\OsrNonTaxBidderAttachment;
use App\Osr\OsrNonTaxBidderAttachmentUpload;
use App\Osr\OsrNonTaxBiddingBiddersDetail;
use App\Osr\OsrNonTaxBiddingGeneralDetail;
use App\Osr\OsrNonTaxBiddingSettlementDetail;
use App\Master\MasterCaste;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use Crypt;
use DB;

class OsrNonTaxAssetBiddingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'user_mdas']);
    }

    /*------------------------------------ DISTRICT WISE BIDDING -----------------------------------------------------*/

    public function asset_bidding(Request $request, $asset_code, $fy_id)
    {

        $asset_code = base64_decode(base64_decode(base64_decode($asset_code)));
        $fy_id = base64_decode(base64_decode(base64_decode($fy_id)));
		
		$max_fy_id=OsrMasterFyYear::getMaxFyYear();
        $data=[
            'fy_id'=>$max_fy_id
        ];
		
		//echo json_encode(encrypt($data['fy_id']));
		
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
            return redirect(route('login'));
        }

        $checkIsInShortlist = OsrNonTaxAssetShortlist::isInShortlist($asset_code, $fy_id, $level, $id);

        if (!$checkIsInShortlist) {
            return redirect(route('login'));
		}


        $genderAll = MasterGender::all();
        $casteAll = MasterCaste::all();

        $branchData = OsrMasterNonTaxBranch::getBranchByAssetCode($asset_code);

        $assetData = OsrNonTaxAssetEntry::getAssetByAssetCode($asset_code);

        $osrFyYear = OsrMasterFyYear::getFyYear($fy_id);

        $bidderRemarks = OsrMasterBidderRemark::getActiveList();

        $imgUrl = ConfigMdas::allActiveList()->imgUrl;

        $generalDetail = OsrNonTaxBiddingGeneralDetail::getEntryByCodeAndFyYr($asset_code, $fy_id);

        $activeDocs = OsrNonTaxBiddingAttachment::getAllActiveDoc();
        
        $activeBidderDocs = OsrNonTaxBidderAttachment::getAllActiveBidderDoc();
	$uploadedBidderDoc = NULL;
        if ($generalDetail) {
            $bidderDetail = OsrNonTaxBiddingBiddersDetail::getAllBiddersByGeneralId($generalDetail->id);
            $uploadedDoc = OsrNonTaxBiddingAttachmentUpload::getUploadedAttachments($generalDetail->id);
            
            foreach($bidderDetail AS $bidder){
            $uploadAttachCount[$bidder->id] = OsrNonTaxBidderAttachmentUpload::attachmentUploadCount($bidder->id);
            }
            
            $acceptedBidderData = OsrNonTaxBiddingBiddersDetail::acceptedBidder($generalDetail->id);
            $totalBidder = OsrNonTaxBiddingBiddersDetail::totalBiddersCount($generalDetail->id);
            $totalWithdrawnBidder = OsrNonTaxBiddingBiddersDetail::totalWithdrawnBiddersCount($generalDetail->id);

            if ($acceptedBidderData) {
                $forfeitedBidderData = OsrNonTaxBiddingBiddersDetail::totalForfeitedBiddersCount($generalDetail->id, $acceptedBidderData->bidding_amt);
            } else {
                $forfeitedBidderData = [];
                $acceptedBidderData = NULL;
            }

            $settlementData = OsrNonTaxBiddingSettlementDetail::getSettlementInfo($generalDetail->id);
        } else {
            $totalWithdrawnBidder = NULL;
            $forfeitedBidderData = [];
            $bidderDetail = [];
            $uploadedDoc = NULL;
			$uploadedBidderDoc = NULL;
            $uploadAttachCount = NULL;
            $totalBidder = NULL;
            $acceptedBidderData = NULL;
            $settlementData = NULL;
        }

        

        return view('Osr.non_tax.asset.asset_bidding', compact('data', 'branchData', 'assetData', 'osrFyYear', 'genderAll', 'casteAll', 'generalDetail', 'bidderDetail', 'bidderRemarks', 'totalBidder', 'forfeitedBidderData', 'totalWithdrawnBidder', 'acceptedBidderData', 'activeDocs', 'activeBidderDocs', 'uploadedDoc','uploadedBidderDoc', 'settlementData', 'imgUrl','uploadAttachCount'));
    }

    /*------------------------------------ OSR BIDDING SAVE GENERAL DETAILS ------------------------------------------*/

    public function save_general_detail(Request $request)
    {

        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $fy_id = $request->input('fy_id');
        $asset_code = $request->input('asset_code');

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
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }

        $checkIsInShortlist = OsrNonTaxAssetShortlist::isInShortlist($asset_code, $fy_id, $level, $id);

        if (!$checkIsInShortlist) {
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }

        $osr_ad_path = NULL;
        $govt_value = doubleval(preg_replace('/[^\d.]/', '', $request->input('govt_value')));


        //---------------------VALIDATION---------------------------------------------
        $messages = [
            'date_of_tender.required' => 'This is required!',
            'date_of_tender.date_format' => 'This format is invalid!',

            'advertisement.required' => 'This is required!',
            'advertisement.mimes' => 'Document must be in pdf format.',
            'advertisement.min' => 'Document size must not be less than 10 KBs.',
            'advertisement.max' => 'Document size must not exceed 1Mb.',
        ];

        $validatorArray = [
            'date_of_tender' => 'required|date_format:Y-m-d',
            'advertisement' => 'mimes:pdf|max:1500|min:10',

            'govt_value' => [
                'required',
                function ($attribute, $value, $fail) {

                    $value = doubleval(preg_replace('/[^\d.]/', '', $value));

                    if (!preg_match('/^[0-9]+(\.[0-9][0-9]?)?$/', $value)) {
                        $fail("Amount up to two decimal points is allowed!");
                    }

                    if ($value > 999999999) {
                        $fail('Amount should not exceed 99 crores!');
                    }

                    if ($value < 0) {
                        $fail('Amount should not be less than zero!');
                    }

                },
            ],
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);

        }

        //---------------------VALIDATION ENDED----------------------------------------

        if ($request->file('advertisement')) {
            $osr_ad_path = $request->file('advertisement')->store('osr/non_tax/asset/advertisement/' . $level . '/' . $id . '/' . $fy_id);
        }

        $alreadyExists = OsrNonTaxBiddingGeneralDetail::getEntryByCodeAndFyYr($asset_code, $fy_id);

        if ($alreadyExists) {

            if ($alreadyExists->stage == 3) {
                $returnData['msg'] = "Final submit is already done. Kindly refresh";
                return response()->json($returnData);
            }

            $updateArray = [
                'govt_value' => $govt_value,
                'date_of_tender' => $request->input('date_of_tender'),
                'updated_by' => $users->username,
                'updated_at' => Carbon::now()
            ];

            if ($osr_ad_path) {
                $updateArray['advertisement'] = $osr_ad_path;
            }

            $updateGEntry = OsrNonTaxBiddingGeneralDetail::where('id', $alreadyExists->id)
                ->update($updateArray);

            if (!$updateGEntry) {
                $returnData['msg'] = "Opps! Something went wrong.";
                return response()->json($returnData);
            }

            $returnData['msg'] = "General details data successfully updated";
        } else {
            if (!$osr_ad_path) {
                $returnData['msg'] = "Please select advertisement to proceed.";
                return response()->json($returnData);
            }

            $osrGEntry = new OsrNonTaxBiddingGeneralDetail();

            $osrGEntry->asset_code = $asset_code;
            $osrGEntry->osr_fy_year_id = $fy_id;

            //EXCLUDING COMMAS FROM NUMBERS -------------------------------------------------------
            $osrGEntry->govt_value = $govt_value;

            $osrGEntry->date_of_tender = $request->input('date_of_tender');
            $osrGEntry->advertisement = $osr_ad_path;
            $osrGEntry->stage = 1;

            $osrGEntry->created_by = $users->username;
            $osrGEntry->save();

            if (!$osrGEntry->save()) {
                $returnData['msg'] = "Opps! Something went wrong.";
                return response()->json($returnData);
            }

            $returnData['msg'] = "General details data successfully submitted";
        }


        $returnData['msgType'] = true;
        $returnData['data'] = [];
        return response()->json($returnData);
    }

    public function getGeneralDetails(Request $request)
    {

        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $fy_id = $request->input('fy_id');
        $asset_code = $request->input('asset_code');

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
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }

        $checkIsInShortlist = OsrNonTaxAssetShortlist::isInShortlist($asset_code, $fy_id, $level, $id);

        if (!$checkIsInShortlist) {
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }

        $generalData = OsrNonTaxBiddingGeneralDetail::getEntryByCodeAndFyYr($asset_code, $fy_id);

        if (!$generalData) {
            $returnData['msg'] = "Opps! Something went wrong!";
            return response()->json($returnData);
        }

        $returnData['msg'] = "Data Submitted";
        $returnData['msgType'] = true;
        $returnData['data'] = $generalData;
        return response()->json($returnData);
    }

    public function bidder_status_update(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $asset_code = $request->input('asset_code');
        $fy_id = $request->input('fy_id');

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
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }

        $checkIsInShortlist = OsrNonTaxAssetShortlist::isInShortlist($asset_code, $fy_id, $level, $id);

        if (!$checkIsInShortlist) {
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }

        $generalDetail = OsrNonTaxBiddingGeneralDetail::getEntryByCodeAndFyYr($asset_code, $fy_id);

        if (!$generalDetail) {
            $returnData['msg'] = "Oops! Something went wrong.";
            return response()->json($returnData);
        }

        if ($generalDetail->stage == 3) {
            $returnData['msg'] = "Final submit is already done. Kindly refresh";
            return response()->json($returnData);
        }

        $totalBidder = OsrNonTaxBiddingBiddersDetail::totalBiddersCount($generalDetail->id);
        if ($totalBidder < 1) {
            $returnData['msg'] = "Kindly add bidders to continue.";
            return response()->json($returnData);
        }

        $acceptedBidderData = OsrNonTaxBiddingBiddersDetail::acceptedBidder($generalDetail->id);
        if (!$acceptedBidderData) {
            $returnData['msg'] = "No bidder is accepted yet.";
            return response()->json($returnData);
        }

        $updateData = OsrNonTaxBiddingGeneralDetail::where([
            ['id', '=', $generalDetail->id],
        ])->update(['stage' => 2, 'updated_by' => $users->username, 'updated_at' => Carbon::now()]);

        if (!$updateData) {
            $returnData['msg'] = "Opps! Something went wrong!";
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['msg'] = "Data updated successfully!";
        $returnData['data'] = ["acceptedBidder" => $acceptedBidderData, 'totalBidder' => $totalBidder];
        return response()->json($returnData);
    }
    
    /*------------------------------BIDDING ATTACHMENT---------------------------------------------*/
    public function bidder_attachment_upload(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $attachment_path = NULL;

        $asset_code = $request->input('asset_code');
        $fy_id = $request->input('fy_id');
        $doc_no = $request->input('doc_no');

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
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }

        $checkIsInShortlist = OsrNonTaxAssetShortlist::isInShortlist($asset_code, $fy_id, $level, $id);

        if (!$checkIsInShortlist) {
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }

        //---------------------VALIDATION---------------------------------------------

        $messages = [
            'attachment.required' => 'This is required!',
            'attachment.mimes' => 'Document must be in pdf format.',
            'attachment.min' => 'Document size must not be less than 10 KB.',
            'attachment.max' => 'Document size must not exceed 1Mb.',
        ];

        $validatorArray = [
            'attachment' => 'required|mimes:pdf|max:1024|min:10',
            'doc_no' => 'required|exists:osr_non_tax_bidding_attachments,id',
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);

        }

        //---------------------VALIDATION ENDED----------------------------------------


        $generalDetail = OsrNonTaxBiddingGeneralDetail::getEntryByCodeAndFyYr($asset_code, $fy_id);
        if (!$generalDetail) {
            $returnData['msg'] = "Oops! Something went wrong!";
            return response()->json($returnData);
        }

        if ($generalDetail->stage == 3) {
            $returnData['msg'] = "Final submit is already done. Kindly refresh";
            return response()->json($returnData);
        }

        if ($request->file('attachment')) {
            $attachment_path = $request->file('attachment')->store('osr/non_tax/asset/attachment/' . $level . '/' . $id . '/' . $doc_no);
        } else {
            $returnData['msg'] = "Upload attachment.";
            return response()->json($returnData);
        }

        $alreadyExists = OsrNonTaxBiddingAttachmentUpload::alreadyExist($generalDetail->id, $doc_no);

        if ($alreadyExists) {
            $updateData = OsrNonTaxBiddingAttachmentUpload::where([
                ['osr_non_tax_bidding_general_detail_id', '=', $generalDetail->id],
                ['osr_non_tax_bidding_attachment_id', '=', $doc_no],
            ])->update([
                'attachment_path' => $attachment_path,
                'updated_by' => $users->username,
                'updated_at' => Carbon::now(),
            ]);

            if (!$updateData) {
                $returnData['msg'] = "Oops! Something went wrong!";
                return response()->json($returnData);
            }
        } else {
            $insertData = new OsrNonTaxBiddingAttachmentUpload();
            $insertData->osr_non_tax_bidding_general_detail_id = $generalDetail->id;
            $insertData->osr_non_tax_bidding_attachment_id = $doc_no;
            $insertData->attachment_path = $attachment_path;
            $insertData->created_by = $users->username;

            if (!$insertData->save()) {
                $returnData['msg'] = "Oops! Something went wrong!";
                return response()->json($returnData);
            }
        }

        $imgUrl = ConfigMdas::allActiveList()->imgUrl;

        $returnData['msgType'] = true;
        $returnData['msg'] = "Uploaded successfully!";
        $returnData['data'] = ['imgUrl' => $imgUrl, 'attachment_path' => $attachment_path, 'doc_no' => $doc_no];
        return response()->json($returnData);
    }

    /*------------------------------------ FINAL SUBMIT --------------------------------------------------------------*/

    public function bidding_final_submit(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $asset_code = $request->input('asset_code');
        $fy_id = $request->input('fy_id');

        $totalForfeitedAmt = 0;
        $securityDeposit= doubleval(preg_replace('/[^\d.]/', '', $request->input('security_deposit')));

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
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }

        $checkIsInShortlist = OsrNonTaxAssetShortlist::isInShortlist($asset_code, $fy_id, $level, $id);

        if (!$checkIsInShortlist) {
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }

        //---------------------VALIDATION----------------------------------------------

        $messages = [
            'work_order_no.required' => 'This is required!',
            'work_order_no.max' => 'Maximum 35 characters allowed!',
            'work_order_no.min' => 'Minimum 5 characters allowed!',

            'file_no.required' => 'This is required!',
            'file_no.max' => 'Maximum 35 characters allowed!',
            'file_no.min' => 'Minimum 5 characters allowed!',

            'awarded_date.required' => 'This is required!',
            'awarded_date.date_format' => 'This format is invalid!',

        ];

        $validatorArray = [
            'work_order_no' => 'required|max:35|min:5',
            'file_no' => 'required|max:35|min:5',
            'awarded_date' => 'required|date_format:Y-m-d',

            'security_deposit' => [
                'required',
                function ($attribute, $value, $fail) {

                    $value = doubleval(preg_replace('/[^\d.]/', '', $value));

                    if (!preg_match('/^[0-9]+(\.[0-9][0-9]?)?$/', $value)) {
                        $fail("Amount up to two decimal points is allowed!");
                    }

                    if ($value > 999999999) {
                        $fail('Amount should not exceed 99 crores!');
                    }

                    if ($value < 0) {
                        $fail('Amount should not be less than zero!');
                    }

                },
            ],
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);

        }

        //---------------------VALIDATION ENDED----------------------------------------

        $generalDetail = OsrNonTaxBiddingGeneralDetail::getEntryByCodeAndFyYr($asset_code, $fy_id);
        if (!$generalDetail) {
            $returnData['msg'] = "Oops! Something went wrong!";
            return response()->json($returnData);
        }

        $alreadyExists = OsrNonTaxBiddingSettlementDetail::alreadyExist($generalDetail->id);

        if (!$alreadyExists) {
            $returnData['msg'] = "Final Submit is already done for the financial year";
            return response()->json($returnData);
        }

        $acceptedBidderData = OsrNonTaxBiddingBiddersDetail::acceptedBidder($generalDetail->id);

        if (!$acceptedBidderData) {
            $returnData['msg'] = "Oops! Something went wrong!";
            return response()->json($returnData);
        }

        //----------------------------- CHECK DOCUMENTS TOTAL ----------------------------------------------------------

        $checkDocsUploaded = OsrNonTaxBiddingAttachmentUpload::checkDocsUploaded($generalDetail->id);

        if (!$checkDocsUploaded) {
            $returnData['msg'] = "Please upload all attachments before final submit.";
            return response()->json($returnData);
        }

        DB::beginTransaction();
        try {
            $totalBidder = OsrNonTaxBiddingBiddersDetail::totalBiddersCount($generalDetail->id);
            $totalWithdrawnBidder = OsrNonTaxBiddingBiddersDetail::totalWithdrawnBiddersCount($generalDetail->id);

            $forfeitedBidderData = OsrNonTaxBiddingBiddersDetail::totalForfeitedBiddersCount($generalDetail->id, $acceptedBidderData->bidding_amt);

            foreach ($forfeitedBidderData AS $li) {
                $totalForfeitedAmt = $totalForfeitedAmt + $li->ernest_amt;
            }

            $insertData = new OsrNonTaxBiddingSettlementDetail();
            $insertData->osr_non_tax_bidding_general_detail_id = $generalDetail->id;
            $insertData->osr_non_tax_bidding_bidders_detail_id = $acceptedBidderData->bidding_bidder_id;
            $insertData->osr_non_tax_bidder_entry_id = $acceptedBidderData->id;
            $insertData->work_order_no = $request->input('work_order_no');
            $insertData->settlement_amt = $acceptedBidderData->bidding_amt;
            $insertData->security_deposit = $securityDeposit;
            $insertData->managed_by = $level;
            $insertData->file_no = $request->input('file_no');
            $insertData->awarded_date = $request->input('awarded_date');
            $insertData->total_bidder = $totalBidder;
            $insertData->total_withdrawn_bidder = $totalWithdrawnBidder;
            $insertData->total_forfeited_bidder = count($forfeitedBidderData);
            $insertData->total_forfeited_amount = $totalForfeitedAmt;
            $insertData->awarded_date = $request->input('awarded_date');
            $insertData->created_by = $users->username;

            if (!$insertData->save()) {
                DB::rollback();
                $returnData['msg'] = "Oops! Something went wrong!";
                return response()->json($returnData);
            }

            OsrNonTaxBiddingGeneralDetail::where([
                ['id', '=', $generalDetail->id],
            ])->update(['stage' => 3, 'updated_by' => $users->username, 'bidding_completed_status'=>1, 'updated_at' => Carbon::now()]);


            //UPDATE FINAL RECORD TABLE

            $finalRecordCount=OsrNonTaxAssetFinalRecord::finalRecordCount($asset_code, $fy_id);

            if($finalRecordCount==0){
                $finalRecordNew= new OsrNonTaxAssetFinalRecord();

                $finalRecordNew->asset_code=$asset_code;
                $finalRecordNew->fy_id=$fy_id;
                $finalRecordNew->settlement_amt=$acceptedBidderData->bidding_amt;
                $finalRecordNew->total_forfeited_emd_amt=$totalForfeitedAmt;
                $finalRecordNew->security_deposit_amt=$securityDeposit;
				$finalRecordNew->bidding_status=1;
				
                $finalRecordNew->created_by=$users->username;

                if(!$finalRecordNew->save()){
                    DB::rollback();
                    return response()->json($returnData);
                }

            }elseif($finalRecordCount==1){

                $finalRecordUpdate= OsrNonTaxAssetFinalRecord::where([
                    ['asset_code', '=', $asset_code],
                    ['fy_id', '=', $fy_id],
                ])->update([
                    'settlement_amt'=> $acceptedBidderData->bidding_amt,
                    'total_forfeited_emd_amt'=> $totalForfeitedAmt,
                    'security_deposit_amt'=> $securityDeposit
                ]);

                if(!$finalRecordUpdate){
                    DB::rollback();
                    return response()->json($returnData);
                }

            }else{
                DB::rollback();
                return response()->json($returnData);
            }

            //UPDATE FINAL RECORD TABLE ENDED


        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Server Exception!" . $e->getMessage();
            return response()->json($returnData);
        }

        DB::commit();
        $returnData['msgType'] = true;
        $returnData['msg'] = "Successfully final submitted!";
        $returnData['data'] = [];
        return response()->json($returnData);
    }

    /************************************ UPLOAD REPORT ****************************************************************/
    public function report_upload(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $asset_code = $request->input('asset_code');
        $fy_id = $request->input('fy_id');
        $attachment_path = NULL;

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
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }

        $checkIsInShortlist = OsrNonTaxAssetShortlist::isInShortlist($asset_code, $fy_id, $level, $id);

        if (!$checkIsInShortlist) {
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }


        //---------------------VALIDATION---------------------------------------------

        $messages = [
            'upload_report.required' => 'This is required!',
            'upload_report.mimes' => 'Document must be in pdf format.',
            'upload_report.min' => 'Document size must not be less than 10 KB.',
            'upload_report.max' => 'Document size must not exceed 200 KB.',
        ];

        $validatorArray = [
            'upload_report' => 'required|max:200|min:10',
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);

        }

        //---------------------VALIDATION ENDED----------------------------------------


        $generalDetail = OsrNonTaxBiddingGeneralDetail::getEntryByCodeAndFyYr($asset_code, $fy_id);
        if (!$generalDetail) {
            $returnData['msg'] = "Oops! Something went wrong!";
            return response()->json($returnData);
        }


        if ($request->file('upload_report')) {
            $attachment_path = $request->file('upload_report')->store('osr/non_tax/asset/upload_report/' . $level . '/' . $id . '/' . $asset_code);
        } else {
            $returnData['msg'] = "Upload attachment.";
            return response()->json($returnData);
        }

        DB::beginTransaction();
        try {
            $updateData = OsrNonTaxBiddingSettlementDetail::where([
                ['osr_non_tax_bidding_general_detail_id', '=', $generalDetail->id],
            ])->update([
                'final_report_path' => $attachment_path,
                'updated_by' => $users->username,
                'updated_at' => Carbon::now(),
            ]);

            $updateData1 = OsrNonTaxBiddingGeneralDetail::where([
                ['id', '=', $generalDetail->id],
            ])->update([
                'bidding_completed_status' => 1
            ]);

            if(!$updateData1){
                DB::rollback();
                $returnData['msg'] = "Data already submitted!";
                return response()->json($returnData);
            }
            //UPDATE FINAL RECORD TABLE

            $finalRecordUpdate= OsrNonTaxAssetFinalRecord::where([
                ['asset_code', '=', $asset_code],
                ['fy_id', '=', $fy_id],
            ])->update([
                'bidding_status'=> 1
            ]);

            if(!$finalRecordUpdate){
                DB::rollback();
                return response()->json($returnData);
            }

            //UPDATE FINAL RECORD TABLE ENDED

        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Server Exception!";
            return response()->json($returnData);
        }
        DB::commit();


        $returnData['msgType'] = true;
        $returnData['data'] = ['attachment_path' => $attachment_path];
        $returnData['msg'] = "Successfully uploaded";
        return response()->json($returnData);
    }
}
