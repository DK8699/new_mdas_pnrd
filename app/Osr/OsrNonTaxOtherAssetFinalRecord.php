<?php

namespace App\Osr;

use App\ConfigMdas;
use Illuminate\Database\Eloquent\Model;
use DB;

class OsrNonTaxOtherAssetFinalRecord extends Model
{
    public static function finalRecordCount($asset_code, $fy_id){
        return OsrNonTaxOtherAssetFinalRecord::where([
            ['other_asset_code', '=', $asset_code],
            ['fy_id', '=', $fy_id],
        ])->count();
    }

    public static function getFinalRecord($asset_code, $fy_id){
        return OsrNonTaxOtherAssetFinalRecord::where([
            ['other_asset_code', '=', $asset_code],
            ['fy_id', '=', $fy_id],
        ])->first();
    }


    //REVENUE COLLECTION

    public static function zpWiseRevenueList($fy_id){

        $finalArray=[];

        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries', 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'ZP'],
            ])
            ->select(DB::raw('               
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'),'a_entries.zila_id as zp_id')
            ->groupBy('a_entries.zila_id')
            ->get();

        foreach($data AS $li){
            $other_c=$li->tot_self_zp_share+$li->tot_self_ap_share+$li->tot_self_gp_share+
                     $li->tot_ag_zp_share+$li->tot_ag_ap_share+$li->tot_ag_gp_share;

            $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
            $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
            $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;

            $finalArray[$li->zp_id]=[
                'other_c'=>$other_c, "zp_share"=>$zp_share, "ap_share"=>$ap_share, "gp_share"=>$gp_share,
            ];
        }

        //echo json_encode($finalArray);

        return $finalArray;
    }

    public static function yrWiseRevenueList($fy_id){
        $finalArray=[
            'other_c'=>0
        ];
        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries', 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'ZP'],
            ])
            ->select(DB::raw('               
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'))
            ->groupBy('osr_non_tax_other_asset_final_records.fy_id')
            ->get();

        foreach($data AS $li){
            $other_c=$li->tot_self_zp_share+$li->tot_self_ap_share+$li->tot_self_gp_share+
                $li->tot_ag_zp_share+$li->tot_ag_ap_share+$li->tot_ag_gp_share;

            $finalArray=[
                'other_c'=>$other_c
            ];
        }

        //echo json_encode($finalArray);

        return $finalArray;
    }

    //REVENUE COLLECTION ENDED

    //SHARE DISTRIBUTION

    public static function zpYrWiseShareList($fy_id){

        $finalArray=[];

        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries',                 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'ZP'],
            ])
            ->select(DB::raw('
                            sum(osr_non_tax_other_asset_final_records.tot_self_collected_amt) AS tot_self_collected_amt,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_collected_amt) AS tot_ag_collected_amt,
                                             
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'),'a_entries.zila_id as zp_id')
            ->groupBy('a_entries.zila_id')
            ->get();

        foreach($data AS $li){

            $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
            $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
            $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;


            $total_revenue_collection=$zp_share+$ap_share+$gp_share;

            $finalArray[$li->zp_id]=[
                'tot_r_c'=>$total_revenue_collection, 'zp_share'=>$zp_share, 'ap_share'=>$ap_share, 'gp_share'=> $gp_share
            ];
        }

        return $finalArray;
    }

    public static function yrWiseShareList($fy_id){

        $finalArray=[
            'tot_r_c'=>0, 'zp_share'=>0, 'ap_share'=>0, 'gp_share'=> 0
        ];

        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries','a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'ZP'],
            ])->select(DB::raw('
                            sum(osr_non_tax_other_asset_final_records.tot_self_collected_amt) AS tot_self_collected_amt,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_collected_amt) AS tot_ag_collected_amt,
                                             
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'))
            ->groupBy('osr_non_tax_other_asset_final_records.fy_id')
            ->get();

        foreach($data AS $li){

            $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
            $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
            $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;

            $total_revenue_collection=$zp_share+$ap_share+$gp_share;

            $finalArray=[
                'tot_r_c'=>$total_revenue_collection, 'zp_share'=>$zp_share, 'ap_share'=>$ap_share, 'gp_share'=> $gp_share
            ];
        }

        return $finalArray;
    }

    //SHARE DISTRIBUTION ENDED


    //AP LEVEL ----------------------------------------------------------------------------------------------------------------

    //Revenue
    public static function getYrZpRevenueData($id, $fy_id){
        $finalArray=[];

        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries', 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'ZP'],
                ['a_entries.zila_id', '=', $id],
            ])
            ->select(DB::raw('               
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'),'a_entries.zila_id as zp_id')
            ->groupBy('a_entries.zila_id')
            ->get();

        foreach($data AS $li){
            $other_c=$li->tot_self_zp_share+$li->tot_self_ap_share+$li->tot_self_gp_share+
                $li->tot_ag_zp_share+$li->tot_ag_ap_share+$li->tot_ag_gp_share;

            $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
            $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
            $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;

            $finalArray[$li->zp_id]=[
                'other_c'=>$other_c, "zp_share"=>$zp_share, "ap_share"=>$ap_share, "gp_share"=>$gp_share,
            ];
        }

        //echo json_encode($finalArray);

        return $finalArray;
    }

    public static function getYrApRevenueList($id, $fy_id){
        $finalArray=[];

        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries', 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'AP'],
                ['a_entries.zila_id', '=', $id],
            ])
            ->select(DB::raw('               
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'),'a_entries.anchalik_id as ap_id')
            ->groupBy('a_entries.anchalik_id')
            ->get();

        foreach($data AS $li){

            $other_c=$li->tot_self_zp_share+$li->tot_self_ap_share+$li->tot_self_gp_share+
                $li->tot_ag_zp_share+$li->tot_ag_ap_share+$li->tot_ag_gp_share;

            $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
            $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
            $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;

            $finalArray[$li->ap_id]=[
                'other_c'=>$other_c, "zp_share"=>$zp_share, "ap_share"=>$ap_share, "gp_share"=>$gp_share,
            ];
        }

        //echo json_encode($finalArray);

        return $finalArray;
    }
    //Share
    public static function getYrZpShareData($id, $fy_id){

        $finalArray=[];

        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries',                 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'ZP'],
                ['a_entries.zila_id', '=', $id],
            ])
            ->select(DB::raw('
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'),'a_entries.zila_id as zp_id')
            ->groupBy('a_entries.zila_id')
            ->get();

        foreach($data AS $li){

            $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
            $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
            $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;


            $total_revenue_collection=$zp_share+$ap_share+$gp_share;

            $finalArray[$li->zp_id]=[
                'tot_r_c'=>$total_revenue_collection, 'zp_share'=>$zp_share, 'ap_share'=>$ap_share, 'gp_share'=> $gp_share
            ];
        }

        return $finalArray;
    }

    public static function getYrApShareList($id, $fy_id){

        $finalArray=[];

        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries',                 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'AP'],
                ['a_entries.zila_id', '=', $id],
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

        foreach($data AS $li){

            $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
            $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
            $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;

            $total_revenue_collection=$zp_share+$ap_share+$gp_share;

            $finalArray[$li->ap_id]=[
                'tot_r_c'=>$total_revenue_collection, 'zp_share'=>$zp_share, 'ap_share'=>$ap_share, 'gp_share'=> $gp_share
            ];
        }

        return $finalArray;
    }


    //GP LEVEL----------------------------------------------------------------------------------------------------------------

    // Revenue
    public static function getYrApRevenueData($id, $ap_id, $fy_id){
        $finalArray=[];

        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries', 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'AP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.anchalik_id', '=', $ap_id],
            ])
            ->select(DB::raw('               
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'),'a_entries.anchalik_id as ap_id')
            ->groupBy('a_entries.anchalik_id')
            ->get();

        foreach($data AS $li){
            $other_c=$li->tot_self_zp_share+$li->tot_self_ap_share+$li->tot_self_gp_share+
                $li->tot_ag_zp_share+$li->tot_ag_ap_share+$li->tot_ag_gp_share;

            $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
            $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
            $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;

            $finalArray[$li->ap_id]=[
                'other_c'=>$other_c, "zp_share"=>$zp_share, "ap_share"=>$ap_share, "gp_share"=>$gp_share,
            ];
        }

        //echo json_encode($finalArray);

        return $finalArray;
    }

    public static function getYrGpRevenueList($id, $ap_id, $fy_id){
        $finalArray=[];

        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries', 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'GP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.anchalik_id', '=', $ap_id],
            ])
            ->select(DB::raw('               
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'),'a_entries.gram_panchayat_id as gp_id')
            ->groupBy('a_entries.gram_panchayat_id')
            ->get();

        foreach($data AS $li){

            $other_c=$li->tot_self_zp_share+$li->tot_self_ap_share+$li->tot_self_gp_share+
                $li->tot_ag_zp_share+$li->tot_ag_ap_share+$li->tot_ag_gp_share;

            $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
            $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
            $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;

            $finalArray[$li->gp_id]=[
                'other_c'=>$other_c, "zp_share"=>$zp_share, "ap_share"=>$ap_share, "gp_share"=>$gp_share,
            ];
        }

        //echo json_encode($finalArray);

        return $finalArray;
    }

    //Share

    public static function getYrApShareData($id, $ap_id, $fy_id){
        $finalArray=[];

        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries', 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'AP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.anchalik_id', '=', $ap_id],
            ])
            ->select(DB::raw('               
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'),'a_entries.anchalik_id as ap_id')
            ->groupBy('a_entries.anchalik_id')
            ->get();

        foreach($data AS $li){
            $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
            $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
            $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;


            $total_revenue_collection=$zp_share+$ap_share+$gp_share;

            $finalArray[$li->ap_id]=[
                'tot_r_c'=>$total_revenue_collection, 'zp_share'=>$zp_share, 'ap_share'=>$ap_share, 'gp_share'=> $gp_share
            ];
        }

        //echo json_encode($finalArray);

        return $finalArray;
    }

    public static function getYrGpShareList($id, $ap_id, $fy_id){
        $finalArray=[];

        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries', 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'GP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.anchalik_id', '=', $ap_id],
            ])
            ->select(DB::raw('               
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'),'a_entries.gram_panchayat_id as gp_id')
            ->groupBy('a_entries.gram_panchayat_id')
            ->get();

        foreach($data AS $li){
            $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
            $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
            $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;

            $total_revenue_collection=$zp_share+$ap_share+$gp_share;

            $finalArray[$li->gp_id]=[
                'tot_r_c'=>$total_revenue_collection, 'zp_share'=>$zp_share, 'ap_share'=>$ap_share, 'gp_share'=> $gp_share
            ];
        }

        //echo json_encode($finalArray);

        return $finalArray;
    }

    //BRANCH LIST-------------------------------------------------------------------------------------------------------

    //REVENUE
    //ZP
    public static function getZpRevenueCatList($id, $fy_id, $list){
        $finalArray=[];

        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries', 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'ZP'],
                ['a_entries.zila_id', '=', $id],
            ])
            ->select(DB::raw('               
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'),'a_entries.osr_non_tax_master_asset_category_id as c_id')
            ->groupBy('a_entries.osr_non_tax_master_asset_category_id')
            ->get();

        foreach($data AS $li){
            $other_c=$li->tot_self_zp_share+$li->tot_self_ap_share+$li->tot_self_gp_share+
                $li->tot_ag_zp_share+$li->tot_ag_ap_share+$li->tot_ag_gp_share;

            $finalArray[$li->c_id]=[
                'other_c'=>ConfigMdas::cur_format($other_c)
            ];
        }


        foreach ($list AS $li){
            if(!isset($finalArray[$li->id])){
                $finalArray[$li->id]=[
                    'other_c'=>0
                ];
            }
        }

        return $finalArray;
    }
    //AP
    public static function getApRevenueCatList($id, $ap_id, $fy_id, $list){
        $finalArray=[];

        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries', 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'AP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.anchalik_id', '=', $ap_id],
            ])
            ->select(DB::raw('               
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'),'a_entries.osr_non_tax_master_asset_category_id as c_id')
            ->groupBy('a_entries.osr_non_tax_master_asset_category_id')
            ->get();

        foreach($data AS $li){
            $other_c=$li->tot_self_zp_share+$li->tot_self_ap_share+$li->tot_self_gp_share+
                $li->tot_ag_zp_share+$li->tot_ag_ap_share+$li->tot_ag_gp_share;

            $finalArray[$li->c_id]=[
                'other_c'=>ConfigMdas::cur_format($other_c)
            ];
        }


        foreach ($list AS $li){
            if(!isset($finalArray[$li->id])){
                $finalArray[$li->id]=[
                    'other_c'=>0
                ];
            }
        }

        return $finalArray;
    }
    //GP
    public static function getGpRevenueCatList($id, $ap_id, $gp_id, $fy_id, $list){
        $finalArray=[];

        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries', 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'GP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.anchalik_id', '=', $ap_id],
                ['a_entries.gram_panchayat_id', '=', $gp_id],
            ])
            ->select(DB::raw('               
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'),'a_entries.osr_non_tax_master_asset_category_id as c_id')
            ->groupBy('a_entries.osr_non_tax_master_asset_category_id')
            ->get();

        foreach($data AS $li){
            $other_c=$li->tot_self_zp_share+$li->tot_self_ap_share+$li->tot_self_gp_share+
                $li->tot_ag_zp_share+$li->tot_ag_ap_share+$li->tot_ag_gp_share;

            $finalArray[$li->c_id]=[
                'other_c'=>ConfigMdas::cur_format($other_c)
            ];
        }


        foreach ($list AS $li){
            if(!isset($finalArray[$li->id])){
                $finalArray[$li->id]=[
                    'other_c'=>0
                ];
            }
        }

        return $finalArray;
    }

    //SHARE
    //ZP
    public static function getZpShareCatList($id, $fy_id, $list){
        $finalArray=[];

        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries', 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'ZP'],
                ['a_entries.zila_id', '=', $id],
            ])
            ->select(DB::raw('               
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'),'a_entries.osr_non_tax_master_asset_category_id as c_id')
            ->groupBy('a_entries.osr_non_tax_master_asset_category_id')
            ->get();

        foreach($data AS $li){
            $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
            $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
            $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;


            $total_revenue_collection=$zp_share+$ap_share+$gp_share;

            $finalArray[$li->c_id]=[
                'tot_r_c'=>ConfigMdas::cur_format($total_revenue_collection), 'zp_share'=>ConfigMdas::cur_format($zp_share),
                'ap_share'=>ConfigMdas::cur_format($ap_share), 'gp_share'=> ConfigMdas::cur_format($gp_share)
            ];
        }


        foreach ($list AS $li){
            if(!isset($finalArray[$li->id])){
                $finalArray[$li->id]=[
                    'tot_r_c'=>0, 'zp_share'=>0, 'ap_share'=>0, 'gp_share'=>0
                ];
            }
        }

        return $finalArray;
    }
    //AP
    public static function getApShareCatList($id, $ap_id, $fy_id, $list){
        $finalArray=[];

        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries', 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'AP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.anchalik_id', '=', $ap_id],
            ])
            ->select(DB::raw('               
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'),'a_entries.osr_non_tax_master_asset_category_id as c_id')
            ->groupBy('a_entries.osr_non_tax_master_asset_category_id')
            ->get();

        foreach($data AS $li){
            $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
            $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
            $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;


            $total_revenue_collection=$zp_share+$ap_share+$gp_share;

            $finalArray[$li->c_id]=[
                'tot_r_c'=>ConfigMdas::cur_format($total_revenue_collection), 'zp_share'=>ConfigMdas::cur_format($zp_share),
                'ap_share'=>ConfigMdas::cur_format($ap_share), 'gp_share'=> ConfigMdas::cur_format($gp_share)
            ];
        }


        foreach ($list AS $li){
            if(!isset($finalArray[$li->id])){
                $finalArray[$li->id]=[
                    'tot_r_c'=>0, 'zp_share'=>0, 'ap_share'=>0, 'gp_share'=>0
                ];
            }
        }

        return $finalArray;
    }
    //GP
    public static function getGpShareCatList($id, $ap_id, $gp_id, $fy_id, $list){
        $finalArray=[];

        $data = OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries as a_entries', 'a_entries.other_asset_code','=','osr_non_tax_other_asset_final_records.other_asset_code')
            ->where([
                ['osr_non_tax_other_asset_final_records.fy_id', '=', $fy_id],
                ['a_entries.managed_by', '=', 'GP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.anchalik_id', '=', $ap_id],
                ['a_entries.gram_panchayat_id', '=', $gp_id],
            ])
            ->select(DB::raw('               
                            sum(osr_non_tax_other_asset_final_records.tot_self_zp_share) AS tot_self_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_ap_share) AS tot_self_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_self_gp_share) AS tot_self_gp_share,
                            
                            sum(osr_non_tax_other_asset_final_records.tot_ag_zp_share) AS tot_ag_zp_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_ap_share) AS tot_ag_ap_share,
                            sum(osr_non_tax_other_asset_final_records.tot_ag_gp_share) AS tot_ag_gp_share'),'a_entries.osr_non_tax_master_asset_category_id as c_id')
            ->groupBy('a_entries.osr_non_tax_master_asset_category_id')
            ->get();

        foreach($data AS $li){
            $zp_share=$li->tot_self_zp_share+$li->tot_ag_zp_share;
            $ap_share=$li->tot_self_ap_share+$li->tot_ag_ap_share;
            $gp_share=$li->tot_self_gp_share+$li->tot_ag_gp_share;
            $total_revenue_collection=$zp_share+$ap_share+$gp_share;

            $finalArray[$li->c_id]=[
                'tot_r_c'=>ConfigMdas::cur_format($total_revenue_collection), 'zp_share'=>ConfigMdas::cur_format($zp_share),
                'ap_share'=>ConfigMdas::cur_format($ap_share), 'gp_share'=> ConfigMdas::cur_format($gp_share)
            ];
        }


        foreach ($list AS $li){
            if(!isset($finalArray[$li->id])){
                $finalArray[$li->id]=[
                    'tot_r_c'=>0, 'zp_share'=>0, 'ap_share'=>0, 'gp_share'=>0
                ];
            }
        }

        return $finalArray;
    }
}
