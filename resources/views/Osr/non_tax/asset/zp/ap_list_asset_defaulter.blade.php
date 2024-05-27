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
        button.dt-button, div.dt-button, a.dt-button {
            background-image: linear-gradient(to bottom, #4fe14f 0%, green 100%);
            color: #fff;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: linear-gradient(to bottom, #4fe14f 0%, green 100%);
            color: #fff !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: linear-gradient(to bottom, #4fe14f 0%, green 100%);
            color: #fff;
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
                Defaulter List Of Anchalik Panchayats : {{$data['zpData']->zila_parishad_name}} ({{$data['fyData']->fy_name}})
            </div>
            <div class="panel-body gray-back">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="dataTable1">
                        <thead>
                        <tr class="bg-primary text-center">
                            <td>SL</td>
                            <td>AP Name</td>
                            <td>Settled asset</td>
                            <td>Defaulter</td>
                        </tr>
                        </thead>
                        <tbody class="text-center">
                        @php $i=1; @endphp
                        @foreach($data['apList'] as $ap)
                            <tr>
                                <td>{{$i}}</td>
                                <td><a href="{{route('osr.non_tax.asset.common.branch_list_settlement_defaulter', [encrypt($data['fy_id']), encrypt('DEFAULTER'), encrypt('AP'), encrypt($data['zpData']->id), encrypt($ap->id), encrypt('NULL')])}}">{{$ap->anchalik_parishad_name}}</a></td>
                                <td>
                                    @if(isset($data['settled'][$ap->id]))
                                        {{$data['settled'][$ap->id]}}
                                    @else
                                        {{'0'}}
                                    @endif
                                </td>
								<td>
                                    @if(isset($data['defaulter'][$ap->id]))
                                        <a href="#" data-apfyyear="{{$data['fy_id']}}" data-zid="{{$data['zpData']->id}}" data-apid="{{$ap->id}}" data-apname=
                                        "{{$ap->anchalik_parishad_name}}" class="listOfAPDefaulterModalViewC">
                                        {{$data['defaulter'][$ap->id]}}
                                        </a>
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
    </div>
</div>

<!-- Model  AP Defaulters -->
<div class="modal fade listOfAPDefaulterModalView" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 33px 0 0 0;">
            <div class="modal-header" style="background-color: #ff9000">
                <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-close"></i>
                </button>
                <h4 style="color: #fff;font-family: 'Old Standard TT', serif;">Defaulter List of AP:-<span class="apname"></span> for the Financial Year {{$data['fyData']->fy_name}}</h4>
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable13" id="" style="width:100%">
                        <thead>
                        <tr class="tr-row">
                            <td>SL</td>
                            <td>Zila Parishad</td>
                            <td>Anchalik Parishad</td>
                            <td>Asset Category</td>
                            <td>Asset Code</td>
                            <td>Asset Name</td>
                            <td>Defaulter Name</td>
                            <td>Defaulter Father Name</td>
                            <td>Mobile</td>
                            <td>Asset Under</td>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
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
                                    title:     'Defaulter List Of Anchalik Panchayats : {{$data['zpData']->zila_parishad_name}} ({{$data['fyData']->fy_name}})',
                                    text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                                    titleAttr: 'Excel'
                                }
                            ]
            });
        });

        //        List of Defaulter AP Wise Model
        $('.listOfAPDefaulterModalViewC').on('click', function(e){
            e.preventDefault();
            if ($.fn.DataTable.isDataTable('.dataTable13') ) {
                $('.dataTable13').dataTable().fnClearTable();
                $('.dataTable13').dataTable().fnDestroy()

            }

            $('.listOfAPDefaulterModalView').modal('hide');

            var apfyyear = $(this).data('apfyyear');
            var zid = $(this).data('zid');
            var apid = $(this).data('apid');
            var apname = $(this).data('apname');

            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('admin.Osr.dashboard.listOfAPDefaulterZilaWise')}}',
                dataType: "json",
                data: {apfyyear : apfyyear, zid : zid, apid : apid},
                success: function (data) {
                    if (data.msgType == true) {

                        var dataSet=data.data;
                        $('.dataTable13').DataTable( {
                            dom: 'Bfrtip',
                            buttons: [
                                {
                                    extend:    'excelHtml5',
                                    title:      'Defaulter List of AP:-'+apname+' for the Financial Year {{$data['fyData']->fy_name}}',
                                    text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                                    titleAttr: 'Excel'
                                }
                            ],
                            data: dataSet,
                            columns: [
                                { title: "SL" },
                                { title: "Zila Parishad" },
                                { title: "Anchalik Parishad" },
                                { title: "Asset Category" },
                                { title: "Asset Code" },
                                { title: "Asset Name" },
                                { title: "Defaulter Name" },
                                { title: "Defaulter Father Name" },
                                { title: "Mobile" },
                                { title: "Asset Under" }
                            ]
                        } );

                        $('.apname').text(apname);
                        $('.listOfAPDefaulterModalView').modal('show');

                    }else{
                        swal(data.msg);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                },
                complete: function (data) {
                    $('.page-loader-wrapper').fadeOut();
                }
            });
        });
    </script>
@endsection