<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrNonTaxOtherAssetAgreementInstalment extends Model
{
    public static function getInstalments($asset_code, $osr_fy_year_id, $agreement_id){
        return OsrNonTaxOtherAssetAgreementInstalment::where([
            ['other_asset_code', '=', $asset_code],
            ['osr_master_fy_year_id', '=', $osr_fy_year_id],
            ['osr_non_tax_other_asset_agreement_id', '=', $agreement_id]
        ])->get();
    }
}
