<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrNonTaxBiddingSettlementDetail extends Model
{
    public static function alreadyExist($general_id){
        $count= OsrNonTaxBiddingSettlementDetail::where('osr_non_tax_bidding_general_detail_id', $general_id)
            ->count();
        if($count>0){
            return false;
        }
        return true;
    }

    public static function getSettlementInfo($general_id){
        return OsrNonTaxBiddingSettlementDetail::where('osr_non_tax_bidding_general_detail_id', $general_id)
            ->first();
    }
}
