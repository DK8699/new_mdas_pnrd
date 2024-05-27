<?php

namespace App\Http\Controllers\Osr;

use App\CommonModels\District;
use App\Osr\OsrMasterFyYear;
use App\CommonModels\Village;
use App\Osr\OsrNonTaxMasterAssetCategory;
use App\Osr\OsrNonTaxOtherAssetEntry;
use App\CommonModels\ZilaParishad;
use App\CommonModels\AnchalikParishad;
use App\CommonModels\GramPanchyat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Validator;
use DB;

class OsrNonTaxOtherAssetController extends Controller
{
    public function check_zilla(Request $request, $zp_id){

        $returnData['msgType'] = "false";
        $users = $request->session()->get('users');

        $checkZila = District::getZilaByDistrictId($users->district_code);

        if(!$checkZila){
            $returnData['msg'] = "You are not authorised to perform this task.";
            return $returnData;
        }elseif($checkZila->id != $zp_id){
            $returnData['msg'] = "You are not authorised to perform this task.";
            return $returnData;
        }

        $returnData['data'] = $checkZila;
        $returnData['msgType'] = "true";
        return $returnData;


    }

    public function index(Request $request){
        
        $otherAssetCount=[];
        
        
        $categories= OsrNonTaxMasterAssetCategory::all();
        
        foreach($categories as $cat)
        {
            $otherAssetCount[$cat->id]=OsrNonTaxOtherAssetEntry::dw_cw_asset_count($cat->id);
        }

        $max_fy_id=OsrMasterFyYear::getMaxFyYear();
        $data=[
            'fy_id'=>$max_fy_id
        ];
        return view('Osr.non_tax.other_asset.dw_other_asset_cat', compact('categories','otherAssetCount', 'data'));
    }

    public function nt_dw_other_asset_list(Request $request, $cat_id){

        $users = Auth::user();

        $zpData=District::getZilaByDistrictId($users->district_code);

        if(!$zpData){
            return redirect()->route('osr.dashboard');
        }

        $cat_id=base64_decode(base64_decode($cat_id));
        $catData=OsrNonTaxMasterAssetCategory::getById($cat_id);

        if(!$catData){
            return redirect(route('osr.non_tax.dw_asset.other_assets'));
        }

        $allOsrFyYears= OsrMasterFyYear::getAllYears();

        if($users->mdas_master_role_id==2) //ZP ADMIN-----------------------------------------------------
        {
            $apData=AnchalikParishad::getAPsByZilaId($users->zp_id);
            
            $whereArray=[
                ['osr_non_tax_other_asset_entries.osr_non_tax_master_asset_category_id', '=', $cat_id],
                ['osr_non_tax_other_asset_entries.zila_id', '=', $users->zp_id],
                ['osr_non_tax_other_asset_entries.managed_by', '=', 'ZP'],
            ];
        }
        elseif($users->mdas_master_role_id==3) //AP ADMIN-----------------------------------------------------
        {
            $apData=AnchalikParishad::getAPsByApId($users->ap_id);
        
            $whereArray=[
                ['osr_non_tax_other_asset_entries.osr_non_tax_master_asset_category_id', '=', $cat_id],
                ['osr_non_tax_other_asset_entries.zila_id', '=', $users->zp_id],
                ['osr_non_tax_other_asset_entries.managed_by', '=', 'AP'],
            ];
        }
        elseif($users->mdas_master_role_id==4)
        {
        $apData=AnchalikParishad::getAPsByApId($users->ap_id);
        $gpData=GramPanchyat::getGPsByGpId($users->gp_id);
        $whereArray=[
                ['osr_non_tax_other_asset_entries.osr_non_tax_master_asset_category_id', '=', $cat_id],
                ['osr_non_tax_other_asset_entries.zila_id', '=', $users->zp_id],
                ['osr_non_tax_other_asset_entries.managed_by', '=', 'GP'],
            ];
        }else{
            return redirect()->route('osr.osr_panel');
        }
        
        
        $assetList=OsrNonTaxOtherAssetEntry::join('zila_parishads AS z', 'osr_non_tax_other_asset_entries.zila_id', '=', 'z.id')
            ->join('anchalik_parishads AS a', 'osr_non_tax_other_asset_entries.anchalik_id', '=', 'a.id')
            ->join('gram_panchyats AS g', 'osr_non_tax_other_asset_entries.gram_panchayat_id', '=', 'g.gram_panchyat_id')
            ->join('villages AS v', 'osr_non_tax_other_asset_entries.village_id', '=', 'v.id')
            ->where($whereArray)->select('z.zila_parishad_name', 'a.anchalik_parishad_name', 'g.gram_panchayat_name', 'v.village_name', 'osr_non_tax_other_asset_entries.*')
            ->orderBy('osr_non_tax_other_asset_entries.id', 'desc')
            ->get();

        $max_fy_id=OsrMasterFyYear::getMaxFyYear();
        $data=[
            'fy_id'=>$max_fy_id
        ];

        return view('Osr.non_tax.other_asset.dw_other_asset_list', compact('data', 'allOsrFyYears', 'catData', 'zpData', 'apData','gpData', 'assetList'));
    }


