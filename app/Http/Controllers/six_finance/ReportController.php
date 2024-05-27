<?php

namespace App\Http\Controllers\six_finance;

use App\CommonModels\GramPanchyat;
use App\survey\six_finance\AnchalikParishad;
use App\survey\six_finance\SixFinanceFinals;
use App\survey\six_finance\SixFinanceGpSelectionList;
use App\survey\six_finance\ZilaParishad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'user_mdas']);
    }

    public function index(Request $request){

        $users=$request->session()->get('users');

        $anchalikList=NULL;
        $zpGraph=NULL;

        if (in_array(4, $users->role)) {

            //-------------------   DISTRICT ADMIN    ------------------------------
            $cardCount= $this->cardCount(4, $users->district_code);
            $anchalikList= $this->getAnchalikList($users->district_code);
            $zpGraph= $this->graphZP($users->district_code);

        }elseif(in_array(1, $users->role)){

            $cardCount=$this->cardCount(1, NULL);

        }else{

            $cardCount=$this->cardCountGP($users->employee_code);

        }

        return view('survey.six_finance.report_dashboard', compact('cardCount', 'zpGraph', 'anchalikList'));
    }
	
	public function report_district_wise(Request $request){

        $msgType=true;

        $users = $request->session()->get('users');

        if (!in_array(4, $users->role)) {
            $msg = "You are unauthorised to delete.";
            $msgType=false;
        }

        $district_id= $users->district_code;


        return view('survey.six_finance.report_dashboard_combined', compact('msgType', 'msg', 'district_id'));
    }

    public function graphAP(Request $request){
        $ap_id= $request->input('ap_id');
    }

    private function getAnchalikList($district){
        $results= AnchalikParishad::join('zila_parishads', 'anchalik_parishads.zila_id', '=', 'zila_parishads.id')
            ->where([
                ['zila_parishads.district_id', '=', $district]
            ])->select('anchalik_parishads.id', 'anchalik_parishad_name')->get();
        return $results;
    }

    /*----------------------------------- REPORT CARD COUNT ----------------------------------------------------------*/

    private function cardCount($role, $district){

        $applicable=1;
        if($role==1) {
            $data_where=[
                ['applicable_id', '=', $applicable],
                ['final_submission_status', '=', 1],
            ];

            $data_district=[

            ];
        }else{
            $data_where=[
                ['applicable_id', '=', $applicable],
                ['district_id', '=', $district],
                ['final_submission_status', '=', 1],
            ];

            $data_district=[
                ['district_id', '=', $district]
            ];
        }

        $total_zp=ZilaParishad::where($data_district)->count();
        $submitted_zp=SixFinanceFinals::where($data_where)->count();

        //AP
        $applicable++;
        $total_ap=AnchalikParishad::join('zila_parishads', 'anchalik_parishads.zila_id', '=', 'zila_parishads.id')
            ->where($data_district)->count();
        $submitted_ap=SixFinanceFinals::where([
                ['applicable_id', '=', 2],
                ['district_id', '=', $district],
                ['final_submission_status', '=', 1],
            ])->count();

        //GP
        $applicable++;
        $total_gp=GramPanchyat::join('anchalik_parishads', 'anchalik_parishads.id', '=', 'gram_panchyats.anchalik_id')
            ->join('zila_parishads', 'anchalik_parishads.zila_id', '=', 'zila_parishads.id')
            ->where($data_district)->count();
        $submitted_gp=SixFinanceFinals::where([
                ['applicable_id', '=', 3],
                ['district_id', '=', $district],
                ['final_submission_status', '=', 1],
            ])->count();

        return [
            "ZP"=>["T"=>$total_zp, "S"=>$submitted_zp],
            "AP"=>["T"=>$total_ap, "S"=>$submitted_ap],
            "GP"=>["T"=>$total_gp, "S"=>$submitted_gp]
        ];

    }

    private function cardCountGP($emp_code){
        $total=SixFinanceGpSelectionList::where([
                ['emp_code', '=', $emp_code]
            ])->count();

        $submitted=SixFinanceFinals::where([
                ['employee_code', '=', $emp_code],
                ['final_submission_status', '=', 1],
            ])->count();

        return [
            "ZP"=>NULL,
            "AP"=>NULL,
            "GP"=>["T"=>$total, "S"=>$submitted]
        ];
    }

    /*----------------------------------- ZP GRAPH -------------------------------------------------------------------*/

    private function graphZP($district){

        $fianalArray=[];
        $fianalArrayChart=[];
        $apsArray=[];

        $data_district=[
            ['district_id', '=', $district]
        ];

        $zps= ZilaParishad::where($data_district)
            ->select('id', 'zila_parishad_name')
            ->first();

        $aps= AnchalikParishad::join('zila_parishads', 'anchalik_parishads.zila_id', '=', 'zila_parishads.id')
            ->where($data_district)
            ->select('anchalik_parishads.id', 'zila_id', 'anchalik_parishad_name')
            ->get();

        $submitted_zp_ap= SixFinanceFinals::where($data_district)
            ->whereIn('applicable_id', [1, 2])
            ->select('applicable_id', 'zila_id', 'anchalik_id',
                     'basic_info', 'staff_info', 'revenue_info',
                     'expenditure_info', 'balance_info', 'other_info',
                     'five_year_info', 'final_submission_status', 'verify')
            ->get();

        foreach($submitted_zp_ap AS $list){

            if($list->applicable_id == 1){
                $data["applicable_id"] = 1;
                if($zps->id == $list->zila_id){
                    $data["applicable_id"] = 1;
                    $data["zila_parishad_name"] = $zps->zila_parishad_name;
                    $data["anchalik_parishad_name"] = NULL;
                    $data["form_parts"]=$this->formPartsServive(true, $list);
                }else{
                    $data["applicable_id"] = 1;
                    $data["zila_parishad_name"] = $zps->zila_parishad_name;
                    $data["anchalik_parishad_name"] = NULL;
                    $data["form_parts"]=$this->formPartsServive(false, NULL);
                }
                array_push($fianalArray, $data);

            }elseif($list->applicable_id == 2){

                foreach($aps AS $key=>$ap) {
                    if ($ap->id == $list->anchalik_id) {
                        $data["applicable_id"] = 2;
                        $data["zila_parishad_name"] = $zps->zila_parishad_name;
                        $data["anchalik_parishad_name"] = $ap->anchalik_parishad_name;
                        $data["form_parts"] = $this->formPartsServive(true, $list);

                        //array_push($apsArray, $ap->id);
                        array_push($fianalArray, $data);
                        unset($aps[$key]);
                    }
                }
            }
        }

        foreach($aps AS $ap) {
            $data["applicable_id"] = 2;
            $data["zila_parishad_name"] = $zps->zila_parishad_name;
            $data["anchalik_parishad_name"] = $ap->anchalik_parishad_name;
            $data["form_parts"]=$this->formPartsServive(false, NULL);

            array_push($fianalArray, $data);
        }

        //echo json_encode($fianalArray);

        return $this->generateGraph($fianalArray);
    }

    private function formPartsServive($action, $list){
        if($action==true){
            $data["basic_info"] = $list->basic_info;
            $data["staff_info"] = $list->staff_info;
            $data["revenue_info"] = $list->revenue_info;
            $data["expenditure_info"] = $list->expenditure_info;
            $data["balance_info"] = $list->balance_info;
            $data["other_info"] = $list->other_info;
            $data["five_year_info"] = $list->five_year_info;
            $data["final_submission_status"] = $list->final_submission_status;
            $data["verify"] = $list->verify;
        }else{
            $data["basic_info"] = 0;
            $data["staff_info"] = 0;
            $data["revenue_info"] = 0;
            $data["expenditure_info"] = 0;
            $data["balance_info"] = 0;
            $data["other_info"] = 0;
            $data["five_year_info"] = 0;
            $data["final_submission_status"] = 0;
            $data["verify"] = 0;
        }

        return $data;
    }

    public function view_submitted_list(Request $request){

        $users=$request->session()->get('users');

        if(in_array(1, $users->role)){
            $data_district=[

            ];
        }elseif (in_array(4, $users->role)) { //DISTRICT ADMIN
            $district=$users->district_code;
            $data_district=[
                ['f.district_id', '=', $district]
            ];
        }else{
            $data_district=[
                ['f.employee_code', '=', $users->employee_code]
            ];
        }



        $results= DB::table('six_finance_finals AS f')
            ->join('districts AS dt', 'dt.id','=','f.district_id')
            ->join('applicables AS alp', 'alp.id','=','f.applicable_id')
            ->leftjoin('zila_parishads AS zp', 'zp.id', '=', 'f.zila_id')
            ->leftjoin('anchalik_parishads AS ap', 'ap.id', '=', 'f.anchalik_id')
            ->leftjoin('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'f.gram_panchayat_id')
            ->where($data_district)
            ->whereIn('applicable_id', [1, 2, 3])
            ->select('f.id','f.employee_code','dt.district_name', 'zp.zila_parishad_name', 'alp.applicable_name',
                'ap.anchalik_parishad_name', 'gp.gram_panchayat_name', 'f.basic_info', 'f.staff_info',
                'f.revenue_info', 'f.expenditure_info', 'f.balance_info', 'f.other_info', 'f.five_year_info', 'f.final_submission_status')
            ->get();

        return view('survey.six_finance.view_submitted_list', compact('results'));
    }

    public function getAP(Request $request){

        $returnData['msgType']=false;
        $returnData['msg']="Opps! Something went wrong!";
        $returnData['data']=[];
        $fianalArray=[];

        $ap_id=$request->input('ap_id');

        $ap=AnchalikParishad::where([
            ['id', '=', $ap_id]
        ])->first();

        if(!$ap){
            return $returnData;
        }

        $gps=GramPanchyat::where([
            ['anchalik_id', '=', $ap_id]
        ])->select('gram_panchyat_id AS id', 'gram_panchayat_name')->get();



        $submitted__ap= SixFinanceFinals::where([
                ['applicable_id', '=',3],
                ['anchalik_id', '=',$ap_id],
            ])->select('applicable_id', 'anchalik_id', 'gram_panchayat_id',
                'basic_info', 'staff_info', 'revenue_info',
                'expenditure_info', 'balance_info', 'other_info',
                'five_year_info', 'final_submission_status', 'verify')
            ->get();

        foreach($submitted__ap AS $list) {
            foreach ($gps AS $key=>$gp) {
                if ($gp->id == $list->gram_panchayat_id) {
                    $data["applicable_id"] = 3;
                    $data["gram_panchayat_name"] = $gp->gram_panchayat_name;
                    $data["form_parts"] = $this->formPartsServive(true, $list);

                    //array_push($apsArray, $ap->id);
                    array_push($fianalArray, $data);
                    unset($gps[$key]);
                }
            }
        }

        foreach($gps AS $gp) {
            $data["applicable_id"] = 3;
            $data["gram_panchayat_name"] = $gp->gram_panchayat_name;
            $data["form_parts"] = $this->formPartsServive(false, NULL);

            array_push($fianalArray, $data);
        }

        //echo json_encode($fianalArray);

        $returnData['msgType']=true;
        $returnData['msg']="Successfully done the task!";
        $returnData['data']=$this->generateGraph($fianalArray);
        return $returnData;
    }


    private function generateGraph($fianalArray){

        $fianalArrayChart['labels']=[];
        $fianalArrayChart['basics']=[];
        $fianalArrayChart['staffs']=[];
        $fianalArrayChart['revenues']=[];
        $fianalArrayChart['expenditures']=[];
        $fianalArrayChart['balances']=[];
        $fianalArrayChart['others']=[];
        $fianalArrayChart['nexts']=[];
        $fianalArrayChart['finals']=[];
        $fianalArrayChart['verify']=[];

        foreach($fianalArray AS $graph) {

            if ($graph['applicable_id']==1) {

                array_push($fianalArrayChart['labels'], $graph['zila_parishad_name'] . " (ZP)");

            } elseif($graph['applicable_id']==2) {

                array_push($fianalArrayChart['labels'], $graph['anchalik_parishad_name'] . " (AP)");

            }else{

                array_push($fianalArrayChart['labels'], $graph['gram_panchayat_name'] . " (GP)");
                array_push($fianalArrayChart['verify'], $graph['form_parts']['verify']);
            }

            array_push($fianalArrayChart['basics'], $graph['form_parts']['basic_info']);
            array_push($fianalArrayChart['staffs'], $graph['form_parts']['staff_info']);
            array_push($fianalArrayChart['revenues'], $graph['form_parts']['revenue_info']);
            array_push($fianalArrayChart['expenditures'], $graph['form_parts']['expenditure_info']);
            array_push($fianalArrayChart['balances'], $graph['form_parts']['balance_info']);
            array_push($fianalArrayChart['others'], $graph['form_parts']['other_info']);
            array_push($fianalArrayChart['nexts'], $graph['form_parts']['five_year_info']);
            array_push($fianalArrayChart['finals'], $graph['form_parts']['final_submission_status']);

        }

        return $fianalArrayChart;
    }
}
