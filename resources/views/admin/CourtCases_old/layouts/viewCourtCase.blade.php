@php
    $page_title="dashboard";
@endphp

@extends('admin.CourtCases.layouts.frame')

@section('custom_css')
    <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/al.css"> -->
    <!-- Bootstrap core CSS -->
    <!-- <link href="{{ asset('mdas_assets/mdbootstrap/css/bootstrap.mi.css') }}" rel="stylesheet"> -->
    <!-- Material Design Bootstrap -->
    <link href="{{ asset('mdas_assets/mdbootstrap/css/mdb.min.css') }}" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="{{ asset('mdas_assets/mdbootstrap/css/style.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('mdas_assets/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
<style>
    .navbar .dropdown-menu a {
        padding: 10px;
        font-size: 10pt;
        font-weight: 300;
        color: #000;
    }
    a {
        font-size: 10pt;
    }
    .back {
        webkit-box-shadow: 1px 3px 3px 3px rgba(209,209,209,1);
        -moz-box-shadow: 1px 3px 3px 3px rgba(209,209,209,1);
        box-shadow: 1px 3px 15px 2px rgba(209,209,209,1);
        background-color:rgba(255, 255, 255, 0.75);
        padding:35px 25px;margin:55px 0px;
    }
    h1 {
        webkit-box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        -moz-box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        font-weight:bold;
        font-family:'Agency FB';
        color:#fff;
        padding:10px;
        margin:15px 0px;
    }
    .sections {
        webkit-box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        -moz-box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        background-color:rgba(240, 240, 240, 0.85);
        padding:25px 25px;margin:55px 0px;
    }
    h2 {
        font-weight:bold;
        margin-top:0;
        color:#367fed;
    }
    .select-margin{
        margin-top:-3.5px;
    }
    button {
        font-size:15pt;
    }
    .dropdown-toggle {
        font-size:10pt;
    }
    .dropdown-menu {
        border-radius:1px;
    }
    li {
        line-height:1.5em;
    }
    .btn, label {
        font-size:12pt;
        color:#444;
    }
    .dropdown {
        background:rgba(0,0,0,0);
    }
    .btn-info.dropdown-toggle {
        left:-4px;
        border:2px solid #747474;
        background-color: #dedede !important;
    }
    .Zebra_DatePicker_Icon {
        width: 45px;
    }
    span.Zebra_DatePicker_Icon {
        right:10px;
    }
    table th, table td {
        font-size: 10pt;
    }
    .form-control, button, input, select, textarea {
        height:43.2px;
        border:2px solid #747474;
        color:#444;
        font-size: 12pt;
        font-weight: bold;
        line-height: inherit;
        background-color:rgba(240, 240, 240, 0.85);
        box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0);
    }
    input:-internal-autofill-selected {
        background-color:rgba(240, 240, 240, 0.85) !important;
        color: #444  !important;
    }
    .pt-3-half {
        padding-top: 1.4rem;
    }
