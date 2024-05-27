<?php

namespace App\CommonModels;

use Illuminate\Database\Eloquent\Model;

class AnchalikParishad extends Model
{
    public static function getAPsByZilaId($zila_id){
        return AnchalikParishad::where([
            ['zila_id', '=', $zila_id]
        ])->select('id', 'anchalik_parishad_name')
            ->get();
    }

    public static function apCountByZilaId($zila_id){
        return AnchalikParishad::where([
            ['zila_id', '=', $zila_id]
        ])->count();
    }

    //---------------- NEW --------------------------------------------------------------------------------------

    public static function getAPName($id){
        return AnchalikParishad::where([
            ['id', '=', $id]
        ])->select('id','anchalik_parishad_name')->first();
    }

    public static function getAPsByApId($ap_id){
        return AnchalikParishad::where([
            ['id', '=', $ap_id]
        ])->select('id','anchalik_parishad_name')
            ->first();
    }

    public static function getActiveAPsByZpId($id){
        return AnchalikParishad::where([
            ['zila_id', '=', $id],
            ['is_active', '=', 1],
        ])->select('id','anchalik_parishad_name')->get();
    }

	public static function getZpAp($id) {
	    return AnchalikParishad::join('zila_parishads as z','z.id','=','anchalik_parishads.zila_id')
		    ->where([
            ['anchalik_parishads.id', '=', $id],
            ['anchalik_parishads.is_active', '=', 1],
        ])->select('anchalik_parishads.id as ap_id','z.id as id','anchalik_parishads.anchalik_parishad_name','z.zila_parishad_name')->first();
    }
}
