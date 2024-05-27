
@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_admin')

@section('custom_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>

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
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #6b133d;
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


        .mt10{
            margin-top: 10px;
        }
        .mt20{
            margin-top: 20px;
        }
        .mt30{
            margin-top: 30px;
        }
        strong{
            color:red;
        }
        .form-control{
            height:25px;
            padding:2px 5px;
            font-size: 12px;
        }
        label{
            font-size: 11px;
        }
        .Zebra_DatePicker_Icon_Wrapper{
            width:100% !important;
        }
        .table{
            margin-bottom: 0px;
            border:0px;
        }
        body{
            background-color: #eee;
        }

        #myModalAddPri .modal-body{
            padding-bottom:0px;
            background-color: rgba(125, 210, 235, 0.93);
        }
        .well{
            margin-bottom: 0px;
        }

        .overlay {
            position: absolute;
            bottom: 0;
            background: rgb(0, 0, 0);
            background: rgba(0, 0, 0, 0.5); /* Black see-through */
            color: #f1f1f1;
            width: 100%;
            transition: .5s ease;
            opacity:0;
            color: white;
            font-size: 20px;
            padding: 20px;
            text-align: center;
        }

        /* When you mouse over the container, fade in the overlay title */
        .pri:hover .overlay {
            opacity: 1;
        }
        .btn-round{
            border-radius: 50%;
        }
        .d-header {
            height: 59px;
            color: #fff;
            background-color: #10436d; /* For browsers that do not support gradients */
            background-image: linear-gradient(90deg, #10436d, #1eccbc); /* Standard syntax (must be last) */
            /*border-radius: 31px 0 0 0;*/
        }
        label {
            color: #2575fc;
            font-family: 'Playfair Display', serif;
            font-size: 12px;
        }
        .d-font {
            font-family: 'Playfair Display', serif;
        }
        .modal-body {
            position: relative;
            padding: 15px;
            background-color: aliceblue;
        }
        .modal-footer {
            padding: 5px;
            text-align: right;
            border-top: 1px solid #e5e5e5;
        }


        .profile-modal-row{
            background-color: #ff9000;
            margin-top: -16px;
            margin-bottom: -30px;
            border-bottom: 5px solid #fff;
        }

        #pri_image{
            cursor: pointer;
            border-radius: 50%;
            border: 4px solid #fff;
            margin-bottom: -37px;
            background-color: #fff;
        }
        .badge-primary {
            background-color: #120634;
        }
        .badge-red {
            background-color: red;
        }
    </style>
@endsection

