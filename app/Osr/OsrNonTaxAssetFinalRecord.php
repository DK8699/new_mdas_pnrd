<?php

namespace App\Osr;

use App\CommonModels\AnchalikParishad;
use App\CommonModels\GramPanchyat;
use App\CommonModels\ZilaParishad;
use App\ConfigMdas;
use Illuminate\Database\Eloquent\Model;
use DB;

class OsrNonTaxAssetFinalRecord extends Model
{

    public static function finalRecordCount($asset_code, $fy_id)
    {
        return OsrNonTaxAssetFinalRecord::where([
            ['asset_code', '=', $asset_code],
            ['fy_id', '=', $fy_id],
        ])->count();
    }

    public static function getFinalRecord($asset_code, $fy_id)
    {
        return OsrNonTaxAssetFinalRecord::where([
            ['asset_code', '=', $asset_code],
            ['fy_id', '=', $fy_id],
        ])->first();
    }

    public static function totalStateCount($fy_id)
    {

        $totalAsset = OsrNonTaxAssetShortlist::where([
            ['osr_master_fy_year_id', '=', $fy_id],
            ['level', '=', 'ZP'],
        ])->count();

        $settledAsset = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['a_short.level', '=', "ZP"],
            ])->count();

        $totalDefaulter = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['osr_non_tax_asset_final_records.defaulter_status', '=', 1],
                ['a_short.level', '=', "ZP"],
            ])->count();

        //REVENUE LIST--------------------------------------------------------------------------------------------------

        $totalRevenueCollection = OsrNonTaxAssetFinalRecord::yrWiseRevenueList($fy_id);
        //REVENUE LIST ENDED--------------------------------------------------------------------------------------------

        //SHARE LIST----------------------------------------------------------------------------------------------------
        $assetRevenueCollection = OsrNonTaxAssetFinalRecord::yrWiseShareList($fy_id);
        $otherAssetRevenueCollection = OsrNonTaxOtherAssetFinalRecord::yrWiseShareList($fy_id);

        $tot_r_c = $assetRevenueCollection['tot_r_c'] + $otherAssetRevenueCollection['tot_r_c'];
        $zp_share = $assetRevenueCollection['zp_share'] + $otherAssetRevenueCollection['zp_share'];
        $ap_share = $assetRevenueCollection['ap_share'] + $otherAssetRevenueCollection['ap_share'];
        $gp_share = $assetRevenueCollection['gp_share'] + $otherAssetRevenueCollection['gp_share'];

        $totalRevenueShareCollection = [
            'tot_r_c' => ConfigMdas::cur_format($tot_r_c),
            'zp_share' => ConfigMdas::cur_format($zp_share),
            'ap_share' => ConfigMdas::cur_format($ap_share),
            'gp_share' => ConfigMdas::cur_format($gp_share),
        ];

        //SHARE LIST ENDED ---------------------------------------------------------------------------------------------

        if ($settledAsset > 0) {
            $defaulterPercent = ($totalDefaulter / $settledAsset) * 100;
        } else {
            $defaulterPercent = 0;
        }

        if ($totalAsset > 0) {
            $settledPercent = ($settledAsset / $totalAsset) * 100;
        } else {
            $settledPercent = 0;
        }

        $data = [
            'settledAsset' => $settledAsset,
            'totalDefaulter' => $totalDefaulter,
            'totalAsset' => $totalAsset,
            'defaulterPercent' => $defaulterPercent,
            'settledPercent' => $settledPercent,
            'totalRevenueCollection' => $totalRevenueCollection,
            'totalRevenueShareCollection' => $totalRevenueShareCollection,
        ];

        return $data;
    }

    public static function zpYrWiseSettledAssetCount($fy_id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['a_short.level', '=', "ZP"],
            ])
            ->select(DB::raw('count(*) AS total'), 'a_short.zp_id as z_id')
            ->groupBy('a_short.zp_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->z_id] = $li->total;
        }

        return $finalArray;
    }

    public static function zpYrWiseDefaulterCount($fy_id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['osr_non_tax_asset_final_records.defaulter_status', '=', 1],
                ['a_short.level', '=', 'ZP']
            ])
            ->select(DB::raw('count(*) AS total'), 'a_short.zp_id as z_id')
            ->groupBy('a_short.zp_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->z_id] = $li->total;
        }

        return $finalArray;
    }

    public static function zpWiseTotalAsset($fy_id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->where([
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'ZP']
            ])
            ->select(DB::raw('count(*) AS total'), 'osr_non_tax_asset_shortlists.zp_id as z_id')
            ->groupBy('osr_non_tax_asset_shortlists.zp_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->z_id] = $li->total;
        }

        return $finalArray;
    }

    public static function zpBranchWiseTotalAsset($fy_id, $id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->where([
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['a_entries.zila_id', '=', $id],
                ['osr_non_tax_asset_shortlists.level', '=', 'ZP'],
            ])
            ->select(DB::raw('count(*) AS total'), 'a_entries.osr_asset_branch_id as branch_id')
            ->groupBy('a_entries.osr_asset_branch_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->branch_id] = $li->total;
        }

        return $finalArray;
    }


    //REVENUE COLLECTION
    public static function yrWiseRevenueList($fy_id)
    {

        $finalAssetArray = [
            'gap_c' => 0,
            'bid_c' => 0
        ];

        $data = OsrNonTaxAssetFinalRecord::leftJoin('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.level', '=', 'ZP'],
            ])
            ->select(DB::raw('
                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,

                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,

                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '))
            ->groupBy('osr_non_tax_asset_final_records.fy_id')
            ->get();

        foreach ($data as $li) {

            $gap_c = $li->tot_gap_zp_share + $li->tot_gap_ap_share + $li->tot_gap_gp_share;
            $bid_c = $li->f_emd_zp_share + $li->f_emd_ap_share + $li->f_emd_gp_share +
                $li->df_zp_share + $li->df_ap_share + $li->df_gp_share +
                $li->tot_ins_collected_amt;

            $finalAssetArray = [
                'gap_c' => $gap_c,
                'bid_c' => $bid_c
            ];
        }

        $finalOtherAssetArray = OsrNonTaxOtherAssetFinalRecord::yrWiseRevenueList($fy_id);

        $gap_c = 0;
        $bid_c = 0;
        $other_c = 0;
        $tot_collection = 0;

        $gap_c = $finalAssetArray['gap_c'];
        $bid_c = $finalAssetArray['bid_c'];
        $tot_collection = $gap_c + $bid_c;

        $other_c = $finalOtherAssetArray['other_c'];
        $tot_collection = $tot_collection + $other_c;

        $finalArray = [
            'gap_c' => ConfigMdas::cur_format($gap_c),
            'bid_c' => ConfigMdas::cur_format($bid_c),
            'other_c' => ConfigMdas::cur_format($other_c),
            'tot_c' => ConfigMdas::cur_format($tot_collection)
        ];

        return $finalArray;
    }

    public static function zpWiseRevenueList($fy_id)
    {
        $finalArray = [];
        $finalAssetArray = [];
        $finalOtherAssetArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.level', '=', 'ZP'],
            ])
            ->select(DB::raw('
                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,

                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,

							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,

                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '), 'a_short.zp_id as z_id')
            ->groupBy('a_short.zp_id')
            ->get();

        foreach ($data as $li) {

            $gap_c = $li->tot_gap_zp_share + $li->tot_gap_ap_share + $li->tot_gap_gp_share;

            $bid_c = $li->f_emd_zp_share + $li->f_emd_ap_share + $li->f_emd_gp_share +
                $li->df_zp_share + $li->df_ap_share + $li->df_gp_share +
                $li->tot_ins_collected_amt;

            $zp_share = $li->f_emd_zp_share + $li->df_zp_share + $li->tot_ins_zp_share + $li->tot_gap_zp_share;
            $ap_share = $li->f_emd_ap_share + $li->df_ap_share + $li->tot_ins_ap_share + $li->tot_gap_ap_share;
            $gp_share = $li->f_emd_gp_share + $li->df_gp_share + $li->tot_ins_gp_share + $li->tot_gap_gp_share;

            $finalAssetArray[$li->z_id] = [
                'gap_c' => $gap_c,
                'bid_c' => $bid_c,
                "zp_share" => $zp_share,
                "ap_share" => $ap_share,
                "gp_share" => $gp_share
            ];
        }

        //ASSETS
        $apsShareToZp_asset = OsrNonTaxAssetDisZpShare::getApsShareToZpList($fy_id);
        $gpsShareToZp_asset = OsrNonTaxAssetDisZpShare::getGpsShareToZpList($fy_id);

        //OTHER ASSETS
        $apsShareToZp_other_asset = OsrNonTaxOtherAssetDisZpShare::getApsShareToZpList($fy_id);
        $gpsShareToZp_other_asset = OsrNonTaxOtherAssetDisZpShare::getGpsShareToZpList($fy_id);


        $finalOtherAssetArray = OsrNonTaxOtherAssetFinalRecord::zpWiseRevenueList($fy_id);

        $zpList = ZilaParishad::getZPs();

        foreach ($zpList as $zp) {
            $gap_c = 0;
            $bid_c = 0;
            $other_c = 0;
            $tot_collection = 0;

            $zp_share = 0;
            $ap_share = 0;
            $gp_share = 0;

            $aps_share_to_zp = 0;
            $gps_share_to_zp = 0;

            $tot_a_b = 0;

            if (isset($finalAssetArray[$zp->id])) {
                $gap_c = $finalAssetArray[$zp->id]['gap_c'];
                $bid_c = $finalAssetArray[$zp->id]['bid_c'];

                $zp_share = $zp_share + $finalAssetArray[$zp->id]['zp_share'];
                $ap_share = $ap_share + $finalAssetArray[$zp->id]['ap_share'];
                $gp_share = $gp_share + $finalAssetArray[$zp->id]['gp_share'];
            }

            if (isset($finalOtherAssetArray[$zp->id])) {
                $other_c = $finalOtherAssetArray[$zp->id]['other_c'];

                $zp_share = $zp_share + $finalOtherAssetArray[$zp->id]['zp_share'];
                $ap_share = $ap_share + $finalOtherAssetArray[$zp->id]['ap_share'];
                $gp_share = $gp_share + $finalOtherAssetArray[$zp->id]['gp_share'];
            }

            //ASSET
            if (isset($apsShareToZp_asset[$zp->id])) {
                $aps_share_to_zp = $apsShareToZp_asset[$zp->id];
            }

            if (isset($gpsShareToZp_asset[$zp->id])) {
                $gps_share_to_zp = $gpsShareToZp_asset[$zp->id];
            }

            //OTHER ASSET
            if (isset($apsShareToZp_other_asset[$zp->id])) {
                $aps_share_to_zp = $aps_share_to_zp + $apsShareToZp_other_asset[$zp->id];
            }

            if (isset($gpsShareToZp_other_asset[$zp->id])) {
                $gps_share_to_zp = $gps_share_to_zp + $gpsShareToZp_other_asset[$zp->id];
            }


            $tot_collection = $gap_c + $bid_c + $other_c;

            $tot_a_b = $zp_share + $aps_share_to_zp + $gps_share_to_zp;

            $finalArray[$zp->id] = [
                'gap_c' => ConfigMdas::cur_format($gap_c),
                'bid_c' => ConfigMdas::cur_format($bid_c),
                'other_c' => ConfigMdas::cur_format($other_c),
                'tot_c' => ConfigMdas::cur_format($tot_collection),
                'ap_share' => ConfigMdas::cur_format($ap_share),
                'gp_share' => ConfigMdas::cur_format($gp_share),
                'aps_share_to_zp' => ConfigMdas::cur_format($aps_share_to_zp),
                'gps_share_to_zp' => ConfigMdas::cur_format($gps_share_to_zp),
                'tot_a_b' => ConfigMdas::cur_format($tot_a_b),

            ];
        }

        return $finalArray;
    }
    //REVENUE COLLECTION ENDED

    //SHARE DISTRIBUTION
    public static function yrWiseShareList($fy_id)
    {
        $finalArray = [
            'tot_r_c' => 0,
            'zp_share' => 0,
            'ap_share' => 0,
            'gp_share' => 0
        ];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.level', '=', 'ZP'],
            ])->select(DB::raw('
                            sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,
                            sum(osr_non_tax_asset_final_records.tot_gap_collected_amt) AS tot_gap_collected_amt,

                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,

                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '))
            ->groupBy('osr_non_tax_asset_final_records.fy_id')
            ->get();

        foreach ($data as $li) {

            $zp_share = $li->f_emd_zp_share + $li->df_zp_share + $li->tot_ins_zp_share + $li->tot_gap_zp_share;
            $ap_share = $li->f_emd_ap_share + $li->df_ap_share + $li->tot_ins_ap_share + $li->tot_gap_ap_share;
            $gp_share = $li->f_emd_gp_share + $li->df_gp_share + $li->tot_ins_gp_share + $li->tot_gap_gp_share;

            $total_revenue_collection = $li->tot_ins_collected_amt + $li->tot_gap_collected_amt;

            $finalArray = [
                'tot_r_c' => $total_revenue_collection,
                'zp_share' => $zp_share,
                'ap_share' => $ap_share,
                'gp_share' => $gp_share
            ];
        }

        return $finalArray;
    }

    public static function zpYrWiseShareList($fy_id)
    {

        $finalArray = [];
        $finalAssetArray = [];
        $finalOtherAssetArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.level', '=', 'ZP'],
            ])->select(DB::raw('
                            sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,
                            sum(osr_non_tax_asset_final_records.tot_gap_collected_amt) AS tot_gap_collected_amt,

                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,

                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '), 'a_short.zp_id as z_id')
            ->groupBy('a_short.zp_id')
            ->get();

        foreach ($data as $li) {

            $zp_share = $li->f_emd_zp_share + $li->df_zp_share + $li->tot_ins_zp_share + $li->tot_gap_zp_share;
            $ap_share = $li->f_emd_ap_share + $li->df_ap_share + $li->tot_ins_ap_share + $li->tot_gap_ap_share;
            $gp_share = $li->f_emd_gp_share + $li->df_gp_share + $li->tot_ins_gp_share + $li->tot_gap_gp_share;

            $total_revenue_collection = $li->tot_ins_collected_amt + $li->tot_gap_collected_amt;

            $finalAssetArray[$li->z_id] = [
                'tot_r_c' => $total_revenue_collection,
                'zp_share' => $zp_share,
                'ap_share' => $ap_share,
                'gp_share' => $gp_share
            ];
        }

        $finalOtherAssetArray = OsrNonTaxOtherAssetFinalRecord::zpYrWiseShareList($fy_id);

        $zpList = ZilaParishad::getZPs();

        foreach ($zpList as $zp) {
            $zp_share = 0;
            $ap_share = 0;
            $gp_share = 0;

            $total_revenue_collection = 0;

            if (isset($finalAssetArray[$zp->id])) {
                $zp_share = $finalAssetArray[$zp->id]['zp_share'];
                $ap_share = $finalAssetArray[$zp->id]['ap_share'];
                $gp_share = $finalAssetArray[$zp->id]['gp_share'];

                $total_revenue_collection = $finalAssetArray[$zp->id]['tot_r_c'];
            }

            if (isset($finalOtherAssetArray[$zp->id])) {
                $zp_share = $zp_share + $finalOtherAssetArray[$zp->id]['zp_share'];
                $ap_share = $ap_share + $finalOtherAssetArray[$zp->id]['ap_share'];
                $gp_share = $gp_share + $finalOtherAssetArray[$zp->id]['gp_share'];

                $total_revenue_collection = $total_revenue_collection + $finalOtherAssetArray[$zp->id]['tot_r_c'];
            }

            $finalArray[$zp->id] = [
                'tot_r_c' => ConfigMdas::cur_format($total_revenue_collection),
                'zp_share' => ConfigMdas::cur_format($zp_share),
                'ap_share' => ConfigMdas::cur_format($ap_share),
                'gp_share' => ConfigMdas::cur_format($gp_share)
            ];
        }

        return $finalArray;
    }
    //SHARE DISTRIBUTION ENDED

    //YEAR WISE DEFAULTER COUNT
    public static function yrWiseDefaulterCount($fy_id)
    {

        return OsrNonTaxAssetFinalRecord::where([
            ['fy_id', '=', $fy_id],
            ['defaulter_status', '=', 1]
        ])->count();
    }

    public static function zpBranchWiseSettledAssetCount($fy_id, $id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['a_short.zp_id', '=', $id],
                ['a_short.level', '=', 'ZP']
            ])
            ->select(DB::raw('count(*) AS total'), 'a_short.osr_master_non_tax_branch_id as branch_id')
            ->groupBy('a_short.osr_master_non_tax_branch_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->branch_id] = $li->total;
        }

        return $finalArray;

    }

    public static function zpBranchWiseDefaulterCount($fy_id, $id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['osr_non_tax_asset_final_records.defaulter_status', '=', 1],
                ['a_short.zp_id', '=', $id],
                ['a_short.level', '=', 'ZP']
            ])
            ->select(DB::raw('count(*) AS total'), 'a_short.osr_master_non_tax_branch_id as branch_id')
            ->groupBy('a_short.osr_master_non_tax_branch_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->branch_id] = $li->total;
        }

        return $finalArray;
    }


    //---------------- AP LEVEL ----------------------------------------------------------------------------------------
    //SETTLEMENT

    public static function zpTotalAsset($id, $fy_id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->where([
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.zp_id', '=', $id],
                ['osr_non_tax_asset_shortlists.level', '=', 'ZP'],
            ])
            ->select(DB::raw('count(*) AS total'), 'a_entries.zila_id as zp_id')
            ->groupBy('a_entries.zila_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->zp_id] = $li->total;
        }

        return $finalArray;
    }

    public static function zpSettledAsset($id, $fy_id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['a_short.level', '=', 'ZP'],
                ['a_short.zp_id', '=', $id]
            ])
            ->select(DB::raw('count(*) AS total'), 'a_short.zp_id as z_id')
            ->groupBy('a_short.zp_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->z_id] = $li->total;
        }

        return $finalArray;
    }

    public static function apWiseTotalAsset($fy_id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->where([
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'AP']
            ])
            ->select(DB::raw('count(*) AS total'), 'a_entries.anchalik_id as ap_id')
            ->groupBy('a_entries.anchalik_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->ap_id] = $li->total;
        }

        return $finalArray;
    }

    public static function apYrWiseSettledAssetCount($fy_id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['a_short.level', '=', 'AP']
            ])
            ->select(DB::raw('count(*) AS total'), 'a_short.ap_id as anchalik_id')
            ->groupBy('a_short.ap_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->anchalik_id] = $li->total;
        }

        return $finalArray;
    }

    public static function apBranchWiseTotalAsset($fy_id, $id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->where([
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['a_entries.anchalik_id', '=', $id],
                ['osr_non_tax_asset_shortlists.level', '=', 'AP'],
            ])
            ->select(DB::raw('count(*) AS total'), 'a_entries.osr_asset_branch_id as branch_id')
            ->groupBy('a_entries.osr_asset_branch_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->branch_id] = $li->total;
        }

        return $finalArray;
    }

    //DEFAULTER

    public static function zpDefaulter($id, $fy_id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['osr_non_tax_asset_final_records.defaulter_status', '=', 1],
                ['a_short.zp_id', '=', $id],
                ['a_short.level', '=', 'ZP'],
            ])
            ->select(DB::raw('count(*) AS total'), 'a_short.zp_id as z_id')
            ->groupBy('a_short.zp_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->z_id] = $li->total;
        }

        return $finalArray;
    }

    public static function apYrWiseDefaulterCount($fy_id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['osr_non_tax_asset_final_records.defaulter_status', '=', 1],
                ['a_short.level', '=', 'AP']
            ])
            ->select(DB::raw('count(*) AS total'), 'a_short.ap_id as anchalik_id')
            ->groupBy('a_short.ap_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->anchalik_id] = $li->total;
        }

        return $finalArray;
    }

    public static function apBranchWiseSettledAssetCount($fy_id, $id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['a_short.ap_id', '=', $id],
                ['a_short.level', '=', 'AP']
            ])
            ->select(DB::raw('count(*) AS total'), 'a_short.osr_master_non_tax_branch_id as branch_id')
            ->groupBy('a_short.osr_master_non_tax_branch_id')
            ->get();

        foreach ($data as $li) {

            $finalArray[$li->branch_id] = $li->total;
        }

        return $finalArray;

    }

    public static function apBranchWiseDefaulterCount($fy_id, $id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['osr_non_tax_asset_final_records.defaulter_status', '=', 1],
                ['a_short.ap_id', '=', $id],
                ['a_short.level', '=', 'AP']
            ])
            ->select(DB::raw('count(*) AS total'), 'a_short.osr_master_non_tax_branch_id as branch_id')
            ->groupBy('a_short.osr_master_non_tax_branch_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->branch_id] = $li->total;
        }

        return $finalArray;
    }

    //REVENUE

    public static function getYrZpRevenueData($id, $fy_id)
    {
        $finalArray = [];
        $finalAssetArray = [];
        $finalOtherAssetArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.level', '=', 'ZP'],
                ['a_short.zp_id', '=', $id],
            ])
            ->select(DB::raw('
                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,

                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,

							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,

                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '), 'a_short.zp_id as z_id')
            ->groupBy('a_short.zp_id')
            ->get();

        foreach ($data as $li) {

            $gap_c = $li->tot_gap_zp_share + $li->tot_gap_ap_share + $li->tot_gap_gp_share;
            $bid_c = $li->f_emd_zp_share + $li->f_emd_ap_share + $li->f_emd_gp_share +
                $li->df_zp_share + $li->df_ap_share + $li->df_gp_share +
                $li->tot_ins_collected_amt;

            $zp_share = $li->f_emd_zp_share + $li->df_zp_share + $li->tot_ins_zp_share + $li->tot_gap_zp_share;
            $ap_share = $li->f_emd_ap_share + $li->df_ap_share + $li->tot_ins_ap_share + $li->tot_gap_ap_share;
            $gp_share = $li->f_emd_gp_share + $li->df_gp_share + $li->tot_ins_gp_share + $li->tot_gap_gp_share;

            $finalAssetArray[$li->z_id] = [
                'gap_c' => $gap_c,
                'bid_c' => $bid_c,
                "zp_share" => $zp_share,
                "ap_share" => $ap_share,
                "gp_share" => $gp_share
            ];
        }

        //ASSET
        $apsShareToZp_asset = OsrNonTaxAssetDisZpShare::getApsShareToZp($id, $fy_id);
        $gpsShareToZp_asset = OsrNonTaxAssetDisZpShare::getGpsShareToZp($id, $fy_id);

        //OTHER ASSET
        $apsShareToZp_other_asset = OsrNonTaxOtherAssetDisZpShare::getApsShareToZp($id, $fy_id);
        $gpsShareToZp_other_asset = OsrNonTaxOtherAssetDisZpShare::getGpsShareToZp($id, $fy_id);


        $finalOtherAssetArray = OsrNonTaxOtherAssetFinalRecord::getYrZpRevenueData($id, $fy_id);

        $gap_c = 0;
        $bid_c = 0;
        $other_c = 0;
        $tot_collection = 0;

        $zp_share = 0;
        $ap_share = 0;
        $gp_share = 0;

        $aps_share_to_zp = 0;
        $gps_share_to_zp = 0;

        $tot_a_b = 0;

        if (isset($finalAssetArray[$id])) {

            $gap_c = $finalAssetArray[$id]['gap_c'];
            $bid_c = $finalAssetArray[$id]['bid_c'];

            $zp_share = $zp_share + $finalAssetArray[$id]['zp_share'];
            $ap_share = $ap_share + $finalAssetArray[$id]['ap_share'];
            $gp_share = $gp_share + $finalAssetArray[$id]['gp_share'];
        }

        if (isset($finalOtherAssetArray[$id])) {

            $other_c = $finalOtherAssetArray[$id]['other_c'];

            $zp_share = $zp_share + $finalOtherAssetArray[$id]['zp_share'];
            $ap_share = $ap_share + $finalOtherAssetArray[$id]['ap_share'];
            $gp_share = $gp_share + $finalOtherAssetArray[$id]['gp_share'];
        }

        //ASSET

        if (isset($apsShareToZp_asset[$id])) {
            $aps_share_to_zp = $apsShareToZp_asset[$id];
        }

        if (isset($gpsShareToZp_asset[$id])) {
            $gps_share_to_zp = $gpsShareToZp_asset[$id];
        }

        //OTHER ASSET

        if (isset($apsShareToZp_other_asset[$id])) {
            $aps_share_to_zp = $aps_share_to_zp + $apsShareToZp_other_asset[$id];
        }

        if (isset($gpsShareToZp_other_asset[$id])) {
            $gps_share_to_zp = $gps_share_to_zp + $gpsShareToZp_other_asset[$id];
        }

        $tot_collection = $gap_c + $bid_c + $other_c;

        $tot_a_b = $zp_share + $aps_share_to_zp + $gps_share_to_zp;

        $finalArray[$id] = [
            'gap_c' => ConfigMdas::cur_format($gap_c),
            'bid_c' => ConfigMdas::cur_format($bid_c),
            'other_c' => ConfigMdas::cur_format($other_c),
            'tot_c' => ConfigMdas::cur_format($tot_collection),
            'ap_share' => ConfigMdas::cur_format($ap_share),
            'gp_share' => ConfigMdas::cur_format($gp_share),
            'aps_share_to_zp' => ConfigMdas::cur_format($aps_share_to_zp),
            'gps_share_to_zp' => ConfigMdas::cur_format($gps_share_to_zp),
            'tot_a_b' => ConfigMdas::cur_format($tot_a_b),
        ];

        return $finalArray;
    }

    public static function getYrApRevenueList($id, $fy_id)
    {
        $finalArray = [];
        $finalAssetArray = [];
        $finalOtherAssetArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.level', '=', 'AP'],
                ['a_short.zp_id', '=', $id],
            ])
            ->select(DB::raw('
                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,

                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,

							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,

                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '), 'a_short.ap_id as anchalik_id')
            ->groupBy('a_short.ap_id')
            ->get();

        foreach ($data as $li) {

            $gap_c = $li->tot_gap_zp_share + $li->tot_gap_ap_share + $li->tot_gap_gp_share;
            $bid_c = $li->f_emd_zp_share + $li->f_emd_ap_share + $li->f_emd_gp_share +
                $li->df_zp_share + $li->df_ap_share + $li->df_gp_share +
                $li->tot_ins_collected_amt;

            $zp_share = $li->f_emd_zp_share + $li->df_zp_share + $li->tot_ins_zp_share + $li->tot_gap_zp_share;
            $ap_share = $li->f_emd_ap_share + $li->df_ap_share + $li->tot_ins_ap_share + $li->tot_gap_ap_share;
            $gp_share = $li->f_emd_gp_share + $li->df_gp_share + $li->tot_ins_gp_share + $li->tot_gap_gp_share;

            $finalAssetArray[$li->anchalik_id] = [
                'gap_c' => $gap_c,
                'bid_c' => $bid_c,
                "zp_share" => $zp_share,
                "ap_share" => $ap_share,
                "gp_share" => $gp_share
            ];
        }

        // ASSET

        $zpShareToAp_asset = OsrNonTaxAssetDisApShare::getZpShareToApList($id, $fy_id);
        $gpsShareToAp_asset = OsrNonTaxAssetDisApShare::getGpsShareToApList($id, $fy_id);

        //OTHER ASSET

        $zpShareToAp_other_asset = OsrNonTaxOtherAssetDisApShare::getZpShareToApList($id, $fy_id);
        $gpsShareToAp_other_asset = OsrNonTaxOtherAssetDisApShare::getGpsShareToApList($id, $fy_id);


        $finalOtherAssetArray = OsrNonTaxOtherAssetFinalRecord::getYrApRevenueList($id, $fy_id);

        $apList = AnchalikParishad::getAPsByZilaId($id);

        foreach ($apList as $ap) {
            $gap_c = 0;
            $bid_c = 0;
            $other_c = 0;
            $tot_collection = 0;

            $zp_share = 0;
            $ap_share = 0;
            $gp_share = 0;

            $zp_share_to_ap = 0;
            $gps_share_to_ap = 0;

            $tot_a_b = 0;

            if (isset($finalAssetArray[$ap->id])) {
                $gap_c = $finalAssetArray[$ap->id]['gap_c'];
                $bid_c = $finalAssetArray[$ap->id]['bid_c'];

                $zp_share = $zp_share + $finalAssetArray[$ap->id]['zp_share'];
                $ap_share = $ap_share + $finalAssetArray[$ap->id]['ap_share'];
                $gp_share = $gp_share + $finalAssetArray[$ap->id]['gp_share'];
            }

            if (isset($finalOtherAssetArray[$ap->id])) {
                $other_c = $finalOtherAssetArray[$ap->id]['other_c'];

                $zp_share = $zp_share + $finalOtherAssetArray[$ap->id]['zp_share'];
                $ap_share = $ap_share + $finalOtherAssetArray[$ap->id]['ap_share'];
                $gp_share = $gp_share + $finalOtherAssetArray[$ap->id]['gp_share'];
            }

            //ASSET

            if (isset($zpShareToAp_asset[$ap->id])) {
                $zp_share_to_ap = $zpShareToAp_asset[$ap->id];
            }

            if (isset($gpsShareToAp_asset[$ap->id])) {
                $gps_share_to_ap = $gpsShareToAp_asset[$ap->id];
            }

            //OTHER ASSET

            if (isset($zpShareToAp_other_asset[$ap->id])) {
                $zp_share_to_ap = $zp_share_to_ap + $zpShareToAp_other_asset[$ap->id];
            }

            if (isset($gpsShareToAp_other_asset[$ap->id])) {
                $gps_share_to_ap = $gps_share_to_ap + $gpsShareToAp_other_asset[$ap->id];
            }

            $tot_collection = $gap_c + $bid_c + $other_c;

            $tot_a_b = $ap_share + $zp_share_to_ap + $gps_share_to_ap;

            $finalArray[$ap->id] = [
                'gap_c' => ConfigMdas::cur_format($gap_c),
                'bid_c' => ConfigMdas::cur_format($bid_c),
                'other_c' => ConfigMdas::cur_format($other_c),
                'tot_c' => ConfigMdas::cur_format($tot_collection),
                'zp_share' => ConfigMdas::cur_format($zp_share),
                'gp_share' => ConfigMdas::cur_format($gp_share),
                'zp_share_to_ap' => ConfigMdas::cur_format($zp_share_to_ap),
                'gps_share_to_ap' => ConfigMdas::cur_format($gps_share_to_ap),
                'tot_a_b' => ConfigMdas::cur_format($tot_a_b),
            ];
        }

        //echo json_encode($finalArray);

        return $finalArray;
    }

    //SHARE

    public static function getYrZpShareData($id, $fy_id)
    {
        $finalArray = [];
        $finalAssetArray = [];
        $finalOtherAssetArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.level', '=', 'ZP'],
                ['a_short.zp_id', '=', $id],
            ])->select(DB::raw('
							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,
							sum(osr_non_tax_asset_final_records.tot_gap_collected_amt) AS tot_gap_collected_amt,

                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,

                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '), 'a_short.zp_id as z_id')
            ->groupBy('a_short.zp_id')
            ->get();

        foreach ($data as $li) {

            $zp_share = $li->f_emd_zp_share + $li->df_zp_share + $li->tot_ins_zp_share + $li->tot_gap_zp_share;
            $ap_share = $li->f_emd_ap_share + $li->df_ap_share + $li->tot_ins_ap_share + $li->tot_gap_ap_share;
            $gp_share = $li->f_emd_gp_share + $li->df_gp_share + $li->tot_ins_gp_share + $li->tot_gap_gp_share;

            $total_revenue_collection = $li->tot_ins_collected_amt + $li->tot_gap_collected_amt;

            $finalAssetArray[$li->z_id] = [
                'tot_r_c' => $total_revenue_collection,
                'zp_share' => $zp_share,
                'ap_share' => $ap_share,
                'gp_share' => $gp_share
            ];
        }

        $finalOtherAssetArray = OsrNonTaxOtherAssetFinalRecord::getYrZpShareData($id, $fy_id);


        $zp_share = 0;
        $ap_share = 0;
        $gp_share = 0;
        $total_revenue_collection = 0;

        if (isset($finalAssetArray[$id])) {
            $zp_share = $finalAssetArray[$id]['zp_share'];
            $ap_share = $finalAssetArray[$id]['ap_share'];
            $gp_share = $finalAssetArray[$id]['gp_share'];

            $total_revenue_collection = $finalAssetArray[$id]['tot_r_c'];
        }

        if (isset($finalOtherAssetArray[$id])) {
            $zp_share = $zp_share + $finalOtherAssetArray[$id]['zp_share'];
            $ap_share = $ap_share + $finalOtherAssetArray[$id]['ap_share'];
            $gp_share = $gp_share + $finalOtherAssetArray[$id]['gp_share'];

            $total_revenue_collection = $total_revenue_collection + $finalOtherAssetArray[$id]['tot_r_c'];
        }

        $finalArray[$id] = [
            'tot_r_c' => ConfigMdas::cur_format($total_revenue_collection),
            'zp_share' => ConfigMdas::cur_format($zp_share),
            'ap_share' => ConfigMdas::cur_format($ap_share),
            'gp_share' => ConfigMdas::cur_format($gp_share)
        ];


        return $finalArray;
    }

    public static function getYrApShareList($id, $fy_id)
    {

        $finalArray = [];
        $finalAssetArray = [];
        $finalOtherAssetArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.level', '=', 'AP'],
                ['a_short.zp_id', '=', $id],
            ])->select(DB::raw('
							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,
							sum(osr_non_tax_asset_final_records.tot_gap_collected_amt) AS tot_gap_collected_amt,

                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,

                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '), 'a_short.ap_id as anchalik_id')
            ->groupBy('a_short.ap_id')
            ->get();

        foreach ($data as $li) {

            $zp_share = $li->f_emd_zp_share + $li->df_zp_share + $li->tot_ins_zp_share + $li->tot_gap_zp_share;
            $ap_share = $li->f_emd_ap_share + $li->df_ap_share + $li->tot_ins_ap_share + $li->tot_gap_ap_share;
            $gp_share = $li->f_emd_gp_share + $li->df_gp_share + $li->tot_ins_gp_share + $li->tot_gap_gp_share;

            $total_revenue_collection = $li->tot_ins_collected_amt + $li->tot_gap_collected_amt;

            $finalAssetArray[$li->anchalik_id] = [
                'tot_r_c' => $total_revenue_collection,
                'zp_share' => $zp_share,
                'ap_share' => $ap_share,
                'gp_share' => $gp_share
            ];
        }

        $finalOtherAssetArray = OsrNonTaxOtherAssetFinalRecord::getYrApShareList($id, $fy_id);

        $apList = AnchalikParishad::getAPsByZilaId($id);

        foreach ($apList as $ap) {
            $zp_share = 0;
            $ap_share = 0;
            $gp_share = 0;

            $total_revenue_collection = 0;

            if (isset($finalAssetArray[$ap->id])) {
                $zp_share = $finalAssetArray[$ap->id]['zp_share'];
                $ap_share = $finalAssetArray[$ap->id]['ap_share'];
                $gp_share = $finalAssetArray[$ap->id]['gp_share'];

                $total_revenue_collection = $finalAssetArray[$ap->id]['tot_r_c'];
            }

            if (isset($finalOtherAssetArray[$ap->id])) {
                $zp_share = $zp_share + $finalOtherAssetArray[$ap->id]['zp_share'];
                $ap_share = $ap_share + $finalOtherAssetArray[$ap->id]['ap_share'];
                $gp_share = $gp_share + $finalOtherAssetArray[$ap->id]['gp_share'];

                $total_revenue_collection = $total_revenue_collection + $finalOtherAssetArray[$ap->id]['tot_r_c'];
            }

            $finalArray[$ap->id] = [
                'tot_r_c' => ConfigMdas::cur_format($total_revenue_collection),
                'zp_share' => ConfigMdas::cur_format($zp_share),
                'ap_share' => ConfigMdas::cur_format($ap_share),
                'gp_share' => ConfigMdas::cur_format($gp_share)
            ];
        }

        return $finalArray;
    }


    //---------------- GP LEVEL ----------------------------------------------------------------------------------------

    //SETTLEMENT
    public static function apTotalAsset($id, $fy_id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->where([
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.ap_id', '=', $id],
                ['osr_non_tax_asset_shortlists.level', '=', 'AP']
            ])
            ->select(DB::raw('count(*) AS total'), 'a_entries.anchalik_id as ap_id')
            ->groupBy('a_entries.anchalik_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->ap_id] = $li->total;
        }

        return $finalArray;
    }

    public static function apSettledAsset($id, $fy_id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['a_short.level', '=', 'AP'],
                ['a_short.ap_id', '=', $id]
            ])
            ->select(DB::raw('count(*) AS total'), 'a_short.ap_id as anchalik_id')
            ->groupBy('a_short.ap_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->anchalik_id] = $li->total;
        }

        return $finalArray;
    }

    public static function gpWiseTotalAsset($fy_id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->where([
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'GP']
            ])
            ->select(DB::raw('count(*) AS total'), 'a_entries.gram_panchayat_id as gp_id')
            ->groupBy('a_entries.gram_panchayat_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->gp_id] = $li->total;
        }

        return $finalArray;
    }

    public static function gpYrWiseSettledAssetCount($fy_id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['a_short.level', '=', 'GP']
            ])
            ->select(DB::raw('count(*) AS total'), 'a_short.gp_id as gram_panchayat_id')
            ->groupBy('a_short.gp_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->gram_panchayat_id] = $li->total;
        }

        return $finalArray;
    }

    public static function gpBranchWiseTotalAsset($fy_id, $id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->where([
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['a_entries.gram_panchayat_id', '=', $id],
                ['osr_non_tax_asset_shortlists.level', '=', 'GP'],
            ])
            ->select(DB::raw('count(*) AS total'), 'a_entries.osr_asset_branch_id as branch_id')
            ->groupBy('a_entries.osr_asset_branch_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->branch_id] = $li->total;
        }

        return $finalArray;
    }

    //DEFAULTER
    public static function apDefaulter($id, $fy_id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['osr_non_tax_asset_final_records.defaulter_status', '=', 1],
                ['a_short.ap_id', '=', $id],
                ['a_short.level', '=', 'AP'],
            ])
            ->select(DB::raw('count(*) AS total'), 'a_short.ap_id as anchalik_id')
            ->groupBy('a_short.ap_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->anchalik_id] = $li->total;
        }

        return $finalArray;
    }

    public static function gpYrWiseDefaulterCount($fy_id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['osr_non_tax_asset_final_records.defaulter_status', '=', 1],
                ['a_short.level', '=', 'GP']
            ])
            ->select(DB::raw('count(*) AS total'), 'a_short.gp_id as gram_panchayat_id')
            ->groupBy('a_short.gp_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->gram_panchayat_id] = $li->total;
        }

        return $finalArray;
    }

    public static function gpBranchWiseSettledAssetCount($fy_id, $id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['a_short.gp_id', '=', $id],
                ['a_short.level', '=', 'GP']
            ])
            ->select(DB::raw('count(*) AS total'), 'a_short.osr_master_non_tax_branch_id as branch_id')
            ->groupBy('a_short.osr_master_non_tax_branch_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->branch_id] = $li->total;
        }

        return $finalArray;

    }

    public static function gpBranchWiseDefaulterCount($fy_id, $id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['osr_non_tax_asset_final_records.defaulter_status', '=', 1],
                ['a_short.gp_id', '=', $id],
                ['a_short.level', '=', 'GP']
            ])
            ->select(DB::raw('count(*) AS total'), 'a_short.osr_master_non_tax_branch_id as branch_id')
            ->groupBy('a_short.osr_master_non_tax_branch_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->branch_id] = $li->total;

        }

        return $finalArray;
    }

    //REVENUE
    public static function getYrApRevenueData($id, $ap_id, $fy_id)
    {
        $finalArray = [];
        $finalAssetArray = [];
        $finalOtherAssetArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'AP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.ap_id', '=', $ap_id],
            ])
            ->select(DB::raw('
                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,

                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,

							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,

                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '), 'a_short.ap_id as anchalik_id')
            ->groupBy('a_short.ap_id')
            ->get();

        foreach ($data as $li) {

            $gap_c = $li->tot_gap_zp_share + $li->tot_gap_ap_share + $li->tot_gap_gp_share;
            $bid_c = $li->f_emd_zp_share + $li->f_emd_ap_share + $li->f_emd_gp_share +
                $li->df_zp_share + $li->df_ap_share + $li->df_gp_share +
                $li->tot_ins_collected_amt;

            $zp_share = $li->f_emd_zp_share + $li->df_zp_share + $li->tot_ins_zp_share + $li->tot_gap_zp_share;
            $ap_share = $li->f_emd_ap_share + $li->df_ap_share + $li->tot_ins_ap_share + $li->tot_gap_ap_share;
            $gp_share = $li->f_emd_gp_share + $li->df_gp_share + $li->tot_ins_gp_share + $li->tot_gap_gp_share;

            $finalAssetArray[$li->anchalik_id] = [
                'gap_c' => $gap_c,
                'bid_c' => $bid_c,
                "zp_share" => $zp_share,
                "ap_share" => $ap_share,
                "gp_share" => $gp_share
            ];
        }

        // ASSET

        $zpShareToAp_asset = OsrNonTaxAssetDisApShare::getZpShareToAp($id, $ap_id, $fy_id);
        $gpsShareToAp_asset = OsrNonTaxAssetDisApShare::getGpsShareToAp($id, $ap_id, $fy_id);

        //OTHER ASSET

        $zpShareToAp_other_asset = OsrNonTaxOtherAssetDisApShare::getZpShareToAp($id, $ap_id, $fy_id);
        $gpsShareToAp_other_asset = OsrNonTaxOtherAssetDisApShare::getGpsShareToAp($id, $ap_id, $fy_id);


        $finalOtherAssetArray = OsrNonTaxOtherAssetFinalRecord::getYrApRevenueData($id, $ap_id, $fy_id);

        $gap_c = 0;
        $bid_c = 0;
        $other_c = 0;
        $tot_collection = 0;

        $zp_share = 0;
        $ap_share = 0;
        $gp_share = 0;

        $zp_share_to_ap = 0;
        $gps_share_to_ap = 0;

        $tot_a_b = 0;


        if (isset($finalAssetArray[$ap_id])) {
            $gap_c = $finalAssetArray[$ap_id]['gap_c'];
            $bid_c = $finalAssetArray[$ap_id]['bid_c'];

            $zp_share = $zp_share + $finalAssetArray[$ap_id]['zp_share'];
            $ap_share = $ap_share + $finalAssetArray[$ap_id]['ap_share'];
            $gp_share = $gp_share + $finalAssetArray[$ap_id]['gp_share'];

        }

        if (isset($finalOtherAssetArray[$ap_id])) {
            $other_c = $finalOtherAssetArray[$ap_id]['other_c'];

            $zp_share = $zp_share + $finalOtherAssetArray[$ap_id]['zp_share'];
            $ap_share = $ap_share + $finalOtherAssetArray[$ap_id]['ap_share'];
            $gp_share = $gp_share + $finalOtherAssetArray[$ap_id]['gp_share'];
        }

        //ASSET

        if (isset($zpShareToAp_asset[$ap_id])) {
            $zp_share_to_ap = $zpShareToAp_asset[$ap_id];
        }

        if (isset($gpsShareToAp_asset[$ap_id])) {
            $gps_share_to_ap = $gpsShareToAp_asset[$ap_id];
        }

        //OTHER ASSET

        if (isset($zpShareToAp_other_asset[$ap_id])) {
            $zp_share_to_ap = $zp_share_to_ap + $zpShareToAp_other_asset[$ap_id];
        }

        if (isset($gpsShareToAp_other_asset[$ap_id])) {
            $gps_share_to_ap = $gps_share_to_ap + $gpsShareToAp_other_asset[$ap_id];
        }

        $tot_collection = $gap_c + $bid_c + $other_c;

        $tot_a_b = $ap_share + $zp_share_to_ap + $gps_share_to_ap;

        $finalArray[$ap_id] = [
            'gap_c' => ConfigMdas::cur_format($gap_c),
            'bid_c' => ConfigMdas::cur_format($bid_c),
            'other_c' => ConfigMdas::cur_format($other_c),
            'tot_c' => ConfigMdas::cur_format($tot_collection),
            'zp_share' => ConfigMdas::cur_format($zp_share),
            'gp_share' => ConfigMdas::cur_format($gp_share),
            'zp_share_to_ap' => ConfigMdas::cur_format($zp_share_to_ap),
            'gps_share_to_ap' => ConfigMdas::cur_format($gps_share_to_ap),
            'tot_a_b' => ConfigMdas::cur_format($tot_a_b),
        ];

        return $finalArray;
    }

    public static function getYrGpRevenueList($id, $ap_id, $fy_id)
    {
        $finalArray = [];
        $finalAssetArray = [];
        $finalOtherAssetArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'GP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.ap_id', '=', $ap_id],
            ])
            ->select(DB::raw('
                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,

                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,

							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,

                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '), 'a_short.gp_id as gram_panchayat_id')
            ->groupBy('a_short.gp_id')
            ->get();

        foreach ($data as $li) {

            $gap_c = $li->tot_gap_zp_share + $li->tot_gap_ap_share + $li->tot_gap_gp_share;
            $bid_c = $li->f_emd_zp_share + $li->f_emd_ap_share + $li->f_emd_gp_share +
                $li->df_zp_share + $li->df_ap_share + $li->df_gp_share +
                $li->tot_ins_collected_amt;

            $zp_share = $li->f_emd_zp_share + $li->df_zp_share + $li->tot_ins_zp_share + $li->tot_gap_zp_share;
            $ap_share = $li->f_emd_ap_share + $li->df_ap_share + $li->tot_ins_ap_share + $li->tot_gap_ap_share;
            $gp_share = $li->f_emd_gp_share + $li->df_gp_share + $li->tot_ins_gp_share + $li->tot_gap_gp_share;

            $finalAssetArray[$li->gram_panchayat_id] = [
                'gap_c' => $gap_c,
                'bid_c' => $bid_c,
                "zp_share" => $zp_share,
                "ap_share" => $ap_share,
                "gp_share" => $gp_share
            ];
        }

        // ASSET

        $zpShareToGp_asset = OsrNonTaxAssetDisGpShare::getZpShareToGpList($id, $ap_id, $fy_id);
        $apShareToGp_asset = OsrNonTaxAssetDisGpShare::getApShareToGpList($id, $ap_id, $fy_id);

        //OTHER ASSET

        $zpShareToGp_other_asset = OsrNonTaxOtherAssetDisGpShare::getZpShareToGpList($id, $ap_id, $fy_id);
        $apShareToGp_other_asset = OsrNonTaxOtherAssetDisGpShare::getApShareToGpList($id, $ap_id, $fy_id);

        //echo json_encode($apShareToGp_other_asset);
        $finalOtherAssetArray = OsrNonTaxOtherAssetFinalRecord::getYrGpRevenueList($id, $ap_id, $fy_id);

        $gpList = GramPanchyat::getGpsByAnchalikId($ap_id);

        foreach ($gpList as $gp) {
            $gap_c = 0;
            $bid_c = 0;
            $other_c = 0;
            $tot_collection = 0;

            $zp_share = 0;
            $ap_share = 0;
            $gp_share = 0;

            $zp_share_to_gp = 0;
            $ap_share_to_gp = 0;

            $tot_a_b = 0;


            if (isset($finalAssetArray[$gp->id])) {
                $gap_c = $finalAssetArray[$gp->id]['gap_c'];
                $bid_c = $finalAssetArray[$gp->id]['bid_c'];

                $zp_share = $zp_share + $finalAssetArray[$gp->id]['zp_share'];
                $ap_share = $ap_share + $finalAssetArray[$gp->id]['ap_share'];
                $gp_share = $gp_share + $finalAssetArray[$gp->id]['gp_share'];
            }

            if (isset($finalOtherAssetArray[$gp->id])) {
                $other_c = $finalOtherAssetArray[$gp->id]['other_c'];

                $zp_share = $zp_share + $finalOtherAssetArray[$gp->id]['zp_share'];
                $ap_share = $ap_share + $finalOtherAssetArray[$gp->id]['ap_share'];
                $gp_share = $gp_share + $finalOtherAssetArray[$gp->id]['gp_share'];
            }

            //ASSET

            if (isset($zpShareToGp_asset[$gp->id])) {
                $zp_share_to_gp = $zpShareToGp_asset[$gp->id];
            }

            if (isset($apShareToGp_asset[$gp->id])) {
                $ap_share_to_gp = $apShareToGp_asset[$gp->id];
            }

            //OTHER ASSET

            if (isset($zpShareToGp_other_asset[$gp->id])) {
                $zp_share_to_gp = $zp_share_to_gp + $zpShareToGp_other_asset[$gp->id];
            }

            if (isset($apShareToGp_other_asset[$gp->id])) {
                $ap_share_to_gp = $ap_share_to_gp + $apShareToGp_other_asset[$gp->id];
            }

            $tot_collection = $gap_c + $bid_c + $other_c;

            $tot_a_b = $gp_share + $zp_share_to_gp + $ap_share_to_gp;

            $finalArray[$gp->id] = [
                'gap_c' => ConfigMdas::cur_format($gap_c),
                'bid_c' => ConfigMdas::cur_format($bid_c),
                'other_c' => ConfigMdas::cur_format($other_c),
                'tot_c' => ConfigMdas::cur_format($tot_collection),
                'zp_share' => ConfigMdas::cur_format($zp_share),
                'ap_share' => ConfigMdas::cur_format($ap_share),
                'zp_share_to_gp' => ConfigMdas::cur_format($zp_share_to_gp),
                'ap_share_to_gp' => ConfigMdas::cur_format($ap_share_to_gp),
                'tot_a_b' => ConfigMdas::cur_format($tot_a_b),
            ];
        }

        //echo json_encode($finalArray);

        return $finalArray;
    }

    //SHARE
    public static function getYrApShareData($id, $ap_id, $fy_id)
    {
        $finalArray = [];
        $finalAssetArray = [];
        $finalOtherAssetArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'AP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.ap_id', '=', $ap_id],
            ])
            ->select(DB::raw('
							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,
							sum(osr_non_tax_asset_final_records.tot_gap_collected_amt) AS tot_gap_collected_amt,

                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,

                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '), 'a_short.ap_id as anchalik_id')
            ->groupBy('a_short.ap_id')
            ->get();

        foreach ($data as $li) {

            $zp_share = $li->f_emd_zp_share + $li->df_zp_share + $li->tot_ins_zp_share + $li->tot_gap_zp_share;
            $ap_share = $li->f_emd_ap_share + $li->df_ap_share + $li->tot_ins_ap_share + $li->tot_gap_ap_share;
            $gp_share = $li->f_emd_gp_share + $li->df_gp_share + $li->tot_ins_gp_share + $li->tot_gap_gp_share;

            $total_revenue_collection = $li->tot_ins_collected_amt + $li->tot_gap_collected_amt;

            $finalAssetArray[$li->anchalik_id] = [
                'tot_r_c' => $total_revenue_collection,
                'zp_share' => $zp_share,
                'ap_share' => $ap_share,
                'gp_share' => $gp_share
            ];
        }


        $finalOtherAssetArray = OsrNonTaxOtherAssetFinalRecord::getYrApShareData($id, $ap_id, $fy_id);

        $zp_share = 0;
        $ap_share = 0;
        $gp_share = 0;
        $total_revenue_collection = 0;

        if (isset($finalAssetArray[$ap_id])) {
            $zp_share = $finalAssetArray[$ap_id]['zp_share'];
            $ap_share = $finalAssetArray[$ap_id]['ap_share'];
            $gp_share = $finalAssetArray[$ap_id]['gp_share'];

            $total_revenue_collection = $finalAssetArray[$ap_id]['tot_r_c'];
        }

        if (isset($finalOtherAssetArray[$ap_id])) {
            $zp_share = $zp_share + $finalOtherAssetArray[$ap_id]['zp_share'];
            $ap_share = $ap_share + $finalOtherAssetArray[$ap_id]['ap_share'];
            $gp_share = $gp_share + $finalOtherAssetArray[$ap_id]['gp_share'];

            $total_revenue_collection = $total_revenue_collection + $finalOtherAssetArray[$ap_id]['tot_r_c'];
        }

        $finalArray[$ap_id] = [
            'tot_r_c' => ConfigMdas::cur_format($total_revenue_collection),
            'zp_share' => ConfigMdas::cur_format($zp_share),
            'ap_share' => ConfigMdas::cur_format($ap_share),
            'gp_share' => ConfigMdas::cur_format($gp_share)
        ];

        return $finalArray;
    }

    public static function getYrGpShareList($id, $ap_id, $fy_id)
    {
        $finalArray = [];
        $finalAssetArray = [];
        $finalOtherAssetArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'GP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.ap_id', '=', $ap_id],
            ])
            ->select(DB::raw('
							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,
							sum(osr_non_tax_asset_final_records.tot_gap_collected_amt) AS tot_gap_collected_amt,

                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,

                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '), 'a_short.gp_id as gram_panchayat_id')
            ->groupBy('a_short.gp_id')
            ->get();

        foreach ($data as $li) {

            $zp_share = $li->f_emd_zp_share + $li->df_zp_share + $li->tot_ins_zp_share + $li->tot_gap_zp_share;
            $ap_share = $li->f_emd_ap_share + $li->df_ap_share + $li->tot_ins_ap_share + $li->tot_gap_ap_share;
            $gp_share = $li->f_emd_gp_share + $li->df_gp_share + $li->tot_ins_gp_share + $li->tot_gap_gp_share;

            $total_revenue_collection = $li->tot_ins_collected_amt + $li->tot_gap_collected_amt;

            $finalAssetArray[$li->gram_panchayat_id] = [
                'tot_r_c' => $total_revenue_collection,
                'zp_share' => $zp_share,
                'ap_share' => $ap_share,
                'gp_share' => $gp_share
            ];
        }

        $finalOtherAssetArray = OsrNonTaxOtherAssetFinalRecord::getYrGpShareList($id, $ap_id, $fy_id);

        $gpList = GramPanchyat::getGpsByAnchalikId($ap_id);

        foreach ($gpList as $gp) {
            $zp_share = 0;
            $ap_share = 0;
            $gp_share = 0;

            $total_revenue_collection = 0;

            if (isset($finalAssetArray[$gp->id])) {
                $zp_share = $finalAssetArray[$gp->id]['zp_share'];
                $ap_share = $finalAssetArray[$gp->id]['ap_share'];
                $gp_share = $finalAssetArray[$gp->id]['gp_share'];

                $total_revenue_collection = $finalAssetArray[$gp->id]['tot_r_c'];
            }

            if (isset($finalOtherAssetArray[$gp->id])) {
                $zp_share = $zp_share + $finalOtherAssetArray[$gp->id]['zp_share'];
                $ap_share = $ap_share + $finalOtherAssetArray[$gp->id]['ap_share'];
                $gp_share = $gp_share + $finalOtherAssetArray[$gp->id]['gp_share'];

                $total_revenue_collection = $total_revenue_collection + $finalOtherAssetArray[$gp->id]['tot_r_c'];
            }

            $finalArray[$gp->id] = [
                'tot_r_c' => ConfigMdas::cur_format($total_revenue_collection),
                'zp_share' => ConfigMdas::cur_format($zp_share),
                'ap_share' => ConfigMdas::cur_format($ap_share),
                'gp_share' => ConfigMdas::cur_format($gp_share)
            ];
        }

        //echo json_encode($finalArray);

        return $finalArray;
    }


    //---------------- BRANCH LIST -------------------------------------------------------------------------------------
    //REVENUE
    //ZP
    public static function getZpRevenueBranchList($id, $fy_id, $list)
    {
        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.level', '=', 'ZP'],
                ['a_short.zp_id', '=', $id],
            ])
            ->select(DB::raw('
							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,

                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,

                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '), 'a_short.osr_master_non_tax_branch_id as b_id')
            ->groupBy('a_short.osr_master_non_tax_branch_id')
            ->get();

        foreach ($data as $li) {

            $gap_c = $li->tot_gap_zp_share + $li->tot_gap_ap_share + $li->tot_gap_gp_share;
            $bid_c = $li->f_emd_zp_share + $li->f_emd_ap_share + $li->f_emd_gp_share +
                $li->df_zp_share + $li->df_ap_share + $li->df_gp_share +
                $li->tot_ins_collected_amt;

            $finalArray[$li->b_id] = [
                'gap_c' => ConfigMdas::cur_format($gap_c),
                'bid_c' => ConfigMdas::cur_format($bid_c)
            ];

        }

        foreach ($list as $li) {
            if (!isset($finalArray[$li->id])) {
                $finalArray[$li->id] = [
                    'gap_c' => 0,
                    'bid_c' => 0
                ];
            }
        }

        return $finalArray;
    }
    //AP
    public static function getApRevenueBranchList($id, $ap_id, $fy_id, $list)
    {
        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'AP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.ap_id', '=', $ap_id],
            ])
            ->select(DB::raw('
							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,

                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,

                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '), 'a_short.osr_master_non_tax_branch_id as b_id')
            ->groupBy('a_short.osr_master_non_tax_branch_id')
            ->get();

        foreach ($data as $li) {

            $gap_c = $li->tot_gap_zp_share + $li->tot_gap_ap_share + $li->tot_gap_gp_share;
            $bid_c = $li->f_emd_zp_share + $li->f_emd_ap_share + $li->f_emd_gp_share +
                $li->df_zp_share + $li->df_ap_share + $li->df_gp_share +
                $li->tot_ins_collected_amt;

            $finalArray[$li->b_id] = [
                'gap_c' => ConfigMdas::cur_format($gap_c),
                'bid_c' => ConfigMdas::cur_format($bid_c)
            ];
        }

        foreach ($list as $li) {
            if (!isset($finalArray[$li->id])) {
                $finalArray[$li->id] = [
                    'gap_c' => 0,
                    'bid_c' => 0
                ];
            }
        }

        return $finalArray;
    }
    //GP
    public static function getGpRevenueBranchList($id, $ap_id, $gp_id, $fy_id, $list)
    {
        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'GP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.ap_id', '=', $ap_id],
                ['a_short.gp_id', '=', $gp_id],
            ])
            ->select(DB::raw('
							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,

                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,

                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '), 'a_short.osr_master_non_tax_branch_id as b_id')
            ->groupBy('a_short.osr_master_non_tax_branch_id')
            ->get();

        foreach ($data as $li) {

            $gap_c = $li->tot_gap_zp_share + $li->tot_gap_ap_share + $li->tot_gap_gp_share;
            $bid_c = $li->f_emd_zp_share + $li->f_emd_ap_share + $li->f_emd_gp_share +
                $li->df_zp_share + $li->df_ap_share + $li->df_gp_share +
                $li->tot_ins_collected_amt;

            $finalArray[$li->b_id] = [
                'gap_c' => ConfigMdas::cur_format($gap_c),
                'bid_c' => ConfigMdas::cur_format($bid_c)
            ];
        }

        foreach ($list as $li) {
            if (!isset($finalArray[$li->id])) {
                $finalArray[$li->id] = [
                    'gap_c' => 0,
                    'bid_c' => 0
                ];
            }
        }

        return $finalArray;
    }

    //SHARE
    //ZP
    public static function getZpShareBranchList($id, $fy_id, $list)
    {
        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'ZP'],
                ['a_short.zp_id', '=', $id],
            ])->select(DB::raw('
							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,
							sum(osr_non_tax_asset_final_records.tot_gap_collected_amt) AS tot_gap_collected_amt,

                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,

                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '), 'a_short.osr_master_non_tax_branch_id as b_id')
            ->groupBy('a_short.osr_master_non_tax_branch_id')
            ->get();

        foreach ($data as $li) {

            $zp_share = $li->f_emd_zp_share + $li->df_zp_share + $li->tot_ins_zp_share + $li->tot_gap_zp_share;
            $ap_share = $li->f_emd_ap_share + $li->df_ap_share + $li->tot_ins_ap_share + $li->tot_gap_ap_share;
            $gp_share = $li->f_emd_gp_share + $li->df_gp_share + $li->tot_ins_gp_share + $li->tot_gap_gp_share;

            $total_revenue_collection = $li->tot_ins_collected_amt + $li->tot_gap_collected_amt;

            $finalArray[$li->b_id] = [
                'tot_r_c' => ConfigMdas::cur_format($total_revenue_collection),
                'zp_share' => ConfigMdas::cur_format($zp_share),
                'ap_share' => ConfigMdas::cur_format($ap_share),
                'gp_share' => ConfigMdas::cur_format($gp_share)
            ];
        }

        foreach ($list as $li) {
            if (!isset($finalArray[$li->id])) {
                $finalArray[$li->id] = [
                    'tot_r_c' => 0,
                    'zp_share' => 0,
                    'ap_share' => 0,
                    'gp_share' => 0
                ];
            }
        }


        return $finalArray;
    }
    //AP
    public static function getApShareBranchList($id, $ap_id, $fy_id, $list)
    {
        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'AP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.ap_id', '=', $ap_id],
            ])
            ->select(DB::raw('
							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,
							sum(osr_non_tax_asset_final_records.tot_gap_collected_amt) AS tot_gap_collected_amt,

                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,

                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '), 'a_short.osr_master_non_tax_branch_id as b_id')
            ->groupBy('a_short.osr_master_non_tax_branch_id')
            ->get();

        foreach ($data as $li) {

            $zp_share = $li->f_emd_zp_share + $li->df_zp_share + $li->tot_ins_zp_share + $li->tot_gap_zp_share;
            $ap_share = $li->f_emd_ap_share + $li->df_ap_share + $li->tot_ins_ap_share + $li->tot_gap_ap_share;
            $gp_share = $li->f_emd_gp_share + $li->df_gp_share + $li->tot_ins_gp_share + $li->tot_gap_gp_share;

            $total_revenue_collection = $li->tot_ins_collected_amt + $li->tot_gap_collected_amt;

            $finalArray[$li->b_id] = [
                'tot_r_c' => ConfigMdas::cur_format($total_revenue_collection),
                'zp_share' => ConfigMdas::cur_format($zp_share),
                'ap_share' => ConfigMdas::cur_format($ap_share),
                'gp_share' => ConfigMdas::cur_format($gp_share)
            ];
        }

        foreach ($list as $li) {
            if (!isset($finalArray[$li->id])) {
                $finalArray[$li->id] = [
                    'tot_r_c' => 0,
                    'zp_share' => 0,
                    'ap_share' => 0,
                    'gp_share' => 0
                ];
            }
        }

        return $finalArray;
    }
    //GP
    public static function getGpShareBranchList($id, $ap_id, $gp_id, $fy_id, $list)
    {
        $finalArray = [];

        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'GP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.ap_id', '=', $ap_id],
                ['a_short.gp_id', '=', $gp_id],
            ])
            ->select(DB::raw('
							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,
							sum(osr_non_tax_asset_final_records.tot_gap_collected_amt) AS tot_gap_collected_amt,

                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,

                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,

                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '), 'a_short.osr_master_non_tax_branch_id as b_id')
            ->groupBy('a_short.osr_master_non_tax_branch_id')
            ->get();

        foreach ($data as $li) {

            $zp_share = $li->f_emd_zp_share + $li->df_zp_share + $li->tot_ins_zp_share + $li->tot_gap_zp_share;
            $ap_share = $li->f_emd_ap_share + $li->df_ap_share + $li->tot_ins_ap_share + $li->tot_gap_ap_share;
            $gp_share = $li->f_emd_gp_share + $li->df_gp_share + $li->tot_ins_gp_share + $li->tot_gap_gp_share;

            $total_revenue_collection = $li->tot_ins_collected_amt + $li->tot_gap_collected_amt;

            $finalArray[$li->b_id] = [
                'tot_r_c' => ConfigMdas::cur_format($total_revenue_collection),
                'zp_share' => ConfigMdas::cur_format($zp_share),
                'ap_share' => ConfigMdas::cur_format($ap_share),
                'gp_share' => ConfigMdas::cur_format($gp_share)
            ];
        }

        foreach ($list as $li) {
            if (!isset($finalArray[$li->id])) {
                $finalArray[$li->id] = [
                    'tot_r_c' => 0,
                    'zp_share' => 0,
                    'ap_share' => 0,
                    'gp_share' => 0
                ];
            }
        }

        return $finalArray;
    }


    //===============================STATE ADMIN REPORT=======================================

    public static function levelWiseSettlementData($fy_id, $level)
    {
        $finalArray = [];
        $finalAssetArray = [];

        if ($level == "ALL") {
            $whereArray = [
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                //['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['a_short.level', '!=', 'NA'],
            ];
        } else {
            $whereArray = [
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.level', '=', $level],
                //['osr_non_tax_asset_final_records.bidding_status', '=', 1],
            ];
        }

        $data = OsrNonTaxAssetFinalRecord::where($whereArray)
            ->select(DB::raw('
                sum(osr_non_tax_asset_final_records.settlement_amt) AS settlement_amt,
                sum(osr_non_tax_asset_final_records.tot_gap_collected_amt) AS tot_gap_collected_amt,
                sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt, 
                osr_non_tax_asset_entries.zila_id as z_id
                '))

            ->join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->join('osr_non_tax_asset_entries', 'a_short.asset_code', '=', 'osr_non_tax_asset_entries.asset_code')
            ->join('zila_parishads', 'osr_non_tax_asset_entries.zila_id', '=', 'zila_parishads.id')
            ->groupBy('osr_non_tax_asset_entries.zila_id')
            ->get();


        foreach ($data as $li) {
            $settlement_c = $li->settlement_amt;
            $gap_c = $li->tot_gap_collected_amt;
            $bid_c = $li->tot_ins_collected_amt;

            $finalAssetArray[$li->z_id] = [
                'settlement_c' => $settlement_c,
                'gap_c' => $gap_c,
                'bid_c' => $bid_c
            ];
        }

        $zpList = ZilaParishad::getZPs();

        foreach ($zpList as $zp) {
            $settlement_c = 0;
            $gap_c = 0;
            $bid_c = 0;

            if (isset($finalAssetArray[$zp->id])) {
                $settlement_c = $finalAssetArray[$zp->id]['settlement_c'];
                $bid_c = $finalAssetArray[$zp->id]['bid_c'];
                $gap_c = $finalAssetArray[$zp->id]['gap_c'];
            }

            $finalArray[$zp->id] = [
                'settlement_c' => ($settlement_c),
                'bid_c' => ($bid_c),
                'gap_c' => ($gap_c)
            ];
        }

        return $finalArray;
    }

    public static function levelWiseSettledAsset($fy_id, $level)
    {

        $finalArray = [];


        if ($level == "ALL") {
            $whereArray = [
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                //['osr_non_tax_asset_final_records.bidding_status','=',1],
                ['a_short.level', '!=', 'NA'],
            ];
        } else {
            $whereArray = [
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                //['osr_non_tax_asset_final_records.bidding_status','=',1],
                ['a_short.level', $level],
            ];
        }

        $data = OsrNonTaxAssetFinalRecord::where($whereArray)

            ->select(DB::raw('count(*) AS total'), 'osr.zila_id as z_id')
            ->join('osr_non_tax_asset_shortlists as a_short', 'osr_non_tax_asset_final_records.asset_code', '=', 'a_short.asset_code')
            ->join('osr_non_tax_asset_entries as osr', 'osr_non_tax_asset_final_records.asset_code', '=', 'osr.asset_code')
            ->join('zila_parishads as zp', 'osr.zila_id', '=', 'zp.id')
            ->groupBy('osr.zila_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->z_id] = $li->total;
        }

        return $finalArray;
    }

    public static function levelWiseTotalAsset($fy_to_date)
    {

        $finalArray = [];


        $data = OsrNonTaxAssetEntry::where([
            ['osr_non_tax_asset_entries.asset_listing_date', '<', $fy_to_date],
        ])
            ->select(DB::raw('count(*) AS total'), 'osr_non_tax_asset_entries.zila_id as zp_id')
            ->groupBy('osr_non_tax_asset_entries.zila_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->zp_id] = $li->total;
        }

        return $finalArray;
    }


}