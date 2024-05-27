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
            <li class="active">Sixth Assam State Finance</li>
        </ol>
    </div>

    <div class="container mt20">
        <div class="row">
            {{--<div class="col-xs-6 col-md-3 col-sm-4">
                <a href="#" class="thumbnail text-center">
                    <i class="fa fa-users fa-4x"></i>
                    <h6>ASSIGN GPC</h6>
                </a>
            </div>

            <div class="col-xs-6 col-md-3 col-sm-4">
                <a href="{{route('admin.survey.six_finance.accept_reject_request_list.accept_reject')}}" class="thumbnail text-center">
                    <i class="fa fa-plus fa-4x"></i>
                    <h6>MASTER CATEGORY</h6>
                </a>
            </div>

            <div class="col-xs-6 col-md-3 col-sm-4">
                <a href="{{route('admin.survey.six_finance.delete_request_list')}}" class="thumbnail text-center">
                    <i class="fa fa-trash-o fa-4x"></i>
                    <h6>DELETE FILLED FORMS</h6>
                </a>
            </div>

            <div class="col-xs-6 col-md-3 col-sm-4">
                <a href="{{route('admin.survey.six_finance.combined_list')}}" class="thumbnail text-center">
                    <i class="fa fa-line-chart fa-4x"></i>
                    <h6>REPORT COMBINED [ZP, AP, GP]</h6>
                </a>
            </div>--}}

            <div class="col-xs-6 col-md-3 col-sm-4">
                <a href="{{route('admin.survey.six_finance.track_zp_ap_gp')}}" class="thumbnail text-center">
                    <i class="fa fa-check fa-4x"></i>
                    <h6>TRACK ZP, AP, GP</h6>
                </a>
            </div>
            <div class="col-xs-6 col-md-3 col-sm-4">
                <a href="{{route('admin.survey.six_finance.download_combined')}}" class="thumbnail text-center">
                    <i class="fa fa-download fa-4x"></i>
                    <h6>District Wise Combined</h6>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')

@endsection