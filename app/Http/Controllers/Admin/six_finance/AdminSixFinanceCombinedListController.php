<?php

namespace App\Http\Controllers\Admin\six_finance;

use App\CommonModels\GramPanchyat;
use App\CommonModels\District;
use App\survey\six_finance\AnchalikParishad;
use App\survey\six_finance\SixFinanceFinals;
use App\survey\six_finance\ZilaParishad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminSixFinanceCombinedListController extends Controller
{


    public function combined_list(Request $request){

        $results= DB::table('six_finance_finals AS f')
            ->join('districts AS dt', 'dt.id','=','f.district_id')
            ->join('applicables AS alp', 'alp.id','=','f.applicable_id')
            ->leftjoin('zila_parishads AS zp', 'zp.id', '=', 'f.zila_id')
            ->leftjoin('anchalik_parishads AS ap', 'ap.id', '=', 'f.anchalik_id')
            ->leftjoin('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'f.gram_panchayat_id')
            ->whereIn('applicable_id', [1, 2, 3])
            ->select('f.id','f.employee_code','dt.district_name', 'zp.zila_parishad_name', 'alp.applicable_name',
                'ap.anchalik_parishad_name', 'gp.gram_panchayat_name', 'f.basic_info', 'f.staff_info',
                'f.revenue_info', 'f.expenditure_info', 'f.balance_info', 'f.other_info', 'f.five_year_info', 'f.final_submission_status')
            ->orderBy('dt.district_name')->get();

        return view('admin.survey.six_finance.combined_list', compact('results'));
    }


    public function track_zp_ap_gp(Request $request){
        $zp=ZilaParishad::count();
        $ap=AnchalikParishad::count();
        $gp=GramPanchyat::whereNotNull('anchalik_id')->count();

        $sFV=SixFinanceFinals::whereIn('applicable_id', [1,2,3])
            ->selectRaw('applicable_id, sum(final_submission_status) AS final, sum(verify) AS verify')
            ->groupBy('applicable_id')->get();
        $districts = District::select('id', 'district_name')
                ->where([
                    'status'=>0
                ])
                ->get();
        $tracks = [];
        $i = 0;
        $zpTrackCount = [];
        $apTrackCount = [];
        $gpTrackCount = [];
        $zpTotalTrackCount = [];
        $apTotalTrackCount = [];
        $gpTotalTrackCount = [];
        foreach ($districts as $district) {
            $tracks = $this->get_applicable_finance_count($district['id']);
            $zpTrackCount[$i] = $tracks[0];
            $apTrackCount[$i] = $tracks[1];
            $gpTrackCount[$i] = $tracks[2];
			
            $zpTotalTrackCount[$i] = ZilaParishad::where([
                'district_id'=>$district['id']
            ])->count();
			
            $apTotalTrackCount[$i] = AnchalikParishad::join('zila_parishads','anchalik_parishads.zila_id','=','zila_parishads.id')->where([
                'zila_parishads.district_id'=>$district['id']
            ])->count();
			
            $gpTotalTrackCount[$i] = GramPanchyat::join('anchalik_parishads','gram_panchyats.anchalik_id','=','anchalik_parishads.id')
                    ->join('zila_parishads','anchalik_parishads.zila_id','=','zila_parishads.id')->where([
                'zila_parishads.district_id'=>$district['id']
            ])->count();
            $i++;
        }
        return view('admin.survey.six_finance.ad_track_zp_ap_gp', compact('zp', 'ap', 'gp', 'sFV', 'districts', 'zpTracks', 'zpTrackCount', 'apTrackCount', 'gpTrackCount','zpTotalTrackCount','apTotalTrackCount','gpTotalTrackCount'));
    }
    private function get_applicable_finance_count($district_id) {
        $zilas = ZilaParishad::where([
                    'district_id' => $district_id
                ])
                ->select('id')
                ->get();

        $zila = [];
        $i = 0;
        foreach ($zilas as $value) {
            $zila[$i] = $value['id'];
            $i++;
        }
        $ap = [];
        $anchalikParishads = AnchalikParishad::whereIn('zila_id', $zila)
                ->select('id')
                ->get();
        $j = 0;
        foreach ($anchalikParishads as $value) {
            $ap[$j] = $value['id'];
            $j++;
        }
        $zpTrackCount = SixFinanceFinals::join('zila_parishads', 'six_finance_finals.zila_id', '=', 'zila_parishads.id')
                ->where([
                    'zila_parishads.district_id' => $district_id,
                    'applicable_id' => 1,
                    'final_submission_status' => 1
                ])
                ->count();
        $apTrackCount = SixFinanceFinals::join('anchalik_parishads', 'six_finance_finals.anchalik_id', '=', 'anchalik_parishads.id')
                ->where([
                    'applicable_id' => 2,
                    'final_submission_status' => 1
                ])
                ->whereIn('anchalik_parishads.zila_id', $zila)
                ->count();
        $gpTrackCount = SixFinanceFinals::join('gram_panchyats', 'six_finance_finals.gram_panchayat_id', '=', 'gram_panchyats.gram_panchyat_id')
                ->where([
                    'applicable_id' => 3,
                    'final_submission_status' => 1
                ])
                ->whereIn('gram_panchyats.anchalik_id', $ap)
                ->count();

        

        return [
            $zpTrackCount,
            $apTrackCount,
            $gpTrackCount,
        ];
    }
    public function track_zp(Request $request){
        $applicable_id=1;
        $results= DB::table('districts AS dt')
            ->join('zila_parishads AS zp', 'zp.district_id', '=', 'dt.id')
            ->leftjoin('six_finance_finals AS f', function($leftJoin)use($applicable_id){
                $leftJoin->on('zp.id', '=', 'f.zila_id');
                $leftJoin->on(DB::raw('f.applicable_id'), DB::raw('='),DB::raw("'".$applicable_id."'"));
            })
            ->select('f.id','f.employee_code','dt.district_name', 'zp.zila_parishad_name',
                'f.basic_info', 'f.staff_info', 'f.revenue_info', 'f.expenditure_info', 'f.balance_info', 'f.other_info', 'f.five_year_info', 'f.final_submission_status', 'f.verify')
            ->orderBy('dt.district_name')->get();

        return view('admin.survey.six_finance.ad_track_zp', compact('results'));
    }

    public function track_ap(Request $request){
        $applicable_id=2;
        $results= DB::table('districts AS dt')
            ->join('zila_parishads AS zp', 'zp.district_id', '=', 'dt.id')
            ->join('anchalik_parishads AS ap', 'ap.zila_id', '=', 'zp.id')
            ->leftjoin('six_finance_finals AS f', function($leftJoin)use($applicable_id){
                $leftJoin->on('ap.id', '=', 'f.anchalik_id');
                $leftJoin->on(DB::raw('f.applicable_id'), DB::raw('='),DB::raw("'".$applicable_id."'"));
            })//'f.anchalik_id', '=', 'ap.id')
            ->select('f.id','f.employee_code', 'dt.district_name', 'zp.zila_parishad_name', 'ap.anchalik_parishad_name',
                'f.basic_info', 'f.staff_info', 'f.revenue_info', 'f.expenditure_info', 'f.balance_info', 'f.other_info', 'f.five_year_info', 'f.final_submission_status', 'f.verify')
            ->orderBy('dt.district_name')->get();

        return view('admin.survey.six_finance.ad_track_ap', compact('results'));
    }

    public function track_gp(Request $request){
        $applicable_id=3;
        $results= DB::table('districts AS dt')
            ->join('zila_parishads AS zp', 'zp.district_id', '=', 'dt.id')
            ->join('anchalik_parishads AS ap', 'ap.zila_id', '=', 'zp.id')
            ->join('gram_panchyats AS gp', 'gp.anchalik_id', '=', 'ap.id')
            ->leftjoin('six_finance_finals AS f', function($leftJoin)use($applicable_id){
                $leftJoin->on('gp.gram_panchyat_id', '=', 'f.gram_panchayat_id');
                $leftJoin->on(DB::raw('f.applicable_id'), DB::raw('='),DB::raw("'".$applicable_id."'"));
            })//'gp.gram_panchyat_id', '=', 'f.gram_panchayat_id')
            ->select('f.id','f.employee_code', 'dt.district_name', 'zp.zila_parishad_name', 'ap.anchalik_parishad_name', 'gp.gram_panchayat_name',
                'f.basic_info', 'f.staff_info', 'f.revenue_info', 'f.expenditure_info', 'f.balance_info', 'f.other_info', 'f.five_year_info', 'f.final_submission_status', 'f.verify')
            ->orderBy('dt.district_name')->get();

        return view('admin.survey.six_finance.ad_track_gp', compact('results'));
    }
}
