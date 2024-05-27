<?php

namespace App\survey\six_finance;

use Illuminate\Database\Eloquent\Model;

class SixFinanceGpSelectionList extends Model
{
    //

    public static function getZillasByEmpCode($emp_code){
        $list= SixFinanceGpSelectionList::where([
            ['emp_code', '=', $emp_code]
        ])->distinct('zila_id')->count('zila_id');
        //echo $list;
        if($list != 1){
            return false;
        }

        $list= SixFinanceGpSelectionList::join('zila_parishads', 'zila_parishads.id', '=', 'six_finance_gp_selection_lists.zila_id')
            ->where([
                ['emp_code', '=', $emp_code]
            ])->select('zila_parishads.id', 'zila_parishads.zila_parishad_name')
            ->first();

        $list1= ZilaParishad::join('districts', 'zila_parishads.district_id', '=', 'districts.id')
            ->where([
                ['zila_parishads.id', '=', $list->id]
            ])->select('districts.id', 'districts.district_name')
            ->first();

        return array("zillas"=>$list, "districts"=>$list1);
    }

    public static function getSelectedAnchalikByEmpCode($emp_code){
        $list= SixFinanceGpSelectionList::join('anchalik_parishads', 'anchalik_parishads.id', '=', 'six_finance_gp_selection_lists.anchalik_code')
            ->where([
                ['emp_code', '=', $emp_code]
            ])->select('anchalik_parishads.id', 'anchalik_parishads.anchalik_parishad_name')
            ->distinct('anchalik_parishads.id')
            ->get();

        return $list;

    }
	
	public static function getSelectedGPByEmpCode($emp_code){
        $list= SixFinanceGpSelectionList::join('gram_panchyats', 'six_finance_gp_selection_lists.gp_code', '=', 'gram_panchyats.gram_panchyat_id')
            ->where([
                ['six_finance_gp_selection_lists.emp_code', '=', $emp_code],
                ['vcc', '=', NULL],
            ])
            ->select('gram_panchyats.gram_panchyat_id AS id', 'gram_panchyats.gram_panchayat_name')
            ->get();
        return $list;
    }
	
	public static function getMemberDetailsByZpAp($zp_code, $ap_code)
    {
        $whereArray=[];
        if(!$ap_code){
            $whereArray=[
                ['zila_parishads.id', '=', $zp_code]
            ];
        }else{
            $whereArray=[
                ['zila_parishads.id', '=', $zp_code],
                ['ap.id', '=', $ap_code]
            ];
        }
        return ZilaParishad::join('anchalik_parishads AS ap', 'zila_parishads.id', '=', 'ap.zila_id')
            ->join('gram_panchyats AS gp', 'gp.anchalik_id', '=', 'ap.id')
            ->leftjoin('six_finance_gp_selection_lists AS gp_l', 'gp.gram_panchyat_id', '=', 'gp_l.gp_code')
            ->where($whereArray)
            ->select('zila_parishads.zila_parishad_name', 'ap.anchalik_parishad_name', 'gp.gram_panchayat_name', 'gp_l.emp_code')
            ->get();

    }
}
