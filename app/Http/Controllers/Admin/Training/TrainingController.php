<?php

namespace App\Http\Controllers\Admin\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ConfigMdas;
use App\CommonModels\TrainingCentre;
use App\Training\TrainingDetail;
use App\Training\TrainingLocationDetail;
use App\Training\TrainingProgramme;
use App\Training\TrainingParticipantDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use DB;
use Crypt;
use Carbon\Carbon;
use Validator;

class TrainingController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'admin_mdas']);
    }

    public function index(Request $request){
	   
	   $training_centre = TrainingCentre::all();
	   $programme = TrainingProgramme::all();
	   $current = Carbon::now()->toDateString();
	   $days_after_10 = Carbon::now()->addDays(10)->toDateString();
	    
	   //Trainings for 10 days from current date
	    
	   
	   $centreWiseTrainings = TrainingDetail::getTrainingsByCentre($current,$days_after_10);    
	   $centreWiseParticipants = TrainingLocationDetail::getInterestedParticipantsByCentre($current,$days_after_10);    

	   
	   $programmeWiseTrainings = TrainingDetail::getTrainingsByProgramme($current,$days_after_10);
	   $programmeWiseParticipants = TrainingLocationDetail::getInterestedParticipantsByProgramme($current,$days_after_10);
	   //echo json_encode($programmeWiseTrainings);
	    
	   $overallCentreWiseTrainings = TrainingDetail::getOverallTrainingsByCentre();
	    
	   $overallProgrammeWiseTrainings = TrainingDetail::getOverallTrainingsByProgramme();
	    
	    
	    $data = [
		    'training_centre' =>$training_centre,
		    'programme' =>$programme,
		    'centreWiseTrainings' =>$centreWiseTrainings,
		    'centreWiseParticipants' =>$centreWiseParticipants,
		    'programmeWiseTrainings' =>$programmeWiseTrainings,
		    'programmeWiseParticipants' =>$programmeWiseParticipants,
		    'overallCentreWiseTrainings' =>$overallCentreWiseTrainings,
		    'overallProgrammeWiseTrainings' =>$overallProgrammeWiseTrainings,
	    ];
		   
		   
        return view('admin.Training.dashboard',compact('current','days_after_10','data'));
    }
	
    public function training_entry(){
	    
	    
	    $training_centre = TrainingCentre::all();
	    $training_programme = TrainingProgramme::all();
	    
	    return view('admin.Training.training_entry',compact('training_centre','training_programme'));
    }
	
    public function training_save(Request $request){
	    
	   $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";
	    
	   $users=Auth::user();
	   $trainingCentreList = TrainingCentre::all(); 
	   $training_centres = explode(',', $request->input('t_centre'));
	    
	   DB::beginTransaction();
        try {
		   //---------Validation------------------------------------------\
		   
		    $messages = [
			  
			 'course.required' => 'This field is required',
                'course.max' => 'Characters must not exceed 500 characters',
			    
                't_centre.required' => 'This field is required',
			    
			 'programme.required' => 'This field is required',
			    
			 'year_id.required' => 'This field is required',

                'start_date.required' => 'This field is required',

                'end_date.required' => 'This field is required',
            ];

            $validatorArray = [
			  
                'course' => 'required|string|max:500',

                't_centre' => 'required',
			  
                'programme' => 'required',
			  
                'year_id' => 'required',
			  
                'start_date' => 'required',
			  
                'end_date' => 'required',
            ];
		   
            $validator = Validator::make($request->all(), $validatorArray, $messages);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $returnData['msg'] = "VE";
                $returnData['errors'] = $errors;
                return response()->json($returnData);
            }
		   
		  // IF start date is greater than end date error
		   
		 if($request->input('start_date') > $request->input('end_date'))
			{
				$returnData['msg'] = "Date mismatch";
				return response()->json($returnData);
			}
		   
		   //No of days difference between two dates

		   $start_date = Carbon::parse($request->input('start_date'));
		   $end_date = Carbon::parse($request->input('end_date'));
		   $no_of_days = ($start_date->diffInDays($end_date))+1;
		   
		   
		   
		  //------------Training Details Entry----------------------------
		   
          $trainingDetails = new TrainingDetail();
		  $trainingDetails->programme = $request->input('programme');
		  $trainingDetails->course = $request->input('course');
		  $trainingDetails->year = $request->input('year_id');
		  $trainingDetails->start_date = $request->input('start_date');
		  $trainingDetails->end_date = $request->input('end_date');
		  $trainingDetails->no_of_days = $no_of_days;
		  $trainingDetails->created_by= $users->username;

            if(!$trainingDetails->save()){
                DB::rollback();
                $returnData['msg'] = "Opps! Something went wrong.";
                return response()->json($returnData);
            }
           //------------Training location Details Entry--------------------------------
		   
		 $training_id = $trainingDetails->id;
		   
		foreach($trainingCentreList as $list){
				
			if(in_array($list->centre_id, $training_centres)){
				$trainingLocationDetails= new TrainingLocationDetail();
				$trainingLocationDetails->training_id = $training_id;
				$trainingLocationDetails->training_centre_id = $list->centre_id;
				$trainingLocationDetails->created_by =$users->username;
				$trainingLocationDetails->save();
			}
		}
			if(!$trainingLocationDetails->save()){
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
	
    public function training_schedule_list(){
	    
		$imgUrl=ConfigMdas::allActiveList()->imgUrl;
		
	    $trainingList = TrainingDetail::getTrainingListAll();
	    
	    $participantCount = TrainingParticipantDetail::getParticipantsCountByLocation();

	    $data = [
		  'trainingList'=>$trainingList,  
		  'participantCount'=>$participantCount,  
	    ];
	    
	    return view('admin.Training.training_schedule_list',compact('data','imgUrl'));
    }
	
    public function getTrainingData(Request $request){
		
	   	$returnData['msgType'] = false;
		$returnData['data'] = [];
		$returnData['msg'] = "Oops! Something went wrong!";
		
	   	$id = $request->input('id');
	   
		$trainingData = TrainingDetail::getTrainingDataById($id);
	   
	   if(!$trainingData){
		    $returnData['msg'] = "You are not authorized to perform this task";
                    return response()->json($returnData);
	   }
		
		$returnData['msgType'] = true;
		$returnData['data'] = ['trainingData'=>$trainingData];
	 	$returnData['msg'] = "Success";
		return response()->json($returnData);
    }
	
    public function setTrainingAction(Request $request){
		
	   
	   $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong1!";
	   
	   $users=Auth::user(); 
	   
	   $id = $request->input('training_id');
	   $cur_date=Carbon::now()->toDateString();
	   $no_of_participants = $request->input('no_of_participants');
	   $level_of_participants = $request->input('level_of_participants');
	   
	   $trainingData = TrainingDetail::getTrainingDataById($id);
	   
	   $messages = [
            'no_of_participants.required' => 'This is required!',
            'level_of_participants.required' => 'This is required!',
            'report_attachment.required' => 'This is required!',
            'report_attachment.max' => 'Document size must not exceed 400 KB.',
            
        ];
		
        $validatorArray = [
            'no_of_participants' => 'required',
            'level_of_participants' => 'required',
		  'report_attachment' => 'required|max:400',
        ];	

        $validator = Validator::make($request->all(), $validatorArray, $messages);
		
	   if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);

        }
		
	   $validator = Validator::make(
		  [
			 'report_attachment' => $request->report_attachment,
			 'extension' => strtolower($request->report_attachment->getClientOriginalExtension()),
		  ],
		  [
			 'report_attachment'  => 'required',
			 'extension' => 'required|in:xlsx,xls',
		  ]

		);
		
		if(Input::hasFile('report_attachment')){
		    $uploadedFileMimeType = Input::file('report_attachment')->getMimeType();

		    $mimes = array('application/excel','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

		    if(in_array($_FILES['report_attachment']['type'], $mimes)){

			   //True
		    } else{

			  $returnData['msg'] = "Please select xls/xlsx file";
			  return response()->json($returnData);

		    }
		}
	   if ($request->file('report_attachment')) {
            $doc_path = $request->file('report_attachment')->store('Training/Attendeance/Report/' . $id);
        }
		
	   DB::beginTransaction();
        try {
		   if($trainingData->end_date > $cur_date){
			 $returnData['msg'] = "Training not conducted yet!";
                return response()->json($returnData);
		   }
		   
		   $updateArray = [
			   'participants_no'=> $no_of_participants,
			   'level_of_participants'=> $level_of_participants,
			   'is_training_conducted'=> 1,
			   'updated_by'=> $users->username
		   ];
		   if ($doc_path) {
			    $updateArray['attendance_report'] = $doc_path;
		   }
		   
		   $trainingLocationDetailsUpdate= TrainingLocationDetail::where([
                    ['training_id', '=', $id],
                    ['training_centre_id', '=', 0],
                ])->update($updateArray);
			  
                if(!$trainingLocationDetailsUpdate){
                    DB::rollback();
				$returnData['msg'] = "Location Error!";
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
	
    public function getParticipantDetails(Request $request,$loc_id,$training_id){
	    
	    $l_id = Crypt::decrypt($loc_id);
	    
	    $t_id = Crypt::decrypt($training_id);
	    
	    $participantDetails = TrainingParticipantDetail::join('training_details as details','details.id','=','training_participant_details.training_id')
		    ->join('training_location_details as loc_details','loc_details.id','=','training_participant_details.training_location_id')
		    ->join('training_centres as centre','centre.centre_id','=','loc_details.training_centre_id')
		    ->join('training_programmes as programmes','programmes.id','=','details.programme')
		    ->where([
		    ['training_participant_details.training_location_id',$l_id],
		    ['training_participant_details.training_id',$t_id],
	    ])->get();
	    
	   // echo json_encode($participantDetails);
	    
	    return view('admin.Training.training_participant_list',compact('participantDetails'));
    }
	
}
