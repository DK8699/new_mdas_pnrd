@php
    $page_title="priMenu";
@endphp

@extends('layouts.app_admin')

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
        
        .panel-primary>.panel-heading {
            color: #fff;
            background-color: #337ab7;
        }
        
        table.dataTable thead td, table.dataTable thead td {
            padding: 10px 18px;
            border-bottom: 0px solid #111; 
        }
        
        .bg-pprimary {
            color: #fff;
            background-color: #337ab7 !important;
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
    </style>
@endsection




@section('content')

<div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Home</a></li>
            <li><a  href="{{route('admin.Osr.osr_dashboard')}}">OSR Non-Tax Resources</a></li>
            <li class="active">Defaulters </li>
        </ol>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 mb20">
            <a class="btn btn-info" href="{{route('admin.Osr.osr_dashboard')}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 mt10">
            <div class="panel panel-primary">
                    <div class="panel-heading" style="text-align: center">
                        Defaulters for the Zila Parishad : {{$district_name}} and its Anchalik Panchayats ({{$fy_years}})
                    </div>
                    <div class="panel-body gray-back">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable1">
                                <thead>
                                    <tr class="bg-primary">
                                        <td>Sr. No.</td>
                                        <td>Zila Parishad</td>
                                        <td class="text-center">Settled Assets</td>
                                        <td class="text-center">Defaulters</td>
                                        <td class="text-center">Percentage of Defaulters(%)</td>
                                    </tr>
                                 </thead>
                                 <tbody>
                                   <tr>
                                       <td>1</td>
                                       <td><a href="{{route('admin.Osr.osrDefaulterAssetBranchList',[$id,$fy_id])}}">{{$district_name}}</a></td>
                                       <td class="text-center">
                                       @if(isset($dataCount['zpSettledCount'][$id]))
                                                {{$dataCount['zpSettledCount'][$id]}}
                                        @else
                                                {{0}}
                                        @endif
                                       </td>
                                       <td class="text-center">
                                           @if(isset($dataCount['zpDefaulter'][$id]))
                                               <a href="#" data-zfyyear="{{$fy_id}}" data-zid="{{$id}}" data-zpname="{{$district_name}}" class="listOfZPDefaulterModalViewC">
                                                   {{$dataCount['zpDefaulter'][$id]}}
                                               </a>
                                           @else
                                               {{0}}
                                           @endif
                                       </td>
                                       <td class="text-center">
                                        @if(isset($dataCount['zpSettledCount'][$id]))
                                            @if(isset($dataCount['zpDefaulter'][$id]))
                                                {{round((($dataCount['zpDefaulter'][$id]/$dataCount['zpSettledCount'][$id])*100), 2)}}
                                            @else
                                                {{'0'}}
                                            @endif
                                        @else
                                            {{'0'}}
                                        @endif
                                       
                                       </td>
                                   </tr>
                             
                                    <tr class="bg-pprimary">
                                        <td>Sr. No.</td>
                                        <td>Anchalik Panchayat</td>
                                        <td class="text-center">Settled Assets</td>
                                        <td class="text-center">Defaulters</td>
                                         <td class="text-center">Percentage of Defaulters(%)</td>
                                    </tr>
                    
                                @php 
                                     $i=1; 
                                     $settled=0;
                                     $defaulter=0;
                                @endphp
                                @foreach($ap_list as $ap)
                                   <tr>
                                       <td>{{$i++}}</td>
                                       <td>
                                           <a href="{{route('admin.Osr.subAPWiseAssetDefaulter',[$id,$fy_id,$ap->id])}}">
                                               {{$ap->anchalik_parishad_name}}
										   </a>
                                       </td>
                                       <td class="text-center">
                                        @if(isset($dataCount['apYrWiseSettledAssetCount'][$ap->id]))
                                                {{$dataCount['apYrWiseSettledAssetCount'][$ap->id]}}
                                                @php    $settled = $settled+$dataCount['apYrWiseSettledAssetCount'][$ap->id];    @endphp
                                        @else
                                                {{0}}
                                        @endif
                                       </td>
                                       <td class="text-center">
                                           @if(isset($dataCount['apYrWiseDefaulterCount'][$ap->id]))
                                               <a href="#" data-apfyyear="{{$fy_id}}" data-zid="{{$id}}" data-apid="{{$ap->id}}" data-apname="{{$ap->anchalik_parishad_name}}" class="listOfAPDefaulterModalViewC">
                                                   {{$dataCount['apYrWiseDefaulterCount'][$ap->id]}}
                                                   @php    $defaulter = $defaulter+$dataCount['apYrWiseDefaulterCount'][$ap->id];    @endphp
                                               </a>
                                           @else
                                               {{0}}
                                           @endif
                                       </td>
                                       <td class="text-center">
                                        @if(isset($dataCount['apYrWiseSettledAssetCount'][$ap->id]))
                                            @if(isset($dataCount['apYrWiseDefaulterCount'][$ap->id]))
                                                    {{round((($dataCount['apYrWiseDefaulterCount'][$ap->id]/$dataCount['apYrWiseSettledAssetCount'][$ap->id])*100), 2)}}
                                            @else
                                                {{'0'}}
                                            @endif
                                        @else
                                                {{'0'}}
                                        @endif
                                       
                                       </td>
                                   </tr>
                                @endforeach
                                   <tr class="bg-pprimary ">
                                       <td></td>
                                       <td class="text-center">Total</td>
                                       <td class="text-center">{{$settled}}</td>
                                       <td class="text-center">{{$defaulter}}</td>
                                       <td class="text-center">
                                       @if($settled > 0)
                                           @if($defaulter > 0)
                                                {{round((($defaulter/$settled)*100),2)}}
                                           @else
                                           {{"0"}}
                                           @endif
                                        @else
                                           {{"0"}}
                                       @endif    
                                       </td>
                                   </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>


<!-- Model  ZP Defaulters -->
<div class="modal fade listOfZPDefaulterModalView" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 33px 0 0 0;">
            <div class="modal-header" style="background-color: #ff9000">
                <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-close"></i>
                </button>
                <h4 style="color: #fff;font-family: 'Old Standard TT', serif;">Defaulter List of <span id="zpname"></span> for the Financial Year {{$fy_years}}</h4>
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable12" id="" style="width:100%">
                        <thead>
                        <tr class="tr-row">
                            <td>SL</td>
                            <td>Zila Parishad</td>
                            <td>Asset Category</td>
                            <td>Asset Code</td>
                            <td>Asset Name</td>
                            <td>Defaulter Name</td>
                            <td>Defaulter Father Name</td>
                            <td>PAN No.</td>
							<td>Default Amount(in ₹)</td>
                            <td>Managed by</td>
                            <td>View Details</td>
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

<!-- Model  AP Defaulters -->
<div class="modal fade listOfAPDefaulterModalView" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 33px 0 0 0;">
            <div class="modal-header" style="background-color: #ff9000">
                <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-close"></i>
                </button>
                <h4 style="color: #fff;font-family: 'Old Standard TT', serif;">Defaulter List of AP:-<span id="apname"></span> for the Financial Year {{$fy_years}}</h4>
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
                            <td>PAN No.</td>
							<td>Default Amount(in ₹)</td>
                            <td>Managed by</td>
							<td>View Details</td>
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
                        title: 	   'Defaulters for the Zila Parishad : {{$district_name}} and its Anchalik Panchayats ({{$fy_years}})',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
        });

        //        List of Defaulter Zila Wise Model
        $('.listOfZPDefaulterModalViewC').on('click', function(e){
            e.preventDefault();
            if ($.fn.DataTable.isDataTable('.dataTable12') ) {
                $('.dataTable12').dataTable().fnClearTable();
                $('.dataTable12').dataTable().fnDestroy()

            }

            $('.listOfZPDefaulterModalView').modal('hide');

            var zfyyear = $(this).data('zfyyear');
            var zid = $(this).data('zid');
            var zpname = $(this).data('zpname');

            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('admin.Osr.dashboard.listOfZPDefaulterZilaWise')}}',
                dataType: "json",
                data: {zfyyear : zfyyear, zid : zid},
                success: function (data) {
                    if (data.msgType == true) {

                        var dataSet=data.data;
                        $('.dataTable12').DataTable( {
                            dom: 'Bfrtip',
                            buttons: [
                                {
                                    extend:    'excelHtml5',
                                    title:     'Defaulter List of '+zpname+' for the Financial Year {{$fy_years}}',
                                    text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                                    titleAttr: 'Excel'
                                }
                            ],
                            data: dataSet,
                            columns: [
                                { title: "SL" },
                                { title: "Zila Parishad" },
                                { title: "Asset Category" },
                                { title: "Asset Code" },
                                { title: "Asset Name" },
                                { title: "Defaulter Name" },
                                { title: "Defaulter Father Name" },
                                { title: "PAN No." },
								{ title: "Default Amount(in ₹)" },
                                { title: "Managed by" },
								{ title: "View Detail" }
                            ]
                        } );

                        $('#zpname').text(zpname);
                        $('.listOfZPDefaulterModalView').modal('show');

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
                                    title:     'Defaulter List of '+apname+' for the Financial Year {{$fy_years}}',
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
                                { title: "PAN No." },
								{ title: "Default Amount(in ₹)" },
                                { title: "Managed by" },
								{ title: "View Detail" }
                            ]
                        } );

                        $('#apname').text(apname);
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