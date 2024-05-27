<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrMasterInstalment extends Model
{
    //

    public static function getAllInstalments(){
        return OsrMasterInstalment::all();
    }

}
