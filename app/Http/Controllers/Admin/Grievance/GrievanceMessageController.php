<?php

namespace App\Http\Controllers\Admin\Grievance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Grievance\GrievanceMediaMessageTrackTable;
use App\Grievance\GrievanceRecipient;
use App\Grievance\GrievanceIndividualMsgTrackTable;
use App\Grievance\GrievanceEntry;
use App\Grievance\GrievanceReference;

use Carbon\Carbon;

class GrievanceMessageController extends Controller
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
	
	
	//MEDIA GRIEVANCE
	public function sendMediaMessages(){
		
        $returnData=[
            'msgType'=>false,
            'data'=>[],
            'msg'=>"Error",
        ];

        $cur_date=Carbon::now();

        $results= GrievanceMediaMessageTrackTable::where([
            ['grievance_media_message_track_tables.action_sent_date', '<', $cur_date],
            ['action_taken_status', '=', 0],
        ])->select('media_code','action_sent_date','sent_times','district_id','action_taken_by')->get();

		foreach($results as $res){
			$recipient = GrievanceRecipient::join('grievance_media_message_track_tables as media_message','media_message.action_taken_by','=','grievance_recipients.submitted_to')
				->join('districts as d','d.id','=','media_message.district_id')
				->where([
					['grievance_recipients.submitted_to','=',$res->action_taken_by],
				])
				->whereIn('media_message.level',[1,2,3,4,5,6,7])
				->select('grievance_recipients.mobile_no','media_message.action_sent_date','media_message.id as id','media_message.sent_times','media_message.media_code','d.district_name')->get();
			
			foreach($recipient as $res){
				$start_date = Carbon::parse($res->action_sent_date);
				$end_date = Carbon::parse($cur_date);
				$diff = $start_date->diffInDays($end_date);
				$date=  Carbon::parse($start_date)->format('d/m/Y');
				$message_body = "Pending Grievance $res->media_code dated: $date under District: $res->district_name";
				
				if($diff % 3 == 0 && $diff!=0){
					$response= $this->sendMessage($res->mobile_no,$message_body);
					
					 if($response){
						GrievanceMediaMessageTrackTable::where('id', $res->id)->update(['sent_times'=>$res->sent_times+1]);
						$returnData=[
						    'msgType'=>true,
						    'msg'=>"Success",
						    'data'=>$response
						];
					 }
					
				}
			}
			
		}

 	 return json_encode($returnData);
       
    }
	
	
	//INDIVIDUAL GRIEVANCE
	public function sendActionMessages(Request $request){
		
		 $returnData['msgType'] = false;
		 $returnData['data'] = [];
		 $returnData['msg'] = "Error";
		
       	$cur_date=Carbon::now()->toDateString();

		
		$result = GrievanceEntry::leftJoin('grievance_references as ref','ref.grievance_code','=','grievance_entries.grievance_code')
			->leftJoin('grievance_individual_msg_track_tables as individual_msg','individual_msg.grievance_code','=','grievance_entries.grievance_code')
			->leftJoin('districts as d','d.id','=','grievance_entries.district_id')
			->where([
			['reply_status','=',1]
		])->select('ref.grievance_code','d.district_name','grievance_entries.name','grievance_entries.mobile_no','grievance_entries.entry_date','individual_msg.msg_sent_at_action')->get();
		
		
		foreach($result as $res){
			$date=  Carbon::parse($res->entry_date)->format('d/m/Y');
			
			$message_body = "Grievance code: $res->grievance_code DATED: $date at District: $res->district_name has been resolved for $res->name";
			
			if($res->msg_sent_at_action == 0){
				$response= $this->sendMessage($res->mobile_no,$message_body);

				if($response){

					GrievanceIndividualMsgTrackTable::where('grievance_code','=',$res->grievance_code)
						->update([
							'msg_sent_at_action'=>1,
							'msg_sent_at_action_date'=>$cur_date,
							'msg_sent_at_action_body'=>$message_body,
						]);


					   $returnData['msg'] = "Success";
					   $returnData['msgType']=true;
					   $returnData['data']=[$response];

				}
			}
		}
			
 	 return json_encode($returnData);
       
		
		
	}
	
	
}
