<?php

namespace App\Http\Controllers\CourtCases;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Response;
use Carbon\Carbon;
use Crypt;
use DB;
use File;
use App\CommonModels\District;
class CourtCasesReportsController extends Controller
{
    public function __construct() {
        $this->middleware(['auth','district_courtcase']);
    }
    public function list_status_report(Request $request) {
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
            $court_cases[$i]->c_id = $court_cases[$i]->c_id;

            $temp_date = explode('-', $court_cases[$i]->date_of_receipt_of_wpc_notice);
            $court_cases[$i]->date_of_receipt_of_wpc_notice = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
        }

        $court_cases = array_values($court_cases);

        $court_cases_type = DB::select('select * from court_cases_type order by id asc');
        $court_cases_nature = DB::select('select * from court_cases_nature order by id asc');
        $court_cases_status = DB::select('select * from court_cases_status order by id asc');
        $court_cases_under = DB::select('select * from court_cases_under order by id asc');

        $court_cases_submitted_by = DB::select('select * from court_cases_submitted_by order by id asc');

        for($i=0;$i<sizeof($court_cases);$i++)
        {
            $temp_date = explode('-', $court_cases[$i]->date_of_receipt_of_wpc_notice);
            $court_cases[$i]->date_of_receipt_of_wpc_notice = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
        }

        return view('CourtCases.listStatusReport', compact('districts', 'court_cases', 'court_cases_type', 'court_cases_nature', 'court_cases_status', 'court_cases_under', 'court_cases_submitted_by'));
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

            $case_under = Crypt::decrypt($request->input('case_under'));
            $case_type = Crypt::decrypt($request->input('case_type'));
            $case_nature = Crypt::decrypt($request->input('nature_of_case'));
            $case_status = Crypt::decrypt($request->input('case_status'));

