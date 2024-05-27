<?php

namespace App\Http\Controllers\Admin\Pris;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\PriReportGPNicDataTable;
use App\DataTables\PriReportAPNicDataTable;
use App\DataTables\PriReportZPNicDataTable;

class PriAdminServiceController extends Controller
{
    public function servicePriGPNicDataList(PriReportGPNicDataTable $eDataTable,Request $request)
    {
        return $eDataTable->render('datatables.index');
    }
    public function servicePriAPNicDataList(PriReportAPNicDataTable $eDataTable,Request $request)
    {
        return $eDataTable->render('datatables.index');
    }
    public function servicePriZPNicDataList(PriReportZPNicDataTable $eDataTable,Request $request)
    {
        return $eDataTable->render('datatables.index');
    }
}
