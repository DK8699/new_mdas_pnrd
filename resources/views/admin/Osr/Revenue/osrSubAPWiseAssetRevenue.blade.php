@php
    $page_title="osrSubDistrictWiseAssetSettlement";
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
            <li class="active">Revenue Collection </li>
        </ol>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 mb20">

              <a class="btn btn-info" href="{{route('admin.Osr.subDistrictWiseRevenue',[$id,$fy_id])}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 mt10">
            <div class="panel panel-primary">
                    <div class="panel-heading" style="text-align: center">
                         Revenue Collection by the Anchalik Panchayat : {{$ap_name}} and its Gram Panchayats under the district : {{$district_name}} {{$fy_years}}
                    </div>
                    <div class="panel-body gray-back">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable1">
                                <thead>
                                    <tr class="bg-primary">
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
										<td></td>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <p style="display: none;"></p>
                                   <tr>
                                       <td>1</td>
                                       <td><a href="{{route('admin.Osr.osrRevenueAssetBranchList',[$id,$fy_id,$ap_id])}}">{{$ap_name}}</a></td>
                                       <td class="text-right">{{$dataCount['apData'][$ap_id]['gap_c']}}</td>
                                       <td class="text-right">{{$dataCount['apData'][$ap_id]['bid_c']}}</td>
                                       <td class="text-right">{{$dataCount['apData'][$ap_id]['other_c']}}</td>
                                       <td class="text-right">{{$dataCount['apData'][$ap_id]['tot_c']}}</td>
                                       <td class="text-right">{{$dataCount['apData'][$ap_id]['zp_share']}}</td>
                                       <td class="text-right">{{$dataCount['apData'][$ap_id]['gp_share']}}</td>
                                       <td class="text-right">{{$dataCount['apData'][$ap_id]['zp_share_to_ap']}}</td>
									{{--<td class="text-right">{{$dataCount['apData'][$ap_id]['gps_share_to_ap']}}</td>--}}
                                       <td class="text-right" >{{$dataCount['apData'][$ap_id]['tot_a_b']}}</td>
									    <td></td>
                                   </tr>
								
                                    <tr class="bg-pprimary">
                                        <td>Sr. No.</td>
                                        <td>Gram Panchayat</td>
										
                                        <td class="text-center">Gap Period Revenue Collection from Assets (Cr.)</td>
										<td class="text-center">Revenue Collection from Bids (Cr.)</td>
										<td class="text-center">Revenue Collection from Other Assets (Cr.)</td>
                                        <td class="text-center">Total Revenue Collection (Cr.)</td>
                                        <td class="text-center">Shared Amount Distributed to ZP (Cr.)</td>
                                        <td class="text-center">Shared Amount Distributed to AP (Cr.)</td>
                                        <td class="text-center">Shared Amount Received from ZP (Cr.)</td>
                                        <td class="text-center">Shared Amount Received from AP (Cr.)</td>

                                        <td class="text-center">Total Available Revenue (Cr.)</td>
                                    </tr>

                                    @php
                                        $i=1;
                                        $gap_c=0;
                                        $bid_c=0;
                                        $other_c=0;
                                        $tot_c=0;

                                        $zp_share=0;
                                        $ap_share=0;

                                        $zp_share_to_gp=0;
                                        $ap_share_to_gp=0;
                                        $tot_a_b=0;
                                    @endphp
                                @foreach($gp_list as $gp)
                                   <tr>
                                       <td>{{$i++}}</td>
                                       <td><a href="{{route('admin.Osr.osrRevenueAssetBranchList',[$id,$fy_id,$ap_id,$gp->id])}}">{{$gp->gram_panchayat_name}}</a></td>
                                       <td class="text-right">{{$dataCount['gpDataList'][$gp->id]['gap_c']}}</td>
                                       <td class="text-right">{{$dataCount['gpDataList'][$gp->id]['bid_c']}}</td>
                                       <td class="text-right">{{$dataCount['gpDataList'][$gp->id]['other_c']}}</td>
                                       <td class="text-right">{{$dataCount['gpDataList'][$gp->id]['tot_c']}}</td>
                                       <td class="text-right">{{$dataCount['gpDataList'][$gp->id]['zp_share']}}</td>
                                       <td class="text-right">{{$dataCount['gpDataList'][$gp->id]['ap_share']}}</td>
                                       <td class="text-right">{{$dataCount['gpDataList'][$gp->id]['zp_share_to_gp']}}</td>
                                       <td class="text-right">{{$dataCount['gpDataList'][$gp->id]['ap_share_to_gp']}}</td>
                                       <td class="text-right">{{$dataCount['gpDataList'][$gp->id]['tot_a_b']}}</td>
                                   </tr>
                                   @php
                                       $gap_c=$gap_c+$dataCount['gpDataList'][$gp->id]['gap_c'];
                                       $bid_c=$bid_c+$dataCount['gpDataList'][$gp->id]['bid_c'];
                                       $other_c=$other_c+$dataCount['gpDataList'][$gp->id]['other_c'];
                                       $tot_c=$tot_c+$dataCount['gpDataList'][$gp->id]['tot_c'];

                                       $zp_share=$zp_share+$dataCount['gpDataList'][$gp->id]['zp_share'];
                                       $ap_share=$ap_share+$dataCount['gpDataList'][$gp->id]['ap_share'];

                                       $zp_share_to_gp=$zp_share_to_gp+$dataCount['gpDataList'][$gp->id]['zp_share_to_gp'];
                                       $ap_share_to_gp=$ap_share_to_gp+$dataCount['gpDataList'][$gp->id]['ap_share_to_gp'];
                                       $tot_a_b=$tot_a_b+$dataCount['gpDataList'][$gp->id]['tot_a_b'];
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
                                        <td class="text-right">{{$ap_share}}</td>
                                        <td class="text-right">{{$zp_share_to_gp}}</td>
                                        <td class="text-right">{{$ap_share_to_gp}}</td>
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
						title: 	   'Revenue Collection by the Anchalik Panchayat : {{$ap_name}} and its Gram Panchayats Under the District : {{$district_name}} {{$fy_years}}',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
        });
    </script>
@endsection