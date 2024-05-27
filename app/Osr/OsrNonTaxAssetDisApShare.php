<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;
use DB;

class OsrNonTaxAssetDisApShare extends Model
{
    //

    public static function getAPsList($asset_code, $fy_id, $osr_non_tax_fy_instalment_id){
        return OsrNonTaxAssetDisApShare::join('anchalik_parishads AS ap', 'ap.id', '=', 'osr_non_tax_asset_dis_ap_shares.ap_id')
            ->where([
            ["asset_code", '=', $asset_code],
            ["fy_id", '=', $fy_id],
            ["osr_non_tax_fy_instalment_id", '=', $osr_non_tax_fy_instalment_id],
        ])->select('ap.id', 'ap.anchalik_parishad_name')->orderBy('ap.anchalik_parishad_name', 'DESC')->get();
    }


    //AP

    public static  function getZpShareToApList($id, $fy_id){
        $finalArray=[];

        $data=OsrNonTaxAssetDisApShare::where([
            ['fy_id', '=', $fy_id],
            ['shared_by', '=', "ZP"],
            ['zp_id', '=', $id],
        ])->select(DB::raw('sum(ap_share) AS ap_share, ap_id'))
            ->groupBy('ap_id')->get();

        foreach($data AS $li){
            $finalArray[$li->ap_id]=$li->ap_share;
        }

        return $finalArray;
    }

    public static  function getGpsShareToApList($id, $fy_id){
        $finalArray=[];

        $data=OsrNonTaxAssetDisApShare::where([
            ['fy_id', '=', $fy_id],
            ['shared_by', '=', "GP"],
            ['zp_id', '=', $id],
        ])->select(DB::raw('sum(ap_share) AS ap_share, ap_id'))
            ->groupBy('ap_id')->get();

        foreach($data AS $li){
            $finalArray[$li->ap_id]=$li->ap_share;
        }

        return $finalArray;
    }

    //GP

    public static  function getZpShareToAp($id, $ap_id, $fy_id){
        $finalArray=[];

        $data=OsrNonTaxAssetDisApShare::where([
            ['fy_id', '=', $fy_id],
            ['shared_by', '=', "ZP"],
            ['zp_id', '=', $id],
            ['ap_id', '=', $ap_id],
        ])->select(DB::raw('sum(ap_share) AS ap_share, ap_id'))
            ->groupBy('ap_id')->get();

        foreach($data AS $li){
            $finalArray[$li->ap_id]=$li->ap_share;
        }

        return $finalArray;
    }

    public static  function getGpsShareToAp($id, $ap_id, $fy_id){
		
        $finalArray=[];

        $data=OsrNonTaxAssetDisApShare::where([
            ['fy_id', '=', $fy_id],
            ['shared_by', '=', "GP"],
            ['zp_id', '=', $id],
            ['ap_id', '=', $ap_id],
        ])->select(DB::raw('sum(ap_share) AS ap_share, ap_id'))
            ->groupBy('ap_id')->get();

        foreach($data AS $li){
            $finalArray[$li->ap_id]=$li->ap_share;
        }

        return $finalArray;
    }

	//Revenue Installment Share
	
	public static function getApShareByZp($ins_id,$a_code,$fy_id,$level){
		
		return OsrNonTaxAssetDisApShare::join('anchalik_parishads as a','a.id','=','osr_non_tax_asset_dis_ap_shares.ap_id')
								 ->join('osr_non_tax_fy_instalments as fy_ins','fy_ins.id','=','osr_non_tax_asset_dis_ap_shares.osr_non_tax_fy_instalment_id')
								 ->where([
									 ['fy_ins.osr_master_instalment_id','=',$ins_id],
									 ['osr_non_tax_asset_dis_ap_shares.asset_code','=',$a_code],
									 ['osr_non_tax_asset_dis_ap_shares.fy_id','=',$fy_id],
									 ['osr_non_tax_asset_dis_ap_shares.shared_by','=',$level],
								 ])->select('osr_non_tax_asset_dis_ap_shares.est_ap_share','osr_non_tax_asset_dis_ap_shares.ap_share','a.anchalik_parishad_name')->get();
	}

	//Gap period sharing
	
	public static function getGAPApShareByZp($ins_id,$a_code,$fy_id,$level){
		
		return OsrNonTaxAssetDisApShare::join('anchalik_parishads as a','a.id','=','osr_non_tax_asset_dis_ap_shares.ap_id')
								 ->join('osr_non_tax_fy_instalments as fy_ins','fy_ins.id','=','osr_non_tax_asset_dis_ap_shares.osr_non_tax_fy_instalment_id')
								 ->where([
									 ['osr_non_tax_asset_dis_ap_shares.osr_non_tax_fy_instalment_id','=',$ins_id],
									 ['osr_non_tax_asset_dis_ap_shares.asset_code','=',$a_code],
									 ['osr_non_tax_asset_dis_ap_shares.fy_id','=',$fy_id],
									 ['osr_non_tax_asset_dis_ap_shares.shared_by','=',$level],
								 ])->select('osr_non_tax_asset_dis_ap_shares.est_ap_share','osr_non_tax_asset_dis_ap_shares.ap_share','a.anchalik_parishad_name')->get();
	}
}