    //----------------------------    ASSET SAVE   ---------------------------------------------------------------------

    public function asset_save(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        DB::beginTransaction();
        try {

            $messages = [
                'asset_name.required' => 'This field is required',
                'asset_name.max' => 'Characters must not exceed 100 characters',

                'asset_add.required' => 'This field is required',
                'asset_add.max' => 'Characters must not exceed 150 characters',

                'remarks.max' => 'Characters must not exceed 150 characters',

                'zp_id.required' => 'This field is required',
                'zp_id.exists' => 'Invalid data',

                'ap_id.required' => 'This field is required',
                'ap_id.exists' => 'Invalid data',

                'gp_id.required' => 'This field is required',
                'gp_id.exists' => 'Invalid data',

                'v_id.required' => 'This field is required',
                'v_id.exists' => 'Invalid data',
            ];

            $validatorArray = [
                'asset_name' => 'required|string|max:100',
                'asset_add' => 'required|max:150',
                'remarks' => 'string|max:150|nullable',

                'zp_id' => 'required|exists:zila_parishads,id',
                'ap_id' => 'required|exists:anchalik_parishads,id',
                'gp_id' => 'required|exists:gram_panchyats,gram_panchyat_id',
                'v_id' => 'required|exists:villages,id'
            ];

            $validator = Validator::make($request->all(), $validatorArray, $messages);

            $zp_id = $request->input('zp_id');
            $cat_id = $request->input('osr_cat_id');

            if ($validator->fails()) {
                $errors = $validator->errors();
                $returnData['msg'] = "VE";
                $returnData['errors'] = $errors;
                return response()->json($returnData);
            }

            $users = Auth::user();

            //CHECK DISTRICT

            $verifyZila = $this->check_zilla($request,$zp_id);

            if(!$verifyZila){
                $returnData['msg'] = $verifyZila['msg'] ;
                return response()->json($returnData);
            }

            $checkActiveCat = OsrNonTaxMasterAssetCategory::getById($cat_id);

            if (!$checkActiveCat) {
                $returnData['msg'] = "Sorry the asset is deactivated by admin";
                return response()->json($returnData);
            }
            
             //--------Count Asset against district,AP,GP
            
            if($users->mdas_master_role_id==2){
            $whereArray = [
                ['osr_non_tax_master_asset_category_id', '=', $cat_id],
                ['zila_id', '=', $users->zp_id],
                ['managed_by','=',"ZP"]
            ];
                $levelCode = $users->zp_id;
            }
            elseif($users->mdas_master_role_id==3){
            $whereArray = [
                ['osr_non_tax_master_asset_category_id', '=', $cat_id],
                ['zila_id', '=', $users->zp_id],
                ['anchalik_id', '=', $users->ap_id],
                ['managed_by','=',"AP"]
            ];
                $levelCode = $users->ap_id;
            }
            elseif($users->mdas_master_role_id==4){
            $whereArray = [
                ['osr_non_tax_master_asset_category_id', '=', $cat_id],
                ['zila_id', '=', $users->zp_id],
                ['anchalik_id', '=', $users->ap_id],
                ['gram_panchayat_id', '=', $users->gp_id],
                ['managed_by','=',"GP"]
            ];
                $levelCode = $users->gp_id;
            }
            else{
                $returnData['msgType'] = false;
                $returnData['msg'] = "Not Authorized to create asset";
                return response()->json($returnData);
            }

            $maxValue = OsrNonTaxOtherAssetEntry::where($whereArray)->count();

            if (!$maxValue) {
                $maxValue = 0;
            }

            $assetCode = $this->makeAssetCode($verifyZila['data']->zila_parishad_name, $checkActiveCat->cat_name, $maxValue, $levelCode);

            if($users->mdas_master_role_id==2)
            {
                $managed_by = 'ZP';
            }
            elseif($users->mdas_master_role_id==3)
            {
                $managed_by = 'AP';
            }
            elseif($users->mdas_master_role_id==4)
            {
                $managed_by = 'GP';
            }
            $assetSave = new OsrNonTaxOtherAssetEntry();

            $assetSave->osr_non_tax_master_asset_category_id = $cat_id;
            $assetSave->other_asset_code = $assetCode;
            $assetSave->other_asset_name = $request->input('asset_name');
            $assetSave->other_asset_listing_date = $request->input('list_date');
            
            $assetSave->managed_by = $managed_by;
            $assetSave->asset_add = $request->input('asset_add');
            $assetSave->remarks = $request->input('remarks');

            $assetSave->zila_id = $users->zp_id;
            $assetSave->anchalik_id = $request->input('ap_id');
            $assetSave->gram_panchayat_id = $request->input('gp_id');
            $assetSave->village_id = $request->input('v_id');

            $assetSave->created_by = $users->username;

            if (!$assetSave->save()) {
                return response()->json($returnData);
            }

            $returnData['msgType'] = true;
            $returnData['msg'] = "Successfully added";

        }
        catch (\Exception $e){
            DB::rollback();
            $returnData['msg'] = "Server Exception.";
        }
        DB::commit();
        return response()->json($returnData);
    }

