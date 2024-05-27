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
    h2 {
        font-size:18.5pt;
        font-weight:bold;
        margin-top:0;
        color:#367fed;
    }
    .blue-gradient, .aqua-gradient {
        padding:5px 25px;
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
    .btn {
        padding: 6px 12px;
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
        height:40px;
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
            <li><a href="{{route('admin.courtCases.dashboard')}}">Dashboard</a></li>
            <li class="active">Add Recipients</li>
        </ol>
    </div>
    <div class="container">
        <div class="row back">
            <div class="col-lg-12" style="">
                <center>
                    <h1 class="blue-gradient">
                        <i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;ADD RECIPIENTS
                    </h1>
                </center>
                <form id="add_recipients" class="border border-light p-5" action="" method="POST">
                    <div class="sections">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="exampleInputPassword1">First Name</label>
                                    <input type="text" id="" name="r_f_name" class="form-control mb-4" placeholder="First Name" maxlength="255">
                                </div>
                                <div class="col-sm-4">
                                    <label for="exampleInputPassword1">Middle Name</label>
                                    <input type="text" id="" name="r_m_name" class="form-control mb-4" placeholder="Middle Name" maxlength="255">
                                </div>
                                <div class="col-sm-4">
                                    <label for="exampleInputPassword1">Last Name</label>
                                    <input type="text" id="" name="r_l_name" class="form-control mb-4" placeholder="Last Name" maxlength="255">
                                </div>
                            </div>
                        </div><br />
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="exampleInputPassword1">Mobile No.</label>
                                    <input type="number" id="r_mobile" name="r_mobile" class="form-control mb-4" placeholder="Mobile Number" maxlength="10">
                                </div>
                                <div class="col-sm-4">
                                    <label for="exampleInputPassword1">Email Id</label>
                                    <input type="text" id="r_email" name="r_email" class="form-control mb-4" placeholder="Email Id" maxlength="255">
                                </div>
                                <div class="col-sm-4">
                                    <label for="exampleInputPassword1">Designation</label>
                                    <input type="text" id="r_designation" name="r_designation" class="form-control mb-4" placeholder="Designation" maxlength="255">
                                </div>
                            </div>
                        </div><br />
                        <div class="form-group"> 
                            <div class="row">
                                <div class="col-sm-4" id="underAdmin">
                                    <label for="exampleInputPassword1">Under Group</label>
                                    <select id="group_id" class="court-case-select selectpicker form-control select-margin" name="submitted_by" data-style="btn-info" required >
                                        <option value="">Select</option>
                                        @foreach($court_cases_submitted_by as $submitted)
                                        <option value="{{$submitted->id}}">{{$submitted->submitted_by}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4" id="underDistrict" style="display:none;">
                                    <label>District</label>
                                    <select id="district_id" class="court-case-select selectpicker form-control select-margin" name="district_id" data-style="btn-info" required >
                                        <option value="">Select</option>
                                        @foreach($districts as $values)
                                        <option value="{{$values->id}}">{{$values->district_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4" id="underBlock">
                                    <div id="district_blocks" class="select_districts_blocks"></div>
                                </div>
                            </div>
                        </div><br />
                    </div>
                    <div class="form-group" style="margin:11px;">
                        <div class="row">
                            <div clas="col-md-12">
                                <button type="reset" class="btn btn-primary peach-gradient" style="float:left;font-weight:bold;">Reset</button>
                                <button type="submit" class="btn btn-primary blue-gradient" style="float:right;font-weight:bold;">Add</button>
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
        var districts = '<label>District</label>'
                        +'<select id="district_id" class="court-case-select selectpicker form-control select-margin" name="district_id" data-style="btn-info" required >'
                            +'<option value="">Select</option>'
                            @foreach($districts as $values)
                            +'<option value="{{$values->id}}">{{$values->district_name }}</option>'
                            @endforeach
                        +'</select>';
        /*------------------------------- SAVE BIDDER -------------------------------------------*/
        $('#group_id').on('change', function() {
          $('#underDistrict').css('display', 'none');
          if ( $(this).val() == '3' || $(this).val() == '4' || $(this).val() == '5' || $(this).val() == '6') {
            $('#underDistrict').css('display', 'block');
          }
        });
        $("#add_recipients").validate({
            rules: {
                r_f_name: {
                    required: true,
                    blank:true,
                    maxlength:100
                },
                r_m_name: {
                    maxlength:100
                },
                r_l_name: {
                    required: true,
                    blank:true,
                    maxlength:100
                },
               
                r_mobile: {
                    required: true,
                    digits:true
                },
                r_email: {
                    email:true,
                    maxlength:150
                },
               r_designation:{
                   required: true,
                   maxlength:150
               },
               district_id:{
                    // digits:true
                },
                submitted_by:{
                    required: true,
                    // digits:true
                },
            },
        });

        $(document).on('change', '.court-case-select', function() {
            var d_id = $('#district_id').val();
            var id = $('#group_id').val();
            // var id = $(this).val();
            // var current_id = $(this).attr('id');
            if( $('#group_id').val() == 1 || $('#group_id').val() == 2 ) {
                $('#underDistrict').html('');
                $('#underBlock').html('');
                return false;
            }
            else if( $('#group_id').val() == 3 || $('#group_id').val() == 4 || $('#group_id').val() == 5) {
                if( $('#underDistrict').html() == '') {
                    $('#underBlock').html('');
                    $('#underDistrict').html('');
                    $('#underDistrict').append(districts);
                    $('.selectpicker').selectpicker();
                    return false;
                }
                else {
                    $('#underBlock').html('');
                    return false;
                }
            }
            else if( $('#group_id').val() == 6 ) {
                if( $('#underDistrict').html() == '') {
                    $('#underBlock').html('');
                    $('#underDistrict').html('');
                    $('#underDistrict').append(districts);
                    $('.selectpicker').selectpicker();
                    return false;
                }
            }
            if( $('#district_id').val() == "" || $('#group_id').val() == "")
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
                    $('#underBlock').html(data);
                    $('.selectpicker').selectpicker();
                    $('.page-loader-wrapper').fadeOut();
                }
            });
            return false;
        });

        $('#add_recipients').on('submit', function(e){
            e.preventDefault();

            if($('#add_recipients').valid()){

                $('.page-loader-wrapper').fadeIn();

                $('.form_errors').remove();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.courtCases.saveRecipients')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {

                            swal("Success", data.msg, "success")
                                .then((value) => {
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