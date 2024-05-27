<?php

namespace App\Http\Controllers\Admin\Osr;

use App\Osr\OsrNonTaxBiddingGeneralDetail;
use App\CommonModels\ZilaParishad;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\CommonModels\District;
use App\Osr\OsrMasterNonTaxBranch;
use App\Osr\OsrNonTaxAssetEntry;
use App\Osr\OsrNonTaxAssetShortlist;
use DB;
use Validator;
use App\Osr\OsrMasterFyYear;
use App\Osr\OsrNonTaxMasterAssetCategory;
use App\CommonModels\AnchalikParishad;
use App\CommonModels\GramPanchyat;
use App\Http\Controllers\Controller;
use App\Osr\OsrNonTaxAssetFinalRecord;
use App\Osr\OsrNonTaxOtherAssetFinalRecord;

class AdminOsrApController extends Controller
{
    //Settlement

    public function subDistrictWiseAssetSettlement($id,$fy_id){
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;

        $ap_list=AnchalikParishad::getAPsByZilaId($id);

        $zpSettledCount = OsrNonTaxAssetFinalRecord::zpSettledAsset($id,$fy_id);

        $zpAssetCount = OsrNonTaxAssetFinalRecord::zpTotalAsset($id,$fy_id);

        $apYrWiseSettledAssetCount = OsrNonTaxAssetFinalRecord::apYrWiseSettledAssetCount($fy_id);

        $totalAssetCount = OsrNonTaxAssetFinalRecord::apWiseTotalAsset($fy_id);

        $dataCount = [
            'zpSettledCount' => $zpSettledCount,
            'zpAssetCount' => $zpAssetCount,
            'totalAssetCount' => $totalAssetCount,
            'apYrWiseSettledAssetCount' => $apYrWiseSettledAssetCount,
        ];

        return view('admin.Osr.AssetsSettlement.osrSubDistrictWiseAssetSettlement',compact('dataCount','district_name','fy_years','ap_list','id','fy_id'));
    }

    //Defaulter
    public function subDistrictWiseDefaulterReport($id,$fy_id){
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;
        $ap_list=AnchalikParishad::getAPsByZilaId($id);

        $zpSettledCount = OsrNonTaxAssetFinalRecord::zpSettledAsset($id,$fy_id);

        $apYrWiseSettledAssetCount = OsrNonTaxAssetFinalRecord::apYrWiseSettledAssetCount($fy_id);

        $zpDefaulter = OsrNonTaxAssetFinalRecord::zpDefaulter($id,$fy_id);

        $apYrWiseDefaulterCount = OsrNonTaxAssetFinalRecord::apYrWiseDefaulterCount($fy_id);

        $dataCount = [
            'apYrWiseSettledAssetCount' => $apYrWiseSettledAssetCount,
            'zpDefaulter' => $zpDefaulter,
            'zpSettledCount' => $zpSettledCount,
            'apYrWiseDefaulterCount' => $apYrWiseDefaulterCount,
        ];
        return view('admin.Osr.Defaulters.osrSubDistrictWiseReport',compact('dataCount','district_name','fy_years','ap_list','id','fy_id'));
    }

