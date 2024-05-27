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
            margin-bottom: 50px;
            padding: 20px;
        }


        tr:nth-child(even) {
            background-color: #f2f2f2 !important;
        }

        .center {
            top: 50%;
            left: 50%;
            transform: translate(-50%, 5%);
        }

        .glass {
            background: rgba(206, 191, 191, 0.5);
            border-radius: 16px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(206, 191, 191, 0.3);
        }

        .ning::-webkit-scrollbar {
            display: none;
        }

        .ning {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }


        .dataTables_wrapper .dataTables_filter {
            margin-top: 10px;
        }

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
        }

        /*  */
    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="active"><a href="{{ route('admin.Pris.priMenu') }}"> Back To PRIs Dashboard</a></li>
            <li class="active"><a href="{{ route('admin.Pris.bankProgressReport') }}"> Back To Bank Records</a></li>

        </ol>
    </div>

    <div class="col-md-8 col-sm-10 col-xs-10 center">
        {{-- <h3>This is still not completed</h3> --}}
        <div class="table-responsive m-b-50 ning glass">
            <table class="table table-bordered" id="dataTable1">
                <thead>
                    <tr class="bg-primary">
                        <td>SL No</td>
                        {{-- <td>AP ID</td> --}}
                        <td>Anchalik Parishad Name</td>
                        <td>AP Strength</td>
                        <td>AP Bank Records</td>
                        <td>GP Strength</td>
                        <td>GP Bank Records</td>

                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp

                    @foreach ($apcount as $ap)
                        <tr>
                            <td>{{ $i }}</td>
                            {{-- <td>{{ $ap->id }}</td> --}}
                            <td>
                                <a href="{{ route('admin.Pris.bankSubDistrictGPAdmin', $ap->id) }}"> {{$ap->anchalik_parishad_name }}</a>
                            </td>

                            <td>{{ $apStrength[$i - 1][0]->myapStrength }}</td>
                            <td>{{ $apBankRecordCount[$i - 1][0]->mycountap }}</td>
                            <td>{{ $apStrength[$i - 1][0]->myapStrength * 11 }}</td>
                            <td>{{ $gpBankRecordCount[$i - 1][0]->mycountgp }}</td>
                            
                        </tr>
                        @php
                            $i++;
                        @endphp
                    @endforeach


                </tbody>
            </table>
        </div>
    </div>
@endsection

{{-- normal datatable start --}}
@section('custom_js')
    <script src="//code.jquery.com/jquery-3.5.1.js
                    https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js
                    https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js
                    https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js
                    https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js
                    https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js
                    https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js
                    https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>

    <script type="application/javascript">
    $(document).ready(function() {
        $('#dataTable1').DataTable({
            dom: 'Blfrtip',
            buttons: [
               
                {
                         extend:    'excelHtml5',
                         title:     'Bank SubDistrict Report',
                }
            ]
        });
    });
</script>
@endsection
{{-- normal datatable end --}}
