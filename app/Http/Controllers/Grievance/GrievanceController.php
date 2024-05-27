<?php

namespace App\Http\Controllers\Grievance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\CommonModels\District;
use App\CommonModels\Block;
use App\CommonModels\GramPanchyat;
use App\Grievance\GrievanceEntry;
use App\Grievance\GrievanceReference;
use App\Grievance\GrievanceMediaEntry;
use App\Grievance\GrievanceMediaReference;
use App\Grievance\GrievanceMediaTransferAction;
use App\Grievance\GrievanceTransferAction;

use App\Grievance\GrievanceScheme;
use App\Grievance\GrievanceSchemeEntry;
use App\Grievance\GrievanceSubmittedBy;
use App\Grievance\GrievanceMediaMessageTrackTable;
use App\Grievance\GrievanceIndividualMsgTrackTable;
use App\Master\MasterGender;
use Carbon\Carbon;
use Crypt;
use DB;
use Validator;
class GrievanceController extends Controller
{
     //------dashboard--------------
	
    public function index(){
	    
	    $users = Auth::user();
	    
	    $data=[];
	    
	    if(Auth::user()->mdas_master_role_id==2 || Auth::user()->mdas_master_role_id==7)
	    {	
		    $level=2; 
		    $id = $users->district_code;
		    $b_id = NULL;
		    $gp_id = NULL;
	    }
	    else if(Auth::user()->mdas_master_role_id==3)
	    {
		    $id = $users->district_code;
		    $block_id = Block::getBlockIdByAnchalikId($users->ap_id);
		    $b_id =$block_id->id;
		    $gp_id=NULL;
		    $level=3; 
	    }
	    else if(Auth::user()->mdas_master_role_id==8){
		    	$id = $users->district_code;
		     $b_id = $users->block_id;
		     $gp_id=NULL;
			$level=3; 
	    }
	    else if(Auth::user()->mdas_master_role_id==4)
	    {
		    $id = $users->district_code;
		    $block_id = Block::getBlockIdByAnchalikId($users->ap_id);
		    $b_id =$block_id->id;
		    $gp_id = $users->gp_id;
		    $level=4; 
	    }
	    else if(Auth::user()->mdas_master_role_id==9){
		    $id = $users->district_code;
		    $b_id = $users->block_id;
		    $gp_id = $users->gp_id;
		    $level=4; 
	    }
	    else{
		   
	    }
	    $mediaRecievedData = GrievanceMediaEntry::getRecievedData($id,$b_id,$gp_id,$level);
	    $mediaDisposedData = GrievanceMediaEntry::getDisposedData($id,$b_id,$gp_id,$level);
	    
	    $individualGrievRecievedData=GrievanceEntry::getRecievedData($id,$b_id,$gp_id,$level);
	    $individualGrievDisposedData=GrievanceEntry::getDisposedData($id,$b_id,$gp_id,$level);
	    
	    $data =[
		    'mediaRecievedData'=>$mediaRecievedData,
		    'mediaDisposedData'=>$mediaDisposedData,
		    'individualGrievRecievedData'=>$individualGrievRecievedData,
		    'individualGrievDisposedData'=>$individualGrievDisposedData,
	    ];
	    
	    //echo json_encode($mediaRecievedData);
	    
	    return view('Grievance.griev_dashboard',compact('grievList','data','id'));
    }
	
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

	
    //----------------------------Individual Grievance-----------------------------------------
	
	
    //-------Grievance Form----------
	/*
    public function new_griev(Request $request){
		
		$users=Auth::user();
		
		if(Auth::user()->mdas_master_role_id==2)
		{
			$userData = ZilaParishad::getZPName($users->zp_id);
			
		}
		elseif(Auth::user()->mdas_master_role_id==3){
			$userData = AnchalikParishad::getZpAp($users->ap_id);
		}
		elseif(Auth::user()->mdas_master_role_id==4){
			$userData = GramPanchyat::getZpApGpByGpId($users->gp_id);
		}
		elseif(Auth::user()->mdas_master_role_id==6){
			$userData = SiprdExtensionCenter::where([
				['id','=',$users->ex_id]
			])->select('id','extension_center_name')->first();
		}
		
		$userInfo = MdasUser::where([
				['username','=',$users->username],
			])->select('f_name','m_name','l_name','designation','mobile','email','address')
			  ->first();
		
		return view('Grievance.Daily.new_griev',compact('users','userData','userInfo'));
	}
	
    //------Grievance Submission---------
	
     public function griev_save (Request $request) {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $users=Auth::user();
		
		if(Auth::user()->mdas_master_role_id==2)
		{
			$id=$users->zp_id;
			$initial="DU";
			$maxVal = GrievanceEntry::where('zp_id','=',$id)->count();
			
		}
		elseif(Auth::user()->mdas_master_role_id==3){
			$id=$users->zp_id;
			$ap_id=$users->ap_id;
			$initial="AU";
			$maxVal = GrievanceEntry::where('ap_id','=',$ap_id)->count();
		}
		elseif(Auth::user()->mdas_master_role_id==4){
			$id=$users->zp_id;
			$gp_id=$users->gp_id;
			$initial="GU";
			$maxVal = GrievanceEntry::where('gp_id','=',$gp_id)->count();
		}
		
		$userInfo = MdasUser::where([
				['username','=',$users->username],
			])->first();
	   
        $grievCode = $this->makeGrievCode($id,$maxVal,$initial);

        //--------------------------------VALIDATION ENDED-----------------
		
		
        DB::beginTransaction();
        try {
		   //------------Grievance Table Entry----------------------------
		   
            $grievanceEntry= new GrievanceEntry();
		  $grievanceEntry->grievance_code = $grievCode;
		  $grievanceEntry->district_id = $userInfo->zp_id;
		  $grievanceEntry->f_name = $request->input('f_name');
		  $grievanceEntry->m_name = $request->input('m_name');
		  $grievanceEntry->l_name = $request->input('l_name');
		  $grievanceEntry->mobile_no = $request->input('mobile_no');
		  $grievanceEntry->email_id = $request->input('email');
		  $grievanceEntry->scheme = $request->input('scheme');
		  $grievanceEntry->block_id = $userInfo->ap_id;
		  $grievanceEntry->gp_id = $userInfo->gp_id;
		  $grievanceEntry->grievance_details = $request->input('details');
		  $grievanceEntry->entry_date = Carbon::now()->toDateString();
		  $grievanceEntry->created_by= $users->username;

            if(!$grievanceEntry->save()){
                DB::rollback();
                $returnData['msg'] = "Opps! Something went wrong#3.";
                return response()->json($returnData);
            }

           //------------Reference Table Entry--------------------------------
		   
		  $grievanceRef= new GrievanceReference();
            $grievanceRef->grievance_code=$grievCode;
            $grievanceRef->reference_code=$request->input('ref_code');
            $grievanceRef->reference_date=$request->input('ref_date');
			   
            $grievanceRef->created_by= $users->username;

			  if(!$grievanceRef->save()){
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
	
    //------Function to Make Grievance Code-----
	
	private function makeGrievCode($id,$maxVal,$initial){
		
		
		$maxVal=$maxVal+1;
		if(strlen($maxVal)==1){
			$maxVal = "00000".$maxVal;
		}
		elseif(strlen($maxVal)==2){
			$maxVal = "0000".$maxVal;
		}
		elseif(strlen($maxVal)==3){
			$maxVal = "000".$maxVal;
		}
		elseif(strlen($maxVal)==4){
			$maxVal = "00".$maxVal;
		}
		elseif(strlen($maxVal)==5){
			$maxVal = "0".$maxVal;
		}
		
		return "GRIEV-".$id."/".$initial."-".$maxVal;
		
	}
	
	public function grievance_show(){
		
		$grievList= GrievanceEntry::getGrievListByUser();
	    
	    return view('Grievance.Daily.griev_table_view',compact('grievList'));
	}
	
	public function details($id){
		
		$griev_id=Crypt::decrypt($id);
		
		$grievData = GrievanceEntry::getGrievDataById($griev_id);
		
		return view('Grievance.Daily.griev_details',compact('grievData'));
	}
	*/
	//-------------------------------Media Grievance----------------------------------------------
	
