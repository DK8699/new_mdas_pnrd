<?php

namespace App\Http\Controllers\Website;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CommonModels\ZilaParishad;
use App\Osr\OsrNonTaxAssetEntry;
use App\Osr\OsrNonTaxAssetShortlist;
use App\Osr\OsrNonTaxSignedAssetReport;
use App\Osr\OsrMasterNonTaxBranch;
use App\Osr\OsrMasterFyYear;

use Crypt;


class PublicController extends Controller
{
    public function index(){
        return view('public.index');
    }
    
    public function osr_asset_settlement(Request $request){
        
        $zilas = ZilaParishad::all();
        
        $branches = OsrMasterNonTaxBranch::all();
        
        $asset_count = OsrNonTaxAssetEntry::districtWiseAssetCount();
        
        $branchData = OsrNonTaxAssetEntry::districtWiseBranchCount();
        
        //echo json_encode($branchData);
         
        $data = [
            'asset_count' => $asset_count,
            'branches' => $branches,
            'branchData' => $branchData,
            'zilas' => $zilas,
        ];
        
        return view('public.Osr.osr_public',compact('data'));
    }
    
    public function osr_defaulter(){
        
        $zilas = ZilaParishad::all();
        $asset_count = OsrNonTaxAssetEntry::districtWiseAssetCount();
        
        $defaulterData = OsrNonTaxAssetEntry::districtWiseDefaulter();
        
        //echo json_encode($defaulterData);
        
        $data = [
            'asset_count' => $asset_count,
            'defaulterData' => $defaulterData,
            'zilas' => $zilas,
        ];
        return view('public.Osr.osr_defaulter',compact('data'));
    }
    
    public function osr_year_wise(Request $request){
        
        $zilas = ZilaParishad::all();
        
        $fyList=OsrMasterFyYear::getAllYears();
        $yr_id=($request->input('y_id'));
        $data=[
            'zilas'=>$zilas,
            'fyList'=>$fyList,
            'ditrict_id'=>NULL,
            'data_fy_id'=>NULL,
            'yr_id'=>$yr_id
        ];
        
        return view('public.Osr.osr_year_wise',compact('data'));
    }
	
	public function osr_asset_list($zp_id){
        
        $id = Crypt::decrypt($zp_id);
		
		$zpData=ZilaParishad::getZPName($id);
        
        $assetList = OsrNonTaxAssetEntry::districtWiseAssetList($id);
        
        return view('public.Osr.osr_asset_list',compact('assetList','zpData'));
    }
	public function osr_yr_wise_asset_show(Request $request){
        $zilas = ZilaParishad::all();
        $yr_id=($request->input('y_id'));
        
        $fyData = OsrMasterFyYear::getFyDataId($yr_id);
        
        
        $head_txt = "Asset Shortlisted under ZP,AP,GP for the Financial Year :".$fyData->fy_name;
        
        $asset_count = OsrNonTaxAssetEntry::districtYrWiseAssetCount($fyData->fy_to);
        
        $shortlisted_asset = OsrNonTaxAssetShortlist::AssetShortlisted($yr_id);
        
        
        $data=[
            'asset_count' => $asset_count,
            'zilas'=>$zilas,
            'yr_id'=>$yr_id,
            'shortlisted_asset'=>$shortlisted_asset,
            'head_txt'=>$head_txt,
        ];
        return view('public.Osr.osr_year_wise_asset_table',compact('data'));
    }
	
	public function shortlistReportView(Request $request,$fy_id,$z_id){
        
        $fy_id = Crypt::decrypt($fy_id);
        $z_id = Crypt::decrypt($z_id);
        
        $report_attachment = OsrNonTaxSignedAssetReport::where([
            ['osr_fy_year_id','=',$fy_id],
            ['zila_id','=',$z_id],
        ])->select('attachment_path')
            ->first();
        
        return response()->file(storage_path('app/'.$report_attachment->attachment_path));
    }
	
	public function recruitment(){
		return view('public.recruitment');
	}
}
