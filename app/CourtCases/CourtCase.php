<?php

namespace App\CourtCases;

use Illuminate\Database\Eloquent\Model;

class CourtCase extends Model
{
    public static function getCase($id){
        return CourtCase::where('id', $id)->first();
    }
}
