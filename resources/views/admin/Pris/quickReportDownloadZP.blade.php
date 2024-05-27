@php
    $page_title="quick-report-download";
@endphp

@extends('layouts.app_admin_home')

@section('custom_css')
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>

    <style>
       
    </style>
@endsection

@section('content')

    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            <li><a href="{{route('admin.Pris.priDownloadMenu')}}">Elected Member Details Menu</a></li>
            <li class="active">Elected Member Details ZP</li>
        </ol>
    </div>
    <div class="container-fluid">
        <h1 style="text-align: center; font-family: 'Old Standard TT', serif;"><u>Elected Zila Parishad Member Details in AreaProfiler Application</u></h1>
            <div class="row m-b-50">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="laravel_datatable" style="width: 100%">
                            <thead>
                            <tr class="bg-primary">
                                <td>SL</td>
                                <td>LGD Code</td>
                                <td>Local Body Name</td>
                                <td>Elected Member Name</td>
                                <td>Elected Member Name</td>
                                <td>Elected Member Name</td>
                                <td>Elected Member Name</td>
                                <td>Designation</td>
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
                ajax: "{{ route('admin.Pris.servicePriZPNicDataList') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', render: null,searchable:false,orderable: false,exportable:false,printable:false},
                    { data: 'zp_id', name: 'pri_member_main_records.zilla_id' },
                    { data: 'zila_parishad_name', name: 'zp.zila_parishad_name' },

                    { data: 'pri_f_name', name: 'pri_member_main_records.pri_f_name', visible: false, sortable:true, visible:false},
                    { data: 'pri_m_name', name: 'pri_member_main_records.pri_m_name', visible: false, sortable:true, visible:false},
                    { data: 'pri_l_name', name: 'pri_member_main_records.pri_l_name', visible: false, sortable:true, visible:false},

                    { data: 'pri_name', name: 'pri_name', searchable:false, sortable:false, visible:true},

                    { data: 'design_name', name: 'd.design_name' },
                    { data: 'caste_name', name: 'c.caste_name' },
                    { data: 'gender_name', name: 'g.gender_name' },
                    { data: 'mobile_no', name: 'pri_member_main_records.mobile_no' },
                    { data: 'email_id', name: 'pri_member_main_records.email_id' }
                ]
            });
        });
    </script>
@endsection
