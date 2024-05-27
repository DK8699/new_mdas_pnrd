<head>
    <link rel="icon" type="image/x-icon" href="../../../mdas_assets/images/favicon.png">
</head>

@php
$page_title="osr_assets_reports";
@endphp

@extends('layouts.app_admin')

@section('custom_css')
<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />

<style>
    .panel {
        border: none;
        background: #98D3F6;
    }

    label {
        color: dodgerblue;
    }

    .mb40 {
        margin-bottom: 40px;
    }

    .badge-red {
        background-color: orangered;
    }

    .badge-green {
        background-color: darkgreen;
    }

    .panel-primary>.panel-heading {
        color: #fff;
        background-color: #337ab7;
    }

    table.dataTable thead td,
    table.dataTable thead td {
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
        <li><a href="{{route('admin.Osr.osr_dashboard')}}">OSR Non-Tax Resources</a></li>
        <li class="active">Assets Settlement Report</li>
    </ol>
</div>

<div class="container">

    <div class="row">
        <form action="{{route('admin.Asset.Osr.osrAssetsReport')}}" method="POST">
            @csrf
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label>Financial Year <strong>*</strong></label>
                    <select class="form-control" name="fy_id" id="fy_id" required>
                        <option value="">---Select---</option>
                        @foreach($fyData as $list)
                        <option value="{{($list->id)}}" @if($data['data_fy_id']==$list->
                            id)selected="selected"@endif>{{$list->fy_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label>Level <strong>*</strong></label>
                    <select class="form-control" name="level" id="level" required>
                        <option value="">---Select---</option>
                        <option value="ALL" @if($data['data_level']=='ALL' )selected="selected" @endif>ZP,AP and GP
                        </option>
                        <option value="ZP" @if($data['data_level']=='ZP' )selected="selected" @endif>ZP</option>
                        <option value="AP" @if($data['data_level']=='AP' )selected="selected" @endif>AP</option>
                        <option value="GP" @if($data['data_level']=='GP' )selected="selected" @endif>GP</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-sm-4 col-xs-12">
                <div class="form-group mt20">
                    <button type="submit" class="btn btn-primary btn-save btn-sm">
                        <i class="fa fa-search"></i>
                        Search
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <h3 style="background-color:#d4b3c7; padding:5px;">{{$data['headText']}}</h3>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive mb40">
                <table class="table table-bordered table-striped" id="dataTable1">
                    <thead>
                        <tr class="bg-primary">
                            <td class="text-center">SL No.</td>
                            <td class="text-center">District</td>
                            <td class="text-center">Total Master Assets</td>
                            <td class="text-center">Shortlisted Assets</td>
                            <td class="text-center">Not Shortlisted Assets</td>
                            <td class="text-center">Settled Assets</td>
                            <td class="text-center">% of Settlement</td>
                            <td class="text-center">Total Settlement Amount<br>(In ₹)</td>
                            <td class="text-center">Gap period Collection<br>(In ₹)</td>
                            <td class="text-center">Revenue Collection from Bids<br>(In ₹)</td>
                            <td class="text-center">Total Collection<br>(In ₹)</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php

                        $i=1;
                        $tot_asset_r = 0;
                        $tot_shortlist_asset_r=0;
                        $tot_not_shortlist_asset_r=0;
                        $tot_settled_asset_r = 0;
                        $tot_settled_asset_percent_r=0;
                        $tot_settlement_asset_r = 0;
                        $tot_settlement_asset_r = 0;
                        $tot_collection_gap_r = 0;
                        $tot_collection_bid_r = 0;
                        $tot_collection_asset_r = 0;


                        @endphp

                        @foreach($zilas as $li)

                        @php

                        $totalAsset = 0;
                        $shortlistAsset = 0;
                        $notShortlistAsset=0;
                        $settedData = 0;
                        $settledPercent = 0;
                        @endphp

                        @if(isset($data['totalAsset'][$li->id]))
                        @php
                        $totalAsset = $data['totalAsset'][$li->id];
                        @endphp
                        @endif

                        @if(isset($data['shortlistAsset'][$li->id]))
                        @php
                        $shortlistAsset = $data['shortlistAsset'][$li->id];
                        @endphp
                        @endif

                        @if(isset($data['notShortlistAsset'][$li->id]))
                        @php
                        $notShortlistAsset = $data['notShortlistAsset'][$li->id];
                        @endphp
                        @endif

                        @if(isset($data['settledData'][$li->id]))
                        @php
                        $settedData = $data['settledData'][$li->id];
                        @endphp
                        @endif



                        @if($shortlistAsset!=0)
                        @php
                        $settledPercent = ($settedData/$shortlistAsset)*100;
                        @endphp
                        @endif

                        @php
                        $settlementC = $data['SettlementData'][$li->id]['settlement_c'];
                        @endphp

                        @php
                        $gapC = $data['SettlementData'][$li->id]['gap_c'];
                        @endphp

                        @php
                        $bidC = $data['SettlementData'][$li->id]['bid_c'];
                        @endphp

                        @php
                        $totRevenueC = ($data['SettlementData'][$li->id]['gap_c'] +
                        $data['SettlementData'][$li->id]['bid_c']);
                        @endphp

                        @php
                        $tot_asset_r= $tot_asset_r+$totalAsset;

                        $tot_shortlist_asset_r= $tot_shortlist_asset_r+$shortlistAsset;

                        $tot_not_shortlist_asset_r= $tot_not_shortlist_asset_r+$notShortlistAsset;

                        $tot_settled_asset_r= $tot_settled_asset_r+$settedData;

                        $tot_settlement_asset_r= $tot_settlement_asset_r+$settlementC ;

                        $tot_collection_gap_r= $tot_collection_gap_r+$gapC;

                        $tot_collection_bid_r= $tot_collection_bid_r+$bidC;

                        $tot_collection_asset_r= $tot_collection_asset_r+$totRevenueC;
                        @endphp

                        @if($tot_shortlist_asset_r!=0)
                        @php
                        $tot_settled_asset_percent_r = (($tot_settled_asset_r)/$tot_shortlist_asset_r)*100;
                        @endphp
                        @endif

                        <tr>
                            <td>
                                {{$i}}
                            </td>
                            <td>{{$li->zila_parishad_name}}
                            </td>
                            <td class="text-center">
                                {{$totalAsset}}
                            </td>
                            <td class="text-center">
                                {{$shortlistAsset}}
                            </td>
                            <td class="text-center">
                                @if($notShortlistAsset!=0)
                                <a
                                    href="{{route('admin.Asset.Osr.osrAssetsReport.notShortlistedReport',[encrypt($data['data_fy_id']),encrypt($li->id)])}}">{{$notShortlistAsset}}</a>
                                @else
                                {{$notShortlistAsset}}
                                @endif
                            </td>
                            <td class="text-center">
                                {{$settedData}}
                            </td>
                            <td class="text-center">
                                {{round($settledPercent,2)}}
                            </td>
                            <td class="text-center">
                                <span class="money_txt">{{$settlementC}}</span>
                            </td>
                            <td class="text-center">
                                <span class="money_txt">{{$gapC}}</span>
                            </td>
                            <td class="text-center">
                                <span class="money_txt">{{$bidC}}</span>
                            </td>
                            <td class="text-center">
                                <span class="money_txt">{{$totRevenueC}}</span>
                            </td>
                        </tr>
                        @php $i++; @endphp
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr class="bg-danger">
                            <td class="text-center">#</td>
                            <td class="text-center">Total</td>
                            <td class="text-center">{{$tot_asset_r}}</td>
                            <td class="text-center">{{$tot_shortlist_asset_r}}</td>
                            <td class="text-center">{{$tot_not_shortlist_asset_r}}</td>
                            <td class="text-center">{{$tot_settled_asset_r}}</td>
                            <td class="text-center">{{round($tot_settled_asset_percent_r,2)}}</td>
                            <td class="text-center">
                                <span class="money_txt">{{$tot_settlement_asset_r}}</span>
                            </td>
                            <td class="text-center">
                                <span class="money_txt">{{$tot_collection_gap_r}}</span>
                            </td>
                            <td class="text-center">
                                <span class="money_txt">{{$tot_collection_bid_r}}</span>
                            </td>
                            <td class="text-center">
                                <span class="money_txt">{{$tot_collection_asset_r}}</span>
                            </td>

                        </tr>
                    </tfoot>
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
                paging: false,
                info: false,
                buttons: [
                    {
                        extend:    'excelHtml5',
                        title: 	   '{{$data['headText']}}',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
        });
	    
	     var indianRupeeFormatter = OSREC.CurrencyFormatter.getFormatter({
            currency: 'INR',
            symbol: ''
        });

        var indianRupeeFormatterText = OSREC.CurrencyFormatter.getFormatter({
            currency: 'INR',
            symbol: ''
        });

        $('.money').on('blur', function (e){
            e.preventDefault();
            var value= OSREC.CurrencyFormatter.parse($(this).val(), { locale: 'en_IN' });
            var formattedVal = indianRupeeFormatter(value);
            $(this).val(formattedVal);
        });

        OSREC.CurrencyFormatter.formatAll({
            selector: '.money_txt',
            currency: 'INR',
            symbol: ''
        });
</script>
@endsection