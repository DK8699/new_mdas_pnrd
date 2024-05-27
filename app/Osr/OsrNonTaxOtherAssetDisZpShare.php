<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;
use DB;

class OsrNonTaxOtherAssetDisZpShare extends Model
{
    public static  function getApsShareToZpList($fy_id){
        $finalArray=[];

        $data=OsrNonTaxOtherAssetDisZpShare::where([
            ['fy_id', '=', $fy_id],
            ['shared_by', '=', "AP"],
        ])->select(DB::raw('sum(zp_share) AS zp_share, zp_id'))->groupBy('zp_id')->get();

        foreach($data AS $li){
            $finalArray[$li->zp_id]=$li->zp_share;
        }

        return $finalArray;
    }

    public static  function getGpsShareToZpList($fy_id){
        $finalArray=[];

        $data=OsrNonTaxOtherAssetDisZpShare::where([
            ['fy_id', '=', $fy_id],
            ['shared_by', '=', "GP"],
        ])->select(DB::raw('sum(zp_share) AS zp_share, zp_id'))->groupBy('zp_id')->get();

        foreach($data AS $li){
            $finalArray[$li->zp_id]=$li->zp_share;
        }

        return $finalArray;
    }

    //AP

    public static  function getApsShareToZp($id, $fy_id){
        $finalArray=[];

        $data=OsrNonTaxOtherAssetDisZpShare::where([
            ['fy_id', '=', $fy_id],
            ['shared_by', '=', "AP"],
            ['zp_id', '=', $id],
        ])->select(DB::raw('sum(zp_share) AS zp_share, zp_id'))
            ->groupBy('zp_id')->get();

        foreach($data AS $li){
            $finalArray[$li->zp_id]=$li->zp_share;
        }

        return $finalArray;
    }

    public static  function getGpsShareToZp($id, $fy_id){
        $finalArray=[];

        $data=OsrNonTaxOtherAssetDisZpShare::where([
            ['fy_id', '=', $fy_id],
            ['shared_by', '=', "GP"],
            ['zp_id', '=', $id],
        ])->select(DB::raw('sum(zp_share) AS zp_share, zp_id'))
            ->groupBy('zp_id')->get();

        foreach($data AS $li){
            $finalArray[$li->zp_id]=$li->zp_share;
        }

        return $finalArray;
    }
}
