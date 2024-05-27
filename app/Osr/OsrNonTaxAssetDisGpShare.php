<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;
use DB;

class OsrNonTaxAssetDisGpShare extends Model
{
    public static function getGPsList($asset_code, $fy_id, $osr_non_tax_fy_instalment_id){
        $gpList=[];
        $apList= OsrNonTaxAssetDisGpShare::join('anchalik_parishads AS ap', 'ap.id', '=', 'osr_non_tax_asset_dis_gp_shares.ap_id')
            ->where([
            ["asset_code", '=', $asset_code],
            ["fy_id", '=', $fy_id],
            ["osr_non_tax_fy_instalment_id", '=', $osr_non_tax_fy_instalment_id],
        ])->select('ap.id', 'ap.anchalik_parishad_name')->groupBy('osr_non_tax_asset_dis_gp_shares.ap_id')->orderBy('ap.anchalik_parishad_name')->get();

        foreach ($apList AS $aps){
            $data= OsrNonTaxAssetDisGpShare::join('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'osr_non_tax_asset_dis_gp_shares.gp_id')
                ->where([
                    ["asset_code", '=', $asset_code],
                    ["fy_id", '=', $fy_id],
                    ["ap_id", '=', $aps->id],
                    ["osr_non_tax_fy_instalment_id", '=', $osr_non_tax_fy_instalment_id],
                ])->select('gp.gram_panchyat_id AS id', 'gp.gram_panchayat_name')->orderBy('gp.gram_panchayat_name')->get();

            $gpList[$aps->anchalik_parishad_name]=$data;
        }

        return $gpList;
    }

    //GP

    public static function getZpShareToGpList($id, $ap_id, $fy_id){
        $finalArray=[];

        $data=OsrNonTaxAssetDisGpShare::where([
            ['fy_id', '=', $fy_id],
            ['shared_by', '=', "ZP"],
            ['zp_id', '=', $id],
            ['ap_id', '=', $ap_id],
        ])->select(DB::raw('sum(gp_share) AS gp_share, gp_id'))
            ->groupBy('gp_id')->get();
        foreach($data AS $li){
            $finalArray[$li->gp_id]=$li->gp_share;
        }
        return $finalArray;
    }

    public static function getApShareToGpList($id, $ap_id, $fy_id){
        $finalArray=[];

        $data=OsrNonTaxAssetDisGpShare::where([
            ['fy_id', '=', $fy_id],
            ['shared_by', '=', "AP"],
            ['zp_id', '=', $id],
            ['ap_id', '=', $ap_id],
        ])->select(DB::raw('sum(gp_share) AS gp_share, gp_id'))
            ->groupBy('gp_id')->get();

        foreach($data AS $li){
            $finalArray[$li->gp_id]=$li->gp_share;
        }
        return $finalArray;
    }

	//Revenue Installment Share
	
	public static function getGpShareByZp($ins_id,$a_code,$fy_id,$level){
		
		return OsrNonTaxAssetDisGpShare::join('anchalik_parishads as a','a.id','=','osr_non_tax_asset_dis_gp_shares.ap_id')
		   						  ->join('gram_panchyats as g','g.gram_panchyat_id','=','osr_non_tax_asset_dis_gp_shares.gp_id')
								  ->join('osr_non_tax_fy_instalments as fy_ins','fy_ins.id','=','osr_non_tax_asset_dis_gp_shares.osr_non_tax_fy_instalment_id')
								  ->where([
									 ['fy_ins.osr_master_instalment_id','=',$ins_id],
									 ['osr_non_tax_asset_dis_gp_shares.asset_code','=',$a_code],
									 ['osr_non_tax_asset_dis_gp_shares.fy_id','=',$fy_id],
									 ['osr_non_tax_asset_dis_gp_shares.shared_by','=',$level],
								   ])->select('osr_non_tax_asset_dis_gp_shares.est_gp_share','osr_non_tax_asset_dis_gp_shares.gp_share','a.anchalik_parishad_name','g.gram_panchayat_name','a.id')
								->orderBy('a.id')
								 ->get();
	}

	//Gap period sharing
	public static function getGAPGpShareByZp($ins_id,$a_code,$fy_id,$level){
		
		return OsrNonTaxAssetDisGpShare::join('anchalik_parishads as a','a.id','=','osr_non_tax_asset_dis_gp_shares.ap_id')
		   						  ->join('gram_panchyats as g','g.gram_panchyat_id','=','osr_non_tax_asset_dis_gp_shares.gp_id')
								  ->leftJoin('osr_non_tax_fy_instalments as fy_ins','fy_ins.id','=','osr_non_tax_asset_dis_gp_shares.osr_non_tax_fy_instalment_id')
								  ->where([
									 ['osr_non_tax_asset_dis_gp_shares.osr_non_tax_fy_instalment_id','=',$ins_id],
									 ['osr_non_tax_asset_dis_gp_shares.asset_code','=',$a_code],
									 ['osr_non_tax_asset_dis_gp_shares.fy_id','=',$fy_id],
									 ['osr_non_tax_asset_dis_gp_shares.shared_by','=',$level],
								   ])->select('osr_non_tax_asset_dis_gp_shares.est_gp_share','osr_non_tax_asset_dis_gp_shares.gp_share','a.anchalik_parishad_name','g.gram_panchayat_name','a.id')
								 ->get();
	}
	
}
