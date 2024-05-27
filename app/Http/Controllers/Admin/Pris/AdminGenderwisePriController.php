<?php

namespace App\Http\Controllers\Admin\Pris;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CommonModels\GramPanchyat;
use App\Pris\PriMemberMainRecord;
use App\survey\six_finance\AnchalikParishad;
use App\survey\six_finance\ZilaParishad;
use Carbon\Carbon;

class AdminGenderwisePriController extends Controller
{
    public function priFemaleMenu ()
    {
        return view('admin.Pris.priFemaleMenu');
    }
    public function quickReportFemaleZP (Request $request)
    {
        $zpsDownload=[];

        $datePre = Carbon::parse('2019-01-01')->subYears(30);
        $dateNow = Carbon::parse('2019-01-01');

        $now = Carbon::now();

        $testdate = $dateNow->diff($now)->format('%y years, %m months');

        $zpsArray = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->join('pri_master_designations AS d', 'd.id', '=', 'h.pri_master_designation_id')
                ->join('master_genders AS g', 'g.id', '=', 'pri_member_main_records.gender_id')
                ->join('master_castes AS c', 'c.id', '=', 'pri_member_main_records.caste_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['g.id', '=',2],
                    ['pri_member_main_records.dob', '>=', $datePre ]
                ])->select('zp.id AS zp_id','zp.zila_parishad_name', 'pri_f_name',
                    'pri_m_name', 'pri_l_name','dob','g.gender_name',
                    'mobile_no','d.design_name', 'c.caste_name')
                ->whereIn('h.pri_master_designation_id', [1])
                ->get();


        foreach($zpsArray AS $li){
            array_push($zpsDownload, [
                "zp_id"=>$li->zp_id,
                "zila_parishad_name"=>$li->zila_parishad_name,

                "pri_name"=>$li->pri_f_name." ".$li->pri_m_name." ".$li->pri_l_name,

                "dob"=>Carbon::parse($li->dob)->format('d M Y'),
                "age"=>$dateNow->diff(Carbon::parse($li->dob))->format('%y years, %m months'),

                "gender_name"=>$li->gender_name,
                "mobile_no"=>$li->mobile_no,
                "design_name"=>$li->design_name,
                "caste_name"=>$li->caste_name,
            ]);
        }

        return view('admin.Pris.quickReportFemaleZP',compact('zpsDownload'));

    }

    public function quickReportFemaleAP (Request $request)
    {

        $apsDownload=[];

        $datePre = Carbon::parse('2019-01-01')->subYears(30);
        $dateNow = Carbon::parse('2019-01-01');

        $apsArray = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->join('pri_master_designations AS d', 'd.id', '=', 'h.pri_master_designation_id')
                ->join('master_pri_political_parties AS pp', 'pp.id', '=', 'pri_member_main_records.party_id')
                ->join('master_genders AS g', 'g.id', '=', 'pri_member_main_records.gender_id')
                ->join('master_pris_reserve_seats AS r', 'r.id', '=', 'pri_member_main_records.seat_reserved')
                ->join('master_castes AS c', 'c.id', '=', 'pri_member_main_records.caste_id')
                ->leftjoin('gram_panchyats AS gc', 'gc.gram_panchyat_id', '=', 'pri_member_main_records.ap_constituency')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['g.id', '=',2],
                    ['pri_member_main_records.dob', '>=', $datePre ]
                ])->whereIn('h.pri_master_designation_id', [3])
                ->select('zp.zila_parishad_name', 'ap.id AS ap_id', 'ap.anchalik_parishad_name', 'pri_f_name', 'pri_m_name', 'pri_l_name', 'dob','g.gender_name', 'mobile_no','gc.gram_panchayat_name', 'd.design_name', 'pp.party_name', 'r.seat_name', 'c.caste_name')
                ->orderBy('ap.anchalik_parishad_name', 'asc')->orderBy('d.priority', 'asc')->get();

        foreach($apsArray AS $li){
            array_push($apsDownload, [
                "ap_id"=>$li->ap_id,
                "zila_parishad_name"=>$li->zila_parishad_name,
                "anchalik_parishad_name"=>$li->anchalik_parishad_name,
                "pri_name"=>$li->pri_f_name." ".$li->pri_m_name." ".$li->pri_l_name,

                "dob"=>Carbon::parse($li->dob)->format('d M Y'),
                "age"=>$dateNow->diff(Carbon::parse($li->dob))->format('%y years, %m months'),

                "gender_name"=>$li->gender_name,
                "mobile_no"=>$li->mobile_no,
                "design_name"=>$li->design_name,
                "caste_name"=>$li->caste_name,
            ]);
        }

        return view('admin.Pris.quickReportFemaleAP',compact('apsDownload'));

    }
    public function quickReportFemaleGP (Request $request)
    {
        $gpsDownload=[];

        $datePre = Carbon::parse('2019-01-01')->subYears(30);
        $dateNow = Carbon::parse('2019-01-01');

        $gpsArray = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->join('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->join('pri_master_designations AS d', 'd.id', '=', 'h.pri_master_designation_id')
                ->join('master_pri_political_parties AS pp', 'pp.id', '=', 'pri_member_main_records.party_id')
                ->join('master_genders AS g', 'g.id', '=', 'pri_member_main_records.gender_id')
                ->join('master_pris_reserve_seats AS r', 'r.id', '=', 'pri_member_main_records.seat_reserved')
                ->join('master_castes AS c', 'c.id', '=', 'pri_member_main_records.caste_id')
                ->join('master_wards AS w', 'w.id', '=', 'pri_member_main_records.ward_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['g.id', '=',2],
                    ['pri_member_main_records.dob', '>=', $datePre ]
                ])->whereIn('h.pri_master_designation_id', [5])->orderBy('ap.anchalik_parishad_name', 'asc')
                ->select('zp.zila_parishad_name', 'ap.anchalik_parishad_name', 'gp.gram_panchayat_name', 'gp.gram_panchyat_id AS gp_id', 'pri_f_name', 'pri_m_name', 'pri_l_name','dob','g.gender_name','w.ward_name', 'mobile_no','d.design_name', 'pp.party_name', 'r.seat_name', 'c.caste_name')
                ->orderBy('d.priority', 'asc')->get();

        foreach($gpsArray AS $li){
            array_push($gpsDownload, [
                "gp_id"=>$li->gp_id,
                "zila_parishad_name"=>$li->zila_parishad_name,
                "anchalik_parishad_name"=>$li->anchalik_parishad_name,
                "gram_panchayat_name"=>$li->gram_panchayat_name,
                "pri_name"=>$li->pri_f_name." ".$li->pri_m_name." ".$li->pri_l_name,
                "dob"=>Carbon::parse($li->dob)->format('d M Y'),
                "age"=>$dateNow->diff(Carbon::parse($li->dob))->format('%y years, %m months'),
                "gender_name"=>$li->gender_name,
                "mobile_no"=>$li->mobile_no,
                "design_name"=>$li->design_name,
                "caste_name"=>$li->caste_name,
            ]);
        }


        return view('admin.Pris.quickReportFemaleGP',compact('gpsDownload'));

    }
}
