<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrNonTaxOtherFormSellingIncome extends Model
{
    public static function getByAssetIdAndFyId($fy_year_id, $asset_id){
        return OsrNonTaxOtherFormSellingIncome::where([
            ['osr_master_fy_year_id', '=', $fy_year_id],
            ['osr_non_tax_asset_entry_id', '=', $asset_id],
        ])->first();
    }
}
