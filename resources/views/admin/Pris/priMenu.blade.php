@php
$page_title="priMenu";
@endphp

@extends('layouts.app_admin_home')

@section('custom_css')
<link href="https://fonts.googleapis.com/css?family=Old+Standard+TT&display=swap" rel="stylesheet">
<style>
    .cardd {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        transition: 0.3s;
    }

    .cardd a {
        color: #6e133c;
    }

    .cardd:hover {
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        transform: scale(1.1);
    }

    a.thumbnail.active,
    a.thumbnail:focus,
    a.thumbnail:hover {
        border-color: #6e133c;
        color: #6e133c;
    }

    a:focus,
    a:hover {
        color: #6e133c;
        text-decoration: underline;
    }

    .thumbnail a>img,
    .thumbnail>img {
        margin-right: auto;
        margin-left: auto;
        width: 20%;
    }

    .mt10 {
        margin-top: 10px;
        font-weight: 600;
    }

    .thumbnail {

        border: 1px solid #e6046a;
    }

    body {
        margin: 0px;
        padding: 0px;
        /*background: #e74c3c;*/
        /*font-family: 'Lato', sans-serif;*/

    }
</style>
@endsection

@section('content')
<div class="row">
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    </ol>
</div>


<div class="container" style="margin-top: 80px">
    <div class="row">


        <div class="col-xs-6 col-md-3 col-sm-4">
            <div class="cardd animated zoomIn">
                <a href="{{route('admin.Pris.priDistrictWiseProgressReportZP')}}" class="thumbnail text-center">
                    <i class="fa fa-id-card fa-3x" aria-hidden="true"></i>
                    <p class="mt10">Progress Report ZP Wise</p>
                </a>
            </div>
        </div>

        <div class="col-xs-6 col-md-3 col-sm-4">
            <div class="cardd animated zoomIn">
                <a href="{{route('admin.Pris.reportAdmin')}}" class="thumbnail text-center">
                    <i class="fa fa-search fa-3x" aria-hidden="true"></i>
                    <p class="mt10">PRIs as ZP, AP, GP Wise</p>
                </a>
            </div>
        </div>

        <div class="col-xs-6 col-md-3 col-sm-4">
            <div class="cardd animated zoomIn">
                <a href="{{route('admin.Pris.priDistrictWiseGenderZP')}}" class="thumbnail text-center">
                    <i class="fa fa-venus-mars fa-3x" aria-hidden="true"></i>
                    <p class="mt10">PRIs View by Gender Wise</p>
                </a>
            </div>
        </div>

        <div class="col-xs-6 col-md-3 col-sm-4">
            <div class="cardd animated zoomIn">
                <a href="{{route('admin.Pris.priDistrictWiseQualiReport')}}" class="thumbnail text-center">
                    <i class="fa fa-graduation-cap fa-3x" aria-hidden="true"></i>
                    <p class="mt10">PRIs View by Qualification</p>
                </a>
            </div>
        </div>

        <div class="col-xs-6 col-md-3 col-sm-4">
            <div class="cardd animated zoomIn">
                <a href="{{route('admin.Pris.priDistrictWisePartyZP')}}" class="thumbnail text-center">
                    <i class="fa fa-university fa-3x" aria-hidden="true"></i>
                    <p class="mt10">PRIs View by Political Party</p>
                </a>
            </div>
        </div>

        <div class="col-xs-6 col-md-3 col-sm-4">
            <div class="cardd animated zoomIn">
                <a href="{{route('admin.Pris.priDownloadMenu')}}" class="thumbnail text-center">
                    <i class="fa fa-check fa-3x" aria-hidden="true"></i>
                    <p class="mt10">Elected Members Details</p>
                </a>
            </div>
        </div>

        <div class="col-xs-6 col-md-3 col-sm-4">
            <div class="cardd animated zoomIn">
                <a href="{{route('admin.Pris.priFemaleMenu')}}" class="thumbnail text-center">
                    <i class="fa fa-female fa-3x" aria-hidden="true"></i>
                    <p class="mt10">Female Sarpanche Report</p>
                </a>
            </div>
        </div>
        <!-- ning start -->
        <div class="col-xs-6 col-md-3 col-sm-4">
            <div class="cardd animated zoomIn">
                <a href="{{route('admin.Pris.bankProgressReport')}}" class="thumbnail text-center">
                    <i class="fa-solid fa-piggy-bank fa-3x" aria-hidden="true"></i>
                    <p class="mt10">Bank Progress Report</p>
                </a>
            </div>
        </div>
        <!--  ning end -->
    </div>
    <div class="row mt10">

    </div>
</div>

@endsection

@section('custom_js')
@endsection