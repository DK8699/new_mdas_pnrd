<?php

namespace App\Http\Controllers\Osr;

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

class OsrNonTaxAssetController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'user_mdas']);
    }
    public function dwAssetInformation()
    {
        $max_fy_id = OsrMasterFyYear::getMaxFyYear();
        $data = [
            'fy_id' => $max_fy_id
        ];
        return view('Osr.non_tax.dwAssetInformation', compact('data'));
    }

    private function asset_creation_access($zp_id, $ap_id, $gp_id)
    {
        $returnData['msgType'] = "false";
        $returnData['msg'] = "Access Denied!";
        $returnData['data'] = [];

        $users = Auth::user();

        if ($users->zp_id <> $zp_id) {
            return response()->json($returnData);
        }

        if ($users->mdas_master_role_id == 2) {
            $asset_under = "ZP";
        } elseif ($users->mdas_master_role_id == 3) {
            $asset_under = "AP";
            if ($users->ap_id <> $ap_id) {
                return response()->json($returnData);
            }
        } elseif ($users->mdas_master_role_id == 4) {
            $asset_under = "GP";
            if ($users->ap_id <> $ap_id) {
                return response()->json($returnData);
            } elseif ($users->gp_id <> $gp_id) {
                return response()->json($returnData);
            }
        } else {
            return response()->json($returnData);
        }

        $returnData['data'] = $asset_under;
        $returnData['msgType'] = "true";
        return $returnData;
    }

    //----------------------------    ASSET SAVE   ---------------------------------------------------------------------

    public function asset_save(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $users = Auth::user();

        DB::beginTransaction();
        try {

            $messages = [
                'asset_name.required' => 'This field is required',
                'asset_name.max' => 'Characters must not exceed 100 characters',

                'asset_listing_date.required' => 'This field is required',
                'asset_listing_date.date' => 'Invalid data',

                'b_desc.max' => 'Characters must not exceed 150 characters',

                'zp_id.required' => 'This field is required',
                'zp_id.exists' => 'Invalid data',

                'ap_id.required' => 'This field is required',
                'ap_id.exists' => 'Invalid data',

                'gp_id.required' => 'This field is required',
                'gp_id.exists' => 'Invalid data',
                //'market_natures.required' => 'This field is required',
                'market_natures.exists' => 'Invalid data',
                //'market_category.required' => 'This field is required',
                'market_category.exists' => 'Invalid data',
            ];

            $validatorArray = [
                'asset_name' => 'required|string|max:100',
                'b_desc' => 'string|max:150|nullable',

                'zp_id' => 'required|exists:zila_parishads,id',
                'ap_id' => 'required|exists:anchalik_parishads,id',
                'gp_id' => 'required|exists:gram_panchyats,gram_panchyat_id',
                'market_natures' => 'nullable|exists:osr_market_natures,id',
                'market_category' => 'nullable|exists:osr_market_categories,id',
            ];

            $validator = Validator::make($request->all(), $validatorArray, $messages);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $returnData['msg'] = "VE";
                $returnData['errors'] = $errors;
                return response()->json($returnData);
            }

            $zp_id = $request->input('zp_id');
            $ap_id = $request->input('ap_id');
            $gp_id = $request->input('gp_id');

            $branch_id = $request->input('osr_asset_branch_id');

            $checkActiveBranch = OsrMasterNonTaxBranch::getActiveBranchById($branch_id);

            if (!$checkActiveBranch) {
                $returnData['msg'] = "Sorry the asset category is deactivated by admin";
                return response()->json($returnData);
            }

            $branch_name = $checkActiveBranch->branch_name;

            // --------- Asset Creation Access ----------------------------

            $checkAccess = $this->asset_creation_access($zp_id, $ap_id, $gp_id);
            if (!$checkAccess['msgType']) {
                $returnData['msg'] = $checkAccess['msg'];
                return response()->json($returnData);
            }
            $asset_under = $checkAccess['data'];

            // --------- Asset Creation Access ENDED ----------------------

            // --------- MAKE ASSET CODE ----------------------------------

            // ------------------ old code ----------------
            // $maxValue = OsrNonTaxAssetEntry::where([
            //     ['osr_asset_branch_id', '=', $branch_id],
            //     ['zila_id', '=', $users->zp_id]
            // ])->count();

            // ------------------ new code by ridip ---------------

            $maxValue = DB::table('osr_non_tax_asset_entries')
                ->where([
                    ['osr_asset_branch_id', '=', $branch_id],
                    ['zila_id', '=', $users->zp_id]
                ])
                ->count();

            if (!$maxValue) {
                $maxValue = 0;
            } else {

                // ------------------- old code -------------------
                // $lastAssetEntry = OsrNonTaxAssetEntry::where([
                //     ['osr_asset_branch_id', '=', $branch_id],
                //     ['zila_id', '=', $users->zp_id]
                // ])->orderBy('asset_code', 'desc')->select('asset_code')->first();

                // ------------------- new code by ridp ---------------
                $lastAssetEntry = DB::table('osr_non_tax_asset_entries')
                    ->where([
                        ['osr_asset_branch_id', '=', $branch_id],
                        ['zila_id', '=', $users->zp_id]
                    ])->orderBy('updated_at', 'desc')
                    ->select('updated_at', 'asset_code')
                    ->first();
                $maxValue = chop(substr($lastAssetEntry->asset_code, strrpos($lastAssetEntry->asset_code, "-") + 1));

            }
            $assetCode = $this->makeAssetCode($zp_id, $branch_name, $maxValue);
            //     // --------- MAKE ASSET CODE ENDED -----------------------------

            $assetSave = new OsrNonTaxAssetEntry();

            $assetSave->asset_under = $asset_under;
            $assetSave->osr_asset_branch_id = $branch_id;
            $assetSave->osr_market_category_id = $request->input('market_category');
            $assetSave->osr_market_nature_id = $request->input('market_natures');
            $assetSave->asset_code = $assetCode;
            $assetSave->asset_name = $request->input('asset_name');
            $assetSave->asset_scope = $request->input('asset_scope');
            $assetSave->scope_unit = $request->input('asset_scope_unit');
            $assetSave->asset_listing_date = $request->input('asset_listing_date');
            $assetSave->b_desc = $request->input('b_desc');
            $assetSave->shared_asset = 0;

            $assetSave->zila_id = $zp_id;
            $assetSave->anchalik_id = $ap_id;
            $assetSave->gram_panchayat_id = $gp_id;
            $assetSave->village_id = $request->input('v_id');

            $assetSave->created_by = $users->username;

            if (!$assetSave->save()) {
                $returnData['msg'] = "Opps! Something went wrong123.";
                return response()->json($returnData);
            }
        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Server Exception." . $e->getMessage();

            return response()->json($returnData);
        }

        DB::commit();
        $returnData['msgType'] = true;
        $returnData['msg'] = "Successfully added";
        return response()->json($returnData);
    }


    /*private function makeAssetCode($zp_name, $branch_name, $maxValue,$level_code){
        
        $zp_name = substr($zp_name,0,3);
        $branch_name = strtoupper(substr($branch_name,0,3));
        $maxValue=$maxValue+1;

        if(strlen($maxValue)==1){
                $maxValue="00".$maxValue;
            }elseif(strlen($maxValue)==2){
                $maxValue="0".$maxValue;
            }
       return $zp_name."-".$level_code."/".$branch_name."-".$maxValue;
    }*/

    private function makeAssetCode($zp_id, $branch_name, $maxValue)
    {

        //echo $zp_id."____".$branch_name."____".$maxValue;

        $zp_name = ZilaParishad::getZPName($zp_id)->zila_parishad_name;

        $district_code = Auth::user()->district_code;

        $zp_name = substr($zp_name, 0, 3);
        $branch_name = strtoupper(substr($branch_name, 0, 3));
        $maxValue = $maxValue + 1;

        if (strlen($maxValue) == 1) {
            $maxValue = "00" . $maxValue;
        } elseif (strlen($maxValue) == 2) {
            $maxValue = "0" . $maxValue;
        } else {
            $maxValue = $maxValue;
        }
        return $zp_name . "-" . $district_code . "/" . $branch_name . "-" . $maxValue;
    }

    private function validateData($consolidated_amt, $collected_amt, $zp_share, $ap_share, $gp_share)
    {
        $returnData['msgType'] = false;

        if ($consolidated_amt < $collected_amt) {
            $returnData['msg'] = "Scheduled Amount should be greater than Collected Amount.";
            return $returnData;
        }

        if (($zp_share + $ap_share + $gp_share) > $collected_amt) {
            $returnData['msg'] = "The sum of ZP,AP and GP share must be equal to Collected Amount.";
            return $returnData;
        }

        $returnData['msgType'] = true;
        return $returnData;
    }

    //----------------------------    GET ASSET BY ID   ----------------------------------------------------------------

    public function getAssetEntriesById(Request $request)
    {

        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $aid = $request->input('aid');

        $assetData = OsrNonTaxAssetEntry::getAssetEntryById($aid);


        if (!$assetData) {
            $returnData['msg'] = "Unauthorised access. Please contact admin for more details";
            return response()->json($returnData);
        }

        $users = $request->session()->get('users');

        $checkZila = District::getZilaByDistrictId($users->district_code);

        if (!$checkZila) {
            $returnData['msg'] = "You are not authorised to perform this task1.";
            return response()->json($returnData);
        } elseif ($checkZila->id != $assetData->zila_id) {
            $returnData['msg'] = "You are not authorised to perform this task2-$assetData->id.";
            return response()->json($returnData);
        }
        $aps = AnchalikParishad::getAPsByZilaId($assetData->zila_id);
        $gps = GramPanchyat::getGpsByAnchalikId($assetData->anchalik_id);

        $villages = Village::getVillagesByGP($assetData->gram_panchayat_id);

        $returnData['msgType'] = true;
        $returnData['data'] = ['assetData' => $assetData, 'gps' => $gps, 'villages' => $villages, 'aps' => $aps];
        $returnData['msg'] = "Success";

        return response()->json($returnData);
    }



    //----------------------------    EDIT ASSET SAVE   ----------------------------------------------------------------

    public function saveEditById(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $users = Auth::user();

        DB::beginTransaction();
        try {

            $messages = [
                'ed_asset_name.required' => 'This field is required',
                'ed_asset_name.max' => 'Characters must not exceed 100 characters',

                'ed_asset_listing_date.required' => 'This field is required',
                'ed_asset_listing_date.in' => 'Invalid data',

                'ed_b_desc.max' => 'Characters must not exceed 150 characters',

                'ed_zp_id.required' => 'This field is required',
                'ed_zp_id.exists' => 'Invalid data',

                'ed_ap_id.required' => 'This field is required',
                'ed_ap_id.exists' => 'Invalid data',

                'ed_gp_id.required' => 'This field is required',
                'ed_gp_id.exists' => 'Invalid data',

            ];

            $validatorArray = [
                'ed_asset_name' => 'required|string|max:100',
                'ed_asset_listing_date' => 'required|date_format:Y-m-d',
                'ed_b_desc' => 'string|max:150|nullable',

                'ed_zp_id' => 'required|exists:zila_parishads,id',
                'ed_ap_id' => 'required|exists:anchalik_parishads,id',
                'ed_gp_id' => 'required|exists:gram_panchyats,gram_panchyat_id',


            ];

            $validator = Validator::make($request->all(), $validatorArray, $messages);

            $zp_id = $request->input('ed_zp_id');
            $ap_id = $request->input('ed_ap_id');
            $gp_id = $request->input('ed_gp_id');
            $aid = $request->input('aid');

            $assetData = OsrNonTaxAssetEntry::getAssetEntryById($aid);

            if (!$assetData) {
                $returnData['msg'] = "Unauthorised access. Please contact admin for more details";
                return response()->json($returnData);
            }

            // --------- Asset Creation Access ----------------------------

            $checkAccess = $this->asset_creation_access($zp_id, $ap_id, $gp_id);
            if (!$checkAccess['msgType']) {
                $returnData['msg'] = $checkAccess['msg'];
                return response()->json($returnData);
            }

            // --------- Asset Creation Access ENDED ----------------------

            $updateData = OsrNonTaxAssetEntry::where('id', $aid)
                ->update([
                'asset_name' => $request->input('ed_asset_name'),
                'asset_listing_date' => $request->input('ed_asset_listing_date'),
                'asset_scope' => $request->input('ed_asset_scope'),
                'scope_unit' => $request->input('ed_asset_scope_unit'),
                'b_desc' => $request->input('ed_b_desc'),
                'shared_asset' => 0,
                'zila_id' => $zp_id,
                'anchalik_id' => $ap_id,
                'gram_panchayat_id' => $gp_id,
                'village_id' => $request->input('ed_v_id'),

                'updated_by' => $users->username,
                'updated_at' => \Carbon\Carbon::now(),
            ]);

            if (!$updateData) {
                DB::rollback();
                $returnData['msg'] = "Oops! Something went wrong!";
                return response()->json($returnData);
            }
            $returnData['msgType'] = true;
            $returnData['data'] = [];
            $returnData['msg'] = "Successfully edited";

        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Server Exception." . $e->getMessage();
        }

        DB::commit();
        return response()->json($returnData);
    }

    //------------------------------- ASSET GEO-TAG --------------------------------------------------------------------

    public function geo_tagging_details(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $aid = $request->input('aid');

        $geoTagData = OsrNonTaxAssetEntry::getGeoTagDataByID($aid);

        $returnData['msgType'] = true;
        $returnData['data'] = ['geoTagData' => $geoTagData];
        $returnData['msg'] = "Success";

        return response()->json($returnData);

    }

    //------------------------------- ASSET GEO-TAG APPROVAL -----------------------------------------------------------

    public function geo_tagging_approval(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $aid = $request->input('as_id');

        $geoApprove = OsrNonTaxAssetEntry::isAlreadyApprove($aid);

        if ($geoApprove) {
            $returnData['msg'] = "Asset Geo-tag already approved";
            return response()->json($returnData);
        }
        $updateData = OsrNonTaxAssetEntry::where('id', $aid)
            ->update(
            ['geo_status_approve' => 1]
        );
        if (!$updateData) {
            $returnData['msg'] = "Oops! Data not found";
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = [];
        $returnData['msg'] = "Success";

        return response()->json($returnData);
    }
}
