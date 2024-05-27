<?php

namespace App\CommonModels;

use Illuminate\Database\Eloquent\Model;

class GramPanchyat extends Model
{

    public static function getGpsByAnchalikId($anchalik_id){
        return GramPanchyat::where([
            ['anchalik_id', '=', $anchalik_id]
        ])->select('gram_panchyat_id AS id', 'gram_panchayat_name')
            ->get();
    }

    public static function gpCountByZilaId($zp_id){
        return GramPanchyat::join('anchalik_parishads AS ap', 'gram_panchyats.anchalik_id', 'ap.id')
            ->where([
                ['ap.zila_id', '=', $zp_id]
            ])->count();
    }

    public static function gpCountByApId($ap_id){
        return GramPanchyat::where([
            ['anchalik_id', '=', $ap_id]
        ])->count();
    }

    //---------------- NEW --------------------------------------------------------------------------------------

    public static function getGPName($id){
        return GramPanchyat::where([
            ['gram_panchyat_id', '=', $id]
        ])->select('gram_panchayat_name')->first();
    }

    public static function getGPsByGpId($gp_id){
        return GramPanchyat::where([
            ['gram_panchyat_id', '=', $gp_id]
        ])->select('gram_panchyat_id','gram_panchayat_name')->first();
    }

    public static function getActiveGPsByZpId($id){
        return GramPanchyat::join('anchalik_parishads AS ap', 'gram_panchyats.anchalik_id', 'ap.id')
            ->where([
                ['ap.zila_id', '=', $id],
                ['gram_panchyats.is_active', '=', 1],
            ])->select('gram_panchyat_id', 'anchalik_id', 'gram_panchayat_name')->get();
    }

    public static function getActiveGPsByApId($id){
        return GramPanchyat::where([
            ['anchalik_id', '=', $id],
            ['is_active', '=', 1],
        ])->select('gram_panchyat_id', 'anchalik_id', 'gram_panchayat_name')->get();
    }

	public static function getActiveAPsByGpId($id){
        return GramPanchyat::where([
            ['gram_panchyat_id', '=', $id],
            ['is_active', '=', 1],
        ])->select('anchalik_id')->first();
    }
	
    public static function getGPsByZpId($id){
        return GramPanchyat::join('anchalik_parishads AS ap', 'gram_panchyats.anchalik_id', 'ap.id')
            ->where([
                ['ap.zila_id', '=', $id],
            ])->select('gram_panchyat_id', 'gram_panchyats.anchalik_id', 'ap.anchalik_parishad_name', 'gram_panchyats.gram_panchayat_name')
            ->orderBy('ap.anchalik_parishad_name')
            ->orderBy('gram_panchyats.gram_panchayat_name')
            ->get();
    }
	
	public static function getZpApGpByGpId($id){
	    
	     return GramPanchyat::join('anchalik_parishads as a','a.id','=','gram_panchyats.anchalik_id')
					    ->join('zila_parishads as z','z.id','=','a.zila_id')
		    ->where([
            ['gram_panchyats.gram_panchyat_id', '=', $id],
            ['gram_panchyats.is_active', '=', 1],
        ])->select('gram_panchyats.gram_panchyat_id as gp_id','a.id as ap_id','z.id as id','gram_panchyats.gram_panchayat_name','a.anchalik_parishad_name','z.zila_parishad_name')->first();
    }
	
	// New changes for VCDC
	
    public static function getVCDCByBlockId($block_id){
        return GramPanchyat::where([
            ['block_id', '=', $block_id],
		   ['vcc','=',1]
        ])->select('gram_panchyat_id AS id', 'gram_panchayat_name')
            ->get();
    }


}
