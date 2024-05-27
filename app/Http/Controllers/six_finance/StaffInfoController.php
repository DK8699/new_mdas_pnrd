<?php

namespace App\Http\Controllers\six_finance;

use App\ConfigMdas;
use App\survey\six_finance\SixFinanceFinals;
use App\survey\six_finance\SixFinanceFormStaffDetails;
use App\survey\six_finance\SixFinanceFormStaffs;
use App\survey\six_finance\SixFinanceFormStaffSalarySummaries;
use App\survey\six_finance\SixFinanceStaffCats;
use App\survey\six_finance\SixFinanceStaffDesignationApplicables;
use App\survey\six_finance\SixFinanceStaffDesignations;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Validator;

class StaffInfoController extends Controller
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

                $applicable_id=$six_finance_session_data['applicable_id'];
                $applicable_name=$six_finance_session_data['applicable_name'];

                $acts= DB::table('acts')->where('id', '<=', 5)->get();

                $cats= SixFinanceStaffCats::select('six_finance_staff_cats.id', 'six_finance_staff_cats.category_name')
                    ->get();

                foreach($cats AS $c_li){
                    $designs = SixFinanceStaffCats::join('six_finance_staff_designations AS sd', 'sd.six_finance_staff_cat_id', '=', 'six_finance_staff_cats.id')
                        ->join('six_finance_staff_designation_applicables AS sda', 'sda.six_finance_designation_id', '=', 'sd.id')
                        ->where([
                            ['sda.applicable_id', '=', $applicable_id],
                            ['six_finance_staff_cats.id', '=', $c_li->id],
                            ['sd.is_active', '=', 1],
                        ])
                        ->distinct('sd.id')
                        ->select('sd.id', 'sd.designation_name', 'sda.id AS priority')
						->orderBy('priority', 'ASC')
                        ->get();

                    $final_cats[$c_li->id]= [
                        'id'=>$c_li->id,
                        'category_name'=>$c_li->category_name,
                        'designations'=>$designs
                    ];
                }

                //------------ WHEN SUBMITTED ------------------------
                $six_finance_final_id=$sixFinanceFinal->id;
                $staffInfoFill=NULL;
                $alreadySubmitted=NULL;

                $check=$this->getStaffInfoBySixFinalId($sixFinanceFinal->id);

                if($check){
                    $staffInfoFill=SixFinanceFormStaffs::where([
                        ['six_finance_final_id', '=', $six_finance_final_id],
                    ])->select('*')->first();


                    $staffInfoDetailsFill= SixFinanceFormStaffDetails::where([
                        ['six_finance_final_id', '=', $six_finance_final_id],
                        ['six_finance_form_staff_id', '=', $staffInfoFill->id],
                    ])->select('*')->get();

                    foreach($staffInfoDetailsFill AS $li_dts){
                        $staffInfoDetailsFillFinal["C_".$li_dts->six_finance_staff_cat_id]["D_".$li_dts->six_finance_staff_designation_id]["SC"]=$li_dts->no_of_sanctioned_post;
                        $staffInfoDetailsFillFinal["C_".$li_dts->six_finance_staff_cat_id]["D_".$li_dts->six_finance_staff_designation_id]["SP"]=$li_dts->scale_of_pay;
                        $staffInfoDetailsFillFinal["C_".$li_dts->six_finance_staff_cat_id]["D_".$li_dts->six_finance_staff_designation_id]["CP"]=$li_dts->consolidated_pay;
                        $staffInfoDetailsFillFinal["C_".$li_dts->six_finance_staff_cat_id]["D_".$li_dts->six_finance_staff_designation_id]["VP"]=$li_dts->vacant_post;
                    }

                    $staffInfoSalaryFill=SixFinanceFormStaffSalarySummaries::where([
                        ['six_finance_final_id', '=', $six_finance_final_id],
                        ['six_finance_form_staff_id', '=', $staffInfoFill->id],
                    ])->select('*')->get();

                    foreach($staffInfoSalaryFill AS $li_slry){
                        $staffInfoSalaryFillFinal["C_".$li_slry->six_finance_staff_cat_id]["D_".$li_slry->six_finance_staff_designation_id]["A_".$li_slry->act_id]=$li_slry->salary;
                    }

                    //echo json_encode($staffInfoSalaryFillFinal);

                    $alreadySubmitted=1;
                }


                return view('survey.six_finance.staff_info', compact('applicable_name', 'acts', 'final_cats', 'alreadySubmitted', 'staffInfoFill', 'staffInfoDetailsFillFinal', 'staffInfoSalaryFillFinal'));
            }else {
                return redirect()->route('dashboard');
            }
        }else{
            return redirect()->route('dashboard');
        }
    }

    public function add_design(Request $request){
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
            'cat_id.required'=> "This field is required!",
            'cat_id.exists'=> "Invalid Category!",
        ];

        $validator = Validator::make($request->all(), [
            'cat_id'=> "required|exists:six_finance_staff_cats,id",
            'designation_name'=> "required|string|max:100|min:2",
        ], $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);
        }

        $check=$this->checkNameAlready($applicable_id, $request->input('designation_name'), $request->input('cat_id'));

        if(!$check){
            $returnData['msg'] = "This designation is already requested, ask the admin for more details.";
            return response()->json($returnData);
        }

        DB::beginTransaction();

        try {


            $cat = new SixFinanceStaffDesignations();
            $cat->six_finance_staff_cat_id = $request->input('cat_id');
            $cat->designation_name = $request->input('designation_name');
            $cat->created_by = $users->employee_code;
            $cat->is_active = 0;

            if($cat->save()){
                $cat_app = new SixFinanceStaffDesignationApplicables();
                $cat_app->applicable_id = $applicable_id;
                $cat_app->six_finance_designation_id = $cat->id;
                $cat_app->save();

                DB::commit();
            }


        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng.#2";
            return response()->json($returnData);
        }

        $returnData['msgType']=true;
        $returnData['msg']="Successfully sent the request.!";
        $returnData['data']=[];
        return response()->json($returnData);
    }
	
	private function checkNameAlready($applicable_id, $designation_name, $cat_id){
        $cat=SixFinanceStaffDesignations::join("six_finance_staff_designation_applicables AS ap", 'six_finance_staff_designations.id', '=', 'ap.six_finance_designation_id')
        ->where([
            ["six_finance_staff_designations.designation_name", "=", trim($designation_name)],
            ["six_finance_staff_designations.six_finance_staff_cat_id", "=", $cat_id],
            ["ap.applicable_id", "=", $applicable_id],
        ])->count();

        if($cat > 0){
            return false;
        }

        return true;
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

        $cats= SixFinanceStaffCats::select('six_finance_staff_cats.id', 'six_finance_staff_cats.category_name')
            ->get();

        foreach($cats AS $c_li){
            $designs = SixFinanceStaffCats::join('six_finance_staff_designations AS sd', 'sd.six_finance_staff_cat_id', '=', 'six_finance_staff_cats.id')
                ->join('six_finance_staff_designation_applicables AS sda', 'sda.six_finance_designation_id', '=', 'sd.id')
                ->where([
                    ['sda.applicable_id', '=', $applicable_id],
                    ['six_finance_staff_cats.id', '=', $c_li->id],
                    ['sd.is_active', '=', 1]
                ])
                ->distinct('sd.id')
                ->select('sd.id', 'sd.designation_name')
                ->get();

            $final_cats[$c_li->id]= [
                'id'=>$c_li->id,
                'category_name'=>$c_li->category_name,
                'designations'=>$designs
            ];
        }

        foreach($final_cats AS $li_cat){
            foreach($li_cat['designations'] AS $li_d){
                $validatorRule['san_post_c_'.$li_d->id]= "required|numeric|min:0|max:9999999999";
                $validatorRule['scale_pay_c_'.$li_d->id]= "required|string|max:30";
                $validatorRule['con_pay_c_'.$li_d->id]= "required|numeric|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:9999999999";
                $validatorRule['vac_post_c_'.$li_d->id]= "required|numeric|min:0|max:9999999999";

                $validatorMessages['san_post_c_'.$li_d->id.'.required']= "This field is required!";
                $validatorMessages['san_post_c_'.$li_d->id.'.numeric']= "Must be a number!";
                $validatorMessages['san_post_c_'.$li_d->id.'.min']= "Negative values not accepted!";
                $validatorMessages['san_post_c_'.$li_d->id.'.max']= "Up to 10 digit number is allowed!";

                $validatorMessages['scale_pay_c_'.$li_d->id.'.required']= "This field is required!";
                $validatorMessages['scale_pay_c_'.$li_d->id.'.min']= "Negative values not accepted!";
                $validatorMessages['scale_pay_c_'.$li_d->id.'.max']= "Up to 30 characters are allowed!";

                $validatorMessages['con_pay_c_'.$li_d->id.'.required']= "This field is required!";
                $validatorMessages['con_pay_c_'.$li_d->id.'.numeric']= "Must be a number!";
                $validatorMessages['con_pay_c_'.$li_d->id.'.regex']= "Decimal up to two digit places only!";
                $validatorMessages['con_pay_c_'.$li_d->id.'.min']= "Negative values not accepted!";
                $validatorMessages['con_pay_c_'.$li_d->id.'.max']= "Up to 10 digit number is allowed!";

                $validatorMessages['vac_post_c_'.$li_d->id.'.required']= "This field is required!";
                $validatorMessages['vac_post_c_'.$li_d->id.'.numeric']= "Must be a number!";
                $validatorMessages['vac_post_c_'.$li_d->id.'.min']= "Negative values not accepted!";
                $validatorMessages['vac_post_c_'.$li_d->id.'.max']= "Up to 10 digit number is allowed!";

                foreach($acts AS $li_act){
                    $validatorRule['salary_summary_a_'.$li_act->id.'_d_'.$li_d->id.'_c_'.$li_cat['id']]= "required|numeric|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:9999999999";

                    $validatorMessages['salary_summary_a_'.$li_act->id.'_d_'.$li_d->id.'_c_'.$li_cat['id'].'.required']= "This field is required!";
                    $validatorMessages['salary_summary_a_'.$li_act->id.'_d_'.$li_d->id.'_c_'.$li_cat['id'].'.numeric']= "Must be a number!";
                    $validatorMessages['salary_summary_a_'.$li_act->id.'_d_'.$li_d->id.'_c_'.$li_cat['id'].'.regex']= "Decimal up to two digit places only!";
                    $validatorMessages['salary_summary_a_'.$li_act->id.'_d_'.$li_d->id.'_c_'.$li_cat['id'].'.min']= "Negative values not accepted!";
                    $validatorMessages['salary_summary_a_'.$li_act->id.'_d_'.$li_d->id.'_c_'.$li_cat['id'].'.max']= "Up to 10 digit number is allowed!";
                }
            }

        }

        $validatorRule['arrear_salary']= "required|numeric|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:9999999999";
        $validatorRule['no_muster_roll_fixed_pay_emp']= "required|numeric|min:0|max:9999999999";


        $validator = Validator::make($request->all(), $validatorRule, $validatorMessages);

        if ($validator->fails()) {

            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);

        }

        DB::beginTransaction();

        try {

            $check=$this->getStaffInfoBySixFinalId($sixFinanceFinal->id);

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

                //------------------------------------------------------------------------
                //------------------- DELETE STAFF ---------------------------------------
                //------------------------------------------------------------------------

                $delete= SixFinanceFormStaffs::where([
                    ['six_finance_final_id', '=', $sixFinanceFinal->id],
                ])->delete();

                $delete1= SixFinanceFormStaffDetails::where([
                    ['six_finance_final_id', '=', $sixFinanceFinal->id],
                ])->delete();

                $delete2= SixFinanceFormStaffSalarySummaries::where([
                    ['six_finance_final_id', '=', $sixFinanceFinal->id],
                ])->delete();

                if(!$delete){
                    DB::rollback();
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d2";
                    return $returnData;
                }
                if(!$delete1){
                    DB::rollback();
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d3";
                    return $returnData;
                }
                if(!$delete2){
                    DB::rollback();
                    $returnData['msg'] = "Oops! Something went wrong. Please try again later.#d4";
                    return $returnData;
                }

                //************************DELETE STAFF*******************************************
				
				DB::table('six_finance_resubmit_trackers')->insert(
                ['six_finance_final_id' => $sixFinanceFinal->id, 'r_date' =>Carbon::now()->format("Y-m-d")]
				);
            }

            $checkRequest=$this->checkSixFinanceStaffDesignations($users->employee_code);

            if($checkRequest){
                $returnData['msg']="You have pending designation request. Kindly wait till your request is confirmed or ask admin to confirm it";
                return response()->json($returnData);
            }

            $staffInfo = new SixFinanceFormStaffs();
            $staffInfo->six_finance_final_id = $sixFinanceFinal->id;

            $staffInfo->arrear_salary = $request->input('arrear_salary');
            $staffInfo->number_of_muster_roll_fixed_pay_emp = $request->input('no_muster_roll_fixed_pay_emp');

            if (!$staffInfo->save()) {
                DB::rollback();
                $returnData['msg'] = "Opps! Something went worng.";
                return response()->json($returnData);
            }

            foreach($final_cats AS $li_cat) {
                foreach ($li_cat['designations'] AS $li_d) {
                    $staffInfoDetails = new SixFinanceFormStaffDetails();
                    $staffInfoDetails->six_finance_final_id = $sixFinanceFinal->id;
                    $staffInfoDetails->six_finance_form_staff_id = $staffInfo->id;
                    $staffInfoDetails->six_finance_staff_cat_id = $li_cat['id'];
                    $staffInfoDetails->six_finance_staff_designation_id = $li_d->id;
                    $staffInfoDetails->no_of_sanctioned_post = $request->input('san_post_c_'.$li_d->id);
                    $staffInfoDetails->scale_of_pay = $request->input('scale_pay_c_'.$li_d->id);
                    $staffInfoDetails->consolidated_pay = $request->input('con_pay_c_'.$li_d->id);
                    $staffInfoDetails->vacant_post = $request->input('vac_post_c_'.$li_d->id);

                    if(!$staffInfoDetails->save()){
                        DB::rollback();
                        $returnData['msg'] = "Opps! Something went worng.";
                        return response()->json($returnData);
                    }

                    foreach($acts AS $li_act){
                        $staffInfoSummaries = new SixFinanceFormStaffSalarySummaries();
                        $staffInfoSummaries->six_finance_final_id = $sixFinanceFinal->id;
                        $staffInfoSummaries->six_finance_form_staff_id = $staffInfo->id;
                        $staffInfoSummaries->six_finance_staff_cat_id = $li_cat['id'];
                        $staffInfoSummaries->act_id = $li_act->id;
                        $staffInfoSummaries->six_finance_staff_designation_id = $li_d->id;
                        $staffInfoSummaries->salary = $request->input('salary_summary_a_'.$li_act->id.'_d_'.$li_d->id.'_c_'.$li_cat['id']);

                        if(!$staffInfoSummaries->save()){
                            DB::rollback();
                            $returnData['msg'] = "Opps! Something went worng.";
                            return response()->json($returnData);
                        }
                    }
                }
            }

            $six_final= SixFinanceFinals::where('id', $sixFinanceFinal->id)
                ->update(['staff_info' => 1]);

            if(!$six_final){
                DB::rollback();
                $returnData['msg']="Oops! Could not update.";
                return response()->json($returnData);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng.".$e->getMessage();
            return response()->json($returnData);
        }

        $returnData['msgType']=true;
        $returnData['msg']="Successfully done the task!";
        $returnData['data']=[];
        return response()->json($returnData);
    }

    public function getStaffInfoBySixFinalId($id){
        $result=SixFinanceFormStaffs::where('six_finance_final_id', '=', $id)->select('id')->first();
        return $result;

    }

    public function checkSixFinanceStaffDesignations($employee_code){
        $result=SixFinanceStaffDesignations::where([
            ['created_by', '=', $employee_code],
            ['is_active', '=', 0],
            ['cancel', '=', 0],
        ])->select('id')
          ->first();

        return $result;
    }
}
