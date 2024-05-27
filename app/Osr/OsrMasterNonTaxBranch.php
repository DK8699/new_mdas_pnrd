<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrMasterNonTaxBranch extends Model
{
    public static function get_branches(){
        return OsrMasterNonTaxBranch::all();
    }

    public static function getBranchById($id){
        return OsrMasterNonTaxBranch::where('id', $id)->first();
    }

    public static function getBranchByAssetCode($asset_code){
        return OsrMasterNonTaxBranch::join('osr_non_tax_asset_entries AS a', 'a.osr_asset_branch_id', '=', 'osr_master_non_tax_branches.id')
            ->where([
                ['a.asset_code', '=', $asset_code]
            ])->select('osr_master_non_tax_branches.*')->first();
    }

    public static function getActiveBranchById($id){
        return OsrMasterNonTaxBranch::where([
            ['id', '=', $id],
            ['is_active', '=', 1],
        ])->first();
    }
}
