<?php

namespace App\Http\Controllers\Admin\Grievance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ConfigMdas;
use App\CommonModels\Media;
use App\CommonModels\District;
use App\CommonModels\Block;
use App\CommonModels\GramPanchyat;
use App\Grievance\GrievanceMediaEntry;
use App\Grievance\GrievanceMediaReference;
use App\Grievance\GrievanceMediaSchemeEntry;
use App\Grievance\GrievanceMediaReportBackup;
use App\Grievance\GrievanceMediaMessageTrackTable;
use App\Grievance\GrievanceIndividualMsgTrackTable;
use App\Grievance\GrievanceSubmittedBy;

use App\Grievance\GrievanceScheme;
use App\Grievance\GrievanceEntry;
use App\Grievance\GrievanceReference;
use App\Grievance\GrievanceSchemeEntry;
use App\Master\MasterGender;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Crypt;
use DB;
use Validator;
use Illuminate\Validation\Rule;

class AdminGrievanceController extends Controller
{
   //dashboard
	
   public function dashboard(Request $request){
		
	   	$district= District::all();
	   
	   	$scheme = GrievanceScheme::all();
	   
	   //---------MEDIA GRIEVANCE--------------------
	   
	   	$districtWiseData = GrievanceMediaEntry::getMediaDataByDistrict();
	   
		   //echo json_encode($districtWiseIndividualGrievData);
		   $totMediaGriev = GrievanceMediaEntry::count();

		   $resolvedMediaGriev = GrievanceMediaEntry::join('grievance_media_references as ref','ref.media_code','=','grievance_media_entries.media_code')
			   ->where('ref.action_taken_status','=',1)
			   ->count();

		   $pendingMediaGriev = $totMediaGriev-$resolvedMediaGriev;
	   		
	   		if($totMediaGriev !=0)
			{
				$resolvedPercent = ($resolvedMediaGriev/$totMediaGriev)*100;
			}
		   else{
			   	$resolvedPercent = 0;
		   }
		   
	   
	   //---------MEDIA GRIEVANCE ENDS-----------------
	   
	   
	   //---------INDIVUAL GRIEVANCE-------------------
	   
	     $districtWiseIndividualGrievData = GrievanceEntry::getIndividualDataByDistrict();
	   
	   		$totIndividualGriev = GrievanceEntry::count();
	   
			$resolvedIndividualGriev = GrievanceEntry::join('grievance_references as ref','ref.grievance_code','=','grievance_entries.grievance_code')
					   ->where('ref.reply_status','=',1)
					   ->count();
	   		$pendingIndividualGriev = $totIndividualGriev-$resolvedIndividualGriev;
	   		
	   		if($totIndividualGriev !=0)
			{
	   			$resolvedIndivdualPercent = ($resolvedIndividualGriev/$totIndividualGriev)*100;
			}
	   		else{
				$resolvedIndivdualPercent=0;
			}
	   //---------INDIVUAL GRIEVANCE ENDS---------------
	   
	   
	   //--------SCHEME WISE ANALYSIS---------------------------
	   
	   		$schemeWiseMedia = GrievanceMediaEntry::getSchemeWiseMediaData();
	   		$schemeWiseIndividual = GrievanceEntry::getSchemeWiseIndividualData();
	   
	   		//echo json_encode($schemeWiseIndividual);
	   
	   
	   
	   //--------SCHEME WISE ANALYSIS ENDS---------------------------
	   
	   $data = [
		'totMediaGriev'=>$totMediaGriev, 
		'resolvedMediaGriev'=>$resolvedMediaGriev, 
		'pendingMediaGriev'=>$pendingMediaGriev,
		'resolvedPercent'=>$resolvedPercent,
		'totIndividualGriev'=>$totIndividualGriev,
		'resolvedIndividualGriev'=>$resolvedIndividualGriev,
		'pendingIndividualGriev'=>$pendingIndividualGriev,
		'resolvedIndivdualPercent'=>$resolvedIndivdualPercent,
		'schemeWiseMedia'=>$schemeWiseMedia,
		'schemeWiseIndividual'=>$schemeWiseIndividual,
	   ];
	   
	   return view('admin.Grievance.dashboard',compact('district','scheme','districtWiseData','districtWiseIndividualGrievData','data'));
	}
	
   // -------------------------------------- GET BLOCK BY DISTRICT ----------------------------------------------------

   public function getBlockByDistrict(Request $request){
        $returnData['msgType']=false;
        $returnData['data']=[];

        $district_id= $request->input('district_id');

        if(!$district_id){
            $returnData['msg']="Block List not found";
            return response()->json($returnData);
        }

        $blocks= Block::where([
            ['district_id', '=', $district_id],
        ])->select('id', 'block_name')
            ->get();

        if(empty($blocks)){
            $returnData['msg']="Block List not found1";
            return response()->json($returnData);
        }

        $returnData['msgType']=true;
        $returnData['data']=$blocks;
        $returnData['msg']="Success";
        return response()->json($returnData);
    }
	
