<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrNonTaxBiddingGeneralDetail extends Model
{
    public static function getEntryByCodeAndFyYr($asset_code, $osr_fy_yr_id){
        return OsrNonTaxBiddingGeneralDetail::where([
            ['asset_code', '=', $asset_code],
            ['osr_fy_year_id', '=', $osr_fy_yr_id],
        ])->first();
    }

    public static function getBidderDataByBidderEntryId($id){
        return OsrNonTaxBiddingGeneralDetail::join('osr_non_tax_bidding_bidders_details as bd','osr_non_tax_bidding_general_details.id','=','bd.osr_non_tax_bidding_general_detail_id')
            ->where('bd.osr_master_bidder_entry_id', '=', $id)
            ->select('bd.*')
            ->first();
    }
    
    public static function getGeneralDetailByFyId($fy_id,$zp_id,$branch_id){
        
        return OsrNonTaxBiddingGeneralDetail::join('osr_non_tax_asset_entries as ad','osr_non_tax_bidding_general_details.osr_asset_entry_id','=','ad.id')
        ->leftJoin('osr_non_tax_bidding_settlement_details as s','osr_non_tax_bidding_general_details.id','=','s.osr_non_tax_bidding_general_detail_id')
        ->leftJoin('osr_non_tax_bidding_bidders_details as bd','s.osr_non_tax_bidding_bidders_detail_id','=','bd.id')
        ->join('osr_non_tax_bidder_entries as b','bd.osr_master_bidder_entry_id','=','b.id')
        ->where([
                ['osr_non_tax_bidding_general_details.osr_fy_year_id','=', $fy_id],
                ['ad.zila_id','=', $zp_id],
                ['ad.osr_asset_branch_id','=', $branch_id]
        ])->select('osr_non_tax_bidding_general_details.*', 'bd.*', 's.*','b.*')
            ->get();
        
    }

}
