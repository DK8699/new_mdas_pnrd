<?php

namespace App\CommonModels;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{

    public static function getZilaByDistrictId($id){
        return District::join('zila_parishads AS z','districts.id', '=', 'z.district_id')
            ->where('districts.id', '=', $id)
            ->select('z.id', 'z.zila_parishad_name')
            ->first();
    }
	//New Changes for VCDC
	
	public static function getDistrictCouncil(){
		return District::where('council_id','!=','NULL')
            ->select('id', 'district_name')
            ->get();
	}
	
	public static function getDistrictName($id){
		return District::where('id','=',$id)
            ->select('id', 'district_name')
            ->get();
	}
}
