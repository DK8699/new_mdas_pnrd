@php
    $page_title="OSR Dashboard";
@endphp
@extends('layouts.app_user_osr')
@section('custom_css')
    <link href="https://fonts.googleapis.com/css?family=Old+Standard+TT&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.css" rel="stylesheet" type="text/css"/>
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="https://fonts.googleapis.com/css?family=Saira+Stencil+One&display=swap" rel="stylesheet">
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
        .bg-pprimary{
            color: #fff;
            background-color: #337ab7 !important;
        }
    </style>
@endsection
@section('content')

    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Home</a></li>
            <li class="active">OSR</li>
        </ol>
    </div>

    <div class="container">
        <div class="row mt40">
            <a href="javascript:history.back()" style=""><button class="btn" onMouseOver="this.style.color='#fff'" style="border-radius: 18px;margin-bottom: 2px;"><i class="fa fa-arrow-left"></i> Back</button></a>
            <div class="panel panel-primary">
                <div class="panel-heading" style="text-align: center">
                    {{$data['head_txt']}}
                </div>
                <div class="panel-body gray-back">
                    <div class="table-responsive">
                        @if($data['page_for']=="REVENUE")
                            <table class="table table-bordered table-striped" id="dataTable1">
                                <thead>
                                    <tr class="bg-primary text-center">
                                        <td>SL</td>
                                        <td>Asset Code</td>
                                        <td>Asset Name</td>
                                        <td>Gap Period Revenue Collection <br>(in ₹.)</td>
                                        <td>Revenue Collection from BID <br>(in ₹.)</td>
                                        <td>Total Revenue Collection <br>(in ₹.)</td>
                                    </tr>
                                </thead>
                            <tbody class="text-center">
                            @php
                                $i=1;
                                $tot_c=0;
                                $gap_c=0;
                                $bid_c=0;
                            @endphp
                            @foreach($data['assetList'] as $li)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td class="text-left">
                                        <a href="{{route('osr.non_tax.asset.common.asset_information', [encrypt($data['fy_id']), encrypt($data['level']), encrypt($data['branchData']->id), encrypt($li->id), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">
                                            {{$li->asset_code}}
                                        </a>
                                    </td>
                                    <td class="text-left">
                                        {{$li->asset_name}}
                                    </td>
                                    <td class="text-right">
                                        <span class="money_txt">
                                            {{$data['resArray'][$li->id]['gap_c']}}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <span class="money_txt">
                                            {{$data['resArray'][$li->id]['bid_c']}}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <span class="money_txt">
                                            {{$data['resArray'][$li->id]['tot_c']}}
                                        </span>
                                    </td>
                                </tr>
                                @php $i++; @endphp
                                @php
                                    $tot_c=$tot_c+$data['resArray'][$li->id]['tot_c'];
                                    $gap_c=$gap_c+$data['resArray'][$li->id]['gap_c'];
                                    $bid_c=$bid_c+$data['resArray'][$li->id]['bid_c'];
                                @endphp
                            @endforeach
                            <tr class="bg-pprimary">
                                <td></td>
                                <td></td>
                                <td class="text-right">Total</td>
                                <td class="text-right">
                                    <span class="money_txt">
                                    {{$gap_c}}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <span class="money_txt">
                                    {{$bid_c}}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <span class="money_txt">
                                        {{$tot_c}}
                                    </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        @elseif($data['page_for']=="SHARE")
                            <table class="table table-bordered table-striped" id="dataTable1">
                                <thead>
                                    <tr class="bg-primary text-center">
                                        <td>SL</td>
                                        <td>Asset Code</td>
                                        <td>Asset Name</td>
                                        <td>Total Revenue Collection<br>(in ₹.)</td>
                                        <td>ZP Share<br>(in ₹.)</td>
                                        <td>AP Share<br>(in ₹.)</td>
                                        <td>GP Share<br>(in ₹.)</td>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                @php
                                    $i=1;
                                    $tot_r_c=0;
                                    $zp_share=0;
                                    $ap_share=0;
                                    $gp_share=0;
                                @endphp
                                @foreach($data['assetList'] as $li)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td class="text-left">
                                            <a href="{{route('osr.non_tax.asset.common.asset_information', [encrypt($data['fy_id']), encrypt($data['level']), encrypt($data['branchData']->id), encrypt($li->id), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">
                                                {{$li->asset_code}}
                                            </a>
                                        </td>
                                        <td class="text-left">
                                            {{$li->asset_name}}
                                        </td>
                                        <td class="text-right">
                                            <span class="money_txt">
                                                {{$data['resArray'][$li->id]['tot_r_c']}}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <span class="money_txt">
                                                {{$data['resArray'][$li->id]['zp_share']}}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <span class="money_txt">
                                                {{$data['resArray'][$li->id]['ap_share']}}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <span class="money_txt">
                                                {{$data['resArray'][$li->id]['gp_share']}}
                                            </span>
                                        </td>
                                    </tr>
                                    @php $i++; @endphp
                                    @php
                                        $tot_r_c=$tot_r_c+$data['resArray'][$li->id]['tot_r_c'];
                                        $zp_share=$zp_share+$data['resArray'][$li->id]['zp_share'];
                                        $ap_share=$ap_share+$data['resArray'][$li->id]['ap_share'];
                                        $gp_share=$gp_share+$data['resArray'][$li->id]['gp_share'];
                                    @endphp
                                @endforeach
                                <tr class="bg-pprimary">
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">Total</td>
                                    <td class="text-right">
                                    <span class="money_txt">
                                        {{$tot_r_c}}
                                    </span>
                                    </td>
                                    <td class="text-right">
                                    <span class="money_txt">
                                    {{$zp_share}}
                                    </span>
                                    </td>
                                    <td class="text-right">
                                    <span class="money_txt">
                                    {{$ap_share}}
                                    </span>
                                    </td>
                                    <td class="text-right">
                                    <span class="money_txt">
                                    {{$gp_share}}
                                    </span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        @endif
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
                        title: 	   '{{$data['head_txt']}}',
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