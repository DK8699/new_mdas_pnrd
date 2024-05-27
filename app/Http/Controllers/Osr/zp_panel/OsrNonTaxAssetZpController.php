<?php

namespace App\Http\Controllers\Osr\zp_panel;

use App\CommonModels\District;
use App\CommonModels\GramPanchyat;
use App\CommonModels\Village;
use App\CommonModels\ZilaParishad;
use App\CommonModels\AnchalikParishad;
use App\ConfigMdas;
use App\Osr\OsrMasterNonTaxBranch;
use App\Osr\OsrNonTaxAssetEntry;
use App\Osr\OsrMasterFyYear;
use App\Osr\OsrNonTaxAssetFinalRecord;
use App\Osr\OsrNonTaxAssetSettlement;
use App\Osr\OsrNonTaxAssetShortlist;
use App\Osr\OsrNonTaxBidderAttachmentUpload;
use App\Osr\OsrNonTaxBiddingAttachmentUpload;
use App\Osr\OsrNonTaxBiddingBiddersDetail;
use App\Osr\OsrNonTaxBiddingGeneralDetail;
use App\Osr\OsrNonTaxBiddingSettlementDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Auth;
use Validator;
class OsrNonTaxAssetZpController extends Controller
{
    //---------------------------------------Settlement-----------------------------------------------------------------

