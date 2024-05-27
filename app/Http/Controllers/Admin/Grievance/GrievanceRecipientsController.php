<?php

namespace App\Http\Controllers\Admin\Grievance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\CommonModels\District;
use App\Grievance\GrievanceRecipient;
use App\Grievance\GrievanceSubmittedBy;
use Auth;
use Carbon\Carbon;
use Crypt;
use DB;
use Validator;

class GrievanceRecipientsController extends Controller
{
    public function add_recipients(Request $request){
        
        $district = District::all();
	    
        $actionTakenBy = GrievanceSubmittedBy::all();
            
        return view('admin.Grievance.grievance_addRecipients',compact('district','actionTakenBy'));
    }
    
    public function recipient_save(Request $request){
        
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";


        $users=Auth::user();

        //---------VALIDATION-----------------

        $messages = [
            'f_name.required' => 'This is required.',
            'f_name.max' => 'Maximum 100 characters allowed.',

            'm_name.max' => 'Maximum 100 characters allowed.',

            'l_name.required' => 'This is required.',
            'l_name.max' => 'Maximum 100 characters allowed.',

            'mobile_no.required' => 'This is required.',
            'mobile_no.numeric' => 'This must be numeric value.',
            'mobile_no.min' => 'Invalid data.',
            'mobile_no.max' => 'This must not exceed 10 digits.',

            'email_id.max' => 'Maximum 150 characters allowed.',
            'email_id.email' => 'Invalid email ID.',

            'designation' => 'Maximum 150 characters allowed.',

        ];

        $validatorArray=[
            'f_name' => 'required|string|max:100',
            'm_name' => 'string|max:100|nullable',
            'l_name' => 'required|string|max:100',

            'mobile_no' => 'required|numeric|min:6000000000|max:9999999999',

            'email_id' => 'email|max:150|nullable',
            
            'designation'=>'required|string|max:150',

            
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);
        }
        
        $mobile_no = $request->input('mobile_no');
        $district_id = $request->input('district_id');
        $block_id = $request->input('block_id');
        $gp_id = $request->input('gp_id');
        $submitted_to = $request->input('submitted_to');
	    
        if(!GrievanceRecipient::isDetailsAlreadyExists($mobile_no))
        {
             $returnData['msg'] = "Mobile Number Already Exist";
             return response()->json($returnData);
        }
        
