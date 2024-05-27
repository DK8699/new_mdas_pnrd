<?php

namespace App\Http\Controllers\Admin\UsersManagement;

use App\CommonModels\GramPanchyat;
use App\CommonModels\District;
use App\CommonModels\Block;
use App\survey\six_finance\AnchalikParishad;
use App\survey\six_finance\ZilaParishad;
use App\CommonModels\SiprdExtensionCenter;
use App\UsersManagement\MdasMasterLevel;
use App\UsersManagement\MdasMasterRole;
use App\UsersManagement\MdasUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Validator;
use DB;
use Illuminate\Support\Facades\Hash;
use App\ConfigMdas;

// ........ning              /admin/Pris/viewPri/    go to route of web admin 
// 
class AdminUsersManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin_mdas']);
    }

    public function user_dashboard(Request $request)
    {

        // if(Auth::mdas_users()->mdas_master_level_id == 1){

        $levelList = MdasMasterLevel::all();
        $roleList = MdasMasterRole::all();

        $districtCouncilList = District::getDistrictCouncil();

        //echo json_encode($districtCouncilList);

        $zpList = ZilaParishad::all();
        $extension_centre = SiprdExtensionCenter::all();
        $userCountList = MdasUser::join('mdas_master_levels AS l', 'l.id', '=', 'mdas_users.mdas_master_level_id')
            ->join('mdas_master_roles AS r', 'r.id', '=', 'mdas_users.mdas_master_role_id')
            ->select(DB::raw('count(*) as count'), 'r.role_name', 'r.authority')
            ->groupBy('r.id')
            ->orderBy('r.id')
            ->get();

        $data = [
            "userCountList" => $userCountList,
            "levelList" => $levelList,
            "roleList" => $roleList,
            "zpList" => $zpList,
            "extension_centre" => $extension_centre,
            "districtCouncilList" => $districtCouncilList,
        ];


        return view('admin.UsersManagement.user_dashboard', compact('data'));
    }
    //     return redirect()->back();
    // }

    public function StateUser(Request $request)
    {
        $imgUrl = ConfigMdas::allActiveList()->imgUrl;
        $mdasUserList = MdasUser::leftJoin('mdas_master_levels AS l', 'l.id', '=', 'mdas_users.mdas_master_level_id')
            ->leftJoin('mdas_master_roles AS r', 'r.id', '=', 'mdas_users.mdas_master_role_id')
            ->where('r.id', 1)
            ->select('mdas_users.id AS mdas_id', 'username', 'designation', 'employee_code', 'f_name', 'm_name', 'l_name', 'image', 'mobile', 'email', 'status', 'password', 'l.level_name', 'r.role_name')->get();
        return view('admin.UsersManagement.state_user_management', compact('mdasUserList', 'imgUrl'));
    }

    public function ZpUser(Request $request)
    {
        $imgUrl = ConfigMdas::allActiveList()->imgUrl;
        $mdasUserList = MdasUser::leftJoin('mdas_master_levels AS l', 'l.id', '=', 'mdas_users.mdas_master_level_id')
            ->leftJoin('mdas_master_roles AS r', 'r.id', '=', 'mdas_users.mdas_master_role_id')
            ->leftJoin('zila_parishads AS zp', 'zp.id', '=', 'mdas_users.zp_id')
            ->where('r.id', 2)
            ->select('mdas_users.id AS mdas_id', 'username', 'designation', 'employee_code', 'f_name', 'm_name', 'l_name', 'image', 'mobile', 'email', 'status', 'password', 'l.level_name', 'r.role_name', 'zp.zila_parishad_name')->get();
        return view('admin.UsersManagement.zp_user_management', compact('mdasUserList', 'imgUrl'));
    }

    //new council user
    public function DistrictCouncilUser(Request $request)
    {
        $imgUrl = ConfigMdas::allActiveList()->imgUrl;
        $mdasUserList = MdasUser::leftJoin('mdas_master_levels AS l', 'l.id', '=', 'mdas_users.mdas_master_level_id')
            ->leftJoin('mdas_master_roles AS r', 'r.id', '=', 'mdas_users.mdas_master_role_id')
            ->leftJoin('districts AS d', 'd.id', '=', 'mdas_users.district_code')
            ->where([
                ['r.id', '=', 7],
                ['is_council', '=', 1],
            ])
            ->select('mdas_users.id AS mdas_id', 'username', 'designation', 'employee_code', 'f_name', 'm_name', 'l_name', 'image', 'mobile', 'email', 'mdas_users.status', 'password', 'l.level_name', 'r.role_name', 'd.district_name')->get();
        return view('admin.UsersManagement.district_council_user_management', compact('mdasUserList', 'imgUrl'));
    }

    public function ApUser(Request $request)
    {
        $imgUrl = ConfigMdas::allActiveList()->imgUrl;
        $mdasUserList = MdasUser::leftJoin('mdas_master_levels AS l', 'l.id', '=', 'mdas_users.mdas_master_level_id')
            ->leftJoin('mdas_master_roles AS r', 'r.id', '=', 'mdas_users.mdas_master_role_id')
            ->leftJoin('zila_parishads AS zp', 'zp.id', '=', 'mdas_users.zp_id')
            ->leftJoin('anchalik_parishads AS ap', 'ap.id', '=', 'mdas_users.ap_id')
            ->where('r.id', 3)
            ->select('mdas_users.id AS mdas_id', 'username', 'designation', 'employee_code', 'f_name', 'm_name', 'l_name', 'image', 'mobile', 'email', 'status', 'password', 'l.level_name', 'r.role_name', 'zp.zila_parishad_name', 'ap.anchalik_parishad_name')->get();
        return view('admin.UsersManagement.ap_user_management', compact('mdasUserList', 'imgUrl'));
    }

    //new council user
    public function BlockCouncilUser(Request $request)
    {
        $imgUrl = ConfigMdas::allActiveList()->imgUrl;
        $mdasUserList = MdasUser::leftJoin('mdas_master_levels AS l', 'l.id', '=', 'mdas_users.mdas_master_level_id')
            ->leftJoin('mdas_master_roles AS r', 'r.id', '=', 'mdas_users.mdas_master_role_id')
            ->leftJoin('districts AS d', 'd.id', '=', 'mdas_users.district_code')
            ->leftJoin('blocks AS b', 'b.id', '=', 'mdas_users.block_id')
            ->where([
                ['r.id', '=', 8],
                ['is_council', '=', 1],
            ])
            ->select('mdas_users.id AS mdas_id', 'username', 'designation', 'employee_code', 'f_name', 'm_name', 'l_name', 'image', 'mobile', 'email', 'mdas_users.status', 'password', 'l.level_name', 'r.role_name', 'd.district_name', 'b.block_name')->get();
        return view('admin.UsersManagement.block_council_user_management', compact('mdasUserList', 'imgUrl'));
    }

    public function GpUser(Request $request)
    {
        set_time_limit(999);
        $imgUrl = ConfigMdas::allActiveList()->imgUrl;
        $mdasUserList = MdasUser::leftJoin('mdas_master_levels AS l', 'l.id', '=', 'mdas_users.mdas_master_level_id')
            ->leftJoin('mdas_master_roles AS r', 'r.id', '=', 'mdas_users.mdas_master_role_id')
            ->leftJoin('zila_parishads AS zp', 'zp.id', '=', 'mdas_users.zp_id')
            ->leftJoin('anchalik_parishads AS ap', 'ap.id', '=', 'mdas_users.ap_id')
            ->leftJoin('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'mdas_users.gp_id')
            ->where('r.id', 4)
            ->select('mdas_users.id AS mdas_id', 'username', 'designation', 'employee_code', 'f_name', 'm_name', 'l_name', 'image', 'mobile', 'email', 'status', 'password', 'l.level_name', 'r.role_name', 'zp.zila_parishad_name', 'ap.anchalik_parishad_name', 'gp.gram_panchayat_name')->get();
        return view('admin.UsersManagement.gp_user_management', compact('mdasUserList', 'imgUrl'));
    }

    //new council user
    public function GpCouncilUser(Request $request)
    {
        $imgUrl = ConfigMdas::allActiveList()->imgUrl;
        $mdasUserList = MdasUser::leftJoin('mdas_master_levels AS l', 'l.id', '=', 'mdas_users.mdas_master_level_id')
            ->leftJoin('mdas_master_roles AS r', 'r.id', '=', 'mdas_users.mdas_master_role_id')
            ->leftJoin('districts AS d', 'd.id', '=', 'mdas_users.district_code')
            ->leftJoin('blocks AS b', 'b.id', '=', 'mdas_users.block_id')
            ->leftJoin('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'mdas_users.gp_id')
            ->where([
                ['r.id', '=', 9],
                ['is_council', '=', 1],
            ])
            ->select('mdas_users.id AS mdas_id', 'username', 'designation', 'employee_code', 'f_name', 'm_name', 'l_name', 'image', 'mobile', 'email', 'mdas_users.status', 'password', 'l.level_name', 'r.role_name', 'd.district_name', 'b.block_name', 'gp.gram_panchayat_name')->get();
        return view('admin.UsersManagement.gp_council_user_management', compact('mdasUserList', 'imgUrl'));
    }


    public function CourtCaseUser(Request $request)
    {
        $imgUrl = ConfigMdas::allActiveList()->imgUrl;
        $mdasUserList = MdasUser::leftJoin('mdas_master_levels AS l', 'l.id', '=', 'mdas_users.mdas_master_level_id')
            ->leftJoin('mdas_master_roles AS r', 'r.id', '=', 'mdas_users.mdas_master_role_id')
            ->leftJoin('zila_parishads AS zp', 'zp.id', '=', 'mdas_users.zp_id')
            ->leftJoin('anchalik_parishads AS ap', 'ap.id', '=', 'mdas_users.ap_id')
            ->leftJoin('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'mdas_users.gp_id')
            ->where('r.id', 5)
            ->select('mdas_users.id AS mdas_id', 'username', 'designation', 'employee_code', 'f_name', 'm_name', 'l_name', 'image', 'mobile', 'email', 'status', 'password', 'l.level_name', 'r.role_name', 'zp.zila_parishad_name', 'ap.anchalik_parishad_name', 'gp.gram_panchayat_name')->get();
        return view('admin.UsersManagement.cc_user_management', compact('mdasUserList', 'imgUrl'));
    }

    public function ExtensionCentreUser(Request $request)
    {
        $imgUrl = ConfigMdas::allActiveList()->imgUrl;
        $mdasUserList = MdasUser::leftJoin('mdas_master_levels AS l', 'l.id', '=', 'mdas_users.mdas_master_level_id')
            ->leftJoin('mdas_master_roles AS r', 'r.id', '=', 'mdas_users.mdas_master_role_id')
            ->leftJoin('siprd_extension_centers AS ex', 'ex.id', '=', 'mdas_users.ex_id')
            ->where('r.id', 6)
            ->select('mdas_users.id AS mdas_id', 'username', 'designation', 'employee_code', 'f_name', 'm_name', 'l_name', 'image', 'mobile', 'email', 'status', 'password', 'l.level_name', 'r.role_name')->get();
        return view('admin.UsersManagement.ex_user_management', compact('mdasUserList', 'imgUrl'));
    }

    public function User(Request $request)
    {
        $mdasMasterLevels = MdasMasterLevel::all();
        $mdasMasterRoles = MdasMasterRole::all();
        $zillaForFilters = ZilaParishad::all();
        $filterTier = [];

        $filterArray = [];

        if ($request->isMethod('post')) {
            $filterTier = $request->input('tier');
            if ($filterTier == 2 || $filterTier == 3) {
                $tier = 2;
                $appls = [1, 2];

                $zillas = $zillaForFilters;
                $anchaliks = $request->input('ap_id');

                //////////////////
                if ($filterTier == 2) {
                    $zp_id = $request->input('zp_id');
                    $ap_id = $request->input('ap_id');
                    $aps = AnchalikParishad::where([
                        ['zila_id', '=', $zp_id]
                    ])->select('id', 'anchalik_parishad_name')->get();
                    $filterArray = ["filterTier" => $filterTier, "filterAP" => $ap_id, "filterAPList" => $aps];
                } else {
                    $zp_id = $request->input('zp_id');
                    $ap_id = $request->input('ap_id');
                    $filterArray = ["filterTier" => $filterTier, "filterZP" => $zp_id, "filterAP" => $ap_id];
                }

            } elseif ($filterTier == "4") {
                $tier = 4;
                $appls = [3];

                //////////////////////
                $zp_id = $request->input('zp_id');
                $ap_id = $request->input('ap_id');
                $gp_id = $request->input('gp_id');

                $gps = GramPanchyat::where([
                    ['anchalik_id', '=', $ap_id]
                ])->select('gram_panchyat_id AS id', 'gram_panchayat_name')->get();


                $filterArray = ["filterTier" => $filterTier, "filterZP" => $zp_id, "filterAP" => $ap_id, "filterGP" => $gp_id, "filterGPList" => $gps];

                /////////////////////////////
                $zillas = $zillaForFilters;
            } else {
                $appls = [];
                $tier = NULL;
                $filterArray = [];
            }
        }
        $imgUrl = ConfigMdas::allActiveList()->imgUrl;
        $mdasUserList = MdasUser::leftJoin('mdas_master_levels AS l', 'l.id', '=', 'mdas_users.mdas_master_level_id')
            ->leftJoin('mdas_master_roles AS r', 'r.id', '=', 'mdas_users.mdas_master_role_id')
            ->leftJoin('zila_parishads AS zp', 'zp.id', '=', 'mdas_users.zp_id')
            ->leftJoin('anchalik_parishads AS ap', 'ap.id', '=', 'mdas_users.ap_id')
            ->leftJoin('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'mdas_users.gp_id')
            ->select('mdas_users.id AS mdas_id', 'username', 'designation', 'employee_code', 'f_name', 'm_name', 'l_name', 'image', 'mobile', 'email', 'status', 'password', 'l.level_name', 'r.role_name', 'zp.zila_parishad_name', 'ap.anchalik_parishad_name', 'gp.gram_panchayat_name')->get();
        return view('admin.UsersManagement.user_management', compact('mdasMasterLevels', 'mdasMasterRoles', 'zillaForFilters', 'filterArray', 'mdasUserList', 'imgUrl'));
    }

    public function selectAp(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Failed To Process Request.";

        try {
            $results = AnchalikParishad::getAPsByZilaId($request->input('zila_id'));

            if (!count($results) > 0) {
                $returnData['msg'] = "No Data Found.";
                return response()->json($returnData);
            }

        } catch (\Exception $e) {
            $returnData['msg'] = "Server Exception." . $e->getMessage();
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = $results;
        $returnData['msg'] = "Success.";
        return response()->json($returnData);
    }

    public function selectAjax(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Failed To Process Request.";

        try {
            $results = GramPanchyat::getGpsByAnchalikId($request->input('anchalik_id'));

            if (!count($results) > 0) {
                $returnData['msg'] = "No Data Found.";
                return response()->json($returnData);
            }

        } catch (\Exception $e) {
            $returnData['msg'] = "Server Exception." . $e->getMessage();
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = $results;
        $returnData['msg'] = "Success.";
        return response()->json($returnData);
    }

    public function createMdasUser(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        DB::beginTransaction();
        try {

            $messages = [
                'mdas_master_level_id.required' => 'This field is required',
                'mdas_master_level_id.exists' => 'Invaild data',
                'mdas_master_role_id.required' => 'This field is required',
                'mdas_master_role_id.exists' => 'Invalid data',
                'designation.required' => 'This field is required',
                'zp_id.required_if' => 'This field is required',
                'zp_id.exists' => 'Invalid data',
                'd_id.required_if' => 'This field is required',
                'd_id.exists' => 'Invalid data',
                'ap_id.required_if' => 'This field is required',
                'ap_id.exists' => 'Invalid data',
                'b_id.required_if' => 'This field is required',
                'b_id.exists' => 'Invalid data',
                'gp_id.required_if' => 'This field is required',
                'gp_id.exists' => 'Invalid data',
                'ex_id.required_if' => 'This field is required',
                'ex_id.exists' => 'Invalid data',
                'f_name.required' => 'This field is required',
                'l_name.required' => 'This field is required',
                'mobile.required' => 'This field is required',
                'email.required' => 'This field is required'
            ];

            $validatorArray = [
                'mdas_master_level_id' => 'required|exists:mdas_master_levels,id',
                'mdas_master_role_id' => 'required|exists:mdas_master_roles,id',
                'designation' => 'required',
                'zp_id' => 'required_if:mdas_master_role_id,2,3,4|nullable|exists:zila_parishads,id',
                'd_id' => 'required_if:mdas_master_role_id,7,8,9|nullable|exists:districts,id',
                'ap_id' => 'required_if:mdas_master_role_id,3,4|nullable|exists:anchalik_parishads,id',
                'b_id' => 'required_if:mdas_master_role_id,8,9|nullable|exists:blocks,id',
                'gp_id' => 'required_if:mdas_master_role_id,4,9|nullable|exists:gram_panchyats,gram_panchyat_id',
                'ex_id' => 'required_if:mdas_master_role_id,6|nullable|exists:siprd_extension_centers,id',
                'f_name' => 'required',
                'l_name' => 'required',
                'mobile' => 'required',
                'email' => 'required'
            ];

            $validator = Validator::make($request->all(), $validatorArray, $messages);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $returnData['msg'] = "VE";
                $returnData['errors'] = $errors;
                return response()->json($returnData);
            }
            $users = Auth::user();
            $created_by = $users->username;
            $mdas_master_level_id = $request->input('mdas_master_level_id');
            $mdas_master_role_id = $request->input('mdas_master_role_id');
            $zp_id = $request->input('zp_id');
            $district_id = $request->input('d_id');
            $ap_id = $request->input('ap_id');
            $block_id = $request->input('b_id');
            $gp_id = $request->input('gp_id');
            $ex_id = $request->input('ex_id');
            $mobile = $request->input('mobile');
            $email = $request->input('email');

            $employee_code = $request->input('employee_code');
            $mdasuersLOne = MdasUser::select('username')->where('mdas_master_role_id', '=', $mdas_master_role_id)->orderBy('id', 'DESC')->first();
            $district_code = NULL;
            $is_Council = 0;

            if ($zp_id) {
                $district = ZilaParishad::select('district_id')->where('id', '=', $zp_id)->first();
                $mdasuersLTwo = MdasUser::select('username')->where([
                    ['mdas_master_role_id', '=', 2],
                    ['district_code', '=', $district->district_id]
                ])->orderBy('id', 'DESC')->first();

                $mdasuersLThree = MdasUser::select('username')->where([
                    ['mdas_master_role_id', '=', 3],
                    ['district_code', '=', $district->district_id]
                ])->orderBy('id', 'DESC')->first();

                $mdasuersLFour = MdasUser::select('username')->where([
                    ['mdas_master_role_id', '=', 4],
                    ['district_code', '=', $district->district_id]
                ])->orderBy('id', 'DESC')->first();
                $district_code = $district->district_id;
            }

            // For council/VCDC

            if ($district_id) {
                $mdasuersLTwo = MdasUser::select('username')->where([
                    ['mdas_master_role_id', '=', 7],
                    ['district_code', '=', $district_id]
                ])->orderBy('id', 'DESC')->first();

                $mdasuersLThree = MdasUser::select('username')->where([
                    ['mdas_master_role_id', '=', 8],
                    ['district_code', '=', $district_id]
                ])->orderBy('id', 'DESC')->first();

                $mdasuersLFour = MdasUser::select('username')->where([
                    ['mdas_master_role_id', '=', 9],
                    ['district_code', '=', $district_id]
                ])->orderBy('id', 'DESC')->first();
                $district_code = $district_id;
                $is_Council = 1;
            }

            if ($employee_code) {
                $username = $employee_code;
            } else {
                if ($mdas_master_level_id == 1) {
                    if (!$mdasuersLOne) {
                        $lastNum = 10001;
                    } else {
                        $userLast = substr($mdasuersLOne->username, -5);
                        $lastNum = $userLast + 1;
                    }

                    if ($mdas_master_role_id == 1) {
                        $sString = "SA";
                    } else if ($mdas_master_role_id == 5) {
                        $sString = "CC";
                    } else {
                        $sString = "EX";
                    }

                    $level = "L";
                    $username = $sString . $level . $lastNum;
                } elseif ($mdas_master_level_id == 2) {
                    if ($mdas_master_role_id == 2) {
                        if (!$mdasuersLTwo) {
                            $lastNum = 20001;
                        } else {
                            $userLast = substr($mdasuersLTwo->username, -5);
                            $lastNum = $userLast + 1;
                        }
                        $dString = "DA";
                        $disCode = $district->district_id;
                        $level = "L";
                        $username = $dString . $disCode . $level . $lastNum;
                    } else {
                        if (!$mdasuersLTwo) {
                            $lastNum = 20001;
                        } else {
                            $userLast = substr($mdasuersLTwo->username, -5);
                            $lastNum = $userLast + 1;
                        }
                        $dString = "DCA";
                        $disCode = $district_id;
                        $level = "L";
                        $username = $dString . $disCode . $level . $lastNum;
                    }
                } elseif ($mdas_master_level_id == 3) {
                    if ($mdas_master_role_id == 3) {
                        if (!$mdasuersLThree) {
                            $lastNum = 30001;
                        } else {
                            $userLast = substr($mdasuersLThree->username, -5);
                            $lastNum = $userLast + 1;
                        }
                        $aString = "AA";
                        $disCode = $district->district_id;
                        $level = "L";
                        $username = $aString . $disCode . $level . $lastNum;
                    } else {
                        if (!$mdasuersLThree) {
                            $lastNum = 30001;
                        } else {
                            $userLast = substr($mdasuersLThree->username, -5);
                            $lastNum = $userLast + 1;
                        }
                        $aString = "BCA";
                        $disCode = $district_id;
                        $level = "L";
                        $username = $aString . $disCode . $level . $lastNum;
                    }
                } else {
                    if ($mdas_master_role_id == 4) {
                        if (!$mdasuersLFour) {
                            $lastNum = 40001;
                        } else {
                            $userLast = substr($mdasuersLFour->username, -5);
                            $lastNum = $userLast + 1;
                        }
                        $gString = "GA";
                        $disCode = $district->district_id;
                        $level = "L";
                        $username = $gString . $disCode . $level . $lastNum;
                    } else {
                        if (!$mdasuersLFour) {
                            $lastNum = 40001;
                        } else {
                            $userLast = substr($mdasuersLFour->username, -5);
                            $lastNum = $userLast + 1;
                        }
                        $gString = "GCA";
                        $disCode = $district_id;
                        $level = "L";
                        $username = $gString . $disCode . $level . $lastNum;
                    }
                }
            }
            $password = "pass@123";
            $image_path = NULL;
            if ($request->file('image')) {
                $image_path = $request->file('image')->store('users');
            }
            $checkMobile = MdasUser::where('mobile', '=', $mobile)->first();
            $checkEmail = MdasUser::where('email', '=', $email)->first();
            if ($checkMobile || $checkEmail) {
                $returnData['msgType'] = false;
                $returnData['msg'] = "Mobile Number Or Email ID already Exist";
                return response()->json($returnData);
            } else {
                $mdasUserSave = new MdasUser();
                $mdasUserSave->mdas_master_level_id = $mdas_master_level_id;
                $mdasUserSave->mdas_master_role_id = $request->input('mdas_master_role_id');
                $mdasUserSave->designation = $request->input('designation');
                $mdasUserSave->f_name = $request->input('f_name');
                $mdasUserSave->m_name = $request->input('m_name');
                $mdasUserSave->l_name = $request->input('l_name');
                $mdasUserSave->mobile = $mobile;
                $mdasUserSave->email = $email;
                $mdasUserSave->employee_code = $employee_code;
                $mdasUserSave->zp_id = $zp_id;
                $mdasUserSave->ap_id = $ap_id;
                $mdasUserSave->gp_id = $gp_id;
                $mdasUserSave->ex_id = $ex_id;
                $mdasUserSave->username = $username;
                $mdasUserSave->password = password_hash($password, PASSWORD_DEFAULT);
                $mdasUserSave->image = $image_path;
                $mdasUserSave->district_code = $district_code;
                $mdasUserSave->block_id = $block_id;
                $mdasUserSave->is_council = $is_Council;
                $mdasUserSave->created_by = $created_by;
                if (!$mdasUserSave->save()) {
                    return response()->json($returnData);
                }
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

    public function statusUser(Request $request)
    {
        $user = MdasUser::find($request->user_id);
        $user->status = $request->status;
        $user->save();
        return response()->json(['success' => 'Status change successfully.']);
    }

    public function profile()
    {
        $users = Auth::user();
        $user_id = $users->id;
        $imgUrl = ConfigMdas::allActiveList()->imgUrl;
        $mdasUser = MdasUser::leftJoin('mdas_master_levels AS l', 'l.id', '=', 'mdas_users.mdas_master_level_id')
            ->leftJoin('mdas_master_roles AS r', 'r.id', '=', 'mdas_users.mdas_master_role_id')
            ->where('mdas_users.id', $user_id)
            ->select('mdas_users.id AS mdas_id', 'username', 'designation', 'employee_code', 'f_name', 'm_name', 'l_name', 'image', 'mobile', 'email', 'address', 'status', 'password', 'l.level_name', 'r.role_name')->first();
        // dd($mdasUser);
        return view('admin.UsersManagement.profile', compact('mdasUser', 'imgUrl'));
    }

    public function ChangePassword()
    {
        return view('admin.UsersManagement.change_password');
    }

    public function getMdasUserByid(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $aid = $request->input('aid');

        $MdasUserData = MdasUser::getUserById($aid);

        if (!$MdasUserData) {
            $returnData['msg'] = "Unauthorised access. Please contact admin for more details";
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = ['MdasUserData' => $MdasUserData];
        $returnData['msg'] = "Success";
        return response()->json($returnData);
    }

    public function userPasswordUpdate(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";
        $users = Auth::user();
        $aid = $request->input('aid');
        $new_pass = $request->input('ed_new_pass');
        $confirm_pass = $request->input('ed_confirm_pass');
        $messages = [
            'ed_new_pass.required' => 'This is required.',
            'ed_new_pass.min' => 'Minimum 8 characters required',
            'ed_confirm_pass.required' => 'This is required.',
        ];

        $validatorArray = [
            'ed_new_pass' => 'required|min:8',
            'ed_confirm_pass' => 'required',
        ];
        $validator = Validator::make($request->all(), $validatorArray, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);
        }


        $updateArray = [
            'password' => password_hash($new_pass, PASSWORD_DEFAULT),
            'updated_by' => $users->username,
            'updated_at' => Carbon::now(),
        ];

        if ($new_pass != $confirm_pass) {
            $returnData['msg'] = "Password do not match";
            return $returnData;
        }
        DB::beginTransaction();
        try {
            $updateMdasUserPassword = MdasUser::where('id', $aid)
                ->update($updateArray);

            if (!$updateMdasUserPassword) {
                DB::rollback();
                $returnData['msg'] = "Oops! Something went wrong!";
                return response()->json($returnData);
            }
        } catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng";
            return $returnData;
        }

        DB::commit();


        $returnData['msgType'] = true;
        $returnData['data'] = [];
        $returnData['msg'] = "Password Changed Successfully";
        return response()->json($returnData);
    }

    //New Changes for Council VCDC

    public function selectBlockCouncil(Request $request)
    {

        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Failed To Process Request.";

        try {
            $results = Block::getBlocksByDistrictId($request->input('district_id'));

            if (!count($results) > 0) {
                $returnData['msg'] = "No Data Found.$results";
                return response()->json($returnData);
            }

        } catch (\Exception $e) {
            $returnData['msg'] = "Server Exception." . $e->getMessage();
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = $results;
        $returnData['msg'] = "Success.";
        return response()->json($returnData);

    }

    public function selectVCDCajax(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Failed To Process Request.";

        try {
            $results = GramPanchyat::getVCDCByBlockId($request->input('block_id'));

            if (!count($results) > 0) {
                $returnData['msg'] = "No Data Found.";
                return response()->json($returnData);
            }

        } catch (\Exception $e) {
            $returnData['msg'] = "Server Exception." . $e->getMessage();
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = $results;
        $returnData['msg'] = "Success.";
        return response()->json($returnData);
    }
}