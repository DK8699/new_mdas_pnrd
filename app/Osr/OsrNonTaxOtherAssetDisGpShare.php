<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;
use DB;

class OsrNonTaxOtherAssetDisGpShare extends Model
{

    //GP

    public static function getZpShareToGpList($id, $ap_id, $fy_id){
        $finalArray=[];

        $data=OsrNonTaxOtherAssetDisGpShare::where([
            ['fy_id', '=', $fy_id],
            ['shared_by', '=', "ZP"],
            ['zp_id', '=', $id],
            ['ap_id', '=', $ap_id],
        ])->select(DB::raw('sum(gp_share) AS gp_share, gp_id'))->groupBy('gp_id')->get();

        foreach($data AS $li){
            $finalArray[$li->gp_id]=$li->gp_share;
        }
        return $finalArray;
    }

    public static function getApShareToGpList($id, $ap_id, $fy_id){
        $finalArray=[];

        $data=OsrNonTaxOtherAssetDisGpShare::where([
            ['fy_id', '=', $fy_id],
            ['shared_by', '=', "AP"],
            ['zp_id', '=', $id],
            ['ap_id', '=', $ap_id],
        ])->select(DB::raw('sum(gp_share) AS gp_share, gp_id'))->groupBy('gp_id')->get();

        foreach($data AS $li){
            $finalArray[$li->gp_id]=$li->gp_share;
        }
        return $finalArray;
    }
}
