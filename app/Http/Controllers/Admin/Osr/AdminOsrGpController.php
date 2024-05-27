<?php

namespace App\Http\Controllers\Admin\Osr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Osr\OsrNonTaxBiddingGeneralDetail;
use App\CommonModels\ZilaParishad;
use Carbon\Carbon;
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
use App\Osr\OsrNonTaxAssetFinalRecord;
use App\Osr\OsrNonTaxOtherAssetFinalRecord;


class AdminOsrGpController extends Controller
{
    //SETTLEMENT
    public function subAPWiseAssetSettlement($id,$fy_id,$ap_id){
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;

        $ap_data=AnchalikParishad::getAPName($ap_id);
        $ap_name=$ap_data->anchalik_parishad_name;

        $gp_list=GramPanchyat::getGpsByAnchalikId($ap_id);

        $apAssetCount = OsrNonTaxAssetFinalRecord::apTotalAsset($ap_id,$fy_id);

        $apSettledCount = OsrNonTaxAssetFinalRecord::apSettledAsset($ap_id,$fy_id);

        $totalAssetCount = OsrNonTaxAssetFinalRecord::gpWiseTotalAsset($fy_id);

        $gpYrWiseSettledAssetCount = OsrNonTaxAssetFinalRecord::gpYrWiseSettledAssetCount($fy_id);

        $dataCount = [
            'apAssetCount' => $apAssetCount,
            'apSettledCount' => $apSettledCount,
            'totalAssetCount' => $totalAssetCount,
            'gpYrWiseSettledAssetCount' => $gpYrWiseSettledAssetCount,
        ];


        return view('admin.Osr.AssetsSettlement.osrSubAPWiseAssetSettlement',compact('dataCount','district_name','fy_years','id','fy_id','ap_id','ap_name','gp_list'));
    }

    //DEFAULTER

    public function subAPWiseAssetDefaulter($id,$fy_id,$ap_id){
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;

        $ap_data=AnchalikParishad::getAPName($ap_id);
        $ap_name=$ap_data->anchalik_parishad_name;

        $gp_list=GramPanchyat::getGpsByAnchalikId($ap_id);

        $apSettledCount = OsrNonTaxAssetFinalRecord::apSettledAsset($ap_id,$fy_id);

        $apDefaulter = OsrNonTaxAssetFinalRecord::apDefaulter($ap_id,$fy_id);

        $gpYrWiseSettledAssetCount = OsrNonTaxAssetFinalRecord::gpYrWiseSettledAssetCount($fy_id);

        $gpYrWiseDefaulterCount = OsrNonTaxAssetFinalRecord::gpYrWiseDefaulterCount($fy_id);

        $dataCount = [
            'apSettledCount' => $apSettledCount,
            'apDefaulter' => $apDefaulter,
            'gpYrWiseSettledAssetCount' => $gpYrWiseSettledAssetCount,
            'gpYrWiseDefaulterCount' => $gpYrWiseDefaulterCount,
        ];

        return view('admin.Osr.Defaulters.osrSubAPWiseAssetDefaulter',compact('dataCount','district_name','fy_years','id','fy_id','ap_id','ap_name','gp_list'));
    }

    public function listOfGPDefaulterZilaWise(Request $request)
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
            $gpid = $request->input('gpid');

