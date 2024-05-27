@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <script src="{{asset('mdas_assets/Chart.js-2.8.0/dist/Chart.min.js')}}"></script>
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.css" rel="stylesheet" type="text/css"/>
    <style>
        body{
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


        .mb40{
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
        .card {
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
        
        .line-blue{
            border-left:4px solid blue;
        }
        
        .line-orange{
            border-left:4px solid orange;
        }

        .label-b{
            font-weight: 700;
        }

        .mt40{
            margin-top: 40px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li><a href="{{url('osr/osr_panel')}}/{{encrypt($data['fy_id'])}}">OSR</a></li>
            <li><a href="{{url('osr/non_tax/dw_other_asset_list')}}{{"/"}}{{base64_encode(base64_encode($catData->id))}}">{{$catData->cat_name}}</a></li>
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
                                <input type="text" class="form-control" value="{{$assetData->other_asset_code}}" readonly="readonly"/>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label style="color: #ddd;">Asset Name:</label>
                                <input type="text" class="form-control" value="{{$assetData->other_asset_name}}" readonly="readonly"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <form action="#" method="POST" id="financialBiddingForm">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <label style="color: #ddd;">Select Financial Year</label>
                                    <select name="osr_fy_id" id="fy_year_id" class="form-control">
                                        @foreach($osrFyYears AS $fyr)
                                            <option @if($osrFyYearData->id==$fyr->id) selected="selected"@endif value="{{$fyr->id}}">{{$fyr->fy_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    SELF COLLECTION
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="pull-right" id="callAmount">
                                <i class="fa fa-plus"></i>
                                Add Amount
                            </button>
                        </div>
                    </div>
                    @php $i=1; @endphp

                    @foreach($colList AS $li)
                    <div class="row mt10">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12 line-orange" style="background-color: #eee">
                                <h6> COLLECTION {{$i}}</h6>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 mt10">
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <p class="label-b">From Date - To Date</p>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <p>: {{\Carbon\Carbon::parse($li->s_from)->format('d M Y')}} to {{\Carbon\Carbon::parse($li->s_to)->format('d M Y')}}</p>
                                </div>

                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <p class="label-b">ZP Share</p>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <p>: <span class="money_txt">{{$li->zp_share}}</span></p>
                                </div>

                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <p class="label-b">Collected Amount</p>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <p>: <span class="money_txt">{{$li->collected_amt}}</span></p>
                                </div>

                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <p class="label-b">AP Share</p>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <p>: <span class="money_txt">{{$li->ap_share}}</span></p>
                                </div>

                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <p class="label-b">Receipt No.</p>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <p>: {{$li->receipt_no}}</p>
                                </div>

                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <p class="label-b">GP Share</p>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <p>: <span class="money_txt">{{$li->gp_share}}</span></p>
                                </div>

                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <p class="label-b">Amount Received From</p>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <p>: {{$li->amt_received_from}}</p>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <p class="label-b">Amount Received On</p>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <p>: {{\Carbon\Carbon::parse($li->s_on)->format('d M Y')}}</p>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <p class="label-b">Sharing Remarks</p>
                                    <p>
                                        {{$li->sharing_remark}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php $i++; @endphp
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 line-orange" style="background-color: #eee">
                <h6>Note:- Kindly add agreement by clicking the agreement button below then only you can add agreement wise instalments.</h6>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 text-center mt10">
                <button type="button" class="btn btn-primary" id="callAgreement">
                    <i class="fa fa-plus"></i>
                    Agreement
                </button>
            </div>
        </div>
        <hr/>

        @php $ag_i=1; @endphp
        @foreach($finalAg AS $li_ag)
            <div class="row">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                       AGREEMENT WISE COLLECTION
                    </div>
                    <div class="panel-body">
                        <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-md-12 col-sm-12 col-xs-12 bg-info line-blue">
                                        <h5>AGREEMENT {{$ag_i}} [ @if($li_ag['agList']->agreement_with=="I"){{"INDIVIDUAL"}}@else{{"ORGANIZATION"}}@endif ]</h5>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 line-blue" style="padding:10px"></div>

                                    <div class="col-md-2 col-sm-2 col-xs-2 line-blue">
                                        <p class="label-b">Name</p>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <p>: {{$li_ag['agList']->name}}</p>
                                    </div>

                                    <div class="col-md-2 col-sm-2 col-xs-2 line-orange">
                                        <p class="label-b">GST</p>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <p class="money">: {{$li_ag['agList']->gst}}</p>
                                    </div>

                                    <div class="col-md-2 col-sm-2 col-xs-2 line-blue">
                                        <p class="label-b">Mobile No.</p>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <p>: {{$li_ag['agList']->mobile_no}}</p>
                                    </div>

                                    <div class="col-md-2 col-sm-2 col-xs-2 line-orange">
                                        <p class="label-b">Aggrement Amt</p>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <p>: <span class="money_txt">{{$li_ag['agList']->agreement_amt}}</span></p>
                                    </div>

                                    <div class="col-md-2 col-sm-2 col-xs-2 line-blue">
                                        <p class="label-b">Email ID</p>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <p>: {{$li_ag['agList']->email_id}}</p>
                                    </div>

                                    <div class="col-md-2 col-sm-2 col-xs-2 line-orange">
                                        <p class="label-b">Aggrement Period</p>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <p>: {{\Carbon\Carbon::parse($li_ag['agList']->agreement_from)->format('d M Y')}} to {{\Carbon\Carbon::parse($li_ag['agList']->agreement_to)->format('d M Y')}}</p>
                                    </div>

                                    <div class="col-md-2 col-sm-2 col-xs-2 line-blue">
                                        <p class="label-b">PAN</p>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <p>: {{$li_ag['agList']->pan_no}}</p>
                                    </div>

                                    <div class="col-md-2 col-sm-2 col-xs-2 line-orange">
                                        <p class="label-b">Aggrement Letter</p>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <p>:
                                            @if($li_ag['agList']->agreement_path)
                                                <a href="{{$imgUrl.$li_ag['agList']->agreement_path}}" target="_blank">
                                                    View Letter
                                                </a>
                                            @else
                                                Not Uploaded
                                            @endif
                                        </p>
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12" style="border-left:4px solid blue;">
                                        <p class="label-b">Remarks</p>
                                        <p>
                                            {{$li_ag['agList']->remarks}}
                                        </p>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12" style="border-left:4px solid blue;">
                                        <button type="button" class="callAgreementInstalment" data-id="{{$li_ag['agList']->id}}" data-n="{{$li_ag['agList']->name}}">
                                            <i class="fa fa-plus"></i>
                                            Add Instalment
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @php $ag_j=1; @endphp
                        @foreach($li_ag['insList'] AS $li_ins)
                            <div class="row mt40">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-md-12 col-sm-12 col-xs-12 line-orange" style="background-color: #eee">
                                        <h6>AGREEMENT {{$ag_i}} -> INSTALMENT {{$ag_j}}</h6>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 mt10">
                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <p class="label-b">From Date - To Date</p>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <p>: {{\Carbon\Carbon::parse($li_ins->instalment_from)->format('d M Y')}} to {{\Carbon\Carbon::parse($li_ins->instalment_to)->format('d M Y')}}</p>
                                        </div>

                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <p class="label-b">ZP Share</p>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <p>: <span class="money_txt">{{$li_ins->zp_share}}</span></p>
                                        </div>

                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <p class="label-b">Collected Amount</p>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <p>: <span class="money_txt">{{$li_ins->instalment_paid}}</span></p>
                                        </div>

                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <p class="label-b">AP Share</p>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <p>: <span class="money_txt">{{$li_ins->ap_share}}</span></p>
                                        </div>

                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <p class="label-b">Receipt No.</p>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <p>: {{$li_ins->receipt_no}}</p>
                                        </div>

                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <p class="label-b">GP Share</p>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <p>: <span class="money_txt">{{$li_ins->gp_share}}</span></p>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <p class="label-b">Sharing Remarks</p>
                                            <p>
                                                {{$li_ins->sharing_remark}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php $ag_j++; @endphp
                        @endforeach
                        <hr/>
                    </div>
                </div>
            </div>
            @php $ag_i++; @endphp
        @endforeach
    </div>



    <!-------------------------- MODAL AGREEMENT ------------------------------>

    <div class="modal fade" id="add-agreement">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header  bg-primary">
                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    <h4 class="modal-title">ADD AGREEMENT</h4>
                </div>
                <!-- Modal body -->
                <form action="#" method="POST" id="addAgreementForm">
                    <div class="modal-body">
                        <input type="hidden" name="other_asset_code" value="{{encrypt($assetData->other_asset_code)}}"/>
                        <input type="hidden" name="osr_fy_year_id" value="{{encrypt($osrFyYearData->id)}}"/>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Agreement With <strong>*</strong></label>

                                    <label class="radio-inline">
                                        <input type="radio" name="ag_with" value="I" checked>
                                        Individual
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="ag_with" id="ag_with" value="O">
                                        Organization
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Name <strong>*</strong></label>
                                    <input type="text" class="form-control" name="ag_name" id="ag_name"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Mobile Number <strong>*</strong></label>
                                    <input type="number" class="form-control" name="ag_mobile_no" id="ag_mobile_no"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Email ID</label>
                                    <input type="text" class="form-control" name="ag_email_id" id="ag_email_id"/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12" id="ag_pan_no_div">
                                <div class="form-group">
                                    <label>PAN <strong>*</strong></label>
                                    <input type="text" class="form-control" name="ag_pan_no" id="ag_pan_no"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12" style="display: none" id="ag_gst_div">
                                <div class="form-group">
                                    <label>GST Number <strong>*</strong></label>
                                    <input type="text" class="form-control" name="ag_gst" id="ag_gst"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Aggrement Letter <strong>*</strong></label>
                                    <input type="file" class="form-control" name="ag_letter" id="ag_letter"/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Aggrement Amount <strong>*</strong></label>
                                    <input type="text" class="form-control" name="ag_amt" id="ag_amt"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Aggrement From <strong>*</strong></label>
                                    <input type="text" class="form-control" name="ag_from" id="ag_from"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Aggrement To <strong>*</strong></label>
                                    <input type="text" class="form-control" name="ag_to" id="ag_to"/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Remarks if any</label>
                                    <textarea class="form-control" name="ag_remarks" id="ag_remarks"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-send"></i>
                            Save Aggrement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-------------------------- MODAL AGREEMENT INSTALMENT ------------------------------>

    <div class="modal fade" id="add-agreement-instalment">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header  bg-primary">
                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    <h4 class="modal-title">ADD AGREEMENT INSTALMENT</h4>
                </div>
                <!-- Modal body -->
                <form action="#" method="POST" id="addAgreementInstalmentForm">
                    <div class="modal-body">
                        <input type="hidden" name="ag_id" id="ag_id"/>
                        <input type="hidden" name="other_asset_code" value="{{encrypt($assetData->other_asset_code)}}"/>
                        <input type="hidden" name="osr_fy_year_id" value="{{encrypt($osrFyYearData->id)}}"/>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-12 col-sm-12 col-xs-12 line-orange">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <p class="label-b">Name</p>
                                        </div>
                                        <div class="col-md-8 col-sm-8 col-xs-8">
                                            <p id="ag_name_dis"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt10">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>From <strong>*</strong></label>
                                    <input type="text" class="form-control" name="ag_ins_from" id="ag_ins_from"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>To <strong>*</strong></label>
                                    <input type="text" class="form-control" name="ag_ins_to" id="ag_ins_to"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Instalment Paid <strong>*</strong></label>
                                    <input type="text" class="form-control" name="ag_ins_paid" id="ag_ins_paid"/>
                                </div>
                            </div>
                        </div>

                        <!--<div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <p><span style="font-size: 9px">[20% Estimated amount]</span></p>
                                <div class="form-group">
                                    <label>ZP Share <strong>*</strong> </label>
                                    <input type="text" class="form-control" name="ag_ins_zp_share" id="ag_ins_zp_share"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <p><span style="font-size: 9px">[40% Estimated amount]</span></p>
                                <div class="form-group">
                                    <label>AP Share <strong>*</strong></label>
                                    <input type="text" class="form-control" name="ag_ins_ap_share" id="ag_ins_ap_share"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <p><span style="font-size: 9px">[40% Estimated amount]</span></p>
                                <div class="form-group">
                                    <label>GP Share <strong>*</strong></label>
                                    <input type="text" class="form-control" name="ag_ins_gp_share" id="ag_ins_gp_share"/>
                                </div>
                            </div>
                        </div>-->

                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Receipt No. <strong>*</strong></label>
                                    <input type="text" class="form-control" name="ag_ins_receipt_no" id="ag_ins_receipt_no"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Receipt On Date <strong>*</strong></label>
                                    <input type="text" class="form-control" name="ag_ins_on" id="ag_ins_on"/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Sharing remarks if any</label>
                                    <textarea class="form-control" name="ag_ins_remarks" id="ag_ins_remarks"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-send"></i>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-------------------------- MODAL SELF INSTALMENT ------------------------------>

    <div class="modal fade" id="add-self-instalment">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header  bg-primary">
                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    <h4 class="modal-title">ADD AMOUNT</h4>
                </div>
                <!-- Modal body -->
                <form action="#" method="POST" id="addAmountForm">
                    <div class="modal-body">
                        <input type="hidden" name="other_asset_code" value="{{encrypt($assetData->other_asset_code)}}"/>
                        <input type="hidden" name="osr_fy_year_id" value="{{encrypt($osrFyYearData->id)}}"/>

                        <div class="row mt10">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>From <strong>*</strong></label>
                                    <input type="text" class="form-control" name="s_from" id="s_from"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>To <strong>*</strong></label>
                                    <input type="text" class="form-control" name="s_to" id="s_to"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Collected Amount <strong>*</strong></label>
                                    <input type="text" class="form-control" name="s_amt" id="s_amt"/>
                                </div>
                            </div>
                        </div>

                        <!--<div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <p><span style="font-size: 9px">[20% Estimated amount]</span></p>
                                <div class="form-group">
                                    <label>ZP Share <strong>*</strong> </label>
                                    <input type="text" class="form-control" name="s_zp_share" id="s_zp_share"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <p><span style="font-size: 9px">[40% Estimated amount]</span></p>
                                <div class="form-group">
                                    <label>AP Share <strong>*</strong></label>
                                    <input type="text" class="form-control" name="s_ap_share" id="s_ap_share"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <p><span style="font-size: 9px">[40% Estimated amount]</span></p>
                                <div class="form-group">
                                    <label>GP Share <strong>*</strong></label>
                                    <input type="text" class="form-control" name="s_gp_share" id="s_gp_share"/>
                                </div>
                            </div>
                        </div>-->

                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Receipt No. <strong>*</strong></label>
                                    <input type="text" class="form-control" name="s_receipt_no" id="s_receipt_no"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Receipt On Date <strong>*</strong></label>
                                    <input type="text" class="form-control" name="s_on" id="s_on"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Amount received from <strong>*</strong></label>
                                    <input type="text" class="form-control" name="s_r_from" id="s_r_from"/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Sharing remarks if any</label>
                                    <textarea class="form-control" name="s_remarks" id="s_remarks"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-send"></i>
                            Submit
                        </button>
                    </div>
                </form>
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
                    $('#ag_from').Zebra_DatePicker({
                        format: 'Y-m-d'
                    });
                    $('#ag_to').Zebra_DatePicker({
                        format: 'Y-m-d'
                    });

                    $('#ag_ins_from').Zebra_DatePicker({
                        format: 'Y-m-d'
                    });
                    $('#ag_ins_to').Zebra_DatePicker({
                        format: 'Y-m-d'
                    });
                    $('#ag_ins_on').Zebra_DatePicker({
                        format: 'Y-m-d'
                    });

                    $('#s_from').Zebra_DatePicker({
                        format: 'Y-m-d'
                    });
                    $('#s_to').Zebra_DatePicker({
                        format: 'Y-m-d'
                    });
                    $('#s_on').Zebra_DatePicker({
                        format: 'Y-m-d'
                    });


                    var indianRupeeFormatter = OSREC.CurrencyFormatter.getFormatter({
                        currency: 'INR',
                        symbol: ''
                    });

                    var indianRupeeFormatterText = OSREC.CurrencyFormatter.getFormatter({
                        currency: 'INR',
                        symbol: '₹'
                    });

                    $('.money').on('blur', function (e){
                        e.preventDefault();
                        var value= OSREC.CurrencyFormatter.parse($(this).val(), { locale: 'en_IN' });
                        var formattedVal = indianRupeeFormatter(value);
                        $(this).val(formattedVal);
                    });

                    OSREC.CurrencyFormatter.formatAll({
                        selector: '.money_txt',
                        currency: 'INR'
                    });

                    //------------------------------- AGREEMNET --------------------------------------------------------

                    $('#callAgreement').on('click', function(e){
                        e.preventDefault();
                        $('#add-agreement').modal('show');
                    });

                    $("input[name='agreement_with']").on('change', function(e){
                        e.preventDefault();
                        $('#ag_gst').val('');
                        $('#ag_pan_no').val('');

                        if ($("input[name='agreement_with']:checked").val()=="I") {
                            $('#ag_pan_no_div').show();
                            $('#ag_gst_div').hide();
                        }else{
                            $('#ag_pan_no_div').hide();
                            $('#ag_gst_div').show();
                        }
                    });

                    $("#addAgreementForm").validate({

                        rules: {
                            ag_with: {
                                required: true,
                            },
                            ag_name:{
                                required: true,
                                fullname: true,
                                blank:true,
                                maxlength:150
                            },
                            ag_mobile_no:{
                                required: true,
                                blank:true,
                                maxlength:10
                            },
                            ag_email_id:{
                                required: true,
                                blank:true,
                                maxlength:150
                            },
                            ag_pan_no:{
                                required: true,
                                alphanumeric:true,
                                maxlength:10
                            },
                            ag_gst:{
                                required: true,
                                alphanumeric:true,
                                maxlength:20
                            },
                            ag_letter:{
                                required: true,
                            },
                            ag_amt:{
                                required: true
                            },
                            ag_from:{
                                required: true
                            },
                            ag_to:{
                                required: true
                            },
                            ag_remarks:{
                                blank:true,
                                maxlength:150
                            }
                        },


                    });

                    $('#addAgreementForm').on('submit', function(e){
                        e.preventDefault();

                        if($("#addAgreementForm").valid()){
                            $('.page-loader-wrapper').fadeIn();

                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: "POST",
                                url: '{{route('osr.non_tax.dw_other_asset.track.save_agreement')}}',
                                dataType: "json",
                                data: new FormData(this),
                                contentType: false,
                                cache: false,
                                processData: false,
                                success: function (data) {
                                    if (data.msgType == true) {
                                        swal("Success", data.msg, "success")
                                            .then((value) => {
                                            $('#addAgreementForm').modal('hide');
                                        location.reload();
                                    });

                                    }else{
                                        if(data.msg=="VE"){
                                            swal("Error", "Validation error.Please check the form correctly!", 'error');
                                            $.each(data.errors, function( index, value ) {
                                                $('#'+index).after('<p class="text-danger form_errors">'+value+'</p>');
                                            });
                                        }else{
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


                    //-------------------------------------- INSTALMENTS -----------------------------------------------

                    $('.callAgreementInstalment').on('click', function(e){
                        e.preventDefault();

                        $('#ag_id').val('');
                        $('#ag_name_dis').text('');

                        $('#ag_id').val($(this).data('id'));
                        $('#ag_name_dis').text($(this).data('n'));

                        $('#add-agreement-instalment').modal('show');
                    });


                    $("#addAgreementForm").validate({

                        rules: {

                        },
                    });



                    $('#addAgreementInstalmentForm').on('submit', function(e){
                        e.preventDefault();

                        if($("#addAgreementInstalmentForm").valid()){
                            $('.page-loader-wrapper').fadeIn();

                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: "POST",
                                url: '{{route('osr.non_tax.dw_other_asset.track.save_agreement_instalment')}}',
                                dataType: "json",
                                data: new FormData(this),
                                contentType: false,
                                cache: false,
                                processData: false,
                                success: function (data) {
                                    if (data.msgType == true) {
                                        swal("Success", data.msg, "success")
                                            .then((value) => {
                                            $('#addAgreementForm').modal('hide');
                                        location.reload();
                                    });

                                    }else{
                                        if(data.msg=="VE"){
                                            swal("Error", "Validation error.Please check the form correctly!", 'error');
                                            $.each(data.errors, function( index, value ) {
                                                $('#'+index).after('<p class="text-danger form_errors">'+value+'</p>');
                                            });
                                        }else{
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

                    //------------------------------- SELF AMOUNT ------------------------------------------------------

                    $('#callAmount').on('click', function(e){
                        e.preventDefault();
                        $('#add-self-instalment').modal('show');
                    });

                    $("#addAmountForm").validate({

                        rules: {

                        },
                    });



                    $('#addAmountForm').on('submit', function(e){
                        e.preventDefault();

                        if($("#addAmountForm").valid()){
                            $('.page-loader-wrapper').fadeIn();

                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: "POST",
                                url: '{{route('osr.non_tax.dw_other_asset.track.save_amount')}}',
                                dataType: "json",
                                data: new FormData(this),
                                contentType: false,
                                cache: false,
                                processData: false,
                                success: function (data) {
                                    if (data.msgType == true) {
                                        swal("Success", data.msg, "success")
                                            .then((value) => {
                                            $('#addAgreementForm').modal('hide');
                                        location.reload();
                                    });

                                    }else{
                                        if(data.msg=="VE"){
                                            swal("Error", "Validation error.Please check the form correctly!", 'error');
                                            $.each(data.errors, function( index, value ) {
                                                $('#'+index).after('<p class="text-danger form_errors">'+value+'</p>');
                                            });
                                        }else{
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