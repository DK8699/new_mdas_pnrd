<?php

namespace App\Http\Controllers\Admin\six_finance;

use App\DataTables\AdminSixFinanceDeleteRequestDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminSixFinanceServiceController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'admin_mdas']);
    }

    public function deleteRequestList(AdminSixFinanceDeleteRequestDataTable $eDataTable, Request $request)
    {
        //return json_encode("AAAAA");
        return $eDataTable->render('datatables.index');
    }
}
