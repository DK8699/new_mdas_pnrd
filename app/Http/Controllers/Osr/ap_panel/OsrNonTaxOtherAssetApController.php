<?php

namespace App\Http\Controllers\Osr\ap_panel;

use App\CommonModels\District;
use App\Osr\OsrMasterFyYear;
use App\CommonModels\Village;
use App\Osr\OsrNonTaxMasterAssetCategory;
use App\Osr\OsrNonTaxOtherAssetEntry;
use App\CommonModels\ZilaParishad;
use App\CommonModels\AnchalikParishad;
use App\CommonModels\GramPanchyat;
use App\Osr\OsrNonTaxOtherAssetFinalRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;

class OsrNonTaxOtherAssetApController extends Controller
{
    //--------------------------------------Collection-----------------------------------------------
    
    public function ap_other_asset_collection($fy_id){
        
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);
        $users=Auth::user();

        $assetCategory = OsrNonTaxMasterAssetCategory::getAssetCategory();
        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'assetCategory'=>$assetCategory,
        ];
             
        return view('Osr.non_tax.other_asset.ap.ap_other_asset_collection',compact('data'));
        
    }
    
    public function gp_list_other_asset_collection($fy_id){
        
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);
        $gpList = GramPanchyat::getGpsByAnchalikId($users->ap_id);

        $resArray=[];

        $resList = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries',                 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'GP'],
                ['a_entries.zila_id', '=', $users->zp_id],
                ['a_entries.anchalik_id', '=', $users->ap_id],
            ])
            ->select(DB::raw('
                            sum(osr_non_tax_other_asset_final_records.tot_self_collected_amt) AS tot_self_collected_amt,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_collected_amt) AS tot_ag_collected_amt,
                                             
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'),
                'a_entries.gram_panchayat_id AS gp_id')
            ->groupBy('a_entries.gram_panchayat_id')
            ->get();

        foreach($resList AS $li){

            $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
            $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
            $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;

            $total_revenue_collection=$zp_share+$ap_share+$gp_share;

            $resArray[$li->gp_id]=[
                'tot_c'=>$total_revenue_collection
            ];
        }

        foreach ($gpList AS $gp){
            if(!isset($resArray[$gp->id])){
                $resArray[$gp->id]=[
                    'tot_c'=>0
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

             
        return view('Osr.non_tax.other_asset.ap.gp_list_other_asset_collection',compact('data'));
        
        
    }
    
    public function gp_other_asset_collection($fy_id, $gp_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);
        

        $gp_id=decrypt($gp_id);
        $gpData = GramPanchyat::getGPName($gp_id);
        
        
        $users=Auth::user();

        $assetCategory = OsrNonTaxMasterAssetCategory::getAssetCategory();
        $apData = AnchalikParishad::getAPName($users->ap_id);
        $zpData = ZilaParishad::getZPName($users->zp_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'assetCategory'=>$assetCategory,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'gp_id'=>$gp_id,
            'gpData'=>$gpData,
        ];

        return view('Osr.non_tax.other_asset.ap.gp_other_asset_collection', compact('data'));
    }
    
    
    //------------------------------Share------------------------------------------------------------
    
    public function ap_other_asset_share($fy_id){
        
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);
        $users=Auth::user();

        $assetCategory = OsrNonTaxMasterAssetCategory::getAssetCategory();
        
        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'assetCategory'=>$assetCategory,
        ];
        
        return view('Osr.non_tax.other_asset.ap.ap_other_asset_share',compact('data'));
        
    }
    
    public function gp_list_other_asset_share($fy_id){
        
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);
        $gpList = GramPanchyat::getGpsByAnchalikId($users->ap_id);

        $resArray=[];

        $resList = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries',                 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'GP'],
                ['a_entries.zila_id', '=', $users->zp_id],
                ['a_entries.anchalik_id', '=', $users->ap_id],
            ])
            ->select(DB::raw('
                            sum(osr_non_tax_other_asset_final_records.tot_self_collected_amt) AS tot_self_collected_amt,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_collected_amt) AS tot_ag_collected_amt,
                                             
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'),
                'a_entries.gram_panchayat_id AS gp_id')
            ->groupBy('a_entries.gram_panchayat_id')
            ->get();

        foreach($resList AS $li){

            $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
            $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
            $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;

            $total_revenue_collection=$zp_share+$ap_share+$gp_share;

            $resArray[$li->gp_id]=[
                'tot_r_c'=>$total_revenue_collection, 'zp_share'=>$zp_share, 'ap_share'=>$ap_share, 'gp_share'=>$gp_share
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
        
             
        return view('Osr.non_tax.other_asset.ap.gp_list_other_asset_share',compact('data'));
        
        
    }
    
    public function gp_other_asset_share($fy_id, $gp_id){

       $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);
        

        $gp_id=decrypt($gp_id);
        $gpData = GramPanchyat::getGPName($gp_id);
        
        
        $users=Auth::user();

        $assetCategory = OsrNonTaxMasterAssetCategory::getAssetCategory();
        $apData = AnchalikParishad::getAPName($users->ap_id);
        $zpData = ZilaParishad::getZPName($users->zp_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'assetCategory'=>$assetCategory,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'gp_id'=>$gp_id,
            'gpData'=>$gpData,
        ];

        return view('Osr.non_tax.other_asset.ap.gp_other_asset_share', compact('data'));
    }
    
    
}
