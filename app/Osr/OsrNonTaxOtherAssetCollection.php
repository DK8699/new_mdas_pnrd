<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrNonTaxOtherAssetCollection extends Model
{
    public static function getCollections($asset_code, $osr_fy_year_id){
        return OsrNonTaxOtherAssetCollection::where([
            ['other_asset_code', '=', $asset_code],
            ['osr_master_fy_year_id', '=', $osr_fy_year_id],
        ])->get();
    }
}
