<?php

namespace App\Http\Controllers\six_finance;

use App\survey\six_finance\SixFinanceGpSelectionList;
use App\survey\six_finance\SixFinanceFormBasic;
use App\survey\six_finance\ZilaParishad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Validator;
use App\survey\six_finance\SixFinanceFinals;
use App\ConfigMdas;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

class BasicQuestionController extends Controller {

    public function __construct()
    {
        $this->middleware(['auth', 'user_mdas']);
    }

    //******************************************************************************************************************
    //***************************************  Auth Files  *************************************************************
    //******************************************************************************************************************

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/login');
    }

    protected function loggedOut(Request $request)
    {
        //
    }

    protected function guard()
    {
        return Auth::guard();
    }

    //******************************************************************************************************************
    //******************************************************************************************************************
    //******************************************************************************************************************

    public function dashboard(Request $request){
		$level_selector=NULL;
		$applicables=[];
		
        if(!$request->session()->exists('users')){
            return $this->logout($request);
        }
		
		$users=$request->session()->get('users');

        if(!isset($users->district_code)){
            return $this->logout($request);
        }
		
		$district_code=$users->district_code;

        /*---------- FORGET SIX FINANCE SESSION ARRAY ------------*/
        $request->session()->forget('six_finance_session_data');
        $request->session()->forget('sixFinanceFinal');

        $districts=$this->getDistrictsById($users->district_code);
		
		
		$desigantion_access_zp_level=[16,17];
        $desigantion_access_zp_level_additional_with_districts=["13_708","14_707","14_709","15_705"];
        $desigantion_access_gp_level=[19];

        // ----------------------- DESIGNATION WISE CARDS DISPLAY -------------------

        //16->DPM
        //17->ADPM
        //19->GPC // [----709---]
        //14->District MIS Manager [----709 Hojai---]

        /*if($users->designation_id==16 || $users->designation_id==17 || $users->designation_id==19 
		|| ($users->district_code==709 && $users->designation_id==14) 
		|| ($users->district_code==705 && $users->designation_id==15) 
		|| ($users->district_code==708 && $users->designation_id==13) 
		|| ($users->district_code==707 && $users->designation_id==14)) {

            if ($districts->council_id) {
             
				$applicables=[];
            } else {
                if ($users->designation_id ==16 || $users->designation_id==17) {
                    $six_finance_selector = [1, 2];
                }elseif ($users->designation_id==14 || $users->designation_id==15 || $users->designation_id==13){
                    $six_finance_selector = [1, 2];
                } else {
                    $six_finance_selector = [3];
                }
				
				$applicables = DB::table('applicables')
                ->whereIn('id', $six_finance_selector)
                ->select('*')
                ->get();
            }
			
        }else{
            $applicables=[];
        }*/
		
		// -----------------IF USER IS SIX SCHEDULE DISTRICT THEY WILL SEE NO CARDS-------------------------------------

        if ($districts->council_id){
            $level_selector=NULL;
        }else if (in_array($users->designation_id, $desigantion_access_zp_level)
            || in_array($users->designation_id."_".$users->district_code, $desigantion_access_zp_level_additional_with_districts)){
            $level_selector="ZP";
            $applicables = DB::table('applicables')
                ->whereIn('id', [1,2])
                ->select('*')
                ->get();
        }else if (in_array($users->designation_id, $desigantion_access_gp_level)){
            $level_selector="GP";
            $applicables = DB::table('applicables')
                ->whereIn('id', [3])
                ->select('*')
                ->get();
        }

        // ----------------------- ENDED DESIGNATION WISE CARDS DISPLAY -------------
		if (!$request->session()->exists('applicables')) {
            $request->session()->put('applicables', $applicables);
        }
		

        return view('dashboard', compact('applicables', 'level_selector', 'district_code'));
    }

    public function index(Request $request, $applicable_id){

        if ($request->session()->exists('users')) {

            /*---------- FORGET SIX FINANCE SESSION ARRAY ------------*/
            $request->session()->forget('sixFinanceFinal');

            $users=$request->session()->get('users');

            $sixFinanceFinal=[];
			
			if (!in_array($applicable_id, [1,2,3,4,6,7])){
			  return redirect()->route('dashboard');
			}
            //16->DPM
            //17->ADPM
            //19->GPC
            if((($applicable_id==3 || $applicable_id==7) && $users->designation_id==16) 
			|| (($applicable_id==3 || $applicable_id==7) && $users->designation_id==17) 
			|| (($applicable_id==3 || $applicable_id==7) && $users->designation_id==14) 
			|| (($applicable_id==3 || $applicable_id==7) && $users->designation_id==15) 
			|| (($applicable_id==3 || $applicable_id==7) && $users->designation_id==13)){
                return redirect()->route('dashboard');
            }

            $districts=$this->getDistrictsById($users->district_code);

            if(($applicable_id==1 || $applicable_id==2 || $applicable_id==4 || $applicable_id==6) && $users->designation_id==18){
                return redirect()->route('dashboard');
            }

            $zillas=[];
            $anchaliks=[];
            $councils=[];
            $blocks=[];

            $applicable= DB::table('applicables')
                ->where('id', '=', $applicable_id)
                ->select('*')
                ->first();

            /* if(!$applicable){
                 return redirect()->route('dashboard');
             }*/

            $applicable_name=$applicable->applicable_name;


            if($districts->council_id){
                if($applicable_id==4 || $applicable_id==6 || $applicable_id==7){
                    $councils=$this->getCouncilsById($districts->council_id);

                    if($applicable_id==6 || $applicable_id==7){
                        $blocks=$this->getBlocksByDistrictId($users->district_code);
                    }
                }else{
                    return redirect()->route('dashboard');
                }
            }else{
                if($applicable_id==1 || $applicable_id==2 || $applicable_id==3) {
                    $zillas = $this->getZillasByDistrictId($users->district_code);

                    if ($applicable_id == 2) {
                        $anchaliks = $this->getAnchaliksByZillaId($zillas->id);
                    } elseif ($applicable_id == 3) {
                        $outcomes = $this->getZillasByEmpCode($users->employee_code);
					
                        if(!$zillas){
                            $districts=[];
                            $zillas=[];
                            $anchaliks = [];
                        }else{
							//echo "Testing1";
							//echo json_encode($outcomes);
                            $zillas=$outcomes['zillas'];
                            $districts=$outcomes['districts'];
                            $anchaliks = $this->getSelectedAnchalikByEmpCode($users->employee_code);
                        }
                    }
                }else{
                    return redirect()->route('dashboard');
                }
            }
            //$request->session()->forget('users');

            $six_finance_session_data=[
                'applicable_name' => $applicable_name,
                'applicable_id' => $applicable_id
            ];

            $request->session()->put('six_finance_session_data', $six_finance_session_data);

            if ($request->session()->exists('sixFinanceFinal')) {
                $sixFinanceFinal=$request->session()->get('sixFinanceFinal');
            }

            //echo json_encode($gps);

            return view('survey.six_finance.six_finance_form', compact('districts', 'zillas', 'councils', 'anchaliks', 'blocks', 'gps', 'applicable_id', 'applicable_name', 'sixFinanceFinal'));

        }else {
            return redirect()->route('dashboard');
        }
    }

    public function six_finance_dashboard(Request $request){
        if ($request->session()->exists('users')) {

            $users=$request->session()->get('users');

            if($request->session()->exists('six_finance_session_data') && $request->session()->exists('sixFinanceFinal')){
                $six_finance_session_data=$request->session()->get('six_finance_session_data');
                $sixFinanceFinal=$request->session()->get('sixFinanceFinal');

                $applicable_id=$six_finance_session_data['applicable_id'];
                $applicable_name=$six_finance_session_data['applicable_name'];

                $matchArray=["six_finance_finals.id"=>$sixFinanceFinal->id];

                $sixFinanceFinal=$this->getSixFinanceFinal($matchArray);

                return view('survey.six_finance.six_finance_form_dashboard', compact('applicable_id', 'applicable_name', 'sixFinanceFinal'));

            }else {
                return redirect()->route('dashboard');
            }
        }else {
            return redirect()->route('dashboard');
        }
    }

    public function basic(Request $request){

        if ($request->session()->exists('users')) {

            $users=$request->session()->get('users');

            if($request->session()->exists('six_finance_session_data') && $request->session()->exists('sixFinanceFinal')){
                $six_finance_session_data=$request->session()->get('six_finance_session_data');
                $sixFinanceFinal=$request->session()->get('sixFinanceFinal');
                $six_finance_final_id=$sixFinanceFinal->id;

                $applicable_id=$six_finance_session_data['applicable_id'];
                $applicable_name=$six_finance_session_data['applicable_name'];

                $panchayatArray=[1,2,3];
                $basicInfoFill=NULL;
                $alreadySubmitted=NULL;

                if (in_array($applicable_id, $panchayatArray)) {
                    $electionName="Panchayat";
                } else {
                    $electionName="Council";
                }

                $check=$this->getSixFinanceFormBasicBySixFinalId($sixFinanceFinal->id);

                if($check){
                    $basicInfoFill= SixFinanceFormBasic::where([
                        ['six_finance_final_id','=', $six_finance_final_id],
                    ])->select('*')->first();

                    $alreadySubmitted=1;
                }

                return view('survey.six_finance.basic_info', compact('applicable_id', 'applicable_name', 'electionName', 'six_finance_final_id', 'basicInfoFill', 'alreadySubmitted'));
            }else {
                return redirect()->route('dashboard');
            }
        }else {
            return redirect()->route('dashboard');
        }
    }

    public function basic_save(Request $request){

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


        $messages = [
            'app_name.required' => $applicable_name.' name is required!',
            'app_name.max' => $applicable_name.' name must not exceed 100 characters!',

            'app_area.required' => $applicable_name.' area is required!',
            'app_area.regex' => $applicable_name.' area up to two decimal points allowed!',

            'app_house_nos.required' => 'No. of household in '.$applicable_name.' is required!',
            'app_house_nos.numeric' => 'No. of household in '.$applicable_name.' must be numeric value!',
            'app_house_nos.max' => 'No. of household in '.$applicable_name.' must not exceed 10 digits!',

            'pop_male.required' => 'Male count is required!',
            'pop_male.numeric' => 'Male count must be numeric value!',
            'pop_male.max' => 'Male count must not exceed 10 digits',

            'pop_female.required' => 'Female count is required!',
            'pop_female.numeric' => 'Female count must be numeric value!',
            'pop_female.max' => 'Female count must not exceed 10 digits',

            'pop_sc.required' => 'SC count is required!',
            'pop_sc.numeric' => 'SC count must be numeric value!',
            'pop_sc.max' => 'SC count must not exceed 10 digits',

            'pop_st.required' => 'ST count is required!',
            'pop_st.numeric' => 'ST count must be numeric value!',
            'pop_st.max' => 'ST count must not exceed 10 digits',

            'pop_total.required' => 'Total count is required!',
            'pop_total.numeric' => 'Total count must be numeric value!',
            'pop_total.max' => 'Total count must not exceed 10 digits',

            'election_dt.required' => 'Election Date is required!',
            'election_dt.date_format' => 'Election date format is invalid!',

            'app_household_rented.required' => 'Please select Yes or No',
            'app_household_rented.in' => 'Please select Yes or No',

            'app_monthly_rent.required_if' => 'Monthly rent is required',
            'app_monthly_rent.regex' => 'Monthly rent value up to two decimal points are allowed',

            //------------ NETWORK CONNECTIVITY ----------------------------------------------------------------
            'network_dt.required' => 'This is required!',
            'network_dt.date_format' => 'This format is invalid!',

            'nodal_mobile_no.required' => 'Nodal Mobile No. is required!',
            'nodal_mobile_no.numeric' => 'Nodal Mobile No. must be numeric value!',
            'nodal_mobile_no.min' => 'Nodal Mobile No. is invalid',
            'nodal_mobile_no.max' => 'Nodal Mobile No. must not exceed 10 digits',

            'nofn_status.required' => 'This is required!',
            'nofn_status.in' => 'Please select the options!',

            'bsnl_i_con.required' => 'This is required!',
            'bsnl_i_con.in' => 'Please select Yes or No',

            'bsnl_c_op.required_if' => 'This is required!',
            'bsnl_c_op.in' => 'Please select Yes or No',

            'bsnl_i_speed.required_if' => 'This is required!',
            'bsnl_i_speed.numeric' => 'This must be numeric value!',
            'bsnl_i_speed.min' => 'Internet Speed must be greater than zero!',
            'bsnl_i_speed.max' => 'Internet Speed must not exceed 10 digits',

            'bsnl_c_fully.required_if' => 'This is required!',
            'bsnl_c_fully.in' => 'Please select Yes or No',

            'bsnl_l_wifi_m.required_if' => 'This is required!',
            'bsnl_l_wifi_m.in' => 'Please select Yes or No',
        ];

        $validatorArray=[
            'app_name' => 'required|string|max:100',
            'app_area' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'app_house_nos' => 'required|numeric|min:0|max:9999999999',

            'pop_male' => 'required|numeric|min:0|max:9999999999',
            'pop_female' => 'required|numeric|min:0|max:9999999999',
            'pop_sc' => 'required|numeric|min:0|max:9999999999',
            'pop_st' => 'required|numeric|min:0|max:9999999999',
            'pop_total' => 'required|numeric|min:0|max:9999999999',

            'election_dt' => 'required|date_format:Y-m-d',
            'app_household_rented' => 'required|in:1,2',
            'app_monthly_rent' => 'required_if:app_household_rented,2|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|nullable',

            //------------ NETWORK CONNECTIVITY ----------------------------------------------------------------

            /*'network_dt' => 'required|date_format:Y-m-d',
            'nodal_mobile_no' => 'required|numeric|min:6000000000|max:9999999999',
            'nofn_status' => 'required|in:1,2,3',
            'bsnl_i_con' => 'required|in:1,2',

            'bsnl_c_op' => 'required_if:bsnl_i_con,1|in:1,2|nullable',
            'bsnl_i_speed' => 'required_if:bsnl_c_op,1|numeric|min:0|max:9999999999|nullable',
            'bsnl_c_fully' => 'required_if:bsnl_c_op,1|in:1,2|nullable',
            'bsnl_l_wifi_m' => 'required_if:bsnl_i_con,1|in:1,2|nullable',*/

        ];

        if($applicable_id==2 || $applicable_id==3) {
            $validatorArray['network_dt'] = 'date_format:Y-m-d|nullable';
            $validatorArray['nodal_mobile_no'] = 'numeric|min:6000000000|max:9999999999|nullable';
            $validatorArray['nofn_status'] = 'in:1,2,3|nullable';
            $validatorArray['bsnl_i_con'] = 'in:1,2|nullable';

            $validatorArray['bsnl_c_op'] = 'required_if:bsnl_i_con,1|in:1,2|nullable';
            $validatorArray['bsnl_i_speed'] = 'required_if:bsnl_c_op,1|numeric|min:0|max:9999999999|nullable';
            $validatorArray['bsnl_c_fully'] = 'required_if:bsnl_c_op,1|in:1,2|nullable';
            $validatorArray['bsnl_l_wifi_m'] = 'required_if:bsnl_i_con,1|in:1,2|nullable';
        }

        //echo json_encode($validatorArray);

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {

            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);

        }
		DB::beginTransaction();
        try {
			
			$check=$this->getSixFinanceFormBasicBySixFinalId($sixFinanceFinal->id);

			if($check){
					//------------------- CHECK CONFIG RULES ----------------------------

					if(isset(ConfigMdas::allActiveList()->six_finance_delete_request_up_to_date)){
						if(ConfigMdas::allActiveList()->six_finance_delete_request_up_to_date < Carbon::now()->format("Y-m-d")){
							$returnData['msg']="Resubmit is currently suspended. Please contact admin for more details.";
							return response()->json($returnData);
						}
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

					$delete= SixFinanceFormBasic::where([
						['six_finance_final_id', '=', $sixFinanceFinal->id],
					])->delete();

					if(!$delete){
						DB::rollback();
						$returnData['msg'] = "Oops! Something went wrong. #d1";
						return $returnData;
					}
			}

			$matchArray=["six_finance_finals.id"=>$sixFinanceFinal->id];

			$basic= new SixFinanceFormBasic();

			$basic->six_finance_final_id= $sixFinanceFinal->id;
			$basic->app_name= $request->input('app_name');
			$basic->app_area= $request->input('app_area');
			$basic->app_house_nos= $request->input('app_house_nos');
			$basic->election_date= $request->input('election_dt');
			$basic->app_household_rented= $request->input('app_household_rented');
			$basic->app_monthly_rent= $request->input('app_monthly_rent');

			$basic->pop_male= $request->input('pop_male');
			$basic->pop_female= $request->input('pop_female');
			$basic->pop_sc= $request->input('pop_sc');
			$basic->pop_st= $request->input('pop_st');
			$basic->pop_total= (int)$request->input('pop_male')+(int)$request->input('pop_female');

			if($applicable_id==2 || $applicable_id==3) {
				$basic->network_dt = $request->input('network_dt');
				$basic->nodal_mobile_no = $request->input('nodal_mobile_no');
				$basic->nofn_status = $request->input('nofn_status');
				$basic->bsnl_i_con = $request->input('bsnl_i_con');

				$basic->bsnl_c_op = $request->input('bsnl_c_op');
				$basic->bsnl_i_speed = $request->input('bsnl_i_speed');
				$basic->bsnl_c_fully = $request->input('bsnl_c_fully');
				$basic->bsnl_l_wifi_m = $request->input('bsnl_l_wifi_m');
			}

			if($basic->save()){
				$six_final= SixFinanceFinals::where($matchArray)
					->update(['basic_info' => 1]);

				if(!$six_final){
					DB::rollback();
					$returnData['msg']="Oops! Could not update.";
					return response()->json($returnData);
				}
			}else{
				DB::rollback();
                $returnData['msg']="Oops! Could not save data.";
                return response()->json($returnData);
			}
		}catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng.#BAS";
            return $returnData;
        }

        DB::commit();

        $returnData['msgType']=true;
        $returnData['msg']="Successfully submitted the basic_info!";
        $returnData['data']=[];
        return response()->json($returnData);
    }

    public function getSixFinanceFormBasicBySixFinalId($id){
        $result=SixFinanceFormBasic::where('six_finance_final_id', '=', $id)->select('id')->first();
        return $result;
    }

    public function getBasicQuestions(){

        $results= DB::table('questionnaires')
            ->where('applicable_id', '=',1)
            ->orWhere('applicable_id', '=', NULL)
            ->select('question')
            ->get();


        return $results;

    }

    /********************************************  GET DISTRICTS BY ID ************************************************/

    public function getDistrictsById($district_code){
        $districts= DB::table('districts')
            ->where('id', '=', $district_code)
            ->select('id', 'district_name', 'council_id')
            ->first();

        return $districts;

    }

    /********************************************  GET ZILLA BY DISTRICT ID *******************************************/

    public function getZillasByDistrictId($district_code){
        $zillas= DB::table('zila_parishads')
            ->where('district_id', '=', $district_code)
            ->select('id', 'zila_parishad_name')
            ->first();

        return $zillas;

    }

    /********************************************  GET ANCHALIK BY ZILLA ID *******************************************/

    public function getAnchaliksByZillaId($zila_id){
        $anchaliks= DB::table('anchalik_parishads')
            ->where('zila_id', '=', $zila_id)
            ->select('id', 'anchalik_parishad_name')
            ->get();

        return $anchaliks;

    }

    /********************************************  GET COUNCIL BY ID **************************************************/

    public function getCouncilsById($id){
        $councils= DB::table('councils')
            ->where('id', '=', $id)
            ->select('id', 'council_name')
            ->first();

        return $councils;

    }

    /********************************************  GET BLOCKS BY DISTRICT ID ******************************************/

    public function getBlocksByDistrictId($district_code){
        $blocks= DB::table('blocks')
            ->where('district_id', '=', $district_code)
            ->select('id', 'block_name')
            ->get();

        return $blocks;

    }

    /********************************************  GET GP BY ANCHALIK ID AND EMPLOYEE CODE **********************************************/

    public function getGPsByAnchalikId(Request $request){

        $returnData['msgType']=false;
        $returnData['data']=[];

        $anchalik_id= $request->input('anchalik_code');

        if(!$anchalik_id){
            $returnData['msg']="GP List not found";
            return response()->json($returnData);
        }

        $users=$request->session()->get('users');

        $gps= DB::table('six_finance_gp_selection_lists')
            ->join('gram_panchyats', 'six_finance_gp_selection_lists.gp_code', '=', 'gram_panchyats.gram_panchyat_id')
            ->where([
                ['six_finance_gp_selection_lists.anchalik_code', '=', $anchalik_id],
                ['six_finance_gp_selection_lists.emp_code', '=', $users->employee_code],
                ['vcc', '=', NULL],
            ])
            ->select('gram_panchyats.gram_panchyat_id AS id', 'gram_panchyats.gram_panchayat_name')
            ->get();

        if(empty($gps)){
            $returnData['msg']="GP List not found";
            return response()->json($returnData);
        }

        $returnData['msgType']=true;
        $returnData['data']=$gps;
        $returnData['msg']="Success";
        return response()->json($returnData);;
    }

    /********************************************  BY EMPLOYEE CODE **********************************************/

    public function getSelectedAnchalikByEmpCode($emp_code){
        $list= SixFinanceGpSelectionList::join('anchalik_parishads', 'anchalik_parishads.id', '=', 'six_finance_gp_selection_lists.anchalik_code')
            ->where([
            ['emp_code', '=', $emp_code]
            ])->select('anchalik_parishads.id', 'anchalik_parishads.anchalik_parishad_name')
            ->distinct('anchalik_parishads.id')
            ->get();

        return $list;

    }

    private function getZillasByEmpCode($emp_code){
        $list= SixFinanceGpSelectionList::where([
                ['emp_code', '=', $emp_code]
            ])->distinct('zila_id')->count('zila_id');
        //echo $list;
        if($list != 1){
            return false;
        }

        $list= SixFinanceGpSelectionList::join('zila_parishads', 'zila_parishads.id', '=', 'six_finance_gp_selection_lists.zila_id')
            ->where([
                ['emp_code', '=', $emp_code]
            ])->select('zila_parishads.id', 'zila_parishads.zila_parishad_name')
            ->first();

        $list1= ZilaParishad::join('districts', 'zila_parishads.district_id', '=', 'districts.id')
            ->where([
                ['zila_parishads.id', '=', $list->id]
            ])->select('districts.id', 'districts.district_name')
            ->first();

        return array("zillas"=>$list, "districts"=>$list1);
    }

    /********************************************  GET GP BY GP ID **********************************************/

    public function getAnchaliksById($id){

        $anchaliks= DB::table('anchalik_parishads')
            ->where('id', '=', $id)
            ->select('id', 'anchalik_parishad_name')
            ->first();

        return $anchaliks;
    }

    public function getGPsByBlockId(Request $request){

        $returnData['msgType']=false;
        $returnData['data']=[];

        $block_id= $request->input('block_code');

        if(!$block_id){
            $returnData['msg']="GP List not found";
            return response()->json($returnData);
        }

        $gps= DB::table('gram_panchyats')
            ->where([
                ['block_id', '=', $block_id],
            ])
            ->select('gram_panchyat_id AS id', 'gram_panchayat_name')
            ->get();

        if(empty($gps)){
            $returnData['msg']="GP List not found";
            return response()->json($returnData);
        }

        $returnData['msgType']=true;
        $returnData['data']=$gps;
        $returnData['msg']="Success";
        return response()->json($returnData);;
    }

    public function saveAndCheckSixFinance(Request $request){

        $returnData['msgType']=false;
        $returnData['data']=[];

        if (!$request->session()->exists('users')) {
            $returnData['msg']="User Not Found. Please login again";
            return response()->json($returnData);
        }else if (!$request->session()->exists('six_finance_session_data')) {
            $returnData['msg']="Oops! Something went wrong. Please try again later";
            return response()->json($returnData);
        }

        $users=  $request->session()->get('users');

        $six_finance_session_data=  $request->session()->get('six_finance_session_data');

        $applicable_id= $six_finance_session_data['applicable_id'];

        $messages = [
            'district_code.required' => 'District is required!',
            'district_code.exists' => 'District is Invalid!',

            'zilla_code.required' => 'Zila Parishad is required!',
            'zilla_code.exists' => 'Zila Parishad is Invalid!',
            'anchalik_code.required' => 'Anchalik Panchayat is required!',
            'anchalik_code.exists' => 'Anchalik Panchayat is Invalid!',
            'gp_code.required' => 'GP is required! ',
            'gp_code.exists' => 'GP is Invalid! ',

            'council_id.required' => 'Council is required!',
            'council_id.exists' => 'Council is Invalid!',
            'block_code.required' => 'Block is required!',
            'block_code.exists' => 'Block is Invalid!',
            'v_code.required' => 'VCDC/VDC/MAC is required!',
            'v_code.exists' => 'VCDC/VDC/MAC is Invalid!',
        ];


        $validatorSelector =$this->selectValidatorArray($applicable_id);

        if(empty($validatorSelector)){
            $returnData['msg']="Oops! something went wrong. Please try again later!";
            return response()->json($returnData);
        }

        $validator = Validator::make($request->all(), $validatorSelector, $messages);

        if ($validator->fails()) {

            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);

        }

        $zilla_code=NULL;
        $anchalik_code=NULL;
        $gp_code=NULL;
        $council_id=NULL;
        $block_code=NULL;
        $v_code=NULL;
        $matchArray=[];

        $district_code=$request->input('district_code');

        if($applicable_id==1 || $applicable_id==2){

            $zilla_code=$request->input('zilla_code');

            if($district_code!=$users->district_code){
                $returnData['msg']="Sorry District is not assign to you.";
                return response()->json($returnData);
            }

            $ckeckZilla=$this->getZillasByDistrictId($users->district_code);

            if(!$ckeckZilla){
                $returnData['msg']="Invalid Zila Parishad.";
                return response()->json($returnData);
            }

            if($ckeckZilla->id!=$zilla_code){
                $returnData['msg']="Sorry Zila Parishad is not assign to you.";
                return response()->json($returnData);
            }

            if($applicable_id==2){
                $anchalik_code=$request->input('anchalik_code');

                $checkAnchalik=$this->getAnchaliksByZillaId($zilla_code);

                $checkAnchalikArray=[];

                foreach ($checkAnchalik AS $an){
                    array_push($checkAnchalikArray, $an->id);
                }

                //echo json_encode($checkAnchalikArray);

                if (!in_array($anchalik_code, $checkAnchalikArray)) {
                    $returnData['msg']="Sorry Anchalik Panchayat is not assign to you.";
                    return response()->json($returnData);
                }
            }

            $matchArray=[
                "six_finance_finals.employee_code" => $users->employee_code,
                "six_finance_finals.district_id" => $district_code,
                "six_finance_finals.zila_id" => $zilla_code,
                "six_finance_finals.anchalik_id" => $anchalik_code,
                "six_finance_finals.gram_panchayat_id" => $gp_code
            ];

        }elseif($applicable_id==3){

                $ckeckZilla=$this->getZillasByDistrictId($district_code);

                if(!$ckeckZilla){
                    $returnData['msg']="Invalid Zila Parishad.";
                    return response()->json($returnData);
                }
                $zilla_code=$request->input('zilla_code');
                $anchalik_code=$request->input('anchalik_code');

                $checkAnchalik=$this->getAnchaliksByZillaId($zilla_code);

                $checkAnchalikArray=[];

                foreach ($checkAnchalik AS $an){
                    array_push($checkAnchalikArray, $an->id);
                }

                if (!in_array($anchalik_code, $checkAnchalikArray)) {
                    $returnData['msg']="Sorry Anchalik Panchayat is not assign to you.";
                    return response()->json($returnData);
                }

                $gp_code=$request->input('gp_code');

                $ckeckSelected=$this->checkIfGpExistsInSelectedGps($gp_code, $anchalik_code, $users->employee_code);

                if(!$ckeckSelected){
                    $returnData['msg']="Sorry GP not assign to you.";
                    return response()->json($returnData);
                }


            $matchArray=[
                "six_finance_finals.employee_code" => $users->employee_code,
                "six_finance_finals.district_id" => $district_code,
                "six_finance_finals.zila_id" => $zilla_code,
                "six_finance_finals.anchalik_id" => $anchalik_code,
                "six_finance_finals.gram_panchayat_id" => $gp_code
            ];
        }elseif($applicable_id==4 || $applicable_id==6 || $applicable_id==7){

            $council_id=$request->input('council_id');

            if($applicable_id==6 || $applicable_id==7){
                $block_code=$request->input('block_code');
            }

            if($applicable_id==7){
                $v_code=$request->input('v_code');
            }

            $matchArray=[
                "six_finance_finals.employee_code" => $users->employee_code,
                "six_finance_finals.district_id" => $district_code,
                "six_finance_finals.council_id" => $council_id,
                "six_finance_finals.block_id" => $block_code,
                "six_finance_finals.vdc_id" => $v_code
            ];

        }

        $sixFinanceFinal=$this->getSixFinanceFinal($matchArray);

        if(!$sixFinanceFinal){
            /*-------------- INSERT NEW ENTRY INTO SIX FINANCE TABLE ------------------------------*/

            $six_table = new SixFinanceFinals();

            $six_table->employee_code=$users->employee_code;
            $six_table->applicable_id=$applicable_id;

            $six_table->district_id=$district_code;
            $six_table->zila_id=$zilla_code;
            $six_table->anchalik_id=$anchalik_code;
            $six_table->gram_panchayat_id=$gp_code;

            $six_table->council_id=$council_id;
            $six_table->block_id=$block_code;
            $six_table->vdc_id=$v_code;

            if($six_table->save()){
                $matchArray=["six_finance_finals.id"=>$six_table->id];

                $sixFinanceFinal=$this->getSixFinanceFinal($matchArray);
            }else{
                $returnData['msg']="Could not save. Please try again later!";
                return response()->json($returnData);
            }


        }else{
            /*if($sixFinanceFinal->final_submission_status==1){
                $returnData['msg']="Forms already submitted!";
                return response()->json($returnData);
            }else*/if ($sixFinanceFinal->applicable_id!=$applicable_id){
                $returnData['msg']="Applicables didnot match. Please contact admin!";
                return response()->json($returnData);
            }
        }

        $request->session()->put('sixFinanceFinal', $sixFinanceFinal);

        $returnData['msgType']=true;
        $returnData['data']=$sixFinanceFinal;
        return response()->json($returnData);
    }

    public function checkIfGpExistsInSelectedGps($gp_code, $anchalik_code, $emp_code){
        $list= SixFinanceGpSelectionList::where([
            ['gp_code', '=', $gp_code],
            ['anchalik_code', '=', $anchalik_code],
            ['emp_code', '=', $emp_code],
        ])->first();

        return $list;
    }

    public function getSixFinanceFinal($matchArray){
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
    }

    public function selectValidatorArray($applicable_id){

        $validatorSelector=[];

        if($applicable_id==1){
            $validatorSelector=[
                'district_code' => 'required|exists:districts,id',
                'zilla_code' => 'required|exists:zila_parishads,id',
            ];
        }elseif ($applicable_id==2){
            $validatorSelector=[
                'district_code' => 'required|exists:districts,id',
                'zilla_code' => 'required|exists:zila_parishads,id',
                'anchalik_code' => 'required|exists:anchalik_parishads,id',
            ];
        }elseif ($applicable_id==3){
            $validatorSelector=[
                'district_code' => 'required|exists:districts,id',
                'zilla_code' => 'required|exists:zila_parishads,id',
                'anchalik_code' => 'required|exists:anchalik_parishads,id',
                'gp_code' => 'required|exists:gram_panchyats,gram_panchyat_id',
            ];
        }elseif ($applicable_id==4){
            $validatorSelector=[
                'district_code' => 'required|exists:districts,id',
                'council_id' => 'required|exists:councils,id',
            ];
        }elseif ($applicable_id==6){
            $validatorSelector=[
                'district_code' => 'required|exists:districts,id',
                'council_id' => 'required|exists:councils,id',
                'block_code' => 'required|exists:blocks,id',
            ];
        }elseif ($applicable_id==7){
            $validatorSelector=[
                'district_code' => 'required|exists:districts,id',
                'council_id' => 'required|exists:councils,id',
                'block_code' => 'required|exists:blocks,id',
                'v_code' => 'required|exists:gram_panchyats,gram_panchyat_id',
            ];
        }

        return $validatorSelector;

    }

    public function finalSubmit(Request $request){

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

        $matchArray=["six_finance_finals.id"=>$sixFinanceFinal->id];

        $sixFinanceFinal=$this->getSixFinanceFinal($matchArray);

        if($sixFinanceFinal['final_submission_status']==1){
            $returnData['msg']="Already submitted the form.";
            return response()->json($returnData);
        }else if($sixFinanceFinal['basic_info'] && $sixFinanceFinal['staff_info'] && $sixFinanceFinal['revenue_info'] && $sixFinanceFinal['expenditure_info'] && $sixFinanceFinal['balance_info'] && $sixFinanceFinal['other_info'] && $sixFinanceFinal['five_year_info']){
            $six_final= SixFinanceFinals::where('id', $sixFinanceFinal->id)
                ->update(['final_submission_status' => 1]);

            if($six_final){
                $returnData['msgType']=true;
                $returnData['msg']="Successfully done the task!";
                $returnData['data']=[];
                return response()->json($returnData);
            }
        }else{
            $returnData['msg']="Please submit not completed red bordered forms before final submit";
            return response()->json($returnData);
        }

        $returnData['msg']="Oops! Something went wrong. Please try again later.";
        return response()->json($returnData);
    }

}
