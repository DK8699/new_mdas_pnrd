@php
    $page_title="Grievance Details";
@endphp

@extends('layouts.app_admin_griev')

@section('custom_css')
 	<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
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
	    .btn, label {
            font-size:12pt;
        }
	    .form-control {
		    height: 40px;
		}
	    .show-grid [class^=col-] {
	    padding-top: 18px;
	    padding-bottom: 18px;
	    border-top: 2px solid #ddd;
	    border-top: 2px solid rgba(86,61,124,.2);
	    }
	    .box-title {
		    padding-top: 20px;
		    text-align: center;
		    width: 100%;
		    color: #5d5d5d;
		}
	    .head{
		    font-weight: 900;
		    font-size: 15px;
	    }
	    .text-justify {
		    text-align: justify!important;
		}
	    .back {
        webkit-box-shadow: 1px 3px 3px 3px rgba(209,209,209,1);
        -moz-box-shadow: 1px 3px 3px 3px rgba(209,209,209,1);
        box-shadow: 1px 3px 15px 2px rgba(209,209,209,1);
        background-color:rgba(255, 255, 255, 0.90);
        padding:15px 25px;margin:25px 0px;
        min-height:555px;
    		}
	     h3 {
        font-weight:bold;
    }
	</style>
@endsection

@section('content')

<div class="container mb40 mt20">
	<div class="back" style="width:100%;">
	
		<div class="box bg-gray-light box-info">
		    <div class="box-header with-border">
			   <h3 class="box-title text-blue" style="margin-bottom: 30px;">Grievance Details
					<span class="pull-right">
						<a href="{{url('admin/Grievance/download/Acknowledgement')}}/{{encrypt($griev_id)}}" target="_blank" style="text-decoration:none" class="btn-primary btn-lg"> <i class="fa fa-print" aria-hidden="true"></i> Print</a>
				    </span>
			    </h3>
			    
		    </div>
		    <div class="box-body">
			   <div class="row show-grid">
				  <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><span class="head">Grievance Number</span></div>
				  <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">{{$grievData->grievance_code}}</div>
			   </div>
			   <div class="row show-grid">
				  <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><span class="head">Name</span></div>
				  <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">{{$grievData->name}}</div>
			   </div>
			   <div class="row show-grid">
				  <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
					 <span class="head">Date of receipt</span>
				  </div>
				  <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">{{$grievData->entry_date}}</div>
			   </div>
			   <div class="row show-grid">
				  <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><span class="head">Mobile Number</span></div>
				  <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">{{$grievData->mobile_no}}</div>
			   </div>
			   <div class="row show-grid">
				  <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
					 <span class="head">Email ID</span>
				  </div>
				  <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">{{$grievData->email_id}}</div>
			   </div>
			  <div class="row show-grid">
				  <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
					 <span class="head">Grievance Description</span>
				  </div>
				  <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-justify"></div>
			   </div>
			   <div class="row" style="margin-bottom:12px">
				  <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
					  {{$grievData->grievance_details}}
					  
				  </div>
			   </div>
			   <div class="row show-grid">
				  <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
					 <span class="head">Attached Document</span>
				  </div>
				  <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
					  @if(isset($grievData->document))
									<a href="{{route('admin.grievance.Individual.Document.view', [encrypt($grievData->id)])}}" target="_blank" id="attachment_view_link1">
										<i class="fa fa-2x fa-file-pdf-o text-red"></i>
									</a>
							@else
								No file attached
						    @endif
					
				  </div>
			   </div>
			   <div class="row show-grid">
				  <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
					 <span class="head">&nbsp;</span>
				  </div>
			   </div>

		    </div>
		</div>
	
	</div>
</div>



@endsection

@section('custom_js')

    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <script> 
    </script>
@endsection