<?php
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename='.rand().'.xls');
?>
@php
    $page_title="dashboard";
@endphp

@extends('admin.CourtCases.layouts.excelframe')

@section('content')
    {{-----------------------DATA TABLE-----------------------------------------}}
    <div class="container-fluid mb40">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="dataTable1" border="1">
                        <thead>
                            <tr class="bg-primary">
                                <th style="width:65px;">SL</th>
                                <th style="width:8%;">Case Type</th>
                                <th style="width:10%;">Case Number</th>
                                <th style="width:8%;">Nature of Case</th>
                                <th style="width:8%;">Receipt of WP(C) / Notice</th>
                                <th style="width:8%;">Subject Matter of the Case</th>
                                <th style="width:8%;">District(s)</th>
                                <th style="width:8%;">Case Under</th>
                                <th style="width:8%;">Case Status</th>
                                <th style="width:8%;">Case Remarks</th>
                                <th style="width:10%;">Parawise Comments</th>
                                <th style="width:10%;">Affidavit</th>
                                <th style="width:10%;">Interim Order</th>
                                <th style="width:10%;">Instruction</th>
                                <th style="width:10%;">Final Order</th>
                                <th style="width:10%;">Speaking Order</th>
                            </tr>
                        </thead>
                        <tbody>
                        <style>
                            .left-align{
                                text-align:left;
                            }
                            table td {
                                vertical-align:top;
                            }
                        </style>
                        @php $j=1; @endphp
                        @foreach($court_cases as $li)
                            <tr>
                                <td>{{ $j }}</td>
                                <td>{{ $li->court_case_type }}</td>
                                <td>{{ $li->case_number }}</td>
                                <td>{{ $li->court_case_nature }}</td>
                                <td>{{ $li->date_of_receipt_of_wpc_notice }}</td>
                                <td>{{ $li->subject_matter_of_case }}</td>
                                <td>{{ $li->district_name }}</td>
                                <td>{{ $li->under }}</td>
                                <td>{{ $li->court_case_status }}</td>
                                <td>{{ $li->remarks }}</td>
                                <td class="left-align">
                                <div class="actions-holder">
                                <?php
                                    $court_cases_parawise_comments = DB::select('select * from court_cases_parawise_comments a, court_cases_submitted_by b where a.court_case_id = ?
                                    AND a.status = "active" AND a.parawise_comments_submitted_by = b.id order by due_date_of_parawise_comments asc', [$li->case_id]);

                                    if( !empty($court_cases_parawise_comments)) {
                                        $temp_date = explode('-', $court_cases_parawise_comments[0]->due_date_of_parawise_comments);
                                        $court_cases_parawise_comments[0]->due_date_of_parawise_comments = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                                        echo "<u>Due Date:</u><br />".$court_cases_parawise_comments[0]->due_date_of_parawise_comments."<br />";
                                        echo "<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_parawise_comments[0]->submitted_by."<br /></div>";
                                        echo "<div style='margin:7.5px 0px;'><u>Letter Number:</u><br />".$court_cases_parawise_comments[0]->letter_number."<br /></div>";
                                        echo "<div style='margin:7.5px 0px;'><u>Submitted:</u><br />".$court_cases_parawise_comments[0]->submitted."<br /></div>";
                                    }
                                    else
                                        echo "N/A";
                                ?>
                                </div>
                                </td>
                                <td class="left-align">
                                <div class="actions-holder">
                                <?php
                                    $court_cases_additional_affidavit = DB::select('select *, b.id as c_id from court_cases_affidavit a, court_cases_submitted_by b where a.court_case_id = ?
                                    AND a.category="additional" AND a.affidavit_submitted_by = b.id order by date_of_affidavit_submitted asc', [$li->case_id]);

                                    if( !empty($court_cases_additional_affidavit)) {
                                        $temp_date = explode('-', $court_cases_additional_affidavit[0]->date_of_affidavit_submitted);
                                        $court_cases_additional_affidavit[0]->date_of_affidavit_submitted = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                                        echo "<b>ADDITIONAL AFFIDAVIT</b><br /><u>Due Date:</u><br />".$court_cases_additional_affidavit[0]->date_of_affidavit_submitted."<br />";
                                        echo "<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_additional_affidavit[0]->submitted_by."<br /></div>";
                                    }
                                    else {
                                        $court_cases_affidavit = DB::select('select *, b.id as c_id from court_cases_affidavit a, court_cases_submitted_by b where a.court_case_id = ?
                                        AND a.category="primary" AND a.affidavit_submitted_by = b.id order by date_of_affidavit_submitted asc', [$li->case_id]);

                                        if( !empty($court_cases_affidavit)) {
                                            $temp_date = explode('-', $court_cases_affidavit[0]->date_of_affidavit_submitted);
                                            $court_cases_affidavit[0]->date_of_affidavit_submitted = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                                            echo "<b>AFFIDAVIT</b><br /><u>Due Date:</u><br />".$court_cases_affidavit[0]->date_of_affidavit_submitted."<br />";
                                            echo "<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_affidavit[0]->submitted_by."<br /></div>"; 
                                        }
                                        else
                                            echo "N/A";
                                    }
                                ?>
                                </div>
                                </td>
                                <td class="left-align">
                                    <div class="actions-holder">
                                    <?php
                                        $court_cases_interim_order = DB::select('select *, b.id as c_id from court_cases_interim_order a, court_cases_submitted_by b where a.court_case_id = ?
                                        AND a.status = "active" AND a.action_to_be_taken_by = b.id order by due_date_of_interim_order asc', [$li->case_id]);

                                        if( !empty($court_cases_interim_order)) {
                                            $temp_date = explode('-', $court_cases_interim_order[0]->due_date_of_interim_order);
                                            $court_cases_interim_order[0]->due_date_of_interim_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                                            echo "<u>Due Date:</u><br />".$court_cases_interim_order[0]->due_date_of_interim_order."<br />";
                                            echo "<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_interim_order[0]->submitted_by."<br /></div>";
                                            echo "<div style='margin:7.5px 0px;'><u>Brief of Interim Order:</u><br />".$court_cases_interim_order[0]->brief_of_interim_order."<br /></div>";
                                            echo "<div style='margin:7.5px 0px;'><u>Details of Action taken as per Interim Order:</u><br />".$court_cases_interim_order[0]->details_action_taken_as_per_interim_order."<br /></div>";
                                        }
                                        else
                                            echo "N/A";
                                    ?>
                                    </div>
                                </td>
                                <td class="left-align">
                                <div class="actions-holder">
                                <?php
                                    $court_cases_instruction = DB::select('select *, b.id as c_id from court_cases_instruction a, court_cases_submitted_by b where a.court_case_id = ?
                                    AND a.status = "active" AND a.instruction_submitted_by = b.id order by due_date_of_instruction asc', [$li->case_id]);

                                    if( !empty($court_cases_instruction)) {
                                        $temp_date = explode('-', $court_cases_instruction[0]->due_date_of_instruction);
                                        $court_cases_instruction[0]->due_date_of_instruction = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                                        echo "<u>Due Date:</u><br />".$court_cases_instruction[0]->due_date_of_instruction."<br />";
                                        echo "<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_instruction[0]->submitted_by."<br /></div>";
                                        echo "<div style='margin:7.5px 0px;'><u>Letter Number:</u><br />".$court_cases_instruction[0]->letter_number."<br /></div>";
                                        echo "<div style='margin:7.5px 0px;'><u>Submitted:</u><br />".$court_cases_instruction[0]->submitted."<br /></div>";
                                    }
                                    else
                                        echo "N/A";

                                ?>
                                </div>
                                </td>
                                <td class="left-align">
                                <div class="actions-holder">
                                <?php
                                    $court_cases_final_order = DB::select('select *, b.id as c_id from court_cases_final_order a, court_cases_submitted_by b where a.court_case_id = ?
                                    AND a.action_to_be_taken_by = b.id order by due_date_of_final_order asc', [$li->case_id]);

                                    if( !empty($court_cases_final_order)) {
                                        $temp_date = explode('-', $court_cases_final_order[0]->due_date_of_final_order);
                                        $court_cases_final_order[0]->due_date_of_final_order = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
                                        echo "<u>Due Date:</u><br />".$court_cases_final_order[0]->due_date_of_final_order."<br />";
                                        echo "<div style='margin:7.5px 0px;'><u>To be Submitted By:</u><br />".$court_cases_final_order[0]->submitted_by."<br /></div>";
                                        echo "<div style='margin:7.5px 0px;'><u>Date of Receipt of Final Order:</u><br />".$court_cases_final_order[0]->date_of_receipt_of_final_order."<br /></div>";
                                        echo "<div style='margin:7.5px 0px;'><u>Details of Final Order:</u><br />".$court_cases_final_order[0]->details_of_final_order."<br /></div>";
                                        echo "<div style='margin:7.5px 0px;'><u>Action taken as per Final Order:</u><br />".$court_cases_final_order[0]->details_of_action_taken_as_per_financial_order."<br /></div>";
                                        echo "<div style='margin:7.5px 0px;'><u>Appeal Petition:</u><br />".$court_cases_final_order[0]->details_of_appeal_petition."<br /></div>";
                                    }
                                    else
                                        echo "N/A";
                                ?>
                                </div>
                                </td>
                                <td class="left-align">
                                <div class="actions-holder">
                                <?php
                                    $speaking_temp = DB::select('select *, b.id as c_id from court_cases_speaking_order a, court_cases_submitted_by b where a.court_case_id = ?
                                    AND a.status = "active" AND a.speaking_order_passed_by = b.id', [$li->case_id]);

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
                                        echo "<u>Due Date:</u><br />".$court_cases_speaking_order[0]->due_date_of_speaking_order."<br />";
                                        echo "<div style='margin:7.5px 0px;'><u>Date of Speaking Order:</u><br />".$court_cases_speaking_order[0]->date_of_speaking_order."<br /></div>";
                                        // echo "<div style='margin:7.5px 0px;'><u>:</u><br />".$court_cases_speaking_order[0]->ddso."<br /></div>";
                                        echo "<div style='margin:7.5px 0px;'><u>Letter No.:</u><br />".$court_cases_speaking_order[0]->letter_number."<br /></div>";
                                        echo "<div style='margin:7.5px 0px;'><u>Submitted:</u><br />".$court_cases_speaking_order[0]->submitted."<br /></div>";
                                    }
                                    else
                                        echo "N/A";
                                ?>
                                </div>
                                </td>
                            </tr>
                        @php $j++; @endphp
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div><br /><br />
        {{------------------DATA TABLE ENDED-----------------------------------------}}
    </div>
@endsection

@section('custom_js')
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <!-- <script type="text/javascript" src="{{ asset('mdas_assets/mdbootstrap/js/jquer-3.4.1.min.js') }}"></script> -->
    <!-- Bootstrap tooltips -->
    <!-- <script type="text/javascript" src="{{ asset('mdas_assets/mdbootstrap/js/poper.min.js') }}"></script> -->
    <!-- Bootstrap core JavaScript -->
    <!-- <script type="text/javascript" src="{{ asset('mdas_assets/mdbootstrap/js/bootstra.min.js') }}"></script> -->
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="{{ asset('mdas_assets/mdbootstrap/js/mdb.min.js') }}"></script>

    <script src="{{ asset('mdas_assets/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('mdas_assets/bootstrap-select/dist/js/i18n/defaults-en_US.min.js') }}"></script>
@endsection