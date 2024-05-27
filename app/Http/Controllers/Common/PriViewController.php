<?php

namespace App\Http\Controllers\Common;

use DB;
use App\ConfigMdas;
use App\Pris\PriMemberMainRecord;
use App\Pris\PriMasterDesignation;
use App\Pris\PriMemberTermHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PriViewController extends Controller
{

    public function viewPri(Request $request)
    {
        $data['msgType'] = false;
        $data['msg'] = "Oops! something went wrong";
        $data['data'] = [];

        $priCode = $request->input('pri_code');

        if (!$priData = $this->checkPriCodeExits($priCode)) {
            $data['msg'] = "No record found";
            return response()->json($data);
        }

        $results = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
            ->join('pri_master_designations AS d', 'd.id', '=', 'h.pri_master_designation_id')
            ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
            ->leftjoin('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
            ->leftjoin('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
            ->leftjoin('master_pris_reserve_seats AS rs', 'rs.id', '=', 'pri_member_main_records.seat_reserved')
            ->leftjoin('master_pri_political_parties AS pp', 'pp.id', '=', 'pri_member_main_records.party_id')
            ->leftjoin('master_genders AS gd', 'gd.id', '=', 'pri_member_main_records.gender_id')
            ->leftjoin('master_religions AS rl', 'rl.id', '=', 'pri_member_main_records.religion_id')
            ->leftjoin('master_blood_groups AS bg', 'bg.id', '=', 'pri_member_main_records.blood_group_id')
            ->leftjoin('master_highest_qualifications AS hq', 'hq.id', '=', 'pri_member_main_records.qual_id')
            ->leftjoin('master_castes AS mc', 'mc.id', '=', 'pri_member_main_records.caste_id')
            ->leftjoin('master_marital_statuses AS ms', 'ms.id', '=', 'pri_member_main_records.marital_status_id')
            ->leftjoin('master_annual_incomes AS ai', 'ai.id', '=', 'pri_member_main_records.annual_income_id')
            ->leftjoin('master_wards AS wrd', 'wrd.id', '=', 'pri_member_main_records.ward_id')
            ->leftjoin('gram_panchyats AS ap_con', 'ap_con.gram_panchyat_id', '=', 'pri_member_main_records.ap_constituency')
            ->leftjoin('districts AS p_dis', 'p_dis.id', '=', 'pri_member_main_records.p_district')
            ->where([
                ['h.master_pri_term_id', '=', 4],
                ['pri_member_main_records.id', '=', $priData->id],
            ])->select(
                'pri_member_main_records.id AS pmmr_id',
                'o_add',
                'o_pin',
                'p_add',
                'p_pin',
                'p_district',
                'mobile_no',
                'dob',
                'pri_code',
                'pri_f_name',
                'pri_m_name',
                'pri_l_name',
                'constituency',
                'occupation',
                //foreign data
                'gd.gender_name',
                'rl.religion_name',
                'bg.blood_name',
                'hq.qual_name',
                'mc.caste_name',
                'ms.marital_status_name',
                'rs.seat_name',
                'pp.party_name',
                'ai.income_name',
                'd.design_name',
                'd.id as design_id',
                'pri_pic',
                'h.id AS pri_md_id',
                'zp.zila_parishad_name',
                'ap.anchalik_parishad_name',
                'gp.gram_panchayat_name',
                'wrd.ward_name',
                'ap_con.gram_panchayat_name AS ap_constituency',
                'p_dis.district_name AS p_district_name',
                'pri_member_main_records.differently_abled'
            )
            ->first();
        if (!$results) {
            $data['msg'] = "No record found";
            return response()->json($data);
        }
        if ($results) {
            $main_record_id = $results->pmmr_id;
            $terms_all = DB::table('master_pri_terms as pmt')->orderBy('id', 'DESC')->get();
            $terms = DB::table('master_pri_terms as pmt')
                ->leftJoin('pri_member_term_histories as pmth', 'pmt.id', '=', 'pmth.master_pri_term_id')
                ->join('pri_master_designations as pmd', 'pmth.pri_master_designation_id', '=', 'pmd.id')
                ->where([['pmth.pri_member_main_record_id', '=', $main_record_id]])
                ->select('pmd.id AS pmd_id', 'pmd.design_name AS pmd_d_name', 'pmt.id AS pmt_id', 'pmt.term_name')
                ->get();
        }
        $imgUrl = ConfigMdas::allActiveList()->imgUrl;
        $bankdetails = [];
        $bankdetails = DB::table('pri_members_bank_records')
            ->join('banks', 'pri_members_bank_records.bank_id', '=', 'banks.id')
            ->where('pri_member_main_record_id', $priData->id)
            ->select('bank_name', 'ifsc_code', 'account_no', 'branch_name', 'pass_book')
            ->first();
        if (!$bankdetails) {
            $bankdetails = [
                'bank_name' => 'NA',
                'ifsc_code' => 'NA',
                'account_no' => 'NA',
                'branch_name' => 'NA',
                'pass_book' => 'NA',
            ];
        }
        $data['msgType'] = true;
        $data['msg'] = "Successfully done the task";
        $data['data'] = array("res" => $results, 'imgUrl' => $imgUrl, 'terms' => $terms, 'all_terms' => $terms_all, 'bank' => $bankdetails);

        return response()->json($data);
    }

    private function checkPriCodeExits($priCode)
    {
        $result = PriMemberMainRecord::where([
            ['pri_code', '=', $priCode]
        ])->first();
        return $result;
    }
}