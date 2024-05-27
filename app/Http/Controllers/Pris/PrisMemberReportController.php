<?php

namespace App\Http\Controllers\Pris;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CommonModels\GramPanchyat;
use App\CommonModels\District;
use App\CommonModels\GaonPanchayat;
use App\survey\six_finance\AnchalikParishad;
use App\survey\six_finance\ZilaParishad;
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
use Auth;
use App\Osr\OsrMasterFyYear;


class PrisMemberReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'user_mdas']);
    }

    public function reportDist(Request $request)
    {

        $priMembers = [];
        $searchResult = false;
        $imgUrl = ConfigMdas::allActiveList()->imgUrl;

        //---------- GET CURRENT USER ----------------------------------------------------------------------------------

        $users = Auth::user();

        $district_code = $users->district_code;

        $zillaForFilters = ZilaParishad::where([
            ['district_id', '=', $district_code]
        ])->first();

        $anchalikForFilters = AnchalikParishad::where([
            ['zila_id', '=', $zillaForFilters->id]
        ])->select('id', 'anchalik_parishad_name')->get();

        //---------- POST METHOD ACTIVATED -----------------------------------------------------------------------------
		
		$max_fy_id=OsrMasterFyYear::getMaxFyYear();
        $data=[
            'fy_id'=>$max_fy_id
        ];

        if ($request->isMethod('post')) {

            $searchResult = true;
            $zillas = [];
            $anchaliks = [];
            $gps = [];


            $filterTier = $request->input('tier');

            if($filterTier=="ZP" || $filterTier=="AP"){
                $tier = "ZP";
                $appls= [1,2];

                $zillas = $zillaForFilters;
                $anchaliks = $anchalikForFilters;

                //////////////////
                if ($filterTier == "ZP") {
                    $zp_id = $request->input('zp_id');
                    $filterArray= ["filterTier"=>$filterTier, "filterZP"=>$zp_id];
                } else {
                    $zp_id = $request->input('zp_id');
                    $ap_id = $request->input('ap_id');
                    $filterArray= ["filterTier"=>$filterTier, "filterZP"=>$zp_id, "filterAP"=>$ap_id];
                }

            }elseif($filterTier=="GP"){
                $tier = "GP";
                $appls= [3];

                //////////////////////
                $zp_id = $request->input('zp_id');
                $ap_id = $request->input('ap_id');
                $gp_id = $request->input('gp_id');

                $gps= GramPanchyat::where([
                    ['anchalik_id', '=', $ap_id]
                ])->select('gram_panchyat_id AS id', 'gram_panchayat_name')->get();


                $filterArray= ["filterTier"=>$filterTier, "filterZP"=>$zp_id, "filterAP"=>$ap_id, "filterGP"=>$gp_id, "filterGPList"=>$gps];

                /////////////////////////////
                $zillas = $zillaForFilters;
                $anchaliks = $anchalikForFilters;
            }else{
                $appls= [];
                $tier = NULL;
                $filterArray=[];
            }


            $all_designs = PriMasterDesignation::all();
            $designs = PriMasterDesignation::getDesignByApplicables($appls);
            $politicals = MasterPriPoliticalParty::all();
            $incomes = MasterAnnualIncome::all();
            $bloods = MasterBloodGroup::all();
            $maritals = MasterMaritalStatus::all();
            $religions = MasterReligion::all();
            $castes = MasterCaste::all();
            $genders = MasterGender::all();
            $qualifications = MasterHighestQualification::all();
            $wards = MasterWard::all();
            $reserveSeats = MasterPrisReserveSeat::all();
            $districts = District::orderBy('district_name')->get();


            //--------------GET PRI MEMBERS ACCORDING TO ZP, AP, GP WISE------------------------------------------------

            $priMembers= $this->getMembersByTier($request);

            return view('Pris.Member.reportDist', compact('data', 'tier', 'all_designs', 'searchResult', 'zillas', 'anchaliks', 'zillaForFilters',
                'anchalikForFilters', 'priMembers', 'imgUrl', 'zillas', 'anchaliks', 'designs', 'politicals',
                'incomes', 'bloods', 'maritals', 'religions', 'castes', 'genders', 'qualifications', 'wards',
                'reserveSeats', 'districts', 'filterArray'));

        } else {
            return view('Pris.Member.reportDist', compact('data', 'searchResult', 'zillaForFilters', 'anchalikForFilters',
                'priMembers', 'imgUrl'));
        }

    }


    public function selectAjax(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Failed To Process Request.";

        try {
            $results = GramPanchyat::getGpsByAnchalikId($request->input('anchalik_id'));

            if (!count($results) > 0) {
                $returnData['msg'] = "No Data Found.";
                return response()->json($returnData);
            }

        } catch (\Exception $e) {
            $returnData['msg'] = "Server Exception." . $e->getMessage();
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = $results;
        $returnData['msg'] = "Success.";
        return response()->json($returnData);
    }

    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //--------------GET PRI MEMBERS ACCORDING TO ZP, AP, GP WISE--------------------------------------------------------
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    private function getMembersByTier($request){

        $tier = $request->input('tier');

        $priMembers=[];

        if ($tier == "ZP") {

            $zp_id = $request->input('zp_id');
            $designArray = [1, 2, 7];
            $whereArray = [
                ['h.master_pri_term_id', '=', 4],
                ['pri_member_main_records.zilla_id', '=', $zp_id],

            ];
            $priMembers = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->join('pri_master_designations AS d', 'd.id', '=', 'h.pri_master_designation_id')
                ->where($whereArray)
                ->whereIn('h.pri_master_designation_id', $designArray)
                ->select('pri_member_main_records.id','pri_code', 'pri_f_name', 'pri_m_name', 'pri_l_name', 'd.design_name', 'pri_pic', 'd.color_code')
                ->orderByRaw('d.priority ASC, pri_member_main_records.id ASC')
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
                ->select('pri_member_main_records.id','pri_code', 'pri_f_name', 'pri_m_name', 'pri_l_name', 'd.design_name', 'pri_pic', 'd.color_code')
                ->orderByRaw('d.priority ASC, pri_member_main_records.id ASC')
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
                ->select('pri_member_main_records.id','pri_code', 'pri_f_name', 'pri_m_name', 'pri_l_name', 'd.design_name', 'pri_pic', 'd.color_code')
                ->orderByRaw('d.priority ASC, pri_member_main_records.id ASC')
                ->get();

        }


        return $priMembers;
    }


    public function reportProgress (Request $request) {

        $apArray=[];
        $users = Auth::user();
        $district_code = $users->district_code;

        // ---------------------- ZILLA PRISHAD-------------------------------------------------------------------------

        $zps = ZilaParishad::where([
            ['district_id', '=', $district_code]
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

        $progressReport["ZP".$zps->id]=['P'=> $zp_p, 'M'=>$zp_m];

        $zp_vp = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
            ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
            ->where([
                ['h.master_pri_term_id', '=', 4],
                ['pri_member_main_records.zilla_id', '=', $zps->id],
                ['h.pri_master_designation_id', '=', 7],
            ])->groupBy('h.pri_master_designation_id')->count();

        $progressReport["ZP".$zps->id]=['P'=> $zp_p, 'M'=>$zp_m, 'V'=>$zp_vp];


        //-----------------------  ANCHALIK PARICHAD     ---------------------------------------------------------------

        $aps=AnchalikParishad::where('zila_id', '=', $zps->id)->select('id', 'anchalik_parishad_name')->orderBy('anchalik_parishad_name', 'asc')->get();

        foreach ($aps AS $ap) {

            $ap_p=PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS a', 'a.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.anchalik_id', '=', $ap->id],
                    ['h.pri_master_designation_id', '=', 3],

                ])->groupBy('h.pri_master_designation_id')->count();

            $ap_m=PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS a', 'a.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.anchalik_id', '=', $ap->id],
                    ['h.pri_master_designation_id', '=', 4],

                ])->groupBy('h.pri_master_designation_id')->count();

            $progressReport["AP".$ap->id]=['P'=> $ap_p, 'M'=>$ap_m];

            $ap_vp=PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('anchalik_parishads AS a', 'a.id', '=', 'pri_member_main_records.anchalik_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['pri_member_main_records.anchalik_id', '=', $ap->id],
                    ['h.pri_master_designation_id', '=', 8],

                ])->groupBy('h.pri_master_designation_id')->count();

            $progressReport["AP".$ap->id]=['P'=> $ap_p, 'M'=>$ap_m, 'V'=>$ap_vp];


            array_push($apArray, $ap->id);

        }

        //-----------------------  GRAM PANCHAYAT        -----------------------------------------------------------

        $gps= GramPanchyat::join('anchalik_parishads AS a', 'a.id', '=', 'gram_panchyats.anchalik_id')
            ->whereIn('anchalik_id', $apArray)
            ->select('gram_panchyat_id AS id', 'a.anchalik_parishad_name', 'a.id AS ap_id', 'gram_panchayat_name')
            ->orderBy('anchalik_parishad_name', 'asc')
            ->get();

        foreach ($gps AS $gp) {

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


            $progressReport["GP".$gp->id] =['P' => $gp_p, 'M' => $gp_m, 'V' => $gp_vp];

        }


        return view('Pris.Member.reportProgress', compact('zps','aps', 'gps', 'progressReport'));
    }

    //-----------------------  Deletion of PRI Memmber-----------------------------------------------------------


    public function destroyPRI(Request $request){
    
        $data['msgType']=false;
        $data['data']=[];
        $data['msg']="";

        $pri_id = $request->input('pri_id');
        $pri_code = $request->input('pri_code');

        $users = $request->session()->get('users');
	/*echo "In".$users->role; die();
        if (!in_array(4, $users->role)) { //DISTRICT ADMIN
            $data['msg'] = "You are unauthorised to delete.";
            return response()->json($data);
        }*/

        DB::beginTransaction();

        try {
            $destroyPRI= PriMemberMainRecord::where([
                ['id', '=', $pri_id],
                ['pri_code', '=', $pri_code],
            ])->delete();
            $destroyH= PriMemberTermHistory::where('pri_member_main_record_id',$pri_id)->delete();

            if(!$destroyPRI || !$destroyH){
                DB::rollback();
                $data['msg'] = "Could not delete ".$pri_code;
                return response()->json($data);
            }else{
            	
            }

        }catch (\Exception $e) {
            DB::rollback();
            $data['msg'] = "Opps! Something went wrong.EX";
            return response()->json($data);
        }

        DB::commit();

        $data['msgType']=true;
        $data['msg']="Successfully deleted ".$pri_code;
        return response()->json($data);
    }
}

