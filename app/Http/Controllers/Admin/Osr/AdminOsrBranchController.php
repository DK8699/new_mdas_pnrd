<?php

namespace App\Http\Controllers\Admin\Osr;

use App\CommonModels\AnchalikParishad;
use App\CommonModels\GramPanchyat;
use App\CommonModels\ZilaParishad;
use App\ConfigMdas;
use App\Osr\OsrMasterFyYear;
use App\Osr\OsrMasterNonTaxBranch;
use App\Osr\OsrNonTaxAssetEntry;
use App\Osr\OsrNonTaxAssetFinalRecord;
use App\Osr\OsrNonTaxAssetShortlist;
use App\Osr\OsrNonTaxMasterAssetCategory;
use App\Osr\OsrNonTaxOtherAssetFinalRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class AdminOsrBranchController extends Controller
{
    //Settlement--------------------------------------------------------------------------------------------------------

    public function osrAssetBranchList($id, $fy_id, $ap_id=NULL, $gp_id=NULL) {
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $masterBranches=OsrMasterNonTaxBranch::all();
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;
        $ap_name=NULL;
        $gp_name=NULL;

        $totalAssetList=[];
        $settledList=[];
        if($ap_id==NULL){
            $heade_text="Settlement list of various categories of Zila Parishad : ".$district_name." (".$fy_years.")";

            $totalAssetList = OsrNonTaxAssetFinalRecord::zpBranchWiseTotalAsset($fy_id,$id);
            $settledList = OsrNonTaxAssetFinalRecord::zpBranchWiseSettledAssetCount($fy_id,$id);


        }else if($gp_id==NULL){
            $ap_data=AnchalikParishad::getAPName($ap_id);
            $ap_name=$ap_data->anchalik_parishad_name;
            $heade_text="Settlement list of various categories of Anchalik Panchayat : ".$ap_name.",  Zila Parishad : ".$district_name." (".$fy_years.")";

            $totalAssetList = OsrNonTaxAssetFinalRecord::apBranchWiseTotalAsset($fy_id,$ap_id);
            $settledList = OsrNonTaxAssetFinalRecord::apBranchWiseSettledAssetCount($fy_id,$ap_id);

        }else{
            $ap_data=AnchalikParishad::getAPName($ap_id);
            $ap_name=$ap_data->anchalik_parishad_name;
            $gp_data=GramPanchyat::getGPName($gp_id);
            $gp_name=$gp_data->gram_panchayat_name;
            $heade_text="Settlement list of various categories of Gram Panchayat : ".$gp_name.", Anchalik Panchayat : ".$ap_name." , Zila Parishad : ".$district_name." (".$fy_years.")";

            $totalAssetList = OsrNonTaxAssetFinalRecord::gpBranchWiseTotalAsset($fy_id,$gp_id);
            $settledList = OsrNonTaxAssetFinalRecord::gpBranchWiseSettledAssetCount($fy_id,$gp_id);
        }

        $dataCount = [
            'totalAssetList' =>$totalAssetList,
            'settledList' =>$settledList,
        ];

        return view('admin.Osr.AssetsSettlement.osrAssetBranchList',compact('dataCount','id','fy_id','district_name','fy_years','masterBranches','heade_text','ap_id','ap_name','gp_id','gp_name'));
    }

     public function osrAssetSingleBranchList($id,$fy_id,$branch_id,$ap_id=NULL,$gp_id=NULL) {
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;
        $branch_name=OsrMasterNonTaxBranch::getBranchById($branch_id)->branch_name;
        $ap_name=NULL;
        $gp_name=NULL;
        if($ap_id==NULL){
            $heade_text="Settlement list of ".$branch_name." of Zila Parishad : ".$district_name." (".$fy_years.")";

            $query1=[
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'ZP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
            ];

            $query2=[
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.level', '=', 'ZP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
            ];

        }else if($gp_id==NULL){
            $ap_data=AnchalikParishad::getAPName($ap_id);
            $ap_name=$ap_data->anchalik_parishad_name;
            $heade_text="Settlement list of ".$branch_name." of Anchalik Panchayat : ".$ap_name.", Zila Parishad : ".$district_name." (".$fy_years.")";

            $query1=[
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'AP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.anchalik_id', '=', $ap_id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
            ];

            $query2=[
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.level', '=', 'AP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.ap_id', '=', $ap_id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
            ];

        }else{
            $ap_data=AnchalikParishad::getAPName($ap_id);
            $ap_name=$ap_data->anchalik_parishad_name;
            $gp_data=GramPanchyat::getGPName($gp_id);
            $gp_name=$gp_data->gram_panchayat_name;

            $heade_text="Settlement list of ".$branch_name." of Gram Panchayat : ".$gp_name.", Anchalik Panchayat : ".$ap_name." , Zila Parishad : ".$district_name." (".$fy_years.")";

            $query1=[
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'GP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.anchalik_id', '=', $ap_id],
                ['a_entries.gram_panchayat_id', '=', $gp_id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
            ];

            $query2=[
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.level', '=', 'GP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.ap_id', '=', $ap_id],
                ['a_short.gp_id', '=', $gp_id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
            ];
        }


        $dataCount=[];

        $assetList=OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->where($query1)->select('osr_non_tax_asset_shortlists.id as id','a_entries.id as master_asset_id','a_entries.asset_code','a_entries.asset_name')->get();


        $data= OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->where($query2)
            ->select('a_short.id','osr_non_tax_asset_final_records.settlement_amt')
            ->get();

        foreach($data AS $li){

            $dataCount[$li->id]=[
                'settlement_amt'=>$li->settlement_amt
            ];
        }

        foreach($assetList AS $li){
            if(!isset($dataCount[$li->id])){
                $dataCount[$li->id]=[
                    'settlement_amt'=>0
                ];
            }
        }


        return view('admin.Osr.AssetsSettlement.osrAssetSingleBranchList',compact('assetList','dataCount','district_name','fy_years','id','fy_id','branch_id','ap_id','gp_id','heade_text','ap_name','gp_name'));
    }

    //Defaulter---------------------------------------------------------------------------------------------------------

    public function osrDefaulterAssetBranchList($id, $fy_id, $ap_id=NULL, $gp_id=NULL) {
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $masterBranches=OsrMasterNonTaxBranch::all();
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;
        $ap_name=NULL;
        $gp_name=NULL;

        $defaulterList=[];
        $settledList=[];

        if($ap_id==NULL){
            $heade_text="Defaulters of various categories of Zila Parishad : ".$district_name." ( ".$fy_years." )";

            $settledList = OsrNonTaxAssetFinalRecord::zpBranchWiseSettledAssetCount($fy_id,$id);
            $defaulterList = OsrNonTaxAssetFinalRecord::zpBranchWiseDefaulterCount($fy_id,$id);

        }else if($gp_id==NULL){
            $ap_data=AnchalikParishad::getAPName($ap_id);
            $ap_name=$ap_data->anchalik_parishad_name;
            $heade_text="Defaulters of various categories of Anchalik Panchayat : ".$ap_name." ( Zila Parishad : ".$district_name." )"." ( ".$fy_years." )";

            $settledList = OsrNonTaxAssetFinalRecord::apBranchWiseSettledAssetCount($fy_id,$ap_id);
            $defaulterList = OsrNonTaxAssetFinalRecord::apBranchWiseDefaulterCount($fy_id,$ap_id);
        }else{
            $ap_data=AnchalikParishad::getAPName($ap_id);
            $ap_name=$ap_data->anchalik_parishad_name;
            $gp_data=GramPanchyat::getGPName($gp_id);
            $gp_name=$gp_data->gram_panchayat_name;
            $heade_text="Defaulters of various categories of Gram Panchayat : ".$gp_name." ( Anchaik Panchayat : ".$ap_name." , Zila Parishad : ".$district_name." )"." ( ".$fy_years." )";

            $settledList = OsrNonTaxAssetFinalRecord::gpBranchWiseSettledAssetCount($fy_id,$gp_id);
            $defaulterList = OsrNonTaxAssetFinalRecord::gpBranchWiseDefaulterCount($fy_id,$gp_id);
        }


        $dataCount= [
            'settledList' =>$settledList,
            'defaulterList' =>$defaulterList,
        ];

        return view('admin.Osr.Defaulters.osrDefaulterAssetBranchList',compact('dataCount','id','fy_id','district_name','fy_years','masterBranches','heade_text','ap_id','ap_name','gp_id','gp_name'));
    }

    public function osrDefaulterSingleBranchList($id,$fy_id,$branch_id,$ap_id=NULL,$gp_id=NULL) {
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;
        $branch_name=OsrMasterNonTaxBranch::getBranchById($branch_id)->branch_name;
        $ap_name=NULL;
        $gp_name=NULL;
        $nonTaxAssets=NULL;
        $bidderData=NULL;
        if($ap_id==NULL){
            $heade_text="Defaulters of ".$branch_name." of Zila Parishad : ".$district_name." ( ".$fy_years." )";
            $query1=[
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'ZP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
            ];

            $query2=[
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'ZP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
            ];
            $query3=[
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'ZP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['osr_non_tax_asset_final_records.defaulter_status', '=', 1],
            ];

        }else if($gp_id==NULL){
            $ap_data=AnchalikParishad::getAPName($ap_id);
            $ap_name=$ap_data->anchalik_parishad_name;
            $heade_text="Defaulters of ".$branch_name." of Anchalik Panchayat : ".$ap_name.", Zila Parishad : ".$district_name." ( ".$fy_years." )";
            $query1=[
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'AP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.anchalik_id', '=', $ap_id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
            ];
            $query2=[
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'AP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.ap_id', '=', $ap_id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
            ];
            $query3=[
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'AP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.ap_id', '=', $ap_id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['osr_non_tax_asset_final_records.defaulter_status', '=', 1],
            ];

        }else{
            $ap_data=AnchalikParishad::getAPName($ap_id);
            $ap_name=$ap_data->anchalik_parishad_name;
            $gp_data=GramPanchyat::getGPName($gp_id);
            $gp_name=$gp_data->gram_panchayat_name;
            $heade_text="Defaulters of ".$branch_name." of Gram Panchayat : ".$gp_name.", Anchalik Panchayat : ".$ap_name." , Zila Parishad : ".$district_name." ( ".$fy_years." )";
            $query1=[
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'GP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.anchalik_id', '=', $ap_id],
                ['a_entries.gram_panchayat_id', '=', $gp_id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
            ];
            $query2=[
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'GP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.ap_id', '=', $ap_id],
                ['a_short.gp_id', '=', $gp_id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
            ];
            $query3=[
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'GP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.ap_id', '=', $ap_id],
                ['a_short.gp_id', '=', $gp_id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
                ['osr_non_tax_asset_final_records.bidding_status', '=', 1],
                ['osr_non_tax_asset_final_records.defaulter_status', '=', 1],
            ];
        }


        $dataCount=[];

        $assetList=OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->where($query1)->select('osr_non_tax_asset_shortlists.id as id','a_entries.id as master_asset_id','a_entries.asset_code','a_entries.asset_name')->get();

        $bidderList = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
            ->join('osr_non_tax_bidding_general_details as general','general.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->join('osr_non_tax_bidding_settlement_details as settlement','settlement.osr_non_tax_bidding_general_detail_id','=','general.id')
            ->join('osr_non_tax_bidder_entries as bidder','bidder.id','=','settlement.osr_non_tax_bidder_entry_id')
            ->where($query3)
            ->select('a_short.id','bidder.b_f_name','bidder.b_m_name','bidder.b_l_name','b_father_name','bidder.b_pan_no','osr_non_tax_asset_final_records.settlement_amt','osr_non_tax_asset_final_records.security_deposit_amt','osr_non_tax_asset_final_records.tot_ins_collected_amt')
            ->get();

        foreach($bidderList AS $li){

            $defaulter_amt = $li->settlement_amt - ($li->tot_ins_collected_amt + $li->security_deposit_amt);
            
            $bidderData[$li->id]=[
                'b_f_name'=>$li->b_f_name,
                'b_m_name'=>$li->b_m_name,
                'b_l_name'=>$li->b_l_name,
                'b_father_name'=>$li->b_father_name,
                'b_pan_no'=>$li->b_pan_no,
                'defaulter_amt'=> $defaulter_amt
            ];
        }

        foreach($assetList AS $li){
            if(!isset($bidderData[$li->id])){
                $bidderData[$li->id]=[
                    'b_f_name'=> '-',
                    'b_m_name'=> '-',
                    'b_l_name'=> '-',
                    'b_father_name'=> '- - -',
                    'b_pan_no'=> '- - -',
                    'defaulter_amt'=> '- - -'
                ];
            }
        }


        $data= OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->where($query2)
            ->select('a_short.id','osr_non_tax_asset_final_records.settlement_amt','osr_non_tax_asset_final_records.security_deposit_amt','osr_non_tax_asset_final_records.tot_ins_collected_amt')
            ->get();
        
        foreach($data AS $li){

            $dataCount[$li->id]=[
                'settlement_amt'=>($li->settlement_amt),
                'security_deposit_amt'=>($li->security_deposit_amt),
                'tot_ins_collected_amt'=>($li->tot_ins_collected_amt)
            ];
        }
        foreach($assetList AS $li){
            if(!isset($dataCount[$li->id])){
                $dataCount[$li->id]=[
                    'settlement_amt'=>0,
                    'security_deposit_amt'=>0,
                    'tot_ins_collected_amt'=>0,
                ];
            }
        }

        return view('admin.Osr.Defaulters.osrDefaulterSingleBranchList',compact('bidderData','dataCount','assetList','district_name','fy_years','id','fy_id','branch_id','nonTaxAssets','ap_id','gp_id','heade_text','ap_name','gp_name'));
    }

    public function listOfDefaulterBranchWise(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Failed to Request Process.";

        $results=[];
        $i=1;

        try {
            $max_osr_fy_year = $request->input('bfyyear');
            $zid = $request->input('zid');
            $apid = $request->input('apid');
            $gpid = $request->input('gpid');
            $bid = $request->input('bid');
            if($apid==NULL){
                $listOfDefaulterAPWise = OsrNonTaxAssetEntry::join('osr_non_tax_asset_shortlists AS a_short','a_short.asset_code','=','osr_non_tax_asset_entries.asset_code')
					->join('osr_non_tax_bidding_general_details AS gd','a_short.asset_code','=','gd.asset_code')
                    ->join('osr_non_tax_bidding_settlement_details As sd','gd.id','=','sd.osr_non_tax_bidding_general_detail_id')
                    ->join('osr_non_tax_bidder_entries As be','sd.osr_non_tax_bidder_entry_id','=','be.id')
                    ->join('osr_non_tax_asset_final_records As fr','a_short.asset_code','=','fr.asset_code')
                    ->join('zila_parishads AS z','osr_non_tax_asset_entries.zila_id','=','z.id')
                    ->join('anchalik_parishads AS ap','osr_non_tax_asset_entries.anchalik_id','=','ap.id')
                    ->join('gram_panchyats AS gp','osr_non_tax_asset_entries.gram_panchayat_id','=','gp.gram_panchyat_id')
                    ->join('osr_master_non_tax_branches AS c','osr_non_tax_asset_entries.osr_asset_branch_id','=','c.id')
                    ->where([
                        ['fr.fy_id',$max_osr_fy_year],
						['gd.osr_fy_year_id',$max_osr_fy_year],
						['a_short.osr_master_fy_year_id',$max_osr_fy_year],
                        ['fr.defaulter_status',1],
                        ['z.id',$zid],
                        ['c.id',$bid],
                        ['a_short.level','ZP']
                    ])
                    ->select(
                        'a_short.id AS asset_id',
                        'osr_non_tax_asset_entries.asset_code',
                        'osr_non_tax_asset_entries.asset_name',
                        'a_short.level',
                        'c.branch_name',
                        'be.b_f_name',
                        'be.b_m_name',
                        'be.b_l_name',
                        'be.b_father_name',
                        'be.b_mobile',
                        'be.b_pan_no',
                        'z.id AS z_id',
                        'z.zila_parishad_name')->get();

                foreach ($listOfDefaulterAPWise as $list) {
                    array_push($results,
                        array(
                            $i,
                            $list->zila_parishad_name,
                            $list->branch_name,
                            $list->asset_code,
                            $list->asset_name,
                            $list->b_f_name.' '.$list->b_m_name.' '.$list->b_l_name,
                            $list->b_father_name,
                            // $list->b_mobile,
                            $list->b_pan_no,
                            $list->level,
                            '<a href="'.route("admin.Osr.assetInformation", [$zid,$max_osr_fy_year,$bid,$list->asset_id]).'">click</a>'
                        )
                    );
                    $i++;
                }

            }else if($gpid==NULL){

                $listOfDefaulterAPWise = OsrNonTaxAssetEntry::join('osr_non_tax_asset_shortlists AS a_short','a_short.asset_code','=','osr_non_tax_asset_entries.asset_code')
					->join('osr_non_tax_bidding_general_details AS gd','a_short.asset_code','=','gd.asset_code')
                    ->join('osr_non_tax_bidding_settlement_details As sd','gd.id','=','sd.osr_non_tax_bidding_general_detail_id')
                    ->join('osr_non_tax_bidder_entries As be','sd.osr_non_tax_bidder_entry_id','=','be.id')
                    ->join('osr_non_tax_asset_final_records As fr','a_short.asset_code','=','fr.asset_code')
                    ->join('zila_parishads AS z','osr_non_tax_asset_entries.zila_id','=','z.id')
                    ->join('anchalik_parishads AS ap','osr_non_tax_asset_entries.anchalik_id','=','ap.id')
                    ->join('gram_panchyats AS gp','osr_non_tax_asset_entries.gram_panchayat_id','=','gp.gram_panchyat_id')
                    ->join('osr_master_non_tax_branches AS c','osr_non_tax_asset_entries.osr_asset_branch_id','=','c.id')
                    ->where([
                        ['fr.fy_id',$max_osr_fy_year],
						['a_short.osr_master_fy_year_id',$max_osr_fy_year],
						['gd.osr_fy_year_id',$max_osr_fy_year],
                        ['fr.defaulter_status',1],
                        ['z.id',$zid],
                        ['ap.id',$apid],
                        ['c.id',$bid],
                        ['a_short.level','AP']
                    ])
                    ->select(
                        'a_short.id AS asset_id',
                        'osr_non_tax_asset_entries.asset_code',
                        'osr_non_tax_asset_entries.asset_name',
                        'a_short.level',
                        'c.branch_name',
                        'be.b_f_name',
                        'be.b_m_name',
                        'be.b_l_name',
                        'be.b_father_name',
                        'be.b_mobile',
                        'be.b_pan_no',
                        'z.id AS z_id',
                        'z.zila_parishad_name',
                        'ap.id AS ap_id',
                        'ap.anchalik_parishad_name')->get();

                foreach ($listOfDefaulterAPWise as $list) {
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
                            $list->level,
                            '<a href="'.route("admin.Osr.assetInformation", [$zid,$max_osr_fy_year,$bid,$list->asset_id,$apid]).'">click</a>'
                        )
                    );
                    $i++;
                }

            }else{

                $listOfDefaulterAPWise = OsrNonTaxAssetEntry::join('osr_non_tax_asset_shortlists AS a_short','a_short.asset_code','=','osr_non_tax_asset_entries.asset_code')
					->join('osr_non_tax_bidding_general_details AS gd','a_short.asset_code','=','gd.asset_code')
                    ->join('osr_non_tax_bidding_settlement_details As sd','gd.id','=','sd.osr_non_tax_bidding_general_detail_id')
                    ->join('osr_non_tax_bidder_entries As be','sd.osr_non_tax_bidder_entry_id','=','be.id')
                    ->join('osr_non_tax_asset_final_records As fr','a_short.asset_code','=','fr.asset_code')
                    ->join('zila_parishads AS z','osr_non_tax_asset_entries.zila_id','=','z.id')
                    ->join('anchalik_parishads AS ap','osr_non_tax_asset_entries.anchalik_id','=','ap.id')
                    ->join('gram_panchyats AS gp','osr_non_tax_asset_entries.gram_panchayat_id','=','gp.gram_panchyat_id')
                    ->join('osr_master_non_tax_branches AS c','osr_non_tax_asset_entries.osr_asset_branch_id','=','c.id')
                    ->where([
                        ['fr.fy_id',$max_osr_fy_year],
						['a_short.osr_master_fy_year_id',$max_osr_fy_year],
						['gd.osr_fy_year_id',$max_osr_fy_year],
                        ['fr.defaulter_status',1],
                        ['z.id',$zid],
                        ['ap.id',$apid],
                        ['gp.gram_panchyat_id',$gpid],
                        ['c.id',$bid],
                        ['a_short.level','GP']
                    ])
                    ->select(
                        'a_short.id AS asset_id',
                        'osr_non_tax_asset_entries.asset_code',
                        'osr_non_tax_asset_entries.asset_name',
                        'a_short.level',
                        'c.branch_name',
                        'be.b_f_name',
                        'be.b_m_name',
                        'be.b_l_name',
                        'be.b_father_name',
                        'be.b_mobile',
                        'be.b_pan_no',
                        'z.id AS z_id',
                        'z.zila_parishad_name',
                        'ap.id AS ap_id',
                        'ap.anchalik_parishad_name',
                        'gp.gram_panchyat_id AS gp_id',
                        'gp.gram_panchayat_name')->get();

                foreach ($listOfDefaulterAPWise as $list) {
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
                            $list->level,
                            '<a href="'.route("admin.Osr.assetInformation", [$zid,$max_osr_fy_year,$bid,$list->asset_id,$apid,$gpid]).'">click</a>'
                        )
                    );
                    $i++;
                }
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

    public function osrRevenueAssetBranchList($id, $fy_id, $ap_id=NULL, $gp_id=NULL) {
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $masterBranches=OsrMasterNonTaxBranch::all();
        $otherCats=OsrNonTaxMasterAssetCategory::all();
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;
        $ap_name=NULL;
        $gp_name=NULL;

        if($ap_id==NULL){
            $heade_text="Revenue Collection after sharing by Zila Parishad : ".$district_name." ( ".$fy_years." )";

            $assetList= OsrNonTaxAssetFinalRecord::getZpRevenueBranchList($id, $fy_id, $masterBranches);

            $otherAssetList= OsrNonTaxOtherAssetFinalRecord::getZpRevenueCatList($id, $fy_id, $otherCats);

        }else if($gp_id==NULL){
            $ap_data=AnchalikParishad::getAPName($ap_id);
            $ap_name=$ap_data->anchalik_parishad_name;

            $heade_text="Revenue Collection after sharing by Anchalik Panchayat : ".$ap_name.", Zila Parishad : ".$district_name." ( ".$fy_years." )";

            $assetList= OsrNonTaxAssetFinalRecord::getApRevenueBranchList($id, $ap_id, $fy_id, $masterBranches);

            $otherAssetList= OsrNonTaxOtherAssetFinalRecord::getApRevenueCatList($id, $ap_id, $fy_id, $otherCats);

        }else{
            $ap_data=AnchalikParishad::getAPName($ap_id);
            $ap_name=$ap_data->anchalik_parishad_name;
            $gp_data=GramPanchyat::getGPName($gp_id);
            $gp_name=$gp_data->gram_panchayat_name;

            $heade_text="Revenue Collection after sharing by Gram Panchayat : ".$gp_name.", Anchalik Panchayat : ".$ap_name." , Zila Parishad : ".$district_name." ( ".$fy_years." )";

            $assetList= OsrNonTaxAssetFinalRecord::getGpRevenueBranchList($id, $ap_id, $gp_id, $fy_id, $masterBranches);

            $otherAssetList= OsrNonTaxOtherAssetFinalRecord::getGpRevenueCatList($id, $ap_id, $gp_id, $fy_id, $otherCats);

        }

        $dataCount=[
            "assetList"=>$assetList,
            "otherAssetList"=>$otherAssetList,
        ];

        return view('admin.Osr.Revenue.osrRevenueAssetBranchList',compact('dataCount', 'id','fy_id','district_name','fy_years','masterBranches','heade_text','ap_id','ap_name','gp_id','gp_name','otherCats'));
    }

    public function osrRevenueSingleBranchList($id,$fy_id,$branch_id,$ap_id=NULL,$gp_id=NULL) {
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;
        $branch_name=OsrMasterNonTaxBranch::getBranchById($branch_id)->branch_name;
        $ap_name=NULL;
        $gp_name=NULL;

        if($ap_id==NULL){
            $heade_text="Revenue Collection after sharing from ".$branch_name." by Zila Parishad : ".$district_name." ( ".$fy_years." )";

            $query1=[
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'ZP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
            ];

            $query2=[
				['a_short.osr_master_fy_year_id','=',$fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.level', '=', 'ZP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
            ];

        }else if($gp_id==NULL){
            $ap_data=AnchalikParishad::getAPName($ap_id);
            $ap_name=$ap_data->anchalik_parishad_name;
            $heade_text="Revenue Collection after sharing from ".$branch_name." by Anchalik Panchayat : ".$ap_name." ( ZP : ".$district_name." ) ( ".$fy_years." )";

            $query1=[
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'AP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.anchalik_id', '=', $ap_id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
            ];

            $query2=[
				['a_short.osr_master_fy_year_id','=',$fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.level', '=', 'AP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.ap_id', '=', $ap_id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
            ];

        }else{
            $ap_data=AnchalikParishad::getAPName($ap_id);
            $ap_name=$ap_data->anchalik_parishad_name;
            $gp_data=GramPanchyat::getGPName($gp_id);
            $gp_name=$gp_data->gram_panchayat_name;
            $heade_text="Revenue Collection after sharing from ".$branch_name." by Gram Panchayat : ".$gp_name.", Anchalik Panchayat : ".$ap_name." , Zila Parishad : ".$district_name." ( ".$fy_years." )";

            $query1=[
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'GP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.anchalik_id', '=', $ap_id],
                ['a_entries.gram_panchayat_id', '=', $gp_id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
            ];

            $query2=[
				['a_short.osr_master_fy_year_id','=',$fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.level', '=', 'GP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.ap_id', '=', $ap_id],
                ['a_short.gp_id', '=', $gp_id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
            ];
        }

        $dataCount=[];

        $assetList=OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->where($query1)->select('osr_non_tax_asset_shortlists.id as id','a_entries.id as master_asset_id','a_entries.asset_code','a_entries.asset_name')->get();

        $data= OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->where($query2)
            ->select(DB::raw('
                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,
                            
                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,
                            
							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,
							
                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,
                            
                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share'),'a_short.id')
							->groupBy('a_short.id')
							->get();

        foreach($data AS $li){

            if($ap_id==NULL){
                $gap_c=$li->tot_gap_zp_share+$li->tot_gap_ap_share+$li->tot_gap_gp_share;
                $bid_c=$li->tot_ins_collected_amt;
            }else if($gp_id==NULL){
                $gap_c=$li->tot_gap_ap_share;
                $bid_c=$li->tot_ins_collected_amt;
            }else{
                $gap_c=$li->tot_gap_gp_share;
                $bid_c=$li->tot_ins_collected_amt;
            }

            $dataCount[$li->id]=[
                'gap_c'=>($gap_c), 'bid_c'=>($bid_c)
            ];
        }

        foreach($assetList AS $li){
            if(!isset($dataCount[$li->id])){
                $dataCount[$li->id]=[
                    'gap_c'=>0, 'bid_c'=>0
                ];
            }
        }

        return view('admin.Osr.Revenue.osrRevenueSingleBranchList',compact('dataCount', 'assetList', 'district_name','fy_years','id','fy_id','branch_id','ap_id','gp_id','heade_text','ap_name','gp_name'));
    }

    //Share-------------------------------------------------------------------------------------------------------------

    public function osrShareAssetBranchList($id, $fy_id, $ap_id=NULL, $gp_id=NULL) {
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $masterBranches=OsrMasterNonTaxBranch::all();
        $otherCats=OsrNonTaxMasterAssetCategory::all();
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;
        $ap_name=NULL;
        $gp_name=NULL;



        if($ap_id==NULL){
            $heade_text="Share Distribution by Zila Parishad : ".$district_name." ( ".$fy_years." )";

            $assetList= OsrNonTaxAssetFinalRecord::getZpShareBranchList($id, $fy_id, $masterBranches);

            $otherAssetList= OsrNonTaxOtherAssetFinalRecord::getZpShareCatList($id, $fy_id, $otherCats);


        }else if($gp_id==NULL){
            $ap_data=AnchalikParishad::getAPName($ap_id);
            $ap_name=$ap_data->anchalik_parishad_name;
            $heade_text="Share Distribution by Anchalik Panchayat : ".$ap_name.", Zila Parishad : ".$district_name." ( ".$fy_years." )";

            $assetList= OsrNonTaxAssetFinalRecord::getApShareBranchList($id, $ap_id, $fy_id, $masterBranches);

            $otherAssetList= OsrNonTaxOtherAssetFinalRecord::getApShareCatList($id, $ap_id, $fy_id, $otherCats);
        }else{
            $ap_data=AnchalikParishad::getAPName($ap_id);
            $ap_name=$ap_data->anchalik_parishad_name;
            $gp_data=GramPanchyat::getGPName($gp_id);
            $gp_name=$gp_data->gram_panchayat_name;
            $heade_text="Share Distribution by Gram Panchayat : ".$gp_name.", Anchalik Panchayat : ".$ap_name." , Zila Parishad : ".$district_name." ( ".$fy_years." )";

            $assetList= OsrNonTaxAssetFinalRecord::getGpShareBranchList($id, $ap_id, $gp_id, $fy_id, $masterBranches);

            $otherAssetList= OsrNonTaxOtherAssetFinalRecord::getGpShareCatList($id, $ap_id, $gp_id, $fy_id, $otherCats);

        }

        $dataCount=[
            "assetList"=>$assetList,
            "otherAssetList"=>$otherAssetList,
        ];

        return view('admin.Osr.Share.osrShareAssetBranchList',compact('dataCount','id','fy_id','district_name','fy_years','masterBranches','otherCats','heade_text','ap_id','ap_name','gp_id','gp_name'));
    }

    public function osrShareSingleBranchList($id,$fy_id,$branch_id,$ap_id=NULL,$gp_id=NULL) {
        $district_data=ZilaParishad::getZPName($id);
        $district_name=$district_data->zila_parishad_name;
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;
        $branch_name=OsrMasterNonTaxBranch::getBranchById($branch_id)->branch_name;
        $ap_name=NULL;
        $gp_name=NULL;

        if($ap_id==NULL){
            $heade_text="Share Distribution of ".$branch_name." of Zila Parishad : ".$district_name." (".$fy_years." )";

            $query1=[
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'ZP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
            ];

            $query2=[
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.level', '=', 'ZP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
            ];

        }else if($gp_id==NULL){
            $ap_data=AnchalikParishad::getAPName($ap_id);
            $ap_name=$ap_data->anchalik_parishad_name;
            $heade_text="Share Distribution of  ".$branch_name." of Anchalik Panchayat : ".$ap_name.",  Zila Parishad : ".$district_name." (".$fy_years." )";

            $query1=[
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'AP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.anchalik_id', '=', $ap_id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
            ];

            $query2=[
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.level', '=', 'AP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.ap_id', '=', $ap_id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
            ];
        }else{
            $ap_data=AnchalikParishad::getAPName($ap_id);
            $ap_name=$ap_data->anchalik_parishad_name;
            $gp_data=GramPanchyat::getGPName($gp_id);
            $gp_name=$gp_data->gram_panchayat_name;
            $heade_text="Share Distribution of ".$branch_name." of Gram Panchayat : ".$gp_name.", Anchalik Panchayat : ".$ap_name." , Zila Parishad : ".$district_name." (".$fy_years." )";

            $query1=[
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'GP'],
                ['a_entries.zila_id', '=', $id],
                ['a_entries.anchalik_id', '=', $ap_id],
                ['a_entries.gram_panchayat_id', '=', $gp_id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
            ];

            $query2=[
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.level', '=', 'GP'],
                ['a_short.zp_id', '=', $id],
                ['a_short.ap_id', '=', $ap_id],
                ['a_short.gp_id', '=', $gp_id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
            ];
        }


        $dataCount=[];

        $assetList=OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->where($query1)->select('osr_non_tax_asset_shortlists.id as id','a_entries.id as master_asset_id','a_entries.asset_code','a_entries.asset_name')->get();


        $data= OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->where($query2)
            ->select(DB::raw('
			
							osr_non_tax_asset_final_records.tot_ins_collected_amt,
							
                            osr_non_tax_asset_final_records.f_emd_zp_share,
                            osr_non_tax_asset_final_records.f_emd_ap_share,
                            osr_non_tax_asset_final_records.f_emd_gp_share,
                            
                            osr_non_tax_asset_final_records.df_zp_share,
                            osr_non_tax_asset_final_records.df_ap_share,
                            osr_non_tax_asset_final_records.df_gp_share,
                            
                            osr_non_tax_asset_final_records.tot_ins_zp_share,
                            osr_non_tax_asset_final_records.tot_ins_ap_share,
                            osr_non_tax_asset_final_records.tot_ins_gp_share,
                            
                            osr_non_tax_asset_final_records.tot_gap_zp_share,
                            osr_non_tax_asset_final_records.tot_gap_ap_share,
                            osr_non_tax_asset_final_records.tot_gap_gp_share'),'a_short.id')
            ->get();

        foreach($data AS $li){

            $zp_share=$li->f_emd_zp_share+$li->df_zp_share+$li->tot_ins_zp_share+$li->tot_gap_zp_share;
            $ap_share=$li->f_emd_ap_share+$li->df_ap_share+$li->tot_ins_ap_share+$li->tot_gap_ap_share;
            $gp_share=$li->f_emd_gp_share+$li->df_gp_share+$li->tot_ins_gp_share+$li->tot_gap_gp_share;

            $total_revenue_collection=$li->tot_ins_collected_amt;

            $dataCount[$li->id]=[
                'tot_r_c'=>ConfigMdas::cur_format($total_revenue_collection), 'zp_share'=>ConfigMdas::cur_format($zp_share),
                'ap_share'=>ConfigMdas::cur_format($ap_share), 'gp_share'=> ConfigMdas::cur_format($gp_share)
            ];
        }

        foreach($assetList AS $li){
            if(!isset($dataCount[$li->id])){
                $dataCount[$li->id]=[
                    'tot_r_c'=>0, 'zp_share'=>0, 'ap_share'=>0, 'gp_share'=> 0
                ];
            }
        }

        return view('admin.Osr.Share.osrShareSingleBranchList',compact('dataCount','assetList', 'district_name','fy_years','id','fy_id','branch_id','ap_id','gp_id','heade_text','ap_name','gp_name'));
    }

}
