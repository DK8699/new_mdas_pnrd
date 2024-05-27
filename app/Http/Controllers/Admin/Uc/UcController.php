<?php

namespace App\Http\Controllers\Admin\Uc;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CommonModels\ZilaParishad;
use App\CommonModels\SiprdExtensionCenter;
use App\Uc\UcProjectEntry;
use App\Uc\UcProjectDivision;
use App\Uc\UcProjectYear;
use App\Uc\UcComponentsEntities;
use Illuminate\Support\Facades\Auth;

use Validator;
use DB;
use Crypt;
class UcController extends Controller
{
	public function __construct() {
        $this->middleware(['auth']);
    }

	public function dashboard() {
        // $project_states_components = DB::select('SELECT * FROM uc_components_entities');

        // session_start();
        // $project_ids = $_SESSION['project_ids'];
        // $project_ids = explode(',', $project_ids);
        // $i=0;
        // foreach($project_ids as $values)
        // {
        //     $project_ids[$i] = Crypt::decrypt($values);
        //     $i++;
        // }

        // $components_headers = DB::select('SELECT * FROM uc_components');
        // $comp = array_fill(0,10,'');
        // $j=0;
        // for($i=0;$i<count($project_states_components);$i++)
        // {
        //     $cpt = DB::select('SELECT * FROM uc_components_details WHERE component_id = ?', [$project_states_components[$i]]);
        //     if( !Empty($cpt) )
        //     {
        //         if($cpt[0]->component_header_id == 6)
        //         $project_states_components[$i];
        //         $p=0;
        //         for($k=0;$k<=$j;$k++)
        //         {
        //             if( $comp[$k] != $cpt[0]->component_header_id )
        //             {
        //                 $p = 1;
        //                 continue;
        //             }
        //             else
        //             {
        //                 $p=0;
        //                 break;
        //             }
        //         }
        //         if($p == 1)
        //             $comp[$j++] = $cpt[0]->component_header_id;
        //     }
        // }

        // $p1=0;
        // for($j=0;$j<count($project_ids);$j++)
        // {
        //     if($p1==0)
        //     {
        //         $project1 = "id=?";
        //         $p1=1;
        //     }
        //     else
        //     $project1 .= " OR id=?";
        // }

        // $components_headers = $comp;
        // $components_sub_headers = $project_states_components;

        // $uc_extension_centers = DB::select('select distinct extension_center_name from siprd_extension_centers a, uc_project_divisions b, uc_components_entities c
        // where a.id = b.zilla_extension_id and b.division_type = ? and b.id = c.division_id', [1]);
        $uc_extension_centers = DB::select('select * from siprd_extension_centers');
        $uc_zila_parishads = DB::select('select * from zila_parishads');
        $Projects = DB::select('select * from uc_project_entries');

        return view('admin.Uc.dashboard', compact('uc_extension_centers', 'uc_zila_parishads', 'Projects'));
    }

    public function ucAddProject(Request $request){
        $zilas = ZilaParishad::all();
        $extension_centre = SiprdExtensionCenter::all();
        return view('admin.Uc.ucAddProject',compact('zilas','extension_centre'));
    }

