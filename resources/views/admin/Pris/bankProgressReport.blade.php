@php
    $page_title = 'dashboard';
@endphp

@extends('layouts.app_admin_home')

@section('custom_css')
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('mdas_assets/css/multiple-select.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .count-style {
            font-weight: 700;
            font-size: 20px;
            color: #10436d;
            text-shadow: 0px 1px 4px #19191d4f;
        }

        .m-b-50 {
            margin-top: 50px;
            margin-bottom: 50px;
            padding: 20px;
        }

        .table-bordered {
            border: 1
        }

        tr:nth-child(even) {
            background-color: #f2f2f2 !important;
        }

        .center {
            /* position: absolute; */
            top: 50%;
            left: 50%;
            transform: translate(-50%, 5%);
        }

        .glass {
            background: rgba(206, 191, 191, 0.5);
            /* top-left, top-right, bot-right, bot-left */
            border-radius: 16px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            /* backdrop-filter: blur(5px); */
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(206, 191, 191, 0.3);
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        .ning::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .ning {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }


        /* searchbar button of DOM*/
        .dataTables_wrapper .dataTables_filter {
            margin-top: 10px;
        }

        /* excel button of DOM*/
        button.dt-button,
        div.dt-button,
        a.dt-button {
            background-image: linear-gradient(to right, #D31027 0%, #EA384D 51%, #D31027 100%) !important;
            padding: 10px 25px;
            text-align: center;
            text-transform: uppercase;
            transition: 0.5s;
            background-size: 200% auto;
            color: white;
            border-radius: 10px;
            display: block;
        }

        button.dt-button,
        div.dt-button,
        a.dt-button:hover {
            background-position: right center;
            color: #fff;
            text-decoration: none;
            /* change the direction of the change here */
        }

        .displayNone {
            display: none;
        }

        .text-left {
            text-align: left;
        }

        /*  */
    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="active"><a href="{{ route('admin.Pris.priMenu') }}"> Back To PRIs Dashboard</a></li>
        </ol>
    </div>

    <div class="col-md-8 col-sm-10 col-xs-10 center">
        <div class="table-responsive m-b-50 ning glass">
            <table class="table table-bordered table-striped" id="dataTable1">
                <thead>
                    <tr class="bg-primary">
                        <td>SL No</td>
                        <td>Zila Parishad Name</td>
                        <td>ZP Strength</td>
                        <td>ZP Bank Records</td>
                        <td>AP Strength</td>
                        <td>AP Bank Records</td>
                        <td>GP Strength</td>
                        <td>GP Bank Records</td>
                        <td><b>Total Strength</b></td>
                        <td><b>Filled Bank Account Details</b></td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $tt = 0;
                        $ut = 0;
                        $i = 1;
                    @endphp


                    @foreach ($submittedZPs as $zp)
                        @php
                            $total_strength = $zpstrength[$i - 1] + $gpcount[$i - 1] + $gpcount[$i - 1] * 11;
                            
                            $tt = $tt + $total_strength;
                            
                            $unfilled = $zpBankRecordCount[$i - 1][0]->mycountzp + $apBankRecordCount[$i - 1][0]->mycountap + $gpBankRecordCount[$i - 1][0]->mycountgp;
                            $ut = $ut + $unfilled;
                            
                        @endphp
                        {{-- <p>{{ dd($zp->id) }}</p> --}}
                        <tr>
                            <td>{{ $i }}</td>
                            <td><a
                                    href="{{ url('/admin/Pris/priMenu/bankSubDistrictAdmin', $zp->id) }}">{{ $zp->zila_parishad_name }}</a>
                            </td>
                            <td><strong>{{ $zpstrength[$i - 1] }}</strong></td>
                            <td><strong>{{ $zpBankRecordCount[$i - 1][0]->mycountzp }}</strong></td>
                            <td><strong>{{ $gpcount[$i - 1] }}</strong></td>
                            <td><strong>{{ $apBankRecordCount[$i - 1][0]->mycountap }}</strong></td>
                            <td><strong>{{ $gpcount[$i - 1] * 11 }}</strong></td>
                            <td><strong>{{ $gpBankRecordCount[$i - 1][0]->mycountgp }}</strong></td>
                            <td><b>{{ $total_strength }}</b></td>
                            <td><b>{{ $unfilled }}</b></td>
                        </tr>

                        @php $i++; @endphp
                    @endforeach
                    <tr class="bg-danger displayNone">
                        <td class="text-center"><strong>#</strong></td>
                        <td class="text-center"><strong>Total</strong></td>
                        <td class="text-center"><strong>{{ $totalZpStrength }}</strong></td>
                        <td class="text-center"><strong>{{ $totalZpBankRecordCount }}</strong></td>
                        <td class="text-center"><strong>{{ $totalaps }}</strong></td>
                        <td class="text-center"><strong>{{ $totalapBankRecordCount }}</strong></td>
                        <td class="text-center"><strong>{{ $totalgps * 11 }}</strong></td>
                        <td class="text-center"><strong>{{ $totalgpBankRecordCount }}</strong></td>
                        <td class="text-center"><strong>{{ $tt }}</strong></td>
                        <td class="text-center"><strong>{{ $ut }}</strong></td>
                    </tr>

                </tbody>
                @php
                @endphp
                {{-- this doesnt show up in excel --}}
                <tfoot>
                    <tr class="bg-danger">
                        <td class="text-left"><strong>#</strong></td>
                        <td class="text-left"><strong>Total</strong></td>
                        <td class="text-left"><strong>{{ $totalZpStrength }}</strong></td>
                        <td class="text-left"><strong>{{ $totalZpBankRecordCount }}</strong></td>
                        <td class="text-left"><strong>{{ $totalaps }}</strong></td>
                        <td class="text-left"><strong>{{ $totalapBankRecordCount }}</strong></td>
                        <td class="text-left"><strong>{{ $totalgps * 11 }}</strong></td>
                        <td class="text-left"><strong>{{ $totalgpBankRecordCount }}</strong></td>
                        <td class="text-left"><strong>{{ $tt }}</strong></td>
                        <td class="text-left"><strong>{{ $ut }}</strong></td>
                    </tr>
                </tfoot>
            </table>
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
                dom: 'Blfrtip',
			    ordering: false,
                paging: true,
                info: false,
                buttons: [
                    {
                        extend:    'excelHtml5',
                        title:     'Bank Progress Report',
                        text:      'Export to Excel <i class="fa-solid fa-file-excel" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
        });




</script>
@endsection
