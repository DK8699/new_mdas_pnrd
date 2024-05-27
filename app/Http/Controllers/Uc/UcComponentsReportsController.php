<?php

namespace App\Http\Controllers\Uc;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\CommonModels\ZilaParishad;
use App\CommonModels\SiprdExtensionCenter;
use App\Uc\UcProjectEntry;
use App\Uc\UcProjectDivision;
use App\Uc\UcComponentsEntities;
use App\Uc\UcProjectYear;
use App\CommonModels\District;

use Validator;
use DB;
use Auth;
use Response;
use Carbon\Carbon;
use Crypt;
use File;
class UcComponentsReportsController extends Controller
{
	 public function __construct() {
       $this->middleware(['auth', 'user_uc']);
    	}
     public function componentWiseReports(Request $request)
     {
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
		   return $this->logout($request);
        }
        $project = UcProjectEntry::join('uc_project_divisions as p_div','p_div.project_id','=','uc_project_entries.id')
                    ->where($whereArray)
                    ->select('uc_project_entries.*')
                    ->get();

		
	
	   if ($request->isMethod('post')) {
		   
		   $p_id = $request->input('project_id');
		   $py_id = $request->input('project_year');
		   
	   }
        $zilas = ZilaParishad::all();
        $extension_centre = SiprdExtensionCenter::all();

