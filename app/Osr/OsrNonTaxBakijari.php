<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrNonTaxBakijari extends Model
{
    public static function getByAssetIdAndFyId($fy_year_id, $asset_id){
        return OsrNonTaxBakijari::where([
            ['osr_master_fy_year_id', '=', $fy_year_id],
            ['osr_non_tax_asset_entry_id', '=', $asset_id],
        ])->first();
    }
}
