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
        
        table.dataTable thead td, table.dataTable thead td {
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
            <li class="active">Share Distribution </li>
        </ol>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 mb20">

              <a class="btn btn-info" href="{{route('admin.Osr.subDistrictWiseShare',[$id,$fy_id])}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 mt10">
            <div class="panel panel-primary">
                    <div class="panel-heading" style="text-align: center">
                        Share Distribution of Anchalik Panchayat: {{$ap_name}}, Zila Parishad : {{$district_name}} and its Gram Panchayats ({{$fy_years}})
                    </div>
                    <div class="panel-body gray-back">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable1">
                                <thead>
                                    <tr class="bg-primary">
                                        <td>Sr. No.</td>
                                        <td>Anchalik Panchayat</td>
                                        <td class="text-center">Total Revenue Collection <br>(Cr.)</td>
										<td>Estimated ZP Share <br>(Cr.)</td>
										<td>Estimated AP Share <br>(Cr.)</td>
										<td>Estimated GP Share <br>(Cr.)</td>
										
                                        <td class="text-center">ZP Share <br>(Cr.)</td>
                                        <td class="text-center">AP Share <br>(Cr.)</td>
                                        <td class="text-center">GP Share <br>(Cr.)</td>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <p style="display: none;"></p>
                                   <tr>
                                       <td>1</td>
                                       <td><a href="{{route('admin.Osr.osrShareAssetBranchList',[$id,$fy_id,$ap_id])}}">{{$ap_name}}</a></td>
                                       <td class="text-right">{{$dataCount['apData'][$ap_id]['tot_r_c']}}</td>
                                       <td class="text-right">{{$dataCount['apData'][$ap_id]['zp_share']}}</td>
                                       <td class="text-right">{{$dataCount['apData'][$ap_id]['ap_share']}}</td>
                                       <td class="text-right">{{$dataCount['apData'][$ap_id]['gp_share']}}</td>
									   
									   <td class="text-right">{{$dataCount['apData'][$ap_id]['zp_share']}}</td>
                                       <td class="text-right">{{$dataCount['apData'][$ap_id]['ap_share']}}</td>
                                       <td class="text-right">{{$dataCount['apData'][$ap_id]['gp_share']}}</td>

                                   </tr>
                             
                                    <tr class="bg-pprimary">
                                        <td>Sr. No.</td>
                                        <td>Gram Panchayat</td>
                                        <td class="text-center">Total Revenue Collection <br>(Cr.)</td>
										<td>Estimated ZP Share <br>(Cr.)</td>
										<td>Estimated AP Share <br>(Cr.)</td>
										<td>Estimated GP Share <br>(Cr.)</td>
                                        <td class="text-center">ZP Share <br>(Cr.)</td>
                                        <td class="text-center">AP Share <br>(Cr.)</td>
                                        <td class="text-center">GP Share <br>(Cr.)</td>
                                    </tr>

                                    @php
                                        $i=1;
                                        $tot_r_c=0;
                                        $zp_share=0;
                                        $ap_share=0;
                                        $gp_share=0;
                                    @endphp
                                @foreach($gp_list as $gp)
                                   <tr>
                                       <td>{{$i++}}</td>
                                       <td><a href="{{route('admin.Osr.osrShareAssetBranchList',[$id,$fy_id,$ap_id,$gp->id])}}">{{$gp->gram_panchayat_name}}</a></td>
                                       <td class="text-right">{{$dataCount['gpDataList'][$gp->id]['tot_r_c']}}</td>
                                       <td class="text-right">{{$dataCount['gpDataList'][$gp->id]['zp_share']}}</td>
                                       <td class="text-right">{{$dataCount['gpDataList'][$gp->id]['ap_share']}}</td>
                                       <td class="text-right">{{$dataCount['gpDataList'][$gp->id]['gp_share']}}</td>
									   
									   <td class="text-right">{{$dataCount['gpDataList'][$gp->id]['zp_share']}}</td>
                                       <td class="text-right">{{$dataCount['gpDataList'][$gp->id]['ap_share']}}</td>
                                       <td class="text-right">{{$dataCount['gpDataList'][$gp->id]['gp_share']}}</td>
                                   </tr>
                                   @php
                                       $tot_r_c=$tot_r_c+$dataCount['gpDataList'][$gp->id]['tot_r_c'];
                                       $zp_share=$zp_share+$dataCount['gpDataList'][$gp->id]['zp_share'];
                                       $ap_share=$ap_share+$dataCount['gpDataList'][$gp->id]['ap_share'];
                                       $gp_share=$gp_share+$dataCount['gpDataList'][$gp->id]['gp_share'];
                                   @endphp
                                @endforeach
                                    <tr class="bg-pprimary">
                                        <td></td>
                                        <td>
                                            Total
                                        </td>
                                        <td class="text-right">{{$tot_r_c}}</td>
                                        <td class="text-right">{{$zp_share}}</td>
                                        <td class="text-right">{{$ap_share}}</td>
                                        <td class="text-right">{{$gp_share}}</td>
										
										<td class="text-right">{{$zp_share}}</td>
                                        <td class="text-right">{{$ap_share}}</td>
                                        <td class="text-right">{{$gp_share}}</td>
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
                        title: 	   "Share Distribution of Anchalik Panchayat: {{$ap_name}}, Zila Parishad : {{$district_name}} and its Gram Panchayats ({{$fy_years}})",
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
        });
    </script>
@endsection