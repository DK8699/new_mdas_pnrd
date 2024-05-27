<?php

namespace App\Osr;

use Illuminate\Support\Facades\Auth;
use DB;

use Illuminate\Database\Eloquent\Model;

class OsrNonTaxAssetShortlist extends Model
{
    public static function getShortlistAsset($fy_id, $branch_id, $level, $id)
    {

        if ($level == "ZP") {
            $whereArray = [
                ['osr_master_non_tax_branch_id', '=', $branch_id],
                ['osr_master_fy_year_id', '=', $fy_id],
                ['level', '=', $level],
                ['zp_id', '=', $id],
            ];
        } elseif ($level == "AP") {
            $whereArray = [
                ['osr_master_non_tax_branch_id', '=', $branch_id],
                ['osr_master_fy_year_id', '=', $fy_id],
                ['level', '=', $level],
                ['ap_id', '=', $id],
            ];
        } else {
            $whereArray = [
                ['osr_master_non_tax_branch_id', '=', $branch_id],
                ['osr_master_fy_year_id', '=', $fy_id],
                ['level', '=', $level],
                ['gp_id', '=', $id],
            ];
        }

        return OsrNonTaxAssetShortlist::where($whereArray)->select('asset_code')->get();
    }

    public static function getAsset($assetCode, $fy_id)
    {
        $whereArray = [
            ['asset_code', '=', $assetCode],
            ['osr_master_fy_year_id', '=', $fy_id],
        ];
        return OsrNonTaxAssetShortlist::where($whereArray)->select('*')->first();
    }

    public static function isInShortlist($assetCode, $fy_id, $level, $id)
    {


        if ($level == "ZP") {
            $whereArray = [
                ['asset_code', '=', $assetCode],
                ['osr_master_fy_year_id', '=', $fy_id],
                ['level', '=', $level],
                ['zp_id', '=', $id],
            ];
        } elseif ($level == "AP") {
            $whereArray = [
                ['asset_code', '=', $assetCode],
                ['osr_master_fy_year_id', '=', $fy_id],
                ['level', '=', $level],
                ['ap_id', '=', $id],
            ];
        } else {
            $whereArray = [
                ['asset_code', '=', $assetCode],
                ['osr_master_fy_year_id', '=', $fy_id],
                ['level', '=', $level],
                ['gp_id', '=', $id],
            ];
        }

        $count = OsrNonTaxAssetShortlist::where($whereArray)->count();

        if ($count == 1) {
            return true;
        }

        return false;
    }

    public static function yrWiseAssetCount($fy_id, $branch_id)
    {
        return OsrNonTaxAssetShortlist::where([
            ['osr_master_fy_year_id', '=', $fy_id],
            ['osr_master_non_tax_branch_id', $branch_id]
        ])->count();
    }


    public static function dw_asset_count($fy_id)
    {
        $users = Auth::user();

        $data = [];

        if ($users->mdas_master_role_id == 2) {
            $zpAsset = OsrNonTaxAssetShortlist::where([
                ['zp_id', '=', $users->zp_id],
                ['osr_master_fy_year_id', '=', $fy_id],
                ['level', '=', "ZP"],
            ])->count();
            $apAsset = OsrNonTaxAssetShortlist::where([
                ['zp_id', '=', $users->zp_id],
                ['osr_master_fy_year_id', '=', $fy_id],
                ['level', '=', "AP"],
            ])->count();
            $gpAsset = OsrNonTaxAssetShortlist::where([
                ['zp_id', '=', $users->zp_id],
                ['osr_master_fy_year_id', '=', $fy_id],
                ['level', '=', "GP"],
            ])->count();

            $data = ['zpAsset' => $zpAsset, 'apAsset' => $apAsset, 'gpAsset' => $gpAsset];

        } elseif ($users->mdas_master_role_id == 3) {

            $apAsset = OsrNonTaxAssetShortlist::where([
                ['ap_id', '=', $users->ap_id],
                ['level', '=', "AP"],
            ])->count();
            $gpAsset = OsrNonTaxAssetShortlist::where([
                ['ap_id', '=', $users->ap_id],
                ['level', '=', "GP"],
            ])->count();
            $data = ['apAsset' => $apAsset, 'gpAsset' => $gpAsset];

        } elseif ($users->mdas_master_role_id == 4) {

            $gpAsset = OsrNonTaxAssetShortlist::where([
                ['gp_id', '=', $users->gp_id],
                ['level', '=', "GP"],
            ])->count();

            $data = ['gpAsset' => $gpAsset];
        }

        return $data;
    }

