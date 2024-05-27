@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{asset('mdas_assets/css/animate.css')}}" rel="stylesheet" type="text/css"/>

    <style>

        .card span {
            font-weight: bolder;
        }

        strong {
            color: red;
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

        /*******************************************************Progress Bar***********************************************/

        .multi-steps > li.is-active:before, .multi-steps > li.is-active ~ li:before {
            content: counter(stepNum);
            font-family: inherit;
            font-weight: 700;
        }
        .multi-steps > li.is-active:after, .multi-steps > li.is-active ~ li:after {
            background-color: #ededed;
        }

        .multi-steps {
            display: table;
            table-layout: fixed;
            width: 100%;
        }
        .multi-steps > li {
            counter-increment: stepNum;
            text-align: center;
            display: table-cell;
            position: relative;
            color: #000006f5;
            font-weight: 500;
            font-size: 14px;
        }
        .multi-steps > li:before {
            content: '\f00c';
            content: '\2713;';
            content: '\10003';
            content: '\10004';
            content: '\2713';
            display: block;
            margin: 0 auto 4px;
            background-color: #fff;
            width: 36px;
            height: 36px;
            line-height: 32px;
            text-align: center;
            font-weight: bold;
            border-width: 2px;
            border-style: solid;
            border-color: #566ed0;;
            border-radius: 50%;
        }
        .multi-steps > li:after {
            content: '';
            height: 2px;
            width: 100%;
            background-color: #4782ff;
            position: absolute;
            top: 16px;
            left: 50%;
            z-index: -1;
        }
        .multi-steps > li:last-child:after {
            display: none;
        }
        .multi-steps > li.is-active:before {
            background-color: #fff;
            border-color: tomato;
        }
        .multi-steps > li.is-active ~ li {
            color: #808080;
        }
        .multi-steps > li.is-active ~ li:before {
            background-color: #ededed;
            border-color: #ededed;
        }

        .table-one{
            border-left:2px solid deepskyblue;
        }
        .table-accept{
            border-left:2px solid darkgreen;
            border-top:2px solid deepskyblue;
        }
        .table-reject{
            border-left:2px solid orangered;
            border-top:2px solid deepskyblue;
        }

        .img-td{
            width:60px;
            background-color: #fff;
        }
        .white-td{
            background-color: #fff;
        }

        .radio-inline{
            color:#eee;
            font-weight: 500;
            font-size: 14px;
        }

        .badge-red{
            background-color: orangered;
        }
        .badge-green{
            background-color: darkgreen;
        }

        .p-sm{
            font-size: 11px;
        }

     
        /*******************************************************Progress Bar Ends******************************************/


    </style>

@endsection
@section('content')

    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Home</a></li>
            <li><a href="{{route('osr.non_tax.asset_shortlist_bidding_update_payment')}}">Asset Tender and Payment</a></li>
            <li class="active">Bidding</li>
        </ol>
    </div>

    <div class="container mb40">
        <div class="row">
            <div class="col-md-3 col-sm-4 col-xs-12" style="background-color: #fff;border:1px solid #10436d;padding:8px 15px;">
                <label id="asset_code"> Asset Code: </label>
                <span style="color:#ee0a54;font-weight: bold;font-size: 14px;text-transform: uppercase">{{$assetData->asset_code}}</span>
            </div>
            <div class="col-md-6 col-sm-4 col-xs-12" style="background-color: #fff;border:1px solid #10436d;padding:8px 15px;">
                <label id="asset_name"> Asset Name: </label>
                <span style="color:#ee0a54;font-weight: bold;font-size: 14px;text-transform: uppercase"> {{$assetData->asset_name}}</span>
            </div>
            <div class="col-md-3 col-sm-4 col-xs-12" style="background-color: #fff;border:1px solid #10436d;padding:8px 15px;">
                <label id="fy_yr"> Financial Year: </label>
                <span style="color:#ee0a54;font-weight: bold;font-size: 14px;text-transform: uppercase">{{$osrFyYear->fy_name}}</span>
            </div>
        </div>
        <hr/>
        <!---------------------------------==============Progress bar============-------------------------------------->
        <ul class="list-unstyled multi-steps">
            <li class="@if($generalDetail)@if($generalDetail->stage==0){{"is-active"}}@endif @else{{"is-active"}}@endif">General Details</li>
            <li class="@if($generalDetail)@if($generalDetail->stage==1){{"is-active"}}@endif @endif">Bidder Information</li>
            <li class="@if($generalDetail)@if($generalDetail->stage==2){{"is-active"}}@endif @endif">Settlement Details</li>
        </ul>
        <!-----------------------------==============Progress bar end============-------------------------------------->
        <hr/>

        <!-----------------------------==============General Details============--------------------------------------->
        <div class="panel panel-primary general_panel" style="@if($generalDetail)@if($generalDetail->stage==0){{"display:block"}}@else{{"display:none"}}@endif @endif">
            <div class="panel-heading">A. GENERAL DETAILS</div>
            <form action="" method="POST" id="generalEntry" autocomplete="off">
                <div class="panel-body">
                    <div class="row">
                        <input type="hidden" class="form-control" name="asset_code" value="{{$assetData->asset_code}}"/>
                        <input type="hidden" class="form-control" name="fy_id" value="{{$osrFyYear->id}}"/>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Government Value <strong>*</strong></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-inr" aria-hidden="true"></i>
                                            </div>
                                            <input type="text" class="form-control money" name="govt_value" id="govt_value" value="@if(isset($generalDetail->govt_value)){{$generalDetail->govt_value}}@endif"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Date Of Tender <strong>*</strong></label>
                                        <input type="text" class="form-control" name="date_of_tender" id="date_of_tender"/>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group" id="general_panel_adv">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <label>Advertisement (pdf/max-size 1Mb)<strong>*</strong></label>
                                            <input type="file" class="form-control" name="advertisement" id="advertisement"/>
                                            <button type="button" class="btn btn-danger btn-xs" id="general_panel_adv_cancel" style="display: none">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group" id="general_panel_adv_view" style="display:none">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <label>Advertisement <strong>*</strong></label>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <a href="#" type="button" target="_blank" class="btn btn-success btn-xs" id="general_panel_adv_view_link">View Advertisement</a>
                                            <button type="button" class="btn btn-primary btn-xs" id="general_panel_adv_change">
                                                <i class="fa fa-edit"></i>
                                                Change
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button type="submit" class="btn btn-primary pull-right">
                                <i class="fa fa-send"></i>
                                SAVE &amp; NEXT
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-----------------------------==============General Details End============----------------------------------->

        <!-----------------------------==============COMPARATIVE STATEMENTS============-------------------------------->
        <div class="panel panel-primary bidder-panel" style="@if($generalDetail)@if($generalDetail->stage==1){{"display:block"}}@else{{"display:none"}}@endif @else{{"display:none"}} @endif">
            <div class="panel-heading">B. COMPARATIVE STATEMENTS</div>
            <div class="panel-body">
                <div class="row">
                    @if(count($bidderDetail)>0)
                        @php $i=1; @endphp
                        @foreach($bidderDetail AS $bidder)
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <form action="#" method="POST">
                                    <table class="table table-bordered @if($bidder->bidder_status==0){{"table-reject"}}@else{{"table-accept"}}@endif">
                                    <tbody>
                                        
                                        <tr style="background-color: deepskyblue;color:white">
                                            <td colspan="4">BIDDER {{$i}}</td>
                                            
                                            <td>
                                                <input type="hidden" class="bidder_id" name="bidder_id" value="{{$bidder->id}}"/>
                                                <button class="btn btn-info btn-xs editBidder" type="button" data-bid="{{$bidder->id}}" title="Edit Bidder Details">
                                                    <i class="fa fa-edit"></i>
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-info btn-xs addBidderDocuments" data-toggle="modal" data-bid="{{$bidder->id}}" title=" Upload Bidder Attachment">
                                                    <i class="fa fa-upload" aria-hidden="true"></i>
                                                    Upload Bidder Documents
                                                </button>
                                               
                                            </td>
                                        </tr>
                                        <tr class="bg-info">
                                            <td rowspan="2" class="img-td">
                                                @if($bidder->b_pic_path)
                                                    <img src="{{$imgUrl.$bidder->b_pic_path}}" style="border:1px solid #ddd;width:60px;max-height:75px;" />
                                                @else
                                                    <img src="{{asset('mdas_assets/images/user_add.png')}}" style="border:1px solid #ddd;width:60px;max-height:75px;" />
                                                @endif
                                            </td>
                                            <th style="width:25%">Name</th>
                                            <th>Father's/Husband's Name</th>
                                            <th style="width:25%">Mobile Number</th>
                                            <th style="width:25%">Email ID</th>
                                        </tr>
                                        <tr>
                                            <th>{{$bidder->b_f_name." ".$bidder->b_m_name." ".$bidder->b_l_name}} ({{$bidder->gender_name}})({{$bidder->caste_name}})</th>
                                            <td>{{$bidder->b_father_name}}</td>
                                            <td>{{$bidder->b_mobile}}@if($bidder->b_alt_mobile), {{$bidder->b_alt_mobile}} (Alt.No.)@endif</td>
                                            <td>{{$bidder->b_email}}</td>
                                        </tr>
                                        <tr class="bg-info">
                                            @if(isset($bidder->b_pan_path))
                                            <td class="img-td">
                                                <a href="{{$imgUrl.$bidder->b_pan_path}}" target="_blank" class="btn btn-primary btn-xs">Pan Card</a>
                                            </td>
                                            @else
                                            <td>
                                                Pan Card not available
                                            </td>
                                            @endif
                                            <th>PAN Number</th>
                                            <th>GST Number</th>
                                            <th colspan="2">Address</th>
                                        </tr>
                                        <tr>
                                            <th rowspan="2">Documents Uploaded</th>
                                            <td>{{$bidder->b_pan_no}}</td>
                                            <td>{{$bidder->b_gst_no}}</td>
                                            <td colspan="2">{{$bidder->b_address}}, PS: {{$bidder->b_police_station}}, PIN: {{$bidder->b_pin}}</td>
                                        </tr>
                                        <tr class="bg-info">
                                            <th>Bidding Amount</th>
                                            <th>Earnest Money Deposit</th>
                                            <th>Bidding Status</th>
                                            <th>Remarks</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">{{count($uploadAttachCount[$bidder->id])}}/{{count($activeBidderDocs)}}</th>
                                            <th class="money_txt">{{$bidder->bidding_amt}}</th>
                                            <th class="">
                                                <span class="money_txt">{{$bidder->ernest_amt}}</span>
                                            </th>
                                            <td>
                                                @if($bidder->bidder_status==0)
                                                    <span class="badge badge-red">Rejected</span>
                                                @elseif($bidder->bidder_status==1)
                                                    <span class="badge badge-green">Accepted</span>
                                                @else
                                                    <span class="badge badge-info">Withdrawn</span>
                                                @endif
                                            </td>
                                            <th>
                                                @if($bidder->bidder_status==0)
                                                    {{$bidder->remark}}
                                                @endif
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                                </form>
                            </div>
                        </div>
                            @php $i++; @endphp
                        @endforeach
                    @else
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                No Bidder is added yet. Kindly click the <strong>ADD BIDDER</strong> button below to add new bidder.
                            </div>
                        </div>
                    @endif
                </div>
                <hr/>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#addMore">
                            <i class="fa fa-plus"></i>
                            ADD BIDDER
                        </button>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <button type="button" class="btn btn-primary" id="bidderPrevious" data-ac="{{$assetData->asset_code}}" data-fy="{{$osrFyYear->id}}">
                            <i class="fa fa-arrow-left"></i>
                            PREVIOUS
                        </button>

                        <button type="submit" class="btn btn-primary pull-right" id="bidderEntry" data-ac="{{$assetData->asset_code}}" data-fy="{{$osrFyYear->id}}">
                            <i class="fa fa-send"></i>
                            SAVE &amp; NEXT
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-----------------------------==============COMPARATIVE STATEMENTS End============---------------------------->

        <!-----------------------------============== TENDER AND SETTLEMENT DETAILS============------------------------>

        <div class="panel panel-primary settlement-panel" style="@if($generalDetail)@if($generalDetail->stage==2){{"display:block"}}@else{{"display:none"}}@endif @else{{"display:none"}}@endif">
            <div class="panel-heading">C. SETTLEMENT DETAILS</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 col-sm-8 col-xs-12">
                        <div class="col-md-8 col-sm-8 col-xs-12">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <p>Bidding Summary</p>
                                            <hr/>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group" style="padding-left:10px;border-left:2px solid darkgreen">
                                            <p>Total Bidders:
                                                <span id="f_tbs">
                                                    @if($totalBidder){{$totalBidder}}@endif
                                                </span>
                                            </p>
                                            <p>Selected Bidder:
                                                <span id="f_a_b_n">
                                                    @if($acceptedBidderData){{$acceptedBidderData->b_f_name." ".$acceptedBidderData->b_m_name." ".$acceptedBidderData->b_l_name}}@endif
                                                </span>
                                            </p>
                                            <p>Father's Name:
                                                <span id="f_a_b_fn">
                                                    @if($acceptedBidderData){{$acceptedBidderData->b_father_name}}@endif
                                                </span>
                                            </p>
                                            <p>Bidding Amount:
                                                <span id="f_a_b_ba" class="money_txt">
                                                    @if($acceptedBidderData){{$acceptedBidderData->bidding_amt}}@endif
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group" style="padding-left:10px;border-left:2px solid darkgreen">
                                         <p>Total Withrawn Bidder:
                                                <span id="f_tbs">
                                                    @if($totalWithdrawnBidder){{$totalWithdrawnBidder}}@endif
                                                </span>
                                         </p>
                                        <p>Total Bidder Forfeited:
                                                <span id="f_tbs">
                                                    {{count($forfeitedBidderData)}}
                                                </span>
                                         </p>
                                        <p>Forfeited Withdrawn Amount:
                                                <span id="f_tbs">
                                                    @php
                                                        $totalForfeitedAmt=0;
                                                        foreach($forfeitedBidderData AS $li)
                                                        {
                                                            $totalForfeitedAmt=$totalForfeitedAmt+$li->ernest_amt;
                                                        } 
                                                    @endphp
                                                    
                                                    {{$totalForfeitedAmt}}
                                                </span>
                                         </p>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <form action="#" method="POST" id="settlementForm">
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Settled Amount <strong>*</strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-inr" aria-hidden="true"></i>
                                                    </div>
                                                    <input type="number" class="form-control" name="s_schuled_amount" id="s_schuled_amount" value="@if($acceptedBidderData){{$acceptedBidderData->bidding_amt}}@endif" disabled="disabled"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Security Money Deposit <strong>*</strong></label>
                                                    <input type="text" class="form-control money" name="security_deposit" id="security_deposit" value=""/>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Work Order Number <strong>*</strong></label>
                                                <input type="text" class="form-control" name="work_order_no" id="work_order_no"/>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            <label>File Number <strong>*</strong></label>
                                            <input type="text" class="form-control" name="file_no" id="file_no"/>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            <label>Awarded Date <strong>*</strong></label>
                                            <input type="text" class="form-control" name="awarded_date" id="awarded_date"/>
                                        </div>
                                    </form>
                                </div><!---End of inner row----->
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-12 well">
                        <h5>Attachments</h5>
                        <div class="row">
                            @foreach($activeDocs AS $doc)
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <label>{{$doc->doc_name}} <strong>*</strong></label>
                                <p id="viewAttachment_{{$doc->id}}" style="@if(!isset($uploadedDoc[$doc->id])) display:none @endif">
                                    <a href="@if(isset($uploadedDoc[$doc->id])){{$imgUrl.$uploadedDoc[$doc->id]}}@endif" target="_blank" class="btn btn-success btn-xs" id="attachment_view_link_{{$doc->id}}">
                                        <i class="fa fa-check"></i>
                                        View {{$doc->doc_name}}
                                    </a>
                                    <button type="button" class="btn btn-warning btn-xs edit_attachment" data-doc_id="{{$doc->id}}">
                                        <i class="fa fa-edit"></i>
                                        Edit
                                    </button>
                                </p>

                                <form action="#" method="POST" id="attachmentForm_{{$doc->id}}" style="@if(isset($uploadedDoc[$doc->id])) display:none @endif">
                                    <input type="hidden" name="doc_no" value="{{$doc->id}}"/>
                                    <input type="hidden" name="asset_code" value="{{$assetData->asset_code}}"/>
                                    <input type="hidden" name="fy_id" value="{{$osrFyYear->id}}"/>

                                    <input type="file" class="form-control" name="attachment"/>
                                    <button type="submit" class="btn btn-primary btn-xs" id="upload_attach_{{$doc->id}}" style="margin-top: 4px;">
                                        <i class="fa fa-upload"></i>
                                        Upload
                                    </button>
                                    @if(isset($uploadedDoc[$doc->id]))
                                        <button type="button" class="btn btn-danger btn-xs cancel_attachment" id="cancel_attach_{{$doc->id}}" data-doc_id="{{$doc->id}}" style="margin-top: 4px;">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                            Cancel
                                        </button>
                                    @endif
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <button type="button" class="btn btn-primary" id="finalPrevious">
                            <i class="fa fa-arrow-left"></i>
                            PREVIOUS
                        </button>
                        <button type="button" class="btn btn-primary pull-right" id="finalSubmit">
                            <i class="fa fa-send"></i>
                            FINAL SUBMIT
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!------------------------============== TENDER AND SETTLEMENT DETAILS End============------------------------->

        <div class="panel panel-primary final-panel" style="@if($generalDetail)@if($generalDetail->stage==3){{"display:block"}}@else{{"display:none"}}@endif @else{{"display:none"}}@endif">
            <div class="panel-heading">D. FINAL REPORT</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                   @if(isset($settlementData->final_report_path))
                                    <div class="alert alert-success" role="alert">
                                        <img src="{{asset('mdas_assets/images/checked.gif')}}" style="width:40px" alt="Checked Image"/>
                                        Successfully uploaded the signed report.
                                    </div>
                                   @else
                                    <div class="alert alert-danger" role="alert">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                        Kindly download the Comparative BID Report, sign it and upload.
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group" style="padding-left:10px;border-left:4px solid darkgreen">
                                    <p>BID Summary</p>
                                    <hr/>
                                    <p>Total Bidders :
                                        <span>
                                            @if($totalBidder){{$totalBidder}}@endif
                                        </span>
                                    </p>
                                    <p>Selected Bidder :
                                        <span>
                                            @if($acceptedBidderData){{$acceptedBidderData->b_f_name." ".$acceptedBidderData->b_m_name." ".$acceptedBidderData->b_l_name}}@endif
                                        </span>
                                    </p>
                                    <p>Father's Name :
                                        <span>
                                            @if($acceptedBidderData){{$acceptedBidderData->b_father_name}}@endif
                                        </span>
                                    </p>
                                    <p>Total Bidder Forfeited :
                                        <span id="f_tbs">
                                            {{count($forfeitedBidderData)}}
                                        </span>
                                    </p>
                                    <p>Total Forfeited EMD :
                                        <span class="money_txt">
                                            @php
                                                $totalForfeitedAmt=0;
                                                foreach($forfeitedBidderData AS $li)
                                                {
                                                    $totalForfeitedAmt=$totalForfeitedAmt+$li->ernest_amt;
                                                } 
                                            @endphp
                                            {{$totalForfeitedAmt}}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group" style="padding-left:10px;border-left:4px solid blue">
                                    <p>Settlement Summary</p>
                                    <hr/>
                                    <p>Goverment Value:
                                        <span class="money_txt">
                                             @if(isset($generalDetail->govt_value)){{$generalDetail->govt_value}}@else{{'NA'}}@endif
                                        </span>
                                    </p>
                                    <p>Settled Amount:
                                        <span class="money_txt">
                                             @if($acceptedBidderData){{$acceptedBidderData->bidding_amt}}@endif
                                        </span>
                                    </p>
                                    <p>Work Order Number:
                                        <span>
                                             @if(isset($settlementData->work_order_no)){{$settlementData->work_order_no}}@endif
                                        </span>
                                    </p>
                                    <p>Managed By:
                                        <span>
                                             @if(isset($settlementData->managed_by)){{$settlementData->managed_by}}@endif
                                        </span>
                                    </p>
                                    <p>File Number:
                                        <span>
                                             @if(isset($settlementData->file_no)){{$settlementData->file_no}}@endif
                                        </span>
                                    </p>
                                    <p>Date of Settlement:
                                        <span>
                                             @if(isset($settlementData->awarded_date))
                                            {{Carbon\Carbon::parse($settlementData->awarded_date)->format('d M Y')}}
                                            @endif
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <h5>Attachments</h5>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <p id="viewAttachment_{{$doc->id}}" style="@if(!isset($uploadedDoc[$doc->id])) display:none @endif">
                                    <a href="@if(isset($generalDetail->advertisement)){{$imgUrl.$generalDetail->advertisement}}@else{{'JavaScript:void(0);'}}@endif" target="_blank" class="btn btn-success btn-xs">
                                        <i class="fa fa-file-pdf-o"></i>
                                        View Advertisement
                                    </a>
                                </p>
                            </div>
                            @foreach($activeDocs AS $doc)
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <p id="viewAttachment_{{$doc->id}}" style="@if(!isset($uploadedDoc[$doc->id])) display:none @endif">
                                        <a href="@if(isset($uploadedDoc[$doc->id])){{$imgUrl.$uploadedDoc[$doc->id]}}@else{{'JavaScript:void(0);'}}@endif" target="_blank" class="btn btn-success btn-xs" id="attachment_view_link_{{$doc->id}}">
                                            <i class="fa fa-file-pdf-o"></i>
                                            View {{$doc->doc_name}}
                                        </a>
                                    </p>
                                </div>
                            @endforeach
                       
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <a href="{{url('osr/non_tax/asset/download/comparative')}}{{"/"}}{{base64_encode(base64_encode(base64_encode($assetData->asset_code)))}}{{"/"}}{{base64_encode(base64_encode(base64_encode($osrFyYear->id)))}}" target="_blank" class="btn btn-primary btn-block">
                            <i class="fa fa-download"></i>
                            Comparative BID Report
                        </a>
                    </div>
                   <div class="col-md-12 col-sm-12 col-xs-12 mt10">
                        <a href="{{url('osr/non_tax/asset/download/detailreport')}}{{"/"}}{{base64_encode(base64_encode(base64_encode($assetData->asset_code)))}}{{"/"}}{{base64_encode(base64_encode(base64_encode($osrFyYear->id)))}}" target="_blank" class="btn btn-primary btn-block">
                            <i class="fa fa-download"></i>
                           Overall BID Report
                        </a>
                    </div>
                    </div>
                </div>            
            </div>
            <hr/>
            @if(!(isset($settlementData->final_report_path)))
            <div class="row" id="upload_report_div">
                <form action="#" method="POST" id="upload_report">
                    <input type="hidden" name="asset_code" value="{{$assetData->asset_code}}"/>
                    <input type="hidden" name="fy_id" value="{{$osrFyYear->id}}"/>
                    <div class="col-md-4 col-sm-4 col-xs-8 col-md-offset-3">
                        <div class="form-group">
                            <input type="file" class="form-control" name="upload_report"/>
                        </div>

                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-8">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm" id="upload_report_final">Upload
                                <i class="fa fa-upload"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-4 col-xs-8 col-md-offset-3">
                        (<strong>*</strong> Please upload the scanned copy of the signed Comparitive BID Report)
                    </div>
                </form>
            </div>
            @endif
            
            </div>
        </div>
    </div>

    <!--------------------------------==============ADD LESSEE============--------------------------------------------->

    <div class="modal fade" id="addMore">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header  bg-primary">
                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    <h3 class="modal-title">Add Bidder</h3>
                    <p>Fields with asterisk (<strong>*</strong>) are required.</p>
                </div>

                <form action="" method="POST" id="addBidderForm" autocomplete="off">
                    <!-- Modal body -->
                    <div class="modal-body">
                        <h5>1. Bidder Details</h5>
                        <hr/>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input type="hidden" name="asset_code" value="{{$assetData->asset_code}}"/>
                                <input type="hidden" name="fy_id" value="{{$osrFyYear->id}}"/>

                                <div class="form-group text-center">
                                    <img id="l_pic_image" src="{{asset('mdas_assets/images/user_add.png')}}" style="border:1px solid #ddd;width:120px;max-height:130px;cursor:pointer" />
                                    <input type="file" name="b_pic" id="b_pic" style="display: none"/><br>
                                    <p class="p-sm">
                                        Click above to upload bidder's photo
                                        <a href="#" data-toggle="tooltip" title="Note: Click on the photo to upload passport photo. Upload jpg, jpeg and png file only. Max image size should not exceed 100KB and not less than 10KB">
                                            <i class="fa fa-question-circle"></i>
                                        </a>
                                    </p>
                                </div>
                                <div class="form-group text-center">
                                    <img id="l_pan_image" src="{{asset('mdas_assets/images/pancard.jpg')}}"
                                         style="border:1px solid #ddd;width:240px;max-height:140px;cursor:pointer"/>
                                    <input type="file" name="b_pan_pic" id="b_pan_pic" style="display: none"/><br>
                                    <p class="p-sm">
                                        Click above to upload bidder's PAN
                                        <a href="#" data-toggle="tooltip" title="Note: Click on the photo to upload pan card photo. Upload jpg, jpeg and png file only. Max image size should not exceed 100KB and not less than 10KB">
                                            <i class="fa fa-question-circle"></i>
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>First Name <strong>*</strong></label>
                                            <input type="text" class="form-control text-uppercase" name="b_f_name" id="b_f_name" placeholder=""/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Middle Name</label>
                                            <input type="text" class="form-control text-uppercase" name="b_m_name" id="b_m_name" placeholder=""/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Last Name <strong>*</strong></label>
                                            <input type="text" class="form-control text-uppercase" name="b_l_name" id="b_l_name" placeholder=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Father's/Husband's Name<strong>*</strong></label>
                                            <input type="text" class="form-control text-uppercase" name="b_father_name" id="b_father_name" placeholder=""/>
                                        </div>
                                    </div>
									<div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Gender <strong>*</strong></label>
                                            <select class="form-control" name="b_gender_id" id="b_gender_id">
                                                <option value="">---Select---</option>
                                                @foreach($genderAll AS $g)
                                                    <option value="{{$g->id}}">{{$g->gender_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
									<div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Caste<strong>*</strong></label>
                                            <select class="form-control" name="b_caste_id" id="b_caste_id">
                                            <option value="">---Select---</option>
                                            @foreach($casteAll AS $c)
                                                <option value="{{$c->id}}">{{$c->caste_name}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Pan Card Number<strong>*</strong></label>
                                            <input type="text" class="form-control text-uppercase" name="b_pan_no" id="b_pan_no" placeholder=""/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>GST Number </label>
                                            <input type="text" class="form-control text-uppercase" name="b_gst_no" id="b_gst_no" placeholder=""/>
                                        </div>
                                    </div>
									<div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Aadhaar Number </label>
                                            <input type="text" class="form-control text-uppercase" name="b_aadhaar_no" id="b_aadhaar_no" placeholder=""/>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Mobile Number <strong>*</strong></label>
                                            <input type="number" class="form-control" name="b_mobile" id="b_mobile" placeholder=""/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Alt Mobile Number</label>
                                            <input type="number" class="form-control" name="b_alt_mobile" id="b_alt_mobile" placeholder=""/>
                                        </div>
                                    </div>
									<div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Email Id</label>
                                            <input type="text" class="form-control" name="b_email" id="b_email" placeholder=""/>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <label>Pin Number <strong>*</strong></label>
                                        <input type="text" class="form-control" name="b_pin" id="b_pin" placeholder=""/>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Address <strong>*</strong></label>
                                            <textarea class="form-control" rows="2" name="b_addr" id="b_addr" value=""></textarea>
                                        </div>
                                    </div>
									<div class="col-md-4 col-sm-4 col-xs-12">
                                        <label>Police Station <strong>*</strong></label>
                                        <input type="text" class="form-control" name="b_p_station" id="b_p_station" placeholder=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h5>2. Bidder's Bidding Details</h5>
                        <hr/>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Bidding Amount <strong>*</strong></label>
                                    <input type="text" class="form-control money" name="b_bidding_amt" id="b_bidding_amt"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Earnest Money Deposit <strong>*</strong></label>
                                    <input type="text" class="form-control money" name="b_ernest_amt" id="b_ernest_amt"/>
                                </div>
                            </div>
                            <!--<div class="col-md-3 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Security Amount Deposited</label>
                                    <input type="text" class="form-control money" name="b_security_amt" id="b_security_amt"/>
                                </div>
                            </div>-->
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Bidder accepted/rejected/withdrawn? <strong>*</strong></label>
                                    <select name="b_status" id="b_status" class="form-control">
                                        <option value="">--Select Status--</option>
                                        <option value="0">Rejected</option>
                                        <option value="1">Accepted</option>
                                        <option value="2">Withdraw</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12" id="b_remark_div" style="display: none">
                                <div class="form-group">
                                    <label>Remarks</label>
                                    <select name="b_remark_id" id="b_remark_id" class="form-control">
                                        <option value="">--Select Status--</option>
                                        @foreach($bidderRemarks AS $remark)
                                            <option value="{{$remark->id}}">{{$remark->remark}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!--<div class="col-md-4 col-sm-4 col-xs-12" id="b_other_remark_div" style="display: none">
                                <div class="form-group">
                                    <label>Other Remark</label>
                                    <input type="text" class="form-control" name="b_other_remark" id="b_other_remark"/>
                                </div>
                            </div>-->
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-save">
                            <i class="fa fa-send"></i>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--------------------------------==============ADD LESSEE End============----------------------------------------->

    <!-------------------------------------Edit Bidder----------------------------------------------------------------->
    <div class="modal fade" id="editBidder">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header  bg-primary">
                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    <h3 class="modal-title">Edit Bidder</h3>
                    <p>Fields with asterisk (<strong>*</strong>) are required.</p>
                </div>
                {{--<div class="panel-heading panel-search" style="background-color: #f5f5f5;border-radius: 1px">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <input type="text" class="form-control" name="ed_search_data" id="ed_search_data" placeholder="Enter PAN Number..."/>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <button class="btn btn-primary btn-sm">
                                <i class="fa fa-search"></i>
                                SEARCH
                            </button>
                        </div>
                    </div>
                </div>--}}

                <form action="" method="POST" id="editBidderForm" autocomplete="off">
                    <!-- Modal body -->
                    <div class="modal-body">
                        <input type="hidden" name="asset_code" value="{{$assetData->asset_code}}"/>
                        <input type="hidden" name="fy_id" value="{{$osrFyYear->id}}"/>
                        <input type="hidden" id="bid" name="bid" value=""/>
                        <h5>1. Bidder Details</h5>
                        <hr/>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group text-center">
                                    <img id="ed_l_pic_image" src="{{asset('mdas_assets/images/user_add.png')}}" style="border:1px solid #ddd;width:120px;max-height:130px;cursor:pointer" />
                                    <input type="file" name="ed_b_pic" id="ed_b_pic" style="display: none"/><br>
                                    <p class="p-sm">
                                        Click above to upload bidder's photo
                                        <a href="#" data-toggle="tooltip" title="Note: Click on the photo to upload passport photo. Upload jpg, jpeg and png file only. Max image size should not exceed 100KB and not less than 10KB">
                                            <i class="fa fa-question-circle"></i>
                                        </a>
                                    </p>
                                </div>
                                <div class="form-group text-center">
                                    <img id="ed_l_pan_image" src="{{asset('mdas_assets/images/pancard.jpg')}}"
                                         style="border:1px solid #ddd;width:240px;max-height:140px;cursor:pointer"/>
                                    <input type="file" name="ed_b_pan_pic" id="ed_b_pan_pic" style="display: none"/><br>
                                    <p class="p-sm">
                                        Click above to upload bidder's PAN
                                        <a href="#" data-toggle="tooltip" title="Note: Click on the photo to upload pan card photo. Upload jpg, jpeg and png file only. Max image size should not exceed 100KB and not less than 10KB">
                                            <i class="fa fa-question-circle"></i>
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>First Name <strong>*</strong></label>
                                            <input type="text" class="form-control" name="ed_b_f_name" id="ed_b_f_name" placeholder=""/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Middle Name</label>
                                            <input type="text" class="form-control" name="ed_b_m_name" id="ed_b_m_name" placeholder=""/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Last Name <strong>*</strong></label>
                                            <input type="text" class="form-control" name="ed_b_l_name" id="ed_b_l_name" placeholder=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Father's/Husband's Name<strong>*</strong></label>
                                            <input type="text" class="form-control text-uppercase" name="ed_b_father_name" id="ed_b_father_name" placeholder=""/>
                                        </div>
                                    </div>
									<div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Gender <strong>*</strong></label>
                                            <select class="form-control" name="ed_b_gender_id" id="ed_b_gender_id">
                                                <option value="">---Select---</option>
                                                @foreach($genderAll AS $g)
                                                    <option value="{{$g->id}}">{{$g->gender_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Caste <strong>*</strong></label>
                                            <select class="form-control" name="ed_b_caste_id" id="ed_b_caste_id">
                                                <option value="">---Select---</option>
                                                @foreach($casteAll AS $c)
                                                    <option value="{{$c->id}}">{{$c->caste_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Pan Card Number<strong>*</strong></label>
                                            <input type="text" class="form-control text-uppercase" name="ed_b_pan_no" id="ed_b_pan_no" placeholder=""/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>GST Number </label>
                                            <input type="text" class="form-control text-uppercase" name="ed_b_gst_no" id="ed_b_gst_no" placeholder=""/>
                                        </div>
                                    </div>
									<div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Aadhaar Number </label>
                                            <input type="text" class="form-control text-uppercase" name="ed_b_aadhaar_no" id="ed_b_aadhaar_no" placeholder=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Mobile Number <strong>*</strong></label>
                                            <input type="number" class="form-control" name="ed_b_mobile" id="ed_b_mobile" placeholder=""/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Alt Mobile Number</label>
                                            <input type="number" class="form-control" name="ed_b_alt_mobile" id="ed_b_alt_mobile" placeholder=""/>
                                        </div>
                                    </div>
									<div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Email Id</label>
                                            <input type="text" class="form-control" name="ed_b_email" id="ed_b_email" placeholder=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <label>Pin Number <strong>*</strong></label>
                                        <input type="text" class="form-control" name="ed_b_pin" id="ed_b_pin" placeholder=""/>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Address <strong>*</strong></label>
                                            <textarea class="form-control" rows="2" name="ed_b_addr" id="ed_b_addr" value=""></textarea>
                                        </div>
                                    </div>
									<div class="col-md-4 col-sm-4 col-xs-12">
                                        <label>Police Station <strong>*</strong></label>
                                        <input type="text" class="form-control" name="ed_b_p_station" id="ed_b_p_station" placeholder=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h5>2. Bidder's Bidding Details</h5>
                        <hr/>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Bidding Amount <strong>*</strong></label>
                                    <input type="text" class="form-control money" name="ed_b_bidding_amt" id="ed_b_bidding_amt"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Earnest Money Deposit <strong>*</strong></label>
                                    <input type="text" class="form-control money" name="ed_b_ernest_amt" id="ed_b_ernest_amt"/>
                                </div>
                            </div>
                           <!-- <div class="col-md-3 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Security Amount Deposited</label>
                                    <input type="text" class="form-control money" name="ed_b_security_amt" id="ed_b_security_amt"/>
                                </div>
                            </div>-->

                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Bidder accepted/rejected/withdrawn? <strong>*</strong></label>
                                    <select name="ed_b_status" id="ed_b_status" class="form-control">
                                        <option value="">--Select Status--</option>
                                        <option value="0">Rejected</option>
                                        <option value="1">Accepted</option>
                                        <option value="2">Withdraw</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12" id="ed_b_remark_div" style="display: none">
                                <div class="form-group">
                                    <label>Remarks</label>
                                    <select name="ed_b_remark_id" id="ed_b_remark_id" class="form-control">
                                        <option value="">--Select Status--</option>
                                        @foreach($bidderRemarks AS $remark)
                                            <option value="{{$remark->id}}">{{$remark->remark}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!--<div class="col-md-4 col-sm-4 col-xs-12" id="ed_b_other_remark_div" style="display: none">
                                <div class="form-group">
                                    <label>Other Remark</label>
                                    <input type="text" class="form-control" name="ed_b_other_remark" id="ed_b_other_remark"/>
                                </div>
                            </div>-->
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-save">
                            <i class="fa fa-send"></i>
                            Edit &amp; Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

 <!-------------------------------------Upload Bidder Documents----------------------------------------------------------------->
    <div class="modal fade" id="addBidderDocuments">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header  bg-primary">
                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    <h3 class="modal-title">Upload Bidder Documents</h3>
                    <p>Fields with asterisk (<strong>*</strong>) are required.</p>
                </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <h5>Attachments</h5>
                                @foreach($activeBidderDocs AS $doc)
                                        <label>{{$doc->doc_name}} <strong>*</strong></label>
                                        <p id="viewBidderAttachment_{{$doc->id}}" style="display:none">
                                            <a href="" target="_blank" class="btn btn-success btn-xs" id="bidder_attachment_view_link_{{$doc->id}}">
                                                <i class="fa fa-check"></i>
                                                View {{$doc->doc_name}}
                                            </a>
                                            <button type="button" class="btn btn-warning btn-xs edit_bidder_attachment" data-doc_id="{{$doc->id}}">
                                                <i class="fa fa-edit"></i>
                                                Edit
                                            </button>
                                        </p>
                                        <form action="#" method="POST" id="bidderAttachmentForm_{{$doc->id}}" style="display:none">
                                            <input type="hidden" name="doc_no" value="{{$doc->id}}"/>
                                            <input type="hidden" name="bidder_asset_code" value="{{$assetData->asset_code}}"/>
                                            <input type="hidden" name="bidder_fy_yr_id" value="{{$osrFyYear->id}}"/>
                                            <input type="hidden" class="bidder_id" name="bidder_id" value=""/>
                                            <input type="file" class="form-control" name="attachment"/>
                                            <button type="submit" class="btn btn-primary btn-xs" id="upload_bidder_attach_{{$doc->id}}" style="margin-top: 4px;">
                                                <i class="fa fa-upload"></i>
                                                Upload
                                            </button>  
                                            <button type="button" class="btn btn-danger btn-xs cancel_bidder_attachment" id="cancel_bidder_attach_{{$doc->id}}" data-doc_id="{{$doc->id}}" style="margin-top: 4px;">
                                            <i class="fa fa-times" aria-hidden="true"></i>
												Cancel
                                        </button>
                                        </form>
                                @endforeach
                    </div>
            </div>
        </div>
    </div>

    <!-------------------------------------Edit Bidder End------------------------------------------------------------->
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

        $('#date_of_tender').Zebra_DatePicker({
			
		});
        
        $('#awarded_date').Zebra_DatePicker();

        var indianRupeeFormatter = OSREC.CurrencyFormatter.getFormatter({
                currency: 'INR',
                symbol: '',
            });

        var indianRupeeFormatterText = OSREC.CurrencyFormatter.getFormatter({
                currency: 'INR',
                symbol: '₹',
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

        /*--------------------------------- GENERAL DETAILS ------------------------------------------------*/

        $("#generalEntry").validate({
            rules: {
                date_of_tender: {
                    required: true,
                },
                govt_value: {
                    required: true,
                },
                advertisement:{
                    required: true,
                }
            },
        });

        $('#generalEntry').on('submit', function(e){
            e.preventDefault();

            $('.form_errors').remove();

            if($('#generalEntry').valid()){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('osr.non_tax.asset.bidding.save_general_detail')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {

                            //swal("Success", data.msg, "success");

                            $('.multi-steps li:first').removeClass('is-active');
                            $('.multi-steps li:nth-child(2)').addClass('is-active');

                            $('.general_panel').hide();
                            $('.bidder-panel').show();


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

        /*---------------------------------- END GENERAL DETAILS --------------------------------------------*/

        $('#bidderPrevious').on('click', function(e){
            e.preventDefault();

            var asset_code= $(this).data('ac');
            var fy_id= $(this).data('fy');

            if(asset_code && fy_id){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('osr.non_tax.asset.bidding.getGeneralDetails')}}',
                    dataType: "json",
                    data: {asset_code: asset_code, fy_id: fy_id},
                    cache: false,
                    success: function (data) {
                        if (data.msgType == true) {

                            $('.multi-steps li:nth-child(2)').removeClass('is-active');
                            $('.multi-steps li:first').addClass('is-active');

                            $('.general_panel').show();
                            $('.bidder-panel').hide();

                            $('#govt_value').val(indianRupeeFormatter(data.data.govt_value));
                            $('#date_of_tender').val(data.data.date_of_tender);

                            $('#general_panel_adv').hide();
                            $('#general_panel_adv_view').show();
                            $('#general_panel_adv_view_link').attr('href', '{{$imgUrl}}'+data.data.advertisement);
                        }else{
                            swal("Error", data.msg, 'error');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                    },
                    complete: function (data) {
                        $('.page-loader-wrapper').fadeOut();
                    }
                });
            }else{
                alert("Opps! something went wrong");
            }
        });


        $('#general_panel_adv_change').on('click', function(e){
            e.preventDefault();
            $('#general_panel_adv').show();
            $('#general_panel_adv_cancel').show();
            $('#general_panel_adv_view').hide();
        });

        $('#general_panel_adv_cancel').on('click', function(e){
            e.preventDefault();
            $('#general_panel_adv').hide();
            $('#general_panel_adv_cancel').hide();
            $('#general_panel_adv_view').show();
        });

        $('#finalPrevious').on('click', function(e){
            e.preventDefault();
            $('.multi-steps li:nth-child(3)').removeClass('is-active');
            $('.multi-steps li:nth-child(2)').addClass('is-active');


            //$('.general_panel').show();
            $('.bidder-panel').show();
            $('.settlement-panel').hide();
        });

        /*------------------------------- SAVE BIDDER -------------------------------------------*/

        $("#addBidderForm").validate({
            rules: {
                b_f_name: {
                    required: true,
                    blank:true,
                    maxlength:100
                },
                b_m_name: {
                    maxlength:100
                },
                b_l_name: {
                    required: true,
                    blank:true,
                    maxlength:100
                },
                b_father_name: {
                    required: true,
                    blank:true,
                    maxlength:100
                },
                b_email: {
                    email:true,
                    maxlength:150
                },
                b_gender_id:{
                    required: true,
                    digits:true,
                },
                b_caste_id:{
                    required: true,
                    digits:true,
                },
                b_pan_no: {
                    required: true,
                },
                b_gst_no:{
                    blank:true,
                    maxlength:15,
                    minlength:15,
                },
                b_addr:{
                    required: true,
                    maxlength:200
                },
                b_p_station:{
                    required: true,
                    maxlength:100
                },
                b_pin:{
                    required: true,
                    digits:true,
                    maxlength:6
                },
				 b_bidding_amt:{
				  required: true,
				  },
				  b_ernest_amt:{
					  required: true,
				  },
				  b_status:{
					required: true,
						digits:true,
				  }

            },
        });

        $('#addBidderForm').on('submit', function(e){
            e.preventDefault();

            if($('#addBidderForm').valid()){

                $('.page-loader-wrapper').fadeIn();

                $('.form_errors').remove();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('osr.non_tax.asset.bidder.save')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {

                            swal("Success", data.msg, "success")
                                .then((value) => {
                                $('#addBidderForm').modal('hide');
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

        $('#b_status').on('change', function(e){
            e.preventDefault();
            $('#b_remark_id').val('');

            if($('#b_status').val()==0){
                $('#b_remark_div').show();
            }else{
                $('#b_remark_div').hide();
            }

        });

       /* $('#b_remark_id').on('change', function(e){
            e.preventDefault();
            $('#b_other_remark').val('');
            if($('#b_remark_id').val()==1){
                $('#b_other_remark_div').show();
            }else{
                $('#b_other_remark_div').hide();
            }

        });*/

        //-- BIDDER IMAGE ---------------------------------

        $('#b_pic').change(function () {
            if (this.files && this.files[0]) {
                checkImage(this.files[0]);
            }
        });

        function checkImage(file){
            var extension = file.name.substr((file.name.lastIndexOf('.') + 1));

            if (extension === 'jpg' || extension === 'jpeg' || extension === 'png') {

                var img=file.size;
                var imgsize=img/1024;
                if(imgsize >= 10 && imgsize <=110){
                    var reader = new FileReader();
                    reader.onload = imageIsLoaded;
                    reader.readAsDataURL(file);
                }else{
                    swal("Information", "Image size must be less than or equal to 100 KB and greater than 10 KB!", "info");
                    $('#b_pic').val('');
                    $('#l_pic_image').attr('src', '{{asset('mdas_assets/images/user_add.png')}}');
                    exit();
                }

            } else {
                swal("Information", "Please select only jpeg, jpg and png format only!", "info");
                $('#b_pic').val('');
                $('#l_pic_image').attr('src', '{{asset('mdas_assets/images/user_add.png')}}');
                exit();
            }

        }

        function imageIsLoaded(e) {
            $('#l_pic_image').attr('src', e.target.result);
        }

        $('#l_pic_image').click(function(e){
            e.preventDefault();
            $('#b_pic').click()
        });

        //-----------BIDDER PAN CARD-------------------------

        $('#b_pan_pic').change(function () {
            if (this.files && this.files[0]) {
                checkPan(this.files[0]);
            }
        });

        function checkPan(file){
            var extension = file.name.substr((file.name.lastIndexOf('.') + 1));

            if (extension === 'jpg' || extension === 'jpeg' || extension === 'png') {

                var img=file.size;
                var imgsize=img/1000;
                if(imgsize >= 10 && imgsize <=100){
                    var reader = new FileReader();
                    reader.onload = panIsLoaded;
                    reader.readAsDataURL(file);
                }else{
                    swal("Information", "Image size must be less than or equal to 100 KB and greater than 10 KB!", "info");
                    $('#b_pan_pic').val('');
                    $('#l_pan_image').attr('src', '{{asset('mdas_assets/images/pancard.jpg')}}');
                    exit();
                }

            } else {
                swal("Information", "Please select only jpeg, jpg and png format only!", "info");
                $('#b_pan_pic').val('');
                $('#l_pan_image').attr('src', '{{asset('mdas_assets/images/pancard.jpg')}}');
                exit();
            }

        }

        function panIsLoaded(e) {
            $('#l_pan_image').attr('src', e.target.result);
        }

        $('#l_pan_image').click(function(e){
            e.preventDefault();
            $('#b_pan_pic').click()
        });

        //--------------EDIT OF BIDDER JQUERY ------------------------------


        $('#ed_b_status').on('change', function(e){
            e.preventDefault();
            $('#ed_b_remark_id').val('');

            if($('#ed_b_status').val()==0){
                $('#ed_b_remark_div').show();
            }else{
                $('#ed_b_remark_div').hide();
            }

        });

        /*$('#ed_b_remark_id').on('change', function(e){
            e.preventDefault();
            $('#ed_b_other_remark').val('');
            if($('#ed_b_remark_id').val()==1){
                $('#ed_b_other_remark_div').show();
            }else{
                $('#ed_b_other_remark_div').hide();
            }

        });*/

        //-- EDIT BIDDER IMAGE ---------------------------------

        //-- BIDDER IMAGE ---------------------------------

        $('#ed_b_pic').change(function () {
            if (this.files && this.files[0]) {
                ed_checkImage(this.files[0]);
            }
        });

        function ed_checkImage(file){
            var extension = file.name.substr((file.name.lastIndexOf('.') + 1));

            if (extension === 'jpg' || extension === 'jpeg' || extension === 'png') {

                var img=file.size;
                var imgsize=img/1024;
                if(imgsize >= 10 && imgsize <=110){
                    var reader = new FileReader();
                    reader.onload = imageIsLoaded;
                    reader.readAsDataURL(file);
                }else{
                    swal("Information", "Image size must be less than or equal to 100 KB and greater than 10 KB!", "info");
                    $('#ed_b_pic').val('');
                    $('#ed_l_pic_image').attr('src', '{{asset('mdas_assets/images/user_add.png')}}');
                    exit();
                }

            } else {
                swal("Information", "Please select only jpeg, jpg and png format only!", "info");
                $('#ed_b_pic').val('');
                $('#ed_l_pic_image').attr('src', '{{asset('mdas_assets/images/user_add.png')}}');
                exit();
            }

        }

        function imageIsLoaded(e) {
            $('#ed_l_pic_image').attr('src', e.target.result);
        }

        $('#ed_l_pic_image').click(function(e){
            e.preventDefault();
            $('#ed_b_pic').click()
        });
        //-----------EDIT BIDDER PAN CARD-------------------------

        $('#ed_b_pan_pic').change(function () {
            if (this.files && this.files[0]) {
                ed_checkPan(this.files[0]);
            }
        });

        function ed_checkPan(file){
            var extension = file.name.substr((file.name.lastIndexOf('.') + 1));

            if (extension === 'jpg' || extension === 'jpeg' || extension === 'png') {

                var img=file.size;
                var imgsize=img/1000;
                if(imgsize >= 10 && imgsize <=100){
                    var reader = new FileReader();
                    reader.onload = ed_panIsLoaded;
                    reader.readAsDataURL(file);
                }else{
                    swal("Information", "Image size must be less than or equal to 100 KB and greater than 10 KB!", "info");
                    $('#ed_b_pan_pic').val('');
                    $('#ed_l_pan_image').attr('src', '{{asset('mdas_assets/images/pancard.jpg')}}');
                    exit();
                }

            } else {
                swal("Information", "Please select only jpeg, jpg and png format only!", "info");
                $('#ed_b_pan_pic').val('');
                $('#ed_l_pan_image').attr('src', '{{asset('mdas_assets/images/pancard.jpg')}}');
                exit();
            }

        }

        function ed_panIsLoaded(e) {
            $('#ed_l_pan_image').attr('src', e.target.result);
        }

        $('#ed_l_pan_image').click(function(e){
            e.preventDefault();
            $('#ed_b_pan_pic').click()
        });

        /*------------------------------- END SAVE BIDDER --------------------------------------------------*/

        $('#bidderEntry').on('click', function(e){
            e.preventDefault();

            $('.page-loader-wrapper').fadeIn();

            $('.form_errors').remove();

            var asset_code= $(this).data('ac');
            var fy_id= $(this).data('fy');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('osr.non_tax.asset.bidding.status_update')}}',
                dataType: "json",
                data: {asset_code:asset_code, fy_id:fy_id},
                cache: false,
                success: function (data) {
                    if (data.msgType == true) {

                        $('#f_tbs').text(data.data.totalBidder);
                        $('#f_a_b_n').text(data.data.acceptedBidder.b_f_name+" "+data.data.acceptedBidder.b_m_name+" "+data.data.acceptedBidder.b_l_name);
                        $('#f_a_b_fn').text(data.data.acceptedBidder.b_father_name);
                        $('#f_a_b_ba').text(indianRupeeFormatterText(data.data.acceptedBidder.bidding_amt));

                        $('.multi-steps li:nth-child(2)').removeClass('is-active');
                        $('.multi-steps li:nth-child(3)').addClass('is-active');

                        $('.bidder-panel').hide();

                        $('.settlement-panel').show();

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
        });
        /*--------------------------------FINAL REPORT UPLOAD---------------------------------------------------------*/
        
        $('#upload_report').on('submit', function(e){
            e.preventDefault();
            

            if($('#upload_report').valid()) {
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url:  '{{route('osr.non_tax.asset.bidding.report_upload')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {
                            swal("Success", data.msg, "success")
                                .then((value) => {
                            location.reload();
                        });
                        } 
                       else {
                            if (data.msg == "VE") {
                                swal("Error","Please select attachment to upload.The attachment must be in pdf format only. Maximum size is 200KB and minimum 10KB", 'error');
                            }
                            else {
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
            }else{
                alert('Please select attachment.');
            }
        });
        
        
        /*--------------------------------FINAL REPORT UPLOAD ENDS----------------------------------------------------*/
        
        
        
        /*--------------------------------BIDDER ATTACHMENT-----------------------------------------------------------*/
        $('.edit_bidder_attachment').on('click', function(e){
            e.preventDefault();
            var doc_id= $(this).data('doc_id');
            if(doc_id){
                $('#viewBidderAttachment_'+doc_id).hide();
                $('#cancel_bidder_attach_'+doc_id).show();
                $('#bidderAttachmentForm_'+doc_id).show();
            }

        });
        
           $(document).on("click",".cancel_bidder_attachment",function(e){
            e.preventDefault();
            var doc_id= $(this).data('doc_id');
            if(doc_id){
                $('#viewBidderAttachment_'+doc_id).show();
                $('#bidderAttachmentForm_'+doc_id).hide();
                $('#cancel_bidder_attach_'+doc_id).hide();
            }
        });

        @foreach($activeBidderDocs AS $doc)

        $('#bidderAttachmentForm_{{$doc->id}}').validate({
            rules: {
                attachment: {
                    required: true,
                }
            },
        });

        $('#bidderAttachmentForm_{{$doc->id}}').on('submit', function(e){
            e.preventDefault();
            $('.form_errors').remove();

            if($('#bidderAttachmentForm_{{$doc->id}}').valid()) {
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('osr.non_tax.bidder.attachment_upload')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {
                            $('#viewBidderAttachment_'+data.data.doc_no).show();
                            $('#bidderAttachmentForm_'+data.data.doc_no).hide();
                            $('#bidder_attachment_view_link_'+data.data.doc_no).attr('href', data.data.imgUrl+data.data.attachment_path);
                            $('#cancel_bidder_attach_'+data.data.doc_no).remove();
                            $('#upload_bidder_attach_'+data.data.doc_no).after('<button type="button" class="btn btn-danger btn-xs cancel_bidder_attachment" id="cancel_bidder_attach_'+data.data.doc_no+'" data-doc_id="{{$doc->id}}">Cancel'+'</button>');
                        } else {
                            if (data.msg == "VE") {
                                swal("Error", "Please select attachment to upload. The attachment must be in pdf format only. Maximum size is 400KB and minimum 10KB", 'error');
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
            }else{
                alert('Please select attachment.');
            }
        });

        @endforeach


        /*--------------------------------SETTLEMENT FORM-------------------------------------------------------------*/

        $('.edit_attachment').on('click', function(e){
            e.preventDefault();
            var doc_id= $(this).data('doc_id');
            if(doc_id){
                $('#viewAttachment_'+doc_id).hide();
                $('#attachmentForm_'+doc_id).show();
            }

        });

        $(document).on("click",".cancel_attachment",function(e){
            e.preventDefault();
            var doc_id= $(this).data('doc_id');
            if(doc_id){
                $('#viewAttachment_'+doc_id).show();
                $('#attachmentForm_'+doc_id).hide();
            }
        });

        @foreach($activeDocs AS $doc)

        $('#attachmentForm_{{$doc->id}}').validate({
            rules: {
                attachment: {
                    required: true,
                }
            },
        });

        $('#attachmentForm_{{$doc->id}}').on('submit', function(e){
            e.preventDefault();
            $('.form_errors').remove();

            if($('#attachmentForm_{{$doc->id}}').valid()) {
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('osr.non_tax.asset.bidding.attachment_upload')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {
                            $('#viewAttachment_'+data.data.doc_no).show();
                            $('#attachmentForm_'+data.data.doc_no).hide();
                            $('#attachment_view_link_'+data.data.doc_no).attr('href', data.data.imgUrl+data.data.attachment_path);
                            $('#cancel_attach_'+data.data.doc_no).remove();
                            $('#upload_attach_'+data.data.doc_no).after('<button type="button" class="btn btn-danger btn-xs cancel_attachment" id="cancel_attach_'+data.data.doc_no+'" data-doc_id="{{$doc->id}}">Cancel'+'</button>');
                        } 
						else {
                            if (data.msg == "VE") {
                                swal("Error", "Please select attachment to upload. The attachment must be in pdf format only. Maximum size is 400KB and minimum 10KB", 'error');
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
            }else{
                alert('Please select attachment.');
            }
        });

        @endforeach


        $('#finalSubmit').on('click', function(e) {
            e.preventDefault();
            $('.form_errors').remove();
            var work_order_no= $('#work_order_no').val();
            var security_deposit= $('#security_deposit').val();
            var file_no= $('#file_no').val();
            var awarded_date= $('#awarded_date').val();
            var asset_code= '{{$assetData->asset_code}}';
            var fy_id= {{$osrFyYear->id}};


            swal({
                title: "Are you sure?",
                text: "You are sure you want to final submit. Once submitted you will not be able to modify.",
                icon: "warning",
                buttons: {
                    cancel: "Cancel",
                    catch: {
                        text: "Proceed",
                        value: "catch",
                    }
                },
            }).then((value) => {
                switch (value) {
                case "catch":

                    $('.page-loader-wrapper').fadeIn();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "{{route('osr.non_tax.asset.bidding.final_submit')}}",
                        dataType: "json",
                        data: {work_order_no: work_order_no, security_deposit: security_deposit, file_no: file_no, awarded_date: awarded_date, asset_code: asset_code, fy_id: fy_id},
                        cache: false,
                        success: function (data) {
                            if (data.msgType == true) {
                                swal("Success", data.msg, "success")
                                    .then((value) => {
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
                    break;

                default:
                    swal("Cancelled operation!");
                }
            })
        });

        /*------------------------------------ GET BIDDER INFO -------------------------------------------------------*/

        $('.editBidder').on('click', function(e){
            e.preventDefault();

            var bid= $(this).data('bid');
            $('#ed_b_remark_id').val('');
            $('#ed_b_other_remark').val('');

            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('osr.non_tax.asset.bidder.getById')}}',
                dataType: "json",
                data: {bid: bid},
                cache: false,
                success: function (data) {
                    if (data.msgType == true) {
                        $('#bid').val(data.data.BidderData.id);

                        if(data.data.BidderData.b_pan_path){
                            $('#ed_l_pan_image').attr('src', data.data.imgUrl + data.data.BidderData.b_pan_path);
                        }else{
                            $('#ed_l_pan_image').attr('src', '{{asset('mdas_assets/images/pancard.jpg')}}');
                        }

                        if(data.data.BidderData.b_pic_path){
                            $('#ed_l_pic_image').attr('src', data.data.imgUrl + data.data.BidderData.b_pic_path);
                        }else{
                            $('#ed_l_pic_image').attr('src', '{{asset('mdas_assets/images/user_add.png')}}');
                        }

                        $('#ed_b_f_name').val(data.data.BidderData.b_f_name);
                        $('#ed_b_m_name').val(data.data.BidderData.b_m_name);
                        $('#ed_b_l_name').val(data.data.BidderData.b_l_name);
                        $('#ed_b_father_name').val(data.data.BidderData.b_father_name);
                        $('#ed_b_mobile').val(data.data.BidderData.b_mobile);
                        $('#ed_b_alt_mobile').val(data.data.BidderData.b_alt_mobile);
                        $('#ed_b_email').val(data.data.BidderData.b_email);
                        $('#ed_b_gender_id').val(data.data.BidderData.b_gender_id);
                        $('#ed_b_caste_id').val(data.data.BidderData.b_caste_id);
                        $('#ed_b_pan_no').val(data.data.BidderData.b_pan_no);
                        $('#ed_b_gst_no').val(data.data.BidderData.b_gst_no);
                        $('#ed_b_aadhaar_no').val(data.data.BidderData.b_aadhaar_no);
                        $('#ed_b_addr').val(data.data.BidderData.b_address);
                        $('#ed_b_p_station').val(data.data.BidderData.b_police_station);
                        $('#ed_b_pin').val(data.data.BidderData.b_pin);
                        $('#ed_b_bidding_amt').val(indianRupeeFormatter(data.data.BidderData.bidding_amt));
                        $('#ed_b_ernest_amt').val(indianRupeeFormatter(data.data.BidderData.ernest_amt));

                        var status = data.data.BidderData.bidder_status;
                        if(status==0){
                            var remark = data.data.BidderData.osr_master_bidder_remark_id;
                            $('#ed_b_remark_div').show();
                            $('#ed_b_remark_id').val(remark);

                        }else{
                            $('#ed_b_remark_div').hide();
                        }

                        $('#ed_b_status').val(status);

                        $('#editBidder').modal('show');

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
            })});

        $("#editBidderForm").validate({
            rules: {
                ed_b_f_name: {
                    required: true,
                    blank:true,
                    maxlength:100
                },
                ed_b_m_name: {
                    maxlength:100
                },
                ed_b_l_name: {
                    required: true,
                    blank:true,
                    maxlength:100
                },
                ed_b_father_name: {
                    required: true,
                    blank:true,
                    maxlength:100
                },
                ed_b_email: {
                    email:true,
                    maxlength:150
                },
                ed_b_gender_id:{
                    required: true,
                    digits:true,
                },
                ed_b_caste_id:{
                    required: true,
                    digits:true,
                },
                ed_b_pan_no: {
                    required: true,
                },
                ed_b_gst_no:{
                    blank:true,
                    maxlength:15,
                    minlength:15
                },
                ed_b_addr:{
                    required: true,
                    maxlength:200
                },
                ed_b_p_station:{
                    required: true,
                    maxlength:100
                },
                ed_b_pin:{
                    required: true,
                    digits:true,
                    maxlength:6
                },
				  ed_b_bidding_amt:{
					 required: true,
				  },
				  ed_b_ernest_amt:{
					required: true,
				 },
				  ed_b_status:{
					required: true,
						digits:true,
				 }
            },
        });

        /*-----------------------------------  EDIT  -----------------------------------------------------------------*/

        $('#editBidderForm').on('submit', function(e){
            e.preventDefault();

            var bid= $(this).data('bid');
            if($('#editBidderForm').valid()){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('osr.non_tax.asset.bidder.edit')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {
                            swal("Success", data.msg, "success")
                                .then((value) => {
                                $('#editBidderForm').modal('hide');
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
        
         /*------------------------------------ GET BIDDER ATTACHMENT-------------------------------------------------------*/

        $('.addBidderDocuments').on('click', function(e){
            e.preventDefault();
            $('.cancel_bidder_attachment').hide();
            var bid = $(this).data('bid');
           
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('osr.non_tax.bidderAttachment.getById')}}',
                dataType: "json",
                data: {bid:bid},
                cache: false,
                success: function (data) {
                    if (data.msgType == true) {
                        $.each(data.data.uploadedBidderDocs,function(index,value){
                            if(value.uploadDetails){
                                $('#bidder_attachment_view_link_'+value.uploadDetails.osr_non_tax_bidder_attachment_id).attr("href", "{{$imgUrl}}"+value.uploadDetails.attachment_path);
                                $('#bidderAttachmentForm_'+value.att_id).hide();
                                $('#viewBidderAttachment_'+value.att_id).show();
                                $('#cancel_bidder_attach_'+value.att_id).hide();
                            }else{
                                $('#bidder_attachment_view_link_'+value.att_id).attr("href", "");
                                $('#bidderAttachmentForm_'+value.att_id).show();
                                $('#viewBidderAttachment_'+value.att_id).hide();
                            }
                        })
                       
                        $('.bidder_id').val(data.data.BidderData.id);
                        $('#addBidderDocuments').modal('show');

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
            })});
    </script>
@endsection
