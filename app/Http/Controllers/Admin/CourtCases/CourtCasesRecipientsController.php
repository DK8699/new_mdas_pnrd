<?php

namespace App\Http\Controllers\Admin\CourtCases;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CommonModels\District;
use App\CourtCases\CourtCasesSubmittedBy;
use App\CourtCases\CourtCasesRecipient;

use Auth;
use Carbon\Carbon;
use Crypt;
use DB;
use Validator;

class CourtCasesRecipientsController extends Controller
{
    public function add_recipients(Request $request){
        
        $districts = District::all();
        $court_cases_submitted_by = CourtCasesSubmittedBy::all();
            
        return view('admin.CourtCases.addRecipients',compact('districts','court_cases_submitted_by'));
    }
    public function load_district_blocks(Request $request) {
        if($request->ajax())
        {
            $id = $request->input('id');
            $d_id = $request->input('d_id');

            if($d_id) {
                //$district_ids = json_decode($d_id);
                //$size = sizeof($district_ids);
                $options = '';
                $options = '<label>Blocks</label>
                            <select id="" class="selectpicker form-control select-margin" name="block_id" data-style="btn-info" autocomplete="off" required >';
                //for($i=0;$i<$size;$i++) {
                    $court_cases_district = DB::select('select district_name from districts where id = ? order by id asc', [$d_id]);
                    $court_cases_blocks = DB::select('select * from blocks where district_id = ? order by block_name asc', [$d_id]);

                    $options .= '<optgroup label="'.$court_cases_district[0]->district_name.' - District">';
                    foreach($court_cases_blocks as $values) {
                        $options .= '<option value="'.$values->id.'" >'.$values->block_name.'</option>';
                    }
                    $options .= '</optgroup>';
                //}
                $options .= '</select>';

                echo $options;
                return;
            }
            else
                return "";
        }
    }

    
    public function recipient_save(Request $request){
        
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";


        $users=Auth::user();

        //---------VALIDATION-----------------

        $messages = [
            'r_f_name.required' => 'This is required.',
            'r_f_name.max' => 'Maximum 100 characters allowed.',

            'r_m_name.max' => 'Maximum 100 characters allowed.',

            'r_l_name.required' => 'This is required.',
            'r_l_name.max' => 'Maximum 100 characters allowed.',

            'r_mobile.required' => 'This is required.',
            'r_mobile.numeric' => 'This must be numeric value.',
            'r_mobile.min' => 'Invalid data.',
            'r_mobile.max' => 'This must not exceed 10 digits.',

            'r_email.max' => 'Maximum 150 characters allowed.',
            'r_email.email' => 'Invalid email ID.',

            'r_designation' => 'Maximum 150 characters allowed.',
            
            // 'district_id.exists' => 'Invalid data',
            // 'block_id.exists' => 'Invalid data',
            
            // 'submitted_by.exists' => 'Invalid data',

        ];

        $validatorArray=[
            'r_f_name' => 'required|string|max:100',
            'r_m_name' => 'string|max:100|nullable',
            'r_l_name' => 'required|string|max:100',

            'r_mobile' => 'required|numeric|min:6000000000|max:9999999999',

            'r_email' => 'email|max:150|nullable',
            
            'r_designation'=>'required|string|max:150',
            
            // 'district_id' => 'exists:districts,id|nullable',
            // 'block_id' => 'exists:districts,id|nullable',
            // 'submitted_by' => 'exists:court_cases_submitted_by,id',

            
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);
        }
        
        $mobile_no = $request->input('r_mobile');
        
        if(!CourtCasesRecipient::isMobileAlreadyExists($mobile_no))
        {
             $returnData['msg'] = "Mobile Number Already Exist";
             return response()->json($returnData);
        }
        