   // -------------------------------------- GET GPs BY BLOCK ----------------------------------------------------
	
   public function getGPsByBlock(Request $request){
        $returnData['msgType']=false;
        $returnData['data']=[];

        $block_id= $request->input('block_id');

        if(!$block_id){
            $returnData['msg']="GP List not found";
            return response()->json($returnData);
        }

        $gps= GramPanchyat::where([
            ['block_id', '=', $block_id],
        ])->select('gram_panchyat_id AS id', 'gram_panchayat_name')
            ->get();

        if(empty($gps)){
            $returnData['msg']="GP List not found";
            return response()->json($returnData);
        }

        $returnData['msgType']=true;
        $returnData['data']=$gps;
        $returnData['msg']="Success";
        return response()->json($returnData);
    }
	
   //--------------------------------------MEDIA GRIEVANCE----------------------------------------
	
   public function media_entry(Request $request){
	    
	    $media = Media::all();
	    $district= District::all();
	    $scheme = GrievanceScheme::all();
	    if(!$request->input('id')){
          $cur_date=Carbon::now()->toDateString();
        }else{
            $cur_date=$request->input('id');
        }
        
	    
	    
	    $users=Auth::user();
	    return view('admin.Grievance.media_grievance',compact('users','media','scheme','district','blocks','cur_date'));
    }
	
   public function media_entry_save(Request $request){
	   $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";
	   
	   $users=Auth::user();
	   
	   $cur_date=Carbon::now()->toDateString();
	   
	   $maxVal = GrievanceMediaReference::where('date_of_entry','=',$cur_date)->count();

        $grievMediaCode = $this->makeMediaGrievCode($maxVal,$cur_date);
	   
	   $schemeList = GrievanceScheme::all();
	   
	   $scheme = explode(',', $request->input('scheme'));
	   
	   
	   if(!$request->input('scheme')){
                $returnData['msg'] = "Scheme can not be empty";
                return response()->json($returnData);
            }
	   
	   
        DB::beginTransaction();
        try {
		  //------------Grievance Media Table Entry----------------------------
		   
		   if($request->input('district_id')!= NULL || $request->input('block_id')!= NULL || $request->input('gp_id')!= NULL){
			    $district_id = $request->input('district_id');
		   }
		   else{
			  $district_id=0;
		   }
		   
		   if ($request->file('s_document')) {
            $supporting_doc_path = $request->file('s_document')->store('Grievance/Media/SupportingDoc/' .$grievMediaCode);
        		}
		   
            $grievanceMediaEntry= new GrievanceMediaEntry();
		  $grievanceMediaEntry->media_code = $grievMediaCode;
		  $grievanceMediaEntry->name_of_media_publisher = $request->input('name');
		  $grievanceMediaEntry->media_id = $request->input('media_id');
		  $grievanceMediaEntry->district_id = $district_id;
		  $grievanceMediaEntry->block_id = $request->input('block_id');
		  $grievanceMediaEntry->gp_id = $request->input('gp_id');
		  $grievanceMediaEntry->published_date = $request->input('date');
		  $grievanceMediaEntry->description = $request->input('details');
		  $grievanceMediaEntry->supporting_doc_path = $supporting_doc_path;
		  $grievanceMediaEntry->supporting_link = $request->input('s_link');
		  $grievanceMediaEntry->created_by= $users->username;

            if(!$grievanceMediaEntry->save()){
                DB::rollback();
                $returnData['msg'] = "Opps! Something went wrong.";
                return response()->json($returnData);
            }
		   
		   $grievanceMediaMessageTableEntry = new GrievanceMediaMessageTrackTable();
		   $grievanceMediaMessageTableEntry->media_code = $grievMediaCode;
		   $grievanceMediaMessageTableEntry->media_id = $request->input('media_id');
		   $grievanceMediaMessageTableEntry->district_id = $district_id;
		   $grievanceMediaMessageTableEntry->block_id = $request->input('block_id');
		   $grievanceMediaMessageTableEntry->gp_id = $request->input('gp_id');
		   $grievanceMediaMessageTableEntry->created_by= $users->username;
		   
            if(!$grievanceMediaMessageTableEntry->save()){
                DB::rollback();
                $returnData['msg'] = "Opps! Something went wrong.";
                return response()->json($returnData);
            }
           //------------Reference Table Entry--------------------------------
		   
		  $grievanceMediaRef= new GrievanceMediaReference();
            $grievanceMediaRef->media_code=$grievMediaCode;
            $grievanceMediaRef->date_of_entry=$cur_date;
			   
            $grievanceMediaRef->created_by= $users->username;

		if(!$grievanceMediaRef->save()){
			DB::rollback();
			$returnData['msg'] = "Opps! Something went wrong#4.";
			return response()->json($returnData);
		}
		   
		foreach($schemeList as $list){
				
			if(in_array($list->id, $scheme)){
				$grievanceMediaScheme= new GrievanceMediaSchemeEntry();
				$grievanceMediaScheme->media_code = $grievMediaCode;
				$grievanceMediaScheme->scheme_id = $list->id;
				$grievanceMediaScheme->media_id = $request->input('media_id');
				$grievanceMediaScheme->district_id = $district_id;
				$grievanceMediaScheme->block_id =$request->input('block_id');
				$grievanceMediaScheme->gp_id =$request->input('gp_id');
				$grievanceMediaScheme->created_by =$users->username;
				$grievanceMediaScheme->save();
			}
		}
			   if(!$grievanceMediaScheme->save()){
				 DB::rollback();
				 $returnData['msg'] = "Opps! Something went wrong#4.";
				 return response()->json($returnData);
			  }
		   
		   
        }catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng#5".$e->getMessage();
            return $returnData;
        }