    public function project_save(Request $request) {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";
       
        $users = Auth::user();
        
        $sactioned_amt = doubleval(preg_replace('/[^\d.]/', '', $request->input('sactioned_amt')));
        $goi_share = doubleval(preg_replace('/[^\d.]/', '', $request->input('goi_share')));
        $goa_share = doubleval(preg_replace('/[^\d.]/', '', $request->input('goa_share')));
        
        
        $zila = explode(',', $request->input('zilla_id'));
        // print_r($zila);return;
        $extensions = explode(',', $request->input('extension_id'));
        //print_r($extensions);return;
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
            
            if( $zila[0] != "" )
            {
                for ($i=0;$i<sizeof($zila);$i++)
                {
                    $UcProjectDivision = new UcProjectDivision();
                    $UcProjectDivision->project_id = $p_id;
                    $UcProjectDivision->division_type = 2;
                    $UcProjectDivision->zilla_extension_id = $zila[$i];
                    $UcProjectDivision->save();
                }
            }
            if( $extensions[0] != "" )
            {
                for ($i=0;$i<sizeof($extensions);$i++)
                {
                    $UcProjectDivision = new UcProjectDivision();
                    $UcProjectDivision->project_id = $p_id;
                    $UcProjectDivision->division_type = 1;
                    $UcProjectDivision->zilla_extension_id = $extensions[$i];
                    $UcProjectDivision->save();
                }
            }
            // else{
            //     $returnData['msg'] = "Select district/extension".$e->getMessage();
            //     return $returnData;
            // }

            if(!$UcProjectDivision->save()){
              DB::rollback();
                $returnData['msg'] = "Opps! Something went wrong#9.";
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

    public function ucViewProject(){   
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

            $data = '<select id="project_year" class="selectpicker form-control" name="project_year" data-style="btn-info" required >
                        <option value="">Select</option>';
                        foreach($project_years as $values)
                        {
                            $data .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->project_year.'</option>';
                        }
            $data .= '</select>';

            return $data;
        }
    }

    public function load_project_years1(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;
            $p_id = Crypt::decrypt($request->input('p_id'));

            $project_years = UcProjectYear::where('project_id', '=', $p_id)->get();

            $data = '<select id="project_year" class="selectpicker form-control" name="project_year" required >
                        <option value="">Select</option>';
                        foreach($project_years as $values)
                        {
                            $data .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->project_year.'</option>';
                        }
            $data .= '</select>';

            return $data;

        }
    }

    // public function load_project_states(Request $request) {
    //     if($request->ajax())
    //     {
    //         $returnData['msgType']=false;
    //         echo $p_id = $request->input('p_id'); return;

    //         $court_cases_submitted_by = DB::select('select * from court_cases_submitted_by order by id asc');

    //         $data = '
    //             <div class="form-group"> 
    //                 <div class="row">
    //                     <input type="hidden" name="parawise_comments_id" value="'.Crypt::encrypt($i_id).'" />
    //                     <div class="col-sm-6">
    //                         <label for="exampleInputPassword1">Parawise Comments to be Submitted By</label>
    //                         <select id="update_parawise_comments_submitted_by" class="selectpicker form-control select-margin court-case-select" name="parawise_comments_submitted_by" data-style="btn-info" required >
    //                             <option value="'.Crypt::encrypt('').'">Select</option>';
    //                             foreach($court_cases_submitted_by as $values)
    //                             {
    //                                 if( isset($court_cases_parawise_comments[0]->parawise_comments_submitted_by) ) {
    //                                     if( $court_cases_parawise_comments[0]->parawise_comments_submitted_by == $values->id ) {
    //                                         $data .= '<option value="'.Crypt::encrypt($values->id).'" selected >'.$values->submitted_by.'</option>';
    //                                     }
    //                                     else
    //                                         $data .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->submitted_by.'</option>';
    //                                 }
    //                             }
    //                 $data .= '</select>
    //                     </div>
    //                 </div>
    //             </div>

    //             <script>
    //                 $("#parawise_comments_update_date").Zebra_DatePicker({
    //                     format: "d-m-Y"
    //                 });
    //             </script>';

    //     }
    //     return $data;
    // }

    public function add_project_years() {   
        $project = UcProjectEntry::all();
        return view('admin.Uc.addProjectYears',compact('project'));
    }