        DB::beginTransaction();
        try {
            $grievanceRecipientEntry = new GrievanceRecipient();
           
            $grievanceRecipientEntry->f_name = strtoupper($request->input('f_name'));
            $grievanceRecipientEntry->m_name = strtoupper($request->input('m_name'));
            $grievanceRecipientEntry->l_name = strtoupper($request->input('l_name'));

            $grievanceRecipientEntry->mobile_no = $request->input('mobile_no');
            $grievanceRecipientEntry->email_id = $request->input('email_id');
            
            $grievanceRecipientEntry->designation = $request->input('designation');

            $grievanceRecipientEntry->submitted_to = $request->input('submitted_to');
		   
		  $grievanceRecipientEntry->district_id = $request->input('district_id');
            $grievanceRecipientEntry->block_id = $request->input('block_id');
            $grievanceRecipientEntry->gp_id = $request->input('gp_id');

            $grievanceRecipientEntry->created_by= $users->username;

            if(!$grievanceRecipientEntry->save()){
                DB::rollback();
                $returnData['msg'] = "Opps! Something went wrong#3.";
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
    
    public function view_recipients(Request $request){
        
        $district = District::all();
        $blocks = DB::select('select * from blocks');
        $actionTakenBy = GrievanceSubmittedBy::all();
	    
	    
        $grievance_recipients = GrievanceRecipient::leftJoin('districts as d','d.id','=','grievance_recipients.district_id')
                                                    ->leftJoin('blocks as b','b.id','=','grievance_recipients.block_id')
		   								  ->leftJoin('gram_panchyats as g','g.gram_panchyat_id','=','grievance_recipients.gp_id')
                                                     ->join('grievance_submitted_by as submit_by','submit_by.id','=','grievance_recipients.submitted_to')
                                                     ->select('d.district_name', 'submit_by.submitted_by','b.block_name','g.gram_panchayat_name','grievance_recipients.*')
                                                     ->orderBy('grievance_recipients.id', 'desc')
                                                     ->get();

        return view('admin.Grievance.grievance_viewRecipients',compact('district','blocks','actionTakenBy','grievance_recipients'));
    }
    
    public function getRecipientsByid(Request $request){
        
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $rid=$request->input('rid');

        $RecipientData = GrievanceRecipient::geRecipientById($rid);

        if(!$RecipientData){
            $returnData['msg'] = "Unauthorised access. Please contact admin for more details";
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = ['RecipientData'=>$RecipientData];
        $returnData['msg'] = "Success";
        return response()->json($returnData);
    }
    
    public function editRecipient(Request $request){
        
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";
        
        $rid = $request->input('rid');
        $users=Auth::user();
        
        //---------VALIDATION-----------------

        $messages = [
            'ed_f_name.required' => 'This is required.',
            'ed_f_name.max' => 'Maximum 100 characters allowed.',

            'ed_m_name.max' => 'Maximum 100 characters allowed.',

            'ed_l_name.required' => 'This is required.',
            'ed_l_name.max' => 'Maximum 100 characters allowed.',

            'ed_mobile_no.required' => 'This is required.',
            'ed_mobile_no.numeric' => 'This must be numeric value.',
            'ed_mobile_no.min' => 'Invalid data.',
            'ed_mobile_no.max' => 'This must not exceed 10 digits.',

            'ed_email_id.max' => 'Maximum 150 characters allowed.',
            'ed_email_id.email' => 'Invalid email ID.',

            'ed_designation' => 'Maximum 150 characters allowed.',
            
            'ed_district_id.exists' => 'Invalid data',
            'ed_block_id.exists' => 'Invalid data',
            'ed_gp_id.exists' => 'Invalid data',
            
            'ed_submitted_by.exists' => 'Invalid data',

        ];

        $validatorArray=[
            'ed_f_name' => 'required|string|max:100',
            'ed_m_name' => 'string|max:100|nullable',
            'ed_l_name' => 'required|string|max:100',

            'ed_mobile_no' => 'required|numeric|min:6000000000|max:9999999999',

            'ed_email' => 'email|max:150|nullable',
            
            'ed_designation'=>'required|string|max:150',
            
            'ed_district_id' => 'exists:districts,id|nullable',
		   
            'ed_block_id' => 'exists:blocks,id|nullable',
            'ed_gp_id' => 'exists:gram_panchyats,gram_panchyat_id|nullable',
            'ed_submitted_by' => 'exists:grievance_submitted_by,id',

            
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);
        }
        
        $recipientData = GrievanceRecipient::geRecipientById($rid);
        
        if(!$recipientData){
            $returnData['msg'] = "Recipients details successfully updated";
            return response()->json($returnData);
        }
        
        $mobile_no = $request->input('ed_mobile_no');
        
        
        if($recipientData->mobile_no!=$mobile_no){
            if(!GrievanceRecipient::isDetailsAlreadyExists($mobile_no))
            {            
                $returnData['msg'] = "Recipients mobile number already in use";
                return response()->json($returnData); 
            }
        }
        $updateArray = [
            'f_name' => strtoupper($request->input('ed_f_name')),
            'm_name' => strtoupper($request->input('ed_m_name')),
            'l_name' => strtoupper($request->input('ed_l_name')),
            'mobile_no' =>  $mobile_no,
            'email_id' => $request->input('ed_email_id'),
            'designation' => $request->input('ed_designation'),
            'district_id' => $request->input('ed_district_id'),
            'block_id' => $request->input('ed_block_id'),
            'gp_id' => $request->input('ed_gp_id'),
            'submitted_to' => $request->input('ed_submitted_to'),
            'updated_by' =>  $users->employee_code,
            ];
        
        try{
            $GrievanceRecipient = GrievanceRecipient::where('id', $rid)
                                ->update($updateArray); 
                
            if(!$GrievanceRecipient){
                $returnData['msg'] = "Oops! Something went wrong!";
                return response()->json($returnData);
            }
        }
        catch (\Exception $e) {
            echo $e->getMessage();
            $returnData['msg'] = "Opps! Something wentr worng.";
            return $returnData;
        }

        $returnData['msgType'] = true;
        $returnData['data'] = [];
        $returnData['msg'] = "Recipients details successfully updated";
        return response()->json($returnData);
    }

    public function statusRecipient(Request $request) {
        $recipient = GrievanceRecipient::find($request->id);
        $recipient->is_active = $request->is_active;
        $recipient->save();
        return response()->json(['success'=>'Status change successfully.']);
    }
}