    public function listOfAPDefaulterZilaWise(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Failed to Request Process.";

        $results=[];
        $i=1;

        try {

            $max_osr_fy_year = $request->input('apfyyear');
            $zid = $request->input('zid');
            $apid = $request->input('apid');

            $listOfDefaulterAPWise = OsrNonTaxAssetEntry::join('osr_non_tax_bidding_general_details AS gd','osr_non_tax_asset_entries.asset_code','=','gd.asset_code')
                ->join('osr_non_tax_bidding_settlement_details As sd','gd.id','=','sd.osr_non_tax_bidding_general_detail_id')
                ->join('osr_non_tax_bidder_entries As be','sd.osr_non_tax_bidder_entry_id','=','be.id')
                ->join('osr_non_tax_asset_final_records As fr','osr_non_tax_asset_entries.asset_code','=','fr.asset_code')
                ->join('zila_parishads AS z','osr_non_tax_asset_entries.zila_id','=','z.id')
                ->join('anchalik_parishads AS ap','osr_non_tax_asset_entries.anchalik_id','=','ap.id')
                ->join('osr_master_non_tax_branches AS c','osr_non_tax_asset_entries.osr_asset_branch_id','=','c.id')
                ->where([
                    ['fr.fy_id',$max_osr_fy_year],
                    ['fr.defaulter_status',1],
                    ['z.id',$zid],
                    ['ap.id',$apid],
                    ['osr_non_tax_asset_entries.asset_under','AP']
                ])
                ->select(
                    'osr_non_tax_asset_entries.id AS asset_id',
                    'osr_non_tax_asset_entries.asset_code',
                    'osr_non_tax_asset_entries.asset_name',
                    'osr_non_tax_asset_entries.asset_under',
                    'c.id AS c_id',
                    'c.branch_name',
                    'be.b_f_name',
                    'be.b_m_name',
                    'be.b_l_name',
                    'be.b_father_name',
                    'be.b_mobile',
                    'be.b_pan_no',
					'fr.settlement_amt',
                    'fr.tot_ins_collected_amt',
                    'z.id AS z_id',
                    'z.zila_parishad_name',
                    'ap.id AS ap_id',
                    'ap.anchalik_parishad_name')->get();

            foreach ($listOfDefaulterAPWise as $list) {
				$default_amt = $list->settlement_amt - $list->tot_ins_collected_amt;
                array_push($results,
                    array(
                        $i,
                        $list->zila_parishad_name,
                        $list->anchalik_parishad_name,
                        $list->branch_name,
                        $list->asset_code,
                        $list->asset_name,
                        $list->b_f_name.' '.$list->b_m_name.' '.$list->b_l_name,
                        $list->b_father_name,
                        // $list->b_mobile,
                        $list->b_pan_no,
						$default_amt,
                        $list->asset_under,
                        '<a href="'.route("admin.Osr.assetInformation", [$list->z_id,$max_osr_fy_year,$list->c_id,$list->asset_id,$list->ap_id]).'">click</a>'
                    )
                );
                $i++;
            }


        }catch(\Exception $e) {
            $returnData['msg'] = "Server Exception.".$e->getMessage();
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = $results;
        $returnData['msg'] = "Success";
        return response()->json($returnData);
    }




    //Revenue-----------------------------------------------------------------------------------------------------------
    
    public function subDistrictWiseRevenue($id, $fy_id){
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;
        $ap_list=AnchalikParishad::getAPsByZilaId($id);

        $zpData = OsrNonTaxAssetFinalRecord::getYrZpRevenueData($id,$fy_id);

        $apDataList = OsrNonTaxAssetFinalRecord::getYrApRevenueList($id,$fy_id);

        $dataCount=[
            'zpData'=>$zpData,
            'apDataList'=>$apDataList
        ];
        
        return view('admin.Osr.Revenue.osrSubDistrictWiseRevenue',compact('district_name','fy_years','ap_list','id','fy_id','dataCount'));
    }

    //Share-------------------------------------------------------------------------------------------------------------
    
    public function subDistrictWiseShare($id, $fy_id){
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;
        $ap_list=AnchalikParishad::getAPsByZilaId($id);

        $zpData = OsrNonTaxAssetFinalRecord::getYrZpShareData($id,$fy_id);

        $apDataList = OsrNonTaxAssetFinalRecord::getYrApShareList($id,$fy_id);

        $dataCount=[
            'zpData'=>$zpData,
            'apDataList'=>$apDataList
        ];

        //echo json_encode($dataCount);


        return view('admin.Osr.Share.osrSubDistrictWiseShare',compact('district_name','fy_years','ap_list','id','fy_id','dataCount'));
    }

}
