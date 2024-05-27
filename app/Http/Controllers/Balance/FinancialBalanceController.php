<?php

namespace App\Http\Controllers\Balance;

use App\ConfigMdas;
use App\ExpenditureModels\FinancialExpenditure;
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
use App\CommonModels\Act;
use App\Http\Requests\Balance\BalanceRequest;
use App\BalanceModels\FinancialBalance;
use App\survey\six_finance\SixFinanceFinals;
use App\CommonModels\GramPanchyat;
use App\CommonModels\GaonPanchayat;
use DB;

class FinancialBalanceController extends Controller {

    public function __construct()
    {
        $this->middleware(['auth', 'user_mdas']);
    }

    public function index(Request $request) {
        if (!$request->session()->exists('users')) {
            return redirect()->route('dashboard');
        }

        $users=$request->session()->get('users');

        if(!$request->session()->exists('six_finance_session_data') || !$request->session()->exists('sixFinanceFinal')){
            return redirect()->route('dashboard');
        }

        $six_finance_session_data=$request->session()->get('six_finance_session_data');
        $sixFinanceFinal=$request->session()->get('sixFinanceFinal');

        $applicable_id=$six_finance_session_data['applicable_id'];
        $applicable_name=$six_finance_session_data['applicable_name'];

        $balanceInfoFillFinal=NULL;

        //--------------------------------------------------------------------------------------------------------------
        //----------------------------------<<    AFTER FILL UP     >>--------------------------------------------------
        //--------------------------------------------------------------------------------------------------------------

        $acts=  Act::where('id', '<=', 5)->get();

        //REVENUE INFO
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

        $six_finance_final_id=$sixFinanceFinal->id;
        $alreadySubmitted=NULL;
        $grand_tot_revenue=NULL;

        $check=$this->getRevenueInfoBySixFinalId($sixFinanceFinal->id);

        $check1 = FinancialExpenditure::where([
            'six_finance_final_id' => $six_finance_final_id
        ])->count();

        if($check && $check1){

            $revenueInfoOwnFill= SixFinanceFormRevenueOwnRevenues::where([
                ['six_finance_final_id', '=', $six_finance_final_id],
            ])->select('act_id', DB::raw('sum(own_revenue_value) as own_revenue_value'))->groupBy('act_id')->get();

            foreach($revenueInfoOwnFill AS $li){
                $revenueInfoOwnFillFinal["A_".$li->act_id]=$li->own_revenue_value;
            }

            $revenueInfoArrearFill=SixFinanceFormRevenueArrearTaxes::where([
                ['six_finance_final_id', '=', $six_finance_final_id],
            ])->select('act_id', DB::raw('sum(arrear_tax_value) as arrear_tax_value'))->groupBy('act_id')->get();

            foreach($revenueInfoArrearFill AS $li){
                $revenueInfoArrearFillFinal["A_".$li->act_id]=$li->arrear_tax_value;
            }

            $revenueInfoShareFill= SixFinanceFormRevenueCssShares::where([
                ['six_finance_final_id', '=', $six_finance_final_id],
            ])->select('act_id', DB::raw('sum(share_value) as share_value'))->groupBy('act_id')->get();

            foreach($revenueInfoShareFill AS $li){
                $revenueInfoShareFillFinal["A_".$li->act_id]=$li->share_value;
            }

            $revenueInfoTRFill= SixFinanceFormRevenueTransferredResources::where([
                ['six_finance_final_id', '=', $six_finance_final_id],
            ])->select('act_id', DB::raw('sum(tr_value) as tr_value'))->groupBy('act_id')->get();

            foreach($revenueInfoTRFill AS $li){
                $revenueInfoTRFillFinal["A_".$li->act_id]=$li->tr_value;
            }

            foreach($acts AS $li_act){
                $grand_tot_revenue["A_".$li_act->id]=0;
                if(isset($revenueInfoOwnFillFinal["A_".$li_act->id]) && isset($revenueInfoArrearFillFinal["A_".$li_act->id]) && isset($revenueInfoShareFillFinal["A_".$li_act->id]) && isset($revenueInfoTRFillFinal["A_".$li_act->id])) {
                    $grand_tot_revenue["A_".$li_act->id] = (float)$revenueInfoOwnFillFinal["A_".$li_act->id] + (float)$revenueInfoArrearFillFinal["A_".$li_act->id] + (float)$revenueInfoShareFillFinal["A_".$li_act->id] + (float)$revenueInfoTRFillFinal["A_".$li_act->id];
                }
            }

            /*---------------------------GRAND TOTAL EXPENDITURE LIST-------------------------------------------------*/

            $expenditureInfoFill=FinancialExpenditure::where([
                ['six_finance_final_id', '=', $six_finance_final_id]
            ])->select('act_id', DB::raw('sum(expenditure_cost) as expenditure_cost'))->groupBy('act_id')->get();

            foreach ($expenditureInfoFill AS $li){
                $grand_tot_expenditure["A_".$li->act_id]=$li->expenditure_cost;
            }

            /*---------------------------PREPARING DATA OF EXPENDITURE LIST ENDED-------------------------------------*/

            /*-----------------------------------WHEN SUBMITTED BALANCE INFO------------------------------------------*/

            $count = FinancialBalance::where([
                'six_finance_final_id' => $sixFinanceFinal->id
            ])->count();

            if ($count > 0) {
                $balanceInfoFill=FinancialBalance::where([
                    'six_finance_final_id' => $sixFinanceFinal->id
                ])->select('*')->get();

                foreach($balanceInfoFill AS $li_b){
                    $balanceInfoFillFinal["O_A_".$li_b->act_id]=$li_b->opening_balance;
                    $balanceInfoFillFinal["C_A_".$li_b->act_id]=$li_b->closing_balance;
                }

                $alreadySubmitted=1;
            }

            $ready=1;
        }else{
            $ready=0;
        }
        //REVENUE INFO ENDED

        $balance_type = [
            'Opening balance at the beginning of the year',
            'Inflow during the year (Grand Total Revenue Info)',
            'Outflow during the year (Grand Total Capital Expenditure)',
            'Closing Balance [Inflow-Outflow+Opening Balance]'
        ];

        return view('balances.balance', compact('acts', 'balance_type', 'applicable_name', 'grand_tot_revenue', 'grand_tot_expenditure', 'ready', 'balanceInfoFillFinal', 'alreadySubmitted'));
    }

