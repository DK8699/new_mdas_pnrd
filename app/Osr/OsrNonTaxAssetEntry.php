<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;

class OsrNonTaxAssetEntry extends Model
{
    public static function getAssetEntriesByBranchId($branch_id){
        return OsrNonTaxAssetEntry::where([
            ['osr_asset_branch_id', '=', $branch_id]
        ])->select('*')
          ->get();
    }

    public static function getAssetByAssetCode($asset_code){
        return OsrNonTaxAssetEntry::where('asset_code', $asset_code)->first();
    }

    public static function getAssetEntryById($id){
        return OsrNonTaxAssetEntry::where([
            ['id', '=', $id]
        ])->select('*')
          ->first();
    }

    public static function checkZilaMismatch($user_zp_id, $asset_id){
        $returnData['msgType'] = false;
        $assetData= OsrNonTaxAssetEntry::getAssetEntryById($asset_id);

        if(!$assetData){
            $returnData['msg'] = "Opps! Something went wrong!";
            return $returnData;
        }

        if($user_zp_id <> $assetData->zila_id){
            $returnData['msg'] = "Unauthorized access, district mismatch";
            return $returnData;
        }
        $returnData['msgType'] = true;
        return $returnData;
    }

    public static function getGeoTagDataByID($id){
        return OsrNonTaxAssetEntry::where([
            ['id','=',$id]
        ])->select('id','geotag_img_path','geotag_add','geotag_lat','geotag_long','geotag_by','geotag_at')
            ->first();
    }

    public static function isAlreadyApprove($id){
        return OsrNonTaxAssetEntry::where([
            ['id','=',$id]
        ])->select('geo_status_approve')
            ->first()->geo_status_approve;
    }

    public static function getAssetEntryByIdJoinedData($asset_code){
        return OsrNonTaxAssetEntry::join('zila_parishads AS z', 'osr_non_tax_asset_entries.zila_id', '=', 'z.id')
            ->join('anchalik_parishads AS a', 'osr_non_tax_asset_entries.anchalik_id', '=', 'a.id')
            ->join('gram_panchyats AS g', 'osr_non_tax_asset_entries.gram_panchayat_id', '=', 'g.gram_panchyat_id')
            ->join('villages AS v', 'osr_non_tax_asset_entries.village_id', '=', 'v.id')
            ->where([
                ['osr_non_tax_asset_entries.asset_code', '=', $asset_code]
            ])->select('z.zila_parishad_name', 'a.anchalik_parishad_name', 'g.gram_panchayat_name', 'v.village_name', 'osr_non_tax_asset_entries.*')
            ->first();
    }
	public static function getAssetEntryByBranchIdAndZId($z_id,$branch_id){
        return OsrNonTaxAssetEntry::where([
            ['osr_asset_branch_id','=', $branch_id],
            ['zila_id','=', $z_id]
        ])->select('*')
          ->get();
     
     }

     //--------------------------NEW------------------------------------------------------------------------------------

    public static function dw_asset_count(){
	    $users=Auth::user();

	    $data=[];

	    if($users->mdas_master_role_id==2){
            $zpAsset=OsrNonTaxAssetEntry::where([
                ['zila_id', '=', $users->zp_id],
                ['asset_under', '=', "ZP"],
            ])->count();
            $apAsset=OsrNonTaxAssetEntry::where([
                ['zila_id', '=', $users->zp_id],
                ['asset_under', '=', "AP"],
            ])->count();
            $gpAsset=OsrNonTaxAssetEntry::where([
                ['zila_id', '=', $users->zp_id],
                ['asset_under', '=', "GP"],
            ])->count();

            $data=['zpAsset'=>$zpAsset, 'apAsset'=>$apAsset,'gpAsset'=>$gpAsset];

        }elseif($users->mdas_master_role_id==3){

            $apAsset=OsrNonTaxAssetEntry::where([
                ['anchalik_id', '=', $users->ap_id],
                ['asset_under', '=', "AP"],
            ])->count();
            $gpAsset=OsrNonTaxAssetEntry::where([
                ['anchalik_id', '=', $users->ap_id],
                ['asset_under', '=', "GP"],
            ])->count();
            $data=['apAsset'=>$apAsset,'gpAsset'=>$gpAsset];

        }elseif($users->mdas_master_role_id==4){

            $gpAsset=OsrNonTaxAssetEntry::where([
                ['gram_panchayat_id', '=', $users->gp_id],
                ['asset_under', '=', "GP"],
            ])->count();

            $data=['gpAsset'=>$gpAsset];
        }

        return $data;
    }

