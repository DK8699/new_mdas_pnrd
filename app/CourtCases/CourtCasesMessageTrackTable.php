<?php

namespace App\CourtCases;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use DB;
class CourtCasesMessageTrackTable extends Model
{
    public static function add_message_sending($section_info, $case_id, $due_date, $submitted_by_id, $section_id){

        $returnData['msgType']=false;
        $returnData['data']=[];
        $returnData['msg']="Opps somthing went wrong";
        $returnData['msg1']="";

        $targetBlocks=[];

        $users=Auth::user();

        $message_due_date=Carbon::parse($due_date)->format('d M Y');

        $currentDate=Carbon::now()->format('Y-m-d');
        $targetDate=Carbon::parse($due_date)->format('Y-m-d');

        if($currentDate > $targetDate){
            $returnData['msg']= "Message not sent since Due Date has to be greater than Today's Date";
            return $returnData;
        }

        $courtData= CourtCase::getCase($case_id);
        $case_no= $courtData->case_number;

        if($section_info=="PARAWISE COMMENTS"){
            $message_body="Plz. Submit PWC in Case No.-".$case_no." within due date ".$message_due_date;
            $target_section_id=1;
        }elseif ($section_info=="INTERIM ORDER"){
            $message_body="Plz. Comply with Interim Order regarding Case No.-".$case_no." within the stipulated date ".$message_due_date." as per Court Order";
            $target_section_id=4;
        }elseif ($section_info=="INSTRUCTION"){
            $message_body="Plz. Provide Instruction regarding Case No.-".$case_no." within the stipulated date ".$message_due_date;
            $target_section_id=5;
        }elseif ($section_info=="FINAL ORDER"){
            $message_body="Plz. Comply with Final Order regarding Case No.-".$case_no." within the stipulated date ".$message_due_date." as per Court Order.";
            $target_section_id=6;
        }elseif ($section_info=="SPEAKING ORDER"){
            $message_body="Plz. Issue Speaking Order regarding Case No.-".$case_no." within the stipulated date ".$message_due_date." as per direction of Court.";
            $target_section_id=7;
        }else{
            return $returnData;
        }

        if($submitted_by_id==6){
            $targetBlockList=DB::table('court_cases_blocks')->where([
                ['section_id', '=', $target_section_id],
                ['section_table_id', '=', $section_id]
            ])->select('block_id')->get();

            foreach($targetBlockList AS $block){
                array_push($targetBlocks, $block->block_id);
            }
        }

        $receipients= CourtCasesRecipient::getRecepientsMobileNos($courtData->district_id, $submitted_by_id, $targetBlocks);

        if(count($receipients)> 0){
            $receipient_nos = implode(',', $receipients);
            CourtCasesMessageTrackTable::sendMessage($receipient_nos, "New - ".$message_body);
        }else{
            $receipient_nos = "NA";
            $returnData['msg1']= " Message not sent because recepients mobile numbers not added.";
        }

        //$deleteEarlierEntries=CourtCasesMessageTrackTable::where('case_no', $case_no)->delete();

        $alData=CourtCasesMessageTrackTable::where([
            ['case_no', '=', $case_no],
            ['section_info', '=', $section_info],
            ['message_halted', '=', 0],
        ])->update(["message_halted"=>1, "updated_by"=>$users->username, 'updated_at'=>Carbon::now()]);

        $newEntry= new CourtCasesMessageTrackTable();  
        $newEntry->case_no=$case_no;
        $newEntry->section_info=$section_info;
        $newEntry->section_id=$section_id;
        $newEntry->court_cases_submitted_by_id=$submitted_by_id;
        $newEntry->recipients=$receipient_nos;
        $newEntry->orginal_due_date=Carbon::parse($due_date)->format('Y-m-d');
        $newEntry->prior_message_sending_date= Carbon::parse($due_date)->subDay(7)->format('Y-m-d');
        $newEntry->message_body="Reminder- ".$message_body;
        $newEntry->created_by=$users->username;

        if(!$newEntry->save()){
            $returnData['msg']= $returnData['msg1']." Message alarm not set.";
            return $returnData;
        }

        $returnData['msgType']=true;
        $returnData['data']=[];

        if($receipient_nos == "NA"){
            $returnData['msg']=$returnData['msg1']." Message alarm not set.";
        }else{
            $returnData['msg']= "Successfully set the alarm";
        }

        return $returnData;
    }

