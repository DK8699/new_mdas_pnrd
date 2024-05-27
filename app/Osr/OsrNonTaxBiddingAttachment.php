<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrNonTaxBiddingAttachment extends Model
{

    public static function getAllActiveDoc(){
        return OsrNonTaxBiddingAttachment::where('is_active', 1)
            ->get();
    }
}
