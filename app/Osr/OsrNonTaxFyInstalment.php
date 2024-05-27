<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrNonTaxFyInstalment extends Model
{

    public static function getInstalmentByAssetAndFy($asset_code, $fy_year_id, $instalment_id){
        return OsrNonTaxFyInstalment::where([
            ['asset_code', '=', $asset_code],
            ['osr_master_fy_year_id', '=', $fy_year_id],
            ['osr_master_instalment_id', '=', $instalment_id],
            ['flag', '=', "I"]
        ])->first();
    }
    public static function getSubmittedInstalments($asset_code, $fy_year_id){
        return OsrNonTaxFyInstalment::where([
            ['asset_code', '=', $asset_code],
            ['osr_master_fy_year_id', '=', $fy_year_id],
            ['flag', '=', "I"]
        ])->get();
    }

    public static function getGapPeriodInstalments($asset_code, $fy_year_id){
        return OsrNonTaxFyInstalment::where([
            ['asset_code', '=', $asset_code],
            ['osr_master_fy_year_id', '=', $fy_year_id],
            ['flag', '=', "G"]
        ])->get();
    }


	/*public static function getFyInstalmentByFyId($fy_id,$zp_id,$branch_id){
    
       return OsrNonTaxFyInstalment::join('osr_non_tax_asset_entries as ad','osr_non_tax_fy_instalments.osr_non_tax_asset_entry_id','=','ad.id')
       ->where([
           ['osr_master_fy_year_id', '=', $fy_id],
           ['ad.zila_id','=', $zp_id],
           ['ad.osr_asset_branch_id','=', $branch_id]
       ])->get();
    
    }*/

}
