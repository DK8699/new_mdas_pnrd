@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_admin_home')

@section('custom_css')
    <style>
        .cardd {
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            transition: 0.3s;
        }
        .cardd a {
            color: #6e133c;
        }
        .cardd:hover {
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
            transform: scale(1.1);
        }
        a.thumbnail.active, a.thumbnail:focus, a.thumbnail:hover {
            border-color: #6e133c;
            color: #6e133c;
        }
        a:focus, a:hover {
            color: #6e133c;
            text-decoration: underline;
        }
        .thumbnail a>img, .thumbnail>img {
            margin-right: auto;
            margin-left: auto;
            width: 20%;
        }
		.mb-40{
			margin-bottom: 40px;
		}
    </style>

@endsection

@section('content')

    <div class="container mb-40" style="margin-top: 80px">
        <div class="row">
			<!--<div class="col-xs-6 col-md-3 col-sm-4">
                <div class="cardd animated zoomIn">
                    <a href="{{route('admin.survey.six_finance')}}" class="thumbnail text-center">
                        <img src="{{asset('mdas_assets/images/6th finance.png')}}">
                        <p class="mt10">Sixth Assam State Finance Commission</p>
                    </a>
                </div>
            </div>!-->
            <div class="col-xs-6 col-md-3 col-sm-4">
                <div class="cardd animated zoomIn">
                <a href="{{route('admin.Pris.priMenu')}}" class="thumbnail text-center">
                    <img src="{{asset('mdas_assets/images/PRI.png')}}"/>
                    <p class="mt10">Panchayati Raj Institution <br/>(PRI)</p>
                </a>
                </div>
            </div>
			<div class="col-xs-6 col-md-3 col-sm-4">
                <div class="cardd animated zoomIn">
                <a style="height: 134px;" href="{{route('admin.Training.dashboard')}}" class="thumbnail text-center">
                    <img src="{{asset('mdas_assets/images/training.png')}}"/>
                    <p class="mt10">Need Based<br/>Training</p>
                </a>
                </div>
            </div>
			
            <div class="col-xs-6 col-md-3 col-sm-4">
                <div class="cardd animated zoomIn">
                <a href="{{route('admin.Osr.osr_dashboard')}}" class="thumbnail text-center">
                    <img src="{{asset('mdas_assets/images/OSR.png')}}"/>
                    <p class="mt10">Own Source of Revenue <br/>(OSR)</p>
                </a>
                </div>
            </div>
			
			<div class="col-xs-6 col-md-3 col-sm-4">
                <div class="cardd animated zoomIn">
                    <a href="{{route('admin.courtCases.dashboard')}}" class="thumbnail text-center">
                        <img src="{{asset('mdas_assets/images/courtcase.png')}}"/>
                        <p class="mt10">Court <br/>Cases</p>
                    </a>
                </div>
            </div>
			<div class="col-xs-6 col-md-3 col-sm-4">
                <div class="cardd animated zoomIn">
                <a href="{{route('admin.Uc.dashboard')}}" class="thumbnail text-center">
                    <img src="{{asset('mdas_assets/images/uc.png')}}"/>
                    <p class="mt10">Utilization Certificate <br/>(UC)</p>
                </a>
                </div>
            </div>
			 <div class="col-xs-6 col-md-3 col-sm-4">
                    <div class="cardd animated zoomIn">
                        <a href="{{route('admin.Grievance.dashboard')}}" class="thumbnail text-center">
                            <img src="{{asset('mdas_assets/images/grievance.png')}}"/>
                            <p class="mt10">Grievance <br/>System</p>
                        </a>
                    </div>
            </div>
			<div class="col-xs-6 col-md-3 col-sm-4">
                <div class="cardd animated zoomIn">
                    <a href="javascript:void(0);" class="thumbnail text-center mission_redirect">
                        <img src="{{asset('mdas_assets/images/mission.png')}}"/>
                        <p class="mt10">Misson <br/>Antyodaya</p>
                    </a>
                </div>
            </div>
			<div class="col-xs-6 col-md-3 col-sm-4">
                <div class="cardd animated zoomIn">
                    <a href="javascript:void(0);" class="thumbnail text-center geo_redirect">
                        <img src="{{asset('mdas_assets/images/gis.png')}}"/>
                        <p class="mt10">Geo Informatics System <br/>(GIS)</p>
                    </a>
                </div>
            </div>
			<div class="col-xs-6 col-md-3 col-sm-4">
                <div class="cardd animated zoomIn">
                    <a href="javascript:void(0);" class="thumbnail text-center sdg_redirect">
                        <img src="{{asset('mdas_assets/images/sdg.png')}}"/>
                        <p class="mt10">Sustainable Development Goals <br/>(SDG)</p>
                    </a>
                </div>
            </div>
			<div class="col-xs-6 col-md-3 col-sm-4">
                <div class="cardd animated zoomIn">
                    <a href="javascript:void(0);" class="thumbnail text-center samparka_redirect">
                        <img src="{{asset('mdas_assets/images/Samparka.png')}}"/>
                        <p class="mt10"><br/>Samparka</p>
                    </a>
                </div>
            </div>
			<div class="col-xs-6 col-md-3 col-sm-4">
                <div class="cardd animated zoomIn">
                    <a href="{{route('admin.UsersManagement.user_dashboard')}}" class="thumbnail text-center">
                        <img src="{{asset('mdas_assets/images/user management.png')}}"/>
                        <p class="mt10">User <br/>Management</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
<script>
	$('.mission_redirect').on('click',function(){
		
		var r = confirm("You will be redirected to another page");
                    if (r == true) {
					window.open('https://missionantyodaya.nic.in/');
				}
			else {
                        swal("Information", "Cancelled the action", 'info');
                    }
	})
	
	$('.geo_redirect').on('click',function(){
		
		var r = confirm("You will be redirected to another page");
                    if (r == true) {
					window.open('http://103.241.146.57/gis_frontend'); 
					// window.open('http://103.241.146.57/gis_frontend'); // replace with new route 
				}
			else {
                        swal("Information", "Cancelled the action", 'info');
                    }
	})
	$('.sdg_redirect').on('click',function(){
		
		var r = confirm("You will be redirected to another page");
                    if (r == true) {
					window.open('https://pnrd.assam.gov.in/frontimpotentdata/sustainable-development-goals-sdgs');
				}
			else {
                        swal("Information", "Cancelled the action", 'info');
                    }
	})
	$('.samparka_redirect').on('click',function(){
		
		var r = confirm("You will be redirected to another page");
                    if (r == true) {
					window.open('/samparka');
				}
			else {
                        swal("Information", "Cancelled the action", 'info');
                    }
	})
</script>

@endsection