    private function makeAssetCode($zp_name, $cat_name, $maxValue, $levelCode){
        $zp_name = substr($zp_name,0,3);
        $cat_name = strtoupper(substr($cat_name,0,3));

        $maxValue=$maxValue+1;

        if(strlen($maxValue)==1){
            $maxValue="00".$maxValue;
        }elseif(strlen($maxValue)==2){
            $maxValue="0".$maxValue;
        }

        return $zp_name."-".$levelCode."/".$cat_name."-".$maxValue;
    }
    
     //------------------------------------------------------------------------------------------------------------------
    //----------------------------------- ZP OTHER ASSET SHOW LIST --------------------------------------------------------------
    //------------------------------------------------------------------------------------------------------------------

    public function other_asset_show_list(Request $request){

        $users=Auth::user();
        $catList=OsrNonTaxMasterAssetCategory::getAssetCategory();
        $zpData=ZilaParishad::getZPName($users->zp_id);
        $apList=AnchalikParishad::getAPsByZilaId($users->zp_id);

        $data=[
            'catList'=>$catList,
            'apList'=>$apList,
            'gpList'=>[],
            'otherAssetList'=>[],
            'catData'=>NULL,
            'apData'=>NULL,
            'cat_id'=>NULL,
            'ap_id'=>NULL,
            'gp_id'=>NULL,
            'searchText'=>"Select and search to see the result.."
        ];

        if($users->mdas_master_role_id==2){// ZP ADMIN--------------------------------------------------------------
            $ap_id=$request->input('ap_id');
            $apData=AnchalikParishad::getAPName($ap_id);
            $gp_id=$request->input('gp_id');

            if ($request->isMethod('post')) {
                $cat_id=$request->input('cat_id');
                $catData=OsrNonTaxMasterAssetCategory::getById($cat_id);
                
                if(!$gp_id){
                    $level="AP";

                    $whereArray=[
                        ['osr_non_tax_other_asset_entries.osr_non_tax_master_asset_category_id', '=', $cat_id],
                        ['osr_non_tax_other_asset_entries.zila_id', '=', $users->zp_id],
                        ['osr_non_tax_other_asset_entries.anchalik_id', '=', $ap_id],
                        ['osr_non_tax_other_asset_entries.managed_by', '=', $level],
                    ];

                    $searchText="Showing the list of ".$catData->cat_name." in ".$apData->anchalik_parishad_name."(AP) of ".$zpData->zila_parishad_name."(ZP)";

                }else{
                    $gpData=GramPanchyat::getGPName($gp_id);
                    $level="GP";
                    $whereArray=[
                        ['osr_non_tax_other_asset_entries.osr_non_tax_master_asset_category_id', '=', $cat_id],
                        ['osr_non_tax_other_asset_entries.zila_id', '=', $users->zp_id],
                        ['osr_non_tax_other_asset_entries.anchalik_id', '=', $ap_id],
                        ['osr_non_tax_other_asset_entries.gram_panchayat_id', '=', $gp_id],
                        ['osr_non_tax_other_asset_entries.managed_by', '=', $level],
                    ];

                    $searchText="Showing the list of ".$catData->cat_name." in ".$gpData->gram_panchayat_name."(GP) of ".$apData->anchalik_parishad_name."(AP), ".$zpData->zila_parishad_name."(ZP)";
                }

                $gpList=GramPanchyat::getGpsByAnchalikId($ap_id);

                $data['gpList']=$gpList;
                $data['apData']=$apData;
                $data['ap_id']=$ap_id;
                $data['gp_id']=$gp_id;


                $otherAssetList=OsrNonTaxOtherAssetEntry::join('zila_parishads AS z', 'osr_non_tax_other_asset_entries.zila_id', '=', 'z.id')
                    ->join('anchalik_parishads AS a', 'osr_non_tax_other_asset_entries.anchalik_id', '=', 'a.id')
                    ->join('gram_panchyats AS g', 'osr_non_tax_other_asset_entries.gram_panchayat_id', '=', 'g.gram_panchyat_id')
                    ->join('villages AS v', 'osr_non_tax_other_asset_entries.village_id', '=', 'v.id')
                    ->where($whereArray)
                    ->select('z.zila_parishad_name', 'a.anchalik_parishad_name', 'g.gram_panchayat_name', 'v.village_name', 'osr_non_tax_other_asset_entries.*')
                    ->orderBy('osr_non_tax_other_asset_entries.id', 'desc')
                    ->get();

                $data['otherAssetList']=$otherAssetList;
                $data['catData']=$catData;
                $data['cat_id']=$cat_id;
                $data['searchText']=$searchText;
            }

        }elseif($users->mdas_master_role_id==3){/*AP ADMIN ---------------------------------------------------------*/
            $gpList = GramPanchyat::getGpsByAnchalikId($users->ap_id);
            $apData = AnchalikParishad::getAPName($users->ap_id);

            $data['gpList'] = $gpList;
            $data['apData'] = $apData;

            if ($request->isMethod('post')) {
                $cat_id = $request->input('cat_id');
                $catData = OsrNonTaxMasterAssetCategory::getById($cat_id);
                $gp_id = $request->input('gp_id');

                $gpData = GramPanchyat::getGPName($gp_id);
                $whereArray = [
                    ['osr_non_tax_other_asset_entries.osr_non_tax_master_asset_category_id', '=', $cat_id],
                    ['osr_non_tax_other_asset_entries.zila_id', '=', $users->zp_id],
                    ['osr_non_tax_other_asset_entries.anchalik_id', '=', $users->ap_id],
                    ['osr_non_tax_other_asset_entries.gram_panchayat_id', '=', $gp_id],
                    ['osr_non_tax_other_asset_entries.managed_by', '=', "GP"],
                ];

                $searchText = "Showing the list of " . $catData->cat_name . " in " . $gpData->gram_panchayat_name . "(GP) of " . $apData->anchalik_parishad_name . "(AP), " . $zpData->zila_parishad_name . "(ZP)";

                $data['gp_id'] = $gp_id;

                $otherAssetList=OsrNonTaxOtherAssetEntry::join('zila_parishads AS z', 'osr_non_tax_other_asset_entries.zila_id', '=', 'z.id')
                    ->join('anchalik_parishads AS a', 'osr_non_tax_other_asset_entries.anchalik_id', '=', 'a.id')
                    ->join('gram_panchyats AS g', 'osr_non_tax_other_asset_entries.gram_panchayat_id', '=', 'g.gram_panchyat_id')
                    ->join('villages AS v', 'osr_non_tax_other_asset_entries.village_id', '=', 'v.id')
                    ->where($whereArray)
                    ->select('z.zila_parishad_name', 'a.anchalik_parishad_name', 'g.gram_panchayat_name', 'v.village_name', 'osr_non_tax_other_asset_entries.*')
                    ->orderBy('osr_non_tax_other_asset_entries.id', 'desc')
                    ->get();

                $data['otherAssetList']=$otherAssetList;
                $data['catData']=$catData;
                $data['cat_id']=$cat_id;
                $data['searchText']=$searchText;
            }
        }else{
            return redirect()->route('osr.osr_panel');
        }

        $max_fy_id=OsrMasterFyYear::getMaxFyYear();
        $data['fy_id']=$max_fy_id;

        return view('Osr.non_tax.other_asset.other_asset_show_list', compact('data'));
    }
    
