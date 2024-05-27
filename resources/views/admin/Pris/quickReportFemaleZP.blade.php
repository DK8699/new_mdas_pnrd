@php
    $page_title="quick-report-download";
@endphp

@extends('layouts.app_admin_home')

@section('custom_css')
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>

    <style>
        .count-style{
            font-weight: 700;font-size: 20px;color: #10436d;text-shadow: 0px 1px 4px #19191d4f;
        }
        .m-b-50{
            margin-bottom: 50px;
        }
        table.dataTable thead th, table.dataTable thead td {
            font-weight: 500;
            text-align: center;
        }
        .partytd {
            font-size: 9px;
        }
        .olophoru {
            font-size: 12px;
        }
        /*    collapsible */
        .collapsible1 {
            background-color: #03A9F4;
            color: white;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
            margin-bottom: 1px;
        }

        .collapsible1:hover {
            background-color: #00BCD4;
        }

        .collapsible1:after {
            content: '\002B';
            color: white;
            font-weight: bold;
            float: right;
            margin-left: 5px;
        }

        .active:after {
            content: "\2212";
        }

        .content {
            padding: 0 0px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 1.5s ease-out;
            background-color: #f1f1f1;
        }

        
    </style>
@endsection

@section('content')

    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            <li><a href="{{route('admin.Pris.priFemaleMenu')}}">Female Sarpanches</a></li>
            <li class="active">Female Sarpanches Details ZP</li>
        </ol>
    </div>
    <div class="container-fluid">
        <h1 style="text-align: center; font-family: 'Old Standard TT', serif;"><u>Female Sarpanches Zila Parishad Under 30 Years</u> <span style="font-size: 20px">(as on 01/01/2019)</span></h1>
        <div class="row m-b-50">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable1">
                        <thead>
                        <tr class="bg-primary">
                            <td>SL</td>
                            <td>Zila Parishad</td>
                            <td>Member Name</td>
                            <td>Designation</td>
                            <td>Gender</td>
                            <td>DOB</td>
                            <td>Age</td>
                            <td>Caste</td>
                            <td>Mobile</td>
                            <td>Email</td>
                        </thead>
                        <tbody>
                        @php $i = 1; @endphp
                        @foreach ($zpsDownload AS $zps)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$zps['zila_parishad_name']}}</td>
                                <td>{{$zps['pri_name']}}</td>
                                <td>{{$zps['design_name']}}</td>
                                <td>{{$zps['gender_name']}}</td>
                                <td>{{$zps['dob']}}</td>
                                <td>{{$zps['age']}}</td>
                                <td>{{$zps['caste_name']}}</td>
                                <td>{{$zps['mobile_no']}}</td>
                                <td></td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                        </tbody>
                        <tfoot class="bg-danger">
                        <tr>
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
    <script>

        $(document).ready(function () {
            $('#dataTable1').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ]
            });
            $('#dataTable2').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ]
            });
            $('#dataTable3').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ]
            });
        });
    </script>
@endsection
