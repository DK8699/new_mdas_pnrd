<?php

namespace App\Http\Controllers\Osr\gp_panel;

use App\CommonModels\District;
use App\CommonModels\GramPanchyat;
use App\CommonModels\Village;
use App\CommonModels\ZilaParishad;
use App\CommonModels\AnchalikParishad;
use App\Osr\OsrMasterNonTaxBranch;
use App\Osr\OsrNonTaxAssetEntry;
use App\Osr\OsrMasterFyYear;
use App\Osr\OsrNonTaxAssetSettlement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Auth;
use Validator;


class OsrNonTaxAssetGpController extends Controller
{
    //-------------------------------Settlement-------------------------------------
    
    public function gp_asset_settlement_percent($fy_id){
        
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);
        $gpData = GramPanchyat::getGPName($users->gp_id);
         
        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'gpData'=>$gpData,
        ];

        return view('Osr.non_tax.asset.gp.gp_asset_settlement_percent',compact('data'));
        
        
    }
    
    public function gp_asset_settlement_percent_branch($fy_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);
        
        $users=Auth::user();
        
        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);
        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);
        $gpData = GramPanchyat::getGPName($users->gp_id);
        
        

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "GP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $users->ap_id],
            ['gram_panchayat_id', '=', $users->gp_id],
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


        return view('Osr.non_tax.asset.gp.gp_asset_settlement_percent_branch', compact('data'));
    }

    
    //-------------------------------Collection-------------------------------------
    
    public function gp_asset_collection($fy_id){
        
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);
        $gpData = GramPanchyat::getGPName($users->gp_id);
         
        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'gpData'=>$gpData,
        ];

        return view('Osr.non_tax.asset.gp.gp_asset_collection',compact('data'));
    }
    
    public function gp_asset_collection_branch($fy_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);
        
        $users=Auth::user();
        
        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);
        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);
        $gpData = GramPanchyat::getGPName($users->gp_id);
        
        

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "GP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $users->ap_id],
            ['gram_panchayat_id', '=', $users->gp_id],
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

        return view('Osr.non_tax.asset.gp.gp_asset_collection_branch', compact('data'));
    }

    
    //-------------------------------Defaulter-------------------------------------
    
    public function gp_asset_defaulter($fy_id){
        
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);
        $gpData = GramPanchyat::getGPName($users->gp_id);
         
        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'gpData'=>$gpData,
        ];

        return view('Osr.non_tax.asset.gp.gp_asset_defaulter',compact('data'));
        
        
    }
    
    public function gp_asset_defaulter_branch($fy_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);
        
        $users=Auth::user();
        
        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);
        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);
        $gpData = GramPanchyat::getGPName($users->gp_id);
        
        

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "GP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $users->ap_id],
            ['gram_panchayat_id', '=', $users->gp_id],
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


        return view('Osr.non_tax.asset.gp.gp_asset_defaulter_branch', compact('data'));
    }
    
    //-------------------------------Share-------------------------------------
    
    public function gp_asset_share($fy_id){
        
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);
        $gpData = GramPanchyat::getGPName($users->gp_id);
         
        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'gpData'=>$gpData,
        ];
        
        return view('Osr.non_tax.asset.gp.gp_asset_share',compact('data'));
        
    }
    
    public function gp_asset_share_branch($fy_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);
        
        $users=Auth::user();
        
        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);
        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apData = AnchalikParishad::getAPName($users->ap_id);
        $gpData = GramPanchyat::getGPName($users->gp_id);
        
        

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "GP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $users->ap_id],
            ['gram_panchayat_id', '=', $users->gp_id],
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

        return view('Osr.non_tax.asset.gp.gp_asset_share_branch', compact('data'));
    }
    
    
}
