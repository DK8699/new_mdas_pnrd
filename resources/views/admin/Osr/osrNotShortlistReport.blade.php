@php
    $page_title="osr_assets_reports";
@endphp

@extends('layouts.app_admin')

@section('custom_css')
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>

    <style>
      
        .panel
        {
            border: none;
            background: #98D3F6;
        }
        label{
            color: dodgerblue;
        }
        .mb40{
            margin-bottom: 40px;
        }
        .badge-red{
            background-color: orangered;
        }
        .badge-green{
            background-color: darkgreen;
        }
        
        .panel-primary>.panel-heading {
            color: #fff;
            background-color: #337ab7;
        }
        
        table.dataTable thead td, table.dataTable thead td {
            padding: 10px 18px;
            border-bottom: 0px solid #111; 
        }
	    strong {
            color: red;
        }

    </style>
@endsection

@section('content')

<div class="row">
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}">Home</a></li>
        <li><a  href="{{route('admin.Osr.osr_dashboard')}}">OSR Non-Tax Resources</a></li>
        <li class="active">Not Shortlisted Asset Report</li>
    </ol>
</div>

<div class="container">
	
	<div class="row">
	
	 <h3 style="background-color:#d4b3c7; padding:5px;">{{$data['head_text']}}</h3>
	</div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive mb40">
                            <table class="table table-bordered table-striped" id="dataTable1">
                                <thead>
                                    <tr class="bg-primary">
                                        <td class="text-center">SL No.</td>
                                        <td class="text-center">Asst Code</td>
                                        <td class="text-center">Asset Name</td>
                                        <td class="text-center">Reason for Not Shortlisting</td>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @php  $i=1;  @endphp
							   @foreach($data['notShortlistAsset'] as $list)
                                   <tr>
							    <td>
								   {{$i}}
							    </td>
								<td>{{$list->asset_code}}</td>
								<td class="text-center">{{$list->asset_name}}</td>
								<td class="text-center">{{$list->reason}}</td>
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
	<script src="{{asset('mdas_assets/js/currencyFormatter.min.js')}}"></script>
    <script type="application/javascript">
        $(document).ready(function () {
            $('#dataTable1').DataTable({
                dom: 'Bfrtip',
			 ordering: false,
                paging: true,
                info: false,
                buttons: [
                    {
                        extend:    'excelHtml5',
                        title: 	   '{{$data['head_text']}}',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
        });
    </script>
@endsection