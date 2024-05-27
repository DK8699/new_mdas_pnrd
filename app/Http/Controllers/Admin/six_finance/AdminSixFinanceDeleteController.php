<?php

namespace App\Http\Controllers\Admin\six_finance;

use App\BalanceModels\FinancialBalance;
use App\ExpenditureModels\FinancialExpenditure;
use App\NewSchemeModels\SchemeProposal;
use App\survey\six_finance\SixFinanceFinalDeletes;
use App\survey\six_finance\SixFinanceFinals;
use App\survey\six_finance\SixFinanceFormBasic;
use App\survey\six_finance\SixFinanceFormOtherRegisters;
use App\survey\six_finance\SixFinanceFormOthers;
use App\survey\six_finance\SixFinanceFormOtherSubs;
use App\survey\six_finance\SixFinanceFormRevenueArrearTaxes;
use App\survey\six_finance\SixFinanceFormRevenueCssShares;
use App\survey\six_finance\SixFinanceFormRevenueOwnRevenues;
use App\survey\six_finance\SixFinanceFormRevenueTransferredResources;
use App\survey\six_finance\SixFinanceFormStaffDetails;
use App\survey\six_finance\SixFinanceFormStaffs;
use App\survey\six_finance\SixFinanceFormStaffSalarySummaries;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminSixFinanceDeleteController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'admin_mdas']);
    }

    public function index(Request $request){

        $results= DB::table('six_finance_final_deletes AS d')
            ->join('six_finance_finals AS f', 'd.six_finance_final_id','=','f.id')

            ->join('districts AS dt', 'dt.id','=','f.district_id')
            ->join('applicables AS alp', 'alp.id','=','f.applicable_id')
            ->leftjoin('zila_parishads AS zp', 'zp.id', '=', 'f.zila_id')
            ->leftjoin('anchalik_parishads AS ap', 'ap.id', '=', 'f.anchalik_id')
            ->leftjoin('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'f.gram_panchayat_id')

            ->select('f.id','f.employee_code','dt.district_name', 'zp.zila_parishad_name', 'alp.applicable_name',
                'ap.anchalik_parishad_name', 'gp.gram_panchayat_name', 'd.basic_info', 'd.staff_info',
                'd.revenue_info', 'd.expenditure_info', 'd.balance_info', 'd.other_info', 'd.five_year_info')
            ->get();

        return view('admin.survey.six_finance.ad_delete_request_list', compact('results'));
    }

    public function deleteRequest(Request $request) {
        $returnData['msgType']=false;
        $returnData['data']=[];

        $id = $request->input('id'); // Six Finance Final ID
        $df = $request->input('df');

        $checkReturn = $this->checkAndFormParts($df);

        if (!$checkReturn) {
            $returnData['msg'] = "Oops! Something went wrong. Please try again later.#1";
            return response()->json($returnData);
        }

        $checkDeleteTable = $this->checkDeleteRequest($id, $checkReturn);

        if (!$checkDeleteTable) {
            $returnData['msg'] = "No records found against your request.";
            return response()->json($returnData);
        }

        DB::beginTransaction();
        try {
            if ($df == "BAS") {

                $delete= SixFinanceFormBasic::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                if(!$delete){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#2";
                    return response()->json($returnData);
                }

            } elseif ($df == "STA") {

                $delete= SixFinanceFormStaffs::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                $delete1= SixFinanceFormStaffDetails::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                $delete2= SixFinanceFormStaffSalarySummaries::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                if(!$delete){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#3";
                    return response()->json($returnData);
                }
                if(!$delete1){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#4";
                    return response()->json($returnData);
                }
                if(!$delete2){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#5";
                    return response()->json($returnData);
                }

            } elseif ($df == "REV") {

                $delete= SixFinanceFormRevenueArrearTaxes::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                $delete1= SixFinanceFormRevenueCssShares::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                $delete2= SixFinanceFormRevenueOwnRevenues::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                $delete3= SixFinanceFormRevenueTransferredResources::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                if(!$delete){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#6";
                    return response()->json($returnData);
                }
                if(!$delete1){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#71";
                    return response()->json($returnData);
                }
                if(!$delete2){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#8";
                    return response()->json($returnData);
                }
                if(!$delete3){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#9";
                    return response()->json($returnData);
                }

                $delete4= FinancialBalance::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                /*if(!$delete4){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#72";
                    return response()->json($returnData);
                }*/

            } elseif ($df == "EXP") {
                $delete= FinancialExpenditure::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                if(!$delete){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#6";
                    return response()->json($returnData);
                }

                $delete4= FinancialBalance::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                /*if(!$delete4){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#7";
                    return response()->json($returnData);
                }*/

            } elseif ($df == "BAL") {
                $delete= FinancialBalance::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                if(!$delete){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#7";
                    return response()->json($returnData);
                }

            } elseif ($df == "OTH") {
                $delete= SixFinanceFormOthers::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                $delete1= SixFinanceFormOtherSubs::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                $delete2= SixFinanceFormOtherRegisters::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                if(!$delete){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#8";
                    return response()->json($returnData);
                }
                if(!$delete1){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#9";
                    return response()->json($returnData);
                }
                if(!$delete2){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#10";
                    return response()->json($returnData);
                }

            } elseif ($df == "NEX") {
                $delete= SchemeProposal::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                if(!$delete){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#11";
                    return response()->json($returnData);
                }
            }else{
                $returnData['msg'] = "Oops! Something went wrong. Please try again later.#12";
                return response()->json($returnData);
            }

            $update= SixFinanceFinalDeletes::where([
                ['six_finance_final_id', '=', $id]
            ])->update([$checkReturn => 0]);

            if(!$update){
                $returnData['msg'] = "Oops! Something went wrong. Please try again later.#11";
                return response()->json($returnData);
            }

        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng.#EX";
            return response()->json($returnData);
        }

        DB::commit();

        $returnData['msgType']=true;
        $returnData['msg']="Successfully deleted ".strtoupper($checkReturn).".";
        return response()->json($returnData);
    }

    public function checkAndFormParts($fg){

        if($fg=="BAS"){
            return "basic_info";
        }elseif ($fg=="STA"){
            return "staff_info";
        }elseif ($fg=="REV"){
            return "revenue_info";
        }elseif ($fg=="EXP"){
            return "expenditure_info";
        }elseif ($fg=="BAL"){
            return "balance_info";
        }elseif ($fg=="OTH"){
            return "other_info";
        }elseif ($fg=="NEX"){
            return "five_year_info";
        }

        return false;
    }

    public function checkDeleteRequest($id, $checkReturn){

        $result= SixFinanceFinalDeletes::where([
            [$checkReturn, '=', 1],
            ['six_finance_final_id', '=', $id],
        ])->first();

        if($result){
            return $result;
        }

        return false;
    }

    public function cancelRequest(Request $request) {

        $returnData['msgType']=false;
        $returnData['data']=[];

        $id = $request->input('id');
        $df = $request->input('df');

        $checkReturn = $this->checkAndFormParts($df);

        if (!$checkReturn) {
            $returnData['msg'] = "Oops! Something went wrong. Please try again later.#1";
            return response()->json($returnData);
        }

        $checkDeleteTable = $this->checkDeleteRequest($id, $checkReturn);

        if (!$checkDeleteTable) {
            $returnData['msg'] = "No records found against your request.";
            return response()->json($returnData);
        }

        DB::beginTransaction();

        try {

            $update= SixFinanceFinalDeletes::where([
                ['six_finance_final_id', '=', $id]
            ])->update([$checkReturn => 0]);

            if(!$update){
                $returnData['msg'] = "Oops! Something went wrong. Please try again later.#1";
                return response()->json($returnData);
            }

            if($df=="REV" || $df=="EXP"){
                if($df=="REV"){
                    if($checkDeleteTable->expenditure_info==1){
                        $reqArray=[$checkReturn => 1, 'balance_info' => 0];
                    }else{
                        $reqArray=[$checkReturn => 1, 'balance_info' => 1];
                    }
                }else{
                    if($checkDeleteTable->expenditure_info==1){
                        $reqArray=[$checkReturn => 1, 'balance_info' => 0];
                    }else{
                        $reqArray=[$checkReturn => 1, 'balance_info' => 1];
                    }
                }
            }else{
                $reqArray=[$checkReturn => 1];
            }

            $update1= SixFinanceFinals::where([
                ['id', '=', $id]
            ])->update($reqArray);

            if(!$update1){
                $returnData['msg'] = "Oops! Something went wrong. Please try again later.#1";
                return response()->json($returnData);
            }

        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng.#EX";
            return response()->json($returnData);
        }

        DB::commit();

        $returnData['msgType']=true;
        $returnData['msg']="Successfully cancelled ".strtoupper($checkReturn).".";
        return response()->json($returnData);
    }
}
