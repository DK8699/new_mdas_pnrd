<?php
namespace App\Http\Controllers\Uc;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CommonModels\ZilaParishad;
use App\CommonModels\SiprdExtensionCenter;
use App\Uc\UcProjectEntry;
use App\Uc\UcProjectDivision;
use App\CommonModels\District;

use Validator;
use DB;
use Auth;
use Response;
use Carbon\Carbon;
use Crypt;
use File;

class UcComponentsController extends Controller
{
	public function __construct() {
        $this->middleware(['auth']);
    }

    public function add_edit_component_details(Request $request) {
        $project = UcProjectEntry::all();

        $zilas = ZilaParishad::all();
        $extension_centre = SiprdExtensionCenter::all();

        $districts = District::select('id','district_name')->get();
        $court_cases_type = DB::select('select * from court_cases_type order by id asc');
        $court_cases_nature = DB::select('select * from court_cases_nature order by id asc');
        $court_cases_status = DB::select('select * from court_cases_status order by id asc');
        $court_cases_under = DB::select('select * from court_cases_under order by id asc');

        $court_cases_submitted_by = DB::select('select * from court_cases_submitted_by order by id asc');

        return view('Uc.ComponentsDetails', compact('project', 'zilas','extension_centre', 'districts', 'court_cases_type', 'court_cases_nature', 'court_cases_status', 'court_cases_under', 'court_cases_submitted_by'));
    }

	public function get_entity_components(Request $request)
    {
		$components_table = Crypt::decrypt($request->input('e_val'));
		$components = DB::select('SELECT * FROM uc_components_details WHERE components_table_id = ?', [$components_table]);
		$components_headers = DB::select('SELECT * FROM uc_components_headers');
		$components_sub_headers = DB::select('SELECT * FROM uc_components');
		if( Empty($components) )
		{
			return view('Uc.addEditComponentsDetails', compact('components_table', 'components', 'components_headers', 'components_sub_headers'));
		}
		else
		{
			return view('Uc.addEditComponentsDetails', compact('components_table', 'components', 'components_headers', 'components_sub_headers'));
		}
		return;
    }

	public function saveComponentDetails(Request $request)
	{
		if(Auth::check() && Auth::user()->status == 1001)
		{
			if($request->ajax())
			{
				$components_table_id = Crypt::decrypt($request->input('components_table'));
				$components_header = Crypt::decrypt($request->input('components_header'));
				$components_sub_headers = $request->input('components_sub_headers');
				$head = $request->input('head');
				$unit = $request->input('unit');
				$unit_cost = $request->input('unit_cost');
				$physical_target = $request->input('physical_target');
				$physical_achieved = $request->input('physical_achieved');
				$GOI_share_released = $request->input('GOI_share_released');
				$UC_submitted_by_state = $request->input('UC_submitted_by_state');
				$porjects_check = DB::select('SELECT * FROM components_details WHERE components_table_id = ?', [$components_table_id]);
				if( Empty($porjects_check) )
				{
					for($i=0;$i<count($components_sub_headers);$i++)
					{
						$components_sub_headers_id = Crypt::decrypt($components_sub_headers[$i]);

						$porjects = DB::insert('insert into components_details(components_table_id,header_id,component_sub_header_id,head,unit,unit_cost,physical_target,physical_achieved,GOI_share,UC_submitted) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
						[ $components_table_id, $components_header, $components_sub_headers_id, $head[$i], $unit[$i], $unit_cost[$i], $physical_target[$i], $physical_achieved[$i], $GOI_share_released[$i], $UC_submitted_by_state[$i] ]);
					}
					return "Details Added";
				}
				else
				{
					$porjects_check=DB::table('components_details')->where(['components_table_id' => $components_table_id, 'header_id' => $components_header])->delete();
					for($i=0;$i<count($components_sub_headers);$i++)
					{
						$components_sub_headers_id = Crypt::decrypt($components_sub_headers[$i]);

						$porjects = DB::insert('insert into components_details(components_table_id,header_id,component_sub_header_id,head,unit,unit_cost,physical_target,physical_achieved,GOI_share,UC_submitted) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
						[ $components_table_id, $components_header, $components_sub_headers_id, $head[$i], $unit[$i], $unit_cost[$i], $physical_target[$i], $physical_achieved[$i], $GOI_share_released[$i], $UC_submitted_by_state[$i] ]);
					}
					return "Details Updated";
				}
			}
		}
		else
		{
			Auth::logout();
			return redirect('/');
		}
	}
}