		return view('Uc.componentWiseReports', compact('project', 'zilas','extension_centre'));
	}
	
	 public function selectYearsAjax(Request $request)
    	{
        $returnData['msgType'] = false;
        $retrunData['data'] = [];
        $returnData['msg'] = "Failed to Request Process.";
		 
	$pro_id = $request->input('p_id');
		 
	$users=Auth::user();
		 
	if($users->mdas_master_role_id==2)
     {
		$user_id = $users->zp_id;
	}
	else if($users->mdas_master_role_id==6)
     {
		$user_id = $users->ex_id;
	}
	else{
		return $this->logout($request);
	}
        try {
			   for ($i=0;$i<count($pro_id);$i++)
			   {
				   $projectYears= UcProjectYear::getYearsByProjectId($pro_id[$i],$user_id);
			   }
		  
		   //echo json_encode($projectYears);
		   
            if(empty($projectYears)) {
			 $returnData['msgType'] = false;
                $returnData['msg'] = "No Data Found";
                return response()->json($returnData);
            }
        } catch (\Exception $e) {
            $returnData['msg'] = "Server Exception.";
            return response()->json($returnData);
        }
        $returnData['msgType'] = true;
        $returnData['data'] = $projectYears;
        $returnData['msg'] = "Success";
        return response()->json($returnData);

    }
	public function get_project_years(Request $request)
     {
		$project_ids = $request->input('project_id');
		$project_ids = explode(',', $project_ids);
		$i = 0;
		$select = '<select id="project_year" name="project_years" class="selectpicker form-control" title="Select" data-style="btn-info" multiple data-actions-box="true" data-live-search="true" required>';
		foreach($project_ids as $values)
		{
			$project_ids[$i] = ($values);
			$Project = DB::select('SELECT project_name FROM uc_project_entries WHERE id = ?', [$project_ids[$i]]);
			$Project_years = DB::select('SELECT * FROM uc_projects_years WHERE project_id = ?', [$project_ids[$i]]);
			
			foreach($Project_years as $values1)
				$select .= '<option value="'.Crypt::encrypt($values1->id).'">'.$Project[0]->project_name.' '.$values1->project_year.'</option>';
			$i++;
		}
		$select .= '</select>';
		return $select;
    }
	
     public function get_components_headings(Request $request)
     {
		$projects_ids = $request->input('py_id');
		session_start();
		$_SESSION['project_year_ids'] = $projects_ids;
		$project_year_ids = explode(',', $projects_ids);

		$i = 0;
		foreach($project_year_ids as $values)
		{
			$project_year_ids[$i] = Crypt::decrypt($values);
			$i++;
		}

		$p = 0;
		for($j=0;$j<count($project_year_ids);$j++)
		{
			if($p==0)
			{
				$project = "id=?";
				$p=1;
			}
			else
				$project .= " OR id=?";
		}

		$p1=0;
		for($j=0;$j<count($project_year_ids);$j++)
		{
			if($p1==0)
			{
				$project1 = "project_year_id=?";
				$p1=1;
			}
			else
			    $project1 .= " OR project_year_id=?";
		}

		$Projects_Components = DB::select('SELECT DISTINCT c.id, c.component_name, c.component_header_id FROM uc_components_details a, uc_components_entities b, uc_components c WHERE
							   a.components_entity_id=b.id AND a.component_id=c.id AND b.project_year_id IN (SELECT id FROM uc_projects_years WHERE '.$project.')', $project_year_ids);
		$Project_Components_Entities = DB::select('SELECT * FROM uc_components_entities WHERE '.$project1, $project_year_ids);

		$components_headers = DB::select('SELECT * FROM uc_components_headers');

		$components = DB::select('SELECT * FROM uc_components');
		return view('Uc.viewComponentsHeadings', compact('project_year_ids', 'Projects_Components', 'Project_Components_Entities', 'components_headers', 'components'));

	}
	
	public function gist_all_entities_components(Request $request)
	{
			$project_states_components = $request->input('project_entities_components');
		
			session_start();
			$project_ids = $_SESSION['project_year_ids'];
			$project_ids = explode(',', $project_ids);
			$i=0;
			foreach($project_ids as $values)
			{
				$project_ids[$i] = Crypt::decrypt($values);
				$i++;
			}

			$components_headers = DB::select('SELECT * FROM uc_components');
			$comp = array_fill(0,10,'');
			$j=0;
			for($i=0;$i<count($project_states_components);$i++)
			{
				$cpt = DB::select('SELECT * FROM uc_components_details WHERE component_id = ?', [$project_states_components[$i]]);
				if( !Empty($cpt) )
				{
					if($cpt[0]->component_header_id == 6)
					$project_states_components[$i];
					$p=0;
					for($k=0;$k<=$j;$k++)
					{
						if( $comp[$k] != $cpt[0]->component_header_id )
						{
							$p = 1;
							continue;
						}
						else
						{
							$p=0;
							break;
						}
					}
					if($p == 1)
						$comp[$j++] = $cpt[0]->component_header_id;
				}
			}

			$p1=0;
			for($j=0;$j<count($project_ids);$j++)
			{
				if($p1==0)
				{
					$project1 = "id=?";
					$p1=1;
				}
				else
				$project1 .= " OR id=?";
			}

			$components_headers = $comp;
			$components_sub_headers = $project_states_components;
			return view('Uc.gistallEntitiesComponents', compact('project1', 'project_ids', 'components_headers', 'components_sub_headers'));
			//return view('Uc.gistEntitiesComponents', compact('project_ids', 'Project_Components_Table', 'components_headers', 'components_sub_headers'));
	}
	
	public function gist_entities_components(Request $request)
	{
		$projects_ids = $request->input('py_id');
		$project_states_components = $request->input('project_entities_components');
		$project_components_entities = $request->input('project_entities');

		// dd($project_components_entities);
		session_start();
		$project_ids = $_SESSION['project_year_ids'];
		$project_ids = explode(',', $project_ids);
		$i=0;
		foreach($project_ids as $values)
		{
			$project_ids[$i] = Crypt::decrypt($values);
			$i++;
		}

		$components_headers = DB::select('SELECT * FROM uc_components_headers');
		$comp = array_fill(0,10,'');
		$j=0;
		for($i=0;$i<count($project_states_components);$i++)
		{
			for($m=0;$m<count($project_components_entities);$m++)
			{
				$cpt = DB::select('SELECT * FROM uc_components_details WHERE components_entity_id = ? AND component_id = ?', [$project_components_entities[$m],$project_states_components[$i]]);
				if( !Empty($cpt) )
				{
					//if($cpt[0]->header_id == 6)
					//$project_states_components[$i];
					$p=0;
					for($k=0;$k<=$j;$k++)
					{
						if( $comp[$k] != $cpt[0]->component_header_id )
						{
							$p = 1;
							continue;
						}
						else
						{
							$p=0;
							break;
						}
					}
					if($p == 1)
						$comp[$j++] = $cpt[0]->component_header_id;
				}
			}
		}
		$components_headers = $comp;
		$components_sub_headers = $project_states_components;
		// return view('Login.gistStatesConsolidated', compact('project_ids', 'Project_Components_Table', 'components_headers', 'components_sub_headers'));
		return view('Uc.gistEntitiesComponents', compact('project_ids', 'project_components_entities', 'components_headers', 'components_sub_headers'));
	}
}