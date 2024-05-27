<?php

namespace App\Http\Controllers\Osr;

use App\CommonModels\District;
use App\ConfigMdas;
use App\Master\MasterGender;
use App\Osr\OsrMasterBidderRemark;
use App\Osr\OsrMasterFyYear;
use App\Osr\OsrMasterNonTaxBranch;
use App\Osr\OsrNonTaxAssetEntry;
use App\Osr\OsrNonTaxBiddingAttachment;
use App\Osr\OsrNonTaxBiddingAttachmentUpload;
use App\Osr\OsrNonTaxBiddingBiddersDetail;
use App\Osr\OsrNonTaxBiddingGeneralDetail;
use App\Osr\OsrNonTaxBiddingSettlementDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use DB;

class OsrNonTaxAssetBiddingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'user_mdas']);
    }

    /*------------------------------------ DISTRICT WISE BIDDING -----------------------------------------------------*/

    public function dw_asset_bidding(Request $request, $asset_id, $osr_fy_id){
        $users=$request->session()->get('users');

        $zpData=District::getZilaByDistrictId($users->district_code);

        $genderAll = MasterGender::all();

        if(!$zpData){
            return redirect()->route('osr.dashboard');
        }

        $asset_id=base64_decode(base64_decode(base64_decode($asset_id)));
        $osr_fy_id=base64_decode(base64_decode(base64_decode($osr_fy_id)));

        $branchData=OsrMasterNonTaxBranch::getBranchByAssetId($asset_id);

        $assetData=OsrNonTaxAssetEntry::getAssetByAssetId($asset_id);

        $osrFyYear= OsrMasterFyYear::getFyYear($osr_fy_id);

        //----------ZILA MISMATCH ----------------------------------
        $checkMisMatch=OsrNonTaxAssetEntry::checkZilaMismatch($zpData->id, $asset_id);

        if(!$assetData || !$osrFyYear || !$checkMisMatch['msgType']){
            return redirect(route('osr.dashboard'));
        }

        $bidderRemarks=OsrMasterBidderRemark::getActiveList();

        $imgUrl=ConfigMdas::allActiveList()->imgUrl;

        $generalDetail= OsrNonTaxBiddingGeneralDetail::getEntryByIdAndFyYr($asset_id, $osr_fy_id);

        $activeDocs=OsrNonTaxBiddingAttachment::getAllActiveDoc();

        if($generalDetail){
            $bidderDetail= OsrNonTaxBiddingBiddersDetail::getAllBiddersByGeneralId($generalDetail->id);
            $uploadedDoc= OsrNonTaxBiddingAttachmentUpload::getUploadedAttachments($generalDetail->id);

            $totalBidder= OsrNonTaxBiddingBiddersDetail::totalBiddersCount($generalDetail->id);
            $acceptedBidderData= OsrNonTaxBiddingBiddersDetail::acceptedBidder($generalDetail->id);

            $settlementData= OsrNonTaxBiddingSettlementDetail::getSettlementInfo($generalDetail->id);
        }else{
            $bidderDetail=[];
            $uploadedDoc=NULL;
            $totalBidder=NULL;
            $acceptedBidderData=NULL;
            $settlementData=NULL;
        }

        return view('Osr.non_tax.dw_asset_bidding',compact('branchData', 'assetData','osrFyYear', 'genderAll', 'generalDetail', 'bidderDetail', 'bidderRemarks', 'totalBidder', 'acceptedBidderData', 'activeDocs', 'uploadedDoc', 'settlementData', 'imgUrl'));
    }

    /*------------------------------------ OSR BIDDING SAVE GENERAL DETAILS ------------------------------------------*/

    public function save_general_detail(Request $request){

        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        //---------------------INITIALIZATION ----------------------------------------

        $osr_ad_path = NULL;
        $cur_year=Carbon::now()->format('Y');
        $govt_value = doubleval(preg_replace('/[^\d.]/', '', $request->input('govt_value')));

        //---------------------INITIALIZATION ----------------------------------------


        //---------------------VALIDATION---------------------------------------------
        $messages =[
            'date_of_tender.required' => 'This is required!',
            'date_of_tender.date_format' => 'This format is invalid!',

            'advertisement.required' => 'This is required!',
            'advertisement.mimes' => 'Document must be in pdf format.',
            'advertisement.min' => 'Document size must not be less than 10 KB.',
            'advertisement.max' => 'Document size must not exceed 200 KB.',
        ];

        $validatorArray=[
            'date_of_tender' => 'required|date_format:Y-m-d',
            'advertisement' => 'mimes:pdf|max:200|min:10',

            'govt_value' => [
                'required',
                function ($attribute, $value, $fail) {

                    $value= doubleval(preg_replace('/[^\d.]/', '', $value));

                    if (!preg_match('/^[0-9]+(\.[0-9][0-9]?)?$/', $value))
                    {
                        $fail("Amount up to two decimal points is allowed!");
                    }

                    if($value > 999999999){
                        $fail('Amount should not exceed 99 crores!');
                    }

                    if($value < 0){
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

        $users=$request->session()->get('users');

        $zpData = District::getZilaByDistrictId($users->district_code);

        if(!$zpData){
            $returnData['msg'] = "Opps! Something went wrong.";
            return response()->json($returnData);
        }

        $osr_fy_yr_id= $request->input('osr_fy_yr_id');
        $osr_asset_entry_id= $request->input('osr_asset_entry_id');

        //----------ZILA MISMATCH ----------------------------------
        $checkMisMatch=OsrNonTaxAssetEntry::checkZilaMismatch($zpData->id, $osr_asset_entry_id);

        if(!$checkMisMatch['msgType']){
            $returnData['msg'] = $checkMisMatch['msg'];
            return response()->json($returnData);
        }
        //----------ZILA MISMATCH ENDED------------------------------

        if ($request->file('advertisement')) {
            $osr_ad_path = $request->file('advertisement')->store('osr/non_tax/asset/advertisement/'.preg_replace('/\s+/', '_', $zpData->zila_parishad_name).'/'.$cur_year);
        }

        $alreadyExists=OsrNonTaxBiddingGeneralDetail::getEntryByIdAndFyYr($osr_asset_entry_id, $osr_fy_yr_id);

        if($alreadyExists){

            if($alreadyExists->stage==3){
                $returnData['msg'] = "Final submit is already done. Kindly refresh";
                return response()->json($returnData);
            }

            $updateArray=[
                'govt_value'=>$govt_value,
                'date_of_tender'=>$request->input('date_of_tender'),
                'updated_by'=>$users->employee_code,
                'updated_at'=>Carbon::now()
            ];

            if($osr_ad_path){
                $updateArray['advertisement']=$osr_ad_path;
            }

            $updateGEntry=OsrNonTaxBiddingGeneralDetail::where('id', $alreadyExists->id)
                ->update($updateArray);

            if(!$updateGEntry){
                $returnData['msg'] = "Opps! Something went wrong.";
                return response()->json($returnData);
            }

            $returnData['msg'] = "General details data successfully updated";
        }else{
            if(!$osr_ad_path){
                $returnData['msg'] = "Please select advertisement to proceed.";
                return response()->json($returnData);
            }

            $osrGEntry= new OsrNonTaxBiddingGeneralDetail();

            $osrGEntry->osr_asset_entry_id = $osr_asset_entry_id;
            $osrGEntry->osr_fy_year_id = $osr_fy_yr_id;

            //EXCLUDING COMMAS FROM NUMBERS -------------------------------------------------------
            $osrGEntry->govt_value = $govt_value;

            $osrGEntry->date_of_tender = $request->input('date_of_tender');
            $osrGEntry->advertisement = $osr_ad_path;
            $osrGEntry->stage = 1;

            $osrGEntry->save();

            if(!$osrGEntry->save()){
                $returnData['msg'] = "Opps! Something went wrong.";
                return response()->json($returnData);
            }

            $returnData['msg'] = "General details data successfully submitted";
        }


        $returnData['msgType']=true;
        $returnData['data']=[];
        return response()->json($returnData);
    }

    public function getGeneralDetails(Request $request){

        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $users=$request->session()->get('users');

        $zpData = District::getZilaByDistrictId($users->district_code);

        if(!$zpData){
            $returnData['msg'] = "Opps! Something went wrong.";
            return response()->json($returnData);
        }

        $osr_fy_yr_id= $request->input('fy_year_id');
        $osr_asset_entry_id= $request->input('asset_id');

        //----------ZILA MISMATCH ----------------------------------
        $checkMisMatch=OsrNonTaxAssetEntry::checkZilaMismatch($zpData->id, $osr_asset_entry_id);

        if(!$checkMisMatch['msgType']){
            $returnData['msg'] = $checkMisMatch['msg'];
            return response()->json($returnData);
        }
        //----------ZILA MISMATCH ENDED------------------------------
        $generalData=OsrNonTaxBiddingGeneralDetail::getEntryByIdAndFyYr($osr_asset_entry_id, $osr_fy_yr_id);

        if(!$generalData){
            $returnData['msg'] = "Opps! Something went wrong!";
            return response()->json($returnData);
        }

        $returnData['msg'] = "Data Submitted";
        $returnData['msgType']=true;
        $returnData['data']=$generalData;
        return response()->json($returnData);
    }

    public function bidder_status_update(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $asset_id = $request->input('asset_id');
        $fy_year_id = $request->input('fy_year_id');

        $generalDetail= OsrNonTaxBiddingGeneralDetail::getEntryByIdAndFyYr($asset_id, $fy_year_id);
        if(!$generalDetail){
            $returnData['msg'] = "Oops! Something went wrong.";
            return response()->json($returnData);
        }

        if($generalDetail->stage==3){
            $returnData['msg'] = "Final submit is already done. Kindly refresh";
            return response()->json($returnData);
        }

        $totalBidder= OsrNonTaxBiddingBiddersDetail::totalBiddersCount($generalDetail->id);
        if($totalBidder < 1){
            $returnData['msg'] = "Kindly add bidders to continue.";
            return response()->json($returnData);
        }

        $acceptedBidderData= OsrNonTaxBiddingBiddersDetail::acceptedBidder($generalDetail->id);
        if(!$acceptedBidderData){
            $returnData['msg'] = "No bidder is accepted yet.";
            return response()->json($returnData);
        }

        $users=$request->session()->get('users');
        $zpData=District::getZilaByDistrictId($users->district_code);
        if(!$zpData){
            $returnData['msg'] = "Opps! Something went wrong!";
            return response()->json($returnData);
        }

        //----------ZILA MISMATCH ----------------------------------
        $checkMisMatch=OsrNonTaxAssetEntry::checkZilaMismatch($zpData->id, $asset_id);

        if(!$checkMisMatch['msgType']){
            $returnData['msg'] = $checkMisMatch['msg'];
            return response()->json($returnData);
        }
        //----------ZILA MISMATCH ENDED------------------------------


        $updateData= OsrNonTaxBiddingGeneralDetail::where([
            ['id', '=', $generalDetail->id],
        ])->update(['stage'=>2, 'updated_by'=>$users->employee_code, 'updated_at'=>Carbon::now()]);

        if(!$updateData){
            $returnData['msg'] = "Opps! Something went wrong!";
            return response()->json($returnData);
        }

        $returnData['msgType']=true;
        $returnData['msg'] = "Data updated successfully!";
        $returnData['data']=["acceptedBidder"=>$acceptedBidderData, 'totalBidder'=>$totalBidder];
        return response()->json($returnData);
    }

    public function bidder_attachment_upload(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $cur_year=Carbon::now()->format('Y');
        $attachment_path=NULL;

        //---------------------VALIDATION---------------------------------------------

        $messages =[
            'attachment.required' => 'This is required!',
            'attachment.mimes' => 'Document must be in pdf format.',
            'attachment.min' => 'Document size must not be less than 10 KB.',
            'attachment.max' => 'Document size must not exceed 200 KB.',
        ];

        $validatorArray=[
            'attachment' => 'required|mimes:pdf|max:200|min:10',
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

        $users=$request->session()->get('users');
        $zpData = District::getZilaByDistrictId($users->district_code);

        if(!$zpData){
            $returnData['msg'] = "Opps! Something went wrong!2";
            return response()->json($returnData);
        }

        $asset_id = $request->input('asset_id');
        $fy_year_id = $request->input('fy_yr_id');
        $doc_no = $request->input('doc_no');

        //echo $asset_id."--".$fy_year_id;

        $generalDetail= OsrNonTaxBiddingGeneralDetail::getEntryByIdAndFyYr($asset_id, $fy_year_id);
        if(!$generalDetail){
            $returnData['msg'] = "Oops! Something went wrong!";
            return response()->json($returnData);
        }

        if($generalDetail->stage==3){
            $returnData['msg'] = "Final submit is already done. Kindly refresh";
            return response()->json($returnData);
        }

        //----------ZILA MISMATCH ----------------------------------
        $checkMisMatch=OsrNonTaxAssetEntry::checkZilaMismatch($zpData->id, $asset_id);

        if(!$checkMisMatch['msgType']){
            $returnData['msg'] = $checkMisMatch['msg'];
            return response()->json($returnData);
        }
        //----------ZILA MISMATCH ENDED------------------------------

        if ($request->file('attachment')) {
            $attachment_path = $request->file('attachment')->store('osr/non_tax/asset/attachment/'.preg_replace('/\s+/', '_', $zpData->zila_parishad_name).'/'.$cur_year.'/'.$doc_no);
        }else{
            $returnData['msg'] = "Upload attachment.";
            return response()->json($returnData);
        }

        $alreadyExists= OsrNonTaxBiddingAttachmentUpload::alreadyExist($generalDetail->id, $doc_no);

        if($alreadyExists){
            $updateData=OsrNonTaxBiddingAttachmentUpload::where([
                ['osr_non_tax_bidding_general_detail_id', '=', $generalDetail->id],
                ['osr_non_tax_bidding_attachment_id', '=', $doc_no],
            ])->update([
                'attachment_path'=>$attachment_path,
                'updated_by'=>$users->employee_code,
                'updated_at'=>Carbon::now(),
            ]);

            if(!$updateData){
                $returnData['msg'] = "Oops! Something went wrong!4";
                return response()->json($returnData);
            }
        }else{
            $insertData= new OsrNonTaxBiddingAttachmentUpload();
            $insertData->osr_non_tax_bidding_general_detail_id=$generalDetail->id;
            $insertData->osr_non_tax_bidding_attachment_id=$doc_no;
            $insertData->attachment_path=$attachment_path;
            $insertData->created_by=$users->employee_code;

            if(!$insertData->save()){
                $returnData['msg'] = "Oops! Something went wrong!4";
                return response()->json($returnData);
            }
        }



        $imgUrl=ConfigMdas::allActiveList()->imgUrl;

        $returnData['msgType']=true;
        $returnData['msg'] = "Uploaded successfully!";
        $returnData['data']=['imgUrl'=>$imgUrl, 'attachment_path'=>$attachment_path, 'doc_no'=>$doc_no];
        return response()->json($returnData);
    }

    /*------------------------------------ FINAL SUBMIT --------------------------------------------------------------*/

    public function bidder_final_submit(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        //---------------------VALIDATION----------------------------------------------

        $messages =[
            'work_order_no.required' => 'This is required!',
            'work_order_no.max' => 'Maximum 20 characters allowed!',
            'work_order_no.min' => 'Minimum 5 characters allowed!',

            'managed_by.required' => 'This is required!',
            'managed_by.in' => 'Invalid data!',
        ];

        $validatorArray=[
            'work_order_no' => 'required|max:20|min:5',
            'managed_by' => 'required|in:ZP,AP,GP',
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);

        }

        //---------------------VALIDATION ENDED----------------------------------------

        $users=$request->session()->get('users');

        $zpData = District::getZilaByDistrictId($users->district_code);

        if(!$zpData){
            $returnData['msg'] = "Opps! Something went wrong!";
            return response()->json($returnData);
        }

        $asset_id = $request->input('asset_id');
        $fy_year_id = $request->input('fy_year_id');

        $generalDetail= OsrNonTaxBiddingGeneralDetail::getEntryByIdAndFyYr($asset_id, $fy_year_id);
        if(!$generalDetail){
            $returnData['msg'] = "Oops! Something went wrong!";
            return response()->json($returnData);
        }

        //----------ZILA MISMATCH ----------------------------------
        $checkMisMatch=OsrNonTaxAssetEntry::checkZilaMismatch($zpData->id, $asset_id);

        if(!$checkMisMatch['msgType']){
            $returnData['msg'] = $checkMisMatch['msg'];
            return response()->json($returnData);
        }
        //----------ZILA MISMATCH ENDED------------------------------

        $alreadyExists= OsrNonTaxBiddingSettlementDetail::alreadyExist($generalDetail->id);

        if(!$alreadyExists){
            $returnData['msg'] = "Final Submit is already done for the financial year";
            return response()->json($returnData);
        }

        $acceptedBidderData= OsrNonTaxBiddingBiddersDetail::acceptedBidder($generalDetail->id);

        if(!$acceptedBidderData){
            $returnData['msg'] = "Oops! Something went wrong!";
            return response()->json($returnData);
        }

        //----------------------------- CHECK DOCUMENTS TOTAL ----------------------------------------------------------

        $checkDocsUploaded= OsrNonTaxBiddingAttachmentUpload::checkDocsUploaded($generalDetail->id);

        if(!$checkDocsUploaded){
            $returnData['msg'] = "Please upload all attachments before final submit.";
            return response()->json($returnData);
        }

        DB::beginTransaction();
        try{
            $insertData= new OsrNonTaxBiddingSettlementDetail();
            $insertData->osr_non_tax_bidding_general_detail_id=$generalDetail->id;
            $insertData->osr_non_tax_bidding_bidders_detail_id=$acceptedBidderData->bidding_bidder_id;
            $insertData->osr_non_tax_bidder_entry_id=$acceptedBidderData->id;
            $insertData->work_order_no=$request->input('work_order_no');
            $insertData->managed_by=$request->input('managed_by');
            $insertData->created_by=$users->employee_code;

            if(!$insertData->save()){
                DB::rollback();
                $returnData['msg'] = "Oops! Something went wrong!";
                return response()->json($returnData);
            }

            OsrNonTaxBiddingGeneralDetail::where([
                ['id', '=', $generalDetail->id],
            ])->update(['stage'=>3, 'updated_by'=>$users->employee_code, 'updated_at'=>Carbon::now()]);


        }catch(\Exception $e){
            DB::rollback();
            $returnData['msg'] = "Server Exception!";
            return response()->json($returnData);
        }
        DB::commit();
        $returnData['msgType']=true;
        $returnData['msg'] = "Successfully final submitted!";
        $returnData['data']=[];
        return response()->json($returnData);
    }
    /************************************ UPLOAD REPORT ****************************************************************/
    public function report_upload(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $cur_year=Carbon::now()->format('Y');
        $attachment_path=NULL;

        //---------------------VALIDATION---------------------------------------------

        $messages =[
            'upload_report.required' => 'This is required!',
            'upload_report.mimes' => 'Document must be in pdf format.',
            'upload_report.min' => 'Document size must not be less than 10 KB.',
            'upload_report.max' => 'Document size must not exceed 200 KB.',
        ];

        $validatorArray=[
            'upload_report' => 'required|mimes:pdf|max:200|min:10',
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);

        }

        //---------------------VALIDATION ENDED----------------------------------------

        $users=$request->session()->get('users');
        $zpData = District::getZilaByDistrictId($users->district_code);

        if(!$zpData){
            $returnData['msg'] = "Opps! Something went wrong!2";
            return response()->json($returnData);
        }

        $asset_id = $request->input('asset_id');
        $fy_year_id = $request->input('fy_yr_id');


        $generalDetail= OsrNonTaxBiddingGeneralDetail::getEntryByIdAndFyYr($asset_id, $fy_year_id);
        if(!$generalDetail){
            $returnData['msg'] = "Oops! Something went wrong!";
            return response()->json($returnData);
        }

        //----------ZILA MISMATCH ----------------------------------
        $checkMisMatch=OsrNonTaxAssetEntry::checkZilaMismatch($zpData->id, $asset_id);

        if(!$checkMisMatch['msgType']){
            $returnData['msg'] = $checkMisMatch['msg'];
            return response()->json($returnData);
        }
        //----------ZILA MISMATCH ENDED------------------------------

        if ($request->file('upload_report')) {
            $attachment_path = $request->file('upload_report')->store('osr/non_tax/asset/upload_report/'.preg_replace('/\s+/', '_', $zpData->zila_parishad_name).'/'.$cur_year.'/'.$asset_id);
        }
        else{
            $returnData['msg'] = "Upload attachment.";
            return response()->json($returnData);
        }

        $updateData=OsrNonTaxBiddingSettlementDetail::where([
            ['osr_non_tax_bidding_general_detail_id', '=', $generalDetail->id],
        ])->update([
            'final_report_path'=>$attachment_path,
            'updated_by'=>$users->employee_code,
            'updated_at'=>Carbon::now(),
        ]);

        $returnData['msgType'] = true;
        $returnData['data'] = ['attachment_path'=>$attachment_path];
        $returnData['msg'] = "Successfully uploaded";
        return response()->json($returnData);
    }



}
