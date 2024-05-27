@php
    $page_title="OSR";
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
            <li class="active"> osrAssetSingleBranchList :- {{$fy_years}}</li>
        </ol>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 mb20">
           @if($ap_id==NULL)
            <a class="btn btn-info" href="{{route('admin.Osr.osrRevenueAssetBranchList',[$id,$fy_id])}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
            @elseif($gp_id==NULL)
              <a class="btn btn-info" href="{{route('admin.Osr.osrRevenueAssetBranchList',[$id,$fy_id,$ap_id])}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
            @else
              <a class="btn btn-info" href="{{route('admin.Osr.osrRevenueAssetBranchList',[$id,$fy_id,$ap_id,$gp_id])}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 mt10">

            <div class="panel panel-primary">
                    <div class="panel-heading" style="text-align: center">
                       {{$heade_text}}
                    </div>
                    <div class="panel-body gray-back">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable1">
                                <thead>
                                    <tr class="bg-primary">
                                       <td class=" ">Sr. No.</td>
                                        <td class=" ">Asset Code</td>
                                        <td class=" ">Asset Name</td>
                                        <td class="text-center">Gap Period Collection</td>
                                        <td class="text-center">Collection From Installments</td>
                                    </tr>
                                 </thead>
                                 <tbody>
                                @php
                                    $i=1;
                                    $tot_gap_c=0;
                                    $tot_bid_c=0;
                                @endphp
                                @foreach($assetList as $asset)
                                   <tr>
                                       <td>{{$i++}}</td>
                                       <td>
                                           @if($ap_id==NULL)
                                               <a href="{{route('admin.Osr.assetInformation',[$id,$fy_id,$branch_id,$asset->id])}}">{{$asset->asset_code}}</a>
                                           @elseif($gp_id==NULL)
                                               <a href="{{route('admin.Osr.assetInformation',[$id,$fy_id,$branch_id,$asset->id,$ap_id])}}">{{$asset->asset_code}}</a>
                                           @else
                                               <a href="{{route('admin.Osr.assetInformation',[$id,$fy_id,$branch_id,$asset->id,$ap_id,$gp_id])}}">{{$asset->asset_code}}</a>
                                           @endif
                                       </td>
                                       <td>{{$asset->asset_name}}</td>
                                       <td class="text-right money_txt">{{$dataCount[$asset->id]['gap_c']}}</td>
                                       <td class="text-right money_txt">{{$dataCount[$asset->id]['bid_c']}}</td>
                                   </tr>

                                    @php
                                        $tot_gap_c=$tot_gap_c+$dataCount[$asset->id]['gap_c'];
                                        $tot_bid_c=$tot_bid_c+$dataCount[$asset->id]['bid_c'];
                                    @endphp
                                @endforeach
                                    <tr class="bg-pprimary">
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right">Total</td>
                                        <td class="text-right money_txt">{{$tot_gap_c}}</td>
                                        <td class="text-right money_txt">{{$tot_bid_c}}</td>
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
                buttons: [{
                    extend: 'excelHtml5',
                    title: '{{$heade_text}}',
                    text: 'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                    titleAttr: 'Excel',
                }]
            });
        });
		var indianRupeeFormatter = OSREC.CurrencyFormatter.getFormatter({
                    currency: 'INR',
                    symbol: '',
                });

                var indianRupeeFormatterText = OSREC.CurrencyFormatter.getFormatter({
                    currency: 'INR',
                    symbol: '₹',
                });

                $('.money').on('blur', function (e) {
                    e.preventDefault();
                    var value = OSREC.CurrencyFormatter.parse($(this).val(), {locale: 'en_IN'});
                    var formattedVal = indianRupeeFormatter(value);
                    $(this).val(formattedVal);
                });

                OSREC.CurrencyFormatter.formatAll({
                    selector: '.money_txt',
                    currency: 'INR'
                });
    </script>
@endsection