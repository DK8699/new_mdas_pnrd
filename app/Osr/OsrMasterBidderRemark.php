<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrMasterBidderRemark extends Model
{
    public static function getActiveList(){
        return OsrMasterBidderRemark::where('is_active', 1)
            ->orderBy('id', 'desc')
            ->get();
    }
}
