<?php

namespace App\Http\Controllers\Osr\ap_panel;

use App\CommonModels\District;
use App\CommonModels\GramPanchyat;
use App\CommonModels\Village;
use App\CommonModels\ZilaParishad;
use App\CommonModels\AnchalikParishad;
use App\Osr\OsrMasterNonTaxBranch;
use App\Osr\OsrNonTaxAssetEntry;
use App\Osr\OsrMasterFyYear;
use App\Osr\OsrNonTaxAssetFinalRecord;
use App\Osr\OsrNonTaxAssetSettlement;
use App\Osr\OsrNonTaxAssetShortlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Auth;
use Validator;

class OsrNonTaxAssetApController extends Controller
{
    //---------------------------------------Settlement-----------------------------------------------------------------
    public function ap_asset_settlement_percent($fy_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'apData'=>$apData,
        ];

        return view('Osr.non_tax.asset.ap.ap_asset_settlement_percent', compact('data'));
    }

    public function gp_list_asset_settlement_percent($fy_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);
        $gpList = GramPanchyat::getGpsByAnchalikId($users->ap_id);

        $settled=[];
        $shortlist=[];
        $totalScope=[];


        //TOTAL SCOPE
        $totalData = OsrNonTaxAssetEntry::where([
            ['osr_non_tax_asset_entries.asset_under','=','GP'],
            ['osr_non_tax_asset_entries.anchalik_id','=',$users->ap_id],
            ['asset_listing_date','<',$fyData->fy_to]
        ])->select(DB::raw('count(*) AS total'),'osr_non_tax_asset_entries.gram_panchayat_id as gp_id')
            ->groupBy('osr_non_tax_asset_entries.gram_panchayat_id')
            ->get();

        foreach($totalData AS $li){
            $totalScope[$li->gp_id]=$li->total;
        }

        //SHORLIST

        $shortlistData = OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries','a_entries.asset_code','=','osr_non_tax_asset_shortlists.asset_code')
            ->where([
                ['osr_non_tax_asset_shortlists.level','=','GP'],
                ['a_entries.anchalik_id','=',$users->ap_id],
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id]
            ])->select(DB::raw('count(*) AS total'),'a_entries.gram_panchayat_id as gp_id')
            ->groupBy('a_entries.gram_panchayat_id')
            ->get();

        foreach($shortlistData AS $li){
            $shortlist[$li->gp_id]=$li->total;
        }

        //SETTLEMENT

        $settledData = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_entries.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['a_short.level','=','GP'],
                ['a_short.ap_id','=',$users->ap_id],
				['a_short.osr_master_fy_year_id','=',$fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id]
            ])->select(DB::raw('count(*) AS total'),'a_short.gp_id as gp_id')
            ->groupBy('a_short.gp_id')
            ->get();

        foreach($settledData AS $li){
            $settled[$li->gp_id]=$li->total;
        }



        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'gpList'=>$gpList,
            'totalScope'=>$totalScope,
            'shortlist'=>$shortlist,
            'settled'=>$settled,
        ];

        //echo json_encode($gpList);

        return view('Osr.non_tax.asset.ap.gp_list_asset_settlement_percent', compact('data'));
    }

    public function ap_asset_settlement_percent_branch($fy_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "AP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $users->ap_id],
        ])->get();

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branch_id'=>$branch_id,
            'branchData'=>$branchData,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'assetList'=>$assetList,
        ];


        return view('Osr.non_tax.asset.ap.ap_asset_settlement_percent_branch', compact('data'));
    }

    public function gp_asset_settlement_percent($fy_id, $gp_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $gp_id=decrypt($gp_id);
        $gpData = GramPanchyat::getGPName($gp_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);

        $apData = AnchalikParishad::getAPName($users->ap_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'ap_id'=>$users->ap_id,
            'apData'=>$apData,
            'gp_id'=>$gp_id,
            'gpData'=>$gpData,
        ];

        //echo json_encode($data);

        return view('Osr.non_tax.asset.ap.gp_asset_settlement_percent', compact('data'));
    }

    public function gp_asset_settlement_percent_branch($fy_id, $gp_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $gp_id = decrypt($gp_id);
        $gpData=GramPanchyat::getGPName($gp_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);

        $apData=AnchalikParishad::getAPName($users->ap_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "GP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $users->ap_id],
            ['gram_panchayat_id', '=', $gp_id],
        ])->get();

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branch_id'=>$branch_id,
            'branchData'=>$branchData,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'gpData'=>$gpData,
            'assetList'=>$assetList,
        ];

        return view('Osr.non_tax.asset.ap.gp_asset_settlement_percent_branch', compact('data'));
    }

    //----------------------------------------defaulter------------------------------------------------

    public function ap_asset_defaulter($fy_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'apData'=>$apData,
        ];

        return view('Osr.non_tax.asset.ap.ap_asset_defaulter', compact('data'));
    }

    public function gp_list_asset_defaulter($fy_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);
        $gpList = GramPanchyat::getGpsByAnchalikId($users->ap_id);

        $settled=[];
        $defaulter=[];

        $settledData = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['a_short.level','=','GP'],
                ['a_short.ap_id','=',$users->ap_id],
				['a_short.osr_master_fy_year_id','=',$fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id]
            ])->select(DB::raw('count(*) AS total'),'a_short.gp_id as gp_id')
            ->groupBy('a_entries.gram_panchayat_id')
            ->get();

        foreach($settledData AS $li){
            $settled[$li->gp_id]=$li->total;
        }

        $defaulterData = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id','=',$fy_id],
                ['osr_non_tax_asset_final_records.bidding_status','=',1],
                ['osr_non_tax_asset_final_records.defaulter_status','=',1],
				['a_short.osr_master_fy_year_id','=',$fy_id],
                ['a_short.ap_id', '=', $users->ap_id],
                ['a_short.level', '=', 'GP']
            ])->select(DB::raw('count(*) AS total'),'a_short.gp_id as gp_id')
            ->groupBy('a_short.gp_id')
            ->get();

        foreach($defaulterData AS $li){
            $defaulter[$li->gp_id]=$li->total;
        }

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'gpList'=>$gpList,
            'settled'=>$settled,
            'defaulter'=>$defaulter,
        ];

        //echo json_encode($gpList);

        return view('Osr.non_tax.asset.ap.gp_list_asset_defaulter', compact('data'));
    }

    public function ap_asset_defaulter_branch($fy_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "AP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $users->ap_id],
        ])->get();

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branch_id'=>$branch_id,
            'branchData'=>$branchData,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'assetList'=>$assetList,
        ];


        return view('Osr.non_tax.asset.ap.ap_asset_defaulter_branch', compact('data'));
    }

    public function gp_asset_defaulter($fy_id, $gp_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $gp_id=decrypt($gp_id);
        $gpData = GramPanchyat::getGPName($gp_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);

        $apData = AnchalikParishad::getAPName($users->ap_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'ap_id'=>$users->ap_id,
            'apData'=>$apData,
            'gp_id'=>$gp_id,
            'gpData'=>$gpData,
        ];

        //echo json_encode($data);

        return view('Osr.non_tax.asset.ap.gp_asset_defaulter', compact('data'));
    }

    public function gp_asset_defaulter_branch($fy_id, $gp_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $gp_id = decrypt($gp_id);
        $gpData=GramPanchyat::getGPName($gp_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);

        $apData=AnchalikParishad::getAPName($users->ap_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "GP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $users->ap_id],
            ['gram_panchayat_id', '=', $gp_id],
        ])->get();

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branch_id'=>$branch_id,
            'branchData'=>$branchData,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'gpData'=>$gpData,
            'assetList'=>$assetList,
        ];

        return view('Osr.non_tax.asset.ap.gp_asset_defaulter_branch', compact('data'));
    }

    //-----------------------------------------collection----------------------------------------------


    public function ap_asset_collection($fy_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'apData'=>$apData,
        ];

        return view('Osr.non_tax.asset.ap.ap_asset_collection', compact('data'));
    }

    public function gp_list_asset_collection($fy_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);
        $gpList = GramPanchyat::getGpsByAnchalikId($users->ap_id);

        $resArray=[];
        $resList = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id','=',$fy_id],
                ['a_short.level', '=', 'GP'],
                ['a_short.zp_id', '=', $users->zp_id],
                ['a_short.ap_id', '=', $users->ap_id],
            ])->select(DB::raw('    
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
                            '),'a_short.gp_id AS gp_id')
            ->groupBy('a_short.gp_id')
            ->get();

        foreach($resList AS $li){

            $gap_c=$li->tot_gap_zp_share+$li->tot_gap_ap_share+$li->tot_gap_gp_share;

            $bid_c=$li->tot_ins_collected_amt;

            $tot_c=$gap_c+$bid_c;

            $resArray[$li->gp_id]=[
                'gap_c'=>$gap_c, 'bid_c'=>$bid_c, 'tot_c'=>$tot_c
            ];
        }

        foreach ($gpList AS $gp){
            if(!isset($resArray[$gp->id])){
                $resArray[$gp->id]=[
                    'gap_c'=>0, 'bid_c'=>0, 'tot_c'=>0
                ];
            }
        }

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'gpList'=>$gpList,
            'resArray'=>$resArray,
        ];

        //echo json_encode($gpList);

        return view('Osr.non_tax.asset.ap.gp_list_asset_collection', compact('data'));
    }

    public function ap_asset_collection_branch($fy_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "AP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $users->ap_id],
        ])->get();

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branch_id'=>$branch_id,
            'branchData'=>$branchData,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'assetList'=>$assetList,
        ];


        return view('Osr.non_tax.asset.ap.ap_asset_collection_branch', compact('data'));
    }

    public function gp_asset_collection($fy_id, $gp_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $gp_id=decrypt($gp_id);
        $gpData = GramPanchyat::getGPName($gp_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);

        $apData = AnchalikParishad::getAPName($users->ap_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'ap_id'=>$users->ap_id,
            'apData'=>$apData,
            'gp_id'=>$gp_id,
            'gpData'=>$gpData,
        ];

        //echo json_encode($data);

        return view('Osr.non_tax.asset.ap.gp_asset_collection', compact('data'));
    }

    public function gp_asset_collection_branch($fy_id, $gp_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $gp_id = decrypt($gp_id);
        $gpData=GramPanchyat::getGPName($gp_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);

        $apData=AnchalikParishad::getAPName($users->ap_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "GP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $users->ap_id],
            ['gram_panchayat_id', '=', $gp_id],
        ])->get();

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branch_id'=>$branch_id,
            'branchData'=>$branchData,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'gpData'=>$gpData,
            'assetList'=>$assetList,
        ];

        return view('Osr.non_tax.asset.ap.gp_asset_collection_branch', compact('data'));
    }
    
    //----------------------------------------Share----------------------------------------------------
    
    public function ap_asset_share($fy_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'apData'=>$apData,
        ];

        return view('Osr.non_tax.asset.ap.ap_asset_share', compact('data'));
    }
    
    public function gp_list_asset_share($fy_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);
        $gpList = GramPanchyat::getGpsByAnchalikId($users->ap_id);

        $resArray=[];
        $resList = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'GP'],
                ['a_short.zp_id', '=', $users->zp_id],
                ['a_short.ap_id', '=', $users->ap_id],
            ])->select(DB::raw('
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
                            '),'a_short.gp_id AS gp_id')
            ->groupBy('a_short.gp_id')
            ->get();

        foreach($resList AS $li){

            $zp_share=$li->$li->tot_ins_zp_share;
            $ap_share=$li->$li->tot_ins_ap_share;
            $gp_share=$li->$li->tot_ins_gp_share;

            $total_revenue_collection=$li->tot_ins_collected_amt;

            $resArray[$li->gp_id]=[
                'tot_r_c'=>$total_revenue_collection, 'zp_share'=>$zp_share, 'ap_share'=>$ap_share, 'gp_share'=> $gp_share
            ];
        }

        foreach ($gpList AS $gp){
            if(!isset($resArray[$gp->id])){
                $resArray[$gp->id]=[
                    'tot_r_c'=>0, 'zp_share'=>0, 'ap_share'=>0, 'gp_share'=>0
                ];
            }
        }

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'gpList'=>$gpList,
            'resArray'=>$resArray,
        ];

        //echo json_encode($gpList);

        return view('Osr.non_tax.asset.ap.gp_list_asset_share', compact('data'));
    }

    public function ap_asset_share_branch($fy_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "AP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $users->ap_id],
        ])->get();

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branch_id'=>$branch_id,
            'branchData'=>$branchData,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'assetList'=>$assetList,
        ];


        return view('Osr.non_tax.asset.ap.ap_asset_share_branch', compact('data'));
    }

    public function gp_asset_share($fy_id, $gp_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $gp_id=decrypt($gp_id);
        $gpData = GramPanchyat::getGPName($gp_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);

        $apData = AnchalikParishad::getAPName($users->ap_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'ap_id'=>$users->ap_id,
            'apData'=>$apData,
            'gp_id'=>$gp_id,
            'gpData'=>$gpData,
        ];

        //echo json_encode($data);

        return view('Osr.non_tax.asset.ap.gp_asset_share', compact('data'));
    }

    public function gp_asset_share_branch($fy_id, $gp_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $gp_id = decrypt($gp_id);
        $gpData=GramPanchyat::getGPName($gp_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);

        $apData=AnchalikParishad::getAPName($users->ap_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "GP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $users->ap_id],
            ['gram_panchayat_id', '=', $gp_id],
        ])->get();

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branch_id'=>$branch_id,
            'branchData'=>$branchData,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'gpData'=>$gpData,
            'assetList'=>$assetList,
        ];

        return view('Osr.non_tax.asset.ap.gp_asset_share_branch', compact('data'));
    }
    
    
    
}
