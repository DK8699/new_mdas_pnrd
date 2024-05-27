<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Training\TrainingDetail;
use App\Training\TrainingParticipantDetail;
use Carbon\Carbon;
use Crypt;
use App\Master\MasterGender;
use App\Master\MasterCaste;
use App\CommonModels\District;
use App\CommonModels\Block;
use App\CommonModels\GramPanchyat;
use DB;
use Validator;

class TrainingController extends Controller
{
    public function index(){
	    
	    $current = Carbon::now()->toDateString();
	    
	    $trainings_upcoming = TrainingDetail::getUpcomingTrainings($current);
	    $trainings_conducted = TrainingDetail::getConductedTrainings($current);
	    
	    $data=[
		    'trainings_upcoming'=>$trainings_upcoming,
		    'trainings_conducted'=>$trainings_conducted,
	    ];
	    
	    return view('public.Training.index',compact('data'));
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
	
	
    public function view_more($type){
		
	     $type_status = $type;
		$current = Carbon::now()->toDateString();
	    
	    if($type_status == "UPCOMING"){
		    
		    $head_txt = "$type_status TRAININGS";
		    $allTrainings = TrainingDetail::join('training_location_details as loc_details','training_details.id','loc_details.training_id')
		     ->leftJoin('training_centres as centres','centres.centre_id','loc_details.training_centre_id')
			->select('training_details.id as training_id','training_details.*','loc_details.id as loc_id','centres.centre_name')
		     ->where([
				   ['training_details.start_date','>=',$current],
				])
			->orderBy('training_details.start_date','Asc')
		     ->get();
	    }
	    else if($type_status == "CONDUCTED")
	    {
		    $head_txt = "$type_status TRAININGS";
		    
		    $allTrainings = TrainingDetail::join('training_location_details as loc_details','training_details.id','loc_details.training_id')
		     ->leftJoin('training_centres as centres','centres.centre_id','loc_details.training_centre_id')
			->select('training_details.id as training_id','training_details.*','loc_details.id as loc_id','centres.centre_name')
		     ->where([
				   ['training_details.start_date','<',$current],
				   ['loc_details.is_training_conducted','=',1],
				])
			->orderBy('training_details.start_date','Asc')
		     ->get();
	    }
		
		
				
		$data = [
			'allTrainings'=>$allTrainings,
			'head_txt'=>$head_txt,
		];
		
		return view('public.Training.view_more',compact('data','type_status'));
	}
	
    public function participants_entry($t_id,$l_id){
	    
	    $training_id = Crypt::decrypt($t_id);
	    $location_id = Crypt::decrypt($l_id);
	    
	    $district= District::all();
	    $gender= MasterGender::all();
	    $caste= MasterCaste::all();
	    
	    $getTrainingDetails = TrainingDetail::getTrainingDetailsByIds($training_id,$location_id);
	    
	    $data = [
		    'training_id'=>$training_id,
		    'location_id'=>$location_id,
		    'getTrainingDetails'=>$getTrainingDetails
	    ];
	    
	    
	    return view('public.Training.participants_entry',compact('data','district','gender','caste'));

    }
	
    public function participants_entry_save(Request $request){
	    
	   $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";
	    
	    //return $request->all();
	   DB::beginTransaction();
        try {
		   //---------Validation------------------------------------------\
		   
		    $messages = [
			 'training_id.required' => 'This field is required',
			    
			 'training_location_id.required' => 'This field is required',
			    
                'centre_id.required' => 'This field is required',
			    
                'programme.required' => 'This field is required',
			    
                'no_of_days.required' => 'This field is required',
			    
                'name.required' => 'This field is required',
			    
                'gender.required' => 'This field is required',
                'gender.esists' => 'This :attribute field is required',
			    
			 'caste.required' => 'This field is required',
			 'caste.esists' => 'This :attribute field is required',
			    
			 'mobile_no.required' => 'This field is required',
			 'mobile_no.numeric' => 'This must be numeric value.',
			 'mobile_no.min' => 'Invalid data.',
			 'mobile_no.max' => 'This must not exceed 10 digits.',
			 'mobile_no.digits' => 'This must not exceed 10 digits.',
			    
			 'alt_mobile_no.numeric' => 'This must be numeric value.',
			 'alt_mobile_no.min' => 'Invalid data.',
			 'alt_mobile_no.max' => 'This must not exceed 10 digits.',
			 'alt_mobile_no.digits' => 'This must not exceed 10 digits.',

                'email.required' => 'This :attribute field is required',
			 'email.max' => 'Maximum 150 characters allowed.',
            	 'email.email' => 'Invalid email ID.',
			    
                'district_id.required' => 'This field is required',
			    
                'working_status.required' => 'This field is required',
			 'working_status.in' => 'Invalid data.',
			    
			 'designation.required_if' => 'This is required.',
            	 'designation.exists' => 'Invalid data.',
			    
                'o_designation.required_if' => 'This field is required',
			    
                'address.required' => 'This field is required',
			 'address.max' => 'Maximum 500 characters allowed.',
			    
                'description.required' => 'This field is required',
			 'description.max' => 'Characters must not exceed 500 characters',
            ];

            $validatorArray = [
                 'training_id'=> 'required',
                 'training_location_id'=> 'required',
                 'centre_id'=> 'required',
			  'programme'=> 'required',
			  'no_of_days'=> 'required',
			  'name'=> 'required',
			  'gender'=> 'required|exists:master_genders,id',
			  'caste'=> 'required|exists:master_castes,id',
			  'mobile_no'=> 'numeric|min:6000000000|max:9999999999|digits:10',
			  'alt_mobile_no'=> 'numeric|min:6000000000|max:9999999999|digits:10|nullable',
			  'email' => 'required|email|max:150|nullable',
			  'district_id'=> 'required',
			  'working_status'=> 'required|in:1,2', // 1=yes and 2= No
			  'designation' => 'required_if:working_status,==,1|nullable',
			  'o_designation'=> 'required_if:designation,==,0|nullable',
			  'address'=> 'required|max:500',
			  'description'=> 'required|string|max:500',
            ];
		   
            $validator = Validator::make($request->all(), $validatorArray, $messages);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $returnData['msg'] = "VE";
                $returnData['errors'] = $errors;
                return response()->json($returnData);
            }
		   
		   
		   
		  //------------Participants Details Entry----------------------------
		   
            $trainingParticipantDetails = new TrainingParticipantDetail();
		  $trainingParticipantDetails->training_id = $request->input('training_id');
		  $trainingParticipantDetails->training_location_id = $request->input('training_location_id');
		  $trainingParticipantDetails->p_name = $request->input('name');
		  $trainingParticipantDetails->p_gender = $request->input('gender');
		  $trainingParticipantDetails->p_caste = $request->input('caste');
		  $trainingParticipantDetails->p_mobile_no = $request->input('mobile_no');
		  $trainingParticipantDetails->p_alt_mobile_no = $request->input('alt_mobile_no');
		  $trainingParticipantDetails->p_email = $request->input('email');
		  $trainingParticipantDetails->district_id = $request->input('district_id');
		  $trainingParticipantDetails->block_id = $request->input('block_id');
		  $trainingParticipantDetails->gp_id = $request->input('gp_id');
		  $trainingParticipantDetails->working_status = $request->input('working_status');
		  $trainingParticipantDetails->designation_id = $request->input('designation');
		  $trainingParticipantDetails->o_designation = $request->input('o_designation');
		  $trainingParticipantDetails->address = $request->input('address');
		  $trainingParticipantDetails->description =$request->input('description');
            if(!$trainingParticipantDetails->save()){
                DB::rollback();
                $returnData['msg'] = "Opps! Something went wrong.";
                return response()->json($returnData);
            } 
		   $participant_id = Crypt::encrypt($trainingParticipantDetails->id);
		   
        }catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng#5".$e->getMessage();
            return $returnData;
        }

        DB::commit();

        $returnData['msg'] = "Data Submitted";
        $returnData['msgType']=true;
        $returnData['data']=['participant_id'=>$participant_id];
        return response()->json($returnData);
	    
	    
	    
    }
	
    public function participant_confirmation(Request $request){
	    
	    $id = Crypt::decrypt($request->input('participant_id'));
	    
	    $getParticipantDetials = TrainingParticipantDetail::where('id',$id)->first();
	    
	    
	    return view('public.Training.participants_confirmation',compact('getParticipantDetials'));
    }
}
