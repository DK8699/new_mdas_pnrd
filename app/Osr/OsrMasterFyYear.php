<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrMasterFyYear extends Model
{
    public static function getPreviousYear(){
        return OsrMasterFyYear::where('id', 1)->first();
    }

    public static function getPreviousThreeYears(){
        return OsrMasterFyYear::whereIn('id',[1,2,3])->get();
    }
    public static function getFyYear($id){
        return OsrMasterFyYear::where('id', $id)->first();
    }

    public static function getAllYears(){
        return OsrMasterFyYear::orderBy('id', 'DESC')->get();
    }
    public static function getFyYrById($id){
        return OsrMasterFyYear::where('id', $id)
            ->select('fy_name')
            ->get();

    }
    public static function getFyByDate($cr_date){
        return OsrMasterFyYear::where([
            ['fy_from','<=',$cr_date],
            ['fy_to','>=',$cr_date],
        ])->first();
    }
    public static function getMaxFyYear(){
        return OsrMasterFyYear::max('id');
        /*return OsrMasterFyYear::where('id', \DB::raw("(select max(`id`) from osr_master_fy_years)"))->first();*/
    }
	public static function getFyDataId($id){
        return OsrMasterFyYear::where('id', $id)
            ->select('fy_name','fy_from','fy_to')
            ->first();

    }


}
