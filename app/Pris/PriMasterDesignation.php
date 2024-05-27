<?php

namespace App\Pris;

use Illuminate\Database\Eloquent\Model;

class PriMasterDesignation extends Model
{
    //
    public static function getDesignByApplicables($applicables){
        return PriMasterDesignation::whereIn('applicable_id', $applicables)->get();
    }
}