        DB::commit();

        $returnData['msg'] = "Data Submitted";
        $returnData['msgType']=true;
        $returnData['data']=[];
        return response()->json($returnData);
   }
	
   private function makeMediaGrievCode($maxVal,$cur_date){
		
		$maxVal=$maxVal+1;
		if(strlen($maxVal)==1){
			$maxVal = "000".$maxVal;
		}
		elseif(strlen($maxVal)==2){
			$maxVal = "00".$maxVal;
		}
		elseif(strlen($maxVal)==3){
			$maxVal = "0".$maxVal;
		}
		
		return "GRV-M/".$cur_date."/"."SG"."-".$maxVal;
		
	}
	
   public function action_panel(Request $request){
	  $cur_date=Carbon::now()->toDateString();
	   
	   $data=[
            'date'=>NULL,
            'actionLevel'=>[],
            'actionTakenBy'=>[],
            'yearWiseMediaData'=>[],
		  'date'=>$cur_date
        ];
	   
	   if ($request->isMethod('post')) {
	   
	   $date= $request->input('search_date');
		
	   $actionLevel = DB::table('grievance_action_levels')->get();
	   
	   $actionTakenBy = GrievanceSubmittedBy::all();
	   
	   $yearWiseMediaData = GrievanceMediaEntry::getReportByDate($date);
	   
	   $imgUrl=ConfigMdas::allActiveList()->imgUrl;
	   
		//echo json_encode($date);   
		  $data['date']= $date;
		   
		   $data = [
			'date'=> $date,
			'actionLevel'=> $actionLevel,
			'actionTakenBy'=> $actionTakenBy,
			'yearWiseMediaData'=> $yearWiseMediaData
		   ];
	   }
	   
	   return view('admin.Grievance.media_grievance_action_panel',compact('cur_date','imgUrl','data'));
   }
	
   public function action_list(Request $request){
	   
	   $date= $request->input('id');
		
	   $actionLevel = DB::table('grievance_action_levels')->get();
	   
	   $actionTakenBy = GrievanceSubmittedBy::all();
	   
	   $yearWiseMediaData = GrievanceMediaEntry::getReportByDate($date);
	   
	   $imgUrl=ConfigMdas::allActiveList()->imgUrl;
	  
	   return view('admin.Grievance.media_grievance_action_list',compact('yearWiseMediaData','actionLevel','actionTakenBy','imgUrl'));
	   
   }
	
   public function mediaActionReportView(Request $request,$m_id){
        
        $id = Crypt::decrypt($m_id);
        
        $media_report_attachment = GrievanceMediaEntry::leftJoin('grievance_media_references as g_ref','g_ref.media_code','grievance_media_entries.media_code')
	   ->where([
            ['grievance_media_entries.id','=',$id],
        ])->select('g_ref.action_file_path')
            ->first();
        
			return response()->file(storage_path('app/'.$media_report_attachment->action_file_path));
    }
	
   public function mediaReplyReportView(Request $request,$m_id){
        
        $id = Crypt::decrypt($m_id);
        
        $media_report_attachment = GrievanceMediaEntry::leftJoin('grievance_media_references as g_ref','g_ref.media_code','grievance_media_entries.media_code')
	   ->where([
            ['grievance_media_entries.id','=',$id],
        ])->select('g_ref.report_file_path')
            ->first();
        
			return response()->file(storage_path('app/'.$media_report_attachment->report_file_path));
    }
	
   public function getMediaData(Request $request){
	   	$returnData['msgType'] = false;
		$returnData['data'] = [];
		$returnData['msg'] = "Oops! Something went wrong!";
		
	   	$id = $request->input('id');
	   
		$mediaData = GrievanceMediaEntry::getDataByMediaId($id);
	   
	   if(!$mediaData){
		    $returnData['msg'] = "You are not authorized to perform this task";
                    return response()->json($returnData);
	   }
		
		$returnData['msgType'] = true;
		$returnData['data'] = $mediaData;
	 	$returnData['msg'] = "Success";
		return response()->json($returnData);
   }
	
   public function action(Request $request){
	   $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";
	   
	   $users=Auth::user(); 
	   
	   $id = $request->input('m_e_id');
	   $cur_date=Carbon::now()->toDateString();
	   $level = $request->input('action_level');
	   
	   $mediaData = GrievanceMediaEntry::getDataByMediaId($id);
	   
	   $messages = [
		  'action_level.required' => 'This is required!',
		   
		  'action_taken_by.required' => 'This is required!',
		   
            'attachment.required' => 'This is required!',
            'attachment.mimes' => 'Document must be in pdf format.',
            'attachment.min' => 'Document size must not be less than 10 KB.',
            'attachment.max' => 'Document size must not exceed 400 KB.',
		   
		  'report_attachment.required_if' => 'This is required!',
		  'report_attachment.mimes' => 'Document must be in pdf format.',
            'report_attachment.min' => 'Document size must not be less than 10 KB.',
            'report_attachment.max' => 'Document size must not exceed 400 KB.',
        ];

        $validatorArray = [
		  'action_level'=>'required',
		  'action_taken_by'=>'required',
            'attachment' => 'required|mimes:pdf|max:400|min:10',
		  'report_attachment' =>'required',
        		Rule::exists('grievance_action_levels')->where(function ($query) {
            $query->where('id', 1);
        }),
		   'report_attachment' => 'mimes:pdf|max:400|min:10',
        ];

	   
	   
 
        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);

        }
	   
	   
	   if ($request->file('attachment')) {
            $doc_path = $request->file('attachment')->store('Grievance/Media/Action/' . $mediaData->name. '/' . $mediaData->id . '/' . $cur_date);
        }
	   if ($request->file('report_attachment')) {
            $doc_path_report = $request->file('report_attachment')->store('Grievance/Media/Report/' . $mediaData->name. '/' . $mediaData->id . '/' . $cur_date);
        }
	   
	   DB::beginTransaction();
        try {
			
			// Condition to check District,Block,GP selection
			   
			if($level == 2 && !($mediaData->district_id) ){
				 $returnData['msg'] = "Grievance not assigned to any District";
				 return $returnData;
			}  
			if($level == 3 && !($mediaData->block_id) ){
				 $returnData['msg'] = "Grievance not assigned to any District/Block";
				 return $returnData;
			}
			if($level == 4 && !($mediaData->gp_id) ){
				 $returnData['msg'] = "Grievance not assigned to any Block/GP";
				 return $returnData;
			}
		   
	   if($level == 1){
		   $updateArray = [
		   'level'=> $level,
		   'action_taken_by'=> $request->input('action_taken_by'),
		   'sent_status'=>1,
		   'action_sent_date'=> $cur_date,
		   'action_taken_status'=> 1,
		   'action_taken_status_date'=> $cur_date,
		   'updated_by'=> $users->username
	   ];
		   
		  if ($doc_path) {
              $updateArray['action_file_path'] = $doc_path;
        		}
		   if ($doc_path_report) {
              $updateArray['report_file_path'] = $doc_path_report;
        		}
		   
		    $messageTableUpdate = [
			    'level'=>$level,
			    'action_taken_by'=>$request->input('action_taken_by'),
			    'sent_status'=>1,
			    'action_sent_date'=>$cur_date,
			    'action_taken_status'=>1,
			    'updated_by'=> $users->username
		   ];
	   }
	   else{
		   $updateArray = [
			   'level'=> $level,
			   'action_taken_by'=> $request->input('action_taken_by'),
			   'sent_status'=>1,
			   'action_sent_date'=> $cur_date,
			   'updated_by'=> $users->username
		   ];


		   if ($doc_path) {
			    $updateArray['action_file_path'] = $doc_path;
		   }
		   
		   $messageTableUpdate = [
			    'level'=>$level,
			    'action_taken_by'=>$request->input('action_taken_by'),
			    'sent_status'=>1,
			    'action_sent_date'=>$cur_date,
			    'updated_by'=> $users->username
		   ];
		}
		   
	   $mediaReferenceUpdate= GrievanceMediaReference::where([
                    ['media_code', '=', $mediaData->media_code],
                ])->update($updateArray);
			  
                if(!$mediaReferenceUpdate){
                    DB::rollback();
                    return response()->json($returnData);
                }
		   
		   
	 	$grievanceMediaMessageTableEntry = GrievanceMediaMessageTrackTable::where([
                    ['media_code', '=', $mediaData->media_code],
		 		 ])->update($messageTableUpdate);
		   
		   		if(!$grievanceMediaMessageTableEntry){
                    DB::rollback();
                    return response()->json($returnData);
                }		
		   
		   
	   }catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng#5".$e->getMessage();
            return $returnData;
        }

        DB::commit();
	   
	   $returnData['msg'] = "Data Submitted";
        $returnData['msgType']=true;
        $returnData['data']=[];
        return response()->json($returnData);
	   
   }
	
   public function reportStatus (Request $request) {
	   
	   $cur_date=Carbon::now()->toDateString();
	   
       $grievMediaRef = GrievanceMediaReference::where('media_code','=',$request->griev_code)->first();
	   $status = $request->status;
	   
	   if($grievMediaRef->level ==1){
		   $returnData['msg'] = "Report submitted by state level cannot be changed";
             return response()->json($returnData);
	   }
	   
	   if($status==1){
		   		$returnData['msg'] = "Report no submitted yet!";
                return response()->json($returnData);	   }
	   
	   DB::beginTransaction();
        try{
	   	  $reportBackup= new GrievanceMediaReportBackup();
		  $reportBackup->media_code = $grievMediaRef->media_code;
		  $reportBackup->report_file_path_backup = $grievMediaRef->report_file_path;
		  $reportBackup->delete_date = $cur_date;
		  
		 if(!$reportBackup->save()){
			  DB::rollback();
                $returnData['msg'] = "Opps! Something went wrong.";
                return response()->json($returnData);
		   }
	   
		  $grievMediaRefUpdate = GrievanceMediaReference::where('media_code','=',$request->griev_code)
			->update([
			   'report_file_path'=>NULL, 'action_taken_status'=>0, 'action_taken_status_date'=>NULL
		   	]);
		   
	    	 if(!$grievMediaRefUpdate){
			 DB::rollback();
                $returnData['msg'] = "Opps! Something went wrong.";
                return response()->json($returnData);
            }
	   }catch(\Exception $e){
            DB::rollback();
            $returnData['msg'] = "Server Exception.";
            return response()->json($returnData);
        }
        DB::commit();
	   
	   
		   $returnData['msgType'] = true;
		   $returnData['msg'] = "Successfully Updated.";
		   return response()->json($returnData);
    }
	
   //--------------------------------------MEDIA GRIEVANCE ENDED-------------------------------------
	
	
   //--------------------------------------INDIVIDUAL GRIEVANCE--------------------------------------
	
	
	public function individual_griev_entry(){
		
		$cur_date=Carbon::now()->toDateString();
		
		$gender = MasterGender::all();
		$scheme = GrievanceScheme::all();
		
		$district= District::all();
		return view('admin.Grievance.individual_grievance_entry',compact('users','district','scheme','cur_date','gender'));
		
	}
	
	public function individual_griev_save (Request $request) {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $users=Auth::user();
		
	   $cur_date=Carbon::now()->toDateString();
	   $initial="GRV-I/".$cur_date;
	   $maxVal = GrievanceEntry::where('entry_date','=',$cur_date)->count();
	   
        $grievCode = $this->makeGrievCode($initial,$maxVal);
		
	   $schemeList = GrievanceScheme::all();	
	   $scheme = explode(',', $request->input('scheme'));
	   
	   
	   if(!$request->input('scheme')){
                $returnData['msg'] = "Scheme can not be empty";
                return response()->json($returnData);
            }

        //--------------------------------VALIDATION ENDED-----------------
		if ($request->file('document')) {
            $doc_path = $request->file('document')->store('Grievance/Individual/Document/' .$grievCode);
        }
		
        DB::beginTransaction();
        try {
		   //------------Grievance Table Entry----------------------------
		   
		   if($request->input('district_id')!=NULL || $request->input('block_id')!=NULL || $request->input('gp_id')!=NULL){
			   $district_id = $request->input('district_id');
		   }
		   else{
			   $district_id = 0;
		   }
            $grievanceEntry= new GrievanceEntry();
		  $grievanceEntry->grievance_code = $grievCode;
		  $grievanceEntry->district_id =$district_id;
		  $grievanceEntry->name = $request->input('name');
		  $grievanceEntry->gender = $request->input('gender');
		  $grievanceEntry->mobile_no = $request->input('mobile_no');
		  $grievanceEntry->email_id = $request->input('email');
		  $grievanceEntry->block_id = $request->input('block_id');
		  $grievanceEntry->gp_id = $request->input('gp_id');
		  $grievanceEntry->address = $request->input('address');
		  $grievanceEntry->grievance_details = $request->input('details');
		  $grievanceEntry->entry_date = $cur_date;
		  $grievanceEntry->created_by= $users->username;

            if(!$grievanceEntry->save()){
                DB::rollback();
                $returnData['msg'] = "Opps! Something went wrong#3.";
                return response()->json($returnData);
            }
		  $entry_id = Crypt::encrypt($grievanceEntry->id);
		   
           //------------Reference Table Entry--------------------------------
		   
		  $grievanceRef= new GrievanceReference();
            $grievanceRef->grievance_code=$grievCode;
            $grievanceRef->reference_code=$request->input('ref_code');
            $grievanceRef->reference_date=$request->input('ref_date');
            $grievanceRef->entry_level=1;
            $grievanceRef->action_level=1;
			   
            $grievanceRef->created_by= $users->username;
		  if ($doc_path) {
			    $grievanceRef->document = $doc_path;
		   }
			  if(!$grievanceRef->save()){
				 DB::rollback();
				 $returnData['msg'] = "Opps! Something went wrong#4.";
				 return response()->json($returnData);
			  }
		   
		   foreach($schemeList as $list){
				
			if(in_array($list->id, $scheme)){
				$grievanceScheme= new GrievanceSchemeEntry();
				$grievanceScheme->grievance_code = $grievCode;
				$grievanceScheme->scheme_id = $list->id;
				$grievanceScheme->district_id = $district_id;
				$grievanceScheme->block_id =$request->input('block_id');
				$grievanceScheme->gp_id =$request->input('gp_id');
				$grievanceScheme->created_by =$users->username;
				$grievanceScheme->save();
			}
		}
			   if(!$grievanceScheme->save()){
				 DB::rollback();
				 $returnData['msg'] = "Opps! Something went wrong#4.";
				 return response()->json($returnData);
			  }
            
        }catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng#5".$e->getMessage();
            return $returnData;
        }

        DB::commit();

        $returnData['msg'] = "Data Submitted";
        $returnData['msgType']=true;
        $returnData['data']=['entry_id'=>$entry_id];
        return response()->json($returnData);

    }
	
	private function makeGrievCode($initial,$maxVal){
		
		$maxVal=$maxVal+1;
		if(strlen($maxVal)==1){
			$maxVal = "0000".$maxVal;
		}
		elseif(strlen($maxVal)==2){
			$maxVal = "000".$maxVal;
		}
		elseif(strlen($maxVal)==3){
			$maxVal = "00".$maxVal;
		}
		elseif(strlen($maxVal)==4){
			$maxVal = "0".$maxVal;
		}
		
		return $initial."/"."L1-".$maxVal;
		
	}
	
	public function griev_confirm_page(Request $request){
		
		$id = Crypt::decrypt($request->input('entry_id'));
		
		$GrievCode = GrievanceEntry::getGrievCodeById($id);
		
		
		return view('admin.Grievance.individual_grievance_confirmation',compact('GrievCode','id'));
	}
	
	public function individual_griev_list(){
		
		$grievList = GrievanceEntry::getGrievanceList();
		
		$actionLevel = DB::table('grievance_action_levels')->get();
		
		$actionTakenBy = GrievanceSubmittedBy::all();
		
		return view('admin.Grievance.individual_grievance_list',compact('grievList','actionLevel','actionTakenBy'));
		
	}
	
	public function details($id){
		
		$griev_id=Crypt::decrypt($id);
		
		$grievData = GrievanceEntry::getGrievDataById($griev_id);
		
		
		return view('admin.Grievance.individual_griev_details',compact('grievData','griev_id'));
	}
	
     public function individualDocumentView(Request $request,$id){
        
        $id = Crypt::decrypt($id);
        
        $individual_doc = GrievanceEntry::leftJoin('grievance_references as g_ref','g_ref.grievance_code','grievance_entries.grievance_code')
	   ->where([
            ['grievance_entries.id','=',$id],
        ])->select('g_ref.document')
            ->first();
        
			return response()->file(storage_path('app/'.$individual_doc->document));
    }
	
	public function getGrievData(Request $request){
	   	$returnData['msgType'] = false;
		$returnData['data'] = [];
		$returnData['msg'] = "Oops! Something went wrong!";
		
	   	$id = $request->input('id');
	   
		$grievData = GrievanceEntry::getGrievDataById($id);
	   
	   if(!$grievData){
		    $returnData['msg'] = "You are not authorized to perform this task";
                    return response()->json($returnData);
	   }
		
		$returnData['msgType'] = true;
		$returnData['data'] = ['grievData'=>$grievData];
	 	$returnData['msg'] = "Success";
		return response()->json($returnData);
   }
	
	public function individual_griev_action(Request $request){
	   $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";
	   
	   $users=Auth::user(); 
	   
	   $id = $request->input('griev_id');
	   $cur_date=Carbon::now()->toDateString();
	   $level = $request->input('action_level');
	   
	   $grievData = GrievanceEntry::getGrievCodeById($id);
	   $grievDetails = GrievanceEntry::getGrievDataById($id);
	   
	   $griev_code = Crypt::encrypt($grievData->grievance_code);
		
	   $messages = [
		  'action_level.required' => 'This is required!',
		   
            'report_attachment.required_if' => 'This is required!',
            'report_attachment.mimes' => 'Document must be in pdf format.',
            'report_attachment.min' => 'Document size must not be less than 10 KB.',
            'report_attachment.max' => 'Document size must not exceed 400 KB.',
        ];

        $validatorArray = [
		   'action_level'=>'required',
            'report_attachment' => 'required_if:action_level,==,1|mimes:pdf|max:400|min:10',
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);

        }
	   
	   DB::beginTransaction();
        try {
		   
		// Condition to check District,Block,GP selection
		   
		if($level == 2 && !($grievDetails->district_id) ){
			 $returnData['msg'] = "Grievance not assigned to any District";
			 return $returnData;
		}  
		if($level == 3 && !($grievDetails->block_id) ){
			 $returnData['msg'] = "Grievance not assigned to any District/Block";
			 return $returnData;
		}
		if($level == 4 && !($grievDetails->gp_id) ){
			 $returnData['msg'] = "Grievance not assigned to any Block/GP";
			 return $returnData;
		}
	   	
	   if($level == 1){
		   
		  if ($request->file('report_attachment'))
		  {
            	$doc_path = $request->file('report_attachment')->store('Grievance/Individual/Action/ReplyDocument/'.$grievData->grievance_code);
        	  }
		   
		 $updateArray = [
		   'action_level'=> $level,
		   'sent_status'=>1,
		   'reply_status'=>1,
		   'reply_date'=>$cur_date,
		   'sent_date'=> $cur_date,
		   'action_taken_by'=> $request->action_taken_by,
		   'reply_document'=>$doc_path,
		   'updated_by'=> $users->username 
		   ];
	   }  else{ 
		   
	   $updateArray = [
		   'action_level'=> $level,
		   'sent_status'=>1,
		   'sent_date'=> $cur_date,
		   'action_taken_by'=> $request->action_taken_by,
		   'transferred_status'=>1,
		   'updated_by'=> $users->username
	   ];
       }
	   
	   $grievReferenceUpdate= GrievanceReference::where([
                    ['grievance_code', '=', $grievData->grievance_code],
                ])->update($updateArray);
			  
                if(!$grievReferenceUpdate){
                    DB::rollback();
                    return response()->json($returnData);
                }
		   
	   }catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng#5".$e->getMessage();
            return $returnData;
        }

        DB::commit();
	   
	   $returnData['msg'] = "Data Submitted";
        $returnData['msgType']=true;
        $returnData['data']=['grievance_code'=>$griev_code];
        return response()->json($returnData);
	   
   }
	
	
	//--Message Sending for Individual Grievance Grievance-----------
	
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
	
	
	private function sendOtp($mobileNo, $otp) {

        //$endpoint = "http://202.65.131.85/attendance_monitor/public/send_otp";
        $endpoint = "http://202.65.131.85/attendance_monitor/public/send_msg_siprd";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $endpoint, ['query' => [
                'mobile_no' => $mobileNo,
                'otp' => $otp,
        ]]);


        $statusCode = $response->getStatusCode();
        $content = $response->getBody();
        return $content;
    }
	
	public function entry_msg(Request $request){
		
		   $returnData['msgType'] = false;
		   $returnData['data'] = [];
		   $returnData['msg'] = "Error";
		
	   $griev_id = Crypt::decrypt($request->input('id'));
		
       $cur_date=Carbon::now()->toDateString();

		
		$result = GrievanceEntry::join('grievance_references as ref','ref.grievance_code','=','grievance_entries.grievance_code')
			->leftJoin('districts as d','d.id','=','grievance_entries.district_id')
			->where([
			['grievance_entries.id','=',$griev_id]
		])->select('ref.grievance_code','d.district_name','grievance_entries.mobile_no','grievance_entries.entry_date','ref.entry_level')->first();
		
		$date=  Carbon::parse($result->entry_date)->format('d/m/Y');
		
		if($result->entry_level == 1){
			$message_body = "Grievance registered with code $result->grievance_code dated: $date at State.Kindly use it for future reference";
		}
		else{
			$message_body = "Grievance registered with code $result->grievance_code dated: $date at District: $result->district_name .Kindly use it for future reference";
		}
		
			
				
		$response= $this->sendOtp($result->mobile_no,$message_body);
		
		if($response){
			$individualGrievMsgTrack = new GrievanceIndividualMsgTrackTable();
			$individualGrievMsgTrack->grievance_code = $result->grievance_code;
			$individualGrievMsgTrack->msg_sent = 1;
			$individualGrievMsgTrack->msg_sent_body = $message_body;
			$individualGrievMsgTrack->msg_sent_date = $cur_date;
			
			if(!$individualGrievMsgTrack->save()){
				 $returnData['msg'] = "Opps! Something went wrong#4.";
				 return response()->json($returnData);
			}
			   $returnData['msg'] = "Success";
			   $returnData['msgType']=true;
			   $returnData['data']=[$response];
			   			
		}
					
				
			
 	 return json_encode($returnData);
	}
	
	public function action_msg(Request $request){
		
		
	   $returnData['msgType'] = false;
	   $returnData['data'] = [];
	   $returnData['msg'] = "Error";
		
	   $users=Auth::user();	
	   $griev_code = Crypt::decrypt($request->input('code'));
		
        $cur_date=Carbon::now()->toDateString();

		
		$result = GrievanceEntry::join('grievance_references as ref','ref.grievance_code','=','grievance_entries.grievance_code')
			->leftJoin('districts as d','d.id','=','grievance_entries.district_id')
			->where([
			['grievance_entries.grievance_code','=',$griev_code]
		])->select('ref.grievance_code','d.district_name','grievance_entries.mobile_no','grievance_entries.entry_date','ref.action_level')->first();
		
		$date=  Carbon::parse($result->entry_date)->format('d/m/Y');
		
		$message_body = "Grievance resolved with code $result->grievance_code dated: $date at State. Contact concerned department for further clarification.";
			
				
		$response= $this->sendOtp($result->mobile_no,$message_body);
		
		if($response){
			
			$updateArray = [
			   'msg_sent_at_action'=> 1,
			   'msg_sent_at_action_date'=>$cur_date,
			   'msg_sent_at_action_body'=> $message_body,
			   'updated_by'=> $users->username
			];
			
			$individualGrievMsgTrackUpdate= GrievanceIndividualMsgTrackTable::where([
                    ['grievance_code', '=', $griev_code],
                ])->update($updateArray);
			  
                if(!$individualGrievMsgTrackUpdate){
				$returnData['msg'] = "Opps! Something went wrong#10.";
                    return response()->json($returnData);
                }
			
			
			   $returnData['msg'] = "Success";
			   $returnData['msgType']=true;
			   $returnData['data']=[$response];
			   			
		}
					
				
			
 	 return json_encode($returnData);
		
		
	}
	
	
	//Dashboard Reports
	
	public function scheme_report($id){
		
		$scheme_id=Crypt::decrypt($id);
		
		$district= District::all();
		
		$schemeName = GrievanceScheme::where('id','=',$scheme_id)
					->select('scheme_name')
					->first();
		$head_text = "District wise Report under ".$schemeName->scheme_name ;
		
		$schemeWiseIndividualGriev = GrievanceEntry::getDistrictWiseIndividualScheme($scheme_id);
		
		$schemeWiseMediaGriev = GrievanceMediaEntry::getDistrictWiseMediaScheme($scheme_id);
		
		$data = [
			'schemeWiseIndividualGriev'=>$schemeWiseIndividualGriev,
			'schemeWiseMediaGriev'=>$schemeWiseMediaGriev,
		];
		
		//echo json_encode($schemeWiseMediaGriev);
		return view('admin.Grievance.DashboardReport.scheme_wise_report',compact('district','head_text','data'));
	}
	
	
	public function type_report($griev_type){
		
		$type=$griev_type;
		
		$district= District::all();
		
		
		if($type=="INDIVIDUAL"){
			
		$districtWiseGrievRecieved = GrievanceEntry::getDistrictWiseIndividualRecievedData();
		$districtWiseGrievDisposed = GrievanceEntry::getDistrictWiseIndividualDisposedData();
		
		$head_text = "District wise Report under ".$type. " Grievance" ;
		}
		else{
			
		$districtWiseGrievRecieved = GrievanceMediaEntry::getDistrictWiseMediaRecievedData();
		$districtWiseGrievDisposed = GrievanceMediaEntry::getDistrictWiseMediaDisposedData();
		
		$head_text = "District wise Report under ".$type. " Grievance" ;
		}
		
		$data = [
			'districtWiseGrievRecieved'=>	$districtWiseGrievRecieved,
			'districtWiseGrievDisposed'=>	$districtWiseGrievDisposed,
		];
		
		
		//echo json_encode($districtWiseGrievRecieved);
		
		return view('admin.Grievance.DashboardReport.type_wise_report',compact('district','data','head_text'));
	}
	
	public function msg_sent($mobile,$msg){
		$endpoint = "http://202.65.131.85/attendance_monitor/public/send_msg_siprd";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $endpoint, ['query' => [
                'mobile_no' => $mobile,
                'otp' => $msg,
        ]]);


        $statusCode = $response->getStatusCode();
        $content = $response->getBody();
        return $content;
	}
	
   //-----------------------------------INDIVIDUAL GRIEVANCE ENDED-----------------------------------
	
}
