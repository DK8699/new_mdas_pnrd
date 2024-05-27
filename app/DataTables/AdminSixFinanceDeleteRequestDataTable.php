<?php

namespace App\DataTables;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;

class AdminSixFinanceDeleteRequestDataTable extends DataTable
{

    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('action', function ($query) {
                return '<button  class="btn btn-sm btn-primary" style="font-size: 0.70rem;"><i class="fa fa-eye"></i></button>';
            })
            ->addIndexColumn()
            ->addColumn('location', function ($query){
                return ($query->district_name? 'District: '.$query->district_name : 'District: --- ')
                        .($query->zila_parishad_name? ' ZP: '.$query->zila_parishad_name: 'ZP: --- ')
                        .($query->anchalik_parishad_name? ' AP: '.$query->anchalik_parishad_name: 'AP: --- ')
                        .($query->gram_panchayat_name? ' GP: '.$query->gram_panchayat_name: ' GP: --- ');

            });
    }


    public function query()
    {
        return DB::table('six_finance_final_deletes AS d')
            ->join('six_finance_finals AS f', 'd.six_finance_final_id','=','f.id')

            ->join('districts AS dt', 'dt.id','=','f.district_id')
            ->leftjoin('zila_parishads AS zp', 'zp.id', '=', 'f.zila_id')
            ->leftjoin('anchalik_parishads AS ap', 'ap.id', '=', 'f.zila_id')
            ->leftjoin('gram_panchyats AS gp', 'gp.gram_panchyat_id', '=', 'f.gram_panchayat_id')

            ->select('f.id','f.employee_code','dt.district_name', 'zp.zila_parishad_name',
                'ap.anchalik_parishad_name', 'gp.gram_panchayat_name', 'd.basic_info', 'd.staff_info',
                'd.revenue_info', 'd.expenditure_info', 'd.balance_info', 'd.other_info', 'd.five_year_info');

    }


    public function html()
    {
        return $this->builder()
            ->columns([
                'employee_code' => ['title' => 'Employee Code'],
                'district_name' => ['title' => 'Name'],

                'district_name' => ['title' => 'DT'],
                'zila_parishad_name' => ['title' => 'ZP'],
                'anchalik_parishad_name' => ['title' => 'AP'],
                'gram_panchayat_name' => ['title' => 'GP'],

                'basic_info' => ['title' => 'Basic Info'],
                'staff_info' => ['title' => 'Staff Info'],
                'revenue_info' => ['title' => 'Revenue Info'],
                'expenditure_info' => ['title' => 'Expenditure Info'],
                'balance_info' => ['title' => 'Balance Info'],
                'other_info' => ['title' => 'Other Info'],
                'five_year_info' => ['title' => 'Next 5 Year Info'],

            ]);
    }


    protected function getColumns()
    {
        return [
            'id',
            'employee_code',

            'district_name',

            'zila_parishad_name',
            'anchalik_parishad_name',
            'gram_panchayat_name',

            'basic_info',
            'staff_info',
            'revenue_info',
            'expenditure_info',
            'balance_info',
            'other_info',
            'five_year_info',
        ];
    }


    protected function filename()
    {
        return 'DeleteRequest' . date('YmdHis');
    }
}
