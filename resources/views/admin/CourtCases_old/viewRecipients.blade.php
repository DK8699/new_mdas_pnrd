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
        font-weight:bold;
        font-family:'Agency FB';
        color:#fff;
        padding:10px;
        margin:15px 0px;
    }
    .sections {
        webkit-box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        -moz-box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        background-color:rgba(240, 240, 240, 0.85);
        padding:25px 25px;
    }
    h2 {
        font-weight:bold;
        margin-top:0;
        color:#367fed;
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
    .btn {
        padding: 6px 12px;
    }
    .btn, label {
        font-size:12pt;
        color:#444;
    }
    .select-margin{
        margin-top:-3.5px;
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
    table.dataTable thead th, table.dataTable thead td {
        border-bottom: 1px solid #8f8f8f;
    }
    .table-bordered>thead>tr>th, .table-bordered>tbody>tr>td {
        font-size: 12pt;
        font-weight:bold;
        font-family:'Roboto';
        color:#5f5f5f;
        vertical-align: middle;
        padding-bottom:5px;
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
    }
    .pt-3-half {
        padding-top: 1.4rem;
    }
    .modal-close {
        background-color: #ff2d2d;
        color: white;
        position: absolute;
        top: -19px;
        right: -19px;
        border-radius: 500px;
        box-shadow: 0px 0px 6px 0px #6d0a0a;
        }
    .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: #fff;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #66bb6a;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
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
    <div class="container mb40">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12" style="border-bottom:3px solid #7c8487;margin:25px 0px 15px 0px;">
                <h3 style="text-transform: uppercase;font-weight:bold;">
                    List of All Recipients
                </h3>
            </div>
        </div>
        {{-----------------------DATA TABLE-----------------------------------------}}
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="dataTable1">
                        <thead>
                            <tr class="bg-primary">
                                <th>SL</th>
                                <th>Recipient Name</th>
                                <th>Mobile Number</th>
                                <th>Email</th>
                                <th>Designation</th>
                                <th>District</th>
                                <th>Head</th>
                                <th>Action</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($court_cases_recipients as $recipients)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{$recipients->recipient_f_name}} {{$recipients->recipient_m_name}} {{$recipients->recipient_l_name}}</td>
                                <td>{{$recipients->recipient_mobile}}</td>
                                <td>{{$recipients->recipient_email}}</td>
                                <td>{{$recipients->recipient_designation}}</td>
                                <td>{{$recipients->district_name}}</td>
                                <td>{{$recipients->submitted_by}}</td>
                                <td class="text-center">
                                    <a href="#" class="editRecipient" type="button" data-rid="{{$recipients->id}}" title="Edit Recipients Details">
                                        <i class="fa fa-edit" style="color:#3a9fff; font-size:20px;"></i>
                                    </a>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input class="toggle-class" data-id="{{$recipients->id}}" type="checkbox" {{ $recipients->is_active ? 'checked' : '' }} >
                                        <span class="slider round">
                                        </span>
                                    </label>
                                </td>
                            </tr>
                        @php $i++; @endphp
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div><br /><br />
        {{------------------DATA TABLE ENDED-----------------------------------------}}
    </div>

    <!-------------------------------------Edit Bidder----------------------------------------------------------------->
    <div class="modal fade" id="editRecipient">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header" style = "background-color:#4d112bfc!important; color:#fff">
                    <button type="button" class="btn bg-red modal-close pull-right" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close" style="color:#fff"></i>
                    </button>
                    <h3 class="modal-title">Edit Recipient Details</h3>
                    <p>Fields with asterisk (<strong>*</strong>) are required.</p>
                </div>
                <form id="editRecipientForm" class="border border-light p-5" action="" method="POST">
                    <div class="sections">
                        <input type="hidden" id="rid" name="rid" value=""/>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="exampleInputPassword1">First Name</label>
                                    <input type="text" id="ed_r_f_name" name="ed_r_f_name" class="form-control mb-4" placeholder="First Name" maxlength="255">
                                </div>
                                <div class="col-sm-4">
                                    <label for="exampleInputPassword1">Middle Name</label>
                                    <input type="text" id="ed_r_m_name" name="ed_r_m_name" class="form-control mb-4" placeholder="Middle Name" maxlength="255">
                                </div>
                                <div class="col-sm-4">
                                    <label for="exampleInputPassword1">Last Name</label>
                                    <input type="text" id="ed_r_l_name" name="ed_r_l_name" class="form-control mb-4" placeholder="Last Name" maxlength="255">
                                </div>
                            </div>
                        </div><br />
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="exampleInputPassword1">Mobile No.</label>
                                    <input type="number" id="ed_r_mobile" name="ed_r_mobile" class="form-control mb-4" placeholder="Mobile Number" maxlength="10">
                                </div>
                                <div class="col-sm-4">
                                    <label for="exampleInputPassword1">Email Id</label>
                                    <input type="text" id="ed_r_email" name="ed_r_email" class="form-control mb-4" placeholder="Email Id" maxlength="255">
                                </div>
                                <div class="col-sm-4">
                                    <label for="exampleInputPassword1">Designation</label>
                                    <input type="text" id="ed_r_designation" name="ed_r_designation" class="form-control mb-4" placeholder="Designation" maxlength="255">
                                </div>
                            </div>
                        </div><br />
                        <div class="form-group"> 
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="exampleInputPassword1">Under Group</label>
                                    <select class="court-case-select selectpicker form-control select-margin" name="ed_submitted_by" id="ed_submitted_by" data-style="btn-info" required >
                                        <option value="">Select</option>
                                        @foreach($court_cases_submitted_by as $submitted)
                                        <option value="{{$submitted->id}}">{{$submitted->submitted_by}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="district_content" class="col-sm-4 receipient-hide">
                                    <div id="underDistrict" style="display: none;">
                                        <label>District</label>
                                        <select class="court-case-select selectpicker form-control select-margin" name="ed_district_id" id="ed_district_id" data-style="btn-info" required >
                                            <option value="">Select</option>
                                            @foreach($districts as $values)
                                            <option value="{{$values->id}}">{{$values->district_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div id="blocks_content" class="col-sm-4 receipient-hide">
                                    <!-- <div id="district_blocks" class=""></div> -->
                                </div>
                            </div>
                        </div><br />
                    </div>
                    <div class="form-group" style="margin:11px;">
                        <div class="row">
                            <div clas="col-md-12">
                                <button type="reset" class="btn btn-primary peach-gradient" style="margin: 0 16px 14px 25px; font-weight:bold;">Reset</button>
                                <button type="submit" class="btn btn-primary blue-gradient pull-right" style="margin-right: 23px; font-weight:bold;">Edit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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
            var districts='<label for="exampleInputPassword1">District</label>'
                            +'<select class="court-case-select selectpicker form-control select-margin" name="ed_district_id" id="ed_district_id" data-style="btn-info" required >'
                                +'<option value="">Select</option>'
                                @foreach($districts as $values)
                                +'<option value="{{$values->id}}">{{$values->district_name }}</option>'
                                @endforeach
                            +'</select>';
        $(document).ready(function () {
//Change Status of Recipient

        $('.toggle-class').on('change', function(e) {
            
            var checkbox=$(this);

            var is_active = $(this).prop('checked') == true ? 1 : 0;
            var id = $(this).data('id');
            if (confirm('Are you sure you want to change the status of the Recipient?')) {
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{route('admin.courtCases.statusRecipient')}}',
                    data: {'is_active': is_active, 'id': id},
                    success: function(data){
                        console.log(data.success);
                    },
                    error: function(data){
                        if(checkbox.prop("checked") == true){
                            checkbox.prop('checked', false);
                        } else if(checkbox.prop("checked") == false){
                            checkbox.prop('checked', true);
                        }
                    }
                });
            } else{
                if(checkbox.prop("checked") == true){
                    checkbox.prop('checked', false);
                } else if(checkbox.prop("checked") == false){
                    checkbox.prop('checked', true);
                }
            }



        });
        });
        $(document).ready(function() {

            $('#dataTable1').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend:    'excelHtml5',
                        title: 	   'Recipient List',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    },
                ],
                'columnDefs'        : [         // see https://datatables.net/reference/option/columns.searchable
                    {
                        'searchable'    : false,
                        'targets'       : [6]
                    },
                ]
            });

            $('.my-select').selectpicker();

            $('.datepicker').Zebra_DatePicker({
                format: 'd-m-Y'
            });

            $(".buttons-excel").addClass("btn btn-outline-default waves-effect");

        });
        
        
        
        /*------------------------------------ GET RECIPIENT INFO -------------------------------------------------------*/

        $(document).on('change', '.court-case-select', function() {
            var d_id = $('#ed_district_id').val();
            var id = $('#ed_submitted_by').val();
            // var id = $(this).val();
            // var current_id = $(this).attr('id');
            if( $('#ed_submitted_by').val() == 1 || $('#ed_submitted_by').val() == 2 ) {
                $('#district_content').html('');
                $('#blocks_content').html('');
                return false;
            }
            else if( $('#ed_submitted_by').val() == 3 || $('#ed_submitted_by').val() == 4 || $('#ed_submitted_by').val() == 5) {
                if( $('#district_content').html() == '') {
                    $('#blocks_content').html('');
                    $('#district_content').html('');
                    $('#district_content').append(districts);
                    $('.selectpicker').selectpicker();
                    return false;
                }
                else {
                    $('#blocks_content').html('');
                    return false;
                }
            }
            else if( $('#ed_submitted_by').val() == 6 ) {
                if( $('#district_content').html() == '') {
                    $('#blocks_content').html('');
                    $('#district_content').html('');
                    $('#district_content').append(districts);
                    $('.selectpicker').selectpicker();
                    return false;
                }
            }
            if( $('#ed_district_id').val() == "" || $('#ed_submitted_by').val() == "" )
                return false;
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                url: '{{ route('admin.courtCases.loadDistrictBlocks1') }}',
                data: '&id='+id+'&d_id='+d_id,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    $('#blocks_content').html(data);
                    $('.selectpicker').selectpicker();
                    $('.page-loader-wrapper').fadeOut();
                }
            });
            return false;
        });

        $('.editRecipient').on('click', function(e){
            e.preventDefault();

            var rid= $(this).data('rid');
            
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('admin.courtCases.getRecipientsByid')}}',
                dataType: "json",
                data: {rid: rid},
                cache: false,
                success: function (data) {
                    if (data.msgType == true) {
                        $('#rid').val(data.data.RecipientData.id);
                        $('#ed_r_f_name').val(data.data.RecipientData.recipient_f_name);
                        $('#ed_r_m_name').val(data.data.RecipientData.recipient_m_name);
                        $('#ed_r_l_name').val(data.data.RecipientData.recipient_l_name);
                        $('#ed_r_mobile').val(data.data.RecipientData.recipient_mobile);
                        $('#ed_r_email').val(data.data.RecipientData.recipient_email);
                        $('#ed_r_designation').val(data.data.RecipientData.recipient_designation);
                        $('#ed_district_id').val(data.data.RecipientData.district_id);
                        $('#underDistrict').css('display', 'block');
                        $('#ed_district_id').selectpicker("refresh");

                        $('#blocks_content').html(data.options).show();
                        $('.selectpicker').selectpicker();

                        if( data.data.RecipientData.court_cases_submitted_by_id == 1 || data.data.RecipientData.court_cases_submitted_by_id == 2) {
                            $('.receipient-hide').html('');
                        }
                        else if( data.data.RecipientData.court_cases_submitted_by_id == 3 || data.data.RecipientData.court_cases_submitted_by_id == 4 ||
                            data.data.RecipientData.court_cases_submitted_by_id == 5) {
                            $('#blocks_content').html('');
                        }

                        $('#ed_submitted_by').val(data.data.RecipientData.court_cases_submitted_by_id);
                        $('#ed_submitted_by').selectpicker("refresh");
                        $('#editRecipient').modal('show');

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
            })});
        
        $("#editRecipientForm").validate({
            rules: {
                ed_r_f_name: {
                    required: true,
                    blank:true,
                    maxlength:100
                },
                ed_r_m_name: {
                    maxlength:100
                },
                ed_r_l_name: {
                    required: true,
                    blank:true,
                    maxlength:100
                },
               
                ed_r_mobile: {
                    required: true,
                    digits:true
                },
                ed_r_email: {
                    email:true,
                    maxlength:150
                },
               ed_r_designation:{
                   required: true,
                   maxlength:150
               },
               ed_district_id:{
                    digits:true
                },
               ed_submitted_by:{
                    required: true,
                    digits:true
                },
            },
        });

        /*-----------------------------------  EDIT  -----------------------------------------------------------------*/

        $('#editRecipientForm').on('submit', function(e){
            e.preventDefault();

            var rid= $(this).data('rid');
            if($('#editRecipientForm').valid()){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.courtCases.editRecipient')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {
                            swal("Success", data.msg, "success")
                                .then((value) => {
                                $('#editRecipient').modal('hide');
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
        
        
        
        
        
    </script>
@endsection