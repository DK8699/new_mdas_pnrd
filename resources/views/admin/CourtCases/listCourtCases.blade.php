@php
    $page_title="dashboard";
@endphp

@extends('admin.CourtCases.layouts.frame')

@section('custom_css')
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
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
    .dropdown {
        background:rgba(0,0,0,0);
    }
    .select-margin{
        margin-top:-3.5px;
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
        font-size: 1.25rem;
        font-weight: 300;
    }
    table.dataTable thead th, table.dataTable thead td {
        border-bottom: 1px solid #8f8f8f;
        font-size: 10pt;
    }
    .table-bordered>thead>tr>th, .table-bordered>tbody>tr>td {
        font-size: 12pt;
        font-weight:bold;
        font-family:'Roboto';
        color:#5f5f5f;
        vertical-align: middle;
        padding: 5px 5px 10px 5px;
        border-top: 0px solid #ddd;
    }
    .table-bordered>thead>tr>th {
        background-color:#3a9fff;
        color:white;
    }
    table.table-hover tbody tr:hover {
        transition: .5s;
        background-color: rgba(200, 200, 200, 0.75);
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
        padding-top:4px;
    }
    .pt-3-half {
        padding-top: -1.4rem;
    }




    body {
        font-size: 13px;
        font-family: 'Open Sans', sans-serif;
    }
    .f-strip {
        font-size: 1.1rem;
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
    <div class="container mb40">
        <div class="row mt10">
            <div class="col-md-12 col-sm-12 col-xs-12" style="border-bottom:3px solid #7c8487;margin:25px 0px 15px 0px;">
                <h2 style="text-transform: uppercase;font-weight:bold;">
                    Search Criteria for displaying custom list of Court Cases
                </h2>
            </div>
        </div>
        <form class="show-list" action="{{route('admin.courtCases.searchCourtCase')}}" method="post">
            @csrf
            <label>Date Range for Receipt of WP(C)/Notice </label>
            <div class="row">
                <div class="col-md-3">
                    <label style="color:#a1a1a1;">From </label>
                    <input id="date1" name="from_receipt_of_wpc_notice" type="text" class="form-control datepicker" placeholder="Start Date Range" data-zdp_readonly_element="true" >
                </div>
                <div class="col-md-3">
                    <label style="color:#a1a1a1;">To</label>
                    <input id="date1" name="to_receipt_of_wpc_notice" type="text" class="form-control datepicker" placeholder="End Date Range" data-zdp_readonly_element="true" >
                </div>
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>District(s)</label>
                        <select class="selectpicker form-control select-margin" name="district" data-style="btn-info">
                            <option value="{{ Crypt::encrypt('') }}">ALL SELECTED</option>
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
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>Case Under</label>
                        <select class="selectpicker form-control select-margin" name="case_under" data-style="btn-info">
                            <option value="{{ Crypt::encrypt('') }}">ALL SELECTED</option>
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
            </div>
            <div class="row mt10">
                <div class="col-sm-3">
                    <label>Nature of Case</label>
                    <select class="selectpicker form-control" name="nature_of_case" data-style="btn-info" required>
                        <option value="{{ Crypt::encrypt('') }}">ALL SELECTED</option>
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
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>Case Type</label>
                        <select class="selectpicker form-control" name="case_type" data-style="btn-info">
                            <option value="{{ Crypt::encrypt('') }}">ALL SELECTED</option>
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
                </div>
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>Case Status</label>
                        <select class="selectpicker form-control" name="case_status" data-style="btn-info">
                            <option value="{{ Crypt::encrypt('') }}">ALL SELECTED</option>
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
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group mt20">
                        <button type="submit" class="btn btn-primary blue-gradient" style="font-weight:bold;margin-top:11px;width:100%;"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</button>
                    </div>
                </div>
            </div>
        </form>
        <br /> <h4><b>OR</b></h4> <br />
        <form class="show-list" action="{{route('admin.courtCases.searchCourtCasebyNo')}}" method="post">
            <div class="row">
                <div class="col-sm-3 col-sm-4 col-xs-1">
                    <label for="exampleInputEmail1">Case Number</label>
                    <input type="text" id="" name="case_number" class="form-control mb-4" placeholder="Enter Case Number" maxlength="100">
                </div>
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group mt20">
                        <button type="submit" class="btn btn-primary blue-gradient" style="font-weight:bold;margin-top:7px;"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</button>
                    </div>
                </div>
            </div>
        </form><br /><br />
        <div id="resultset" class="row">
            <div class="col-md-12 col-sm-12 col-xs-12" style="border-bottom:3px solid #7c8487;margin:25px 0px 15px 0px;">
                <h2 style="text-transform: uppercase;font-weight:bold;">
                    List of All Cases Registered
                </h2>
            </div>
        </div>
    </div>
    {{-----------------------DATA TABLE-----------------------------------------}}
    <div class="container-fluid mb40">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="dataTable1">
                        <thead>
                            <tr class="bg-primary">
                                <th>SL</th>
                                <th>Case Type</th>
                                <th>Case Number</th>
                                <th>Nature of Case</th>
                                <th>Name of Petitioner v/s Respondant</th>
                                <th>District(s)</th>
                                <th>Case Under</th>
                                <th>Receipt of WP(C) / Notice</th>
                                <th>Case Status</th>
                                <th style="width:85px;">Task</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php $j=1; @endphp
                        @foreach($court_cases as $li)
                            <tr>
                                <td>{{ $j }}</td>
                                <td>{{ $li->court_case_type }}</td>
                                <td>{{ $li->case_number }}</td>
                                <td>{{ $li->court_case_nature }}</td>
                                <td>{{ $li->name_of_petitioner }}</td>
                                <td>
                                <?php
                                    $first_item = "NOT COVERED";
                                    foreach( $districts as $value )
                                    {
                                        $district_ids = json_decode($li->district_id);
                                        for($i=0;$i<sizeof($district_ids);$i++)
                                        {
                                            if( $first_item == "NOT COVERED" ) {
                                                $first_item = "COVERED";
                                                if( $value->id == $district_ids[$i] ) {
                                                    echo $value->district_name;
                                                }
                                            }
                                            else {
                                                if( $value->id == $district_ids[$i] ) {
                                                    echo ', '.$value->district_name;
                                                }
                                            }
                                        }
                                        $first_item = "NOT COVERED";
                                    }
                                ?>
                                </td>
                                <td>{{ $li->under }}</td>
                                <td>{{ $li->date_of_receipt_of_wpc_notice }}</td>
                                <td>{{ $li->court_case_status }}</td>
                                <td>
                                    <a href="{{ route('admin.courtCases.viewCourtCase', Crypt::encrypt($li->c_id)) }}" target="_blank" class="btn peach-gradient waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:5px 11px;width:100px;text-align:left;"><i class="fa fa-eye"></i>&nbsp;&nbsp;View</a>
                                    <a href="{{ route('admin.courtCases.manageCourtCase', Crypt::encrypt($li->c_id)) }}" target="_blank" class="btn aqua-gradient waves-effect btn-sm" style="font-weight:bold;font-size:10pt;top:5px;padding:5px 11px;width:100px;text-align:left;"><i class="fa fa-edit"></i>&nbsp;&nbsp;Manage</a>
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
    <script type="text/javascript">
        $(document).ready(function() {
            var dataTrack = $('#dataTable1').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        messageTop: 'List'
                    },
                ],
                'columnDefs'        : [         // see https://datatables.net/reference/option/columns.searchable
                    {
                        'searchable'    : false,
                        'targets'       : [9]
                    },
                ]
            });

            $('.my-select').selectpicker();

            $('.datepicker').Zebra_DatePicker({
                format: 'd-m-Y'
            });

            $(".buttons-excel").addClass("btn btn-outline-default waves-effect");

            $('.show-list').on('submit', function(e){
                e.preventDefault();
                if($('.show-list').valid()){
                    $('.page-loader-wrapper').fadeIn();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "post",
                        url: $(this).attr('action'),
                        dataType: "json",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (html) {
                            if(html.msgType == true) {
                                if (html.data.length != 0) {
                                    dataTrack.rows().remove().draw();
                                    for(i=0;i<html.data.length;i++)
                                    {
                                        dataTrack.row.add( [
                                            i+1,
                                            html.data[i].court_case_type,
                                            html.data[i].case_number,
                                            html.data[i].court_case_nature,
                                            html.data[i].name_of_petitioner,
                                            html.data[i].district_name,
                                            html.data[i].under,
                                            html.data[i].date_of_receipt_of_wpc_notice,
                                            html.data[i].court_case_status,
                                            '<a href="{{ route("admin.courtCases.viewCourtCase", '') }}/'+html.data[i].c_id+'" target="_blank" class="btn peach-gradient waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:5px 11px;width:100px;text-align:left;"><i class="fa fa-eye"></i>&nbsp;&nbsp;View</a>\n\
                                            <a href="{{ route("admin.courtCases.manageCourtCase", '') }}/'+html.data[i].c_id+'" target="_blank" class="btn aqua-gradient waves-effect btn-sm" style="font-weight:bold;font-size:10pt;top:5px;padding:5px 11px;width:100px;text-align:left;"><i class="fa fa-edit"></i>&nbsp;&nbsp;Manage</a>'
                                        ] ).draw();
                                    }
                                    $('html, body').animate({
                                        scrollTop: $("#resultset").offset().top
                                    }, 1000);
                                }
                                else {
                                    swal("No Data", "No Record to Display", 'info');
                                    dataTrack.rows().remove().draw();
                                    for(i=0;i<html.data.length;i++)
                                    {
                                        dataTrack.row.add( [
                                            i+1,
                                            html.data[i].court_case_type,
                                            html.data[i].case_number,
                                            html.data[i].court_case_nature,
                                            html.data[i].name_of_petitioner,
                                            html.data[i].district_name,
                                            html.data[i].under,
                                            html.data[i].date_of_receipt_of_wpc_notice,
                                            html.data[i].court_case_status,
                                            '<a href="{{ route("admin.courtCases.viewCourtCase", '') }}/'+html.data[i].c_id+'" target="_blank" class="btn peach-gradient waves-effect btn-sm" style="font-weight:bold;font-size:10pt;padding:5px 11px;width:100px;text-align:left;"><i class="fa fa-eye"></i>&nbsp;&nbsp;View</a>\n\
                                            <a href="{{ route("admin.courtCases.manageCourtCase", '') }}/'+html.data[i].c_id+'" target="_blank" class="btn aqua-gradient waves-effect btn-sm" style="font-weight:bold;font-size:10pt;top:5px;padding:5px 11px;width:100px;text-align:left;"><i class="fa fa-edit"></i>&nbsp;&nbsp;Manage</a>'
                                        ] ).draw();
                                    }
                                }
                            }
                            else if (html.msgType == false) {
                                swal("Date Range", html.msg, 'info');
                            }
                            else {
                                swal("Error", html.msg, 'error');
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
    </script>
@endsection