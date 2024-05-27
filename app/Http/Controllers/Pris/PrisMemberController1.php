<?php

namespace App\Http\Controllers\Pris;

use App\CommonModels\District;
use App\CommonModels\GramPanchyat;
use App\ConfigMdas;
use App\Master\MasterAnnualIncome;
use App\Master\MasterBloodGroup;
use App\Master\MasterCaste;
use App\Master\MasterGender;
use App\Master\MasterHighestQualification;
use App\Master\MasterMaritalStatus;
use App\Master\MasterPriPoliticalParty;
use App\Master\MasterPrisReserveSeat;
use App\Master\MasterReligion;
use App\Master\MasterWard;
use App\Pris\PriMasterDesignation;
use App\Pris\PriMemberMainRecord;
use App\Pris\PriMemberTermHistory;
use App\survey\six_finance\AnchalikParishad;
use App\survey\six_finance\SixFinanceGpSelectionList;
use App\survey\six_finance\ZilaParishad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use DB;

class PrisMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'user_mdas']);
    }

    // MAIN DASHBOARD OF PRIs

    public function index(Request $request){

        $zillas=[];
        $anchaliks=[];
        $gps=[];

        $users=$request->session()->get('users');
        $applicables=$request->session()->get('applicables');
        $appls=[];
        $tier=NULL;


        //echo json_encode($applicables);

        foreach($applicables AS $applicable){
            array_push($appls, $applicable->id);
        }

        if(in_array(3, $appls)){
            $tier="GP";
        }elseif(in_array(1, $appls)){
            $tier="ZP";
        }

        if($users->district_code){
            if($tier=="ZP"){
                $zillas=ZilaParishad::where('district_id','=', $users->district_code)->first();
                $anchaliks=AnchalikParishad::where('zila_id','=', $zillas->id)->get();
            }elseif($tier=="GP"){

                $outcomes = SixFinanceGpSelectionList::getZillasByEmpCode($users->employee_code);

                if(!$outcomes){
                    $zillas=[];
                    $anchaliks = [];
                }else{
                    $zillas=$outcomes['zillas'];
                    /*echo "AA";
                    echo json_encode($zillas);*/
                    $anchaliks = SixFinanceGpSelectionList::getSelectedAnchalikByEmpCode($users->employee_code);
                }
            }

        }



        $designs= PriMasterDesignation::getDesignByApplicables($appls);
		
		$all_designs= PriMasterDesignation::all();

        $politicals= MasterPriPoliticalParty::all();

        $incomes=MasterAnnualIncome::all();
        $bloods=MasterBloodGroup::all();
        $maritals=MasterMaritalStatus::all();
        $religions=MasterReligion::all();
        $castes=MasterCaste::all();
        $genders=MasterGender::all();
        $qualifications=MasterHighestQualification::all();
        $wards=MasterWard::all();
        $reserveSeats=MasterPrisReserveSeat::all();

        $districts=District::orderBy('district_name')->get();

        $priMembers=PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
            ->join('pri_master_designations AS d', 'd.id', '=', 'h.pri_master_designation_id')
            ->where([
                ['h.master_pri_term_id', '=', 4],
                ['created_by', '=', $users->employee_code],
            ])->select('pri_code', 'pri_f_name', 'pri_m_name', 'pri_l_name', 'd.design_name', 'pri_pic', 'd.color_code')
            ->orderBy('pri_member_main_records.id', 'DESC')
            ->paginate(9);

        $imgUrl=ConfigMdas::allActiveList()->imgUrl;


        return view('Pris.Member.index', compact('zillas', 'anchaliks', 'designs', 'all_designs',
            'incomes', 'bloods','maritals', 'religions', 'castes', 'genders', 'qualifications',
            'wards', 'reserveSeats', 'districts', 'priMembers', 'imgUrl', 'tier', 'politicals'));
    }

    // SAVING DATA OF PRIs

    public function save(Request $request){
        $returnData['msgType']=false;
        $returnData['data']=[];

        if (!$request->session()->exists('users')) {
            $returnData['msg']="User not found. Please Login.";
            return response()->json($returnData);
        }

        $users=$request->session()->get('users');

        $messages = [

            /*-------------- TOP --------------------------------------*/
            't_deg4.required' => 'This is required.',
            't_deg4.exists' => 'Invalid data.',

            'zilla_id.required' => 'This is required.',
            'zilla_id.exists' => 'Invalid data.',

            'anchalik_code.required_if' => 'This is required.',
            'anchalik_code.exists' => 'Invalid data.',

            'gp_code.required_if' => 'This is required.',
            'gp_code.exists' => 'Invalid data.',

            'ward_no.required_if' => 'This is required.',
            'ward_no.exists' => 'Invalid data.',

            'seat_reserved.required' => 'This is required.',
            'seat_reserved.exists' => 'Invalid data.',

            'party_id.required' => 'This is required.',
            'party_id.exists' => 'Invalid data.',

            'constituency.required_if' => 'This is required.',
            'constituency.string' => 'Invalid data.',
            'constituency.max' => 'Maximum 100 characters allowed.',
			
			'ap_constituency.required_if' => 'This is required.',
            'ap_constituency.exists' => 'Invalid data.',

            /*-------------- FIRST --------------------------------------*/

            'pic.mimes' => 'Image must be in jpeg, jpg and png format.',
            'pic.min' => 'Image size must not be less than 10 KB.',
            'pic.max' => 'Image size must not exceed 100 KB.',

            'pri_f_name.required' => 'This is required.',
            'pri_f_name.max' => 'Maximum 100 characters allowed.',

            'pri_m_name.max' => 'Maximum 100 characters allowed.',

            'pri_l_name.required' => 'This is required.',
            'pri_l_name.max' => 'Maximum 100 characters allowed.',

            'mobile_no.required' => 'This is required.',
            'mobile_no.numeric' => 'This must be numeric value.',
            'mobile_no.min' => 'Invalid data.',
            'mobile_no.max' => 'This must not exceed 10 digits.',

            'pri_dob.required' => 'This is required.',
            'pri_dob.date_format' => 'Invalid data.',

            'gender_id.required' => 'This is required.',
            'gender_id.exists' => 'Invalid data.',

            'caste_id.required' => 'This is required.',
            'caste_id.exists' => 'Invalid data.',

            'religion_id.required' => 'This is required.',
            'religion_id.exists' => 'Invalid data.',

            'blood_group_id.required' => 'This is required.',
            'blood_group_id.exists' => 'Invalid data.',

            /*-------------- SECOND --------------------------------------*/

            'differently_abled.required' => 'This is required.',
            'differently_abled.in' => 'Invalid data.',

            'marital_status_id.required' => 'This is required.',
            'marital_status_id.in' => 'Invalid data.',

            'occupation.string' => 'Invalid data.',
            'occupation.max' => 'Maximum 100 characters allowed.',

            'alt_mobile_no.numeric' => 'This must be numeric value.',
            'alt_mobile_no.min' => 'Invalid data.',
            'alt_mobile_no.max' => 'This must not exceed 10 digits.',

            'income_id.required' => 'This is required.',
            'income_id.exists' => 'Invalid data.',

            'qual_id.required' => 'This is required.',
            'qual_id.exists' => 'Invalid data.',

            'earlier_pri.required' => 'This is required.',
            'earlier_pri.in' => 'Invalid data.',

            't_deg3.exists' => 'Invalid data.',
            't_deg2.exists' => 'Invalid data.',
            't_deg1.exists' => 'Invalid data.',

            /*-------------- THIRD --------------------------------------*/

            'o_add.required' => 'This is required.',
            'o_add.string' => 'Invalid data.',
            'o_add.max' => 'Maximum 200 characters allowed.',

            'o_pin.required' => 'This is required.',
            'o_pin.min' => 'Invalid data.',
            'o_pin.max' => 'Invalid data.',

            'p_add.required' => 'This is required.',
            'p_add.string' => 'Invalid data.',
            'p_add.max' => 'Maximum 200 characters allowed.',

            'p_district.required' => 'This is required.',
            'p_district.exists' => 'Invalid data.',

            'p_pin.required' => 'This is required.',
            'p_pin.min' => 'Invalid data.',
            'p_pin.max' => 'Invalid data.',

            'photo_i_proof.mimes' => 'Image must be in jpeg, jpg and png format.',
            'photo_i_proof.min' => 'Image size must not be less than 10 KB.',
            'photo_i_proof.max' => 'Image size must not exceed 200 KB.',

        ];

        $validatorArray=[

            /*-------------- TOP --------------------------------------*/

            't_deg4' => 'required|exists:pri_master_designations,id',

            'zilla_id' => 'required|exists:zila_parishads,id',

            'anchalik_code' => 'required_if:t_deg4,3,4,5,6|required_if:t_deg4,4|exists:anchalik_parishads,id|nullable',

            'gp_code' => 'required_if:t_deg4,5,6|required_if:t_deg4,6|exists:gram_panchyats,gram_panchyat_id|nullable',

            'ward_no' => 'required_if:t_deg4,6|exists:master_wards,id|nullable',

            'seat_reserved' => 'required|exists:master_pris_reserve_seats,id',

            'constituency' => 'required_if:t_deg4,1,2|string|max:100|nullable',
			
			'ap_constituency' => 'required_if:t_deg4,3,4|exists:gram_panchyats,gram_panchyat_id|nullable',

            'party_id' => 'required|exists:master_pri_political_parties,id',

            /*-------------- FIRST --------------------------------------*/

            'pic' => 'image|mimes:jpg,jpeg,png|max:100|min:10|nullable',

            'pri_f_name' => 'required|string|max:100',

            'pri_m_name' => 'string|max:100|nullable',

            'pri_l_name' => 'required|string|max:100',

            'mobile_no' => 'required|numeric|min:6000000000|max:9999999999',

            'pri_dob' => 'required|date_format:Y-m-d',

            'gender_id' => 'required|exists:master_genders,id',

            'caste_id' => 'required|exists:master_castes,id',

            'religion_id' => 'required|exists:master_religions,id',

            'blood_group_id' => 'exists:master_blood_groups,id|nullable',

            /*-------------- SECOND --------------------------------------*/

            'differently_abled' => 'required|in:0,1',

            'marital_status_id' => 'required|exists:master_marital_statuses,id',

            'occupation' => 'string|max:100|nullable',

            'alt_mobile_no' => 'numeric|min:6000000000|max:9999999999|nullable',

            'income_id' => 'required|exists:master_annual_incomes,id',

            'qual_id' => 'required|exists:master_highest_qualifications,id',

            'earlier_pri' => 'required|in:0,1',

            't_deg3' => 'exists:pri_master_designations,id|nullable',

            't_deg2' => 'exists:pri_master_designations,id|nullable',

            't_deg1' => 'exists:pri_master_designations,id|nullable',

            /*-------------- THIRD --------------------------------------*/

            'o_add' => 'required|string|max:200',
            'o_pin' => 'required|numeric|min:700000|max:999999',

            'p_add' => 'required|string|max:200',
            'p_pin' => 'required|numeric|min:700000|max:999999',
            'p_district' => 'required|exists:districts,id',


            'photo_i_proof' => 'image|mimes:jpeg,jpg,png|max:200|min:10|nullable',

        ];

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
            $checkMember=$this->checkAlreadyExists($request->input('mobile_no'));

            if(!$checkMember){
                $returnData['msg']= "Mobile number already exists";
                return response()->json($returnData);
            }

            $pri_pic_path=NULL;
            $pri_photo_id_path=NULL;

            $lastPriMember=PriMemberMainRecord::select('pri_code')->orderBy('id', 'DESC')->first();

            $yearTwo=substr(now()->format("Y"), 2, 2);

            if(!$lastPriMember){
                $priCode="PRI".$yearTwo."AAA"."100000";
            }else{
                if(strlen($lastPriMember->pri_code)!=14){
                    $returnData['msg']= "PRI Code mismatch please contact admin for more details";
                    return response()->json($returnData);
                }

                //echo $lastPriMember->pri_code." | ";

                $alphaCode=substr($lastPriMember->pri_code,5,3);
                $intCode=(int)substr($lastPriMember->pri_code, 8,6);

                if($intCode >=999999){
                    $alphaCode++;
                    $intCode="100000";
                }else{
                    $intCode++;
                }

                $priCode="PRI".$yearTwo.$alphaCode.$intCode;
            }

            if($request->file('pic')){
                $pri_pic_path = $request->file('pic')->store('pris/pri_pic');
            }

            if($request->file('photo_i_proof')){
                $pri_photo_id_path = $request->file('photo_i_proof')->store('pris/pri_photo_id');
            }

            $newEntry=new PriMemberMainRecord();

            $newEntry->pri_code=$priCode;
            $newEntry->zilla_id=$request->input('zilla_id');
            $newEntry->anchalik_id=$request->input('anchalik_code');
            $newEntry->gram_panchayat_id=$request->input('gp_code');
            $newEntry->ward_id=$request->input('ward_no');
            $newEntry->seat_reserved=$request->input('seat_reserved');
            $newEntry->constituency=$request->input('constituency');
			$newEntry->ap_constituency=$request->input('ap_constituency');
            $newEntry->party_id=$request->input('party_id');

            $newEntry->pri_pic=$pri_pic_path;
            $newEntry->pri_f_name=$request->input('pri_f_name');
            $newEntry->pri_m_name=$request->input('pri_m_name');
            $newEntry->pri_l_name=$request->input('pri_l_name');
            $newEntry->mobile_no=$request->input('mobile_no');
            $newEntry->dob=$request->input('pri_dob');
            $newEntry->gender_id=$request->input('gender_id');
            $newEntry->caste_id=$request->input('caste_id');
            $newEntry->religion_id=$request->input('religion_id');
            $newEntry->blood_group_id=$request->input('blood_group_id');

            $newEntry->differently_abled=$request->input('differently_abled');
            $newEntry->marital_status_id=$request->input('marital_status_id');
            $newEntry->occupation=$request->input('occupation');
            $newEntry->alt_mobile_no=$request->input('alt_mobile_no');
            $newEntry->annual_income_id=$request->input('income_id');
            $newEntry->qual_id=$request->input('qual_id');
            $newEntry->earlier_pri=$request->input('earlier_pri');

            $newEntry->o_add=$request->input('o_add');
            $newEntry->o_pin=$request->input('o_pin');
            $newEntry->p_add=$request->input('p_add');
            $newEntry->p_pin=$request->input('p_pin');
            $newEntry->p_district=$request->input('p_district');
            $newEntry->photo_i_proof=$pri_photo_id_path;

            $newEntry->created_by=$users->employee_code;

            $newEntry->save();


            $memberHistory=new PriMemberTermHistory();
            $memberHistory->pri_member_main_record_id=$newEntry->id;
            $memberHistory->master_pri_term_id=4;   //----------CURRENT TERM-----------------
            $memberHistory->pri_master_designation_id=$request->input('t_deg4');
            $memberHistory->save();


            if($request->input('earlier_pri')) {
                $ifEarlier = $this->saveEarlierMemberHistory($request, $newEntry->id);

                if(!$ifEarlier){
                    DB::rollback();
                    $returnData['msg'] = "You have selected YES in Earlier Elected as PRI. 
                        So, you have to select at least one designation.";
                    return $returnData;
                }
            }
        }catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went wrong.EX";
            return $returnData;
        }

        DB::commit();

        $returnData['msgType']=true;
        $returnData['msg']="Successfully done the task!";
        $returnData['data']=[];
        return response()->json($returnData);
    }

    private function saveEarlierMemberHistory($request, $id){
        $i=0;

        if($request->input('t_deg3')){
            $memberHistory=new PriMemberTermHistory();
            $memberHistory->pri_member_main_record_id=$id;
            $memberHistory->master_pri_term_id=3;
            $memberHistory->pri_master_designation_id=$request->input('t_deg3');
            $memberHistory->save();

            $i++;

            if(!$memberHistory->save()){
                return false;
            }
        }
        if($request->input('t_deg2')){
            $memberHistory=new PriMemberTermHistory();
            $memberHistory->pri_member_main_record_id=$id;
            $memberHistory->master_pri_term_id=2;
            $memberHistory->pri_master_designation_id=$request->input('t_deg2');
            $memberHistory->save();

            $i++;

            if(!$memberHistory->save()){
                return false;
            }
        }
        if($request->input('t_deg1')){
            $memberHistory=new PriMemberTermHistory();
            $memberHistory->pri_member_main_record_id=$id;
            $memberHistory->master_pri_term_id=1;
            $memberHistory->pri_master_designation_id=$request->input('t_deg1');
            $memberHistory->save();

            $i++;

            if(!$memberHistory->save()){
                return false;
            }
        }

        if($i==0){
            return false;
        }

        return true;
    }

    private function checkAlreadyExists($mobile_no){
        $checkMember=PriMemberMainRecord::where([
            ['mobile_no', '=', $mobile_no]
        ])->first();

        if($checkMember){
            return false;
        }

        return true;
    }

    public function view(Request $request){
        $data['msgType']=false;
        $data['data']=[];
		
		$selectedGpList=[];
		$ap_con_gps=[];

        $pri_code=$request->input('pri_code');

        $priMember= PriMemberMainRecord::where([
            ['pri_code', '=', $pri_code]
        ])->first();

        if($priMember){
            $priMemberHistory= PriMemberTermHistory::where([
                ['pri_member_main_record_id', '=', $priMember->id]
            ])->get();
			
			foreach($priMemberHistory AS $history){
                if($history->master_pri_term_id == 4){

                    if($history->pri_master_designation_id == 3 || $history->pri_master_designation_id == 4){
                        $ap_con_gps= GramPanchyat::where([
                            ['anchalik_id', '=', $priMember->anchalik_id],
                            ['vcc', '=', NULL],
                        ])->select('gram_panchyat_id AS id', 'gram_panchayat_name')->get();
                    }elseif($history->pri_master_designation_id == 5 || $history->pri_master_designation_id == 6){
                        $selectedGpList= SixFinanceGpSelectionList::join('gram_panchyats', 'six_finance_gp_selection_lists.gp_code', '=', 'gram_panchyats.gram_panchyat_id')
                            ->where([
                                ['six_finance_gp_selection_lists.anchalik_code', '=', $priMember->anchalik_id],
                                ['six_finance_gp_selection_lists.emp_code', '=', $priMember->created_by],
                                ['vcc', '=', NULL],
                            ])
                            ->select('gram_panchyats.gram_panchyat_id AS id', 'gram_panchyats.gram_panchayat_name')
                            ->get();
                    }
                }
            }
        }else{
            $data['msg']="Data not found.";
            return $data;
        }

        $data['msgType']=true;
        $data['msg']="Successfully done the task.";
        $data['data']=array('priMember'=>$priMember, 'priMemberHistory'=>$priMemberHistory, 'ap_con_gps' =>$ap_con_gps,  'selectedGpList'=>$selectedGpList);
        return $data;
    }

    // -------------------------------------- GET GP BY ANCHALIK ID ----------------------------------------------------

    public function getGPsByAnchalikId(Request $request){
        $returnData['msgType']=false;
        $returnData['data']=[];

        $anchalik_id= $request->input('anchalik_code');

        if(!$anchalik_id){
            $returnData['msg']="GP List not found";
            return response()->json($returnData);
        }

        $gps= GramPanchyat::where([
                ['anchalik_id', '=', $anchalik_id],
                ['vcc', '=', NULL],
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
        return response()->json($returnData);
    }
}