    public static function dw_bw_asset_count($branch_id){
	    $users=Auth::user();

	    $data=[];

	    if($users->mdas_master_role_id==2){
            $zpAsset=OsrNonTaxAssetEntry::where([
                ['zila_id', '=', $users->zp_id],
                ['asset_under', '=', "ZP"],
                ['osr_asset_branch_id', '=', $branch_id],
            ])->count();
            $apAsset=OsrNonTaxAssetEntry::where([
                ['zila_id', '=', $users->zp_id],
                ['asset_under', '=', "AP"],
                ['osr_asset_branch_id', '=', $branch_id],
            ])->count();
            $gpAsset=OsrNonTaxAssetEntry::where([
                ['zila_id', '=', $users->zp_id],
                ['asset_under', '=', "GP"],
                ['osr_asset_branch_id', '=', $branch_id],
            ])->count();

            $data=['zpAsset'=>$zpAsset, 'apAsset'=>$apAsset,'gpAsset'=>$gpAsset];
        }elseif($users->mdas_master_role_id==3){

            $apAsset=OsrNonTaxAssetEntry::where([
                ['anchalik_id', '=', $users->ap_id],
                ['asset_under', '=', "AP"],
                ['osr_asset_branch_id', '=', $branch_id],
            ])->count();
            $gpAsset=OsrNonTaxAssetEntry::where([
                ['anchalik_id', '=', $users->ap_id],
                ['asset_under', '=', "GP"],
                ['osr_asset_branch_id', '=', $branch_id],
            ])->count();
            $data=['apAsset'=>$apAsset,'gpAsset'=>$gpAsset];
        }elseif($users->mdas_master_role_id==4){

            $gpAsset=OsrNonTaxAssetEntry::where([
                ['gram_panchayat_id', '=', $users->gp_id],
                ['asset_under', '=', "GP"],
                ['osr_asset_branch_id', '=', $branch_id],
            ])->count();
            $data=['gpAsset'=>$gpAsset];
        }

        return $data;
    }

    public static function checkAssetListForShortlist($branch_id,$fyData,$assetCodeList,$level,$id){
        $assetCount=count($assetCodeList);

        if($level=="ZP"){
            $whereArray=[
                ['asset_under', '=', $level],
                ['osr_asset_branch_id', '=', $branch_id],
                ['zila_id', '=', $id],
                ['asset_listing_date', '<=', $fyData->fy_to],
            ];
        }elseif($level=="AP"){
            $whereArray=[
                ['asset_under', '=', $level],
                ['osr_asset_branch_id', '=', $branch_id],
                ['anchalik_id', '=', $id],
                ['asset_listing_date', '<=', $fyData->fy_to],
            ];
        }else{
            $whereArray=[
                ['asset_under', '=', $level],
                ['osr_asset_branch_id', '=', $branch_id],
                ['gram_panchayat_id', '=', $id],
                ['asset_listing_date', '<=', $fyData->fy_to],
            ];
        }

        $count= OsrNonTaxAssetEntry::where($whereArray)->whereIn('asset_code', $assetCodeList)->count();

        if($assetCount==$count){
            return true;
        }

        return false;
    }


