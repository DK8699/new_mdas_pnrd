@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_admin')

@section('custom_css')
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <style>
        .ml-5{
            margin-left: 5px;
        }

        .w-85{
            width: 85px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            <li><a href="{{route('admin.survey.six_finance')}}">Sixth Assam State Finance</a></li>
            <li class="active">Delete Request List</li>
        </ol>
    </div>

    <div class="container-fuild">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h4>Six Finance Delete Request List</h4>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable1">
                        <thead>
                            <tr class="bg-primary">
                                <td>#</td>
                                <td>Employee Code</td>
                                <td>Applicable Name</td>
                                <td>Location</td>
                                <td>Basic Info</td>
                                <td>Staff Info</td>
                                <td>Revenue Info</td>
                                <td>Expenditure Info</td>
                                <td>Balance Info</td>
                                <td>Other Info</td>
                                <td>Next 5 Year Info</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                            @foreach($results AS $res)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$res->employee_code}}</td>
                                    <td>{{$res->applicable_name}}</td>
                                    <td>
                                        <label>District :</label> {{$res->district_name}}
                                        @if($res->zila_parishad_name) <label> ZP :</label> {{$res->zila_parishad_name}} @endif
                                        @if($res->anchalik_parishad_name) <label> AP :</label> {{$res->anchalik_parishad_name}} @endif
                                        @if($res->gram_panchayat_name) <label> GP :</label> {{$res->gram_panchayat_name}} @endif
                                    </td>

                                    <td class="w-85" style="@if(!$res->basic_info){{"background-color:#eee"}}@endif">
                                        @if($res->basic_info)
                                            <button class="btn btn-danger ml-5 dFormParts" type="button" data-fan="{{$res->id}}" data-df="BAS">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                            <button class="btn btn-warning cFormParts" type="button" data-fan="{{$res->id}}" data-df="BAS">
                                                <i class="fa fa-undo"></i>
                                            </button>
                                        @endif
                                    </td>
                                    <td class="w-85" style="@if(!$res->staff_info){{"background-color:#eee"}}@endif">
                                        @if($res->staff_info)
                                            <button class="btn btn-danger ml-5 dFormParts" type="button" data-fan="{{$res->id}}" data-df="STA">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                            <button class="btn btn-warning cFormParts" type="button" data-fan="{{$res->id}}" data-df="STA">
                                                <i class="fa fa-undo"></i>
                                            </button>
                                        @endif
                                    </td>
                                    <td class="w-85" style="@if(!$res->revenue_info){{"background-color:#eee"}}@endif">
                                        @if($res->revenue_info)
                                            <button class="btn btn-danger ml-5 dFormParts" type="button" data-fan="{{$res->id}}" data-df="REV">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                            <button class="btn btn-warning cFormParts" type="button" data-fan="{{$res->id}}" data-df="REV">
                                                <i class="fa fa-undo"></i>
                                            </button>
                                        @endif
                                    </td>

                                    <td class="w-85" style="@if(!$res->expenditure_info){{"background-color:#eee"}}@endif">
                                        @if($res->expenditure_info)
                                            <button class="btn btn-danger ml-5 dFormParts" type="button" data-fan="{{$res->id}}" data-df="EXP">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                            <button class="btn btn-warning cFormParts" type="button" data-fan="{{$res->id}}" data-df="EXP">
                                                <i class="fa fa-undo"></i>
                                            </button>
                                        @endif
                                    </td>

                                    <td class="w-85" style="@if(!$res->balance_info){{"background-color:#eee"}}@endif">
                                        @if($res->balance_info)
                                            <button class="btn btn-danger ml-5 dFormParts" type="button" data-fan="{{$res->id}}" data-df="BAL">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                            <button class="btn btn-warning cFormParts" type="button" data-fan="{{$res->id}}" data-df="BAL">
                                                <i class="fa fa-undo"></i>
                                            </button>
                                        @endif
                                    </td>

                                    <td class="w-85" style="@if(!$res->other_info){{"background-color:#eee"}}@endif">
                                        @if($res->other_info)
                                            <button class="btn btn-danger ml-5 dFormParts" type="button" data-fan="{{$res->id}}" data-df="OTH">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                            <button class="btn btn-warning cFormParts" type="button" data-fan="{{$res->id}}" data-df="OTH">
                                                <i class="fa fa-undo"></i>
                                            </button>
                                        @endif
                                    </td>

                                    <td class="w-85" style="@if(!$res->five_year_info){{"background-color:#eee"}}@endif">
                                        @if($res->five_year_info)
                                            <button class="btn btn-danger ml-5 dFormParts" type="button" data-fan="{{$res->id}}" data-df="NEX">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                            <button class="btn btn-warning cFormParts" type="button" data-fan="{{$res->id}}" data-df="NEX">
                                                <i class="fa fa-undo"></i>
                                            </button>
                                        @endif
                                    </td>

                                    <td>
                                        <button class="btn btn-info vFormParts" type="button" data-fan="{{$res->id}}">
                                            <i class="fa fa-list"></i>
                                        </button>
                                    </td>
                                </tr>
                                @php $i++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')

    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="application/javascript">
        $(document).ready( function () {
            $('#dataTable1').DataTable();
        } );


        $('.dFormParts').on('click', function(e){
            e.preventDefault();

            var df= $(this).data('df');
            var id= $(this).data('fan');

            if(df && id){
                swal({
                    title: "Are you sure?",
                    text: "You are sure you want to delete the request!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willStore) => {
                    if (willStore) {
                        $('.page-loader-wrapper').fadeIn();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '{{route('admin.survey.six_finance.delete_request_list.delete')}}',
                            dataType: "json",
                            data: {df: df, id: id},
                            cache: false,
                            success: function (data) {
                                if (data.msgType == true) {
                                    swal('Success', data.msg, 'success');
                                    location.reload();
                                }else{
                                    swal('Message', data.msg, 'info');
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                            },
                            complete: function (data) {
                                $('.page-loader-wrapper').fadeOut();
                            }
                        });
                    } else {
                        swal("You have canceled your operation!");
                    }
                })
            }else{
                swal("Something went wrong. Please try again later");
            }
        })

        $('.cFormParts').on('click', function(e){
            e.preventDefault();

            var df= $(this).data('df');
            var id= $(this).data('fan');

            if(df && id){
                swal({
                    title: "Are you sure?",
                    text: "You are sure you want to cancel the request!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willStore) => {
                    if (willStore) {
                        $('.page-loader-wrapper').fadeIn();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '{{route('admin.survey.six_finance.delete_request_list.cancel')}}',
                            dataType: "json",
                            data: {df: df, id: id},
                            cache: false,
                            success: function (data) {
                                if (data.msgType == true) {
                                    swal('Success', data.msg, 'success');
                                    location.reload();
                                }else{
                                    swal('Message', data.msg, 'info');
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                            },
                            complete: function (data) {
                                $('.page-loader-wrapper').fadeOut();
                            }
                        });
                    } else {
                        swal("You have canceled your operation!");
                    }
                })
            }else{
                swal("Something went wrong. Please try again later");
            }
        })
    </script>
@endsection