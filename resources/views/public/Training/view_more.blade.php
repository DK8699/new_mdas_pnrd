@php
    $page_title="Grievance System";
@endphp


@extends('layouts.app_website')


@section('custom_css')
	<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
	<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <style>
	    .mb40{
            margin-bottom: 40px;
        }
	    .mt20{
		    margin-top: 20px;
	    }
	    .mt40{
		    margin-top: 40px;
	    }
	    .list-group-item.active, .list-group-item.active:focus, .list-group-item.active:hover {
	    color: #fff;
	    background-color: #6e133c;
	    border-color: #3f0e3b;
	    }
	   
	    
    </style>
@endsection

@section('content')
<div class="container mt40 mb40">
	  <!--Table to display the Media Entry date wise-->   
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<h3 class="text-center">{{$data['head_txt']}}</h3>
		</div>
	</div>
	<div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
               <!-- <h3 style="background-color:#d4b3c7; padding:5px;"></h3><br/>-->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped no-wrap" id="dataTable">
                        <thead>
					    <tr style="background-color:#5f146f; color:#fff">
						   <td>Sl. No.</td>
						   <td>Centre Name.</td>
						   <td>Courses</td>
						   <td>Date of Training</td>
						</tr>
                        </thead>
				    <tbody>
					    @php $i=1; @endphp
					    @foreach($data['allTrainings'] as $list)
						<tr>
							
							   <td>{{$i}}</td>
							   <td>{{$list->centre_name}}</td>
							   <td>
								   {{$list->course}} <span style="color:red">-{{$list->no_of_days}} Days</span>
								   @if($type_status == 'UPCOMING')
								   <a href="{{route('training.participants.entry',[Crypt::encrypt($list->training_id),Crypt::encrypt($list->loc_id)])}}" class="btn btn-info btn-sm">Apply</a>
								   @else
								   @endif

							   </td>

							   <td>{{\Carbon\Carbon::parse($list->start_date)->format('d M Y')}}</td>
							
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
                paging: true,
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