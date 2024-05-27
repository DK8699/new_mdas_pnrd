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
            @if($category == 1)
            <h4>Proposed Expenditure Request List</h4>
            @elseif($category == 2)
            <h4>Proposed Next 5 years Proposal Request List</h4>
            @elseif($category == 3)
            <h4>Proposed Designation Request List</h4>
            @elseif($category == 4)
            <h4>Proposed Revenue Request List</h4>
            @elseif($category == 5)
            <h4>Proposed Revenue Transfered CSS Resources Request List</h4>
            @elseif($category == 5)
            <h4>Proposed Revenue Transfered Shared CSS Resources Request List</h4>
            @endif
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable1">
                    @if($category == 1)
                    <thead>
                        <tr class="bg-primary">
                            <td>Sl No.</td>
                            <td>Employee Code</td>
                            <td>Expenditure Category</td>
                            <td>Proposed Expenditure Name</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach($request AS $value)
                        <tr>
                            <td>{{$i}}</td><td>{{$value['employee_code']}}</td><td>{{$value['category_name']}}</td><td>{{$value['expenditure_name']}}</td><td>
                                @if($value['is_active'] == 0 && $value['cancel'] == 0)
                                <form method="post" id="form{{$value['expenditure']}}"><div class="form-group row"><div class="col-md-1"><button type="button" class="btn btn-xs btn-primary accept-reject-btn" id="accept_{{$value['expenditure_id']}}"><i class="fa fa-check"></i></button></div><div class="col-md-1"><button type="button" class="btn btn-xs btn-danger accept-reject-btn" id="reject_{{$value['expenditure_id']}}"><i class="fa fa-close"></i></button></div></div></form>
                                @elseif($value['is_active'] == 1 && $value['cancel'] == 0)
                                Active
                                @elseif($value['is_active'] == 0 && $value['cancel'] ==1)
                                Rejected
                                @endif
                            </td>
                        </tr>
                        @php
                        $i++
                        @endphp
                        @endforeach
                    </tbody>
                    @endif

                    @if($category == 2)
                    <thead>
                        <tr class="bg-primary">
                            <td>Sl No.</td>
                            <td>Employee Code</td>
                            <td>Proposed proposal Entity</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach($request AS $value)
                        <tr>
                            <td>{{$i}}</td><td>{{$value['employee_code']}}</td><td>{{$value['entity_name']}}</td><td>
                                @if($value['is_active'] == 0 && $value['cancel'] == 0)
                                <form method="post" id="form{{$value['expenditure']}}"><div class="form-group row"><div class="col-md-1"><button type="button" class="btn btn-xs btn-primary accept-reject-btn" id="accept_{{$value['expenditure_id']}}"><i class="fa fa-check"></i></button></div><div class="col-md-1"><button type="button" class="btn btn-xs btn-danger accept-reject-btn" id="reject_{{$value['expenditure_id']}}"><i class="fa fa-close"></i></button></div></div></form>
                                @elseif($value['is_active'] == 1 && $value['cancel'] == 0)
                                Active
                                @elseif($value['is_active'] == 0 && $value['cancel'] ==1)
                                Rejected
                                @endif
                            </td>
                        </tr>
                        @php
                        $i++
                        @endphp
                        @endforeach
                    </tbody>
                    @endif

                    @if($category == 3)
                    <thead>
                        <tr class="bg-primary">
                            <td>Sl No.</td>
                            <td>Employee Code</td>
                            <td>Designation Category</td>
                            <td>Proposed Designation</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach($request AS $value)
                        <tr>
                            <td>{{$i}}</td><td>{{$value['created_by']}}</td><td>{{$value['category_name']}}</td><td>{{$value['designation_name']}}</td><td>
                                @if($value['is_active'] == 0 && $value['cancel'] == 0)
                                <form method="post" id="form{{$value['expenditure']}}"><div class="form-group row"><div class="col-md-1"><button type="button" class="btn btn-xs btn-primary accept-reject-btn" id="accept_{{$value['expenditure_id']}}"><i class="fa fa-check"></i></button></div><div class="col-md-1"><button type="button" class="btn btn-xs btn-danger accept-reject-btn" id="reject_{{$value['expenditure_id']}}"><i class="fa fa-close"></i></button></div></div></form>
                                @elseif($value['is_active'] == 1 && $value['cancel'] == 0)
                                Active
                                @elseif($value['is_active'] == 0 && $value['cancel'] ==1)
                                Rejected
                                @endif
                            </td>
                        </tr>
                        @php
                        $i++
                        @endphp
                        @endforeach
                    </tbody>
                    @endif
                    @if($category == 4)
                    <thead>
                        <tr class="bg-primary">
                            <td>Sl No.</td>
                            <td>Employee Code</td>
                            <td>Proposed Revenue Category</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach($request AS $value)
                        <tr>
                            <td>{{$i}}</td><td>{{$value['created_by']}}</td><td>{{$value['own_revenue_name']}}</td><td>
                                @if($value['is_active'] == 0 && $value['cancel'] == 0)
                                <form method="post" id="form{{$value['expenditure']}}"><div class="form-group row"><div class="col-md-1"><button type="button" class="btn btn-xs btn-primary accept-reject-btn" id="accept_{{$value['expenditure_id']}}"><i class="fa fa-check"></i></button></div><div class="col-md-1"><button type="button" class="btn btn-xs btn-danger accept-reject-btn" id="reject_{{$value['expenditure_id']}}"><i class="fa fa-close"></i></button></div></div></form>
                                @elseif($value['is_active'] == 1 && $value['cancel'] == 0)
                                Active
                                @elseif($value['is_active'] == 0 && $value['cancel'] ==1)
                                Rejected
                                @endif
                            </td>
                        </tr>
                        @php
                        $i++
                        @endphp
                        @endforeach
                    </tbody>
                    @endif
                    @if($category == 5)
                    <thead>
                        <tr class="bg-primary">
                            <td>Sl No.</td>
                            <td>Employee Code</td>
                            <td>Applicability</td>
                            <td>Proposed Revenue Category</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach($request AS $value)
                        <tr>
                            <td>{{$i}}</td><td>{{$value['created_by']}}</td><td>{{$value['applicable_name']}}</td><td>{{$value['transferred_resource_cat_name']}}</td><td>
                                @if($value['is_active'] == 0 && $value['cancel'] == 0)
                                <form method="post" id="form{{$value['expenditure']}}"><div class="form-group row"><div class="col-md-1"><button type="button" class="btn btn-xs btn-primary accept-reject-btn" id="accept_{{$value['expenditure_id']}}"><i class="fa fa-check"></i></button></div><div class="col-md-1"><button type="button" class="btn btn-xs btn-danger accept-reject-btn" id="reject_{{$value['expenditure_id']}}"><i class="fa fa-close"></i></button></div></div></form>
                                @elseif($value['is_active'] == 1 && $value['cancel'] == 0)
                                Active
                                @elseif($value['is_active'] == 0 && $value['cancel'] ==1)
                                Rejected
                                @endif
                            </td>
                        </tr>
                        @php
                        $i++
                        @endphp
                        @endforeach
                    </tbody>
                    @endif

                    @if($category == 6)
                    <thead>
                        <tr class="bg-primary">
                            <td>Sl No.</td>
                            <td>Employee Code</td>
                            <td>Proposed Revenue Category</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach($request AS $value)
                        <tr>
                            <td>{{$i}}</td><td>{{$value['created_by']}}</td><td>{{$value['scheme_name']}}</td><td>
                                @if($value['is_active'] == 0 && $value['cancel'] == 0)
                                <form method="post" id="form{{$value['expenditure']}}"><div class="form-group row"><div class="col-md-1"><button type="button" class="btn btn-xs btn-primary accept-reject-btn" id="accept_{{$value['expenditure_id']}}"><i class="fa fa-check"></i></button></div><div class="col-md-1"><button type="button" class="btn btn-xs btn-danger accept-reject-btn" id="reject_{{$value['expenditure_id']}}"><i class="fa fa-close"></i></button></div></div></form>
                                @elseif($value['is_active'] == 1 && $value['cancel'] == 0)
                                Active
                                @elseif($value['is_active'] == 0 && $value['cancel'] ==1)
                                Rejected
                                @endif
                            </td>
                        </tr>
                        @php
                        $i++
                        @endphp
                        @endforeach
                    </tbody>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')

