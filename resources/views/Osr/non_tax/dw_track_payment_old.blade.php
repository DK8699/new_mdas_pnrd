@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <script src="{{asset('mdas_assets/Chart.js-2.8.0/dist/Chart.min.js')}}"></script>
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.css" rel="stylesheet" type="text/css"/>
    <link href="{{asset('mdas_assets/css/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        body {
            background-color: #ddd;
        }

        /*card {
            border: solid 2px #ddd;
            background-color: #f6f6f6;
            color: #606267;

        }*/

        .card span {
            font-weight: bolder;
        }

        strong {
            color: red;
        }

        .headd {
            margin-top: 0px;
            color: #333;
            text-transform: uppercase;
            padding: 10px;
            /*text-shadow: 1.1px 1.1px rgb(49, 44, 44);*/
            background: #1db1d3;
            /*box-shadow: 0 0 2px 1px #046a77;*/
        }

        .mb40 {
            margin-bottom: 40px;
        }

        .Zebra_DatePicker_Icon_Wrapper {
            width: 100% !important;
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

        .m-cl {

            padding: 20px;
        }

        .card1 {
            text-align: center;
            border: 2px solid #fff;
            padding: 5px 3px;
        }

        .horubtn {
            float: right;
            padding: 3px 10px;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .pdiv {
            padding: 17px;
        }

        .table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {
            border: 1px solid #ddd;
            font-size: 13px;
        }

        .apList button.btn, .gpList button.btn {
            background-color: #ddd;
            color: #333;
            border-radius: 0;
        }
    </style>

@endsection

@section('content')

    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li><a href="{{url('osr/osr_panel')}}/{{encrypt($data['fy_id'])}}">OSR</a></li>
            <li class="active">Payment</li>
        </ol>
    </div>

    <div class="container mb40">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label style="color: #ddd;">Asset Code</label>
                                <input type="text" class="form-control" value="{{$assetData->asset_code}}"
                                       readonly="readonly"/>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label style="color: #ddd;">Asset Name:</label>
                                <input type="text" class="form-control" value="{{$assetData->asset_name}}"
                                       readonly="readonly"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <form action="#" method="POST" id="financialBiddingForm">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <label style="color: #ddd;">Select Financial Year</label>
                                    <select name="osr_fy_id" id="fy_year_id" class="form-control">
                                        <option value="{{$osrFyYear->id}}">{{$osrFyYear->fy_name}}</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading text-center">{{$osrFyYear->fy_name}}</div>
                        <div class="panel-body">
                            <div class="row">
                                @foreach($monthArray AS $key=>$value)
                                    <div class="col-md-3 m-cl">
                                        <div class="card1" @if(isset($settlementData->awarded_date) && $key==\Carbon\Carbon::parse($settlementData->awarded_date)->format('m')) style="border:2px solid green" @endif>{{$value}}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <canvas id="bar-chart" style="width:100%;height:253px"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <h4>Gap Period Collection Section</h4>
            <hr/>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Gap Period Collection</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        @if($gapPeriod)
                                            <button type="button" class="btn btn-primary btn-xs pull-right"
                                                    style="margin-bottom: 5px;" data-toggle="modal"
                                                    data-target="#gapEdit">View & Edit Amount
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-primary btn-xs pull-right"
                                                    style="margin-bottom: 5px;" data-toggle="modal"
                                                    data-target="#gapAdd">Add Amount
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    @if($gapPeriod)
                                        <table class="table table-bordered">
                                            <tr class="bg-info">
                                                <td>From</td>
                                                <td>To</td>
                                                <td>Collected Amount</td>
                                                <td>ZP Share</td>
                                                <td>AP Share</td>
                                                <td>GP Share</td>
                                                <td>Managed By</td>
                                            </tr>
                                            <tr>
                                                <td>{{date('d-m-Y', strtotime($gapPeriod->form_date))}}</td>
                                                <td>{{date('d-m-Y', strtotime($gapPeriod->to_date))}}</td>
                                                <td><span class="money_txt"> {{$gapPeriod->collected_amount}}</span>
                                                </td>
                                                <td><span class="money_txt"> {{$gapPeriod->gap_zp_share}}</span></td>
                                                <td><span class="money_txt"> {{$gapPeriod->gap_ap_share}}</span></td>
                                                <td><span class="money_txt"> {{$gapPeriod->gap_gp_share}}</span></td>
                                                <td>{{$gapPeriod->managed_by}}</td>
                                            </tr>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            @if($settlementData)
                <h4>Bidding Summary</h4>
                <hr/>

                <div class="panel panel-primary">
                    <div class="panel-heading text-center">Bidding Details</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <table class="table table-bordered">
                                    <tr class="bg-info">
                                        <td>Settlement Date</td>
                                        <td>Bidder Name</td>
                                        <td>Forfeited Earnest Money</td>
                                        <td>Settled Amount</td>
                                        <td>Security Money Deposit</td>
                                        <td>Managed By</td>
                                    </tr>
                                    <tr>
                                        <td>{{$settlementData->awarded_date}}</td>
                                        <td>@if(isset($acceptedBidderData))
                                                <span>{{$acceptedBidderData->b_f_name}} {{$acceptedBidderData->b_m_name}} {{$acceptedBidderData->b_l_name}}</span>
                                            @else
                                                {{"NA"}}
                                            @endif
                                        </td>
                                        <td><span class="money_txt">{{$settlementData->total_forfeited_amount}}</span>
                                        </td>
                                        <td>@if($acceptedBidderData)<span
                                                    class="money_txt">{{$acceptedBidderData->bidding_amt}}</span>@else{{"NA"}}@endif
                                        </td>
                                        <td>@if($settlementData)<span
                                                    class="money_txt">{{$settlementData->security_deposit}}</span>@else{{"NA"}}@endif
                                        </td>
                                        <td>@if(isset($settlementData->managed_by)){{$settlementData->managed_by}}@else{{"NA"}}@endif</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{---------------------------------- Forfeited  Earnest Money SECTION -------------------------------------------------------------------------------------------}}

                @if($settlementData->total_forfeited_amount > 0)
                    <h4>Forfeited Earnest Money Section</h4>
                    <hr/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Forfeited Earnest Money Section</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-primary btn-xs pull-right"
                                                        style="margin-bottom: 5px;" data-toggle="modal"
                                                        data-target="#forfeit_earnest_money">Forfeited Earnest Money Sharing
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <table class="table table-bordered">
                                                <tr class="bg-info">
                                                    <td>Forfeited Earnest Money</td>
                                                    <td>ZP Share</td>
                                                    <td>AP Share</td>
                                                    <td>GP Share</td>
                                                    <td>Sharing Date</td>
                                                    <td>RTGS/NEFT No.</td>
                                                    <td>Sharing Remarks</td>
                                                </tr>
                                                <tr>

                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="forfeit_earnest_money" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-mm">
                            <div class="modal-content">
                                <div class="modal-header  bg-primary">
                                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal"
                                            aria-hidden="true">
                                        <i class="fa fa-close"></i>
                                    </button>
                                    <h5 class="modal-title text-center">Forfeited Earnest Money</h5>
                                </div>
                                <form action="#" method="POST" id="forfeited_earnest_money_save" autocomplete="off">
                                    <input type="hidden" name="asset_code" value="{{$assetData->asset_code}}"/>
                                    <input type="hidden" name="fy_id" value="{{$osrFyYear->id}}"/>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <div class="form-group">
                                                    <label>Forfeited Earnest Money</label>
                                                    <p>
                                                        <span class="money_txt">{{$settlementData->total_forfeited_amount}}</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <div class="form-group">
                                                    <label><span style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">Estimated ZP Share (20%)</span></label>
                                                    <p>
                                                        <span class="money_txt">{{round($settlementData->total_forfeited_amount*20/100, 2)}}</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <div class="form-group">
                                                    <label><span style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">Estimated AP Share (40%)</span></label>
                                                    <p>
                                                        <span class="money_txt">{{round($settlementData->total_forfeited_amount*40/100, 2)}}</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <div class="form-group">
                                                    <label><span style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">Estimated GP Share (40%)</span></label>
                                                    <p>
                                                        <span class="money_txt">{{round($settlementData->total_forfeited_amount*40/100, 2)}}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label>ZP Share <strong>*</strong></label>
                                                    <input type="text" class="form-control money" id="f_zp_share" name="f_zp_share" value=""/>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label>AP Share <strong>*</strong></label>
                                                    <input type="text" class="form-control money" id="f_ap_share" name="f_ap_share" value=""/>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label>GP Share <strong>*</strong></label>
                                                    <input type="text" class="form-control money" id="f_gp_share" name="f_gp_share" value=""/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            @if($data['level']=="ZP")
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <label>AP Share Distributed Among <strong>*</strong></label>
                                                    <div class="form-group">
                                                        <select class="apList form-control" id="f_ap_list" name="f_ap_list" multiple data-selected-text-format="count" data-count-selected-text="AP ({0})">
                                                            @foreach($data['apList'] AS $li)
                                                                <option selected="selected" disabled="disabled"
                                                                        value="{{$li->id}}">{{$li->anchalik_parishad_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <label>GP Share Distributed Among <strong>*</strong></label>
                                                    <div class="form-group">
                                                        <select class="gpList form-control" id="f_gp_list"
                                                                name="f_gp_list" data-live-search="true" multiple
                                                                data-selected-text-format="count"
                                                                data-count-selected-text="GP ({0})">
                                                            @foreach($data['gpList'] AS $li_m)
                                                                <optgroup label="{{$li_m['ap_name']}}">
                                                                    @foreach($li_m['list'] AS $li)
                                                                        <option selected="selected" disabled="disabled"
                                                                                value="{{$li->id}}">{{$li->gram_panchayat_name}}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif($data['level']=="AP")
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <label>GP Share Distributed Among <strong>*</strong></label>
                                                    <div class="form-group">
                                                        <select class="gpList form-control" id="f_gp_list"
                                                                name="f_gp_list" multiple
                                                                data-selected-text-format="count"
                                                                data-count-selected-text="GP ({0})">
                                                            @foreach($data['gpList'] AS $li)
                                                                <option selected="selected" disabled="disabled"
                                                                        value="{{$li->id}}">{{$li->gram_panchayat_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label>RTGS No./NEFT No.</label>
                                                    <input type="text" class="form-control" id="f_transaction_no"
                                                           name="f_transaction_no" value=""/>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label>Transaction Date</label>
                                                    <input type="text" class="form-control date" id="f_transaction_date"
                                                           name="f_transaction_date" value=""> </input>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label>Remarks of Sharing</label>
                                                    <textarea rows="3" type="text" class="form-control" id="f_remarks"
                                                              name="f_remarks"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary btn-save">
                                            Submit
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                {{--------------------------------- Forfeited  Earnest Money SECTION ENDED-------------------------------------------------------------------------------------------}}
                @php
                    $tot_rec_amt=0;
                    $tot_zp=0;
                    $tot_ap=0;
                    $tot_gp=0;
                    foreach($data['submittedInstalments'] AS $li_all){
                        $tot_rec_amt=$tot_rec_amt+$li_all->receipt_amt;
                        $tot_zp=$tot_ap+$li_all->zp_share;
                        $tot_ap=$tot_ap+$li_all->ap_share;
                        $tot_gp=$tot_ap+$li_all->gp_share;
                    }
                @endphp

                <h4>Installment Section</h4>
                <hr/>

                @if($settlementData->p_instalment_completed==0)
                    <div class="alert alert-danger">
                        <strong>Collected amount: </strong> <span class="money_txt">{{$settlementData->p_collected_amt}}</span> | <strong>Pending amount: </strong> <span class="money_txt">{{$settlementData->settlement_amt-$settlementData->p_collected_amt}}</span> | Consolidated - ZP Share: <span class="money_txt">{{$settlementData->p_total_zp_share}}</span>, AP Share: <span class="money_txt">{{$settlementData->p_total_ap_share}}</span>, GP Share: <span class="money_txt">{{$settlementData->p_total_gp_share}}</span>
                    </div>
                @else
                    @if($settlementData->p_is_rebate==1)
                        <div class="alert alert-success">
                            <strong>Collected amount: </strong> <span class="money_txt">{{$settlementData->p_collected_amt}}</span> | <strong>Rebate amount: </strong> <span class="money_txt">{{$settlementData->settlement_amt-$settlementData->p_collected_amt}}</span> | Consolidated - ZP Share: <span class="money_txt">{{$settlementData->p_total_zp_share}}</span>, AP Share: <span class="money_txt">{{$settlementData->p_total_ap_share}}</span>, GP Share: <span class="money_txt">{{$settlementData->p_total_gp_share}}</span>
                        </div>
                    @else
                        <div class="alert alert-success">
                            <strong>Collected amount: </strong> <span class="money_txt">{{$settlementData->p_collected_amt}}</span> | <strong>Pending amount: </strong> <span class="money_txt">{{$settlementData->settlement_amt-$settlementData->p_collected_amt}}</span> | Consolidated - ZP Share: <span class="money_txt">{{$settlementData->p_total_zp_share}}</span>, AP Share: <span class="money_txt">{{$settlementData->p_total_ap_share}}</span>, GP Share: <span class="money_txt">{{$settlementData->p_total_gp_share}}</span>
                        </div>
                    @endif
                @endif

                {{----------------------------------- DATA TABLE -----------------------------------------------------}}

                @php
                    $firstInstallmentLastDate = \Carbon\Carbon::parse($settlementData->awarded_date)->addDay(7)->format('d M Y');

                    $preYear=\Carbon\Carbon::parse($osrFyYear->fy_from)->format('Y');
                    $preTo=\Carbon\Carbon::parse($osrFyYear->fy_to)->format('Y');

                    $secondInstallmentLastDate = \Carbon\Carbon::parse("01-10-".$preYear)->format('d M Y');
                    $thirdInstallmentLastDate = \Carbon\Carbon::parse("01-01-".$preTo)->format('d M Y');
                @endphp

                <div class="row">


                    @foreach($instalments AS $li)
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">{{$li->name}} <span style="float: right">Last Date @if($li->id==1){{$firstInstallmentLastDate}}@elseif($li->id==2){{$secondInstallmentLastDate}}@else($li->id==3){{$thirdInstallmentLastDate}}@endif</span></div>
                                <div class="panel-body">


                                    @foreach($data['submittedInstalments'] AS $li_sub)
                                        @if(isset($submittedIns[$li->id][$li_sub->id]['data']->osr_master_instalment_id) && $submittedIns[$li->id][$li_sub->id]['data']->osr_master_instalment_id == $li_sub->id)


                                            @php

                                                //echo $submittedIns[$li->id][$li_sub->id]->osr_master_instalment_id;
                                                //echo $li->id."_____".$li_sub->id;
                                                //echo json_encode($data['submittedInstalments']);

                                            @endphp


                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="alert alert-success">
                                                        <i class="fa fa-check"></i>
                                                        @if($submittedIns[$li->id][$li_sub->id]->payment_mode =="F") Full @else Partial @endif payment submitted!
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Date of receipt</label>
                                                        <p class="">{{$submittedIns[$li->id][$li_sub->id]->date_of_receipt}}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Amount receipt</label>
                                                        <p class="money_txt">{{$submittedIns[$li->id][$li_sub->id]->receipt_amt}}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($settlementData->p_is_rebate==1 && $li->id==$settlementData->p_instalment_no)
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label>Rebate amount</label>
                                                            <p class="money_txt">{{$settlementData->settlement_amt-$settlementData->p_collected_amt}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label>Rebate remarks</label>
                                                            <p class="">{{$submittedIns[$li->id][$li_sub->id]->rebate_remarks}}</p>
                                                        </div>
                                                    </div>
                                                    @if($submittedIns[$li->id]->supporting_doc_path)
                                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <label>Rebate remarks document</label>
                                                                <p><a target="_blank" href="{{$data['imgUrl'].$submittedIns[$li->id]->supporting_doc_path}}">Rebate Doc</a></p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Estimated Amount <p style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">20% of 1st Installent</p></label>
                                                        <p style="margin: 0;padding: 0"><span class="money_txt">{{20/100*$submittedIns[$li->id]->receipt_amt}}</span></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label>ZP Share</label>
                                                        <p class="money_txt">{{$submittedIns[$li->id]->zp_share}}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Estimated Amount <p style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">40% of 1st Installent</p></label>
                                                        <p style="margin: 0;padding: 0"><span class="money_txt">{{40/100*$submittedIns[$li->id]->receipt_amt}}</span></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label>AP Share</label>
                                                        <p class="money_txt">{{$submittedIns[$li->id]->ap_share}}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Estimated Amount <p style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">40% of 1st Installent</p></label>
                                                        <p style="margin: 0;padding: 0"><span class="money_txt">{{40/100*$submittedIns[$li->id]->receipt_amt}}</span></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label>GP Share</label>
                                                        <p class="money_txt">{{$submittedIns[$li->id]->gp_share}}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                @if($data['level']=="ZP")
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <label>AP Share Distributed Among </label>
                                                        <div class="form-group">
                                                            <select class="apList form-control"  name="ap_list" multiple data-selected-text-format="count" data-count-selected-text="AP ({0})">
                                                                @foreach($submittedIns[$li->id]['apList'] AS $li_a)
                                                                    <option selected="selected" disabled="disabled" value="{{$li_a->id}}">{{$li_a->anchalik_parishad_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <label>GP Share Distributed Among </label>
                                                        <div class="form-group">
                                                            <select class="gpList form-control" name="gp_list" data-live-search="true" multiple data-selected-text-format="count" data-count-selected-text="GP ({0})">
                                                                @foreach($submittedIns[$li->id]['gpList'] AS $key=>$li_m)
                                                                    <optgroup label="{{$key}}">
                                                                        @foreach($li_m AS $li_g)
                                                                            <option selected="selected" disabled="disabled" value="{{$li_g->id}}">{{$li_g->gram_panchayat_name}}</option>
                                                                        @endforeach
                                                                    </optgroup>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                @elseif($data['level']=="AP")
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <label>GP Share Distributed Among </label>
                                                        <div class="form-group">
                                                            <select class="gpList form-control" id="gp_list_ins_{{$li->id}}" name="gp_list_ins_{{$li->id}}" multiple data-selected-text-format="count" data-count-selected-text="GP ({0})">
                                                                @foreach($data['gpList'] AS $li_g)
                                                                    <option selected="selected" disabled="disabled" value="{{$li_g->id}}">{{$li_g->gram_panchayat_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label>NEFT No./RTGS No.</label>
                                                        <p class="">{{$submittedIns[$li->id]->transaction_no}}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Transaction Date</label>
                                                        <p class="">{{$submittedIns[$li->id]->transaction_date}}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Sharing Remarks</label>
                                                        <p class="">{{$submittedIns[$li->id]->remarks}}</p>
                                                    </div>
                                                </div>
                                            </div>

                                        @endif
                                    @endforeach

                                    @if(isset($submittedIns[$li->id]->osr_master_instalment_id) && $submittedIns[$li->id]->osr_master_instalment_id==$li->id)

                                        {{--<div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="alert alert-success">
                                                    <i class="fa fa-check"></i>
                                                    @if($submittedIns[$li->id]->payment_mode =="F") Full @else Partial @endif payment submitted!
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label>Date of receipt</label>
                                                    <p class="">{{$submittedIns[$li->id]->date_of_receipt}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label>Amount receipt</label>
                                                    <p class="money_txt">{{$submittedIns[$li->id]->receipt_amt}}</p>
                                                </div>
                                            </div>
                                        </div>

                                        @if($settlementData->p_is_rebate==1 && $li->id==$settlementData->p_instalment_no)
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Rebate amount</label>
                                                        <p class="money_txt">{{$settlementData->settlement_amt-$settlementData->p_collected_amt}}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Rebate remarks</label>
                                                        <p class="">{{$submittedIns[$li->id]->rebate_remarks}}</p>
                                                    </div>
                                                </div>
                                                @if($submittedIns[$li->id]->supporting_doc_path)
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Rebate remarks document</label>
                                                        <p><a target="_blank" href="{{$data['imgUrl'].$submittedIns[$li->id]->supporting_doc_path}}">Rebate Doc</a></p>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label>Estimated Amount <p style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">20% of 1st Installent</p></label>
                                                    <p style="margin: 0;padding: 0"><span class="money_txt">{{20/100*$submittedIns[$li->id]->receipt_amt}}</span></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label>ZP Share</label>
                                                    <p class="money_txt">{{$submittedIns[$li->id]->zp_share}}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label>Estimated Amount <p style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">40% of 1st Installent</p></label>
                                                    <p style="margin: 0;padding: 0"><span class="money_txt">{{40/100*$submittedIns[$li->id]->receipt_amt}}</span></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label>AP Share</label>
                                                    <p class="money_txt">{{$submittedIns[$li->id]->ap_share}}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label>Estimated Amount <p style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">40% of 1st Installent</p></label>
                                                    <p style="margin: 0;padding: 0"><span class="money_txt">{{40/100*$submittedIns[$li->id]->receipt_amt}}</span></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label>GP Share</label>
                                                    <p class="money_txt">{{$submittedIns[$li->id]->gp_share}}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            @if($data['level']=="ZP")
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <label>AP Share Distributed Among </label>
                                                    <div class="form-group">
                                                        <select class="apList form-control"  name="ap_list" multiple data-selected-text-format="count" data-count-selected-text="AP ({0})">
                                                            @foreach($submittedIns[$li->id]['apList'] AS $li_a)
                                                                <option selected="selected" disabled="disabled" value="{{$li_a->id}}">{{$li_a->anchalik_parishad_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <label>GP Share Distributed Among </label>
                                                    <div class="form-group">
                                                        <select class="gpList form-control" name="gp_list" data-live-search="true" multiple data-selected-text-format="count" data-count-selected-text="GP ({0})">
                                                            @foreach($submittedIns[$li->id]['gpList'] AS $key=>$li_m)
                                                                <optgroup label="{{$key}}">
                                                                    @foreach($li_m AS $li_g)
                                                                        <option selected="selected" disabled="disabled" value="{{$li_g->id}}">{{$li_g->gram_panchayat_name}}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif($data['level']=="AP")
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <label>GP Share Distributed Among </label>
                                                    <div class="form-group">
                                                        <select class="gpList form-control" id="gp_list_ins_{{$li->id}}" name="gp_list_ins_{{$li->id}}" multiple data-selected-text-format="count" data-count-selected-text="GP ({0})">
                                                            @foreach($data['gpList'] AS $li_g)
                                                                <option selected="selected" disabled="disabled" value="{{$li_g->id}}">{{$li_g->gram_panchayat_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label>NEFT No./RTGS No.</label>
                                                    <p class="">{{$submittedIns[$li->id]->transaction_no}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label>Transaction Date</label>
                                                    <p class="">{{$submittedIns[$li->id]->transaction_date}}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label>Sharing Remarks</label>
                                                    <p class="">{{$submittedIns[$li->id]->remarks}}</p>
                                                </div>
                                            </div>
                                        </div>--}}

                                    @elseif($settlementData->p_instalment_completed==0)
                                        <fieldset @if($settlementData->p_instalment_no+1<>$li->id)disabled="disabled"@endif">
                                            <form action="#" method="POST" id="installmentForm{{$li->id}}">
                                                <input type="hidden" name="ins" value="{{$li->id}}"/>
                                                <input type="hidden" name="asset_code" value="{{$assetData->asset_code}}"/>
                                                <input type="hidden" name="fy_id" value="{{$osrFyYear->id}}"/>
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <div class="form-group" id="payment_ins_{{$li->id}}">
                                                            @if($li->id<>3)
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="payment_ins_{{$li->id}}" value="P">Partial Payemnt
                                                                </label>
                                                            @endif
                                                            <label class="radio-inline">
                                                                <input type="radio" name="payment_ins_{{$li->id}}" value="F">Full Payment
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" id="amt_receipt_{{$li->id}}">
                                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label>Date of receipt <strong>*</strong></label>
                                                            <input type="text" name="receipt_date_{{$li->id}}" id="receipt_date_{{$li->id}}" class="form-control date"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label>Amount receipt <strong>*</strong></label>
                                                            <input type="text" name="receipt_amount_{{$li->id}}" id="receipt_amount_{{$li->id}}" class="form-control money"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label>Estimated Amount <p style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">20% of {{$li->id}} Installent</p></label>
                                                            <p style="margin: 0;padding: 0"><span id="es_zp_share{{$li->id}}"></span></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label>ZP Share <strong>*</strong></label>
                                                            <input type="text" name="zp_share_{{$li->id}}" id="zp_share_{{$li->id}}" class="form-control money"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label>Estimated Amount <p style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">40% of {{$li->id}} Installent</p></label>
                                                            <p style="margin: 0;padding: 0"><span id="es_ap_share{{$li->id}}"></span></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label>AP Share <strong>*</strong></label>
                                                            <input type="text" name="ap_share_{{$li->id}}" id="ap_share_{{$li->id}}" class="form-control money"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label>Estimated Amount <p style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">40% of {{$li->id}} Installent</p></label>
                                                            <p style="margin: 0;padding: 0"><span id="es_gp_share{{$li->id}}"></span></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label>GP Share <strong>*</strong></label>
                                                            <input type="text" name="gp_share_{{$li->id}}" id="gp_share_{{$li->id}}" class="form-control money"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    @if($data['level']=="ZP")
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <label>AP Share Distributed Among <strong>*</strong></label>
                                                            <div class="form-group">
                                                                <select class="apList form-control" id="ap_list_ins_{{$li->id}}" name="ap_list_ins_{{$li->id}}" multiple data-selected-text-format="count" data-count-selected-text="AP ({0})">
                                                                    @foreach($data['apList'] AS $li_a)
                                                                        <option selected="selected" disabled="disabled" value="{{$li_a->id}}">{{$li_a->anchalik_parishad_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <label>GP Share Distributed Among <strong>*</strong></label>
                                                            <div class="form-group">
                                                                <select class="gpList form-control" id="gp_list_ins_{{$li->id}}" name="gp_list_ins_{{$li->id}}" data-live-search="true" multiple data-selected-text-format="count" data-count-selected-text="GP ({0})">
                                                                    @foreach($data['gpList'] AS $li_m)
                                                                        <optgroup label="{{$li_m['ap_name']}}">
                                                                            @foreach($li_m['list'] AS $li_g)
                                                                                <option selected="selected" disabled="disabled" value="{{$li_g->id}}">{{$li_g->gram_panchayat_name}}</option>
                                                                            @endforeach
                                                                        </optgroup>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @elseif($data['level']=="AP")
                                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                                            <label>GP Share Distributed Among <strong>*</strong></label>
                                                            <div class="form-group">
                                                                <select class="gpList form-control" id="gp_list_ins_{{$li->id}}" name="gp_list_ins_{{$li->id}}" multiple data-selected-text-format="count" data-count-selected-text="GP ({0})">
                                                                    @foreach($data['gpList'] AS $li_g)
                                                                        <option selected="selected" disabled="disabled" value="{{$li_g->id}}">{{$li_g->gram_panchayat_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label>NEFT No./RTGS No. <strong>*</strong></label>
                                                            <input type="text" name="transaction_no_{{$li->id}}" id="transaction_no_{{$li->id}}" class="form-control"/>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label>Transaction Date <strong>*</strong></label>
                                                            <input type="text" name="transaction_date_{{$li->id}}" id="transaction_date_{{$li->id}}" class="form-control date"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label>Sharing remarks</label>
                                                            <textarea row="3" name="sharing_remark_{{$li->id}}" id="sharing_remark_{{$li->id}}" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary btn-block">
                                                                Submit
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </fieldset>
                                    @else
                                        <div class="row">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="alert alert-success">
                                                        <i class="fa fa-check"></i>
                                                        Installment completed
                                                    </div>
                                                </div>
                                            </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>


                <h4>Defaulter Section</h4>
                <hr/>


                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Defaulter</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-primary btn-xs pull-right"
                                                    style="margin-bottom: 5px;" data-toggle="modal"
                                                    data-target="#defaulter_modal">Mark Defaulter
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <tr class="bg-info">
                                                <td>Security Money Deposit</td>
                                                <td>ZP Share</td>
                                                <td>AP Share</td>
                                                <td>GP Share</td>
                                                <td>Sharing Date</td>
                                                <td>RTGS/NEFT No.</td>
                                                <td>Sharing Remarks</td>
                                            </tr>
                                            <tr>

                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="defaulter_modal" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-mm">
                        <div class="modal-content">
                            <div class="modal-header  bg-primary">
                                <button type="button" class="btn bg-red modal-close" data-dismiss="modal"
                                        aria-hidden="true">
                                    <i class="fa fa-close"></i>
                                </button>
                                <h5 class="modal-title text-center">Security Money Deposit Sharing</h5>
                            </div>
                            <form action="#" method="POST" id="mark_defaulter_save" autocomplete="off">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label>Security Money Deposit</label>
                                                <p>
                                                    <span class="money_txt">{{$settlementData->security_deposit}}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label><span
                                                            style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">Estimated ZP Share (20%)</span></label>
                                                <p>
                                                    <span class="money_txt">{{round($settlementData->security_deposit*20/100, 2)}}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label><span
                                                            style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">Estimated AP Share (40%)</span></label>
                                                <p>
                                                    <span class="money_txt">{{round($settlementData->security_deposit*40/100, 2)}}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label><span
                                                            style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">Estimated GP Share (40%)</span></label>
                                                <p>
                                                    <span class="money_txt">{{round($settlementData->security_deposit*40/100, 2)}}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>ZP Share <strong>*</strong></label>
                                                <input type="text" class="form-control money" id="d_zp_share"
                                                       name="f_zp_share" value=""/>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>AP Share <strong>*</strong></label>
                                                <input type="text" class="form-control money" id="d_ap_share"
                                                       name="f_ap_share" value=""/>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>GP Share <strong>*</strong></label>
                                                <input type="text" class="form-control money" id="d_gp_share"
                                                       name="f_gp_share" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @if($data['level']=="ZP")
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label>AP Share Distributed Among <strong>*</strong></label>
                                                <div class="form-group">
                                                    <select class="apList form-control" id="d_ap_list"
                                                            name="d_ap_list" multiple
                                                            data-selected-text-format="count"
                                                            data-count-selected-text="AP ({0})">
                                                        @foreach($data['apList'] AS $li)
                                                            <option selected="selected" disabled="disabled"
                                                                    value="{{$li->id}}">{{$li->anchalik_parishad_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label>GP Share Distributed Among <strong>*</strong></label>
                                                <div class="form-group">
                                                    <select class="gpList form-control" id="d_gp_list"
                                                            name="d_gp_list" data-live-search="true" multiple
                                                            data-selected-text-format="count"
                                                            data-count-selected-text="GP ({0})">
                                                        @foreach($data['gpList'] AS $li_m)
                                                            <optgroup label="{{$li_m['ap_name']}}">
                                                                @foreach($li_m['list'] AS $li)
                                                                    <option selected="selected" disabled="disabled"
                                                                            value="{{$li->id}}">{{$li->gram_panchayat_name}}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @elseif($data['level']=="AP")
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label>GP Share Distributed Among <strong>*</strong></label>
                                                <div class="form-group">
                                                    <select class="gpList form-control" id="d_gp_list"
                                                            name="d_gp_list" multiple
                                                            data-selected-text-format="count"
                                                            data-count-selected-text="GP ({0})">
                                                        @foreach($data['gpList'] AS $li)
                                                            <option selected="selected" disabled="disabled"
                                                                    value="{{$li->id}}">{{$li->gram_panchayat_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>RTGS No./NEFT No.</label>
                                                <input type="text" class="form-control" id="d_transaction_no"
                                                       name="d_transaction_no" value=""/>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Transaction Date</label>
                                                <input type="text" class="form-control date" id="d_transaction_date"
                                                       name="d_transaction_date" value=""> </input>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Remarks of Sharing</label>
                                                <textarea rows="3" type="text" class="form-control" id="d_remarks"
                                                          name="d_remarks"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-save">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>



                <h4>Bakijai Section</h4>
                <hr/>
                <div class="panel panel-primary">
                    <div class="panel-heading text-center">Baki Jari Case</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                @if($bakiJari)
                                    @if($bakiJari->status==1)
                                        <button type="button" class="btn btn-primary btn-xs pull-right"
                                                style="margin-bottom: 5px;" data-toggle="modal"
                                                data-target="#bakiJariEdit">Close Case
                                        </button>
                                    @endif
                                @else
                                    <button type="button" class="btn btn-primary btn-xs pull-right"
                                            style="margin-bottom: 5px;" data-toggle="modal" data-target="#bakiJariAdd">
                                        Add Case
                                    </button>
                                @endif
                            </div>
                            @if($bakiJari)
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <table class="table table-bordered">
                                        <tr class="bg-info">
                                            <td>Case No.</td>
                                            <td>Case Remarks</td>
                                            <td>Case Date</td>
                                            <td>Status</td>
                                            @if(isset($bakiJari->closed_date))
                                                <td>Closed Date</td>
                                                <td>Close Remarks</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>{{$bakiJari->case_no}}</td>
                                            <td>{{$bakiJari->case_remarks}}</td>
                                            <td>{{$bakiJari->case_date}}</td>
                                            @if($bakiJari->status==1)
                                                <td>Open</td>
                                            @elseif($bakiJari->status==2)
                                                <td>Closed</td>
                                            @endif
                                            @if(isset($bakiJari->closed_date))
                                                <td>{{$bakiJari->closed_date}}</td>
                                                <td>{{$bakiJari->closed_remarks}}</td>
                                            @endif
                                        </tr>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            @else
                <div class="alert alert-danger">
                    <strong>Bidding</strong> is not completed yet.
                </div>
            @endif



            {{------------------DATA TABLE ENDED-----------------------------------------}}
        </div>

        @if($gapPeriod)

        @else
            {{--------------------------------Gap Period Entry Form---------------------------------------------------------------}}
            <div class="modal fade" id="gapAdd" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-mm">
                    <div class="modal-content">
                        <div class="modal-header  bg-primary">
                            <button type="button" class="btn bg-red modal-close" data-dismiss="modal"
                                    aria-hidden="true">
                                <i class="fa fa-close"></i>
                            </button>
                            <h5 class="modal-title text-center">Gap Period Collection Entry</h5>
                        </div>
                        <div class="modal-body" style="padding: 10px 25px 0px 25px;">
                            <form class="form-horizontal" action="#" method="POST" id="gap_period" autocomplete="off">
                                <!------------------------- TOP BAND ------------------------------>
                                <input type="hidden" name="osr_master_fy_year_id" value="{{$osrFyYear->id}}">
                                <input type="hidden" name="osr_non_tax_asset_entry_id" value="{{$assetData->id}}">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="pdiv">
                                            <div class="form-group">
                                                <label>Amount <strong>*</strong></label>
                                                <input id="amount_gap" type="text" class="form-control money" name="collected_amount" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="">
                                                <label>Estimated ZP Share <span
                                                            style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">(20%)</span></label>
                                                <p style="margin: 0;padding: 0"><span class="money_txt"
                                                                                      id="g_zp_share"></span></p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="">
                                                <label>Estimated AP Share <span
                                                            style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">(40%)</span></label>
                                                <p style="margin: 0;padding: 0"><span class="money_txt"
                                                                                      id="g_ap_share"></span></p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="">
                                                <label>Estimated GP Share <span
                                                            style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">(40%)</span></label>
                                                <p style="margin: 0;padding: 0"><span class="money_txt"
                                                                                      id="g_gp_share"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-md-4 col-sm-12 col-xs-12 pdiv">
                                            <div class="form-group">
                                                <label>ZP Share <strong>*</strong></label>
                                                <input id="" type="text" class="form-control money" name="gap_zp_share"
                                                       value=""/>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 pdiv">
                                            <div class="form-group">
                                                <label>AP Share <strong>*</strong></label>
                                                <input id="" type="text" class="form-control money" name="gap_ap_share"
                                                       value=""/>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 pdiv">
                                            <div class="form-group">
                                                <label>GP Share <strong>*</strong></label>
                                                <input id="" type="text" class="form-control money" name="gap_gp_share"
                                                       value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-md-4 col-sm-12 col-xs-12 pdiv">
                                            <div class="form-group">
                                                <label>From <strong>*</strong></label>
                                                <input id="from" type="text" class="form-control date" name="from_date"
                                                       value=""/>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 pdiv">
                                            <div class="form-group">
                                                <label>To <strong>*</strong></label>
                                                <input id="to" type="text" class="form-control date" name="to_date"
                                                       value=""/>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 pdiv">
                                            <div class="form-group">
                                                <label>Managed By <strong>*</strong></label>
                                                <select class="form-control" name="managed_by">
                                                    <option value="ZP">ZP</option>
                                                    <option value="AP">AP</option>
                                                    <option value="GP">GP</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-md-6 col-sm-12 col-xs-12 pdiv">
                                            <div class="form-group">
                                                <label>RTGS No./NEFT No.</label>
                                                <input id="remarks" type="text" class="form-control"
                                                       name="transaction_no"> </input>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12 pdiv">
                                            <div class="form-group">
                                                <label>Transaction Date</label>
                                                <input id="transactionDate" type="text" class="form-control date"
                                                       name="transaction_date"> </input>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="pdiv">
                                            <div class="form-group">
                                                <label>Remarks of Sharing</label>
                                                <textarea rows="3" id="remarks" type="text" class="form-control"
                                                          name="remarks"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-save">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{--------------------------------Edit Baki Jari Entry Form-------------------------------------------------------------}}
        @if($bakiJari)
            <div class="modal fade" id="bakiJariEdit" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-mm">
                    <div class="modal-content">
                        <div class="modal-header  bg-primary">
                            <button type="button" class="btn bg-red modal-close" data-dismiss="modal"
                                    aria-hidden="true">
                                <i class="fa fa-close"></i>
                            </button>
                            <h5 class="modal-title text-center">Edit Baki Jari Case Entry</h5>
                        </div>
                        <div class="modal-body" style="padding: 10px 25px 0px 25px;">
                            <form class="form-horizontal" action="#" method="POST" id="bakiJariEditForm"
                                  autocomplete="off">
                                <!------------------------- TOP BAND ------------------------------>
                                <div class="row">
                                    <div class="col-md-8 pdiv">
                                        <input type="hidden" name="baki_jari_id" value="{{$bakiJari->id}}">
                                        <div class="form-group">
                                            <label>Case No. <strong>*</strong></label>
                                            <input id="case_no" type="text" class="form-control" name="case_no"
                                                   value="{{$bakiJari->case_no}}" readonly/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 pdiv">
                                        <div class="form-group">
                                            <label>Case Date <strong>*</strong></label>
                                            <input id="case_date" type="hidden" class="form-control" name="case_date"
                                                   value="{{$bakiJari->case_date}}"/>
                                            <input id="case_date" type="text" class="form-control" name="case_date"
                                                   value="{{$bakiJari->case_date}}" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Case Remarks<strong>*</strong></label>
                                            <textarea rows="5" id="case_remarks" type="text" class="form-control"
                                                      name="case_remarks"
                                                      readonly>{{$bakiJari->case_remarks}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Closed Date <strong>*</strong></label>
                                            <input id="closed_date" type="text" class="form-control date"
                                                   name="closed_date" value="{{$bakiJari->closed_date}}" readonly/>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Closed Remarks<strong>*</strong></label>
                                            <textarea rows="5" id="closed_remarks" type="text" class="form-control"
                                                      name="closed_remarks">{{$bakiJari->closed_remarks}}</textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-save">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{--------------------------------Baki Jari Entry Form-------------------------------------------------------------}}
            <div class="modal fade" id="bakiJariAdd" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-mm">
                    <div class="modal-content">
                        <div class="modal-header  bg-primary">
                            <button type="button" class="btn bg-red modal-close" data-dismiss="modal"
                                    aria-hidden="true">
                                <i class="fa fa-close"></i>
                            </button>
                            <h5 class="modal-title text-center">Baki Jari Case Entry</h5>
                        </div>
                        <div class="modal-body" style="padding: 10px 25px 0px 25px;">
                            <form class="form-horizontal" action="#" method="POST" id="bakiJariForm" autocomplete="off">
                                <!------------------------- TOP BAND ------------------------------>
                                <div class="row">
                                    <div class="col-md-8 pdiv">
                                        <input type="hidden" name="osr_master_fy_year_id" value="{{$osrFyYear->id}}">
                                        <input type="hidden" name="osr_non_tax_asset_entry_id"
                                               value="{{$assetData->id}}">
                                        <div class="form-group">
                                            <label>Case No. <strong>*</strong></label>
                                            <input id="case_no" type="text" class="form-control" name="case_no"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 pdiv">
                                        <div class="form-group">
                                            <label>Case Date <strong>*</strong></label>
                                            <input id="case_date" type="text" class="form-control date" name="case_date"
                                                   readonly/>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Case Remarks<strong>*</strong></label>
                                            <textarea rows="5" id="case_remarks" type="text" class="form-control"
                                                      name="case_remarks"></textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-save">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    <!-------------------------------------/Edit Bidder End------------------------------------------------------------->
        {{------------------------------Form Selling, Gap Period and Total Amount Calculations---------------------------------}}
        @php
            $form_selling_amt =0;
            $gap_period_amt =0;
            $total_forfeited_amount =0;
            if(!isset($tot_rec_amt)){
              $tot_rec_amt =0;
            }
                if($formSelling){
                 $form_selling_amt=$formSelling->cost_per_form * $formSelling->form_quantity;
                }
                if($gapPeriod){
                 $gap_period_amt=$gapPeriod->collected_amount;
                }
                if($settlementData) {
                $total_forfeited_amount = $settlementData->total_forfeited_amount;
                }
        $total_amount_graph =$form_selling_amt + $gap_period_amt + $tot_rec_amt+ $total_forfeited_amount;

        @endphp

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
            <script src="{{asset('mdas_assets/js/bootstrap-select.min.js')}}"></script>
            <script type="application/javascript">

                $('.date').Zebra_DatePicker({
                    format: 'Y-m-d'
                });

                $('.apList').selectpicker();
                $('.gpList').selectpicker();


                var indianRupeeFormatter = OSREC.CurrencyFormatter.getFormatter({
                    currency: 'INR',
                    symbol: '',
                });

                var indianRupeeFormatterText = OSREC.CurrencyFormatter.getFormatter({
                    currency: 'INR',
                    symbol: '₹',
                });

                $('.money').on('blur', function (e) {
                    e.preventDefault();
                    var value = OSREC.CurrencyFormatter.parse($(this).val(), {locale: 'en_IN'});
                    var formattedVal = indianRupeeFormatter(value);
                    $(this).val(formattedVal);
                });

                OSREC.CurrencyFormatter.formatAll({
                    selector: '.money_txt',
                    currency: 'INR'
                });

                /*--------------------------------- GENERAL DETAILS ------------------------------------------------*/
                @if($settlementData)
                    @foreach($instalments AS $li)

                $('#receipt_amount_{{$li->id}}').on('change', function (e) {
                    e.preventDefault();

                    var amt = $('#receipt_amount_{{$li->id}}').val();
                    amt = OSREC.CurrencyFormatter.parse(amt, {locale: 'en_IN'});

                    $('#es_zp_share{{$li->id}}').text(indianRupeeFormatterText(20 / 100 * amt));
                    $('#es_ap_share{{$li->id}}').text(indianRupeeFormatterText(40 / 100 * amt));
                    $('#es_gp_share{{$li->id}}').text(indianRupeeFormatterText(40 / 100 * amt));


                    var payment= $("input[name='payment_ins_{{$li->id}}']:checked").val();

                    var req_amt= {{$settlementData->settlement_amt-$settlementData->p_collected_amt}};

                    if(amt > req_amt || amt==0){

                        if(amt==0){
                            alert("Amount can not be zero");
                        }else{
                            alert("Amount is greater than the amount required");
                        }

                        $('#receipt_amount_{{$li->id}}').val('');

                        $('#es_zp_share{{$li->id}}').text('');
                        $('#es_ap_share{{$li->id}}').text('');
                        $('#es_gp_share{{$li->id}}').text('');
                    }

                    var ins={{$li->id}};

                    call_rebate(payment, amt, req_amt, ins);
                });


                $("input[name='payment_ins_{{$li->id}}']").on('change', function(e){
                    e.preventDefault();

                    var payment= $("input[name='payment_ins_{{$li->id}}']:checked").val();

                    var amt = $('#receipt_amount_{{$li->id}}').val();
                    amt = OSREC.CurrencyFormatter.parse(amt, {locale: 'en_IN'});

                    var req_amt= {{$settlementData->settlement_amt-$settlementData->p_collected_amt}};

                    var ins={{$li->id}};

                    call_rebate(payment, amt, req_amt, ins);

                });


                function call_rebate(payment, amt, req_amt, ins){
                    if(payment=="F" && amt < req_amt){
                        $('#rebate_section_'+ins).remove();

                        var rebate_amt= indianRupeeFormatterText({{$settlementData->settlement_amt-$settlementData->p_collected_amt}} - amt);

                        if(amt) {

                            $('#amt_receipt_' + ins).after('<div class="row" id="rebate_section_' + ins + '">' +
                                '<div class="col-md-12 col-sm-12 col-xs-12">' +
                                '<div class="form-group">' +
                                '<label>Rebate Amount : </label> <span> ' + rebate_amt + '</span>' +
                                '</div>' +
                                '<div class="form-group">' +
                                '<label>Remarks for rebate <strong>*</strong></label>' +
                                '<textarea type="text" name="rebate_remarks_' + ins + '" id="rebate_remarks_' + ins + '" class="form-control"></textarea>' +
                                '</div>' +
                                '</div>' +
                                '<div class="col-md-12 col-sm-12 col-xs-12">' +
                                '<div class="form-group">' +
                                '<label>Documents of rebate</label>' +
                                '<input type="file" name="rebate_doc_' + ins + '" id="rebate_doc_' + ins + '" class="form-control"/>' +
                                '</div>' +
                                '</div>' +
                                '</div>');
                        }
                    }else{
                        $('#rebate_section_'+ins).remove();
                        $('#rebate_remarks_'+ins).val('');
                        $('#rebate_doc_'+ins).val('');
                    }
                }


                $("#installmentForm{{$li->id}}").validate({
                    rules: {
                        date_{{$li->id}}: {
                            required: true
                        },
                        receipt_amount_{{$li->id}}: {
                            required: true
                        },
                        zp_share_{{$li->id}}: {
                            required: true
                        },
                        ap_share_{{$li->id}}: {
                            required: true
                        },
                        gp_share_{{$li->id}}: {
                            required: true
                        },
                        transaction_no_{{$li->id}}: {
                            required: true
                        }
                    },
                });

                $('#installmentForm{{$li->id}}').on('submit', function (e) {
                    e.preventDefault();

                    $('.form_errors').remove();

                    if ($('#installmentForm{{$li->id}}').valid()) {
                        $('.page-loader-wrapper').fadeIn();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '{{route('osr.non_tax.dw_asset.track.instalment')}}',
                            dataType: "json",
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                if (data.msgType == true) {
                                    swal("Success", data.msg, "success");
                                    location.reload();
                                } else {
                                    if (data.msg == "VE") {
                                        swal("Error", "Validation error.Please check the form correctly!", 'error');
                                        $.each(data.errors, function (index, value) {
                                            $('#' + index).after('<p class="text-danger form_errors">' + value + '</p>');
                                        });
                                    } else {
                                        swal("Error", data.msg, 'error');

                                    }
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                            },
                            complete: function (data) {
                                $('.page-loader-wrapper').fadeOut();
                            }
                        });
                    }
                });
                @endforeach
            @endif
    {{------------------------------------Gap Period Share Calculations---------------------------------------------------}}
                $('#amount_gap').on('change', function (e) {
                    e.preventDefault();

                    var amt = $('#amount_gap').val();
                    amt = OSREC.CurrencyFormatter.parse(amt, {locale: 'en_IN'});

                    $('#g_zp_share').text(indianRupeeFormatterText(20 / 100 * amt));
                    $('#g_ap_share').text(indianRupeeFormatterText(40 / 100 * amt));
                    $('#g_gp_share').text(indianRupeeFormatterText(40 / 100 * amt));
                });
                {{------------------------------------XXXXXXXXXXXXXXXXXXXXXXXXXXXXX---------------------------------------------------}}
                //      Bar chart
                new Chart(document.getElementById("bar-chart"), {
                    type: 'bar',
                    data: {
                        labels: ["Gap Period Collection", "Forfeited Earnest Money", "Forfeited Security Money", "Bidding Collection", "Total Collection"],
                        datasets: [
                            {
                                label: "₹ (In Rupees)",
                                backgroundColor: ["#3e95cd", "#c45850", "#c45850", "#3cba9f", "#e8c3b9"],
                                data: [{{$gap_period_amt}}, {{$total_forfeited_amount}}, {{$total_forfeited_amount}},{{$tot_rec_amt}},{{$total_amount_graph}}]
                            }
                        ]
                    },
                    options: {
                        legend: {display: false},
                        title: {
                            display: true,
                            text: '{{$assetData->asset_name}} ₹ (In Rupees)'
                        }
                    }
                });
                //        --------------Form Selling Calculation------------------
                $(function () {
                    $('#quantity, #rate').keyup(function () {
                        var quantity = parseFloat($('#quantity').val()) || 0;
                        var rate = parseFloat($('#rate').val()) || 0;
                        $('#result').val(quantity * rate);
                    });
                });
                //----------------------------------------------Form selling------------------------------------------------------------
                $("#formSelling").validate({
                    rules: {
                        form_quantity: {
                            required: true
                        },
                        cost_per_form: {
                            required: true
                        }
                    }
                });

                $('#formSelling').on('submit', function (e) {
                    e.preventDefault();
                    if ($('#formSelling').valid()) {
                        $('.page-loader-wrapper').fadeIn();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '{{route('osr.non_tax.dw_asset.formSelling')}}',
                            dataType: "json",
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                if (data.msgType == true) {
                                    swal("Success", data.msg, "success")
                                        .then((value) => {
                                        $('#formAdd').modal('hide');
                                    location.reload();
                                });
                                } else {
                                    if (data.msg == "VE") {
                                        swal("Error", "Validation error.Please check the form correctly!", 'error');
                                        $.each(data.errors, function (index, value) {
                                            $('#' + index).after('<p class="text-danger form_errors">' + value + '</p>');
                                        });
                                    } else {
                                        swal("Error", data.msg, 'error');
                                    }
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                            },
                            complete: function (data) {
                                $('.page-loader-wrapper').fadeOut();
                            }
                        });
                    }

                });
                //----------------------------------------------Form selling Edit-------------------------------------------------------
                $("#formSellingEdit").validate({
                    rules: {
                        form_quantity: {
                            required: true
                        },
                        cost_per_form: {
                            required: true
                        }
                    }
                });

                $('#formSellingEdit').on('submit', function (e) {
                    e.preventDefault();
                    if ($('#formSellingEdit').valid()) {
                        $('.page-loader-wrapper').fadeIn();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '{{route('osr.non_tax.dw_asset.formSellingEdit')}}',
                            dataType: "json",
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                if (data.msgType == true) {
                                    swal("Success", data.msg, "success")
                                        .then((value) => {
                                        $('#formEdit').modal('hide');
                                    location.reload();
                                })
                                    ;
                                } else {
                                    if (data.msg == "VE") {
                                        swal("Error", "Validation error.Please check the form correctly!", 'error');
                                        $.each(data.errors, function (index, value) {
                                            $('#' + index).after('<p class="text-danger form_errors">' + value + '</p>');
                                        });
                                    } else {
                                        swal("Error", data.msg, 'error');
                                    }
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                            },
                            complete: function (data) {
                                $('.page-loader-wrapper').fadeOut();
                            }
                        });
                    }

                });


                //----------------------------------------------Gap Period--------------------------------------------------------------
                $("#gap_period").validate({
                    rules: {
                        from_date: {
                            required: true
                        },
                        to_date: {
                            required: true
                        },
                        collected_amount: {
                            required: true
                        }
                    }
                });

                $('#gap_period').on('submit', function (e) {
                    e.preventDefault();
                    if ($('#gap_period').valid()) {
                        $('.page-loader-wrapper').fadeIn();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '{{route('osr.non_tax.dw_asset.gapPeriod')}}',
                            dataType: "json",
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                if (data.msgType == true) {
                                    swal("Success", data.msg, "success")
                                        .then((value) => {
                                        $('#gapAdd').modal('hide');
                                    location.reload();
                                })
                                    ;
                                } else {
                                    if (data.msg == "VE") {
                                        swal("Error", "Validation error.Please check the form correctly!", 'error');
                                        $.each(data.errors, function (index, value) {
                                            $('#' + index).after('<p class="text-danger form_errors">' + value + '</p>');
                                        });
                                    } else {
                                        swal("Error", data.msg, 'error');
                                    }
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                            },
                            complete: function (data) {
                                $('.page-loader-wrapper').fadeOut();
                            }
                        });
                    }

                });
                //----------------------------------------------Gap Period Edit---------------------------------------------------------
                $("#gap_period_edit").validate({
                    rules: {
                        from_date: {
                            required: true
                        },
                        to_date: {
                            required: true
                        },
                        collected_amount: {
                            required: true
                        }
                    }
                });

                $('#gap_period_edit').on('submit', function (e) {
                    e.preventDefault();
                    if ($('#gap_period_edit').valid()) {
                        $('.page-loader-wrapper').fadeIn();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '{{route('osr.non_tax.dw_asset.gapPeriodEdit')}}',
                            dataType: "json",
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                if (data.msgType == true) {
                                    swal("Success", data.msg, "success")
                                        .then((value) => {
                                        $('#gapEdit').modal('hide');
                                    location.reload();
                                })
                                    ;
                                } else {
                                    if (data.msg == "VE") {
                                        swal("Error", "Validation error.Please check the form correctly!", 'error');
                                        $.each(data.errors, function (index, value) {
                                            $('#' + index).after('<p class="text-danger form_errors">' + value + '</p>');
                                        });
                                    } else {
                                        swal("Error", data.msg, 'error');
                                    }
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                            },
                            complete: function (data) {
                                $('.page-loader-wrapper').fadeOut();
                            }
                        });
                    }

                });
                //----------------------------------------------Baki Jari Entry Form---------------------------------------------------
                $("#bakiJariForm").validate({
                    rules: {
                        case_no: {
                            required: true
                        },
                        case_date: {
                            required: true
                        },
                        case_remarks: {
                            required: true
                        }
                    }
                });

                $('#bakiJariForm').on('submit', function (e) {
                    e.preventDefault();
                    if ($('#bakiJariForm').valid()) {
                        $('.page-loader-wrapper').fadeIn();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '{{route('osr.non_tax.dw_asset.bakiJari')}}',
                            dataType: "json",
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                if (data.msgType == true) {
                                    swal("Success", data.msg, "success")
                                        .then((value) => {
                                        $('#bakiJariAdd').modal('hide');
                                    location.reload();
                                });
                                } else {
                                    if (data.msg == "VE") {
                                        swal("Error", "Validation error.Please check the form correctly!", 'error');
                                        $.each(data.errors, function (index, value) {
                                            $('#' + index).after('<p class="text-danger form_errors">' + value + '</p>');
                                        });
                                    } else {
                                        swal("Error", data.msg, 'error');
                                    }
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                            },
                            complete: function (data) {
                                $('.page-loader-wrapper').fadeOut();
                            }
                        });
                    }

                });
                //----------------------------------------------Edit Baki Jari Entry Form-----------------------------------------------
                $("#bakiJariEditForm").validate({
                    rules: {
                        case_no: {
                            required: true
                        },
                        case_date: {
                            required: true
                        },
                        case_remarks: {
                            required: true
                        },
                        closed_date: {
                            required: true
                        },
                        closed_remarks: {
                            required: true
                        }
                    }
                });

                $('#bakiJariEditForm').on('submit', function (e) {
                    e.preventDefault();
                    if ($('#bakiJariEditForm').valid()) {
                        $('.page-loader-wrapper').fadeIn();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '{{route('osr.non_tax.dw_asset.bakiJariEdit')}}',
                            dataType: "json",
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                if (data.msgType == true) {
                                    swal("Success", data.msg, "success")
                                        .then((value) => {
                                        $('#bakiJariEdit').modal('hide');
                                    location.reload();
                                });
                                } else {
                                    if (data.msg == "VE") {
                                        swal("Error", "Validation error.Please check the form correctly!", 'error');
                                        $.each(data.errors, function (index, value) {
                                            $('#' + index).after('<p class="text-danger form_errors">' + value + '</p>');
                                        });
                                    } else {
                                        swal("Error", data.msg, 'error');
                                    }
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                            },
                            complete: function (data) {
                                $('.page-loader-wrapper').fadeOut();
                            }
                        });
                    }

                });

                $('#fy_year_id').on('change', function (e) {
                    e.preventDefault();
                    var asset_id = window.btoa(window.btoa(window.btoa({{$assetData->id}})));
                    var osr_fy_id = window.btoa(window.btoa(window.btoa($(this).val())));
                    location.href = "{{url('osr/non_tax/dw_asset/track/fy')}}" + "/" + asset_id + "/" + osr_fy_id;
                });


                //----------------------------------------------Edit Baki Jari Entry Form-----------------------------------------------
                $("#forfeited_earnest_money_save").validate({
                    rules: {
                        zp_share: {
                            required: true
                        },
                        ap_share: {
                            required: true
                        },
                        gp_share: {
                            required: true
                        },
                        transaction_no: {
                            required: true
                        },
                        transaction_date: {
                            required: true
                        }
                    }
                });

                $('#forfeited_earnest_money_save').on('submit', function (e) {
                    e.preventDefault();
                    if ($('#forfeited_earnest_money_save').valid()) {
                        $('.page-loader-wrapper').fadeIn();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '{{route('osr.non_tax.asset.payment.forfeited_earnest_money_save')}}',
                            dataType: "json",
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                if (data.msgType == true) {
                                    swal("Success", data.msg, "success")
                                        .then((value)=>{
                                        location.reload();
                                });
                                } else {
                                    if (data.msg == "VE") {
                                        swal("Error", "Validation error.Please check the form correctly!", 'error');
                                        $.each(data.errors, function (index, value) {
                                            $('#' + index).after('<p class="text-danger form_errors">' + value + '</p>');
                                        });
                                    } else {
                                        swal("Error", data.msg, 'error');
                                    }
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                            },
                            complete: function (data) {
                                $('.page-loader-wrapper').fadeOut();
                            }
                        });
                    }

                });
            </script>
@endsection