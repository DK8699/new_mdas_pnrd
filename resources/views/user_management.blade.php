@php
    $page_title="user_management";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
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
        .paginate_button current {
            padding: 2px 10px 2px 10px !important;
            margin: 5px !important;
        }
    </style>
@endsection

@section('content')
    <div class="row" style="margin: 0px">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="active">User</li>
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
    <script type="application/javascript">
        $(document).ready(function () {
            $('#dataTable1').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend:    'excelHtml5',
                        text:      'Export <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel'
                    }
                ]
            });
        });

        $('.toggle-class').change(function(e) {
            e.preventDefault();
            var checkbox=$(this);
            var status = $(this).prop('checked') == true ? 1 : 0;
            var user_id = $(this).data('id');
            if(status==1) {
                alert('You are not Authorised to Reactivate the User! Ask State Admin to Reactivate');
                if(checkbox.prop("checked") == true){
                    checkbox.prop('checked', false);
                }
            }else {
                if (confirm('Are you sure you want to deactive the user?')) {
                    $.ajax({
                        type: "GET",
                        dataType: "json",
                        url: '{{route('user.user_management.statusUser')}}',
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
            }
        });

    </script>
@endsection