    public function getOtherAssetEntriesById(Request $request){
    
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $aid=$request->input('aid');

        $otherAssetData=OsrNonTaxOtherAssetEntry::getAssetEntryById($aid);


        if(!$otherAssetData){
            $returnData['msg'] = "Unauthorised access. Please contact admin for more details";
            return response()->json($returnData);
        }

        $users = $request->session()->get('users');

        $checkZila = District::getZilaByDistrictId($users->district_code);

        if(!$checkZila){
            $returnData['msg'] = "You are not authorised to perform this task.";
            return response()->json($returnData);
        }elseif($checkZila->id != $otherAssetData->zila_id){
            $returnData['msg'] = "You are not authorised to perform this task.";
            return response()->json($returnData);
        }
        $aps=AnchalikParishad::getAPsByZilaId($otherAssetData->zila_id);
        $gps=GramPanchyat::getGpsByAnchalikId($otherAssetData->anchalik_id);
        
        $villages= Village::getVillagesByGP($otherAssetData->gram_panchayat_id);

        $returnData['msgType'] = true;
        $returnData['data'] = ['otherAssetData'=>$otherAssetData,'gps'=>$gps, 'villages'=>$villages, 'aps'=>$aps];
        $returnData['msg'] = "Success";

        return response()->json($returnData);
    }
    
