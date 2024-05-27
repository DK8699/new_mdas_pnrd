<?php

namespace App\Http\Controllers\Admin\six_finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminSixFinanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin_mdas']);
    }

    public function index(Request $request){

        return view('admin.survey.six_finance.index');
    }
}