	public function media_details(){
		
	    $users = Auth::user();
	    $actionLevel = DB::table('grievance_action_levels')->get();
	    $actionTakenBy = GrievanceSubmittedBy::all();
	    
		if(Auth::user()->mdas_master_role_id==2 || Auth::user()->mdas_master_role_id==7)
	    {	
		    $level=2; 
		    $district_id = $users->district_code;
		    $b_id = NULL;
		    $gp_id = NULL;
	    }
	    else if(Auth::user()->mdas_master_role_id==3)
	    {
		    $district_id = $users->district_code;
		    $block_id = Block::getBlockIdByAnchalikId($users->ap_id);
		    $b_id =$block_id->id;
		    $gp_id=NULL;
		    $level=3; 
	    }
	    else if(Auth::user()->mdas_master_role_id==8){
		    	$district_id = $users->district_code;
		     $b_id = $users->block_id;
		     $gp_id=NULL;
			$level=3; 
	    }
	    else if(Auth::user()->mdas_master_role_id==4)
	    {
		    $district_id = $users->district_code;
		    $block_id = Block::getBlockIdByAnchalikId($users->ap_id);
		    $b_id =$block_id->id;
		    $gp_id = $users->gp_id;
		    $level=4; 
	    }
	    else if(Auth::user()->mdas_master_role_id==9){
		    $district_id = $users->district_code;
		    $b_id = $users->block_id;
		    $gp_id = $users->gp_id;
		    $level=4; 
	    }
	    else{
		   
	    }
		
		$mediaData = GrievanceMediaEntry::getMediaById($district_id,$b_id,$gp_id,$level);
		
		return view('Grievance.Media.media_details',compact('mediaData','actionLevel','users','actionTakenBy'));
		
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
		
		$returnData['msgType'] = true;
		$returnData['data'] = $mediaData;
	 	$returnData['msg'] = "Success";
		return response()->json($returnData);
   }
	
