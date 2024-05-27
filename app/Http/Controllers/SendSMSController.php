<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SendSMSController extends Controller
{
    public function osrSendInstalmentNotification(){
        $recepient_no="8011307559";

        $msg="First Installment Pending of Haat  Jorhat: 1";

        $param['uname'] = 'PNRDGT';
        $param['password'] = 'test123@';
        $param['sender'] = 'SIRDAS';
        $param['receiver'] = $recepient_no;
        $param['route'] = 'TA';
        $param['msgtype'] = 1;
        $param['sms'] = $msg;
        $parameters = http_build_query($param);

        $url="http://sms.dataoxytech.com/index.php/Bulksmsapi/httpapi";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_POSTFIELDS,$parameters);
        $result = curl_exec($ch);
        return true;
    }
}
