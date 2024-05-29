@php
    $page_title="Grievance System";
@endphp


@extends('layouts.app_website')


@section('custom_css')
    <style>
	   .back {
        webkit-box-shadow: 1px 3px 3px 3px rgba(209,209,209,1);
        -moz-box-shadow: 1px 3px 3px 3px rgba(209,209,209,1);
        box-shadow: 1px 3px 15px 2px rgba(209,209,209,1);
        background-color:rgba(255, 255, 255, 0.90);
        padding:15px 25px;margin:25px 0px;
        min-height:355px;
    		}
    </style>
@endsection

@section('content')

<div class="container mb40 mt40">
	<div class="row">
		<div class="back" style="width:100%;">
			<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" style="text-align:center;">
				<img src="{{asset('mdas_assets/images/checked.gif')}}" style="width:150px" alt="Checked Image"/>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" style="text-align:center;">
					<h3>Dear <span style="color:red">{{$getParticipantDetials->p_name}}</span>,Thank You for showing your interest towards the training programme. We are looking forward for your active participation.</h3>
			</div>
		 </div>
	</div>
	
</div>




@endsection

@section('custom_js')
    <script> 
	    
    </script>
@endsection