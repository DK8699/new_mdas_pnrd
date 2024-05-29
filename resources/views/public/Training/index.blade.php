@php
    $page_title="Grievance System";
@endphp


@extends('layouts.app_website')


@section('custom_css')
	<link href="{{asset('mdas_assets/css/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .panel-heading{
            border: 1px solid #6b133d33;
            background-color:rgb(117, 22, 65);
            max-height: 80px;
            min-height: 50px;
        }
        .card span {
            font-weight: bolder;
        }
	    
        strong {
            color: red;
        }
	    .mb40{
            margin-bottom: 40px;
        }
	    .mt20{
		    margin-top: 20px;
	    }
	    .mt40{
		    margin-top: 40px;
	    }
	    .btn, label {
            font-size:10pt;
        }
	   .form-control {
        width: 100%;
        height: 40px;
        padding: 6px 12px;
        font-size: 14px;
        background-color: #fff;
        border: 1px solid #ccc;

    		}
	    .form-control:focus {
		   color: #616161;
		   border-color: #beb6b6;
	    }
	   input[type=number]::-webkit-inner-spin-button,
		input[type=number]::-webkit-outer-spin-button {
		    -webkit-appearance: none;
		    -moz-appearance: none;
		    appearance: none;
		    margin: 0;
	   }
	    .btn::before{
		    background:#fff;
	    }
	    .bootstrap-select>.dropdown-toggle{
		    border:1px solid;
		    border-radius: 6px;
	    }
	    .btn-default.active, .btn-default:active, .open>.dropdown-toggle.btn-default{
		    background: #fff;
	    }
	    .dropdown-menu>li>a {
		    color: #151414;
		}
	    .btn-primary {
	    		background: #751641;
	    		color: #fff;
	    }
	    .btn-primary:hover::after{
		    background: #751641;
	    }
	    .btn-primary:hover::before{
		    background: #751641;
	    }
	    .about-content{
		    background-color: #462634;
	    }
    </style>
@endsection

@section('content')
<!--<div class="container">
	<div class="row" id="container" style="margin: 10px 0 15px 0; height: 255px; position: relative">
		<div class="col-md-6 col-xs-6 col-sm-6">
			
		</div>
	</div>
	<div class="row">
		<div class="col-md-3 col-xs-6 col-sm-6">
			<input type="text" class="form-control start_date" name="start_date" id="start_date" value="" style="height:35px"/>
		</div>
	</div>
</div>-->
<div class="container mb40 mt40">
	<div class="row">
		<div class="col-md-6 col-xs-12 col-sm-12">
			<div class="about-content">
				<div class="widget-box categories-box" style="color:white;">
					<h3 class="title" style="color:white;">Upcoming Trainings</h3><br />
						<marquee class="marquee" behavior="scroll" direction="up" scrollamount="2" onmouseover="this.stop();" onmouseout="this.start();">
							 <ul style="list-style:none;height:200px;font-size:13px;margin:0;padding:0;">
								 @foreach($data['trainings_upcoming'] as $list)
								<li>
									<a href="{{route('training.index.view_more',('UPCOMING'))}}" target="_blank" style="color:#eee">
										<i class="icofont-dotted-right"></i>
											{{$list->course}} <!--<span style="color:red">-{{$list->centre_name}}</span>-->
									</a>
								</li><br>
								 @endforeach
							 </ul>
						 </marquee>
			    </div>
              			<a href="{{route('training.index.view_more',('UPCOMING'))}}" target="_blank"class="btn btn-primary" style="border:2px solid black;padding:5px 10px;">View More</a>
      		</div>
		</div>
		<div class="col-md-6 col-xs-12 col-sm-12">
			<div class="about-content">
				<div class="widget-box categories-box" style="color:white;">
					<h3 class="title" style="color:white;">Trainings Conducted</h3><br />
						<marquee class="marquee" behavior="scroll" direction="up" scrollamount="2" onmouseover="this.stop();" onmouseout="this.start();">
							 <ul style="list-style:none;height:200px;font-size:13px;margin:0;padding:0;">
								 @foreach($data['trainings_conducted'] as $list)
								<li>
									<a href="{{route('training.index.view_more',('CONDUCTED'))}}" target="_blank" style="color:#eee">
										<i class="icofont-dotted-right"></i>
											{{$list->course}} <!--<span style="color:red">-{{$list->centre_name}}</span>-->
									</a>
								</li><br>
								 @endforeach
							 </ul>
						 </marquee>
			    </div>
              			<a href="{{route('training.index.view_more',('CONDUCTED'))}}" target="_blank" class="btn btn-primary" style="border:2px solid black;padding:5px 10px;">View More</a>
      		</div>
		</div>
	</div>
</div>




@endsection

@section('custom_js')
<script src="{{asset('mdas_assets/js/bootstrap-select.min.js')}}"></script>
    <script>
	    
	   $('.schemeList').selectpicker(); 
	    
	   //$('#ref_date').Zebra_DatePicker();
	    $('#start_date').Zebra_DatePicker({
		    always_visible: $('#container'),
		    format: 'm Y',
		});
	    
    </script>
@endsection