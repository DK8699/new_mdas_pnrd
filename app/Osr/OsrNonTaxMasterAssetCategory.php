<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrNonTaxMasterAssetCategory extends Model
{
    public static function getById($id){
        return OsrNonTaxMasterAssetCategory::where('id', $id)->first();
    }
    public static function getAssetCategory()
    {
        return OsrNonTaxMasterAssetCategory::all();
    }
}
