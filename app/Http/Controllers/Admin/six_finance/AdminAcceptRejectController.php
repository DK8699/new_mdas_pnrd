<?php

namespace App\Http\Controllers\Admin\six_finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ExpenditureModels\Expenditure;
use App\NewSchemeModels\ProposalEntity;
use App\survey\six_finance\SixFinanceStaffDesignations;
use App\survey\six_finance\SixFinanceRevenueOwnRevenueCats;
use App\survey\six_finance\SixFinanceRevenueTransferredResourcesCats;
use App\survey\six_finance\SixFinanceRevenueCssShareCats;
use DB;

class AdminAcceptRejectController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'admin_mdas']);
    }

    public function index() {
        return view('admin.survey.six_finance.request_categories');
    }

    public function expenditureIndex($category) {
        $request = [];
        
        if ($category == 1) {
            $request = Expenditure::join('category_expenditures', 'expenditures.id', '=', 'category_expenditures.expenditure_id')
                    ->join('expenditure_categories', 'expenditure_categories.id', '=', 'category_expenditures.category_id')
                    ->select('category_name', 'expenditure_name', 'category_id', 'expenditure_id', 'employee_code','is_active','cancel')
                    ->get();
        } else if ($category == 2) {
            $request = ProposalEntity::select('id', 'entity_name', 'employee_code','is_active','cancel')
                    ->get();
        }
        else if($category == 3){
            $request = SixFinanceStaffDesignations::rightjoin('six_finance_staff_cats','six_finance_staff_designations.six_finance_staff_cat_id','=','six_finance_staff_cats.id')
                    ->select('category_name','designation_name','six_finance_staff_designations.id','created_by','is_active','cancel')
                    ->get();
        }
        else if($category == 4){
            $request = SixFinanceRevenueOwnRevenueCats::select('id','own_revenue_name','created_by','is_active','cancel')
                    ->get();
        }
        else if($category == 5){
            $request = SixFinanceRevenueTransferredResourcesCats::join('applicables','six_finance_revenue_transferred_resources_cats.applicable_id','=','applicables.id')
                    ->select('six_finance_revenue_transferred_resources_cats.id','applicable_name','transferred_resource_cat_name','created_by','is_active','cancel')
                    ->get();
        }
        else if($category == 6){
            $request = SixFinanceRevenueCssShareCats::select('id','scheme_name','created_by','is_active','cancel')
                    ->get();
        }
        return view('admin.survey.six_finance.employee_requests', compact('request', 'category'));
    }

    public function acceptRequest(Request $request) {
        $acceptStatus = 0;
        $message = "";
        DB::transaction(function()use(&$acceptStatus, $request, &$message) {
            if ($request->input('category_type') == 1) {
                $expenditure = Expenditure::where([
                            'id' => $request->input('category')
                        ])
                        ->first();
                $expenditure->is_active = 1;
                $acceptStatus = $expenditure->update();
                $message = 'You have successfully accepted te proposed expenditure.';
            }
            if ($request->input('category_type') == 2) {
                $proposal = ProposalEntity::where([
                            'id' => $request->input('category')
                        ])
                        ->first();
                $proposal->is_active = 1;
                $acceptStatus = $proposal->update();
                $message = "You have successfully accepted te proposed next 5 year proposal entity.";
            }
            if ($request->input('category_type') == 3) {
                $designation = SixFinanceStaffDesignations::where([
                            'id' => $request->input('category')
                        ])
                        ->first();
                $designation->is_active = 1;
                $acceptStatus = $designation->update();
                $message = "You have successfully accepted a newly proposed designation.";
            }
            if ($request->input('category_type') == 4) {
                $revenue = SixFinanceRevenueOwnRevenueCats::where([
                            'id' => $request->input('category')
                        ])
                        ->first();
                $revenue->is_active = 1;
                $acceptStatus = $revenue->update();
                $message = "You have successfully accepted a newly proposed designation.";
            }
            if ($request->input('category_type') == 5) {
                $revenue = SixFinanceRevenueTransferredResourcesCats::where([
                            'id' => $request->input('category')
                        ])
                        ->first();
                $revenue->is_active = 1;
                $acceptStatus = $revenue->update();
                $message = "You have successfully accepted a newly proposed Transferred Resource Categories.";
            }
            if ($request->input('category_type') == 6) {
                $revenue = SixFinanceRevenueCssShareCats::where([
                            'id' => $request->input('category')
                        ])
                        ->first();
                $revenue->is_active = 1;
                $acceptStatus = $revenue->update();
                $message = "You have successfully accepted a newly proposed Revenue CSS Share Category.";
            }
        });
        if ($acceptStatus > 0) {
            return response()->json([
                        'msgType' => TRUE,
                        'msg' => $message
            ]);
        }
    }

    public function rejectRequest(Request $request) {
        $rejectStatus = 0;
        $message = "";
        DB::transaction(function()use(&$rejectStatus, $request,&$message) {
            if ($request->input('category_type') == 1) {
                $expenditure = Expenditure::where([
                            'id' => $request->input('category')
                        ])
                        ->first();
                $expenditure->cancel = 1;
                $rejectStatus = $expenditure->update();
                $message = "You have successfully rejected the proposed expenditure.";
            }
            if ($request->input('category_type') == 2) {
                $proposal = ProposalEntity::where([
                            'id' => $request->input('category')
                        ])
                        ->first();
                $proposal->cancel = 1;
                $rejectStatus = $proposal->update();
                $message = "You have successfully rejected next 5 year proposal entity";
            }
            if ($request->input('category_type') == 3) {
                $designation = SixFinanceStaffDesignations::where([
                            'id' => $request->input('category')
                        ])
                        ->first();
                $designation->cancel = 1;
                $rejectStatus = $designation->update();
                $message = "You have successfully rejected a newly proposed designation.";
            }
            if ($request->input('category_type') == 4) {
                $revenue = SixFinanceRevenueOwnRevenueCats::where([
                            'id' => $request->input('category')
                        ])
                        ->first();
                $revenue->cancel = 1;
                $rejectStatus = $revenue->update();
                $message = "You have successfully rejected a newly proposed revenue category.";
            }
            if ($request->input('category_type') == 5) {
                $revenue = SixFinanceRevenueTransferredResourcesCats::where([
                            'id' => $request->input('category')
                        ])
                        ->first();
                $revenue->cancel = 1;
                $rejectStatus = $revenue->update();
                $message = "You have successfully rejected a newly proposed Revenue Transfered CSS category.";
            }
            if ($request->input('category_type') == 6) {
                $revenue = SixFinanceRevenueCssShareCats::where([
                            'id' => $request->input('category')
                        ])
                        ->first();
                $revenue->cancel = 1;
                $rejectStatus = $revenue->update();
                $message = "You have successfully rejected a newly proposed Revenue CSS Share category.";
            }
        });
        if ($rejectStatus > 0) {
            return response()->json([
                        'msgType' => TRUE,
                        'msg' => $message
   
            ]);
        }
    }

}
