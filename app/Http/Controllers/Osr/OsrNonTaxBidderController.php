<?php

namespace App\Http\Controllers\Osr;

use App\CommonModels\District;
use App\ConfigMdas;
use App\Osr\OsrNonTaxAssetEntry;
use App\Osr\OsrNonTaxAssetShortlist;
use App\Osr\OsrNonTaxBiddingBiddersDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Osr\OsrNonTaxBidderAttachmentUpload;
use App\Osr\OsrNonTaxBidderAttachment;
use App\Osr\OsrNonTaxBidderEntry;
use App\Osr\OsrNonTaxBiddingGeneralDetail;

use Illuminate\Support\Facades\Auth;
use Validator;
use DB;


class OsrNonTaxBidderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'user_mdas']);
    }

    /************************************* SAVE BIDDER ****************************************************************/

    public function bidder_save (Request $request) {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $asset_code=$request->input('asset_code');
        $fy_id=$request->input('fy_id');

        $osr_pic_path = NULL;
        $osr_pan_path = NULL;
        $pan_no = $request->input('b_pan_no');
        $mobile_no = $request->input('b_mobile');
        $b_bidding_amt = doubleval(preg_replace('/[^\d.]/', '', $request->input('b_bidding_amt')));
        $b_ernest_amt = doubleval(preg_replace('/[^\d.]/', '', $request->input('b_ernest_amt')));

        $users=Auth::user();
        if($users->mdas_master_role_id==2){//ZP ADMIN
            $level="ZP";
            $id=$users->zp_id;
        }elseif($users->mdas_master_role_id==3){//AP ADMIN
            $level="AP";
            $id=$users->ap_id;
        }elseif($users->mdas_master_role_id==4){//GP ADMIN
            $level="GP";
            $id=$users->gp_id;
        }else{
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }

        $checkIsInShortlist= OsrNonTaxAssetShortlist::isInShortlist($asset_code, $fy_id, $level, $id);

        if(!$checkIsInShortlist){
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }

        //---------VALIDATION-----------------
		/*'b_mobile.required' => 'This is required.',
            'b_mobile.numeric' => 'This must be numeric value.',
            'b_mobile.min' => 'Invalid data.',
            'b_mobile.max' => 'This must not exceed 10 digits.',
            'b_mobile.digits' => 'This must not exceed 10 digits.',*/

        $messages = [
            'b_pic.mimes' => 'Image must be in jpeg, jpg and png format.',
            'b_pic.min' => 'Image size must not be less than 10 KB.',
            'b_pic.max' => 'Image size must not exceed 100 KB.',

            'b_pan_pic.mimes' => 'Image must be in jpeg, jpg and png format.',
            'b_pan_pic.min' => 'Image size must not be less than 10 KB.',
            'b_pan_pic.max' => 'Image size must not exceed 100 KB.',

            'b_f_name.required' => 'This is required.',
            'b_f_name.max' => 'Maximum 100 characters allowed.',

            'b_m_name.max' => 'Maximum 100 characters allowed.',

            'b_l_name.required' => 'This is required.',
            'b_l_name.max' => 'Maximum 100 characters allowed.',
				
			
            

            'b_alt_mobile.numeric' => 'This must be numeric value.',
            'b_alt_mobile.min' => 'Invalid data.',
            'b_alt_mobile.max' => 'This must not exceed 10 digits.',
            'b_alt_mobile.digits' => 'This must not exceed 10 digits.',

            'b_email.max' => 'Maximum 150 characters allowed.',
            'b_email.email' => 'Invalid email ID.',

            'b_gender_id.required' => 'This is required.',
            'b_gender_id.exists' => 'Invalid data.',

            'b_caste_id.required' => 'This is required.',
            'b_caste_id.exists' => 'Invalid data.',

            'b_pan_no.required' => 'This is required',

            'b_gst_no.max' => 'Maximum 15 characters allowed.',
            'b_gst_no.min' => 'Minimum 15 characters required.',

            'b_addr.required' => 'This is required.',
            'b_addr.string' => 'Invalid data.',
            'b_addr.max' => 'Maximum 200 characters allowed.',

            'b_p_station.required' => 'This is required.',
            'b_p_station.max' => 'Maximum 100 characters allowed.',

            'b_pin.required' => 'This is required.',
            'b_pin.min' => 'Invalid pin.',
            'b_pin.max' => 'Invalid pin.',

            'b_status.required' => 'This is required.',
            'b_status.in' => 'Invalid data.',

            'b_remark_id.required_if' => 'This is required.',
            'b_remark_id.exists' => 'Invalid data.',

        ];

        $validatorArray=[
            'b_pic' => 'image|mimes:jpg,jpeg,png|max:100|min:10|nullable',
            'b_pan_pic' => 'image|mimes:jpg,jpeg,png|max:100|min:10|nullable',

            'b_f_name' => 'required|string|max:100',
            'b_m_name' => 'string|max:100|nullable',
            'b_l_name' => 'required|string|max:100',
            'b_father_name' => 'required|string|max:150',

            
            'b_alt_mobile' => 'numeric|min:6000000000|max:9999999999|digits:10|nullable',

            'b_email' => 'email|max:150|nullable',
            'b_gender_id' => 'required|exists:master_genders,id',

            'b_caste_id' => 'required|exists:master_castes,id',

            'b_pan_no' => 'required',
            'b_gst_no' => 'string|max:15|min:15|nullable',

            'b_addr' => 'required|string|max:200',
            'b_p_station' => 'required|string|max:100',
            'b_pin' => 'required|numeric|min:600000|max:999999',

            'b_status' => 'required|in:0,1,2', //0 => Rejected //1 => Accepted
            'b_remark_id' => 'required_if:b_status,==,0|exists:osr_master_bidder_remarks,id|nullable',

            'b_bidding_amt' => [
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
            'b_ernest_amt' => [
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
            /*'b_security_amt' => [
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
            ],*/
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);
        }

        //---------VALIDATION ENDED-----------------

        $generalDetailData=OsrNonTaxBiddingGeneralDetail::getEntryByCodeAndFyYr($asset_code, $fy_id);

        if(!$generalDetailData){
            $returnData['msg'] = "Opps! Something went wrong#2.";
            return response()->json($returnData);
        }

        if($generalDetailData->stage==3){
            $returnData['msg'] = "Final submit is already done. Kindly refresh";
            return response()->json($returnData);
        }

        /*if(!OsrNonTaxBidderEntry::isBidderAlreadyAdded($generalDetailData->id, $pan_no, $mobile_no)){
            $returnData['msg'] = "Mobile / PAN Number already added to this asset";
            return response()->json($returnData);
        }*/

        if(!OsrNonTaxBiddingBiddersDetail::isAcceptedBidder($generalDetailData->id, $request->input('b_status'))){
            $returnData['msg'] = "A bidder is already accepted. You can not accept more than one bidder.";
            return response()->json($returnData);
        }

        if ($request->file('b_pic')) {
            $osr_pic_path = $request->file('b_pic')->store('osr/non_tax_asset/bidder/photo/'.$id.'/'.$fy_id);
        }

        if ($request->file('b_pan_pic')) {
            $osr_pan_path = $request->file('b_pan_pic')->store('osr/non_tax_asset/bidder/pan/'.$id.'/'.$fy_id);
        }

        DB::beginTransaction();
        try {

            $osrBidderEntry= new OsrNonTaxBidderEntry();
            $osrBidderEntry->b_pic_path = $osr_pic_path;
            $osrBidderEntry->b_pan_path = $osr_pan_path;

            $osrBidderEntry->b_f_name = strtoupper($request->input('b_f_name'));
            $osrBidderEntry->b_m_name = strtoupper($request->input('b_m_name'));
            $osrBidderEntry->b_l_name = strtoupper($request->input('b_l_name'));

            $osrBidderEntry->b_father_name = strtoupper($request->input('b_father_name'));

            $osrBidderEntry->b_mobile = $mobile_no;
            $osrBidderEntry->b_email = $request->input('b_email');
            $osrBidderEntry->b_alt_mobile = $request->input('b_alt_mobile');

            $osrBidderEntry->b_gender_id = $request->input('b_gender_id');
            $osrBidderEntry->b_caste_id = $request->input('b_caste_id');
            $osrBidderEntry->b_pan_no = strtoupper($pan_no);
            $osrBidderEntry->b_gst_no = strtoupper($request->input('b_gst_no'));
            $osrBidderEntry->b_aadhaar_no = strtoupper($request->input('b_aadhaar_no'));

            $osrBidderEntry->b_address = $request->input('b_addr');
            $osrBidderEntry->b_pin = $request->input('b_pin');
            $osrBidderEntry->b_police_station= $request->input('b_p_station');
            $osrBidderEntry->created_by= $users->username;

            if(!$osrBidderEntry->save()){
                DB::rollback();
                $returnData['msg'] = "Opps! Something went wrong#3.";
                return response()->json($returnData);
            }

            $b_status=$request->input('b_status');
            $b_remark_id=NULL;
            $b_other_remark=NULL;
            if($b_status==0){
               $b_remark_id=$request->input('b_remark_id');
               /*if($b_remark_id==1){
                  $b_other_remark=$request->input('b_other_remark');
               }*/
            }

            $osrBiddingBidder= new OsrNonTaxBiddingBiddersDetail();
            $osrBiddingBidder->osr_non_tax_bidding_general_detail_id=$generalDetailData->id;
            $osrBiddingBidder->osr_master_bidder_entry_id=$osrBidderEntry->id;
            $osrBiddingBidder->bidding_amt=$b_bidding_amt;
            $osrBiddingBidder->ernest_amt=$b_ernest_amt;
            $osrBiddingBidder->bidder_status=$b_status;
            $osrBiddingBidder->osr_master_bidder_remark_id=$b_remark_id;
            $osrBiddingBidder->created_by= $users->username;

            if(!$osrBiddingBidder->save()){
                DB::rollback();
                $returnData['msg'] = "Opps! Something went wrong#4.";
                return response()->json($returnData);
            }

        }catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng#5".$e->getMessage();
            return $returnData;
        }

        DB::commit();

        $returnData['msg'] = "Data Submitted";
        $returnData['msgType']=true;
        $returnData['data']=[];
        return response()->json($returnData);

    }

    /************************************* GET BIDDER BY ID ***********************************************************/

    public function bidderGetById(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $bid=$request->input('bid');

        $BidderData=OsrNonTaxBiddingBiddersDetail::getBidderById($bid);

        if(!$BidderData){
            $returnData['msg'] = "Unauthorised access. Please contact admin for more details";
            return response()->json($returnData);
        }

        $imgUrl=ConfigMdas::allActiveList()->imgUrl;

        $returnData['msgType'] = true;
        $returnData['data'] = ['BidderData'=>$BidderData,'imgUrl'=>$imgUrl];
        $returnData['msg'] = "Success";
        return response()->json($returnData);
    }
    
    /************************************* GET BIDDER ATTACHMENT BY ID ***********************************************************/

    public function bidderAttachmentGetById (Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";
        
        $bid=$request->input('bid');
        
        $master_docs=OsrNonTaxBidderAttachment::all();
        
        $uploadedBidderDocs=[];
        foreach($master_docs as $doc){
            $uploaded_details=OsrNonTaxBidderAttachmentUpload::getAttachmentByBid($bid,$doc->id);
            
            $uploadedBidderDocs[]=['att_id'=>$doc->id,'att_name'=>$doc->doc_name,'uploadDetails'=>$uploaded_details];
        }
        
        
        $BidderData=OsrNonTaxBiddingBiddersDetail::getBidderById($bid);
        if(!$BidderData){
            $returnData['msg'] = "Unauthorised access. Please contact admin for more details";
            return response()->json($returnData);
        }
        
        $users = $request->session()->get('users');
        
        $returnData['msgType'] = true;
        $returnData['data'] = ['BidderData'=>$BidderData,'uploadedBidderDocs'=>$uploadedBidderDocs];
        $returnData['msg'] = "Success";
        return response()->json($returnData);
    }
   
    /*-----------------------------BIDDER DOCUMENT UPLOAD--------------------------------*/
    public function bidder_attachment_upload(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $attachment_path = NULL;

        $asset_code = $request->input('bidder_asset_code');
        $fy_id = $request->input('bidder_fy_yr_id');
        $b_id = $request->input('bidder_id');
        
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
            'attachment.max' => 'Document size must not exceed 400 KB.',
        ];

        $validatorArray = [
            'attachment' => 'required|mimes:pdf|max:400|min:10',
            'doc_no' => 'required|exists:osr_non_tax_bidder_attachments,id',
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

        if ($request->file('attachment')) {
            $attachment_path = $request->file('attachment')->store('osr/non_tax/asset/bidder/attachment/' .$b_id. '/'. $level . '/' . $id . '/' . $doc_no);
        } else {
            $returnData['msg'] = "Upload attachment.";
            return response()->json($returnData);
        }

        $alreadyExists = OsrNonTaxBidderAttachmentUpload::alreadyExist($b_id, $doc_no);

        if ($alreadyExists) {
            $updateData = OsrNonTaxBidderAttachmentUpload::where([
                ['osr_non_tax_bidder_entry_id', '=', $b_id],
                ['osr_non_tax_bidder_attachment_id', '=', $doc_no],
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
            $insertData = new OsrNonTaxBidderAttachmentUpload();
            $insertData->osr_non_tax_bidder_entry_id = $b_id;
            
            $insertData->asset_code = $asset_code;
            $insertData->osr_fy_year_id = $fy_id;
            $insertData->osr_non_tax_bidder_attachment_id = $doc_no;
            $insertData->attachment_path = $attachment_path;
            $insertData->created_by = $users->username;

            if (!$insertData->save()) {
                $returnData['msg'] = "Oops! Something went wrong!4";
                return response()->json($returnData);
            }
        }

        $imgUrl = ConfigMdas::allActiveList()->imgUrl;

        $returnData['msgType'] = true;
        $returnData['msg'] = "Uploaded successfully!";
        $returnData['data'] = ['imgUrl' => $imgUrl, 'attachment_path' => $attachment_path, 'doc_no' => $doc_no];
        return response()->json($returnData);
    }

     /************************************ EDIT BIDDER *************************************************************/
   public function bidder_edit(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $asset_code=$request->input('asset_code');
        $fy_id=$request->input('fy_id');
        $bid=$request->input('bid');
        $BidderData=OsrNonTaxBiddingBiddersDetail::getBidderById($bid);
        
        $osr_pic_path = NULL;
        $osr_pan_path = NULL;

        $ed_b_bidding_amt = doubleval(preg_replace('/[^\d.]/', '', $request->input('ed_b_bidding_amt')));
        $ed_b_ernest_amt = doubleval(preg_replace('/[^\d.]/', '', $request->input('ed_b_ernest_amt')));

        $bidder_status=$request->input('ed_b_status');

        $users=Auth::user();
        if($users->mdas_master_role_id==2){//ZP ADMIN
            $level="ZP";
            $id=$users->zp_id;
        }elseif($users->mdas_master_role_id==3){//AP ADMIN
            $level="AP";
            $id=$users->ap_id;
        }elseif($users->mdas_master_role_id==4){//GP ADMIN
            $level="GP";
            $id=$users->gp_id;
        }else{
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }

        $checkIsInShortlist= OsrNonTaxAssetShortlist::isInShortlist($asset_code, $fy_id, $level, $id);

        if(!$checkIsInShortlist){
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }

        //VALIDATION---------------------
        $messages = [
            'ed_b_pic.mimes' => 'Image must be in jpeg, jpg and png format.',
            'ed_b_pic.min' => 'Image size must not be less than 10 KB.',
            'ed_b_pic.max' => 'Image size must not exceed 100 KB.',

            /* 'ed_b_pan_pic.required' => 'This is required.',*/
            'ed_b_pan_pic.mimes' => 'Image must be in jpeg, jpg and png format.',
            'ed_b_pan_pic.min' => 'Image size must not be less than 10 KB.',
            'ed_b_pan_pic.max' => 'Image size must not exceed 100 KB.',

            'ed_b_f_name.required' => 'This is required.',
            'ed_b_f_name.max' => 'Maximum 100 characters allowed.',

            'ed_b_m_name.max' => 'Maximum 100 characters allowed.',

            'ed_b_l_name.required' => 'This is required.',
            'ed_b_l_name.max' => 'Maximum 100 characters allowed.',

            'ed_b_alt_mobile.numeric' => 'This must be numeric value.',
            'ed_b_alt_mobile.min' => 'Invalid data.',
            'ed_b_alt_mobile.max' => 'This must not exceed 10 digits.',
            'ed_b_alt_mobile.digits' => 'This must not exceed 10 digits.',

            'ed_b_email.max' => 'Maximum 150 characters allowed.',
            'ed_b_email.email' => 'Invalid email ID.',

            'ed_b_gender_id.required' => 'This is required.',
            'ed_b_gender_id.exists' => 'Invalid data.',

            'ed_b_caste_id.required' => 'This is required.',
            'ed_b_caste_id.exists' => 'Invalid data.',

            'ed_b_pan_no.required' => 'This is required',
            'ed_b_gst_no.max' => 'Maximum 15 characters allowed.',
            'ed_b_gst_no.min' => 'Minimum 15 characters required.',

            'ed_b_addr.required' => 'This is required.',
            'ed_b_addr.string' => 'Invalid data.',
            'ed_b_addr.max' => 'Maximum 200 characters allowed.',

            'ed_b_p_station.required' => 'This is required.',
            'ed_b_p_station.max' => 'Maximum 100 characters allowed.',

            'ed_b_pin.required' => 'This is required.',
            'ed_b_pin.min' => 'Invalid pin.',
            'ed_b_pin.max' => 'Invalid pin.',

            'ed_b_status.required' => 'This is required.',
            'ed_b_status.in' => 'Invalid data.',

            'ed_b_remark_id.required_if' => 'This is required.',
            'ed_b_remark_id.exists' => 'Invalid data.',

            'ed_b_other_remark.required_if' => 'This is required.',
            'ed_b_other_remark.max' => 'Maximum 150 characters allowed.',
        ];

        $validatorArray=[
            'ed_b_pic' => 'image|mimes:jpg,jpeg,png|max:100|min:10|nullable',
            'ed_b_pan_pic' => 'image|mimes:jpg,jpeg,png|max:100|min:10',

            'ed_b_f_name' => 'required|string|max:100',
            'ed_b_m_name' => 'string|max:100|nullable',
            'ed_b_l_name' => 'required|string|max:100',
            'ed_b_father_name' => 'required|string|max:150',

            'ed_b_alt_mobile' => 'numeric|min:6000000000|max:9999999999|digits:10|nullable',

            'ed_b_email' => 'email|max:150|nullable',
            'ed_b_gender_id' => 'required|exists:master_genders,id',

            'ed_b_caste_id' => 'required|exists:master_castes,id',

            'ed_b_pan_no' => 'required',
            'ed_b_gst_no' => 'string|max:15|min:15|nullable',

            'ed_b_addr' => 'required|string|max:200',
            'ed_b_p_station' => 'required|string|max:100',
            'ed_b_pin' => 'required|numeric|min:600000|max:999999',

            'ed_b_status' => 'required|in:0,1,2', //0 => Rejected //1 => Accepted
            'ed_b_remark_id' => 'required_if:b_status,==,0|exists:osr_master_bidder_remarks,id|nullable',

            'ed_b_other_remark' => 'required_if:b_remark_id,==,1|string|max:150|nullable',

            'ed_b_bidding_amt' => [
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
            'ed_b_ernest_amt' => [
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
            /*'ed_b_security_amt' => [
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
            ],*/
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);
        }

        //VALIDATIO ENDED---------------------------------


        $generalDetailData=OsrNonTaxBiddingGeneralDetail::getEntryByCodeAndFyYr($asset_code, $fy_id);
        $bidderData=OsrNonTaxBiddingGeneralDetail::getBidderDataByBidderEntryId($bid);

        if(!$generalDetailData || !$bidderData){
            $returnData['msg'] = "Opps! Something went wrong.";
            return response()->json($returnData);
        }

        if($generalDetailData->id <> $bidderData->osr_non_tax_bidding_general_detail_id){
            $returnData['msg'] = "Opps! Something went wrong.";
            return response()->json($returnData);
        }

        if($generalDetailData->stage==3){
            $returnData['msg'] = "Final submit is already done. Kindly refresh";
            return response()->json($returnData);
        }

        if(!OsrNonTaxBiddingBiddersDetail::isAcceptedBidder($generalDetailData->id, $request->input('ed_b_status'))){
            if($bidderData->bidder_status <> 1){
                $returnData['msg'] = "A bidder is already accepted. You can not accept more than one bidder.";
                return response()->json($returnData);
            }
        }

        $updateArray = [
            'b_f_name' => strtoupper($request->input('ed_b_f_name')),
            'b_m_name' => strtoupper($request->input('ed_b_m_name')),
            'b_l_name' => strtoupper($request->input('ed_b_l_name')),
            'b_father_name' => strtoupper($request->input('ed_b_father_name')),
            'b_mobile' => $request->input('ed_b_mobile'),
            'b_email' => $request->input('ed_b_email'),
            'b_alt_mobile' => $request->input('ed_b_alt_mobile'),
            'b_gender_id' => $request->input('ed_b_gender_id'),
            'b_caste_id' => $request->input('ed_b_caste_id'),
            'b_pan_no' =>  strtoupper($request->input('ed_b_pan_no')),
            'b_gst_no' =>  strtoupper($request->input('ed_b_gst_no')),
            'b_aadhaar_no' =>  strtoupper($request->input('ed_b_aadhaar_no')),
            'b_address' => $request->input('ed_b_addr'),
            'b_pin' =>  $request->input('ed_b_pin'),
            'b_police_station' =>  $request->input('ed_b_p_station'),
            'updated_by' =>  $users->employee_code,
            'updated_at' => Carbon::now(),
        ];


        if ($request->file('ed_b_pic')) {
            $osr_pic_path = $request->file('ed_b_pic')->store('osr/non_tax_asset/bidder/photo/'.preg_replace('/\s+/', '_', $id).'/'.$fy_id);
            $updateArray['b_pic_path']=$osr_pic_path;
        }

        if ($request->file('ed_b_pan_pic')) {
            
            if( $BidderData->b_pan_path != NULL || $BidderData->b_pan_path != "") {
                                $file = fopen(storage_path('app/'.$BidderData->b_pan_path), 'w') or die("can't open file");
                                fclose($file);
                                unlink(storage_path('app/'.$BidderData->b_pan_path));
                            }
            $osr_pan_path = $request->file('ed_b_pan_pic')->store('osr/non_tax_asset/bidder/pan/'.preg_replace('/\s+/', '_', $id).'/'.$fy_id);
            $updateArray['b_pan_path']=$osr_pan_path;
        }


        DB::beginTransaction();
        try{
            $updateBidderEntryData = OsrNonTaxBidderEntry::where('id', $bid)
                ->update($updateArray);

            $updateGeneralEntryData= OsrNonTaxBiddingGeneralDetail::where('id', $generalDetailData->id)
                ->update(['stage' =>1]);

            $updateBiddingBidder = OsrNonTaxBiddingBiddersDetail::where('osr_master_bidder_entry_id', $bid)
                ->update([
                    'bidding_amt' =>$ed_b_bidding_amt,
                    'ernest_amt' =>$ed_b_ernest_amt,
                    'bidder_status' => $request->input('ed_b_status'),
                    'osr_master_bidder_remark_id' => $request->input('ed_b_remark_id'),
                    'updated_by' => $users->employee_code,
                    'updated_at' => Carbon::now(),

                ]);

            if(!$updateBidderEntryData || !$updateBiddingBidder){
                DB::rollback();
                $returnData['msg'] = "Oops! Something went wrong!";
                return response()->json($returnData);
            }
        }catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng";
            return $returnData;
        }

        DB::commit();

        $returnData['msgType'] = true;
        $returnData['data'] = [];
        $returnData['msg'] = "Bidder details successfully updated";
        return response()->json($returnData);
    }
    
   
}
