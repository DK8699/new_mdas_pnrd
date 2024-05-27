<?php

namespace App\Http\Controllers\six_finance;

use App\ConfigMdas;
use App\survey\six_finance\SixFinanceFinals;
use App\survey\six_finance\SixFinanceFormOtherRegisters;
use App\survey\six_finance\SixFinanceFormOthers;
use App\survey\six_finance\SixFinanceFormOtherSubs;
use App\survey\six_finance\SixFinanceOtherInfoRegisterCats;
use App\survey\six_finance\SixFinanceOtherInfoRoadCats;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Validator;

class OtherInfoController extends Controller
{
    //

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

                $six_finance_final_id=$sixFinanceFinal->id;

                $applicable_id=$six_finance_session_data['applicable_id'];
                $applicable_name=$six_finance_session_data['applicable_name'];

                $alreadySubmitted=NULL;

                $acts= DB::table('acts')->where('id', '<=', 5)->get();

                $cats= SixFinanceOtherInfoRoadCats::select('*')->get();

                $register_cats= SixFinanceOtherInfoRegisterCats::where('is_active', '=', 1)->select('*')->get();

                $otherInfoVal=NULL;
                $otherFinalSub=[];

                $check=$this->getOtherInfoBySixFinalId($six_finance_final_id);

                if($check){
                    $otherInfoVal=SixFinanceFormOthers::where([
                        ['six_finance_final_id', '=', $six_finance_final_id],
                    ])->select('*')->first();

                    $otherSubVal=SixFinanceFormOtherSubs::where([
                        ['six_finance_final_id', '=', $six_finance_final_id],
                        ['six_finance_form_other_id', '=', $otherInfoVal->id],
                    ])->select('*')->get();

                    $otherRegVal=SixFinanceFormOtherRegisters::where([
                        ['six_finance_final_id', '=', $six_finance_final_id],
                        ['six_finance_form_other_id', '=', $otherInfoVal->id],
                    ])->select('*')->get();

                    foreach ($otherSubVal AS $val){
                        $otherFinalSub["SIX_".$val->six_finance_final_id]["A_".$val->act_id]["C_".$val->six_finance_other_info_road_cat_id]=$val->length;
                    }

                    foreach($otherRegVal AS $reg){
                        $otherFinalReg["R_".$reg->six_finance_other_info_register_cat_id]=$reg->register_value;
                    }

                    $alreadySubmitted=1;
                }

