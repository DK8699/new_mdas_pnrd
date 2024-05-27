@php
    $page_title="Grievance Schemes";
@endphp

@extends('layouts.app_admin_griev')

@section('custom_css')

   <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
   <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
	
	<style>
		.panel-default>.panel-heading {
		    background-color: #6e133c;
		}
	</style>
	   
@endsection

@section('content')
	
	<div class="container mb40">
        <div class="row mt40">
		  <div class="panel panel-default mt10">
                    <div class="panel-heading" style="text-align: center; color:#fff">
					{{$head_text}}
                    </div>
                    <div class="panel-body gray-back">
				 <table class="table table-striped table-bordered table-responsive" id="dataTable">
						    <thead>
						    <tr style="background-color:#5f146f; color:#fff">
							   <td>SL</td>
							   <td>District/State</td>
							   <td>Media Grievance</td>
							   <td>Individual Grievance</td>
						    </tr>
						    </thead>
						    <tbody>
							    <td>1</td>
							    <td>STATE</td>
							    <td>@if(isset($data['schemeWiseMediaGriev'][0])){{$data['schemeWiseMediaGriev'][0]}}@else{{0}}@endif</td>
							    <td>@if(isset($data['schemeWiseIndividualGriev'][0])){{$data['schemeWiseIndividualGriev'][0]}}@else{{0}}@endif</td>
							  @php $i=2; @endphp
								@foreach($district as $list)
							    <tr>
								  <td>{{$i}}</td>
								  <td>{{$list->district_name}}</td>
								  <td>@if(isset($data['schemeWiseMediaGriev'][$list->id])){{$data['schemeWiseMediaGriev'][$list->id]}}@else{{0}}@endif</td>
								  <td>@if(isset($data['schemeWiseIndividualGriev'][$list->id])){{$data['schemeWiseIndividualGriev'][$list->id]}}@else{{0}}@endif</td>
							   </tr>
							    @php $i++; @endphp
								@endforeach
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

	<script>
	   $(document).ready(function () {
            $('#dataTable').DataTable({
                dom: 'Bfrtip',
                ordering: false,
                paging: false,
                buttons: [
                    {
                        extend:    'excelHtml5',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
        });
	</script>
@endsection