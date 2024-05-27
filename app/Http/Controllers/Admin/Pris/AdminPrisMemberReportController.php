<?php

namespace App\Http\Controllers\Admin\Pris;

use App\CommonModels\GramPanchyat;
use App\survey\six_finance\AnchalikParishad;
use App\survey\six_finance\ZilaParishad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CommonModels\District;
use App\Pris\PriMemberMainRecord;
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
use App\Pris\PriMemberTermHistory;
use App\survey\six_finance\SixFinanceGpSelectionList;
use DB;


class AdminPrisMemberReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin_mdas']);
    }
    public function priMenu()
    {

        return view('admin.Pris.priMenu');
    }

    public function priDistrictWiseGenderZP()
    {
        //--------------------------------------------------------------------------------------------------------------
        //================================< PRI count by Gender(Zila) Wise >==============================================
        //--------------------------------------------------------------------------------------------------------------

        ///////////////////////////////////////////////////////ZP///////////////////////////////////////////////////////////////

        $zpsG = ZilaParishad::orderBy('id')->get();
        foreach ($zpsG as $zp) {
            $apsArray = [];
            $gpsArray = [];
            //=======================================> ZP Male Count >==============================================================
            $zp_men_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.gender_id', '=', 1],
                ])->whereIn('h.pri_master_designation_id', [1]) //---------ZP PRESIDENT-----------------
                ->count();
            $zp_men_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.gender_id', '=', 1],
                ])->whereIn('h.pri_master_designation_id', [7]) //---------ZP VICE-PRESIDENT-----------------
                ->count();
            $zp_men_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.gender_id', '=', 1],
                ])->whereIn('h.pri_master_designation_id', [2]) //---------ZP MEMBER-----------------
                ->count();
            //=======================================> ZP Female Count >============================================================
            $zp_women_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.gender_id', '=', 2],
                ])->whereIn('h.pri_master_designation_id', [1]) //---------ZP PRESIDENT-----------------
                ->count();
            $zp_women_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.gender_id', '=', 2],
                ])->whereIn('h.pri_master_designation_id', [7]) //---------ZP VICE-PRESIDENT-----------------
                ->count();
            $zp_women_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.gender_id', '=', 2],
                ])->whereIn('h.pri_master_designation_id', [2]) //---------ZP MEMBER-----------------
                ->count();

            //////////////////////////////////////////////////////////////AP////////////////////////////////////////////////////////

            $apsG = AnchalikParishad::getAPsByZilaId($zp->id);

            foreach ($apsG as $aps) {
                array_push($apsArray, $aps->id);
            }
            //=======================================> AP Male Count >============================================================
            $ap_men_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS a', 'a.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.gender_id', '=', 1],
                ])->whereIn('pri_member_main_records.anchalik_id', $apsArray)
                ->whereIn('h.pri_master_designation_id', [3]) //---------AP PRESIDENT-----------------
                ->count();
            $ap_men_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS a', 'a.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.gender_id', '=', 1],
                ])->whereIn('pri_member_main_records.anchalik_id', $apsArray)
                ->whereIn('h.pri_master_designation_id', [8]) //---------AP VICE-PRESIDENT-----------------
                ->count();
            $ap_men_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS a', 'a.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.gender_id', '=', 1],
                ])->whereIn('pri_member_main_records.anchalik_id', $apsArray)
                ->whereIn('h.pri_master_designation_id', [4]) //---------AP MEMBER-----------------
                ->count();
            //=======================================> AP Female Count >============================================================
            $ap_women_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS a', 'a.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.gender_id', '=', 2],
                ])->whereIn('pri_member_main_records.anchalik_id', $apsArray)
                ->whereIn('h.pri_master_designation_id', [3]) //---------AP PRESIDENT-----------------
                ->count();
            $ap_women_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS a', 'a.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.gender_id', '=', 2],
                ])->whereIn('pri_member_main_records.anchalik_id', $apsArray)
                ->whereIn('h.pri_master_designation_id', [8]) //---------VICE-PRESIDENT-----------------
                ->count();
            $ap_women_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS a', 'a.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.gender_id', '=', 2],
                ])->whereIn('pri_member_main_records.anchalik_id', $apsArray)
                ->whereIn('h.pri_master_designation_id', [4]) //---------AP MEMBER-----------------
                ->count();
            ///////////////////////////////////////////////////////////////GP///////////////////////////////////////////////////////
            $gpsG = GramPanchyat::whereIn('anchalik_id', $apsArray)->get();

            foreach ($gpsG as $gps) {
                array_push($gpsArray, $gps->gram_panchyat_id);
            }
            //=======================================> GP Male Count >============================================================
            $gp_men_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.gender_id', '=', 1],
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsArray)
                ->whereIn('h.pri_master_designation_id', [5]) //---------GP PRESIDENT-----------------
                ->count();
            $gp_men_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.gender_id', '=', 1],
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsArray)
                ->whereIn('h.pri_master_designation_id', [9]) //---------GP VICE-PRESIDENT-----------------
                ->count();
            $gp_men_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.gender_id', '=', 1],
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsArray)
                ->whereIn('h.pri_master_designation_id', [6]) //---------GP MEMBER-----------------
                ->count();
            //=======================================> GP Male Count >============================================================
            $gp_women_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.gender_id', '=', 2],
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsArray)
                ->whereIn('h.pri_master_designation_id', [5]) //---------GP PRESIDENT-----------------
                ->count();
            $gp_women_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.gender_id', '=', 2],
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsArray)
                ->whereIn('h.pri_master_designation_id', [9]) //---------GP VICE-PRESIDENT-----------------
                ->count();
            $gp_women_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.gender_id', '=', 2],
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsArray)
                ->whereIn('h.pri_master_designation_id', [6]) //---------GP MEMBER-----------------
                ->count();
            //-------------------------------ZP Male-------------------------------
            $finalGenderArr["ZP_zp_M_id_P" . $zp->id] = $zp_men_count_P;
            $finalGenderArr["ZP_zp_M_id_V" . $zp->id] = $zp_men_count_V;
            $finalGenderArr["ZP_zp_M_id_M" . $zp->id] = $zp_men_count_M;
            //-------------------------------ZP Female------------------------------
            $finalGenderArr["ZP_zp_W_id_P" . $zp->id] = $zp_women_count_P;
            $finalGenderArr["ZP_zp_W_id_V" . $zp->id] = $zp_women_count_V;
            $finalGenderArr["ZP_zp_W_id_M" . $zp->id] = $zp_women_count_M;
            //-------------------------------AP Male--------------------------------
            $finalGenderArr["AP_zp_M_id_P" . $zp->id] = $ap_men_count_P;
            $finalGenderArr["AP_zp_M_id_V" . $zp->id] = $ap_men_count_V;
            $finalGenderArr["AP_zp_M_id_M" . $zp->id] = $ap_men_count_M;
            //-------------------------------AP Female------------------------------
            $finalGenderArr["AP_zp_W_id_P" . $zp->id] = $ap_women_count_P;
            $finalGenderArr["AP_zp_W_id_V" . $zp->id] = $ap_women_count_V;
            $finalGenderArr["AP_zp_W_id_M" . $zp->id] = $ap_women_count_M;
            //-------------------------------GP Male---------------------------------
            $finalGenderArr["GP_zp_M_id_P" . $zp->id] = $gp_men_count_P;
            $finalGenderArr["GP_zp_M_id_V" . $zp->id] = $gp_men_count_V;
            $finalGenderArr["GP_zp_M_id_M" . $zp->id] = $gp_men_count_M;
            //-------------------------------GP Female-------------------------------
            $finalGenderArr["GP_zp_W_id_P" . $zp->id] = $gp_women_count_P;
            $finalGenderArr["GP_zp_W_id_V" . $zp->id] = $gp_women_count_V;
            $finalGenderArr["GP_zp_W_id_M" . $zp->id] = $gp_women_count_M;
        }

        return view(
            'admin.Pris.priDistrictWiseGenderZP',
            compact('zpsG', 'finalGenderArr')
        );
    }
    public function priDistrictWiseQualiReport(Request $request)
    {
        $qualifications = MasterHighestQualification::all();

        return view(
            'admin.Pris.priDistrictWiseQualiReport',
            compact('qualifications')
        );
    }
    public function reportHQualByHQualList(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Failed to Request Process.";

        $results = [];
        $i = 1;
        $sum = 0;

        $hQList = [];

        try {

            $q_ids = $request->input('h_q_select');

            $qList = MasterHighestQualification::whereIn('id', $q_ids)->select('qual_name')->get();

            foreach ($qList as $qual) {
                array_push($hQList, $qual->qual_name);
            }

            $zpsQ = ZilaParishad::orderBy('id')->get();

            foreach ($zpsQ as $zp) {
                $apsArray = [];
                $gpsArray = [];
                $zp_Q_count = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                    ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                    ->where([
                        ['h.master_pri_term_id', '=', 4],
                        ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ])->whereIn('h.pri_master_designation_id', [1, 2, 7]) //---------ZP PRESIDENT, ZP VICE-PRESIDENT AND ZP MEMBER-----------------
                    ->whereIn('pri_member_main_records.qual_id', $q_ids)
                    ->count();

                $apsQ = AnchalikParishad::getAPsByZilaId($zp->id);

                foreach ($apsQ as $aps) {
                    array_push($apsArray, $aps->id);
                }

                $ap_Q_count = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                    ->join('anchalik_parishads AS a', 'a.id', '=', 'pri_member_main_records.anchalik_id')
                    ->where([
                        ['h.master_pri_term_id', '=', 4],
                    ])->whereIn('pri_member_main_records.anchalik_id', $apsArray)
                    ->whereIn('h.pri_master_designation_id', [3, 4, 8]) //---------AP PRESIDENT, AP VICE-PRESIDENT AND AP MEMBER-----------------
                    ->whereIn('pri_member_main_records.qual_id', $q_ids)
                    ->count();

                $gpsQ = GramPanchyat::whereIn('anchalik_id', $apsArray)->get();

                foreach ($gpsQ as $gps) {
                    array_push($gpsArray, $gps->gram_panchyat_id);
                }

                $gp_Q_count = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                    ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                    ->where([
                        ['h.master_pri_term_id', '=', 4],
                    ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsArray)
                    ->whereIn('h.pri_master_designation_id', [5, 6, 8]) //---------GP PRESIDENT, GP VICE-PRESIDENT AND GP MEMBER-----------------
                    ->whereIn('pri_member_main_records.qual_id', $q_ids)
                    ->count();

                array_push(
                    $results,
                    array(
                        $i,
                        $zp->zila_parishad_name,
                        $zp_Q_count,
                        $ap_Q_count,
                        $gp_Q_count,
                        $zp_Q_count + $ap_Q_count + $gp_Q_count
                    )
                );

                $sum = $sum + $zp_Q_count + $ap_Q_count + $gp_Q_count;

                $i++;
            }

        } catch (\Exception $e) {
            $returnData['msg'] = "Server Exception.";
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = ["results" => $results, 'hQList' => $hQList, 'hQSum' => $sum];
        $returnData['msg'] = "Success";
        return response()->json($returnData);
    }
    public function priDistrictWisePartyZP()
    {
        //--------------------------------------------------------------------------------------------------------------
        //================================< PRI count by Political(Zila) Wise >==============================================
        //--------------------------------------------------------------------------------------------------------------

        ///////////////////////////////////////////////////////ZP///////////////////////////////////////////////////////////////

        $zpsParty = ZilaParishad::orderBy('id')->get();
        foreach ($zpsParty as $zp) {
            $apsPartyArray = [];
            $gpsPartyArray = [];

            //=======================================> ZP AGP Count >==============================================================
            $zp_AGP_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.party_id', '=', 1], //---------AGP-------------------------
                ])->whereIn('h.pri_master_designation_id', [1]) //---------ZP PRESIDENT-----------------
                ->count();
            $zp_AGP_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.party_id', '=', 1], //---------AGP-------------------------
                ])->whereIn('h.pri_master_designation_id', [7]) //---------ZP VICE-PRESIDENT-----------------
                ->count();
            $zp_AGP_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.party_id', '=', 1], //---------AGP-------------------------
                ])->whereIn('h.pri_master_designation_id', [2]) //---------ZP MEMBER-----------------
                ->count();
            //=======================================> ZP BJP Count >==============================================================
            $zp_BJP_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.party_id', '=', 2], //---------BJP-------------------------
                ])->whereIn('h.pri_master_designation_id', [1]) //---------ZP PRESIDENT-----------------
                ->count();
            $zp_BJP_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.party_id', '=', 2], //---------BJP-------------------------
                ])->whereIn('h.pri_master_designation_id', [7]) //---------ZP VICE-PRESIDENT-----------------
                ->count();
            $zp_BJP_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.party_id', '=', 2], //---------BJP-------------------------
                ])->whereIn('h.pri_master_designation_id', [2]) //---------ZP MEMBER-----------------
                ->count();

            //=======================================> ZP CONGRESS Count >==============================================================
            $zp_CON_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.party_id', '=', 3], //---------Congress-------------------------
                ])->whereIn('h.pri_master_designation_id', [1]) //---------ZP PRESIDENT-----------------
                ->count();
            $zp_CON_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.party_id', '=', 3], //---------Congress-------------------------
                ])->whereIn('h.pri_master_designation_id', [7]) //---------ZP VICE-PRESIDENT-----------------
                ->count();
            $zp_CON_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.party_id', '=', 3], //---------AUIDF-------------------------
                ])->whereIn('h.pri_master_designation_id', [2]) //---------ZP MEMBER-----------------
                ->count();

            //=======================================> ZP AUIDF Count >==============================================================
            $zp_AUIDF_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.party_id', '=', 4], //---------AUIDF-------------------------
                ])->whereIn('h.pri_master_designation_id', [1]) //---------ZP PRESIDENT-----------------
                ->count();
            $zp_AUIDF_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.party_id', '=', 4], //---------AUIDF-------------------------
                ])->whereIn('h.pri_master_designation_id', [7]) //---------ZP VICE-PRESIDENT-----------------
                ->count();
            $zp_AUIDF_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.party_id', '=', 4], //---------AUIDF-------------------------
                ])->whereIn('h.pri_master_designation_id', [2]) //---------ZP MEMBER-----------------
                ->count();

            //=======================================> ZP Independent Count >==============================================================
            $zp_IND_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.party_id', '=', 5], //---------Independent-------------------------
                ])->whereIn('h.pri_master_designation_id', [1]) //---------ZP PRESIDENT-----------------
                ->count();
            $zp_IND_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.party_id', '=', 5], //---------Independent-------------------------
                ])->whereIn('h.pri_master_designation_id', [7]) //---------ZP VICE-PRESIDENT-----------------
                ->count();
            $zp_IND_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.party_id', '=', 5], //---------Independent-------------------------
                ])->whereIn('h.pri_master_designation_id', [2]) //---------ZP MEMBER-----------------
                ->count();

            //=======================================> ZP Other Party Count >==============================================================
            $zp_OTH_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.party_id', '=', 6], //---------Other Party-------------------------
                ])->whereIn('h.pri_master_designation_id', [1]) //---------ZP PRESIDENT-----------------
                ->count();
            $zp_OTH_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.party_id', '=', 6], //---------Other Party-------------------------
                ])->whereIn('h.pri_master_designation_id', [7]) //---------ZP VICE-PRESIDENT-----------------
                ->count();
            $zp_OTH_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.zilla_id', '=', $zp->id],
                    ['pri_member_main_records.party_id', '=', 6], //---------Other Party-------------------------
                ])->whereIn('h.pri_master_designation_id', [2]) //---------ZP MEMBER-----------------
                ->count();

            //-------------------------------ZP AGP------------------------------------
            $finalPartyArr["ZP__AGP_id_P" . $zp->id] = $zp_AGP_count_P;
            $finalPartyArr["ZP__AGP_id_V" . $zp->id] = $zp_AGP_count_V;
            $finalPartyArr["ZP__AGP_id_M" . $zp->id] = $zp_AGP_count_M;
            //-------------------------------ZP BJP------------------------------------
            $finalPartyArr["ZP__BJP_id_P" . $zp->id] = $zp_BJP_count_P;
            $finalPartyArr["ZP__BJP_id_V" . $zp->id] = $zp_BJP_count_V;
            $finalPartyArr["ZP__BJP_id_M" . $zp->id] = $zp_BJP_count_M;
            //-------------------------------ZP Congress-------------------------------
            $finalPartyArr["ZP__CON_id_P" . $zp->id] = $zp_CON_count_P;
            $finalPartyArr["ZP__CON_id_V" . $zp->id] = $zp_CON_count_V;
            $finalPartyArr["ZP__CON_id_M" . $zp->id] = $zp_CON_count_M;
            //-------------------------------ZP AUIDF ---------------------------------
            $finalPartyArr["ZP__AUIDF_id_P" . $zp->id] = $zp_AUIDF_count_P;
            $finalPartyArr["ZP__AUIDF_id_V" . $zp->id] = $zp_AUIDF_count_V;
            $finalPartyArr["ZP__AUIDF_id_M" . $zp->id] = $zp_AUIDF_count_M;
            //-------------------------------ZP Independent----------------------------
            $finalPartyArr["ZP__IND_id_P" . $zp->id] = $zp_IND_count_P;
            $finalPartyArr["ZP__IND_id_V" . $zp->id] = $zp_IND_count_V;
            $finalPartyArr["ZP__IND_id_M" . $zp->id] = $zp_IND_count_M;
            //-------------------------------ZP Other Party----------------------------
            $finalPartyArr["ZP__OTH_id_P" . $zp->id] = $zp_OTH_count_P;
            $finalPartyArr["ZP__OTH_id_V" . $zp->id] = $zp_OTH_count_V;
            $finalPartyArr["ZP__OTH_id_M" . $zp->id] = $zp_OTH_count_M;


            ///////////////////////////////////////////////////////AP///////////////////////////////////////////////////////////////

            $apsParty = AnchalikParishad::getAPsByZilaId($zp->id);
            foreach ($apsParty as $ap) {
                array_push($apsPartyArray, $ap->id);
            }
            //=======================================> AP AGP Count >==============================================================
            $ap_AGP_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 3],
                    //--------- AP PRESIDENT-----------------
                    ['pri_member_main_records.party_id', '=', 1], //---------AGP-------------------------
                ])->whereIn('pri_member_main_records.anchalik_id', $apsPartyArray)
                ->count();
            $ap_AGP_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 8],
                    ['pri_member_main_records.party_id', '=', 1], //---------AGP-------------------------
                ])->whereIn('pri_member_main_records.anchalik_id', $apsPartyArray) //---------AP VICE-PRESIDENT-----------------
                ->count();
            $ap_AGP_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 4],
                    ['pri_member_main_records.party_id', '=', 1], //---------AGP-------------------------
                ])->whereIn('pri_member_main_records.anchalik_id', $apsPartyArray) //---------AP MEMBER-----------------
                ->count();
            //=======================================> AP BJP Count >==============================================================
            $ap_BJP_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 3],
                    ['pri_member_main_records.party_id', '=', 2], //---------BJP-------------------------
                ])->whereIn('pri_member_main_records.anchalik_id', $apsPartyArray) //---------AP PRESIDENT-----------------
                ->count();
            $ap_BJP_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 8],
                    ['pri_member_main_records.party_id', '=', 2], //---------BJP-------------------------
                ])->whereIn('pri_member_main_records.anchalik_id', $apsPartyArray) //---------AP VICE-PRESIDENT-----------------
                ->count();
            $ap_BJP_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 4],
                    ['pri_member_main_records.party_id', '=', 2], //---------BJP-------------------------
                ])->whereIn('pri_member_main_records.anchalik_id', $apsPartyArray) //---------AP MEMBER-----------------
                ->count();

            //=======================================> AP CONGRESS Count >==============================================================
            $ap_CON_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 3],
                    ['pri_member_main_records.party_id', '=', 3], //---------Congress-------------------------
                ])->whereIn('pri_member_main_records.anchalik_id', $apsPartyArray) //---------AP PRESIDENT-----------------
                ->count();
            $ap_CON_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 8],
                    ['pri_member_main_records.party_id', '=', 3], //---------Congress-------------------------
                ])->whereIn('pri_member_main_records.anchalik_id', $apsPartyArray) //---------AP VICE-PRESIDENT-----------------
                ->count();
            $ap_CON_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 4],
                    ['pri_member_main_records.party_id', '=', 3], //---------AUIDF-------------------------
                ])->whereIn('pri_member_main_records.anchalik_id', $apsPartyArray) //---------AP MEMBER-----------------
                ->count();

            //=======================================> AP AUIDF Count >==============================================================
            $ap_AUIDF_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 3],
                    ['pri_member_main_records.party_id', '=', 4], //---------AUIDF-------------------------
                ])->whereIn('pri_member_main_records.anchalik_id', $apsPartyArray) //---------AP PRESIDENT-----------------
                ->count();
            $ap_AUIDF_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 8],
                    ['pri_member_main_records.party_id', '=', 4], //---------AUIDF-------------------------
                ])->whereIn('pri_member_main_records.anchalik_id', $apsPartyArray) //---------AP VICE-PRESIDENT-----------------
                ->count();
            $ap_AUIDF_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 4],
                    ['pri_member_main_records.party_id', '=', 4], //---------AUIDF-------------------------
                ])->whereIn('pri_member_main_records.anchalik_id', $apsPartyArray) //---------AP MEMBER-----------------
                ->count();

            //=======================================> AP Independent Count >==============================================================
            $ap_IND_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 3],
                    ['pri_member_main_records.party_id', '=', 5], //---------Independent-------------------------
                ])->whereIn('pri_member_main_records.anchalik_id', $apsPartyArray) //---------AP PRESIDENT-----------------
                ->count();
            $ap_IND_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 8],
                    ['pri_member_main_records.party_id', '=', 5], //---------Independent-------------------------
                ])->whereIn('pri_member_main_records.anchalik_id', $apsPartyArray) //---------AP VICE-PRESIDENT-----------------
                ->count();
            $ap_IND_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 4],
                    ['pri_member_main_records.party_id', '=', 5], //---------Independent-------------------------
                ])->whereIn('pri_member_main_records.anchalik_id', $apsPartyArray) //---------AP MEMBER-----------------
                ->count();

            //=======================================> AP Other Party Count >==============================================================
            $ap_OTH_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 3],
                    ['pri_member_main_records.party_id', '=', 6], //---------Other Party-------------------------
                ])->whereIn('pri_member_main_records.anchalik_id', $apsPartyArray) //---------AP PRESIDENT-----------------
                ->count();
            $ap_OTH_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 8],
                    ['pri_member_main_records.party_id', '=', 6], //---------Other Party-------------------------
                ])->whereIn('pri_member_main_records.anchalik_id', $apsPartyArray) //---------AP VICE-PRESIDENT-----------------
                ->count();
            $ap_OTH_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 4],
                    ['pri_member_main_records.party_id', '=', 6], //---------Other Party-------------------------
                ])->whereIn('pri_member_main_records.anchalik_id', $apsPartyArray) //---------AP MEMBER-----------------
                ->count();

            //-------------------------------AP AGP------------------------------------
            $finalPartyArr["AP__AGP_id_P" . $zp->id] = $ap_AGP_count_P;
            $finalPartyArr["AP__AGP_id_V" . $zp->id] = $ap_AGP_count_V;
            $finalPartyArr["AP__AGP_id_M" . $zp->id] = $ap_AGP_count_M;
            //-------------------------------AP BJP------------------------------------
            $finalPartyArr["AP__BJP_id_P" . $zp->id] = $ap_BJP_count_P;
            $finalPartyArr["AP__BJP_id_V" . $zp->id] = $ap_BJP_count_V;
            $finalPartyArr["AP__BJP_id_M" . $zp->id] = $ap_BJP_count_M;
            //-------------------------------AP Congress-------------------------------
            $finalPartyArr["AP__CON_id_P" . $zp->id] = $ap_CON_count_P;
            $finalPartyArr["AP__CON_id_V" . $zp->id] = $ap_CON_count_V;
            $finalPartyArr["AP__CON_id_M" . $zp->id] = $ap_CON_count_M;
            //-------------------------------AP AUIDF ---------------------------------
            $finalPartyArr["AP__AUIDF_id_P" . $zp->id] = $ap_AUIDF_count_P;
            $finalPartyArr["AP__AUIDF_id_V" . $zp->id] = $ap_AUIDF_count_V;
            $finalPartyArr["AP__AUIDF_id_M" . $zp->id] = $ap_AUIDF_count_M;
            //-------------------------------AP Independent----------------------------
            $finalPartyArr["AP__IND_id_P" . $zp->id] = $ap_IND_count_P;
            $finalPartyArr["AP__IND_id_V" . $zp->id] = $ap_IND_count_V;
            $finalPartyArr["AP__IND_id_M" . $zp->id] = $ap_IND_count_M;
            //-------------------------------AP Other Party----------------------------
            $finalPartyArr["AP__OTH_id_P" . $zp->id] = $ap_OTH_count_P;
            $finalPartyArr["AP__OTH_id_V" . $zp->id] = $ap_OTH_count_V;
            $finalPartyArr["AP__OTH_id_M" . $zp->id] = $ap_OTH_count_M;


            ///////////////////////////////////////////////////////GP///////////////////////////////////////////////////////////////
            $gpsParty = GramPanchyat::whereIn('anchalik_id', $apsPartyArray)->get();

            foreach ($gpsParty as $gps) {
                array_push($gpsPartyArray, $gps->gram_panchyat_id);
            }
            //=======================================> GP AGP Count >==============================================================
            $gp_AGP_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 5],
                    //--------- GP PRESIDENT-----------------
                    ['pri_member_main_records.party_id', '=', 1], //---------AGP-------------------------
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsPartyArray)
                ->count();
            $gp_AGP_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 9],
                    //--------- GP VICE PRESIDENT-----------------
                    ['pri_member_main_records.party_id', '=', 1], //---------AGP-------------------------
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsPartyArray) //---------AP VICE-PRESIDENT-----------------
                ->count();
            $gp_AGP_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 6],
                    //--------- GP MEMBER-----------------
                    ['pri_member_main_records.party_id', '=', 1], //---------AGP-------------------------
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsPartyArray) //---------AP MEMBER-----------------
                ->count();
            //=======================================> GP BJP Count >==============================================================
            $gp_BJP_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 5],
                    //--------- GP PRESIDENT-----------------
                    ['pri_member_main_records.party_id', '=', 2], //---------BJP-------------------------
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsPartyArray) //---------AP PRESIDENT-----------------
                ->count();
            $gp_BJP_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 9],
                    //--------- GP VICE PRESIDENT-----------------
                    ['pri_member_main_records.party_id', '=', 2], //---------BJP-------------------------
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsPartyArray) //---------AP VICE-PRESIDENT-----------------
                ->count();
            $gp_BJP_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 6],
                    //--------- GP MEMBER-----------------
                    ['pri_member_main_records.party_id', '=', 2], //---------BJP-------------------------
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsPartyArray) //---------AP MEMBER-----------------
                ->count();

            //=======================================> GP CONGRESS Count >==============================================================
            $gp_CON_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 5],
                    //--------- GP PRESIDENT-----------------
                    ['pri_member_main_records.party_id', '=', 3], //---------Congress-------------------------
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsPartyArray) //---------AP PRESIDENT-----------------
                ->count();
            $gp_CON_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 9],
                    //--------- GP VICE PRESIDENT-----------------
                    ['pri_member_main_records.party_id', '=', 3], //---------Congress-------------------------
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsPartyArray) //---------AP VICE-PRESIDENT-----------------
                ->count();
            $gp_CON_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 6],
                    //--------- GP MEMBER-----------------
                    ['pri_member_main_records.party_id', '=', 3], //---------AUIDF-------------------------
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsPartyArray) //---------AP MEMBER-----------------
                ->count();

            //=======================================> GP AUIDF Count >==============================================================
            $gp_AUIDF_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 5],
                    //--------- GP PRESIDENT-----------------
                    ['pri_member_main_records.party_id', '=', 4], //---------AUIDF-------------------------
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsPartyArray) //---------AP PRESIDENT-----------------
                ->count();
            $gp_AUIDF_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 9],
                    //--------- GP VICE PRESIDENT-----------------
                    ['pri_member_main_records.party_id', '=', 4], //---------AUIDF-------------------------
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsPartyArray) //---------AP VICE-PRESIDENT-----------------
                ->count();
            $gp_AUIDF_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 6],
                    //--------- GP MEMBER-----------------
                    ['pri_member_main_records.party_id', '=', 4], //---------AUIDF-------------------------
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsPartyArray) //---------AP MEMBER-----------------
                ->count();

            //=======================================> GP Independent Count >==============================================================
            $gp_IND_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 5],
                    //--------- GP PRESIDENT-----------------
                    ['pri_member_main_records.party_id', '=', 5], //---------Independent-------------------------
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsPartyArray) //---------AP PRESIDENT-----------------
                ->count();
            $gp_IND_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 9],
                    //--------- GP VICE PRESIDENT-----------------
                    ['pri_member_main_records.party_id', '=', 5], //---------Independent-------------------------
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsPartyArray) //---------AP VICE-PRESIDENT-----------------
                ->count();
            $gp_IND_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 6],
                    //--------- GP MEMBER-----------------
                    ['pri_member_main_records.party_id', '=', 5], //---------Independent-------------------------
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsPartyArray) //---------AP MEMBER-----------------
                ->count();

            //=======================================> GP Other Party Count >==============================================================
            $gp_OTH_count_P = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 5],
                    //--------- GP PRESIDENT-----------------
                    ['pri_member_main_records.party_id', '=', 6], //---------Other Party-------------------------
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsPartyArray) //---------AP PRESIDENT-----------------
                ->count();
            $gp_OTH_count_V = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 9],
                    //--------- GP VICE PRESIDENT-----------------
                    ['pri_member_main_records.party_id', '=', 6], //---------Other Party-------------------------
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsPartyArray) //---------AP VICE-PRESIDENT-----------------
                ->count();
            $gp_OTH_count_M = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['h.pri_master_designation_id', '=', 6],
                    //--------- GP MEMBER-----------------
                    ['pri_member_main_records.party_id', '=', 6], //---------Other Party-------------------------
                ])->whereIn('pri_member_main_records.gram_panchayat_id', $gpsPartyArray) //---------AP MEMBER-----------------
                ->count();

            //-------------------------------GP AGP------------------------------------
            $finalPartyArr["GP__AGP_id_P" . $zp->id] = $gp_AGP_count_P;
            $finalPartyArr["GP__AGP_id_V" . $zp->id] = $gp_AGP_count_V;
            $finalPartyArr["GP__AGP_id_M" . $zp->id] = $gp_AGP_count_M;
            //-------------------------------GP BJP------------------------------------
            $finalPartyArr["GP__BJP_id_P" . $zp->id] = $gp_BJP_count_P;
            $finalPartyArr["GP__BJP_id_V" . $zp->id] = $gp_BJP_count_V;
            $finalPartyArr["GP__BJP_id_M" . $zp->id] = $gp_BJP_count_M;
            //-------------------------------GP Congress-------------------------------
            $finalPartyArr["GP__CON_id_P" . $zp->id] = $gp_CON_count_P;
            $finalPartyArr["GP__CON_id_V" . $zp->id] = $gp_CON_count_V;
            $finalPartyArr["GP__CON_id_M" . $zp->id] = $gp_CON_count_M;
            //-------------------------------GP AUIDF ---------------------------------
            $finalPartyArr["GP__AUIDF_id_P" . $zp->id] = $gp_AUIDF_count_P;
            $finalPartyArr["GP__AUIDF_id_V" . $zp->id] = $gp_AUIDF_count_V;
            $finalPartyArr["GP__AUIDF_id_M" . $zp->id] = $gp_AUIDF_count_M;
            //-------------------------------GP Independent----------------------------
            $finalPartyArr["GP__IND_id_P" . $zp->id] = $gp_IND_count_P;
            $finalPartyArr["GP__IND_id_V" . $zp->id] = $gp_IND_count_V;
            $finalPartyArr["GP__IND_id_M" . $zp->id] = $gp_IND_count_M;
            //-------------------------------GP Other Party----------------------------
            $finalPartyArr["GP__OTH_id_P" . $zp->id] = $gp_OTH_count_P;
            $finalPartyArr["GP__OTH_id_V" . $zp->id] = $gp_OTH_count_V;
            $finalPartyArr["GP__OTH_id_M" . $zp->id] = $gp_OTH_count_M;
        }

        return view(
            'admin.Pris.priDistrictWisePartyZP',
            compact('zpsParty', 'finalPartyArr')
        );
    }

    public function reportAdmin(Request $request)
    {
        $zilas = ZilaParishad::all();
        $priMembers = [];
        $searchResult = false;
        $imgUrl = ConfigMdas::allActiveList()->imgUrl;


        //---------- POST METHOD ACTIVATED -------------------------------------------------------------------------------------

        if ($request->isMethod('post')) {


            //--------------GET PRI MEMBERS ACCORDING TO ZP, AP, GP WISE------------------------------------------------------------

            $priMembers = $this->getMembersByTier($request);

            $tier = $request->input('tier');
            $zp_id = $request->input('zp_id');
            //------------------------If choose ZP----------------------------------------------------------------------------------
            if ($tier == "ZP") {
                $filterArray = ["filterTier" => $tier, "filterZP" => $zp_id];
            } //------------------------If choose AP----------------------------------------------------------------------------------
            else if ($tier == "AP") {
                $ap_id = $request->input('ap_id');
                $filterArray = ["filterTier" => $tier, "filterZP" => $zp_id, "filterAP" => $ap_id];
            } //------------------------If choose GP----------------------------------------------------------------------------------
            else if ($tier == "GP") {
                $ap_id = $request->input('ap_id');
                $gp_id = $request->input('gp_id');

                $aps = AnchalikParishad::where([
                    ['zila_id', '=', $zp_id]
                ])->select('id', 'anchalik_parishad_name')->get();

                $gps = GramPanchyat::where([
                    ['anchalik_id', '=', $ap_id]
                ])->select('gram_panchyat_id AS id', 'gram_panchayat_name')->get();

                $filterArray = [
                    "filterTier" => $tier,
                    "filterZP" => $zp_id,
                    "filterAP" => $ap_id,
                    "filterGP" => $gp_id,
                    "filterAPList" => $aps,
                    "filterGPList" => $gps
                ];
            }


            //-------------------------Return View for Post-------------------------------------------------------------------------

            return view('admin.Pris.reportAdmin', compact('zilas', 'priMembers', 'imgUrl', 'filterArray', 'searchResult'));

        } //-------------------------Return View for Get-------------------------------------------------------------------------
        else {
            return view(
                'admin.Pris.reportAdmin',
                compact(
                    'zilas',
                    'priMembers',
                    'imgUrl',
                    'searchResult'
                )
            );
        }
    }

    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //--------------GET PRI MEMBERS ACCORDING TO ZP, AP, GP WISE--------------------------------------------------------
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    private function getMembersByTier($request)
    {

        $tier = $request->input('tier');

        $priMembers = [];

        if ($tier == "ZP") {

            $zp_id = $request->input('zp_id');
            $designArray = [1, 2, 7];
            $whereArray = [
                ['h.master_pri_term_id', '=', 4, 'OR', 5],
                ['pri_member_main_records.zilla_id', '=', $zp_id],

            ];
            $priMembers = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->join('pri_master_designations AS d', 'd.id', '=', 'h.pri_master_designation_id')
                ->where($whereArray)
                ->whereIn('h.pri_master_designation_id', $designArray)
                ->select('pri_member_main_records.id', 'pri_code', 'pri_f_name', 'pri_m_name', 'pri_l_name', 'd.design_name', 'pri_pic', 'd.color_code')
                ->get();

        } else if ($tier == "AP") {

            $ap_id = $request->input('ap_id');
            $designArray = [3, 4, 8];
            $whereArray = [
                ['h.master_pri_term_id', '=', 4],
                ['pri_member_main_records.anchalik_id', '=', $ap_id],

            ];
            $priMembers = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
                ->join('pri_master_designations AS d', 'd.id', '=', 'h.pri_master_designation_id')
                ->where($whereArray)
                ->whereIn('h.pri_master_designation_id', $designArray)
                ->select('pri_member_main_records.id', 'pri_code', 'pri_f_name', 'pri_m_name', 'pri_l_name', 'd.design_name', 'pri_pic', 'd.color_code')
                ->get();

        } else if ($tier == "GP") {

            $gp_id = $request->input('gp_id');
            $designArray = [5, 6, 9];
            $whereArray = [
                ['h.master_pri_term_id', '=', 4],
                ['pri_member_main_records.gram_panchayat_id', '=', $gp_id],

            ];
            $priMembers = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->join('pri_master_designations AS d', 'd.id', '=', 'h.pri_master_designation_id')
                ->where($whereArray)
                ->whereIn('h.pri_master_designation_id', $designArray)
                ->select('pri_member_main_records.id', 'pri_code', 'pri_f_name', 'pri_m_name', 'pri_l_name', 'd.design_name', 'pri_pic', 'd.color_code')
                ->get();

        }


        return $priMembers;
    }

    public function selectZilaAjax(Request $request)
    {
        $returnData['msgType'] = false;
        $retrunData['data'] = [];
        $returnData['msg'] = "Failed to Request Process.";
        try {
            $resultZP = ZilaParishad::getZPsByDistrictId($request->input('district_id'));
            if (!count($resultZP) > 0) {
                $returnData['msg'] = "No Data Found";
                return response()->json($returnData);
            }
        } catch (\Exception $e) {
            $returnData['msg'] = "Server Exception." . $e->getMessage();
            return response()->json($returnData);
        }
        $returnData['msgType'] = true;
        $returnData['data'] = $resultZP;
        $returnData['msg'] = "Success";
        return response()->json($returnData);

    }

    public function selectAnchalAjax(Request $request)
    {
        $returnData['msgType'] = false;
        $retrunData['data'] = [];
        $returnData['msg'] = "Failed to Request Process.";
        try {
            $resultAP = AnchalikParishad::getAPsByZilaId($request->input('zila_id'));
            if (!count($resultAP) > 0) {
                $returnData['msg'] = "No Data Found";
                return response()->json($returnData);
            }
        } catch (\Exception $e) {
            $returnData['msg'] = "Server Exception." . $e->getMessage();
            return response()->json($returnData);
        }
        $returnData['msgType'] = true;
        $returnData['data'] = $resultAP;
        $returnData['msg'] = "Success";
        return response()->json($returnData);

    }

    public function selectGramAjax(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Failed To Process Request.";

        try {
            $resultGP = GramPanchyat::getGpsByAnchalikId($request->input('anchalik_id'));

            if (!count($resultGP) > 0) {
                $returnData['msg'] = "No Data Found.";
                return response()->json($returnData);
            }

        } catch (\Exception $e) {
            $returnData['msg'] = "Server Exception." . $e->getMessage();
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = $resultGP;
        $returnData['msg'] = "Success.";
        return response()->json($returnData);
    }

    //------------------------------------------------------------------------------------------------------------------
    //---------------------------- DISTICT WISE PROGRESS ---------------------------------------------------------------
    //------------------------------------------------------------------------------------------------------------------

    public function priDistrictWiseProgressReportZP()
    {
        $finalGivenNos = [];

        $submittedZPs = ZilaParishad::join('pri_member_main_records AS pri', 'zila_parishads.id', '=', 'pri.zilla_id')
            ->leftjoin('anchalik_parishads AS ap', 'ap.id', '=', 'pri.anchalik_id')
            ->leftjoin('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'pri.gram_panchayat_id')
            ->join('pri_member_term_histories AS h', 'pri.id', '=', 'h.pri_member_main_record_id')
            ->join('pri_master_designations AS deg', 'deg.id', '=', 'h.pri_master_designation_id')
            ->where([
                ['h.master_pri_term_id', '=', 4],
            ])->select(DB::raw('zila_parishads.id, zila_parishads.zila_parishad_name, COUNT(*) AS total'))
            ->groupBy('zila_parishads.zila_parishad_name', 'zila_parishads.id')
            ->get();

        $zpArray = [];

        foreach ($submittedZPs as $zp) {
            array_push($zpArray, $zp->id);
        }

        $givenNos = DB::table('pri_given_records')->get();

        foreach ($givenNos as $given) {
            $finalGivenNos[$given->zp_id] = [
                "no_of_aps" => $given->no_of_aps,
                "no_of_gps" => $given->no_of_gps,
                "tot_zp_count" => $given->tot_zp_count,
                "tot_ap_count" => $given->tot_ap_count,
                "tot_gp_count" => $given->tot_gp_count,
                "grand_tot" => $given->grand_tot
            ];
        }

        $notSubmittedZPs = ZilaParishad::whereNotIn('id', $zpArray)
            ->select('zila_parishads.id', 'zila_parishads.zila_parishad_name')
            ->get();



        return view(
            'admin.Pris.priDistrictWiseProgressReportZP',
            compact('submittedZPs', 'notSubmittedZPs', 'finalGivenNos')
        );
    }

    public function reportProgress(Request $request, $zp_id)
    {

        $apArray = [];
        $users = $request->session()->get('users');

        // ---------------------- ZILLA PRISHAD-------------------------------------------------------------------------

        $zps = ZilaParishad::where([
            ['id', '=', $zp_id]
        ])->first();

        $zp_p = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
            ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
            ->where([
                ['h.master_pri_term_id', '=', 4],
                ['pri_member_main_records.zilla_id', '=', $zps->id],
                ['h.pri_master_designation_id', '=', 1],
            ])->groupBy('h.pri_master_designation_id')->count();

        $zp_m = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
            ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
            ->where([
                ['h.master_pri_term_id', '=', 4],
                ['pri_member_main_records.zilla_id', '=', $zps->id],
                ['h.pri_master_designation_id', '=', 2],
            ])->groupBy('h.pri_master_designation_id')->count();

        $progressReport["ZP" . $zps->id] = ['P' => $zp_p, 'M' => $zp_m];

        $zp_vp = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
            ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
            ->where([
                ['h.master_pri_term_id', '=', 4],
                ['pri_member_main_records.zilla_id', '=', $zps->id],
                ['h.pri_master_designation_id', '=', 7],
            ])->groupBy('h.pri_master_designation_id')->count();

        $progressReport["ZP" . $zps->id] = ['P' => $zp_p, 'M' => $zp_m, 'V' => $zp_vp];


        //-----------------------  ANCHALIK PARICHAD     ---------------------------------------------------------------

        $aps = AnchalikParishad::where('zila_id', '=', $zps->id)->select('id', 'anchalik_parishad_name')->orderBy('anchalik_parishad_name', 'asc')->get();

        foreach ($aps as $ap) {

            $ap_p = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS a', 'a.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.anchalik_id', '=', $ap->id],
                    ['h.pri_master_designation_id', '=', 3],

                ])->groupBy('h.pri_master_designation_id')->count();

            $ap_m = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS a', 'a.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.anchalik_id', '=', $ap->id],
                    ['h.pri_master_designation_id', '=', 4],

                ])->groupBy('h.pri_master_designation_id')->count();

            $progressReport["AP" . $ap->id] = ['P' => $ap_p, 'M' => $ap_m];

            $ap_vp = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS a', 'a.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.anchalik_id', '=', $ap->id],
                    ['h.pri_master_designation_id', '=', 8],

                ])->groupBy('h.pri_master_designation_id')->count();

            $progressReport["AP" . $ap->id] = ['P' => $ap_p, 'M' => $ap_m, 'V' => $ap_vp];


            array_push($apArray, $ap->id);

        }

        //-----------------------  GRAM PANCHAYAT        -----------------------------------------------------------

        $gps = GramPanchyat::join('anchalik_parishads AS a', 'a.id', '=', 'gram_panchyats.anchalik_id')
            ->whereIn('anchalik_id', $apArray)
            ->select('gram_panchyat_id AS id', 'a.anchalik_parishad_name', 'a.id AS ap_id', 'gram_panchayat_name')
            ->orderBy('anchalik_parishad_name', 'asc')
            ->get();

        foreach ($gps as $gp) {

            $gp_p = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.gram_panchayat_id', '=', $gp->id],
                    ['h.pri_master_designation_id', '=', 5],

                ])->groupBy('h.pri_master_designation_id')->count();

            $gp_m = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.gram_panchayat_id', '=', $gp->id],
                    ['h.pri_master_designation_id', '=', 6],

                ])->groupBy('h.pri_master_designation_id')->count();

            $gp_vp = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.gram_panchayat_id', '=', $gp->id],
                    ['h.pri_master_designation_id', '=', 9],

                ])->groupBy('h.pri_master_designation_id')->count();


            $progressReport["GP" . $gp->id] = ['P' => $gp_p, 'M' => $gp_m, 'V' => $gp_vp];

        }


        return view('admin.Pris.reportProgress', compact('zps', 'aps', 'gps', 'progressReport'));
    }

   
}