<?php

namespace App\DataTables;

use App\Pris\PriMemberMainRecord;
use Yajra\DataTables\Services\DataTable;

class PriReportGPNicDataTable extends DataTable
{

    public function dataTable($query)
    {
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('pri_name', function($query) {
                return $query->pri_f_name.' '.$query->pri_m_name.' '.$query->pri_l_name;
            });
    }

    public function query(PriMemberMainRecord $model)
    {
        return $model->newQuery()->join('pri_member_term_histories AS h', 'h.pri_member_main_record_id', '=', 'pri_member_main_records.id')
            ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')//joined with AP to show AP name
            ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')////joined with AP to show ZP name
            ->join('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'pri_member_main_records.gram_panchayat_id')
            ->join('pri_master_designations AS d', 'd.id', '=', 'h.pri_master_designation_id')
            ->join('master_genders AS g', 'g.id', '=', 'pri_member_main_records.gender_id')
            ->join('master_castes AS c', 'c.id', '=', 'pri_member_main_records.caste_id')
            ->leftJoin('master_wards AS w', 'w.id', '=', 'pri_member_main_records.ward_id')
            ->where([
                ['h.master_pri_term_id', '=', 4]
            ])->whereIn('h.pri_master_designation_id', [5,6,9])
            ->select('pri_member_main_records.id AS pri_id','zp.zila_parishad_name','ap.anchalik_parishad_name','pri_member_main_records.gram_panchayat_id AS g_id',
                'gp.gram_panchayat_name', 'pri_member_main_records.pri_f_name', 'pri_member_main_records.pri_m_name',
                'pri_member_main_records.pri_l_name','g.gender_name','w.ward_name', 'mobile_no','d.design_name',
                'c.caste_name', 'email_id');
    }

    public function html()
    {
        return $this->builder()
            ->columns([
                'zila_parishad_name' => ['title' => 'Zilla Parisad'],
                'anchalik_parishad_name' => ['title' => 'Anchalik Parisad'],
                'g_id' => ['title' => 'LGD Code'],
                'gram_panchayat_name' => ['title' => 'Local Body Name'],
                'pri_name' => ['title' => 'Elected Member Name'],
                'gender_name' => ['title' => 'Gender'],
                'ward_name' => ['title' => 'Ward Name'],
                'mobile_no' => ['title' => 'Mobile'],
                'design_name' => ['title' => 'Designation'],
                'caste_name' => ['title' => 'Caste'],
                'email_id' => ['title' => 'Email'],
            ]);
    }

    protected function getColumns()
    {
        return [
            'zila_parishad_name',
            'anchalik_parishad_name',
            'g_id',
            'gram_panchayat_name',
            'pri_name',
            'gender_name',
            'ward_name',
            'mobile_no',
            'design_name',
            'caste_name',
            'email_id',
        ];
    }

    protected function filename()
    {
        return 'PriReportGPNic_' . date('YmdHis');
    }
}