                return view('survey.six_finance.other_info', compact('applicable_name', 'acts', 'cats', 'register_cats', 'six_finance_final_id', 'otherInfoVal', 'otherFinalSub', 'otherFinalReg', 'alreadySubmitted'));
            }else {
                return redirect()->route('dashboard');
            }
        }else {
            return redirect()->route('dashboard');
        }
    }


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

        $cats= SixFinanceOtherInfoRoadCats::select('*')->get();

        $register_cats= SixFinanceOtherInfoRegisterCats::where('is_active', '=', 1)->select('*')->get();

        foreach($cats AS $li_cat){
            foreach($acts AS $li_act){
                $validatorRule['length_c_'.$li_cat->id.'_a_'.$li_act->id]= "required|numeric|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:9999999999";

                $validatorMessages['length_c_'.$li_cat->id.'_a_'.$li_act->id.'.required']= "This field is required!";
                $validatorMessages['length_c_'.$li_cat->id.'_a_'.$li_act->id.'.numeric']= "Must be a number!";
                $validatorMessages['length_c_'.$li_cat->id.'_a_'.$li_act->id.'.regex']= "Decimal up to two digit places only!";
                $validatorMessages['length_c_'.$li_cat->id.'_a_'.$li_act->id.'.min']= "Negative values not accepted!";
                $validatorMessages['length_c_'.$li_cat->id.'_a_'.$li_act->id.'.max']= "Up to 10 digit number is allowed!";
            }
        }

        $validatorRule['present_account_audit_status']= "required|string|max:500";
        $validatorRule['trained_account_staff']= "required|in:1,2";
        $validatorRule['seperate_cashbook_maintained']= "required|in:1,2";

        foreach($register_cats AS $reg){
            $validatorRule['register_'.$reg->id]= "required|in:1,2";

            $validatorMessages['register_'.$reg->id.'.required']= "This field is required!";
            $validatorMessages['register_'.$reg->id.'.in']= "Select Yes or No to continue!";
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

            $check=$this->getOtherInfoBySixFinalId($sixFinanceFinal->id);

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

                $delete= SixFinanceFormOthers::where([
                    ['six_finance_final_id', '=', $sixFinanceFinal->id],
                ])->delete();

                $delete1= SixFinanceFormOtherSubs::where([
                    ['six_finance_final_id', '=', $sixFinanceFinal->id],
                ])->delete();

                $delete2= SixFinanceFormOtherRegisters::where([
                    ['six_finance_final_id', '=', $sixFinanceFinal->id],
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

                DB::table('six_finance_resubmit_trackers')->insert(
                    ['six_finance_final_id' => $sixFinanceFinal->id, 'r_date' => Carbon::now()->format("Y-m-d")]
                );
            }

            $otherInfo = new SixFinanceFormOthers();
            $otherInfo->six_finance_final_id = $sixFinanceFinal->id;

            $otherInfo->present_account_audit_status = $request->input('present_account_audit_status');
            $otherInfo->trained_account_staff = $request->input('trained_account_staff');
            $otherInfo->seperate_cashbook_maintained = $request->input('seperate_cashbook_maintained');

            if (!$otherInfo->save()) {
                DB::rollback();
                $returnData['msg'] = "Opps! Something went worng.#1";
                return response()->json($returnData);
            }

            foreach ($cats AS $li_cat) {
                foreach ($acts AS $li_act) {
                    $otherInfoSub = new SixFinanceFormOtherSubs();
                    $otherInfoSub->six_finance_final_id = $sixFinanceFinal->id;
                    $otherInfoSub->six_finance_form_other_id = $otherInfo->id;
                    $otherInfoSub->act_id = $li_act->id;
                    $otherInfoSub->six_finance_other_info_road_cat_id = $li_cat->id;
                    $otherInfoSub->length = $request->input('length_c_' . $li_cat->id . '_a_' . $li_act->id);

                    if(!$otherInfoSub->save()){
                        DB::rollback();
                        $returnData['msg'] = "Opps! Something went worng.#2";
                        return response()->json($returnData);
                    }
                }
            }

            foreach($register_cats AS $reg){
                $otherRegister = new SixFinanceFormOtherRegisters();
                $otherRegister->six_finance_final_id = $sixFinanceFinal->id;
                $otherRegister->six_finance_form_other_id = $otherInfo->id;
                $otherRegister->six_finance_other_info_register_cat_id = $reg->id;
                $otherRegister->register_value = $request->input('register_'.$reg->id);
                if(!$otherRegister->save()){
                    DB::rollback();
                    $returnData['msg'] = "Opps! Something went worng.#3";
                    return response()->json($returnData);
                }
            }

            $six_final= SixFinanceFinals::where('id', $sixFinanceFinal->id)
                ->update(['other_info' => 1]);

            if(!$six_final){
                DB::rollback();
                $returnData['msg']="Oops! Could not update.#4";
                return response()->json($returnData);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng.#2".$e->getMessage();
            return response()->json($returnData);
        }

        $returnData['msgType']=true;
        $returnData['msg']="Successfully done the task!";
        $returnData['data']=[];
        return response()->json($returnData);
    }


    public function getOtherInfoBySixFinalId($id){
        $result=SixFinanceFormOthers::where('six_finance_final_id', '=', $id)->select('id')->first();
        return $result;

    }


    /*public function getSixFinanceFinal($matchArray){
        $sixFinanceFinal=SixFinanceFinals::join('districts AS d', 'd.id', '=', 'six_finance_finals.district_id')
            ->leftjoin('zila_parishads AS z', 'z.id', '=', 'six_finance_finals.zila_id')
            ->leftjoin('anchalik_parishads AS a', 'a.id', '=', 'six_finance_finals.anchalik_id')
            ->leftjoin('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'six_finance_finals.gram_panchayat_id')

            ->leftjoin('councils AS c', 'c.id', '=', 'six_finance_finals.council_id')
            ->leftjoin('blocks AS b', 'b.id', '=', 'six_finance_finals.block_id')
            ->leftjoin('gram_panchyats AS v', 'v.gram_panchyat_id', '=', 'six_finance_finals.vdc_id')
            ->where($matchArray)
            ->select('six_finance_finals.*', 'd.district_name', 'z.zila_parishad_name', 'a.anchalik_parishad_name', 'g.gram_panchayat_name', 'c.council_name', 'b.block_name', 'v.gram_panchayat_name AS vcdc_vdc_mac_name')
            ->first();

        return $sixFinanceFinal;
    }*/
}