    public function zp_asset_settlement_percent($fy_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);
        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'users'=>$users
        ];

        return view('Osr.non_tax.asset.zp.zp_asset_settlement_percent', compact('data'));
    }

    public function ap_list_asset_settlement_percent($fy_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apList = AnchalikParishad::getAPsByZilaId($users->zp_id);
        $settled=[];
        $shortlist=[];
        $totalScope=[];


        //TOTAL SCOPE
        $totalData = OsrNonTaxAssetEntry::where([
            ['osr_non_tax_asset_entries.asset_under','=','AP'],
            ['osr_non_tax_asset_entries.zila_id','=',$users->zp_id],
            ['asset_listing_date','<',$fyData->fy_to]
        ])->select(DB::raw('count(*) AS total'),'osr_non_tax_asset_entries.anchalik_id as ap_id')
            ->groupBy('osr_non_tax_asset_entries.anchalik_id')
            ->get();

        foreach($totalData AS $li){
            $totalScope[$li->ap_id]=$li->total;
        }

        //SHORLIST

        $shortlistData = OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries','a_entries.asset_code','=','osr_non_tax_asset_shortlists.asset_code')
            ->where([
                ['osr_non_tax_asset_shortlists.level','=','AP'],
                ['osr_non_tax_asset_shortlists.zp_id','=',$users->zp_id],
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id]
            ])->select(DB::raw('count(*) AS total'),'a_entries.anchalik_id as ap_id')
            ->groupBy('a_entries.anchalik_id')
            ->get();

        foreach($shortlistData AS $li){
            $shortlist[$li->ap_id]=$li->total;
        }

        //SETTLEMENT

        $settledData = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['a_short.level','=','AP'],
                ['a_short.zp_id','=',$users->zp_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id','=',$fy_id]
            ])->select(DB::raw('count(*) AS total'),'a_short.ap_id as ap_id')
            ->groupBy('a_short.ap_id')
            ->get();

        foreach($settledData AS $li){
            $settled[$li->ap_id]=$li->total;
        }

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'apList'=>$apList,
            'totalScope'=>$totalScope,
            'shortlist'=>$shortlist,
            'settled'=>$settled,
        ];

        return view('Osr.non_tax.asset.zp.ap_list_asset_settlement_percent',compact('data'));
    }

    public function gp_list_asset_settlement_percent($fy_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $gpList = GramPanchyat::getGPsByZpId($users->zp_id);
        $settled=[];
        $shortlist=[];
        $totalScope=[];


        //TOTAL SCOPE
        $totalData = OsrNonTaxAssetEntry::where([
            ['osr_non_tax_asset_entries.asset_under','=','GP'],
            ['osr_non_tax_asset_entries.zila_id','=',$users->zp_id],
            ['asset_listing_date','<',$fyData->fy_to]
        ])->select(DB::raw('count(*) AS total'),'osr_non_tax_asset_entries.gram_panchayat_id as gp_id')
            ->groupBy('osr_non_tax_asset_entries.gram_panchayat_id')
            ->get();

        foreach($totalData AS $li){
            $totalScope[$li->gp_id]=$li->total;
        }

        //SHORLIST

        $shortlistData = OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries','a_entries.asset_code','=','osr_non_tax_asset_shortlists.asset_code')
            ->where([
                ['osr_non_tax_asset_shortlists.level','=','GP'],
                ['osr_non_tax_asset_shortlists.zp_id','=',$users->zp_id],
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
				['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id]
            ])->select(DB::raw('count(*) AS total'),'osr_non_tax_asset_shortlists.gp_id as gp_id')
            ->groupBy('a_entries.gp_id')
            ->get();

        foreach($shortlistData AS $li){
            $shortlist[$li->gp_id]=$li->total;
        }


        //SETTLEMENT

        $settledData = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['a_short.level','=','GP'],
                ['a_short.zp_id','=',$users->zp_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id', '=', $fy_id]
            ])->select(DB::raw('count(*) AS total'),'a_short.gp_id as gp_id')
            ->groupBy('a_entries.gp_id')
            ->get();

        foreach($settledData AS $li){
            $settled[$li->gp_id]=$li->total;
        }

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'gpList'=>$gpList,
            'totalScope'=>$totalScope,
            'shortlist'=>$shortlist,
            'settled'=>$settled
        ];

        return view('Osr.non_tax.asset.zp.gp_list_asset_settlement_percent', compact('data'));


    }

    public function zp_asset_settlement_percent_branch($fy_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "ZP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
        ])->get();

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branch_id'=>$branch_id,
            'branchData'=>$branchData,
            'zpData'=>$zpData,
            'assetList'=>$assetList,
        ];


        return view('Osr.non_tax.asset.zp.zp_asset_settlement_percent_branch', compact('data'));
    }

    public function ap_asset_settlement_percent($fy_id, $ap_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $ap_id=decrypt($ap_id);
        $apData = AnchalikParishad::getAPName($ap_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);


        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'apData'=>$apData,
        ];

        return view('Osr.non_tax.asset.zp.ap_asset_settlement_percent', compact('data'));
    }

    public function gp_asset_settlement_percent($fy_id, $ap_id, $gp_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $ap_id=decrypt($ap_id);
        $apData = AnchalikParishad::getAPName($ap_id);

        $gp_id=decrypt($gp_id);
        $gpData = GramPanchyat::getGPName($gp_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'ap_id'=>$ap_id,
            'apData'=>$apData,
            'gp_id'=>$gp_id,
            'gpData'=>$gpData,
        ];

        return view('Osr.non_tax.asset.zp.gp_asset_settlement_percent', compact('data'));
    }

    public function ap_asset_settlement_percent_branch($fy_id, $ap_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $ap_id = decrypt($ap_id);
        $apData=AnchalikParishad::getAPName($ap_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "AP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $ap_id],
        ])->get();

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branch_id'=>$branch_id,
            'branchData'=>$branchData,
            'zpData'=>$zpData,
            'apData'=>$apData,

            'assetList'=>$assetList,
        ];


        return view('Osr.non_tax.asset.zp.ap_asset_settlement_percent_branch', compact('data'));
    }

    public function gp_asset_settlement_percent_branch($fy_id, $ap_id, $gp_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $ap_id = decrypt($ap_id);
        $apData=AnchalikParishad::getAPName($ap_id);

        $gp_id = decrypt($gp_id);
        $gpData=GramPanchyat::getGPName($gp_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "GP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $ap_id],
            ['anchalik_id', '=', $ap_id],
            ['gram_panchayat_id', '=', $gp_id],
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

        return view('Osr.non_tax.asset.zp.gp_asset_settlement_percent_branch', compact('data'));
    }

    //--------------------------------Defaulter------------------------------------------------------------------

    public function zp_asset_defaulter($fy_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
        ];
        return view('Osr.non_tax.asset.zp.zp_asset_defaulter',compact('data'));
    }

    public function ap_list_asset_defaulter($fy_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apList = AnchalikParishad::getAPsByZilaId($users->zp_id);
        $settled=[];
        $defaulter=[];


        $settledData = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['a_short.level','=','AP'],
                ['a_short.zp_id','=',$users->zp_id],
				['a_short.osr_master_fy_year_id','=',$fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id]
            ])->select(DB::raw('count(*) AS total'),'a_short.ap_id as ap_id')
            ->groupBy('a_short.ap_id')
            ->get();

        foreach($settledData AS $li){
            $settled[$li->ap_id]=$li->total;
        }

        $defaulterData = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id','=',$fy_id],
                ['osr_non_tax_asset_final_records.bidding_status','=',1],
                ['osr_non_tax_asset_final_records.defaulter_status','=',1],
				['a_short.osr_master_fy_year_id','=',$fy_id],
                ['a_short.zp_id', '=', $users->zp_id],
                ['a_short.level', '=', 'AP']
            ])->select(DB::raw('count(*) AS total'),'a_short.ap_id as ap_id')
            ->groupBy('a_short.ap_id')
            ->get();

        foreach($defaulterData AS $li){
            $defaulter[$li->ap_id]=$li->total;
        }

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'apList'=>$apList,
            'settled'=>$settled,
            'defaulter'=>$defaulter,
        ];

        return view('Osr.non_tax.asset.zp.ap_list_asset_defaulter',compact('data'));


    }

    public function gp_list_asset_defaulter($fy_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apList = AnchalikParishad::getAPsByZilaId($users->zp_id);
        $gpList = GramPanchyat::getGPsByZpId($users->zp_id);

        $settled=[];
        $defaulter=[];


        $settledData = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['a_short.level','=','GP'],
                ['a_short.zp_id','=',$users->zp_id],
				['a_short.osr_master_fy_year_id','=',$fy_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id]
            ])->select(DB::raw('count(*) AS total'),'a_short.gp_id as gp_id')
            ->groupBy('a_short.gp_id')
            ->get();

        foreach($settledData AS $li){
            $settled[$li->gp_id]=$li->total;
        }

        $defaulterData = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id','=',$fy_id],
                ['osr_non_tax_asset_final_records.bidding_status','=',1],
                ['osr_non_tax_asset_final_records.defaulter_status','=',1],
                ['a_short.zp_id', '=', $users->zp_id],
				['a_short.osr_master_fy_year_id','=',$fy_id],
                ['a_short.level', '=', 'GP']
            ])->select(DB::raw('count(*) AS total'),'a_short.gp_id as gp_id')
            ->groupBy('a_short.gp_id')
            ->get();

        foreach($defaulterData AS $li){
            $defaulter[$li->gp_id]=$li->total;
        }

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'apList'=>$apList,
            'gpList'=>$gpList,
            'settled'=>$settled,
            'defaulter'=>$defaulter
        ];

        return view('Osr.non_tax.asset.zp.gp_list_asset_defaulter',compact('data'));

    }

    public function zp_asset_defaulter_branch($fy_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "ZP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
        ])->get();

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branch_id'=>$branch_id,
            'branchData'=>$branchData,
            'zpData'=>$zpData,
            'assetList'=>$assetList,
        ];


        return view('Osr.non_tax.asset.zp.zp_asset_defaulter_branch', compact('data'));
    }

    public function ap_asset_defaulter($fy_id, $ap_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $ap_id=decrypt($ap_id);
        $apData = AnchalikParishad::getAPName($ap_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);


        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'apData'=>$apData,
        ];

        return view('Osr.non_tax.asset.zp.ap_asset_defaulter', compact('data'));
    }

    public function gp_asset_defaulter($fy_id, $ap_id, $gp_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $ap_id=decrypt($ap_id);
        $apData = AnchalikParishad::getAPName($ap_id);

        $gp_id=decrypt($gp_id);
        $gpData = GramPanchyat::getGPName($gp_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'ap_id'=>$ap_id,
            'apData'=>$apData,
            'gp_id'=>$gp_id,
            'gpData'=>$gpData,
        ];

        return view('Osr.non_tax.asset.zp.gp_asset_defaulter', compact('data'));
    }

    public function ap_asset_defaulter_branch($fy_id, $ap_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $ap_id = decrypt($ap_id);
        $apData=AnchalikParishad::getAPName($ap_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "AP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $ap_id],
        ])->get();

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branch_id'=>$branch_id,
            'branchData'=>$branchData,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'assetList'=>$assetList,
        ];


        return view('Osr.non_tax.asset.zp.ap_asset_defaulter_branch', compact('data'));
    }

    public function gp_asset_defaulter_branch($fy_id, $ap_id, $gp_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $ap_id = decrypt($ap_id);
        $apData=AnchalikParishad::getAPName($ap_id);

        $gp_id = decrypt($gp_id);
        $gpData=GramPanchyat::getGPName($gp_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "GP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $ap_id],
            ['anchalik_id', '=', $ap_id],
            ['gram_panchayat_id', '=', $gp_id],
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

        return view('Osr.non_tax.asset.zp.gp_asset_defaulter_branch', compact('data'));
    }

//**************************************Defaulter Osr Panel PORAG-15-10-19****************************************************************************

//District Level Defaulter
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

            $listOfDefaulterZilaWise = OsrNonTaxAssetEntry::join('osr_non_tax_bidding_general_details AS gd','osr_non_tax_asset_entries.asset_code','=','gd.asset_code')
                ->join('osr_non_tax_bidding_settlement_details As sd','gd.id','=','sd.osr_non_tax_bidding_general_detail_id')
                ->join('osr_non_tax_bidder_entries As be','sd.osr_non_tax_bidder_entry_id','=','be.id')
                ->join('osr_non_tax_asset_final_records As fr','osr_non_tax_asset_entries.asset_code','=','fr.asset_code')
                ->join('zila_parishads AS z','osr_non_tax_asset_entries.zila_id','=','z.id')
                ->join('osr_master_non_tax_branches AS c','osr_non_tax_asset_entries.osr_asset_branch_id','=','c.id')
                ->where([
                    ['fr.fy_id',$max_osr_fy_year],
                    ['fr.defaulter_status',1],
                    ['z.id',$zid],
                    ['osr_non_tax_asset_entries.asset_under','ZP']
                ])
                ->select(
                    'osr_non_tax_asset_entries.asset_code',
                    'osr_non_tax_asset_entries.asset_name',
                    'osr_non_tax_asset_entries.asset_under',
                    'c.branch_name',
                    'be.b_f_name',
                    'be.b_m_name',
                    'be.b_l_name',
                    'be.b_father_name',
                    'be.b_mobile',
                    'z.id AS z_id',
                    'z.zila_parishad_name')->get();

            foreach ($listOfDefaulterZilaWise as $list) {
                array_push($results,
                    array(
                        $i,
                        $list->zila_parishad_name,
                        $list->branch_name,
                        $list->asset_code,
                        $list->asset_name,
                        $list->b_f_name.' '.$list->b_m_name.' '.$list->b_l_name,
                        $list->b_father_name,
                        $list->b_mobile,
                        $list->asset_under
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
                    ['osr_non_tax_asset_entries.asset_under','AP']
                ])
                ->select(
                    'osr_non_tax_asset_entries.asset_code',
                    'osr_non_tax_asset_entries.asset_name',
                    'osr_non_tax_asset_entries.asset_under',
                    'c.branch_name',
                    'be.b_f_name',
                    'be.b_m_name',
                    'be.b_l_name',
                    'be.b_father_name',
                    'be.b_mobile',
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
                        $list->b_mobile,
                        $list->asset_under
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
                    ['osr_non_tax_asset_entries.asset_under','GP']
                ])
                ->select(
                    'osr_non_tax_asset_entries.asset_code',
                    'osr_non_tax_asset_entries.asset_name',
                    'osr_non_tax_asset_entries.asset_under',
                    'c.branch_name',
                    'be.b_f_name',
                    'be.b_m_name',
                    'be.b_l_name',
                    'be.b_father_name',
                    'be.b_mobile',
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
                        $list->b_mobile,
                        $list->asset_under
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
//AP Level Defaulter

    public function listOfAPDefaulterAPWise(Request $request)
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
                    'osr_non_tax_asset_entries.asset_code',
                    'osr_non_tax_asset_entries.asset_name',
                    'osr_non_tax_asset_entries.asset_under',
                    'c.branch_name',
                    'be.b_f_name',
                    'be.b_m_name',
                    'be.b_l_name',
                    'be.b_father_name',
                    'be.b_mobile',
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
                        $list->b_mobile,
                        $list->asset_under
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

    public function listOfGPDefaulterAPWise(Request $request)
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
                ->join('gram_panchyats AS gp','osr_non_tax_asset_entries.gram_panchayat_id','=','gp.gram_panchyat_id')
                ->join('osr_master_non_tax_branches AS c','osr_non_tax_asset_entries.osr_asset_branch_id','=','c.id')
                ->where([
                    ['fr.fy_id',$max_osr_fy_year],
                    ['fr.defaulter_status',1],
                    ['z.id',$zid],
                    ['ap.id',$apid],
                    ['osr_non_tax_asset_entries.asset_under','GP']
                ])
                ->select(
                    'osr_non_tax_asset_entries.asset_code',
                    'osr_non_tax_asset_entries.asset_name',
                    'osr_non_tax_asset_entries.asset_under',
                    'c.branch_name',
                    'be.b_f_name',
                    'be.b_m_name',
                    'be.b_l_name',
                    'be.b_father_name',
                    'be.b_mobile',
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
                        $list->b_mobile,
                        $list->asset_under
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
//GP Level Defaulter
    public function listOfGPDefaulterGPWise(Request $request)
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
                    'osr_non_tax_asset_entries.asset_code',
                    'osr_non_tax_asset_entries.asset_name',
                    'osr_non_tax_asset_entries.asset_under',
                    'c.branch_name',
                    'be.b_f_name',
                    'be.b_m_name',
                    'be.b_l_name',
                    'be.b_father_name',
                    'be.b_mobile',
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
                        $list->b_mobile,
                        $list->asset_under
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
//xxxxxxxxxxxxxxxxxx----Defaulter Osr Panel-----xxxxxxxxxxxxxxxxxxxxxxxx
    //-------------------------Collection------------------------------------------------------------------------

    public function ap_list_asset_collection($fy_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apList = AnchalikParishad::getAPsByZilaId($users->zp_id);

        $resArray=[];

        $resList = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'AP'],
                ['a_short.zp_id', '=', $users->zp_id],
            ])->select(DB::raw('  
							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,
							
                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,
                            
                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,
                            
                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,
                            
                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '),'a_short.ap_id AS ap_id')
            ->groupBy('a_short.ap_id')
            ->get();

        foreach($resList AS $li){

            $gap_c=$li->tot_gap_zp_share+$li->tot_gap_ap_share+$li->tot_gap_gp_share;

            $bid_c=$li->tot_ins_collected_amt;

            $tot_c=$gap_c+$bid_c;

            $resArray[$li->ap_id]=[
                'gap_c'=>$gap_c, 'bid_c'=>$bid_c, 'tot_c'=>$tot_c
            ];
        }

        foreach ($apList AS $ap){
            if(!isset($resArray[$ap->id])){
                $resArray[$ap->id]=[
                    'gap_c'=>0, 'bid_c'=>0, 'tot_c'=>0
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

        return view('Osr.non_tax.asset.zp.ap_list_asset_collection',compact('data'));


    }

    public function gp_list_asset_collection($fy_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apList = AnchalikParishad::getAPsByZilaId($users->zp_id);
        $gpList = GramPanchyat::getGPsByZpId($users->zp_id);

        $resArray=[];
        $resList = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'GP'],
                ['a_short.zp_id', '=', $users->zp_id],
            ])->select(DB::raw('     
							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,
			
                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,
                            
                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,
                            
                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,
                            
                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '),'a_short.gp_id AS gp_id')
            ->groupBy('a_short.gp_id')
            ->get();

        foreach($resList AS $li){

            $gap_c=$li->tot_gap_zp_share+$li->tot_gap_ap_share+$li->tot_gap_gp_share;

            $bid_c=$li->tot_ins_collected_amt;

            $tot_c=$gap_c+$bid_c;

            $resArray[$li->gp_id]=[
                'gap_c'=>$gap_c, 'bid_c'=>$bid_c, 'tot_c'=>$tot_c
            ];
        }

        foreach ($gpList AS $gp){
            if(!isset($resArray[$gp->gram_panchyat_id])){
                $resArray[$gp->gram_panchyat_id]=[
                    'gap_c'=>0, 'bid_c'=>0, 'tot_c'=>0
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

        return view('Osr.non_tax.asset.zp.gp_list_asset_collection',compact('data'));
    }

    public function zp_asset_collection_branch($fy_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "ZP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
        ])->get();

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branch_id'=>$branch_id,
            'branchData'=>$branchData,
            'zpData'=>$zpData,
            'assetList'=>$assetList,
        ];


        return view('Osr.non_tax.asset.zp.zp_asset_collection_branch', compact('data'));
    }

    public function ap_asset_collection($fy_id, $ap_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $ap_id=decrypt($ap_id);
        $apData = AnchalikParishad::getAPName($ap_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);


        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'apData'=>$apData,
        ];

        return view('Osr.non_tax.asset.zp.ap_asset_collection', compact('data'));
    }

    public function gp_asset_collection($fy_id, $ap_id, $gp_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $ap_id=decrypt($ap_id);
        $apData = AnchalikParishad::getAPName($ap_id);

        $gp_id=decrypt($gp_id);
        $gpData = GramPanchyat::getGPName($gp_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'ap_id'=>$ap_id,
            'apData'=>$apData,
            'gp_id'=>$gp_id,
            'gpData'=>$gpData,
        ];

        return view('Osr.non_tax.asset.zp.gp_asset_collection', compact('data'));
    }

    public function ap_asset_collection_branch($fy_id, $ap_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $ap_id = decrypt($ap_id);
        $apData=AnchalikParishad::getAPName($ap_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "AP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $ap_id],
        ])->get();

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branch_id'=>$branch_id,
            'branchData'=>$branchData,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'assetList'=>$assetList,
        ];


        return view('Osr.non_tax.asset.zp.ap_asset_collection_branch', compact('data'));
    }

    public function gp_asset_collection_branch($fy_id, $ap_id, $gp_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $ap_id = decrypt($ap_id);
        $apData=AnchalikParishad::getAPName($ap_id);

        $gp_id = decrypt($gp_id);
        $gpData=GramPanchyat::getGPName($gp_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "GP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $ap_id],
            ['anchalik_id', '=', $ap_id],
            ['gram_panchayat_id', '=', $gp_id],
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

        return view('Osr.non_tax.asset.zp.gp_asset_collection_branch', compact('data'));
    }

    //---------------------------------------------Share----------------------------------------------------------

    public function zp_asset_share($fy_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
        ];


        return view('Osr.non_tax.asset.zp.zp_asset_share',compact('data'));
    }

    public function zp_asset_share_branch($fy_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "ZP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
        ])->get();

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branch_id'=>$branch_id,
            'branchData'=>$branchData,
            'zpData'=>$zpData,
            'assetList'=>$assetList,
        ];


        return view('Osr.non_tax.asset.zp.zp_asset_share_branch', compact('data'));
    }

    public function ap_list_asset_share($fy_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apList = AnchalikParishad::getAPsByZilaId($users->zp_id);

        $resArray=[];

        $resList = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->where([
                    ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
					['a_short.osr_master_fy_year_id', '=', $fy_id],
                    ['a_short.level', '=', 'AP'],
                    ['a_short.zp_id', '=', $users->zp_id],
                ])->select(DB::raw('    
							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,
                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,
                            
                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,
                            
                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,
                            
                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '),'a_short.ap_id AS ap_id')
            ->groupBy('a_short.ap_id')
            ->get();

        foreach($resList AS $li){

            $zp_share=$li->f_emd_zp_share+$li->df_zp_share+$li->tot_ins_zp_share+$li->tot_gap_zp_share;
            $ap_share=$li->f_emd_ap_share+$li->df_ap_share+$li->tot_ins_ap_share+$li->tot_gap_ap_share;
            $gp_share=$li->f_emd_gp_share+$li->df_gp_share+$li->tot_ins_gp_share+$li->tot_gap_gp_share;

            $total_revenue_collection=$li->tot_ins_collected_amt;

            $resArray[$li->ap_id]=[
                'tot_r_c'=>$total_revenue_collection, 'zp_share'=>$zp_share, 'ap_share'=>$ap_share, 'gp_share'=> $gp_share
            ];
        }

        foreach ($apList AS $ap){
            if(!isset($resArray[$ap->id])){
                $resArray[$ap->id]=[
                    'tot_r_c'=>0, 'zp_share'=>0, 'ap_share'=>0, 'gp_share'=> 0
                ];
            }
        }

        //echo json_encode($resArray);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'zpData'=>$zpData,
            'apList'=>$apList,
            'resArray'=>$resArray
        ];

        return view('Osr.non_tax.asset.zp.ap_list_asset_share',compact('data'));

    }

    public function ap_asset_share_branch($fy_id, $ap_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $ap_id = decrypt($ap_id);
        $apData=AnchalikParishad::getAPName($ap_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "AP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $ap_id],
        ])->get();

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branch_id'=>$branch_id,
            'branchData'=>$branchData,
            'zpData'=>$zpData,
            'apData'=>$apData,
            'assetList'=>$assetList,
        ];


        return view('Osr.non_tax.asset.zp.ap_asset_share_branch', compact('data'));
    }

    public function gp_list_asset_share($fy_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);
        $apList = AnchalikParishad::getAPsByZilaId($users->zp_id);
        $gpList = GramPanchyat::getGPsByZpId($users->zp_id);

        $resArray=[];
        $resList = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->where([
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'GP'],
                ['a_short.zp_id', '=', $users->zp_id],
            ])->select(DB::raw('    
							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,
                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,
                            
                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,
                            
                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,
                            
                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '),'a_short.gp_id AS gp_id')
            ->groupBy('a_short.gp_id')
            ->get();

        foreach($resList AS $li){

            $zp_share=$li->f_emd_zp_share+$li->df_zp_share+$li->tot_ins_zp_share+$li->tot_gap_zp_share;
            $ap_share=$li->f_emd_ap_share+$li->df_ap_share+$li->tot_ins_ap_share+$li->tot_gap_ap_share;
            $gp_share=$li->f_emd_gp_share+$li->df_gp_share+$li->tot_ins_gp_share+$li->tot_gap_gp_share;

            $total_revenue_collection=$li->tot_ins_collected_amt;

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

        return view('Osr.non_tax.asset.zp.gp_list_asset_share',compact('data'));
    }
    
    public function ap_asset_share($fy_id, $ap_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $ap_id=decrypt($ap_id);
        $apData = AnchalikParishad::getAPName($ap_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);


        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'apData'=>$apData,
        ];

        return view('Osr.non_tax.asset.zp.ap_asset_share', compact('data'));
    }
    
    public function gp_asset_share($fy_id, $ap_id, $gp_id){

        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $ap_id=decrypt($ap_id);
        $apData = AnchalikParishad::getAPName($ap_id);

        $gp_id=decrypt($gp_id);
        $gpData = GramPanchyat::getGPName($gp_id);

        $users=Auth::user();

        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'zpData'=>$zpData,
            'ap_id'=>$ap_id,
            'apData'=>$apData,
            'gp_id'=>$gp_id,
            'gpData'=>$gpData,
        ];

        return view('Osr.non_tax.asset.zp.gp_asset_share', compact('data'));
    }

    public function gp_asset_share_branch($fy_id, $ap_id, $gp_id, $branch_id){
        $fy_id=decrypt($fy_id);
        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $branch_id = decrypt($branch_id);
        $branchData=OsrMasterNonTaxBranch::getBranchById($branch_id);

        $ap_id = decrypt($ap_id);
        $apData=AnchalikParishad::getAPName($ap_id);

        $gp_id = decrypt($gp_id);
        $gpData=GramPanchyat::getGPName($gp_id);

        $users=Auth::user();

        $zpData = ZilaParishad::getZPName($users->zp_id);

        $assetList=OsrNonTaxAssetEntry::where([
            ['asset_under', '=', "GP"],
            ['osr_asset_branch_id', '=', $branch_id],
            ['zila_id', '=', $users->zp_id],
            ['anchalik_id', '=', $ap_id],
            ['anchalik_id', '=', $ap_id],
            ['gram_panchayat_id', '=', $gp_id],
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

        return view('Osr.non_tax.asset.zp.gp_asset_share_branch', compact('data'));
    }

    // SETTLEMENT AND DEFAULTER COMMON -------------------------------------------------------------------------------

    public function branch_list_settlement_defaulter($fy_id, $page_for, $level, $zp_id, $ap_id=NULL, $gp_id=NULL){

        $fy_id=decrypt($fy_id);
        $page_for=decrypt($page_for);
        $level=decrypt($level);
        $zp_id=decrypt($zp_id);
        $ap_id=decrypt($ap_id);
        $gp_id=decrypt($gp_id);
        $apData = NULL;
        $gpData = NULL;
        $totalScope = [];
        $shortlist = [];
        $settled = [];
        $defaulter = [];
        $head_txt2 = NULL;

        $page_for_list=["SETTLEMENT", "DEFAULTER"];
        $level_list=["ZP", "AP", "GP"];

        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();
        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);

        if(!in_array($page_for, $page_for_list) || !in_array($level, $level_list)){

        }

        if($level=="ZP"){
            $totalWhereArray = [
                ['osr_non_tax_asset_entries.asset_under','=','ZP'],
                ['osr_non_tax_asset_entries.zila_id','=',$zp_id],
                ['osr_non_tax_asset_entries.asset_listing_date','<',$fyData->fy_to]
            ];

            $shortlistWhereArray = [
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['a_entries.zila_id', '=', $zp_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'ZP'],
            ];

            $settledWhereArray = [
                ['a_short.level','=','ZP'],
                ['a_short.zp_id','=',$zp_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id','=', $fy_id]
            ];

            $defaulterWhereArray = [
                ['osr_non_tax_asset_final_records.fy_id','=',$fy_id],
                ['osr_non_tax_asset_final_records.bidding_status','=',1],
                ['osr_non_tax_asset_final_records.defaulter_status','=',1],
				['a_short.osr_master_fy_year_id','=', $fy_id],
                ['a_short.zp_id', '=', $zp_id],
                ['a_short.level', '=', 'ZP']
            ];
        }

        elseif($level=="AP"){
            $apData = AnchalikParishad::getAPName($ap_id);
            $totalWhereArray = [
                ['osr_non_tax_asset_entries.asset_under','=','AP'],
                ['osr_non_tax_asset_entries.zila_id','=',$zp_id],
                ['osr_non_tax_asset_entries.anchalik_id','=',$ap_id],
                ['osr_non_tax_asset_entries.asset_listing_date','<',$fyData->fy_to]
            ];

            $shortlistWhereArray = [
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['a_entries.zila_id', '=', $zp_id],
                ['a_entries.anchalik_id', '=', $ap_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'AP'],
            ];

            $settledWhereArray = [
                ['a_short.level','=','AP'],
                ['a_short.zp_id','=',$zp_id],
                ['a_short.ap_id','=',$ap_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id','=', $fy_id]
            ];

            $defaulterWhereArray = [
                ['osr_non_tax_asset_final_records.fy_id','=',$fy_id],
                ['osr_non_tax_asset_final_records.bidding_status','=',1],
                ['osr_non_tax_asset_final_records.defaulter_status','=',1],
				['a_short.osr_master_fy_year_id','=', $fy_id],
                ['a_short.zp_id', '=', $zp_id],
                ['a_short.ap_id','=',$ap_id],
                ['a_short.level', '=', 'AP']
            ];

        }

        else{
            $apData = AnchalikParishad::getAPName($ap_id);
            $gpData = GramPanchyat::getGPName($gp_id);

            $totalWhereArray = [
                ['osr_non_tax_asset_entries.asset_under','=','GP'],
                ['osr_non_tax_asset_entries.zila_id','=',$zp_id],
                ['osr_non_tax_asset_entries.anchalik_id','=',$ap_id],
                ['osr_non_tax_asset_entries.gram_panchayat_id','=',$gp_id],
                ['osr_non_tax_asset_entries.asset_listing_date','<',$fyData->fy_to]
            ];

            $shortlistWhereArray = [
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['a_entries.zila_id', '=', $zp_id],
                ['a_entries.anchalik_id', '=', $ap_id],
                ['a_entries.gram_panchayat_id', '=', $gp_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'GP'],
            ];

            $settledWhereArray = [
                ['a_short.level','=','GP'],
                ['a_short.zp_id','=',$zp_id],
                ['a_short.ap_id', '=', $ap_id],
                ['a_short.gp_id', '=', $gp_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id','=', $fy_id]
            ];

            $defaulterWhereArray = [
                ['osr_non_tax_asset_final_records.fy_id','=',$fy_id],
                ['osr_non_tax_asset_final_records.bidding_status','=',1],
                ['osr_non_tax_asset_final_records.defaulter_status','=',1],
				['a_short.osr_master_fy_year_id','=', $fy_id],
                ['a_short.zp_id', '=', $zp_id],
                ['a_short.ap_id', '=', $ap_id],
                ['a_short.gp_id', '=', $gp_id],
                ['a_short.level', '=', 'GP']
            ];

        }

        if($page_for=="SETTLEMENT")
        {
            $totalData = OsrNonTaxAssetEntry::where($totalWhereArray)
                ->select(DB::raw('count(*) AS total'),'osr_non_tax_asset_entries.osr_asset_branch_id as b_id')
                ->groupBy('osr_non_tax_asset_entries.osr_asset_branch_id')
                ->get();

            foreach($totalData AS $li){
                $totalScope[$li->b_id]= $li->total;
            }

            //echo json_encode($totalScope);

            $shortlistData = OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries','a_entries.asset_code','=','osr_non_tax_asset_shortlists.asset_code')
                ->where($shortlistWhereArray)
                ->select(DB::raw('count(*) AS total'),'a_entries.osr_asset_branch_id as b_id')
                ->groupBy('a_entries.osr_asset_branch_id')
                ->get();

            foreach($shortlistData AS $li){
                $shortlist[$li->b_id]=$li->total;
            }

            $settledData = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
                ->where($settledWhereArray)
                ->select(DB::raw('count(*) AS total'),'a_short.osr_master_non_tax_branch_id as b_id')
                ->groupBy('a_short.osr_master_non_tax_branch_id')
                ->get();

            foreach($settledData AS $li){
                $settled[$li->b_id]=$li->total;
            }

        }

        else{
            $settledData = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
                ->where($settledWhereArray)
                ->select(DB::raw('count(*) AS total'),'a_short.osr_master_non_tax_branch_id as b_id')
                ->groupBy('a_short.osr_master_non_tax_branch_id')
                ->get();

            foreach($settledData AS $li){
                $settled[$li->b_id]=  $li->total;
            }

            $defaulterData =  OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
                ->where($defaulterWhereArray)
                ->select(DB::raw('count(*) AS total'),'a_short.osr_master_non_tax_branch_id as b_id')
                ->groupBy('a_short.osr_master_non_tax_branch_id')
                ->get();

            foreach($defaulterData AS $li){
                $defaulter[$li->b_id] = $li->total;
            }
        }

        if($page_for=="SETTLEMENT"){
            if($level=="ZP"){
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Settlement of Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }elseif ($level=="AP"){
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Settlement of Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }else{
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Settlement of Gram Panchayat : ".$gpData->gram_panchayat_name.", Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }
        }
        else{
            if($level=="ZP"){
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Defaulter of Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
                $head_txt2="Defaulter of Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }elseif ($level=="AP"){
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Defaulter of Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : $zpData->zila_parishad_name (".$fyData->fy_name.")";
                $head_txt2="Defaulter of Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : $zpData->zila_parishad_name (".$fyData->fy_name.")";
            }else{
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Defaulter of Gram Panchayat : ".$gpData->gram_panchayat_name.", Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
                $head_txt2="Defaulter of Gram Panchayat : ".$gpData->gram_panchayat_name.", Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }
        }

        $data = [
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'page_for'=>$page_for,
            'zpData'=>$zpData,
            'apData'=>$zpData,
            'gpData'=>$zpData,
            'totalScope'=>$totalScope,
            'shortlist'=>$shortlist,
            'settled'=>$settled,
            'defaulter'=>$defaulter,
            'head_txt'=>$head_txt,
            'head_txt2'=>$head_txt2,

            'level'=>$level,
            'zp_id'=>$zp_id,
            'ap_id'=>$ap_id,
            'gp_id'=>$gp_id,
        ];

        return view('Osr.non_tax.asset.common.branch_list_settlement_defaulter',compact('data'));
    }

    public function single_branch_settlement_defaulter($fy_id, $page_for, $level, $branch_id, $zp_id, $ap_id=NULL, $gp_id=NULL){

        $fy_id=decrypt($fy_id);
        $page_for=decrypt($page_for);
        $level=decrypt($level);
        $branch_id=decrypt($branch_id);
        $zp_id=decrypt($zp_id);
        $ap_id=decrypt($ap_id);
        $gp_id=decrypt($gp_id);
        $apData = NULL;
        $gpData = NULL;
        $totalScope = [];
        $shortlist = [];
        $settled = [];
        $defaulter = [];

        $page_for_list=["SETTLEMENT", "DEFAULTER"];
        $level_list=["ZP", "AP", "GP"];

        $fyData = OsrMasterFyYear::getFyYear($fy_id);

        $users=Auth::user();
        $branchData = OsrMasterNonTaxBranch::getBranchById($branch_id);
        $zpData = ZilaParishad::getZPName($users->zp_id);

        if(!in_array($page_for, $page_for_list) || !in_array($level, $level_list)){

        }
        if($level=="ZP"){

            $totalWhereArray = [
                ['osr_non_tax_asset_entries.asset_under','=','ZP'],
                ['osr_non_tax_asset_entries.zila_id','=',$zp_id],
                ['osr_non_tax_asset_entries.osr_asset_branch_id', '=', $branch_id],
                ['osr_non_tax_asset_entries.asset_listing_date','<',$fyData->fy_to]
            ];

            $shortlistWhereArray = [
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['a_entries.zila_id', '=', $zp_id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'ZP'],
            ];

            $settledWhereArray = [
                ['a_short.level','=','ZP'],
                ['a_entries.zila_id','=',$zp_id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id', '=', $fy_id]
            ];

            $defaulterWhereArray = [
                ['osr_non_tax_asset_final_records.fy_id','=',$fy_id],
                ['osr_non_tax_asset_final_records.bidding_status','=',1],
                ['osr_non_tax_asset_final_records.defaulter_status','=',1],
                ['a_short.zp_id', '=', $zp_id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
                ['a_short.level', '=', 'ZP'],
				['a_short.osr_master_fy_year_id', '=', $fy_id]
            ];

        }

        elseif($level=="AP"){
            $apData = AnchalikParishad::getAPName($ap_id);

            $totalWhereArray = [

                ['osr_non_tax_asset_entries.asset_under','=','AP'],
                ['osr_non_tax_asset_entries.zila_id','=',$zp_id],
                ['osr_non_tax_asset_entries.anchalik_id','=',$ap_id],
                ['osr_non_tax_asset_entries.osr_asset_branch_id', '=', $branch_id],
                ['osr_non_tax_asset_entries.asset_listing_date','<',$fyData->fy_to]
            ];

            $shortlistWhereArray = [
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['a_entries.zila_id', '=', $zp_id],
                ['a_entries.anchalik_id', '=', $ap_id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'AP'],
            ];

            $settledWhereArray = [
                ['a_short.level','=','AP'],
                ['a_short.zp_id','=',$zp_id],
                ['a_short.ap_id','=',$ap_id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id', '=', $fy_id]
            ];

            $defaulterWhereArray = [
                ['osr_non_tax_asset_final_records.fy_id','=',$fy_id],
                ['osr_non_tax_asset_final_records.bidding_status','=',1],
                ['osr_non_tax_asset_final_records.defaulter_status','=',1],
                ['a_short.zp_id', '=', $zp_id],
                ['a_short.ap_id','=',$ap_id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
				['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'AP']
            ];
        }

        else{
            $apData = AnchalikParishad::getAPName($ap_id);
            $gpData = GramPanchyat::getGPName($gp_id);

            $totalWhereArray = [
                ['osr_non_tax_asset_entries.asset_under','=','GP'],
                ['osr_non_tax_asset_entries.zila_id','=',$zp_id],
                ['osr_non_tax_asset_entries.anchalik_id','=',$ap_id],
                ['osr_non_tax_asset_entries.gram_panchayat_id','=',$gp_id],
                ['osr_non_tax_asset_entries.osr_asset_branch_id', '=', $branch_id],
                ['osr_non_tax_asset_entries.asset_listing_date','<',$fyData->fy_to]
            ];

            $shortlistWhereArray = [
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['a_entries.zila_id', '=', $zp_id],
                ['a_entries.anchalik_id', '=', $ap_id],
                ['a_entries.gram_panchayat_id', '=', $gp_id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'GP'],
            ];

            $settledWhereArray = [
                ['a_entries.asset_under','=','GP'],
                ['a_entries.zila_id','=',$zp_id],
                ['a_entries.anchalik_id', '=', $ap_id],
                ['a_entries.gram_panchayat_id', '=', $gp_id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id]
            ];

            $defaulterWhereArray = [
                ['osr_non_tax_asset_final_records.fy_id','=',$fy_id],
                ['osr_non_tax_asset_final_records.bidding_status','=',1],
                ['osr_non_tax_asset_final_records.defaulter_status','=',1],
                ['a_short.zp_id', '=', $zp_id],
                ['a_short.ap_id', '=', $ap_id],
                ['a_short.gp_id', '=', $gp_id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
                ['a_short.level', '=', 'GP']
            ];
        }

        $assetList=OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->where($shortlistWhereArray)
            ->select('osr_non_tax_asset_shortlists.id', 'a_entries.asset_code', 'a_entries.asset_name')
            ->get();

        $settledData = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->join('osr_non_tax_asset_entries as a_entries','a_entries.asset_code','=','a_short.asset_code')
			->where($settledWhereArray)
            ->select('a_short.id','osr_non_tax_asset_final_records.settlement_amt')
            ->get();

        foreach($settledData AS $li){

            $settled[$li->id]=[
                'settlement_amt'=>$li->settlement_amt
            ];
        }

        foreach($assetList AS $li){
            if(!isset($settled[$li->id])){
                $settled[$li->id]=[
                    'settlement_amt'=>0
                ];
            }
        }


        if($page_for=="SETTLEMENT")
        {
            $totalData = OsrNonTaxAssetEntry::where($totalWhereArray)
                ->select(DB::raw('count(*) AS total'),'osr_non_tax_asset_entries.id as a_id')
                ->groupBy('osr_non_tax_asset_entries.id')
                ->get();

            foreach($totalData AS $li){
                $totalScope[$li->a_id]= $li->total;
            }

            $shortlistData = OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries','a_entries.asset_code','=','osr_non_tax_asset_shortlists.asset_code')
                ->where($shortlistWhereArray)
                ->select(DB::raw('count(*) AS total'),'a_entries.id as a_id')
                ->groupBy('a_entries.id')
                ->get();

            foreach($shortlistData AS $li){
                $shortlist[$li->b_id]=$li->total;
            }
        }
        else
        {
            $defaulterData =  OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code', '=', 'osr_non_tax_asset_final_records.asset_code')
                ->join('osr_non_tax_bidding_general_details as general','general.asset_code','=','osr_non_tax_asset_final_records.asset_code')
                ->join('osr_non_tax_bidding_settlement_details as settlement','settlement.osr_non_tax_bidding_general_detail_id','=','general.id')
                ->join('osr_non_tax_bidder_entries as bidder','bidder.id','=','settlement.osr_non_tax_bidder_entry_id')
                ->where($defaulterWhereArray)
                ->select('a_short.id','bidder.b_f_name','bidder.b_m_name','bidder.b_l_name','b_father_name','bidder.b_pan_no')
                ->get();

            foreach($defaulterData AS $li){
                $defaulter[$li->id]=[
                    'b_f_name'=>$li->b_f_name,
                    'b_m_name'=>$li->b_m_name,
                    'b_l_name'=>$li->b_l_name,
                    'b_father_name'=>$li->b_father_name,
                    'b_pan_no'=>$li->b_pan_no
                ];
            }

            foreach($assetList AS $li){
                if(!isset($defaulter[$li->id])){
                    $defaulter[$li->id]=[
                        'b_f_name'=> '-',
                        'b_m_name'=> '-',
                        'b_l_name'=> '-',
                        'b_father_name'=> '- - -',
                        'b_pan_no'=> '- - -'
                    ];
                }
            }
        }

        if($page_for=="SETTLEMENT"){
            if($level=="ZP"){
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Settlement of ".$branchData->branch_name." of Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }elseif ($level=="AP"){
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Settlement of ".$branchData->branch_name." of Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }else{
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Settlement of ".$branchData->branch_name." of Gram Panchayat : ".$gpData->gram_panchayat_name.", Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }
        }else{
            if($level=="ZP"){
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Defaulter of ".$branchData->branch_name." of Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }elseif ($level=="AP"){
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Defaulter of ".$branchData->branch_name." of Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : $zpData->zila_parishad_name (".$fyData->fy_name.")";
            }else{
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Defaulter of ".$branchData->branch_name." of Gram Panchayat : ".$gpData->gram_panchayat_name.", Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }
        }

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branchData'=>$branchData,
            'assetList'=>$assetList,
            'page_for'=>$page_for,
            'zpData'=>$zpData,
            'apData'=>$zpData,
            'gpData'=>$zpData,
            'totalScope'=>$totalScope,
            'shortlist'=>$shortlist,
            'settled'=>$settled,
            'defaulter'=>$defaulter,
            'head_txt'=>$head_txt,
            'level'=>$level,
            'zp_id'=>$zp_id,
            'ap_id'=>$ap_id,
            'gp_id'=>$gp_id,
        ];

        return view('Osr.non_tax.asset.common.single_branch_settlement_defaulter',compact('data'));
    }

    // SHARE AND REVENUE COMMON ----------------------------------------------------------------------------------------

    public function branch_list_revenue_share($fy_id, $page_for, $level, $zp_id, $ap_id=NULL, $gp_id=NULL){
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
        $branches = OsrMasterNonTaxBranch::get_branches();
        $zpData = ZilaParishad::getZPName($users->zp_id);

        if(!in_array($page_for, $page_for_list) || !in_array($level, $level_list)){

        }

        if($level=="ZP"){
            $whereArray=[
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
                ['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'ZP'],
                ['a_short.zp_id', '=', $zp_id],
            ];
        }elseif($level=="AP"){
            $apData = AnchalikParishad::getAPName($ap_id);
            $whereArray=[
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'AP'],
                ['a_short.zp_id', '=', $zp_id],
                ['a_short.ap_id', '=', $ap_id],
            ];
        }else{
            $apData = AnchalikParishad::getAPName($ap_id);
            $gpData = GramPanchyat::getGPName($gp_id);
            $whereArray=[
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'GP'],
                ['a_short.zp_id', '=', $zp_id],
                ['a_short.ap_id', '=', $ap_id],
                ['a_short.gp_id', '=', $gp_id],
            ];
        }

        $resList = OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
            ->leftJoin('osr_non_tax_asset_entries as a_entries','a_entries.asset_code','=','a_short.asset_code')
			->where($whereArray)
            ->select(DB::raw('
							sum(osr_non_tax_asset_final_records.tot_ins_collected_amt) AS tot_ins_collected_amt,
			
                            sum(osr_non_tax_asset_final_records.f_emd_zp_share) AS f_emd_zp_share,
                            sum(osr_non_tax_asset_final_records.f_emd_ap_share) AS f_emd_ap_share,
                            sum(osr_non_tax_asset_final_records.f_emd_gp_share) AS f_emd_gp_share,
                            
                            sum(osr_non_tax_asset_final_records.df_zp_share) AS df_zp_share,
                            sum(osr_non_tax_asset_final_records.df_ap_share) AS df_ap_share,
                            sum(osr_non_tax_asset_final_records.df_gp_share) AS df_gp_share,
                            
                            sum(osr_non_tax_asset_final_records.tot_ins_zp_share) AS tot_ins_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_ap_share) AS tot_ins_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_ins_gp_share) AS tot_ins_gp_share,
                            
                            sum(osr_non_tax_asset_final_records.tot_gap_zp_share) AS tot_gap_zp_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_ap_share) AS tot_gap_ap_share,
                            sum(osr_non_tax_asset_final_records.tot_gap_gp_share) AS tot_gap_gp_share
                            '),'a_entries.osr_asset_branch_id as b_id')
            ->groupBy('a_entries.osr_asset_branch_id')
            ->get();

        foreach($resList AS $li){
            if($page_for=="REVENUE"){

                $gap_c=$li->tot_gap_zp_share+$li->tot_gap_ap_share+$li->tot_gap_gp_share;

                $bid_c=$li->tot_ins_collected_amt;

                $tot_c=$gap_c+$bid_c;

                $resArray[$li->b_id]=[
                    'gap_c'=>$gap_c, 'bid_c'=>$bid_c, 'tot_c'=>$tot_c
                ];

            }else{
                $zp_share=$li->f_emd_zp_share+$li->df_zp_share+$li->tot_ins_zp_share+$li->tot_gap_zp_share;
                $ap_share=$li->f_emd_ap_share+$li->df_ap_share+$li->tot_ins_ap_share+$li->tot_gap_ap_share;
                $gp_share=$li->f_emd_gp_share+$li->df_gp_share+$li->tot_ins_gp_share+$li->tot_gap_gp_share;

                $total_revenue_collection=$li->tot_ins_collected_amt;

                $resArray[$li->b_id]=[
                    'tot_r_c'=>$total_revenue_collection, 'zp_share'=>$zp_share, 'ap_share'=>$ap_share, 'gp_share'=> $gp_share
                ];

            }
        }


        foreach ($branches AS $branch){
            if(!isset($resArray[$branch->id])){
                if($page_for=="REVENUE") {
                    $resArray[$branch->id]=[
                        'gap_c'=>0, 'bid_c'=>0, 'tot_c'=>0
                    ];
                }else{
                    $resArray[$branch->id] = [
                        'tot_r_c' => 0, 'zp_share' => 0, 'ap_share' => 0, 'gp_share' => 0
                    ];
                }
            }
        }

        if($page_for=="REVENUE"){
            if($level=="ZP"){
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Revenue Collection of Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }elseif ($level=="AP"){
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Revenue Collection of Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }else{
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Revenue Collection of Gram Panchayat : ".$gpData->gram_panchayat_name.", Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }
        }else{
            if($level=="ZP"){
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Share Distribution of Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }elseif ($level=="AP"){
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Share Distribution of Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : $zpData->zila_parishad_name (".$fyData->fy_name.")";
            }else{
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Share Distribution of Gram Panchayat : ".$gpData->gram_panchayat_name.", Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }
        }

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branches'=>$branches,
            'page_for'=>$page_for,
            'zpData'=>$zpData,
            'apData'=>$zpData,
            'gpData'=>$zpData,
            'resArray'=>$resArray,
            'head_txt'=>$head_txt,

            'level'=>$level,
            'zp_id'=>$zp_id,
            'ap_id'=>$ap_id,
            'gp_id'=>$gp_id,
        ];

        return view('Osr.non_tax.asset.common.branch_list_revenue_share',compact('data'));
    }

    public function single_branch_revenue_share($fy_id, $page_for, $level, $branch_id, $zp_id, $ap_id=NULL, $gp_id=NULL){
        $fy_id=decrypt($fy_id);
        $page_for=decrypt($page_for);
        $level=decrypt($level);
        $branch_id=decrypt($branch_id);
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
        $branchData = OsrMasterNonTaxBranch::getBranchById($branch_id);
        $zpData = ZilaParishad::getZPName($users->zp_id);

        if(!in_array($page_for, $page_for_list) || !in_array($level, $level_list)){

        }

        if($level=="ZP"){
            $query1=[
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'ZP'],
                ['a_entries.zila_id', '=', $zp_id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
            ];

            $query2=[
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'ZP'],
                ['a_short.zp_id', '=', $zp_id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
            ];
        }elseif($level=="AP"){
            $apData = AnchalikParishad::getAPName($ap_id);

            $query1=[
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'AP'],
                ['a_entries.zila_id', '=', $zp_id],
                ['a_entries.anchalik_id', '=', $ap_id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
            ];

            $query2=[
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'AP'],
                ['a_short.zp_id', '=', $zp_id],
                ['a_short.ap_id', '=', $ap_id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
            ];
        }else{
            $apData = AnchalikParishad::getAPName($ap_id);
            $gpData = GramPanchyat::getGPName($gp_id);

            $query1=[
                ['osr_non_tax_asset_shortlists.osr_master_fy_year_id', '=', $fy_id],
                ['osr_non_tax_asset_shortlists.level', '=', 'GP'],
                ['a_entries.zila_id', '=', $zp_id],
                ['a_entries.anchalik_id', '=', $ap_id],
                ['a_entries.gram_panchayat_id', '=', $gp_id],
                ['a_entries.osr_asset_branch_id', '=', $branch_id],
            ];

            $query2=[
                ['osr_non_tax_asset_final_records.fy_id', '=', $fy_id],
				['a_short.osr_master_fy_year_id', '=', $fy_id],
                ['a_short.level', '=', 'GP'],
                ['a_short.zp_id', '=', $zp_id],
                ['a_short.ap_id', '=', $ap_id],
                ['a_short.gp_id', '=', $gp_id],
                ['a_short.osr_master_non_tax_branch_id', '=', $branch_id],
            ];
        }

        $assetList=OsrNonTaxAssetShortlist::join('osr_non_tax_asset_entries as a_entries', 'a_entries.asset_code', '=', 'osr_non_tax_asset_shortlists.asset_code')
            ->where($query1)
            ->select('osr_non_tax_asset_shortlists.id as id','a_entries.id as master_asset_id', 'a_entries.asset_code', 'a_entries.asset_name')
            ->get();

        $resList= OsrNonTaxAssetFinalRecord::join('osr_non_tax_asset_shortlists as a_short', 'a_short.asset_code','=','osr_non_tax_asset_final_records.asset_code')
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

        foreach($resList AS $li){
            if($page_for=="REVENUE"){

                $gap_c=$li->tot_gap_zp_share+$li->tot_gap_ap_share+$li->tot_gap_gp_share;

                $bid_c=$li->tot_ins_collected_amt;

                $tot_c=$gap_c+$bid_c;

                $resArray[$li->id]=[
                    'gap_c'=>$gap_c, 'bid_c'=>$bid_c, 'tot_c'=>$tot_c
                ];

            }else{
                $zp_share=$li->f_emd_zp_share+$li->df_zp_share+$li->tot_ins_zp_share+$li->tot_gap_zp_share;
                $ap_share=$li->f_emd_ap_share+$li->df_ap_share+$li->tot_ins_ap_share+$li->tot_gap_ap_share;
                $gp_share=$li->f_emd_gp_share+$li->df_gp_share+$li->tot_ins_gp_share+$li->tot_gap_gp_share;

                $total_revenue_collection=$zp_share+$ap_share+$gp_share;

                $resArray[$li->id]=[
                    'tot_r_c'=>$total_revenue_collection, 'zp_share'=>$zp_share, 'ap_share'=>$ap_share, 'gp_share'=> $gp_share
                ];
            }
        }

        foreach ($assetList AS $asset){
            if(!isset($resArray[$asset->id])){
                if($page_for=="REVENUE") {
                    $resArray[$asset->id]=[
                        'gap_c'=>0, 'bid_c'=>0, 'tot_c'=>0
                    ];
                }else{
                    $resArray[$asset->id] = [
                        'tot_r_c' => 0, 'zp_share' => 0, 'ap_share' => 0, 'gp_share' => 0
                    ];
                }
            }
        }

        if($page_for=="REVENUE"){
            if($level=="ZP"){
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Revenue Collection of ".$branchData->branch_name." of Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }elseif ($level=="AP"){
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Revenue Collection of ".$branchData->branch_name." of Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }else{
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Revenue Collection of ".$branchData->branch_name." of Gram Panchayat : ".$gpData->gram_panchayat_name.", Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }
        }else{
            if($level=="ZP"){
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Share Distribution of ".$branchData->branch_name." of Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }elseif ($level=="AP"){
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Share Distribution of ".$branchData->branch_name." of Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : $zpData->zila_parishad_name (".$fyData->fy_name.")";
            }else{
                $head_txt="Asset(Haat, Ghat, Fishery, Animal Pound) Share Distribution of ".$branchData->branch_name." of Gram Panchayat : ".$gpData->gram_panchayat_name.", Anchalik Panchayat : ".$apData->anchalik_parishad_name.", Zila Parishad : ".$zpData->zila_parishad_name." (".$fyData->fy_name.")";
            }
        }

        $data=[
            'fy_id'=>$fy_id,
            'fyData'=>$fyData,
            'branchData'=>$branchData,
            'assetList'=>$assetList,
            'page_for'=>$page_for,
            'zpData'=>$zpData,
            'apData'=>$zpData,
            'gpData'=>$zpData,
            'resArray'=>$resArray,
            'head_txt'=>$head_txt,

            'level'=>$level,
            'zp_id'=>$zp_id,
            'ap_id'=>$ap_id,
            'gp_id'=>$gp_id,
        ];

        return view('Osr.non_tax.asset.common.single_branch_revenue_share',compact('data'));
    }

    // ASSET VIEW

    public function assetInformation ($fy_id,$level,$branch_id,$asset_id,$zp_id, $ap_id=NULL,$gp_id=NULL) {
        $fy_id=decrypt($fy_id);
        $branch_id=decrypt($branch_id);
        $level=decrypt($level);
        $asset_id=decrypt($asset_id);
        $zp_id=decrypt($zp_id);
        $ap_id=decrypt($ap_id);
        $gp_id=decrypt($gp_id);

        $district_data=ZilaParishad::getZPName($zp_id);
        $district_name=$district_data->zila_parishad_name;
        $fy_years=OsrMasterFyYear::getFyYear($fy_id)->fy_name;
        $ap_name=NULL;
        $gp_name=NULL;

        if($level=="ZP"){
            $query=[
                'zila_id'=>$zp_id,
                'osr_asset_branch_id'=>$branch_id,
                'a_short.level'=>'ZP',
                'a_short.id'=>$asset_id
            ];
        }else if($level=="AP"){
            $ap_data=AnchalikParishad::getAPName($ap_id);
            $ap_name=$ap_data->anchalik_parishad_name;
            $query=[
                'zila_id'=>$zp_id,
                'anchalik_id'=>$ap_id,
                'osr_asset_branch_id'=>$branch_id,
                'a_short.level'=>'AP',
				'a_short.id'=>$asset_id
            ];
        }else{
            $ap_data=AnchalikParishad::getAPName($ap_id);
            $ap_name=$ap_data->anchalik_parishad_name;
            $gp_data=GramPanchyat::getGPName($gp_id);
            $gp_name=$gp_data->gram_panchayat_name;
            $query=[
                'zila_id'=>$zp_id,
                'anchalik_id'=>$ap_id,
                'gram_panchayat_id'=>$gp_id,
                'osr_asset_branch_id'=>$branch_id,
                'a_short.level'=>'GP',
                'a_short.id'=>$asset_id
            ];
        }

        // asset Details
        $assetData=OsrNonTaxAssetEntry::join('osr_non_tax_asset_shortlists as a_short','a_short.asset_code','=','osr_non_tax_asset_entries.asset_code')
		->where($query)->first();
        if(!$assetData){
            return redirect()->route('admin.dashboard');
        }

        $zpData=ZilaParishad::where('id',$assetData->zila_id)->first();
        $apData=AnchalikParishad::where('id',$assetData->anchalik_id)->first();
        $gpData=GramPanchyat::where('gram_panchyat_id',$assetData->gram_panchayat_id)->first();
        $villData=Village::where('id',$assetData->village_id)->first();
        $branchData =OsrMasterNonTaxBranch::getBranchById($assetData->osr_asset_branch_id);

        $asset_zp_name =$zpData->zila_parishad_name;
        $asset_ap_name =$apData->anchalik_parishad_name;
        $asset_gp_name =$gpData->gram_panchayat_name;
        //$asset_vill_name =$villData->village_name;
        $asset_code =$assetData->asset_code;

        // bidding details

        $generalDetail=NULL;
        $acceptedBidderData=NULL;
        $settlementData=NULL;
        $finalRecordData = NULL;
        $uploadedDoc=[];

        $generalDetail = OsrNonTaxBiddingGeneralDetail::getEntryByCodeAndFyYr($asset_code, $fy_id);

        $finalRecordData = OsrNonTaxAssetFinalRecord::getFinalRecord($asset_code, $fy_id);

        if ($generalDetail && $finalRecordData && $finalRecordData->bidding_status==1) {

            $settlementData = OsrNonTaxBiddingSettlementDetail::getSettlementInfo($generalDetail->id);
            $uploadedDoc = OsrNonTaxBiddingAttachmentUpload::getOnlyUploadedAttachments($generalDetail->id);

            $bidderDetail = OsrNonTaxBiddingBiddersDetail::getAllBiddersByGeneralId($generalDetail->id);


            foreach($bidderDetail AS $bidder){
                $uploadAttachCount[$bidder->id] = OsrNonTaxBidderAttachmentUpload::attachmentUploadCount($bidder->id);
            }

            $acceptedBidderData = OsrNonTaxBiddingBiddersDetail::acceptedBidder($generalDetail->id);

            $totalBidder = OsrNonTaxBiddingBiddersDetail::totalBiddersCount($generalDetail->id);
            $totalWithdrawnBidder = OsrNonTaxBiddingBiddersDetail::totalWithdrawnBiddersCount($generalDetail->id);

            if ($acceptedBidderData) {
                $forfeitedBidderData = OsrNonTaxBiddingBiddersDetail::totalForfeitedBiddersCount($generalDetail->id, $acceptedBidderData->bidding_amt);
            }

        }

        $imgUrl =$imgUrl=ConfigMdas::allActiveList()->imgUrl;

        $data=[
            "fy_id"=>$fy_id,
            "assetData"=>$assetData,
            "branchData"=>$branchData,
            "asset_zp_name"=>$asset_zp_name,
            "asset_ap_name"=>$asset_ap_name,
            "asset_gp_name"=>$asset_gp_name,
            //"asset_vill_name"=>$asset_vill_name,

            "generalDetail"=>$generalDetail,
            "finalRecordData"=>$finalRecordData,
            "settlementData"=>$settlementData,
            "acceptedBidderData"=>$acceptedBidderData,
            "uploadedDoc"=>$uploadedDoc,
            "imgUrl"=>$imgUrl,
        ];

        return view('Osr.non_tax.asset.common.asset_information',compact('data', 'id','fy_id','district_name','fy_years','branch_id','ap_id','gp_id','imgUrl'));
    }

}
