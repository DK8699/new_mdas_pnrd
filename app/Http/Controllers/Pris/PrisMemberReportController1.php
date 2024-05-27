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

class PrisMemberReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'user_mdas']);
    }

    public function report(Request $request) {
    	$users=$request->session()->get('users');
    	$district_code=$users->district_code;
    	
    	$zillas=ZilaParishad::where([
    		['district_id', '=', $district_code]
    	])->first();

    	$anchaliks= AnchalikParishad::where([
                ['zila_id', '=', $zillas->id]
            ])->select('id', 'anchalik_parishad_name')->get();

    	return view('Pris.Member.report', compact('zillas', 'anchaliks'));
    }


    public function searchReportGP(Request $request) {
    	$zp_id=$request->input('zilla_code');
    	$ap_id=$request->input('anchalik_code');

    	$zillas=ZilaParishad::where([
    		['id', '=', $zp_id]
    	])->first();

    	$aps= AnchalikParishad::where([
    		['id', '=', $ap_id]
    	])->first();

    	$gps= GramPanchyat::where([
    		['anchalik_id', '=', $ap_id]
    	])->select('gram_panchyat_id AS id', 'gram_panchayat_name')->get();

    	foreach($gps AS $gp){

    		$gp_president=PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
    		->join('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
    		->where([
    			['h.master_pri_term_id', '=', 4],
    			['pri_member_main_records.gram_panchayat_id', '=', $gp->id],
    			['h.pri_master_designation_id', '=', 5],

    		])->groupBy('h.pri_master_designation_id')->count();

    		$gp_member=PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
    		->join('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
    		->where([
    			['h.master_pri_term_id', '=', 4],
    			['pri_member_main_records.gram_panchayat_id', '=', $gp->id],
    			['h.pri_master_designation_id', '=', 6],

    		])->groupBy('h.pri_master_designation_id')->count();


    		$result_gp[$gp->id]=['gp_president'=> $gp_president, 'gp_member'=>$gp_member];


    	}

    	return view('Pris.Member.searchReportGP', compact('zillas', 'aps', 'gps', 'result_gp'));
    }


}

