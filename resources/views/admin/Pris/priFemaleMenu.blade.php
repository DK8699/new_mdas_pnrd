@php
    $page_title="priMenu";
@endphp

@extends('layouts.app_admin_home')

@section('custom_css')
    <link href="https://fonts.googleapis.com/css?family=Old+Standard+TT&display=swap" rel="stylesheet">
    <style>
        body{
            margin: 0px;
            padding: 0px;
            /*background: #e74c3c;*/
            /*font-family: 'Lato', sans-serif;*/

        }

        

        .dangorbackgroundimage {
            background-image: url({{asset('mdas_assets/images/pri_village.jpg')}});
            padding: 4%;
            background-size: cover;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            <li class="active"><a href="{{route('admin.Pris.priMenu')}}"> Back To PRIs Dashboard</a></li>
        </ol>
    </div>
    <div class="container">
        <div class="row">
            <h1 style="text-align: center; color: orangered;font-family: 'Old Standard TT', serif;"><u>FEMALE SARPANCHE REPORT</u></h1>
            <nav class="nav_s">
                <ul>
                    <li><a href="{{route('admin.Pris.quickReportFemaleZP')}}">Zila Parishad</a></li>
                    <li><a href="{{route('admin.Pris.quickReportFemaleAP')}}">Anchalik Parishad</a></li>
                    <li><a href="{{route('admin.Pris.quickReportFemaleGP')}}">Gram Panchayat</a></li>
                </ul>
            </nav>
        </div>
        <div class="row mt20">
        </div>
        <div class="row mt10">
            <div class="col-md-12 col-sm-12 col-xs-12 text-right">
            </div>
        </div>
    </div>
    </div>

@endsection

@section('custom_js')
@endsection