	public function action (Request $request) {
	
	$returnData['msgType'] = false;
     $returnData['data'] = [];
     $returnData['msg'] = "Oops! Something went wrong!";	
		
	$id = $request->input('m_e_id');
	$users=Auth::user();
	$cur_date=Carbon::now()->toDateString();
	$mediaData = GrievanceMediaEntry::getDataByMediaId($id);
	    if(Auth::user()->mdas_master_role_id==2 || Auth::user()->mdas_master_role_id==7)
	    {
		    $level=2; 
	    }
	    else if(Auth::user()->mdas_master_role_id==3 || Auth::user()->mdas_master_role_id==8)
	    {
		    $level=3; 
	    }
	    else if(Auth::user()->mdas_master_role_id==4 || Auth::user()->mdas_master_role_id==9)
	    {
		    $level=4; 
	    }
	    else{
		   
	    }
		
	  if ($request->file('report_attachment')) {
            $doc_path = $request->file('report_attachment')->store('Grievance/Media/Report/' . $mediaData->name. '/' . $mediaData->id . '/' . $cur_date);
        }
		
	  DB::beginTransaction();
        try {
		   $updateArray = [
			   'action_taken_status_date'=>$cur_date,
			   'action_taken_status'=>1,
			   'updated_by'=> $users->username
		   ];

		   if ($doc_path) {
			    $updateArray['report_file_path'] = $doc_path;
		   }

		   $media_action_status = GrievanceMediaReference::where([
			   ['media_code','=',$mediaData->media_code],
			   ['level','=',$level]
		   ])->update($updateArray);

			if(!$media_action_status){
					DB::rollback();
					$returnData['msg'] = "You are not authorized to perform this task";
					return response()->json($returnData);
			  }

		   $grievanceMediaMessageTableEntry = GrievanceMediaMessageTrackTable::where([
			   ['media_code','=',$mediaData->media_code],
			   ['level','=',$level]
		   ])->update([
			   'action_taken_status'=>1,
			   'updated_by'=> $users->username
		   ]);
		   
		   if(!$grievanceMediaMessageTableEntry){
					DB::rollback();
					$returnData['msg'] = "You are not authorized to perform this task";
					return response()->json($returnData);
			  }
		   
	   }catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng#5".$e->getMessage();
            return $returnData;
        }

