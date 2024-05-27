<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;
use DB;

class OsrNonTaxBiddingBiddersDetail extends Model
{
    public static function getAllBiddersByGeneralId($general_id){
        return DB::table('osr_non_tax_bidding_bidders_details AS bd')
            ->join('osr_non_tax_bidder_entries AS b', 'b.id', '=', 'bd.osr_master_bidder_entry_id')
            ->leftJoin('osr_master_bidder_remarks AS r', 'r.id', '=', 'bd.osr_master_bidder_remark_id')
            ->join('master_genders AS g', 'g.id', '=', 'b.b_gender_id')
            ->join('master_castes AS c', 'c.id', '=', 'b.b_caste_id')
            ->where([
                ['bd.osr_non_tax_bidding_general_detail_id', '=', $general_id],
            ])
            ->select('b.*', 'bd.id AS bidding_bidder_id', 'bd.bidding_amt','bd.ernest_amt',
                'bd.bidder_status', 'bd.osr_master_bidder_remark_id',
                'r.remark', 'g.gender_name','c.caste_name')
            ->get();
    }

    public static function getAllBiddersByGeneralIdArrangeBiddingAmt($general_id){
        return DB::table('osr_non_tax_bidding_bidders_details AS bd')
            ->join('osr_non_tax_bidder_entries AS b', 'b.id', '=', 'bd.osr_master_bidder_entry_id')
            ->leftJoin('osr_master_bidder_remarks AS r', 'r.id', '=', 'bd.osr_master_bidder_remark_id')
            ->join('master_genders AS g', 'g.id', '=', 'b.b_gender_id')
            ->where([
                ['bd.osr_non_tax_bidding_general_detail_id', '=', $general_id],
            ])
            ->select('b.*', 'bd.id AS bidding_bidder_id', 'bd.bidding_amt','bd.ernest_amt',
                     'bd.bidder_status', 'bd.osr_master_bidder_remark_id',
                'r.remark', 'g.gender_name')
            ->orderBy('bidding_amt', 'DESC')->get();
    }

    public static function getBidderById($id){
        return DB::table('osr_non_tax_bidding_bidders_details AS bd')
            ->join('osr_non_tax_bidder_entries AS b', 'b.id', '=', 'bd.osr_master_bidder_entry_id')
            ->where([
                ['b.id', '=', $id],
            ])
            ->select('b.*', 'bd.id AS bidding_bidder_id', 'bd.bidding_amt','bd.ernest_amt',
                 'bd.bidder_status', 'bd.osr_master_bidder_remark_id')
            ->first();
    }

    public static function totalBiddersCount($general_id){
        return OsrNonTaxBiddingBiddersDetail::where([
            ['osr_non_tax_bidding_general_detail_id', '=', $general_id],
        ])->count();
    }
    public static function totalWithdrawnBiddersCount($general_id){
        return OsrNonTaxBiddingBiddersDetail::where([
            ['osr_non_tax_bidding_general_detail_id', '=', $general_id],
            ['bidder_status','=', 2]
        ])->count();
    }
    public static function totalForfeitedBiddersCount($general_id, $forfeited){
        return OsrNonTaxBiddingBiddersDetail::where([
            ['osr_non_tax_bidding_general_detail_id', '=', $general_id],
            ['bidding_amt', '>', $forfeited],
            ['bidder_status','=', 2]
        ])->get();
    }
     

    public static function isAcceptedBidder($general_id, $bidder_status){
        if($bidder_status==1){
            $count= OsrNonTaxBiddingBiddersDetail::where([
                ['osr_non_tax_bidding_general_detail_id', '=', $general_id],
                ['bidder_status', '=', 1]
            ])->count();

            if($count > 0){
                return false;
            }
        }
        return true;
    }

    public static function acceptedBidder($general_id){
        return DB::table('osr_non_tax_bidding_bidders_details AS bd')
            ->join('osr_non_tax_bidder_entries AS b', 'b.id', '=', 'bd.osr_master_bidder_entry_id')
            ->leftJoin('osr_master_bidder_remarks AS r', 'r.id', '=', 'bd.osr_master_bidder_remark_id')
            ->join('master_genders AS g', 'g.id', '=', 'b.b_gender_id')
            ->where([
                ['bd.osr_non_tax_bidding_general_detail_id', '=', $general_id],
                ['bd.bidder_status', '=', 1],
            ])
            ->select('b.*', 'bd.id AS bidding_bidder_id', 'bd.ernest_amt','bd.bidder_status', 'bd.osr_master_bidder_remark_id',
                 'bd.bidding_amt','r.remark', 'g.gender_name')
            ->first();
    }
}
