<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrNonTaxAssetConfirmation extends Model
{
    public static function getData(){
	    
	    return OsrNonTaxAssetConfirmation::select('*')->get();
    }
}
