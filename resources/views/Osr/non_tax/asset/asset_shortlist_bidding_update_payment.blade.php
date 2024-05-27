@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{asset('mdas_assets/css/multi-select.css')}}"/>
    <style>


        .custom-header{
            padding: 10px;
            background-color: #6d133c;
            color: #eee;
        }

        .form-control {
            height: 28px;
            padding: 2px 5px;
            font-size: 12px;
        }

        label {
            font-size: 11px;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .mb40{
            margin-bottom: 40px;
        }

        .popover.top>.arrow:after {
            border-top-color: #0f436d;
        }

        .ms-container .ms-selectable li.disabled, .ms-container .ms-selection li.disabled {
            background-color: white;
            color: #333;
        }

        .ms-container .ms-selectable li.disabled.ms-selected, .ms-container .ms-selection li.disabled.ms-selected {
            color: green !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Home</a></li>
            <li><a href="{{url('osr/osr_panel')}}/{{encrypt($data['fy_id'])}}">OSR</a></li>
            <li class="active">Asset Tender and Payment</li>
        </ol>
    </div>

    <div class="container mb40">
        <div class="row mt40">
            <form action="{{route('osr.non_tax.asset_shortlist_bidding_update_payment')}}" method="post">
                @csrf
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-control" name="branch_id" id="branch_id" required>
                            <option value="">---Select---</option>
                            @foreach($data['branchList'] AS $li)
                                <option value="{{$li->id}}" @if($data['branch_id']==$li->id)selected="selected"@endif>{{$li->branch_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>Financial Year</label>
                        <select class="form-control" name="fy_id" id="fy_id" required>
                            <option value="">---Select---</option>
                            @foreach($data['fyList'] AS $li)
                                <option value="{{$li->id}}" @if($data['data_fy_id']==$li->id)selected="selected"@endif>{{$li->fy_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group mt20">
                        <button type="submit" class="btn btn-primary btn-save btn-sm">
                            <i class="fa fa-search"></i>
                            Search
                        </button>
                    </div>
                </div>
            </form>
        </div>


        <div class="row mt40">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h4>{{$data['head_txt']}}</h4>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable1">
                        <thead>
                        <tr class="bg-primary">
                            <td>SL</td>
                            <td>Asset Code / Asset Name</td>
                            <td>Settlement Amount <br/> (in ₹)</td>
                            <td>Gap Period Collection <br/> (in ₹)</td>
                            <td>Revenue Collection From BID <br/> (in ₹)</td>
							<td>Defaulter</td>
                            <td>Tender Information</td>
                            <td>Revenue Collection Details</td>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($data['assetList'] AS $li)
                            <tr>
                                <td>{{$i}}</td>
                                <td>
                                    {{$li->asset_code_n}} <br/>
                                    {{$li->asset_name}}
                                </td>
                                <td>@if($li->bidding_status==1)<span class="money_txt">{{$li->settlement_amt}}</span>@else<span style="color:red">{{"Settlement Pending"}}</span>@endif</td>
                                <td>@if($li->gap_period_status==1)<span class="money_txt">{{$li->tot_gap_collected_amt}}<span>@else{{"0"}}@endif</td>
                                <td>@if($li->bidding_status==1)<span class="money_txt">{{$li->tot_ins_collected_amt}}</span>@else{{"0"}}@endif</td>
								<td>
                                    @if($li->defaulter_status==1)
                                        <span class="badge" style="background-color: red;color:#fff">{{"YES"}}</span>
                                    @else
                                        <span>---</span>
                                    @endif

                                </td>
                                <td>
                                    
                                    <a target="_blank" href="{{url('osr/non_tax/asset/bidding/fy')}}/{{base64_encode(base64_encode(base64_encode($li->asset_code_n)))}}/{{base64_encode(base64_encode(base64_encode($data['data_fy_id'])))}}" class="btn btn-primary btn-xs" data-aid="{{$li->id}}" data-ac="{{$li->asset_code}}" data-an="{{$li->asset_name}}">
                                        <i class="fa fa-plus"></i>
                                        Tender
                                    </a>
								
                                </td>
                                <td>
                                    <a target="_blank" href="{{url('osr/non_tax/asset/track/fy')}}/{{base64_encode(base64_encode(base64_encode($li->asset_code_n)))}}/{{base64_encode(base64_encode(base64_encode($data['data_fy_id'])))}}" class="btn btn-primary btn-xs" data-aid="{{$li->id}}" data-ac="{{$li->asset_code}}" data-an="{{$li->asset_name}}">
                                        <i class="fa fa-cog"></i>
                                        View/Edit
                                    </a>
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
        // ------------DATA TABLE FOR GHATS--------------------------
        $(document).ready(function () {
            $('#dataTable1').DataTable({
                dom: 'Bfrtip',
                ordering: false,
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


        $('.osr_fy_assign').on('click', function(e){
            e.preventDefault();
            var asset_id= window.btoa(window.btoa(window.btoa($(this).data('a_id'))));
            var osr_fy_id= window.btoa(window.btoa(window.btoa({{$data['fy_id']}})));

            location.href = "{{url('osr/non_tax/dw_asset/bidding/fy')}}"+"/"+asset_id+"/"+osr_fy_id;
        });


        $('.osr_fy_payment').on('click', function(e){
            e.preventDefault();
            var asset_id= window.btoa(window.btoa(window.btoa($(this).data('a_id'))));
            var osr_fy_id= window.btoa(window.btoa(window.btoa({{$data['fy_id']}})));

            location.href = "{{url('osr/non_tax/dw_asset/track/fy')}}"+"/"+asset_id+"/"+osr_fy_id;
        });


    </script>
@endsection