@section('content')
    <div class="row" style="margin: 0px">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            <li class="active">OSR</li>
        </ol>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">

    </div>
    <div class="container-fluid">
    <div class="mb40">
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center">
                USER MANAGEMENT
            </div>
            <div class="panel-body gray-back">
        <div class="row mt20">
            <div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px">
                <button type="button" class="btn btn-primary pull-right" style="margin-left:5px; margin-top:5px" data-toggle="modal" data-target="#createUser">
                    <i class="fa fa-plus"></i>
                    Create User
                </button>
            </div><br>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="dataTable1">
                        <thead>
                        <tr class="tr-row">
                            <td>#</td>
                            <td>Image</td>
                            <td>Name</td>
                            <td>Username</td>
                            <td>Role</td>
                            <td>Level</td>
                            <td>Designation</td>
                            <td>ZP</td>
                            <td>AP</td>
                            <td>GP</td>
                            <td>Mobile</td>
                            <td>Email</td>
                            <td>Status</td>
                            {{--<td>Action</td>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @php $i =1; @endphp
                        @foreach($mdasUserList AS $list)
                            <tr>
                                <td>{{$i}}</td>
                                <td>@if($list->image)
                                    <img width="50px;" src="{{$imgUrl}}{{$list->image}}">
                                    @else
                                        <img width="50px;"  src="{{asset('mdas_assets/images/user_add.png')}}"/>
                                    @endif
                                </td>
                                <td>{{$list->f_name}} {{$list->m_name}} {{$list->l_name}}</td>
                                <td>{{$list->username}}</td>
                                <td>{{$list->role_name}}</td>
                                <td>{{$list->level_name}}</td>
                                <td>{{$list->designation}}</td>
                                <td>{{$list->zila_parishad_name}}</td>
                                <td>{{$list->anchalik_parishad_name}}</td>
                                <td>{{$list->gram_panchayat_name}}</td>
                                <td>{{$list->mobile}}</td>
                                <td>{{$list->email}}</td>
                                <td>
                                    {{--<input data-id="{{$list->id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $list->status ? 'checked' : '' }}>--}}
                                    <label class="switch">
                                        <input class="toggle-class" data-id="{{$list->mdas_id}}" type="checkbox" {{ $list->status ? 'checked' : '' }}>
                                        <span class="slider round">
                                            @if (password_verify('pass@123', $list->password))
                                                {{--<i class="fa fa-warning" style="color: red;font-size: 14px;padding: 10px"></i>--}}
                                            @else <i class="fa fa-lock" style="color: #0be50b;font-size: 18px;padding: 10px"></i>
                                            @endif
                                        </span>
                                    </label>

                                </td>
                                {{--<td><a href="" class="btn btn-round btn-primary btn-sm">Edit</a> </td>--}}
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            </div>
        </div>
    </div>
    </div>

    {{--------------------------------Create User-------------------------------------------------------------}}

    <div class="modal fade" id="createUser" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 33px 0 0 0;">
                <div class="modal-header" style="background-color: #ff9000">
                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    {{--<p class="deorifont">Fields with asterisk (<strong>*</strong>) are required.</p>--}}
                </div>
                <form action="#" method="POST" id="mdasUserForm" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <!------------------------- TOP BAND ------------------------------>

                        <div class="row profile-modal-row">
                            <div class="col-md-4 col-sm-4 col-xs-4 text-center col-xs-offset-4">
                                <img id="pri_image" src="{{asset('mdas_assets/images/user_add.png')}}" style="width:120px;max-height:130px;" />
                            </div>
                        </div>
                        <div class="row" style="margin-top:60px">
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                <label style="font-size: 10px">Click the above image to upload passport photo</label>
                                <input type="file" name="image" id="pic" style="display: none"/><br>
                                <a href="#" data-toggle="tooltip" title="Note: Click on the photo to upload passport photo. Upload jpg, jpeg and png file only. Max image size should not exceed 100KB and not less than 10KB">
                                    <i class="fa fa-question-circle"></i>
                                </a>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 15px;">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label><i class="fa fa-user-circle"></i> First Name <strong>*</strong></label>
                                    <input type="text" class="form-control" name="f_name" id="mdas_user_f_name"/>
                                </div>
                             </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label><i class="fa fa-user-circle"></i> Middle Name</label>
                                    <input type="text" class="form-control" name="m_name" id="mdas_user_m_name"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label><i class="fa fa-user-circle"></i> Last Name <strong>*</strong></label>
                                    <input type="text" class="form-control" name="l_name" id="mdas_user_l_name"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label><i class="fa fa-phone"></i> Mobile Number <strong>*</strong></label>
                                    <input type="number" class="form-control" name="mobile" id="mobile"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label><i class="fa fa-envelope"></i> Email <strong>*</strong></label>
                                    <input type="email" class="form-control" name="email" id="email"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label><i class="fa fa-anchor"></i> Designation</label>
                                    <input type="text" class="form-control" name="designation" id="designation"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label><i class="fa fa-sitemap"></i> Level <strong>*</strong></label>
                                        <select class="form-control" name="mdas_master_level_id" id="select1">
                                            <option value="">--Select Level--</option>
                                            @foreach($mdasMasterLevels AS $list)
                                                <option value="{{$list->id}}">{{$list->level_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label><i class="fa fa-universal-access"></i> Role</label>
                                    <select class="form-control" name="mdas_master_role_id" id="select2">
                                        <option value="">--Select Role--</option>
                                        @foreach($mdasMasterRoles AS $list)
                                            <option value="{{$list->id}}">{{$list->role_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label><i class="fa fa-briefcase"></i> Employee Code (If Any)</label>
                                    <input type="text" class="form-control" name="employee_code" id="employee_code"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12 zilaDiv">
                                <div class="form-group">
                                    <label><i class="fa fa-institution"></i> Zilla Prarisad <strong>*</strong></label>
                                    <select class="form-control" name="zp_id" id="filter_zp_id">
                                        <option value="">--Select--</option>
                                        @foreach($zillaForFilters AS $li_z)
                                            <option value="{{$li_z->id}}">{{$li_z->zila_parishad_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 anchalDiv">
                                <div class="form-group">
                                    <label><i class="fa fa-institution"></i> Anchalik Panchayat <strong>*</strong></label>
                                    <select class="form-control" name="ap_id" id="filter_anchalik_id">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 gramDiv">
                                <div class="form-group">
                                    <label><i class="fa fa-institution"></i> Gram Panchayat <strong class="dis-ap">*</strong></label>
                                    <select class="form-control" name="gp_id" id="filter_gram_panchyat_id">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-save">
                            <i class="fa fa-send"></i>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
@section('custom_js')
    <script type="application/javascript">
        $(document).ready(function () {
            $('#dataTable1').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend:    'excelHtml5',
                        text:      'Export <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
        });
//************************** JQuery to enable and disable **************************************************************
        var $select1 = $( '#select1' ),
            $select2 = $( '#select2' ),
            $options = $select2.find( 'option' );

        $select1.on( 'change', function() {
            $select2.html( $options.filter( '[value="' + this.value + '"]' ) );
            if(this.value == 2) {
                $('.zilaDiv').show();
                $('.anchalDiv').hide();
                $('.gramDiv').hide();
            }
            else if(this.value == 3) {
                $('.zilaDiv').show();
                $('.anchalDiv').show();
                $('.gramDiv').hide();
            }else if(this.value == 4) {
                $('.zilaDiv').show();
                $('.anchalDiv').show();
                $('.gramDiv').show();
            }else {
                $('.zilaDiv').hide();
                $('.anchalDiv').hide();
                $('.gramDiv').hide();
            }
        } ).trigger( 'change' );

//************************* End ****************************************************************************************
        $('#pic').change(function () {
            if (this.files && this.files[0]) {
                checkImage(this.files[0]);
            }
        });

        function imageIsLoaded(e) {
            $('#pri_image').attr('src', e.target.result);
        }

        function checkImage(file){
            var extension = file.name.substr((file.name.lastIndexOf('.') + 1));

            if (extension === 'jpg' || extension === 'jpeg' || extension === 'png') {

                var img=file.size;
                var imgsize=img/1024;
                if(imgsize >= 10 && imgsize <=110){
                    var reader = new FileReader();
                    reader.onload = imageIsLoaded;
                    reader.readAsDataURL(file);
                }else{
                    swal("Information", "Image size must be less than or equal to 100 KB and greater than 10 KB!", "info");
                    $('#pic').val('');
                    $('#pri_image').attr('src', '{{asset('mdas_assets/images/d-user.jpg')}}');
                    exit();
                }

            } else {
                swal("Information", "Please select only jpeg, jpg and png format only!", "info");
                $('#pic').val('');
                $('#pri_image').attr('src', '{{asset('mdas_assets/images/d-user.jpg')}}');
                exit();
            }

        }

        $('#pri_image').click(function(e){
            e.preventDefault();
            $('#pic').click()
        });
        $(document).on('change', '#filter_zp_id', function(e){
            e.preventDefault();
            $('#filter_anchalik_id').empty();

            var zila_id = $(this).val();

            if(zila_id){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.userManagement.select_ap')}}',
                    dataType: "json",
                    data: {zila_id : zila_id},
                    success: function (data) {
                        if (data.msgType == true) {

                            $('#filter_anchalik_id')
                                .append($("<option></option>")
                                    .attr("value", '')
                                    .text('--Select--'));

                            $.each(data.data, function(key, value) {
                                $('#filter_anchalik_id')
                                    .append($("<option></option>")
                                        .attr("value", value['id'])
                                        .text(value['anchalik_parishad_name']));
                            });
                        }else{
                            swal(data.msg);
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

        $(document).on('change', '#filter_anchalik_id', function(e){
            e.preventDefault();
            $('#filter_gram_panchyat_id').empty();

            var anchalik_id = $(this).val();

            if(anchalik_id){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.userManagement.select_ajax')}}',
                    dataType: "json",
                    data: {anchalik_id : anchalik_id},
                    success: function (data) {
                        if (data.msgType == true) {

                            $('#filter_gram_panchyat_id')
                                .append($("<option></option>")
                                    .attr("value", '')
                                    .text('--Select--'));

                            $.each(data.data, function(key, value) {
                                $('#filter_gram_panchyat_id')
                                    .append($("<option></option>")
                                        .attr("value", value['id'])
                                        .text(value['gram_panchayat_name']));
                            });
                        }else{
                            swal(data.msg);
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

//----------------------------------------------Mdas User Entry Form---------------------------------------------------
        $("#mdasUserForm").validate({
            rules: {
                mdas_master_level_id:{
                    required: true
                },
                mdas_master_role_id:{
                    required: true
                },
                designation:{
                    required: true
                },
                mdas_user_f_name:{
                    required: true
                },
                mdas_user_l_name:{
                    required: true
                },
                mobile:{
                    required: true
                },
                email:{
                    required: true
                }
            }
        });

        $('#mdasUserForm').on('submit', function(e){
            e.preventDefault();
            if($('#mdasUserForm').valid()){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.userManagement.createMdasUser')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {
                            swal("Success", data.msg, "success")
                                .then((value) => {
                                $('#createUser').modal('hide');
                            location.reload();
                        });
                        }else{
                            if(data.msg == "VE"){
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

        $('.toggle-class').change(function(e) {
            e.preventDefault();

            var checkbox=$(this);

            var status = $(this).prop('checked') == true ? 1 : 0;
            var user_id = $(this).data('id');
            if (confirm('Are you sure you want to change the status of the user?')) {
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{route('admin.userManagement.statusUser')}}',
                    data: {'status': status, 'user_id': user_id},
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



    </script>
@endsection