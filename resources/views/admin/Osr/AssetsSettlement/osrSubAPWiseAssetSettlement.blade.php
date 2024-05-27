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
            <li class="active">Assets Settlement :-{{$fy_years}} </li>
        </ol>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 mb20">
            <a class="btn btn-info" href="{{route('admin.Osr.subDistrictWiseAssetSettlement',[$id,$fy_id])}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 mt10">
            <div class="panel panel-primary">
                    <div class="panel-heading" style="text-align: center">
                        Settlement list of Anchalik Panchayat: {{$ap_name}}, Zila Parishad:  {{$district_name}} and its Gram Panchayats ({{$fy_years}})
                    </div>
                    <div class="panel-body gray-back">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable1">
                                <thead>
                                    <tr class="bg-primary">
                                        <td>Sr. No.</td>
                                        <td>Anchalik Panchayat</td>
                                        <td class="text-center">Total Asset</td>
                                        <td class="text-center">Settled Asset</td>
                                        <td class="text-center">Percentage of Settlement(%)</td>
                                    </tr>
                                 </thead>
                                 <tbody>
                                   <tr>
                                       <td>1</td>
                                       <td><a href="{{route('admin.Osr.osrAssetBranchList',[$id,$fy_id,$ap_id])}}">{{$ap_name}}</a></td>
                                       <td class="text-center">
                                        @if(isset($dataCount['apAssetCount'][$ap_id]))
                                                {{$dataCount['apAssetCount'][$ap_id]}}
                                        @else
                                                {{0}}
                                        @endif
                                       </td>
                                       <td class="text-center">
                                        @if(isset($dataCount['apSettledCount'][$ap_id]))
                                                {{$dataCount['apSettledCount'][$ap_id]}}
                                        @else
                                                {{0}}
                                        @endif
                                       </td>
                                       <td class="text-center">
                                       @if(isset($dataCount['apAssetCount'][$ap_id]))
                                            @if(isset($dataCount['apSettledCount'][$ap_id]))
                                                {{round((($dataCount['apSettledCount'][$ap_id]/$dataCount['apAssetCount'][$ap_id])*100), 2)}}
                                            @else
                                                    {{'0'}}
                                            @endif
                                        @else
                                                {{'0'}}
                                        @endif
                                       </td>
                                   </tr>
                             
                                    <tr class="bg-pprimary">
                                        <td>Sr. No.</td>
                                        <td>Gram Panchayat</td>
                                        <td class="text-center">Total Asset</td>
                                        <td class="text-center">Settled Asset</td>
                                        <td class="text-center">Percentage of Settlement(%)</td>
                                    </tr>
                    
                                @php $i=1;  @endphp
                                     
                                @php $totalAsset=0; $settled=0; @endphp
                                @foreach($gp_list as $gp)
                                   <tr>
                                       <td>{{$i++}}</td>
                                       <td><a href="{{route('admin.Osr.osrAssetBranchList',[$id,$fy_id,$ap_id,$gp->id])}}">{{$gp->gram_panchayat_name}}</a></td>
                                       <td class="text-center">
                                       @if(isset($dataCount['totalAssetCount'][$gp->id]))
                                                {{$dataCount['totalAssetCount'][$gp->id]}}
                                           @php $totalAsset=$totalAsset+$dataCount['totalAssetCount'][$gp->id]; @endphp
                                       @else
                                                {{0}}
                                       @endif
                                       </td>
                                       <td class="text-center">
                                       @if(isset($dataCount['gpYrWiseSettledAssetCount'][$gp->id]))
                                                {{$dataCount['gpYrWiseSettledAssetCount'][$gp->id]}}
                                           @php $settled=$settled+$dataCount['gpYrWiseSettledAssetCount'][$gp->id]; @endphp
                                        @else
                                                {{0}}
                                        @endif
                                       </td>
                                       <td class="text-center">
                                       @if(isset($dataCount['totalAssetCount'][$gp->id]))
                                            @if(isset($dataCount['gpYrWiseSettledAssetCount'][$gp->id]))
                                                {{round(($dataCount['gpYrWiseSettledAssetCount'][$gp->id]/$dataCount['totalAssetCount'][$gp->id])*100,2)}}
                                            @else
                                                {{'0'}}
                                            @endif
                                        @else
                                            {{'0'}}
                                        @endif
                                       </td>
                                   </tr>
                                @endforeach
                                    <tr class="bg-pprimary">
                                       <td></td>
                                       <td>Total</td>
                                       <td class="text-center">{{$totalAsset}}</td>
                                       <td class="text-center">{{$settled}}</td>
                                        <td class="text-center">
                                            @if($totalAsset > 0)
                                                @if($settled > 0)
                                                    {{round($settled/$totalAsset*100, 2)}}
                                                @else
                                                    {{"0"}}
                                                @endif
                                            @else
                                                {{"0"}}
                                            @endif
                                       </td>
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
                        title: 	   'Settlement list of Anchalik Panchayat: {{$ap_name}}, Zila Parishad:  {{$district_name}} and its Gram Panchayats ({{$fy_years}})',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
        });
    </script>
@endsection