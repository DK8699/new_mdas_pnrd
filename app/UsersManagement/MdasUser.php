<?php

namespace App\UsersManagement;

use Illuminate\Database\Eloquent\Model;

class MdasUser extends Model
{
    public static function getUserById($id){
        return MdasUser::where([
                ['id', '=', $id],
            ])
            ->select('mdas_users.id')
            ->first();
    }
}
