@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{asset('mdas_assets/css/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css"/>
    <style>

        .mb40{
            margin-bottom: 40px;
        }

    </style>
@endsection

@section('content')

    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Home</a></li>
            <li><a href="{{url('osr/osr_panel')}}/{{encrypt($data['fy_id'])}}">OSR</a></li>
            <li class="active">Asset Tender and Payment</li>
            <li class="active">Gap Share Distribution </li>
        </ol>
    </div>


    <div class="container mb40">
        <div class="row mt40">

            @if($users->mdas_master_role_id == 2)
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable">
                            <thead>
                            <tr class="bg-primary">
                                <td>SL</td>
                                <td>Anchalik Panchayat</td>
                                <td>Estimated Share Amount <br/> (in <i class="fa fa-rupee"></i>)</td>
                                <td>Share Amount <br/> (in <i class="fa fa-rupee"></i>)</td>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i=1; @endphp
                            @foreach($data['zpLevelAPShare'] as $ap_share)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$ap_share->anchalik_parishad_name}}</td>
                                    <td><span class="money_txt">{{$ap_share->est_ap_share}}</span></td>
                                    <td><span class="money_txt">{{$ap_share->ap_share}}</span></td>
                                </tr>
                                @php $i++; @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable1">
                            <thead>
                            <tr class="bg-primary">
                                <td>SL</td>
                                <td>Anchalik Panchayat</td>
                                <td>Gram Panchayat</td>
                                <td>Estimated Share Amount <br/> (in <i class="fa fa-rupee"></i>)</td>
                                <td>Share Amount <br/> (in <i class="fa fa-rupee"></i>)</td>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i=1; @endphp
                            @foreach($data['gpShareList'] as $gp_share)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$gp_share->anchalik_parishad_name}}</td>
                                    <td>{{$gp_share->gram_panchayat_name}}</td>
                                    <td><span class="money_txt">{{$gp_share->est_gp_share}}</span></td>
                                    <td><span class="money_txt">{{$gp_share->gp_share}}</span></td>
                                </tr>
                                @php $i++; @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @elseif($users->mdas_master_role_id == 3)
		   
		   <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable1">
                        <thead>
                        <tr class="bg-primary">
                            <td>SL</td>
                            <td>Gram Panchayat</td>
                            <td>Estimated Share Amount <br/> (in <i class="fa fa-rupee"></i>)</td>
                            <td>Share Amount <br/> (in <i class="fa fa-rupee"></i>)</td>
                        </tr>
                        </thead>
                        <tbody>
                         @php $i=1; @endphp
                        	@foreach($data['gpShareList'] as $gp_share)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$gp_share->gram_panchayat_name}}</td>
						   <td><span class="money_txt">{{$gp_share->est_gp_share}}</span></td>
						   <td><span class="money_txt">{{$gp_share->gp_share}}</span></td>
                            </tr>
                            @php $i++; @endphp
					@endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @else
                <div class="col-md-12 col-sm-12 col-xs-12">

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
    <script src="{{asset('mdas_assets/js/jquery.multi-select.js')}}"></script>

    <script type="application/javascript">

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

        $(document).ready(function () {
            $('#dataTable').DataTable({
                dom: 'Bfrtip',
                ordering: false,
                buttons: [
                    {
                        extend:    'excelHtml5',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });

            $('#dataTable1').DataTable({
                dom: 'Bfrtip',
                ordering: false,
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