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
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Home</a></li>
            <li><a href="{{route('admin.UsersManagement.user_dashboard')}}">User Management</a></li>
            <li class="active">State Admin List</li>
        </ol>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading" style="text-align: center">
            State Admin List
        </div>
        <div class="panel-body gray-back">
            <div class="row">
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
                                <td>Mobile</td>
                                <td>Email</td>
                                <td>Status</td>
                                <td>Change Password</td>
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
                                    <td><button class="changePass" data-aid="{{$list->mdas_id}}"><i class="fa fa-lock" style="font-size:24px;color:red"></i></button> </td>
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



   
<div class="modal fade" id="changePass">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #6b133d;color: #fff;">
                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    <h3 class="modal-title">Change Password</h3>
                </div>

                <form action="#" method="POST" id="change_pass" autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" class="form-control" name="aid" id="aid" value="" placeholder="" />
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>New Passsword<strong>*</strong></label>
                                    <input type="password" class="form-control" name="ed_new_pass" id="ed_new_pass" value="" placeholder="" />
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Confirm Passsword<strong>*</strong></label>
                                    <input type="password" class="form-control" name="ed_confirm_pass" id="ed_confirm_pass" value="" placeholder="" />
                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-save">
                                <i class="fa fa-send" aria-hidden="true"></i>
                                Update Password
                            </button>
                        </div>
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
                        text:      'Export in Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
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
        
        
        $('.changePass').on('click', function(e){
            e.preventDefault();
            
            var aid= $(this).data('aid');
            
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('admin.userManagement.getMdasUserByid')}}',
                dataType: "json",
                data: {aid: aid},
                cache: false,
                success: function (data) {
                    if (aid) {
                        
                        $('#aid').val(data.data.MdasUserData.id);

                        
                         $('#changePass').modal('show');
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
            })
        });
        
        $("#change_pass").validate({
            rules: {
                ed_new_pass: {
                    required: true,
                    minlength:8
                },
                ed_confirm_pass: {
                    required: true,
                },
            },
        });

        /*-----------------------------------  EDIT  -----------------------------------------------------------------*/

        $('#change_pass').on('submit', function(e){
            e.preventDefault();

            var aid= $(this).data('aid');
            if($('#change_pass').valid()){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.userManagement.userPasswordUpdate')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {
                            swal("Success", data.msg, "success")
                                .then((value) => {
                                $('#changePass').modal('hide');
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