<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ConfigMdas;
use App\CommonModels\TrainingCentre;
use App\CommonModels\SiprdExtensionCenter;
use App\Training\TrainingDetail;
use App\Training\TrainingLocationDetail;
use App\Training\TrainingProgramme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use DB;
use Validator;

class TrainingController extends Controller
{
    public function index(){
		
		$users=Auth::user();
		$current = Carbon::now()->toDateString();
	   	$days_after_10 = Carbon::now()->addDays(10)->toDateString();
		$centre_name = SiprdExtensionCenter::where('id','=',$users->ex_id)->first();
		$programme = TrainingProgramme::all();
		
		$programmeWiseTrainings = TrainingDetail::programmeWiseTrainings($users->ex_id);
		
		$trainingList = TrainingDetail::trainingListByCentre($users->ex_id,$current,$days_after_10);
		
		
		
		//echo json_encode($trainingList);
		
		$data = [
			'programmeWiseTrainings'=>$programmeWiseTrainings,
			'trainingList'=>$trainingList,
			'programme'=>$programme,
		];
		
	    return view('Training.dashboard',compact('centre_name','data','current','days_after_10'));
    }
	
	public function training_schedule_list(){
		
	    $users=Auth::user();
		
		$imgUrl=ConfigMdas::allActiveList()->imgUrl;
		
	    $trainingList = TrainingDetail::getTrainingListCentreWise($users->ex_id);
	   
	    $data = [
		  'trainingList'=>$trainingList,  
	    ];
		
		
	    return view('Training.training_schedule_list',compact('data','imgUrl'));
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
                    ['training_centre_id', '=', $users->ex_id],
                ])->update($updateArray);
			  
                if(!$trainingLocationDetailsUpdate){
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
	
}
