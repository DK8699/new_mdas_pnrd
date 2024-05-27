<?php

namespace App\Uc;

use Illuminate\Database\Eloquent\Model;

class UcGfr extends Model
{
     public static function alreadyExist($e_id){
        $res= UcGfr::where([
            ['entity_id', '=', $e_id],
        ])->count();

        if($res > 0){
            return true;
        }
        return false;
    }
}
