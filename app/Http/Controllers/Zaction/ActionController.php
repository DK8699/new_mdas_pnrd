<?php

namespace App\Http\Controllers\Zaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ActionController extends Controller
{


    public function updateZPPriEmail(){
        $zpEmailList= DB::table('z_action_pri_email_zp AS e')
            ->join('zila_parishads AS zp', 'e.district_id', '=', 'zp.district_id')
            ->select('zp.id', 'zp.zila_parishad_name', 'e.email_id')
            ->get();

        $sum=0;
        $sum1=0;

        foreach($zpEmailList AS $email){
            $update=DB::table('pri_member_main_records AS r')
                ->join('pri_member_term_histories AS h', 'r.id', '=', 'h.pri_member_main_record_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['r.zilla_id', '=', $email->id]
                ])
                ->whereIn('h.pri_master_designation_id', [1,2,7])
                ->update(['r.email_id'=>$email->email_id]);

            if($update){
                $sum=$sum+1;
            }else{
                $sum1=$sum1+1;

                echo "[".$email->id."--".$email->zila_parishad_name."]";
            }
        }

        echo "Hey Done ".$sum;
        echo " | Hey Not Done ".$sum1;
    }

    public function updateAPPriEmail(){
        $apEmailList= DB::table('z_action_pri_email_ap AS e')
            ->join('anchalik_parishads AS ap', 'e.anchalik_id', '=', 'ap.id')
            ->select('ap.id', 'e.email_id')
            ->get();

        $sum=0;
        $sum1=0;

        foreach($apEmailList AS $email){
            $update=DB::table('pri_member_main_records AS r')
                ->join('pri_member_term_histories AS h', 'r.id', '=', 'h.pri_member_main_record_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['r.anchalik_id', '=', $email->id]
                ])
                ->whereIn('h.pri_master_designation_id', [3,4,8])
                ->update(['r.email_id'=>$email->email_id]);

            if($update){
                $sum=$sum+1;
            }else{
                $sum1=$sum1+1;
            }
        }

        echo "Hey Done ".$sum;
        echo " | Hey Not Done ".$sum1;
    }

    public function updateGPPriEmail(){
        $apEmailList= DB::table('z_action_pri_email_gp AS e')
            ->join('gram_panchyats AS gp', 'e.gram_panchayat_id', '=', 'gp.gram_panchyat_id')
            ->select('gp.gram_panchyat_id AS id', 'e.email_id')
            ->get();

        $sum=0;
        $sum1=0;

        foreach($apEmailList AS $email){
            $update=DB::table('pri_member_main_records AS r')
                ->join('pri_member_term_histories AS h', 'r.id', '=', 'h.pri_member_main_record_id')
                ->where([
                    ['h.master_pri_term_id', '=', 4],
                    ['r.gram_panchayat_id', '=', $email->id]
                ])
                ->whereIn('h.pri_master_designation_id', [5,6,9])
                ->update(['r.email_id'=>$email->email_id]);

            if($update){
                $sum=$sum+1;
            }else{
                $sum1=$sum1+1;
            }
        }

        echo "Hey Done ".$sum;
        echo " | Hey Not Done ".$sum1;
    }


}
