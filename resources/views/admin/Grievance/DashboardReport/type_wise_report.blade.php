@php
    $page_title="Grievance Type";
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
							   <td>Total Grievance</td>
							   <td>Resolved Grievance</td>
							   <td>Pending Grievance</td>
							   <td>Resolved Percentage(%)</td>
						    </tr>
						    </thead>
						    <tbody>
							    
							    @php
							    $grievRecieved=0;
							    $grievDisposed=0;
							    $pending = 0;
							    $grievPercent=0;
							    @endphp
							    
							    @if((isset($data['districtWiseGrievRecieved'][0])))
							    	@php
							    		$grievRecieved = $data['districtWiseGrievRecieved'][0];
							    	@endphp
							    @endif
							    
							    @if((isset($data['districtWiseGrievDisposed'][0])))
							    	@php
							    		$grievDisposed = $data['districtWiseGrievDisposed'][0];
							    	@endphp
							    @endif
							    
							    @php
							    		$pending = $grievRecieved-$grievDisposed;
							    
							    @endphp
							    
							    @if($grievRecieved!=0)
							    			@php
							    			$grievPercent = ($grievDisposed/$grievRecieved)*100;
							    			@endphp
							    @endif
							    
							    
							    
							    
							    <td>1</td>
							    <td>STATE</td>
							    <td>{{$grievRecieved}}</td>
							    <td>{{$grievDisposed}}</td>
							    <td>{{$pending}}</td>
							    <td>{{round($grievPercent,2)}}</td>
							    @php $i=2; @endphp
							    
							    
							    @foreach($district as $list)
							    
							    @php
							    $grievRecieved=0;
							    $grievDisposed=0;
							    $pending = 0;
							    $grievPercent=0;
							    @endphp
							    
							    @if((isset($data['districtWiseGrievRecieved'][$list->id])))
							    	@php
							    		$grievRecieved = $data['districtWiseGrievRecieved'][$list->id];
							    	@endphp
							    @endif
							    
							    @if((isset($data['districtWiseGrievDisposed'][$list->id])))
							    	@php
							    		$grievDisposed = $data['districtWiseGrievDisposed'][$list->id];
							    	@endphp
							    @endif
							    
							    @php
							    		$pending = $grievRecieved-$grievDisposed;
							    
							    @endphp
							    
							    @if($grievRecieved!=0)
							    			@php
							    			$grievPercent = ($grievDisposed/$grievRecieved)*100;
							    			@endphp
							    @endif
							    
							    <tr>
								    <td>{{$i}}</td>
								    <td>{{$list->district_name}}</td>
								    <td>{{$grievRecieved}}</td>
								    <td>{{$grievDisposed}}</td>
								    <td>{{$pending}}</td>
								    <td>{{$grievPercent}}</td>
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