        DB::commit();
		
		
	   $returnData['msg'] = "Status change successfully.";
        $returnData['msgType']=true;
        $returnData['data']=[];
        return response()->json($returnData);
        
    }
	
	public function transfer_action(Request $request){
	   
	   $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";
	   
	   $users=Auth::user(); 
	   
	   $id = $request->input('m_e_id');
	   $cur_date=Carbon::now()->toDateString();
	   $forward_from = $request->input('forward_from');
	   $forward_to = $request->input('forward_to');
	   $action_taken_by = $request->input('action_taken_by');
	   
	   $mediaData = GrievanceMediaEntry::getDataByMediaId($id);
	   
	   $messages = [
            'forward_from.required' => 'This is required!',
            'forward_to.required' => 'This is required!',
            'action_taken_by.required' => 'This is required!',
            
        ];
		
        $validatorArray = [
            'forward_from' => 'required',
            'forward_to' => 'required',
            'action_taken_by' => 'required',
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
		   
		   if($forward_to == 1){
			 $returnData['msg'] = "Unauthorized Access to state";
                return response()->json($returnData);
		   }
		   // Condition to check District,Block,GP selection
		   
			if($forward_to == 2 && !($mediaData->district_id) ){
				 $returnData['msg'] = "Grievance not assigned to any District";
				 return $returnData;
			}  
			if($forward_to == 3 && !($mediaData->block_id) ){
				 $returnData['msg'] = "Grievance not assigned to any District/Block";
				 return $returnData;
			}
			if($forward_to == 4 && !($mediaData->gp_id) ){
				 $returnData['msg'] = "Grievance not assigned to any Block/GP";
				 return $returnData;
			}
		   $updateArray = [
			   'level'=> $forward_to,
			   'transferred_status'=> 1,
			   'action_taken_by'=> $action_taken_by,
			   'updated_by'=> $users->username
		   ];
		   
		   //------------Grievance Media Transfer Table Entry----------------------------
            $mediaTransferEntry= new GrievanceMediaTransferAction();
		  $mediaTransferEntry->media_code = $mediaData->media_code;
		  $mediaTransferEntry->transferred_from = $forward_from;
		  $mediaTransferEntry->transferred_to =$forward_to;
		  $mediaTransferEntry->transfer_date = $cur_date;
		  $mediaTransferEntry->created_by= $users->username;

            if(!$mediaTransferEntry->save()){
                DB::rollback();
                $returnData['msg'] = "Opps! Something went wrong.";
                return response()->json($returnData);
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
                ])->update([
			    'level'=> $forward_to,
			   'action_taken_by'=> $action_taken_by,
			   'updated_by'=> $users->username
		    ]);
		   
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
	
	
	//-----------------------------INDIVIDUAL GRIEVANCE--------------------------------------------
	
	public function individual_griev_entry(){
		
		$cur_date=Carbon::now()->toDateString();
		
		$gender = MasterGender::all();
		
		$scheme = GrievanceScheme::all();
		
		$users = Auth::user();
		
		if(Auth::user()->mdas_master_role_id==2){
			$d_id = $users->district_code;
			$dictrictData = District::getDistrictName($d_id);
			$blockData = Block::getBlocksByDistrictId($d_id);
		}
		else if(Auth::user()->mdas_master_role_id==3){
			$d_id = $users->district_code;
			$dictrictData = District::getDistrictName($d_id);
			$b_id = Block::getBlockIdByAnchalikId($users->ap_id);
			$blockData = Block::getBlockNameById($b_id->id);
			
		}
		else if(Auth::user()->mdas_master_role_id==4){
			$d_id = $users->district_code;
			$dictrictData = District::getDistrictName($d_id);
			$b_id = Block::getBlockIdByAnchalikId($users->ap_id);
			$blockData = Block::getBlockNameById($b_id->id);
			$gp_id = $users->gp_id;
			$gpData= GramPanchyat::getGPsByGpId($gp_id);
		}
		else if(Auth::user()->mdas_master_role_id==7){
			$d_id = $users->district_code;
			$dictrictData = District::getDistrictName($d_id);
			$blockData = Block::getBlocksByDistrictId($d_id);
		}
		else if(Auth::user()->mdas_master_role_id==8){
			$d_id = $users->district_code;
			$dictrictData = District::getDistrictName($d_id);
			$b_id = $users->block_id;
			$blockData = Block::getBlockNameById($b_id);
		}
		
		else if(Auth::user()->mdas_master_role_id==9){
			$d_id = $users->district_code;
			$dictrictData = District::getDistrictName($d_id);
			$b_id = $users->block_id;
			$blockData = Block::getBlockNameById($b_id);
			$gp_id = $users->gp_id;
			$gpData= GramPanchyat::getGPsByGpId($gp_id);
		}
		else{
			
		}
		
		$district= District::all();
		return view('Grievance.Individual.individual_grievance_entry',compact('users','district','scheme','cur_date','gender','dictrictData','blockData','gpData'));
		
	}
	
	public function individual_griev_save(Request $request) {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $users=Auth::user();
		
		if(Auth::user()->mdas_master_role_id==2 || Auth::user()->mdas_master_role_id==7)
		{
			$level = 2;
			$code=$users->district_code;
			$level_code = "L2";
		}
		elseif(Auth::user()->mdas_master_role_id==3 || Auth::user()->mdas_master_role_id==8)
		{
			$level = 3;
			$code=$users->district_code;
			$level_code = "L3";
		}
		elseif(Auth::user()->mdas_master_role_id==4 || Auth::user()->mdas_master_role_id==9)
		{
			$level = 4;
			$code=$users->district_code;
			$level_code = "L4";
		}
		else
		{
			$level = 1;
		}
		
		
		$cur_date=Carbon::now()->toDateString();
		$initial="GRV-I/".$cur_date;
		$maxVal = GrievanceEntry::where('entry_date','=',$cur_date)->count();
		
	   $schemeList = GrievanceScheme::all();	
	   $scheme = explode(',', $request->input('scheme'));
	   
        $grievCode = $this->makeGrievCode($initial,$maxVal,$code,$level_code);

        //--------------------------------VALIDATION ENDED-----------------
	
	  if ($request->file('document')) {
            $doc_path = $request->file('document')->store('Grievance/Individual/Document/' .$grievCode);
        }
		
        DB::beginTransaction();
        try {
		   //------------Grievance Table Entry----------------------------
		   
            $grievanceEntry= new GrievanceEntry();
		  $grievanceEntry->grievance_code = $grievCode;
		  $grievanceEntry->district_id =$request->input('district_id');
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
            $grievanceRef->entry_level=$level;
            $grievanceRef->action_level=$level;
            $grievanceRef->reference_code=$request->input('ref_code');
            $grievanceRef->reference_date=$request->input('ref_date');
			
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
				$grievanceScheme->district_id = $request->input('district_id');
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
		$this->sendRegistrationMessage($request->input('mobile_no'),$grievCode);
        return response()->json($returnData);
		
    }
	private function sendRegistrationMessage($mobile,$message){
		
		$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "http://2factor.in/API/V1/fad5d8c4-a961-11eb-80ea-0200cd936042/SMS/9101379463/1000",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_POSTFIELDS => "",
			  CURLOPT_HTTPHEADER => array(
				"content-type: application/x-www-form-urlencoded"
			  ),
			));
			
			$response = curl_exec($curl);
			$err = curl_error($curl);
			
			curl_close($curl);
			
			if ($err) {
			  echo "cURL Error #:" . $err;
			} else {
			  echo $response;
			}
	}
	private function makeGrievCode($initial,$maxVal,$code,$level_code){
		
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
		
		return $initial."/".$code.$level_code."-".$maxVal;
		
	}
	
	public function griev_confirm_page(Request $request){
		
		$id = Crypt::decrypt($request->input('entry_id'));
		
		$GrievCode = GrievanceEntry::getGrievCodeById($id);
		
		
		return view('Grievance.Individual.individual_grievance_confirmation',compact('GrievCode','id'));
	}
	
	public function individual_griev_list(){
		
		$users=Auth::user();
		
		$grievList = GrievanceEntry::getGrievanceListByLevel();
		
		$actionLevel = DB::table('grievance_action_levels')->get();
		
		$actionTakenBy = GrievanceSubmittedBy::all();
		
		return view('Grievance.Individual.individual_grievance_list',compact('grievList','users','actionLevel','actionTakenBy'));
		
	}
	
	public function individual_level_wise_details(){
		
		$individualGrievData = GrievanceEntry::getIndividualData();
		
		return view('Grievance.Individual.individual_grievance_level_wise_list',compact('individualGrievData'));
	}
	
	public function details($id){
		
		$griev_id=Crypt::decrypt($id);
		
		$grievData = GrievanceEntry::getGrievDataById($griev_id);
		
		
		return view('Grievance.Individual.individual_griev_details',compact('grievData','griev_id'));
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
		
	   $griev_code = Crypt::encrypt($grievData->grievance_code);
		
	   $messages = [
		  'action_level.required' => 'This is required!',
		   
		   'action_taken_by.required' => 'This is required!',
		   
            'report_attachment.required' => 'This is required!',
            'report_attachment.mimes' => 'Document must be in pdf format.',
            'report_attachment.min' => 'Document size must not be less than 10 KB.',
            'report_attachment.max' => 'Document size must not exceed 400 KB.',
        ];

        $validatorArray = [
		   'action_level'=>'required',
		   'action_taken_by'=>'required',
            'report_attachment' => 'required|mimes:pdf|max:400|min:10',
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
		   
		  if ($request->file('report_attachment'))
		  {
            	$doc_path = $request->file('report_attachment')->store('Grievance/Individual/Action/ReplyDocument/'.$grievData->grievance_code);
        	  }
		   
		 $updateArray = [
		   'action_level'=> $level,
		   'reply_status'=>1,
		   'reply_date'=>$cur_date,
		   'action_taken_by'=> $request->action_taken_by,
		   'reply_document'=>$doc_path,
		   'updated_by'=> $users->username 
		   ];
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
	
	
	public function griev_transfer_action(Request $request){
	   
	   $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";
	   
	   $users=Auth::user(); 
	   
	   $id = $request->input('griev_id1');
	   $cur_date=Carbon::now()->toDateString();
	   $forward_from = $request->input('forward_from');
	   $forward_to = $request->input('forward_to');
	   $action_taken_by = $request->input('action_taken_by1');
	   
	    $grievData = GrievanceEntry::getGrievCodeById($id);
		$grievDetails = GrievanceEntry::getGrievDataById($id);
	   
	   $messages = [
            'forward_from.required' => 'This is required!',
            'forward_to.required' => 'This is required!',
            'action_taken_by1.required' => 'This is required!',
            
        ];
		
        $validatorArray = [
            'forward_from' => 'required',
            'forward_to' => 'required',
            'action_taken_by1' => 'required',
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
		   
		   if($forward_to == 1){
			 $returnData['msg'] = "Unauthorized Access to state";
                return response()->json($returnData);
		   }
		   
		   if($forward_from == $forward_to){
			    $returnData['msg'] = "Already assigned to that particular level";
			    return $returnData;
		   }
		   
		   // Condition to check District,Block,GP selection
		   
			if($forward_to == 2 && !($grievDetails->district_id) ){
				 $returnData['msg'] = "Grievance not assigned to any District";
				 return $returnData;
			}  
			if($forward_to == 3 && !($grievDetails->block_id) ){
				 $returnData['msg'] = "Grievance not assigned to any District/Block";
				 return $returnData;
			}
			if($forward_to == 4 && !($grievDetails->gp_id) ){
				 $returnData['msg'] = "Grievance not assigned to any Block/GP";
				 return $returnData;
			}
		   
		   $updateArray = [
			   'action_level'=> $forward_to,
			   'transferred_status'=> 1,
			   'sent_status'=> 1,
			   'sent_date'=> 1,
			   'action_taken_by'=> $action_taken_by,
			   'updated_by'=> $users->username
		   ];
		   
		   //------------Grievance Media Transfer Table Entry----------------------------
            $grievTransferEntry= new GrievanceTransferAction();
		  $grievTransferEntry->grievance_code = $grievData->grievance_code;
		  $grievTransferEntry->transferred_from = $forward_from;
		  $grievTransferEntry->transferred_to =$forward_to;
		  $grievTransferEntry->transfer_date = $cur_date;
		  $grievTransferEntry->created_by= $users->username;

            if(!$grievTransferEntry->save()){
                DB::rollback();
                $returnData['msg'] = "Opps! Something went wrong.";
                return response()->json($returnData);
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
        $returnData['data']=[];
        return response()->json($returnData);
	   
	}
	
	
	//--Message Sending for Individual Grievance Grievance------------------------------------------
	
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
		])->select('ref.grievance_code','d.district_name','grievance_entries.mobile_no','grievance_entries.entry_date')->first();
		
		$date=  Carbon::parse($result->entry_date)->format('d/m/Y');
		
		$message_body = "Grievance registered with code $result->grievance_code dated: $date at District: $result->district_name.Kindly use it for future reference";
			
				
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
		
		
		$message_body = "Grievance resolved with code $result->grievance_code dated: $date.Contact concerned department for further clarification.";
			
				
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
	
	
	
	
}
