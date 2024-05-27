@php
    $page_title="Grievance System";
@endphp

@extends('layouts.app_admin_griev')

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

<div class="container mb40 mt20">
	<div class="row">
		<div class="back" style="width:100%;">
			<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" style="text-align:center;">
				<img src="{{asset('mdas_assets/images/checked.gif')}}" style="width:150px" alt="Checked Image"/>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" style="text-align:center;">
					<h3>Grievance Registered with <span style="font-weight:bold; font-style:italic">Grievance code: {{$GrievCode->grievance_code}}</span></h3>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" style="text-align:center;"><br><br>
				<a href="{{route('admin.Grievance.individual_griev_entry')}}" style="text-decoration:none" class="btn-primary btn-lg"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
				<a href="{{url('admin/Grievance/download/Acknowledgement')}}/{{encrypt($id)}}" target="_blank" style="text-decoration:none" class="btn-primary btn-lg"> <i class="fa fa-print" aria-hidden="true"></i> Print</a>
			</div>
		 </div>
	</div>
	
</div>




@endsection

@section('custom_js')
    <script>
	    
	    
    </script>
@endsection