            $from=strtotime($from_receipt_of_wpc_notice);
            $to=strtotime($to_receipt_of_wpc_notice);
            if( $from_receipt_of_wpc_notice != "" && $to_receipt_of_wpc_notice != "")
            {
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
                // if( $district != "") {
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


                //Parawise Comments
                $court_cases_parawise_comments = DB::select('select *, b.id as c_id from court_cases_parawise_comments a, court_cases_submitted_by b where a.court_case_id = ?
                AND a.status = "active" AND a.parawise_comments_submitted_by = b.id order by due_date_of_parawise_comments asc', [$court_cases[$i]->c_id]);
                $parawise_comments = "";
                if( !empty($court_cases_parawise_comments)) {
                    $temp_date = explode('-', $court_cases_parawise_comments[0]->due_date_of_parawise_comments);
                    $court_cases_parawise_comments[0]->due_date_of_parawise_comments = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                    $parawise_comments .= "<u>Due Date:</u><br />".$court_cases_parawise_comments[0]->due_date_of_parawise_comments."<br />";
                    $parawise_comments .= "<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_parawise_comments[0]->submitted_by."<br /></div>";
                    if( $court_cases_parawise_comments[0]->submitted == "" ) {
                        $parawise_comments .= '<span class="badge" style="background-color: red">Pending</span>';
                    }
                    else if( $court_cases_parawise_comments[0]->submitted == "No" )
                        $parawise_comments .= '<span class="badge" style="background-color: grey">Not Submitted</span>';
                    else
                        $parawise_comments .= '<span class="badge" style="background-color: green">Submitted</span>';
                }
                else
                    $parawise_comments .= "N/A";
                $court_cases[$i]->parawise = $parawise_comments;


                //Affidavit
                $court_cases_additional_affidavit = DB::select('select *, b.id as c_id from court_cases_affidavit a, court_cases_submitted_by b where a.court_case_id = ?
                AND a.category="additional" AND a.affidavit_submitted_by = b.id order by date_of_affidavit_submitted asc', [$court_cases[$i]->c_id]);
                $Affidavit = "";
                if( !empty($court_cases_additional_affidavit)) {
                    $temp_date = explode('-', $court_cases_additional_affidavit[0]->date_of_affidavit_submitted);
                    $court_cases_additional_affidavit[0]->date_of_affidavit_submitted = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                    $Affidavit .= "ADDITIONAL AFFIDAVIT<br /><u>Due Date:</u><br />".$court_cases_additional_affidavit[0]->date_of_affidavit_submitted."<br />";
                    $Affidavit .= "<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_additional_affidavit[0]->submitted_by."<br /></div>";
                    if( $court_cases_additional_affidavit[0]->document == "" ) {
                        $Affidavit .= '<span class="badge" style="background-color: grey">Not Sweared</span>';
                    }
                    else
                        $Affidavit .= '<span class="badge" style="background-color: green">Sweared</span>';
                }
                else {
                    $court_cases_affidavit = DB::select('select *, b.id as c_id from court_cases_affidavit a, court_cases_submitted_by b where a.court_case_id = ?
                    AND a.category="primary" AND a.affidavit_submitted_by = b.id order by date_of_affidavit_submitted asc', [$court_cases[$i]->c_id]);

                    if( !empty($court_cases_affidavit)) {
                        $temp_date = explode('-', $court_cases_affidavit[0]->date_of_affidavit_submitted);
                        $court_cases_affidavit[0]->date_of_affidavit_submitted = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                        $Affidavit .= "AFFIDAVIT<br /><u>Due Date:</u><br />".$court_cases_affidavit[0]->date_of_affidavit_submitted."<br />";
                        $Affidavit .= "<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_affidavit[0]->submitted_by."<br /></div>";
                        if( $court_cases_affidavit[0]->document == "" ) {
                            $Affidavit .= '<span class="badge" style="background-color: grey">Not Sweared</span>';
                        }
                        else
                        $Affidavit .= '<span class="badge" style="background-color: green">Sweared</span>';
                    }
                    else
                        $Affidavit .= "N/A";
                }
                $court_cases[$i]->affidavit = $Affidavit;                                    


                //Interim Order
                $court_cases_interim_order = DB::select('select *, b.id as c_id from court_cases_interim_order a, court_cases_submitted_by b where a.court_case_id = ?
                AND a.status = "active" AND a.action_to_be_taken_by = b.id order by due_date_of_interim_order asc', [$court_cases[$i]->c_id]);
                $interim_order = "";
                if( !empty($court_cases_interim_order)) {
                    $temp_date = explode('-', $court_cases_interim_order[0]->due_date_of_interim_order);
                    $court_cases_interim_order[0]->due_date_of_interim_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                    $interim_order .= "<u>Due Date:</u><br />".$court_cases_interim_order[0]->due_date_of_interim_order."<br />";
                    $interim_order .= "<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_interim_order[0]->submitted_by."<br /></div>";
                    if( $court_cases_interim_order[0]->details_action_taken_as_per_interim_order == "" ) {
                        $interim_order .= '<span class="badge" style="background-color: grey">Not Submitted</span>';
                    }
                    else
                    $interim_order .= '<span class="badge" style="background-color: green">Submitted</span>';
                }
                else
                    $interim_order .= "N/A";
                $court_cases[$i]->interim_order = $interim_order;


                //Instruction
                $court_cases_instruction = DB::select('select *, b.id as c_id from court_cases_instruction a, court_cases_submitted_by b where a.court_case_id = ?
                AND a.status = "active" AND a.instruction_submitted_by = b.id order by due_date_of_instruction asc', [$court_cases[$i]->c_id]);
                $instruction = "";
                if( !empty($court_cases_instruction)) {
                    $temp_date = explode('-', $court_cases_instruction[0]->due_date_of_instruction);
                    $court_cases_instruction[0]->due_date_of_instruction = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                    $instruction .= "<u>Due Date:</u><br />".$court_cases_instruction[0]->due_date_of_instruction."<br />";
                    $instruction .= "<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_instruction[0]->submitted_by."<br /></div>";
                    if( $court_cases_instruction[0]->submitted == "" ) {
                        $instruction .= '<span class="badge" style="background-color: red">Pending</span>';
                    }
                    else if( $court_cases_instruction[0]->submitted == "No" )
                        $instruction .= '<span class="badge" style="background-color: grey">Not Submitted</span>';
                    else
                        $instruction .= '<span class="badge" style="background-color: green">Submitted</span>';
                }
                else
                    $instruction .= "N/A";
                $court_cases[$i]->instruction = $instruction;


                //Final Order
                $court_cases_final_order = DB::select('select *, b.id as c_id from court_cases_final_order a, court_cases_submitted_by b where a.court_case_id = ?
                AND a.action_to_be_taken_by = b.id order by due_date_of_final_order asc', [$court_cases[$i]->c_id]);
                $final_order = "";
                if( !empty($court_cases_final_order)) {
                    $temp_date = explode('-', $court_cases_final_order[0]->due_date_of_final_order);
                    $court_cases_final_order[0]->due_date_of_final_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                    $final_order .= "<u>Due Date:</u><br />".$court_cases_final_order[0]->due_date_of_final_order."<br />";
                    $final_order .="<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_final_order[0]->submitted_by."<br /></div>";
                    if( $court_cases_final_order[0]->details_of_action_taken_as_per_financial_order == "" ) {
                        $final_order .='<span class="badge" style="background-color: grey">Not Submitted</span>';
                    }
                    else
                    $final_order .='<span class="badge" style="background-color: green">Submitted</span>';
                }
                else
                    $final_order .="N/A";
                $court_cases[$i]->final_order = $final_order;

                
                //Speaking Order
                $speaking_temp = DB::select('select *, b.id as c_id from court_cases_speaking_order a, court_cases_submitted_by b where a.court_case_id = ?
                AND a.status = "active" AND a.speaking_order_passed_by = b.id', [$court_cases[$i]->c_id]);
                $speaking_order = "";
                if( !empty($speaking_temp)) {
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

                    // if( $temp >= $case_start && $temp <= $case_end ) {
                        $speaking_temp->due_date_of_speaking_order = $temp;
                        $temp_date = explode('-', $speaking_temp->due_date_of_speaking_order);
                        $speaking_temp->due_date_of_speaking_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                        $court_cases_speaking_order[0] = $speaking_temp;
                    // }

                    // $temp_date = explode('-', $court_cases_speaking_order[0]->due_date_of_instruction);
                    // $court_cases_speaking_order[0]->due_date_of_instruction = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                    $speaking_order .= "<u>Due Date:</u><br />".$court_cases_speaking_order[0]->due_date_of_speaking_order."<br />";
                    $speaking_order .= "<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_speaking_order[0]->submitted_by."<br /></div>";
                    if( $court_cases_speaking_order[0]->submitted == "" ) {
                        $speaking_order .= '<span class="badge" style="background-color: red">Pending</span>';
                    }
                    else if( $court_cases_speaking_order[0]->submitted == "No" )
                        $speaking_order .= '<span class="badge" style="background-color: grey">Not Submitted</span>';
                    else
                        $speaking_order .= '<span class="badge" style="background-color: green">Submitted</span>';
                }
                else
                    $speaking_order .= "N/A";
                $court_cases[$i]->speaking_order = $speaking_order;



                $court_cases[$i]->district_name = $return_districts;
                $court_cases[$i]->c_id = Crypt::encrypt($court_cases[$i]->c_id);

                $temp_date = explode('-', $court_cases[$i]->date_of_receipt_of_wpc_notice);
                $court_cases[$i]->date_of_receipt_of_wpc_notice = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
            }
            
            $court_cases = array_values($court_cases);
            
            $returnData['msgType']=true;
            $returnData['data']=$court_cases;
            $returnData['msg']="Successfully got the count cases";
            // var_dump($returnData);return;

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
            //Parawise Comments
            $court_cases_parawise_comments = DB::select('select *, b.id as c_id from court_cases_parawise_comments a, court_cases_submitted_by b where a.court_case_id = ?
            AND a.status = "active" AND a.parawise_comments_submitted_by = b.id order by due_date_of_parawise_comments asc', [$court_cases[$i]->c_id]);
            $parawise_comments = "";
            if( !empty($court_cases_parawise_comments)) {
                $temp_date = explode('-', $court_cases_parawise_comments[0]->due_date_of_parawise_comments);
                $court_cases_parawise_comments[0]->due_date_of_parawise_comments = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                $parawise_comments .= "<u>Due Date:</u><br />".$court_cases_parawise_comments[0]->due_date_of_parawise_comments."<br />";
                $parawise_comments .= "<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_parawise_comments[0]->submitted_by."<br /></div>";
                if( $court_cases_parawise_comments[0]->submitted == "" ) {
                    $parawise_comments .= '<span class="badge" style="background-color: red">Pending</span>';
                }
                else if( $court_cases_parawise_comments[0]->submitted == "No" )
                    $parawise_comments .= '<span class="badge" style="background-color: grey">Not Submitted</span>';
                else
                    $parawise_comments .= '<span class="badge" style="background-color: green">Submitted</span>';
            }
            else
                $parawise_comments .= "N/A";
            $court_cases[$i]->parawise = $parawise_comments;


            //Affidavit
            $court_cases_additional_affidavit = DB::select('select *, b.id as c_id from court_cases_affidavit a, court_cases_submitted_by b where a.court_case_id = ?
            AND a.category="additional" AND a.affidavit_submitted_by = b.id order by date_of_affidavit_submitted asc', [$court_cases[$i]->c_id]);
            $Affidavit = "";
            if( !empty($court_cases_additional_affidavit)) {
                $temp_date = explode('-', $court_cases_additional_affidavit[0]->date_of_affidavit_submitted);
                $court_cases_additional_affidavit[0]->date_of_affidavit_submitted = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                $Affidavit .= "ADDITIONAL AFFIDAVIT<br /><u>Due Date:</u><br />".$court_cases_additional_affidavit[0]->date_of_affidavit_submitted."<br />";
                $Affidavit .= "<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_additional_affidavit[0]->submitted_by."<br /></div>";
                if( $court_cases_additional_affidavit[0]->document == "" ) {
                    $Affidavit .= '<span class="badge" style="background-color: grey">Not Sweared</span>';
                }
                else
                    $Affidavit .= '<span class="badge" style="background-color: green">Sweared</span>';
            }
            else {
                $court_cases_affidavit = DB::select('select *, b.id as c_id from court_cases_affidavit a, court_cases_submitted_by b where a.court_case_id = ?
                AND a.category="primary" AND a.affidavit_submitted_by = b.id order by date_of_affidavit_submitted asc', [$court_cases[$i]->c_id]);

                if( !empty($court_cases_affidavit)) {
                    $temp_date = explode('-', $court_cases_affidavit[0]->date_of_affidavit_submitted);
                    $court_cases_affidavit[0]->date_of_affidavit_submitted = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                    $Affidavit .= "AFFIDAVIT<br /><u>Due Date:</u><br />".$court_cases_affidavit[0]->date_of_affidavit_submitted."<br />";
                    $Affidavit .= "<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_affidavit[0]->submitted_by."<br /></div>";
                    if( $court_cases_affidavit[0]->document == "" ) {
                        $Affidavit .= '<span class="badge" style="background-color: grey">Not Sweared</span>';
                    }
                    else
                    $Affidavit .= '<span class="badge" style="background-color: green">Sweared</span>';
                }
                else
                    $Affidavit .= "N/A";
            }
            $court_cases[$i]->affidavit = $Affidavit;                                    


            //Interim Order
            $court_cases_interim_order = DB::select('select *, b.id as c_id from court_cases_interim_order a, court_cases_submitted_by b where a.court_case_id = ?
            AND a.status = "active" AND a.action_to_be_taken_by = b.id order by due_date_of_interim_order asc', [$court_cases[$i]->c_id]);
            $interim_order = "";
            if( !empty($court_cases_interim_order)) {
                $temp_date = explode('-', $court_cases_interim_order[0]->due_date_of_interim_order);
                $court_cases_interim_order[0]->due_date_of_interim_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                $interim_order .= "<u>Due Date:</u><br />".$court_cases_interim_order[0]->due_date_of_interim_order."<br />";
                $interim_order .= "<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_interim_order[0]->submitted_by."<br /></div>";
                if( $court_cases_interim_order[0]->details_action_taken_as_per_interim_order == "" ) {
                    $interim_order .= '<span class="badge" style="background-color: grey">Not Submitted</span>';
                }
                else
                $interim_order .= '<span class="badge" style="background-color: green">Submitted</span>';
            }
            else
                $interim_order .= "N/A";
            $court_cases[$i]->interim_order = $interim_order;


            //Instruction
            $court_cases_instruction = DB::select('select *, b.id as c_id from court_cases_instruction a, court_cases_submitted_by b where a.court_case_id = ?
            AND a.status = "active" AND a.instruction_submitted_by = b.id order by due_date_of_instruction asc', [$court_cases[$i]->c_id]);
            $instruction = "";
            if( !empty($court_cases_instruction)) {
                $temp_date = explode('-', $court_cases_instruction[0]->due_date_of_instruction);
                $court_cases_instruction[0]->due_date_of_instruction = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                $instruction .= "<u>Due Date:</u><br />".$court_cases_instruction[0]->due_date_of_instruction."<br />";
                $instruction .= "<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_instruction[0]->submitted_by."<br /></div>";
                if( $court_cases_instruction[0]->submitted == "" ) {
                    $instruction .= '<span class="badge" style="background-color: red">Pending</span>';
                }
                else if( $court_cases_instruction[0]->submitted == "No" )
                    $instruction .= '<span class="badge" style="background-color: grey">Not Submitted</span>';
                else
                    $instruction .= '<span class="badge" style="background-color: green">Submitted</span>';
            }
            else
                $instruction .= "N/A";
            $court_cases[$i]->instruction = $instruction;


            //Final Order
            $court_cases_final_order = DB::select('select *, b.id as c_id from court_cases_final_order a, court_cases_submitted_by b where a.court_case_id = ?
            AND a.action_to_be_taken_by = b.id order by due_date_of_final_order asc', [$court_cases[$i]->c_id]);
            $final_order = "";
            if( !empty($court_cases_final_order)) {
                $temp_date = explode('-', $court_cases_final_order[0]->due_date_of_final_order);
                $court_cases_final_order[0]->due_date_of_final_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                $final_order .= "<u>Due Date:</u><br />".$court_cases_final_order[0]->due_date_of_final_order."<br />";
                $final_order .="<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_final_order[0]->submitted_by."<br /></div>";
                if( $court_cases_final_order[0]->details_of_action_taken_as_per_financial_order == "" ) {
                    $final_order .='<span class="badge" style="background-color: grey">Not Submitted</span>';
                }
                else
                    $final_order .='<span class="badge" style="background-color: green">Submitted</span>';
            }
            else
                $final_order .="N/A";
            $court_cases[$i]->final_order = $final_order;


            //Speaking Order
            $speaking_temp = DB::select('select *, b.id as c_id from court_cases_speaking_order a, court_cases_submitted_by b where a.court_case_id = ?
            AND a.status = "active" AND a.speaking_order_passed_by = b.id', [$court_cases[$i]->c_id]);
            $speaking_order = "";
            if( !empty($speaking_temp)) {
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

                // if( $temp >= $case_start && $temp <= $case_end ) {
                    $speaking_temp->due_date_of_speaking_order = $temp;
                    $temp_date = explode('-', $speaking_temp->due_date_of_speaking_order);
                    $speaking_temp->due_date_of_speaking_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                    $court_cases_speaking_order[0] = $speaking_temp;
                // }

                // $temp_date = explode('-', $court_cases_speaking_order[0]->due_date_of_instruction);
                // $court_cases_speaking_order[0]->due_date_of_instruction = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                $speaking_order .= "<u>Due Date:</u><br />".$court_cases_speaking_order[0]->due_date_of_speaking_order."<br />";
                $speaking_order .= "<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_speaking_order[0]->submitted_by."<br /></div>";
                if( $court_cases_speaking_order[0]->submitted == "" ) {
                    $speaking_order .= '<span class="badge" style="background-color:red">Pending</span>';
                }
                else if( $court_cases_speaking_order[0]->submitted == "No" )
                    $speaking_order .= '<span class="badge" style="background-color: grey">Not Submitted</span>';
                else
                    $speaking_order .= '<span class="badge" style="background-color: green">Submitted</span>';
            }
            else
                $speaking_order .= "N/A";
            $court_cases[$i]->speaking_order = $speaking_order;
                
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
    // public function excel_status_report(Request $request) {
    //     $districts = District::select('id','district_name')->get();
    //     $court_cases = DB::select('select *, a.id as case_id  from court_cases a, court_cases_type b, court_cases_status c, court_cases_nature d, court_cases_under e where a.case_type_id = b.id AND a.case_status_id = c.id AND a.nature_of_case = d.id AND a.case_under = e.id order by a.id desc');
    //     $court_cases_type = DB::select('select * from court_cases_type order by id asc');
    //     $court_cases_nature = DB::select('select * from court_cases_nature order by id asc');
    //     $court_cases_status = DB::select('select * from court_cases_status order by id asc');
    //     $court_cases_under = DB::select('select * from court_cases_under order by id asc');

    //     $court_cases_submitted_by = DB::select('select * from court_cases_submitted_by order by id asc');

    //     for($i=0;$i<sizeof($court_cases);$i++)
    //     {
    //         $temp_date = explode('-', $court_cases[$i]->date_of_receipt_of_wpc_notice);
    //         $court_cases[$i]->date_of_receipt_of_wpc_notice = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
    //     }

    //     return view('admin.CourtCases.excelStatusReport', compact('districts', 'court_cases', 'court_cases_type', 'court_cases_nature', 'court_cases_status', 'court_cases_under', 'court_cases_submitted_by',
    //     'court_cases_parawise_comments', 'court_cases_instruction', 'court_cases_interim_order', 'court_cases_final_order', 'court_cases_speaking_order'));
    // }
    public function status_excel_court_case(Request $request) {
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
            $db_query = "select *, a.id as case_id, a.district_id as district_name from court_cases a, court_cases_type b, court_cases_nature c, court_cases_status d, court_cases_under e
                         where ".$query." a.case_type_id = b.id AND a.nature_of_case = c.id AND a.case_status_id = d.id AND a.case_under = e.id order by a.id desc";
            $court_cases = "";
            $court_cases = DB::select( $db_query, $array_items);

            $all_districts = District::select('id','district_name')->get();
            $case_size = sizeof($court_cases);
            for($i=0;$i<$case_size;$i++)
            {
                $district_ids = json_decode($court_cases[$i]->district_id);
                $return_districts = "";
                // if( $district != "") {
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
            //$court_cases[$i]->case_id = Crypt::encrypt($court_cases[$i]->case_id);

                $temp_date = explode('-', $court_cases[$i]->date_of_receipt_of_wpc_notice);
                $court_cases[$i]->date_of_receipt_of_wpc_notice = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
            }

            $court_cases = array_values($court_cases);

            return view('CourtCases.excelStatusReport', compact('districts', 'court_cases', 'court_cases_type', 'court_cases_nature', 'court_cases_status', 'court_cases_under', 'court_cases_submitted_by'));

    }
    public function status_excel_court_case_byno(Request $request) {
        $returnData['msgType']=false;
        $returnData['data']=[];
        $returnData['msg']="Opps! Something went wrong";

        $case_number = $request->input('case_number');

        $court_cases = DB::table('court_cases')
            ->select('*', 'court_cases.id as case_id')
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
            //$court_cases[$i]->case_id = Crypt::encrypt($court_cases[$i]->case_id);

            $temp_date = explode('-', $court_cases[$i]->date_of_receipt_of_wpc_notice);
            $court_cases[$i]->date_of_receipt_of_wpc_notice = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
        }

        $court_cases = json_encode($court_cases);
        $court_cases = json_decode($court_cases);
        $court_cases = (array)$court_cases;
        $court_cases = array_values($court_cases);

        return view('CourtCases.excelStatusReport', compact('districts', 'court_cases', 'court_cases_type', 'court_cases_nature', 'court_cases_status', 'court_cases_under', 'court_cases_submitted_by'));

    }
}
