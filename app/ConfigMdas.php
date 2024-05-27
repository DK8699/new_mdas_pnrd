<?php

namespace App;

use App\CommonModels\AnchalikParishad;
use App\CommonModels\GramPanchyat;
use App\CommonModels\ZilaParishad;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ConfigMdas extends Model
{
    //

    public static function allActiveList(){
        $data=[];
        $lists= ConfigMdas::where('is_active', 1)->select('setting', 'value')->get();
        foreach ($lists AS $li){
           $data[$li->setting]=$li->value;
        }
        return (object)$data;
    }


    public static function getOriginName(){
        $users=Auth::user();
        $name=NULL;

        if($users->mdas_master_role_id==2){
            $name = ZilaParishad::getZPName($users->zp_id)->zila_parishad_name;
        }elseif($users->mdas_master_role_id==3){
            $name = AnchalikParishad::getAPName($users->ap_id)->anchalik_parishad_name;
        }elseif($users->mdas_master_role_id==4){
            $name = GramPanchyat::getGPName($users->gp_id)->gram_panchayat_name;
        }

        return $name;
    }

    public static function cur_format($amt){

        if($amt > 0){
            return round($amt/10000000, 4);
        }
        return 0;
    }
}
