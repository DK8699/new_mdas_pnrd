<?php

namespace App\Http\Controllers\Osr;

use App\CommonModels\AnchalikParishad;
use App\CommonModels\District;
use App\CommonModels\GramPanchyat;
use App\ConfigMdas;
use App\Osr\OsrMasterFyYear;
use App\Osr\OsrNonTaxMasterAssetCategory;
use App\Osr\OsrNonTaxOtherAssetAgreement;
use App\Osr\OsrNonTaxOtherAssetAgreementInstalment;
use App\Osr\OsrNonTaxOtherAssetCollection;
use App\Osr\OsrNonTaxOtherAssetDisApShare;
use App\Osr\OsrNonTaxOtherAssetDisGpShare;
use App\Osr\OsrNonTaxOtherAssetDisZpShare;
use App\Osr\OsrNonTaxOtherAssetEntry;
use App\Osr\OsrNonTaxOtherAssetFinalRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Validator;
use DB;

class OsrNonTaxOtherAssetPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'user_mdas']);
    }

    public function index(Request $request, $asset_code, $osr_fy_year_id){
        $categories= OsrNonTaxMasterAssetCategory::all();

        $asset_code=base64_decode(base64_decode(base64_decode($asset_code)));
        $osr_fy_year_id=base64_decode(base64_decode(base64_decode($osr_fy_year_id)));

        $assetData=OsrNonTaxOtherAssetEntry::getByCode($asset_code);
        $osrFyYearData=OsrMasterFyYear::getFyYear($osr_fy_year_id);

        if(!$assetData || !$osrFyYearData){
            return redirect(route('osr.non_tax.dw_asset.other_assets'));
        }

        $catData=OsrNonTaxMasterAssetCategory::getById($assetData->osr_non_tax_master_asset_category_id);

        if(!$catData){
            return redirect(route('osr.non_tax.dw_asset.other_assets'));
        }

        $osrFyYears= OsrMasterFyYear::getAllYears();

        $agList=OsrNonTaxOtherAssetAgreement::getByAssetCodeAndFy($asset_code, $osr_fy_year_id);

        $finalAg=[];

        foreach($agList AS $li){
            $insList= OsrNonTaxOtherAssetAgreementInstalment::getInstalments($asset_code, $osr_fy_year_id, $li->id);

           $data=[
               "agList"=>$li,
               "insList"=>$insList,
           ];

           array_push($finalAg, $data);
        }

        $colList=OsrNonTaxOtherAssetCollection::getCollections($asset_code, $osr_fy_year_id);

        $imgUrl=ConfigMdas::allActiveList()->imgUrl;

        $max_fy_id=OsrMasterFyYear::getMaxFyYear();
        $data=[
            'fy_id'=>$max_fy_id
        ];

        return view('Osr.non_tax.dw_other_track_payment', compact('data', 'categories', 'catData', 'assetData', 'osrFyYearData', 'osrFyYears', 'finalAg', 'colList', 'imgUrl'));
    }

    public function save_amount(Request $request){

        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $other_asset_code=$request->input('other_asset_code');
        $fy_id=$request->input('osr_fy_year_id');

        $other_asset_code=decrypt($other_asset_code);
        $fy_id=decrypt($fy_id);

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

        $checkIsInShortlist = OsrNonTaxOtherAssetEntry::checkOtherAssetEntry($other_asset_code, $fy_id, $level, $id);

        if (!$checkIsInShortlist) {
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }

        //---------------------- VALIDATION ----------------------------------------------------------------------------

        $messages = [
            's_from.required' => 'This field is required',
            's_from.date_format' => 'Invalid data',

            's_to.required' => 'This field is required',
            's_to.date_format' => 'Invalid data',

            's_on.required' => 'This field is required',
            's_on.date_format' => 'Invalid data',

            's_amt.required' => 'This field is required',
            's_amt.numeric' => 'Must be a numeric value',
            's_amt.min' => 'Invalid amount',
            's_amt.max' => 'Invalid amount',

            /*'s_zp_share.required' => 'This field is required',
            's_zp_share.numeric' => 'Must be a numeric value',
            's_zp_share.min' => 'Invalid amount',
            's_zp_share.max' => 'Invalid amount',

            's_ap_share.required' => 'This field is required',
            's_ap_share.numeric' => 'Must be a numeric value',
            's_ap_share.min' => 'Invalid amount',
            's_ap_share.max' => 'Invalid amount',

            's_gp_share.required' => 'This field is required',
            's_gp_share.numeric' => 'Must be a numeric value',
            's_gp_share.min' => 'Invalid amount',
            's_gp_share.max' => 'Invalid amount',*/

            's_receipt_no.required' => 'This field is required',
            's_receipt_no.min' => 'Invalid data',
            's_receipt_no.max' => 'Invalid data',

            's_r_from.required' => 'This field is required',
            's_r_from.max' => 'Characters must not exceed 150 characters',

            's_remarks.max' => 'Characters must not exceed 150 characters',
        ];

        $validatorArray = [
            's_from' => 'required|date_format:Y-m-d',
            's_to' => 'required|date_format:Y-m-d',

            's_on' => 'required|date_format:Y-m-d',

            's_amt' => 'required|numeric|min:0|max:9999999999',

            /*'s_zp_share' => 'required|numeric|min:0|max:9999999999',
            's_ap_share' => 'required|numeric|min:0|max:9999999999',
            's_gp_share' => 'required|numeric|min:0|max:9999999999',*/

            's_receipt_no' => 'string|min:1|max:50|nullable',
            's_r_from' => 'string|max:150|nullable',

            's_remarks' => 'max:150|nullable',
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);
        }


        $receipt_amt=$request->input('s_amt');
        /*$zp_share= $request->input('s_zp_share');
        $ap_share= $request->input('s_ap_share');
        $gp_share= $request->input('s_gp_share');*/
		
		if($level=="ZP"){
            $zp_share= $receipt_amt;//$request->input('s_zp_share');
            $ap_share= 0;//$request->input('s_ap_share');
            $gp_share= 0;//$request->input('s_gp_share');
        }elseif ($level=="AP"){
            $zp_share= 0;//$request->input('s_zp_share');
            $ap_share= $receipt_amt;//$request->input('s_ap_share');
            $gp_share= 0;//$request->input('s_gp_share');
        }else{
            $zp_share= 0;//$request->input('s_zp_share');
            $ap_share= 0;//$request->input('s_ap_share');
            $gp_share= $receipt_amt;//$request->input('s_gp_share');
        }


        $otherAssetData=OsrNonTaxOtherAssetEntry::getByCode($other_asset_code);

        if(!$otherAssetData){
            return response()->json($returnData);
        }

        if($receipt_amt <> ($zp_share+$ap_share+$gp_share)){
            $returnData['msg'] = "Receipt amount must be equal to the sum of the shares of ZP, AP and GP combined.";
            return response()->json($returnData);
        }

        //---------------------- END VALIDATION ------------------------------------------------------------------------
        DB::beginTransaction();
        try{

            $agSave= new OsrNonTaxOtherAssetCollection();
            $agSave->other_asset_code= $other_asset_code;
            $agSave->osr_master_fy_year_id= $fy_id;

            $agSave->s_from= $request->input('s_from');
            $agSave->s_to= $request->input('s_to');

            $agSave->s_on= $request->input('s_on');

            $agSave->collected_amt= $receipt_amt;

            $agSave->receipt_no= $request->input('s_receipt_no');

            $agSave->amt_received_from= $request->input('s_r_from');

            $agSave->zp_share= $zp_share;
            $agSave->ap_share= $ap_share;
            $agSave->gp_share= $gp_share;

            $agSave->sharing_remark= $request->input('s_remarks');
            $agSave->created_by= $users->username;

            if (!$agSave->save()) {
                return response()->json($returnData);
            }

            $finalRecordCount=OsrNonTaxOtherAssetFinalRecord::finalRecordCount($other_asset_code, $fy_id);

            if($finalRecordCount==0){
                $finalRecordNew= new OsrNonTaxOtherAssetFinalRecord();

                $finalRecordNew->other_asset_code=$other_asset_code;
                $finalRecordNew->fy_id=$fy_id;
                $finalRecordNew->self_collection_status=1;
                $finalRecordNew->tot_self_collected_amt=$receipt_amt;
                $finalRecordNew->tot_self_zp_share=$zp_share;
                $finalRecordNew->tot_self_ap_share=$ap_share;
                $finalRecordNew->tot_self_gp_share=$gp_share;
                $finalRecordNew->created_by=$users->username;

                if(!$finalRecordNew->save()){
                    DB::rollback();
                    return response()->json($returnData);
                }

            }elseif($finalRecordCount==1){

                $finalRecordData=OsrNonTaxOtherAssetFinalRecord::getFinalRecord($other_asset_code, $fy_id);

                $tot_self_collected_amt=$receipt_amt+$finalRecordData->tot_self_collected_amt;
                $tot_self_zp_share=$zp_share+$finalRecordData->tot_self_zp_share;
                $tot_self_ap_share=$ap_share+$finalRecordData->tot_self_ap_share;
                $tot_self_gp_share=$gp_share+$finalRecordData->tot_self_gp_share;

                $finalRecordUpdate= OsrNonTaxOtherAssetFinalRecord::where([
                    ['other_asset_code', '=', $other_asset_code],
                    ['fy_id', '=', $fy_id],
                ])->update([
                    'self_collection_status'=> 1, 'tot_self_collected_amt'=> $tot_self_collected_amt,
                    'tot_self_zp_share'=> $tot_self_zp_share,'tot_self_ap_share'=> $tot_self_ap_share,
                    'tot_self_gp_share'=> $tot_self_gp_share
                ]);

                if(!$finalRecordUpdate){
                    DB::rollback();
                    return response()->json($returnData);
                }

            }else{
                DB::rollback();
                return response()->json($returnData);
            }

            $flag_id=$agSave->id;
            $flag="S";

            $share_dis= $this->share_distribution_new($flag, $flag_id, $users, $level, $otherAssetData, $fy_id, $receipt_amt, $zp_share, $ap_share, $gp_share);

            if(!$share_dis){
                DB::rollback();
                return response()->json($returnData);
            }


        }catch(\Exception $e){
            DB::rollback();
            $returnData['msg'] = "Server Exception.".$e->getMessage();
            return response()->json($returnData);
        }
        DB::commit();

        $returnData['msgType'] = true;
        $returnData['data'] = [];
        $returnData['msg'] = "Successfully save the collection.";
        return response()->json($returnData);
    }

    public function save_agreement(Request $request){

        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $other_asset_code=$request->input('other_asset_code');
        $fy_id=$request->input('osr_fy_year_id');

        $other_asset_code=decrypt($other_asset_code);
        $fy_id=decrypt($fy_id);

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

        $checkIsInShortlist = OsrNonTaxOtherAssetEntry::checkOtherAssetEntry($other_asset_code, $fy_id, $level, $id);

        if (!$checkIsInShortlist) {
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }

        $cur_year=Carbon::now()->format('Y');

        $agreement_path=NULL;


        //---------------------- VALIDATION ----------------------------------------------------------------------------

        $messages = [
            'ag_with.required' => 'This field is required',
            'ag_with.in' => 'Invalid data',

            'ag_name.required' => 'This field is required',
            'ag_name.max' => 'Characters must not exceed 150 characters',

            'ag_mobile_no.required' => 'This field is required',
            'ag_mobile_no.max' => 'Invalid data',

            'ag_email_id.required' => 'This field is required',
            'ag_email_id.email' => 'Must be an valid email',
            'ag_email_id.max' => 'Email must not exceed 150 characters',

            'ag_pan_no.required_if' => 'This field is required',
            'ag_pan_no.min' => 'Invalid data',
            'ag_pan_no.max' => 'Invalid data',

            'ag_gst.required_if' => 'This field is required',
            'ag_gst.min' => 'Invalid data',
            'ag_gst.max' => 'Invalid data',

            'ag_letter.required' => 'This field is required',
            'ag_letter.mimes' => 'Must be a pdf format only.',
            'ag_letter.min' => 'Must be greater than 10 KB.',
            'ag_letter.max' => 'Must not exceed 200 KB.',

            'ag_amt.required' => 'This field is required',
            'ag_amt.min' => 'Invalid amount',
            'ag_amt.max' => 'Invalid amount',

            'ag_from.required' => 'This field is required',
            'ag_from.date_format' => 'Invalid data',

            'ag_to.required' => 'This field is required',
            'ag_to.date_format' => 'Invalid data',

            'ag_remarks.max' => 'Characters must not exceed 150 characters',
        ];

        $validatorArray = [
            'ag_with' => 'required|in:I,O',
            'ag_name' => 'required|max:150',
            'ag_mobile_no' => 'required|max:9999999999',
            'ag_email_id' => 'email|max:150|nullable',
            'ag_pan_no' => 'required_if:ag_with,I|min:10|max:10|nullable',

            'ag_gst' => 'required_if:ag_with,O|min:10|max:15|nullable',
            'ag_letter' => 'required|mimes:pdf|max:200|min:10',
            'ag_amt' => 'required|min:0|max:9999999999',
            'ag_from' => 'required|date_format:Y-m-d',
            'ag_to' => 'required|date_format:Y-m-d',

            'ag_remarks' => 'max:150|nullable',
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);
        }


        //---------------------- END VALIDATION ------------------------------------------------------------------------

        if ($request->file('ag_letter')) {
            $agreement_path = $request->file('ag_letter')->store('osr/non_tax_other_asset/agreement/'.$level.'/'.$id.'/'.$fy_id);
        }

        $agSave= new OsrNonTaxOtherAssetAgreement();
        $agSave->other_asset_code= $other_asset_code;
        $agSave->osr_master_fy_year_id= $fy_id;
        $agSave->agreement_with= $request->input('ag_with');
        $agSave->name= $request->input('ag_name');
        $agSave->mobile_no= $request->input('ag_mobile_no');
        $agSave->email_id= $request->input('ag_email_id');
        $agSave->pan_no= $request->input('ag_pan_no');
        $agSave->gst= $request->input('ag_gst');
        $agSave->agreement_path= $agreement_path;
        $agSave->agreement_amt= $request->input('ag_amt');
        $agSave->agreement_from= $request->input('ag_from');
        $agSave->agreement_to= $request->input('ag_to');
        $agSave->remarks= $request->input('ag_remarks');
        $agSave->created_by= $users->username;

        if (!$agSave->save()) {
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = [];
        $returnData['msg'] = "Successfully save the agreement.";
        return response()->json($returnData);
    }

    public function save_agreement_instalment(Request $request){

        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $other_asset_code=$request->input('other_asset_code');
        $fy_id=$request->input('osr_fy_year_id');

        $other_asset_code=decrypt($other_asset_code);
        $fy_id=decrypt($fy_id);

        $ag_id=$request->input('ag_id');

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

        $checkIsInShortlist = OsrNonTaxOtherAssetEntry::checkOtherAssetEntry($other_asset_code, $fy_id, $level, $id);

        if (!$checkIsInShortlist) {
            $returnData['msg'] = "Access Denied!";
            return response()->json($returnData);
        }

        //---------------------- VALIDATION ----------------------------------------------------------------------------

        $messages = [
            'ag_ins_from.required' => 'This field is required',
            'ag_ins_from.date_format' => 'Invalid data',

            'ag_ins_to.required' => 'This field is required',
            'ag_ins_to.date_format' => 'Invalid data',

            'ag_ins_on.required' => 'This field is required',
            'ag_ins_on.date_format' => 'Invalid data',

            'ag_ins_paid.required' => 'This field is required',
            'ag_ins_paid.numeric' => 'Must be a numeric value',
            'ag_ins_paid.min' => 'Invalid amount',
            'ag_ins_paid.max' => 'Invalid amount',

            /*'ag_ins_zp_share.required' => 'This field is required',
            'ag_ins_zp_share.numeric' => 'Must be a numeric value',
            'ag_ins_zp_share.min' => 'Invalid amount',
            'ag_ins_zp_share.max' => 'Invalid amount',

            'ag_ins_ap_share.required' => 'This field is required',
            'ag_ins_ap_share.numeric' => 'Must be a numeric value',
            'ag_ins_ap_share.min' => 'Invalid amount',
            'ag_ins_ap_share.max' => 'Invalid amount',

            'ag_ins_gp_share.required' => 'This field is required',
            'ag_ins_gp_share.numeric' => 'Must be a numeric value',
            'ag_ins_gp_share.min' => 'Invalid amount',
            'ag_ins_gp_share.max' => 'Invalid amount',*/

            'ag_ins_receipt_no.required' => 'This field is required',
            'ag_ins_receipt_no.min' => 'Invalid data',
            'ag_ins_receipt_no.max' => 'Invalid data',

            'ag_ins_remarks.max' => 'Characters must not exceed 150 characters',
        ];

        $validatorArray = [
            'ag_ins_from' => 'required|date_format:Y-m-d',
            'ag_ins_to' => 'required|date_format:Y-m-d',

            'ag_ins_on' => 'required|date_format:Y-m-d',

            'ag_ins_paid' => 'required|numeric|min:0|max:9999999999',

            /*'ag_ins_zp_share' => 'required|numeric|min:0|max:9999999999',
            'ag_ins_ap_share' => 'required|numeric|min:0|max:9999999999',
            'ag_ins_gp_share' => 'required|numeric|min:0|max:9999999999',*/

            'ag_ins_receipt_no' => 'string|min:1|max:50|nullable',

            'ag_ins_remarks' => 'max:150|nullable',
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);
        }

        if(OsrNonTaxOtherAssetAgreement::getByAssetCodeAndFyAndId($other_asset_code, $fy_id, $ag_id)<>1){
            return response()->json($returnData);
        }

        $otherAssetData=OsrNonTaxOtherAssetEntry::getByCode($other_asset_code);

        if(!$otherAssetData){
            return response()->json($returnData);
        }

        $receipt_amt=$request->input('ag_ins_paid');
        /*$zp_share= $request->input('ag_ins_zp_share');
        $ap_share= $request->input('ag_ins_ap_share');
        $gp_share= $request->input('ag_ins_gp_share');*/
		
		if($level=="ZP"){
            $zp_share= $receipt_amt; //$request->input('ag_ins_zp_share');
            $ap_share= 0; //$request->input('ag_ins_ap_share');
            $gp_share= 0; //$request->input('ag_ins_gp_share');
        }elseif ($level=="AP"){
            $zp_share= 0; //$request->input('ag_ins_zp_share');
            $ap_share= $receipt_amt; //$request->input('ag_ins_ap_share');
            $gp_share= 0; //$request->input('ag_ins_gp_share');
        }else{
            $zp_share= 0; //$request->input('ag_ins_zp_share');
            $ap_share= 0; //$request->input('ag_ins_ap_share');
            $gp_share= $receipt_amt; //$request->input('ag_ins_gp_share');
        }

        if($receipt_amt <> ($zp_share+$ap_share+$gp_share)){
            $returnData['msg'] = "Receipt amount must be equal to the sum of the shares of ZP, AP and GP combined.";
            return response()->json($returnData);
        }

        //---------------------- END VALIDATION ------------------------------------------------------------------------
        DB::beginTransaction();
        try{

            $agSave= new OsrNonTaxOtherAssetAgreementInstalment();
            $agSave->other_asset_code= $other_asset_code;
            $agSave->osr_master_fy_year_id= $fy_id;
            $agSave->osr_non_tax_other_asset_agreement_id= $ag_id;

            $agSave->instalment_from= $request->input('ag_ins_from');
            $agSave->instalment_to= $request->input('ag_ins_to');

            $agSave->instalment_on= $request->input('ag_ins_on');

            $agSave->instalment_paid= $receipt_amt;

            $agSave->receipt_no= $request->input('ag_ins_receipt_no');

            $agSave->zp_share= $zp_share;
            $agSave->ap_share= $ap_share;
            $agSave->gp_share= $gp_share;

            $agSave->sharing_remark= $request->input('ag_ins_remarks');
            $agSave->created_by= $users->username;

            if (!$agSave->save()) {
                return response()->json($returnData);
            }

            $finalRecordCount=OsrNonTaxOtherAssetFinalRecord::finalRecordCount($other_asset_code, $fy_id);

            if($finalRecordCount==0){
                $finalRecordNew= new OsrNonTaxOtherAssetFinalRecord();

                $finalRecordNew->other_asset_code=$other_asset_code;
                $finalRecordNew->fy_id=$fy_id;
                $finalRecordNew->agreement_collection_status=1;
                $finalRecordNew->agreement_count=1;
                $finalRecordNew->tot_ag_collected_amt=$receipt_amt;
                $finalRecordNew->tot_ag_zp_share=$zp_share;
                $finalRecordNew->tot_ag_ap_share=$ap_share;
                $finalRecordNew->tot_ag_gp_share=$gp_share;
                $finalRecordNew->created_by=$users->username;

                if(!$finalRecordNew->save()){
                    DB::rollback();
                    return response()->json($returnData);
                }

            }elseif($finalRecordCount==1){

                $finalRecordData=OsrNonTaxOtherAssetFinalRecord::getFinalRecord($other_asset_code, $fy_id);

                $tot_ag_collected_amt=$receipt_amt+$finalRecordData->tot_ag_collected_amt;
                $tot_ag_zp_share=$zp_share+$finalRecordData->tot_ag_zp_share;
                $tot_ag_ap_share=$ap_share+$finalRecordData->tot_ag_ap_share;
                $tot_ag_gp_share=$gp_share+$finalRecordData->tot_ag_gp_share;

                $agreement_count=1+$finalRecordData->agreement_count;

                $finalRecordUpdate= OsrNonTaxOtherAssetFinalRecord::where([
                    ['other_asset_code', '=', $other_asset_code],
                    ['fy_id', '=', $fy_id],
                ])->update([
                    'agreement_collection_status'=> 1, 'agreement_count'=> $agreement_count, 'tot_ag_collected_amt'=> $tot_ag_collected_amt,
                    'tot_ag_zp_share'=> $tot_ag_zp_share,'tot_ag_ap_share'=> $tot_ag_ap_share,
                    'tot_ag_gp_share'=> $tot_ag_gp_share
                ]);

                if(!$finalRecordUpdate){
                    DB::rollback();
                    return response()->json($returnData);
                }

            }else{
                DB::rollback();
                return response()->json($returnData);
            }


            $flag_id=$agSave->id;
            $flag="A";

            $share_dis= $this->share_distribution_new($flag, $flag_id, $users, $level, $otherAssetData, $fy_id, $receipt_amt, $zp_share, $ap_share, $gp_share);

            if(!$share_dis){
                DB::rollback();
                return response()->json($returnData);
            }


        }catch(\Exception $e){
            DB::rollback();
            $returnData['msg'] = "Server Exception.".$e->getMessage();
            return response()->json($returnData);
        }
        DB::commit();

        $returnData['msgType'] = true;
        $returnData['data'] = [];
        $returnData['msg'] = "Successfully save the agreement's installment.";
        return response()->json($returnData);
    }

    private function share_distribution($flag, $flag_id, $users, $level, $otherAssetData, $fy_id, $receipt_amt, $zp_share, $ap_share, $gp_share){

        if($level=="ZP"){
            $apList=AnchalikParishad::getActiveAPsByZpId($otherAssetData->zila_id);
            $gpList=GramPanchyat::getActiveGPsByZpId($otherAssetData->zila_id);

            foreach ($apList AS $ap){
                $disAP= new OsrNonTaxOtherAssetDisApShare();

                $disAP->flag=$flag;
                $disAP->other_asset_code=$otherAssetData->other_asset_code;
                $disAP->fy_id=$fy_id;

                if($flag=="S"){
                    $disAP->osr_non_tax_other_asset_collection_id=$flag_id;
                }else{
                    $disAP->osr_non_tax_other_asset_agreement_instalment_id=$flag_id;
                }

                $disAP->shared_by=$level;
                $disAP->zp_id=$otherAssetData->zila_id;
                $disAP->ap_id=$ap->id;
                $disAP->est_ap_share=round(($receipt_amt*40/100)/count($apList), 2);
                $disAP->ap_share=round($ap_share/count($apList), 2);
                $disAP->created_by=$users->username;

                if(!$disAP->save()){
                    return false;
                }
            }

            foreach ($gpList AS $gp){
                $disGP= new OsrNonTaxOtherAssetDisGpShare();

                $disGP->flag=$flag;
                $disGP->other_asset_code=$otherAssetData->other_asset_code;
                $disGP->fy_id=$fy_id;

                if($flag=="S"){
                    $disGP->osr_non_tax_other_asset_collection_id=$flag_id;
                }else{
                    $disGP->osr_non_tax_other_asset_agreement_instalment_id=$flag_id;
                }

                $disGP->shared_by=$level;
                $disGP->zp_id=$otherAssetData->zila_id;
                $disGP->ap_id=$gp->anchalik_id;
                $disGP->gp_id=$gp->gram_panchyat_id;
                $disGP->est_gp_share=round(($receipt_amt*40/100)/count($gpList), 2);
                $disGP->gp_share=round($gp_share/count($gpList),2);
                $disGP->created_by=$users->username;

                if(!$disGP->save()){
                    return false;
                }
            }


        }elseif($level=="AP"){
            $gpList=GramPanchyat::getActiveGPsByApId($otherAssetData->anchalik_id);

            $disAP= new OsrNonTaxOtherAssetDisApShare();

            $disAP->flag=$flag;
            $disAP->other_asset_code=$otherAssetData->other_asset_code;
            $disAP->fy_id=$fy_id;

            if($flag=="S"){
                $disAP->osr_non_tax_other_asset_collection_id=$flag_id;
            }else{
                $disAP->osr_non_tax_other_asset_agreement_instalment_id=$flag_id;
            }

            $disAP->shared_by=$level;
            $disAP->zp_id=$otherAssetData->zila_id;
            $disAP->ap_id=$otherAssetData->anchalik_id;
            $disAP->est_ap_share=round($receipt_amt*40/100, 2);
            $disAP->ap_share=$ap_share;
            $disAP->created_by=$users->username;

            if(!$disAP->save()){
                return false;
            }

            foreach ($gpList AS $gp){
                $disGP= new OsrNonTaxOtherAssetDisGpShare();

                $disGP->flag=$flag;
                $disGP->other_asset_code=$otherAssetData->other_asset_code;
                $disGP->fy_id=$fy_id;

                if($flag=="S"){
                    $disGP->osr_non_tax_other_asset_collection_id=$flag_id;
                }else{
                    $disGP->osr_non_tax_other_asset_agreement_instalment_id=$flag_id;
                }

                $disGP->shared_by=$level;
                $disGP->zp_id=$otherAssetData->zila_id;
                $disGP->ap_id=$otherAssetData->anchalik_id;
                $disGP->gp_id=$gp->gram_panchyat_id;
                $disGP->est_gp_share=round(($receipt_amt*40/100)/count($gpList), 2);
                $disGP->gp_share=round($gp_share/count($gpList),2);
                $disGP->created_by=$users->username;

                if(!$disGP->save()){
                    return false;
                }
            }
        }else{
            $disAP= new OsrNonTaxOtherAssetDisApShare();

            $disAP->flag=$flag;
            $disAP->other_asset_code=$otherAssetData->other_asset_code;
            $disAP->fy_id=$fy_id;

            if($flag=="S"){
                $disAP->osr_non_tax_other_asset_collection_id=$flag_id;
            }else{
                $disAP->osr_non_tax_other_asset_agreement_instalment_id=$flag_id;
            }

            $disAP->shared_by=$level;
            $disAP->zp_id=$otherAssetData->zila_id;
            $disAP->ap_id=$otherAssetData->anchalik_id;
            $disAP->est_ap_share=round($receipt_amt*40/100, 2);
            $disAP->ap_share=$ap_share;
            $disAP->created_by=$users->username;

            if(!$disAP->save()){
                return false;
            }

            $disGP= new OsrNonTaxOtherAssetDisGpShare();

            $disGP->flag=$flag;
            $disGP->other_asset_code=$otherAssetData->other_asset_code;
            $disGP->fy_id=$fy_id;

            if($flag=="S"){
                $disGP->osr_non_tax_other_asset_collection_id=$flag_id;
            }else{
                $disGP->osr_non_tax_other_asset_agreement_instalment_id=$flag_id;
            }

            $disGP->shared_by=$level;
            $disGP->zp_id=$otherAssetData->zila_id;
            $disGP->ap_id=$otherAssetData->anchalik_id;
            $disGP->gp_id=$otherAssetData->gram_panchayat_id;
            $disGP->est_gp_share=round($receipt_amt*40/100, 2);
            $disGP->gp_share=$gp_share;
            $disGP->created_by=$users->username;

            if(!$disGP->save()){
                return false;
            }
        }

        $disZP= new OsrNonTaxOtherAssetDisZpShare();

        $disZP->flag=$flag;
        $disZP->other_asset_code=$otherAssetData->other_asset_code;
        $disZP->fy_id=$fy_id;

        if($flag=="S"){
            $disZP->osr_non_tax_other_asset_collection_id=$flag_id;
        }else{
            $disZP->osr_non_tax_other_asset_agreement_instalment_id=$flag_id;
        }

        $disZP->shared_by=$level;
        $disZP->zp_id=$otherAssetData->zila_id;
        $disZP->est_zp_share=round($receipt_amt*20/100, 2);
        $disZP->zp_share=$zp_share;
        $disZP->created_by=$users->username;

        if(!$disZP->save()){
            return false;
        }

        return true;
    }
	
	private function share_distribution_new($flag, $flag_id, $users, $level, $otherAssetData, $fy_id, $receipt_amt, $zp_share, $ap_share, $gp_share){

        if($level=="ZP"){

            $disZP= new OsrNonTaxOtherAssetDisZpShare();

            $disZP->flag=$flag;
            $disZP->other_asset_code=$otherAssetData->other_asset_code;
            $disZP->fy_id=$fy_id;

            if($flag=="S"){
                $disZP->osr_non_tax_other_asset_collection_id=$flag_id;
            }else{
                $disZP->osr_non_tax_other_asset_agreement_instalment_id=$flag_id;
            }

            $disZP->shared_by=$level;
            $disZP->zp_id=$otherAssetData->zila_id;
            $disZP->est_zp_share=$receipt_amt;
            $disZP->zp_share=$zp_share;
            $disZP->created_by=$users->username;

            if(!$disZP->save()){
                return false;
            }



        }elseif($level=="AP"){

            $disAP= new OsrNonTaxOtherAssetDisApShare();

            $disAP->flag=$flag;
            $disAP->other_asset_code=$otherAssetData->other_asset_code;
            $disAP->fy_id=$fy_id;

            if($flag=="S"){
                $disAP->osr_non_tax_other_asset_collection_id=$flag_id;
            }else{
                $disAP->osr_non_tax_other_asset_agreement_instalment_id=$flag_id;
            }

            $disAP->shared_by=$level;
            $disAP->zp_id=$otherAssetData->zila_id;
            $disAP->ap_id=$otherAssetData->anchalik_id;
            $disAP->est_ap_share=$receipt_amt;
            $disAP->ap_share=$ap_share;
            $disAP->created_by=$users->username;

            if(!$disAP->save()){
                return false;
            }

        }else{

            $disGP= new OsrNonTaxOtherAssetDisGpShare();

            $disGP->flag=$flag;
            $disGP->other_asset_code=$otherAssetData->other_asset_code;
            $disGP->fy_id=$fy_id;

            if($flag=="S"){
                $disGP->osr_non_tax_other_asset_collection_id=$flag_id;
            }else{
                $disGP->osr_non_tax_other_asset_agreement_instalment_id=$flag_id;
            }

            $disGP->shared_by=$level;
            $disGP->zp_id=$otherAssetData->zila_id;
            $disGP->ap_id=$otherAssetData->anchalik_id;
            $disGP->gp_id=$otherAssetData->gram_panchayat_id;
            $disGP->est_gp_share=$receipt_amt;
            $disGP->gp_share=$gp_share;
            $disGP->created_by=$users->username;

            if(!$disGP->save()){
                return false;
            }
        }



        return true;
    }
}
