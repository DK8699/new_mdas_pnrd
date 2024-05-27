<?php

namespace App\Http\Controllers\Admin\six_finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CommonModels\District;

class AdminSixfinanceDownloadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin_mdas']);
    }

    public function download_combined(){
        $districts = District::where('status', 0)->orderBy('district_name')->get();
        return view('admin.survey.six_finance.download_combined', compact('districts'));
    }
}