    public function save_project_years(Request $request) {   
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $users = Auth::user()->username;

        $project_id = Crypt::decrypt($request->input('project_id'));
        $year = $request->input('year');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        //---------VALIDATION-----------------

        $messages = [
            'project_id.required' => 'This is required.',
            
            'year.required' => 'This is required.',
            'year.string' => 'Invalid data.',
            'year.max' => 'Maximum 200 characters allowed.',

            'start_date.required' => 'This is required!',
            'start_date.date_format' => 'This format is invalid!',
            
            'end_date.required' => 'This is required!',
            'end_date.date_format' => 'This format is invalid!'
        ];

        $validatorArray=[
            'project_id' => 'required',
            'year' => 'required|max:100',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
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
            $UcProjectYear= new UcProjectYear();

            $UcProjectYear->project_id = $project_id;
            $UcProjectYear->project_year = $year;
            $UcProjectYear->duration_from = $start_date;
            $UcProjectYear->duration_to = $end_date;
            $UcProjectYear->created_by = $users;

            $UcProjectYear->save();
            
            if(!$UcProjectYear->save()){
              DB::rollback();
                $returnData['msg'] = "Opps! Something went wrong#3.";
               return response()->json($returnData);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng #5 ".$e->getMessage();
            return $returnData;
        }

        DB::commit();

        $returnData['msg'] = "Data Submitted";
        $returnData['msgType']=true;
        $returnData['data']=[];
        return response()->json($returnData);
    }

    public function view_project_years() {   
        $projectDetails = UcProjectEntry::all();
        //dd($projectDetails);return;

        $projectYearDetails = UcProjectEntry::select('uc_project_entries.*', 'uc_projects_years.*')
                            ->join('uc_projects_years', 'uc_project_entries.id', '=', 'uc_projects_years.project_id')
                            ->get();

        return view('admin.Uc.viewProjectsYears',compact('projectDetails', 'projectYearDetails'));
    }

    public function load_extensions_districts(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;
            $py_id = Crypt::decrypt($request->input('py_id'));

            $project_years = UcProjectYear::where('uc_projects_years.id', '=', $py_id)
                                            //->leftJoin('uc_projects_years', 'uc_projects_years.id', '=', 'uc_components_table.project_year_id')
                                            ->join('uc_project_divisions', 'uc_project_divisions.project_id', '=', 'uc_projects_years.project_id')
                                            ->select('uc_projects_years.project_id', 'uc_project_divisions.*')
                                            ->get();
            //var_dump($project_years);return;

            $data = '<select id="extensions_districts" class="selectpicker form-control" name="extensions_districts" data-style="btn-info" required >
                        <optgroup label="Extension Centers">';
                        foreach($project_years as $values)
                        {
                            if( $values->division_type == 1 ) {
                                $ec = SiprdExtensionCenter::where('siprd_extension_centers.id', '=', $values->zilla_extension_id)
                                                        ->select('siprd_extension_centers.extension_center_name')
                                                        ->get();
                                $data .= '<option value="'.Crypt::encrypt($values->id).'" >'.$ec[0]->extension_center_name.'</option>';
                            }
                        }
            $data .=    '</optgroup>
                        <optgroup label="Districts">';
                        foreach($project_years as $values)
                        {
                            if( $values->division_type == 2 ) {
                                $zilla = ZilaParishad::where('zila_parishads.id', '=', $values->zilla_extension_id)
                                                        ->select('zila_parishads.zila_parishad_name')
                                                        ->get();
                                $data .= '<option value="'.Crypt::encrypt($values->id).'" >'.$zilla[0]->zila_parishad_name.'</option>';
                            }
                        }
            $data .=    '</optgroup>
                    </select>';

            return $data;

        }
    }