</style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Home</a></li>
            {{--<li class="active"></li>--}}
        </ol>
    </div>
    <div class="container">
        <div class="row back">
            <div class="col-lg-12" style="">
                <center>
                    <h1 class="blue-gradient">
                        <i class="fa fa-gavel" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;COURT CASE DETAILS
                    </h1>
                </center>
                <form id="form-register" class="border border-light p-5" action="{{ route('admin.courtCases.addCourtCaseDetails') }}" method="post">
                <div class="sections">
                        <h2>Primary Information</h2><br />
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="exampleInputPassword1">Case Type</label>
                                    <select class="selectpicker form-control" name="case_type" data-style="btn-info" required>
                                    <option value="">Select</option>
                                    <?php
                                        foreach($court_cases_type as $values)
                                        {
                                    ?>
                                            <option value="{{ Crypt::encrypt('$values->id') }}" <?php if( isset($court_cases[0]->case_type_id) ) { if( $values->id == $court_cases[0]->case_type_id ) { echo "selected"; } } ?> >{{ $values->court_case_type }}</option>
                                    <?php
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="exampleInputPassword1">District</label>
                                    <select class="selectpicker form-control" name="district" data-style="btn-info" required>
                                    <option value="">Select</option>
                                    <?php
                                        foreach($districts as $values)
                                        {
                                    ?>
                                            <option value="{{ Crypt::encrypt('$values->id') }}" <?php if( isset($court_cases[0]->district_id) ) { if( $values->id == $court_cases[0]->district_id ) { echo "selected"; } } ?> >{{ $values->district_name }}</option>
                                    <?php
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div><br />
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">File Number</label>
                                    <input type="text" id="" name="file_number" class="form-control mb-4" value="<?php if( isset($court_cases[0]->file_number) ) { echo $court_cases[0]->file_number; } ?>" placeholder="Enter File Number of the Case" maxlength="100">
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Case Number</label>
                                    <input type="text" id="" name="case_number" class="form-control mb-4" value="<?php if( isset($court_cases[0]->case_number) ) { echo $court_cases[0]->case_number; } ?>" placeholder="Enter Case Number" maxlength="100">
                                </div>
                            </div>
                        </div><br />
                        <div class="form-group"> 
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Name of the Petitioner</label>
                                    <input type="text" id="" name="name_of_the_petitioner" class="form-control mb-4" value="<?php if( isset($court_cases[0]->name_of_petitioner) ) { echo $court_cases[0]->name_of_petitioner; } ?>" placeholder="Enter Name of the Petitioner v/s Respondant" maxlength="255">
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Date of Receipt of WP(C)/Notice </label>
                                    <input id="datepicker1" name="date_of_receipt_of_wpc_notice" type="text" class="form-control datepicker" value="<?php if( isset($court_cases[0]->date_of_receipt_of_wpc_notice) ) { echo $court_cases[0]->date_of_receipt_of_wpc_notice; } ?>" placeholder="Enter Date of Receipt of WP(C)/Notice" data-zdp_readonly_element="true" required>
                                </div>
                            </div>
                        </div><br />
                        <div class="form-group"> 
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Subject Matter of the Case</label>
                                    <input type="text" id="" name="subject_matter_of_the_case" class="form-control mb-4" value="<?php if( isset( $court_cases[0]->subject_matter_of_case ) ) { echo $court_cases[0]->subject_matter_of_case; } ?>" placeholder="Enter Subject Matter of the Case" maxlength="255" required>
                                </div>
                                <div class="col-sm-6">
                                    <label for="exampleInputPassword1">Case Status</label>
                                    <select class="selectpicker form-control select-margin" name="case_status" data-style="btn-info" required>
                                    <option value="">Select</option>
                                    <?php
                                        foreach($court_cases_status as $values)
                                        {
                                    ?>
                                            <option value="{{ Crypt::encrypt('$values->id') }}" <?php if( isset( $court_cases[0]->case_status_id ) ) { if( $values->id == $court_cases[0]->case_status_id ) { echo "selected"; } } ?> >{{ $values->court_case_status }}</option>
                                    <?php
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sections">
                        <div class="form-group"> 
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="exampleInputEmail1">REMARKS</label>
                                    <textarea class="form-control" name="remarks" rows="5" maxlength="2000" placeholder="Within 2000 characters"><?php if( isset($court_cases[0]->remarks) ) { echo $court_cases[0]->remarks; } ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sections">
                        <div class="form-group"> 
                            <div class="row">
                                <div id="content">
                                    <style>
                                        .nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
                                            color: #555;
                                            cursor: default;
                                            background-color: #black;
                                            border: 1px solid #ddd;
                                            border-bottom-color: transparent;
                                        }
                                        a {
                                            font-family:1pt;
                                        }
                                    </style>
                                    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                                        <li class="active"><a href="#red" data-toggle="tab_1">Parawise Comments</a></li>
                                        <li><a href="#tab_2" data-toggle="tab">Affidavit</a></li>
                                        <li><a href="#tab_3" data-toggle="tab">Instruction</a></li>
                                        <li><a href="#tab_4" data-toggle="tab">Speaking Order</a></li>
                                        <li><a href="#tab_5" data-toggle="tab">ATR</a></li>
                                        <li><a href="#tab_7" data-toggle="tab">Interim Order</a></li>
                                        <li><a href="#tab_8" data-toggle="tab">Final Order</a></li>
                                    </ul>
                                    <div id="my-tab-content" class="tab-content">
                                        <div class="tab-pane active" id="tab_1">
                                            <h1>Red</h1>
                                            <p>red red red red red red</p>
                                            <button>Red</button>
                                        </div>
                                        <div class="tab-pane" id="tab_2">
                                            <h1>Orange</h1>
                                            <p>orange orange orange orange orange</p>
                                            <button>Red</button>
                                        </div>
                                        <div class="tab-pane" id="tab_3">
                                            <h1>Yellow</h1>
                                            <p>yellow yellow yellow yellow yellow</p>
                                            <button>Red</button>
                                        </div>
                                        <div class="tab-pane" id="tab_4">
                                            <h1>Green</h1>
                                            <p>green green green green green</p>
                                            <button>Blue</button>
                                        </div>
                                        <div class="tab-pane" id="tab_5">
                                            <h1>Blue</h1>
                                            <p>blue blue blue blue blue</p>
                                            <button>Blue</button>
                                        </div>
                                        <div class="tab-pane" id="tab_6">
                                            <h1>Blue</h1>
                                            <p>blue blue blue blue blue</p>
                                            <button>Blue</button>
                                        </div>
                                        <div class="tab-pane" id="tab_7">
                                            <h1>Blue</h1>
                                            <p>blue blue blue blue blue</p>
                                            <button>Blue</button>
                                        </div>
                                    </div>
                                </div>
                                <script type="text/javascript">
                                    jQuery(document).ready(function ($) {
                                        $('#tabs').tab();
                                    });
                                    $('button').addClass('btn-primary').text('Switch to Orange Tab');
                                    $('button').click(function(){
                                    $('#tabs a[href=#tab_2]').tab('show');
                                    });
                                </script> 
                            </div>
                        </div>
                    </div>
                    <div class="sections">
                        <h2>Parawise Comments</h2><br />
                        <!-- <div class="card">
                            <div class="card-body">
                                <div id="table" class="table-editable">
                                    <span class="table-add float-right mb-3 mr-2" style="float:right;">
                                        <a href="#!" class="btn btn-outline-primary waves-effect">
                                            <i class="fa fa-plus fa-2x" aria-hidden="true"></i>
                                        </a>
                                    </span>
                                    <table class="table table-bordered table-responsive-md table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th width="555px" class="text-center">Parawise Comments Submitted by</th>
                                                <th width="355px"  class="text-center">Due Date of Parawise Comments Submitted by</th>
                                                <th width="355px" class="text-center">Date of Affidavit Sweared</th>
                                                <th width="155px" class="text-center">Delete Record</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="pt-3-half">
                                                    <select class="selectpicker form-control" name="case_type" data-style="btn-info" required>
                                                        <option value="">Select</option>
                                                    <?php

                                                        foreach($court_cases_submitted_by as $values)
                                                        {
                                                    ?>
                                                            <option value="{{ Crypt::encrypt('$values->id') }}" <?php if( $values->id == $court_cases[0]->id ) { echo "selected"; } ?> >{{ $values->submitted_by }}</option>
                                                    <?php
                                                        }
                                                    ?>
                                                    </select>
                                                </td>
                                                <td class="pt-3-half" style="padding:12px;">
                                                    <center><input id="datepicker2" name="date_of_parawise_comments" type="text" class="form-control datepicker" data-zdp_readonly_element="true"></center>
                                                </td>
                                                <td class="pt-3-half" style="padding:12px;">
                                                    <center><input id="datepicker3" name="date_of_affidavit_submitted" type="text" class="form-control datepicker" data-zdp_readonly_element="true"><center>
                                                </td>
                                                <td>
                                                    <span class="table-remove">
                                                        <button type="button" class="btn btn-danger btn-xl" style="font-size:10pt;">Remove</button>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr class="hide">
                                                <td class="pt-3-half">
                                                    <select class="selectpicker form-control" name="case_type" data-style="btn-info" required>
                                                        <option value="">Select</option>
                                                    <?php
                                                        foreach($court_cases_submitted_by as $values)
                                                        {
                                                    ?>
                                                        <option value="{{ Crypt::encrypt('$values->id') }}">{{ $values->submitted_by }}</option>
                                                    <?php
                                                        }
                                                    ?>
                                                    </select>
                                                </td>
                                                <td class="pt-3-half" style="padding:12px;">
                                                    <center><input id="datepicker2" name="date_of_parawise_comments" type="text" class="form-control datepicker" data-zdp_readonly_element="true"></center>
                                                </td>
                                                <td class="pt-3-half" style="padding:12px;">
                                                    <center><input id="datepicker3" name="date_of_affidavit_submitted" type="text" class="form-control datepicker" data-zdp_readonly_element="true"><center>
                                                </td>
                                                <!-- <td class="pt-3-half" contenteditable="true">Portica</td> -->
                                            <!--     <td>
                                                    <span class="table-remove">
                                                        <button type="button" class="btn btn-danger btn-xl" style="font-size:10pt;">Remove</button>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="exampleInputPassword1">Parawise Comments Submitted by</label>
                                    <select class="selectpicker form-control" name="parawise_comments_submitted_by" data-style="btn-info" >
                                        <option value="">Select</option>
                                    <?php
                                        foreach($court_cases_submitted_by as $values)
                                        {
                                    ?>
                                            <option value="{{ Crypt::encrypt($values->id) }}">{{ $values->submitted_by }}</option>
                                    <?php
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div><br /><br />
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Due Date of Parawise Comments Submitted</label>
                                    <input id="datepicker2" name="due_date_of_parawise_comments" type="text" class="form-control datepicker" data-zdp_readonly_element="true">
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Date of Affidavit Sweared</label>
                                    <input id="datepicker3" name="date_of_affidavit_submitted" type="text" class="form-control datepicker" data-zdp_readonly_element="true">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sections">
                        <h2>Interim Order Information</h2><br />
                        <div class="form-group"> 
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Date of receipt of Interim Order</label>
                                    <input id="datepicker4" name="date_of_interim_order" type="text" class="form-control datepicker" data-zdp_readonly_element="true">
                                </div>
                            </div>
                        </div><br />
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="exampleInputEmail1">Brief of Interim order if any</label>
                                    <textarea class="form-control" name="brief_of_interim_order" rows="4" placeholder="Within 2000 Characters"></textarea>
                                </div>
                            </div>
                        </div><br />
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="exampleInputEmail1">Details of Action taken as per interim order</label>
                                    <textarea class="form-control" name="details_action_taken_as_per_interim_order" rows="4" placeholder="Within 2000 Characters"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sections">
                        <h2>Final Order Information</h2><br />
                        <div class="form-group"> 
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Date of Final order</label>
                                    <input id="datepicker5" name="date_of_final_order" type="text" class="form-control datepicker" data-zdp_readonly_element="true">
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Date of receipt of Final order</label>
                                    <input id="datepicker6" name="date_of_receipt_of_final_order" type="text" class="form-control datepicker" data-zdp_readonly_element="true">
                                </div>
                            </div>
                        </div><br />
                        <div class="form-group"> 
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Details of Final Order</label>
                                    <textarea class="form-control" name="details_of_final_order" rows="4"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Details of Action taken as per Final Order</label>
                                    <textarea class="form-control" name="details_of_action_taken_as_per_financial_order" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="margin:11px;">
                        <div class="row">
                            <div clas="col-md-12">
                                <button type="reset" class="btn btn-primary peach-gradient" style="float:left;font-weight:bold;">Reset</button>
                                <button type="submit" class="btn btn-primary blue-gradient" style="float:right;font-weight:bold;">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <!-- <script type="text/javascript" src="{{ asset('mdas_assets/mdbootstrap/js/jquer-3.4.1.min.js') }}"></script> -->
    <!-- Bootstrap tooltips -->
    <!-- <script type="text/javascript" src="{{ asset('mdas_assets/mdbootstrap/js/poper.min.js') }}"></script> -->
    <!-- Bootstrap core JavaScript -->
    <!-- <script type="text/javascript" src="{{ asset('mdas_assets/mdbootstrap/js/bootstra.min.js') }}"></script> -->
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="{{ asset('mdas_assets/mdbootstrap/js/mdb.min.js') }}"></script>



    <script src="{{ asset('mdas_assets/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('mdas_assets/bootstrap-select/dist/js/i18n/defaults-en_US.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.my-select').selectpicker();

            $('.datepicker').Zebra_DatePicker({
                format: 'M d, Y'
            });

            $('#form-register').on('submit', function(e){
                e.preventDefault();
                //alert();return;
                if($('#form-register').valid()){
                    $('.page-loader-wrapper').fadeIn();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: '{{route('admin.courtCases.addCourtCaseDetails')}}',
                        //dataType: "json",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data) {
                            alert(data);
                            return;
                            if (data.msgType == true) {
                                swal("Success", data.msg, "success")
                                    .then((value) => {
                                    $('#editAssetForm').modal('hide');
                                location.reload();
                            });

                            }else{
                                if(data.msg=="VE"){
                                    swal("Error", "Validation error.Please check the form correctly!", 'error');
                                    $.each(data.errors, function( index, value ) {
                                        $('#'+index).after('<p class="text-danger form_errors">'+value+'</p>');
                                    });
                                }else{
                                    swal("Error", data.msg, 'error');
                                }
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                        },
                        complete: function (data) {
                            $('.page-loader-wrapper').fadeOut();
                        }
                    });
                }

            });

        });



        const $tableID = $('#table');
        const $BTN = $('#export-btn');
        const $EXPORT = $('#export');

        const newTr = `
        <tr class="hide">
            <td class="pt-3-half" contenteditable="true"></td>
            <td class="pt-3-half" contenteditable="true"></td>
            <td class="pt-3-half" contenteditable="true"></td>
            <td>
                <span class="table-remove"><button type="button" class="btn btn-danger btn-rounded btn-sm my-0 waves-effect waves-light">Remove</button></span>
            </td>
        </tr>`;

        $('.table-add').on('click', 'i', () => {
            const $clone = $tableID.find('tbody tr').last().clone(true).removeClass('hide table-line');
            if ($tableID.find('tbody tr').length === 0) {
                $('tbody').append(newTr);
            }
            $tableID.find('table').append($clone);
        });

        $tableID.on('click', '.table-remove', function () {
            $(this).parents('tr').detach();
        });

        $tableID.on('click', '.table-up', function () {
            const $row = $(this).parents('tr');
            if ($row.index() === 1) {
                return;
            }
            $row.prev().before($row.get(0));
        });

        $tableID.on('click', '.table-down', function () {
            const $row = $(this).parents('tr');
            $row.next().after($row.get(0));
        });

        // A few jQuery helpers for exporting only
        jQuery.fn.pop = [].pop;
        jQuery.fn.shift = [].shift;

        $BTN.on('click', () => {

        const $rows = $tableID.find('tr:not(:hidden)');
        const headers = [];
        const data = [];

        // Get the headers (add special header logic here)
        $($rows.shift()).find('th:not(:empty)').each(function () {
            headers.push($(this).text().toLowerCase());
        });

        // Turn all existing rows into a loopable array
        $rows.each(function () {
            const $td = $(this).find('td');
            const h = {};

            // Use the headers from earlier to name our hash keys
            headers.forEach((header, i) => {

            h[header] = $td.eq(i).text();
            });

            data.push(h);
        });

        // Output the result
        $EXPORT.text(JSON.stringify(data));
        });
    </script>
@endsection