<?php

namespace App\Http\Controllers\six_finance;

use App\BalanceModels\FinancialBalance;
use App\ConfigMdas;
use App\ExpenditureModels\FinancialExpenditure;
use App\NewSchemeModels\SchemeProposal;
use App\survey\six_finance\SixFinanceFinalDeletes;
use App\survey\six_finance\SixFinanceFinalDeleteSubs;
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
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DeleteInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'user_mdas']);
    }

    public function index(Request $request){

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

        $fg= $request->input('df');

        $chkArray = array("BAS", "STA", "REV", "EXP", "BAL", "OTH", "NEX");

        if (!in_array($fg, $chkArray)) {
            $returnData['msg']="Oops! Something went wrong. Please try again later.#3";
            return response()->json($returnData);
        }

        $checkReturn=$this->checkAndFormParts($fg);

        if(!$checkReturn){
            $returnData['msg'] = "Opps! Something went worng.";
            return response()->json($returnData);
        }

        $checkAlready=$this->checkAlreadyRequested($sixFinanceFinal->id);

        if($checkAlready){
            if($checkAlready->$checkReturn==1){
                $returnData['msg'] = "Delete request already sent to admin. Please wait while our admin verifies the request.";
                return response()->json($returnData);
            }
        }

        //------------------- CHECK CONFIG RULES ----------------------

        if(isset(ConfigMdas::allActiveList()->six_finance_delete_request_up_to_date)){
            if(ConfigMdas::allActiveList()->six_finance_delete_request_up_to_date < Carbon::now()->format("Y-m-d")){
                $returnData['msg']="Delete request to six assam state finance are current suspended. Please contact Admin for more details.";
                return response()->json($returnData);
            }
        }

        //------------------- CHECK CONFIG RULES ENDED ----------------------
		
		
		//------------------- DELETE LOCALLY STARTED ----------------------
		//--------- delete instantly when there is no request pending and final submit is zero and if verify is zero---------------------
		$sixFinanceTest=SixFinanceFinals::where([
            ['id', '=', $sixFinanceFinal->id]
        ])->first();

        if(!$sixFinanceTest){
            $returnData['msg'] = "Oops! Something went wrong.Please try again later";
            return response()->json($returnData);
        }elseif($sixFinanceTest->verify==1){
            $returnData['msg'] = "Sorry you can not delete the form because your form is verified. Please ask the admin for more details.";
            return response()->json($returnData);
        }elseif($sixFinanceTest->final_submission_status==0){
            $deleteLocally=$this->deleteLocally($sixFinanceFinal->id, $fg, $checkReturn);

            if(!$deleteLocally['msgType']){
                $returnData['msg'] = $deleteLocally['msg'];
                return response()->json($returnData);
            }else{
                return response()->json($deleteLocally);
            }
        }
		//------------------- DELETE LOCALLY STARTED ENDED ----------------------
		

        DB::beginTransaction();
        try {

            if($checkAlready){
                $dRequestUp= SixFinanceFinalDeletes::where('six_finance_final_id', $sixFinanceFinal->id)
                    ->update([$checkReturn => 1]);

                if(!$dRequestUp){
                    DB::rollback();
                    $returnData['msg'] = "Opps! Something went worng.#s1";
                    return response()->json($returnData);
                }

            }else{
                $dRequest = new SixFinanceFinalDeletes();
                $dRequest->six_finance_final_id = $sixFinanceFinal->id;
                $dRequest->$checkReturn = 1;

                if(!$dRequest->save()){
                    DB::rollback();
                    $returnData['msg'] = "Opps! Something went worng.#s2";
                    return response()->json($returnData);
                }
            }

            $dRequestSub= new SixFinanceFinalDeleteSubs();
            $dRequestSub->six_finance_final_id = $sixFinanceFinal->id;
            $dRequestSub->employee_code = $users->employee_code;
            $dRequestSub->form_parts = $fg;

            if(!$dRequestSub->save()){
                DB::rollback();
                $returnData['msg'] = "Opps! Something went worng.#s3";
                return response()->json($returnData);
            }

            if($fg=="REV" || $fg=="EXP"){
                $reqArray=['final_submission_status' => 0, $checkReturn => 0, 'balance_info' => 0];
            }else{
                $reqArray=['final_submission_status' => 0, $checkReturn => 0];
            }

            $sixFinalUp= SixFinanceFinals::where('id', $sixFinanceFinal->id)->update($reqArray);

            if(!$sixFinalUp){
                DB::rollback();
                $returnData['msg'] = "Opps! Something went worng.#s4";
                return response()->json($returnData);
            }

        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng.#EX";
            return response()->json($returnData);
        }

        DB::commit();

        $returnData['msgType']=true;
        $returnData['msg']="Successfully sent request to delete ".strtoupper($checkReturn).".";
        return response()->json($returnData);
    }
	
	// ------------------------  DELETE INSTANTLY BLOCK ---------------------------------------------------------
	
	private function deleteLocally($id, $df, $checkReturn){
        $returnData['msgType']=false;
        $returnData['data']=[];

        DB::beginTransaction();

        try {
            if ($df == "BAS") {

                $delete= SixFinanceFormBasic::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                if(!$delete){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d1";
                    return $returnData;
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
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d2";
                    return $returnData;
                }
                if(!$delete1){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d3";
                    return $returnData;
                }
                if(!$delete2){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d4";
                    return $returnData;
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
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d5";
                    return $returnData;
                }
                if(!$delete1){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d6";
                    return $returnData;
                }
                if(!$delete2){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d7";
                    return $returnData;
                }
                if(!$delete3){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d8";
                    return $returnData;
                }

                $delete4= FinancialBalance::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                /*if(!$delete4){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#7";
                    return response()->json($returnData);
                }*/

            } elseif ($df == "EXP") {
                $delete= FinancialExpenditure::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                if(!$delete){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d9";
                    return $returnData;
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
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d10";
                    return $returnData;
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
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d11";
                    return $returnData;
                }
                if(!$delete1){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d12";
                    return $returnData;
                }
                if(!$delete2){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d13";
                    return $returnData;
                }

            } elseif ($df == "NEX") {
                $delete= SchemeProposal::where([
                    ['six_finance_final_id', '=', $id],
                ])->delete();

                if(!$delete){
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#14";
                    return $returnData;
                }
            }else{
                $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d15";
                return $returnData;
            }

            if($df=="REV" || $df=="EXP"){
                $reqArray=['final_submission_status' => 0, $checkReturn => 0, 'balance_info' => 0];
            }else{
                $reqArray=['final_submission_status' => 0, $checkReturn => 0];
            }

            $sixFinalUp= SixFinanceFinals::where('id', $id)->update($reqArray);

            if(!$sixFinalUp){
                DB::rollback();
                $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d16";
                return $returnData;
            }

        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng.#DEX";
            return $returnData;
        }

        DB::commit();

        $returnData['msgType']=true;
        $returnData['data']=[];
        $returnData['msg'] = "Successfully deleted the data.".strtoupper($checkReturn)." Kindly reload the page.";
        return $returnData;
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

    public function checkAlreadyRequested($six_finance_final_id){
        $return= SixFinanceFinalDeletes::where([
            ['six_finance_final_id', '=', $six_finance_final_id],
        ])->select('*')->first();

        return $return;
    }

}
