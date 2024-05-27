@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_admin')

@section('custom_css')

@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            <li><a href="{{route('admin.survey.six_finance')}}">Sixth Assam State Finance</a></li>
            <li class="active">Accept/Reject Employee Request</li>
        </ol>
    </div>

    <div class="container mt20">
        <div class="row">
            <div class="col-xs-6 col-md-3 col-sm-4">
                <a href="{{route('admin.survey.six_finance.accept_reject_request_list.employee_request',1)}}" class="thumbnail text-center">
                    <i class="fa fa-rupee fa-4x"></i>
                    <h6>Expenditure</h6>
                </a>
            </div>

            <div class="col-xs-6 col-md-3 col-sm-4">
                <a href="{{route('admin.survey.six_finance.accept_reject_request_list.employee_request',2)}}" class="thumbnail text-center">
                    <i class="fa fa-line-chart fa-4x"></i>
                    <h6>Proposals</h6>
                </a>
            </div>
            <div class="col-xs-6 col-md-3 col-sm-4">
                <a href="{{route('admin.survey.six_finance.accept_reject_request_list.employee_request',3)}}" class="thumbnail text-center">
                    <i class="fa fa-user fa-4x"></i>
                    <h6>Staff Designation</h6>
                </a>
            </div>
            <div class="col-xs-6 col-md-3 col-sm-4">
                <a href="{{route('admin.survey.six_finance.accept_reject_request_list.employee_request',4)}}" class="thumbnail text-center">
                    <i class="fa fa-money fa-4x"></i>
                    <h6>Revenue</h6>
                </a>
            </div>
            <div class="col-xs-6 col-md-3 col-sm-4">
                <a href="{{route('admin.survey.six_finance.accept_reject_request_list.employee_request',5)}}" class="thumbnail text-center">
                    <i class="fa fa-money fa-4x"></i>
                    <h6>Revenue Transfer Resources Css</h6>
                </a>
            </div>
            <div class="col-xs-6 col-md-3 col-sm-4">
                <a href="{{route('admin.survey.six_finance.accept_reject_request_list.employee_request',6)}}" class="thumbnail text-center">
                    <i class="fa fa-money fa-4x"></i>
                    <h6>Revenue Css Share</h6>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')

@endsection