    public static function AssetShortlisted($fy_id)
    {
        $finalArray = [];
        $data = OsrNonTaxAssetShortlist::where('osr_master_fy_year_id', $fy_id)
            ->select(DB::raw('count(*) AS total'), 'osr_non_tax_asset_shortlists.zp_id as z_id', 'osr_non_tax_asset_shortlists.level as level')
            ->groupBy('osr_non_tax_asset_shortlists.zp_id')
            ->groupBy('osr_non_tax_asset_shortlists.level')
            ->get();
        foreach ($data as $li) {
            $finalArray[$li->z_id][$li->level] = $li->total;
        }
        return $finalArray;
    }

    public static function levelWiseShortlistedCount($fy_id, $z_id)
    {
        $finalArray = [];
        $data = OsrNonTaxAssetShortlist::where([
            ['osr_master_fy_year_id', $fy_id],
            ['osr_non_tax_asset_shortlists.zp_id', $z_id],
        ])
            ->select(DB::raw('count(*) AS total'), 'osr_non_tax_asset_shortlists.level as level')
            ->groupBy('osr_non_tax_asset_shortlists.level')
            ->get();
        foreach ($data as $li) {
            $finalArray[$li->level] = $li->total;
        }
        return $finalArray;
    }

    public static function ZPshortlistedList($fy_id, $z_id)
    {

        return OsrNonTaxAssetShortlist::leftJoin('zila_parishads as z', 'z.id', '=', 'osr_non_tax_asset_shortlists.zp_id')
            ->leftJoin('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->leftJoin('osr_master_non_tax_branches as branches', 'branches.id', '=', 'osr_non_tax_asset_shortlists.osr_master_non_tax_branch_id')
            ->where([
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.zp_id', '=', $z_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'ZP'],
            ])
            ->select('osr_non_tax_asset_shortlists.*', 'a_entries.asset_name', 'a_entries.asset_listing_date', 'branches.branch_name')
            ->orderBy('osr_non_tax_asset_shortlists.osr_master_non_tax_branch_id')
            ->get();

    }

    public static function APshortlistedList($fy_id, $z_id)
    {

        return OsrNonTaxAssetShortlist::leftJoin('zila_parishads as z', 'z.id', '=', 'osr_non_tax_asset_shortlists.zp_id')
            ->leftJoin('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->leftJoin('osr_master_non_tax_branches as branches', 'branches.id', '=', 'osr_non_tax_asset_shortlists.osr_master_non_tax_branch_id')
            ->where([
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.zp_id', '=', $z_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'AP'],
            ])
            ->select('osr_non_tax_asset_shortlists.*', 'a_entries.asset_name', 'a_entries.asset_listing_date', 'branches.branch_name')
            ->orderBy('osr_non_tax_asset_shortlists.osr_master_non_tax_branch_id')
            ->get();

    }

    public static function GPshortlistedList($fy_id, $z_id)
    {

        return OsrNonTaxAssetShortlist::leftJoin('zila_parishads as z', 'z.id', '=', 'osr_non_tax_asset_shortlists.zp_id')
            ->leftJoin('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->leftJoin('osr_master_non_tax_branches as branches', 'branches.id', '=', 'osr_non_tax_asset_shortlists.osr_master_non_tax_branch_id')
            ->where([
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.zp_id', '=', $z_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'GP'],
            ])
            ->select('osr_non_tax_asset_shortlists.*', 'a_entries.asset_name', 'a_entries.asset_listing_date', 'branches.branch_name')
            ->orderBy('osr_non_tax_asset_shortlists.osr_master_non_tax_branch_id')
            ->get();

    }

    public static function NAshortlistedList($fy_id, $z_id)
    {

        return OsrNonTaxAssetShortlist::leftJoin('zila_parishads as z', 'z.id', '=', 'osr_non_tax_asset_shortlists.zp_id')
            ->leftJoin('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->leftJoin('osr_master_non_tax_branches as branches', 'branches.id', '=', 'osr_non_tax_asset_shortlists.osr_master_non_tax_branch_id')
            ->where([
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.zp_id', '=', $z_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'NA'],
            ])
            ->select('osr_non_tax_asset_shortlists.*', 'a_entries.asset_name', 'a_entries.asset_listing_date', 'branches.branch_name')
            ->orderBy('osr_non_tax_asset_shortlists.osr_master_non_tax_branch_id')
            ->get();


    }

    public static function getAssetEntryByIdJoinedData($asset_code)
    {
        return OsrNonTaxAssetShortlist::join('zila_parishads AS z', 'osr_non_tax_asset_shortlists.zp_id', '=', 'z.id')
            ->join('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->join('anchalik_parishads AS a', 'osr_non_tax_asset_shortlists.ap_id', '=', 'a.id')
            ->join('gram_panchyats AS g', 'osr_non_tax_asset_shortlists.gp_id', '=', 'g.gram_panchyat_id')
            ->where([
                ['osr_non_tax_asset_shortlists.asset_code', '=', $asset_code]
            ])->select('z.zila_parishad_name', 'a.anchalik_parishad_name', 'g.gram_panchayat_name', 'osr_non_tax_asset_shortlists.*', 'a_entries.asset_name')
            ->first();
    }

    public static function levelWiseShortlistAssetCount($fy_id, $level)
    {

        $finalArray = [];

        if ($level == "ALL") {
            $whereArray = [
                ['osr_master_fy_year_id', $fy_id],
                ['osr_non_tax_asset_shortlists.level', '!=', 'NA'],
            ];
        } else {
            $whereArray = [
                ['osr_master_fy_year_id', $fy_id],
                ['osr_non_tax_asset_shortlists.level', $level],
            ];
        }

        // $data = OsrNonTaxAssetShortlist::where($whereArray)
        //                                     ->select(DB::raw('count(*) AS total'),'osr_non_tax_asset_shortlists.zp_id as z_id')
        //                                     ->groupBy('osr_non_tax_asset_shortlists.zp_id')
        //                                     ->get();

        $data = OsrNonTaxAssetShortlist::where($whereArray)
            ->select(DB::raw('count(*) AS total'), 'osr.zila_id as z_id')
            ->join('osr_non_tax_asset_entries as osr', 'osr_non_tax_asset_shortlists.asset_code', '=', 'osr.asset_code')
            ->join('zila_parishads as zp', 'osr.zila_id', '=', 'zp.id')
            ->groupBy('osr.zila_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->z_id] = $li->total;
        }
        return $finalArray;
    }
    public static function levelWiseNotShortlistedAssetCount($fy_id)
    {

        $finalArray = [];

        $data = OsrNonTaxAssetShortlist::where([
            ['osr_master_fy_year_id', $fy_id],
            ['osr_non_tax_asset_shortlists.level', 'NA'],
        ])
            ->select(DB::raw('count(*) AS total'), 'osr.zila_id as z_id')
            ->join('osr_non_tax_asset_entries as osr', 'osr_non_tax_asset_shortlists.asset_code', '=', 'osr.asset_code')
            ->join('zila_parishads as zp', 'osr.zila_id', '=', 'zp.id')
            ->groupBy('osr.zila_id')
            ->get();

        foreach ($data as $li) {
            $finalArray[$li->z_id] = $li->total;
        }
        return $finalArray;
    }

}