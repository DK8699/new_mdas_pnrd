<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OsrNonTaxOtherAssetEntry extends Model
{
    public static function getByCode($asset_code){
        return OsrNonTaxOtherAssetEntry::where('other_asset_code', $asset_code)->first();
    }


    //--------------------------NEW------------------------------------------------------------------------------------

    public static function dw_other_asset_count(){
        $users=Auth::user();

        $data=[];

        if($users->mdas_master_role_id==2){
            $zpAsset=OsrNonTaxOtherAssetEntry::where([
                ['zila_id', '=', $users->zp_id],
                ['managed_by', '=', "ZP"],
            ])->count();
            $apAsset=OsrNonTaxOtherAssetEntry::where([
                ['zila_id', '=', $users->zp_id],
                ['managed_by', '=', "AP"],
            ])->count();
            $gpAsset=OsrNonTaxOtherAssetEntry::where([
                ['zila_id', '=', $users->zp_id],
                ['managed_by', '=', "GP"],
            ])->count();

            $data=['zpAsset'=>$zpAsset, 'apAsset'=>$apAsset,'gpAsset'=>$gpAsset];

        }elseif($users->mdas_master_role_id==3){

            $apAsset=OsrNonTaxOtherAssetEntry::where([
                ['anchalik_id', '=', $users->ap_id],
                ['managed_by', '=', "AP"],
            ])->count();
            $gpAsset=OsrNonTaxOtherAssetEntry::where([
                ['anchalik_id', '=', $users->ap_id],
                ['managed_by', '=', "GP"],
            ])->count();
            $data=['apAsset'=>$apAsset,'gpAsset'=>$gpAsset];

        }elseif($users->mdas_master_role_id==4){

            $gpAsset=OsrNonTaxOtherAssetEntry::where([
                ['gram_panchayat_id', '=', $users->gp_id],
                ['managed_by', '=', "GP"],
            ])->count();

            $data=['gpAsset'=>$gpAsset];
        }

        return $data;
    }
    public static function dw_cw_asset_count($cat_id){
        $users=Auth::user();

        $data=[];

        if($users->mdas_master_role_id==2){
            $zpAsset=OsrNonTaxOtherAssetEntry::where([
                ['zila_id', '=', $users->zp_id],
                ['managed_by', '=', "ZP"],
                ['osr_non_tax_master_asset_category_id', '=', $cat_id],
            ])->count();

            $data=['zpAsset'=>$zpAsset];
        }elseif($users->mdas_master_role_id==3){

            $apAsset=OsrNonTaxOtherAssetEntry::where([
                ['anchalik_id', '=', $users->ap_id],
                ['managed_by', '=', "AP"],
                ['osr_non_tax_master_asset_category_id', '=', $cat_id],
            ])->count();
            $data=['apAsset'=>$apAsset];
        }elseif($users->mdas_master_role_id==4){

            $gpAsset=OsrNonTaxOtherAssetEntry::where([
                ['gram_panchayat_id', '=', $users->gp_id],
                ['managed_by', '=', "GP"],
                ['osr_non_tax_master_asset_category_id', '=', $cat_id],
            ])->count();
            $data=['gpAsset'=>$gpAsset];
        }

        return $data;
    }
    public static function getAssetEntryById($id){
        return OsrNonTaxOtherAssetEntry::where([
            ['id', '=', $id]
        ])->select('*')
            ->first();
    }


    public static function checkOtherAssetEntry($other_asset_code, $fy_id, $level, $id){

        $fyData=OsrMasterFyYear::getFyYear($fy_id);

        if(!$fyData){
            return false;
        }

        if($level=="ZP"){
            $whereArray=[
                ['other_asset_code', '=', $other_asset_code],
                ['other_asset_listing_date', '<', $fyData->fy_to],

                ['managed_by', '=', $level],
                ['zila_id', '=', $id],
            ];
        }elseif ($level=="AP"){
            $whereArray=[
                ['other_asset_code', '=', $other_asset_code],
                ['other_asset_listing_date', '<', $fyData->fy_to],

                ['managed_by', '=', $level],
                ['anchalik_id', '=', $id],
            ];
        }else{
            $whereArray=[
                ['other_asset_code', '=', $other_asset_code],
                ['other_asset_listing_date', '<', $fyData->fy_to],

                ['managed_by', '=', $level],
                ['gram_panchayat_id', '=', $id],
            ];
        }

        return OsrNonTaxOtherAssetEntry::where($whereArray)->first();

    }
}
