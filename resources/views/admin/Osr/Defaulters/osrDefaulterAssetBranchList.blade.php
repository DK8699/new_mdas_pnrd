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
            <li class="active">Defaulters :- {{$fy_years}} </li>
        </ol>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 mb20">
            @if($ap_id)
                <a class="btn btn-info" href="{{route('admin.Osr.subAPWiseAssetDefaulter',[$id,$fy_id,$ap_id])}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
            @else
              <a class="btn btn-info" href="{{route('admin.Osr.subDistrictWiseDefaulterReport',[$id,$fy_id])}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-primary mt10">
                    <div class="panel-heading" style="text-align: center">
                        {{$heade_text}}
                    </div>
                    <div class="panel-body gray-back">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable1">
                                <thead>
                                    <tr class="bg-primary">
                                        <td class="text-center">Asset Category</td>
                                        <td class="text-center">Settled Assets</td>
                                        <td class="text-center">Defaulters</td>
                                        <td class="text-center">% of Defaulters</td>
                                    </tr>
                                 </thead>
                                 <tbody>
                                     @php $settled=0; $defaulter=0; @endphp
                               @foreach($masterBranches as $mbranche)
                                   <tr>
                                       <td>
                                           @if($ap_id==NULL)
                                             <a href="{{route('admin.Osr.osrDefaulterSingleBranchList',[$id,$fy_id,$mbranche->id])}}"> {{$mbranche->branch_name}}</a>
                                           @elseif($gp_id==NULL)
                                             <a href="{{route('admin.Osr.osrDefaulterSingleBranchList',[$id,$fy_id,$mbranche->id,$ap_id])}}"> {{$mbranche->branch_name}}</a>
                                           @else
                                             <a href="{{route('admin.Osr.osrDefaulterSingleBranchList',[$id,$fy_id,$mbranche->id,$ap_id,$gp_id])}}"> {{$mbranche->branch_name}}</a>
                                           @endif
                                       </td>
                                       <td class="text-center">
                                            @if(isset($dataCount['settledList'][$mbranche->id]))
                                                {{$dataCount['settledList'][$mbranche->id]}}
                                                @php $settled=$settled+$dataCount['settledList'][$mbranche->id]; @endphp
                                            @else
                                                {{0}}
                                            @endif
                                        </td>
                                       <td class="text-center">
                                           @if(isset($dataCount['defaulterList'][$mbranche->id]))
                                               <a href="#" data-bfyyear="{{$fy_id}}" data-bid="{{$mbranche->id}}" data-bname="{{$mbranche->branch_name}}" data-zid="{{$id}}" data-apid="{{$ap_id}}" data-gpid="{{$gp_id}}"   class="listOfBranchDefaulterModalViewC">
                                                   {{$dataCount['defaulterList'][$mbranche->id]}}
                                                   @php $defaulter=$defaulter+$dataCount['defaulterList'][$mbranche->id]; @endphp
                                               </a>
                                           @else
                                               {{0}}
                                           @endif
                                       </td>
                                        <td class="text-center">
                                            @if(isset($dataCount['settledList'][$mbranche->id]))
                                                @if(isset($dataCount['defaulterList'][$mbranche->id]))
                                                    {{round(($dataCount['defaulterList'][$mbranche->id]/$dataCount['settledList'][$mbranche->id])*100, 2)}}
                                                @else
                                                    {{'0'}}
                                                @endif
                                            @else
                                                {{'0'}}
                                            @endif
                                        </td>
                                   </tr>
                               @endforeach   
                                   <tr class="bg-pprimary">
                                       <td class="text-center">Total</td>
                                       <td class="text-center">{{$settled}}</td>
                                       <td class="text-center">{{$defaulter}}</td>
                                       <td class="text-center">
                                            @if($settled > 0)
                                                {{round($defaulter/$settled*100, 2)}}
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

<!-- Model  Branch Defaulters -->
<div class="modal fade listOfBranchDefaulterModalView" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 33px 0 0 0;">
            <div class="modal-header" style="background-color: #ff9000">
                <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-close"></i>
                </button>
                <h4 style="color: #fff;font-family: 'Old Standard TT', serif;">Defaulter List of <span id="bname"></span> for the Financial Year {{$fy_years}}</h4>
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable14" id="" style="width:100%">
                        <thead>
                        <tr class="tr-row">
                            <td>SL</td>
                            <td>Zila Parishad</td>

                            @if($ap_id==NULL)

                            @elseif($gp_id==NULL)
                                <td>Anchalik Parishad</td>
                            @else
                                <td>Anchalik Parishad</td>
                                <td>Gram Panchayat</td>
                            @endif
                            <td>Asset Category</td>
                            <td>Asset Code</td>
                            <td>Asset Name</td>
                            <td>Defaulter Name</td>
                            <td>Defaulter Father Name</td>
                            <td>PAN No.</td>
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
                                    title:     '{{$heade_text}}',
                                    text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                                    titleAttr: 'Excel'
                                }
                            ]
            });
        });



        //        List of Defaulter Branch Wise Model
        $('.listOfBranchDefaulterModalViewC').on('click', function(e){
            e.preventDefault();
            if ($.fn.DataTable.isDataTable('.dataTable14') ) {
                $('.dataTable14').dataTable().fnClearTable();
                $('.dataTable14').dataTable().fnDestroy()

            }

            $('.listOfBranchDefaulterModalView').modal('hide');
            var bfyyear = $(this).data('bfyyear');
            var zid = $(this).data('zid');
            var apid = $(this).data('apid');
            var gpid = $(this).data('gpid');
            var bid = $(this).data('bid');
            var bname = $(this).data('bname');

            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('admin.Osr.dashboard.listOfDefaulterBranchWise')}}',
                dataType: "json",
                data: {bfyyear : bfyyear, zid : zid, apid : apid, gpid : gpid, bid : bid},
                success: function (data) {
                    if (data.msgType == true) {

                        var dataSet=data.data;
                        $('.dataTable14').DataTable( {
                            dom: 'Bfrtip',
                            buttons: [
                                {
                                    extend:    'excelHtml5',
                                    title:     'Defaulter List of '+bname+' for the Financial Year {{$fy_years}}',
                                    text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                                    titleAttr: 'Excel'
                                }
                            ],
                            data: dataSet,
                            columns: [
                                { title: "SL" },
                                { title: "Zila Parishad" },
                                    @if($ap_id==NULL)

                                    @elseif($gp_id==NULL)
                                { title: "Anchalik Parishad" },
                                    @else
                                { title: "Anchalik Parishad" },
                                { title: "Gram Panchayat" },
                                    @endif
                                { title: "Asset Category" },
                                { title: "Asset Code" },
                                { title: "Asset Name" },
                                { title: "Defaulter Name" },
                                { title: "Defaulter Father Name" },
                                { title: "PAN No." },
                                { title: "Managed by" },
								{ title: "View Detail" }
                            ]
                        } );

                        $('#bname').text(bname);
                        $('.listOfBranchDefaulterModalView').modal('show');

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