<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PanchayatProfileRequest;
use Carbon\Carbon;
use DB;
use Auth;

class PanchayatController extends Controller
{
    //
    public function index()
    {
        $questions = DB::table('panchayat_profile_questions')->select('id', 'question')->get();
        $isp = DB::table('panchayat_profile_isps')->select('id', 'isp_name')->get();
        $profileAnswerCount = DB::table('panchayat_profiles')->where([
            'gram_panchyat_id' => Auth::user()->gp_id
        ])->count();
        return view('panchayat_profile.panchayat_profile_dashboard', compact('questions', 'isp', 'profileAnswerCount'));

    }

    public function submit(PanchayatProfileRequest $request)
    {
        if ($request->ajax()) {
            $data = [];
            $questionCount = DB::table('panchayat_profile_questions')->count();
            DB::beginTransaction();

            try {
                for ($i = 1; $i <= $questionCount; $i++) {
                    $otherIsp = NULL;
                    $others = NULL;
                    if ($i == 5) {
                        $otherIsp = $request->other_isp_name;
                        $others = $request->input('ISP-name');
                    }
                    DB::table('panchayat_profiles')->insert([

                        'gram_panchyat_id' => Auth::user()->gp_id,
                        'panchayat_profile_question_id' => $i,
                        'panchayat_profile_isp_id' => $others,
                        'other_isp_name' => $otherIsp,
                        'profile_answer' => $request->input('answer' . $i),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);


                }

                DB::commit();
                $data['msg'] = "success";
            }
            catch (\Exception $e) {
                DB::rollback();
                $data['msg'] = $e->getMessage();
            }
            return response()->json($data);
        }
    }

// function getData(Request $request)
// {
//     echo "form Submitted";
// }
}