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

    <!--/**************************************Timeline***********************************/-->
    <link rel="stylesheet" type="text/css" href="{{ asset('mdas_assets/VerticalTimeline/css/default.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('mdas_assets/VerticalTimeline/css/component.css') }}" />
	<script src="{{ asset('mdas_assets/VerticalTimeline/js/modernizr.custom.js') }}"></script>
    <!-- /**************************************Timeline***********************************/ -->

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
    .sections>h2, .main>h2 {
        font-size:18.5pt;
        font-weight:bold;
        margin-top:0;
        color:#367fed;
    }
    .content-text {
        font-weight:bold;
        font-size:15pt;
        color:#4a4f5b;
    }
    label {
        font-size:12pt;
    }
    .t-label {
        font-size:12pt;
        color:#444;
    }
    table th, table td {
        font-size: 10pt;
    }
    table.dataTable thead th, table.dataTable thead td {
        border-bottom: 1px solid #8f8f8f;
    }
    .table-bordered>thead>tr>th, .table-bordered>tbody>tr>td {
        background-color:white;
        font-size: 12pt;
        font-weight:bold;
        font-family:'Roboto';
        color:#727272;
        vertical-align: middle;
    }
    .table-bordered>thead>tr>th {
        background-color:#3a9fff;
        color:white;
    }






    body {
        font-size: '';
        font-family: 'Open Sans', sans-serif;
    }
    .main {
        font-family: 'Lato', Calibri, Arial, sans-serif;
        color: #47a3da;
    }
    .f-strip {
        font-size: 0.8rem;
    }
    .cbp_tmlabel h2 {
        color:#444;
    }
    .cbp_tmtimeline li {
        margin-top:75px;
    }
    .cbp_tmtimeline > li .cbp_tmtime span:first-child {
        font-size: 1.25em;
    }


    .block-back {
        webkit-box-shadow: 1px 1px 4px 1px rgba(209,209,209,1);
        -moz-box-shadow: 1px 1px 4px 1px rgba(209,209,209,1);
        box-shadow:  1px 1px 4px 1px rgba(209,209,209,1);
        background: #f6f6f6;
    }
    .row.make-columns {
        -moz-column-width: 14em;
        -webkit-column-width: 14em;
        -moz-column-gap: 0.1em;
        -webkit-column-gap:0.1em; 
    }
    .row.make-columns > div {
        display: block;
        padding:  .1rem;
        width:  100%; 
    }
    .panel {
        display: inline-block;
        height: 100%;
        width:  100%; 
    }
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
                        <i class="fa fa-gavel" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;COURT CASE DETAILS
                    </h1>
                </center>
                    <div class="sections">
                        <h2>Primary Information</h2><br />
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="exampleInputPassword1">Case Type</label>    
                                    <?php
                                        foreach($court_cases_type as $values)
                                        {
                                            if( isset($court_cases[0]->case_type_id) ) {
                                                if( $values->id == $court_cases[0]->case_type_id ) {
                                                    echo '<br /><label class="content-text">'.$values->court_case_type.'</label>';
                                                }
                                             }
                                        }
                                    ?>
                                </div>
                                <div class="col-sm-6">
                                    <label for="exampleInputPassword1">District(s)</label>
                                    <br /><label class="content-text">
                                    <?php
                                        $first_item = "NOT COVERED";
                                        foreach($districts as $values)
                                        {
                                            $district_ids = json_decode($court_cases[0]->district_id);
                                            for($i=0;$i<sizeof($district_ids);$i++)
                                            {
                                                if( $first_item == "NOT COVERED" ) {
                                                    $first_item = "COVERED";
                                                    if( $values->id == $district_ids[$i] ) {
                                                        echo $values->district_name;
                                                    }
                                                }
                                                else {
                                                    if( $values->id == $district_ids[$i] ) {
                                                        echo ', '.$values->district_name;
                                                    }
                                                }
                                            }
                                            $first_item = "NOT COVERED";
                                            // if( isset($court_cases[0]->district_id) ) {
                                            //     if( $values->id == $court_cases[0]->district_id ) {
                                            //         echo '<br /><label class="content-text">'.$values->district_name.'</label>';
                                            //     }
                                            // }
                                        }
                                    ?>
                                    </label>
                                </div>
                            </div>
                        </div><br />
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">File Number</label>
                                    <br /><label class="content-text"><?php if( isset($court_cases[0]->file_number) ) { echo $court_cases[0]->file_number; } ?></label>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Case Number</label>
                                    <br /><label class="content-text"><?php if( isset($court_cases[0]->case_number) ) { echo $court_cases[0]->case_number; } ?></label>
                                </div>
                            </div>
                        </div><br />
                        <div class="form-group"> 
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Name of the Petitioner</label>
                                    <br /><label class="content-text"><?php if( isset($court_cases[0]->name_of_petitioner) ) { echo $court_cases[0]->name_of_petitioner; } ?></label>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Date of Receipt of WP(C)/Notice </label>
                                    <br /><label class="content-text"><?php if( isset($court_cases[0]->date_of_receipt_of_wpc_notice) ) { echo $court_cases[0]->date_of_receipt_of_wpc_notice; } ?></label>
                                </div>
                            </div>
                        </div><br />
                        <div class="form-group"> 
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="exampleInputPassword1">Nature of Case</label>
                                    <?php
                                        foreach($court_cases_nature as $values)
                                        {
                                            if( isset($court_cases[0]->nature_of_case) ) {
                                                if( $values->id == $court_cases[0]->nature_of_case ) {
                                                    echo '<br /><label class="content-text">'.$values->court_case_nature.'</label>';
                                                }
                                             }
                                        }
                                    ?>
                                </div>
                                <div class="col-sm-6">
                                    <label for="exampleInputPassword1">Case Status</label>
                                    <?php
                                        foreach($court_cases_status as $values)
                                        {
                                            if( isset($court_cases[0]->case_status_id) ) {
                                                if( $values->id == $court_cases[0]->case_status_id ) {
                                                    echo '<br /><label class="content-text">'.$values->court_case_status.'</label>';
                                                }
                                             }
                                        }
                                    ?>
                                </div>
                            </div>
                        </div><br />
                        <div class="form-group"> 
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Subject Matter of the Case</label>
                                    <br /><label class="content-text"><?php if( isset($court_cases[0]->subject_matter_of_case) ) { echo $court_cases[0]->subject_matter_of_case; } ?></label>
                                </div>
                                <div class="col-sm-6">
                                    <label for="exampleInputPassword1">Case Under</label>
                                    <?php
                                        foreach($court_cases_under as $values)
                                        {
                                            if( isset($court_cases[0]->case_under) ) {
                                                if( $values->id == $court_cases[0]->case_under ) {
                                                    echo '<br /><label class="content-text">'.$values->under.'</label>';
                                                }
                                             }
                                        }
                                    ?>
                                </div>
                            </div>
                        </div><br />
                        <div class="form-group"> 
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="exampleInputEmail1">REMARKS</label>
                                    <br /><label class="content-text"><?php if( isset($court_cases[0]->remarks) ) { echo $court_cases[0]->remarks; } ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                    <div class="main">
                        <center><h2 style="font-weight:bold;position:relative;margin-top:105px;">Action</h2></center>
                        <ul class="cbp_tmtimeline">
                            <li>
                                <?php
                                    // if( isset($court_cases_parawise_comments[0]->due_date_of_parawise_comments) ) {
                                    //     $temp = $court_cases_parawise_comments[0]->due_date_of_parawise_comments;
                                    // }
                                    // else
                                    //     $temp = "N/A";
                                    $size_of_array = sizeof($court_cases_parawise_comments);
                                    if($size_of_array > 0) {
                                        // $i = $size_of_array - 1;
                                        // if( isset($court_cases_interim_order[$i]->due_date_of_interim_order) ) {
                                        //     $temp = $court_cases_interim_order[$i]->due_date_of_interim_order;
                                        // }
                                        // else
                                        //     $temp = "N/A";
                                        $temp = $size_of_array;
                                    }
                                    else
                                        $temp = "N/A";
                                ?>
                                <time class="cbp_tmtime"><span>Comments Requested</span> <span><?php echo $temp; ?></span></time>
                                <div class="cbp_tmicon cbp_tmicon-phone"></div>
                                <div class="cbp_tmlabel">
                                    <h2>Parawise Comment</h2>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-bordered table-striped table-hover" id="dataTable1">
                                                <thead>
                                                    <tr class="bg-primary">
                                                        <th width="15%">Due Date of Submission of Parawise Comments</th>
                                                        <th width="20%">To be Submitted by</th>
                                                        <th width="20%">Letter Number</th>
                                                        <th width="10%">Document</th>
                                                        <th width="8%">Submitted</th>
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
                                                        ?></td>
                                                        <td>{{ $li->submitted_by }}
                                                        <?php
                                                            if( $li->id == 6 ) {
                                                        ?>
                                                                <button onClick="javascript:viewBlocks('{{ Crypt::encrypt(1) }}', '{{ Crypt::encrypt($li->i_id) }}', 'Parawise to be Submitted by following Block(s)');" data-toggle="modal" data-target="#viewBlockModal"   class="btn btn-outline-primary waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:135px;margin:5px 0px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View Block(s)</button>
                                                        <?php
                                                            }
                                                        ?>
                                                        </td>
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
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <?php
                                    if( isset($court_cases_additional_affidavit[0]->date_of_affidavit_submitted) ) {
                                        $temp = $court_cases_additional_affidavit[0]->date_of_affidavit_submitted;
                                    }
                                    else if( isset($court_cases_affidavit[0]->date_of_affidavit_submitted) ) {
                                        $temp = $court_cases_affidavit[0]->date_of_affidavit_submitted;
                                    }
                                    else
                                        $temp = "N/A";
                                ?>
                                <time class="cbp_tmtime"><span>Due Date</span> <span><?php echo $temp; ?></span></time>
                                <div class="cbp_tmicon cbp_tmicon-phone"></div>
                                <div class="cbp_tmlabel">
                                    <h2>Affidavit</h2>
                                    <p>
                                        <label class="t-label">Affidavit Sweared by</label>
                                        <?php
                                            foreach($court_cases_submitted_by as $values)
                                            {
                                                if( isset($court_cases_affidavit[0]->affidavit_submitted_by) ) {
                                                    if( $values->id == $court_cases_affidavit[0]->affidavit_submitted_by ) {
                                                        echo '<br /><label class="content-text">'.$values->submitted_by.'</label>';
                                                        break;
                                                    }
                                                }
                                            }
                                            if( isset($court_cases_affidavit[0]->affidavit_submitted_by) ) {
                                                if( $court_cases_affidavit[0]->affidavit_submitted_by == 6 ) {
                                        ?>
                                                &nbsp;&nbsp;&nbsp;&nbsp;<button onClick="javascript:viewBlocks('{{ Crypt::encrypt(2) }}', '{{ Crypt::encrypt($court_cases_affidavit[0]->id) }}', 'Affidavit Sweared by following Block(s)');" data-toggle="modal" data-target="#viewBlockModal"   class="btn btn-outline-primary waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:135px;margin:5px 0px;top:-4px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View Block(s)</button>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </p>
                                    <p>
                                    <?php
                                        if( isset($court_cases_additional_affidavit[0]->date_of_affidavit_submitted) ) {
                                            if( isset($court_cases_affidavit[0]->date_of_affidavit_submitted) ) {
                                    ?>
                                            <label class="t-label">Date of Affidavit Sweared</label>
                                    <?php
                                            echo '<br /><label class="content-text">'.$court_cases_affidavit[0]->date_of_affidavit_submitted.'</label>';
                                            }
                                        }
                                    ?>
                                    </p>
                                    <?php
                                        if( isset($court_cases_affidavit[0]->id) )
                                        {
                                    ?>
                                            <a href="{{ url('admin/courtCases/viewCourtCaseAffidavit/'.Crypt::encrypt($court_cases_affidavit[0]->id)) }}" target="_blank"  class="btn btn-outline-default waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:155px;margin:5px 0px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View Document</a>
                                    <?php
                                        }
                                    ?>
                                    <br /><br /><br />
                                    <h2>Additional Affidavit</h2>
                                    <p>
                                        <label class="t-label">Affidavit Sweared by</label>
                                        <?php
                                            foreach($court_cases_submitted_by as $values)
                                            {
                                                if( isset($court_cases_additional_affidavit[0]->affidavit_submitted_by) ) {
                                                    if( $values->id == $court_cases_additional_affidavit[0]->affidavit_submitted_by ) {
                                                        echo '<br /><label class="content-text">'.$values->submitted_by.'</label>';
                                                        break;
                                                    }
                                                }
                                            }
                                            //print_r($court_cases_additional_affidavit);return;
                                            if( isset($court_cases_affidavit[0]->affidavit_submitted_by) ) {
                                                if( isset($court_cases_additional_affidavit[0]->affidavit_submitted_by) ) {
                                                    if( $court_cases_additional_affidavit[0]->affidavit_submitted_by == 6 ) {
                                        ?>
                                                &nbsp;&nbsp;&nbsp;&nbsp;<button onClick="javascript:viewBlocks('{{ Crypt::encrypt(3) }}', '{{ Crypt::encrypt($court_cases_additional_affidavit[0]->id) }}', 'Additional Affidavit Sweared by following Block(s)');" data-toggle="modal" data-target="#viewBlockModal"   class="btn btn-outline-primary waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:135px;margin:5px 0px;top:-4px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View Block(s)</button>
                                        <?php
                                                    }
                                                }
                                            }
                                        ?>
                                    </p>
                                    <?php
                                        if( isset($court_cases_additional_affidavit[0]->id) )
                                        {
                                    ?>
                                            <a href="{{ url('admin/courtCases/viewCourtCaseAffidavit/'.Crypt::encrypt($court_cases_additional_affidavit[0]->id)) }}" target="_blank"  class="btn btn-outline-default waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:155px;margin:5px 0px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View Document</a>
                                    <?php
                                        }
                                    ?>
                                </div>
                            </li>
                            <li>
                                <?php
                                    $size_of_array = sizeof($court_cases_interim_order);
                                    if($size_of_array > 0) {
                                        // $i = $size_of_array - 1;
                                        // if( isset($court_cases_interim_order[$i]->due_date_of_interim_order) ) {
                                        //     $temp = $court_cases_interim_order[$i]->due_date_of_interim_order;
                                        // }
                                        // else
                                        //     $temp = "N/A";
                                        $temp = $size_of_array;
                                    }
                                    else
                                        $temp = "N/A";
                                ?>
                                <time class="cbp_tmtime"><span>Orders Passed</span> <span><?php echo $temp; ?></span></time>
                                <div class="cbp_tmicon cbp_tmicon-phone"></div>
                                <div class="cbp_tmlabel">
                                    <h2>Interim Order</h2>
                                    <p>
                                    <table class="table table-bordered table-striped table-hover" id="dataTable1">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th width="15%">Due Date of Compliance of Interim Order</th>
                                                <th width="10%">Action to be taken by</th>
                                                <th width="20%">Brief of Interim order if any</th>
                                                <th width="25%">Details of Action taken as per Interim Order</th>
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
                                                ?></td>
                                                <td>{{ $li->submitted_by }}
                                                <?php
                                                    if( $li->id == 6 ) {
                                                ?>
                                                        <button onClick="javascript:viewBlocks('{{ Crypt::encrypt(4) }}', '{{ Crypt::encrypt($li->i_id) }}', 'Interim Order to be Complied by following Block(s)');" data-toggle="modal" data-target="#viewBlockModal"   class="btn btn-outline-primary waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:135px;margin:5px 0px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View Block(s)</button>
                                                <?php
                                                    }
                                                ?>
                                                </td>
                                                <td>{{ $li->brief_of_interim_order }}</td>
                                                <td>{{ $li->details_action_taken_as_per_interim_order }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    </p>
                                </div>
                            </li>
                            <li>
                                <?php
                                    // if( isset($court_cases_instruction[0]->due_date_of_instruction) ) {
                                    //     $temp = $court_cases_instruction[0]->due_date_of_instruction;
                                    // }
                                    // else
                                    //     $temp = "N/A";
                                    $size_of_array = sizeof($court_cases_instruction);
                                    if($size_of_array > 0) {
                                        // $i = $size_of_array - 1;
                                        // if( isset($court_cases_instruction[$i]->due_date_of_instruction) ) {
                                        //     $temp = $court_cases_instruction[$i]->due_date_of_instruction;
                                        // }
                                        // else
                                        //     $temp = "N/A";
                                        $temp = $size_of_array;
                                    }
                                    else
                                        $temp = "N/A";
                                ?>
                                <time class="cbp_tmtime"><span>Orders Passed</span> <span><?php echo $temp; ?></span></time>
                                <div class="cbp_tmicon cbp_tmicon-phone"></div>
                                <div class="cbp_tmlabel">
                                    <h2>Instruction</h2>
                                    <!-- <p>
                                        <label class="t-label">Instruction to be Submitted By</label>
                                        <?php
                                            foreach($court_cases_submitted_by as $values)
                                            {
                                                if( isset($court_cases_instruction[0]->instruction_submitted_by) ) {
                                                    if( $values->id == $court_cases_instruction[0]->instruction_submitted_by ) {
                                                        echo '<br /><label class="content-text">'.$values->submitted_by.'</label>';
                                                        break;
                                                    }
                                                }
                                            }
                                        ?>
                                    </p> -->
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-bordered table-striped table-hover" id="dataTable1">
                                                <thead>
                                                    <tr class="bg-primary">
                                                        <th width="15%">Due Date of Submission of Instruction</th>
                                                        <th width="20%">To be Submitted by</th>
                                                        <th width="20%">Letter Number</th>
                                                        <th width="10%">Document</th>
                                                        <th width="8%">Submitted</th>
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
                                                        ?></td>
                                                        <td>{{ $li->submitted_by }}
                                                        <?php
                                                            if( $li->id == 6 ) {
                                                        ?>
                                                                <button onClick="javascript:viewBlocks('{{ Crypt::encrypt(5) }}', '{{ Crypt::encrypt($li->i_id) }}', 'Instructions to be Submitted by following Block(s)');" data-toggle="modal" data-target="#viewBlockModal"   class="btn btn-outline-primary waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:135px;margin:5px 0px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View Block(s)</button>
                                                        <?php
                                                            }
                                                        ?>
                                                        </td>
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
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <?php
                                    if( isset($court_cases_final_order[0]->due_date_of_final_order) ) {
                                        $temp = $court_cases_final_order[0]->due_date_of_final_order;
                                    }
                                    else
                                        $temp = "N/A";
                                    ?>
                                <time class="cbp_tmtime"><span>Due Date for Compliance</span> <span><?php echo $temp; ?></span></time>
                                <div class="cbp_tmicon cbp_tmicon-phone"></div>
                                <div class="cbp_tmlabel">
                                    <h2>Final Order</h2>
                                    <p>
                                        <label class="t-label">Action to be Taken By</label>
                                        <?php
                                            $final_order = '';
                                            if( isset($court_cases_final_order[0]->i_id) ) {
                                                $final_order = $court_cases_final_order[0]->i_id;
                                            }
                                            foreach($court_cases_submitted_by as $values)
                                            {
                                                if( isset($court_cases_final_order[0]->action_to_be_taken_by) ) {
                                                    if( $values->id == $court_cases_final_order[0]->action_to_be_taken_by ) {
                                                        echo '<br /><label class="content-text">'.$values->submitted_by.'</label>';
                                                        break;
                                                    }
                                                }
                                            }
                                            if( isset($court_cases_final_order[0]->id) ) {
                                                if( $court_cases_final_order[0]->id == 6 ) {
                                        ?>
                                                &nbsp;&nbsp;&nbsp;&nbsp;<button onClick="javascript:viewBlocks('{{ Crypt::encrypt(6) }}', '{{ Crypt::encrypt($final_order) }}', 'Final Order to be Complied by following Block(s)');" data-toggle="modal" data-target="#viewBlockModal"   class="btn btn-outline-primary waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:135px;margin:5px 0px;top:-4px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View Block(s)</button>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </p><br/>
                                    <p>
                                        <label class="t-label">Date of receipt of Final order</label>
                                        <?php
                                            if( isset($court_cases_final_order[0]->date_of_receipt_of_final_order) ) {
                                                echo '<br /><label class="content-text">'.$court_cases_final_order[0]->date_of_receipt_of_final_order.'</label>';
                                            }
                                        ?>
                                    </p><br />
                                    <p>
                                        <label class="t-label">Details of Final Order</label>
                                        <?php
                                            if( isset($court_cases_final_order[0]->details_of_final_order) ) {
                                                echo '<br /><label class="content-text">'.$court_cases_final_order[0]->details_of_final_order.'</label>';
                                            }
                                        ?>
                                    </p><br />
                                    <p>
                                        <label class="t-label">Details of Action taken as per Final Order</label>
                                        <?php
                                            if( isset($court_cases_final_order[0]->details_of_action_taken_as_per_financial_order) ) {
                                                echo '<br /><label class="content-text">'.$court_cases_final_order[0]->details_of_action_taken_as_per_financial_order.'</label>';
                                            }
                                        ?>
                                    </p><br />
                                    <p>
                                        <label class="t-label">Appeal Petition</label>
                                        <?php
                                            if( isset($court_cases_final_order[0]->details_of_appeal_petition) ) {
                                                echo '<br /><label class="content-text">'.$court_cases_final_order[0]->details_of_appeal_petition.'</label>';
                                            }
                                            else
                                                echo '<br /><label class="content-text">N/A</label>'
                                        ?>
                                    </p>
                                </div>
                            </li>
                            <li>
                                <?php
                                    // if( isset($court_cases_speaking_order[0]->date_of_speaking_order) ) {
                                    //     $dso = $court_cases_speaking_order[0]->date_of_speaking_order;
                                    //     if( isset($court_cases_speaking_order[0]->due_date_of_speaking_order) ) {
                                    //         $ddso = $court_cases_speaking_order[0]->due_date_of_speaking_order;
                                    //     }
                                    //     $date=date_create($dso);    
                                    //     date_sub($date,date_interval_create_from_date_string($ddso." days"));
                                    //     $temp = date_format($date,"d-m-Y");
                                    // }
                                    // else
                                    //     $temp = "N/A";
                                    $size_of_array = sizeof($court_cases_speaking_order);
                                    if($size_of_array > 0) {
                                        // $i = $size_of_array - 1;
                                        // if( isset($court_cases_instruction[$i]->due_date_of_instruction) ) {
                                        //     $temp = $court_cases_instruction[$i]->due_date_of_instruction;
                                        // }
                                        // else
                                        //     $temp = "N/A";
                                        $temp = $size_of_array;
                                    }
                                    else
                                        $temp = "N/A";
                                ?>
                                <time class="cbp_tmtime"><span>Orders Passed</span> <span><?php echo $temp; ?></span></time>
                                <div class="cbp_tmicon cbp_tmicon-phone"></div>
                                <div class="cbp_tmlabel">
                                    <h2>Speaking Order</h2>
                                    <!-- <p>
                                        <label class="t-label">Speaking Order to be Passed By</label>
                                        <?php
                                            foreach($court_cases_submitted_by as $values)
                                            {
                                                if( isset($court_cases_speaking_order[0]->speaking_order_passed_by) ) {
                                                    if( $values->id == $court_cases_speaking_order[0]->speaking_order_passed_by ) {
                                                        echo '<br /><label class="content-text">'.$values->submitted_by.'</label>';
                                                        break;
                                                    }
                                                }
                                            }
                                        ?>
                                    </p><br />
                                    <p>
                                        <label class="t-label">Date of Issue of Speaking Order</label>
                                        <?php
                                            if( isset($court_cases_speaking_order[0]->date_of_speaking_order) ) {
                                                echo '<br /><label class="content-text">'.$court_cases_speaking_order[0]->date_of_speaking_order.'</label>';
                                            }
                                        ?>
                                    </p><br />
                                    <p>
                                    <p>
                                        <label class="t-label">Due Date of Issue of Speaking Order</label>
                                        <?php
                                            if( isset($court_cases_speaking_order[0]->due_date_of_speaking_order) ) {
                                                echo '<br /><label class="content-text">'.$court_cases_speaking_order[0]->due_date_of_speaking_order.' Days Prior</label>';
                                            }
                                        ?>
                                    </p> -->
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-bordered table-striped table-hover" id="dataTable1">
                                                <thead>
                                                    <tr class="bg-primary">
                                                        <th width="18%">Date of Speaking Order to be Passed</th>
                                                        <th width="15%">Due Date of Issue</th>
                                                        <th width="25%">To be Passed By</th>
                                                        <th width="25%">Letter Number</th>
                                                        <th width="10%">Document</th>
                                                        <th width="8%">Passed</th>
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
                                                        ?></td>
                                                        <td>{{ $li->due_date_of_speaking_order }} Days Prior</td>
                                                        <td>{{ $li->submitted_by }}
                                                        <?php
                                                            if( $li->id == 6 ) {
                                                        ?>
                                                                <button onClick="javascript:viewBlocks('{{ Crypt::encrypt(7) }}', '{{ Crypt::encrypt($li->i_id) }}', 'Speaking Order to be Passed by following Block(s)');" data-toggle="modal" data-target="#viewBlockModal"   class="btn btn-outline-primary waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:135px;margin:5px 0px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View Block(s)</button>
                                                        <?php
                                                            }
                                                        ?>
                                                        </td>
                                                        <td>{{ $li->letter_number }}</td>
                                                        <td>
                                                        <?php
                                                            if( isset($li->document) != "" ) {
                                                        ?>
                                                                <a href="{{ url('admin/courtCases/viewCourtCaseSpeakingOrder/'.Crypt::encrypt($li->i_id)) }}" target="_blank"  class="btn btn-outline-default waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:2px 3px;width:73px;margin:5px 0px;" title="Download Document"><i class="fa fa-eye"></i>&nbsp;&nbsp;View</a>
                                                        <?php
                                                            }
                                                            else
                                                                echo "No Document";
                                                        ?>
                                                        </td>
                                                        <td>{{ $li->submitted }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewBlockModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width:755px;">
            <div class="modal-content">
                <div class="modal-header aqua-gradient" style="height:75px;">
                    <div class="portlet-title">
                        <div class="caption caption-md">
                            <center><h3 class="caption-subject font-blue-madison bold uppercase"><b id="section_block_head" style="color:#444;"></b></h3></center>
                            <button type="button" class="btn-outline-danger" data-dismiss="modal" style="position:absolute;height:38px;width:35px;right:15px;top:19px;border-radius:2px;">
                                <span aria-hidden="true" >&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="view_blocks_body" class="modal-body">

                </div>
                <div id="applicant_details_footer" class="modal-footer" style="padding:5px 18.5px;">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-outline-danger" data-dismiss="modal" style="float:right;color:white;padding:5px 10px;font-size:10pt;">Close</button>
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
        function viewBlocks(section_id, section_table_id, section_head) {
            $('#section_block_head').html("");
            $('#view_blocks_body').html("");
            var d_id = "{{ Crypt::encrypt($court_cases[0]->district_id) }}";
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                url: '{{ route('admin.courtCases.viewBlocks') }}',
                data: '&section_id='+section_id+'&section_table_id='+section_table_id+'&d_id='+d_id,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    $('#section_block_head').html(section_head);
                    $('#view_blocks_body').html(data);
                    $('.page-loader-wrapper').fadeOut();
                }
            });
            return false;
        }
    </script>
@endsection