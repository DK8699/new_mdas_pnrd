<?php

namespace App\Http\Controllers\Admin\Pris;

use App\CommonModels\GramPanchyat;
use App\survey\six_finance\AnchalikParishad;
use App\survey\six_finance\ZilaParishad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pris\PriMemberMainRecord;
use App\ConfigMdas;
use DB;


class AdminBankReportController extends Controller
{
    //ning start
    // --------------------bank progress report--------------------
    public function bankProgressReport()
    {

        $submittedZPs = ZilaParishad::select('id', 'zila_parishad_name')
            ->where('is_active', 1)
            ->orderBy('zila_parishad_name')
            ->get();

        $gpcount = [];
        // $zpstrength=[
        //     0b10, 030, 0xD, 0b1100, 033, 0x7, 0b1110, 007, 0x18, 0b10111, 020, 0x11, 0b1011, 014, 0x13, 0b11010, 006, 0x14, 0b10001, 004, 0xD, 0b011101, 015, 0x11, 0b10011, 005, 0x14
        // ];
        $zpstrength=[ 4,24,13,12,27,7,14,7,24,23,16,17,11,12,19,26,6,20,17,4,13,29,13,17,19,5,20];
        //dd($zpstrength);
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


            // -------------------------this is done to count and distinct for GP end---------------------------------

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
        return view('admin.Pris.bankProgressReport', compact('submittedZPs', 'gpcount', 'zpstrength', 'zpBankRecordCount', 'apBankRecordCount', 'gpBankRecordCount', 'totalZpStrength', 'totalZpBankRecordCount', 'totalapBankRecordCount', 'totalgpBankRecordCount', 'totalaps', 'totalgps','aps'));
    }
    // ---------------------------------------------------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------------------------------------

    public function bankSubDistrictAdmin($id)
    {

        $submittedZPs = ZilaParishad::select('id', 'zila_parishad_name')
            ->where('is_active', 1)
            ->where('id', $id)
            ->orderBy('zila_parishad_name')
            ->get();

       
        $apcount = AnchalikParishad::where('zila_id', $id)
            ->orderBy('anchalik_parishad_name')
            ->get();

           
        $gpcount = [];
        $apBankRecordCount = [];
        $gpBankRecordCount = [];
        $apall = [];
        $ap_id = [];


        $i = 0;
        $k = 0;
        $j = 0;
        foreach ($apcount as $val) {
            $ap_id[$j] = $val->id;
            $j++;

            $gpcount[$i] = DB::table('gram_panchyats')
                ->whereIn('anchalik_id', $ap_id)
                ->count();

            // -------------------------this is done to count and distinct for AP strength start------------------------------
            $myapStrengthquery="SELECT COUNT(DISTINCT pri_member_main_records.id) as myapStrength  FROM `pri_member_main_records` 
            -- JOIN pri_members_bank_records ON pri_members_bank_records.pri_member_main_record_id=pri_member_main_records.id
            JOIN pri_member_term_histories ON pri_member_term_histories.pri_member_main_record_id=pri_member_main_records.id
            
            WHERE `zilla_id` = '" . $id . "' 
                and anchalik_id= '" . $ap_id[$i] ."'
                and gram_panchayat_id is NULL
                and pri_member_term_histories.master_pri_term_id=4";
            $apStrength[$i]=DB::select($myapStrengthquery);

            // -------------------------this is done to count and distinct for AP strength end---------------------------------


            // -------------------------this is done to count and distinct for AP bank start---------------------------------

            $myqueryap = "SELECT COUNT(DISTINCT pri_members_bank_records.id) as mycountap FROM pri_members_bank_records 
                JOIN pri_member_main_records ON pri_members_bank_records.pri_member_main_record_id = pri_member_main_records.id
                JOIN pri_member_term_histories ON pri_members_bank_records.pri_member_main_record_id = pri_member_term_histories.pri_member_main_record_id
                WHERE pri_member_main_records.zilla_id='" . $id . "'
                AND pri_member_main_records.anchalik_id='" . $ap_id[$i] ."'
                AND pri_member_main_records.gram_panchayat_id is NULL
                AND pri_member_term_histories.master_pri_term_id = 4";
                $apBankRecordCount[$i] = DB::select($myqueryap);

            // -------------------------this is done to count and distinct for AP end---------------------------------


            // -------------------------this is done to count and distinct for GP start---------------------------------

            $myquerygp = "SELECT COUNT(DISTINCT pri_members_bank_records.id) as mycountgp FROM pri_members_bank_records 
            JOIN pri_member_main_records ON pri_members_bank_records.pri_member_main_record_id = pri_member_main_records.id
            JOIN pri_member_term_histories ON pri_members_bank_records.pri_member_main_record_id = pri_member_term_histories.pri_member_main_record_id
            WHERE pri_member_main_records.zilla_id='" . $id . "'
            AND pri_member_main_records.anchalik_id='" . $ap_id[$i] ."'
            -- AND pri_member_main_records.gram_panchayat_id is NULL
            AND pri_member_term_histories.master_pri_term_id = 4
            AND pri_member_term_histories.pri_master_designation_id in (6,5,9)" ;
            $gpBankRecordCount[$i] = DB::select($myquerygp);

            // -------------------------this is done to count and distinct for GP end---------------------------------

            $i++;
            $k++;

        }

        return view('admin.Pris.bankSubDistrictAdmin', compact('submittedZPs', 'gpcount', 'apStrength','apBankRecordCount', 'gpBankRecordCount','apcount'));
    }
    // -------------------------------------------------------------------------------------------------
    // -------------------------------------------------------------------------------------------------
    // -------------------------------------------------------------------------------------------------
    // -------------------------------------------------------------------------------------------------

    public function bankSubDistrictGPAdmin($id)
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
    
        return view('admin.Pris.bankSubDistrictGPAdmin', compact('gramid', 'gpcount', 'gpStrength', 'gpBankRecordCount'));
    }


    //ning end
}
