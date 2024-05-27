<?php

namespace App\Http\Controllers\Pris;

use App\CommonModels\AnchalikParishad;
use App\CommonModels\District;
use App\CommonModels\GramPanchyat;
use App\CommonModels\ZilaParishad;
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
//use App\survey\six_finance\AnchalikParishad;
use App\survey\six_finance\SixFinanceGpSelectionList;
//use App\survey\six_finance\ZilaParishad;
use Illuminate\Http\Request;
use App\Http\Requests\PriBankRequest;
use App\Pris\PriMembersBankRecord;
use App\Http\Controllers\Controller;
use Validator;
use DB;
use Auth;

class PrisMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'user_mdas']);
    }

    // MAIN DASHBOARD OF PRIs

    public function index(Request $request)
    {

        $users = Auth::user();

        $zp_id = NULL;
        $ap_id = NULL;
        $gp_id = NULL;
        $zpData = NULL;
        $apData = NULL;
        $gpData = NULL;
        $apList = [];
        $gpList = [];
        $appls = [];
        $whereArray = [];

        if ($users->mdas_master_role_id == 2) { //DISTRICT ADMIN
            $level = "ZP";
            $zp_id = $users->zp_id;

            $zpData = ZilaParishad::getZPName($zp_id);
            $apList = AnchalikParishad::getActiveAPsByZpId($zp_id);

            $whereArray = [
                ['h.master_pri_term_id', '=', 4, 'OR', 5],
                ['pri_member_main_records.zilla_id', '=', $zp_id]
            ];

            $appls = [1, 2, 3];
        } elseif ($users->mdas_master_role_id == 3) { //AP ADMIN
            $level = "AP";
            $zp_id = $users->zp_id;
            $ap_id = $users->ap_id;

            $zpData = ZilaParishad::getZPName($zp_id);
            $apData = AnchalikParishad::getAPName($ap_id);
            $gpList = GramPanchyat::getGpsByAnchalikId($ap_id);

            $whereArray = [
                ['h.master_pri_term_id', '=', 4, 'OR', 5],
                ['pri_member_main_records.zilla_id', '=', $zp_id],
                ['pri_member_main_records.anchalik_id', '=', $ap_id],
            ];

            $appls = [2, 3];
        } elseif ($users->mdas_master_role_id == 4) { //GP ADMIN
            $level = "GP";
            $zp_id = $users->zp_id;
            $ap_id = $users->ap_id;
            $gp_id = $users->gp_id;

            $zpData = ZilaParishad::getZPName($zp_id);
            $apData = AnchalikParishad::getAPName($ap_id);
            $gpData = GramPanchyat::getGPName($gp_id);

            $whereArray = [
                ['h.master_pri_term_id', '=', 4, 'OR', 5],
                ['pri_member_main_records.zilla_id', '=', $zp_id],
                ['pri_member_main_records.anchalik_id', '=', $ap_id],
                ['pri_member_main_records.gram_panchayat_id', '=', $gp_id],
            ];

            $appls = [3];
        } else {
            $level = "NA";
        }

        $priList = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
            ->join('pri_master_designations AS d', 'd.id', '=', 'h.pri_master_designation_id')
            ->where($whereArray)
            ->select('pri_code', 'pri_f_name', 'pri_m_name', 'pri_l_name', 'd.design_name', 'pri_pic', 'd.color_code', 'pri_member_main_records.created_by', 'pri_member_main_records.created_at', 'pri_member_main_records.updated_by', 'pri_member_main_records.updated_at', 'pri_member_main_records.id as pri_id')
            ->orderBy('pri_member_main_records.id', 'DESC')
            ->get();

        $bankrecord = [];
        $i = 0;
        foreach ($priList as $value) {
            $bankrecord[$i] = PriMembersBankRecord::where([
                'pri_member_main_record_id' => $value['pri_id']
            ])->count();
            $i++;
        }
        $designList = PriMasterDesignation::getDesignByApplicables($appls);


        $politicalList = MasterPriPoliticalParty::all();

        $incomeList = MasterAnnualIncome::all();
        $bloodList = MasterBloodGroup::all();
        $maritalList = MasterMaritalStatus::all();
        $religionList = MasterReligion::all();
        $casteList = MasterCaste::all();
        $genderList = MasterGender::all();
        $qualList = MasterHighestQualification::all();
        $wardList = MasterWard::all();
        $rSeatList = MasterPrisReserveSeat::all();
        $districtList = District::orderBy('district_name')->get();


        $imgUrl = ConfigMdas::allActiveList()->imgUrl;

        $data = [
            "level" => $level,
            "priList" => $priList,
            "zpData" => $zpData,
            "apData" => $apData,
            "gpData" => $gpData,
            "apList" => $apList,
            "gpList" => $gpList,
            "designList" => $designList,
            "incomeList" => $incomeList,
            "bloodList" => $bloodList,
            "maritalList" => $maritalList,
            "religionList" => $religionList,
            "casteList" => $casteList,
            "genderList" => $genderList,
            "qualList" => $qualList,
            "wardList" => $wardList,
            "rSeatList" => $rSeatList,
            "districtList" => $districtList,
            "politicalList" => $politicalList
        ];
        $banks = DB::table('banks')->select('id', 'bank_name')->get();
        return view('Pris.Member.index', compact('data', 'imgUrl', 'banks', 'bankrecord'));
    }

    // SAVING DATA OF PRIs

    public function save(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];

        $users = Auth::user();

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

        $validatorArray = [
            'editCode' => 'exists:pri_member_main_records,id|nullable',
            'editPriCode' => 'exists:pri_member_main_records,pri_code|nullable',
            /*-------------- TOP --------------------------------------*/

            't_deg4' => 'required|exists:pri_master_designations,id',
            'zilla_id' => 'required|exists:zila_parishads,id',
            'anchalik_code' => 'required_if:t_deg4,3,4,5,6,8,9|exists:anchalik_parishads,id|nullable',
            'gp_code' => 'required_if:t_deg4,5,6,9|exists:gram_panchyats,gram_panchyat_id|nullable',
            'ward_no' => 'required_if:t_deg4,6,9|exists:master_wards,id|nullable',
            'seat_reserved' => 'required|exists:master_pris_reserve_seats,id',
            'constituency' => 'required_if:t_deg4,1,2,7|string|max:100|nullable',
            'ap_constituency' => 'required_if:t_deg4,3,4,8|exists:gram_panchyats,gram_panchyat_id|nullable',
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
            /*'earlier_pri' => 'required|in:0,1',*/

            /*'t_deg3' => 'exists:pri_master_designations,id|nullable',
            't_deg2' => 'exists:pri_master_designations,id|nullable',
            't_deg1' => 'exists:pri_master_designations,id|nullable',*/

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

            $pri_pic_path = NULL;
            $pri_photo_id_path = NULL;

            $t_deg4 = $request->input('t_deg4');

            $zp_id = $users->zp_id;
            $seat_reserved = $request->input('seat_reserved');
            $party_id = $request->input('party_id');

            if (!$request->input('editCode') || !$request->input('editPriCode')) {

                //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                //------------------------------Save Block------------------------------------------------------------------
                //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

                $checkMember = $this->checkAlreadyExists($request->input('mobile_no'));

                if (!$checkMember) {
                    $returnData['msg'] = "Mobile number already exists";
                    return response()->json($returnData);
                }

                //CREATE PRI CODE FOR MEMBER----------------------------------------------------------------------------

                $lastPriMember = PriMemberMainRecord::select('pri_code')->orderBy('id', 'DESC')->first();

                $yearTwo = substr(now()->format("Y"), 2, 2);

                if (!$lastPriMember) {
                    $priCode = "PRI" . $yearTwo . "AAA" . "100000";
                } else {
                    if (strlen($lastPriMember->pri_code) != 14) {
                        $returnData['msg'] = "PRI Code mismatch please contact admin for more details";
                        return response()->json($returnData);
                    }

                    //echo $lastPriMember->pri_code." | ";

                    $alphaCode = substr($lastPriMember->pri_code, 5, 3);
                    $intCode = (int) substr($lastPriMember->pri_code, 8, 6);

                    if ($intCode >= 999999) {
                        $alphaCode++;
                        $intCode = "100000";
                    } else {
                        $intCode++;
                    }

                    $priCode = "PRI" . $yearTwo . $alphaCode . $intCode;
                }

                //CREATE PRI CODE FOR MEMBER- ENDED --------------------------------------------------------------------

                if ($request->file('pic')) {
                    $pri_pic_path = $request->file('pic')->store('pris/pri_pic');
                }

                if ($request->file('photo_i_proof')) {
                    $pri_photo_id_path = $request->file('photo_i_proof')->store('pris/pri_photo_id');
                }


                if ($t_deg4 == 1 || $t_deg4 == 2 || $t_deg4 == 7) {
                    $constituency = $request->input('constituency');


                    $ap_id = NULL;
                    $ap_constituency = NULL;
                    $gp_id = NULL;
                    $ward_id = NULL;
                } else if ($t_deg4 == 3 || $t_deg4 == 4 || $t_deg4 == 8) {

                    $ap_id = $request->input('anchalik_code');
                    $ap_constituency = $request->input('ap_constituency');

                    $constituency = NULL;
                    $gp_id = NULL;
                    $ward_id = NULL;
                } else if ($t_deg4 == 5 || $t_deg4 == 6 || $t_deg4 == 9) {

                    $ap_id = $request->input('anchalik_code');
                    $gp_id = $request->input('gp_code');

                    if ($t_deg4 == 6 || $t_deg4 == 9) {
                        $ward_id = $request->input('ward_no');
                    } else {
                        $ward_id = NULL;
                    }

                    $constituency = NULL;
                    $ap_constituency = NULL;
                } else {
                    $returnData['msg'] = "Opps! Unauthorised access.!";
                    return response()->json($returnData);
                }


                $newEntry = new PriMemberMainRecord();

                $newEntry->pri_code = $priCode;
                $newEntry->zilla_id = $zp_id;
                $newEntry->anchalik_id = $ap_id;
                $newEntry->gram_panchayat_id = $gp_id;
                $newEntry->ward_id = $ward_id;
                $newEntry->seat_reserved = $seat_reserved;
                $newEntry->constituency = $constituency;
                $newEntry->ap_constituency = $ap_constituency;
                $newEntry->party_id = $party_id;

                $newEntry->pri_pic = $pri_pic_path;
                $newEntry->pri_f_name = $request->input('pri_f_name');
                $newEntry->pri_m_name = $request->input('pri_m_name');
                $newEntry->pri_l_name = $request->input('pri_l_name');
                $newEntry->mobile_no = $request->input('mobile_no');
                $newEntry->dob = $request->input('pri_dob');
                $newEntry->gender_id = $request->input('gender_id');
                $newEntry->caste_id = $request->input('caste_id');
                $newEntry->religion_id = $request->input('religion_id');
                $newEntry->blood_group_id = $request->input('blood_group_id');

                $newEntry->differently_abled = $request->input('differently_abled');
                $newEntry->marital_status_id = $request->input('marital_status_id');
                $newEntry->occupation = $request->input('occupation');
                $newEntry->alt_mobile_no = $request->input('alt_mobile_no');
                $newEntry->annual_income_id = $request->input('income_id');
                $newEntry->qual_id = $request->input('qual_id');
                $newEntry->earlier_pri = 0; //$request->input('earlier_pri');

                $newEntry->o_add = $request->input('o_add');
                $newEntry->o_pin = $request->input('o_pin');
                $newEntry->p_add = $request->input('p_add');
                $newEntry->p_pin = $request->input('p_pin');
                $newEntry->p_district = $request->input('p_district');
                $newEntry->photo_i_proof = $pri_photo_id_path;

                $newEntry->created_by = $users->username;

                $newEntry->save();

                if (!$newEntry->save()) {
                    DB::rollback();
                    $returnData['msg'] = "Opps! Something went wrong.";
                    return $returnData;
                }


                $memberHistory = new PriMemberTermHistory();
                $memberHistory->pri_member_main_record_id = $newEntry->id;
                $memberHistory->master_pri_term_id = 4; //----------CURRENT TERM-----------------
                $memberHistory->pri_master_designation_id = $request->input('t_deg4');
                $memberHistory->save();

                if (!$memberHistory->save()) {
                    DB::rollback();
                    $returnData['msg'] = "Opps! Something went wrong.";
                    return $returnData;
                }

                if ($request->input('earlier_pri')) {
                    $ifEarlier = $this->saveEarlierMemberHistory($request, $newEntry->id);

                    if (!$ifEarlier) {
                        DB::rollback();
                        $returnData['msg'] = "You have selected YES in Earlier Elected as PRI. 
                        So, you have to select at least one designation.";
                        return $returnData;
                    }
                }

                $returnData['msg'] = "Successfully submitted the PRI details of " . $request->input('pri_f_name') . " " . $request->input('pri_m_name') . " " . $request->input('pri_l_name');

                //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                //------------------------------Save Block------------------------------------------------------------------
                //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

            } else {

                $id = $request->input('editCode');
                $pri_code = $request->input('editPriCode');

                $canEdit = $this->canEdit($id, $pri_code);

                if (!$canEdit) {
                    $returnData['msg'] = "Sorry edit can not be done. Something went wrong.!";
                    return response()->json($returnData);
                }

                $checkMember = $this->edit_checkAlreadyExists($request->input('mobile_no'), $pri_code);

                if (!$checkMember) {
                    $returnData['msg'] = "Mobile number already exists";
                    return response()->json($returnData);
                }



                if ($t_deg4 == 1 || $t_deg4 == 2 || $t_deg4 == 7) {
                    $upEntry = PriMemberMainRecord::find($id);

                    $upEntry->zilla_id = $zp_id;
                    $upEntry->constituency = $request->input('constituency');
                    $upEntry->seat_reserved = $seat_reserved;
                    $upEntry->party_id = $party_id;

                    $upEntry->anchalik_id = NULL;
                    $upEntry->ap_constituency = NULL;
                    $upEntry->gram_panchayat_id = NULL;
                    $upEntry->ward_id = NULL;
                } else if ($t_deg4 == 3 || $t_deg4 == 4 || $t_deg4 == 8) {
                    $upEntry = PriMemberMainRecord::find($id);

                    $upEntry->zilla_id = $zp_id;
                    $upEntry->anchalik_id = $request->input('anchalik_code');
                    $upEntry->ap_constituency = $request->input('ap_constituency');
                    $upEntry->seat_reserved = $seat_reserved;
                    $upEntry->party_id = $party_id;


                    $upEntry->constituency = NULL;
                    $upEntry->gram_panchayat_id = NULL;
                    $upEntry->ward_id = NULL;
                } else if ($t_deg4 == 5 || $t_deg4 == 6 || $t_deg4 == 9) {
                    $upEntry = PriMemberMainRecord::find($id);

                    $upEntry->zilla_id = $zp_id;
                    $upEntry->anchalik_id = $request->input('anchalik_code');
                    $upEntry->gram_panchayat_id = $request->input('gp_code');

                    if ($t_deg4 == 6 || $t_deg4 == 9) {
                        $upEntry->ward_id = $request->input('ward_no');
                    } else {
                        $upEntry->ward_id = NULL;
                    }

                    $upEntry->seat_reserved = $seat_reserved;
                    $upEntry->party_id = $party_id;

                    $upEntry->constituency = NULL;
                    $upEntry->ap_constituency = NULL;
                } else {
                    $returnData['msg'] = "Opps! Unauthorised access.!";
                    return response()->json($returnData);
                }

                if ($request->file('pic')) {
                    $pri_pic_path = $request->file('pic')->store('pris/pri_pic');
                    $upEntry->pri_pic = $pri_pic_path;
                }

                $upEntry->pri_f_name = $request->input('pri_f_name');
                $upEntry->pri_m_name = $request->input('pri_m_name');
                $upEntry->pri_l_name = $request->input('pri_l_name');
                $upEntry->mobile_no = $request->input('mobile_no');
                $upEntry->dob = $request->input('pri_dob');
                $upEntry->gender_id = $request->input('gender_id');
                $upEntry->caste_id = $request->input('caste_id');
                $upEntry->religion_id = $request->input('religion_id');
                $upEntry->blood_group_id = $request->input('blood_group_id');

                $upEntry->differently_abled = $request->input('differently_abled');
                $upEntry->marital_status_id = $request->input('marital_status_id');
                $upEntry->occupation = $request->input('occupation');
                $upEntry->alt_mobile_no = $request->input('alt_mobile_no');
                $upEntry->annual_income_id = $request->input('income_id');
                $upEntry->qual_id = $request->input('qual_id');
                $upEntry->earlier_pri = 0; //$request->input('earlier_pri');

                $upEntry->o_add = $request->input('o_add');
                $upEntry->o_pin = $request->input('o_pin');
                $upEntry->p_add = $request->input('p_add');
                $upEntry->p_pin = $request->input('p_pin');
                $upEntry->p_district = $request->input('p_district');

                if ($request->file('photo_i_proof')) {
                    $pri_photo_id_path = $request->file('photo_i_proof')->store('pris/pri_photo_id');
                    $upEntry->photo_i_proof = $pri_photo_id_path;
                }


                $upEntry->updated_by = $users->username;

                $upEntry->save();

                if (!$upEntry->save()) {
                    DB::rollback();
                    $returnData['msg'] = "Opps! Something went wrong.";
                    return $returnData;
                }

                $deleteHistoryRecords = $this->deleteEarlierMemberHistory($id);

                if (!$deleteHistoryRecords) {
                    DB::rollback();
                    $returnData['msg'] = "Opps! Something went wrong.";
                    return $returnData;
                }

                $memberHistory = new PriMemberTermHistory();
                $memberHistory->pri_member_main_record_id = $id;
                $memberHistory->master_pri_term_id = 4; //----------CURRENT TERM-----------------
                $memberHistory->pri_master_designation_id = $request->input('t_deg4');
                $memberHistory->save();

                if (!$memberHistory->save()) {
                    DB::rollback();
                    $returnData['msg'] = "Opps! Something went wrong.";
                    return $returnData;
                }

                $returnData['msg'] = "Successfully edited the PRI details of " . $request->input('pri_f_name') . " " . $request->input('pri_m_name') . " " . $request->input('pri_l_name');

                /*if ($request->input('earlier_pri')) {
                $ifEarlier = $this->saveEarlierMemberHistory($request, $id);
                if (!$ifEarlier) {
                DB::rollback();
                $returnData['msg'] = "You have selected YES in Earlier Elected as PRI. 
                So, you have to select at least one designation.";
                return $returnData;
                }
                }*/
            }
        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went wrong.EX" . $e->getMessage();
            return $returnData;
        }


        DB::commit();

        $returnData['msgType'] = true;
        $returnData['data'] = [];
        return response()->json($returnData);
    }

    /*private function saveEarlierMemberHistory($request, $id){
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
    }*/

    private function deleteEarlierMemberHistory($id)
    {

        $delete = PriMemberTermHistory::where('pri_member_main_record_id', '=', $id)->delete();

        if (!$delete) {
            return false;
        }
        return true;
    }

    private function checkAlreadyExists($mobile_no)
    {
        $checkMember = PriMemberMainRecord::where([
            ['mobile_no', '=', $mobile_no]
        ])->first();

        if ($checkMember) {
            return false;
        }

        return true;
    }

    private function edit_checkAlreadyExists($mobile_no, $pri_code)
    {
        $checkMember = PriMemberMainRecord::where([
            ['mobile_no', '=', $mobile_no],
            ['pri_code', '<>', $pri_code],
        ])->first();

        if ($checkMember) {
            return false;
        }

        return true;
    }

    private function canEdit($id, $pri_code)
    {
        $checkMember = PriMemberMainRecord::where([
            ['id', '=', $id],
            ['pri_code', '=', $pri_code]
        ])->count();

        if ($checkMember == 1) {
            return true;
        }
        return false;
    }

    public function view(Request $request)
    {
        $data['msgType'] = false;
        $data['data'] = [];

        $selectedGpList = [];
        $ap_con_gps = [];

        $pri_code = $request->input('pri_code');

        $priMember = PriMemberMainRecord::where([
            ['pri_code', '=', $pri_code]
        ])->first();

        if ($priMember) {
            $priMemberHistory = PriMemberTermHistory::where([
                ['pri_member_main_record_id', '=', $priMember->id]
            ])->get();

            foreach ($priMemberHistory as $history) {
                if ($history->master_pri_term_id == 4) {

                    if ($history->pri_master_designation_id == 3 || $history->pri_master_designation_id == 4 || $history->pri_master_designation_id == 8) {

                        $ap_con_gps = GramPanchyat::where([
                            ['anchalik_id', '=', $priMember->anchalik_id],
                            ['vcc', '=', NULL],
                        ])->select('gram_panchyat_id AS id', 'gram_panchayat_name')->get();
                    } elseif ($history->pri_master_designation_id == 5 || $history->pri_master_designation_id == 6 || $history->pri_master_designation_id == 9) {

                        $sEmpCode = SixFinanceGpSelectionList::where([
                            ['gp_code', '=', $priMember->gram_panchayat_id]
                        ])->select('emp_code')->first();

                        if ($sEmpCode) {
                            $selectedGpList = GramPanchyat::where([
                                ['anchalik_id', '=', $priMember->anchalik_id],
                                ['vcc', '=', NULL],
                            ])->select('gram_panchyat_id AS id', 'gram_panchayat_name')->get();
                        }
                    }
                }
            }
        } else {
            $data['msg'] = "Data not found.";
            return $data;
        }
        $bankdetails = DB::table('pri_members_bank_records')
            ->join('banks', 'pri_members_bank_records.bank_id', '=', 'banks.id')
            ->where('pri_member_main_record_id', $priMember->id)
            ->select('bank_name', 'account_no', 'branch_name', 'ifsc_code', 'pass_book')
            ->first();

        if (!$bankdetails) {
            $bankdetails = [
                'bank_name' => 'NA',
                'ifsc_code' => 'NA',
                'account_no' => '00',
                'branch_name' => 'NA',
                'pass_book' => 'NA',
            ];
        }

        $data['msgType'] = true;
        $data['msg'] = "Successfully done the task.";
        $data['data'] = array('priMember' => $priMember, 'priMemberHistory' => $priMemberHistory, 'ap_con_gps' => $ap_con_gps, 'selectedGpList' => $selectedGpList, 'bank' => $bankdetails);
        return $data;
    }

    public function sendBankBranch(Request $request)
    {
        $check = DB::table('banks')
            ->where("id", $request->id)
            ->select('bank_name')
            ->get();

        $data = [];

        if ($check != null) {
            $result = DB::table('bank_ifscs')
                ->where("bank_id", $request->id)
                ->select('id', 'branch_name', 'ifsc_code')
                ->get();
            array_push($data, $result);
            return $data;
        } else {
            return "Not found!";
        }
    }

    // -------------------------------------- GET GP BY ANCHALIK ID ----------------------------------------------------

    public function getGPsByAnchalikId(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];

        $anchalik_id = $request->input('anchalik_code');

        if (!$anchalik_id) {
            $returnData['msg'] = "GP List not found";
            return response()->json($returnData);
        }

        $gps = GramPanchyat::where([
            ['anchalik_id', '=', $anchalik_id],
            ['vcc', '=', NULL],
        ])->select('gram_panchyat_id AS id', 'gram_panchayat_name')
            ->get();

        if (empty($gps)) {
            $returnData['msg'] = "GP List not found";
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = $gps;
        $returnData['msg'] = "Success";
        return response()->json($returnData);
    }

    // ----------------------------------------------------------Bank details------------------------------------------------------------------
    public function store(PriBankRequest $request)
    {
        $jdata[0] = [
            ''
        ];
        if ($request->ajax()) {
            $pri_id = DB::table('pri_member_main_records')
                ->where(
                    ['pri_code' => $request->pricode]
                )
                ->value("id");
            $path = "";
            if ($request->hasFile('bank_image')) {
                if ($request->file('bank_image')->isValid()) {
                    $path = $request->file('bank_image')->store('bank_images');
                } else {
                    $jdata[1] = [
                        'error' => 'Sorry could not be submitted! Please try again.',
                    ];
                }
            } else {
                $jdata[1] = [
                    'error' => 'Sorry could not be submitted! Please try again.',
                ];
            }

            if ($path != "") {
                DB::beginTransaction();

                try {
                    $bank = new PriMembersBankRecord;
                    $bank->pri_member_main_record_id = $pri_id;
                    $bank->bank_id = $request->bank_name;
                    $bank->ifsc_code = $request->ifsc;
                    $bank->account_no = $request->acc_no;
                    $bank->branch_name = $request->branch_name;
                    $bank->pass_book = $path;
                    $bank->save();
                    DB::commit();
                    $jdata[1] = [
                        'success' => 'Successfully Submitted!',
                    ];
                } catch (\Exception $e) {
                    $jdata[1] = [
                        'error' => 'Sorry could not be submitted! Please try again.' . $e->getMessage(),
                    ];

                    DB::rollback();
                }
            }
        }

        return response()->json($jdata);
    }

    // -------------------------------------- bank report for members---------------------------------
    public function bankReport()
    {

        $submittedZPs = ZilaParishad::select('id', 'zila_parishad_name')
            ->where('is_active', 1)
            ->orderBy('zila_parishad_name')
            ->get();

        $gpcount = [];
        $zpstrength = [
            0b10, 030, 0xD, 0b1100, 033, 0x7, 0b1110, 007, 0x18, 0b10111, 020, 0x11, 0b1011, 014, 0x13, 0b11010, 006, 0x14, 0b10001, 004, 0xD, 0b011101, 015, 0x11, 0b10011, 005, 0x14];
        $zpBankRecordCount = [];
        $apBankRecordCount = [];
        $gpBankRecordCount = [];


        $i = 0;
        foreach ($submittedZPs as $val) {
            $aps = DB::table('anchalik_parishads')
                ->where('zila_id', $val->id)
                ->select('id')
                ->get();

            $ap_id = [];
            $j = 0;
            foreach ($aps as $values) {
                $ap_id[$j] = $values->id;
                $j++;
            }
            $gpcount[$i] = DB::table('gram_panchyats')
                ->whereIn('anchalik_id', $ap_id)
                ->count();

            // ------------------------this is done to count and distinct for ZP start------------------------------
            $myqueryzp = "SELECT COUNT(DISTINCT pri_members_bank_records.id) as mycountzp FROM pri_members_bank_records join pri_member_main_records ON pri_members_bank_records.pri_member_main_record_id =pri_member_main_records.id join pri_member_term_histories ON pri_members_bank_records.pri_member_main_record_id = pri_member_term_histories.pri_member_main_record_id where pri_member_main_records.zilla_id= '" . $val->id . "' AND pri_master_designation_id IN (1, 2, 7) AND pri_member_term_histories.master_pri_term_id = 4";
            $zpBankRecordCount[$i] = DB::select($myqueryzp);

            // ----------------------------this is done to count and distinct for ZP end------------------------------


            // -------------------------this is done to count and distinct for AP start---------------------------------

            $myqueryap = "SELECT COUNT(DISTINCT pri_members_bank_records.id) as mycountap FROM pri_members_bank_records join pri_member_main_records ON pri_members_bank_records.pri_member_main_record_id =pri_member_main_records.id join pri_member_term_histories ON pri_members_bank_records.pri_member_main_record_id = pri_member_term_histories.pri_member_main_record_id where pri_member_main_records.zilla_id= '" . $val->id . "' AND pri_master_designation_id IN (3, 4, 8) AND pri_member_term_histories.master_pri_term_id = 4";
            $apBankRecordCount[$i] = DB::select($myqueryap);

            // -------------------------this is done to count and distinct for AP end---------------------------------


            // -------------------------this is done to count and distinct for GP start---------------------------------

            $myquerygp = "SELECT COUNT(DISTINCT pri_members_bank_records.id) as mycountgp FROM pri_members_bank_records join pri_member_main_records ON pri_members_bank_records.pri_member_main_record_id =pri_member_main_records.id join pri_member_term_histories ON pri_members_bank_records.pri_member_main_record_id = pri_member_term_histories.pri_member_main_record_id where pri_member_main_records.zilla_id= '" . $val->id . "' AND pri_master_designation_id IN (5, 6, 9) AND pri_member_term_histories.master_pri_term_id = 4";
            $gpBankRecordCount[$i] = DB::select($myquerygp);

            // -------------------------this is done to count and distinct for GP end---------------------------------

            $totalZpStrength = array_sum($zpstrength);

            //----------------------making a new total zp bank count start----------------------------
            $zpbanktemp = array();
            for ($k = 0; $k < sizeof($zpBankRecordCount); $k++) {
                array_push($zpbanktemp, $zpBankRecordCount[$k][0]->mycountzp);
            }
            $totalZpBankRecordCount = array_sum($zpbanktemp);
            // -----------------------making a new total zp bank count end----------------------------

            //----------------------making a new total ap bank count start----------------------------
            $apbanktemp = array();
            for ($k = 0; $k < sizeof($apBankRecordCount); $k++) {
                array_push($apbanktemp, $apBankRecordCount[$k][0]->mycountap);
            }
            $totalapBankRecordCount = array_sum($apbanktemp);
            // -----------------------making a new total zp bank count end----------------------------

            //----------------------making a new total gp bank count start----------------------------
            $gpbanktemp = array();
            for ($k = 0; $k < sizeof($gpBankRecordCount); $k++) {
                array_push($gpbanktemp, $gpBankRecordCount[$k][0]->mycountgp);
            }
            $totalgpBankRecordCount = array_sum($gpbanktemp);
            // -----------------------making a new total gp bank count end----------------------------

            $totalaps = array_sum(($gpcount));
            $totalgps = array_sum(($gpcount));

            $i++;
        }
        return view('Pris.Member.bankReport', compact('submittedZPs', 'gpcount', 'zpstrength', 'zpBankRecordCount', 'apBankRecordCount', 'gpBankRecordCount', 'totalZpStrength', 'totalZpBankRecordCount', 'totalapBankRecordCount', 'totalgpBankRecordCount', 'totalaps', 'totalgps', 'aps'));
    }
    // -------------------------------------------------------------------------------------------------
    // -------------------------------------------------------------------------------------------------
    // -------------------------------------------------------------------------------------------------
    public function bankSubDistrict($id)
    {
        $apcount = AnchalikParishad::where('zila_id', $id)
            ->orderBy('anchalik_parishad_name')
            ->get();


        $gpcount = [];
        $apBankRecordCount = [];
        $gpBankRecordCount = [];
        $apall = [];
        $ap_id = [];


        $i = 0;
        $j = 0;
        foreach ($apcount as $val) {
            $ap_id[$j] = $val->id;
            $j++;

            $gpcount[$i] = DB::table('gram_panchyats')
                ->whereIn('anchalik_id', $ap_id)
                ->count();

            // dd($gpcount);

            // -------------------------this is done to count and distinct for AP strength start------------------------------
            $myapStrengthquery = "SELECT COUNT(DISTINCT pri_member_main_records.id) as myapStrength  FROM `pri_member_main_records` 
            -- JOIN pri_members_bank_records ON pri_members_bank_records.pri_member_main_record_id=pri_member_main_records.id
            JOIN pri_member_term_histories ON pri_member_term_histories.pri_member_main_record_id=pri_member_main_records.id
            
            WHERE `zilla_id` = '" . $id . "' 
                and anchalik_id= '" . $ap_id[$i] . "'
                and gram_panchayat_id is NULL
                and pri_member_term_histories.master_pri_term_id=4";
            $apStrength[$i] = DB::select($myapStrengthquery);

            // -------------------------this is done to count and distinct for AP strength end---------------------------------


            // -------------------------this is done to count and distinct for AP bank start---------------------------------

            $myqueryap = "SELECT COUNT(DISTINCT pri_members_bank_records.id) as mycountap FROM pri_members_bank_records 
                JOIN pri_member_main_records ON pri_members_bank_records.pri_member_main_record_id = pri_member_main_records.id
                JOIN pri_member_term_histories ON pri_members_bank_records.pri_member_main_record_id = pri_member_term_histories.pri_member_main_record_id
                WHERE pri_member_main_records.zilla_id='" . $id . "'
                AND pri_member_main_records.anchalik_id='" . $ap_id[$i] . "'
                AND pri_member_main_records.gram_panchayat_id is NULL
                AND pri_member_term_histories.master_pri_term_id = 4";
            $apBankRecordCount[$i] = DB::select($myqueryap);

            // -------------------------this is done to count and distinct for AP Bank end---------------------------------


            // -------------------------this is done to count and distinct for GP Bank start---------------------------------

            $myquerygp = "SELECT COUNT(DISTINCT pri_members_bank_records.id) as mycountgp FROM pri_members_bank_records 
            JOIN pri_member_main_records ON pri_members_bank_records.pri_member_main_record_id = pri_member_main_records.id
            JOIN pri_member_term_histories ON pri_members_bank_records.pri_member_main_record_id = pri_member_term_histories.pri_member_main_record_id
            WHERE pri_member_main_records.zilla_id='" . $id . "'
            AND pri_member_main_records.anchalik_id='" . $ap_id[$i] . "'
            -- AND pri_member_main_records.gram_panchayat_id is NULL
            AND pri_member_term_histories.master_pri_term_id = 4
            AND pri_member_term_histories.pri_master_designation_id in (6,5,9)";
            $gpBankRecordCount[$i] = DB::select($myquerygp);

            // -------------------------this is done to count and distinct for GP Bank end---------------------------------

            $i++;
        }

        return view('Pris.Member.bankSubDistrict', compact('gpcount', 'apStrength', 'apBankRecordCount', 'gpBankRecordCount', 'apcount'));
    }
    // -------------------------------------------------------------------------------------------------
    // -------------------------------------------------------------------------------------------------
    // -------------------------------------------------------------------------------------------------
    // -------------------------------------------------------------------------------------------------

    public function bankSubDistrictGP($id)
    {

        $gramid = GramPanchyat::where('anchalik_id', $id)
            ->where('is_active', 1)
            ->orderBy('gram_panchayat_name')
            ->get();

        $gpcount = [];
        $gpBankRecordCount = [];
        $gp_id = [];


        $i = 0;
        $j = 0;

        foreach ($gramid as $val) {
            $gp_id[$j] = $val->gram_panchyat_id;
            $j++;

            $gpcount[$i] = DB::table('gram_panchyats')
                ->whereIn('gram_panchyat_id', $gp_id)
                ->count();

            // this one is shiiit
            // dd($gpcount);

                 // -------------------------this is done to count and distinct for GP strength start------------------------------
            $mygpStrengthquery = "SELECT COUNT(DISTINCT pri_member_main_records.id) as mygpStrength  FROM `pri_member_main_records` 
            JOIN pri_member_term_histories ON pri_member_term_histories.pri_member_main_record_id=pri_member_main_records.id
                WHERE 
                pri_member_main_records.anchalik_id='" . $id. "'
                and pri_member_main_records.gram_panchayat_id= '" . $gp_id[$i] . "'
                and pri_member_term_histories.master_pri_term_id= 4 ";
            $gpStrength[$i] = DB::select($mygpStrengthquery);

            // -------------------------this is done to count and distinct for GP strength end---------------------------------

            
            // -------------------------this is done to count and distinct for GP Bank start---------------------------------

            $myquerygp = "SELECT COUNT(DISTINCT pri_members_bank_records.id) as mycountgp FROM pri_members_bank_records 
            JOIN pri_member_main_records ON pri_members_bank_records.pri_member_main_record_id = pri_member_main_records.id
            JOIN pri_member_term_histories ON pri_members_bank_records.pri_member_main_record_id = pri_member_term_histories.pri_member_main_record_id
            WHERE 
            pri_member_main_records.anchalik_id='" . $id . "'
            AND pri_member_main_records.gram_panchayat_id='" . $gp_id[$i] . "'
            AND pri_member_term_histories.master_pri_term_id = 4
            AND pri_member_term_histories.pri_master_designation_id in (6,5,9)";
            $gpBankRecordCount[$i] = DB::select($myquerygp);

            // -------------------------this is done to count and distinct for GP Bank end---------------------------------

            $i++;
        }
    
        return view('Pris.Member.bankSubDistrictGP', compact('gramid', 'gpcount', 'gpStrength', 'gpBankRecordCount'));
    }

    // ---------------------------------------------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------------------------------
    // ----------------------------------Mishra sir doesnt want PRI wala ATM----------------------------------------------
    // ---------------------------------------------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------------------------------

}
