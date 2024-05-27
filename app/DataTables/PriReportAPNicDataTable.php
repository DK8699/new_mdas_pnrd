<?php

namespace App\DataTables;

use App\Pris\PriMemberMainRecord;
use Yajra\DataTables\Services\DataTable;

class PriReportAPNicDataTable extends DataTable
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
            ->join('anchalik_parishads AS ap', 'ap.id', '=', 'pri_member_main_records.anchalik_id')
            ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')//joined with ZP to show ZP name
            ->join('pri_master_designations AS d', 'd.id', '=', 'h.pri_master_designation_id')
            ->join('master_genders AS g', 'g.id', '=', 'pri_member_main_records.gender_id')
            ->join('master_castes AS c', 'c.id', '=', 'pri_member_main_records.caste_id')
            ->where([
                ['h.master_pri_term_id', '=', 4]
            ])->whereIn('h.pri_master_designation_id', [3,4,8])
            ->select('pri_member_main_records.id AS pri_id','zp.zila_parishad_name','pri_member_main_records.anchalik_id AS ap_id',
                'ap.anchalik_parishad_name', 'pri_member_main_records.pri_f_name', 'pri_member_main_records.pri_m_name',
                'pri_member_main_records.pri_l_name','g.gender_name', 'pri_member_main_records.mobile_no','d.design_name',
                'c.caste_name', 'email_id');
                
    }

    public function html()
    {
        return $this->builder()
            ->columns([
                'zila_parishad_name' => ['title' => 'Zilla Parisad'],
                'ap_id' => ['title' => 'LGD Code'],
                'anchalik_parishad_name' => ['title' => 'Local Body Name'],
                'pri_name' => ['title' => 'Elected Member Name'],
                'gender_name' => ['title' => 'Gender'],
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
            'ap_id',
            'anchalik_parishad_name',
            'pri_name',
            'gender_name',
            'mobile_no',
            'design_name',
            'caste_name',
            'email_id',
        ];
    }

    protected function filename()
    {
        return 'PriReportAPNic_' . date('YmdHis');
    }
}
