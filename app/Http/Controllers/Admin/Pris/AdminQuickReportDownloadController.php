<?php

namespace App\Http\Controllers\Admin\Pris;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CommonModels\GramPanchyat;
use App\Pris\PriMemberMainRecord;
use App\survey\six_finance\AnchalikParishad;
use App\survey\six_finance\ZilaParishad;
use Redirect,Response,DB,Config;
use Datatables;
use Yajra\DataTables\Services\DataTable;

class AdminQuickReportDownloadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin_mdas']);
    }

    public function priDownloadMenu ()
    {
        return view('admin.Pris.priDownloadMenu');
    }
    public function quickReportDownloadZP (Request $request)
    {

        return view('admin.Pris.quickReportDownloadZP');

    }
    public function quickReportDownloadZPP(Request $request)
    {
        $model = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
                ->join('pri_master_designations AS d', 'd.id', '=', 'h.pri_master_designation_id')
                ->join('master_genders AS g', 'g.id', '=', 'pri_member_main_records.gender_id')
                ->join('master_castes AS c', 'c.id', '=', 'pri_member_main_records.caste_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4]
                ])
                ->select('zp.id AS zp_id','zp.zila_parishad_name', 'pri_f_name', 'pri_m_name', 'pri_l_name','g.gender_name', 'mobile_no','d.design_name', 'c.caste_name', 'email_id')
                ->whereIn('h.pri_master_designation_id', [1,2,7]);

        return datatables()->of($model)
            ->addIndexColumn()
            ->addColumn('pri_name', function($model) {
                return '' . $model->pri_f_name . ' '. $model->pri_m_name . ' '. $model->pri_l_name .'';
            })->toJson();
    }

    public function quickReportDownloadAP (Request $request)
    {

        return view('admin.Pris.quickReportDownloadAP');

    }
        public function quickReportDownloadAPP(Request $request)
    {
        $model = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
            ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
            ->join('pri_master_designations AS d', 'd.id', '=', 'h.pri_master_designation_id')
            ->join('master_genders AS g', 'g.id', '=', 'pri_member_main_records.gender_id')
            ->join('master_castes AS c', 'c.id', '=', 'pri_member_main_records.caste_id')
            ->where([
                ['h.master_pri_term_id', '=', 4]
            ])->whereIn('h.pri_master_designation_id', [3,4,8])
            ->select('pri_member_main_records.id AS pri_id','pri_member_main_records.anchalik_id AS ap_id',
                'ap.anchalik_parishad_name', 'pri_member_main_records.pri_f_name', 'pri_member_main_records.pri_m_name',
                'pri_member_main_records.pri_l_name','g.gender_name', 'pri_member_main_records.mobile_no','d.design_name',
                'c.caste_name', 'email_id');
//dd($model);
        return datatables()->of($model)
            ->addIndexColumn()
            ->addColumn('pri_name', function($model) {
                return '' . $model->pri_f_name . ' '. $model->pri_m_name . ' '. $model->pri_l_name .'';
            })->toJson();
    }

    public function quickReportDownloadGP (Request $request)
    {
        return view('admin.Pris.quickReportDownloadGP');
    }
    public function quickReportDownloadGPP(Request $request)
    {
        $model = PriMemberMainRecord::join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
                ->join('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
                ->join('pri_master_designations AS d', 'd.id', '=', 'h.pri_master_designation_id')
                ->join('master_genders AS g', 'g.id', '=', 'pri_member_main_records.gender_id')
                ->join('master_castes AS c', 'c.id', '=', 'pri_member_main_records.caste_id')
                ->leftJoin('master_wards AS w', 'w.id', '=', 'pri_member_main_records.ward_id')
                ->where([
                   ['h.master_pri_term_id', '=', 4]
                ])->whereIn('h.pri_master_designation_id', [5,6,9])
                ->select('pri_member_main_records.id AS pri_id','pri_member_main_records.gram_panchayat_id AS g_id',
                    'gp.gram_panchayat_name', 'pri_member_main_records.pri_f_name', 'pri_member_main_records.pri_m_name',
                    'pri_member_main_records.pri_l_name','g.gender_name','w.ward_name', 'mobile_no','d.design_name',
                    'c.caste_name', 'email_id');

        return datatables()->of($model)
            ->addIndexColumn()
            ->addColumn('pri_name', function($model) {
                return '' . $model->pri_f_name . ' SSSSSSSSSSSSSSSS'. $model->pri_m_name . ' '. $model->pri_l_name .'';
            })->toJson();


        /*return datatables()->of($gpsDownload)
            ->addIndexColumn()
            ->addColumn('pri_name', function(PriMemberMainRecord $gpsDownload) {
                return '' . $gpsDownload->pri_f_name . ' '. $gpsDownload->pri_m_name . ' '. $gpsDownload->pri_l_name .'';
            })
            ->make(true);*/
    }

}
