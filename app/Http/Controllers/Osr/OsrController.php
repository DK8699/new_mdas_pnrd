<?php

namespace App\Http\Controllers\Osr;


use App\CommonModels\District;
use App\CommonModels\AnchalikParishad;
use App\CommonModels\GramPanchyat;
use App\CommonModels\ZilaParishad;
use App\ConfigMdas;
use App\Osr\OsrMasterNonTaxBranch;
use App\Osr\OsrNonTaxAssetEntry;
use App\Osr\OsrNonTaxSignedAssetReport;

use App\Osr\OsrMasterFyYear;

use App\Osr\OsrNonTaxAssetFinalRecord;
use App\Osr\OsrNonTaxAssetShortlist;
use App\Osr\OsrNonTaxOtherAssetEntry;
use App\Osr\OsrNonTaxOtherAssetFinalRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Crypt;
use DB;


class OsrController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'user_mdas']);
    }

    //------------------------------------------------------------------------------------------------------------------
    //----------------------------------- OSR PANEL ----------------------------------------------------------------
    //------------------------------------------------------------------------------------------------------------------
    public function panel(Request $request, $fy_id)
    {
        $users = Auth::user();
        $originName = ConfigMdas::getOriginName();

        $fy_id = decrypt($fy_id);

        $fyList = OsrMasterFyYear::getAllYears();
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $levelCount = ['zpwApCount' => 0, 'zpwGpCount' => 0, 'apwGpCount' => 0];
        $assetCount = OsrNonTaxAssetShortlist::dw_asset_count($fy_id);
        $otherAssetCount = OsrNonTaxOtherAssetEntry::dw_other_asset_count();

        $data = [
            'originName' => $originName,
            'levelCount' => $levelCount,
            'assetCount' => $assetCount,
            'otherAssetCount' => $otherAssetCount,
            'fy_id' => $fy_id,
            'fyList' => $fyList,
            'fyData' => $fyData,

            'zp_id' => NULL,
            'ap_id' => NULL,
            'gp_id' => NULL,
        ];

        //---------------------  ZP ADMIN  -----------------------------------------------------------------------------

        if ($users->mdas_master_role_id == 2) {

            $levelCount['zpwApCount'] = AnchalikParishad::apCountByZilaId($users->zp_id);
            $levelCount['zpwGpCount'] = GramPanchyat::gpCountByZilaId($users->zp_id);


            $data['levelCount'] = $levelCount;
            $data['zp_id'] = $users->zp_id;

            //SETTLEMENT
            $zp_settlement = $this->zp_settlement($users->zp_id, $fy_id, $fyData);

            //DEFAULTER
            $zp_defaulter = $this->zp_defaulter($users->zp_id, $fy_id);

            $data['zp_settlement'] = $zp_settlement;
            $data['zp_defaulter'] = $zp_defaulter;

            //REVENUE
            $zp_revenue = $this->zp_revenue($users->zp_id, $fy_id);
            $data['zp_revenue'] = $zp_revenue;

            //SHARE
            $zp_share_dis = $this->zp_share_distribution($users->zp_id, $fy_id);
            $data['zp_share_dis'] = $zp_share_dis;

            //OTHER ASSET-----------------------------------------------------------------------------------------------

            //REVENUE
            $zp_other_revenue = $this->zp_other_revenue($users->zp_id, $fy_id);
            $data['zp_other_revenue'] = $zp_other_revenue;

            //SHARE
            $zp_other_share_dis = $this->zp_other_share_distribution($users->zp_id, $fy_id);
            $data['zp_other_share_dis'] = $zp_other_share_dis;


            //---------------------  AP ADMIN  -----------------------------------------------------------------------------
        } elseif ($users->mdas_master_role_id == 3) {

            $levelCount['apwGpCount'] = GramPanchyat::gpCountByApId($users->ap_id);

            $data['levelCount'] = $levelCount;
            $data['zp_id'] = $users->zp_id;
            $data['ap_id'] = $users->ap_id;

            //SETTLEMENT

            $ap_settlement = $this->ap_settlement($users->zp_id, $users->ap_id, $fy_id, $fyData);

            //DEFAULTER

            $ap_defaulter = $this->ap_defaulter($users->zp_id, $users->ap_id, $fy_id);

            $data['ap_settlement'] = $ap_settlement;
            $data['ap_defaulter'] = $ap_defaulter;

            //REVENUE
            $ap_revenue = $this->ap_revenue($users->zp_id, $users->ap_id, $fy_id);
            $data['ap_revenue'] = $ap_revenue;

            //SHARE
            $ap_share_dis = $this->ap_share_distribution($users->zp_id, $users->ap_id, $fy_id);
            $data['ap_share_dis'] = $ap_share_dis;

            //OTHER ASSET-----------------------------------------------------------------------------------------------

            //REVENUE
            $ap_other_revenue = $this->ap_other_revenue($users->zp_id, $users->ap_id, $fy_id);
            $data['ap_other_revenue'] = $ap_other_revenue;

            //SHARE
            $ap_other_share_dis = $this->ap_other_share_distribution($users->zp_id, $users->ap_id, $fy_id);
            $data['ap_other_share_dis'] = $ap_other_share_dis;

            //---------------------  GP ADMIN  -----------------------------------------------------------------------------
        } elseif ($users->mdas_master_role_id == 4) {
            $data['zp_id'] = $users->zp_id;
            $data['ap_id'] = $users->ap_id;
            $data['gp_id'] = $users->gp_id;

            //SETTLEMENT
            $gp_settlement = $this->gp_settlement($users->zp_id, $users->ap_id, $users->gp_id, $fy_id, $fyData);

            //DEFAULTER
            $gp_defaulter = $this->gp_defaulter($users->zp_id, $users->ap_id, $users->gp_id, $fy_id);

            $data['gp_settlement'] = $gp_settlement;
            $data['gp_defaulter'] = $gp_defaulter;

            //REVENUE
            $gp_revenue = $this->gp_revenue($users->zp_id, $users->ap_id, $users->gp_id, $fy_id);
            $data['gp_revenue'] = $gp_revenue;
            //SHARE
            $gp_share_dis = $this->gp_share_distribution($users->zp_id, $users->ap_id, $users->gp_id, $fy_id);
            $data['gp_share_dis'] = $gp_share_dis;

            //OTHER ASSET-----------------------------------------------------------------------------------------------
            //REVENUE
            $gp_other_revenue = $this->gp_other_revenue($users->zp_id, $users->ap_id, $users->gp_id, $fy_id);
            $data['gp_other_revenue'] = $gp_other_revenue;

            //SHARE
            $gp_other_share_dis = $this->gp_other_share_distribution($users->zp_id, $users->ap_id, $users->gp_id, $fy_id);
            $data['gp_other_share_dis'] = $gp_other_share_dis;
        }

        return view('Osr.osr_panel', compact('originName', 'data'));
    }

    //----------------------------------SETTLEMENT ------------------------------------------------------------------
    //ZP
    public function zp_settlement($zp_id, $fy_id, $fyData)
    {

        $totalWhereArray = [
            ['osr_non_tax_asset_entries.asset_under', '=', 'ZP'],
            ['osr_non_tax_asset_entries.zila_id', '=', $zp_id],
            ['asset_listing_date', '<', $fyData->fy_to]
        ];

        $sortWhereArray =
            [
                ['osr_non_tax_asset_shortlists.level', '=', 'ZP'],
                ['a_entries.zila_id', '=', $zp_id],
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id]
            ];

        $settledWhereArray =
            [
                ['a_short.level', '=', 'ZP'],
                ['a_short.zp_id', '=', $zp_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id]
            ];

        $zpData = $this->settlement_query($fy_id, $totalWhereArray, $sortWhereArray, $settledWhereArray);

        $totalWhereArray = [
            ['osr_non_tax_asset_entries.asset_under', '=', 'AP'],
            ['osr_non_tax_asset_entries.zila_id', '=', $zp_id],
            ['asset_listing_date', '<', $fyData->fy_to]
        ];

        $sortWhereArray = [
            ['osr_non_tax_asset_shortlists.level', '=', 'AP'],
            ['a_entries.zila_id', '=', $zp_id],
            ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id]
        ];

        $settledWhereArray =
            [
                ['a_short.level', '=', 'AP'],
                ['a_short.zp_id', '=', $zp_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id]
            ];

        $apsData = $this->settlement_query($fy_id, $totalWhereArray, $sortWhereArray, $settledWhereArray);

        $totalWhereArray = [
            ['osr_non_tax_asset_entries.asset_under', '=', 'GP'],
            ['osr_non_tax_asset_entries.zila_id', '=', $zp_id],
            ['asset_listing_date', '<', $fyData->fy_to]
        ];

        $sortWhereArray = [
            ['osr_non_tax_asset_shortlists.level', '=', 'GP'],
            ['a_entries.zila_id', '=', $zp_id],
            ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id]
        ];

        $settledWhereArray = [
            ['a_short.level', '=', 'GP'],
            ['a_short.zp_id', '=', $zp_id],
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['a_short.osr_master_fy_year_id', '=', $fy_id]
        ];

        $gpsData = $this->settlement_query($fy_id, $totalWhereArray, $sortWhereArray, $settledWhereArray);

        return
            [
                'zp' => $zpData,
                'aps' => $apsData,
                'gps' => $gpsData,
            ];
    }
    //AP
    public function ap_settlement($zp_id, $ap_id, $fy_id, $fyData)
    {

        $totalWhereArray = [
            ['osr_non_tax_asset_entries.asset_under', '=', 'AP'],
            ['osr_non_tax_asset_entries.zila_id', '=', $zp_id],
            ['osr_non_tax_asset_entries.anchalik_id', '=', $ap_id],
            ['asset_listing_date', '<', $fyData->fy_to]
        ];
        $sortWhereArray = [
            ['osr_non_tax_asset_shortlists.level', '=', 'AP'],
            ['a_entries.zila_id', '=', $zp_id],
            ['a_entries.anchalik_id', '=', $ap_id],
            ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id]
        ];

        $settledWhereArray = [
            ['a_short.level', '=', 'AP'],
            ['a_short.zp_id', '=', $zp_id],
            ['a_short.ap_id', '=', $ap_id],
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id]
        ];

        $apData = $this->settlement_query($fy_id, $totalWhereArray, $sortWhereArray, $settledWhereArray);

        $totalWhereArray = [
            ['osr_non_tax_asset_entries.asset_under', '=', 'GP'],
            ['osr_non_tax_asset_entries.zila_id', '=', $zp_id],
            ['osr_non_tax_asset_entries.anchalik_id', '=', $ap_id],
            ['asset_listing_date', '<', $fyData->fy_to]
        ];
        $sortWhereArray = [
            ['osr_non_tax_asset_shortlists.level', '=', 'GP'],
            ['a_entries.zila_id', '=', $zp_id],
            ['a_entries.anchalik_id', '=', $ap_id],
            ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id]
        ];
        $settledWhereArray = [
            ['a_short.level', '=', 'GP'],
            ['a_short.zp_id', '=', $zp_id],
            ['a_short.ap_id', '=', $ap_id],
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['a_short.osr_master_fy_year_id', '=', $fy_id],
        ];

        $gpsData = $this->settlement_query($fy_id, $totalWhereArray, $sortWhereArray, $settledWhereArray);

        return
            [
                'ap' => $apData,
                'gps' => $gpsData,
            ];
    }
    //GP
    public function gp_settlement($zp_id, $ap_id, $gp_id, $fy_id, $fyData)
    {

        $totalWhereArray = [
            ['osr_non_tax_asset_entries.asset_under', '=', 'GP'],
            ['osr_non_tax_asset_entries.zila_id', '=', $zp_id],
            ['osr_non_tax_asset_entries.anchalik_id', '=', $ap_id],
            ['osr_non_tax_asset_entries.gram_panchayat_id', '=', $gp_id],
            ['asset_listing_date', '<', $fyData->fy_to]
        ];
        $sortWhereArray = [
            ['osr_non_tax_asset_shortlists.level', '=', 'GP'],
            ['a_entries.zila_id', '=', $zp_id],
            ['a_entries.anchalik_id', '=', $ap_id],
            ['a_entries.gram_panchayat_id', '=', $gp_id],
            ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id]
        ];
        $settledWhereArray = [
            ['a_short.level', '=', 'GP'],
            ['a_short.zp_id', '=', $zp_id],
            ['a_short.ap_id', '=', $ap_id],
            ['a_short.gp_id', '=', $gp_id],
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['a_short.osr_master_fy_year_id', '=', $fy_id],

        ];

        $gpData = $this->settlement_query($fy_id, $totalWhereArray, $sortWhereArray, $settledWhereArray);

        return
            ['gp' => $gpData];
    }


    //----------------------------------DEFAULTER---------------------------------------------------------------------
    //ZP
    public function zp_defaulter($zp_id, $fy_id)
    {

        $whereArray = [
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
            ['osr_non_tax_asset_final_records.defaulter_status', '=', 1],
            ['a_entries.zila_id', '=', $zp_id],
            ['a_entries.asset_under', '=', 'ZP']
        ];

        $zpData = $this->defaulter_query($fy_id, $whereArray);

        $whereArray = [
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
            ['osr_non_tax_asset_final_records.defaulter_status', '=', 1],
            ['a_entries.zila_id', '=', $zp_id],
            ['a_entries.asset_under', '=', 'AP']
        ];
        $apsData = $this->defaulter_query($fy_id, $whereArray);

        $whereArray = [
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
            ['osr_non_tax_asset_final_records.defaulter_status', '=', 1],
            ['a_entries.zila_id', '=', $zp_id],
            ['a_entries.asset_under', '=', 'GP']
        ];
        $gpsData = $this->defaulter_query($fy_id, $whereArray);

        return
            [
                'zp' => $zpData,
                'aps' => $apsData,
                'gps' => $gpsData,
            ];
    }
    //AP
    public function ap_defaulter($zp_id, $ap_id, $fy_id)
    {

        $whereArray = [
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
            ['osr_non_tax_asset_final_records.defaulter_status', '=', 1],
            ['a_entries.zila_id', '=', $zp_id],
            ['a_entries.anchalik_id', '=', $ap_id],
            ['a_entries.asset_under', '=', 'AP']
        ];
        $apData = $this->defaulter_query($fy_id, $whereArray);

        $whereArray = [
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
            ['osr_non_tax_asset_final_records.defaulter_status', '=', 1],
            ['a_entries.zila_id', '=', $zp_id],
            ['a_entries.anchalik_id', '=', $ap_id],
            ['a_entries.asset_under', '=', 'GP']
        ];
        $gpsData = $this->defaulter_query($fy_id, $whereArray);

        return
            [
                'ap' => $apData,
                'gps' => $gpsData,
            ];
    }
    //GP
    public function gp_defaulter($zp_id, $ap_id, $gp_id, $fy_id)
    {

        $whereArray = [
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
            ['osr_non_tax_asset_final_records.defaulter_status', '=', 1],
            ['a_entries.zila_id', '=', $zp_id],
            ['a_entries.anchalik_id', '=', $ap_id],
            ['a_entries.gram_panchayat_id', '=', $gp_id],
            ['a_entries.asset_under', '=', 'GP']
        ];
        $gpData = $this->defaulter_query($fy_id, $whereArray);

        return
            [
                'gp' => $gpData,
            ];
    }

    //--------------------------------SETTLEMENT QUERY -----------------------------------------------------------------
    public function settlement_query($fy_id, $totalWhereArray, $sortWhereArray, $settledWhereArray)
    {


        $total = OsrNonTaxAssetEntry::where($totalWhereArray)->count();

        $shortlist = OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->where($sortWhereArray)
            ->count();

        $settled = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where($settledWhereArray)
            ->count();

        if ($shortlist > 0 && $settled > 0) {
            $percent = round($settled / $shortlist * 100, 2);
        } else {
            $percent = 0;
        }


        return [
            'totalScope' => $total,
            'shortlist' => $shortlist,
            'settled' => $settled,
            'percent' => $percent
        ];
    }

    //--------------------------------DEFAULTER QUERY ------------------------------------------------------------------
    public function defaulter_query($fy_id, $whereArray)
    {
        $defaulter = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where($whereArray)
            ->count();
        return [
            'defaulter' => $defaulter
        ];
    }

    //REVENUE-----------------------------------------------------------------------------------------------------------

    //ZP
    private function zp_revenue($zp_id, $fy_id)
    {

        $whereArray = [
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['a_short.osr_master_fy_year_id', '=', $fy_id],
            ['a_short.level', '=', 'ZP'],
            ['a_short.zp_id', '=', $zp_id],
        ];

        $zpDataArray = $this->revenue_query($fy_id, $whereArray);

        $whereArray = [
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['a_short.osr_master_fy_year_id', '=', $fy_id],
            ['a_short.level', '=', 'AP'],
            ['a_short.zp_id', '=', $zp_id],
        ];

        $apsDataArray = $this->revenue_query($fy_id, $whereArray);

        $whereArray = [
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['a_short.osr_master_fy_year_id', '=', $fy_id],
            ['a_short.level', '=', 'GP'],
            ['a_short.zp_id', '=', $zp_id],
        ];

        $gpsDataArray = $this->revenue_query($fy_id, $whereArray);

        return [
            "zp" => $zpDataArray,
            "aps" => $apsDataArray,
            "gps" => $gpsDataArray,
        ];
    }
    //AP
    private function ap_revenue($zp_id, $ap_id, $fy_id)
    {

        $whereArray = [
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['a_short.osr_master_fy_year_id', '=', $fy_id],
            ['a_short.level', '=', 'AP'],
            ['a_short.zp_id', '=', $zp_id],
            ['a_short.ap_id', '=', $ap_id],
        ];

        $apDataArray = $this->revenue_query($fy_id, $whereArray);

        $whereArray = [
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['a_short.osr_master_fy_year_id', '=', $fy_id],
            ['a_short.level', '=', 'GP'],
            ['a_short.zp_id', '=', $zp_id],
            ['a_short.zp_id', '=', $ap_id],
        ];

        $gpsDataArray = $this->revenue_query($fy_id, $whereArray);

        return [
            "ap" => $apDataArray,
            "gps" => $gpsDataArray,
        ];
    }
    //GP
    private function gp_revenue($zp_id, $ap_id, $gp_id, $fy_id)
    {

        $whereArray = [
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['a_short.osr_master_fy_year_id', '=', $fy_id],
            ['a_short.level', '=', 'GP'],
            ['a_short.zp_id', '=', $zp_id],
            ['a_short.zp_id', '=', $ap_id],
            ['a_short.gp_id', '=', $gp_id],
        ];

        $gpDataArray = $this->revenue_query($fy_id, $whereArray);

        return [
            "gp" => $gpDataArray,
        ];
    }

    private function revenue_query($fy_id, $whereArray)
    {
        $resArray[$fy_id] = [
            'gap_c' => 0, 'bid_c' => 0, 'tot_c' => 0
        ];
        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where($whereArray)->select(DB::raw(' 

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
                            '), 'osr_non_tax_asset_final_records.fy_id')
            ->groupBy('osr_non_tax_asset_final_records.fy_id')
            ->get();

        foreach ($data as $li) {

            $gap_c = $li->tot_gap_zp_share + $li->tot_gap_ap_share + $li->tot_gap_gp_share;

            $bid_c = $li->tot_ins_collected_amt;

            $tot_c = $gap_c + $bid_c;

            $resArray[$li->fy_id] = [
                'gap_c' => $gap_c, 'bid_c' => $bid_c, 'tot_c' => $tot_c
            ];
        }

        return $resArray;
    }

    //OTHER REVENUE-------------------------------------------------------------------------------------------------------

    //ZP
    private function zp_other_revenue($zp_id, $fy_id)
    {

        $whereArray = [
            ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
            ['a_entries.managed_by', '=', 'ZP'],
            ['a_entries.zila_id', '=', $zp_id],
        ];

        $zpDataArray = $this->other_revenue_query($fy_id, $whereArray);

        $whereArray = [
            ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
            ['a_entries.managed_by', '=', 'AP'],
            ['a_entries.zila_id', '=', $zp_id],
        ];

        $apsDataArray = $this->other_revenue_query($fy_id, $whereArray);

        $whereArray = [
            ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
            ['a_entries.managed_by', '=', 'GP'],
            ['a_entries.zila_id', '=', $zp_id],
        ];

        $gpsDataArray = $this->other_revenue_query($fy_id, $whereArray);

        return [
            "zp" => $zpDataArray,
            "aps" => $apsDataArray,
            "gps" => $gpsDataArray,
        ];
    }
    //AP
    private function ap_other_revenue($zp_id, $ap_id, $fy_id)
    {

        $whereArray = [
            ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
            ['a_entries.managed_by', '=', 'AP'],
            ['a_entries.zila_id', '=', $zp_id],
            ['a_entries.anchalik_id', '=', $ap_id],
        ];

        $apDataArray = $this->other_revenue_query($fy_id, $whereArray);

        $whereArray = [
            ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
            ['a_entries.managed_by', '=', 'GP'],
            ['a_entries.zila_id', '=', $zp_id],
            ['a_entries.anchalik_id', '=', $ap_id],
        ];

        $gpsDataArray = $this->other_revenue_query($fy_id, $whereArray);

        return [
            "ap" => $apDataArray,
            "gps" => $gpsDataArray,
        ];
    }
    //GP
    private function gp_other_revenue($zp_id, $ap_id, $gp_id, $fy_id)
    {

        $whereArray = [
            ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
            ['a_entries.managed_by', '=', 'GP'],
            ['a_entries.zila_id', '=', $zp_id],
            ['a_entries.anchalik_id', '=', $ap_id],
            ['a_entries.gram_panchayat_id', '=', $gp_id],
        ];

        $gpDataArray = $this->other_revenue_query($fy_id, $whereArray);

        return [
            "gp" => $gpDataArray,
        ];
    }

    private function other_revenue_query($fy_id, $whereArray)
    {
        $resArray[$fy_id] = [
            'other_c' => 0
        ];

        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries', 'a_entries.other_asset_code', '=', 'osr_non_tax_other_asset_final_records.other_asset_code')
            ->where($whereArray)
            ->select(DB::raw('
                            sum(osr_non_tax_other_asset_final_records.tot_self_collected_amt) AS tot_self_collected_amt,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_collected_amt) AS tot_ag_collected_amt,
                                             
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'), 'osr_non_tax_other_asset_final_records.fy_id')
            ->groupBy('osr_non_tax_other_asset_final_records.fy_id')
            ->get();
        //echo json_encode($data);

        foreach ($data as $li) {

            $other_c = $li->tot_self_collected_amt + $li->tot_ag_collected_amt;;

            $resArray[$li->fy_id] = [
                'other_c' => $other_c
            ];
        }

        return $resArray;
    }

    //SHARE-------------------------------------------------------------------------------------------------------------

    //ZP
    private function zp_share_distribution($zp_id, $fy_id)
    {

        $whereArray = [
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['a_short.osr_master_fy_year_id', $fy_id],
            ['a_short.level', '=', 'ZP'],
            ['a_short.zp_id', '=', $zp_id],
        ];

        $zpDataArray = $this->share_dis_query($fy_id, $whereArray);

        $whereArray = [
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['a_short.osr_master_fy_year_id', $fy_id],
            ['a_short.level', '=', 'AP'],
            ['a_short.zp_id', '=', $zp_id],
        ];

        $apsDataArray = $this->share_dis_query($fy_id, $whereArray);

        $whereArray = [
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['a_short.osr_master_fy_year_id', $fy_id],
            ['a_short.level', '=', 'GP'],
            ['a_short.zp_id', '=', $zp_id],
        ];

        $gpsDataArray = $this->share_dis_query($fy_id, $whereArray);

        return [
            "zp" => $zpDataArray,
            "aps" => $apsDataArray,
            "gps" => $gpsDataArray,
        ];
    }
    //AP
    private function ap_share_distribution($zp_id, $ap_id, $fy_id)
    {

        $whereArray = [
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['a_short.osr_master_fy_year_id', $fy_id],
            ['a_short.level', '=', 'AP'],
            ['a_short.zp_id', '=', $zp_id],
            ['a_short.ap_id', '=', $ap_id],
        ];

        $apDataArray = $this->share_dis_query($fy_id, $whereArray);

        $whereArray = [
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['a_short.osr_master_fy_year_id', $fy_id],
            ['a_short.level', '=', 'GP'],
            ['a_short.zp_id', '=', $zp_id],
            ['a_short.ap_id', '=', $ap_id],
        ];

        $gpsDataArray = $this->share_dis_query($fy_id, $whereArray);

        return [
            "ap" => $apDataArray,
            "gps" => $gpsDataArray,
        ];
    }
    //GP
    private function gp_share_distribution($zp_id, $ap_id, $gp_id, $fy_id)
    {

        $whereArray = [
            ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
            ['a_short.osr_master_fy_year_id', $fy_id],
            ['a_short.level', '=', 'GP'],
            ['a_short.zp_id', '=', $zp_id],
            ['a_short.ap_id', '=', $ap_id],
            ['a_short.gp_id', '=', $gp_id],
        ];

        $gpDataArray = $this->share_dis_query($fy_id, $whereArray);

        return [
            "gp" => $gpDataArray,
        ];
    }

    private function share_dis_query($fy_id, $whereArray)
    {
        $resArray[$fy_id] = [
            'tot_r_c' => 0, 'zp_share' => 0, 'ap_share' => 0, 'gp_share' => 0
        ];
        $data = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->where($whereArray)->select(DB::raw('
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
                            '), 'osr_non_tax_asset_final_records.fy_id')
            ->groupBy('osr_non_tax_asset_final_records.fy_id')
            ->get();

        foreach ($data as $li) {

            $zp_share = $li->f_emd_zp_share + $li->df_zp_share + $li->tot_ins_zp_share + $li->tot_gap_zp_share;
            $ap_share = $li->f_emd_ap_share + $li->df_ap_share + $li->tot_ins_ap_share + $li->tot_gap_ap_share;
            $gp_share = $li->f_emd_gp_share + $li->df_gp_share + $li->tot_ins_gp_share + $li->tot_gap_gp_share;

            $total_revenue_collection = $li->tot_ins_collected_amt;

            $resArray[$li->fy_id] = [
                'tot_r_c' => $total_revenue_collection, 'zp_share' => $zp_share, 'ap_share' => $ap_share, 'gp_share' => $gp_share
            ];
        }

        return $resArray;
    }

    //OTHER SHARE-------------------------------------------------------------------------------------------------------

    //ZP
    private function zp_other_share_distribution($zp_id, $fy_id)
    {

        $whereArray = [
            ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
            ['a_entries.managed_by', '=', 'ZP'],
            ['a_entries.zila_id', '=', $zp_id],
        ];

        $zpDataArray = $this->other_share_dis_query($fy_id, $whereArray);

        $whereArray = [
            ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
            ['a_entries.managed_by', '=', 'AP'],
            ['a_entries.zila_id', '=', $zp_id],
        ];

        $apsDataArray = $this->other_share_dis_query($fy_id, $whereArray);

        $whereArray = [
            ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
            ['a_entries.managed_by', '=', 'GP'],
            ['a_entries.zila_id', '=', $zp_id],
        ];

        $gpsDataArray = $this->other_share_dis_query($fy_id, $whereArray);

        return [
            "zp" => $zpDataArray,
            "aps" => $apsDataArray,
            "gps" => $gpsDataArray,
        ];
    }
    //AP
    private function ap_other_share_distribution($zp_id, $ap_id, $fy_id)
    {

        $whereArray = [
            ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
            ['a_entries.managed_by', '=', 'AP'],
            ['a_entries.zila_id', '=', $zp_id],
            ['a_entries.anchalik_id', '=', $ap_id],
        ];

        $apDataArray = $this->other_share_dis_query($fy_id, $whereArray);

        $whereArray = [
            ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
            ['a_entries.managed_by', '=', 'GP'],
            ['a_entries.zila_id', '=', $zp_id],
            ['a_entries.anchalik_id', '=', $ap_id],
        ];

        $gpsDataArray = $this->other_share_dis_query($fy_id, $whereArray);

        return [
            "ap" => $apDataArray,
            "gps" => $gpsDataArray,
        ];
    }
    //GP
    private function gp_other_share_distribution($zp_id, $ap_id, $gp_id, $fy_id)
    {

        $whereArray = [
            ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
            ['a_entries.managed_by', '=', 'GP'],
            ['a_entries.zila_id', '=', $zp_id],
            ['a_entries.anchalik_id', '=', $ap_id],
            ['a_entries.gram_panchayat_id', '=', $gp_id],
        ];

        $gpDataArray = $this->other_share_dis_query($fy_id, $whereArray);

        return [
            "gp" => $gpDataArray,
        ];
    }

    private function other_share_dis_query($fy_id, $whereArray)
    {
        $resArray[$fy_id] = [
            'tot_r_c' => 0, 'zp_share' => 0, 'ap_share' => 0, 'gp_share' => 0
        ];


        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries', 'a_entries.other_asset_code', '=', 'osr_non_tax_other_asset_final_records.other_asset_code')
            ->where($whereArray)
            ->select(DB::raw('
                            sum(osr_non_tax_other_asset_final_records.tot_self_collected_amt) AS tot_self_collected_amt,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_collected_amt) AS tot_ag_collected_amt,
                                             
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'), 'osr_non_tax_other_asset_final_records.fy_id')
            ->groupBy('osr_non_tax_other_asset_final_records.fy_id')
            ->get();
        //echo json_encode($data);

        foreach ($data as $li) {

            $zp_share = $li->tot_self_zp_share + $li->tot_ag_zp_share;
            $ap_share = $li->tot_self_ap_share + $li->tot_ag_ap_share;
            $gp_share = $li->tot_self_gp_share + $li->tot_ag_gp_share;

            $total_revenue_collection = $zp_share + $ap_share + $gp_share;

            $resArray[$li->fy_id] = [
                'tot_r_c' => $total_revenue_collection, 'zp_share' => $zp_share, 'ap_share' => $ap_share, 'gp_share' => $gp_share
            ];
        }

        return $resArray;
    }


    //------------------------------------------------------------------------------------------------------------------
    //----------------------------------- OSR ENRTY PANEL ----------------------------------------------------------------
    //------------------------------------------------------------------------------------------------------------------
    public function asset_entry_panel(Request $request)
    {

        $assetCount = [];

        $branchList = OsrMasterNonTaxBranch::get_branches();

        foreach ($branchList as $branch) {
            $assetCount[$branch->id] = OsrNonTaxAssetEntry::dw_bw_asset_count($branch->id);
        }

        $max_fy_id = OsrMasterFyYear::getMaxFyYear();

        $data = [
            'fy_id' => $max_fy_id
        ];

        return view('Osr.non_tax.asset.asset_entry_panel', compact('branchList', 'assetCount', 'data'));
    }

    //------------------------------------------------------------------------------------------------------------------
    //----------------------------------- ASSET SHOW LIST --------------------------------------------------------------
    //------------------------------------------------------------------------------------------------------------------
    public function asset_show_list(Request $request)
    {

        $users = Auth::user();
        $branchList = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apList = AnchalikParishad::getAPsByZilaId($users->zp_id);

        $data = [
            'branchList' => $branchList,
            'apList' => $apList,
            'gpList' => [],
            'assetList' => [],
            'branchData' => NULL,
            'apData' => NULL,
            'branch_id' => NULL,
            'ap_id' => NULL,
            'gp_id' => NULL,
            'searchText' => "Select and search to see the result.."
        ];

        if ($users->mdas_master_role_id == 2) { // ZP ADMIN--------------------------------------------------------------
            $ap_id = $request->input('ap_id');
            $apData = AnchalikParishad::getAPName($ap_id);
            $gp_id = $request->input('gp_id');


            if ($request->isMethod('post')) {
                $branch_id = $request->input('branch_id');
                $branchData = OsrMasterNonTaxBranch::getBranchById($branch_id);

                if (!$gp_id) {
                    $level = "AP";
                    $whereArray = [
                        ['osr_non_tax_asset_entries.osr_asset_branch_id', '=', $branch_id],
                        ['osr_non_tax_asset_entries.zila_id', '=', $users->zp_id],
                        ['osr_non_tax_asset_entries.anchalik_id', '=', $ap_id],
                        ['osr_non_tax_asset_entries.asset_under', '=', $level],
                    ];
                    $searchText = "Showing the list of " . $branchData->branch_name . " in " . $apData->anchalik_parishad_name . "(AP) of " . $zpData->zila_parishad_name . "(ZP)";
                } else {
                    $gpData = GramPanchyat::getGPName($gp_id);
                    $level = "GP";
                    $whereArray = [
                        ['osr_non_tax_asset_entries.osr_asset_branch_id', '=', $branch_id],
                        ['osr_non_tax_asset_entries.zila_id', '=', $users->zp_id],
                        ['osr_non_tax_asset_entries.anchalik_id', '=', $ap_id],
                        ['osr_non_tax_asset_entries.gram_panchayat_id', '=', $gp_id],
                        ['osr_non_tax_asset_entries.asset_under', '=', $level],
                    ];

                    $searchText = "Showing the list of " . $branchData->branch_name . " in " . $gpData->gram_panchayat_name . "(GP) of " . $apData->anchalik_parishad_name . "(AP), " . $zpData->zila_parishad_name . "(ZP)";
                }

                $gpList = GramPanchyat::getGpsByAnchalikId($ap_id);

                $data['gpList'] = $gpList;
                $data['apData'] = $apData;
                $data['ap_id'] = $ap_id;
                $data['gp_id'] = $gp_id;

                $assetList = OsrNonTaxAssetEntry::join('zila_parishads AS z', 'osr_non_tax_asset_entries.zila_id', '=', 'z.id')
                    ->join('anchalik_parishads AS a', 'osr_non_tax_asset_entries.anchalik_id', '=', 'a.id')
                    ->join('gram_panchyats AS g', 'osr_non_tax_asset_entries.gram_panchayat_id', '=', 'g.gram_panchyat_id')
                    ->leftJoin('villages AS v', 'osr_non_tax_asset_entries.village_id', '=', 'v.id')
                    ->where($whereArray)
                    ->distinct()
                    ->select('z.zila_parishad_name', 'a.anchalik_parishad_name', 'g.gram_panchayat_name', 'v.village_name', 'osr_non_tax_asset_entries.*')
                    ->orderBy('osr_non_tax_asset_entries.id', 'desc')
                    ->get();


                $data['assetList'] = $assetList;
                $data['branchData'] = $branchData;
                $data['branch_id'] = $branch_id;
                $data['searchText'] = $searchText;
            }
        } elseif ($users->mdas_master_role_id == 3) {/*AP ADMIN ---------------------------------------------------------*/
            $gpList = GramPanchyat::getGpsByAnchalikId($users->ap_id);
            $apData = AnchalikParishad::getAPName($users->ap_id);

            $data['gpList'] = $gpList;
            $data['apData'] = $apData;

            if ($request->isMethod('post')) {
                $branch_id = $request->input('branch_id');
                $branchData = OsrMasterNonTaxBranch::getBranchById($branch_id);
                $gp_id = $request->input('gp_id');

                $gpData = GramPanchyat::getGPName($gp_id);
                $whereArray = [
                    ['osr_non_tax_asset_entries.osr_asset_branch_id', '=', $branch_id],
                    ['osr_non_tax_asset_entries.zila_id', '=', $users->zp_id],
                    ['osr_non_tax_asset_entries.anchalik_id', '=', $users->ap_id],
                    ['osr_non_tax_asset_entries.gram_panchayat_id', '=', $gp_id],
                    ['osr_non_tax_asset_entries.asset_under', '=', "GP"],
                ];

                $searchText = "Showing the list of " . $branchData->branch_name . " in " . $gpData->gram_panchayat_name . "(GP) of " . $apData->anchalik_parishad_name . "(AP), " . $zpData->zila_parishad_name . "(ZP)";

                $data['gp_id'] = $gp_id;

                $assetList = OsrNonTaxAssetEntry::join('zila_parishads AS z', 'osr_non_tax_asset_entries.zila_id', '=', 'z.id')
                    ->join('anchalik_parishads AS a', 'osr_non_tax_asset_entries.anchalik_id', '=', 'a.id')
                    ->join('gram_panchyats AS g', 'osr_non_tax_asset_entries.gram_panchayat_id', '=', 'g.gram_panchyat_id')
                    ->leftJoin('villages AS v', 'osr_non_tax_asset_entries.village_id', '=', 'v.id')
                    ->where($whereArray)
                    ->distinct()
                    ->select('z.zila_parishad_name', 'a.anchalik_parishad_name', 'g.gram_panchayat_name', 'v.village_name', 'osr_non_tax_asset_entries.*')
                    ->orderBy('osr_non_tax_asset_entries.id', 'desc')
                    ->get();

                $data['assetList'] = $assetList;
                $data['branchData'] = $branchData;
                $data['branch_id'] = $branch_id;
                $data['searchText'] = $searchText;
            }
        } else {
            return redirect()->route('dashboard');
        }

        $max_fy_id = OsrMasterFyYear::getMaxFyYear();
        $data['fy_id'] = $max_fy_id;

        return view('Osr.non_tax.asset.asset_show_list', compact('data'));
    }

    //------------------------------------------------------------------------------------------------------------------
    //----------------------------------- OSR NON TAX DISTRICT WISE ASSET LIST [BRANCH]---------------------------------
    //------------------------------------------------------------------------------------------------------------------
    public function nt_dw_asset_list(Request $request, $branch_id)
    {

        $users = Auth::user();

        $zpData = District::getZilaByDistrictId($users->district_code);

        if (!$zpData) {
            return redirect()->route('dashboard');
        }

        $branch_id = base64_decode(base64_decode($branch_id));
        $branchData = OsrMasterNonTaxBranch::getBranchById($branch_id);

        if (!$branchData) {
            return redirect()->route('dashboard');
        }

        if ($users->mdas_master_role_id == 2) {
            $apData = AnchalikParishad::getAPsByZilaId($users->zp_id);
            $whereArray = [
                ['osr_non_tax_asset_entries.osr_asset_branch_id', '=', $branch_id],
                ['osr_non_tax_asset_entries.zila_id', '=', $users->zp_id],
                ['osr_non_tax_asset_entries.asset_under', '=', "ZP"],
            ];

            $parent_name = $zpData->zila_parishad_name;
        } elseif ($users->mdas_master_role_id == 3) {
            $apData = AnchalikParishad::getAPsByApId($users->ap_id);
            $whereArray = [
                ['osr_non_tax_asset_entries.osr_asset_branch_id', '=', $branch_id],
                ['osr_non_tax_asset_entries.anchalik_id', '=', $users->ap_id],
                ['osr_non_tax_asset_entries.asset_under', '=', "AP"],
            ];

            $parent_name = $apData->anchalik_parishad_name;
        } elseif ($users->mdas_master_role_id == 4) {
            $apData = AnchalikParishad::getAPsByApId($users->ap_id);
            $gpData = GramPanchyat::getGPsByGpId($users->gp_id);
            $whereArray = [
                ['osr_non_tax_asset_entries.osr_asset_branch_id', '=', $branch_id],
                ['osr_non_tax_asset_entries.gram_panchayat_id', '=', $users->gp_id],
                ['osr_non_tax_asset_entries.asset_under', '=', "GP"],
            ];

            $parent_name = $gpData->gram_panchayat_name;
        } else {
            return redirect()->route('dashboard');
        }


        $assetList = OsrNonTaxAssetEntry::join('zila_parishads AS z', 'osr_non_tax_asset_entries.zila_id', '=', 'z.id')
            ->join('anchalik_parishads AS a', 'osr_non_tax_asset_entries.anchalik_id', '=', 'a.id')
            ->join('gram_panchyats AS g', 'osr_non_tax_asset_entries.gram_panchayat_id', '=', 'g.gram_panchyat_id')
            ->leftjoin('villages AS v', 'osr_non_tax_asset_entries.village_id', '=', 'v.id')
            ->where($whereArray)
            ->distinct()
            ->select('z.zila_parishad_name', 'a.anchalik_parishad_name', 'g.gram_panchayat_name', 'v.village_name', 'osr_non_tax_asset_entries.*')
            ->orderBy('osr_non_tax_asset_entries.id', 'desc')
            ->get();

        $max_fy_id = OsrMasterFyYear::getMaxFyYear();

        $data = [
            'fy_id' => $max_fy_id,
            'parent_name' => $parent_name
        ];
        $marketNatures = DB::table('osr_market_natures')->select('id', 'nature_name')->get();
        $marketCategories = DB::table('osr_market_categories')->select('id', 'category_name')->get();
        return view('Osr.non_tax.asset.dw_asset_list', compact('zpData', 'apData', 'gpData', 'assetList', 'branchData', 'data', 'marketNatures', 'marketCategories', 'branch_id'));
    }

    //------------------------------------------------------------------------------------------------------------------
    //----------------------------------- OSR NON TAX DISTRICT WISE ASSET LIST [BRANCH]---------------------------------
    //------------------------------------------------------------------------------------------------------------------
    public function nt_dw_asset_show_list(Request $request, $branch_id, $level)
    {

        $users = Auth::user();

        $zpData = District::getZilaByDistrictId($users->district_code);

        if (!$zpData) {
            return redirect()->route('dashboard');
        }

        $branch_id = base64_decode(base64_decode($branch_id));
        $level = base64_decode(base64_decode($level));
        $branchData = OsrMasterNonTaxBranch::getBranchById($branch_id);

        if (!$branchData) {
            return redirect()->route('dashboard');
        }

        $apData = AnchalikParishad::getAPsByZilaId($zpData->id);

        if ($users->mdas_master_role_id == 2) {

            if (!in_array($level, ["AP", "GP"])) {
                return redirect()->route('dashboard');
            }
            $whereArray = [
                ['osr_non_tax_asset_entries.osr_asset_branch_id', '=', $branch_id],
                ['osr_non_tax_asset_entries.zila_id', '=', $users->zp_id],
                ['osr_non_tax_asset_entries.asset_under', '=', $level],
            ];
        } elseif ($users->mdas_master_role_id == 3) {
            if ($level <> "GP") {
                return redirect()->route('dashboard');
            }
            $whereArray = [
                ['osr_non_tax_asset_entries.osr_asset_branch_id', '=', $branch_id],
                ['osr_non_tax_asset_entries.anchalik_id', '=', $users->ap_id],
                ['osr_non_tax_asset_entries.asset_under', '=', "GP"],
            ];
        } else {
            return redirect()->route('dashboard');
        }

        $assetList = OsrNonTaxAssetEntry::join('zila_parishads AS z', 'osr_non_tax_asset_entries.zila_id', '=', 'z.id')
            ->join('anchalik_parishads AS a', 'osr_non_tax_asset_entries.anchalik_id', '=', 'a.id')
            ->join('gram_panchyats AS g', 'osr_non_tax_asset_entries.gram_panchayat_id', '=', 'g.gram_panchyat_id')
            ->join('villages AS v', 'osr_non_tax_asset_entries.village_id', '=', 'v.id')
            ->where($whereArray)
            ->distinct()
            ->select('z.zila_parishad_name', 'a.anchalik_parishad_name', 'g.gram_panchayat_name', 'v.village_name', 'osr_non_tax_asset_entries.*')
            ->orderBy('osr_non_tax_asset_entries.id', 'desc')
            ->get();

        $max_fy_id = OsrMasterFyYear::getMaxFyYear();

        $data = [
            'fy_id' => $max_fy_id
        ];

        return view('Osr.non_tax.asset.dw_asset_show_list', compact('data', 'zpData', 'apData', 'assetList', 'branchData'));
    }

    //------------------------------------------------------------------------------------------------------------------
    //----------------------------------- ASSET SHORTLIST --------------------------------------------------------------
    //------------------------------------------------------------------------------------------------------------------
    public function asset_shortlist_bidding(Request $request)
    {
        $users = Auth::user();
        $branchList = OsrMasterNonTaxBranch::get_branches();
        $fyList = OsrMasterFyYear::getAllYears();

        $data = [
            'data' => NULL,
            'shortlist' => 0,
            'searchText' => NULL,
            'shortlistAssetList' => [],
            'branchList' => $branchList,
            'fyList' => $fyList,
            'branch_id' => NULL,
            'data_fy_id' => NULL,
            'assetList' => []
        ];

        if ($request->isMethod('post')) {

            $fy_id = $request->input('fy_id');
            $branch_id = $request->input('branch_id');
            $fyData = OsrMasterFyYear::getFyYear($fy_id);
            $branchData = OsrMasterNonTaxBranch::getBranchById($branch_id);

            if ($users->mdas_master_role_id == 2) { // ZP ADMIN
                $level = "ZP";
                $id = $users->zp_id;
                $whereArray = [
                    ['osr_non_tax_asset_entries.osr_asset_branch_id', '=', $branch_id],
                    ['osr_non_tax_asset_entries.zila_id', '=', $id],
                    ['s.level', '=', $level],
                    ['osr_non_tax_asset_entries.asset_listing_date', '<=', $fyData->fy_to],
                ];
            } elseif ($users->mdas_master_role_id == 3) { // AP ADMIN
                $level = "AP";
                $id = $users->ap_id;
                $whereArray = [
                    ['osr_non_tax_asset_entries.osr_asset_branch_id', '=', $branch_id],
                    ['osr_non_tax_asset_entries.anchalik_id', '=', $id],
                    ['s.level', '=', $level],
                    ['osr_non_tax_asset_entries.asset_listing_date', '<=', $fyData->fy_to],
                ];
            } elseif ($users->mdas_master_role_id == 4) { // GP ADMIN
                $level = "GP";
                $id = $users->gp_id;
                $whereArray = [
                    ['osr_non_tax_asset_entries.osr_asset_branch_id', '=', $branch_id],
                    ['osr_non_tax_asset_entries.gram_panchayat_id', '=', $id],
                    ['s.level', '=', $level],
                    ['osr_non_tax_asset_entries.asset_listing_date', '<=', $fyData->fy_to],
                ];
            } else {
                return redirect()->route('dashboard');
            }

            $shortAssetList = OsrNonTaxAssetShortlist::getShortlistAsset($fy_id, $branch_id, $level, $id);

            if (count($shortAssetList) > 0) {
                $data['shortlist'] = 1;
                $shortlistAssetList = [];
                foreach ($shortAssetList as $li) {
                    array_push($shortlistAssetList, $li->asset_code);
                }
                $data['shortlistAssetList'] = $shortlistAssetList;
                $data['searchText'] = "Selection is successfully done for " . $branchData->branch_name . " on " . $fyData->fy_name;
            } else {
                $data['searchText'] = "Selection is pending for " . $branchData->branch_name . " on " . $fyData->fy_name;
            }

            $assetList = OsrNonTaxAssetEntry::join('zila_parishads AS z', 'osr_non_tax_asset_entries.zila_id', '=', 'z.id')
                ->join('anchalik_parishads AS a', 'osr_non_tax_asset_entries.anchalik_id', '=', 'a.id')
                ->join('gram_panchyats AS g', 'osr_non_tax_asset_entries.gram_panchayat_id', '=', 'g.gram_panchyat_id')
                ->join('villages AS v', 'osr_non_tax_asset_entries.village_id', '=', 'v.id')
                ->where($whereArray)
                ->distinct()
                ->select('z.zila_parishad_name', 'a.anchalik_parishad_name', 'g.gram_panchayat_name', 'v.village_name', 'osr_non_tax_asset_entries.*')
                ->orderBy('osr_non_tax_asset_entries.id', 'desc')
                ->get();

            $data['data'] = 1;
            $data['branchData'] = $branchData;
            $data['fyData'] = $fyData;
            $data['branch_id'] = $branch_id;
            $data['data_fy_id'] = $fy_id;
            $data['assetList'] = $assetList;
        }

        $max_fy_id = OsrMasterFyYear::getMaxFyYear();

        $data['fy_id'] = $max_fy_id;

        return view('Osr.non_tax.asset.asset_shortlist_bidding', compact('data'));
    }

    public function asset_shortlist_entry(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";
        $assetCodeList = [];
        $users = Auth::user();

        DB::beginTransaction();
        try {

            $assetCodeList = $request->input('my-select');
            $branch_id = $request->input('branch_id');
            $fy_id = $request->input('fy_id');

            $fyData = OsrMasterFyYear::getFyYear($fy_id);

            if (count($assetCodeList) == 0 || !$fyData) {
                $returnData['msg'] = "Oops! Something went wrong!";
                return response()->json($returnData);
            }

            if ($users->mdas_master_role_id == 2) { //ZP ADMIN
                $level = "ZP";
                $id = $users->zp_id;
            } elseif ($users->mdas_master_role_id == 3) { //AP ADMIN
                $level = "AP";
                $id = $users->ap_id;
            } elseif ($users->mdas_master_role_id == 4) { //GP ADMIN
                $level = "GP";
                $id = $users->gp_id;
            } else {
                $returnData['msg'] = "Access Denied!";
                return response()->json($returnData);
            }

            $shortAssetList = OsrNonTaxAssetShortlist::getShortlistAsset($fy_id, $branch_id, $level, $id);

            if (count($shortAssetList) > 0) {
                $returnData['msg'] = "Already done the task.";
                return response()->json($returnData);
            }

            $checkAssetEntry = OsrNonTaxAssetEntry::checkAssetListForShortlist($branch_id, $fyData, $assetCodeList, $level, $id);

            if (!$checkAssetEntry) {
                $returnData['msg'] = "Asset Mismatch!";
                return response()->json($returnData);
            }

            foreach ($assetCodeList as $assetCode) {
                $newEntry = new OsrNonTaxAssetShortlist();
                $newEntry->osr_master_non_tax_branch_id = $branch_id;
                $newEntry->asset_code = $assetCode;
                $newEntry->osr_master_fy_year_id = $fy_id;

                $newEntry->level = $level;

                if ($level == "ZP") {
                    $newEntry->zp_id = $users->zp_id;
                    $newEntry->ap_id = NULL;
                    $newEntry->gp_id = NULL;
                } elseif ($level == "AP") {
                    $newEntry->zp_id = $users->zp_id;
                    $newEntry->ap_id = $users->ap_id;
                    $newEntry->gp_id = NULL;
                } else {
                    $newEntry->zp_id = $users->zp_id;
                    $newEntry->ap_id = $users->ap_id;
                    $newEntry->gp_id = $users->gp_id;
                }

                $newEntry->created_by = $users->username;

                if (!$newEntry->save()) {
                    DB::rollback();
                    return response()->json($returnData);
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Server Exception.";
        }
        DB::commit();
        $returnData['msgType'] = true;
        $returnData['msg'] = "Successfully added";
        $returnData['data'] = [];
        return response()->json($returnData);
    }

    public function asset_shortlist_bidding_update_payment(Request $request)
    {
        $users = Auth::user();
        $branchList = OsrMasterNonTaxBranch::get_branches();
        $fyList = OsrMasterFyYear::getAllYears();

        $head_txt = NULL;

        $data = [
            'data' => NULL,
            'head_txt' => $head_txt,
            'shortlist' => 0,
            'shortlistAssetList' => [],
            'branchList' => $branchList,
            'fyList' => $fyList,
            'branch_id' => NULL,
            'data_fy_id' => NULL,
            'assetList' => []
        ];

        if ($request->isMethod('post')) {



            $fy_id = $request->input('fy_id');
            $branch_id = $request->input('branch_id');
            $fyData = OsrMasterFyYear::getFyYear($fy_id);
            $branchData = OsrMasterNonTaxBranch::getBranchById($branch_id);

            if ($users->mdas_master_role_id == 2) { // ZP ADMIN
                $level = "ZP";
                $id = $users->zp_id;
                $whereArray = [
                    ['s.osr_master_non_tax_branch_id', '=', $branch_id],
                    ['s.zp_id', '=', $id],
                    ['s.level', '=', $level],
                    ['s.osr_master_fy_year_id', '=', $fy_id],

                ];


                $zpData = ZilaParishad::getZPName($id);
                $head_txt = "Showing the list of shortlisted " . $branchData->branch_name . " of Zila Parishad : " . $zpData->zila_parishad_name . " for the " . $fyData->fy_name;
            } elseif ($users->mdas_master_role_id == 3) { // AP ADMIN
                $level = "AP";
                $id = $users->ap_id;
                $whereArray = [
                    ['s.osr_master_non_tax_branch_id', '=', $branch_id],
                    ['s.ap_id', '=', $id],
                    ['s.level', '=', $level],
                    ['s.osr_master_fy_year_id', '=', $fy_id],

                ];
                $apData = AnchalikParishad::getAPName($id);
                $head_txt = "Showing the list of shortlisted " . $branchData->branch_name . " of Anchalik Panchayat : " . $apData->anchalik_parishad_name . " for the " . $fyData->fy_name;
            } elseif ($users->mdas_master_role_id == 4) { // GP ADMIN
                $level = "GP";
                $id = $users->gp_id;
                $whereArray = [
                    ['s.osr_master_non_tax_branch_id', '=', $branch_id],
                    ['s.gp_id', '=', $id],
                    ['s.level', '=', $level],
                    ['s.osr_master_fy_year_id', '=', $fy_id],

                ];
                $gpData = GramPanchyat::getGPName($id);
                $head_txt = "Showing the list of shortlisted " . $branchData->branch_name . " of Gram Panchayat : " . $gpData->gram_panchayat_name . " for the " . $fyData->fy_name;
            } else {
                return redirect()->route('dashboard');
            }

            $shortAssetList = OsrNonTaxAssetShortlist::getShortlistAsset($fy_id, $branch_id, $level, $id);

            if (count($shortAssetList) > 0) {
                $data['shortlist'] = 1;
                $shortlistAssetList = [];
                foreach ($shortAssetList as $li) {
                    array_push($shortlistAssetList, $li->asset_code);
                }
                $data['shortlistAssetList'] = $shortlistAssetList;
            }

            $assetList = OsrNonTaxAssetEntry::join('osr_non_tax_asset_shortlists AS s', 'osr_non_tax_asset_entries.asset_code', '=', 's.asset_code')
                ->leftJoin('osr_non_tax_asset_final_records AS f', function ($join) {
                    $join->on('f.fy_id', '=', 's.osr_master_fy_year_id');
                    $join->on('s.asset_code', '=', 'f.asset_code');
                })
                ->where($whereArray)
                ->select('s.asset_code AS asset_code_n', 'osr_non_tax_asset_entries.asset_name', 'f.*')
                ->orderBy('s.id', 'desc')
                ->get();


            $data['data'] = 1;
            $data['branchData'] = $branchData;
            $data['fyData'] = $fyData;
            $data['branch_id'] = $branch_id;
            $data['data_fy_id'] = $fy_id;
            $data['assetList'] = $assetList;
            $data['head_txt'] = $head_txt;
        }


        $max_fy_id = OsrMasterFyYear::getMaxFyYear();
        $data['fy_id'] = $max_fy_id;
        // dd($data['assetList'] = $assetList);
        return view('Osr.non_tax.asset.asset_shortlist_bidding_update_payment', compact('data'));
    }

    public function asset_download_upload()
    {

        $max_fy_id = OsrMasterFyYear::getMaxFyYear();

        $fyList = OsrMasterFyYear::all();
        $asset_signed_report = NULL;
        $users = Auth::user();

        $z_id = $users->zp_id;

        $data = [
            'fy_id' => $max_fy_id,
            'fyList' => $fyList,
            'users' => $users,
        ];
        $imgUrl = ConfigMdas::allActiveList()->imgUrl;

        return view('Osr.non_tax.asset.asset_download_upload', compact('data', 'z_id', 'imgUrl'));
    }

    public function shortlistReportView(Request $request, $fy_id, $z_id)
    {

        $fy_id = Crypt::decrypt($fy_id);
        $z_id = Crypt::decrypt($z_id);

        $report_attachment = OsrNonTaxSignedAssetReport::where([
            ['osr_fy_year_id', '=', $fy_id],
            ['zila_id', '=', $z_id],
        ])->select('attachment_path')
            ->first();

        $imgUrl = ConfigMdas::allActiveList()->imgUrl;
        return response()->file(storage_path('app/' . $report_attachment->attachment_path));
    }
}