    public function load_extensions_districts1(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;
            $py_id = Crypt::decrypt($request->input('py_id'));

            $project_years = UcProjectYear::where('uc_projects_years.id', '=', $py_id)
                                            ->join('uc_project_divisions', 'uc_project_divisions.project_id', '=', 'uc_projects_years.project_id')
                                            ->select('uc_projects_years.project_id', 'uc_project_divisions.*')
                                            ->get();

            $data = '<select id="extensions_districts" class="selectpicker form-control" name="extensions_districts" >
                        <optgroup label="Extension Centers">';
                        foreach($project_years as $values)
                        {
                            if( $values->division_type == 1 ) {
                                $ec = SiprdExtensionCenter::where('siprd_extension_centers.id', '=', $values->zilla_extension_id)
                                                        ->select('siprd_extension_centers.extension_center_name')
                                                        ->get();
                                $data .= '<option value="'.Crypt::encrypt($values->id).'" >'.$ec[0]->extension_center_name.'</option>';
                            }
                        }
            $data .=    '</optgroup>
                        <optgroup label="Districts">';
                        foreach($project_years as $values)
                        {
                            if( $values->division_type == 2 ) {
                                $zilla = ZilaParishad::where('zila_parishads.id', '=', $values->zilla_extension_id)
                                                        ->select('zila_parishads.zila_parishad_name')
                                                        ->get();
                                $data .= '<option value="'.Crypt::encrypt($values->id).'" >'.$zilla[0]->zila_parishad_name.'</option>';
                            }
                        }
            $data .=    '</optgroup>
                    </select>';

            return $data;

        }
    }

    public function add_entities() {   
        $project = UcProjectEntry::all();
        return view('admin.Uc.addEntities',compact('project'));
    }

    public function save_entities(Request $request) {   
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $users = Auth::user()->username;

        $project_id = Crypt::decrypt($request->input('project_id'));
        $project_year = Crypt::decrypt($request->input('project_year'));
        $division = Crypt::decrypt($request->input('extensions_districts'));
        $entity_short_name = $request->input('entity_short_name');

        //---------VALIDATION-----------------

        $messages = [
            'project_id.required' => 'This is required.',
            
            'project_year.required' => 'This is required.',

            'extensions_districts.required' => 'This is required!',
            
            'entity_short_name.required' => 'This is required!',
            'entity_short_name.max' => 'Maximum 50 characters allowed!'
        ];

        $validatorArray=[
            'project_id' => 'required',
            'project_year' => 'required',
            'extensions_districts' => 'required',
            'entity_short_name' => 'required|max:50',
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
            $UcComponentsEntities= new UcComponentsEntities();

            $UcComponentsEntities->project_year_id = $project_year;
            $UcComponentsEntities->division_id = $division;
            $UcComponentsEntities->short_entity_name = $entity_short_name;
            $UcComponentsEntities->created_by = $users;

            $UcComponentsEntities->save();

            if(!$UcComponentsEntities->save()){
              DB::rollback();
                $returnData['msg'] = "Opps! Something went wrong#3.";
               return response()->json($returnData);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            $returnData['msg'] = "Opps! Something went worng #5 ".$e->getMessage();
            return $returnData;
        }

        DB::commit();

        $returnData['msg'] = "Data Submitted";
        $returnData['msgType']=true;
        $returnData['data']=[];
        return response()->json($returnData);
    }

    public function view_entities() {   
        // $project = UcProjectEntry::all();

        // $projectDetails = UcProjectEntry::getProjectDetails();

        $projectEntities = UcComponentsEntities::select('uc_components_entities.*', 'uc_projects_years.project_year', 'uc_project_entries.project_name', 'uc_project_entries.project_name', 'uc_project_divisions.division_type', 'uc_project_divisions.zilla_extension_id')
                            ->leftJoin('uc_projects_years', 'uc_projects_years.id', '=', 'uc_components_entities.project_year_id')
                            ->leftJoin('uc_project_entries', 'uc_project_entries.id', '=', 'uc_projects_years.project_id')
                            ->leftJoin('uc_project_divisions', 'uc_project_divisions.id', '=', 'uc_components_entities.division_id')
                            ->get();
        // foreach($project as $pr)
        // {
        //  $p[$pr->id] = UcProjectDivision::getProjectById($pr->id);
        // }
        /*echo json_encode($p);*/

        return view('admin.Uc.viewEntities',compact('projectEntities'));
    }
}