        DB::beginTransaction();
        try {
            $courtCaseRecipientEntry = new CourtCasesRecipient();
           
            $courtCaseRecipientEntry->recipient_f_name = strtoupper($request->input('r_f_name'));
            $courtCaseRecipientEntry->recipient_m_name = strtoupper($request->input('r_m_name'));
            $courtCaseRecipientEntry->recipient_l_name = strtoupper($request->input('r_l_name'));

            $courtCaseRecipientEntry->recipient_mobile = $request->input('r_mobile');
            $courtCaseRecipientEntry->recipient_email = $request->input('r_email');
            
            $courtCaseRecipientEntry->recipient_designation = $request->input('r_designation');

            $courtCaseRecipientEntry->district_id = $request->input('district_id');
            $courtCaseRecipientEntry->block_id = $request->input('block_id');
            $courtCaseRecipientEntry->court_cases_submitted_by_id = $request->input('submitted_by');

            $courtCaseRecipientEntry->created_by= $users->username;

            if(!$courtCaseRecipientEntry->save()){
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
        
        $districts = District::all();
        $blocks = DB::select('select * from blocks');
        $court_cases_submitted_by = CourtCasesSubmittedBy::all();
        $court_cases_recipients = CourtCasesRecipient::leftJoin('districts as d','d.id','=','court_cases_recipients.district_id')
                                                    ->leftJoin('blocks as b','b.id','=','court_cases_recipients.block_id')
                                                     ->join('court_cases_submitted_by as submit_by','submit_by.id','=','court_cases_recipients.court_cases_submitted_by_id')
                                                     ->select('d.district_name', 'submit_by.submitted_by','court_cases_recipients.block_id', 'court_cases_recipients.*')
                                                     ->orderBy('court_cases_recipients.id', 'desc')
                                                     ->get();

        return view('admin.CourtCases.viewRecipients',compact('districts','blocks','court_cases_submitted_by','court_cases_recipients'));
    }
    
    public function getRecipientsByid(Request $request){
        
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $rid=$request->input('rid');

        $RecipientData = CourtCasesRecipient::geRecipientById($rid);

        if(!$RecipientData){
            $returnData['msg'] = "Unauthorised access. Please contact admin for more details";
            return response()->json($returnData);
        }

        $options = '';
        if( $RecipientData->district_id != '' || $RecipientData->district_id != NULL) {
        $options = '<label>Blocks</label>
                    <select id="" class="selectpicker form-control select-margin" name="block_id" data-style="btn-info" autocomplete="off" required >';
        //for($i=0;$i<$size;$i++) {
            $court_cases_district = DB::select('select district_name from districts where id = ? order by id asc', [$RecipientData->district_id]);
            $court_cases_blocks = DB::select('select * from blocks where district_id = ? order by block_name asc', [$RecipientData->district_id]);

            $options .= '<optgroup label="'.$court_cases_district[0]->district_name.' - District">';
            foreach($court_cases_blocks as $values) {
                if( $RecipientData->block_id == $values->id )
                    $options .= '<option value="'.$values->id.'" selected >'.$values->block_name.'</option>';
                else
                    $options .= '<option value="'.$values->id.'" >'.$values->block_name.'</option>';
            }
            $options .= '</optgroup>';
        //}
        $options .= '</select>';
        }

        $returnData['msgType'] = true;
        $returnData['data'] = ['RecipientData'=>$RecipientData];
        $returnData['msg'] = "Success";
        $returnData['options'] = $options;
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
            'ed_r_f_name.required' => 'This is required.',
            'ed_r_f_name.max' => 'Maximum 100 characters allowed.',

            'ed_r_m_name.max' => 'Maximum 100 characters allowed.',

            'ed_r_l_name.required' => 'This is required.',
            'ed_r_l_name.max' => 'Maximum 100 characters allowed.',

            'ed_r_mobile.required' => 'This is required.',
            'ed_r_mobile.numeric' => 'This must be numeric value.',
            'ed_r_mobile.min' => 'Invalid data.',
            'ed_r_mobile.max' => 'This must not exceed 10 digits.',

            'ed_r_email.max' => 'Maximum 150 characters allowed.',
            'ed_r_email.email' => 'Invalid email ID.',

            'ed_r_designation' => 'Maximum 150 characters allowed.',
            
            'ed_district_id.exists' => 'Invalid data',
            
            'ed_submitted_by.exists' => 'Invalid data',

        ];

        $validatorArray=[
            'ed_r_f_name' => 'required|string|max:100',
            'ed_r_m_name' => 'string|max:100|nullable',
            'ed_r_l_name' => 'required|string|max:100',

            'ed_r_mobile' => 'required|numeric|min:6000000000|max:9999999999',

            'ed_r_email' => 'email|max:150|nullable',
            
            'ed_r_designation'=>'required|string|max:150',
            
            'ed_district_id' => 'exists:districts,id|nullable',
            'ed_submitted_by' => 'exists:court_cases_submitted_by,id',

            
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);
        }
        
        $recipientData = CourtCasesRecipient::geRecipientById($rid);
        
        if(!$recipientData){
            $returnData['msg'] = "Recipients details successfully updated";
            return response()->json($returnData);
        }
        
        $mobile_no = $request->input('ed_r_mobile');
        
        
        if($recipientData->recipient_mobile!=$mobile_no){
            if(!CourtCasesRecipient::isMobileAlreadyExists($mobile_no))
            {            
                $returnData['msg'] = "Recipients mobile number already in use";
                return response()->json($returnData); 
            }
        }
        $updateArray = [
            'recipient_f_name' => strtoupper($request->input('ed_r_f_name')),
            'recipient_m_name' => strtoupper($request->input('ed_r_m_name')),
            'recipient_l_name' => strtoupper($request->input('ed_r_l_name')),
            'recipient_mobile' =>  $mobile_no,
            'recipient_email' => $request->input('ed_r_email'),
            'recipient_designation' => $request->input('ed_r_designation'),
            'district_id' => $request->input('ed_district_id'),
            'block_id' => $request->input('block_id'),
            'court_cases_submitted_by_id' => $request->input('ed_submitted_by'),
            'updated_by' =>  $users->employee_code,
            'updated_at' => Carbon::now(),
            ];
        
        try{
            $CourtCasesRecipient = CourtCasesRecipient::where('id', $rid)
                                ->update($updateArray); 
                
            if(!$CourtCasesRecipient){
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
        $recipient = CourtCasesRecipient::find($request->id);
        $recipient->is_active = $request->is_active;
        $recipient->save();
        return response()->json(['success'=>'Status change successfully.']);
    }
}
