<?php

namespace App\survey\six_finance;

use Illuminate\Database\Eloquent\Model;

class AnchalikParishad extends Model
{
    public static function getAPsByZilaId($zila_id){
        return AnchalikParishad::where([
            ['zila_id', '=', $zila_id]
          ])->select('id', 'anchalik_parishad_name')
            ->get();
    }
}
