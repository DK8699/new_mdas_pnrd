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
    <!-- <link href="{{ asset('mdas_assets/mdbootstrap/css/style.css') }}" rel="stylesheet"> -->

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
        font-size:23pt;
        font-weight:bold;
        font-family:'Agency FB';
        color:#fff;
        padding:7.5px;
        margin:15px 0px 0px 0px;
    }
    h2 {
        font-size:18.5pt;
        font-weight:bold;
        margin-top:0;
        color:#367fed;
    }
    .blue-gradient, .aqua-gradient {
        padding:5px 25px;
    }
    .sections {
        webkit-box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        -moz-box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        background-color:rgba(240, 240, 240, 0.85);
        padding:25px 25px;margin:55px 0px;
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
    .error {
        color:#f84d4d;
    }
    .dropdown {
        background:rgba(0,0,0,0);
    }
    .btn{
        padding: 6px 12px;
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
        height:40px;
        border:2px solid #747474;
        color:#444;
        font-size: 12pt;
        font-weight: bold;
        line-height: inherit;
        background-color:rgba(240, 240, 240, 0.85);
        box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0);
        padding-top:4px;
    }
    input:-internal-autofill-selected {
        background-color:rgba(240, 240, 240, 0.85) !important;
        color: #444  !important;
    }
    .pt-3-half {
        padding-top: 1.4rem;
    }



body {
    font-size: '';
    font-family: 'Open Sans', sans-serif;
}
.main {
    font-family: 'Lato', Calibri, Arial, sans-serif;
    color: #47a3da;
}
.bootstrap-select>.dropdown-toggle.bs-placeholder.btn-info {
    color: #444;
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
                        <i class="fa fa-gavel" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;ADD COURT CASE
                    </h1>
                </center>
                <form id="form-register" class="border border-light p-5" action="{{ route('admin.courtCases.addCourtCaseDetails') }}" method="post">
                    <div class="sections">
                        <h2>Primary Information</h2><br />
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Case Type</label>
                                    <select class="selectpicker form-control" name="case_type" data-style="btn-info" required>
                                        <option value="">NOTHING SELECTED</option>
                                    <?php
                                        foreach($court_cases_type as $values)
                                        {
                                    ?>
                                            <option value="{{ Crypt::encrypt($values->id) }}">{{ $values->court_case_type }}</option>
                                    <?php
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>District(s)</label>
                                    <select id="district" class="selectpicker form-control" name="district" data-style="btn-info" multiple required>
                                    <?php
                                        foreach($districts as $values)
                                        {
                                    ?>
                                            <option value="{{ Crypt::encrypt($values->id) }}">{{ $values->district_name }}</option>
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
                                    <input type="text" id="" name="file_number" class="form-control mb-4" placeholder="Enter File Number of the Case" maxlength="100">
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Case Number</label>
                                    <input type="text" id="" name="case_number" class="form-control mb-4" placeholder="Enter Case Number" maxlength="100">
                                </div>
                            </div>
                        </div><br />
                        <div class="form-group"> 
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Name of the Petitioner</label>
                                    <input type="text" id="" name="name_of_the_petitioner" class="form-control mb-4" placeholder="Enter Name of the Petitioner v/s Respondant" maxlength="255">
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Date of Receipt of WP(C)/Notice </label>
                                    <input id="datepicker1" name="date_of_receipt_of_wpc_notice" type="text" class="form-control datepicker" placeholder="Enter Date of Receipt of WP(C)/Notice" data-zdp_readonly_element="true" required >
                                </div>
                            </div>
                        </div><br />
                        <div class="form-group"> 
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="exampleInputPassword1">Nature of Case</label>
                                    <select class="selectpicker form-control select-margin" name="nature_of_case" data-style="btn-info" required>
                                        <option value="">NOTHING SELECTED</option>
                                    <?php
                                        foreach($court_cases_nature as $values)
                                        {
                                    ?>
                                            <option value="{{ Crypt::encrypt($values->id) }}">{{ $values->court_case_nature }}</option>
                                    <?php
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="exampleInputPassword1">Case Status</label>
                                        <select class="selectpicker form-control select-margin" name="case_status" data-style="btn-info" required>
                                        <option value="">NOTHING SELECTED</option>
                                    <?php
                                        foreach($court_cases_status as $values)
                                        {
                                    ?>
                                            <option value="{{ Crypt::encrypt($values->id) }}">{{ $values->court_case_status }}</option>
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
                                    <label for="exampleInputEmail1">Subject Matter of the Case</label>
                                    <input type="text" id="" name="subject_matter_of_the_case" class="form-control mb-4" placeholder="Enter Subject Matter of the Case" maxlength="255" required>
                                </div>
                                <div class="col-sm-6">
                                    <label for="exampleInputPassword1">Case Under</label>
                                    <select class="selectpicker form-control select-margin" name="case_under" data-style="btn-info" required>
                                        <option value="">NOTHING SELECTED</option>
                                    <?php
                                        foreach($court_cases_under as $values)
                                        {
                                    ?>
                                            <option value="{{ Crypt::encrypt($values->id) }}">{{ $values->under }}</option>
                                    <?php
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div><br />
                        <div class="form-group"> 
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="exampleInputEmail1">REMARKS</label>
                                    <textarea class="form-control" name="remarks" rows="5" maxlength="2000" placeholder="Within 2000 characters"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="margin:0px 11px;">
                        <div class="row">
                            <div clas="col-md-12">
                                <button type="reset" class="btn btn-primary peach-gradient" style="float:left;font-weight:bold;width:95px;">Reset</button>
                                <button type="submit" class="btn btn-primary blue-gradient" style="float:right;font-weight:bold;width:105px;">Submit</button>
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
                format: 'd-m-Y'
            });

            $('#form-register').on('submit', function(e){
                e.preventDefault();
                var formData = new FormData(this);
                formData.append('districts', $("#district").val() );   
                if($('#form-register').valid()){
                    $('.page-loader-wrapper').fadeIn();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: '{{route('admin.courtCases.addCourtCaseDetails')}}',
                        dataType: "json",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data) {
                            if (data.msgType == true) {
                               swal("Successfully Submitted", "The Court Case has been Successfully Added", "success");
                            }
                            else {
                                if(data.msg=="VE"){
                                    swal("Error", "Validation error.Please check the form correctly!", 'error');
                                    $.each(data.errors, function( index, value ) {
                                        $('#'+index).after('<p class="text-danger form_errors">'+value+'</p>');
                                    });
                                }
                                else {
                                    swal("Error", data.msg, 'error');
                                }
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            swal("Database Problem", 'Cannot Submit Details', 'error');return;
                            callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                        },
                        complete: function (data) {
                            $('#form-register').trigger("reset");
                            $(".selectpicker").val('');
                            $(".selectpicker").selectpicker("refresh");
                            $('.page-loader-wrapper').fadeOut();
                        }
                    });
                }

            });

        });
    </script>
@endsection