            $listOfDefaulterAPWise = OsrNonTaxAssetEntry::join('osr_non_tax_bidding_general_details AS gd','osr_non_tax_asset_entries.asset_code','=','gd.asset_code')
                ->join('osr_non_tax_bidding_settlement_details As sd','gd.id','=','sd.osr_non_tax_bidding_general_detail_id')
                ->join('osr_non_tax_bidder_entries As be','sd.osr_non_tax_bidder_entry_id','=','be.id')
                ->join('osr_non_tax_asset_final_records As fr','osr_non_tax_asset_entries.asset_code','=','fr.asset_code')
                ->join('zila_parishads AS z','osr_non_tax_asset_entries.zila_id','=','z.id')
                ->join('anchalik_parishads AS ap','osr_non_tax_asset_entries.anchalik_id','=','ap.id')
                ->join('gram_panchyats AS gp','osr_non_tax_asset_entries.gram_panchayat_id','=','gp.gram_panchyat_id')
                ->join('osr_master_non_tax_branches AS c','osr_non_tax_asset_entries.osr_asset_branch_id','=','c.id')
                ->where([
                    ['fr.fy_id',$max_osr_fy_year],
                    ['fr.defaulter_status',1],
                    ['z.id',$zid],
                    ['ap.id',$apid],
                    ['gp.gram_panchyat_id',$gpid],
                    ['osr_non_tax_asset_entries.asset_under','GP']
                ])
                ->select(
					'osr_non_tax_asset_entries.id AS asset_id',
                    'osr_non_tax_asset_entries.asset_code',
                    'osr_non_tax_asset_entries.asset_name',
                    'osr_non_tax_asset_entries.asset_under',
					'c.id AS cid',
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
                    'ap.anchalik_parishad_name',
                    'gp.gram_panchyat_id AS gp_id',
                    'gp.gram_panchayat_name')->get();

            foreach ($listOfDefaulterAPWise as $list) {
				$default_amt = $list->settlement_amt - $list->tot_ins_collected_amt;
                array_push($results,
                    array(
                        $i,
                        $list->zila_parishad_name,
                        $list->anchalik_parishad_name,
                        $list->gram_panchayat_name,
                        $list->branch_name,
                        $list->asset_code,
                        $list->asset_name,
                        $list->b_f_name.' '.$list->b_m_name.' '.$list->b_l_name,
                        $list->b_father_name,
                        // $list->b_mobile,
                        $list->b_pan_no,
						$default_amt,
                        $list->asset_under,
                        '<a href="'.route("admin.Osr.assetInformation", [$zid,$max_osr_fy_year,$list->cid,$list->asset_id,$apid,$gpid]).'">click</a>'
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


    //Revenue

    public function subAPWiseAssetRevenue($id,$fy_id,$ap_id){
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;

        $ap_data=AnchalikParishad::getAPName($ap_id);
        $ap_name=$ap_data->anchalik_parishad_name;

        $gp_list=GramPanchyat::getGpsByAnchalikId($ap_id);

        //AP DATA

        $apData = OsrNonTaxAssetFinalRecord::getYrApRevenueData($id, $ap_id, $fy_id);

        //GP DATA LIST

        $gpDataList = OsrNonTaxAssetFinalRecord::getYrGpRevenueList($id, $ap_id, $fy_id);

        $dataCount=[
            'apData'=>$apData,
            'gpDataList'=>$gpDataList
        ];

        return view('admin.Osr.Revenue.osrSubAPWiseAssetRevenue',compact('dataCount', 'district_name','fy_years','id','fy_id','ap_id','ap_name','gp_list'));
    }

    //Share

    public function subAPWiseAssetShare($id,$fy_id,$ap_id){
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;

        $ap_data=AnchalikParishad::getAPName($ap_id);
        $ap_name=$ap_data->anchalik_parishad_name;

        $gp_list=GramPanchyat::getGpsByAnchalikId($ap_id);


        //AP DATA

        $apData = OsrNonTaxAssetFinalRecord::getYrApShareData($id, $ap_id, $fy_id);

        //GP DATA LIST

        $gpDataList = OsrNonTaxAssetFinalRecord::getYrGpShareList($id, $ap_id, $fy_id);

        $dataCount=[
            'apData'=>$apData,
            'gpDataList'=>$gpDataList
        ];

        return view('admin.Osr.Share.osrSubAPWiseAssetShare',compact('dataCount', 'district_name','fy_years','id','fy_id','ap_id','ap_name','gp_list'));
    }

}