    public static function checkAssetEntryMismatch($asset_code, $fy_id){

        $users=Auth::user();

        $assetData=OsrNonTaxAssetShortlist::getAsset($asset_code, $fy_id);

        if(!$assetData){
            return false;
        }

        if($users->mdas_master_role_id==2){
            $level="ZP";
            if($assetData->zp_id<>$users->zp_id || $assetData->level<>$level){
                return false;
            }
        }elseif ($users->mdas_master_role_id==3){
            $level="AP";
            if($assetData->ap_id<>$users->ap_id || $assetData->level<>$level){
                return false;
            }
        }elseif ($users->mdas_master_role_id==4){
            $level="GP";
            if($assetData->gp_id<>$users->gp_id || $assetData->level<>$level){
                return false;
            }
        }else{
            return false;
        }

        return true;
    }
	
	//------------------------------- Website---------------------------------------------------------------------------
    
    public static function districtWiseAssetCount(){
        
        $finalArray= [];
        $data = OsrNonTaxAssetEntry::select(DB::raw('count(*) AS total'),'osr_non_tax_asset_entries.zila_id as zp_id')
                                            ->groupBy('osr_non_tax_asset_entries.zila_id')
                                            ->get();
        foreach($data as $li)
        {
            $finalArray[$li->zp_id] = $li->total;
        }
        return $finalArray;
    }
    
    public static function districtWiseBranchCount(){
        
        $finalArray= [];
        
        $data = OsrNonTaxAssetEntry::select(DB::raw('count(*) AS total'),'osr_non_tax_asset_entries.osr_asset_branch_id as branch_id','osr_non_tax_asset_entries.zila_id as zp_id')
                                            ->groupBy('branch_id','zp_id')
                                            ->get();
        foreach($data as $li)
        {
            $finalArray[$li->zp_id][$li->branch_id]= $li->total;
        }
        
        return $finalArray;
        
    }
    
    public static function districtWiseDefaulter(){
        
        $finalArray = [];
        
        $data = OsrNonTaxAssetEntry::join('osr_non_tax_asset_final_records as f_record','f_record.asset_code','=','osr_non_tax_asset_entries.asset_code')
                                    ->where('f_record.defaulter_status','=',1)
                                    ->select(DB::raw('count(*) AS total'),'osr_non_tax_asset_entries.zila_id as zp_id')
                                    ->groupBy('osr_non_tax_asset_entries.zila_id')
                                    ->get();
        foreach($data as $li)
        {
            $finalArray[$li->zp_id] = $li->total;
        }
        
        return $finalArray;                           
        
    }
    
	public static function districtWiseAssetList($id){
        
        return OsrNonTaxAssetEntry::join('osr_master_non_tax_branches as branch','osr_non_tax_asset_entries.osr_asset_branch_id','=','branch.id')
                                    ->join('zila_parishads as z','osr_non_tax_asset_entries.zila_id','=','z.id')
                                    ->join('anchalik_parishads as a','osr_non_tax_asset_entries.anchalik_id','=','a.id')
                                    ->join('gram_panchyats as g','osr_non_tax_asset_entries.gram_panchayat_id','=','g.gram_panchyat_id')
                                    ->where([
                                    ['osr_non_tax_asset_entries.zila_id','=', $id]
                                    ])
                                    ->select('osr_non_tax_asset_entries.*','branch.branch_name','z.zila_parishad_name','a.anchalik_parishad_name','g.gram_panchayat_name')
                                    ->get();
        
        
    }
	
	public static function districtYrWiseAssetCount($fy_to){
        
        $finalArray= [];
        $data = OsrNonTaxAssetEntry::where('asset_listing_date','<',$fy_to)
                                    ->select(DB::raw('count(*) AS total'),'osr_non_tax_asset_entries.zila_id as zp_id')
                                    ->groupBy('osr_non_tax_asset_entries.zila_id')
                                    ->get();
        foreach($data as $li)
        {
            $finalArray[$li->zp_id] = $li->total;
        }
        return $finalArray;
    }
	 public static function ZilaWiseAssetCount($fy_to,$zp_id){
        
        return OsrNonTaxAssetEntry::where([
                                        ['asset_listing_date','<',$fy_to],
                                        ['zila_id','=',$zp_id]
                                    ])->count();
        
    }
    
    
}
