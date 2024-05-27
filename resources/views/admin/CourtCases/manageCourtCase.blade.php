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
    .select-margin{
        margin-top:-3.5px;
    }
    .sections {
        webkit-box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        -moz-box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        background-color:rgba(240, 240, 240, 0.85);
        padding:25px 25px;margin:55px 0px;
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
    table.dataTable thead th, table.dataTable thead td {
        border-bottom: 1px solid #8f8f8f;
    }
    .table-bordered>thead>tr>th, .table-bordered>tbody>tr>td {
        //background-color:white;
        font-size: 12pt;
        font-weight:bold;
        font-family:'Roboto';
        color:#4d4d4d;
        vertical-align: middle;
        padding-bottom:13px;
    }
    .table-bordered>thead>tr>th {
        background-color:#3a9fff;
        color:white;
    }
    .dataTables_wrapper .dataTables_filter input {
        padding: 0px 5px;
        margin-top: 4px;
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
        padding: 5.5px 10px
    }
    .pt-3-half {
        padding-top: -1.4rem;
    }
    .Zebra_DatePicker_Icon_Wrapper{
        width:100% !important;
    }
    #details_of_appeal_petition:disabled {
        background-color:#ccc;
        border-color:#b8b8b8;
    }
    .blue-gradient, .aqua-gradient {
        padding:5px 25px;
    }
    .bootstrap-select>.dropdown-toggle.bs-placeholder.btn-info {
        color: #444;
    }
    .bootstrap-select>.dropdown-toggle {
        top: -0.85px;
    }
    /* .btn-default {
        background-color: 
        #2bbbad !important;
        color: #fff;
    }
    .btn {
        margin: 0;
        margin-left: 0.375rem;
        color: inherit;
        text-transform: uppercase;
        word-wrap: break-word;
        white-space: normal;
        cursor: pointer;
        border: 0;
        border-radius: .125rem;
            border-top-right-radius: 0.125rem;
            border-bottom-right-radius: 0.125rem;
        box-shadow: 0 2px 5px 0 rgba(0,0,0,.16),0 2px 10px 0 rgba(0,0,0,.12);
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        //padding: .84rem 2.14rem;
        //font-size: 14pt;
    }
 */

/*******************************Animated Checkbox***********************************/
.checkbox-animated {
    position: relative;
    margin-top: 10px;
    margin-bottom: 10px;
}
.checkbox-animated .checkbox {
display: none;
}
.checkbox-animated .checkbox:disabled ~ label .box {
border-color: #777;
background-color: #E6E6E6;
}
.checkbox-animated .checkbox:disabled ~ label .check {
border-color: #777;
}
.checkbox-animated .checkbox:checked ~ label .box {
opacity: 0;
-webkit-transform: scale(0) rotate(-180deg);
-moz-transform: scale(0) rotate(-180deg);
transform: scale(0) rotate(-180deg);
}
.checkbox-animated .checkbox:checked ~ label .check {
opacity: 1;
-webkit-transform: scale(1) rotate(45deg);
-moz-transform: scale(1) rotate(45deg);
transform: scale(1) rotate(45deg);
}
.checkbox-animated label {
cursor: pointer;
padding-left: 28px;
font-weight: normal;
margin-bottom: 0;
}
.checkbox-animated label span {
display: block;
position: absolute;
left: 0;
-webkit-transition-duration: 0.3s;
-moz-transition-duration: 0.3s;
transition-duration: 0.3s;
}
.checkbox-animated label .box {
border: 2px solid #6a6a6a;
height: 20px;
width: 20px;
z-index: 888;
-webkit-transition-delay: 0.2s;
-moz-transition-delay: 0.2s;
transition-delay: 0.2s;
}
.checkbox-animated label .check {
top: -7px;
left: 6px;
width: 12px;
height: 24px;
border: 2px solid #3771e9;
border-top: none;
border-left: none;
opacity: 0;
z-index: 888;
-webkit-transform: rotate(180deg);
-moz-transform: rotate(180deg);
transform: rotate(180deg);
-webkit-transition-delay: 0.3s;
-moz-transition-delay: 0.3s;
transition-delay: 0.3s;
}
.checkbox-animated-inline {
position: relative;
margin-top: 10px;
margin-bottom: 10px;
}
.checkbox-animated-inline .checkbox {
display: none;
}
.checkbox-animated-inline .checkbox:disabled ~ label .box {
border-color: #777;
background-color: #E6E6E6;
}
.checkbox-animated-inline .checkbox:disabled ~ label .check {
border-color: #777;
}
.checkbox-animated-inline .checkbox:checked ~ label .box {
opacity: 0;
-webkit-transform: scale(0) rotate(-180deg);
-moz-transform: scale(0) rotate(-180deg);
transform: scale(0) rotate(-180deg);
}
.checkbox-animated-inline .checkbox:checked ~ label .check {
opacity: 1;
-webkit-transform: scale(1) rotate(45deg);
-moz-transform: scale(1) rotate(45deg);
transform: scale(1) rotate(45deg);
}
.checkbox-animated-inline label {
cursor: pointer;
padding-left: 28px;
font-weight: normal;
margin-bottom: 0;
}
.checkbox-animated-inline label span {
display: block;
position: absolute;
left: 0;
-webkit-transition-duration: 0.3s;
-moz-transition-duration: 0.3s;
transition-duration: 0.3s;
}
.checkbox-animated-inline label .box {
border: 2px solid #000;
height: 20px;
width: 20px;
z-index: 888;
-webkit-transition-delay: 0.2s;
-moz-transition-delay: 0.2s;
transition-delay: 0.2s;
}
.checkbox-animated-inline label .check {
top: -7px;
left: 6px;
width: 12px;
height: 24px;
border: 2px solid #BADA55;
border-top: none;
border-left: none;
opacity: 0;
z-index: 888;
-webkit-transform: rotate(180deg);
-moz-transform: rotate(180deg);
transform: rotate(180deg);
-webkit-transition-delay: 0.3s;
-moz-transition-delay: 0.3s;
transition-delay: 0.3s;
}
.checkbox-animated-inline.checkbox-animated-inline {
display: inline-block;
}
.checkbox-animated-inline.checkbox-animated-inline + .checkbox-animated-inline {
margin-left: 10px;
}
/*******************************Animated Checkbox***********************************/

</style>
@endsection

