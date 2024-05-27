<?php

namespace App\Http\Controllers\Osr;

use App\CommonModels\AnchalikParishad;
use App\CommonModels\District;
use App\CommonModels\GramPanchyat;
use App\ConfigMdas;
use App\Osr\OsrMasterFyYear;
use App\Osr\OsrMasterInstalment;
use App\Osr\OsrMasterNonTaxBranch;
use App\Osr\OsrNonTaxAssetDisApShare;
use App\Osr\OsrNonTaxAssetDisGpShare;
use App\Osr\OsrNonTaxAssetDisZpShare;
use App\Osr\OsrNonTaxAssetEntry;
use App\Osr\OsrNonTaxAssetFinalRecord;
use App\Osr\OsrNonTaxAssetShortlist;
use App\Osr\OsrNonTaxBakijari;
use App\Osr\OsrNonTaxBiddingBiddersDetail;
use App\Osr\OsrNonTaxBiddingGeneralDetail;
use App\Osr\OsrNonTaxBiddingSettlementDetail;
use App\Osr\OsrNonTaxFyInstalment;
use App\Osr\OsrNonTaxOtherFormSellingIncome;
use App\Osr\OsrNonTaxOtherGapPeriodIncome;
use App\Osr\OsrNonTaxOtherIncome;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
use Carbon\Carbon;

class OsrNonTaxAssetPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'user_mdas']);
    }

    public function index(Request $request, $asset_code, $fy_id){

        $asset_code=base64_decode(base64_decode(base64_decode($asset_code)));
        $fy_id=base64_decode(base64_decode(base64_decode($fy_id)));

        $imgUrl= ConfigMdas::allActiveList()->imgUrl;

        $monthArray =["07"=>"JUL","08"=>"AUG","09"=>"SEP","10"=>"OCT","11"=>"NOV","12"=>"DEC","01"=>"JAN","02"=>"FEB","03"=>"MAR","04"=>"APR","05"=>"MAY","06"=>"JUN"];
        $apList=[];
        $gpList=[];
        $subInsData=[];

        $users = Auth::user();
        if ($users->mdas_master_role_id == 2) {//ZP ADMIN
            $level = "ZP";
            $id = $users->zp_id;
            $apList=AnchalikParishad::getActiveAPsByZpId($id);

            foreach($apList AS $li){
                $list=GramPanchyat::getActiveGpsByApId($li->id);
                $data_gp=[
                    "ap_name"=>$li->anchalik_parishad_name,
                    "list"=>$list
                ];
                array_push($gpList, $data_gp);
            }

        } elseif ($users->mdas_master_role_id == 3) {//AP ADMIN
            $level = "AP";
            $id = $users->ap_id;
            $gpList=GramPanchyat::getActiveGpsByApId($id);
        } elseif ($users->mdas_master_role_id == 4) {//GP ADMIN
            $level = "GP";
            $id = $users->gp_id;
        } else {
            return redirect(route('osr.osr_panel'));
        }

        $checkIsInShortlist = OsrNonTaxAssetShortlist::isInShortlist($asset_code, $fy_id, $level, $id);
        if (!$checkIsInShortlist) {
            return redirect(route('osr.osr_panel'));
        }

        $assetData=OsrNonTaxAssetEntry::getAssetByAssetCode($asset_code);
        $osrFyYear= OsrMasterFyYear::getFyYear($fy_id);

        if(!$assetData || !$osrFyYear){
            return redirect(route('osr.osr_panel'));
        }

        $generalDetail= OsrNonTaxBiddingGeneralDetail::getEntryByCodeAndFyYr($asset_code, $fy_id);

        if($generalDetail){
            $acceptedBidderData= OsrNonTaxBiddingBiddersDetail::acceptedBidder($generalDetail->id);
            $settlementData= OsrNonTaxBiddingSettlementDetail::getSettlementInfo($generalDetail->id);
        }else{
            $acceptedBidderData=NULL;
            $settlementData=NULL;
            $bidderInfoData=NULL;
        }

        $allIns=OsrMasterInstalment::getAllInstalments();

        $subIns=OsrNonTaxFyInstalment::getSubmittedInstalments($asset_code, $fy_id);

        //echo json_encode($submittedInstalments);


        foreach ($allIns AS $li){

            $instalmentData=OsrNonTaxFyInstalment::getInstalmentByAssetAndFy($asset_code, $fy_id, $li->id);

            if($instalmentData){
                $subInsData[$li->id]['data']= $instalmentData;
                $subInsData[$li->id]['apList']= OsrNonTaxAssetDisApShare::getAPsList($asset_code, $fy_id, $instalmentData->id);
                $subInsData[$li->id]['gpList']= OsrNonTaxAssetDisGpShare::getGPsList($asset_code, $fy_id, $instalmentData->id);
            }
        }




        //-------------------Gap Period Collection----------------------------------------------------------------------

        $gapPeriodList=OsrNonTaxFyInstalment::getGapPeriodInstalments($asset_code, $fy_id);

        //-------------------Gap Period Collection----------------------------------------------------------------------

        $finalRecordData=OsrNonTaxAssetFinalRecord::getFinalRecord($asset_code, $fy_id);

        $data=[
            "level"=>$level,
            "imgUrl"=>$imgUrl,
            "apList"=>$apList,
            "gpList"=>$gpList,

            "allIns"=>$allIns,// ALL MASTER INSTALLMENTS
            "subIns"=>$subIns,// ALL SUBMITTED INSTALLMENTS LOOP DATA
            "subInsData"=>$subInsData,// ALL SUBMITTED INSTALLMENTS DATA
            "gapPeriodList"=>$gapPeriodList,// ALL GAP PERIOD INSTALLMENTS DATA
            "finalRecordData"=>$finalRecordData,// ALL GAP PERIOD INSTALLMENTS DATA

        ];

        //echo json_encode($subInsData);

        $max_fy_id=OsrMasterFyYear::getMaxFyYear();
        $data['fy_id']=$max_fy_id;
        return view('Osr.non_tax.dw_track_payment', compact('data', 'assetData','osrFyYear', 'zpData', 'acceptedBidderData', 'settlementData','monthArray'));
    }


    public function formSelling(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $fy_year = $request->input('osr_master_fy_year_id');
        $selected_asset = $request->input('osr_non_tax_asset_entry_id');

        $today = Carbon::now();

        DB::beginTransaction();
        try {

            $messages = [
                'form_quantity.required' => 'This field is required',
                'cost_per_form.max' => 'This field is required',
            ];

            $validatorArray = [
                'form_quantity' => 'required',
                'cost_per_form' => 'required',
            ];

            $validator = Validator::make($request->all(), $validatorArray, $messages);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $returnData['msg'] = "VE";
                $returnData['errors'] = $errors;
                return response()->json($returnData);
            }

            $otherFormSellingIncomeData = OsrNonTaxOtherFormSellingIncome::getByAssetIdAndFyId($fy_year, $selected_asset);

            if($otherFormSellingIncomeData){
                $returnData['msg'] = "Already submitted the data.";
                return response()->json($returnData);
            }

            $formSellingSave = new OsrNonTaxOtherFormSellingIncome();
            $formSellingSave->form_quantity = $request->input('form_quantity');
            $formSellingSave->cost_per_form = $request->input('cost_per_form');
            $formSellingSave->osr_master_fy_year_id = $fy_year;
            $formSellingSave->osr_non_tax_asset_entry_id = $selected_asset;
            $formSellingSave->form_selling_date = $today;

            if (!$formSellingSave->save()) {
                return response()->json($returnData);
            }

        }catch (\Exception $e){
            DB::rollback();
            $returnData['msg'] = "Server Exception.".$e->getMessage();
            return response()->json($returnData);
        }

        DB::commit();

        $returnData['msgType'] = true;
        $returnData['msg'] = "Successfully added";
        return response()->json($returnData);
    }
    public function formSellingEdit(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        DB::beginTransaction();
        try {

            $messages = [
                'form_quantity.required' => 'This field is required',
                'cost_per_form.required' => 'This field is required',
            ];

            $validatorArray = [
                'form_quantity' => 'required',
                'cost_per_form' => 'required',
            ];

            $validator = Validator::make($request->all(), $validatorArray, $messages);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $returnData['msg'] = "VE";
                $returnData['errors'] = $errors;
                return response()->json($returnData);
            }
            $formselling_id = $request->input('formselling_id');
            $today = Carbon::now();
            $formSellingSave = OsrNonTaxOtherFormSellingIncome::where('id',$formselling_id)->first();
            $formSellingSave->form_quantity = $request->input('form_quantity');
            $formSellingSave->cost_per_form = $request->input('cost_per_form');
            $formSellingSave->form_selling_date = $today;
            $formSellingSave->save();

            if (!$formSellingSave->save()) {
                return response()->json($returnData);
            }


            $returnData['msgType'] = true;
            $returnData['msg'] = "Successfully Updated";

        }catch (\Exception $e){
            DB::rollback();
            $returnData['msg'] = "Server Exception.".$e->getMessage();
        }
        DB::commit();
        return response()->json($returnData);
    }

    public function gapPeriodEdit(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        DB::beginTransaction();
        try {

            $messages = [
                'from_date.required' => 'This field is required',
                'to_date.required' => 'This field is required',
                'collected_amount.required' => 'This field is required',
                'gap_zp_share.required' => 'This field is required',
                'gap_ap_share.required' => 'This field is required',
                'gap_gp_share.required' => 'This field is required',
                'managed_by.required' => 'This field is required',
                'transaction_no.required' => 'This field is required',
                'transaction_date.required' => 'This field is required',
            ];

            $validatorArray = [
                'from_date' => 'required',
                'to_date' => 'required',
                'managed_by' => 'required',
                'transaction_no' => 'required',
                'transaction_date' => 'required',
                'collected_amount' => [
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
                'gap_zp_share' => [
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
                'gap_ap_share' => [
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
                'gap_gp_share' => [
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
            $gap_period_id = $request->input('gap_period_id');
            $gapPeriodSave = OsrNonTaxOtherGapPeriodIncome::where('id',$gap_period_id)->first();
            $gapPeriodSave->from_date = $request->input('from_date');
            $gapPeriodSave->to_date = $request->input('to_date');
            $gapPeriodSave->collected_amount = doubleval(preg_replace('/[^\d.]/', '', $request->input('collected_amount')));
            $gapPeriodSave->gap_zp_share = doubleval(preg_replace('/[^\d.]/', '', $request->input('gap_zp_share')));
            $gapPeriodSave->gap_ap_share = doubleval(preg_replace('/[^\d.]/', '', $request->input('gap_ap_share')));
            $gapPeriodSave->gap_gp_share = doubleval(preg_replace('/[^\d.]/', '', $request->input('gap_gp_share')));
            $gapPeriodSave->managed_by = $request->input('managed_by');
            $gapPeriodSave->transaction_no = $request->input('transaction_no');
            $gapPeriodSave->transaction_date = $request->input('transaction_date');
            $gapPeriodSave->remarks = $request->input('remarks');
            $gapPeriodSave->save();
            if (!$gapPeriodSave->save()) {
                return response()->json($returnData);
            }


            $returnData['msgType'] = true;
            $returnData['msg'] = "Successfully Updated";

        }catch (\Exception $e){
            DB::rollback();
            $returnData['msg'] = "Server Exception.".$e->getMessage();
        }
        DB::commit();
        return response()->json($returnData);
    }
    public function bakiJari(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";
        $fy_year = $request->input('osr_master_fy_year_id');
        $selected_asset = $request->input('osr_non_tax_asset_entry_id');

        DB::beginTransaction();
        try {

            $messages = [
                'case_no.required' => 'This field is required',
                'case_date.required' => 'This field is required',
                'case_remarks.required' => 'This field is required'
            ];

            $validatorArray = [
                'case_no' => 'required',
                'case_date' => 'required',
                'case_remarks' => 'required'
            ];

            $validator = Validator::make($request->all(), $validatorArray, $messages);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $returnData['msg'] = "VE";
                $returnData['errors'] = $errors;
                return response()->json($returnData);
            }
            $OsrNonTaxBakijariData = OsrNonTaxBakijari::getByAssetIdAndFyId($fy_year, $selected_asset);
            if($OsrNonTaxBakijariData){
                $returnData['msg'] = "Already submitted the data.";
                return response()->json($returnData);
            }
            $bakiJariSave = new OsrNonTaxBakijari();
            $bakiJariSave->case_no = $request->input('case_no');
            $bakiJariSave->case_date = $request->input('case_date');
            $bakiJariSave->case_remarks = $request->input('case_remarks');
            $bakiJariSave->osr_master_fy_year_id = $fy_year;
            $bakiJariSave->osr_non_tax_asset_entry_id = $selected_asset;
            if (!$bakiJariSave->save()) {
                return response()->json($returnData);
            }

        }catch (\Exception $e){
            DB::rollback();
            $returnData['msg'] = "Server Exception.".$e->getMessage();
            return response()->json($returnData);
        }
        DB::commit();
        $returnData['msgType'] = true;
        $returnData['msg'] = "Successfully added";
        return response()->json($returnData);
    }
    public function bakiJariEdit(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        DB::beginTransaction();
        try {

            $messages = [
                'case_no.required' => 'This field is required',
                'case_date.required' => 'This field is required',
                'case_remarks.required' => 'This field is required',
                'closed_date.required' => 'This field is required',
                'closed_remarks.required' => 'This field is required'
            ];

            $validatorArray = [
                'case_no' => 'required',
                'case_date' => 'required',
                'case_remarks' => 'required',
                'closed_date' => 'required',
                'closed_remarks' => 'required'
            ];

            $validator = Validator::make($request->all(), $validatorArray, $messages);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $returnData['msg'] = "VE";
                $returnData['errors'] = $errors;
                return response()->json($returnData);
            }

            $baki_jari_id = $request->input('baki_jari_id');
            $status=2;
            $bakiJariSave = OsrNonTaxBakijari::where('id',$baki_jari_id)->first();
            $bakiJariSave->case_no = $request->input('case_no');
            $bakiJariSave->case_date = $request->input('case_date');
            $bakiJariSave->case_remarks = $request->input('case_remarks');
            $bakiJariSave->closed_date = $request->input('closed_date');
            $bakiJariSave->closed_remarks = $request->input('closed_remarks');
            $bakiJariSave->status = $status;
            if (!$bakiJariSave->save()) {
                return response()->json($returnData);
            }

        }catch (\Exception $e){
            DB::rollback();
            $returnData['msg'] = "Server Exception.".$e->getMessage();
            return response()->json($returnData);
        }
        DB::commit();
        $returnData['msgType'] = true;
        $returnData['msg'] = "Successfully added";
        return response()->json($returnData);
    }

    //----------- FORFEITED EARNEST MONEY DEPOSIT SAVE -------------------------------------------------------------------------

    public function forfeited_earnest_money_save(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $asset_code= $request->input('asset_code');
        $fy_id= $request->input('fy_id');

        $asset_code= decrypt($asset_code);
        $fy_id= decrypt($fy_id);

        $zp_share = doubleval(preg_replace('/[^\d.]/', '', $request->input('f_zp_share')));
        $ap_share = doubleval(preg_replace('/[^\d.]/', '', $request->input('f_ap_share')));
        $gp_share = doubleval(preg_replace('/[^\d.]/', '', $request->input('f_gp_share')));

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

        //-VALIDATION---------------------------------------------------------------------------------------------------

        $messages =[
            'f_transaction_date.required' => 'This is required!',
            'f_transaction_date.date_format' => 'This format is invalid!',

            'f_transaction_no.required' => 'This is required!',
            'f_transaction_no.max' => 'Invalid data!',
            'f_transaction_no.min' => 'Invalid data!',

            'f_remarks.max' => 'Maximum 150 characters are allowed!',
        ];

        $validatorArray=[

            'f_transaction_date' => 'required|date_format:Y-m-d',
            'f_transaction_no' => 'required|string|max:50|min:3',
            'f_remarks' => 'string|max:150|nullable',

            'f_zp_share' => [
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
            'f_ap_share' => [
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
            'f_gp_share' => [
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

        //-VALIDATION ENDED---------------------------------------------------------------------------------------------

        $assetData= OsrNonTaxAssetEntry::getAssetByAssetCode($asset_code);

        if(!$assetData){
            return response()->json($returnData);
        }

        $finalRecordData=OsrNonTaxAssetFinalRecord::getFinalRecord($asset_code, $fy_id);
        if(!$finalRecordData){
            return response()->json($returnData);
        }

        $generalDetail= OsrNonTaxBiddingGeneralDetail::getEntryByCodeAndFyYr($asset_code, $fy_id);
        if(!$generalDetail){
            return response()->json($returnData);
        }

        if($generalDetail->bidding_completed_status<>1 || $finalRecordData->bidding_status<>1){
            $returnData['msg'] = "Kindly complete the bidding process first!";
            return response()->json($returnData);
        }

       /* $settlementData= OsrNonTaxBiddingSettlementDetail::getSettlementInfo($generalDetail->id);
        if(!$settlementData){
            return response()->json($returnData);
        }*/

        $forfieted_amt=$finalRecordData->total_forfeited_emd_amt;

        if($forfieted_amt <= 0){
            return response()->json($returnData);
        }

        if($forfieted_amt <> ($zp_share+$ap_share+$gp_share)){
            $returnData['msg'] = "Receipt amount must be equal to the sum of the shares of ZP, AP and GP combined.";
            return response()->json($returnData);
        }

        if($finalRecordData->forfeited_emd_sharing_status == 1){
            $returnData['msg'] = "Forfeited earnest money already distributed!";
            return response()->json($returnData);
        }
        DB::beginTransaction();
        try{

            $newData=new OsrNonTaxFyInstalment();

            $newData->flag="F";

            $newData->asset_code=$asset_code;
            $newData->osr_master_fy_year_id=$fy_id;

            $newData->receipt_amt=$forfieted_amt;

            $newData->zp_share=$zp_share;
            $newData->ap_share=$ap_share;
            $newData->gp_share=$gp_share;
            $newData->transaction_no=$request->input('f_transaction_no');
            $newData->transaction_date=$request->input('f_transaction_date');
            $newData->remarks=$request->input('f_remarks');
            $newData->created_by=$users->username;

            if(!$newData->save()){
                DB::rollback();
                return response()->json($returnData);
            }

            //UPDATE FINAL RECORD TABLE
            $finalRecordUpdate= OsrNonTaxAssetFinalRecord::where([
                ['asset_code', '=', $asset_code],
                ['fy_id', '=', $fy_id],
            ])->update([
                'forfeited_emd_sharing_status'=> 1, 'f_emd_zp_share'=>$zp_share, 'f_emd_ap_share'=>$ap_share, 'f_emd_gp_share'=>$gp_share
            ]);

            if(!$finalRecordUpdate){
                DB::rollback();
                return response()->json($returnData);
            }
            //UPDATE FINAL RECORD TABLE ENDED

            $osr_non_tax_fy_instalment_id=$newData->id;
            $receipt_amt=$forfieted_amt;

            $share_dis= $this->share_distribution($users, $level, $assetData, $fy_id, $osr_non_tax_fy_instalment_id, $receipt_amt, $zp_share, $ap_share, $gp_share);
            if(!$share_dis){
                DB::rollback();
                return response()->json($returnData);
            }

        }catch(\Exception $e){
            DB::rollback();
            $returnData['msg'] = "Server Exception.";
            return response()->json($returnData);
        }
        DB::commit();

        $returnData['msgType'] = true;
        $returnData['msg'] = "Successfully submitted.";
        return response()->json($returnData);
    }

    //----------- INSTALMENTS SAVE ---------------------------------------------------------------------------------------------

    public function instalment(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $asset_code= $request->input('asset_code');
        $fy_id= $request->input('fy_id');
        $ins=$request->input('ins');

        $ins=decrypt($ins);
        $asset_code= decrypt($asset_code);
        $fy_id= decrypt($fy_id);

        $rebate_path=NULL;
        $rebate_remarks=NULL;

        $insArray=[1,2,3];

        if(!in_array($ins, $insArray)){
            return response()->json($returnData);
        }

        $receipt_amt = doubleval(preg_replace('/[^\d.]/', '', $request->input('ins_receipt_amount')));

        $zp_share = doubleval(preg_replace('/[^\d.]/', '', $request->input('ins_zp_share')));
        $ap_share = doubleval(preg_replace('/[^\d.]/', '', $request->input('ins_ap_share')));
        $gp_share = doubleval(preg_replace('/[^\d.]/', '', $request->input('ins_gp_share')));

        $payment=$request->input('ins_payment');
        $sharing_remarks=$request->input('ins_sharing_remark');

        if($payment=="P"){
            if($ins==3){
                $returnData['msg'] = "Final Settlement can not be partial";
                return response()->json($returnData);
            }
        }

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

        $messages =[
            'ins_payment.required' => 'This is required!',
            'ins_payment.in' => 'Invalid data!',

            'ins_receipt_date.required' => 'This is required!',
            'ins_receipt_date.date_format' => 'This format is invalid!',

            'ins_transaction_no.required' => 'This is required!',
            'ins_transaction_no.max' => 'Invalid data!',

            'ins_transaction_date.required' => 'This is required!',
            'ins_transaction_date.date_format' => 'This format is invalid!',

            'ins_sharing_remark.max' => 'Maximum 150 characters are allowed!',

            'rebate_remarks.max' => 'Maximum 150 characters are allowed!',
            'rebate_doc.mimes' => 'File must be in PDF format.',
            'rebate_doc.min' => 'PDF size must not be less than 10 KB.',
            'rebate_doc.max' => 'PDF size must not exceed 100 KB.',

        ];

        $validatorArray=[
            'ins_payment' => 'required|in:P,F',

            'ins_receipt_date' => 'required|date_format:Y-m-d',

            'ins_transaction_no' => 'required|max:50',
            'ins_transaction_date' => 'required|date_format:Y-m-d',

            'ins_sharing_remark' => 'string|max:150|nullable',

            'rebate_remarks' => 'string|max:150|nullable',
            'rebate_doc' => 'mimes:pdf|max:100|min:10|nullable',

            'ins_receipt_amount' => [
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
            'ins_zp_share' => [
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
            'ins_ap_share' => [
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
            'ins_gp_share' => [
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

        if($receipt_amt <> ($zp_share+$ap_share+$gp_share)){
            $returnData['msg'] = "Receipt amount must be equal to the sum of the shares of ZP, AP and GP combined.";
            return response()->json($returnData);
        }

        $assetData= OsrNonTaxAssetEntry::getAssetByAssetCode($asset_code);

        if(!$assetData){
            return response()->json($returnData);
        }

        $finalRecordData=OsrNonTaxAssetFinalRecord::getFinalRecord($asset_code, $fy_id);
        if(!$finalRecordData){
            return response()->json($returnData);
        }

        $generalDetail= OsrNonTaxBiddingGeneralDetail::getEntryByCodeAndFyYr($asset_code, $fy_id);
        if(!$generalDetail){
            return response()->json($returnData);
        }

        if($generalDetail->bidding_completed_status<>1 || $finalRecordData->bidding_status<>1){
            $returnData['msg'] = "Kindly complete the bidding process first!";
            return response()->json($returnData);
        }

        /*$settlementData= OsrNonTaxBiddingSettlementDetail::getSettlementInfo($generalDetail->id);
        if(!$settlementData){
            return response()->json($returnData);
        }*/

        if($finalRecordData->instalment_completed_status==1 || $finalRecordData->payment_completed_status==1){
            $returnData['msg'] = "Already completed the installment. Kindly contact admin for more details.";
            return response()->json($returnData);
        }

        if(($finalRecordData->instalment_no+1) <> $ins){
            $returnData['msg'] = "Please submit the previous installment first.";
            if($finalRecordData->instalment_no==$ins){
                $returnData['msg'] = "Data is already submitted.";
            }
            return response()->json($returnData);
        }

        if($finalRecordData->defaulter_status == 1){
            $returnData['msg'] = "Defaulter is already mark. Please contact admin for more details";
            return response()->json($returnData);
        }

        if($finalRecordData->total_forfeited_emd_amt > 0) {
            if ($finalRecordData->forfeited_emd_sharing_status == 0) {
                $returnData['msg'] = "Kindly distribute the forfeited EMD first.";
                return response()->json($returnData);
            }
        }


        DB::beginTransaction();
        try{

            if($payment == "F"){

                //WHEN REBATE EQUAL TO 1
                if($finalRecordData->settlement_amt > $receipt_amt+$finalRecordData->tot_ins_collected_amt){

                    $rebate_remarks=$request->input('rebate_remarks');

                    if ($request->file('rebate_doc')) {
                        $rebate_path = $request->file('rebate_doc')->store('osr/non_tax/asset/rebate_doc/'.$level.'/'.$fy_id);
                    }

                    if(!$rebate_remarks){
                        $returnData['msg'] = "Please enter the reason for rebate in rebate remarks!";
                        return response()->json($returnData);
                    }

                    //UPDATE FINAL RECORD TABLE IF REBATE AMOUNT

                    $finalRecordUpdate= OsrNonTaxAssetFinalRecord::where([
                            ['asset_code', '=', $asset_code],
                            ['fy_id', '=', $fy_id],
                        ])->update(['rebate_status'=> 1]);

                    if(!$finalRecordUpdate){
                        DB::rollback();
                        return response()->json($returnData);
                    }
                    //UPDATE FINAL RECORD TABLE ENDED
                }

                //UPDATE FINAL RECORD TABLE IF FULL PAYMENT

                $finalRecordUpdate= OsrNonTaxAssetFinalRecord::where([
                    ['asset_code', '=', $asset_code],
                    ['fy_id', '=', $fy_id],
                ])->update(['payment_completed_status'=> 1, 'instalment_completed_status'=> 1]);

                if(!$finalRecordUpdate){
                    DB::rollback();
                    return response()->json($returnData);
                }
                //UPDATE FINAL RECORD TABLE ENDED
            }

            $newData=new OsrNonTaxFyInstalment();

            $newData->flag="I";

            $newData->asset_code=$asset_code;
            $newData->osr_master_fy_year_id=$fy_id;
            $newData->osr_master_instalment_id=$ins;
            $newData->date_of_receipt=$request->input('ins_receipt_date');
            $newData->receipt_amt=$receipt_amt;
            $newData->payment_mode=$payment;
            $newData->zp_share=$zp_share;
            $newData->ap_share=$ap_share;
            $newData->gp_share=$gp_share;
            $newData->transaction_no=$request->input('ins_transaction_no');
            $newData->transaction_date=$request->input('ins_transaction_date');

            $newData->supporting_doc_path=$rebate_path;
            $newData->rebate_remarks=$rebate_remarks;

            $newData->remarks=$sharing_remarks;

            $newData->created_by=$users->username;

            if(!$newData->save()){
                DB::rollback();
                return response()->json($returnData);
            }

            //UPDATE FINAL RECORD TABLE

            $tot_ins_collected_amt=$finalRecordData->tot_ins_collected_amt+$receipt_amt;
            $tot_ins_zp_share=$finalRecordData->tot_ins_zp_share+$zp_share;
            $tot_ins_ap_share=$finalRecordData->tot_ins_ap_share+$ap_share;
            $tot_ins_gp_share=$finalRecordData->tot_ins_gp_share+$gp_share;

            $finalRecordUpdate= OsrNonTaxAssetFinalRecord::where([
                ['asset_code', '=', $asset_code],
                ['fy_id', '=', $fy_id],
            ])->update([
                'instalment_no'=> $ins, 'tot_ins_collected_amt'=>$tot_ins_collected_amt,
                'tot_ins_zp_share'=>$tot_ins_zp_share, 'tot_ins_ap_share'=>$tot_ins_ap_share,
                'tot_ins_gp_share'=>$tot_ins_gp_share
            ]);

            if(!$finalRecordUpdate){
                DB::rollback();
                return response()->json($returnData);
            }

            //UPDATE FINAL RECORD TABLE ENDED

            $osr_non_tax_fy_instalment_id=$newData->id;

            $share_dis= $this->share_distribution($users, $level, $assetData, $fy_id, $osr_non_tax_fy_instalment_id, $receipt_amt, $zp_share, $ap_share, $gp_share);
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
        $returnData['msg'] = "Successfully submitted.";
        return response()->json($returnData);
    }


    public function mark_as_defaulter(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $asset_code= $request->input('asset_code');
        $fy_id= $request->input('fy_id');

        $asset_code= decrypt($asset_code);
        $fy_id= decrypt($fy_id);

        $zp_share = doubleval(preg_replace('/[^\d.]/', '', $request->input('d_zp_share')));
        $ap_share = doubleval(preg_replace('/[^\d.]/', '', $request->input('d_ap_share')));
        $gp_share = doubleval(preg_replace('/[^\d.]/', '', $request->input('d_gp_share')));

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

        //-VALIDATION---------------------------------------------------------------------------------------------------

        $messages =[
            'd_transaction_date.required' => 'This is required!',
            'd_transaction_date.date_format' => 'This format is invalid!',

            'd_transaction_no.required' => 'This is required!',
            'd_transaction_no.max' => 'Invalid data!',
            'd_transaction_no.min' => 'Invalid data!',

            'd_sharing_remarks.max' => 'Maximum 150 characters are allowed!',
        ];

        $validatorArray=[

            'd_transaction_date' => 'required|date_format:Y-m-d',
            'd_transaction_no' => 'required|string|max:50|min:3',
            'd_sharing_remarks' => 'string|max:150|nullable',

            'd_zp_share' => [
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
            'd_ap_share' => [
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
            'd_gp_share' => [
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

        //-VALIDATION ENDED---------------------------------------------------------------------------------------------

        $assetData= OsrNonTaxAssetEntry::getAssetByAssetCode($asset_code);

        if(!$assetData){
            return response()->json($returnData);
        }

        $finalRecordData=OsrNonTaxAssetFinalRecord::getFinalRecord($asset_code, $fy_id);
        if(!$finalRecordData){
            return response()->json($returnData);
        }

        $generalDetail= OsrNonTaxBiddingGeneralDetail::getEntryByCodeAndFyYr($asset_code, $fy_id);
        if(!$generalDetail){
            return response()->json($returnData);
        }

        if($generalDetail->bidding_completed_status<>1 || $finalRecordData->bidding_status<>1){
            $returnData['msg'] = "Kindly complete the bidding process first!";
            return response()->json($returnData);
        }

        $security_deposit_amt=$finalRecordData->security_deposit_amt;

        if($security_deposit_amt <= 0){
            return response()->json($returnData);
        }

        if($security_deposit_amt <> ($zp_share+$ap_share+$gp_share)){
            $returnData['msg'] = "Receipt amount must be equal to the sum of the shares of ZP, AP and GP combined.";
            return response()->json($returnData);
        }

        if($finalRecordData->defaulter_status == 1){
            $returnData['msg'] = "Already marked as defaulter!";
            return response()->json($returnData);
        }

        if($finalRecordData->instalment_completed_status == 1 || $finalRecordData->payment_completed_status == 1){
            $returnData['msg'] = "Sorry! Payment process already done. Please contact admin for more details.";
            return response()->json($returnData);
        }

        DB::beginTransaction();
        try{

            $newData=new OsrNonTaxFyInstalment();

            $newData->flag="D";

            $newData->asset_code=$asset_code;
            $newData->osr_master_fy_year_id=$fy_id;

            $newData->receipt_amt=$security_deposit_amt;

            $newData->zp_share=$zp_share;
            $newData->ap_share=$ap_share;
            $newData->gp_share=$gp_share;
            $newData->transaction_no=$request->input('d_transaction_no');
            $newData->transaction_date=$request->input('d_transaction_date');
            $newData->remarks=$request->input('d_sharing_remarks');
            $newData->created_by=$users->username;

            if(!$newData->save()){
                DB::rollback();
                return response()->json($returnData);
            }

            //UPDATE FINAL RECORD TABLE
            $finalRecordUpdate= OsrNonTaxAssetFinalRecord::where([
                ['asset_code', '=', $asset_code],
                ['fy_id', '=', $fy_id],
            ])->update([
                'defaulter_status'=> 1, 'instalment_completed_status'=>1,
                'payment_completed_status'=>1, 'df_zp_share'=>$zp_share,
                'df_ap_share'=>$ap_share, 'df_gp_share'=>$gp_share
            ]);

            if(!$finalRecordUpdate){
                DB::rollback();
                return response()->json($returnData);
            }
            //UPDATE FINAL RECORD TABLE ENDED

            $osr_non_tax_fy_instalment_id=$newData->id;
            $receipt_amt=$security_deposit_amt;

            $share_dis= $this->share_distribution($users, $level, $assetData, $fy_id, $osr_non_tax_fy_instalment_id, $receipt_amt, $zp_share, $ap_share, $gp_share);
            if(!$share_dis){
                DB::rollback();
                return response()->json($returnData);
            }

        }catch(\Exception $e){
            DB::rollback();
            $returnData['msg'] = "Server Exception.";
            return response()->json($returnData);
        }
        DB::commit();

        $returnData['msgType'] = true;
        $returnData['msg'] = "Successfully submitted.";
        return response()->json($returnData);
    }

    //----------- GAP PERIOD SAVE ---------------------------------------------------------------------------------------------

    public function gapPeriod(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $asset_code= $request->input('asset_code');
        $fy_id= $request->input('fy_id');

        $asset_code= decrypt($asset_code);
        $fy_id= decrypt($fy_id);

        $receipt_amt = doubleval(preg_replace('/[^\d.]/', '', $request->input('gap_receipt_amount')));

        $zp_share = doubleval(preg_replace('/[^\d.]/', '', $request->input('gap_zp_share')));
        $ap_share = doubleval(preg_replace('/[^\d.]/', '', $request->input('gap_ap_share')));
        $gp_share = doubleval(preg_replace('/[^\d.]/', '', $request->input('gap_gp_share')));

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

        $messages = [
                'gap_from_date.required' => 'This is required!',
                'gap_from_date.date_format' => 'This format is invalid!',

                'gap_to_date.required' => 'This is required!',
                'gap_to_date.date_format' => 'This format is invalid!',

                'gap_transaction_no.required' => 'This is required!',
                'gap_transaction_no.max' => 'Invalid data!',

                'gap_transaction_date.required' => 'This is required!',
                'gap_transaction_date.date_format' => 'This format is invalid!',

                'gap_collected_by.required' => 'This is required!',
                'gap_collected_by.in' => 'Invalid data!',

                'gap_sharing_remarks.max' => 'Maximum 150 characters are allowed!',
            ];

        $validatorArray = [
                'gap_from_date' => 'required|date_format:Y-m-d',
                'gap_to_date' => 'required|date_format:Y-m-d',

                'gap_receipt_amount' => [
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
                'gap_zp_share' => [
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
                'gap_ap_share' => [
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
                'gap_gp_share' => [
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

                'gap_transaction_no' => 'required|max:50',
                'gap_transaction_date' => 'required|date_format:Y-m-d',
                'gap_collected_by' => 'required|in:ZP,AP,GP,OTHER',
                'gap_sharing_remarks' => 'string|max:150|nullable',
            ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
                $errors = $validator->errors();
                $returnData['msg'] = "VE";
                $returnData['errors'] = $errors;
                return response()->json($returnData);
            }

        if($receipt_amt <> ($zp_share+$ap_share+$gp_share)){
            $returnData['msg'] = "Receipt amount must be equal to the sum of the shares of ZP, AP and GP combined.";
            return response()->json($returnData);
        }

        $assetData= OsrNonTaxAssetEntry::getAssetByAssetCode($asset_code);

        if(!$assetData){
            return response()->json($returnData);
        }



        DB::beginTransaction();
        try{

            $newData=new OsrNonTaxFyInstalment();

            $newData->flag="G";

            $newData->asset_code=$asset_code;
            $newData->osr_master_fy_year_id=$fy_id;

            $newData->gap_from_date=$request->input('gap_from_date');
            $newData->gap_to_date=$request->input('gap_to_date');

            $newData->receipt_amt=$receipt_amt;

            $newData->zp_share=$zp_share;
            $newData->ap_share=$ap_share;
            $newData->gp_share=$gp_share;

            $newData->transaction_no=$request->input('gap_transaction_no');
            $newData->transaction_date=$request->input('gap_transaction_date');
            $newData->gap_collected_by=$request->input('gap_collected_by');

            $newData->remarks=$request->input('gap_sharing_remarks');

            $newData->created_by=$users->username;

            if(!$newData->save()){
                DB::rollback();
                return response()->json($returnData);
            }

            $finalRecordCount=OsrNonTaxAssetFinalRecord::finalRecordCount($asset_code, $fy_id);

            if($finalRecordCount==0){
                $finalRecordNew= new OsrNonTaxAssetFinalRecord();

                $finalRecordNew->asset_code=$asset_code;
                $finalRecordNew->fy_id=$fy_id;
                $finalRecordNew->gap_period_status=1;
                $finalRecordNew->tot_gap_collected_amt=$receipt_amt;
                $finalRecordNew->tot_gap_zp_share=$zp_share;
                $finalRecordNew->tot_gap_ap_share=$ap_share;
                $finalRecordNew->tot_gap_gp_share=$gp_share;
                $finalRecordNew->created_by=$users->username;

                if(!$finalRecordNew->save()){
                    DB::rollback();
                    return response()->json($returnData);
                }

            }elseif($finalRecordCount==1){

                $finalRecordData=OsrNonTaxAssetFinalRecord::getFinalRecord($asset_code, $fy_id);

                $tot_gap_collected_amt=$receipt_amt+$finalRecordData->tot_gap_collected_amt;
                $tot_gap_zp_share=$zp_share+$finalRecordData->tot_gap_zp_share;
                $tot_gap_ap_share=$ap_share+$finalRecordData->tot_gap_ap_share;
                $tot_gap_gp_share=$gp_share+$finalRecordData->tot_gap_gp_share;

                $finalRecordUpdate= OsrNonTaxAssetFinalRecord::where([
                    ['asset_code', '=', $asset_code],
                    ['fy_id', '=', $fy_id],
                ])->update([
                    'gap_period_status'=> 1, 'tot_gap_collected_amt'=> $tot_gap_collected_amt,
                    'tot_gap_zp_share'=> $tot_gap_zp_share,'tot_gap_ap_share'=> $tot_gap_ap_share,
                    'tot_gap_gp_share'=> $tot_gap_gp_share
                ]);

                if(!$finalRecordUpdate){
                    DB::rollback();
                    return response()->json($returnData);
                }

            }else{
                DB::rollback();
                return response()->json($returnData);
            }

            $osr_non_tax_fy_instalment_id=$newData->id;

            $share_dis= $this->share_distribution($users, $level, $assetData, $fy_id, $osr_non_tax_fy_instalment_id, $receipt_amt, $zp_share, $ap_share, $gp_share);
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
        $returnData['msg'] = "Successfully added";
        return response()->json($returnData);
    }

    private function share_distribution($users, $level, $assetData, $fy_id, $osr_non_tax_fy_instalment_id, $receipt_amt, $zp_share, $ap_share, $gp_share){

        if($level=="ZP"){
            $apList=AnchalikParishad::getActiveAPsByZpId($assetData->zila_id);
            $gpList=GramPanchyat::getActiveGPsByZpId($assetData->zila_id);

            foreach ($apList AS $ap){
                $disAP= new OsrNonTaxAssetDisApShare();
                $disAP->asset_code=$assetData->asset_code;
                $disAP->fy_id=$fy_id;
                $disAP->osr_non_tax_fy_instalment_id=$osr_non_tax_fy_instalment_id;
                $disAP->shared_by=$level;
                $disAP->zp_id=$assetData->zila_id;
                $disAP->ap_id=$ap->id;
                $disAP->est_ap_share=round(($receipt_amt*40/100)/count($apList), 2);
                $disAP->ap_share=round($ap_share/count($apList), 2);
                $disAP->created_by=$users->username;

                if(!$disAP->save()){
                    return false;
                }
            }

            foreach ($gpList AS $gp){
                $disGP= new OsrNonTaxAssetDisGpShare();
                $disGP->asset_code=$assetData->asset_code;
                $disGP->fy_id=$fy_id;
                $disGP->osr_non_tax_fy_instalment_id=$osr_non_tax_fy_instalment_id;
                $disGP->shared_by=$level;
                $disGP->zp_id=$assetData->zila_id;
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
            $gpList=GramPanchyat::getActiveGPsByApId($assetData->anchalik_id);

            $disAP= new OsrNonTaxAssetDisApShare();
            $disAP->asset_code=$assetData->asset_code;
            $disAP->fy_id=$fy_id;
            $disAP->osr_non_tax_fy_instalment_id=$osr_non_tax_fy_instalment_id;
            $disAP->shared_by=$level;
            $disAP->zp_id=$assetData->zila_id;
            $disAP->ap_id=$assetData->anchalik_id;
            $disAP->est_ap_share=round($receipt_amt*40/100, 2);
            $disAP->ap_share=$ap_share;
            $disAP->created_by=$users->username;

            if(!$disAP->save()){
                return false;
            }

            foreach ($gpList AS $gp){
                $disGP= new OsrNonTaxAssetDisGpShare();
                $disGP->asset_code=$assetData->asset_code;
                $disGP->fy_id=$fy_id;
                $disGP->osr_non_tax_fy_instalment_id=$osr_non_tax_fy_instalment_id;
                $disGP->shared_by=$level;
                $disGP->zp_id=$assetData->zila_id;
                $disGP->ap_id=$assetData->anchalik_id;
                $disGP->gp_id=$gp->gram_panchyat_id;
                $disGP->est_gp_share=round(($receipt_amt*40/100)/count($gpList), 2);
                $disGP->gp_share=round($gp_share/count($gpList),2);
                $disGP->created_by=$users->username;

                if(!$disGP->save()){
                    return false;
                }
            }
        }else{
            $disAP= new OsrNonTaxAssetDisApShare();
            $disAP->asset_code=$assetData->asset_code;
            $disAP->fy_id=$fy_id;
            $disAP->osr_non_tax_fy_instalment_id=$osr_non_tax_fy_instalment_id;
            $disAP->shared_by=$level;
            $disAP->zp_id=$assetData->zila_id;
            $disAP->ap_id=$assetData->anchalik_id;
            $disAP->est_ap_share=round($receipt_amt*40/100, 2);
            $disAP->ap_share=$ap_share;
            $disAP->created_by=$users->username;

            if(!$disAP->save()){
                return false;
            }

            $disGP= new OsrNonTaxAssetDisGpShare();
            $disGP->asset_code=$assetData->asset_code;
            $disGP->fy_id=$fy_id;
            $disGP->osr_non_tax_fy_instalment_id=$osr_non_tax_fy_instalment_id;
            $disGP->shared_by=$level;
            $disGP->zp_id=$assetData->zila_id;
            $disGP->ap_id=$assetData->anchalik_id;
            $disGP->gp_id=$assetData->gram_panchayat_id;
            $disGP->est_gp_share=round($receipt_amt*40/100, 2);
            $disGP->gp_share=$gp_share;
            $disGP->created_by=$users->username;

            if(!$disGP->save()){
                return false;
            }
        }

        $disZP= new OsrNonTaxAssetDisZpShare();
        $disZP->asset_code=$assetData->asset_code;
        $disZP->fy_id=$fy_id;
        $disZP->osr_non_tax_fy_instalment_id=$osr_non_tax_fy_instalment_id;
        $disZP->shared_by=$level;
        $disZP->zp_id=$assetData->zila_id;
        $disZP->est_zp_share=round($receipt_amt*20/100, 2);
        $disZP->zp_share=$zp_share;
        $disZP->created_by=$users->username;

        if(!$disZP->save()){
            return false;
        }

        return true;
    }
}
