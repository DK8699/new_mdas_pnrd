<?php

namespace App\Http\Controllers\CourtCases;

use App\CourtCases\CourtCasesInstruction;
use App\CourtCases\CourtCasesMessageTrackTable;
use App\CourtCases\CourtCasesParawiseComment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Response;
use Carbon\Carbon;
use Crypt;
use DB;
use File;
use App\CommonModels\District;

class CourtCasesController extends Controller
{
	public function __construct() {
        $this->middleware(['auth','district_courtcase']);
    }
    public function dashboard(Request $request) {
        $districts = District::select('id','district_name')->get();
        $court_cases_type = DB::select('select * from court_cases_type order by id asc');
        $court_cases_nature = DB::select('select * from court_cases_nature order by id asc');
        $court_cases_status = DB::select('select * from court_cases_status order by id asc');
        //echo Auth::user()->district_code;return;

        $dt = Carbon::now();
        $case_start = $dt->toDateString();
        $rev_case_start = Carbon::parse($case_start)->format('d-m-Y');

        $date = $dt->addDays(7);
        $case_end = $date->toDateString();
        $rev_case_end = Carbon::parse($case_end)->format('d-m-Y');
        // $court_cases = DB::select('select * from court_cases a,  order by id asc');
        // $court_cases = DB::select('select *, a.id as c_id  from court_cases_parawise_comments a, districts b, court_cases_type c, court_cases_status d where a.district_id = b.id AND a.case_type_id = c.id AND a.case_status_id = d.id order by a.id desc');

        $court_cases_parawise_comments = DB::select('select *, b.id as c_id from court_cases_parawise_comments a, court_cases b, court_cases_submitted_by c
        where a.due_date_of_parawise_comments between ? and ? AND a.status = "active" AND a.court_case_id = b.id AND a.parawise_comments_submitted_by = c.id order by due_date_of_parawise_comments asc', [$case_start, $case_end]);

        if( !empty($court_cases_parawise_comments) ) {
            for($i=0;$i<sizeof($court_cases_parawise_comments);$i++) {
                $temp_date = explode('-', $court_cases_parawise_comments[$i]->due_date_of_parawise_comments);
                $court_cases_parawise_comments[$i]->due_date_of_parawise_comments = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
            }
        }

        $court_cases = DB::select('select id from court_cases order by id');
        $court_cases_instruction = []; $court_cases_interim_order = []; $court_cases_speaking_order = []; $inc = 0;
        for($i=0;$i<sizeof($court_cases);$i++) {

            /*************************INSTRUCTION***********************/
            $interim_temp = DB::select('select *, a.id as c_id from court_cases a, court_cases_interim_order b, court_cases_submitted_by c
            where b.due_date_of_interim_order between ? and ? AND b.status = "active" AND a.id = b.court_case_id AND b.action_to_be_taken_by = c.id AND a.id = ?
            order by b.id asc', [$case_start, $case_end, $court_cases[$i]->id]);
            if( !empty($interim_temp) ) {
                $court_cases_interim_order[$inc] = $interim_temp[ sizeof($interim_temp) - 1 ];
                $temp_date = explode('-', $court_cases_interim_order[$inc]->due_date_of_interim_order);
                $court_cases_interim_order[$inc]->due_date_of_interim_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
            }

            $instruction_temp = DB::select('select *, a.id as c_id from court_cases a, court_cases_instruction b, court_cases_submitted_by c
            where b.due_date_of_instruction between ? and ? AND b.status = "active" AND a.id = b.court_case_id AND b.instruction_submitted_by = c.id AND a.id = ?
            order by b.id asc', [$case_start, $case_end, $court_cases[$i]->id]);
            if( !empty($instruction_temp) ) {
                $court_cases_instruction[$inc] = $instruction_temp[ sizeof($instruction_temp) - 1 ];
                $temp_date = explode('-', $court_cases_instruction[$inc]->due_date_of_instruction);
                $court_cases_instruction[$inc]->due_date_of_instruction = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
            }

            $speaking_temp = DB::select('select *, a.id as c_id from court_cases a, court_cases_speaking_order b, court_cases_submitted_by c
            where b.status = "active" AND a.id = b.court_case_id AND b.speaking_order_passed_by = c.id AND a.id = ? order by b.id asc', [$court_cases[$i]->id]);
            $p = 0;
            if( !empty($speaking_temp) ) {
                $speaking_temp = $speaking_temp[ sizeof($speaking_temp) - 1 ];
 
                $temp_date = explode('-', $speaking_temp->date_of_speaking_order);
                $speaking_temp->date_of_speaking_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];

                $dso = $speaking_temp->date_of_speaking_order;
                if( isset($speaking_temp->due_date_of_speaking_order) ) {
                    $ddso = $speaking_temp->due_date_of_speaking_order;

                    $date=date_create($dso);    
                    date_sub($date,date_interval_create_from_date_string($ddso." days"));
                    $temp = date_format($date,"Y-m-d");
                }
                if( $temp >= $case_start && $temp <= $case_end ) {
                    $speaking_temp->due_date_of_speaking_order = $temp;
                    $temp_date = explode('-', $speaking_temp->due_date_of_speaking_order);
                    $speaking_temp->due_date_of_speaking_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                    $court_cases_speaking_order[$inc] = $speaking_temp;
                }
            }
            $inc++;
        }

        $court_cases_final_order = DB::select('select *, b.id as c_id from court_cases_final_order a, court_cases b
        where due_date_of_final_order between ? and ? AND a.court_case_id = b.id order by due_date_of_final_order asc', [$case_start, $case_end]);
        if( !empty($court_cases_final_order)) {
            for($i=0;$i<sizeof($court_cases_final_order);$i++) {
                $temp_date = explode('-', $court_cases_final_order[$i]->due_date_of_final_order);
                $court_cases_final_order[$i]->due_date_of_final_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
            }
            for($i=0;$i<sizeof($court_cases_final_order);$i++) {
                $temp_date = explode('-', $court_cases_final_order[$i]->date_of_receipt_of_final_order);
                $court_cases_final_order[$i]->date_of_receipt_of_final_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
            }
        }

        return view('CourtCases.dashboard', compact('districts', 'court_cases_type', 'court_cases_nature', 'court_cases_status', 'rev_case_start', 'rev_case_end',
        'court_cases_parawise_comments', 'court_cases_instruction', 'court_cases_interim_order', 'court_cases_final_order', 'court_cases_speaking_order'));
    }
    public function add_court_case(Request $request) {
        $districts = District::select('id','district_name')->get();
        $court_cases_type = DB::select('select * from court_cases_type order by id asc');
        $court_cases_nature = DB::select('select * from court_cases_nature order by id asc');
        $court_cases_status = DB::select('select * from court_cases_status order by id asc');
        $court_cases_under = DB::select('select * from court_cases_under order by id asc');

        $court_cases_submitted_by = DB::select('select * from court_cases_submitted_by order by id asc');

        return view('CourtCases.addCourtCase', compact('districts', 'court_cases_type', 'court_cases_nature', 'court_cases_status', 'court_cases_under', 'court_cases_submitted_by'));
    }
    public function add_court_case_details(Request $request) {
        if(Auth::check())
        {
            if($request->ajax())
            {
                $returnData['msgType']=false;
                $case_type = Crypt::decrypt($request->input('case_type'));
                $district = Crypt::decrypt($request->input('district'));
                $districts = $request->input('districts');
                $districts = explode(',', $districts);

                $i = 0;
                foreach($districts as $values) {
                    $districts[$i] = Crypt::decrypt($values);
                    $i++;
                }

                $districts_json = json_encode($districts);

                $file_number = $request->input('file_number');
                $case_number = $request->input('case_number');
                $name_of_the_petitioner = $request->input('name_of_the_petitioner');
                $subject_matter_of_the_case = $request->input('subject_matter_of_the_case');

                $date_of_receipt_of_wpc_notice = $request->input('date_of_receipt_of_wpc_notice');
                $temp_date = explode('-', $date_of_receipt_of_wpc_notice);
                $date_of_receipt_of_wpc_notice = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];

                $nature_of_case = Crypt::decrypt($request->input('nature_of_case'));
                $case_status = Crypt::decrypt($request->input('case_status'));
                $case_under = Crypt::decrypt($request->input('case_under'));
                $remarks = $request->input('remarks');

                $court_case = DB::insert('insert into court_cases(case_type_id, district_id, file_number, case_number, name_of_petitioner, subject_matter_of_case, date_of_receipt_of_wpc_notice, nature_of_case, case_status_id, case_under, remarks)
                values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',	[ $case_type, $districts_json, $file_number, $case_number, $name_of_the_petitioner, $subject_matter_of_the_case, $date_of_receipt_of_wpc_notice, $nature_of_case, $case_status, $case_under, $remarks]);

                if($court_case){
                    $returnData['msgType']=true;
                }
            }

            return json_encode($returnData);
            // $districts = District::select('id','district_name')->get();
            // $court_cases_type = DB::select('select * from court_cases_type order by id asc');
            // $court_cases_submitted_by = DB::select('select * from court_cases_submitted_by order by id asc');

            // return view('CourtCases.addCourtCase', compact('districts', 'court_cases_type', 'court_cases_submitted_by'));
        }
        else
        {
          Auth::logout();
          return redirect('/');
        }
    }
    public function list_court_case(Request $request) {
        $districts = District::select('id','district_name')->get();
        $court_cases = DB::select('select *, a.id as c_id  from court_cases a, court_cases_type b, court_cases_status c, court_cases_nature d, court_cases_under e where a.case_type_id = b.id AND a.case_status_id = c.id AND a.nature_of_case = d.id AND a.case_under = e.id order by a.id desc');
        $all_districts = District::select('id','district_name')->get();
        $case_size = sizeof($court_cases);
        for($i=0;$i<$case_size;$i++)
        {
            $district_ids = json_decode($court_cases[$i]->district_id);
            $return_districts = "";
            // if( Auth::user()->district_code != "") {
                $delete_item = "No";

                //foreach( $districts as $value )
                //{
                for($j=0;$j<sizeof($district_ids);$j++) {
                    if( Auth::user()->district_code == $district_ids[$j] ) {
                        $delete_item = "No";
                        break;
                    }
                    else {
                        $delete_item = "Yes";
                        continue;
                    }
                }
                //}
                if( $delete_item == "Yes" ) {
                    unset($court_cases[$i]);
                    $delete_item = 0;
                    continue;
                }
            // }

            $first_item = "NOT COVERED";
            foreach( $all_districts as $value ) {
                for($k=0;$k<sizeof($district_ids);$k++)
                {
                    if( $first_item == "NOT COVERED" ) {
                        $first_item = "COVERED";
                        if( $value->id == $district_ids[$k] ) {
                            $return_districts .=  $value->district_name;
                        }
                    }
                    else {
                        if( $value->id == $district_ids[$k] ) {
                            $return_districts .= ', '.$value->district_name;
                        }
                    }
                }
                $first_item = "NOT COVERED";
            }
            $court_cases[$i]->district_name = $return_districts;
            // $court_cases[$i]->c_id = Crypt::encrypt($court_cases[$i]->c_id);

            $temp_date = explode('-', $court_cases[$i]->date_of_receipt_of_wpc_notice);
            $court_cases[$i]->date_of_receipt_of_wpc_notice = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
        }

        $court_cases = array_values($court_cases);

        $court_cases_type = DB::select('select * from court_cases_type order by id asc');
        $court_cases_nature = DB::select('select * from court_cases_nature order by id asc');
        $court_cases_status = DB::select('select * from court_cases_status order by id asc');
        $court_cases_under = DB::select('select * from court_cases_under order by id asc');

        $court_cases_submitted_by = DB::select('select * from court_cases_submitted_by order by id asc');

        for($i=0;$i<sizeof($court_cases);$i++) {
            $temp_date = explode('-', $court_cases[$i]->date_of_receipt_of_wpc_notice);
            $court_cases[$i]->date_of_receipt_of_wpc_notice = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
        }

        return view('CourtCases.listCourtCases', compact('districts', 'court_cases', 'court_cases_type', 'court_cases_nature', 'court_cases_status', 'court_cases_under', 'court_cases_submitted_by'));
    }
    public function search_court_case(Request $request) {
        $returnData['msgType']=false;
        $returnData['data']=[];
        $returnData['msg']="Opps! Something went wrong";

        $from_receipt_of_wpc_notice = $request->input('from_receipt_of_wpc_notice');
        if( $from_receipt_of_wpc_notice != "") {
            $temp_date = explode('-', $from_receipt_of_wpc_notice);
            $from_receipt_of_wpc_notice = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
        }

        $to_receipt_of_wpc_notice = $request->input('to_receipt_of_wpc_notice');
        if( $to_receipt_of_wpc_notice != "") {
            $temp_date = explode('-', $to_receipt_of_wpc_notice);
            $to_receipt_of_wpc_notice = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
        }

        // $district = Crypt::decrypt($request->input('district'));
        $case_under = Crypt::decrypt($request->input('case_under'));
        $case_type = Crypt::decrypt($request->input('case_type'));
        $case_nature = Crypt::decrypt($request->input('nature_of_case'));
        $case_status = Crypt::decrypt($request->input('case_status'));

        $from=strtotime($from_receipt_of_wpc_notice);
        $to=strtotime($to_receipt_of_wpc_notice);
        if( $from_receipt_of_wpc_notice != "" && $to_receipt_of_wpc_notice != "")
        {
            //echo $from;
            if ( $from > $to )
            {
                $returnData['msg']="( From ) date cannot be greater than ( To ) date";
                return response()->json($returnData);
            }
        }

        $query = "";
        $array_items = [];
        if( $from_receipt_of_wpc_notice != "") {
            $query .= "a.date_of_receipt_of_wpc_notice >= ? AND ";
            array_push($array_items, $from_receipt_of_wpc_notice);
        }
        if( $to_receipt_of_wpc_notice != "") {
            // $date = strtotime("+1 day", strtotime($to_receipt_of_wpc_notice));
            // $to_receipt_of_wpc_notice = date("M d, Y", $date);
            $query .= " a.date_of_receipt_of_wpc_notice <= ? AND ";
            array_push($array_items, $to_receipt_of_wpc_notice);
        }
        // if( $district != "") {
        //     $query .= " a.district_id = ? AND ";
        //     array_push($array_items, $district);
        // }
        if( $case_under != "") {
            $query .= " a.case_under = ? AND ";
            array_push($array_items, $case_under);
        }
        if( $case_type != "") {
            $query .= " a.case_type_id = ? AND ";
            array_push($array_items, $case_type);
        }
        if( $case_nature != "") {
            $query .= " a.nature_of_case = ? AND ";
            array_push($array_items, $case_nature);
        }
        if( $case_status != "") {
            $query .= " a.case_status_id = ? AND ";
            array_push($array_items, $case_status);
        }

        //$districts = District::select('id','district_name')->get();
        $db_query = "select *, a.id as c_id, a.district_id as district_name from court_cases a, court_cases_type b, court_cases_nature c, court_cases_status d, court_cases_under e
                     where ".$query." a.case_type_id = b.id AND a.nature_of_case = c.id AND a.case_status_id = d.id AND a.case_under = e.id order by a.id desc";
        $court_cases = "";
        $court_cases = DB::select( $db_query, $array_items);

        $all_districts = District::select('id','district_name')->get();
        $case_size = sizeof($court_cases);
        for($i=0;$i<$case_size;$i++)
        {
            $district_ids = json_decode($court_cases[$i]->district_id);
            $return_districts = "";
            // if( Auth::user()->district_code != "") {
            $delete_item = "No";

            //foreach( $districts as $value )
            //{
            for($j=0;$j<sizeof($district_ids);$j++) {
                if( Auth::user()->district_code == $district_ids[$j] ) {
                    $delete_item = "No";
                    break;
                }
                else {
                    $delete_item = "Yes";
                    continue;
                }
            }
            //}
            if( $delete_item == "Yes" ) {
                unset($court_cases[$i]);
                $delete_item = 0;
                continue;
            }
            // }

            $first_item = "NOT COVERED";
            foreach( $all_districts as $value ) {
                for($k=0;$k<sizeof($district_ids);$k++)
                {
                    if( $first_item == "NOT COVERED" ) {
                    $first_item = "COVERED";
                        if( $value->id == $district_ids[$k] ) {
                            $return_districts .=  $value->district_name;
                        }
                    }
                    else {
                        if( $value->id == $district_ids[$k] ) {
                            $return_districts .= ', '.$value->district_name;
                        }
                    }
                }
                $first_item = "NOT COVERED";
            }
            $court_cases[$i]->district_name = $return_districts;
            $court_cases[$i]->c_id = Crypt::encrypt($court_cases[$i]->c_id);

            $temp_date = explode('-', $court_cases[$i]->date_of_receipt_of_wpc_notice);
            $court_cases[$i]->date_of_receipt_of_wpc_notice = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
        }

        $court_cases = array_values($court_cases);

        $returnData['msgType']=true;
        $returnData['data']=$court_cases;
        $returnData['msg']="Successfully got the count cases";
        return response()->json($returnData);
    }
    public function search_court_case_byno(Request $request) {
        $returnData['msgType']=false;
        $returnData['data']=[];
        $returnData['msg']="Opps! Something went wrong";

        $case_number = $request->input('case_number');

        // $query = "select *, a.id as c_id from court_cases a, districts b, court_cases_type c, court_cases_nature d, court_cases_status e where a.district_id = b.id AND a.case_type_id = c.id AND a.nature_of_case = d.id AND a.case_status_id = e.id AND case_number LIKE ".'%?%' order by a.id desc";
        // $court_cases = DB::select( $query, [ $case_number ]);

        $court_cases = DB::table('court_cases')
            ->select('*', 'court_cases.id as c_id')
            //->join('districts', 'districts.id', '=', 'court_cases.district_id')
            ->join('court_cases_type', 'court_cases_type.id', '=', 'court_cases.case_type_id')
            ->join('court_cases_nature', 'court_cases_nature.id', '=', 'court_cases.nature_of_case')
            ->join('court_cases_status', 'court_cases_status.id', '=', 'court_cases.case_status_id')
            ->join('court_cases_under', 'court_cases_under.id', '=', 'court_cases.case_under')
            ->where('case_number', 'like', '%'.$case_number.'%')
            ->get();

        $all_districts = District::select('id','district_name')->get();
        $case_size = sizeof($court_cases);
        for($i=0;$i<$case_size;$i++)
        {
            $district_ids = json_decode($court_cases[$i]->district_id);
            $return_districts = "";
            $delete_item = "No";

                for($j=0;$j<sizeof($district_ids);$j++) {
                    if( Auth::user()->district_code == $district_ids[$j] ) {
                        $delete_item = "No";
                        break;
                    }
                    else {
                        $delete_item = "Yes";
                        continue;
                    }
                }

                if( $delete_item == "Yes" ) {
                    unset($court_cases[$i]);
                    $delete_item = 0;
                    continue;
                }

            // $district_ids = json_decode($court_cases[$i]->district_id);
            // $return_districts = "";
            $first_item = "NOT COVERED";
            foreach( $all_districts as $value ) {
                for($k=0;$k<sizeof($district_ids);$k++)
                {
                    if( $first_item == "NOT COVERED" ) {
                        $first_item = "COVERED";
                        if( $value->id == $district_ids[$k] ) {
                            $return_districts .=  $value->district_name;
                        }
                    }
                    else {
                        if( $value->id == $district_ids[$k] ) {
                            $return_districts .= ', '.$value->district_name;
                        }
                    }
                }
                $first_item = "NOT COVERED";
            }
            $court_cases[$i]->district_name = $return_districts;
            $court_cases[$i]->c_id = Crypt::encrypt($court_cases[$i]->c_id);

            $temp_date = explode('-', $court_cases[$i]->date_of_receipt_of_wpc_notice);
            $court_cases[$i]->date_of_receipt_of_wpc_notice = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
        }

        $court_cases = json_encode($court_cases);
        $court_cases = json_decode($court_cases);
        $court_cases = (array)$court_cases;
        $court_cases = array_values($court_cases);
        
        $returnData['msgType']=true;
        $returnData['data']=$court_cases;
        $returnData['msg']="Successfully got the count cases";
        return response()->json($returnData);
    }
    public function view_court_case(Request $request) {
        if(Auth::check())
        {
            $court_cases_id = Crypt::decrypt($request->route('id'));
            $districts = District::select('id','district_name')->get();

            $court_cases = DB::select('select * from court_cases where id = ? order by id asc', [$court_cases_id]);
            for($i=0;$i<sizeof($court_cases);$i++)
            {
                $temp_date = explode('-', $court_cases[$i]->date_of_receipt_of_wpc_notice);
                $court_cases[$i]->date_of_receipt_of_wpc_notice = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
            }

            $court_cases_type = DB::select('select * from court_cases_type order by id asc');
            $court_cases_nature = DB::select('select * from court_cases_nature order by id asc');
            $court_cases_status = DB::select('select * from court_cases_status order by id asc');
            $court_cases_under = DB::select('select * from court_cases_under order by id asc');
            $court_cases_submitted_by = DB::select('select * from court_cases_submitted_by order by id asc');

            $court_cases_parawise_comments = DB::select('select *, a.id as i_id from court_cases_parawise_comments a, court_cases_submitted_by b where court_case_id = ? AND a.parawise_comments_submitted_by = b.id order by a.id asc', [$court_cases_id]);
            if( !empty($court_cases_parawise_comments)) {
                for($i=0;$i<sizeof($court_cases_parawise_comments);$i++) {
                    $temp_date = explode('-', $court_cases_parawise_comments[$i]->due_date_of_parawise_comments);
                    $court_cases_parawise_comments[$i]->due_date_of_parawise_comments = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                }
            }

            // $court_cases_instruction = DB::select('select * from court_cases_instruction  where court_case_id = ? order by id asc', [$court_cases_id]);
            // if( !empty($court_cases_instruction)) {
            //     $temp_date = explode('-', $court_cases_instruction[0]->due_date_of_instruction);
            //     $court_cases_instruction[0]->due_date_of_instruction = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
            // }

            $court_cases_instruction = DB::select('select *, a.id as i_id from court_cases_instruction a, court_cases_submitted_by b where court_case_id = ? AND a.instruction_submitted_by = b.id order by a.id asc', [$court_cases_id]);
            if( !empty($court_cases_instruction)) {
                for($i=0;$i<sizeof($court_cases_instruction);$i++) {
                    $temp_date = explode('-', $court_cases_instruction[$i]->due_date_of_instruction);
                    $court_cases_instruction[$i]->due_date_of_instruction = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                }
            }

            $court_cases_affidavit = DB::select('select * from court_cases_affidavit where court_case_id = ? AND category="primary" order by id asc', [$court_cases_id]);
            if( !empty($court_cases_affidavit)) {
                $temp_date = explode('-', $court_cases_affidavit[0]->date_of_affidavit_submitted);
                $court_cases_affidavit[0]->date_of_affidavit_submitted = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
            }

            $court_cases_additional_affidavit = DB::select('select * from court_cases_affidavit where court_case_id = ? AND category="additional" order by id asc', [$court_cases_id]);
            if( !empty($court_cases_additional_affidavit)) {
                $temp_date = explode('-', $court_cases_additional_affidavit[0]->date_of_affidavit_submitted);
                $court_cases_additional_affidavit[0]->date_of_affidavit_submitted = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
            }

            $court_cases_final_order = DB::select('select *, a.id as i_id from court_cases_final_order a, court_cases_submitted_by b where court_case_id = ? AND a.action_to_be_taken_by = b.id order by a.id asc', [$court_cases_id]);
            if( !empty($court_cases_final_order)) {
                $temp_date = explode('-', $court_cases_final_order[0]->due_date_of_final_order);
                $court_cases_final_order[0]->due_date_of_final_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];

                $temp_date = explode('-', $court_cases_final_order[0]->date_of_receipt_of_final_order);
                $court_cases_final_order[0]->date_of_receipt_of_final_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
            }

            // $court_cases_speaking_order = DB::select('select * from court_cases_speaking_order  where court_case_id = ? order by id asc', [$court_cases_id]);
            // if( !empty($court_cases_speaking_order)) {
            //     $temp_date = explode('-', $court_cases_speaking_order[0]->date_of_speaking_order);
            //     $court_cases_speaking_order[0]->date_of_speaking_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
            // }

            $court_cases_speaking_order = DB::select('select *, a.id as i_id from court_cases_speaking_order a, court_cases_submitted_by b where court_case_id = ? AND a.speaking_order_passed_by = b.id order by a.id asc', [$court_cases_id]);
            if( !empty($court_cases_speaking_order)) {
                for($i=0;$i<sizeof($court_cases_speaking_order);$i++) {
                    $temp_date = explode('-', $court_cases_speaking_order[$i]->date_of_speaking_order);
                    $court_cases_speaking_order[$i]->date_of_speaking_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                }
            }

            $court_cases_interim_order = DB::select('select *, a.id as i_id from court_cases_interim_order a, court_cases_submitted_by b where court_case_id = ? AND a.action_to_be_taken_by = b.id order by a.id asc', [$court_cases_id]);
            if( !empty($court_cases_interim_order)) {
                for($i=0;$i<sizeof($court_cases_interim_order);$i++) {
                    $temp_date = explode('-', $court_cases_interim_order[$i]->due_date_of_interim_order);
                    $court_cases_interim_order[$i]->due_date_of_interim_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                }
            }
            return view('CourtCases.viewCourtCase', compact('districts', 'court_cases', 'court_cases_type', 'court_cases_nature', 'court_cases_status', 'court_cases_under', 'court_cases_submitted_by',
            'court_cases_parawise_comments', 'court_cases_instruction', 'court_cases_affidavit', 'court_cases_additional_affidavit', 'court_cases_final_order', 'court_cases_speaking_order', 'court_cases_interim_order'));
        }
        else
        {
          Auth::logout();
          return redirect('/');
        }
    }
    public function manage_court_case(Request $request) {
        if(Auth::check())
        {
            $court_cases_id = Crypt::decrypt($request->route('id'));
            $districts = District::select('id','district_name')->get();

            $court_cases = DB::select('select * from court_cases where id = ? order by id asc', [$court_cases_id]);
            for($i=0;$i<sizeof($court_cases);$i++)
            {
                $temp_date = explode('-', $court_cases[$i]->date_of_receipt_of_wpc_notice);
                $court_cases[$i]->date_of_receipt_of_wpc_notice = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
            }

            $court_cases_type = DB::select('select * from court_cases_type order by id asc');
            $court_cases_nature = DB::select('select * from court_cases_nature order by id asc');
            $court_cases_status = DB::select('select * from court_cases_status order by id asc');
            $court_cases_under = DB::select('select * from court_cases_under order by id asc');

            $court_cases_submitted_by = DB::select('select * from court_cases_submitted_by order by id asc');

            $court_cases_parawise_comments = DB::select('select *, a.id as i_id from court_cases_parawise_comments a, court_cases_submitted_by b where court_case_id = ? AND a.parawise_comments_submitted_by = b.id order by a.id asc', [$court_cases_id]);
            if( !empty($court_cases_parawise_comments)) {
                for($i=0;$i<sizeof($court_cases_parawise_comments);$i++) {
                    $temp_date = explode('-', $court_cases_parawise_comments[$i]->due_date_of_parawise_comments);
                    $court_cases_parawise_comments[$i]->due_date_of_parawise_comments = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                }
            }

            $court_cases_instruction = DB::select('select *, a.id as i_id from court_cases_instruction a, court_cases_submitted_by b where court_case_id = ? AND a.instruction_submitted_by = b.id order by a.id asc', [$court_cases_id]);
            if( !empty($court_cases_instruction)) {
                for($i=0;$i<sizeof($court_cases_instruction);$i++) {
                    $temp_date = explode('-', $court_cases_instruction[$i]->due_date_of_instruction);
                    $court_cases_instruction[$i]->due_date_of_instruction = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                }
            }

            $court_cases_affidavit = DB::select('select * from court_cases_affidavit where court_case_id = ? AND category="primary" order by id asc', [$court_cases_id]);
            if( !empty($court_cases_affidavit)) {
                $temp_date = explode('-', $court_cases_affidavit[0]->date_of_affidavit_submitted);
                $court_cases_affidavit[0]->date_of_affidavit_submitted = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
            }

            $court_cases_additional_affidavit = DB::select('select * from court_cases_affidavit where court_case_id = ? AND category="additional" order by id asc', [$court_cases_id]);
            if( !empty($court_cases_additional_affidavit)) {
                $temp_date = explode('-', $court_cases_additional_affidavit[0]->date_of_affidavit_submitted);
                $court_cases_additional_affidavit[0]->date_of_affidavit_submitted = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
            }

            $court_cases_final_order = DB::select('select * from court_cases_final_order where court_case_id = ? order by id asc', [$court_cases_id]);
            if( !empty($court_cases_final_order)) {
                $temp_date = explode('-', $court_cases_final_order[0]->due_date_of_final_order);
                $court_cases_final_order[0]->due_date_of_final_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];

                $temp_date = explode('-', $court_cases_final_order[0]->date_of_receipt_of_final_order);
                $court_cases_final_order[0]->date_of_receipt_of_final_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
            }

            $court_cases_speaking_order = DB::select('select *, a.id as i_id from court_cases_speaking_order a, court_cases_submitted_by b where court_case_id = ? AND  a.speaking_order_passed_by = b.id order by a.id asc', [$court_cases_id]);
            if( !empty($court_cases_speaking_order)) {
                for($i=0;$i<sizeof($court_cases_speaking_order);$i++) {
                    $temp_date = explode('-', $court_cases_speaking_order[$i]->date_of_speaking_order);
                    $court_cases_speaking_order[$i]->date_of_speaking_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                }
            }

            $court_cases_interim_order = DB::select('select *, a.id as i_id from court_cases_interim_order a, court_cases_submitted_by b where court_case_id = ? AND a.action_to_be_taken_by = b.id order by a.id asc', [$court_cases_id]);
            if( !empty($court_cases_interim_order)) {
                for($i=0;$i<sizeof($court_cases_interim_order);$i++) {
                    $temp_date = explode('-', $court_cases_interim_order[$i]->due_date_of_interim_order);
                    $court_cases_interim_order[$i]->due_date_of_interim_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                }
            }
            return view('CourtCases.manageCourtCase', compact('districts', 'court_cases', 'court_cases_type', 'court_cases_nature', 'court_cases_status', 'court_cases_under', 'court_cases_submitted_by',
            'court_cases_parawise_comments', 'court_cases_instruction', 'court_cases_affidavit', 'court_cases_additional_affidavit', 'court_cases_final_order', 'court_cases_speaking_order', 'court_cases_interim_order'));
        }
        else
        {
          Auth::logout();
          return redirect('/');
        }
    }
    public function update_court_case_primary(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;
            $c_id = Crypt::decrypt($request->input('court_case_id'));
            $case_type = Crypt::decrypt($request->input('case_type'));
            $districts = $request->input('districts');
            $districts = explode(',', $districts);

            $i = 0;
            foreach($districts as $values) {
                $districts[$i] = Crypt::decrypt($values);
                $i++;
            }
            $districts_json = json_encode($districts);

            $file_number = $request->input('file_number');
            $case_number = $request->input('case_number');
            $name_of_the_petitioner = $request->input('name_of_the_petitioner');
            $subject_matter_of_the_case = $request->input('subject_matter_of_the_case');

            $date_of_receipt_of_wpc_notice = $request->input('date_of_receipt_of_wpc_notice');
            $temp_date = explode('-', $date_of_receipt_of_wpc_notice);
            $date_of_receipt_of_wpc_notice = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];

            $nature_of_case = Crypt::decrypt($request->input('nature_of_case'));
            $case_status = Crypt::decrypt($request->input('case_status'));
            $case_under = Crypt::decrypt($request->input('case_under'));
            $remarks = $request->input('remarks');

            // $date_of_interim_order = $request->input('date_of_interim_order');
            // $brief_of_interim_order = $request->input('brief_of_interim_order');
            // $details_action_taken_as_per_interim_order = $request->input('details_action_taken_as_per_interim_order');

            // $date_of_final_order = $request->input('date_of_final_order');
            // $date_of_receipt_of_final_order = $request->input('date_of_receipt_of_final_order');
            // $details_of_final_order = $request->input('details_of_final_order');
            // $details_of_action_taken_as_per_financial_order = $request->input('details_of_action_taken_as_per_financial_order');

            $court_case = DB::update('UPDATE court_cases SET case_type_id = ?, district_id = ?, file_number = ?, case_number = ?, name_of_petitioner = ?, subject_matter_of_case = ?, date_of_receipt_of_wpc_notice = ?, nature_of_case = ?, case_status_id = ?, case_under = ?, remarks = ?  WHERE id = ?',
            [ $case_type, $districts_json, $file_number, $case_number, $name_of_the_petitioner, $subject_matter_of_the_case, $date_of_receipt_of_wpc_notice, $nature_of_case, $case_status, $case_under, $remarks, $c_id]);

            $court_case_districts = DB::select('select district_id from court_cases where id = ? order by id asc', [$c_id]);
            $returnData['districts'] = Crypt::encrypt($court_case_districts[0]->district_id);

            if($court_case == 0){
                $returnData['msgType']="Same";
            }
            if($court_case > 0){
                $returnData['msgType']="Updated";
            }
            return json_encode($returnData);
        }
    }
    public function view_blocks(Request $request) {
        if($request->ajax())
        {
            $id = Crypt::decrypt($request->input('section_id'));
            $st_id = Crypt::decrypt($request->input('section_table_id'));
            $d_id = Crypt::decrypt($request->input('d_id'));

            $district_ids = json_decode($d_id);
            $size = sizeof($district_ids);
            $districts_blocks = DB::select('select * from court_cases_blocks where section_id = ? AND section_table_id = ?', [$id, $st_id]);

            echo    '<div class="row make-columns" style="padding:15px;">';
            for($i=0;$i<$size;$i++) {
                $court_cases_district = DB::select('select * from districts where id = ? order by id asc', [$district_ids[$i]]);
                $court_cases_blocks = DB::select('select * from blocks where district_id = ? order by block_name asc', [$district_ids[$i]]);
                if( empty($districts_blocks) ) {
                    continue;
                }

                $p = 0;
                echo    '<div class="col-md-4" style="padding:4px 10px;">';
                echo    '<div class="panel panel-default block-back" style="background:#f8f8f8;padding:10px 10px 13.5px 10px;border-radius:1px;">';
                echo    '<label style="text-decoration:underline;text-underline-position:under;">'.$court_cases_district[0]->district_name.' - District</label>';
                foreach($court_cases_blocks as $values) {
                    if(!empty($districts_blocks)) {
                        foreach($districts_blocks as $blocks) {
                            if( $values->id == $blocks->block_id ) {
                                echo "<p style='margin:0;'>".$values->block_name."</p>";
                                $p = 1;
                            }
                        }
                    }
                }
                if( $p == 0)
                    echo "<p style='margin-top:10px;'>N/A</p>";
                echo    '</div>';
                echo    '</div>';
            }
            echo    '</div><style>.make-columns p {color:#727272;font-weight:bold;font-size:11.5pt;font-family:"Roboto";}</style>';
            return;
        }
    }

