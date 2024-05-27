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
								<td>
                                    @if(isset($data['defaulter'][$branch->id]))
                                    <a href="#" data-bfyyear="{{$data['fy_id']}}" data-bid="{{$branch->id}}" data-zid="{{$data['zp_id']}}" data-apid="{{$data['ap_id']}}" data-gpid="{{$data['gp_id']}}"   class="listOfBranchDefaulterModalViewC">
                                        {{$data['defaulter'][$branch->id]}}
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

            @endif
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
                <h4 style="color: #fff;font-family: 'Old Standard TT', serif;">{{$data['head_txt2']}}</h4>
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable14" id="" style="width:100%">
                        <thead>
                        <tr class="tr-row">
                            <td>SL</td>
                            <td>Zila Parishad</td>

                            @if($data['ap_id']==NULL)

                            @elseif($data['gp_id']==NULL)
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
                                    title:     '{{$data['head_txt2']}}',
                                    text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                                    titleAttr: 'Excel'
                                }
                            ],
                            data: dataSet,
                            columns: [
                                { title: "SL" },
                                { title: "Zila Parishad" },
                                    @if($data['ap_id']==NULL)

                                    @elseif($data['gp_id']==NULL)
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
                                { title: "Mobile" },
                                { title: "Asset Under" }
                            ]
                        } );
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