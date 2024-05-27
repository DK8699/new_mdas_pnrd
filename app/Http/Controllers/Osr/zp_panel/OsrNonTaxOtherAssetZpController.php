<?php

namespace App\Http\Controllers\Osr\zp_panel;

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


class OsrNonTaxOtherAssetZpController extends Controller
{
    
    //-----------------------------------Collection------------------------------------------------

    
    public function ap_list_other_asset_collection($fy_id){
        
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apList = AnchalikParishad::getAPsByZilaId($users->zp_id);

        $resArray=[];

        $resList = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries',                 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'AP'],
                ['a_entries.zila_id', '=', $users->zp_id],
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
                'a_entries.anchalik_id as ap_id')
            ->groupBy('a_entries.anchalik_id')
            ->get();

        foreach($resList AS $li){

            $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
            $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
            $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;

            $total_revenue_collection=$zp_share+$ap_share+$gp_share;

            $resArray[$li->ap_id]=[
                'tot_c'=>$total_revenue_collection
            ];
        }

        foreach ($apList AS $ap){
            if(!isset($resArray[$ap->id])){
                $resArray[$ap->id]=[
                    'tot_c'=>0
                ];
            }
        }

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'apList'=>$apList,
            'resArray'=>$resArray,
        ];
             
        return view('Osr.non_tax.other_asset.zp.ap_list_other_asset_collection',compact('data'));
    }
    
    public function gp_list_other_asset_collection($fy_id){
        
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apList = AnchalikParishad::getAPsByZilaId($users->zp_id);
        $gpList = GramPanchyat::getGPsByZpId($users->zp_id);

        $resArray=[];

        $resList = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries',                 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'GP'],
                ['a_entries.zila_id', '=', $users->zp_id],
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
            if(!isset($resArray[$gp->gram_panchyat_id])){
                $resArray[$gp->gram_panchyat_id]=[
                    'tot_c'=>0
                ];
            }
        }

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'apList'=>$apList,
            'gpList'=>$gpList,
            'resArray'=>$resArray,
        ];

             
        return view('Osr.non_tax.other_asset.zp.gp_list_other_asset_collection',compact('data'));
    }

    public function ap_other_asset_collection($fy_id, $ap_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $ap_id=decrypt($ap_id);
        $apData = AnchalikParishad::getAPName($ap_id);

        $users=Auth::user();

        $assetCategory = OsrNonTaxMasterAssetCategory::getAssetCategory();
        $zpData = ZilaParishad::getZPName($users->zp_id);


        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'assetCategory'=>$assetCategory,
            'zpData'=>$zpData,
            'apData'=>$apData,
        ];

        return view('Osr.non_tax.other_asset.zp.ap_other_asset_collection', compact('data'));
    }
    
    public function gp_other_asset_collection($fy_id, $ap_id, $gp_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $ap_id=decrypt($ap_id);
        $apData = AnchalikParishad::getAPName($ap_id);

        $gp_id=decrypt($gp_id);
        $gpData = GramPanchyat::getGPName($gp_id);

        $users=Auth::user();

        $assetCategory = OsrNonTaxMasterAssetCategory::getAssetCategory();
        $zpData = ZilaParishad::getZPName($users->zp_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'assetCategory'=>$assetCategory,
            'zpData'=>$zpData,
            'ap_id'=>$ap_id,
            'apData'=>$apData,
            'gp_id'=>$gp_id,
            'gpData'=>$gpData,
        ];

        return view('Osr.non_tax.other_asset.zp.gp_other_asset_collection', compact('data'));
    }
    
    
    //--------------------------------------Share-------------------------------------------------
    
    public function zp_other_asset_share($fy_id){
        
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $assetCategory = OsrNonTaxMasterAssetCategory::getAssetCategory();
        
        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'assetCategory'=>$assetCategory,
        ];
        
             
        return view('Osr.non_tax.other_asset.zp.zp_other_asset_share',compact('data'));
    }
    
    public function ap_list_other_asset_share($fy_id){
        
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apList = AnchalikParishad::getAPsByZilaId($users->zp_id);

        $resArray=[];

        $resList = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries',                 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'AP'],
                ['a_entries.zila_id', '=', $users->zp_id],
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
                'a_entries.anchalik_id as ap_id')
            ->groupBy('a_entries.anchalik_id')
            ->get();

        foreach($resList AS $li){

            $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
            $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
            $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;

            $total_revenue_collection=$zp_share+$ap_share+$gp_share;

            $resArray[$li->ap_id]=[
                'tot_r_c'=>$total_revenue_collection, 'zp_share'=>$zp_share, 'ap_share'=>$ap_share, 'gp_share'=> $gp_share
            ];
        }

        foreach ($apList AS $ap){
            if(!isset($resArray[$ap->id])){
                $resArray[$ap->id]=[
                    'tot_r_c'=>0, 'zp_share'=>0, 'ap_share'=>0, 'gp_share'=>0
                ];
            }
        }

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'apList'=>$apList,
            'resArray'=>$resArray,
        ];

        return view('Osr.non_tax.other_asset.zp.ap_list_other_asset_share',compact('data'));
    }
    
    public function gp_list_other_asset_share($fy_id){
        
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apList = AnchalikParishad::getAPsByZilaId($users->zp_id);
        $gpList = GramPanchyat::getGPsByZpId($users->zp_id);

        $resArray=[];

        $resList = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries',                 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'GP'],
                ['a_entries.zila_id', '=', $users->zp_id],
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
                'tot_r_c'=>$total_revenue_collection, 'zp_share'=>$zp_share, 'ap_share'=>$ap_share, 'gp_share'=> $gp_share
            ];
        }

        foreach ($gpList AS $gp){
            if(!isset($resArray[$gp->gram_panchyat_id])){
                $resArray[$gp->gram_panchyat_id]=[
                    'tot_r_c'=>0, 'zp_share'=>0, 'ap_share'=>0, 'gp_share'=> 0
                ];
            }
        }

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'apList'=>$apList,
            'gpList'=>$gpList,
            'resArray'=>$resArray
        ];
        
             
        return view('Osr.non_tax.other_asset.zp.gp_list_other_asset_share',compact('data'));
        
        
    }

    public function ap_other_asset_share($fy_id, $ap_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $ap_id=decrypt($ap_id);
        $apData = AnchalikParishad::getAPName($ap_id);

        $users=Auth::user();

        $assetCategory = OsrNonTaxMasterAssetCategory::getAssetCategory();
        $zpData = ZilaParishad::getZPName($users->zp_id);


        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'assetCategory'=>$assetCategory,
            'zpData'=>$zpData,
            'apData'=>$apData,
        ];

        return view('Osr.non_tax.other_asset.zp.ap_other_asset_share', compact('data'));
    }
    
    public function gp_other_asset_share($fy_id, $ap_id, $gp_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $ap_id=decrypt($ap_id);
        $apData = AnchalikParishad::getAPName($ap_id);

        $gp_id=decrypt($gp_id);
        $gpData = GramPanchyat::getGPName($gp_id);

        $users=Auth::user();

        $assetCategory = OsrNonTaxMasterAssetCategory::getAssetCategory();
        $zpData = ZilaParishad::getZPName($users->zp_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'assetCategory'=>$assetCategory,
            'zpData'=>$zpData,
            'ap_id'=>$ap_id,
            'apData'=>$apData,
            'gp_id'=>$gp_id,
            'gpData'=>$gpData,
        ];

        return view('Osr.non_tax.other_asset.zp.gp_other_asset_share', compact('data'));
    }


    //---------------------------------COMMON-----------------------------------------------------

    public function cat_list_revenue_share($fy_id, $page_for, $level, $zp_id, $ap_id=NULL, $gp_id=NULL){

        $fy_id=decrypt($fy_id);
        $page_for=decrypt($page_for);
        $level=decrypt($level);
        $zp_id=decrypt($zp_id);
        $ap_id=decrypt($ap_id);
        $gp_id=decrypt($gp_id);
        $apData = NULL;
        $gpData = NULL;
        $resArray=[];

        $page_for_list=["REVENUE", "SHARE"];
        $level_list=["ZP", "AP", "GP"];


        $fyData = OsrMasterFyYear::getFyYear($fy_id);
        $users=Auth::user();

        $assetCategory = OsrNonTaxMasterAssetCategory::getAssetCategory();
        $zpData = ZilaParishad::getZPName($users->zp_id);

        if(!in_array($page_for, $page_for_list) || !in_array($level, $level_list)){

        }

        if($level=="ZP"){
            $whereArray=[
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'ZP'],
                ['a_entries.zila_id', '=', $zp_id],
            ];
        }elseif($level=="AP"){
            $apData = AnchalikParishad::getAPName($ap_id);
            $whereArray=[
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'AP'],
                ['a_entries.zila_id', '=', $zp_id],
                ['a_entries.anchalik_id', '=', $ap_id],
            ];
        }else{
            $apData = AnchalikParishad::getAPName($ap_id);
            $gpData = GramPanchyat::getGPName($gp_id);
            $whereArray=[
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'GP'],
                ['a_entries.zila_id', '=', $zp_id],
                ['a_entries.anchalik_id', '=', $ap_id],
                ['a_entries.gram_panchayat_id', '=', $gp_id],
            ];
        }

        //echo json_encode($whereArray);

        $resList = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries',                 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where($whereArray)
            ->select(DB::raw('
                            sum(osr_non_tax_other_asset_final_records.tot_self_collected_amt) AS tot_self_collected_amt,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_collected_amt) AS tot_ag_collected_amt,
                                             
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'),
                'a_entries.osr_non_tax_master_asset_category_id as c_id')
            ->groupBy('a_entries.osr_non_tax_master_asset_category_id')
            ->get();

        foreach($resList AS $li){

            if($page_for=="REVENUE"){

                $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
                $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
                $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;

                $total_revenue_collection=$zp_share+$ap_share+$gp_share;

                $resArray[$li->c_id]=[
                    'tot_c'=>$total_revenue_collection
                ];

            }else{
                $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
                $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
                $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;

                $total_revenue_collection=$zp_share+$ap_share+$gp_share;

                $resArray[$li->c_id]=[
                    'tot_r_c'=>$total_revenue_collection, 'zp_share'=>$zp_share, 'ap_share'=>$ap_share, 'gp_share'=> $gp_share
                ];
            }
        }

        foreach ($assetCategory AS $cat){
            if(!isset($resArray[$cat->id])){
                if($page_for=="REVENUE") {
                    $resArray[$cat->id]=[
                        'tot_c'=>0
                    ];
                }else{
                    $resArray[$cat->id] = [
                        'tot_r_c' =>0, 'zp_share' =>0, 'ap_share' =>0, 'gp_share' =>0
                    ];
                }
            }
        }

        if($page_for=="REVENUE"){
            if($level=="ZP"){
                $head_txt="Other Asset Revenue Collection of Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }elseif ($level=="AP"){
                $head_txt="Other Asset Revenue Collection of Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }else{
                $head_txt="Other Asset Revenue Collection of Gram Panchayat : ".$gpData->gram_panchayat_name.", Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }
        }else{
            if($level=="ZP"){
                $head_txt="Other Asset Share Distribution of Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }elseif ($level=="AP"){
                $head_txt="Other Asset Share Distribution of Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : $zpData->zila_parishad_name (".$fyData->fy_name.")";
            }else{
                $head_txt="Other Asset Share Distribution of Gram Panchayat : ".$gpData->gram_panchayat_name.", Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }
        }

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'assetCategory'=>$assetCategory,
            'resArray'=>$resArray,
            'head_txt'=>$head_txt,
            'page_for'=>$page_for,
        ];

        return view('Osr.non_tax.other_asset.common.cat_list_revenue_share',compact('data'));
    }
    
}
