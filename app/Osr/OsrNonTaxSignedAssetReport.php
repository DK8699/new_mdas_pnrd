<?php

namespace App\Osr;
use DB;

use Illuminate\Database\Eloquent\Model;

class OsrNonTaxSignedAssetReport extends Model
{
     public static function alreadyExist($fy_id, $z_id){
        $res= OsrNonTaxSignedAssetReport::where([
            ['osr_fy_year_id', '=', $fy_id],
            ['zila_id', '=', $z_id],
        ])->count();

        if($res > 0){
            return true;
        }
        return false;
    }
   public static function getreportByfIdzId($fy_id,$z_id){
        
        return OsrNonTaxSignedAssetReport::where([
            ['osr_fy_year_id','=',$fy_id],
            ['zila_id', '=', $z_id],
        ])->select('attachment_path')
            ->first();
    }
}
