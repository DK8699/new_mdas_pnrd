<?php

namespace App\DataTables;

use App\Pris\PriMemberMainRecord;
use Yajra\DataTables\Services\DataTable;

class PriReportZPNicDataTable extends DataTable
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
            ->join('zila_parishads AS zp', 'zp.id', '=', 'pri_member_main_records.zilla_id')
            ->join('pri_master_designations AS d', 'd.id', '=', 'h.pri_master_designation_id')
            ->join('master_genders AS g', 'g.id', '=', 'pri_member_main_records.gender_id')
            ->join('master_castes AS c', 'c.id', '=', 'pri_member_main_records.caste_id')
            ->where([
                ['h.master_pri_term_id', '=', 4]
            ])
            ->select('zp.id AS zp_id','zp.zila_parishad_name', 'pri_f_name', 'pri_m_name', 'pri_l_name','g.gender_name', 'mobile_no','d.design_name', 'c.caste_name', 'email_id')
            ->whereIn('h.pri_master_designation_id', [1,2,7]);
    }

    public function html()
    {
        return $this->builder()
            ->columns([
                'zp_id' => ['title' => 'LGD Code'],
                'zila_parishad_name' => ['title' => 'Local Body Name'],
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
            'zp_id',
            'zila_parishad_name',
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
        return 'PriReportZPNic_' . date('YmdHis');
    }
}
