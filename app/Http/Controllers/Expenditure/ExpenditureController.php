<?php

namespace App\Http\Controllers\Expenditure;

use App\BalanceModels\FinancialBalance;
use App\ConfigMdas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ExpenditureModels\Expenditure;
use App\ExpenditureModels\CategoryExpenditure;
use App\ExpenditureModels\ExpenditureCategory;
use App\ExpenditureModels\FinancialExpenditure;
use App\Http\Requests\Expenditure\ExpenditureRequest;
use App\Http\Requests\Expenditure\ExpenditureOtherRequest;
use App\CommonModels\Act;
use App\survey\six_finance\SixFinanceFinals;
use Auth;
use DB;

class ExpenditureController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'user_mdas']);
    }

    public function index(Request $request) {
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

        $expenditure_details = $this->get_expenditure_details();
        $expenditure = $expenditure_details[0];
        $financial_years = $expenditure_details[1];
        $category = $expenditure_details[2];

        //--------------------------------------------------------------------------------------------------------------
        //----------------------------------<<    AFTER FILL UP     >>--------------------------------------------------
        //--------------------------------------------------------------------------------------------------------------

        $dataFillFinal = NULL;
        $alreadySubmitted = NULL;
        $count = FinancialExpenditure::where([
            'six_finance_final_id' => $sixFinanceFinal->id
        ])->count();

        if ($count > 0) {
            $dataFill = FinancialExpenditure::where([
                ['six_finance_final_id', '=', $sixFinanceFinal->id]
            ])->select('*')->get();

            foreach ($dataFill AS $li) {
                $dataFillFinal["E_" . $li->expenditure_id]["A_" . $li->act_id] = $li->expenditure_cost;
            }

            $alreadySubmitted = 1;


            //echo json_encode($dataFillFinal);
        }



        return view('expenditure.financial_expenditure', compact('financial_years', 'category', 'expenditure', 'applicable_name', 'alreadySubmitted', 'dataFillFinal'));
    }

    public function save_expenditure(ExpenditureRequest $request) {
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
        $expenditure_details = $this->get_expenditure_details();
        $expenditure = $expenditure_details[0];
        $financial_years = $expenditure_details[1];
        $category = $expenditure_details[2];



        if ($this->get_user_request(Auth::user()->username) > 0) {
            return response()->json([
                'msg' => 'You have already requested for new expenditure category.'
            ]);
        }

        DB::beginTransaction();

        try{
            $count = FinancialExpenditure::where([
                'six_finance_final_id' => $sixFinanceFinal->id
            ])->count();

            $delete4=false;

            if ($count > 0) {

                //------------------- CHECK CONFIG RULES ----------------------------

                if(isset(ConfigMdas::allActiveList()->six_finance_delete_request_up_to_date)){
                    if(ConfigMdas::allActiveList()->six_finance_delete_request_up_to_date < Carbon::now()->format("Y-m-d")){
                        return response()->json([
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
                        'msg' => 'Oops! Something went wrong.Please try again later'
                    ]);
                }elseif($sixFinanceTest->verify==1){
                    DB::rollback();
                    return response()->json([
                        'msg' => 'Sorry you can not resubmit the form because your form is verified. Please ask the admin for more details.'
                    ]);
                }

                $delete= FinancialExpenditure::where([
                    ['six_finance_final_id', '=', $sixFinanceFinal->id],
                ])->delete();

                if(!$delete){
                    DB::rollback();
                    return response()->json([
                        'msg' => 'Oops! Something went wrong. Please try again later.#d9'
                    ]);
                }

                $delete4= FinancialBalance::where([
                    ['six_finance_final_id', '=', $sixFinanceFinal->id],
                ])->delete();
				
				DB::table('six_finance_resubmit_trackers')->insert(
                ['six_finance_final_id' => $sixFinanceFinal->id, 'r_date' => Carbon::now()->format("Y-m-d")]
				);

            }

            foreach ($expenditure as $value) {
                foreach ($financial_years as $value1) {
                    $financial_expenditure = new FinancialExpenditure;
                    $financial_expenditure->expenditure_id = $value['category_expenditure_id'];
                    $financial_expenditure->act_id = $value1['id'];
                    $financial_expenditure->applicable_id = $applicable_id;
                    $financial_expenditure->six_finance_final_id = $sixFinanceFinal->id;
                    $financial_expenditure->expenditure_cost = $request->input('expenditure' . $value['expenditure'] . "" . $value['category'] . "" . $value1['id']);
                    $financial_expenditure->save();
                }
            }

            $expenditure_category_id = $request->input('category_expenditure_id');

            foreach ($financial_years as $value1) {
                $financial_expenditure = new FinancialExpenditure;
                $financial_expenditure->expenditure_id = 34;
                $financial_expenditure->act_id = $value1['id'];
                $financial_expenditure->applicable_id = $applicable_id;
                $financial_expenditure->six_finance_final_id = $sixFinanceFinal->id;
                $financial_expenditure->expenditure_cost = $request->input('expenditure2' . $value1['id']);
                $financial_expenditure->save();
            }

            if($delete4){
                $six_finalArray=['final_submission_status' => 0, 'expenditure_info' => 1, 'balance_info' => 0];
            }else{
                $six_finalArray=['expenditure_info' => 1];
            }

            $six_final = SixFinanceFinals::where('id', $sixFinanceFinal->id)
                ->update($six_finalArray);

            if(!$six_final){
                DB::rollback();
                return response()->json([
                    'msg' => 'Oops! Could not update.'
                ]);
            }

        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng.";
            return response()->json($returnData);
        }
        DB::commit();

        return response()->json([]);
    }

    private function get_expenditure_details() {
        $expenditure = ExpenditureCategory::join('category_expenditures', 'expenditure_categories.id', '=', 'category_expenditures.category_id')
            ->join('expenditures', 'expenditures.id', '=', 'category_expenditures.expenditure_id')
            ->select('category_expenditures.id AS category_expenditure_id', 'expenditures.id AS expenditure', 'expenditure_categories.id AS category', 'expenditure_name', 'category_name')
            ->where([
                ['expenditure_categories.id', '!=', 2],
                ['is_active', '=', 1]
            ])
            ->get();
        $financial_years = Act::where('id', '<=', 5)->select('id', 'financial_year')->get();
        $category = ExpenditureCategory::select('id', 'category_name', 'list_order')->orderBy('list_order')
            ->get();
        return [
            $expenditure,
            $financial_years,
            $category
        ];
    }

    public function save_other_specification(ExpenditureOtherRequest $request) {
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
        $users = $request->session()->get('users');

        DB::transaction(function()use($request, $users) {
            $expenditure = new Expenditure;
            $expenditure->expenditure_name = $request->input('other_specify');
            $expenditure->employee_code = $users->employee_code;
            $expenditure->save();
            $category_expenditure = new CategoryExpenditure;
            $category_expenditure->expenditure_id = $expenditure->id;
            $category_expenditure->category_id = $request->input('category');
            $category_expenditure->save();
        });
    }

    private function get_user_request($employeeCode) {
        return Expenditure::where([
            'is_active' => 0,
            'cancel' => 0,
            'employee_code' => $employeeCode
        ])->count();
    }

}
