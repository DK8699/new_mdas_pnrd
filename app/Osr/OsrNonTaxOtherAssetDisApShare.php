<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;
use DB;

class OsrNonTaxOtherAssetDisApShare extends Model
{


    //AP

    public static  function getZpShareToApList($id, $fy_id){
        $finalArray=[];

        $data=OsrNonTaxOtherAssetDisApShare::where([
            ['fy_id', '=', $fy_id],
            ['shared_by', '=', "ZP"],
            ['zp_id', '=', $id],
        ])->select(DB::raw('sum(ap_share) AS ap_share, ap_id'))
            ->groupBy('ap_id')->get();

        foreach($data AS $li){
            $finalArray[$li->ap_id]=$li->ap_share;
        }

        return $finalArray;
    }

    public static  function getGpsShareToApList($id, $fy_id){
        $finalArray=[];

        $data=OsrNonTaxOtherAssetDisApShare::where([
            ['fy_id', '=', $fy_id],
            ['shared_by', '=', "GP"],
            ['zp_id', '=', $id],
        ])->select(DB::raw('sum(ap_share) AS ap_share, ap_id'))
            ->groupBy('ap_id')->get();

        foreach($data AS $li){
            $finalArray[$li->ap_id]=$li->ap_share;
        }

        return $finalArray;
    }

    //GP

    public static  function getZpShareToAp($id, $ap_id, $fy_id){
        $finalArray=[];

        $data=OsrNonTaxOtherAssetDisApShare::where([
            ['fy_id', '=', $fy_id],
            ['shared_by', '=', "ZP"],
            ['zp_id', '=', $id],
            ['ap_id', '=', $ap_id],
        ])->select(DB::raw('sum(ap_share) AS ap_share, ap_id'))
            ->groupBy('ap_id')->get();

        foreach($data AS $li){
            $finalArray[$li->ap_id]=$li->ap_share;
        }

        return $finalArray;
    }

    public static  function getGpsShareToAp($id, $ap_id, $fy_id){
        $finalArray=[];

        $data=OsrNonTaxOtherAssetDisApShare::where([
            ['fy_id', '=', $fy_id],
            ['shared_by', '=', "GP"],
            ['zp_id', '=', $id],
            ['ap_id', '=', $ap_id],
        ])->select(DB::raw('sum(ap_share) AS ap_share, ap_id'))
            ->groupBy('ap_id')->get();

        foreach($data AS $li){
            $finalArray[$li->ap_id]=$li->ap_share;
        }

        return $finalArray;
    }
}