      //----------------------------    EDIT ASSET SAVE   ----------------------------------------------------------------

    public function saveEditById(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        DB::beginTransaction();
        try {

            $messages = [
                'ed_asset_name.required' => 'This field is required',
                'ed_asset_name.max' => 'Characters must not exceed 100 characters',

                'ed_list_date.required' => 'This field is required',
                'ed_list_date.in' => 'Invalid data',

                'ed_remarks.max' => 'Characters must not exceed 150 characters',

                'ed_zp_id.required' => 'This field is required',
                'ed_zp_id.exists' => 'Invalid data',

                'ed_ap_id.required' => 'This field is required',
                'ed_ap_id.exists' => 'Invalid data',

                'ed_gp_id.required' => 'This field is required',
                'ed_gp_id.exists' => 'Invalid data',

                'ed_v_id.required' => 'This field is required',
                'ed_v_id.exists' => 'Invalid data',

            ];

            $validatorArray = [
                'ed_asset_name' => 'required|string|max:100',
                'ed_list_date' => 'required|date_format:Y-m-d',
                'ed_remarks' => 'string|max:150|nullable',

                'ed_zp_id' => 'required|exists:zila_parishads,id',
                'ed_ap_id' => 'required|exists:anchalik_parishads,id',
                'ed_gp_id' => 'required|exists:gram_panchyats,gram_panchyat_id',
                'ed_v_id' => 'required|exists:villages,id',


            ];

            /*$osrFyThreeYears= OsrMasterFyYear::getPreviousThreeYears();*/
            /*foreach($osrFyThreeYears AS $threeYearsFY)
            {
                $messages['fy_yr'.$threeYearsFY->id.'.required'] = 'This field is required';
                $messages['fy_yr'.$threeYearsFY->id.'.exists'] = 'Invalid data';

                $messages['settled_amt'.$threeYearsFY->id.'.required'] = 'This field is required';
                $messages['settled_amt'.$threeYearsFY->id.'.numeric'] = "Must be a number!";
                $messages['settled_amt'.$threeYearsFY->id.'.regex'] = "Decimal up to two digit places only!";
                $messages['settled_amt'.$threeYearsFY->id.'.min'] = "Negative values not accepted!";
                $messages['settled_amt'.$threeYearsFY->id.'.max'] = "Up to 10 digit number is allowed!";

                $messages['collected_amt'.$threeYearsFY->id.'.required'] = 'This field is required';
                $messages['collected_amt'.$threeYearsFY->id.'.numeric'] = "Must be a number!";
                $messages['collected_amt'.$threeYearsFY->id.'.regex'] = "Decimal up to two digit places only!";
                $messages['collected_amt'.$threeYearsFY->id.'.min'] = "Negative values not accepted!";
                $messages['collected_amt'.$threeYearsFY->id.'.max'] = "Up to 10 digit number is allowed!";

                $messages['settlement_to'.$threeYearsFY->id.'.required'] = 'This field is required';
                $messages['settlement_to'.$threeYearsFY->id.'.in'] = 'Invalid data';

                $messages['zp_share'.$threeYearsFY->id.'.required'] = "This field is required!";
                $messages['zp_share'.$threeYearsFY->id.'.numeric']= "Must be a number!";
                $messages['zp_share'.$threeYearsFY->id.'.regex']= "Decimal up to two digit places only!";
                $messages['zp_share'.$threeYearsFY->id.'.min']= "Negative values not accepted!";
                $messages['zp_share'.$threeYearsFY->id.'.max']= "Up to 10 digit number is allowed!";

                $messages['ap_share'.$threeYearsFY->id.'.required'] = "This field is required!";
                $messages['ap_share'.$threeYearsFY->id.'.numeric']= "Must be a number!";
                $messages['ap_share'.$threeYearsFY->id.'.regex']= "Decimal up to two digit places only!";
                $messages['ap_share'.$threeYearsFY->id.'.min']= "Negative values not accepted!";
                $messages['ap_share'.$threeYearsFY->id.'.max']= "Up to 10 digit number is allowed!";

                $messages['gp_share'.$threeYearsFY->id.'.required'] = "This field is required!";
                $messages['gp_share'.$threeYearsFY->id.'.numeric']= "Must be a number!";
                $messages['gp_share'.$threeYearsFY->id.'.regex']= "Decimal up to two digit places only!";
                $messages['gp_share'.$threeYearsFY->id.'.min']= "Negative values not accepted!";
                $messages['gp_share'.$threeYearsFY->id.'.max']= "Up to 10 digit number is allowed!";

                $validatorArray['fy_yr'.$threeYearsFY->id] = 'required|exists:osr_master_fy_years,id';
                $validatorArray['settled_amt'.$threeYearsFY->id] = 'required|numeric|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:9999999999';
                $validatorArray['collected_amt'.$threeYearsFY->id] = 'required|numeric|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:9999999999';
                $validatorArray['settlement_to'.$threeYearsFY->id] = 'required|in:ZP,AP,GP';
                $validatorArray['zp_share'.$threeYearsFY->id] = 'required|numeric|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:9999999999';
                $validatorArray['ap_share'.$threeYearsFY->id] = 'required|numeric|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:9999999999';
                $validatorArray['gp_share'.$threeYearsFY->id] = 'required|numeric|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|min:0|max:9999999999';
            }*/

            $validator = Validator::make($request->all(), $validatorArray, $messages);

            $zp_id = $request->input('ed_zp_id');
            $aid=$request->input('aid');

            $otherAssetData=OsrNonTaxOtherAssetEntry::getAssetEntryById($aid);

            if(!$otherAssetData){
                $returnData['msg'] = "Unauthorised access. Please contact admin for more details";
                return response()->json($returnData);
            }

            $users = $request->session()->get('users');

            $verifyZila = $this->check_zilla($request,$zp_id);

            if(!$verifyZila){
                $returnData['msg'] = $verifyZila['msg'] ;
                return response()->json($returnData);
            }

            $updateData = OsrNonTaxOtherAssetEntry::where('id', $aid)
                ->update([
                    'other_asset_name' => $request->input('ed_asset_name'),
                    'other_asset_listing_date' => $request->input('ed_list_date'),
                    'remarks' => $request->input('ed_remarks'),
                    'zila_id' => $request->input('ed_zp_id'),
                    'anchalik_id' => $request->input('ed_ap_id'),
                    'gram_panchayat_id' => $request->input('ed_gp_id'),
                    'village_id' => $request->input('ed_v_id'),

                    'updated_by' => $users->employee_code,
                    'updated_at' => \Carbon\Carbon::now(),
                ]);
            /*foreach($osrFyThreeYears AS $threeYearsFY)
            {

                $consolidated_amt=$request->input('ed_settled_amt'.$threeYearsFY->id);
                $collected_amt=$request->input('ed_collected_amt'.$threeYearsFY->id);

                $zp_share=$request->input('ed_zp_share'.$threeYearsFY->id);
                $ap_share=$request->input('ed_ap_share'.$threeYearsFY->id);
                $gp_share=$request->input('ed_gp_share'.$threeYearsFY->id);

                $validateOne=$this->validateData($consolidated_amt, $collected_amt, $zp_share, $ap_share, $gp_share);

                if(!$validateOne['msgType']){
                    $returnData['msg'] = $validateOne['msg'];
                    return response()->json($returnData);
                }

                $updatePre = OsrNonTaxAssetSettlement::where([
                    ['osr_asset_entry_id', '=', $aid],
                    ['osr_fy_year_id', '=', $request->input('ed_fy_yr'.$threeYearsFY->id)],
                ])->update([
                    'consolidated_amt' => $consolidated_amt,
                    'collected_amt' => $collected_amt,
                    'settlement_to' => $request->input('ed_settlement_to'.$threeYearsFY->id),
                    'selected_bidder_f_name' => $request->input('ed_f_name'.$threeYearsFY->id),
                    'selected_bidder_m_name' => $request->input('ed_m_name'.$threeYearsFY->id),
                    'selected_bidder_l_name' => $request->input('ed_l_name'.$threeYearsFY->id),
                    'selected_bidder_pan_no' => $request->input('ed_pan_no'.$threeYearsFY->id),
                    'zp_share' => $zp_share,
                    'ap_share' => $ap_share,
                    'gp_share' => $gp_share,

                    'updated_by' => $users->employee_code,
                    'updated_at' => \Carbon\Carbon::now(),
                ]);


                if(!$updateData || !$updatePre){
                    DB::rollback();
                    $returnData['msg'] = "Oops! Something went wrong!";
                    return response()->json($returnData);
                }

                $returnData['msgType'] = true;
                $returnData['data'] = [];
                $returnData['msg'] = "Successfully edited";
            }*/
            if(!$updateData){
                    DB::rollback();
                    $returnData['msg'] = "Oops! Something went wrong!";
                    return response()->json($returnData);
            }
            $returnData['msgType'] = true;
            $returnData['data'] = [];
            $returnData['msg'] = "Successfully edited";

        }
        catch (\Exception $e){
            DB::rollback();
            $returnData['msg'] = "Server Exception.".$e->getMessage();
        }

        DB::commit();
        return response()->json($returnData);
    }
    

}