    public static function update_message_sending($section_info, $case_id, $due_date, $submitted_by_id, $section_id){
        $returnData['msgType']=false;
        $returnData['data']=[];
        $returnData['msg']="Opps somthing went wrong";
        $returnData['msg1']="";

        $targetBlocks=[];

        $users=Auth::user();
        $message_due_date=Carbon::parse($due_date)->format('d M Y');

        $currentDate=Carbon::now()->format('Y-m-d');
        $targetDate=Carbon::parse($due_date)->format('Y-m-d');
            
        if($currentDate > $targetDate){
            $returnData['msg']= "Message not sent since Due Date has to be greater than Today's Date";
            return $returnData;
        }

        $courtData= CourtCase::getCase($case_id);
        $case_no= $courtData->case_number;

        if($section_info=="PARAWISE COMMENTS"){
            $message_body="Plz. Submit PWC in Case No.-".$case_no." within due date ".$message_due_date.". Plz ignore if already submitted.";
            $target_section_id=1;
        }elseif ($section_info=="INTERIM ORDER"){
            $message_body="Plz. Comply with Interim Order regarding Case No.-".$case_no." within the stipulated date ".$message_due_date." as per Court Order. Plz ignore if already complied.";
            $target_section_id=4;
        }elseif ($section_info=="INSTRUCTION"){
            $message_body="Plz. Provide Instructions regarding Case No.-".$case_no." within the stipulated date ".$message_due_date.". Plz ignore if already provided.";
            $target_section_id=5;
        }elseif ($section_info=="FINAL ORDER"){
            $message_body="Plz. Comply with Final Order regarding Case No.-".$case_no." within the stipulated date ".$message_due_date." as per Court Order. Plz ignore if already complied.";
            $target_section_id=6;
        }elseif ($section_info=="SPEAKING ORDER"){
            $message_body="Plz. Issue Speaking Order regarding Case No.-".$case_no." within the stipulated date ".$message_due_date." as per direction of Court. Plz ignore if already issued.";
            $target_section_id=7;
        }else{
            return $returnData;
        }

        if($submitted_by_id==6){
            $targetBlockList=DB::table('court_cases_blocks')->where([
                ['section_id', '=', $target_section_id],
                ['section_table_id', '=', $section_id]
            ])->select('block_id')->get();

            foreach($targetBlockList AS $block){
                array_push($targetBlocks, $block->block_id);
            }
        }

        $receipients= CourtCasesRecipient::getRecepientsMobileNos($courtData->district_id, $submitted_by_id, $targetBlocks);
        if(count($receipients)> 0){
            $receipient_nos = implode(',', $receipients);
            CourtCasesMessageTrackTable::sendMessage($receipient_nos, "Revised - ".$message_body);
        }else{
            $receipient_nos = "NA";
            $returnData['msg1']= " Message not sent because recepients mobile numbers not added.";
        }

        $alData=CourtCasesMessageTrackTable::where([
            ['case_no', '=', $case_no],
            ['section_info', '=', $section_info],
            ['section_id', '=', $section_id],
            ['message_halted', '=', 0],
        ])->update(["message_halted"=>1, "updated_by"=>$users->username, 'updated_at'=>Carbon::now()]);

        /*if(!$alData){
            $returnData['msg']="Opps somthing went wrong #1";
            return $returnData;
        }*/

        $newEntry= new CourtCasesMessageTrackTable();
        $newEntry->case_no=$case_no;
        $newEntry->section_info=$section_info;
        $newEntry->section_id=$section_id;
        $newEntry->court_cases_submitted_by_id=$submitted_by_id;
        $newEntry->recipients=$receipient_nos;
        $newEntry->orginal_due_date=Carbon::parse($due_date)->format('Y-m-d');
        $newEntry->prior_message_sending_date= Carbon::parse($due_date)->subDay(7)->format('Y-m-d');
        $newEntry->message_body="Reminder- ".$message_body;
        $newEntry->created_by=$users->username;

        if(!$newEntry->save()){
            $returnData['msg']= $returnData['msg1']." Message alarm not set.";
        }

        $returnData['msgType']=true;
        $returnData['data']=[];
        if($receipient_nos == "NA"){
            $returnData['msg']=$returnData['msg1']." Message alarm not set.";
        }else{
            $returnData['msg']= "Successfully set the alarm";
        }
        return $returnData;
    }

    public static function sendMessage($recipients, $message_body) {
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
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_POSTFIELDS,$parameters);
        $response = curl_exec($ch);
        if($response){
            return true;
        }
        return false;
    }
}
