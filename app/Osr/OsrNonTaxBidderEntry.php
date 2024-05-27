<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrNonTaxBidderEntry extends Model
{
    public static function isBidderAlreadyAdded($general_id, $pan_no, $mobile_no){
        $countPan= OsrNonTaxBidderEntry::join('osr_non_tax_bidding_bidders_details AS bd', 'bd.osr_master_bidder_entry_id', '=', 'osr_non_tax_bidder_entries.id')
            ->where([
                ['bd.osr_non_tax_bidding_general_detail_id', '=', $general_id],
                ['osr_non_tax_bidder_entries.b_pan_no', '=', $pan_no]
            ])->count();

        $countMobile= OsrNonTaxBidderEntry::join('osr_non_tax_bidding_bidders_details AS bd', 'bd.osr_master_bidder_entry_id', '=', 'osr_non_tax_bidder_entries.id')
            ->where([
                ['bd.osr_non_tax_bidding_general_detail_id', '=', $general_id],
                ['osr_non_tax_bidder_entries.b_mobile', '=', $mobile_no]
            ])->count();

        if($countPan > 0 || $countMobile > 0) {
            return false;
        }
        return true;
    }
}
