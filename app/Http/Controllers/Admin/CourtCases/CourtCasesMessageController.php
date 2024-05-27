<?php

namespace App\Http\Controllers\Admin\CourtCases;

use App\CourtCases\CourtCasesMessageTrackTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CourtCasesMessageController extends Controller
{
    public function sendMessage($recipients, $message_body){
        $param['uname'] = 'PNRDGT';
        $param['password'] = 'test123@';
        $param['sender'] = 'PNRDGT';
        $param['receiver'] = $recipients;
        $param['route'] = 'TA';
        $param['msgtype'] = 1;
        $param['sms'] = $message_body;
        $parameters = http_build_query($param);

        $url="http://sms.dataoxytech.com/index.php/Bulksmsapi/httpapi";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_POSTFIELDS,$parameters);
        $response = curl_exec($ch);
        if($response){
            return true;
        }
        return false;
    }

    public function sendCourtCaseMessages(){
        $returnData=[
            'msgType'=>false,
            'data'=>[],
            'msg'=>"Error",
        ];

        $curDate=Carbon::now()->format('Y-m-d');

        //Seven Day On Message Sending----------------------------------------------------------------------------------

        $results= CourtCasesMessageTrackTable::where([
            ['prior_message_sending_date', '=', $curDate],
            ['message_halted', '=', 0],
            ['sent_times', '<=', 2],
        ])->select('id', 'message_body', 'recipients', 'sent_times')->get();

        foreach ($results AS $res){
            if($res->recipients <> "NA"){
                $response= $this->sendMessage($res->recipients, $res->message_body);
                if($response){
                    CourtCasesMessageTrackTable::where('id', $res->id)->update(['sent_times'=>$res->sent_times+1]);
                    $returnData=[
                        'msgType'=>true,
                        'msg'=>"Success",
                        'data'=>$response
                    ];
                }
            }
        }

        //First Day On Message Sending----------------------------------------------------------------------------------

        $results= CourtCasesMessageTrackTable::where([
            ['orginal_due_date', '=', $curDate],
            ['message_halted', '=', 0],
            ['sent_times', '<=', 2],
        ])->select('id', 'message_body', 'recipients', 'sent_times')->get();

        foreach ($results AS $res){
            if($res->recipients <> "NA"){
                $response= $this->sendMessage($res->recipients, $res->message_body);
                if($response){
                    CourtCasesMessageTrackTable::where('id', $res->id)->update(['sent_times'=>$res->sent_times+1]);
                    $returnData=[
                        'msgType'=>true,
                        'msg'=>"Success",
                        'data'=>$response
                    ];
                }
            }
        }

        return json_encode($returnData);
    }
}
