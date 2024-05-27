@php
    $page_title="quick-report-download";
@endphp

@extends('layouts.app_admin_home')

@section('custom_css')

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>


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
            <li><a href="{{route('admin.Pris.priDownloadMenu')}}">Elected Member Details Menu</a></li>
            <li class="active">Elected Member Details GP</li>
        </ol>
    </div>
    <div class="container-fluid">
        <h1 style="text-align: center; font-family: 'Old Standard TT', serif;"><u>Elected Gram Panchayat Member Details in AreaProfiler Application</u></h1>
            <div class="row m-b-50">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="laravel_datatable" style="width: 100%">
                            <thead>
                            <tr class="bg-primary">
                                <td>SL</td>
                                <td>Zila Parishad</td>
                                <td>Anchalik Parishad</td>
                                <td>LGD Code</td>
                                <td>Local Body Name</td>
                                <td>Elected Member Name</td>
                                <td>Elected Member Name</td>
                                <td>Elected Member Name</td>
                                <td>Elected Member Name</td>
                                <td>Designation</td>
                                <td>Ward No.</td>
                                <td>Caste</td>
                                <td>Gender</td>
                                <td>Mobile</td>
                                <td>Email</td>
                            </thead>
                            <tbody>


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
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('mdas_assets/vendor/datatables/buttons.server-side.js') }}"></script>


    {{--<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {!! $dataTable->scripts() !!}--}}


    <script>
        $(document).ready( function () {
            $('#laravel_datatable').DataTable({
                dom: 'Bfrtip',
                processing: true,
                serverSide: true,
                buttons: [    {
                    extend: 'collection',
                    text: '<i class="fa fa-download"></i> Export',
                    buttons: ['csv']
                },'reset'

                ],
                ajax: "{{ route('admin.Pris.servicePriGPNicDataList') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', render: null,searchable:false,orderable: false,exportable:false,printable:false},
                    { data: 'zila_parishad_name', name: 'zp.zila_parishad_name' },
                    { data: 'anchalik_parishad_name', name: 'ap.anchalik_parishad_name' },
                    { data: 'g_id', name: 'pri_member_main_records.gram_panchayat_id' },
                    { data: 'gram_panchayat_name', name: 'gp.gram_panchayat_name' },

                    { data: 'pri_f_name', name: 'pri_member_main_records.pri_f_name', visible: false, sortable:true, visible:false},
                    { data: 'pri_m_name', name: 'pri_member_main_records.pri_m_name', visible: false, sortable:true, visible:false},
                    { data: 'pri_l_name', name: 'pri_member_main_records.pri_l_name', visible: false, sortable:true, visible:false},

                    { data: 'pri_name', name: 'pri_name', searchable:false, sortable:false, visible:true},

                    { data: 'design_name', name: 'd.design_name' },
                    { data: 'ward_name', name: 'w.ward_name' },
                    { data: 'caste_name', name: 'c.caste_name' },
                    { data: 'gender_name', name: 'g.gender_name' },
                    { data: 'mobile_no', name: 'mobile_no' },
                    { data: 'email_id', name: 'email_id' },
                ]
            });
        });
    </script>
@endsection
