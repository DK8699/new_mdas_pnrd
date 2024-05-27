<?php

namespace App\Http\Controllers\Uc;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CommonModels\ZilaParishad;
use App\CommonModels\SiprdExtensionCenter;
use App\Uc\UcProjectEntry;
use App\Uc\UcProjectDivision;
use App\Uc\UcProjectYear;
use App\Uc\UcComponentsEntities;
use App\Uc\UcComponentsDetail;
use Illuminate\Support\Facades\Auth;

use Validator;
use DB;
use Crypt;
class UcController extends Controller
{
	 public function __construct() {
       $this->middleware(['auth', 'user_uc']);
    }

	public function dashboard() {
        
	   $projectList=[];
		
		
	   $users=Auth::user();
        if($users->mdas_master_role_id==2)
        {
            $whereArray = [
                ['p_div.division_type','=',2],
                ['p_div.zilla_extension_id','=',$users->zp_id],
            ];
        }
        else if($users->mdas_master_role_id==6)
        {
            $whereArray = [
                ['p_div.division_type','=',1],
                ['p_div.zilla_extension_id','=',$users->ex_id],
            ];
        }
        else{
            
        }
        
        $projectList = UcProjectEntry::join('uc_project_divisions as p_div','p_div.project_id','uc_project_entries.id')
                                      ->leftJoin('zila_parishads as z','z.id','=','p_div.zilla_extension_id')
                                      ->leftJoin('siprd_extension_centers as ex','ex.id','=','p_div.zilla_extension_id')
                                    ->where($whereArray)
                       ->select('uc_project_entries.*','z.zila_parishad_name','ex.extension_center_name','p_div.division_type')
                                    ->get();
		
		
		foreach($projectList as $list)
		{
			$uc_projects = UcComponentsDetail::leftJoin('uc_components_entities as entity','entity.id','=','uc_components_details.components_entity_id')
						   ->join('uc_project_divisions as p_div','entity.division_id','=','p_div.id')
						   ->join('uc_project_entries as entries','p_div.project_id','=','entries.id')
						->where([
							[$whereArray],
							['entries.id','=',$list->id]
						])->select('entries.project_name','entity.short_entity_name','p_div.*','uc_components_details.*')->get();
		
		}
		
        $uc_extension_centers = DB::select('select * from siprd_extension_centers');
        $uc_zila_parishads = DB::select('select * from zila_parishads');

        return view('Uc.dashboard', compact('uc_extension_centers', 'uc_zila_parishads','projectList','users'));
    }

    
    public function ucViewProject(){   
        
        $users=Auth::user();
        if($users->mdas_master_role_id==2)
        {
            $whereArray = [
                ['p_div.division_type','=',2],
                ['p_div.zilla_extension_id','=',$users->zp_id],
            ];
        }
        else if($users->mdas_master_role_id==6)
        {
            $whereArray = [
                ['p_div.division_type','=',1],
                ['p_div.zilla_extension_id','=',$users->ex_id],
            ];
        }
        
        else{
            
        }
        
        $projectList = UcProjectEntry::join('uc_project_divisions as p_div','p_div.project_id','uc_project_entries.id')
                                      ->leftJoin('zila_parishads as z','z.id','=','p_div.zilla_extension_id')
                                      ->leftJoin('siprd_extension_centers as ex','ex.id','=','p_div.zilla_extension_id')
                                    ->where($whereArray)
                                    ->select('uc_project_entries.*','z.zila_parishad_name','ex.extension_center_name')
                                    ->get();
       /*
        echo json_encode($projectList);*/

        return view('Uc.ucViewProject',compact('projectList'));
    }

    public function load_project_years(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;
            $p_id = Crypt::decrypt($request->input('p_id'));
            
            $project_years = UcProjectYear::where('project_id', '=', $p_id)->get();

            $data = '<select id="project_year" class="selectpicker form-control" name="project_year" data-style="btn-info" required >
                        <option value="">Select Year</option>';
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
        return view('Uc.addProjectYears',compact('project'));
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
        /*$projectDetails = UcProjectEntry::all();*/
        //dd($projectDetails);return;

        $projectYearDetails = UcProjectEntry::select('uc_project_entries.*', 'uc_projects_years.*')
                            ->join('uc_projects_years', 'uc_project_entries.id', '=', 'uc_projects_years.project_id')
                            ->get();
        
        $users=Auth::user();
        if($users->mdas_master_role_id==2)
        {
            $whereArray = [
                ['p_div.division_type','=',2],
                ['p_div.zilla_extension_id','=',$users->zp_id],
            ];
        }
        else if($users->mdas_master_role_id==6)
        {
            $whereArray = [
                ['p_div.division_type','=',1],
                ['p_div.zilla_extension_id','=',$users->ex_id],
            ];
        }
        
        else{
            
        }
        
        $projectDetails = UcProjectEntry::join('uc_projects_years as p_yr','p_yr.project_id','=','uc_project_entries.id')
                                        ->join('uc_project_divisions as p_div','p_div.project_id','=','p_yr.project_id')
                                        ->where($whereArray)
                                        ->distinct('uc_project_entries.project_name')
                                        ->select('uc_project_entries.project_name','uc_project_entries.id')
                                        ->get();
       
        
           /*echo json_encode($projectDetails);*/
       
        return view('Uc.viewProjectsYears',compact('projectDetails', 'projectYearDetails'));
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
        return view('Uc.addEntities',compact('project'));
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
        $users=Auth::user();
         if($users->mdas_master_role_id==2)
         {
             $whereArray = [
                 ['p_div.division_type','=',2],
                 ['p_div.zilla_extension_id','=',$users->zp_id],
             ];
         }
         else if($users->mdas_master_role_id==6)
         {
             $whereArray = [
                 ['p_div.division_type','=',1],
                 ['p_div.zilla_extension_id','=',$users->ex_id],
             ];
         }

         else{

         }

        $projectEntities = UcComponentsEntities::select('uc_components_entities.*', 'uc_projects_years.project_year', 'uc_project_entries.project_name', 'uc_project_entries.project_name', 'p_div.division_type', 'p_div.zilla_extension_id')
                            ->join('uc_projects_years', 'uc_projects_years.id', '=', 'uc_components_entities.project_year_id')
                            ->join('uc_project_entries', 'uc_project_entries.id', '=', 'uc_projects_years.project_id')
                            ->join('uc_project_divisions as p_div', 'p_div.id', '=', 'uc_components_entities.division_id')
                            ->where($whereArray)
                            ->get();
        // foreach($project as $pr)
        // {
        //  $p[$pr->id] = UcProjectDivision::getProjectById($pr->id);
        // }
        /*echo json_encode($p);*/

        return view('Uc.viewEntities',compact('projectEntities'));
    }
}