@section('content')
    <!-- <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Home</a></li>
            {{--<li class="active"></li>--}}
        </ol>
    </div> -->
    <div class="container">
        <div class="row back">
            <div class="col-lg-12" style="">
                <center>
                    <h1 class="blue-gradient">
                        <i class="fa fa-gavel" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;MANAGE COURT CASE DETAILS
                    </h1>
                </center>
                <form id="primary-update" action="{{ route('admin.courtCases.updateCourtCasePrimary') }}" method="post">
                    <div class="sections">
                        <h2 style="float:left;">Primary Information</h2>
                        <a href="{{ route('admin.courtCases.viewCourtCase', Crypt::encrypt($court_cases[0]->id)) }}" target="_blank" class="btn waves-effect peach-gradient btn-sm" style="font-weight:bold;font-size:10pt;padding:5px 10px;width:110px;text-align:left;float:right"><i class="fa fa-eye"></i>&nbsp;&nbsp;View Case</a>
                        <br /><br /><br />
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="exampleInputPassword1">Case Type</label>
                                    <select class="selectpicker form-control" name="case_type" data-style="btn-info" autocomplete="off" required>
                                        <option value="">NOTHING SELECTED</option>
                                    <?php
                                        foreach($court_cases_type as $values)
                                        {
                                    ?>
                                            <option value="{{ Crypt::encrypt($values->id) }}" <?php if( isset($court_cases[0]->case_type_id) ) { if( $values->id == $court_cases[0]->case_type_id ) { echo "selected"; } } ?> >{{ $values->court_case_type }}</option>
                                    <?php
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>District(s)</label>
                                    <select id="district" class="selectpicker form-control" name="district" data-style="btn-info" multiple autocomplete="off" required>
                                    <option value="">Select</option>
                                    <?php
                                        $p = 0;
                                        foreach($districts as $values)
                                        {
                                            $district_ids = json_decode($court_cases[0]->district_id);
                                            for($i=0;$i<sizeof($district_ids);$i++)
                                            {
                                                if( $values->id == $district_ids[$i] ) {
                                                    $p = 1;
                                                }
                                            }
                                            if( $p == 1 ) {
                                    ?>
                                                <option value="{{ Crypt::encrypt($values->id) }}" <?php echo "selected"; ?> >{{ $values->district_name }}</option>
                                    <?php
                                            }
                                            else {
                                    ?>
                                                <option value="{{ Crypt::encrypt($values->id) }}">{{ $values->district_name }}</option>
                                    <?php
                                            }
                                            $p = 0;
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
                                <div class="col-sm-6">
                                    <label for="exampleInputPassword1">Nature of Case</label>
                                    <select class="selectpicker form-control select-margin" name="nature_of_case" data-style="btn-info" autocomplete="off" required>
                                        <option value="">Select</option>
                                    <?php
                                        foreach($court_cases_nature as $values)
                                        {
                                    ?>
                                            <option value="{{ Crypt::encrypt($values->id) }}" <?php if( isset($court_cases[0]->nature_of_case) ) { if( $values->id == $court_cases[0]->nature_of_case ) { echo "selected"; } } ?>>{{ $values->court_case_nature }}</option>
                                    <?php
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="exampleInputPassword1">Case Status</label>
                                    <select class="selectpicker form-control select-margin" name="case_status" data-style="btn-info" autocomplete="off" required>
                                    <option value="">Select</option>
                                    <?php
                                        foreach($court_cases_status as $values)
                                        {
                                    ?>
                                            <option value="{{ Crypt::encrypt($values->id) }}" <?php if( isset( $court_cases[0]->case_status_id ) ) { if( $values->id == $court_cases[0]->case_status_id ) { echo "selected"; } } ?> >{{ $values->court_case_status }}</option>
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
                                    <input type="text" id="" name="subject_matter_of_the_case" class="form-control mb-4" value="<?php if( isset( $court_cases[0]->subject_matter_of_case ) ) { echo $court_cases[0]->subject_matter_of_case; } ?>" placeholder="Enter Subject Matter of the Case" maxlength="255" required>
                                </div>
                                <div class="col-sm-6">
                                    <label for="exampleInputPassword1">Case Under</label>
                                    <select class="selectpicker form-control select-margin" name="case_under" data-style="btn-info" required>
                                        <option value="">NOTHING SELECTED</option>
                                    <?php
                                        foreach($court_cases_under as $values)
                                        {
                                    ?>
                                            <option value="{{ Crypt::encrypt($values->id) }}" <?php if( isset( $court_cases[0]->case_under ) ) { if( $values->id == $court_cases[0]->case_under ) { echo "selected"; } } ?> >{{ $values->under }}</option>
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
                                    <textarea class="form-control" name="remarks" rows="5" maxlength="2000" placeholder="Within 2000 characters"><?php if( isset($court_cases[0]->remarks) ) { echo $court_cases[0]->remarks; } ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary blue-gradient" style="float:right;font-weight:bold;">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                    <div class="sections">
                        <div class="form-group"> 
                            <div class="row">
                                <div id="content" style="margin:0px 15px;min-height:455px;">
                                    <style>
                                        .nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover {
                                            color: white;
                                            background-color: #0d99d0;
                                            border: 1px solid #0d99d0;
                                            //border-bottom: 1px solid #0d99d0;
                                            border-radius: 1px;
                                            padding: 7.5px 15px;
                                        }
                                        .nav-tabs {
                                            border-bottom: 3px solid #0d99d0;
                                        }
                                        .nav-tabs > li {
                                        float: left;
                                        margin-bottom: -5px;
                                        }
                                        .nav-tabs > li > a:hover, .nav-tabs > li > a:focus {
                                            background:transparent;
                                            color:#444;
                                            border-color:transparent;
                                        }
                                    </style>
                                    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                                        <li class="active"><a href="#tab_PC" data-toggle="tab" style="font-size:12pt;font-weight:bold;">PARAWISE COMMENTS</a></li>
                                        <li><a href="#tab_A" data-toggle="tab" style="font-size:12pt;font-weight:bold;">AFFIDAVIT</a></li>
                                        <li><a href="#tab_IO" data-toggle="tab" style="font-size:12pt;font-weight:bold;">INTERIM ORDER</a></li>
                                        <li><a href="#tab_I" data-toggle="tab" style="font-size:12pt;font-weight:bold;">INSTRUCTION</a></li>
                                        <li><a href="#tab_FO" data-toggle="tab" style="font-size:12pt;font-weight:bold;">FINAL ORDER</a></li>
                                        <li><a href="#tab_SO" data-toggle="tab" style="font-size:12pt;font-weight:bold;">SPEAKING ORDER</a></li>
                                    </ul>
                                    <div id="my-tab-content" class="tab-content" style="padding:0px 0px;">
                                        <div class="tab-pane active" id="tab_PC"><br /><br /><br />
                                            <h2 style="float:left;">Parawise Comment</h2>
                                            <button type="button" class="btn btn-primary blue-gradient" style="float:right;font-weight:bold;margin:15px 2px 5px 0px;" data-toggle="modal" data-target="#parawisecommentsaddModal"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table class="table table-bordered table-striped table-hover" id="dataTable1">
                                                        <thead>
                                                            <tr class="bg-primary">
                                                                <th>Due Date of Submission of Parawise Comments</th>
                                                                <th>To be Submitted by</th>
                                                                <th>Letter Number</th>
                                                                <th>Document</th>
                                                                <th>Submitted</th>
                                                                <th style="width:85px;">Task</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="parawise_comments_table_body">
                                                        @foreach($court_cases_parawise_comments as $li)
                                                            <tr>
                                                                <td>
                                                                <?php
                                                                    echo $li->due_date_of_parawise_comments;
                                                                    if( $li->status == "active" ) {
                                                                        echo '<br /><span class="badge" style="background-color: green">In Effect</span>';
                                                                    }
                                                                ?>
                                                                </td>
                                                                <td>{{ $li->submitted_by }}</td>
                                                                <td>{{ $li->letter_number }}</td>
                                                                <td>
                                                                <?php
                                                                    if( $li->document != "" && $li->document != NULL) {
                                                                ?>
                                                                        <a href="{{ url('admin/courtCases/viewCourtCaseParawiseComments/'.Crypt::encrypt($li->i_id)) }}" target="_blank"  class="btn btn-outline-default waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:73px;margin:5px 0px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View</a>
                                                                <?php
                                                                    }
                                                                    else
                                                                        echo "No Document";
                                                                ?>
                                                                </td>
                                                                <td>{{ $li->submitted }}</td>
                                                                <td>
                                                                    <a onClick="load_parawise_comments_update({{ $li->i_id }});" data-toggle="modal" data-target="#parawisecommentsupdateModal" class="btn aqua-gradient waves-effect btn-lg" style="font-weight:bold;font-size:10pt;padding:5px 5px;width:87px;"><i class="fa fa-edit"></i>&nbsp;&nbsp;Update</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="parawisecommentsupdateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document" style="width:755px;">
                                                    <div class="modal-content">
                                                        <form id="parawise-comments-update-form" class="action-update" action="{{ route('admin.courtCases.updateParawiseComments') }}" method="post">
                                                        <div class="modal-header aqua-gradient" style="height:75px;">
                                                            <div class="portlet-title">
                                                                <div class="caption caption-md">
                                                                    <center><h3 class="caption-subject font-blue-madison bold uppercase"><b>Update Parawise Comments Details</b></h3></center>
                                                                    <button type="button" class="btn-outline-danger" data-dismiss="modal" style="position:absolute;height:38px;width:35px;right:15px;top:19px;border-radius:2px;">
                                                                        <span aria-hidden="true" >&times;</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="parawise_comments_update_body" class="modal-body">

                                                        </div>
                                                        <div id="applicant_details_footer" class="modal-footer" style="padding:10px;">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal" style="float:left;color:white;;padding:0px 12px;">Close</button>
                                                                        <button type="submit" class="btn btn-primary aqua-gradient" style="float:right;font-weight:bold;">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="parawisecommentsaddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document" style="width:755px;">
                                                    <div class="modal-content">
                                                        <form id="parawise-comments-form" class="action-update" action="{{ route('admin.courtCases.addParawiseComments') }}" method="post">
                                                            <div class="modal-header aqua-gradient" style="height:75px;">
                                                                <div class="portlet-title">
                                                                    <div class="caption caption-md">
                                                                        <center><h3 class="caption-subject font-blue-madison bold uppercase"><b>Add Parawise Comments</b></h3></center>
                                                                        <button type="button" class="btn-outline-danger" data-dismiss="modal" style="position:absolute;height:38px;width:35px;right:15px;top:19px;border-radius:2px;">
                                                                            <span aria-hidden="true" >&times;</span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="parawise_comments_details_body" class="modal-body">
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <label>Parawise Comments to be Submitted By</label>
                                                                            <select id="parawise_comments_submitted_by" class="selectpicker form-control select-margin court-case-select" name="parawise_comments_submitted_by" data-style="btn-info" autocomplete="off" required >
                                                                                <option value="{{ Crypt::encrypt('') }}">NOTHING SELECTED</option>
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
                                                                        <div class="col-sm-6">
                                                                            <div id="parawise_comments_submitted_by_blocks" class="select_districts_blocks"></div>
                                                                        </div>
                                                                    </div><br /><br />
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label for="exampleInputEmail1">Due Date of Submission of Parawise Comments</label>
                                                                            <input id="due_date_of_parawise_comments" name="due_date_of_parawise_comments" type="text" value="" class="form-control datepicker" data-zdp_readonly_element="true" required >
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label for="exampleInputEmail1">Letter Number</label>
                                                                            <input name="parawise_comments_letter_number" type="text" value="" class="form-control" >
                                                                        </div>
                                                                    </div><br /><br />
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label>Upload Document</label>
                                                                            <input type="file" name="parawise_comments_document" id="parawisecommentsDocument" value="" class="form-control" >
                                                                            <b>.pdf files only (should be less than 2 MB)</b>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label for="exampleInputEmail1">Submitted</label><br />
                                                                            <!-- ANIMATED CHECKBOXES -->
                                                                            <div class="checkbox-animated col-sm-6" style="width:155px;">
                                                                                <input id="checkbox_01" type="radio" class="checkbox" name="parawise_comments_submitted" value="Yes" />
                                                                                <label for="checkbox_01">
                                                                                    <span class="check"></span>
                                                                                    <span class="box"></span>
                                                                                    YES 
                                                                                </label>
                                                                            </div>
                                                                            <div class="checkbox-animated col-sm-6" style="width:155px;">
                                                                                <input id="checkbox_02" type="radio" class="checkbox" name="parawise_comments_submitted" value="No" />
                                                                                <label for="checkbox_02">
                                                                                    <span class="check"></span>
                                                                                    <span class="box"></span>
                                                                                        NO
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div><br /><br />
                                                                </div>
                                                            </div>
                                                            <div id="applicant_details_footer" class="modal-footer" style="padding:10px;">
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal" style="float:left;color:white;padding:0px 12px;">Close</button>
                                                                            <button type="submit" class="btn btn-primary aqua-gradient" style="float:right;font-weight:bold;">Save</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab_A"><br /><br /><br />
                                            <div class="form-group">
                                                <div class="row">
                                                <form id="affidavit-form" class="action-update" action="{{ route('admin.courtCases.addCourtCaseAffidavit') }}" method="post" enctype="multipart/form-data">
                                                    <div class="form-group col-sm-6">
                                                        <h2>Affidavit</h2><br />
                                                        <div class="row" style="margin-bottom:22px;">
                                                            <div class="col-sm-12">
                                                                <label for="exampleInputPassword1">Affidavit Sweared by</label>
                                                                <select id="affidavit_submitted_by" class="selectpicker form-control court-case-select" name="affidavit_submitted_by" data-style="btn-info" autocomplete="off" required>
                                                                    <option value="{{ Crypt::encrypt('') }}">Select</option>
                                                                <?php
                                                                    foreach($court_cases_submitted_by as $values)
                                                                    {
                                                                ?>
                                                                        <option value="{{ Crypt::encrypt($values->id) }}" <?php if( isset($court_cases_affidavit[0]->affidavit_submitted_by) ) { if( $court_cases_affidavit[0]->affidavit_submitted_by == $values->id ) { echo "selected"; } } ?> >{{ $values->submitted_by }}</option>
                                                                <?php
                                                                    }
                                                                ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div id="affidavit_submitted_by_blocks" class="">
                                                                <?php
                                                                    $options = '';
                                                                    if( isset($court_cases_affidavit[0]->affidavit_submitted_by) ) {
                                                                        if( $court_cases_affidavit[0]->affidavit_submitted_by == 6 ) {

                                                                            $district_ids = json_decode($court_cases[0]->district_id);
                                                                            $size = sizeof($district_ids);
                                                                            $affidavit_blocks = DB::select('select * from court_cases_blocks where section_id = 2 AND section_table_id = ?', [$court_cases_affidavit[0]->id]);

                                                                            $options = '<label>Blocks</label>
                                                                                        <select id="affidavit_comments_blocks" class="selectpicker form-control select-margin affidavit_blocks" name="blocks" data-style="btn-info" multiple autocomplete="off" required >';
                                                                            for($i=0;$i<$size;$i++) {
                                                                                $court_cases_district = DB::select('select * from districts where id = ? order by id asc', [$district_ids[$i]]);
                                                                                $court_cases_blocks = DB::select('select * from blocks where district_id = ? order by block_name asc', [$district_ids[$i]]);

                                                                                $options .= '<optgroup label="'.$court_cases_district[0]->district_name.'">';
                                                                                foreach($court_cases_blocks as $values) {
                                                                                    $p = 0;
                                                                                    if(!empty($affidavit_blocks)) {
                                                                                        foreach($affidavit_blocks as $blocks) {
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
                                                                    echo $options;
                                                                ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top:22px;">
                                                            <div class="col-md-12">
                                                                <label>Date of Affidavit Sweared</label>
                                                                <input name="date_of_affidavit_submitted" type="text" value="<?php if( isset($court_cases_affidavit[0]->date_of_affidavit_submitted) ) { echo $court_cases_affidavit[0]->date_of_affidavit_submitted; } ?>" class="form-control datepicker" data-zdp_readonly_element="true" required >
                                                            </div>
                                                        </div><br /><br />
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label for="exampleInputEmail1">Upload Document</label>
                                                                <input type="file" name="affidavitDocument" id="affidavitDocument" value="" class="form-control" required >
                                                                <div id="upload_affidavit">
                                                                    <?php
                                                                        if( isset($court_cases_affidavit[0]->document)  )
                                                                        {
                                                                            if( isset($court_cases_affidavit[0]->document) != "" )
                                                                            {
                                                                    ?>
                                                                            <a href="{{ url('admin/courtCases/viewCourtCaseAffidavit/'.Crypt::encrypt($court_cases_affidavit[0]->id)) }}" target="_blank"  class="btn btn-outline-default waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:145px;margin:5px 0px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View Document</a>
                                                                    <?php
                                                                            }
                                                                        }
                                                                    ?>
                                                                </div>
                                                                <b>.pdf files only (should be less than 2 MB)</b>
                                                            </div>
                                                        </div><br />
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <button type="submit" class="btn btn-primary aqua-gradient" style="float:left;font-weight:bold;">Save</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <form id="additional-affidavit-form" class="action-update" action="{{ route('admin.courtCases.addCourtCaseAdditionalAffidavit') }}" method="post" enctype="multipart/form-data">
                                                    <div class="form-group col-sm-6">
                                                        <h2>Additional Affidavit</h2><br />
                                                        <div class="row" style="margin-bottom:22px;">
                                                            <div class="col-sm-12">
                                                                <label for="exampleInputPassword1">Affidavit Sweared by</label>
                                                                <select id="additional_affidavit_submitted_by" class="selectpicker form-control court-case-select" name="additional_affidavit_submitted_by" data-style="btn-info" autocomplete="off" required>
                                                                    <option value="{{ Crypt::encrypt('') }}">Select</option>
                                                                <?php
                                                                    foreach($court_cases_submitted_by as $values)
                                                                    {
                                                                ?>
                                                                        <option value="{{ Crypt::encrypt($values->id) }}"  <?php if( isset($court_cases_additional_affidavit[0]->affidavit_submitted_by) ) { if( $court_cases_additional_affidavit[0]->affidavit_submitted_by == $values->id ) { echo "selected"; } } ?> >{{ $values->submitted_by }}</option>
                                                                <?php
                                                                    }
                                                                ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div id="additional_affidavit_submitted_by_blocks" class="">
                                                                <?php
                                                                    if( isset($court_cases_additional_affidavit[0]->affidavit_submitted_by) ) {
                                                                        $options2 = '';
                                                                        if( $court_cases_additional_affidavit[0]->affidavit_submitted_by == 6 ) {

                                                                            $district_ids = json_decode($court_cases[0]->district_id);
                                                                            $size = sizeof($district_ids);
                                                                            $affidavit_blocks = DB::select('select * from court_cases_blocks where section_id = 3 AND section_table_id = ?', [$court_cases_additional_affidavit[0]->id]);

                                                                            $options2 = '<label>Blocks</label>
                                                                                        <select id="affidavit_comments_blocks" class="selectpicker form-control select-margin affidavit_blocks" name="blocks" data-style="btn-info" multiple autocomplete="off" required >';
                                                                            for($i=0;$i<$size;$i++) {
                                                                                $court_cases_district = DB::select('select * from districts where id = ? order by id asc', [$district_ids[$i]]);
                                                                                $court_cases_blocks = DB::select('select * from blocks where district_id = ? order by block_name asc', [$district_ids[$i]]);
                                                                                
                                                                                $options2 .= '<optgroup label="'.$court_cases_district[0]->district_name.'">';
                                                                                foreach($court_cases_blocks as $values) {
                                                                                    $p = 0;
                                                                                    if(!empty($affidavit_blocks)) {
                                                                                        foreach($affidavit_blocks as $blocks) {
                                                                                            if( $values->id == $blocks->block_id ) {
                                                                                                $options2 .= '<option value="'.Crypt::encrypt($values->id).'" selected >'.$values->block_name.'</option>';
                                                                                                $p=1;break;
                                                                                            }
                                                                                            else {
                                                                                                $p=0;
                                                                                            }
                                                                                        }
                                                                                        if( $p == 0 )
                                                                                            $options2 .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->block_name.'</option>';
                                                                                    }
                                                                                    else
                                                                                        $options2 .= '<option value="'.Crypt::encrypt($values->id).'" >'.$values->block_name.'</option>';
                                                                                }
                                                                                $options2 .= '</optgroup>';
                                                                            }
                                                                            $options2 .= '</select>';
                                                                        }
                                                                        else {
                                                                            $options2 .= '';
                                                                        }
                                                                        echo $options2;
                                                                    }
                                                                ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top:22px;">
                                                            <div class="col-md-12">
                                                                <label for="exampleInputEmail1">Date of Affidavit Sweared</label>
                                                                <input name="date_of_additional_affidavit_submitted" type="text" value="<?php if( isset($court_cases_additional_affidavit[0]->date_of_affidavit_submitted) ) { echo $court_cases_additional_affidavit[0]->date_of_affidavit_submitted; } ?>" class="form-control datepicker" data-zdp_readonly_element="true" required >
                                                            </div>
                                                        </div><br /><br />
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label for="exampleInputEmail1">Upload Document</label>
                                                                <input type="file" name="additionalaffidavitDocument" id="additionalaffidavitDocument" value="" class="form-control" required />
                                                                <div id="upload_additional_afidavit">
                                                                    <?php
                                                                        if( isset($court_cases_additional_affidavit[0]->document) )
                                                                        {
                                                                            if( isset($court_cases_additional_affidavit[0]->document) != "" )
                                                                            {
                                                                    ?>
                                                                            <a href="{{ url('admin/courtCases/viewCourtCaseAffidavit/'.Crypt::encrypt($court_cases_additional_affidavit[0]->id)) }}" target="_blank" class="btn btn-outline-default waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:145px;margin:5px 0px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View Document</a>
                                                                    <?php
                                                                            }
                                                                        }
                                                                    ?>
                                                                </div>
                                                                <b>.pdf files only (should be less than 2 MB)</b>
                                                            </div>
                                                        </div><br />
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <button type="submit" class="btn btn-primary aqua-gradient" style="float:left;font-weight:bold;">Save</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab_IO"><br /><br /><br />
                                            <h2 style="float:left;">Interim Order Information</h2>
                                            <button type="button" class="btn btn-primary blue-gradient" style="float:right;font-weight:bold;margin:15px 2px 5px 0px;" data-toggle="modal" data-target="#interimaddModal"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table class="table table-bordered table-striped table-hover" id="dataTable1">
                                                        <thead>
                                                            <tr class="bg-primary">
                                                                <th>Due Date of Compliance of Interim Order</th>
                                                                <th>Action to be taken by</th>
                                                                <th>Brief of Interim Order</th>
                                                                <th>Details of Action taken as per Interim Order</th>
                                                                <th style="width:85px;">Task</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="interim_table_body">
                                                        @foreach($court_cases_interim_order as $li)
                                                            <tr>
                                                                <td>
                                                                <?php
                                                                    echo $li->due_date_of_interim_order;
                                                                    if( $li->status == "active" ) {
                                                                        echo '<br /><span class="badge" style="background-color: green">In Effect</span>';
                                                                    }
                                                                ?>
                                                                </td>
                                                                <td>{{ $li->submitted_by }}</td>
                                                                <td>{{ $li->brief_of_interim_order }}</td>
                                                                <td>{{ $li->details_action_taken_as_per_interim_order }}</td>
                                                                <td>
                                                                    <a onClick="load_interim_update({{ $li->i_id }});" data-toggle="modal" data-target="#interimupdateModal" class="btn aqua-gradient waves-effect btn-lg" style="font-weight:bold;font-size:10pt;padding:5px 5px;width:87px;"><i class="fa fa-edit"></i>&nbsp;&nbsp;Update</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="interimupdateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document" style="width:755px;">
                                                    <div class="modal-content">
                                                        <form id="interim-update-form" class="action-update" action="{{ route('admin.courtCases.updateInterimOrder') }}" method="post">
                                                        <div class="modal-header aqua-gradient" style="height:75px;">
                                                            <div class="portlet-title">
                                                                <div class="caption caption-md">
                                                                    <center><h3 class="caption-subject font-blue-madison bold uppercase"><b>Update Interim Order Details</b></h3></center>
                                                                    <button type="button" class="btn-outline-danger" data-dismiss="modal" style="position:absolute;height:38px;width:35px;right:15px;top:19px;border-radius:2px;">
                                                                        <span aria-hidden="true" >&times;</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="interim_update_body" class="modal-body">

                                                        </div>
                                                        <div id="applicant_details_footer" class="modal-footer" style="padding:10px;">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal" style="float:left;color:white;;padding:0px 12px;">Close</button>
                                                                        <button type="submit" class="btn btn-primary aqua-gradient" style="float:right;font-weight:bold;">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="interimaddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document" style="width:755px;">
                                                    <div class="modal-content">
                                                        <form id="interim-form" class="action-update" action="{{ route('admin.courtCases.addInterimOrder') }}" method="post">
                                                        <div class="modal-header aqua-gradient" style="height:75px;">
                                                            <div class="portlet-title">
                                                                <div class="caption caption-md">
                                                                    <center><h3 class="caption-subject font-blue-madison bold uppercase"><b>Add Interim Order Details</b></h3></center>
                                                                    <button type="button" class="btn-outline-danger" data-dismiss="modal" style="position:absolute;height:38px;width:35px;right:15px;top:19px;border-radius:2px;">
                                                                        <span aria-hidden="true" >&times;</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="interim_details_body" class="modal-body">
                                                            <div class="form-group"> 
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="exampleInputEmail1">Due Date of Compliance of Interim Order</label><br>
                                                                        <input id="interim_due_date" name="due_date_of_interim_order" type="text" class="form-control datepicker" data-zdp_readonly_element="true" required />
                                                                        <!-- <input id="datepicker2" name="due_date_of_parawise_comments" type="text" class="form-control datepicker" data-zdp_readonly_element="true"> -->
                                                                    </div>
                                                                </div><br /><br />
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <label for="exampleInputPassword1">Action to be taken by</label>
                                                                        <select id="interim_submitted_by" class="selectpicker form-control select-margin court-case-select" name="action_to_be_taken_by" data-style="btn-info" autocomplete="off" required >
                                                                            <option value="{{ Crypt::encrypt('') }}">Select</option>
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
                                                                    <div class="col-sm-6">
                                                                        <div id="interim_submitted_by_blocks" class="select_districts_blocks"></div>
                                                                    </div>
                                                                </div>
                                                            </div><br />
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <label for="exampleInputEmail1">Brief of Interim order</label>
                                                                        <textarea class="form-control" name="brief_of_interim_order" rows="4" placeholder="Within 2000 Characters" required></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="applicant_details_footer" class="modal-footer" style="padding:10px;">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal" style="float:left;color:white;padding:0px 12px;">Close</button>
                                                                        <button type="submit" class="btn btn-primary aqua-gradient" style="float:right;font-weight:bold;">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab_I"><br /><br /><br />
                                            <h2 style="float:left;">Instruction</h2>
                                            <button type="button" class="btn btn-primary blue-gradient" style="float:right;font-weight:bold;margin:15px 2px 5px 0px;" data-toggle="modal" data-target="#instructionaddModal"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table class="table table-bordered table-striped table-hover" id="dataTable1">
                                                        <thead>
                                                            <tr class="bg-primary">
                                                                <th>Due Date of Submission of Instruction</th>
                                                                <th>To be Submitted by</th>
                                                                <th>Letter Number</th>
                                                                <th>Document</th>
                                                                <th>Submitted</th>
                                                                <th style="width:85px;">Task</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="instruction_table_body">
                                                        @foreach($court_cases_instruction as $li)
                                                            <tr>
                                                                <td>
                                                                <?php
                                                                    echo $li->due_date_of_instruction;
                                                                    if( $li->status == "active" ) {
                                                                        echo '<br /><span class="badge" style="background-color: green">In Effect</span>';
                                                                    }
                                                                ?>
                                                                </td>
                                                                <td>{{ $li->submitted_by }}</td>
                                                                <td>{{ $li->letter_number }}</td>
                                                                <td>
                                                                <?php
                                                                    if( isset($li->document) != "" ) {
                                                                ?>
                                                                        <a href="{{ url('admin/courtCases/viewCourtCaseInstruction/'.Crypt::encrypt($li->i_id)) }}" target="_blank"  class="btn btn-outline-default waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:73px;margin:5px 0px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View</a>
                                                                <?php
                                                                    }
                                                                    else
                                                                        echo "No Document";
                                                                ?>
                                                                </td>
                                                                <td>{{ $li->submitted }}</td>
                                                                <td>
                                                                    <a onClick="load_instruction_update({{ $li->i_id }});" data-toggle="modal" data-target="#instructionupdateModal" class="btn aqua-gradient waves-effect btn-lg" style="font-weight:bold;font-size:10pt;padding:5px 5px;width:87px;"><i class="fa fa-edit"></i>&nbsp;&nbsp;Update</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="instructionupdateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document" style="width:755px;">
                                                    <div class="modal-content">
                                                        <form id="instruction-form" class="action-update" action="{{ route('admin.courtCases.updateInstruction') }}" method="post">
                                                        <div class="modal-header aqua-gradient" style="height:75px;">
                                                            <div class="portlet-title">
                                                                <div class="caption caption-md">
                                                                    <center><h3 class="caption-subject font-blue-madison bold uppercase"><b>Update Instruction Details</b></h3></center>
                                                                    <button type="button" class="btn-outline-danger" data-dismiss="modal" style="position:absolute;height:38px;width:35px;right:15px;top:19px;border-radius:2px;">
                                                                        <span aria-hidden="true" >&times;</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="instruction_update_body" class="modal-body">

                                                        </div>
                                                        <div id="applicant_details_footer" class="modal-footer" style="padding:10px;">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal" style="float:left;color:white;;padding:0px 12px;">Close</button>
                                                                        <button type="submit" class="btn btn-primary aqua-gradient" style="float:right;font-weight:bold;">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="instructionaddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document" style="width:755px;">
                                                    <div class="modal-content">
                                                        <form id="instruction-update-form" class="action-update" action="{{ route('admin.courtCases.addInstruction') }}" method="post">
                                                            <div class="modal-header aqua-gradient" style="height:75px;">
                                                                <div class="portlet-title">
                                                                    <div class="caption caption-md">
                                                                        <center><h3 class="caption-subject font-blue-madison bold uppercase"><b>Add Instruction</b></h3></center>
                                                                        <button type="button" class="btn-outline-danger" data-dismiss="modal" style="position:absolute;height:38px;width:35px;right:15px;top:19px;border-radius:2px;">
                                                                            <span aria-hidden="true" >&times;</span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="instruction_details_body" class="modal-body">
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <label>Instruction to be Submitted By</label>
                                                                            <select id="instruction_submitted_by" class="selectpicker form-control select-margin court-case-select" name="instruction_submitted_by" data-style="btn-info" autocomplete="off" required >
                                                                                <option value="{{ Crypt::encrypt('') }}">Select</option>
                                                                                <?php
                                                                                    foreach($court_cases_submitted_by as $values)
                                                                                    {
                                                                                ?>
                                                                                        <option value="{{ Crypt::encrypt($values->id) }}">{{ $values->submitted_by }}</option>
                                                                                <?php
                                                                                    }
                                                                                ?>
                                                                            </select>
                                                                            <style>
                                                                                .bootstrap-select>.dropdown-toggle {
                                                                                    top: -0.85px;
                                                                                }
                                                                            </style>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div id="instruction_submitted_by_blocks" class="select_districts_blocks"></div>
                                                                        </div>
                                                                    </div><br /><br />
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label>Due Date of Submission of Instruction</label>
                                                                            <input id="due_date_of_instruction" name="due_date_of_instruction" type="text" value="" class="form-control datepicker" data-zdp_readonly_element="true" required >
                                                                        </div>  
                                                                        <div class="col-md-6">
                                                                            <label for="exampleInputEmail1">Letter Number </label>
                                                                            <input name="instruction_letter_number" type="text" value="" class="form-control" >
                                                                        </div>
                                                                    </div><br /><br />
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label>Upload Document</label>
                                                                            <input type="file" name="instruction_document" id="instructionDocument" value="" class="form-control" >
                                                                            <b>.pdf files only (should be less than 2 MB)</b>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label for="exampleInputEmail1">Submitted</label><br />
                                                                            <!-- ANIMATED CHECKBOXES -->
                                                                            <div class="checkbox-animated col-sm-6" style="width:155px;">
                                                                                <input id="checkbox_1" type="radio" class="checkbox" name="instruction_submitted" value="Yes" />
                                                                                <label for="checkbox_1">
                                                                                    <span class="check"></span>
                                                                                    <span class="box"></span>
                                                                                    YES 
                                                                                </label>
                                                                            </div>
                                                                            <div class="checkbox-animated col-sm-6" style="width:155px;">
                                                                                <input id="checkbox_2" type="radio" class="checkbox" name="instruction_submitted" value="No" />
                                                                                <label for="checkbox_2">
                                                                                    <span class="check"></span>
                                                                                    <span class="box"></span>
                                                                                    NO
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div><br /><br />
                                                                </div>
                                                            </div>
                                                            <div id="applicant_details_footer" class="modal-footer" style="padding:10px;">
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal" style="float:left;color:white;padding:0px 12px;">Close</button>
                                                                            <button type="submit" class="btn btn-primary aqua-gradient" style="float:right;font-weight:bold;">Save</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab_FO"><br /><br /><br />
                                            <form id="final-order-form" class="action-update" action="{{ route('admin.courtCases.addCourtCaseFinalOrder') }}" method="post">
                                            <h2>Final Order Information</h2><br />
                                            <div class="form-group"> 
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label for="exampleInputPassword1">Action to be taken by</label>
                                                        <select id="final_order_submitted_by" class="selectpicker form-control select-margin court-case-select" name="action_to_be_taken_by" data-style="btn-info" autocomplete="off" required >
                                                            <option value="{{ Crypt::encrypt('') }}">Select</option>
                                                            <?php
                                                                foreach($court_cases_submitted_by as $values)
                                                                {
                                                            ?>
                                                                    <option value="{{ Crypt::encrypt($values->id) }}" <?php if( isset($court_cases_final_order[0]->action_to_be_taken_by) ) { if( $court_cases_final_order[0]->action_to_be_taken_by == $values->id ) { echo "selected"; } } ?> >{{ $values->submitted_by }}</option>
                                                            <?php
                                                                }
                                                            ?>
                                                        </select>
                                                        <style>
                                                            .bootstrap-select>.dropdown-toggle {
                                                                top: -0.85px;
                                                            }
                                                        </style>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div id="final_order_submitted_by_blocks" class="">
                                                            <?php
                                                                if( isset($court_cases_final_order[0]->action_to_be_taken_by) ) {
                                                                    $options = '';
                                                                    if( $court_cases_final_order[0]->action_to_be_taken_by == 6 ) {
                                                                        $district_ids = json_decode($court_cases[0]->district_id);
                                                                        $size = sizeof($district_ids);
                                                                        $final_order_blocks = DB::select('select * from court_cases_blocks where section_id = 6 AND section_table_id = ?', [$court_cases_final_order[0]->id]);

                                                                        $options = '<label>Blocks</label>
                                                                                    <select id="final_order_blocks" class="selectpicker form-control select-margin affidavit_blocks" name="blocks" data-style="btn-info" multiple autocomplete="off" required >';
                                                                        for($i=0;$i<$size;$i++) {
                                                                            $court_cases_district = DB::select('select * from districts where id = ? order by id asc', [$district_ids[$i]]);
                                                                            $court_cases_blocks = DB::select('select * from blocks where district_id = ? order by block_name asc', [$district_ids[$i]]);

                                                                            $options .= '<optgroup label="'.$court_cases_district[0]->district_name.'">';
                                                                            foreach($court_cases_blocks as $values) {
                                                                                $p = 0;
                                                                                if(!empty($final_order_blocks)) {
                                                                                    foreach($final_order_blocks as $blocks) {
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
                                                                echo $options;
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><br />
                                            <div class="form-group"> 
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="exampleInputEmail1">Due Date of Compliance of Final order</label>
                                                        <input name="due_date_of_final_order" type="text" value="<?php if( isset($court_cases_final_order[0]->due_date_of_final_order) ) { echo $court_cases_final_order[0]->due_date_of_final_order; } ?>" class="form-control datepicker" data-zdp_readonly_element="true" required />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="exampleInputEmail1">Date of receipt of Final order</label>
                                                        <input name="date_of_receipt_of_final_order" type="text" value="<?php if( isset($court_cases_final_order[0]->date_of_receipt_of_final_order) ) { echo $court_cases_final_order[0]->date_of_receipt_of_final_order; } ?>" class="form-control datepicker" data-zdp_readonly_element="true" required />
                                                    </div>
                                                </div>
                                            </div><br />
                                            <div class="form-group"> 
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="exampleInputEmail1">Details of Final Order</label>
                                                        <textarea class="form-control" name="details_of_final_order" rows="4" required><?php if( isset($court_cases_final_order[0]->details_of_final_order) ) { echo $court_cases_final_order[0]->details_of_final_order; } ?></textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="exampleInputEmail1">Details of Action taken as per Final Order</label>
                                                        <textarea class="form-control" name="details_of_action_taken_as_per_financial_order" rows="4"><?php if( isset($court_cases_final_order[0]->details_of_action_taken_as_per_financial_order) ) { echo $court_cases_final_order[0]->details_of_action_taken_as_per_financial_order; } ?></textarea>
                                                    </div>
                                                </div>
                                            </div><br />
                                            <div class="form-group"> 
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="exampleInputEmail1">Appeal Petition</label><br />
                                                        <!-- ANIMATED CHECKBOXES -->
                                                        <div class="checkbox-animated col-sm-6" style="width:155px;">
                                                            <input id="checkbox_animated_1" type="radio" class="checkbox" name="final_order_option" value="Yes" <?php if( isset($court_cases_final_order[0]->details_of_appeal_petition) ) { echo 'checked="checked"'; } ?> required>
                                                            <label for="checkbox_animated_1">
                                                                <span class="check"></span>
                                                                <span class="box"></span>
                                                                YES
                                                            </label>
                                                        </div>
                                                        <div class="checkbox-animated col-sm-6" style="width:155px;">
                                                            <input id="checkbox_animated_2" type="radio" class="checkbox" name="final_order_option" value="No" <?php if( !isset($court_cases_final_order[0]->details_of_appeal_petition) ) { echo 'checked="checked"'; } ?> required>
                                                            <label for="checkbox_animated_2">
                                                                <span class="check"></span>
                                                                <span class="box"></span>
                                                                NO
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="exampleInputEmail1">Details of Appeal Petition</label>
                                                        <textarea id="details_of_appeal_petition" class="form-control" name="details_of_appeal_petition" rows="4" <?php if( !isset($court_cases_final_order[0]->details_of_appeal_petition) ) { echo 'disabled'; }  ?> required ><?php if( isset($court_cases_final_order[0]->details_of_appeal_petition) ) { echo $court_cases_final_order[0]->details_of_appeal_petition; } ?></textarea>
                                                    </div>
                                                </div><br />
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-primary aqua-gradient" style="float:left;font-weight:bold;">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane" id="tab_SO"><br /><br /><br />
                                            <h2 style="float:left;">Speaking Order</h2>
                                            <button type="button" class="btn btn-primary blue-gradient" style="float:right;font-weight:bold;margin:15px 2px 5px 0px;" data-toggle="modal" data-target="#speakingorderaddModal"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table class="table table-bordered table-striped table-hover" id="dataTable1">
                                                        <thead>
                                                            <tr class="bg-primary">
                                                                <th>Date of Speaking Order to be Passed as per Court Order</th>
                                                                <th>Due Date of Issue of Speaking Order</th>
                                                                <th>Speaking Order to be Passed By</th>
                                                                <th>Letter Number</th>
                                                                <th>Document</th>
                                                                <th>Passed</th>
                                                                <th style="width:85px;">Task</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="speaking_order_table_body">
                                                        @foreach($court_cases_speaking_order as $li)
                                                            <tr>
                                                                <td>
                                                                <?php
                                                                    echo $li->date_of_speaking_order;
                                                                    if( $li->status == "active" ) {
                                                                        echo '<br /><span class="badge" style="background-color: green">In Effect</span>';
                                                                    }
                                                                ?>
                                                                </td>
                                                                <td>{{ $li->due_date_of_speaking_order }} Days Prior</td>
                                                                <td>{{ $li->submitted_by }}</td>
                                                                <td>{{ $li->letter_number }}</td>
                                                                <td>
                                                                <?php
                                                                    if( isset($li->document) != "" ) {
                                                                ?>
                                                                        <a href="{{ url('admin/courtCases/viewCourtCaseSpeakingOrder/'.Crypt::encrypt($li->i_id)) }}" target="_blank"  class="btn btn-outline-default waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:75px;margin:5px 0px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View</a>
                                                                <?php
                                                                    }
                                                                    else
                                                                        echo "No Document";
                                                                ?>
                                                                </td>
                                                                <td>{{ $li->submitted }}</td>
                                                                <td>
                                                                    <a onClick="load_speaking_order_update({{ $li->i_id }});" data-toggle="modal" data-target="#speakingorderupdateModal" class="btn aqua-gradient waves-effect btn-lg" style="font-weight:bold;font-size:10pt;padding:5px 5px;width:87px;"><i class="fa fa-edit"></i>&nbsp;&nbsp;Update</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="speakingorderupdateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document" style="width:755px;">
                                                    <div class="modal-content">
                                                        <form id="speaking-order-update-form" class="action-update" action="{{ route('admin.courtCases.updateSpeakingOrder') }}" method="post">
                                                        <div class="modal-header aqua-gradient" style="height:75px;">
                                                            <div class="portlet-title">
                                                                <div class="caption caption-md">
                                                                    <center><h3 class="caption-subject font-blue-madison bold uppercase"><b>Update Speaking Order Details</b></h3></center>
                                                                    <button type="button" class="btn-outline-danger" data-dismiss="modal" style="position:absolute;height:38px;width:35px;right:15px;top:19px;border-radius:2px;">
                                                                        <span aria-hidden="true" >&times;</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="speaking_order_update_body" class="modal-body">

                                                        </div>
                                                        <div id="applicant_details_footer" class="modal-footer" style="padding:10px;">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal" style="float:left;color:white;;padding:0px 12px;">Close</button>
                                                                        <button type="submit" class="btn btn-primary aqua-gradient" style="float:right;font-weight:bold;">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="speakingorderaddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document" style="width:755px;">
                                                    <div class="modal-content">
                                                        <form id="speaking-order-form" class="action-update" action="{{ route('admin.courtCases.addSpeakingOrder') }}" method="post">
                                                            <div class="modal-header aqua-gradient" style="height:75px;">
                                                                <div class="portlet-title">
                                                                    <div class="caption caption-md">
                                                                        <center><h3 class="caption-subject font-blue-madison bold uppercase"><b>Add Speaking Order</b></h3></center>
                                                                        <button type="button" class="btn-outline-danger" data-dismiss="modal" style="position:absolute;height:38px;width:35px;right:15px;top:19px;border-radius:2px;">
                                                                            <span aria-hidden="true" >&times;</span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="speaking_order_details_body" class="modal-body">
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <label for="exampleInputPassword1">Speaking Order to be Passed By</label>
                                                                            <select id="speaking_order_submitted_by" class="selectpicker form-control select-margin court-case-select" name="speaking_order_submitted_by" data-style="btn-info" autocomplete="off" required >
                                                                                <option value="{{ Crypt::encrypt('') }}">Select</option>
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
                                                                        <div class="col-sm-6">
                                                                            <div id="speaking_order_submitted_by_blocks" class="select_districts_blocks"></div>
                                                                        </div>
                                                                    </div><br /><br />
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label for="">Date of Speaking Order to be Passed</label>
                                                                            <input name="date_of_speaking_order" type="text" value="" class="form-control datepicker" data-zdp_readonly_element="true" required >
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label for="">Due Date of Issue of Speaking Order</label>
                                                                            <select id="due_date_of_speaking_order" class="selectpicker form-control select-margin" name="due_date_of_speaking_order" data-style="btn-info" autocomplete="off" required >
                                                                                <option value="">Select</option>
                                                                                <option value="15" <?php if( isset($court_cases_speaking_order[0]->due_date_of_speaking_order) ) { if( $court_cases_speaking_order[0]->due_date_of_speaking_order == 15 ) { echo "selected"; } } ?>>15 Days</option>
                                                                                <option value="30" <?php if( isset($court_cases_speaking_order[0]->due_date_of_speaking_order) ) { if( $court_cases_speaking_order[0]->due_date_of_speaking_order == 30 ) { echo "selected"; } } ?>>30 Days</option>
                                                                                <option value="60" <?php if( isset($court_cases_speaking_order[0]->due_date_of_speaking_order) ) { if( $court_cases_speaking_order[0]->due_date_of_speaking_order == 60 ) { echo "selected"; } } ?>>60 Days</option>
                                                                                <option value="90" <?php if( isset($court_cases_speaking_order[0]->due_date_of_speaking_order) ) { if( $court_cases_speaking_order[0]->due_date_of_speaking_order == 90 ) { echo "selected"; } } ?>>90 Days</option>
                                                                            </select>
                                                                        </div>
                                                                    </div><br /><br />
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label for="exampleInputEmail1">Letter Number </label>
                                                                            <input name="speaking_order_letter_number" type="text" value="" class="form-control" >
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Upload Document</label>
                                                                            <input type="file" name="speaking_order_document" id="speakingorderDocument" value="" class="form-control" >
                                                                            <b>.pdf files only (should be less than 2 MB)</b>
                                                                        </div>
                                                                    </div><br /><br />
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label for="exampleInputEmail1">Submitted</label><br />
                                                                            <!-- ANIMATED CHECKBOXES -->
                                                                            <div class="checkbox-animated col-sm-6" style="width:155px;">
                                                                                <input id="checkbox_21" type="radio" class="checkbox" name="speaking_order_submitted" value="Yes" />
                                                                                <label for="checkbox_21">
                                                                                    <span class="check"></span>
                                                                                    <span class="box"></span>
                                                                                    YES 
                                                                                </label>
                                                                            </div>
                                                                            <div class="checkbox-animated col-sm-6" style="width:155px;">
                                                                                <input id="checkbox_22" type="radio" class="checkbox" name="speaking_order_submitted" value="No" />
                                                                                <label for="checkbox_22">
                                                                                    <span class="check"></span>
                                                                                    <span class="box"></span>
                                                                                    NO
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="applicant_details_footer" class="modal-footer" style="padding:10px;">
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal" style="float:left;color:white;padding:0px 12px;">Close</button>
                                                                            <button type="submit" class="btn btn-primary aqua-gradient" style="float:right;font-weight:bold;">Save</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="tab-pane" id="tab_5"><br /><br />
                                            <h3>Action Taken Report</h3><br />
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label for="exampleInputPassword1">Submitted By</label>
                                                        <input type="text" id="" name="file_number" class="form-control mb-4" value="" placeholder="Enter ATR submitted by" maxlength="100">
                                                    </div>
                                                </div><br /><br />
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="exampleInputEmail1">Date of ATR</label>
                                                        <input id="datepicker3" name="date_of_affidavit_submitted" type="text" class="form-control datepicker" data-zdp_readonly_element="true">
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
            //$('.my-select').selectpicker();

            $('.datepicker').Zebra_DatePicker({
                format: 'd-m-Y'
            });

            $('input:radio[name="final_order_option"]').change(
                function(){
                //alert();
                if ($(this).is(':checked') && $(this).val() == 'Yes') {
                    document.getElementById("details_of_appeal_petition").disabled=false;
                }
                if ($(this).is(':checked') && $(this).val() == 'No') {
                    document.getElementById("details_of_appeal_petition").disabled=true;
                }
            });
            $('.modal').on('hidden.bs.modal', function () {
                $('.action-update').trigger('reset');
                $('.select_districts_blocks').html('');
                $('#parawise_comments_submitted_by').val('default');
                $('#parawise_comments_submitted_by').selectpicker('refresh');
                $('#interim_submitted_by').val('default');
                $('#interim_submitted_by').selectpicker('refresh');
                $('#instruction_submitted_by').val('default');
                $('#instruction_submitted_by').selectpicker('refresh');
                $('#speaking_order_submitted_by').val('default');
                $('#speaking_order_submitted_by').selectpicker('refresh');
            });
            $('#primary-update').on('submit', function(e){
                e.preventDefault();
                var formData = new FormData(this);
                formData.append('court_case_id', '{{ Crypt::encrypt($court_cases[0]->id) }}');
                formData.append('districts', $("#district").val() );

                if($('#primary-update').valid()){
                    $('.page-loader-wrapper').fadeIn();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "post",
                        url: '{{route('admin.courtCases.updateCourtCasePrimary')}}',
                        dataType: "json",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data) {
                            if (data.msgType == "Same") {
                                swal("No Modification", "Same Data Uploaded", "info");
                            }
                            else if (data.msgType == "Updated") {
                                swal("Update Successfull", "Successfully Updated the Court Case Details", "success");
                                d_id = data.districts;
                            }
                            else {
                                if(data.msg=="VE") {
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
                            $('.page-loader-wrapper').fadeOut();
                        }
                    });
                }
            });

            var d_id = '{{ Crypt::encrypt($court_cases[0]->district_id) }}';
            $(document).on('change', '.court-case-select', function() {
                var id = $(this).val();
                var current_id = $(this).attr('id');
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    url: '{{ route('admin.courtCases.loadDistrictBlocks') }}',
                    data: '&id='+id+'&d_id='+d_id,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        $('#'+current_id+'_blocks').html(data);
                        $('.selectpicker').selectpicker();
                        $('.page-loader-wrapper').fadeOut();
                    }
                });
                return false;
            });

            $('.action-update').on('submit', function(e){
                e.preventDefault();

                var formData = new FormData(this);
                formData.append('court_case_id', '{{ Crypt::encrypt($court_cases[0]->id) }}');                //e.unbind();
                var cc_id = '{{ Crypt::encrypt($court_cases[0]->id) }}';
                formData.append('districts', $("#district").val() );

                var current_form_id = $(this).closest("form").attr('id');
                var val = $("#"+current_form_id+" :input[name='blocks']");
                formData.append( 'blocks', val.val() );

                if($(this).valid()) {
                    $('.page-loader-wrapper').fadeIn();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: $(this).attr('method'),
                        url: $(this).attr('action'),
                        dataType: "json",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data) {
                            if( data.msgType == "Parawise" ) {
                                if(data.info == "exceeded") {
                                    swal("Limit Exceeded", data.msg, "info");
                                    $('#parawisecommentsaddModal').modal('hide');
                                    return;
                                }
                                if(data.info == "update") {
                                    reload_parawise_details(cc_id);
                                    swal("Update Successfull", data.msg, "success");
                                    $('#parawisecommentsupdateModal').modal('hide');
                                    return;
                                }
                                else {
                                    reload_parawise_details(cc_id);
                                    swal("Success", data.msg, "success");
                                }
                                $('#parawise-comments-form').trigger("reset");
                                $('#due_date_of_parawise_comments').val("");
                                $("#parawise_comments_submitted_by").val('default');
                                $("#parawise_comments_submitted_by").selectpicker("refresh");
                                $("#pc_select").html("");

                                $('#parawisecommentsaddModal').modal('hide');
                                return;
                            }

                            if( data.msgType == "Interim") {
                                if(data.info == "exceeded") {
                                    swal("Limit Exceeded", data.msg, "info");
                                    $('#interimaddModal').modal('hide');
                                    return;
                                }
                                if(data.info == "update") {
                                    reload_interim_details(cc_id);
                                    swal("Update Successfull", data.msg, "success");
                                    $('#interimupdateModal').modal('hide');
                                    return;
                                }
                                else {
                                    reload_interim_details(cc_id);
                                    swal("Success", data.msg, "success");
                                }
                                $('#interim-form').trigger("reset");
                                $('#interim_due_date').val("");
                                $("#interim_due_date_select").val('default');
                                $("#interim_due_date_select").selectpicker("refresh");
                                $('#interimaddModal').modal('hide');
                                return;
                            }

                            if( data.msgType == "Instruction") {
                                if(data.info == "exceeded") {
                                    swal("Limit Exceeded", data.msg, "info");
                                    $('#instructionaddModal').modal('hide');
                                    return;
                                }
                                if(data.info == "update") {
                                    reload_instruction_details(cc_id);
                                    swal("Update Successfull", data.msg, "success");
                                    $('#instructionupdateModal').modal('hide');
                                    return;
                                }
                                else {
                                    reload_instruction_details(cc_id);
                                    swal("Success", data.msg, "success");
                                }
                                $('#instruction-form').trigger("reset");
                                $('#due_date_of_instruction').val("");
                                $("#instruction_submitted_by").val('default');
                                $("#instruction_submitted_by").selectpicker("refresh");
                                $('#instructionaddModal').modal('hide');
                                return;
                            }
                            
                            if( data.msgType == "Speaking") {
                                if(data.info == "exceeded") {
                                    swal("Limit Exceeded", data.msg, "info");
                                    $('#speakingorderaddModal').modal('hide');
                                    return;
                                }
                                if(data.info == "update") {
                                    reload_speaking_order_details(cc_id);
                                    swal("Update Successfull", data.msg, "success");
                                    $('#speakingorderupdateModal').modal('hide');
                                    return;
                                }
                                else {
                                    reload_speaking_order_details(cc_id);
                                    swal("Success", data.msg, "success");
                                }
                                $('#speaking-order-form').trigger("reset");
                                $('#date_of_speaking_order').val("");
                                $("#due_date_of_speaking_order").val('default');
                                $("#due_date_of_speaking_order").selectpicker("refresh");
                                $("#speaking_order_submitted_by").val('default');
                                $("#speaking_order_submitted_by").selectpicker("refresh");
                                $('#speakingorderaddModal').modal('hide');
                                return;
                            }

                            if (data.msgType == true) {
                                if(data.msgFrom == "Affidavit") {
                                    $('#upload_affidavit').html('<a href="{{ route("admin.courtCases.viewCourtCaseAffidavit", '') }}/'+data.Affidavit_id+'" target="_blank"  class="btn btn-outline-default waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:3px 5px;width:135px;margin:5px 0px;" title="Download Document">View Document</a>');
                                }
                                else if(data.msgFrom == "Additional Affidavit") {
                                    $('#upload_additional_afidavit').html('<a href="{{ route("admin.courtCases.viewCourtCaseAffidavit", '') }}/'+data.Affidavit_id+'" target="_blank"  class="btn btn-outline-default waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:3px 5px;width:135px;margin:5px 0px;" title="Download Document">View Document</a>');
                                }
                                swal("Success", data.msg, "success");
                            }
                            else if (data.msgType == false) {
                                swal("Error", data.msg, "error");
                                //return false;
                            }
                            else if (data.msgType == "Updated") {
                                if(data.msgFrom == "Affidavit") {
                                    $('#upload_affidavit').html('<a href="{{ route("admin.courtCases.viewCourtCaseAffidavit", '') }}/'+data.Affidavit_id+'" target="_blank"  class="btn btn-outline-default waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:3px 5px;width:135px;margin:5px 0px;" title="Download Document">View Document</a>');
                                }
                                else if(data.msgFrom == "Additional Affidavit") {
                                    $('#upload_additional_afidavit').html('<a href="{{ route("admin.courtCases.viewCourtCaseAffidavit", '') }}/'+data.Affidavit_id+'" target="_blank"  class="btn btn-outline-default waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:3px 5px;width:135px;margin:5px 0px;" title="Download Document">View Document</a>');
                                }
                                swal("Update Successfull", data.msg, "success");
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
                            swal("Ooops", 'Cannot Submit Details due to some problem', 'error');return;
                            callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                        },
                        complete: function (data) {
                            $('.page-loader-wrapper').fadeOut();
                        }
                    });
                }
            });
            function reload_parawise_details(id)
            {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'get',
                    url: '{{ route('admin.courtCases.refreshParawiseComments') }}',
                    data: '&id='+id,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        $('#parawise_comments_table_body').html(data);
                    }
                });
            }
            function reload_interim_details(id)
            {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'get',
                    url: '{{ route('admin.courtCases.refreshInterimOrder') }}',
                    data: '&id='+id,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        $('#interim_table_body').html(data);
                    }
                });
            }
            function reload_instruction_details(id)
            {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'get',
                    url: '{{ route('admin.courtCases.refreshInstruction') }}',
                    data: '&id='+id,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        $('#instruction_table_body').html(data);
                    }
                });
            }
            function reload_speaking_order_details(id)
            {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'get',
                    url: '{{ route('admin.courtCases.refreshSpeakingOrder') }}',
                    data: '&id='+id,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        
                        $('#speaking_order_table_body').html(data);
                    }
                });
            }
        });
        function load_parawise_comments_update(id)
        {
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'get',
                url: '{{ route('admin.courtCases.loadParawiseComments') }}',
                data: '&id='+id,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    $('#parawise_comments_update_body').html(data);
                    $('.selectpicker').selectpicker();
                    $('.page-loader-wrapper').fadeOut();
                }
            });
            return false;
        }
        function load_interim_update(id)
        {
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'get',
                    url: '{{ route('admin.courtCases.loadInterimOrder') }}',
                    data: '&id='+id,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        $('#interim_update_body').html(data);
                        $('.selectpicker').selectpicker();
                        $('.page-loader-wrapper').fadeOut();
                    }
                });
                return false;
        }
        function load_instruction_update(id)
        {
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'get',
                url: '{{ route('admin.courtCases.loadInstruction') }}',
                data: '&id='+id,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    $('#instruction_update_body').html(data);
                    $('.selectpicker').selectpicker();
                    $('.page-loader-wrapper').fadeOut();
                }
            });
            return false;
        }
        function load_speaking_order_update(id)
        {
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'get',
                    url: '{{ route('admin.courtCases.loadSpeakingOrder') }}',
                    data: '&id='+id,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        $('#speaking_order_update_body').html(data);
                        $('.selectpicker').selectpicker();
                        $('.page-loader-wrapper').fadeOut();
                    }
                });
                return false;
        }
        jQuery(document).ready(function ($) {
            $('#tabs').tab();
        });
       // $('button').addClass('btn-primary').text('Switch to Orange Tab');
        // $('button').click(function(){
        //     $('#tabs a[href=#tab_2]').tab('show');
        // });
    </script>
@endsection