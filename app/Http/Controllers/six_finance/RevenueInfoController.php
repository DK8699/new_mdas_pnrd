<?php

namespace App\Http\Controllers\six_finance;

use App\BalanceModels\FinancialBalance;
use App\ConfigMdas;
use App\survey\six_finance\SixFinanceFinals;
use App\survey\six_finance\SixFinanceFormRevenueArrearTaxes;
use App\survey\six_finance\SixFinanceFormRevenueCssShares;
use App\survey\six_finance\SixFinanceFormRevenueOwnRevenues;
use App\survey\six_finance\SixFinanceFormRevenueTransferredResources;
use App\survey\six_finance\SixFinanceRevenueCssShareCats;
use App\survey\six_finance\SixFinanceRevenueOwnRevenueCats;
use App\survey\six_finance\SixFinanceRevenueTransferredResourcesCats;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Validator;

class RevenueInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'user_mdas']);
    }

    public function index(Request $request){

        if ($request->session()->exists('users')) {

            $users=$request->session()->get('users');

            if($request->session()->exists('six_finance_session_data') && $request->session()->exists('sixFinanceFinal')){
                $six_finance_session_data=$request->session()->get('six_finance_session_data');
                $sixFinanceFinal=$request->session()->get('sixFinanceFinal');

                $applicable_id=$six_finance_session_data['applicable_id'];
                $applicable_name=$six_finance_session_data['applicable_name'];

                $acts= DB::table('acts')->where('id', '<=', 5)->get();


                $own_revenue_cats = SixFinanceRevenueOwnRevenueCats::where('is_active', 1)
                    ->select('id', 'own_revenue_name')
                    ->get();

                $css_shares = SixFinanceRevenueCssShareCats::where('is_active', 1)
                    ->select('id', 'scheme_name')
                    ->get();


                $tr_cats = SixFinanceRevenueTransferredResourcesCats::where([
                    ['applicable_id', '=', $applicable_id],
                    ['parent', '=', NULL],
                    ['is_active', '=', 1],
                ])->orWhere([
                    ['applicable_id', '=', NULL],
                    ['parent', '=', NULL],
                    ['is_active', '=', 1],
                ])->orderBy('id', 'asc')
                    ->select('id', 'transferred_resource_cat_name', 'parent')
                    ->get();

                $tr_cats_final=[];

                foreach($tr_cats AS $li_tr){

                    $sublist = SixFinanceRevenueTransferredResourcesCats::where([
                        ['parent', '=', $li_tr->id],
                        ['is_active', '=', 1],
                    ])->select('id', 'transferred_resource_cat_name')
                        ->get();

                    $data=[
                        'id' => $li_tr->id,
                        'transferred_resource_cat_name' => $li_tr->transferred_resource_cat_name,
                        'parent' => $li_tr->parent,
                        'sublist' => $sublist
                    ];

                    array_push($tr_cats_final, $data);
                }

                //echo json_encode($tr_cats_final);

                //------------ WHEN SUBMITTED ------------------------
                $six_finance_final_id=$sixFinanceFinal->id;
                $revenueInfoOwnFillFinal=NULL;
                $revenueInfoArrearFillFinal=NULL;
                $revenueInfoShareFillFinal=NULL;
                $revenueInfoTRFillFinal=NULL;
                $alreadySubmitted=NULL;

                $check=$this->getRevenueInfoBySixFinalId($sixFinanceFinal->id);

                if($check){

                    $revenueInfoOwnFill= SixFinanceFormRevenueOwnRevenues::where([
                        ['six_finance_final_id', '=', $six_finance_final_id],
                    ])->select('*')->get();

                    foreach($revenueInfoOwnFill AS $li){
                        $revenueInfoOwnFillFinal["O_".$li->six_finance_revenue_own_revenue_cat_id]["A_".$li->act_id]=$li->own_revenue_value;
                    }

                    $revenueInfoArrearFill=SixFinanceFormRevenueArrearTaxes::where([
                        ['six_finance_final_id', '=', $six_finance_final_id],
                    ])->select('*')->get();

                    foreach($revenueInfoArrearFill AS $li){
                        $revenueInfoArrearFillFinal["A_".$li->act_id]=$li->arrear_tax_value;
                    }

                    $revenueInfoShareFill= SixFinanceFormRevenueCssShares::where([
                        ['six_finance_final_id', '=', $six_finance_final_id],
                    ])->select('*')->get();

                    foreach($revenueInfoShareFill AS $li){
                        $revenueInfoShareFillFinal["S_".$li->share]["C_".$li->six_finance_revenue_css_share_cat_id]["A_".$li->act_id]=$li->share_value;
                    }

                    $revenueInfoTRFill= SixFinanceFormRevenueTransferredResources::where([
                        ['six_finance_final_id', '=', $six_finance_final_id],
                    ])->select('*')->get();

                    foreach($revenueInfoTRFill AS $li){
                        $revenueInfoTRFillFinal["C_".$li->six_finance_revenue_transferred_resources_cat_id]["A_".$li->act_id]=$li->tr_value;
                    }

                    //echo json_encode($staffInfoSalaryFillFinal);

                    $alreadySubmitted=1;
                }

                //echo json_encode($revenueInfoTRFillFinal);


                return view('survey.six_finance.revenue_info', compact('applicable_name', 'acts', 'own_revenue_cats', 'css_shares', 'tr_cats_final', 'revenueInfoOwnFillFinal', 'revenueInfoArrearFillFinal', 'revenueInfoShareFillFinal', 'revenueInfoTRFillFinal', 'alreadySubmitted'));
            }else {
                return redirect()->route('dashboard');
            }
        }else{
            return redirect()->route('dashboard');
        }
    }

    public function add_tax_own_revenue(Request $request){
        $returnData['msgType']=false;
        $returnData['data']=[];

        if (!$request->session()->exists('users')) {
            $returnData['msg']="User not found. Please Login.";
            return response()->json($returnData);
        }

        $users=$request->session()->get('users');

        if(!$request->session()->exists('six_finance_session_data') || !$request->session()->exists('sixFinanceFinal')) {
            $returnData['msg']="Oops! Something went wrong. Please try again later.";
            return response()->json($returnData);
        }

        $six_finance_session_data=$request->session()->get('six_finance_session_data');
        $sixFinanceFinal=$request->session()->get('sixFinanceFinal');

        $applicable_id=$six_finance_session_data['applicable_id'];
        $applicable_name=$six_finance_session_data['applicable_name'];

        $messages=[

        ];

        $validator = Validator::make($request->all(), [
            'tax_name'=> "required|string|max:100|min:2",
        ], $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);
        }

        DB::beginTransaction();

        try {

            $cat = new SixFinanceRevenueOwnRevenueCats();
            $cat->own_revenue_name = $request->input('tax_name');
            $cat->is_active = 0;
            $cat->created_by = $users->employee_code;
            if(!$cat->save()){
                DB::rollback();
                $returnData['msg'] = "Opps! Something went worng.#2";
                return response()->json($returnData);
            }

        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng.#3";
            return response()->json($returnData);
        }

        DB::commit();
        $returnData['msgType']=true;
        $returnData['msg']="Successfully sent the request.!";
        $returnData['data']=[];
        return response()->json($returnData);
    }

    public function add_share(Request $request){
        $returnData['msgType']=false;
        $returnData['data']=[];

        if (!$request->session()->exists('users')) {
            $returnData['msg']="User not found. Please Login.";
            return response()->json($returnData);
        }

        $users=$request->session()->get('users');

        if(!$request->session()->exists('six_finance_session_data') || !$request->session()->exists('sixFinanceFinal')) {
            $returnData['msg']="Oops! Something went wrong. Please try again later.";
            return response()->json($returnData);
        }

        $six_finance_session_data=$request->session()->get('six_finance_session_data');
        $sixFinanceFinal=$request->session()->get('sixFinanceFinal');

        $applicable_id=$six_finance_session_data['applicable_id'];
        $applicable_name=$six_finance_session_data['applicable_name'];

        $messages=[

        ];

        $validator = Validator::make($request->all(), [
            'share_name'=> "required|string|max:100|min:2",
        ], $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);
        }

        DB::beginTransaction();

        try {

            $cat = new SixFinanceRevenueCssShareCats();
            $cat->scheme_name = $request->input('share_name');
            $cat->is_active = 0;
            $cat->created_by = $users->employee_code;
            if(!$cat->save()){
                DB::rollback();
                $returnData['msg'] = "Opps! Something went worng.#2";
                return response()->json($returnData);
            }

        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng.#3";
            return response()->json($returnData);
        }

        DB::commit();
        $returnData['msgType']=true;
        $returnData['msg']="Successfully sent the request.!";
        $returnData['data']=[];
        return response()->json($returnData);
    }

    public function addTransferResource(Request $request){
        $returnData['msgType']=false;
        $returnData['data']=[];

        if (!$request->session()->exists('users')) {
            $returnData['msg']="User not found. Please Login.";
            return response()->json($returnData);
        }

        $users=$request->session()->get('users');

        if(!$request->session()->exists('six_finance_session_data') || !$request->session()->exists('sixFinanceFinal')) {
            $returnData['msg']="Oops! Something went wrong. Please try again later.";
            return response()->json($returnData);
        }

        $six_finance_session_data=$request->session()->get('six_finance_session_data');
        $sixFinanceFinal=$request->session()->get('sixFinanceFinal');

        $applicable_id=$six_finance_session_data['applicable_id'];
        $applicable_name=$six_finance_session_data['applicable_name'];

        $messages=[

        ];

        $validator = Validator::make($request->all(), [
            'transfer_resource_name'=> "required|string|max:100|min:2",
        ], $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);
        }

        DB::beginTransaction();

        try {

            $cat = new SixFinanceRevenueTransferredResourcesCats();
            $cat->applicable_id = $applicable_id;
            $cat->transferred_resource_cat_name = $request->input('transfer_resource_name');
            $cat->is_active = 0;
            $cat->parent = NULL;
            $cat->created_by = $users->employee_code;
            if(!$cat->save()){
                DB::rollback();
                $returnData['msg'] = "Opps! Something went worng.#2";
                return response()->json($returnData);
            }

        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng.#3";
            return response()->json($returnData);
        }

        DB::commit();
        $returnData['msgType']=true;
        $returnData['msg']="Successfully sent the request.!";
        $returnData['data']=[];
        return response()->json($returnData);
    }

    /*----------------------------------------------------------------------------------------------------------------*/
    /*====================================== SAVE REVENUE INFO =======================================================*/
    /*----------------------------------------------------------------------------------------------------------------*/

    public function save(Request $request){
        $returnData['msgType']=false;
        $returnData['data']=[];

        if (!$request->session()->exists('users')) {
            $returnData['msg']="User not found. Please Login.";
            return response()->json($returnData);
        }

        $users=$request->session()->get('users');

        if(!$request->session()->exists('six_finance_session_data') || !$request->session()->exists('sixFinanceFinal')) {
            $returnData['msg']="Oops! Something went wrong. Please try again later.";
            return response()->json($returnData);
        }

        $six_finance_session_data=$request->session()->get('six_finance_session_data');
        $sixFinanceFinal=$request->session()->get('sixFinanceFinal');

        $applicable_id=$six_finance_session_data['applicable_id'];
        $applicable_name=$six_finance_session_data['applicable_name'];

        $acts= DB::table('acts')->where('id', '<=', 5)->get();

        $own_revenue_cats = SixFinanceRevenueOwnRevenueCats::where('is_active', 1)
            ->select('id', 'own_revenue_name')
            ->get();

        $css_shares = SixFinanceRevenueCssShareCats::where('is_active', 1)
            ->select('id', 'scheme_name')
            ->get();


        $tr_cats = SixFinanceRevenueTransferredResourcesCats::where([
            ['applicable_id', '=', $applicable_id],
            ['parent', '=', NULL],
            ['is_active', '=', 1],
        ])->orWhere([
            ['applicable_id', '=', NULL],
            ['parent', '=', NULL],
            ['is_active', '=', 1],
        ])->orderBy('id', 'asc')
            ->select('id', 'transferred_resource_cat_name', 'parent')
            ->get();

        $tr_cats_final=[];

        foreach($tr_cats AS $li_tr){

            $sublist = SixFinanceRevenueTransferredResourcesCats::where([
                ['parent', '=', $li_tr->id],
                ['is_active', '=', 1],
            ])->select('id', 'transferred_resource_cat_name')
                ->get();

            $data=[
                'id' => $li_tr->id,
                'transferred_resource_cat_name' => $li_tr->transferred_resource_cat_name,
                'parent' => $li_tr->parent,
                'sublist' => $sublist
            ];

            array_push($tr_cats_final, $data);
        }

        //-------------------------------- VALIDATION REVENUE INFO -----------------------------------------------------

        foreach($acts AS $li_act){
            foreach($own_revenue_cats AS $li_o){

                $validatorRule['own_revenue_a_'.$li_act->id.'_o_'.$li_o->id]= "required|numeric|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:9999999999";

                $validatorMessages['own_revenue_a_'.$li_act->id.'_o_'.$li_o->id.'.required']= "This field is required!";
                $validatorMessages['own_revenue_a_'.$li_act->id.'_o_'.$li_o->id.'.numeric']= "Must be a number!";
                $validatorMessages['own_revenue_a_'.$li_act->id.'_o_'.$li_o->id.'.regex']= "Decimal up to two digit places only!";
                $validatorMessages['own_revenue_a_'.$li_act->id.'_o_'.$li_o->id.'.min']= "Negative values not accepted!";
                $validatorMessages['own_revenue_a_'.$li_act->id.'_o_'.$li_o->id.'.max']= "Up to 10 digit number is allowed!";
            }

            $validatorRule['total_arrear_taxes_a_'.$li_act->id]= "required|numeric|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:9999999999";

            $validatorMessages['total_arrear_taxes_a_'.$li_act->id.'.required']= "This field is required!";
            $validatorMessages['total_arrear_taxes_a_'.$li_act->id.'.numeric']= "Must be a number!";
            $validatorMessages['total_arrear_taxes_a_'.$li_act->id.'.regex']= "Decimal up to two digit places only!";
            $validatorMessages['total_arrear_taxes_a_'.$li_act->id.'.min']= "Negative values not accepted!";
            $validatorMessages['total_arrear_taxes_a_'.$li_act->id.'.max']= "Up to 10 digit number is allowed!";



            foreach($css_shares AS $li_sh){

                $validatorRule['central_share_of_css_a_'.$li_act->id.'_s_'.$li_sh->id]= "required|numeric|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:9999999999";

                $validatorMessages['central_share_of_css_a_'.$li_act->id.'_s_'.$li_sh->id.'.required']= "This field is required!";
                $validatorMessages['central_share_of_css_a_'.$li_act->id.'_s_'.$li_sh->id.'.numeric']= "Must be a number!";
                $validatorMessages['central_share_of_css_a_'.$li_act->id.'_s_'.$li_sh->id.'.regex']= "Decimal up to two digit places only!";
                $validatorMessages['central_share_of_css_a_'.$li_act->id.'_s_'.$li_sh->id.'.min']= "Negative values not accepted!";
                $validatorMessages['central_share_of_css_a_'.$li_act->id.'_s_'.$li_sh->id.'.max']= "Up to 10 digit number is allowed!";

                $validatorRule['state_share_of_css_a_'.$li_act->id.'_s_'.$li_sh->id]= "required|numeric|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:9999999999";

                $validatorMessages['state_share_of_css_a_'.$li_act->id.'_s_'.$li_sh->id.'.required']= "This field is required!";
                $validatorMessages['state_share_of_css_a_'.$li_act->id.'_s_'.$li_sh->id.'.numeric']= "Must be a number!";
                $validatorMessages['state_share_of_css_a_'.$li_act->id.'_s_'.$li_sh->id.'.regex']= "Decimal up to two digit places only!";
                $validatorMessages['state_share_of_css_a_'.$li_act->id.'_s_'.$li_sh->id.'.min']= "Negative values not accepted!";
                $validatorMessages['state_share_of_css_a_'.$li_act->id.'_s_'.$li_sh->id.'.max']= "Up to 10 digit number is allowed!";
            }

            foreach($tr_cats_final AS $li_tr){
                if(count($li_tr['sublist']) == 0){
                    $validatorRule['transfer_a_'.$li_act->id.'_t_'.$li_tr['id']]= "required|numeric|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:9999999999";

                    $validatorMessages['transfer_a_'.$li_act->id.'_t_'.$li_tr['id'].'.required']= "This field is required!";
                    $validatorMessages['transfer_a_'.$li_act->id.'_t_'.$li_tr['id'].'.numeric']= "Must be a number!";
                    $validatorMessages['transfer_a_'.$li_act->id.'_t_'.$li_tr['id'].'.regex']= "Decimal up to two digit places only!";
                    $validatorMessages['transfer_a_'.$li_act->id.'_t_'.$li_tr['id'].'.min']= "Negative values not accepted!";
                    $validatorMessages['transfer_a_'.$li_act->id.'_t_'.$li_tr['id'].'.max']= "Up to 10 digit number is allowed!";
                }else{
                    //No Action Needed
                }

                foreach($li_tr['sublist'] AS $li_sub){
                    $validatorRule['transfer_a_'.$li_act->id.'_t_'.$li_sub->id]= "required|numeric|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:9999999999";

                    $validatorMessages['transfer_a_'.$li_act->id.'_t_'.$li_sub->id.'.required']= "This field is required!";
                    $validatorMessages['transfer_a_'.$li_act->id.'_t_'.$li_sub->id.'.numeric']= "Must be a number!";
                    $validatorMessages['transfer_a_'.$li_act->id.'_t_'.$li_sub->id.'.regex']= "Decimal up to two digit places only!";
                    $validatorMessages['transfer_a_'.$li_act->id.'_t_'.$li_sub->id.'.min']= "Negative values not accepted!";
                    $validatorMessages['transfer_a_'.$li_act->id.'_t_'.$li_sub->id.'.max']= "Up to 10 digit number is allowed!";
                }
            }
        }


        $validator = Validator::make($request->all(), $validatorRule, $validatorMessages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);
        }

        DB::beginTransaction();

        try {

            $check=$this->getRevenueInfoBySixFinalId($sixFinanceFinal->id);

            $delete4=false;

            if($check){
                //------------------- CHECK CONFIG RULES ----------------------------

                if(isset(ConfigMdas::allActiveList()->six_finance_delete_request_up_to_date)){
                    if(ConfigMdas::allActiveList()->six_finance_delete_request_up_to_date < Carbon::now()->format("Y-m-d")){
                        $returnData['msg']="Resubmit is currently suspended. Please contact admin for more details.";
                        return response()->json($returnData);
                    }
                }

                $resubmitTracker=DB::table('six_finance_resubmit_trackers')->where([
                    ['six_finance_final_id', '=', $sixFinanceFinal->id],
                    ['r_date', '=', Carbon::now()->format("Y-m-d")],
                ])->count();

                if($resubmitTracker >= 10){
                    $returnData['msg']="You have crossed the maximum number of resubmit that is 10 times per day. Kindly contact admin for more details!";
                    return response()->json($returnData);
                }

                //------------------- CHECK CONFIG RULES ENDED ----------------------

                $sixFinanceTest=SixFinanceFinals::where([
                    ['id', '=', $sixFinanceFinal->id]
                ])->first();

                if(!$sixFinanceTest){
                    DB::rollback();
                    $returnData['msg'] = "Oops! Something went wrong.Please try again later";
                    return response()->json($returnData);
                }elseif($sixFinanceTest->verify==1){
                    DB::rollback();
                    $returnData['msg'] = "Sorry you can not resubmit the form because your form is verified. Please ask the admin for more details.";
                    return response()->json($returnData);
                }

                $delete= SixFinanceFormRevenueArrearTaxes::where([
                    ['six_finance_final_id', '=', $sixFinanceFinal->id],
                ])->delete();

                $delete1= SixFinanceFormRevenueCssShares::where([
                    ['six_finance_final_id', '=', $sixFinanceFinal->id],
                ])->delete();

                $delete2= SixFinanceFormRevenueOwnRevenues::where([
                    ['six_finance_final_id', '=', $sixFinanceFinal->id],
                ])->delete();

                $delete3= SixFinanceFormRevenueTransferredResources::where([
                    ['six_finance_final_id', '=', $sixFinanceFinal->id],
                ])->delete();

                if(!$delete){
                    DB::rollback();
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d5";
                    return $returnData;
                }
                if(!$delete1){
                    DB::rollback();
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d6";
                    return $returnData;
                }
                if(!$delete2){
                    DB::rollback();
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d7";
                    return $returnData;
                }
                if(!$delete3){
                    DB::rollback();
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d8";
                    return $returnData;
                }

                $delete4= FinancialBalance::where([
                    ['six_finance_final_id', '=', $sixFinanceFinal->id],
                ])->delete();
				
				
				DB::table('six_finance_resubmit_trackers')->insert(
                ['six_finance_final_id' => $sixFinanceFinal->id, 'r_date' =>Carbon::now()->format("Y-m-d")]
				);

            }

            $checkRequest=$this->checkSixFinanceRevenueRequest($users->employee_code);

            if($checkRequest){
                DB::rollback();
                $returnData['msg']="You have pending category request. Kindly wait till your request is confirmed or ask admin to confirm it";
                return response()->json($returnData);
            }


            foreach($acts AS $li_act){
                foreach($own_revenue_cats AS $li_o){

                    $own_revenue = new SixFinanceFormRevenueOwnRevenues();
                    $own_revenue->six_finance_final_id = $sixFinanceFinal->id;
                    $own_revenue->act_id = $li_act->id;
                    $own_revenue->six_finance_revenue_own_revenue_cat_id = $li_o->id;
                    $own_revenue->own_revenue_value = $request->input('own_revenue_a_'.$li_act->id.'_o_'.$li_o->id);

                    if (!$own_revenue->save()) {
                        DB::rollback();
                        $returnData['msg'] = "Opps! Something went worng.#1";
                        return response()->json($returnData);
                    }
                }

                $arrear_tax = new SixFinanceFormRevenueArrearTaxes();
                $arrear_tax->six_finance_final_id = $sixFinanceFinal->id;
                $arrear_tax->act_id = $li_act->id;
                $arrear_tax->arrear_tax_value = $request->input('total_arrear_taxes_a_'.$li_act->id);

                if (!$arrear_tax->save()) {
                    DB::rollback();
                    $returnData['msg'] = "Opps! Something went worng.#2";
                    return response()->json($returnData);
                }

                foreach($css_shares AS $li_sh){

                    $c_share = new SixFinanceFormRevenueCssShares();
                    $c_share->six_finance_final_id = $sixFinanceFinal->id;
                    $c_share->share = 0;
                    $c_share->act_id = $li_act->id;
                    $c_share->six_finance_revenue_css_share_cat_id = $li_sh->id;
                    $c_share->share_value = $request->input('central_share_of_css_a_'.$li_act->id.'_s_'.$li_sh->id);

                    if (!$c_share->save()) {
                        DB::rollback();
                        $returnData['msg'] = "Opps! Something went worng.#3";
                        return response()->json($returnData);
                    }

                    $s_share = new SixFinanceFormRevenueCssShares();
                    $s_share->six_finance_final_id = $sixFinanceFinal->id;
                    $s_share->share = 1;
                    $s_share->act_id = $li_act->id;
                    $s_share->six_finance_revenue_css_share_cat_id = $li_sh->id;
                    $s_share->share_value = $request->input('state_share_of_css_a_'.$li_act->id.'_s_'.$li_sh->id);

                    if (!$s_share->save()) {
                        DB::rollback();
                        $returnData['msg'] = "Opps! Something went worng.#4";
                        return response()->json($returnData);
                    }
                }

                foreach($tr_cats_final AS $li_tr){
                    if(count($li_tr['sublist']) == 0){

                        $t_resource = new SixFinanceFormRevenueTransferredResources();
                        $t_resource->six_finance_final_id = $sixFinanceFinal->id;
                        $t_resource->act_id = $li_act->id;
                        $t_resource->six_finance_revenue_transferred_resources_cat_id = $li_tr['id'];
                        $t_resource->tr_value = $request->input('transfer_a_'.$li_act->id.'_t_'.$li_tr['id']);

                        if (!$t_resource->save()) {
                            DB::rollback();
                            $returnData['msg'] = "Opps! Something went worng.#5";
                            return response()->json($returnData);
                        }

                    }else{
                        //No Action Needed
                    }

                    foreach($li_tr['sublist'] AS $li_sub){

                        $t_resource_1 = new SixFinanceFormRevenueTransferredResources();
                        $t_resource_1->six_finance_final_id = $sixFinanceFinal->id;
                        $t_resource_1->act_id = $li_act->id;
                        $t_resource_1->six_finance_revenue_transferred_resources_cat_id = $li_sub->id;
                        $t_resource_1->tr_value = $request->input('transfer_a_'.$li_act->id.'_t_'.$li_sub->id);

                        if (!$t_resource_1->save()) {
                            DB::rollback();
                            $returnData['msg'] = "Opps! Something went worng.#6";
                            return response()->json($returnData);
                        }
                    }
                }
            }

            if($delete4){
                $six_finalArray=['final_submission_status' => 0, 'revenue_info' => 1, 'balance_info' => 0];
            }else{
                $six_finalArray=['revenue_info' => 1];
            }

            $six_final= SixFinanceFinals::where('id', $sixFinanceFinal->id)
                ->update($six_finalArray);

            if(!$six_final){
                DB::rollback();
                $returnData['msg']="Oops! Could not update.";
                return response()->json($returnData);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng.";
            return response()->json($returnData);
        }

        $returnData['msgType']=true;
        $returnData['msg']="Successfully done the task!";
        $returnData['data']=[];
        return response()->json($returnData);
    }

    public function getRevenueInfoBySixFinalId($id){
        $result=SixFinanceFormRevenueOwnRevenues::where('six_finance_final_id', '=', $id)->select('id')->first();
        return $result;

    }

    public function checkSixFinanceRevenueRequest($employee_code){
        $result=SixFinanceRevenueOwnRevenueCats::where([
            ['created_by', '=', $employee_code],
            ['is_active', '=', 0],
            ['cancel', '=', 0],
        ])->select('id')->first();

        $result1=SixFinanceRevenueCssShareCats::where([
            ['created_by', '=', $employee_code],
            ['is_active', '=', 0],
            ['cancel', '=', 0],
        ])->select('id')->first();

        $result2=SixFinanceRevenueTransferredResourcesCats::where([
            ['created_by', '=', $employee_code],
            ['is_active', '=', 0],
            ['cancel', '=', 0],
        ])->select('id')->first();

        if($result || $result1 || $result2){
            return true;
        }

        return false;
    }

}

