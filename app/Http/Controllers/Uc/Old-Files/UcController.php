<?php

namespace App\Http\Controllers\Uc;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CommonModels\ZilaParishad;
use App\CommonModels\SiprdExtensionCenter;
use App\Uc\UcProjectEntry;
use App\Uc\UcProjectDivision;
use App\Uc\UcProjectYear;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
use Crypt;

class UcController extends Controller
{
	public function __construct() {
        $this->middleware(['auth']);
    }

    public function dashboard(Request $request) {
        return view('uc.dashboard',compact());
    }

    public function ucAddProject() {
        $zilas = ZilaParishad::all();
        $extension_centre = SiprdExtensionCenter::all();
        return view('admin.Uc.ucAddProject', compact('zilas','extension_centre'));
    }

    public function project_save (Request $request) {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $users = Auth::user();

        $sactioned_amt = doubleval(preg_replace('/[^\d.]/', '', $request->input('sactioned_amt')));
        $goi_share = doubleval(preg_replace('/[^\d.]/', '', $request->input('goi_share')));
        $goa_share = doubleval(preg_replace('/[^\d.]/', '', $request->input('goa_share')));

        $zila = explode(',', $request->input('zilla_id'));
        $extensions = explode(',', $request->input('extension_id'));

        //---------VALIDATION-----------------
        $messages = [
            'project_name.required' => 'This is required.',
            'project_name.max' => 'Maximum 100 characters allowed.',

            'start_date.required' => 'This is required!',
            'start_date.date_format' => 'This format is invalid!',

            'end_date.required' => 'This is required!',
            'end_date.date_format' => 'This format is invalid!',

            'about_project.required' => 'This is required.',
            'about_project.string' => 'Invalid data.',
            'about_project.max' => 'Maximum 200 characters allowed.',
        ];

        $validatorArray=[
            'project_name' => 'required|max:100',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
            'about_project' => 'required|string|max:200',

            'sactioned_amt' => [
                'required',
                function ($attribute, $value, $fail) {

                    $value= doubleval(preg_replace('/[^\d.]/', '', $value));

                    if (!preg_match('/^[0-9]+(\.[0-9][0-9]?)?$/', $value))
                    {
                        $fail("Amount up to two decimal points is allowed!");
                    }

                    if($value > 999999999){
                        $fail('Amount should not exceed 99 crores!');
                    }

                    if($value < 0){
                        $fail('Amount should not be less than zero!');
                    }

                },
            ],
            'goi_share' => [
                'required',
                function ($attribute, $value, $fail) {

                    $value= doubleval(preg_replace('/[^\d.]/', '', $value));

                    if (!preg_match('/^[0-9]+(\.[0-9][0-9]?)?$/', $value))
                    {
                        $fail("Amount up to two decimal points is allowed!");
                    }

                    if($value > 999999999){
                        $fail('Amount should not exceed 99 crores!');
                    }

                    if($value < 0){
                        $fail('Amount should not be less than zero!');
                    }

                },
            ],
            'goa_share' => [
                'required',
                function ($attribute, $value, $fail) {

                    $value= doubleval(preg_replace('/[^\d.]/', '', $value));

                    if (!preg_match('/^[0-9]+(\.[0-9][0-9]?)?$/', $value))
                    {
                        $fail("Amount up to two decimal points is allowed!");
                    }

                    if($value > 999999999){
                        $fail('Amount should not exceed 99 crores!');
                    }

                    if($value < 0){
                        $fail('Amount should not be less than zero!');
                    }
                },
            ],
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);
        }

        //---------VALIDATION ENDED-----------------

        DB::beginTransaction();
        try {
            $UcProjectEntry= new ucProjectEntry();

            $UcProjectEntry->project_name = $request->input('project_name');
            $UcProjectEntry->start_date = $request->input('start_date');
            $UcProjectEntry->end_date = $request->input('end_date');

            $UcProjectEntry->sactioned_amt = $sactioned_amt;

            $UcProjectEntry->goi_share = $goi_share;
            $UcProjectEntry->goa_share = $goa_share;
            
            $UcProjectEntry->about_project = $request->input('about_project');
            
            $UcProjectEntry->created_by= $users->username;
            
            $UcProjectEntry->save();
            
            if(!$UcProjectEntry->save()){
              DB::rollback();
                $returnData['msg'] = "Opps! Something went wrong#3.";
               return response()->json($returnData);
            }
            
            $p_id= $UcProjectEntry->id;
            
            if(!empty($zila))
            {
                for ($i=0;$i<sizeof($zila);$i++)
                {
                    $UcProjectDivision = new UcProjectDivision();
                    $UcProjectDivision->project_id = $p_id;
                    $UcProjectDivision->division_id = 1;
                    $UcProjectDivision->zilla_extension_id = $zila[$i];
                    $UcProjectDivision->save();

                }
            }
            elseif(!empty($extensions))
            {
                for ($i=0;$i<sizeof($extensions);$i++)
                {
                    $UcProjectDivision = new UcProjectDivision();
                    $UcProjectDivision->project_id = $p_id;
                    $UcProjectDivision->division_id = 2;
                    $UcProjectDivision->zilla_extension_id = $extensions[$i];
                    $UcProjectDivision->save();
                }
            }
            else{
                $returnData['msg'] = "Select district/extension".$e->getMessage();
                return $returnData;
            }
            
             if(!$UcProjectDivision->save()){
              DB::rollback();
                $returnData['msg'] = "Opps! Something went wrong#9.";
               return response()->json($returnData);
            }

        }
        catch (\Exception $e) {
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

    public function ucViewProject() {
        $project = UcProjectEntry::all();

        //$projectDetails = UcProjectEntry::getProjectDetails();
        foreach($project as $pr)
        {
            $p[$pr->id] = UcProjectDivision::getProjectById($pr->id);
        }
        /*echo json_encode($p);*/

        return view('admin.Uc.ucViewProject',compact('project','p'));
    }

    public function load_project_years(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;
            $p_id = Crypt::decrypt($request->input('p_id'));

            $project_years = UcProjectYear::where('project_id', '=', $p_id)->get();

            $data = '<select id="project_year" class="selectpicker form-control court-case-select" name="project_year" data-style="btn-info" required >
                        <option value="'.Crypt::encrypt('').'">Select</option>';
                        foreach($project_years as $values)
                        {
                            $data .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->project_year.'</option>';
                        }
            $data .= '</select>';

            return $data;
        }
    }
}