    public function save_financial_balance(BalanceRequest $request) {
        if (!$request->session()->exists('users')) {
            return redirect()->route('dashboard');
        }

        $users = $request->session()->get('users');

        if (!$request->session()->exists('six_finance_session_data') || !$request->session()->exists('sixFinanceFinal')) {
            return redirect()->route('dashboard');
        }

        $six_finance_session_data = $request->session()->get('six_finance_session_data');
        $sixFinanceFinal = $request->session()->get('sixFinanceFinal');

        $applicable_id = $six_finance_session_data['applicable_id'];
        $applicable_name = $six_finance_session_data['applicable_name'];

        $acts = Act::where('id', '<=', 5)->select('id', 'financial_year')->get();

        $balance_type = [
            'Opening balance at the begining of the year',
            'Inflow during the year(as the item 13 above)',
            'Outflow during the year(as the item 18 above)'
        ];


        //--------------------------------------------------------------------------------------------------------------
        //----------------------------------<<    CHECKUP     >>--------------------------------------------------
        //--------------------------------------------------------------------------------------------------------------

        $check=$this->getRevenueInfoBySixFinalId($sixFinanceFinal->id);

        $check1 = FinancialExpenditure::where([
            'six_finance_final_id' => $sixFinanceFinal->id
        ])->count();

        if(!$check || !$check1){
            return response()->json([
                'msgType'=>false,
                'msg' => 'Please submit Revenue Info & Expenditure Info First.'
            ]);
        }

        $six_finance_final_id=$sixFinanceFinal->id;

        $revenueInfoOwnFill= SixFinanceFormRevenueOwnRevenues::where([
            ['six_finance_final_id', '=', $six_finance_final_id],
        ])->select('act_id', DB::raw('sum(own_revenue_value) as own_revenue_value'))->groupBy('act_id')->get();

        foreach($revenueInfoOwnFill AS $li){
            $revenueInfoOwnFillFinal["A_".$li->act_id]=$li->own_revenue_value;
        }

        $revenueInfoArrearFill=SixFinanceFormRevenueArrearTaxes::where([
            ['six_finance_final_id', '=', $six_finance_final_id],
        ])->select('act_id', DB::raw('sum(arrear_tax_value) as arrear_tax_value'))->groupBy('act_id')->get();

        foreach($revenueInfoArrearFill AS $li){
            $revenueInfoArrearFillFinal["A_".$li->act_id]=$li->arrear_tax_value;
        }

        $revenueInfoShareFill= SixFinanceFormRevenueCssShares::where([
            ['six_finance_final_id', '=', $six_finance_final_id],
        ])->select('act_id', DB::raw('sum(share_value) as share_value'))->groupBy('act_id')->get();

        foreach($revenueInfoShareFill AS $li){
            $revenueInfoShareFillFinal["A_".$li->act_id]=$li->share_value;
        }

        $revenueInfoTRFill= SixFinanceFormRevenueTransferredResources::where([
            ['six_finance_final_id', '=', $six_finance_final_id],
        ])->select('act_id', DB::raw('sum(tr_value) as tr_value'))->groupBy('act_id')->get();

        foreach($revenueInfoTRFill AS $li){
            $revenueInfoTRFillFinal["A_".$li->act_id]=$li->tr_value;
        }

        foreach($acts AS $li_act){
            $grand_tot_revenue["A_".$li_act->id]=0;
            if(isset($revenueInfoOwnFillFinal["A_".$li_act->id]) && isset($revenueInfoArrearFillFinal["A_".$li_act->id]) && isset($revenueInfoShareFillFinal["A_".$li_act->id]) && isset($revenueInfoTRFillFinal["A_".$li_act->id])) {
                $grand_tot_revenue["A_".$li_act->id] = (float)$revenueInfoOwnFillFinal["A_".$li_act->id] + (float)$revenueInfoArrearFillFinal["A_".$li_act->id] + (float)$revenueInfoShareFillFinal["A_".$li_act->id] + (float)$revenueInfoTRFillFinal["A_".$li_act->id];
            }
        }

        /*---------------------------GRAND TOTAL EXPENDITURE LIST-------------------------------------------------*/

        $expenditureInfoFill=FinancialExpenditure::where([
            ['six_finance_final_id', '=', $six_finance_final_id]
        ])->select('act_id', DB::raw('sum(expenditure_cost) as expenditure_cost'))->groupBy('act_id')->get();

        foreach ($expenditureInfoFill AS $li){
            $grand_tot_expenditure["A_".$li->act_id]=$li->expenditure_cost;
        }

        /*---------------------------PREPARING DATA OF EXPENDITURE LIST ENDED-------------------------------------*/
        DB::beginTransaction();
        try {

            $count = FinancialBalance::where([
                'six_finance_final_id' => $sixFinanceFinal->id
            ])->count();

            if ($count > 0) {
                //------------------- CHECK CONFIG RULES ----------------------------

                if(isset(ConfigMdas::allActiveList()->six_finance_delete_request_up_to_date)){
                    if(ConfigMdas::allActiveList()->six_finance_delete_request_up_to_date < Carbon::now()->format("Y-m-d")){
                        return response()->json([
                            'msgType'=>false,
                            'msg' => 'Resubmit is currently suspended. Please contact admin for more details.'
                        ]);
                    }
                }

                $resubmitTracker=DB::table('six_finance_resubmit_trackers')->where([
                    ['six_finance_final_id', '=', $sixFinanceFinal->id],
                    ['r_date', '=', Carbon::now()->format("Y-m-d")],
                ])->count();

                if($resubmitTracker >= 10){
                    DB::rollback();
                    return response()->json([
                        'msgType'=>false,
                        'msg' => 'You have crossed the maximum number of resubmit that is 10 times per day. Kindly contact admin for more details!'
                    ]);
                }

                //------------------- CHECK CONFIG RULES ENDED ----------------------

                $sixFinanceTest=SixFinanceFinals::where([
                    ['id', '=', $sixFinanceFinal->id]
                ])->first();

                if(!$sixFinanceTest){
                    DB::rollback();
                    return response()->json([
                        'msgType'=>false,
                        'msg' => 'Oops! Something went wrong.Please try again later'
                    ]);
                }elseif($sixFinanceTest->verify==1){
                    DB::rollback();
                    return response()->json([
                        'msgType'=>false,
                        'msg' => 'Sorry you can not resubmit the form because your form is verified. Please ask the admin for more details.'
                    ]);
                }

                $delete= FinancialBalance::where([
                    ['six_finance_final_id', '=', $sixFinanceFinal->id],
                ])->delete();

                if(!$delete){
                    DB::rollback();
                    return response()->json([
                        'msgType'=>false,
                        'msg' => 'Something went wrong! pleas try again!'
                    ]);
                }

                DB::table('six_finance_resubmit_trackers')->insert(
                    ['six_finance_final_id' => $sixFinanceFinal->id, 'r_date' => Carbon::now()->format("Y-m-d")]
                );
            }

            foreach ($acts as $li_act) {
                $balance = new FinancialBalance;
                $balance->act_id = $li_act->id;
                $balance->applicable_id = $applicable_id;
                $balance->six_finance_final_id = $sixFinanceFinal->id;
                $balance->opening_balance = $request->input('balance0'.$li_act->id);
                $balance->inflow_balance = round((float)$grand_tot_revenue["A_".$li_act->id], 2);
                $balance->outflow_balance = round((float)$grand_tot_expenditure["A_".$li_act->id], 2);
                $balance->closing_balance = round(((float)$request->input('balance0'.$li_act->id)+(float)$grand_tot_revenue["A_".$li_act->id])-(float)$grand_tot_expenditure["A_".$li_act->id], 2);
                $save_status = $balance->save();

                if(!$save_status){
                    DB::rollback();
                    return response()->json([
                        'msgType'=>false,
                        'msg' => 'Something went wrong! pleas try again!'
                    ]);
                }
            }


            $six_final = SixFinanceFinals::where('id', $sixFinanceFinal->id)
                ->update(['balance_info' => 1]);

            if(!$six_final){
                DB::rollback();
                return response()->json([
                    'msgType'=>false,
                    'msg' => 'Something went wrong! pleas try again!'
                ]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng.";
            return response()->json($returnData);
        }


            return response()->json([
                'msgType'=>true,
                'msg' => 'Data has been successfully saved.!'
            ]);

    }

    public function match_anchalik_parishads() {
        $update_status = 0;
        $gaon_panchayat = GaonPanchayat::rightjoin('gram_panchyats','gaon_panchayats.id','=','gram_panchyats.gram_panchyat_id')->select('gaon_panchayats.anchalik_id AS anchalik','gram_panchyat_id AS gram')->get();
        DB::transaction(function()use($gaon_panchayat,&$update_status){
            foreach ($gaon_panchayat as $value) {
                GramPanchyat::where([
                    'gram_panchyat_id'=>$value['gram']
                ])->update([
                    'anchalik_id'=>$value['anchalik']
                ]);
            }
        });
        if($update_status > 0){
            echo 'Done';
        }
    }



    //REVENUE INFO
    public function getRevenueInfoBySixFinalId($id){
        $result=SixFinanceFormRevenueOwnRevenues::where('six_finance_final_id', '=', $id)->select('id')->first();
        return $result;

    }
    //REVENUE INFO ENDED


}
