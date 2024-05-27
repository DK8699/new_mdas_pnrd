<?php

namespace App\Http\Controllers\six_finance;

use App\BalanceModels\FinancialBalance;
use App\CommonModels\Act;
use App\CommonModels\GramPanchyat;
use App\ExpenditureModels\ExpenditureCategory;
use App\ExpenditureModels\FinancialExpenditure;
use App\NewSchemeModels\ProposalEntity;
use App\NewSchemeModels\SchemeProposal;
use App\survey\six_finance\AnchalikParishad;
use App\survey\six_finance\SixFinanceFinals;
use App\survey\six_finance\SixFinanceFormBasic;
use App\survey\six_finance\SixFinanceFormOtherRegisters;
use App\survey\six_finance\SixFinanceFormOthers;
use App\survey\six_finance\SixFinanceFormOtherSubs;
use App\survey\six_finance\SixFinanceFormRevenueArrearTaxes;
use App\survey\six_finance\SixFinanceFormRevenueCssShares;
use App\survey\six_finance\SixFinanceFormRevenueOwnRevenues;
use App\survey\six_finance\SixFinanceFormRevenueTransferredResources;
use App\survey\six_finance\SixFinanceFormStaffDetails;
use App\survey\six_finance\SixFinanceFormStaffs;
use App\survey\six_finance\SixFinanceFormStaffSalarySummaries;
use App\survey\six_finance\SixFinanceOtherInfoRegisterCats;
use App\survey\six_finance\SixFinanceOtherInfoRoadCats;
use App\survey\six_finance\SixFinanceRevenueCssShareCats;
use App\survey\six_finance\SixFinanceRevenueOwnRevenueCats;
use App\survey\six_finance\SixFinanceRevenueTransferredResourcesCats;
use App\survey\six_finance\SixFinanceStaffCats;
use App\survey\six_finance\ZilaParishad;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DownloadPDFController extends Controller
{
    public function download_zp_ap_gp(Request $request, $six_finance_final_id,$emp_code){

        //$six_finance_final_id=$request->input('six_finance_final_id');

        //echo json_encode($six_finance_final_id);

        $matchArray=["six_finance_finals.id"=>$six_finance_final_id, "six_finance_finals.employee_code"=>$emp_code];

        $sixFinanceFinal=$this->getSixFinanceFinal($matchArray);

        if($sixFinanceFinal->basic_info!=1 || $sixFinanceFinal->staff_info!=1 || $sixFinanceFinal->revenue_info!=1 || $sixFinanceFinal->expenditure_info!=1 || $sixFinanceFinal->balance_info!=1 || $sixFinanceFinal->other_info!=1 || $sixFinanceFinal->five_year_info!=1){
            session()->flash('message', 'Please submit all sections of the form to download PDF. In case of delete request sent, wait for it to resolve or contact admin for more details');
            return redirect()->route('survey.six_finance.report.view_submitted_list');
        }

        if(!$sixFinanceFinal){
            session()->flash('message', 'Sorry PDF can not be download for this request. Please contact admin for more information.');
            return redirect()->route('survey.six_finance.report.view_submitted_list');
        }

        $acts= DB::table('acts')->where('id', '<=', 5)->get();
        $financial_years = Act::where('id', '>', 5)->select('id', 'financial_year')->get();

        $data=[
            "six_finance_final_id"=>$six_finance_final_id,
            "acts"=>$acts,
            "financial_years"=>$financial_years,
            "sixFinanceFinal"=>$sixFinanceFinal,
            "basicInfoFill"=>$this->getBasicInfo($six_finance_final_id),
            "staffInfos"=>$this->getStaffInfo($six_finance_final_id, $sixFinanceFinal->applicable_id, $acts),
            "revenueInfos"=>$this->getRevenueInfo($six_finance_final_id, $sixFinanceFinal->applicable_id),
            "expInfos"=>$this->getExpInfo($six_finance_final_id),
            "balanceInfos"=>$this->getBalanceInfo($six_finance_final_id),
            "otherInfos"=>$this->getOtherInfo($six_finance_final_id),
            "nextFiveYears"=>$this->getNextFiveYears($six_finance_final_id),
        ];

        $pdf = PDF::loadView('survey.six_finance.template.zp_ap_gp', $data);

        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //================================DOC NAME===============================================

        $doc_name="";

        if($sixFinanceFinal->zila_parishad_name) {

            $doc_name="(D)".str_replace(' ', '_', $sixFinanceFinal->district_name)."(ZP)".str_replace(' ', '_', $sixFinanceFinal->zila_parishad_name);
            if($sixFinanceFinal->anchalik_parishad_name) {
                $doc_name=$doc_name."(AP)".str_replace(' ', '_', $sixFinanceFinal->anchalik_parishad_name);
                if($sixFinanceFinal->gram_panchayat_name) {
                    $doc_name=$doc_name."(GP)".str_replace(' ', '_', $sixFinanceFinal->gram_panchayat_name);
                }
            }
        }

        if($sixFinanceFinal->applicable_id==1){
            $doc_name=$doc_name."(ZP)";
        }elseif($sixFinanceFinal->applicable_id==2){
            $doc_name=$doc_name."(AP)";
        }elseif($sixFinanceFinal->applicable_id==3){
            $doc_name=$doc_name."(GP)";
        }else{
            $doc_name=$doc_name."(Wrong)";
        }

        //========================================================================================
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


        return $pdf->download($doc_name.'.pdf');
    }

    public function getSixFinanceFinal($matchArray){
        $sixFinanceFinal=SixFinanceFinals::join('districts AS d', 'd.id', '=', 'six_finance_finals.district_id')
            ->join('applicables AS ap', 'ap.id','=','six_finance_finals.applicable_id')
            ->leftjoin('zila_parishads AS z', 'z.id', '=', 'six_finance_finals.zila_id')
            ->leftjoin('anchalik_parishads AS a', 'a.id', '=', 'six_finance_finals.anchalik_id')
            ->leftjoin('gram_panchyats AS g', 'g.gram_panchyat_id', '=', 'six_finance_finals.gram_panchayat_id')

            ->where($matchArray)
            ->select('six_finance_finals.*', 'ap.applicable_name', 'd.district_name', 'z.zila_parishad_name', 'a.anchalik_parishad_name', 'g.gram_panchayat_name')
            ->first();

        return $sixFinanceFinal;
    }

    /*-------------------------------- BASIC INFO ---------------------------------*/

    private function getBasicInfo($six_finance_final_id){
        $basicInfoFill= SixFinanceFormBasic::where([
            ['six_finance_final_id','=', $six_finance_final_id],
        ])->select('*')->first();


        return $basicInfoFill;
    }

    /*-------------------------------- STAFF INFO ---------------------------------*/

    private function getStaffInfo($six_finance_final_id,$applicable_id, $acts){
        /*$m= SixFinanceFormStaffs::where([
            ['six_finance_final_id','=', $six_finance_final_id],
        ])->select('*')->first();

        $m= DB::table('six_finance_staff_designations AS deg')
            ->join('six_finance_staff_designation_applicables AS da', 'da.six_finance_designation_id', '=', 'deg.id')
            ->leftjoin('six_finance_form_staff_details AS sd', 'sd.six_finance_staff_designation_id', '=', 'deg.id')
        ->where([
            ['six_finance_final_id','=', $six_finance_final_id],
            ['six_finance_staff_cat_id','=', $li->id],
        ])->select('*')->get();


        return $staffInfoFill=[
            "M"=>$m,
            "D"=>,
            "S"=>,
        ];*/

        $final_cats=[];
        $staffInfoDetailsFillFinal=[];
        $staffInfoSalaryFillFinal=[];

        $cats= SixFinanceStaffCats::select('six_finance_staff_cats.id', 'six_finance_staff_cats.category_name')
            ->get();

        foreach($cats AS $c_li){
            $designs = SixFinanceStaffCats::join('six_finance_staff_designations AS sd', 'sd.six_finance_staff_cat_id', '=', 'six_finance_staff_cats.id')
                ->join('six_finance_staff_designation_applicables AS sda', 'sda.six_finance_designation_id', '=', 'sd.id')
                ->where([
                    ['sda.applicable_id', '=', $applicable_id],
                    ['six_finance_staff_cats.id', '=', $c_li->id],
                    ['sd.is_active', '=', 1],
                ])
                ->distinct('sd.id')
                ->select('sd.id', 'sd.designation_name', 'sda.id AS priority')
                ->orderBy('priority', 'ASC')
                ->get();

            $final_cats[$c_li->id]= [
                'id'=>$c_li->id,
                'category_name'=>$c_li->category_name,
                'designations'=>$designs
            ];
        }

        //------------ WHEN SUBMITTED ------------------------

        $staffInfoFill=NULL;

            $staffInfoFill=SixFinanceFormStaffs::where([
                ['six_finance_final_id', '=', $six_finance_final_id],
            ])->select('*')->first();


            $staffInfoDetailsFill= SixFinanceFormStaffDetails::where([
                ['six_finance_final_id', '=', $six_finance_final_id],
                ['six_finance_form_staff_id', '=', $staffInfoFill->id],
            ])->select('*')->get();

            foreach($staffInfoDetailsFill AS $li_dts){
                $staffInfoDetailsFillFinal["C_".$li_dts->six_finance_staff_cat_id]["D_".$li_dts->six_finance_staff_designation_id]["SC"]=$li_dts->no_of_sanctioned_post;
                $staffInfoDetailsFillFinal["C_".$li_dts->six_finance_staff_cat_id]["D_".$li_dts->six_finance_staff_designation_id]["SP"]=$li_dts->scale_of_pay;
                $staffInfoDetailsFillFinal["C_".$li_dts->six_finance_staff_cat_id]["D_".$li_dts->six_finance_staff_designation_id]["CP"]=$li_dts->consolidated_pay;
                $staffInfoDetailsFillFinal["C_".$li_dts->six_finance_staff_cat_id]["D_".$li_dts->six_finance_staff_designation_id]["VP"]=$li_dts->vacant_post;
            }

            $staffInfoSalaryFill=SixFinanceFormStaffSalarySummaries::where([
                ['six_finance_final_id', '=', $six_finance_final_id],
                ['six_finance_form_staff_id', '=', $staffInfoFill->id],
            ])->select('*')->get();

            foreach($staffInfoSalaryFill AS $li_slry){
                $staffInfoSalaryFillFinal["C_".$li_slry->six_finance_staff_cat_id]["D_".$li_slry->six_finance_staff_designation_id]["A_".$li_slry->act_id]=$li_slry->salary;
            }

           return [
               'cats'=>$cats,
               'final_cats'=>$final_cats,
               'staffInfoFill'=>$staffInfoFill,
               'staffInfoDetailsFillFinal'=>$staffInfoDetailsFillFinal,
               'staffInfoSalaryFillFinal'=>$staffInfoSalaryFillFinal,
           ];
    }

    /*-------------------------------- REVENUE INFO ---------------------------------*/

    private function getRevenueInfo($six_finance_final_id,$applicable_id){
        $own_revenue_cats = SixFinanceRevenueOwnRevenueCats::where('is_active', 1)
            ->select('id', 'own_revenue_name')
            ->get();

        $css_shares = SixFinanceRevenueCssShareCats::where('is_active', 1)
            ->select('id', 'scheme_name')
            ->get();

        $tr_cats = SixFinanceRevenueTransferredResourcesCats::where([
            ['applicable_id', '=', $applicable_id],
            ['parent', '=', NULL],
            ['is_active', '=', 1],
        ])->orWhere([
            ['applicable_id', '=', NULL],
            ['parent', '=', NULL],
            ['is_active', '=', 1],
        ])->orderBy('id', 'asc')
            ->select('id', 'transferred_resource_cat_name', 'parent')
            ->get();

        $tr_cats_final=[];

        foreach($tr_cats AS $li_tr){

            $sublist = SixFinanceRevenueTransferredResourcesCats::where([
                ['parent', '=', $li_tr->id],
                ['is_active', '=', 1],
            ])->select('id', 'transferred_resource_cat_name')
                ->get();

            $data=[
                'id' => $li_tr->id,
                'transferred_resource_cat_name' => $li_tr->transferred_resource_cat_name,
                'parent' => $li_tr->parent,
                'sublist' => $sublist
            ];

            array_push($tr_cats_final, $data);
        }

        //------------ WHEN SUBMITTED ------------------------

        $revenueInfoOwnFillFinal=NULL;
        $revenueInfoArrearFillFinal=NULL;
        $revenueInfoShareFillFinal=NULL;
        $revenueInfoTRFillFinal=NULL;
        $alreadySubmitted=NULL;

            $revenueInfoOwnFill= SixFinanceFormRevenueOwnRevenues::where([
                ['six_finance_final_id', '=', $six_finance_final_id],
            ])->select('*')->get();

            foreach($revenueInfoOwnFill AS $li){
                $revenueInfoOwnFillFinal["O_".$li->six_finance_revenue_own_revenue_cat_id]["A_".$li->act_id]=$li->own_revenue_value;
            }

            $revenueInfoArrearFill=SixFinanceFormRevenueArrearTaxes::where([
                ['six_finance_final_id', '=', $six_finance_final_id],
            ])->select('*')->get();

            foreach($revenueInfoArrearFill AS $li){
                $revenueInfoArrearFillFinal["A_".$li->act_id]=$li->arrear_tax_value;
            }

            $revenueInfoShareFill= SixFinanceFormRevenueCssShares::where([
                ['six_finance_final_id', '=', $six_finance_final_id],
            ])->select('*')->get();

            foreach($revenueInfoShareFill AS $li){
                $revenueInfoShareFillFinal["S_".$li->share]["C_".$li->six_finance_revenue_css_share_cat_id]["A_".$li->act_id]=$li->share_value;
            }

            $revenueInfoTRFill= SixFinanceFormRevenueTransferredResources::where([
                ['six_finance_final_id', '=', $six_finance_final_id],
            ])->select('*')->get();

            foreach($revenueInfoTRFill AS $li){
                $revenueInfoTRFillFinal["C_".$li->six_finance_revenue_transferred_resources_cat_id]["A_".$li->act_id]=$li->tr_value;
            }

            return [
                "own_revenue_cats"=>$own_revenue_cats,
                "css_shares"=>$css_shares,
                "tr_cats_final"=>$tr_cats_final,
                "revenueInfoOwnFillFinal"=>$revenueInfoOwnFillFinal,
                "revenueInfoArrearFillFinal"=>$revenueInfoArrearFillFinal,
                "revenueInfoShareFillFinal"=>$revenueInfoShareFillFinal,
                "revenueInfoTRFillFinal"=>$revenueInfoTRFillFinal,
            ];
    }

    /*-------------------------------- EXPENDITURE INFO ---------------------------------*/

    private function getExpInfo($six_finance_final_id){
        $expenditure = ExpenditureCategory::join('category_expenditures', 'expenditure_categories.id', '=', 'category_expenditures.category_id')
            ->join('expenditures', 'expenditures.id', '=', 'category_expenditures.expenditure_id')
            ->select('category_expenditures.id AS category_expenditure_id', 'expenditures.id AS expenditure', 'expenditure_categories.id AS category', 'expenditure_name', 'category_name')
            ->where([
                ['expenditure_categories.id', '!=', 2],
                ['is_active', '=', 1]
            ])
            ->get();

        $category = ExpenditureCategory::select('id', 'category_name', 'list_order')->orderBy('list_order')
            ->get();

        $dataFillFinal = NULL;

            $dataFill = FinancialExpenditure::where([
                ['six_finance_final_id', '=', $six_finance_final_id]
            ])->select('*')->get();

            foreach ($dataFill AS $li) {
                $dataFillFinal["E_" . $li->expenditure_id]["A_" . $li->act_id] = $li->expenditure_cost;
            }

        return [
            "expenditure"=>$expenditure,
            "category"=>$category,
            "dataFillFinal"=>$dataFillFinal,
        ];
    }

    /*-------------------------------- BALANCE INFO ---------------------------------*/

    private function getBalanceInfo($six_finance_final_id){
        $dataFillFinal=[];
        $data=FinancialBalance::where([
            'six_finance_final_id' => $six_finance_final_id
        ])->select('act_id', 'opening_balance', 'inflow_balance', 'outflow_balance', 'closing_balance')
          ->get();

        foreach ($data AS $li) {
            $dataFillFinal["Op_A_" . $li->act_id] = $li->opening_balance;
            $dataFillFinal["In_A_" . $li->act_id] = $li->inflow_balance;
            $dataFillFinal["Ou_A_" . $li->act_id] = $li->outflow_balance;
            $dataFillFinal["Cl_A_" . $li->act_id] = $li->closing_balance;
        }

        return $dataFillFinal;
    }

    /*-------------------------------- OTHER INFO -------------------------------------*/

    private function getOtherInfo($six_finance_final_id){
        $cats= SixFinanceOtherInfoRoadCats::select('*')->get();

        $register_cats= SixFinanceOtherInfoRegisterCats::where('is_active', '=', 1)->select('*')->get();

        $otherFinalReg=[];
        $otherFinalSub=[];

            $otherInfoVal=SixFinanceFormOthers::where([
                ['six_finance_final_id', '=', $six_finance_final_id],
            ])->select('*')->first();

            $otherSubVal=SixFinanceFormOtherSubs::where([
                ['six_finance_final_id', '=', $six_finance_final_id],
                ['six_finance_form_other_id', '=', $otherInfoVal->id],
            ])->select('*')->get();

            $otherRegVal=SixFinanceFormOtherRegisters::where([
                ['six_finance_final_id', '=', $six_finance_final_id],
                ['six_finance_form_other_id', '=', $otherInfoVal->id],
            ])->select('*')->get();

            foreach ($otherSubVal AS $val){
                $otherFinalSub["SIX_".$val->six_finance_final_id]["A_".$val->act_id]["C_".$val->six_finance_other_info_road_cat_id]=$val->length;
            }

            foreach($otherRegVal AS $reg){
                $otherFinalReg["R_".$reg->six_finance_other_info_register_cat_id]=$reg->register_value;
            }

        return [
            'cats'=>$cats,
            'register_cats'=>$register_cats,
            'otherInfoVal'=>$otherInfoVal,
            'otherFinalSub'=>$otherFinalSub,
            'otherFinalReg'=>$otherFinalReg,
        ];
    }

    /*--------------------------------NEXT FIVE YEARS---------------------------------*/

    private function getNextFiveYears($six_finance_final_id){
        $entities = ProposalEntity::select('id', 'entity_name')->where([
            'is_active' => 1
        ])->get();

        $dataFillFinal = NULL;

        $count = SchemeProposal::where([
            'six_finance_final_id' => $six_finance_final_id
        ])->count();

        if ($count > 0) {
            $dataFill = SchemeProposal::where([
                ['six_finance_final_id', '=', $six_finance_final_id]
            ])->select('*')->get();

            foreach ($dataFill AS $li) {
                $dataFillFinal["E_" . $li->entity_id]["A_" . $li->act_id] = $li->estimated_cost;
            }
        }


        return [
            "entities"=>$entities,
            "nextFiveYearsFill"=>$dataFillFinal,
        ];
    }


    //====================================================================================================
    //===================================== DOWNLOAD COMBINED REPORT ==========================
    //====================================================================================================

    public function downloadCombined(Request $request, $req_for){

        $inputArrayFinal=[];
        $reportNames=[];

        if($req_for=="COMBINED_ZP"){
            $applicable_id=1;
            $inputArray=ZilaParishad::join('six_finance_finals AS f', 'f.zila_id', '=', 'zila_parishads.id')
                ->where([
                    ['f.final_submission_status', '=', 1],
                    ['f.applicable_id', '=', $applicable_id],
                ])
                ->select('f.id', 'zila_parishads.zila_parishad_name')
                ->get();

            foreach ($inputArray AS $in){
                array_push($inputArrayFinal, $in->id);
                array_push($reportNames, $in->zila_parishad_name);
            }

            $inputArrayCount=count($inputArrayFinal);

            $app=DB::table('applicables')->where('id',$applicable_id)->first();
            $applicable_name=$app->applicable_name;

            $reportName="COMBINED REPORT OF ZILA PARISHAD";

        }elseif($req_for=="COMBINED_AP"){

            $applicable_id=2;
            $inputArray=AnchalikParishad::join('six_finance_finals AS f', 'f.anchalik_id', '=', 'anchalik_parishads.id')
                ->where([
                    ['f.final_submission_status', '=', 1],
                    ['f.applicable_id', '=', $applicable_id],
                ])
                ->select('f.id')
                ->get();

            foreach ($inputArray AS $in){
                array_push($inputArrayFinal, $in->id);
            }

            $inputArray1=AnchalikParishad::join('zila_parishads', 'zila_parishads.id', '=', 'anchalik_parishads.zila_id')
                ->join('six_finance_finals AS f', 'f.anchalik_id', '=', 'anchalik_parishads.id')
                ->where([
                    ['f.final_submission_status', '=', 1],
                    ['f.applicable_id', '=', $applicable_id],
                ])
                ->select('zila_parishads.id', 'zila_parishads.zila_parishad_name')
                ->distinct('zila_parishads.id')
                ->get();

            foreach ($inputArray1 AS $in){
                array_push($reportNames, $in->zila_parishad_name);
            }

            $inputArrayCount=count($inputArrayFinal);

            $app=DB::table('applicables')->where('id',$applicable_id)->first();
            $applicable_name=$app->applicable_name;

            $reportName="COMBINED REPORT OF ANCHALIK PANCHAYAT";

        }elseif($req_for=="COMBINED_GP"){
            $applicable_id=3;
            $inputArray=GramPanchyat::join('six_finance_finals AS f', 'f.gram_panchayat_id', '=', 'gram_panchyats.gram_panchyat_id')
                ->where([
                    ['f.final_submission_status', '=', 1],
                    ['f.applicable_id', '=', $applicable_id],
                ])
                ->select('f.id')
                ->get();

            foreach ($inputArray AS $in){
                array_push($inputArrayFinal, $in->id);
            }

            $inputArray1=GramPanchyat::join('anchalik_parishads AS a', 'a.id', '=', 'gram_panchyats.anchalik_id')
                ->join('zila_parishads', 'zila_parishads.id', '=', 'a.zila_id')
                ->join('six_finance_finals AS f', 'f.gram_panchayat_id', '=', 'gram_panchyats.gram_panchyat_id')
                ->where([
                    ['f.final_submission_status', '=', 1],
                    ['f.applicable_id', '=', $applicable_id],
                ])
                ->select('zila_parishads.id', 'zila_parishads.zila_parishad_name')
                ->distinct('zila_parishads.id')
                ->get();

            foreach ($inputArray1 AS $in){
                array_push($reportNames, $in->zila_parishad_name);
            }

            $inputArrayCount=count($inputArrayFinal);

            $app=DB::table('applicables')->where('id',$applicable_id)->first();
            $applicable_name=$app->applicable_name;

            $reportName="COMBINED REPORT OF GRAM PANCHAYAT";
        }elseif($req_for=="DWC_AP"){

        }

        if(empty($inputArray)){
            session()->flash('message', 'Sorry PDF can not be download for this request. Please contact admin for more information.');
            return redirect()->route('admin.survey.six_finance.track_zp_ap_gp');
        }

        $acts= DB::table('acts')->where('id', '<=', 5)->get();
        $financial_years = Act::where('id', '>', 5)->select('id', 'financial_year')->get();

        $data=[
            "acts"=>$acts,
            "req_for"=>$req_for,
            "financial_years"=>$financial_years,
            "applicable_name"=>$applicable_name,
            "reportName"=>$reportName,
            "reportNames"=>$reportNames,
            "basicInfoFill"=>$this->getBasicInfoCombined($inputArrayFinal),
            "staffInfos"=>$this->getStaffInfoCombined($inputArrayFinal, $applicable_id, $acts),
            "revenueInfos"=>$this->getRevenueInfoCombined($inputArrayFinal, $applicable_id),
            "expInfos"=>$this->getExpInfoCombined($inputArrayFinal),
            "balanceInfos"=>$this->getBalanceInfoCombined($inputArrayFinal),
            "otherInfos"=>$this->getOtherInfoCombined($inputArrayFinal, $applicable_id),
            "nextFiveYears"=>$this->getNextFiveYearsCombined($inputArrayFinal),
        ];

        $pdf = PDF::loadView('admin.survey.six_finance.template.zp_ap_gp', $data);

        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //================================DOC NAME===============================================

        $doc_name= $req_for."(".now()->format('d-M-Y').")";

        //========================================================================================
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


        return $pdf->download($doc_name.'.pdf');
    }


    //====================================================================================================
    //===================================== DISTRICT WISE COMBINED REPORT ==========================
    //====================================================================================================

    public function download_distCombined(Request $request){

        $inputArrayFinal=[];
        $reportNames=[];

        $req_for=$request->input('req_for');
        $d_id=$request->input('d_id');

        $districtName=ZilaParishad::join('districts AS d', 'd.id', '=', 'zila_parishads.district_id')
            ->where([
                ['d.id', '=', $d_id],
            ])
            ->select('zila_parishad_name')
            ->first()->zila_parishad_name;


        if($req_for=="DWC_ZP"){

            $applicable_id=1;
            $inputArray=ZilaParishad::join('six_finance_finals AS f', 'f.zila_id', '=', 'zila_parishads.id')
                ->where([
                    ['f.final_submission_status', '=', 1],
                    ['f.applicable_id', '=', $applicable_id],
                    ['f.district_id', '=', $d_id],
                ])
                ->select('f.id')
                ->get();

            foreach ($inputArray AS $in){
                array_push($inputArrayFinal, $in->id);
            }

            $inputArray1=ZilaParishad::join('six_finance_finals AS f', 'f.zila_id', '=', 'zila_parishads.id')
                ->where([
                    ['f.final_submission_status', '=', 1],
                    ['f.applicable_id', '=', $applicable_id],
                    ['f.district_id', '=', $d_id],
                ])
                ->select('zila_parishads.id', 'zila_parishads.zila_parishad_name')
                ->distinct('zila_parishads.id')
                ->get();

            foreach ($inputArray1 AS $in){
                array_push($reportNames, $in->zila_parishad_name);
            }

            $inputArrayCount=count($inputArrayFinal);

            $app=DB::table('applicables')->where('id',$applicable_id)->first();
            $applicable_name=$app->applicable_name;

            $reportName=$districtName." CONSOLIDATED REPORT OF ZILA PANCHAYAT";

        }elseif($req_for=="DWC_AP"){

            $applicable_id=2;
            $inputArray=AnchalikParishad::join('six_finance_finals AS f', 'f.anchalik_id', '=', 'anchalik_parishads.id')
                ->where([
                    ['f.final_submission_status', '=', 1],
                    ['f.applicable_id', '=', $applicable_id],
                    ['f.district_id', '=', $d_id],
                ])
                ->select('f.id')
                ->get();

            foreach ($inputArray AS $in){
                array_push($inputArrayFinal, $in->id);
            }

            $inputArray1=AnchalikParishad::join('zila_parishads', 'zila_parishads.id', '=', 'anchalik_parishads.zila_id')
                ->join('six_finance_finals AS f', 'f.anchalik_id', '=', 'anchalik_parishads.id')
                ->where([
                    ['f.final_submission_status', '=', 1],
                    ['f.applicable_id', '=', $applicable_id],
                    ['f.district_id', '=', $d_id],
                ])
                ->select('anchalik_parishads.id', 'anchalik_parishads.anchalik_parishad_name')
                ->distinct('anchalik_parishads.id')
                ->get();

            foreach ($inputArray1 AS $in){
                array_push($reportNames, $in->anchalik_parishad_name);
            }

            $inputArrayCount=count($inputArrayFinal);

            $app=DB::table('applicables')->where('id',$applicable_id)->first();
            $applicable_name=$app->applicable_name;

            $reportName=$districtName." COMBINED REPORT OF ANCHALIK PANCHAYAT";

        }elseif($req_for=="DWC_GP"){

            $applicable_id=3;
            $inputArray=GramPanchyat::join('six_finance_finals AS f', 'f.gram_panchayat_id', '=', 'gram_panchyats.gram_panchyat_id')
                ->where([
                    ['f.final_submission_status', '=', 1],
                    ['f.applicable_id', '=', $applicable_id],
                    ['f.district_id', '=', $d_id],
                ])
                ->select('f.id')
                ->get();

            foreach ($inputArray AS $in){
                array_push($inputArrayFinal, $in->id);
            }

            $inputArray1=GramPanchyat::join('anchalik_parishads AS a', 'a.id', '=', 'gram_panchyats.anchalik_id')
                ->join('zila_parishads', 'zila_parishads.id', '=', 'a.zila_id')
                ->join('six_finance_finals AS f', 'f.gram_panchayat_id', '=', 'gram_panchyats.gram_panchyat_id')
                ->where([
                    ['f.final_submission_status', '=', 1],
                    ['f.applicable_id', '=', $applicable_id],
                    ['f.district_id', '=', $d_id],
                ])
                ->select('a.anchalik_parishad_name', DB::raw('COUNT(*) AS count_gps'))
                ->groupBy('a.anchalik_parishad_name')
                ->get();

            foreach ($inputArray1 AS $in){
                array_push($reportNames, $in->anchalik_parishad_name."(GP COUNT: ".$in->count_gps.")");
            }

            $inputArrayCount=count($inputArrayFinal);

            $app=DB::table('applicables')->where('id',$applicable_id)->first();
            $applicable_name=$app->applicable_name;

            $reportName=$districtName." COMBINED REPORT OF GRAM PANCHAYAT";
        }

        if(empty($inputArray)){
            session()->flash('message', 'Sorry PDF can not be download for this request. Please contact admin for more information.');
            return redirect()->route('survey.six_finance.report_district_wise');
        }

        $acts= DB::table('acts')->where('id', '<=', 5)->get();
        $financial_years = Act::where('id', '>', 5)->select('id', 'financial_year')->get();

        $data=[
            "acts"=>$acts,
            "req_for"=>$req_for,
            "financial_years"=>$financial_years,
            "applicable_name"=>$applicable_name,
            "reportName"=>$reportName,
            "reportNames"=>$reportNames,
            "basicInfoFill"=>$this->getBasicInfoCombined($inputArrayFinal),
            "staffInfos"=>$this->getStaffInfoCombined($inputArrayFinal, $applicable_id, $acts),
            "revenueInfos"=>$this->getRevenueInfoCombined($inputArrayFinal, $applicable_id),
            "expInfos"=>$this->getExpInfoCombined($inputArrayFinal),
            "balanceInfos"=>$this->getBalanceInfoCombined($inputArrayFinal),
            "otherInfos"=>$this->getOtherInfoCombined($inputArrayFinal, $applicable_id, $req_for),
            "nextFiveYears"=>$this->getNextFiveYearsCombined($inputArrayFinal),
        ];

        $pdf = PDF::loadView('admin.survey.six_finance.template.zp_ap_gp', $data);

        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //================================DOC NAME===============================================

        $doc_name= $req_for."-".$districtName."(".now()->format('d-M-Y').")";

        //========================================================================================
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


        return $pdf->download($doc_name.'.pdf');
    }


    /*-------------------------------- COMBINED NEXT FIVE YEARS---------------------------------*/

    private function getNextFiveYearsCombined($inputArray){
        $entities = ProposalEntity::select('id', 'entity_name')->where([
            'is_active' => 1
        ])->get();

        $dataFillFinal = NULL;

        $count = SchemeProposal::whereIn('six_finance_final_id', $inputArray)->count();

        if ($count > 0) {
            $dataFill = SchemeProposal::whereIn('six_finance_final_id', $inputArray)
                ->selectRaw('entity_id, act_id, sum(estimated_cost) AS estimated_cost')
                ->groupBy('entity_id', 'act_id')
                ->get();

            foreach ($dataFill AS $li) {
                $dataFillFinal["E_" . $li->entity_id]["A_" . $li->act_id] = round($li->estimated_cost/100000,2);
            }
        }
        //echo json_encode($dataFill);
        return [
            "entities"=>$entities,
		
            "nextFiveYearsFill"=>$dataFillFinal,
        ];
    }

    /*-------------------------------- COMBINED OTHER INFO -------------------------------------*/

    private function getOtherInfoCombined($inputArray, $applicable_id, $req_for=NULL){

        $cats= SixFinanceOtherInfoRoadCats::select('*')->get();

        $register_cats= SixFinanceOtherInfoRegisterCats::where('is_active', '=', 1)->select('*')->get();

        $otherInfoVal=[];
        $otherFinalReg=[];
        $otherFinalSub=[];
        $otherInfoAuditStatus=[];

        $otherInfoValTr=SixFinanceFormOthers::whereIn('six_finance_final_id', $inputArray)
            ->where([
                ['trained_account_staff', '=', 2]
            ])->count();

        $otherInfoValSep=SixFinanceFormOthers::whereIn('six_finance_final_id', $inputArray)
            ->where([
                ['seperate_cashbook_maintained', '=', 2]
            ])->count();

        if($applicable_id==1){
            $otherInfoAuditStatus=SixFinanceFormOthers::join('six_finance_finals AS f', 'f.id', '=', 'six_finance_form_others.six_finance_final_id')
                ->join('districts AS d', 'd.id', '=', 'f.district_id')
                ->whereIn('six_finance_final_id', $inputArray)
                ->select('d.district_name AS req_name', 'six_finance_form_others.present_account_audit_status')
                ->orderBy('d.district_name', 'ASC')
                ->get();

        }elseif ($req_for=="DWC_AP" && $applicable_id==2){
            $otherInfoAuditStatus=SixFinanceFormOthers::join('six_finance_finals AS f', 'f.id', '=', 'six_finance_form_others.six_finance_final_id')
                ->join('districts AS d', 'd.id', '=', 'f.district_id')
                ->join('anchalik_parishads AS a', 'f.anchalik_id', '=', 'a.id')
                ->whereIn('six_finance_final_id', $inputArray)
                ->select('a.anchalik_parishad_name AS req_name', 'six_finance_form_others.present_account_audit_status')
                ->orderBy('a.anchalik_parishad_name', 'ASC')
                ->get();
        }

        $otherInfoVal['TR_Y']=(int)count($inputArray)-(int)$otherInfoValTr;
        $otherInfoVal['TR_N']=(int)$otherInfoValTr;
        $otherInfoVal['SP_Y']=(int)count($inputArray)-(int)$otherInfoValSep;
        $otherInfoVal['SP_N']=(int)$otherInfoValSep;

        //echo json_encode($otherInfoVal);

        $otherSubVal=SixFinanceFormOtherSubs::whereIn('six_finance_final_id', $inputArray)
            ->selectRaw('act_id, six_finance_other_info_road_cat_id, sum(length) AS length')
            ->groupBy('act_id', 'six_finance_other_info_road_cat_id')->get();

        $otherRegValTotal=SixFinanceFormOtherRegisters::whereIn('six_finance_final_id', $inputArray)
            ->selectRaw('six_finance_other_info_register_cat_id, count(register_value) AS register_value_total')
            ->groupBy('six_finance_other_info_register_cat_id')
            ->get();

        $otherRegVal=SixFinanceFormOtherRegisters::whereIn('six_finance_final_id', $inputArray)
            ->where([
                ['register_value', '=', 2]
            ])
            ->selectRaw('six_finance_other_info_register_cat_id, count(register_value) AS register_value')
            ->groupBy('six_finance_other_info_register_cat_id')
            ->get();

        foreach ($otherSubVal AS $val){
            $otherFinalSub["A_".$val->act_id]["C_".$val->six_finance_other_info_road_cat_id]=round($val->length,2);
        }

        foreach($otherRegValTotal AS $reg){
            $otherFinalReg["R_Y".$reg->six_finance_other_info_register_cat_id]=$reg->register_value_total;
            $otherFinalReg["R_N".$reg->six_finance_other_info_register_cat_id]=0;
        }

        foreach($otherRegVal AS $reg){
            $otherFinalReg["R_Y".$reg->six_finance_other_info_register_cat_id]= (int)$otherFinalReg["R_Y".$reg->six_finance_other_info_register_cat_id]-(int)$reg->register_value;
            $otherFinalReg["R_N".$reg->six_finance_other_info_register_cat_id]= $reg->register_value;
        }

        //echo json_encode($otherFinalReg);
        return [
            'cats'=>$cats,
            'register_cats'=>$register_cats,
            'otherInfoVal'=>$otherInfoVal,
            'otherFinalSub'=>$otherFinalSub,
            'otherFinalReg'=>$otherFinalReg,
            'otherInfoAuditStatus'=>$otherInfoAuditStatus
        ];
    }

    /*-------------------------------- COMBINED BALANCE INFO -----------------------------------*/

    private function getBalanceInfoCombined($inputArray){
        $dataFillFinal=[];
        $data=FinancialBalance::whereIn('six_finance_final_id', $inputArray)
            ->selectRaw('act_id, sum(opening_balance) AS opening_balance, sum(inflow_balance) AS inflow_balance, sum(outflow_balance) AS outflow_balance, sum(closing_balance) AS closing_balance')
            ->groupBy('act_id')
            ->get();

        foreach ($data AS $li) {
            $dataFillFinal["Op_A_" . $li->act_id] = round($li->opening_balance,2);
            $dataFillFinal["In_A_" . $li->act_id] = round($li->inflow_balance,2);
            $dataFillFinal["Ou_A_" . $li->act_id] = round($li->outflow_balance,2);
            $dataFillFinal["Cl_A_" . $li->act_id] = round($li->closing_balance,2);
        }

        //echo json_encode($dataFillFinal);

        return $dataFillFinal;
    }

    /*-------------------------------- EXPENDITURE INFO ---------------------------------*/

    private function getExpInfoCombined($inputArray){

        $expenditure = ExpenditureCategory::join('category_expenditures', 'expenditure_categories.id', '=', 'category_expenditures.category_id')
            ->join('expenditures', 'expenditures.id', '=', 'category_expenditures.expenditure_id')
            ->select('category_expenditures.id AS category_expenditure_id', 'expenditures.id AS expenditure', 'expenditure_categories.id AS category', 'expenditure_name', 'category_name')
            ->where([
                ['expenditure_categories.id', '!=', 2],
                ['is_active', '=', 1]
            ])
            ->get();

        $category = ExpenditureCategory::select('id', 'category_name', 'list_order')
            ->orderBy('list_order')
            ->get();

        $dataFillFinal = NULL;

        $dataFill = FinancialExpenditure::whereIn('six_finance_final_id', $inputArray)
            ->selectRaw('act_id, expenditure_id, sum(expenditure_cost) AS expenditure_cost')
            ->groupBy('act_id', 'expenditure_id')
            ->get();

        foreach ($dataFill AS $li) {
            $dataFillFinal["E_" . $li->expenditure_id]["A_" . $li->act_id] = round($li->expenditure_cost,2);
        }

        return [
            "expenditure"=>$expenditure,
            "category"=>$category,
            "dataFillFinal"=>$dataFillFinal,
        ];
    }

    /*-------------------------------- REVENUE INFO ---------------------------------*/

    private function getRevenueInfoCombined($inputArray, $applicable_id){

        $own_revenue_cats = SixFinanceRevenueOwnRevenueCats::where('is_active', 1)
            ->select('id', 'own_revenue_name')
            ->get();

        $css_shares = SixFinanceRevenueCssShareCats::where('is_active', 1)
            ->select('id', 'scheme_name')
            ->get();

        $tr_cats = SixFinanceRevenueTransferredResourcesCats::where([
            ['applicable_id', '=', $applicable_id],
            ['parent', '=', NULL],
            ['is_active', '=', 1],
        ])->orWhere([
            ['applicable_id', '=', NULL],
            ['parent', '=', NULL],
            ['is_active', '=', 1],
        ])->orderBy('id', 'asc')
            ->select('id', 'transferred_resource_cat_name', 'parent')
            ->get();

        $tr_cats_final=[];

        foreach($tr_cats AS $li_tr){

            $sublist = SixFinanceRevenueTransferredResourcesCats::where([
                ['parent', '=', $li_tr->id],
                ['is_active', '=', 1],
            ])->select('id', 'transferred_resource_cat_name')
                ->get();

            $data=[
                'id' => $li_tr->id,
                'transferred_resource_cat_name' => $li_tr->transferred_resource_cat_name,
                'parent' => $li_tr->parent,
                'sublist' => $sublist
            ];

            array_push($tr_cats_final, $data);
        }

        //------------ WHEN SUBMITTED ------------------------

        $revenueInfoOwnFillFinal=NULL;
        $revenueInfoArrearFillFinal=NULL;
        $revenueInfoShareFillFinal=NULL;
        $revenueInfoTRFillFinal=NULL;
        $alreadySubmitted=NULL;

        $revenueInfoOwnFill= SixFinanceFormRevenueOwnRevenues::whereIn('six_finance_final_id', $inputArray)
            ->selectRaw('act_id, six_finance_revenue_own_revenue_cat_id, sum(own_revenue_value) AS own_revenue_value')
            ->groupBy('act_id', 'six_finance_revenue_own_revenue_cat_id')
            ->get();

        foreach($revenueInfoOwnFill AS $li){
            $revenueInfoOwnFillFinal["O_".$li->six_finance_revenue_own_revenue_cat_id]["A_".$li->act_id]=round($li->own_revenue_value,2);
        }

        $revenueInfoArrearFill=SixFinanceFormRevenueArrearTaxes::whereIn('six_finance_final_id', $inputArray)
            ->selectRaw('act_id, sum(arrear_tax_value) AS arrear_tax_value')
            ->groupBy('act_id')
            ->get();

        foreach($revenueInfoArrearFill AS $li){
            $revenueInfoArrearFillFinal["A_".$li->act_id]=round($li->arrear_tax_value,2);
        }

        $revenueInfoShareFill= SixFinanceFormRevenueCssShares::whereIn('six_finance_final_id', $inputArray)
            ->selectRaw('act_id, share, six_finance_revenue_css_share_cat_id, sum(share_value) AS share_value')
            ->groupBy('act_id', 'share', 'six_finance_revenue_css_share_cat_id')
            ->get();

        foreach($revenueInfoShareFill AS $li){
            $revenueInfoShareFillFinal["S_".$li->share]["C_".$li->six_finance_revenue_css_share_cat_id]["A_".$li->act_id]=round($li->share_value,2);
        }

        $revenueInfoTRFill= SixFinanceFormRevenueTransferredResources::whereIn('six_finance_final_id', $inputArray)
            ->selectRaw('act_id, six_finance_revenue_transferred_resources_cat_id, sum(tr_value) AS tr_value')
            ->groupBy('act_id', 'six_finance_revenue_transferred_resources_cat_id')
            ->get();

        foreach($revenueInfoTRFill AS $li){
            $revenueInfoTRFillFinal["C_".$li->six_finance_revenue_transferred_resources_cat_id]["A_".$li->act_id]=round($li->tr_value,2);
        }

        return [
            "own_revenue_cats"=>$own_revenue_cats,
            "css_shares"=>$css_shares,
            "tr_cats_final"=>$tr_cats_final,
            "revenueInfoOwnFillFinal"=>$revenueInfoOwnFillFinal,
            "revenueInfoArrearFillFinal"=>$revenueInfoArrearFillFinal,
            "revenueInfoShareFillFinal"=>$revenueInfoShareFillFinal,
            "revenueInfoTRFillFinal"=>$revenueInfoTRFillFinal,
        ];
    }

    /*-------------------------------- STAFF INFO ---------------------------------*/

    private function getStaffInfoCombined($inputArray, $applicable_id, $acts){

        $final_cats=[];
        $staffInfoDetailsFillFinal=[];
        $staffInfoSalaryFillFinal=[];

        $cats= SixFinanceStaffCats::select('six_finance_staff_cats.id', 'six_finance_staff_cats.category_name')
            ->get();

        foreach($cats AS $c_li){
            $designs = SixFinanceStaffCats::join('six_finance_staff_designations AS sd', 'sd.six_finance_staff_cat_id', '=', 'six_finance_staff_cats.id')
                ->join('six_finance_staff_designation_applicables AS sda', 'sda.six_finance_designation_id', '=', 'sd.id')
                ->where([
                    ['sda.applicable_id', '=', $applicable_id],
                    ['six_finance_staff_cats.id', '=', $c_li->id],
                    ['sd.is_active', '=', 1],
                ])
                ->distinct('sd.id')
                ->select('sd.id', 'sd.designation_name', 'sda.id AS priority')
                ->orderBy('priority', 'ASC')
                ->get();

            $final_cats[$c_li->id]= [
                'id'=>$c_li->id,
                'category_name'=>$c_li->category_name,
                'designations'=>$designs
            ];
        }

        //------------ WHEN SUBMITTED ------------------------

        $staffInfoFill=NULL;

        $staffInfoFill=SixFinanceFormStaffs::whereIn('six_finance_final_id', $inputArray)
            ->selectRaw('sum(arrear_salary) AS arrear_salary, sum(number_of_muster_roll_fixed_pay_emp) AS number_of_muster_roll_fixed_pay_emp')
            ->get();


        $staffInfoDetailsFill= SixFinanceFormStaffDetails::whereIn('six_finance_final_id', $inputArray)
            ->selectRaw('six_finance_staff_cat_id, six_finance_staff_designation_id, sum(no_of_sanctioned_post) AS no_of_sanctioned_post, sum(vacant_post) AS vacant_post, sum(consolidated_pay) AS consolidated_pay')
            ->groupBy('six_finance_staff_cat_id', 'six_finance_staff_designation_id')
            ->get();

        foreach($staffInfoDetailsFill AS $li_dts){
            $staffInfoDetailsFillFinal["C_".$li_dts->six_finance_staff_cat_id]["D_".$li_dts->six_finance_staff_designation_id]["SC"]=$li_dts->no_of_sanctioned_post;
            $staffInfoDetailsFillFinal["C_".$li_dts->six_finance_staff_cat_id]["D_".$li_dts->six_finance_staff_designation_id]["SP"]="--";//$li_dts->scale_of_pay;
            $staffInfoDetailsFillFinal["C_".$li_dts->six_finance_staff_cat_id]["D_".$li_dts->six_finance_staff_designation_id]["CP"]=round($li_dts->consolidated_pay,2);
            $staffInfoDetailsFillFinal["C_".$li_dts->six_finance_staff_cat_id]["D_".$li_dts->six_finance_staff_designation_id]["VP"]=$li_dts->vacant_post;
        }

        $staffInfoSalaryFill=SixFinanceFormStaffSalarySummaries::whereIn('six_finance_final_id', $inputArray)
            ->selectRaw('six_finance_staff_cat_id, six_finance_staff_designation_id, act_id, sum(salary) AS salary')
            ->groupBy('six_finance_staff_cat_id', 'six_finance_staff_designation_id', 'act_id')
            ->get();

        foreach($staffInfoSalaryFill AS $li_slry){
            $staffInfoSalaryFillFinal["C_".$li_slry->six_finance_staff_cat_id]["D_".$li_slry->six_finance_staff_designation_id]["A_".$li_slry->act_id]=round($li_slry->salary,2);
        }

        return [
            'cats'=>$cats,
            'final_cats'=>$final_cats,
            'staffInfoFill'=>$staffInfoFill,
            'staffInfoDetailsFillFinal'=>$staffInfoDetailsFillFinal,
            'staffInfoSalaryFillFinal'=>$staffInfoSalaryFillFinal,
        ];
    }

    /*-------------------------------- BASIC INFO ---------------------------------*/

    private function getBasicInfoCombined($inputArray){
        $basicInfoFill= SixFinanceFormBasic::whereIn('six_finance_final_id', $inputArray)
            ->selectRaw('sum(app_area) AS app_area, sum(app_house_nos) AS app_house_nos,
            sum(app_monthly_rent) AS app_monthly_rent, sum(pop_male) AS pop_male, sum(pop_female) AS pop_female, sum(pop_sc) AS pop_sc, 
            sum(pop_st) AS pop_st, sum(pop_total) AS pop_total
            ')->get();

        $basicInfoFillHouseRentedY= SixFinanceFormBasic::whereIn('six_finance_final_id', $inputArray)
            ->where('app_household_rented','=', 1)
            ->count();

        $basicInfoFillHouseRentedN= SixFinanceFormBasic::whereIn('six_finance_final_id', $inputArray)
            ->where('app_household_rented','=', 2)
            ->count();

        $basicInfoFill=$basicInfoFill[0];
        $basicInfoFill["h_r_y"]=$basicInfoFillHouseRentedY;
        $basicInfoFill["h_r_n"]=$basicInfoFillHouseRentedN;
        return $basicInfoFill;
    }


}