    public function load_district_blocks(Request $request) {
        if($request->ajax())
        {
            $id = Crypt::decrypt($request->input('id'));
            $d_id = Crypt::decrypt($request->input('d_id'));

            if( $id == 6) {
                $district_ids = json_decode($d_id);
                $size = sizeof($district_ids);
                $options = '';
                $options = '<label>Blocks</label>
                            <select id="parawise_comments_blocks" class="selectpicker form-control select-margin parawise_blocks" name="blocks" data-style="btn-info" multiple autocomplete="off" required >';
                for($i=0;$i<$size;$i++) {
                    $court_cases_district = DB::select('select * from districts where id = ? order by id asc', [$district_ids[$i]]);
                    $court_cases_blocks = DB::select('select * from blocks where district_id = ? order by block_name asc', [$district_ids[$i]]);

                    $options .= '<optgroup label="'.$court_cases_district[0]->district_name.' (District)">';
                    foreach($court_cases_blocks as $values) {
                        $options .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->block_name.'</option>';
                    }
                    $options .= '</optgroup>';
                }
                $options .= '</select>';

                echo $options;
                return;
            }
            else
                return "";
        }
    }

    //PARAWISE-------------------------------------------------------------------------------------------------------
    public function add_parawise_comments(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;

            $c_id = Crypt::decrypt($request->input('court_case_id'));
            $due_date_of_parawise_comments = $request->input('due_date_of_parawise_comments');
            $parawise_comments_submitted_by = Crypt::decrypt($request->input('parawise_comments_submitted_by'));
            $parawise_comments_letter_number = $request->input('parawise_comments_letter_number');
            $parawise_comments_document = $request->file('parawise_comments_document');
            $parawise_comments_submitted = $request->input('parawise_comments_submitted');

            $date_val=Carbon::parse($due_date_of_parawise_comments)->format('Y-m-d'); 

            $court_cases_interim_order = DB::select('select * from court_cases_parawise_comments a, court_cases_submitted_by b where a.court_case_id = ? AND a.parawise_comments_submitted_by = b.id order by a.id desc', [$c_id]);

            if( sizeof($court_cases_interim_order) >= 5 ) {
                $returnData['msgType']="Parawise";
                $returnData['info']="exceeded";
                $returnData['msg']="Parawise Comments Cannot be Entered more than 5 times";
                return json_encode($returnData);
            }
            $file_path  = "";
            if(isset($_FILES["parawise_comments_document"]["type"]))
            {
                if ( ($_FILES["parawise_comments_document"]["type"] == "application/pdf") && ($_FILES["parawise_comments_document"]["size"] < 2000000) )
                {
                    if ($_FILES['parawise_comments_document']["error"] > 0) {
                        echo "Return Code: " . $_FILES['cv']["error"];
                        return;
                    }
                    else {
                        $file_path = $request->file('parawise_comments_document')->store('courtCases/parawiseDocument');
                    }
                }
                else {
                    if( $_FILES["parawise_comments_document"]["error"] == 4 ) {
                        $file_path = NULL;
                    }
                    else {
                        $returnData['msg'] = "*** File is not Valid or exceeds 2 MB ***";
                        return $returnData;
                    }
                }
            }

            $reset = DB::update('UPDATE court_cases_parawise_comments SET status = "inactive" WHERE court_case_id = ?', [$c_id]);
            $current_datetime = now();

            $court_case_pc= DB::table('court_cases_parawise_comments')
                ->insertGetId([
                    'court_case_id' => $c_id,
                    'due_date_of_parawise_comments' => $date_val,
                    'parawise_comments_submitted_by' => $parawise_comments_submitted_by,
                    'letter_number' => $parawise_comments_letter_number,
                    'document' => $file_path,
                    'submitted' => $parawise_comments_submitted,
                    'status' => "active",
                    'created_by'=> Auth::user()->username,
                    'created_at'=> $current_datetime
                ]);

            if( $parawise_comments_submitted_by == 6 ) {
                $parawise_comments_blocks = $request->input('blocks');
                $parawise_comments_blocks = explode(',', $parawise_comments_blocks);
                $i = 0;
                foreach($parawise_comments_blocks as $values) {
                    $parawise_comments_blocks[$i] = Crypt::decrypt($values);
                    $i++;
                }

                $size = sizeof($parawise_comments_blocks);

                for($i=0;$i<$size;$i++) {
                    $blocks= DB::table('court_cases_blocks')
                    ->insert([
                        'section_id' => 1,
                        'section_table_id' => $court_case_pc,
                        'block_id' => $parawise_comments_blocks[$i]
                    ]);
                }
            }

            if($court_case_pc) {
                $returnData['msgType']="Parawise";
                $returnData['info']="add";
                $returnData['msg']="Parawise Comments has been added... ";
            }

            $messageUpdate= CourtCasesMessageTrackTable::add_message_sending("PARAWISE COMMENTS", $c_id, $due_date_of_parawise_comments, $parawise_comments_submitted_by, $court_case_pc);

            if($messageUpdate['msgType']==false){
                $returnData['msg']= $returnData['msg'].$messageUpdate['msg'];
                return json_encode($returnData);
            }else{
                $returnData['msg']=$returnData['msg']. "Message successfully sent...";
            }

            return json_encode($returnData);
        }
    }
    public function refresh_parawise_comments(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;

            $c_id = Crypt::decrypt($request->input('id'));

            $court_cases_parawise_comments = DB::select('select *, a.id as i_id from court_cases_parawise_comments a, court_cases_submitted_by b where court_case_id = ? AND a.parawise_comments_submitted_by = b.id order by a.id asc', [$c_id]);
            if( !empty($court_cases_parawise_comments)) {
                for($i=0;$i<sizeof($court_cases_parawise_comments);$i++) {
                    $temp_date = explode('-', $court_cases_parawise_comments[$i]->due_date_of_parawise_comments);
                    $court_cases_parawise_comments[$i]->due_date_of_parawise_comments = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                }

                $data = "";
                foreach($court_cases_parawise_comments as $li)
                {
                    if( isset($li->document) != "" )
                        $button = '<a href="'.url("admin/courtCases/viewCourtCaseParawiseComments/".Crypt::encrypt($li->i_id)).'" target="_blank"  class="btn btn-outline-default waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:73px;margin:5px 0px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View</a>';
                    else
                        $button = "No Document";
                    $data .= '<tr>
                            <td>'.$li->due_date_of_parawise_comments;
                                if( $li->status == "active" ) {
                                    $data .= '<br /><span class="badge" style="background-color: green">In Effect</span>';
                                }
                    $data .= '</td>
                            <td>'.$li->submitted_by.'</td>
                            <td>'.$li->letter_number.'</td>
                            <td>'.$button.'</td>
                            <td>'.$li->submitted.'</td>
                            <td>
                                <a onClick="load_parawise_comments_update('.$li->i_id.');" data-toggle="modal" data-target="#parawisecommentsupdateModal" class="btn aqua-gradient waves-effect btn-lg" style="font-weight:bold;font-size:10pt;padding:5px 5px;width:87px;"><i class="fa fa-edit"></i>&nbsp;&nbsp;Update</a>
                            </td>
                        </tr>';
                }
            }
            return $data;
        }
    }
    public function load_parawise_comments(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;

            $i_id = $request->input('id');

            $court_cases_submitted_by = DB::select('select * from court_cases_submitted_by order by id asc');

            $court_cases_parawise_comments = DB::select('select * from court_cases_parawise_comments where id = ? order by id desc', [$i_id]);
            $button = ''; $data = "";$options = '';

            if( !empty($court_cases_parawise_comments)) {
                $temp_date = explode('-', $court_cases_parawise_comments[0]->due_date_of_parawise_comments);
                $court_cases_parawise_comments[0]->due_date_of_parawise_comments = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];

                $court_cases_districts = DB::select('select district_id from court_cases where id = ? order by id desc', [$court_cases_parawise_comments[0]->court_case_id]);

                if( isset($court_cases_parawise_comments[0]->parawise_comments_submitted_by) ) {
                    if( $court_cases_parawise_comments[0]->parawise_comments_submitted_by == 6 ) {

                        $district_ids = json_decode($court_cases_districts[0]->district_id);
                        $size = sizeof($district_ids);
                        $parawise_blocks = DB::select('select * from court_cases_blocks where section_id = 1 AND section_table_id = ?', [$i_id]);

                        $options = '<label>Blocks</label>
                                    <select id="parawise_comments_blocks" class="selectpicker form-control select-margin parawise_blocks" name="blocks" data-style="btn-info" multiple autocomplete="off" required >';
                        for($i=0;$i<$size;$i++) {
                            $court_cases_district = DB::select('select * from districts where id = ? order by id asc', [$district_ids[$i]]);
                            $court_cases_blocks = DB::select('select * from blocks where district_id = ? order by block_name asc', [$district_ids[$i]]);

                            $options .= '<optgroup label="'.$court_cases_district[0]->district_name.'">';
                            foreach($court_cases_blocks as $values) {
                                $p = 0;
                                if(!empty($parawise_blocks)) {
                                    foreach($parawise_blocks as $blocks) {
                                        if( $values->id == $blocks->block_id ) {
                                            $options .= '<option value="'.Crypt::encrypt($values->id).'" selected >'.$values->block_name.'</option>';
                                            $p=1;break;
                                        }
                                        else {
                                            $p=0;
                                        }
                                    }
                                    if( $p == 0 )
                                        $options .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->block_name.'</option>';
                                }
                                else
                                    $options .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->block_name.'</option>';
                            }
                            $options .= '</optgroup>';
                        }
                        $options .= '</select>';
                    }
                    else {
                        $options .= '';
                    }
                }
                if( isset($court_cases_parawise_comments[0]->document) )
                {
                    if( $court_cases_parawise_comments[0]->document != ""  && $court_cases_parawise_comments[0]->document != NULL )
                    {
                        $button = '<a href="'.url("admin/courtCases/viewCourtCaseParawiseComments/".Crypt::encrypt($court_cases_parawise_comments[0]->id)).'" target="_blank"  class="btn btn-outline-default waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:145px;margin:5px 0px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View Document</a>';
                    }                        
                }

                $yesChecked = ""; $noChecked = "";
                if( isset($court_cases_parawise_comments[0]->submitted) ) {
                    if( $court_cases_parawise_comments[0]->submitted == 'Yes' )
                        $yesChecked = "Checked";
                    else
                        $noChecked = "Checked";
                }

                $data = '
                    <div class="form-group"> 
                        <div class="row">
                            <input type="hidden" name="parawise_comments_id" value="'.Crypt::encrypt($i_id).'" />
                            <div class="col-sm-6">
                                <label for="exampleInputPassword1">Parawise Comments to be Submitted By</label>
                                <select id="update_parawise_comments_submitted_by" class="selectpicker form-control select-margin court-case-select" name="parawise_comments_submitted_by" data-style="btn-info" required >
                                    <option value="'.Crypt::encrypt('').'">Select</option>';
                                    foreach($court_cases_submitted_by as $values)
                                    {
                                        if( isset($court_cases_parawise_comments[0]->parawise_comments_submitted_by) ) {
                                            if( $court_cases_parawise_comments[0]->parawise_comments_submitted_by == $values->id ) { 
                                                $data .= '<option value="'.Crypt::encrypt($values->id).'" selected >'.$values->submitted_by.'</option>';
                                            }
                                            else
                                                $data .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->submitted_by.'</option>';
                                        }
                                    }
                       $data .= '</select>
                            </div>
                            <div class="col-sm-6">
                                <div id="update_parawise_comments_submitted_by_blocks" class="select_districts_blocks">'.$options.'</div>
                            </div>
                        </div><br /><br />
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1">Due Date of Submission of Parawise Comments</label><br>
                                <input id="parawise_comments_update_date" name="due_date_of_parawise_comments" type="text" value="'.$court_cases_parawise_comments[0]->due_date_of_parawise_comments.'" class="form-control datepicker" data-zdp_readonly_element="true" required />
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1">Letter Number </label>
                                <input name="parawise_comments_letter_number" type="text" value="'.$court_cases_parawise_comments[0]->letter_number.'" class="form-control" >
                            </div>
                        </div><br /><br />
                        <div class="row">
                            <div class="col-md-6">
                                <label>Upload Document</label>
                                <input type="file" name="parawise_comments_document" id="parawisecommentsDocument" value="" class="form-control" >
                                <div id="upload_parawise_comments">'.$button.'</div>
                                <b>.pdf files only (should be less than 2 MB)</b>
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1">Submitted</label><br />
                                <!-- ANIMATED CHECKBOXES -->
                                <div class="checkbox-animated col-sm-6" style="width:155px;">
                                    <input id="checkbox_11" type="radio" class="checkbox" name="parawise_comments_submitted" value="Yes" '.$yesChecked.' />
                                    <label for="checkbox_11">
                                        <span class="check"></span>
                                        <span class="box"></span>
                                        YES 
                                    </label>
                                </div>
                                <div class="checkbox-animated col-sm-6" style="width:155px;">
                                    <input id="checkbox_21" type="radio" class="checkbox" name="parawise_comments_submitted" value="No" '.$noChecked.' />
                                    <label for="checkbox_21">
                                        <span class="check"></span>
                                        <span class="box"></span>
                                        NO
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        $("#parawise_comments_update_date").Zebra_DatePicker({
                            format: "d-m-Y"
                        });
                    </script>';

            }
            return $data;
        }
    }
    public function update_parawise_comments(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;

            $i_id = Crypt::decrypt($request->input('parawise_comments_id'));
            $due_date_of_parawise_comments = $request->input('due_date_of_parawise_comments');
            $parawise_comments_submitted_by = Crypt::decrypt($request->input('parawise_comments_submitted_by'));
            $parawise_comments_letter_number = $request->input('parawise_comments_letter_number');
            $parawise_comments_document = $request->file('parawise_comments_document');
            $parawise_comments_submitted = $request->input('parawise_comments_submitted');

            $query = DB::select('select court_case_id from court_cases_parawise_comments where id = ?', [$i_id]);
            $due_date_of_parawise_comments=Carbon::parse($due_date_of_parawise_comments)->format('Y-m-d');
            $current_datetime = now();
            $p =  0;
            if(isset($_FILES["parawise_comments_document"]["type"]))
            {
                if ( ($_FILES["parawise_comments_document"]["type"] == "application/pdf") && ($_FILES["parawise_comments_document"]["size"] < 2000000) )
                {
                    if ($_FILES['parawise_comments_document']["error"] > 0) {
                        echo "Return Code: " . $_FILES['cv']["error"];return;
                    }
                    else {
                        if(isset($query[0]->document))
                        {
                            if( $query[0]->document != NULL || $query[0]->document != "") {
                                $file = fopen(storage_path('app/'.$query[0]->document), 'w') or die("can't open file");
                                fclose($file);
                                unlink(storage_path('app/'.$query[0]->document));
                            }
                        }
                        $file_path = $request->file('parawise_comments_document')->store('courtCases/parawiseComments');
                    }
                }
                else if( $_FILES["parawise_comments_document"]["name"] == "" ) {
                    $p = 1;
                }
                else {
                    $returnData['msg'] = "*** CV File is not Valid or exceeds 2 MB ***";
                    return $returnData;
                }

                if( $p == 1) {
                    $court_case = DB::update('UPDATE court_cases_parawise_comments SET due_date_of_parawise_comments = ?, parawise_comments_submitted_by = ?, letter_number = ?, submitted = ?, updated_by = ?, updated_at = ? WHERE id = ?',
                    [ $due_date_of_parawise_comments, $parawise_comments_submitted_by, $parawise_comments_letter_number, $parawise_comments_submitted, Auth::user()->username, $current_datetime, $i_id]);
                }
                else {
                    $court_case = DB::update('UPDATE court_cases_parawise_comments SET due_date_of_parawise_comments = ?, parawise_comments_submitted_by = ?, letter_number = ?, document = ?, submitted = ?, updated_by = ?, updated_at = ? WHERE id = ?',
                    [ $due_date_of_parawise_comments, $parawise_comments_submitted_by, $parawise_comments_letter_number, $file_path, $parawise_comments_submitted, Auth::user()->username, $current_datetime, $i_id]);
                }
            }
            if( $parawise_comments_submitted_by == 6 ) {
                $parawise_comments_blocks = $request->input('blocks');
                $parawise_comments_blocks = explode(',', $parawise_comments_blocks);
                $i = 0;
                foreach($parawise_comments_blocks as $values) {
                    $parawise_comments_blocks[$i] = Crypt::decrypt($values);
                    $i++;
                }
                $parawise_blocks = DB::delete('delete from court_cases_blocks where section_id = 1 AND section_table_id = ?', [$i_id]);

                $size = sizeof($parawise_comments_blocks);

                for($i=0;$i<$size;$i++) {
                    $blocks= DB::table('court_cases_blocks')
                    ->insert([
                        'section_id' => 1,
                        'section_table_id' => $i_id,
                        'block_id' => $parawise_comments_blocks[$i]
                    ]);
                }
            }
            if($court_case > 0){
                $returnData['msgType']="Parawise";
                $returnData['info']="update";
                $returnData['msg']="Parawise Comments Details has been Updated... ";
            }
            //return json_encode($returnData);

            // $messageUpdate= CourtCasesMessageTrackTable::update_message_sending("INTERIM ORDER", $query[0]->court_case_id, $due_date_of_interim_order, $action_to_be_taken_by, $i_id);

            // if($messageUpdate['msgType']==false){
            //     $returnData['msgType']=false;
            //     $returnData['msg']=$messageUpdate['msg'];
            //     return response()->json($returnData);
            // }
            $messageUpdate= CourtCasesMessageTrackTable::update_message_sending("PARAWISE COMMENTS", $query[0]->court_case_id, $due_date_of_parawise_comments, $parawise_comments_submitted_by, $i_id);

            if($messageUpdate['msgType']==false){
                $returnData['msg']= $returnData['msg'].$messageUpdate['msg'];
                return json_encode($returnData);
            }else{
                $returnData['msg']=$returnData['msg']. "Message successfully sent...";
            }
            return json_encode($returnData);
        }
    }
    public function view_court_case_parawise_comments($id) {
        $id = Crypt::decrypt($id);
        $document = DB::select('Select document from court_cases_parawise_comments where id=?', [$id]);
        //return Response::download(storage_path('app/'.$document[0]->document));
        return response()->file(storage_path('app/'.$document[0]->document));
    }

    //AFFIDAVIT---------------------------------------------------------------------------------------------------------
    public function add_court_case_affidavit(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;

            $c_id = Crypt::decrypt($request->input('court_case_id'));
            $affidavit_submitted_by = Crypt::decrypt($request->input('affidavit_submitted_by'));
            $date_of_affidavit_submitted = $request->input('date_of_affidavit_submitted');

            $date_val=Carbon::parse($date_of_affidavit_submitted)->format('Y-m-d');

            $query = DB::select('select * from court_cases_affidavit where court_case_id = ? AND category="primary" order by id asc', [$c_id]);

            if(isset($_FILES["affidavitDocument"]["type"]))
            {
                if ( ($_FILES["affidavitDocument"]["type"] == "application/pdf") && ($_FILES["affidavitDocument"]["size"] < 2000000)) {
                    if ($_FILES['affidavitDocument']["error"] > 0) {
                        echo "Return Code: " . $_FILES['cv']["error"];return;
                    }
                    else {
                        $document = DB::select('Select document from court_cases_affidavit where court_case_id=? AND category="primary"', [$c_id]);
                        if(isset($document[0]->document))
                        {
                            if( $document[0]->document != NULL || $document[0]->document != "") {
                                $file = fopen(storage_path('app/'.$document[0]->document), 'w') or die("can't open file");
                                fclose($file);
                                unlink(storage_path('app/'.$document[0]->document));
                            }
                        }

                        $file_path = $request->file('affidavitDocument')->store('courtCases/affidavit');
                    }
                }
                else
                {
                    $returnData['msg'] = "*** CV File is not Valid or exceeds 2 MB ***";
                    return $returnData;
                }
            }
            if( !empty($query) ) {
                $current_datetime = now();
                $court_case = DB::update('UPDATE court_cases_affidavit SET affidavit_submitted_by = ?, date_of_affidavit_submitted = ?, category = ?, document = ?, updated_by = ?, updated_at = ?  WHERE id = ? AND category != "additional"',
                [ $affidavit_submitted_by, $date_val, 'primary', $file_path,  Auth::user()->username, $current_datetime, $query[0]->id ]);
                if($court_case > 0){
                    $returnData['msgType']="Updated";
                    $returnData['msgFrom']="Affidavit";
                    $returnData['Affidavit_id']=Crypt::encrypt($query[0]->id);
                    $returnData['msg']="Successfully Updated the details of Affidavit";
                }
                $court_case_a = $query[0]->id;
            }
            else {
                $current_datetime = now();
                $court_case= DB::table('court_cases_affidavit')
                    ->insertGetId([
                        'court_case_id' => $c_id,
                        'affidavit_submitted_by' => $affidavit_submitted_by,
                        'date_of_affidavit_submitted' => $date_val,
                        'category' => 'primary',
                        'document' => $file_path,
                        'created_by' => Auth::user()->username,
                        'created_at' => $current_datetime
                    ]);
                if($court_case) {
                    $returnData['msgType']=true;
                    $returnData['msgFrom']="Affidavit";
                    $returnData['Affidavit_id']=Crypt::encrypt($court_case);
                    $returnData['msg']="Affidavit Details have been added";
                }
                $court_case_a = $court_case;
            }
            if( $affidavit_submitted_by == 6 ) {
                $affidavit_blocks = $request->input('blocks');
                $affidavit_blocks = explode(',', $affidavit_blocks);
                $i = 0;
                foreach($affidavit_blocks as $values) {
                    $affidavit_blocks[$i] = Crypt::decrypt($values);
                    $i++;
                }
                $delete_blocks = DB::delete('delete from court_cases_blocks where section_id = 2 AND section_table_id = ?', [$court_case_a]);

                $size = sizeof($affidavit_blocks);

                for($i=0;$i<$size;$i++) {
                    $blocks= DB::table('court_cases_blocks')
                    ->insert([
                        'section_id' => 2,
                        'section_table_id' => $court_case_a,
                        'block_id' => $affidavit_blocks[$i]
                    ]);
                }
            }
            return json_encode($returnData);
        }
    }
    public function view_court_case_affidavit($id){
        $id = Crypt::decrypt($id);
        $document = DB::select('Select document from court_cases_affidavit where id=?', [$id]);
        //return Response::download(storage_path('app/'.$document[0]->document));
        return response()->file(storage_path('app/'.$document[0]->document));
    }
    public function add_court_case_additional_affidavit(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;

            $c_id = Crypt::decrypt($request->input('court_case_id'));
            $additional_affidavit_submitted_by = Crypt::decrypt($request->input('additional_affidavit_submitted_by'));
            $date_of_additional_affidavit_submitted = $request->input('date_of_additional_affidavit_submitted');

            $date_val=Carbon::parse($date_of_additional_affidavit_submitted)->format('Y-m-d');

            $query = DB::select('select * from court_cases_affidavit where court_case_id = ? AND category="primary" order by id asc', [$c_id]);
            if( empty($query) ) {
                $returnData['msg']="Affidavit should be provided before Additional Affidavit";
                return json_encode($returnData);
            }

            $query = DB::select('select * from court_cases_affidavit where court_case_id = ? AND category="additional" order by id asc', [$c_id]);
            if(isset($_FILES["additionalaffidavitDocument"]["type"]))
            {
                if ( ($_FILES["additionalaffidavitDocument"]["type"] == "application/pdf") && ($_FILES["additionalaffidavitDocument"]["size"] < 2000000) )
                {
                    if ($_FILES['additionalaffidavitDocument']["error"] > 0) {
                        echo "Return Code: " . $_FILES['cv']["error"];return;
                    }
                    else {
                        $document = DB::select('Select document from court_cases_affidavit where court_case_id=? AND category="additional"', [$c_id]);
                        if(isset($document[0]->document))
                        {
                            if( $document[0]->document != NULL || $document[0]->document != "") {
                                $file = fopen(storage_path('app/'.$document[0]->document), 'w') or die("can't open file");
                                fclose($file);
                                unlink(storage_path('app/'.$document[0]->document));
                            }
                        }

                        $file_path = $request->file('additionalaffidavitDocument')->store('courtCases/affidavit');
                    }
                }
                else
                {
                    $returnData['msg'] = "*** CV File is not Valid or exceeds 2 MB ***";
                    return $returnData;
                }
            }
            if( !empty($query) ) {
                $current_datetime = now();
                $court_case = DB::update('UPDATE court_cases_affidavit SET affidavit_submitted_by = ?, date_of_affidavit_submitted = ?, category = ?, document = ?, updated_by = ?, updated_at = ?  WHERE id = ? AND category != "primary"',
                [ $additional_affidavit_submitted_by, $date_val, 'additional', $file_path, Auth::user()->username, $current_datetime, $query[0]->id ]);
                if($court_case > 0){
                    $returnData['msgType']="Updated";
                    $returnData['msgFrom']="Additional Affidavit";
                    $returnData['Affidavit_id']=Crypt::encrypt($query[0]->id);
                    $returnData['msg']="Successfully Updated the details of Additional Affidavit";
                }
                $court_case_aa = $query[0]->id;
            }
            else {
                $current_datetime = now();
                $court_case= DB::table('court_cases_affidavit')
                    ->insertGetId([
                        'court_case_id' => $c_id,
                        'affidavit_submitted_by' => $additional_affidavit_submitted_by,
                        'date_of_affidavit_submitted' => $date_val,
                        'category' => 'additional',
                        'document' => $file_path,
                        'created_by' => Auth::user()->username,
                        'created_at' => $current_datetime
                    ]);
                if($court_case){
                    $returnData['msgType']=true;
                    $returnData['msgFrom']="Additional Affidavit";
                    $returnData['Affidavit_id']=Crypt::encrypt($court_case);
                    $returnData['msg']="Additional Affidavit Details have been added";
                }
                $court_case_aa = $court_case;
            }
            if( $additional_affidavit_submitted_by == 6 ) {
                $additional_affidavit_blocks = $request->input('blocks');
                $additional_affidavit_blocks = explode(',', $additional_affidavit_blocks);
                $i = 0;
                foreach($additional_affidavit_blocks as $values) {
                    $additional_affidavit_blocks[$i] = Crypt::decrypt($values);
                    $i++;
                }
                $delete_blocks = DB::delete('delete from court_cases_blocks where section_id = 3 AND section_table_id = ?', [$court_case_aa]);

                $size = sizeof($additional_affidavit_blocks);

                for($i=0;$i<$size;$i++) {
                    $blocks= DB::table('court_cases_blocks')
                    ->insert([
                        'section_id' => 3,
                        'section_table_id' => $court_case_aa,
                        'block_id' => $additional_affidavit_blocks[$i]
                    ]);
                }
            }
            return json_encode($returnData);
        }
    }

    //INTERIM ORDER-----------------------------------------------------------------------------------------------------
    public function add_interim_order(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;

            $c_id = Crypt::decrypt($request->input('court_case_id'));
            $due_date_of_interim_order = $request->input('due_date_of_interim_order');
            $action_to_be_taken_by = Crypt::decrypt($request->input('action_to_be_taken_by'));
            $brief_of_interim_order = $request->input('brief_of_interim_order');

            $due_date_of_interim_order=Carbon::parse($due_date_of_interim_order)->format('Y-m-d');

            $court_cases_interim_order = DB::select('select * from court_cases_interim_order a, court_cases_submitted_by b where a.court_case_id = ? AND a.action_to_be_taken_by = b.id order by a.id desc', [$c_id]);

            if( sizeof($court_cases_interim_order) >= 5 ) {
                $returnData['msgType']="Interim";
                $returnData['info']="exceeded";
                $returnData['msg']="Interim Order Cannot be Entered more than 5 times";
                return json_encode($returnData);
            }

            $reset = DB::update('UPDATE court_cases_interim_order SET status = "inactive" WHERE court_case_id = ?', [$c_id]);
            $current_datetime = now();

            $court_case_i= DB::table('court_cases_interim_order')
                ->insertGetId([
                    'court_case_id' => $c_id,
                    'due_date_of_interim_order' => $due_date_of_interim_order,
                    'action_to_be_taken_by' => $action_to_be_taken_by,
                    'brief_of_interim_order' => $brief_of_interim_order,
                    'status' => "active",
                    'created_by'=> Auth::user()->username,
                    'created_at'=> $current_datetime
                ]);

            if( $action_to_be_taken_by == 6 ) {
                $interim_blocks = $request->input('blocks');
                $interim_blocks = explode(',', $interim_blocks);
                $i = 0;
                foreach($interim_blocks as $values) {
                    $interim_blocks[$i] = Crypt::decrypt($values);
                    $i++;
                }

                $size = sizeof($interim_blocks);

                for($i=0;$i<$size;$i++) {
                    $blocks= DB::table('court_cases_blocks')
                    ->insert([
                        'section_id' => 4,
                        'section_table_id' => $court_case_i,
                        'block_id' => $interim_blocks[$i]
                    ]);
                }
            }
            if($court_case_i) {
                $returnData['msgType']="Interim";
                $returnData['info']="add";
                $returnData['msg']="Interim Order Details have been added... ";
            }

            $messageUpdate= CourtCasesMessageTrackTable::add_message_sending("INTERIM ORDER", $c_id, $due_date_of_interim_order, $action_to_be_taken_by, $court_case_i);

            if($messageUpdate['msgType']==false) {
                $returnData['msg']= $returnData['msg'].$messageUpdate['msg'];
                return json_encode($returnData);
            } else {
                $returnData['msg']=$returnData['msg']. "Message successfully sent...";
            }

            // $messageUpdate= CourtCasesMessageTrackTable::add_message_sending("INTERIM ORDER", $c_id, $due_date_of_interim_order, $action_to_be_taken_by, $court_case_i->id);

            // if($messageUpdate['msgType']==false){
            //     $returnData['msgType']=false;
            //     $returnData['msg']=$messageUpdate['msg'];
            //     return json_encode($returnData);
            // }

            return json_encode($returnData);
        }
    }
    public function refresh_interim_order(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;

            $c_id = Crypt::decrypt($request->input('id'));

            $court_cases_interim_order = DB::select('select *, a.id as i_id from court_cases_interim_order a, court_cases_submitted_by b where court_case_id = ? AND a.action_to_be_taken_by = b.id order by a.id asc', [$c_id]);
            if( !empty($court_cases_interim_order)) {
                for($i=0;$i<sizeof($court_cases_interim_order);$i++) {
                    $temp_date = explode('-', $court_cases_interim_order[$i]->due_date_of_interim_order);
                    $court_cases_interim_order[$i]->due_date_of_interim_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                }

                $data = "";
                foreach($court_cases_interim_order as $li)
                {
                    $interim_id = Crypt::encrypt($li->i_id);
                    $data .= '<tr>
                            <td>'.$li->due_date_of_interim_order;
                            if( $li->status == "active" ) {
                                $data .= '<br /><span class="badge" style="background-color: green">In Effect</span>';
                            }
                    $data .= '</td>
                            <td>'.$li->submitted_by.'</td>
                            <td>'.$li->brief_of_interim_order.'</td>
                            <td>'.$li->details_action_taken_as_per_interim_order.'</td>
                            <td>
                                <a onClick="load_interim_update('.$li->i_id.');" data-toggle="modal" data-target="#interimupdateModal" class="btn aqua-gradient waves-effect btn-lg" style="font-weight:bold;font-size:10pt;padding:5px 5px;width:87px;"><i class="fa fa-edit"></i>&nbsp;&nbsp;Update</a>
                            </td>
                        </tr>';
                }
            }
            return $data;
        }
    }
    public function load_interim_order(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;

            $i_id = $request->input('id');

            $court_cases_submitted_by = DB::select('select * from court_cases_submitted_by order by id asc');

            $court_cases_interim_order = DB::select('select * from court_cases_interim_order where id = ? order by id desc', [$i_id]);
            $data = "";$options = '';

            if( !empty($court_cases_interim_order)) {
                $temp_date = explode('-', $court_cases_interim_order[0]->due_date_of_interim_order);
                $court_cases_interim_order[0]->due_date_of_interim_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];

                $court_cases_districts = DB::select('select district_id from court_cases where id = ? order by id desc', [$court_cases_interim_order[0]->court_case_id]);

                if( isset($court_cases_interim_order[0]->action_to_be_taken_by) ) {
                    if( $court_cases_interim_order[0]->action_to_be_taken_by == 6 ) {

                        $district_ids = json_decode($court_cases_districts[0]->district_id);
                        $size = sizeof($district_ids);
                        $interim_blocks = DB::select('select * from court_cases_blocks where section_id = 4 AND section_table_id = ?', [$i_id]);

                        $options = '';
                        $options = '<label>Blocks</label>
                                    <select id="interim_order_update_blocks" class="selectpicker form-control select-margin" name="blocks" data-style="btn-info" multiple autocomplete="off" required >';
                        for($i=0;$i<$size;$i++) {
                            $court_cases_district = DB::select('select * from districts where id = ? order by id asc', [$district_ids[$i]]);
                            $court_cases_blocks = DB::select('select * from blocks where district_id = ? order by block_name asc', [$district_ids[$i]]);

                            $options .= '<optgroup label="'.$court_cases_district[0]->district_name.'">';
                            foreach($court_cases_blocks as $values) {
                                $p = 0;
                                if(!empty($interim_blocks)) {
                                    foreach($interim_blocks as $blocks) {
                                        if( $values->id == $blocks->block_id ) {
                                            $options .= '<option value="'.Crypt::encrypt($values->id).'" selected >'.$values->block_name.'</option>';
                                            $p=1;break;
                                        }
                                        else {
                                            $p=0;
                                        }
                                    }
                                    if( $p == 0 )
                                        $options .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->block_name.'</option>';
                                }
                                else
                                    $options .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->block_name.'</option>';
                            }
                            $options .= '</optgroup>';
                        }
                        $options .= '</select>';
                    }
                    else {
                        $options .= '';
                    }
                }
                $data = '
                    <div class="form-group"> 
                        <div class="row">
                            <input type="hidden" name="interim_id" value="'.Crypt::encrypt($i_id).'" />
                            <div class="col-md-6">
                                <label for="exampleInputEmail1">Due Date of Compliance of Interim Order</label><br>
                                <input id="interim_update_date" name="due_date_of_interim_order" type="text" value="'.$court_cases_interim_order[0]->due_date_of_interim_order.'" class="form-control datepicker" data-zdp_readonly_element="true" required />
                            </div>
                        </div><br /><br />
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="exampleInputPassword1">Action to be taken by</label>
                                <select id="interim_update_submitted_by" class="selectpicker form-control select-margin court-case-select" name="action_to_be_taken_by" data-style="btn-info" required >
                                    <option value="'.Crypt::encrypt('').'">Select</option>';
                                    foreach($court_cases_submitted_by as $values)
                                    {
                                        if( isset($court_cases_interim_order[0]->action_to_be_taken_by) ) {
                                            if( $court_cases_interim_order[0]->action_to_be_taken_by == $values->id ) { 
                                                $data .= '<option value="'.Crypt::encrypt($values->id).'" selected >'.$values->submitted_by.'</option>';
                                            }
                                            else
                                                $data .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->submitted_by.'</option>';
                                        }
                                    }
                       $data .= '</select>
                                <style>
                                .bootstrap-select>.dropdown-toggle {
                                    top: -0.85px;
                                }
                                </style>
                            </div>
                            <div class="col-sm-6">
                                <div id="interim_update_submitted_by_blocks" class="select_districts_blocks">'.$options.'</div>
                            </div>
                        </div>
                    </div><br />
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1">Brief of Interim order if any</label>
                                <textarea class="form-control" name="brief_of_interim_order" rows="4" placeholder="Within 2000 Characters" required>'.$court_cases_interim_order[0]->brief_of_interim_order.'</textarea>
                            </div>
                        </div>
                    </div><br />
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1">Details of Action taken as per interim order</label>
                                <textarea class="form-control" name="details_action_taken_as_per_interim_order" rows="4" placeholder="Within 2000 Characters" required>'.$court_cases_interim_order[0]->details_action_taken_as_per_interim_order.'</textarea>
                            </div>
                        </div>
                    </div>
                    <script>
                        $("#interim_update_date").Zebra_DatePicker({
                            format: "d-m-Y"
                        });
                    </script>';

            }
            return $data;
        }
    }
    public function update_interim_order(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;

            $i_id = Crypt::decrypt($request->input('interim_id'));
            $due_date_of_interim_order = $request->input('due_date_of_interim_order');
            $action_to_be_taken_by = Crypt::decrypt($request->input('action_to_be_taken_by'));
            $brief_of_interim_order = $request->input('brief_of_interim_order');
            $details_action_taken_as_per_interim_order = $request->input('details_action_taken_as_per_interim_order');

            $query = DB::select('select court_case_id from court_cases_interim_order where id = ?', [$i_id]);

            $due_date_of_interim_order=Carbon::parse($due_date_of_interim_order)->format('Y-m-d');
            $current_datetime = now();
            $court_case = DB::update('UPDATE court_cases_interim_order SET due_date_of_interim_order = ?, action_to_be_taken_by = ?, brief_of_interim_order = ?, details_action_taken_as_per_interim_order = ?, updated_by = ?, updated_at = ? WHERE id = ?',
            [ $due_date_of_interim_order, $action_to_be_taken_by, $brief_of_interim_order, $details_action_taken_as_per_interim_order, Auth::user()->username, $current_datetime, $i_id]);
            
            if( $action_to_be_taken_by == 6 ) {
                $interim_blocks = $request->input('blocks');
                $interim_blocks = explode(',', $interim_blocks);
                $i = 0;
                foreach($interim_blocks as $values) {
                    $interim_blocks[$i] = Crypt::decrypt($values);
                    $i++;
                }
                $blocks = DB::delete('delete from court_cases_blocks where section_id = 4 AND section_table_id = ?', [$i_id]);

                $size = sizeof($interim_blocks);

                for($i=0;$i<$size;$i++) {
                    $blocks= DB::table('court_cases_blocks')
                    ->insert([
                        'section_id' => 4,
                        'section_table_id' => $i_id,
                        'block_id' => $interim_blocks[$i]
                    ]);
                }
            }
            if($court_case > 0){
                $returnData['msgType']="Interim";
                $returnData['info']="update";
                $returnData['msg']="Interim Order Details have been Updated... ";
            }
            

            // $messageUpdate= CourtCasesMessageTrackTable::update_message_sending("INTERIM ORDER", $query[0]->court_case_id, $due_date_of_interim_order, $action_to_be_taken_by, $i_id);

            // if($messageUpdate['msgType']==false){
            //     $returnData['msgType']=false;
            //     $returnData['msg']=$messageUpdate['msg'];
            //     return response()->json($returnData);
            // }

            // return json_encode($returnData);
            $messageUpdate= CourtCasesMessageTrackTable::update_message_sending("INTERIM ORDER", $query[0]->court_case_id, $due_date_of_interim_order, $action_to_be_taken_by, $i_id);

            if($messageUpdate['msgType']==false){
                $returnData['msg']= $returnData['msg'].$messageUpdate['msg'];
                return json_encode($returnData);
            }else{
                $returnData['msg']=$returnData['msg']. "Message successfully sent...";
            }

            return json_encode($returnData);
        }
    }

    //INSTRUCTION-----------------------------------------------------------------------------------------------------
    public function add_instruction(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;

            $c_id = Crypt::decrypt($request->input('court_case_id'));
            $due_date_of_instruction = $request->input('due_date_of_instruction');
            $instruction_submitted_by = Crypt::decrypt($request->input('instruction_submitted_by'));
            $instruction_letter_number = $request->input('instruction_letter_number');
            $instruction_document = $request->file('instruction_document');
            $instruction_submitted = $request->input('instruction_submitted');

            $date_val=Carbon::parse($due_date_of_instruction)->format('Y-m-d'); 

            $court_cases_instruction = DB::select('select * from court_cases_instruction a, court_cases_submitted_by b where a.court_case_id = ? AND a.instruction_submitted_by = b.id order by a.id desc', [$c_id]);

            if( sizeof($court_cases_instruction) >= 5 ) {
                $returnData['msgType']="Instruction";
                $returnData['info']="exceeded";
                $returnData['msg']="Instruction Cannot be Entered more than 5 times";
                return json_encode($returnData);
            }
            $file_path  = "";
            if(isset($_FILES["instruction_document"]["type"]))
            {
                if ( ($_FILES["instruction_document"]["type"] == "application/pdf") && ($_FILES["instruction_document"]["size"] < 2000000) )
                {
                    if ($_FILES['instruction_document']["error"] > 0) {
                        echo "Return Code: " . $_FILES['cv']["error"];
                        return;
                    }
                    else {
                        $file_path = $request->file('instruction_document')->store('courtCases/instruction');
                    }
                }
                else {
                    if( $_FILES["instruction_document"]["error"] == 4 ) {
                        $file_path = NULL;
                    }
                    else {
                        $returnData['msg'] = "*** CV File is not Valid or exceeds 2 MB ***";
                        return $returnData;
                    }
                }
            }

            $reset = DB::update('UPDATE court_cases_instruction SET status = "inactive" WHERE court_case_id = ?', [$c_id]);
            $current_datetime = now();

            $court_case_i= DB::table('court_cases_instruction')
                ->insertGetId([
                    'court_case_id' => $c_id,
                    'due_date_of_instruction' => $date_val,
                    'instruction_submitted_by' => $instruction_submitted_by,
                    'letter_number' => $instruction_letter_number,
                    'document' => $file_path,
                    'submitted' => $instruction_submitted,
                    'status' => "active",
                    'created_by'=> Auth::user()->username,
                    'created_at'=> $current_datetime
                ]);

            if( $instruction_submitted_by == 6 ) {
                $instruction_blocks = $request->input('blocks');
                $instruction_blocks = explode(',', $instruction_blocks);
                $i = 0;
                foreach($instruction_blocks as $values) {
                    $instruction_blocks[$i] = Crypt::decrypt($values);
                    $i++;
                }

                $size = sizeof($instruction_blocks);

                for($i=0;$i<$size;$i++) {
                    $blocks= DB::table('court_cases_blocks')
                    ->insert([
                        'section_id' => 5,
                        'section_table_id' => $court_case_i,
                        'block_id' => $instruction_blocks[$i]
                    ]);
                }
            }
            if($court_case_i) {
                $returnData['msgType']="Instruction";
                $returnData['info']="add";
                $returnData['msg']="Intstruction has been added... ";
            }

            $messageUpdate= CourtCasesMessageTrackTable::add_message_sending("INSTRUCTION", $c_id, $due_date_of_instruction, $instruction_submitted_by, $court_case_i);

            if($messageUpdate['msgType']==false) {
                $returnData['msg']= $returnData['msg'].$messageUpdate['msg'];
                return json_encode($returnData);
            } else {
                $returnData['msg']=$returnData['msg']. "Message successfully sent...";
            }
            // $messageUpdate = CourtCasesMessageTrackTable::add_message_sending("INTERIM ORDER", $c_id, $due_date_of_interim_order, $action_to_be_taken_by, $court_case->id);

            // if($messageUpdate['msgType']==false){
            //     $returnData['msgType']=false;
            //     $returnData['msg']=$messageUpdate['msg'];
            //     return json_encode($returnData);
            // }

            return json_encode($returnData);
        }
    }
    public function refresh_instruction(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;

            $c_id = Crypt::decrypt($request->input('id'));

            $court_cases_instruction = DB::select('select *, a.id as i_id from court_cases_instruction a, court_cases_submitted_by b where court_case_id = ? AND a.instruction_submitted_by = b.id order by a.id asc', [$c_id]);
            if( !empty($court_cases_instruction)) {
                for($i=0;$i<sizeof($court_cases_instruction);$i++) {
                    $temp_date = explode('-', $court_cases_instruction[$i]->due_date_of_instruction);
                    $court_cases_instruction[$i]->due_date_of_instruction = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                }

                $data = "";
                foreach($court_cases_instruction as $li)
                {
                    if( isset($li->document) != "" )
                        $button = '<a href="'.url("admin/courtCases/viewCourtCaseInstruction/".Crypt::encrypt($li->i_id)).'" target="_blank"  class="btn btn-outline-default waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:73px;margin:5px 0px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View</a>';
                    else
                        $button = "No Document";
                    $data .= '<tr>
                            <td>'.$li->due_date_of_instruction;
                            if( $li->status == "active" ) {
                                $data .= '<br /><span class="badge" style="background-color: green">In Effect</span>';
                            }
                    $data .= '</td>
                            <td>'.$li->submitted_by.'</td>
                            <td>'.$li->letter_number.'</td>
                            <td>'.$button.'</td>
                            <td>'.$li->submitted.'</td>
                            <td>
                                <a onClick="load_instruction_update('.$li->i_id.');" data-toggle="modal" data-target="#instructionupdateModal" class="btn aqua-gradient waves-effect btn-lg" style="font-weight:bold;font-size:10pt;padding:5px 5px;width:87px;"><i class="fa fa-edit"></i>&nbsp;&nbsp;Update</a>
                            </td>
                        </tr>';
                }
            }
            return $data;
        }
    }
    public function load_instruction(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;

            $i_id = $request->input('id');

            $court_cases_submitted_by = DB::select('select * from court_cases_submitted_by order by id asc');

            $court_cases_instruction = DB::select('select * from court_cases_instruction where id = ? order by id desc', [$i_id]);
            $button = ''; $data = "";$options = '';

            if( !empty($court_cases_instruction)) {
                $temp_date = explode('-', $court_cases_instruction[0]->due_date_of_instruction);
                $court_cases_instruction[0]->due_date_of_instruction = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];

                $court_cases_districts = DB::select('select district_id from court_cases where id = ? order by id desc', [$court_cases_instruction[0]->court_case_id]);

                if( isset($court_cases_instruction[0]->instruction_submitted_by) ) {
                    if( $court_cases_instruction[0]->instruction_submitted_by == 6 ) {

                        $district_ids = json_decode($court_cases_districts[0]->district_id);
                        $size = sizeof($district_ids);
                        $instruction_blocks = DB::select('select * from court_cases_blocks where section_id = 5 AND section_table_id = ?', [$i_id]);

                        $options = '<label>Blocks</label>
                                    <select id="parawise_comments_blocks" class="selectpicker form-control select-margin parawise_blocks" name="blocks" data-style="btn-info" multiple autocomplete="off" required >';
                        for($i=0;$i<$size;$i++) {
                            $court_cases_district = DB::select('select * from districts where id = ? order by id asc', [$district_ids[$i]]);
                            $court_cases_blocks = DB::select('select * from blocks where district_id = ? order by block_name asc', [$district_ids[$i]]);

                            $options .= '<optgroup label="'.$court_cases_district[0]->district_name.'">';
                            foreach($court_cases_blocks as $values) {
                                $p = 0;
                                if(!empty($instruction_blocks)) {
                                    foreach($instruction_blocks as $blocks) {
                                        if( $values->id == $blocks->block_id ) {
                                            $options .= '<option value="'.Crypt::encrypt($values->id).'" selected >'.$values->block_name.'</option>';
                                            $p=1;break;
                                        }
                                        else {
                                            $p=0;
                                        }
                                    }
                                    if( $p == 0 )
                                        $options .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->block_name.'</option>';
                                }
                                else
                                    $options .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->block_name.'</option>';
                            }
                            $options .= '</optgroup>';
                        }
                        $options .= '</select>';
                    }
                    else {
                        $options .= '';
                    }
                }
                if( isset($court_cases_instruction[0]->document) ) {
                    if( $court_cases_instruction[0]->document != "" && $court_cases_instruction[0]->document != NULL )
                    {
                        $button = '<a href="'.url("admin/courtCases/viewCourtCaseInstruction/".Crypt::encrypt($court_cases_instruction[0]->id)).'" target="_blank"  class="btn btn-outline-default waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:145px;margin:5px 0px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View Document</a>';
                    }
                }

                $yesChecked = ""; $noChecked = "";
                if( isset($court_cases_instruction[0]->submitted) ) {
                    if( $court_cases_instruction[0]->submitted == 'Yes' )
                        $yesChecked = "Checked";
                    else
                        $noChecked = "Checked";
                }

                $data = '
                    <div class="form-group"> 
                        <div class="row">
                            <input type="hidden" name="instruction_id" value="'.Crypt::encrypt($i_id).'" />
                            <div class="col-sm-6">
                                <label for="exampleInputPassword1">Instruction to be Submitted By</label>
                                <select id="instruction_update_submitted_by" class="selectpicker form-control select-margin court-case-select" name="instruction_submitted_by" data-style="btn-info" required >
                                    <option value="'.Crypt::encrypt('').'">Select</option>';
                                    foreach($court_cases_submitted_by as $values)
                                    {
                                        if( isset($court_cases_instruction[0]->instruction_submitted_by) ) {
                                            if( $court_cases_instruction[0]->instruction_submitted_by == $values->id ) { 
                                                $data .= '<option value="'.Crypt::encrypt($values->id).'" selected >'.$values->submitted_by.'</option>';
                                            }
                                            else
                                                $data .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->submitted_by.'</option>';
                                        }
                                    }
                       $data .= '</select>
                            </div>
                            <div class="col-sm-6">
                                <div id="instruction_update_submitted_by_blocks" class="select_districts_blocks">'.$options.'</div>
                            </div>
                        </div><br /><br />
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1">Due Date of Submission of Instruction</label><br>
                                <input id="instruction_update_date" name="due_date_of_instruction" type="text" value="'.$court_cases_instruction[0]->due_date_of_instruction.'" class="form-control datepicker" data-zdp_readonly_element="true" required />
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1">Letter Number </label>
                                <input name="instruction_letter_number" type="text" value="'.$court_cases_instruction[0]->letter_number.'" class="form-control" >
                            </div>
                        </div><br /><br />
                        <div class="row">
                            <div class="col-md-6">
                                <label>Upload Document</label>
                                <input type="file" name="instruction_document" id="instructionDocument" value="" class="form-control" >
                                <div id="upload_instruction">'.$button.'</div>
                                <b>.pdf files only (should be less than 2 MB)</b>
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1">Submitted</label><br />
                                <!-- ANIMATED CHECKBOXES -->
                                <div class="checkbox-animated col-sm-6" style="width:155px;">
                                    <input id="checkbox_11" type="radio" class="checkbox" name="instruction_submitted" value="Yes" '.$yesChecked.' />
                                    <label for="checkbox_11">
                                        <span class="check"></span>
                                        <span class="box"></span>
                                        YES 
                                    </label>
                                </div>
                                <div class="checkbox-animated col-sm-6" style="width:155px;">
                                    <input id="checkbox_21" type="radio" class="checkbox" name="instruction_submitted" value="No" '.$noChecked.' />
                                    <label for="checkbox_21">
                                        <span class="check"></span>
                                        <span class="box"></span>
                                        NO
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        $("#instruction_update_date").Zebra_DatePicker({
                            format: "d-m-Y"
                        });
                    </script>';

            }
            return $data;
        }
    }
    public function update_instruction(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;

            $i_id = Crypt::decrypt($request->input('instruction_id'));
            $due_date_of_instruction = $request->input('due_date_of_instruction');
            $instruction_submitted_by = Crypt::decrypt($request->input('instruction_submitted_by'));
            $instruction_letter_number = $request->input('instruction_letter_number');
            $instruction_document = $request->file('instruction_document');
            $instruction_submitted = $request->input('instruction_submitted');

            $query = DB::select('select court_case_id from court_cases_instruction where id = ?', [$i_id]);
            $due_date_of_instruction=Carbon::parse($due_date_of_instruction)->format('Y-m-d');
            $current_datetime = now();
            $p =  0;
            if(isset($_FILES["instruction_document"]["type"]))
            {
                if ( ($_FILES["instruction_document"]["type"] == "application/pdf") && ($_FILES["instruction_document"]["size"] < 2000000) )
                {
                    if ($_FILES['instruction_document']["error"] > 0) {
                        echo "Return Code: " . $_FILES['cv']["error"];return;
                    }
                    else {
                        if(isset($query[0]->document))
                        {
                            if( $query[0]->document != NULL || $query[0]->document != "" ) {
                                $file = fopen(storage_path('app/'.$query[0]->document), 'w') or die("can't open file");
                                fclose($file);
                                unlink(storage_path('app/'.$query[0]->document));
                            }
                        }
                        $file_path = $request->file('instruction_document')->store('courtCases/instruction');
                    }
                }
                else if( $_FILES["instruction_document"]["name"] == "" ) {
                    $p = 1;
                }
                else {
                    $returnData['msg'] = "*** CV File is not Valid or exceeds 2 MB ***";
                    return $returnData;
                }

                if( $p == 1) {
                    $court_case = DB::update('UPDATE court_cases_instruction SET due_date_of_instruction = ?, instruction_submitted_by = ?, letter_number = ?, submitted = ?, updated_by = ?, updated_at = ? WHERE id = ?',
                    [ $due_date_of_instruction, $instruction_submitted_by, $instruction_letter_number, $instruction_submitted, Auth::user()->username, $current_datetime, $i_id]);
                }
                else {
                    $court_case = DB::update('UPDATE court_cases_instruction SET due_date_of_instruction = ?, instruction_submitted_by = ?, letter_number = ?, document = ?, submitted = ?, updated_by = ?, updated_at = ? WHERE id = ?',
                    [ $due_date_of_instruction, $instruction_submitted_by, $instruction_letter_number, $file_path, $instruction_submitted, Auth::user()->username, $current_datetime, $i_id]);
                }
            }
            
            if( $instruction_submitted_by == 6 ) {
                $instruction_blocks = $request->input('blocks');
                $instruction_blocks = explode(',', $instruction_blocks);
                $i = 0;
                foreach($instruction_blocks as $values) {
                    $instruction_blocks[$i] = Crypt::decrypt($values);
                    $i++;
                }
                $instruction_delete_blocks = DB::delete('delete from court_cases_blocks where section_id = 5 AND section_table_id = ?', [$i_id]);

                $size = sizeof($instruction_blocks);

                for($i=0;$i<$size;$i++) {
                    $blocks= DB::table('court_cases_blocks')
                    ->insert([
                        'section_id' => 5,
                        'section_table_id' => $i_id,
                        'block_id' => $instruction_blocks[$i]
                    ]);
                }
            }

            if($court_case > 0){
                $returnData['msgType']="Instruction";
                $returnData['info']="update";
                $returnData['msg']="Instruction Details has been Updated... ";
            }

            $messageUpdate= CourtCasesMessageTrackTable::update_message_sending("INSTRUCTION", $query[0]->court_case_id, $due_date_of_instruction, $instruction_submitted_by, $i_id);

            if($messageUpdate['msgType']==false){
                $returnData['msg']= $returnData['msg'].$messageUpdate['msg'];
                return json_encode($returnData);
            }else{
                $returnData['msg']=$returnData['msg']. "Message successfully sent...";
            }

            // $messageUpdate= CourtCasesMessageTrackTable::update_message_sending("INTERIM ORDER", $query[0]->court_case_id, $due_date_of_interim_order, $action_to_be_taken_by, $i_id);

            // if($messageUpdate['msgType']==false){
            //     $returnData['msgType']=false;
            //     $returnData['msg']=$messageUpdate['msg'];
            //     return response()->json($returnData);
            // }

            return json_encode($returnData);
        }
    }
    public function view_court_case_instruction($id) {
        $id = Crypt::decrypt($id);
        $document = DB::select('Select document from court_cases_instruction where id=?', [$id]);
        //return Response::download(storage_path('app/'.$document[0]->document));
        return response()->file(storage_path('app/'.$document[0]->document));
    }

    //FINAL ORDER-------------------------------------------------------------------------------------------------------
    public function add_court_case_final_order(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;

            $c_id = Crypt::decrypt($request->input('court_case_id'));
            $action_to_be_taken_by = Crypt::decrypt($request->input('action_to_be_taken_by'));
            $due_date_of_final_order = $request->input('due_date_of_final_order');
            $date_of_receipt_of_final_order = $request->input('date_of_receipt_of_final_order');
            $details_of_final_order = $request->input('details_of_final_order');
            $details_of_action_taken_as_per_financial_order = $request->input('details_of_action_taken_as_per_financial_order');
            $final_order_option = $request->input('final_order_option');
            $details_of_appeal_petition = $request->input('details_of_appeal_petition');

            $due_date_of_final_order=Carbon::parse($due_date_of_final_order)->format('Y-m-d');
            $date_of_receipt_of_final_order=Carbon::parse($date_of_receipt_of_final_order)->format('Y-m-d');

            $query = DB::select('select * from court_cases_final_order where court_case_id = ? order by id asc', [$c_id]);

            $add_update = 0;
            if( !empty($query) ) {
                $current_datetime = now();
                $court_case = DB::update('UPDATE court_cases_final_order SET action_to_be_taken_by = ?, due_date_of_final_order = ?, date_of_receipt_of_final_order = ?, details_of_final_order =?, details_of_action_taken_as_per_financial_order = ?, details_of_appeal_petition = ?, updated_by = ?, updated_at = ?  WHERE court_case_id = ?',
                    [ $action_to_be_taken_by, $due_date_of_final_order, $date_of_receipt_of_final_order, $details_of_final_order, $details_of_action_taken_as_per_financial_order, $details_of_appeal_petition, Auth::user()->username, $current_datetime, $c_id]);

                if($court_case > 0){
                    $returnData['msgType']="Updated";
                    $returnData['msg']="Successfully Updated the details of Instruction... ";
                }
                $court_case_f = $query[0]->id;

                $add_update = 1;
            }
            else {
                $current_datetime = now();

                $court_case= DB::table('court_cases_final_order')
                    ->insertGetId([
                        'court_case_id' => $c_id,
                        'due_date_of_final_order' => $due_date_of_final_order,
                        'action_to_be_taken_by' => $action_to_be_taken_by,
                        'date_of_receipt_of_final_order' => $date_of_receipt_of_final_order,
                        'details_of_final_order' => $details_of_final_order,
                        'details_of_action_taken_as_per_financial_order' => $details_of_action_taken_as_per_financial_order,
                        'details_of_appeal_petition' => $details_of_appeal_petition,
                        'created_by'=> Auth::user()->username,
                        'created_at'=> $current_datetime
                    ]);

                if($court_case){
                    $returnData['msgType']=true;
                    $returnData['msg']="Instruction Details have been added... ";
                }
                $court_case_f = $court_case;

                $add_update = 0;
            }
            if( $action_to_be_taken_by == 6 ) {
                $speaking_order_blocks = $request->input('blocks');
                $speaking_order_blocks = explode(',', $speaking_order_blocks);
                $i = 0;
                foreach($speaking_order_blocks as $values) {
                    $speaking_order_blocks[$i] = Crypt::decrypt($values);
                    $i++;
                }
                $delete_blocks = DB::delete('delete from court_cases_blocks where section_id = 6 AND section_table_id = ?', [$court_case_f]);

                $size = sizeof($speaking_order_blocks);

                for($i=0;$i<$size;$i++) {
                    $blocks= DB::table('court_cases_blocks')
                    ->insert([
                        'section_id' => 6,
                        'section_table_id' => $court_case_f,
                        'block_id' => $speaking_order_blocks[$i]
                    ]);
                }
            }

            if ( $add_update == 0 )
                $messageUpdate= CourtCasesMessageTrackTable::add_message_sending("FINAL ORDER", $c_id, $due_date_of_final_order, $action_to_be_taken_by, $court_case_f);
            else if ( $add_update == 1 )
                $messageUpdate= CourtCasesMessageTrackTable::update_message_sending("FINAL ORDER", $c_id, $due_date_of_final_order, $action_to_be_taken_by, $court_case_f);

            if($messageUpdate['msgType']==false) {
                $returnData['msg']= $returnData['msg'].$messageUpdate['msg'];
                return json_encode($returnData);
            } else {
                $returnData['msg']=$returnData['msg']. "Message successfully sent...";
            }

            return json_encode($returnData);
        }
    }

    //SPEAKING ORDER----------------------------------------------------------------------------------------------------
    public function add_speaking_order(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;

            $c_id = Crypt::decrypt($request->input('court_case_id'));
            $date_of_speaking_order = $request->input('date_of_speaking_order');
            $due_date_of_speaking_order = $request->input('due_date_of_speaking_order');
            $speaking_order_submitted_by = Crypt::decrypt($request->input('speaking_order_submitted_by'));
            $speaking_order_letter_number = $request->input('speaking_order_letter_number');
            $speaking_order_document = $request->file('speaking_order_document');
            $speaking_order_submitted = $request->input('speaking_order_submitted');


            $date_of_speaking_order=Carbon::parse($date_of_speaking_order)->format('Y-m-d');  
            $due_date=Carbon::parse($date_of_speaking_order)->subDay($due_date_of_speaking_order)->format('Y-m-d');

            $court_cases_speaking_order = DB::select('select * from court_cases_speaking_order a, court_cases_submitted_by b where a.court_case_id = ? AND a.speaking_order_passed_by = b.id order by a.id desc', [$c_id]);

            if( sizeof($court_cases_speaking_order) >= 5 ) {
                $returnData['msgType']="Speaking";
                $returnData['info']="exceeded";
                $returnData['msg']="Speaking Order Details Cannot be Entered more than 5 times";
                return json_encode($returnData);
            }
            $file_path  = "";
            if(isset($_FILES["speaking_order_document"]["type"]))
            {
                if ( ($_FILES["speaking_order_document"]["type"] == "application/pdf") && ($_FILES["speaking_order_document"]["size"] < 2000000) )
                {
                    if ($_FILES['speaking_order_document']["error"] > 0) {
                        echo "Return Code: " . $_FILES['cv']["error"];
                        return;
                    }
                    else {
                        $file_path = $request->file('speaking_order_document')->store('courtCases/speakingOrder');
                    }
                }
                else {
                    if( $_FILES["speaking_order_document"]["error"] == 4 ) {
                        $file_path = NULL;
                    }
                    else {
                        $returnData['msg'] = "*** CV File is not Valid or exceeds 2 MB ***";
                        return $returnData;
                    }
                }
            }

            $reset = DB::update('UPDATE court_cases_speaking_order SET status = "inactive" WHERE court_case_id = ?', [$c_id]);
            $current_datetime = now();

            $court_case= DB::table('court_cases_speaking_order')
                ->insertGetId([
                    'court_case_id' => $c_id,
                    'date_of_speaking_order' => $date_of_speaking_order,
                    'due_date_of_speaking_order' => $due_date_of_speaking_order,
                    'speaking_order_passed_by' => $speaking_order_submitted_by,
                    'letter_number' => $speaking_order_letter_number,
                    'document' => $file_path,
                    'submitted' => $speaking_order_submitted,
                    'status' => "active",
                    'created_by'=> Auth::user()->username,
                    'created_at'=> $current_datetime
                ]);

            if( $speaking_order_submitted_by == 6 ) {
                $speaking_order_blocks = $request->input('blocks');
                $speaking_order_blocks = explode(',', $speaking_order_blocks);
                $i = 0;
                foreach($speaking_order_blocks as $values) {
                    $speaking_order_blocks[$i] = Crypt::decrypt($values);
                    $i++;
                }

                $size = sizeof($speaking_order_blocks);

                for($i=0;$i<$size;$i++) {
                    $blocks= DB::table('court_cases_blocks')
                    ->insert([
                        'section_id' => 7,
                        'section_table_id' => $court_case,
                        'block_id' => $speaking_order_blocks[$i]
                    ]);
                }
            }

            if($court_case) {
                $returnData['msgType']="Speaking";
                $returnData['info']="add";
                $returnData['msg']="Speaking Order Details has been added... ";
            }

            $messageUpdate= CourtCasesMessageTrackTable::add_message_sending("SPEAKING ORDER", $c_id, $due_date, $speaking_order_submitted_by, $court_case);

            if($messageUpdate['msgType']==false) {
                $returnData['msg']= $returnData['msg'].$messageUpdate['msg'];
                return json_encode($returnData);
            } else {
                $returnData['msg']=$returnData['msg']. "Message successfully sent...";
            }
            // $messageUpdate= CourtCasesMessageTrackTable::add_message_sending("SPEAKING ORDER", $c_id, $due_date, $speaking_order_passed_by, $court_case->id);

            // if($messageUpdate['msgType']==false){
            //     $returnData['msgType']=false;
            //     $returnData['msg']=$messageUpdate['msg'];
            //     return json_encode($returnData);
            // }

            return json_encode($returnData);
        }
    }
    public function refresh_speaking_order(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;

            $c_id = Crypt::decrypt($request->input('id'));

            $court_cases_speaking_order = DB::select('select *, a.id as i_id from court_cases_speaking_order a, court_cases_submitted_by b where court_case_id = ? AND a.speaking_order_passed_by = b.id order by a.id asc', [$c_id]);
            if( !empty($court_cases_speaking_order)) {
                for($i=0;$i<sizeof($court_cases_speaking_order);$i++) {
                    $temp_date = explode('-', $court_cases_speaking_order[$i]->date_of_speaking_order);
                    $court_cases_speaking_order[$i]->date_of_speaking_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                }

                $data = "";
                foreach($court_cases_speaking_order as $li)
                {
                    if( isset($li->document) != "" )
                        $button = '<a href="'.url("admin/courtCases/viewCourtCaseSpeakingOrder/".Crypt::encrypt($li->i_id)).'" target="_blank"  class="btn btn-outline-default waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:73px;margin:5px 0px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View</a>';
                    else
                        $button = "No Document";
                    $data .= '<tr>
                            <td>'.$li->date_of_speaking_order;
                            if( $li->status == "active" ) {
                                $data .= '<br /><span class="badge" style="background-color: green">In Effect</span>';
                            }
                    $data .= '</td>
                            <td>'.$li->due_date_of_speaking_order.' Days Prior</td>
                            <td>'.$li->submitted_by.'</td>
                            <td>'.$li->letter_number.'</td>
                            <td>'.$button.'</td>
                            <td>'.$li->submitted.'</td>
                            <td>
                                <a onClick="load_speaking_order_update('.$li->i_id.');" data-toggle="modal" data-target="#speakingorderupdateModal" class="btn aqua-gradient waves-effect btn-lg" style="font-weight:bold;font-size:10pt;padding:5px 5px;width:87px;"><i class="fa fa-edit"></i>&nbsp;&nbsp;Update</a>
                            </td>
                        </tr>';
                }
            }
            return $data;
        }
    }
    public function load_speaking_order(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;

            $i_id = $request->input('id');

            $court_cases_submitted_by = DB::select('select * from court_cases_submitted_by order by id asc');

            $court_cases_speaking_order = DB::select('select * from court_cases_speaking_order where id = ? order by id desc', [$i_id]);
            $button = ''; $data = ""; $options = '';

            if( !empty($court_cases_speaking_order)) {
                $temp_date = explode('-', $court_cases_speaking_order[0]->date_of_speaking_order);
                $court_cases_speaking_order[0]->date_of_speaking_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];

                $court_cases_districts = DB::select('select district_id from court_cases where id = ? order by id desc', [$court_cases_speaking_order[0]->court_case_id]);

                if( isset($court_cases_speaking_order[0]->speaking_order_passed_by) ) {
                    if( $court_cases_speaking_order[0]->speaking_order_passed_by == 6 ) {

                        $district_ids = json_decode($court_cases_districts[0]->district_id);
                        $size = sizeof($district_ids);
                        $speaking_order_blocks = DB::select('select * from court_cases_blocks where section_id = 7 AND section_table_id = ?', [$i_id]);

                        $options = '<label>Blocks</label>
                                    <select id="parawise_comments_blocks" class="selectpicker form-control select-margin parawise_blocks" name="blocks" data-style="btn-info" multiple autocomplete="off" required >';
                        for($i=0;$i<$size;$i++) {
                            $court_cases_district = DB::select('select * from districts where id = ? order by id asc', [$district_ids[$i]]);
                            $court_cases_blocks = DB::select('select * from blocks where district_id = ? order by block_name asc', [$district_ids[$i]]);

                            $options .= '<optgroup label="'.$court_cases_district[0]->district_name.'">';
                            foreach($court_cases_blocks as $values) {
                                $p = 0;
                                if(!empty($speaking_order_blocks)) {
                                    foreach($speaking_order_blocks as $blocks) {
                                        if( $values->id == $blocks->block_id ) {
                                            $options .= '<option value="'.Crypt::encrypt($values->id).'" selected >'.$values->block_name.'</option>';
                                            $p=1;break;
                                        }
                                        else {
                                            $p=0;
                                        }
                                    }
                                    if( $p == 0 )
                                        $options .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->block_name.'</option>';
                                }
                                else
                                    $options .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->block_name.'</option>';
                            }
                            $options .= '</optgroup>';
                        }
                        $options .= '</select>';
                    }
                    else {
                        $options .= '';
                    }
                }
                if( isset($court_cases_speaking_order[0]->document)  ) {
                    if( $court_cases_speaking_order[0]->document != "" && $court_cases_speaking_order[0]->document != NULL ) {
                        $button = '<a href="'.url("admin/courtCases/viewCourtCaseSpeakingOrder/".Crypt::encrypt($court_cases_speaking_order[0]->id)).'" target="_blank"  class="btn btn-outline-default waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:145px;margin:5px 0px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View Document</a>';
                    }
                }

                $yesChecked = ""; $noChecked = "";
                if( isset($court_cases_speaking_order[0]->submitted) ) {
                    if( $court_cases_speaking_order[0]->submitted == 'Yes' )
                        $yesChecked = "Checked";
                    else
                        $noChecked = "Checked";
                }

                if( isset($court_cases_speaking_order[0]->due_date_of_speaking_order) ) {
                    $select_due = '
                            <select id="due_date_of_speaking_order" class="selectpicker form-control select-margin" name="due_date_of_speaking_order" data-style="btn-info" autocomplete="off" required >
                                <option value="">Select</option>
                                <option value="15"';
                                if( $court_cases_speaking_order[0]->due_date_of_speaking_order == 15 ) {
                                    $select_due .= 'selected';
                                }
                                $select_due .= '>15 Days</option>
                                <option value="30"';
                                if( $court_cases_speaking_order[0]->due_date_of_speaking_order == 30 ) {
                                    $select_due .= 'selected';
                                }
                                $select_due .= '>30 Days</option>
                                <option value="60"';
                                if( $court_cases_speaking_order[0]->due_date_of_speaking_order == 60 ) {
                                    $select_due .= 'selected';
                                }
                                $select_due .= '>60 Days</option>
                                <option value="90"';
                                if( $court_cases_speaking_order[0]->due_date_of_speaking_order == 90 ) {
                                    $select_due .= 'selected';
                                }
                                $select_due .= '>90 Days</option>
                            </select>';
                }
                else
                    $select_due = "";

                $data = '
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="exampleInputPassword1">Speaking Order To Be Submitted By</label>
                                <select id="speaking_order_update_submitted_by" class="selectpicker form-control select-margin court-case-select" name="speaking_order_submitted_by" data-style="btn-info" required >
                                    <option value="'.Crypt::encrypt('').'">Select</option>';
                                    foreach($court_cases_submitted_by as $values)
                                    {
                                        if( isset($court_cases_speaking_order[0]->speaking_order_passed_by) ) {
                                            if( $court_cases_speaking_order[0]->speaking_order_passed_by == $values->id ) { 
                                                $data .= '<option value="'.Crypt::encrypt($values->id).'" selected >'.$values->submitted_by.'</option>';
                                            }
                                            else
                                                $data .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->submitted_by.'</option>';
                                        }
                                    }
                    $data .=   '</select>
                            </div>
                            <div class="col-sm-6">
                                <div id="speaking_order_update_submitted_by_blocks" class="select_districts_blocks">'.$options.'</div>
                            </div>
                        </div><br /><br />
                        <div class="row">
                            <input type="hidden" name="speaking_order_id" value="'.Crypt::encrypt($i_id).'" />
                            <div class="col-md-6">
                                <label for="exampleInputEmail1">Date of Speaking Order to be Passed</label><br>
                                <input id="speaking_order_update_date" name="date_of_speaking_order" type="text" value="'.$court_cases_speaking_order[0]->date_of_speaking_order.'" class="form-control datepicker" data-zdp_readonly_element="true" required />
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1">Due Date of Issue of Speaking Order</label>'.$select_due.'
                            </div>
                        </div><br /><br />
                        <div class="row">
                            <div class="col-md-6">
                                <label>Letter Number</label>
                                <input name="speaking_order_letter_number" type="text" value="'.$court_cases_speaking_order[0]->letter_number.'" class="form-control" >
                            </div>
                            <div class="col-md-6">
                                <label>Upload Document</label>
                                <input type="file" name="speaking_order_document" id="instructionDocument" value="" class="form-control" >
                                <div id="upload_speaking_order">'.$button.'</div>
                                <b>.pdf files only (should be less than 2 MB)</b>
                            </div>
                        </div><br /><br />
                        <div class="row">
                            <div class="col-md-6">
                                <label>Submitted</label><br />
                                <!-- ANIMATED CHECKBOXES -->
                                <div class="checkbox-animated col-sm-6" style="width:155px;">
                                    <input id="checkbox_31" type="radio" class="checkbox" name="speaking_order_submitted" value="Yes" '.$yesChecked.' />
                                    <label for="checkbox_31">
                                        <span class="check"></span>
                                        <span class="box"></span>
                                        YES 
                                    </label>
                                </div>
                                <div class="checkbox-animated col-sm-6" style="width:155px;">
                                    <input id="checkbox_32" type="radio" class="checkbox" name="speaking_order_submitted" value="No" '.$noChecked.' />
                                    <label for="checkbox_32">
                                        <span class="check"></span>
                                        <span class="box"></span>
                                        NO
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        $("#speaking_order_update_date").Zebra_DatePicker({
                            format: "d-m-Y"
                        });
                    </script>';
            }
            return $data;
        }
    }
    public function update_speaking_order(Request $request) {
        if($request->ajax())
        {
            $returnData['msgType']=false;

            $s_id = Crypt::decrypt($request->input('speaking_order_id'));
            $date_of_speaking_order = $request->input('date_of_speaking_order');
            $due_date_of_speaking_order = $request->input('due_date_of_speaking_order');
            $speaking_order_submitted_by = Crypt::decrypt($request->input('speaking_order_submitted_by'));
            $speaking_order_letter_number = $request->input('speaking_order_letter_number');
            $speaking_order_document = $request->file('speaking_order_document');
            $speaking_order_submitted = $request->input('speaking_order_submitted');

            $query = DB::select('select court_case_id from court_cases_speaking_order where id = ?', [$s_id]);
            $date_of_speaking_order=Carbon::parse($date_of_speaking_order)->format('Y-m-d');
            $due_date=Carbon::parse($date_of_speaking_order)->subDay($due_date_of_speaking_order)->format('Y-m-d');

            $current_datetime = now();
            $p =  0;
            if(isset($_FILES["speaking_order_document"]["type"]))
            {
                if ( ($_FILES["speaking_order_document"]["type"] == "application/pdf") && ($_FILES["speaking_order_document"]["size"] < 2000000) )
                {
                    if ($_FILES['speaking_order_document']["error"] > 0) {
                        echo "Return Code: " . $_FILES['cv']["error"];return;
                    }
                    else {
                        if(isset($query[0]->document))
                        {
                            if( $query[0]->document != NULL || $query[0]->document != "") {
                                $file = fopen(storage_path('app/'.$query[0]->document), 'w') or die("can't open file");
                                fclose($file);
                                unlink(storage_path('app/'.$query[0]->document));
                            }
                        }
                        $file_path = $request->file('speaking_order_document')->store('courtCases/speakingOrder');
                    }
                }
                else if( $_FILES["speaking_order_document"]["name"] == "" ) {
                    $p = 1;
                }
                else {
                    $returnData['msg'] = "*** CV File is not Valid or exceeds 2 MB ***";
                    return $returnData;
                }

                if( $p == 1) {
                    $court_case = DB::update('UPDATE court_cases_speaking_order SET date_of_speaking_order = ?, due_date_of_speaking_order = ?, speaking_order_passed_by = ?, letter_number = ?, submitted = ?, updated_by = ?, updated_at = ? WHERE id = ?',
                    [ $date_of_speaking_order, $due_date_of_speaking_order, $speaking_order_submitted_by, $speaking_order_letter_number, $speaking_order_submitted, Auth::user()->username, $current_datetime, $s_id]);
                }
                else {
                    $court_case = DB::update('UPDATE court_cases_speaking_order SET date_of_speaking_order = ?, due_date_of_speaking_order = ?, speaking_order_passed_by = ?, letter_number = ?, document = ?, submitted = ?, updated_by = ?, updated_at = ? WHERE id = ?',
                    [ $date_of_speaking_order, $due_date_of_speaking_order, $speaking_order_submitted_by, $speaking_order_letter_number, $file_path, $speaking_order_submitted, Auth::user()->username, $current_datetime, $s_id]);
                }
            }
            
            if( $speaking_order_submitted_by == 6 ) {
                $speaking_order_blocks = $request->input('blocks');
                $speaking_order_blocks = explode(',', $speaking_order_blocks);
                $i = 0;
                foreach($speaking_order_blocks as $values) {
                    $speaking_order_blocks[$i] = Crypt::decrypt($values);
                    $i++;
                }
                $delete_blocks = DB::delete('delete from court_cases_blocks where section_id = 7 AND section_table_id = ?', [$s_id]);

                $size = sizeof($speaking_order_blocks);

                for($i=0;$i<$size;$i++) {
                    $blocks= DB::table('court_cases_blocks')
                    ->insert([
                        'section_id' => 7,
                        'section_table_id' => $s_id,
                        'block_id' => $speaking_order_blocks[$i]
                    ]);
                }
            }
            if($court_case > 0){
                $returnData['msgType']="Speaking";
                $returnData['info']="update";
                $returnData['msg']="Speaking Order Details has been Updated... ";
            }

            $messageUpdate= CourtCasesMessageTrackTable::update_message_sending("SPEAKING ORDER", $query[0]->court_case_id, $due_date, $speaking_order_submitted_by, $s_id);

            if($messageUpdate['msgType']==false) {
                $returnData['msg'] = $returnData['msg'].$messageUpdate['msg'];
                return json_encode($returnData);
            } else {
                $returnData['msg']=$returnData['msg']. "Message successfully sent...";
            }
            // $messageUpdate= CourtCasesMessageTrackTable::update_message_sending("INTERIM ORDER", $query[0]->court_case_id, $due_date_of_interim_order, $action_to_be_taken_by, $s_id);

            // if($messageUpdate['msgType']==false){
            //     $returnData['msgType']=false;
            //     $returnData['msg']=$messageUpdate['msg'];
            //     return response()->json($returnData);
            // }

            return json_encode($returnData);
        }
    }
    public function view_court_case_speaking_order($id) {
        $id = Crypt::decrypt($id);
        $document = DB::select('Select document from court_cases_speaking_order where id=?', [$id]);
        //return Response::download(storage_path('app/'.$document[0]->document));
        return response()->file(storage_path('app/'.$document[0]->document));
    }
}