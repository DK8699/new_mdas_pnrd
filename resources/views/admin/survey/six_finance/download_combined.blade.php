@php
    $page_title="six_form";
@endphp

@extends('layouts.app_admin_home')

@section('custom_css')
    <link href="https://fonts.googleapis.com/css?family=DM+Serif+Text&display=swap" rel="stylesheet">
    <style>
        .form-control {
            display: inline-block;
            width: 59%;
            border-radius: 5px;
        }
        *{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        .pj-card{
            max-width: 1000px;
            height: 350px;
            display: inline-block;
            overflow: hidden;
            position: relative;
			
			
        }
        .pj-card a, .pj-card a:hover{
            color: inherit;
            text-decoration: none;
        }
        .pj-card h5{
            font-family: 'DM Serif Text', serif;
            position: absolute;
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            color: #fff;
            opacity: 0;
            -webkit-transition: .8s;
            -moz-transition: .8s;
            transition: .8s;
            font-size: 24px;
        }
        .pj-card:hover h5{
            opacity: 1;
        }
        .pj-card img{
            object-fit: cover;
            height: 400px;
            min-width: 400px;
            width: 100%;
            -webkit-transition: .8s;
            -moz-transition: .8s;
            transition: .8s;
        }
        .pj-card:hover img{
            filter: brightness(30%);
            -webkit-filter: brightness(30%);
        }
        .pj-card .description{
            background: #07b0e3;
            position: absolute;

            width: 100%;
            height: 250px;
            bottom: -200px;
            -webkit-transition: .8s;
            -moz-transition: .8s;
            transition: .8s;
        }
        .pj-card .description h4{
            font-family: 'DM Serif Text', serif;
            line-height: 50px;
            color: #fff;
        }
        .pj-card:hover .description{
            bottom: 0;
        }
        .centered{
            position: absolute;
            padding: 5px;
            top: 50%;
            left: 0;
            width: 100%;
            transform: translateY(-50%);
        }

        @media screen and (max-width: 480px){
            .pj-card{max-width: 100%}
        }

        @media screen and (max-width: 900px){
            .centered{
                position: relative;
                top: 0;
                transform: translateY(0);
            }
            body{
                padding-top: 10px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            <li><a href="{{route('dashboard')}}">Sixth Assam State Finance</a></li>
            <li class="active">Report</li>
        </ol>
    </div>
    <div class="container">
        <div class="centered text-center">
            <div class="pj-card"  style="margin-top:230px">
                <img src="{{asset('mdas_assets/images/data.jpg')}}" class="img-fluid" alt="">
                <h5><a href="">DISTRICT WISE</a></h5>
                <div class="description">
                    <h4>Download Combined Report</h4>
                    <form action="{{route('survey.six_finance.download_distCombined')}}" method="POST">
                        {{csrf_field()}}
                        <select class="form-control" name="req_for"  style="margin-bottom: 10px;" required>
                            <option value="">-------------------Select Type--------------------</option>
                                <option  value="DWC_ZP"> ZILA PARISHAD</option>
                                <option  value="DWC_AP"> ANCHALIK PANCHAYAT</option>
                                <option  value="DWC_GP"> GRAM PANCHAYAT</option>
                        </select>
                        <select class="form-control" name="d_id"  style="margin-bottom: 10px;"required>
                            <option value="">-----------------Select District----------------</option>
                            @foreach($districts AS $dist)
                                <option  value="{{$dist->id}}"> {{$dist->district_name}}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary" style="border-radius: 5px; font-family: 'DM Serif Text', serif;"> <i class="fa fa-download" style="; font-size: 24px;"></i> Download Combined Report</button>
                    </form>
                </div>
            </div>
    </div>
	</div>

@endsection

@section('custom_js')

    <script type="application/javascript">



    </script>
@endsection
