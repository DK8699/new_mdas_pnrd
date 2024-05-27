<?php

namespace App\CommonModels;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{

    public static function getVillagesByGP($gp_id){
        return Village::where('gram_panchayat_id', '=', $gp_id)->select('id', 'village_name')->get();
    }
}
