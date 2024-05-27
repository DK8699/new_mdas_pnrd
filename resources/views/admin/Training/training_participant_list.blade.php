@php
    $page_title="Need Based Training";
@endphp

@extends('layouts.app_admin_training')

@section('custom_css')
	<link href="{{asset('mdas_assets/css/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css"/>
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
    </style>
@endsection

@section('content')

<div class="container mt20 mb40">
	<div class="row mt40">
	
	   <!--Table to display the Media Entry date wise-->   
	
        <div class="col-md-12 col-sm-12 col-xs-12">
               <!-- <h3 style="background-color:#d4b3c7; padding:5px;"></h3><br/>-->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped no-wrap" id="dataTable">
                        <thead>
					    <tr style="background-color:#5f146f; color:#fff">
						   <td>Sl No.</td>
						   <td>Participant Name</td>
						   <td>Interested Programme</td>
						   <td>Interested Course</td>
						   <td>Training Centre</td>
						</tr>
                        </thead>
				    <tbody>
					    @php $i=1; @endphp
					    @foreach($participantDetails as $list)
						<tr>
						   <td>{{$i}}</td>
						   <td>{{$list->p_name}}</td>
						   <td>{{$list->programme_name}}</td>
						   <td>{{$list->course}}</td>
						   <td>{{$list->centre_name}}</td>
						</tr>
					    @endforeach
					    @php $i++; @endphp
				    </tbody>
                    </table>
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

@endsection