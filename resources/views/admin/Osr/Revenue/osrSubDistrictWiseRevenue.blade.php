@php
    $page_title="priMenu";
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
        
        table.dataTable thead th, table.dataTable thead td {
            padding: 10px 18px;
            border-bottom: 0px solid #111; 
        }
        
        .bg-pprimary {
            color: #fff;
            background-color: #337ab7 !important;
        }
    </style>
@endsection




@section('content')

<div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Home</a></li>
            <li><a  href="{{route('admin.Osr.osr_dashboard')}}">OSR Non-Tax Resources</a></li>
            <li class="active">Revenue Collection</li>
        </ol>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 mb20">
            <a class="btn btn-info" href="{{route('admin.Osr.osr_dashboard')}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 mt10">
            <div class="panel panel-primary">
                <div class="panel-heading" style="text-align: center">
                    Revenue Collection by Zila Parishad : {{$district_name}} ({{$fy_years}})
                </div>
                <div class="panel-body gray-back">
                    <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable1">
                                <thead class="bg-primary">
                                    <tr>
                                        <td>Sr. No.</td>
                                        <td>Zila Parishad</td>
										<td class="text-center">Gap Period Revenue Collection from Assets (Cr.)</td>
										<td class="text-center">Revenue Collection from Bids (Cr.)</td>
										<td class="text-center">Revenue Collection from Other Assets (Cr.)</td>
                                        <td class="text-center">Total Revenue Collection (Cr.)</td>
                                        <td class="text-center">Shared Amount Distributed to APs (Cr.)</td>
                                        <td class="text-center">Shared Amount Distributed to GPs (Cr.)</td>
                                        <td class="text-center">Shared Amount Received from APs (Cr.)</td>
                                        {{--<td class="text-center">Shared Amount Received from GPs (Cr.)</td>--}}
                                        <td class="text-center">Total Available Revenue (Cr.)</td>
                                    </tr>
                                 </thead>
                                 <tbody>
                                   <tr>
                                       <td>1</td>
                                       <td><a href="{{route('admin.Osr.osrRevenueAssetBranchList',[$id,$fy_id])}}">{{$district_name}}</a></td>
                                       <td class="text-right">{{$dataCount['zpData'][$id]['gap_c']}}</td>
								       <td class="text-right">{{$dataCount['zpData'][$id]['bid_c']}}</td>
								       <td class="text-right">{{$dataCount['zpData'][$id]['other_c']}}</td>
								       <td class="text-right">{{$dataCount['zpData'][$id]['tot_c']}}</td>
								       <td class="text-right">{{$dataCount['zpData'][$id]['ap_share']}}</td>
								       <td class="text-right">{{$dataCount['zpData'][$id]['gp_share']}}</td>
								       <td class="text-right">{{$dataCount['zpData'][$id]['aps_share_to_zp']}}</td>
										   {{--<td class="text-right">{{$dataCount['zpData'][$id]['gps_share_to_zp']}}</td>--}}
								       <td class="text-right">{{$dataCount['zpData'][$id]['tot_a_b']}}</td>
                                   </tr>

                                    <tr class="bg-pprimary">
                                        <td>Sr. No.</td>
                                        <td>Anchalik Panchayat</td>
                                        <td class="text-center">Gap Period Revenue Collection from Assets (Cr.)</td>
										<td class="text-center">Revenue Collection from Bids (Cr.)</td>
										<td class="text-center">Revenue Collection from Other Assets (Cr.)</td>
                                        <td class="text-center">Total Revenue Collection (Cr.)</td>
                                        <td class="text-center">Shared Amount Distributed to ZP (Cr.)</td>
                                        <td class="text-center">Shared Amount Distributed to GPs (Cr.)</td>
                                        <td class="text-center">Shared Amount Received from ZP (Cr.)</td>
                                        {{--<td class="text-center">Shared Amount Received from GPs (Cr.)</td>--}}
										<td class="text-center">Total Available Revenue (Cr.)</td>
                                    </tr>

                                @php
                                    $i=1;
                                    $gap_c=0;
                                    $bid_c=0;
                                    $other_c=0;
                                    $tot_c=0;
                                    $zp_share=0;
                                    $gp_share=0;
                                    $zp_share_to_ap=0;
                                    $gps_share_to_ap=0;
                                    $tot_a_b=0;
                                @endphp
                                @foreach($ap_list as $ap)
                                   <tr>
                                       <td>{{$i++}}</td>
                                       <td>
                                           <a href="{{route('admin.Osr.subAPWiseAssetRevenue',[$id,$fy_id,$ap->id])}}">
                                               {{$ap->anchalik_parishad_name}}</a>
                                       </td>
                                       <td class="text-right">{{$dataCount['apDataList'][$ap->id]['gap_c']}}</td>
                                       <td class="text-right">{{$dataCount['apDataList'][$ap->id]['bid_c']}}</td>
                                       <td class="text-right">{{$dataCount['apDataList'][$ap->id]['other_c']}}</td>
                                       <td class="text-right">{{$dataCount['apDataList'][$ap->id]['tot_c']}}</td>
                                       <td class="text-right">{{$dataCount['apDataList'][$ap->id]['zp_share']}}</td>
                                       <td class="text-right">{{$dataCount['apDataList'][$ap->id]['gp_share']}}</td>
                                       <td class="text-right">{{$dataCount['apDataList'][$ap->id]['zp_share_to_ap']}}</td>
								{{--<td class="text-right">{{$dataCount['apDataList'][$ap->id]['gps_share_to_ap']}}</td>--}}
                                       <td class="text-right">{{$dataCount['apDataList'][$ap->id]['tot_a_b']}}</td>
                                   </tr>

                                   @php
                                       $gap_c=$gap_c+$dataCount['apDataList'][$ap->id]['gap_c'];
                                       $bid_c=$bid_c+$dataCount['apDataList'][$ap->id]['bid_c'];
                                       $other_c=$other_c+$dataCount['apDataList'][$ap->id]['other_c'];
                                       $tot_c=$tot_c+$dataCount['apDataList'][$ap->id]['tot_c'];

                                       $zp_share=$zp_share+$dataCount['apDataList'][$ap->id]['zp_share'];
                                       $gp_share=$gp_share+$dataCount['apDataList'][$ap->id]['gp_share'];

                                       $zp_share_to_ap=$zp_share_to_ap+$dataCount['apDataList'][$ap->id]['zp_share_to_ap'];
                                       $gps_share_to_ap=$gps_share_to_ap+$dataCount['apDataList'][$ap->id]['gps_share_to_ap'];
                                       $tot_a_b=$tot_a_b+$dataCount['apDataList'][$ap->id]['tot_a_b'];
                                   @endphp
                                @endforeach
                                   <tr class="bg-pprimary">
                                       <td></td>
                                       <td>
                                           Total
                                       </td>
                                       <td class="text-right">{{$gap_c}}</td>
                                       <td class="text-right">{{$bid_c}}</td>
                                       <td class="text-right">{{$other_c}}</td>
                                       <td class="text-right">{{$tot_c}}</td>
                                       <td class="text-right">{{$zp_share}}</td>
                                       <td class="text-right">{{$gp_share}}</td>
                                       <td class="text-right">{{$zp_share_to_ap}}</td>
										   {{--<td class="text-right">{{$gps_share_to_ap}}</td>--}}
                                       <td class="text-right">{{$tot_a_b}}</td>
                                   </tr>
                                </tbody>
                            </table>
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
    <script type="application/javascript">
        $(document).ready(function () {
            $('#dataTable1').DataTable({
                dom: 'Bfrtip',
                searching: false,
                ordering: false,
                paging: false,
                info: false,
                
				buttons: [
                    {
                        extend:    'excelHtml5',
						title: 	   'Revenue Collection by Zila Parishad : {{$district_name}} ({{$fy_years}})',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
        });
    </script>
@endsection