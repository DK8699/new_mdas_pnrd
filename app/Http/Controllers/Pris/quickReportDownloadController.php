<?php

namespace App\Http\Controllers\Pris;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CommonModels\GramPanchyat;
use App\Pris\PriMemberMainRecord;
use App\survey\six_finance\AnchalikParishad;
use App\survey\six_finance\ZilaParishad;

use Auth;

class quickReportDownloadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'user_mdas']);
    }
    public function quickReportDownloadParty (Request $request)
    {
        $users = Auth::user();
        $district_code = $users->district_code;

///////////////////////////////////////////////////////ZP///////////////////////////////////////////////////////////////



        $zpsD = ZilaParishad::where([
            ['district_id', '=', $district_code]
        ])->get();
        foreach ($zpsD as $zps) {
        $zpsDownload = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
            ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
            ->join('pri_master_designations AS d', 'd.id', '=', 'h.pri_master_designation_id')
            ->join('master_pri_political_parties AS pp', 'pp.id', '=', 'pri_member_main_records.party_id')
            ->join('master_genders AS g', 'g.id', '=', 'pri_member_main_records.gender_id')
            ->join('master_pris_reserve_seats AS r', 'r.id', '=', 'pri_member_main_records.seat_reserved')
            ->join('master_castes AS c', 'c.id', '=', 'pri_member_main_records.caste_id')
			->join('master_highest_qualifications  AS q', 'q.id', '=', 'pri_member_main_records.qual_id')
			->join('master_annual_incomes  AS i', 'i.id', '=', 'pri_member_main_records.annual_income_id')
			
            ->where([
                ['h.master_pri_term_id', '=', 4],
                ['pri_member_main_records.zilla_id', '=', $zps->id]
            ])
            ->select('zp.zila_parishad_name', 'pri_f_name', 'pri_m_name', 'pri_l_name', 'dob', 'q.qual_name', 'i.income_name', 'g.gender_name', 'mobile_no','constituency','d.design_name', 'pp.party_name', 'r.seat_name', 'c.caste_name')
            ->whereIn('h.pri_master_designation_id', [1,2,7])->get();

            $apsD = AnchalikParishad::getAPsByZilaId($zps->id);
        foreach ($apsD as $aps) {
            $gpsDArray = [];
        $apsDownload = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
            ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
            ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
            ->join('pri_master_designations AS d', 'd.id', '=', 'h.pri_master_designation_id')
            ->join('master_pri_political_parties AS pp', 'pp.id', '=', 'pri_member_main_records.party_id')
            ->join('master_genders AS g', 'g.id', '=', 'pri_member_main_records.gender_id')
            ->join('master_pris_reserve_seats AS r', 'r.id', '=', 'pri_member_main_records.seat_reserved')
            ->join('master_castes AS c', 'c.id', '=', 'pri_member_main_records.caste_id')
			->join('master_highest_qualifications  AS q', 'q.id', '=', 'pri_member_main_records.qual_id')
			->join('master_annual_incomes  AS i', 'i.id', '=', 'pri_member_main_records.annual_income_id')
            ->leftjoin('gram_panchyats AS gc', 'gc.gram_panchyat_id', '=', 'pri_member_main_records.ap_constituency')
            ->where([
                ['h.master_pri_term_id', '=', 4],
                ['pri_member_main_records.zilla_id', '=', $zps->id]
            ])->whereIn('h.pri_master_designation_id', [3,4,8])
            ->select('zp.zila_parishad_name', 'ap.anchalik_parishad_name', 'pri_f_name', 'pri_m_name', 'pri_l_name', 'dob', 'q.qual_name', 'i.income_name', 'g.gender_name', 'mobile_no','gc.gram_panchayat_name', 'd.design_name', 'pp.party_name', 'r.seat_name', 'c.caste_name')
            ->orderBy('ap.anchalik_parishad_name', 'asc')->orderBy('d.priority', 'asc')->get();
 
            $gpsD = GramPanchyat::where('anchalik_id', '=', $aps->id)->get();
            foreach ($gpsD as $gps) {
        $gpsDownload = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
            ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
            ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
            ->join('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
            ->join('pri_master_designations AS d', 'd.id', '=', 'h.pri_master_designation_id')
            ->join('master_pri_political_parties AS pp', 'pp.id', '=', 'pri_member_main_records.party_id')
            ->join('master_genders AS g', 'g.id', '=', 'pri_member_main_records.gender_id')
            ->join('master_pris_reserve_seats AS r', 'r.id', '=', 'pri_member_main_records.seat_reserved')
            ->join('master_castes AS c', 'c.id', '=', 'pri_member_main_records.caste_id')
			->join('master_highest_qualifications  AS q', 'q.id', '=', 'pri_member_main_records.qual_id')
			->join('master_annual_incomes  AS i', 'i.id', '=', 'pri_member_main_records.annual_income_id')
            ->leftjoin('master_wards AS w', 'w.id', '=', 'pri_member_main_records.ward_id')
            ->where([
                ['h.master_pri_term_id', '=', 4],
                ['pri_member_main_records.zilla_id', '=', $zps->id]
            ])->whereIn('h.pri_master_designation_id', [5,6,9])->orderBy('ap.anchalik_parishad_name', 'asc')
            ->select('zp.zila_parishad_name', 'ap.anchalik_parishad_name','gp.gram_panchayat_name', 'pri_f_name', 'pri_m_name', 'pri_l_name', 'dob', 'q.qual_name', 'i.income_name', 'g.gender_name','w.ward_name', 'mobile_no','d.design_name', 'pp.party_name', 'r.seat_name', 'c.caste_name')
            ->orderBy('d.priority', 'asc')->get();

            }
            }
            }
            
        return view('Pris.Member.quickReportDownload',compact('zpsDownload','apsDownload','gpsDownload' ));

    }
}
