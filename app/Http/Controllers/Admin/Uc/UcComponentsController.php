<?php
namespace App\Http\Controllers\Admin\Uc;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CommonModels\ZilaParishad;
use App\CommonModels\SiprdExtensionCenter;
use App\Uc\UcProjectEntry;

use App\ConfigMdas;
use App\Uc\UcProjectDivision;
use App\Uc\UcComponentsEntities;
use App\Uc\UcProjectYear;
use App\Uc\UcComponent;
use App\Uc\UcComponentsHeader;
use App\Uc\UcComponentsPhysicalAchievementsPeople;
use App\CommonModels\District;
use App\Uc\UcGfr;

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

        return view('admin.Uc.ComponentsDetails', compact('project', 'zilas','extension_centre'));
	}

    public function load_entities(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;
            $py_id = Crypt::decrypt($request->input('py_id'));
											
			$project_entities = UcComponentsEntities::join('uc_project_divisions as div','div.id','=','uc_components_entities.division_id')
												->where('uc_components_entities.project_year_id','=',$py_id)
												->select('div.division_type','uc_components_entities.*')
												->get();

            $data = '<select id="entities" class="selectpicker form-control" name="extensions_districts" data-style="btn-info" required >
                        <optgroup label="Extension Centers">';
                        foreach($project_entities as $values)
                        {
                            if( $values->division_type == 1 ) {								
                                $data .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->short_entity_name.'</option>';
                            }
                        }
            $data .=    '</optgroup>
                        <optgroup label="Districts">';
                        foreach($project_entities as $values)
                        {
                            if( $values->division_type == 2 ) {
                                $data .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->short_entity_name.'</option>';
                            }
                        }
            $data .=    '</optgroup>
                    </select>';

            return $data;

        }
	}

	public function get_entity_components(Request $request)
    {
		$entity_id = Crypt::decrypt($request->input('e_val'));
		$components_details = DB::select('SELECT * FROM uc_components_details WHERE components_entity_id = ?', [$entity_id]);
		$components_headers = DB::select('SELECT * FROM uc_components_headers');
		$components = DB::select('SELECT * FROM uc_components');
		if( Empty($components) )
		{
			return view('admin.Uc.addEditComponentsDetails', compact('entity_id', 'components_headers', 'components', 'components_details'));
		}
		else
		{
			return view('admin.Uc.addEditComponentsDetails', compact('entity_id', 'components_headers', 'components', 'components_details'));
		}
		return;
    }

	public function save_entity_components(Request $request)
	{
		if($request->ajax())
		{
			$returnData['msgType'] = false;
			$returnData['data'] = [];
			$returnData['msg'] = "Oops! Something went wrong!";

			$entity = Crypt::decrypt($request->input('entity'));
			$components_header = Crypt::decrypt($request->input('components_header'));
			$components = $request->input('components');

			$pt_noc = $request->input('pt_noc');
			$pt_nop = $request->input('pt_nop');
			$pa_noc = $request->input('pa_noc');
			$pa_nop = $request->input('pa_nop');
			$ob = $request->input('ob');
			$goa_fund_received = $request->input('goa_fund_received');
			$other_receipts = $request->input('other_receipts');
			$expenditure = $request->input('expenditure');
			$uc_submitted = $request->input('uc_submitted');
			$porjects_check = DB::select('SELECT * FROM uc_components_details WHERE components_entity_id = ?', [$entity]);
			if( Empty($porjects_check) )
			{
				for($i=0;$i<count($components);$i++)
				{
					$components_id = Crypt::decrypt($components[$i]);

					$porjects = DB::insert('insert into uc_components_details(components_entity_id,component_header_id,component_id,pt_noc,pt_nop,pa_noc,pa_nop,ob,goa_fund_received,other_receipts,expenditure,uc_submitted) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
					[ $entity, $components_header, $components_id, $pt_noc[$i], $pt_nop[$i], $pa_noc[$i], $pa_nop[$i], $ob[$i], $goa_fund_received[$i], $other_receipts[$i], $expenditure[$i], $uc_submitted[$i] ]);
				}
				$returnData['msg'] = "Data Added";
				$returnData['msgType']=true;
				$returnData['data']=[];
				return response()->json($returnData);
			}
			else
			{
				$porjects_check=DB::table('uc_components_details')->where(['components_entity_id' => $entity, 'component_header_id' => $components_header])->delete();
				for($i=0;$i<count($components);$i++)
				{
					$components_id = Crypt::decrypt($components[$i]);

					$porjects = DB::insert('insert into uc_components_details(components_entity_id,component_header_id,component_id,pt_noc,pt_nop,pa_noc,pa_nop,ob,goa_fund_received,other_receipts,expenditure,uc_submitted) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
					[ $entity, $components_header, $components_id, $pt_noc[$i], $pt_nop[$i], $pa_noc[$i], $pa_nop[$i], $ob[$i], $goa_fund_received[$i], $other_receipts[$i], $expenditure[$i], $uc_submitted[$i] ]);
				}
				$returnData['msg'] = "Data Updated";
				$returnData['msgType']=true;
				$returnData['data']=[];
				return response()->json($returnData);
			}
		}
	}

    public function view_component_details(Request $request) {
        $project = UcProjectEntry::all();

        $zilas = ZilaParishad::all();
        $extension_centre = SiprdExtensionCenter::all();

        // $districts = District::select('id','district_name')->get();
        // $court_cases_type = DB::select('select * from court_cases_type order by id asc');
        // $court_cases_nature = DB::select('select * from court_cases_nature order by id asc');
        // $court_cases_status = DB::select('select * from court_cases_status order by id asc');
        // $court_cases_under = DB::select('select * from court_cases_under order by id asc');

        // $court_cases_submitted_by = DB::select('select * from court_cases_submitted_by order by id asc');

        return view('admin.Uc.listComponentsDetails', compact('project', 'zilas','extension_centre'));
	}

	public function add_component(){

        $header = UcComponentsHeader::all();
         $uc_components = NULL;

       /* $uc_components = UcComponent::getComponents();

        echo json_encode($uc_components);*/

	    return view('admin.Uc.add_new_component',compact('header','uc_components'));
    }

    public function component_save(Request $request) {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $users = Auth::user();

        //---------VALIDATION-----------------
        $messages = [
            'component_name.required' => 'This is required.',
            'component_name.max' => 'Maximum 100 characters allowed.',
        ];

        $validatorArray=[
            'component_name' => 'required|max:100',
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
            $UcComponent= new UcComponent();

            $UcComponent->component_name = $request->input('component_name');
            $UcComponent->component_header_id = $request->input('header_id');


            $UcComponent->created_by= $users->username;

            $UcComponent->save();

            if(!$UcComponent->save()){
                DB::rollback();
                $returnData['msg'] = "Opps! Something went wrong#3.";
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

	public function gfr_save(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Oops! Something went wrong!";

        $users = Auth::user();
         
        $attachment_path = NULL;

        $entity_id = $request->input('entity_id');

        
       

        //---------------------VALIDATION---------------------------------------------

        $messages = [
            'attachment.required' => 'This is required!',
            'attachment.mimes' => 'Document must be in pdf format.',
            'attachment.min' => 'Document size must not be less than 10 KB.',
            'attachment.max' => 'Document size must not exceed 400 KB.',
        ];

        $validatorArray = [
            'attachment' => 'required|mimes:pdf|max:400|min:10',
        ];

        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $returnData['msg'] = "VE";
            $returnData['errors'] = $errors;
            return response()->json($returnData);

        }

        //---------------------VALIDATION ENDED----------------------------------------


        if ($request->file('attachment')) {
            $attachment_path = $request->file('attachment')->store('uc/gfr/attachment/' .$entity_id);
        } else {
            $returnData['msg'] = "Upload valid attachment.";
            return response()->json($returnData);
        }

        $alreadyExists = Ucgfr::alreadyExist($entity_id);

        if ($alreadyExists) {
            $updateData = Ucgfr::where([
                ['entity_id', '=', $entity_id],
            ])->update([
                'attachment' => $attachment_path,
                'updated_by' => $users->username,
                'updated_at' => Carbon::now(),
            ]);

            if (!$updateData) {
                $returnData['msg'] = "Oops! Something went wrong!4";
                return response()->json($returnData);
            }
        } else {
            $gfrData = new Ucgfr();
            $gfrData->entity_id = $entity_id;
            $gfrData->attachment = $attachment_path;
            $gfrData->created_by = $users->username;

            if (!$gfrData->save()) {
                $returnData['msg'] = "Oops! Something went wrong!4";
                return response()->json($returnData);
            }
        }

        $imgUrl = ConfigMdas::allActiveList()->imgUrl;

        $returnData['msgType'] = true;
        $returnData['msg'] = "Uploaded successfully!";
        $returnData['data'] = ['imgUrl' => $imgUrl, 'attachment_path' => $attachment_path, 'entity_id' => $entity_id];
        return response()->json($returnData);
    }
    
    public function UcView(Request $request,$entity_id){
        
        $entity_id = Crypt::decrypt($entity_id);
        
        $uc_gfr = Ucgfr::where([
            ['entity_id','=',$entity_id],
        ])->select('attachment')
            ->first();
        
        $imgUrl=ConfigMdas::allActiveList()->imgUrl;
        return response()->file(storage_path('app/'.$uc_gfr->attachment));
	}

	public function view_entity_components($id)
    {
		$entity_id = Crypt::decrypt($id);
		$components_details = DB::select('SELECT * FROM uc_components_details WHERE components_entity_id = ?', [$entity_id]);
		$components_headers = DB::select('SELECT * FROM uc_components_headers');
		$components = DB::select('SELECT * FROM uc_components');
		if( Empty($components) )
		{
			return view('admin.Uc.viewComponentsDetails', compact('entity_id', 'components_headers', 'components', 'components_details'));
		}
		else
		{
			return view('admin.Uc.viewComponentsDetails', compact('entity_id', 'components_headers', 'components', 'components_details'));
		}
		return;
	}

	public function show_entity_components(Request $request)
    {
		$entity_id = Crypt::decrypt($request->input('e_val'));
		$components_details = DB::select('SELECT * FROM uc_components_details WHERE components_entity_id = ?', [$entity_id]);
		$components_headers = DB::select('SELECT * FROM uc_components_headers');
		$components = DB::select('SELECT * FROM uc_components');
		if( Empty($components) )
		{
			return view('admin.Uc.showComponentsDetails', compact('entity_id', 'components_headers', 'components', 'components_details'));
		}
		else
		{
			return view('admin.Uc.showComponentsDetails', compact('entity_id', 'components_headers', 'components', 'components_details'));
		}
		return;
	}
}
