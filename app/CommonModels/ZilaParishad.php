<?php

namespace App\CommonModels;

use Illuminate\Database\Eloquent\Model;

class ZilaParishad extends Model
{

    //---------------- NEW --------------------------------------------------------------------------------------
    public static function getZPName($id){
        return ZilaParishad::where([
            ['id', '=', $id]
        ])->select('id', 'zila_parishad_name')->first();
    }

    public static function getZPs(){
        return ZilaParishad::get();
    }
}