<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function () {
$('#dataTable1').DataTable();
});
$(document).on('click', '.accept-reject-btn', function () {
var id = $(this).attr('id').split("_");
var url = "";
var message = "";
if (id[0] == "accept") {
url = "{{route('admin.survey.six_finance.accept_request_list.accept')}}";
@if ($category == 1)

        message = "You are going to accept proposed expenditure category! ";
@elseif($category == 2)

        message = "You are going to accept next 5 year proposal entities! ";
@elseif($category == 3)

        message = "You are going to accept a newly proposed designation! ";
@elseif($category == 4)

        message = "You are going to accept a newly proposed revenue category! ";
@elseif($category == 5)

        message = "You are going to accept a newly proposed Revenue Transfered Resources CSS category! ";
@elseif($category == 6)

        message = "You are going to accept a newly proposed Revenue Transfered Shared CSS category! ";
@endif

        } else {
url = "{{route('admin.survey.six_finance.reject_request_list.reject')}}";
@if ($category == 1)

        message = "You are going to reject proposed expenditure category! ";
@elseif($category == 2)

        message = "You are going to reject next 5 year proposal entities! ";
@elseif($category == 3)

        message = "You are going to reject newly proposed designation! ";
@elseif($category == 4)

        message = "You are going to reject newly proposed revenue category! ";
@elseif($category == 5)
        message = "You are going to reject newly proposed Revenue Transfered Resources CSS category! ";
@elseif($category == 6)
        message = "You are going to reject newly proposed Revenue Transfered Shared Resources CSS category! ";
@endif
        }
swal({
title: "Are you sure?",
        text: message,
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willAccept) => {

        if (willAccept) {
        $.ajax({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
                type: "POST",
                url: url,
                dataType: "json",
                data: {category: id[1], category_type:"{{$category}}"},
                cache: false,
                success: function (data) {
                if (data.msgType == true) {
                swal('Success', data.msg, 'success');
                location.reload();
                } else {
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
        });
});
</script>
@endsection