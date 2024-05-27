<?php

namespace App\Http\Controllers\Admin\Osr;

use App\Osr\OsrNonTaxBiddingGeneralDetail;
use App\CommonModels\ZilaParishad;
use App\Osr\OsrNonTaxOtherAssetEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\CommonModels\District;
use App\Osr\OsrMasterNonTaxBranch;
use App\Osr\OsrNonTaxAssetEntry;
use App\Osr\OsrNonTaxAssetShortlist;
use DB;
use Validator;
use Crypt;
use Illuminate\Support\Facades\Auth;
use App\Osr\OsrMasterFyYear;
use App\Osr\OsrNonTaxMasterAssetCategory;
use App\CommonModels\AnchalikParishad;
use App\CommonModels\GramPanchyat;
use App\Http\Controllers\Controller;
use App\Osr\OsrNonTaxAssetFinalRecord;
use App\Osr\OsrNonTaxOtherAssetFinalRecord;
class AdminOsrController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin_mdas']);
    }

    public function get_formatted_amount($amount){

      return round($amount/10000000,3);
    }
    
    public function osr_dashboard(Request $request){
        
        $assetNontaxs=OsrMasterNonTaxBranch::all();
        $master_fy_years=OsrMasterFyYear::all();
        $zilas = ZilaParishad::all();
        
        
        if(!$request->input('id')){
            $max_osr_fy_year=OsrMasterFyYear::getMaxFyYear();
        }else{
            $max_osr_fy_year=base64_decode($request->input('id'));
        }
        
        $encode_fy_id=base64_encode($max_osr_fy_year);
        
        
        $fy_years=OsrMasterFyYear::getFyYear($max_osr_fy_year)->fy_name;
		
        $master_fys=OsrMasterFyYear::where('id','<=',$max_osr_fy_year)->orderBy('id', 'desc')->take(3)->get();
		
       

//revenue data*****************************************************
        $dataPaiChart=[];
        $card_data=[];
        foreach($master_fys as $fy_year){
               $total_revinue=$this->totalRevinueInFy($fy_year->id);

			   
			   $total_settlement=$this->totalSettlementInFy($fy_year->id);
             
               
               $data=[];
               foreach($assetNontaxs as $assetC){
                    $branchWiseData=$this->getRevinueDataByAssetCategory($fy_year->id,$assetC->id);
                    $data[]=['AC'=>$assetC->branch_name,'data'=>$branchWiseData];

                      if($max_osr_fy_year==$fy_year->id){
                         $dataPaiChart[$assetC->branch_name]=$branchWiseData['total'];
                      }
               }

               $otherAssetCollection=$this->getRevinueDataByAssetCategory($fy_year->id,0);
               if($otherAssetCollection['ZP']){
                 $total_revinue=$total_revinue+$otherAssetCollection['ZP'];
               }
               if($otherAssetCollection['AP']){
                 $total_revinue=$total_revinue+$otherAssetCollection['AP'];
               }      
               if($otherAssetCollection['GP']){
                 $total_revinue=$total_revinue+$otherAssetCollection['GP'];
               }

               if($max_osr_fy_year==$fy_year->id){   
                  $dataPaiChart['Other Assets']=$otherAssetCollection['total'];
               }

               $data[]=['AC'=>'Other Assets','data'=>$otherAssetCollection];


               $card_data[]=['id'=>$fy_year->id, 'FY'=>$fy_year->fy_name,'data'=>$data,'Total'=>$total_revinue,'Settlement'=>$total_settlement];
        }
		
		
		asort($card_data);


  //revinue data end*****************************************************

        //----------------------------------------------- Year wise asset count-----------------------------------------------
        foreach($card_data as $fy_year){
            $fyData = OsrMasterFyYear::getFyYear($fy_year['id']);

            //GHAT,GHAT,FISHERIES,ANIMAL POUND COUNT
            foreach($assetNontaxs as $branch)
            {
                $yrWiseAssetCount[$fy_year['id']][$branch->id] = OsrNonTaxAssetShortlist::yrWiseAssetCount($fy_year['id'],$branch->id);
            }

            //YEAR WISE OTHER ASSET COUNT
            $yrWiseOtherAssetCount[$fy_year['id']]= OsrNonTaxOtherAssetEntry::where([
                ['osr_non_tax_other_asset_entries.other_asset_listing_date','>=',$fyData->fy_from],
                ['osr_non_tax_other_asset_entries.other_asset_listing_date','<=',$fyData->fy_to]
            ])->count();
        }
	//echo json_encode($max_osr_fy_year);
        //---------------------------------------------- zila wise defaulter count--------------------------------------------
        $totalStateCount = OsrNonTaxAssetFinalRecord::totalStateCount($max_osr_fy_year);

        $zpYrWiseSettledAssetCount = OsrNonTaxAssetFinalRecord::zpYrWiseSettledAssetCount($max_osr_fy_year);

        $zpYrWiseDefaulterCount = OsrNonTaxAssetFinalRecord::zpYrWiseDefaulterCount($max_osr_fy_year);
	
	
		//echo json_encode($max_osr_fy_year);

        //---------------------------------------------- zp wise asset settlement count--------------------------------------

        $totalAssetCount = OsrNonTaxAssetFinalRecord::zpWiseTotalAsset($max_osr_fy_year);

        //----------------------------------------------zp wise share--------------------------------------------------------

        $zpYrWiseShareList = OsrNonTaxAssetFinalRecord::zpYrWiseShareList($max_osr_fy_year);

        $zpYrWiseRevenueList = OsrNonTaxAssetFinalRecord::zpWiseRevenueList($max_osr_fy_year);


        
        
        //----------------------------------------------year wise defaulter count--------------------------------------
        foreach($card_data as $fy_year){
            $yrWiseDefaulterCount[$fy_year['id']] = OsrNonTaxAssetFinalRecord::yrWiseDefaulterCount($fy_year['id']);
        }
        
        $dataCount=[
            'zpYrWiseSettledAssetCount' => $zpYrWiseSettledAssetCount,
            'zpYrWiseDefaulterCount' => $zpYrWiseDefaulterCount,
            'totalStateCount' => $totalStateCount,
            'totalAssetCount' => $totalAssetCount,
            'zpYrWiseShareList' => $zpYrWiseShareList,
            'zpYrWiseRevenueList' => $zpYrWiseRevenueList,
			'yrWiseDefaulterCount'=> $yrWiseDefaulterCount
        ];

        //echo json_encode($dataCount);

        
        return view('admin.Osr.osr_dashboard', compact('dataCount','zilas','max_osr_fy_year','master_fy_years','encode_fy_id','fy_years','card_data','yrWiseAssetCount','dataPaiChart', 'yrWiseOtherAssetCount'));
    }

    public function listOfTotalZPDefaulterZilaWise(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Failed to Request Process.";

        $results=[];
        $i=1;

        try {

            $max_osr_fy_year = $request->input('zfyyear');
            
            $listOfDefaulterZilaWise = OsrNonTaxAssetEntry::join('osr_non_tax_asset_shortlists AS a_short','a_short.asset_code','=','osr_non_tax_asset_entries.asset_code')
                ->join('osr_non_tax_bidding_general_details AS gd','osr_non_tax_asset_entries.asset_code','=','gd.asset_code')
                ->join('osr_non_tax_bidding_settlement_details As sd','gd.id','=','sd.osr_non_tax_bidding_general_detail_id')
                ->join('osr_non_tax_bidder_entries As be','sd.osr_non_tax_bidder_entry_id','=','be.id')
                ->join('osr_non_tax_asset_final_records As fr','osr_non_tax_asset_entries.asset_code','=','fr.asset_code')
                ->join('zila_parishads AS z','osr_non_tax_asset_entries.zila_id','=','z.id')
                ->join('osr_master_non_tax_branches AS c','osr_non_tax_asset_entries.osr_asset_branch_id','=','c.id')
                ->where([
                    ['fr.fy_id',$max_osr_fy_year],
					['gd.osr_fy_year_id',$max_osr_fy_year],
					['a_short.osr_master_fy_year_id',$max_osr_fy_year],
                    ['fr.defaulter_status',1],
                    ['a_short.level','ZP']
                ])
                ->select(
                    'a_short.id AS asset_id',
                    'osr_non_tax_asset_entries.asset_code',
                    'osr_non_tax_asset_entries.asset_name',
                    'a_short.level',
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
                    'z.zila_parishad_name')->get();

            foreach ($listOfDefaulterZilaWise as $list) {
				$default_amt = $list->settlement_amt - $list->tot_ins_collected_amt;
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
						$default_amt,
                        $list->level,
                        '<a href="'.route("admin.Osr.assetInformation", [$list->z_id,$max_osr_fy_year,$list->c_id,$list->asset_id]).'">click</a>'
                    )
                );
                $i++;
            }


        }catch(\Exception $e) {
            $returnData['msg'] = "Server Exception.";
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = $results;
        $returnData['msg'] = "Success";
        return response()->json($returnData);
    }

    public function listOfZPDefaulterZilaWise(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Failed to Request Process.";

        $results=[];
        $i=1;

        try {

            $max_osr_fy_year = $request->input('zfyyear');
            $zid = $request->input('zid');

            $listOfDefaulterZilaWise = OsrNonTaxAssetEntry::join('osr_non_tax_asset_shortlists AS a_short','a_short.asset_code','=','osr_non_tax_asset_entries.asset_code')
                ->join('osr_non_tax_bidding_general_details AS gd','osr_non_tax_asset_entries.asset_code','=','gd.asset_code')
                ->join('osr_non_tax_bidding_settlement_details As sd','gd.id','=','sd.osr_non_tax_bidding_general_detail_id')
                ->join('osr_non_tax_bidder_entries As be','sd.osr_non_tax_bidder_entry_id','=','be.id')
                ->join('osr_non_tax_asset_final_records As fr','osr_non_tax_asset_entries.asset_code','=','fr.asset_code')
                ->join('zila_parishads AS z','osr_non_tax_asset_entries.zila_id','=','z.id')
                ->join('osr_master_non_tax_branches AS c','osr_non_tax_asset_entries.osr_asset_branch_id','=','c.id')
                ->where([
                    ['fr.fy_id',$max_osr_fy_year],
					['a_short.osr_master_fy_year_id',$max_osr_fy_year],
					['gd.osr_fy_year_id',$max_osr_fy_year],
                    ['fr.defaulter_status',1],
                    ['z.id',$zid],
                    ['a_short.level','ZP']
                ])
                ->select(
                    'a_short.id AS asset_id',
                    'osr_non_tax_asset_entries.asset_code',
                    'osr_non_tax_asset_entries.asset_name',
                    'a_short.level',
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
                    'z.zila_parishad_name')->get();

            foreach ($listOfDefaulterZilaWise as $list) {
				$default_amt = $list->settlement_amt - $list->tot_ins_collected_amt;
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
						$default_amt,
                        $list->level,
                        '<a href="'.route("admin.Osr.assetInformation", [$list->z_id,$max_osr_fy_year,$list->c_id,$list->asset_id]).'">click</a>'
                    )
                );
                $i++;
            }


        }catch(\Exception $e) {
            $returnData['msg'] = "Server Exception.";
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = $results;
        $returnData['msg'] = "Success";
        return response()->json($returnData);
    }

    public function listOfTotalDefaulterYearWise(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Failed to Request Process.";

        $results=[];
        $i=1;

        try {

            $max_osr_fy_year = $request->input('zfyyear');

            $listOfDefaulterZilaWise = OsrNonTaxAssetEntry::join('osr_non_tax_asset_shortlists AS a_short','a_short.asset_code','=','osr_non_tax_asset_entries.asset_code')
                ->join('osr_non_tax_bidding_general_details AS gd','osr_non_tax_asset_entries.asset_code','=','gd.asset_code')
                ->join('osr_non_tax_bidding_settlement_details As sd','gd.id','=','sd.osr_non_tax_bidding_general_detail_id')
                ->join('osr_non_tax_bidder_entries As be','sd.osr_non_tax_bidder_entry_id','=','be.id')
                ->join('osr_non_tax_asset_final_records As fr','osr_non_tax_asset_entries.asset_code','=','fr.asset_code')
                ->join('zila_parishads AS z','osr_non_tax_asset_entries.zila_id','=','z.id')
                ->join('osr_master_non_tax_branches AS c','osr_non_tax_asset_entries.osr_asset_branch_id','=','c.id')
                ->where([
					['a_short.osr_master_fy_year_id',$max_osr_fy_year],
					['gd.osr_fy_year_id',$max_osr_fy_year],
                    ['fr.fy_id',$max_osr_fy_year],
                    ['fr.defaulter_status',1]
                ])
                ->select(
                    'a_short.id AS asset_id',
                    'osr_non_tax_asset_entries.asset_code',
                    'osr_non_tax_asset_entries.asset_name',
                    'a_short.level',
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
                    'z.zila_parishad_name')->get();
					

            foreach ($listOfDefaulterZilaWise as $list) {
				$default_amt = $list->settlement_amt - $list->tot_ins_collected_amt;
				if($list->level =='AP') {
                    $listDefaulter = OsrNonTaxAssetShortlist::where('id',$list->asset_id)->select('ap_id')->first();
                    $ap_id =$listDefaulter->ap_id;
                    $gp_id=NULL;

                }elseif($list->level =='GP') {
                    $listDefaulter = OsrNonTaxAssetShortlist::where('id',$list->asset_id)->select('ap_id','gp_id')->first();
                    $ap_id =$listDefaulter->ap_id;
                    $gp_id =$listDefaulter->gp_id;

                }else
                {
                $ap_id=NULL;
                $gp_id=NULL;
                }
				
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
						$default_amt,
                        $list->level,
                        '<a href="'.route("admin.Osr.assetInformation", [$list->z_id,$max_osr_fy_year,$list->c_id,$list->asset_id,$ap_id,$gp_id]).'" target=_blank>click</a>'
                    )
                );
                $i++;
            }


        }catch(\Exception $e) {
            $returnData['msg'] = "Server Exception.";
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = $results;
        $returnData['msg'] = "Success";
        return response()->json($returnData);
    }

	public function totalSettlementInFy($fy_id){
		$totalSettledAmount = 0;
		
		$records=OsrNonTaxAssetFinalRecord::where(['fy_id'=>$fy_id,'bidding_status'=>1])->get();
	  
		foreach ($records as $record) {
        if($totalSettledAmount==0){
          $totalSettledAmount=$record->settlement_amt;
		  
        }else{
          $totalSettledAmount=$totalSettledAmount+$record->settlement_amt;
        }
		
      }
	  if($totalSettledAmount!=0){
        $totalSettledAmount=$this->get_formatted_amount($totalSettledAmount);
      }
     return $totalSettledAmount;
	}
    
	public function totalRevinueInFy($fy_id){
      $totalAmount=0;
      $records=OsrNonTaxAssetFinalRecord::where(['fy_id'=>$fy_id,'bidding_status'=>1])->get();
	  
      foreach ($records as $record) {
        if($totalAmount==0){
          $totalAmount=$record->tot_ins_collected_amt+$record->tot_gap_collected_amt;
		  
        }else{
          $totalAmount=$totalAmount+$record->tot_ins_collected_amt+$record->tot_gap_collected_amt;
        }
        //echo "1st:".$totalAmount.'<br>';
        if($record->defaulter_status==1){
           $totalAmount= $totalAmount+$record->security_deposit_amt;
           //echo "2st:".$totalAmount.'<br>';
        }

        if($record->forfeited_emd_sharing_status==1){
          $totalAmount= $totalAmount+$record->total_forfeited_emd_amt;
          //echo "3st:".$totalAmount.'<br>';
        }
        //echo "<br><br>";
      }
		
      if($totalAmount!=0){
        $totalAmount=$this->get_formatted_amount($totalAmount);
      }
     return $totalAmount;
    }

    public function getRevinueDataByAssetCategory($fy_id,$assetC_id){
      if($assetC_id!=0){
         return [
          "ZP"=>$this->get_asset_fy_wise_revinue($fy_id,$assetC_id,'ZP'),
          "AP"=>$this->get_asset_fy_wise_revinue($fy_id,$assetC_id,'AP'),
          "GP"=>$this->get_asset_fy_wise_revinue($fy_id,$assetC_id,'GP'),
          "total"=>$this->get_asset_fy_wise_revinue($fy_id,$assetC_id)
          ];
       }else{
          return [
          "ZP"=>$this->getRevinueDataByOtherAssetCategory($fy_id,'ZP'),
          "AP"=>$this->getRevinueDataByOtherAssetCategory($fy_id,'AP'),
          "GP"=>$this->getRevinueDataByOtherAssetCategory($fy_id,'GP'),
          "total"=>$this->getRevinueDataByOtherAssetCategory($fy_id)
          ];       
       }
    }

    public function getRevinueDataByOtherAssetCategory($fy_id,$assetOwn=NULL){

           $totalAmount=0;
           if($assetOwn){
               $whereArray=[
                'fy_id'=>$fy_id,
                'managed_by'=>$assetOwn
              ];
           }else{
             $whereArray=[
                'fy_id'=>$fy_id
              ];
           }
              $records=OsrNonTaxOtherAssetFinalRecord::join('osr_non_tax_other_asset_entries','osr_non_tax_other_asset_final_records.other_asset_code','=','osr_non_tax_other_asset_entries.other_asset_code')
              ->where($whereArray)
              ->get();

            foreach ($records as $record) {
              if($totalAmount==0){
                $totalAmount=$record->tot_self_collected_amt+$record->tot_ag_collected_amt;
              }else{
                $totalAmount=$totalAmount+$record->tot_self_collected_amt+$record->tot_ag_collected_amt;
              }
              //echo "1st:".$totalAmount.'<br>';
              //echo "<br><br>";
            }
      if($totalAmount!=0){
         $totalAmount=$this->get_formatted_amount($totalAmount);
      }
        return  $totalAmount;
    }

    public function get_asset_fy_wise_revinue($fy_id,$assetC_id,$assetOwn=NULL){
           $totalAmount=0;
           if($assetOwn){
             $whereArray=[
                  'fy_id'=>$fy_id,
				  'a_short.osr_master_fy_year_id'=>$fy_id,
                  'bidding_status'=>1,
                  'a_short.osr_master_non_tax_branch_id'=>$assetC_id,
                  'a_short.level'=>$assetOwn
                ];
            }else{
               $whereArray=[
                  'fy_id'=>$fy_id,
				  'a_short.osr_master_fy_year_id'=>$fy_id,
                  'bidding_status'=>1,
                  'a_short.osr_master_non_tax_branch_id'=>$assetC_id
                ];            
            }
              $records=OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short','osr_non_tax_asset_final_records.asset_code','=','a_short.asset_code')
              ->where($whereArray)
              ->get();
			  
			
            foreach ($records as $record) {
              if($totalAmount==0){
                $totalAmount=$record->tot_ins_collected_amt+$record->tot_gap_collected_amt;
                  
              }else{
                $totalAmount=$totalAmount+$record->tot_ins_collected_amt+$record->tot_gap_collected_amt;
              }
              //echo "1st:".$totalAmount.'<br>';
              if($record->defaulter_status==1){
                 $totalAmount= $totalAmount+$record->security_deposit_amt;
                 //echo "2st:".$totalAmount.'<br>';
              }

              if($record->forfeited_emd_sharing_status==1){
                $totalAmount= $totalAmount+$record->total_forfeited_emd_amt;
                //echo "3st:".$totalAmount.'<br>';
              }

              //echo "<br><br>";
            }
	
      if($totalAmount!=0){
         $totalAmount=$this->get_formatted_amount($totalAmount);
      }
        return  $totalAmount;

    }

    public function osrAssetsReport(Request $request){

	    $fyData=OsrMasterFyYear::all();
	    $zilas = [];
	     $data=[
		   'SettlementData'=>[],
		   'headText'=>NULL,
		   'data_level'=>NULL,
		   'data_fy_id'=>NULL,
          ];
	    
	    if ($request->isMethod('POST')) {
		    
		  $zilas = ZilaParishad::all();
		  $fy_id= $request->input('fy_id');  
		  $level= $request->input('level');
		    
		  $SettlementData = OsrNonTaxAssetFinalRecord::levelWiseSettlementData($fy_id,$level);
		    
		  $settledData = OsrNonTaxAssetFinalRecord::levelWiseSettledAsset($fy_id,$level);
		    
		  $fy_to_date= OsrMasterFyYear::getFyDataId($fy_id);
		    
		  $totalAsset = OsrNonTaxAssetFinalRecord::levelWiseTotalAsset($fy_to_date->fy_to);
		  
		  $shortlistAsset =OsrNonTaxAssetShortlist::levelWiseShortlistAssetCount($fy_id,$level);
		  
		  $notShortlistAsset =OsrNonTaxAssetShortlist::levelWiseNotShortlistedAssetCount($fy_id);
		    
		  $headText = "OSR Non-Tax Asset Report for ".$level." level for " .$fy_to_date->fy_name;
		    
		  $data = [
			  'totalAsset'=>$totalAsset,
			  'shortlistAsset'=>$shortlistAsset,
			  'notShortlistAsset'=>$notShortlistAsset,
			  'SettlementData'=>$SettlementData,
			  'settledData'=>$settledData,
			  'data_level'=>$level,
			  'data_fy_id'=>$fy_id,
			  'headText'=>$headText
		  ];
	    }
	    
        return view('admin.Osr.osrAssetsReport',compact('zilas','fyData','data'));
    }
    
	public function notShortlistedReport($f_id,$id,Request $request){
		
		$fy_id = Crypt::decrypt($f_id);
		$zp_id = Crypt::decrypt($id);

        //dd($zp_id);
		$fy_name = OsrMasterFyYear::getFyYear($fy_id);
		$zp_name = ZilaParishad::getZPName($zp_id);
		
		$head_text = "Not Shortlisted Asset List of ".$fy_name->fy_name ." under ".$zp_name->zila_parishad_name." District";
		
		$notShortlistAsset = OsrNonTaxAssetShortlist::where([
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id',$fy_id],
                ['osr_non_tax_asset_shortlists.level','NA'],
                ['a_entries.zila_id',$zp_id]
            ])
            ->join('osr_non_tax_asset_entries as a_entries','a_entries.asset_code','=','osr_non_tax_asset_shortlists.asset_code')
            ->select('a_entries.asset_name','a_entries.asset_code','osr_non_tax_asset_shortlists.reason')
            ->get();
		
		$data = [
			'head_text'=>$head_text,
			'notShortlistAsset'=>$notShortlistAsset
		];
		
		return view('admin.Osr.osrNotShortlistReport',compact('data'));
	}
    
	
    public function osrBiddingReportIndex(Request $request){
     
     $zilas = ZilaParishad::all();
     $osr_fy = OsrMasterFyYear::all();
     $branchData = OsrMasterNonTaxBranch::all();
         $filterArray=["fy_filter"=>NULL, "zp_filter"=>NULL, "branch_filter"=>NULL];
         $assetList=[];
         $finalData=[];

         if($request->isMethod('post')){
            $zp_id=$request->input('search_zila_id');
            $branch_id=$request->input('search_branch_id');
            $fy_id=$request->input('search_fyYr_id');
            $filterArray= ["fy_filter"=>$fy_id, "zp_filter"=>$zp_id, "branch_filter"=>$branch_id];
            
            $assetList = OsrNonTaxAssetEntry::getAssetEntryByBranchIdAndZId($zp_id,$branch_id);
            
            if(!$assetList){
                $returnData['msg'] = "No data found.";
                return response()->json($returnData);
            }
            $finalList = OsrNonTaxBiddingGeneralDetail::getGeneralDetailByFyId($fy_id);
        
            foreach($finalList AS $li){
                $finalData[$li->osr_asset_entry_id]=[
                    "date_of_tender"=>$li->date_of_tender,
                    "awarded_date"=>$li->awarded_date,
                    "stage"=>$li->stage,
                    "total_bidder"=>$li->total_bidder,
                    "total_withdrawn_bidder"=>$li->total_withdrawn_bidder,
                    "total_forfeited_bidder"=>$li->total_forfeited_bidder,                   
                    "bidding_amt"=>$li->bidding_amt,
                    "bidding_amt"=>$li->bidding_amt,
                    "total_forfeited_amount"=>$li->total_forfeited_amount,
                ];
            }          
         }
         
     return view('admin.Osr.osrBiddingReport',compact('osr_fy','zilas','branchData', 'assetList', 'finalData','filterArray'));
     
     }
	 
    public function asset_status(Request $request){
        
        $assetNontaxs=OsrMasterNonTaxBranch::all();
        $master_fy_years=OsrMasterFyYear::all();
        $zilas = ZilaParishad::all();
        $cats=OsrMasterNonTaxBranch::get_branches();
        $max_fy_id=OsrMasterFyYear::getMaxFyYear();
        $fyList=OsrMasterFyYear::getAllYears();
        $data=[];
        $zila_id=$request->input('$zila_id');
        
        $data=[
            'zila_id'=>$zila_id,
            ];
        
        
        return view('admin.Osr.osrAssetStatus', compact('assetNontaxs','master_fy_years','zilas','data'));
    }
    
    
    public function asset_status_show(Request $request){

        $cats=OsrMasterNonTaxBranch::get_branches();
        $max_fy_id=OsrMasterFyYear::getMaxFyYear();
        $fyList=OsrMasterFyYear::getAllYears();
        $zilas = ZilaParishad::all();
        $zila_id=$request->input('z_id');
		
		$zp_name= ZilaParishad::getZPName($zila_id);
        
        $users=Auth::user();
        $totData=[];

        if($users->mdas_master_role_id<>2){

        }

        $zp_asset=OsrNonTaxAssetShortlist::select(DB::raw('count(*) AS count, osr_master_fy_year_id AS fy_id, zp_id, osr_master_non_tax_branch_id AS cat_id'))
            ->where([
                ["zp_id", "=", $zila_id],
                ["level", "=", "ZP"],
            ])->groupBy('osr_master_fy_year_id', 'zp_id', 'osr_master_non_tax_branch_id')
            ->get();

        $ap_asset=OsrNonTaxAssetShortlist::select(DB::raw('count(*) AS count, osr_master_fy_year_id AS fy_id, zp_id, osr_master_non_tax_branch_id AS cat_id'))
            ->where([
                ["zp_id", "=", $zila_id],
                ["level", "=", "AP"],
            ])->groupBy('osr_master_fy_year_id', 'zp_id', 'osr_master_non_tax_branch_id')
            ->get();

        $gp_asset=OsrNonTaxAssetShortlist::select(DB::raw('count(*) AS count, osr_master_fy_year_id AS fy_id, zp_id, osr_master_non_tax_branch_id AS cat_id'))
            ->where([
                ["zp_id", "=", $zila_id],
                ["level", "=", "GP"],
            ])->groupBy('osr_master_fy_year_id', 'zp_id', 'osr_master_non_tax_branch_id')
            ->get();

        $notselected=OsrNonTaxAssetShortlist::select(DB::raw('count(*) AS count, osr_master_fy_year_id AS fy_id, zp_id, osr_master_non_tax_branch_id AS cat_id'))
            ->where([
                ["zp_id", "=", $zila_id],
                ["level", "=", "NA"],
            ])->groupBy('osr_master_fy_year_id', 'zp_id', 'osr_master_non_tax_branch_id')
            ->get();

        foreach ($fyList AS $fyData){
            $fyWiseTotalAsset=OsrNonTaxAssetEntry::select(DB::raw('count(*) AS count, osr_asset_branch_id AS cat_id'))
                ->where([
                    ["zila_id", "=", $zila_id],
                    ['asset_listing_date','<',$fyData->fy_to]
                ])->groupBy('osr_asset_branch_id')
                ->get();

            $totData[$fyData->id]=$fyWiseTotalAsset;
        }

        $data=[
            'zila_id'=>$zila_id,
            'fy_id'=>$max_fy_id,
            'fyList'=>$fyList,
            'cats'=>$cats,
            'zp_asset'=>$zp_asset,
            'ap_asset'=>$ap_asset,
            'gp_asset'=>$gp_asset,
            'notselected'=>$notselected,
            'totData'=>$totData,
			'zp_name'=>$zp_name
        ];

        return view('admin.Osr.osrAssetStatusTable', compact('data','zilas'));
    }


}
