<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrNonTaxAssetSettlement extends Model
{
    public static function getDataByAssetEntryIdAndFyId($id, $fy_yr){
        return OsrNonTaxAssetSettlement::where([
            ['osr_asset_entry_id', '=', $id],
            ['osr_fy_year_id', '=', $fy_yr]
        ])->select('*')->first();
    }
    public static function getDataByAssetEntryId($id){
        return OsrNonTaxAssetSettlement::where([
            ['osr_asset_entry_id', '=', $id]
        ])->select('*')->get();
    }
}
