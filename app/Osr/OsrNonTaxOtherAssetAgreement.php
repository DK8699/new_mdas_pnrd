<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrNonTaxOtherAssetAgreement extends Model
{

    public static function getByAssetCodeAndFy($asset_code, $osr_fy_year_id){
        return OsrNonTaxOtherAssetAgreement::where([
            ['other_asset_code', '=', $asset_code],
            ['osr_master_fy_year_id', '=', $osr_fy_year_id]
        ])->get();
    }

    public static function getByAssetCodeAndFyAndId($asset_code, $osr_fy_year_id, $ag_id){
        return OsrNonTaxOtherAssetAgreement::where([
            ['other_asset_code', '=', $asset_code],
            ['osr_master_fy_year_id', '=', $osr_fy_year_id],
            ['id', '=', $ag_id]
        ])->count();
    }

}
