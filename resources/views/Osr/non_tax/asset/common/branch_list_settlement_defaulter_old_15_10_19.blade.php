@php
    $page_title="OSR Dashboard";
@endphp

@extends('layouts.app_user_osr')

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

            @if($data['page_for']=="SETTLEMENT")

                <div class="panel panel-primary">
                <div class="panel-heading" style="text-align: center">
                    {{$data['head_txt']}}
                </div>
                <div class="panel-body gray-back">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable1">
                            <thead>
                        <tr class="bg-primary text-center">
                            <td>SL</td>
                            <td>Category Name</td>
                            <td>Total Scope</td>
                            <td>Shorlisted</td>
                            <td>Settled</td>
                            <td>Settlement %</td>
                        </tr>
                        </thead>
                        <tbody class="text-center">
                        @php $i=1; @endphp
                        @foreach($data['branches'] as $branch)
                            <tr >
                                <td>{{$i}}</td>
                                <td class="text-left"><a href="{{route('osr.non_tax.asset.common.single_branch_settlement_defaulter', [encrypt($data['fy_id']), encrypt($data['page_for']), encrypt($data['level']), encrypt($branch->id), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">{{$branch->branch_name}}</a></td>
                                <td>
                                    @if(isset($data['totalScope'][$branch->id]))
                                        {{$data['totalScope'][$branch->id]}}
                                    @else
                                        {{'0'}}
                                    @endif
                                </td>
                                <td>
                                    @if(isset($data['shortlist'][$branch->id]))
                                        {{$data['shortlist'][$branch->id]}}
                                    @else
                                        {{'0'}}
                                    @endif
                                </td>
                                <td>
                                    @if(isset($data['settled'][$branch->id]))
                                        {{$data['settled'][$branch->id]}}
                                    @else
                                        {{'0'}}
                                    @endif
                                </td>
                                <td>
                                    @if(isset($data['shortlist'][$branch->id]))
                                        @if(isset($data['settled'][$branch->id]))
                                            {{round(($data['settled'][$branch->id]/$data['shortlist'][$branch->id])*100,2)}}
                                        @else
                                            {{'0'}}
                                        @endif
                                    @else
                                        {{'0'}}
                                    @endif
                                </td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @elseif($data['page_for']=="DEFAULTER")

                <div class="panel panel-primary">
                    <div class="panel-heading" style="text-align: center">
                        {{$data['head_txt']}}
                    </div>
                    <div class="panel-body gray-back">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable1">
                                <thead>
                        <tr class="bg-primary text-center">
                            <td>SL</td>
                            <td>Category</td>
                            <td>Settled Asset</td>
                            <td>Defaulter</td>
                        </tr>
                        </thead>
                        <tbody class="text-center">
                        @php $i=1; @endphp
                        @foreach($data['branches'] as $branch)
                            <tr >
                                <td>{{$i}}</td>
                                <td><a href="{{route('osr.non_tax.asset.common.single_branch_settlement_defaulter', [encrypt($data['fy_id']), encrypt($data['page_for']), encrypt($data['level']), encrypt($branch->id), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">{{$branch->branch_name}}</a></td>
                                <td>
                                    @if(isset($data['settled'][$branch->id]))
                                        {{$data['settled'][$branch->id]}}
                                    @else
                                        {{'0'}}
                                    @endif
                                </td>
								<td  data-toggle="modal" data-target="#defaulterModel" style="cursor: pointer;">
                                    @if(isset($data['defaulter'][$branch->id]))
                                        {{$data['defaulter'][$branch->id]}}
                                    @else
                                        {{'0'}}
                                    @endif
                                </td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                        </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            @endif
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