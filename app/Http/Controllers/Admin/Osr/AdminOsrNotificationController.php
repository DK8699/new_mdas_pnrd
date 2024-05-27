<?php

namespace App\Http\Controllers\Admin\Osr;

use App\Osr\OsrNonTaxFyInstalment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminOsrNotificationController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'admin_mdas']);
    }

    public function sendFirstInstalmentPendingAlert(Request $request){
        $osr_fy_year_id=4;
        $branch_id=1;
        $installment_id=1;

        $firstInstalmentPending= OsrNonTaxFyInstalment::join('osr_non_tax_asset_entries AS a', 'a.id', '=', 'osr_non_tax_fy_instalments.osr_non_tax_asset_entry_id')
            ->join('osr_non_tax_bidding_general_details AS g', 'a.id', '=', 'g.osr_asset_entry_id')
            ->where([
                ['osr_non_tax_fy_instalments.osr_master_instalment_id', '=', $installment_id],
                ['osr_non_tax_fy_instalments.osr_master_fy_year_id', '=', $osr_fy_year_id],
                ['a.osr_asset_branch_id', '=', $branch_id],
            ])->get();
    }
}
