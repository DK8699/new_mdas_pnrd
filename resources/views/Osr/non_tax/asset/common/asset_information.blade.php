@php
    $page_title="assetInformation";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <link href="https://fonts.googleapis.com/css?family=Old+Standard+TT&display=swap" rel="stylesheet">
    <style>


        #exTab2 h3 {
            color: white;
            background-color: #428bca;
            padding: 5px 15px;
        }

        .nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover {
            color: #fff;
            cursor: default;
            background-color: #FF5722;
            border: 1px solid #ddd;
            border-bottom-color: transparent;
        }

        .nav > li > a:focus, .nav > li > a:hover {
            text-decoration: none;
            background-color: #a4114c;
        }

        table.hf td{
            width:50%
        }
        .panel-primary>.panel-heading {
            background-color: rgb(255, 118, 15);
            background-image: linear-gradient(to right, #FF5722 , #FF5722);
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

    <div class="container mt30">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 mb20">
                <a href="javascript:history.back()" style=""><button class="btn" onMouseOver="this.style.color='#fff'" style="border-radius: 18px;margin-bottom: 2px;"><i class="fa fa-arrow-left"></i> Back</button></a>
            </div>
        </div>
        <h5> ASSET, BID & PAYMENT INFORMATION of <b>{{$data['assetData']->asset_name}}</b> for the {{$fy_years}}</h5>
    </div>

    <div  class="container">
        <ul class="nav nav-tabs green-back">
            <li class="active">
                <a href="#tab_1" data-toggle="tab" style="color: #fff">BID Evaluation Report</a>
            </li>
            <li class="">
                <a href="#tab_2" data-toggle="tab" style="color: #fff">Payment Information</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div class="col-md-12 col-sm-12 col-xs-12" style="background-color: #fff;margin-bottom: 40px">

                    <hr/>

                    {{-----------  ASSET INFORMATION ---------------------------------------------------------------------}}

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Asset Information
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered hf">
                                    <tbody>
                                    <tr>
                                        <td class="bold-text">Asset Category</td>
                                        <td>{{$data['branchData']->branch_name}}</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Asset Code</td>
                                        <td>{{$data['assetData']->asset_code}}</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Asset Name</td>
                                        <td>{{$data['assetData']->asset_name}}</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Asset Managed By</td>
                                        <td>
                                            @if($data['assetData']->asset_under=="ZP")
                                                {{"Zila Parishad"}}
                                            @elseif($data['assetData']->asset_under=="AP")
                                                {{"Anchalik Panchayat"}}
                                            @else
                                                {{"Gram Panchayat"}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Listing Date</td>
                                        <td>{{\Carbon\Carbon::parse($data['assetData']->asset_listing_date)->format('d M Y')}}</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Location</td>
                                        <td>ZP: {{$data['asset_zp_name']}} AP: {{$data['asset_ap_name']}} GP: {{$data['asset_gp_name']}}</td>
                                    </tr>
                                    <tr>
                                        <td class="bold-text">Brief Description</td>
                                        <td>{{$data['assetData']->b_desc}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-----------  BID INFORMATION ---------------------------------------------------------------------}}

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            BID Information
                        </div>
                        <div class="panel-body">
                            @if($data['generalDetail'] && $data['settlementData'] && $data['finalRecordData'])
                                <div class="table-responsive">
                                    <table class="table table-bordered hf">
                                        <tbody>
                                        <tr>
                                            <td class="bold-text">BID Value</td>
                                            <td>
                                        <span class="money_txt">
                                            <i class="fa fa-rupee"></i>
                                            {{$data['generalDetail']->govt_value}}
                                        </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Tender Date</td>
                                            <td>{{\Carbon\Carbon::parse($data['generalDetail']->date_of_tender)->format('d M Y')}}</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Total BID Submitted</td>
                                            <td>{{$data['settlementData']->total_bidder}}</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Withdrawn Bidders</td>
                                            <td>{{$data['settlementData']->total_withdrawn_bidder}}</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">No. of BID Rejected</td>
                                            <td>{{$data['settlementData']->total_bidder - $data['settlementData']->total_withdrawn_bidder -1}}</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Forfieted Withdrawn Bidders</td>
                                            <td>{{$data['settlementData']->total_forfeited_bidder}}</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Forfieted Earnest Money Deposit</td>
                                            <td>
                                                <i class="fa fa-rupee"></i>
                                                {{$data['settlementData']->total_forfeited_amount}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Settled BID Amount</td>
                                            <td>
                                                <i class="fa fa-rupee"></i>
                                                {{$data['finalRecordData']->settlement_amt}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="bold-text">Security Money Deposited</td>
                                            <td>
                                                <i class="fa fa-rupee"></i>
                                                {{$data['finalRecordData']->security_deposit_amt}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="bold-text">Contract Awarded Date</td>
                                            <td>{{\Carbon\Carbon::parse($data['settlementData']->awarded_date)->format('d M Y')}}</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">File No.</td>
                                            <td>{{$data['settlementData']->file_no}}</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Work Order No.</td>
                                            <td>{{$data['settlementData']->work_order_no}}</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    <p>BID information is not available</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-----------  BID WINNER INFORMATION -------------------------------------------------------------}}

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            BID Winner Information
                        </div>
                        <div class="panel-body">
                            @if($data['acceptedBidderData'])
                                <div class="table-responsive">
                                    <table class="table table-bordered hf">
                                        <tbody>
                                        <tr>
                                            <td class="bold-text">Bidder Name</td>
                                            <td>{{$data['acceptedBidderData']->b_f_name}} {{$data['acceptedBidderData']->b_m_name}} {{$data['acceptedBidderData']->b_l_name}}</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Father's Name</td>
                                            <td>{{$data['acceptedBidderData']->b_father_name}}</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Address / Police Station / Pin</td>
                                            <td>
                                                {{"Address : ".$data['acceptedBidderData']->b_address}},
                                                {{"Police Station : ".$data['acceptedBidderData']->b_police_station}}
                                                {{"PIN  : ".$data['acceptedBidderData']->b_pin}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Mobile No</td>
                                            <td>{{$data['acceptedBidderData']->b_mobile}}</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Email ID</td>
                                            <td>{{$data['acceptedBidderData']->b_email}}</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">PAN</td>
                                            <td>{{$data['acceptedBidderData']->b_pan_no}}</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">GST</td>
                                            <td>{{$data['acceptedBidderData']->b_gst_no}}</td>
                                        </tr>

                                        <tr>
                                            <td class="bold-text">EMD Deposited</td>
                                            <td>
                                                <i class="fa fa-rupee"></i>
                                                {{$data['acceptedBidderData']->ernest_amt}}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    <p>BID winner information is not available</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-----------  ATTACHMENTS -----------------------------------------------------------------------}}

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Attachments Uploaded
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered hf">
                                    <tbody>
                                    @if($data['generalDetail'])
                                        <tr>
                                            <td class="bold-text">Advertisement</td>
                                            <td><a href="{{$imgUrl.$data['generalDetail']->advertisement}}" target="_blank">Click to view</a></td>
                                        </tr>
                                    @endif
                                    @foreach($data['uploadedDoc'] AS $doc)
                                        <tr>
                                            <td class="bold-text">{{$doc->doc_name}}</td>
                                            <td><a href="{{$imgUrl.$doc->attachment_path}}" target="_blank">Click to view</a></td>
                                        </tr>
                                    @endforeach
                                    @if(isset($data['settlementData']->final_report_path))
                                        <tr>
                                            <td class="bold-text">Final Uploaded Comparitive Statement with signatures</td>
                                            <td><a href="{{$imgUrl.$data['settlementData']->final_report_path}}" target="_blank">Click to view</a></td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="tab-pane" id="tab_2">
                <div class="col-md-12 col-sm-12 col-xs-12" style="background-color: #fff;margin-bottom: 40px">
                    <hr/>

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Payment Details
                        </div>
                        <div class="panel-body">
                        @if($data['finalRecordData'] && $data['finalRecordData']->bidding_status==1)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="bold-text">Payment Status</td>
                                    <td>
                                        @if($data['finalRecordData']->payment_completed_status==1)
                                            {{"Completed"}}
                                        @else
                                            {{"Pending"}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold-text">Settlement Amount</td>
                                    <td>₹ {{$data['finalRecordData']->settlement_amt}}</td>
                                </tr>
								
								<tr>
                                    <td class="bold-text">Security Money Deposit @if($data['finalRecordData']->defaulter_status==1)(Defaulter)@endif</td>
                                    <td>₹ {{$data['finalRecordData']->security_deposit_amt}}</td>
                                </tr>
								@if($data['finalRecordData']->forfeited_emd_sharing_status==1)
                                    <tr>
                                        <td class="bold-text">Forfeited EMD of withdrawn bidders</td>
                                        <td>₹ {{$data['finalRecordData']->total_forfeited_emd_amt}}</td>
                                    </tr>
                                @endif
								
                                    
                                

                                </tbody>
                            </table>

                            <table class="table table-bordered">
                                <tbody>
                                <tr class="bg-primary">
                                    <td style="width:40%">Revenue Collection</td>
                                    <td style="width:15%">Amount in Rs.</td>
                                    <td style="width:15%">ZP Share in Rs.</td>
                                    <td style="width:15%">AP Share in Rs.</td>
                                    <td style="width:15%">GP Share in Rs.</td>
                                </tr>
                                <tr>
                                    <td>Total Gap Period Collection</td>
                                    <td class="text-right">₹ {{$data['finalRecordData']->tot_gap_collected_amt}}</td>
                                    <td class="text-right">₹ {{$data['finalRecordData']->tot_gap_zp_share}}</td>
                                    <td class="text-right">₹ {{$data['finalRecordData']->tot_gap_ap_share}}</td>
                                    <td class="text-right">₹ {{$data['finalRecordData']->tot_gap_gp_share}}</td>
                                </tr>
                                <tr>
                                    <td class="bold-text">Total Collection (1st + 2nd + 3rd) Installments</td>
                                    <td class="text-right">₹ {{$data['finalRecordData']->tot_ins_collected_amt}}</td>
                                    <td class="text-right">₹ {{$data['finalRecordData']->tot_ins_zp_share}}</td>
                                    <td class="text-right">₹ {{$data['finalRecordData']->tot_ins_ap_share}}</td>
                                    <td class="text-right">₹ {{$data['finalRecordData']->tot_ins_gp_share}}</td>
                                </tr>

                                

                                

                                @if($data['finalRecordData']->rebate_status==1)
                                    <tr>
                                        <td class="bold-text">Rebate Amount</td>
                                        <td class="text-right">- ₹ {{$data['finalRecordData']->settlement_amt-$data['finalRecordData']->tot_ins_collected_amt}}</td>
                                        <td class="text-right">--</td>
                                        <td class="text-right">--</td>
                                        <td class="text-right">--</td>
                                    </tr>
                                @endif

                                @php
                                  $zp_share=$data['finalRecordData']->tot_gap_zp_share+
                                            $data['finalRecordData']->tot_ins_zp_share+
                                            $data['finalRecordData']->f_emd_zp_share+
                                            $data['finalRecordData']->df_zp_share;

                                  $ap_share=$data['finalRecordData']->tot_gap_ap_share+
                                            $data['finalRecordData']->tot_ins_ap_share+
                                            $data['finalRecordData']->f_emd_ap_share+
                                            $data['finalRecordData']->df_ap_share;

                                  $gp_share=$data['finalRecordData']->tot_gap_gp_share+
                                            $data['finalRecordData']->tot_ins_gp_share+
                                            $data['finalRecordData']->f_emd_gp_share+
                                            $data['finalRecordData']->df_gp_share;

                                  $totalCollection= $zp_share+$ap_share+$gp_share;
                                @endphp


                                <tr class="bg-primary">
                                    <td class="text-right">Total Net Collection</td>
                                    <td class="text-right">₹ {{$totalCollection}}</td>
                                    <td class="text-right">₹ {{$zp_share}}</td>
                                    <td class="text-right">₹ {{$ap_share}}</td>
                                    <td class="text-right">₹ {{$gp_share}}</td>
                                </tr>
                                </tbody>
                            </table>

                            @if($data['finalRecordData']->defaulter_status==1)
                            <h4>Defaulter and Bakijai Details</h4>

                            <table class="table table-bordered" style="border-left:2px solid red">
                                <tbody>
                                <tr class="bg-primary">
                                    <td style="width:40%">Defaulter Details</td>
                                    <td style="width:15%">Settled Amount <br/> (in Rs.)</td>
                                    <td style="width:15%">Total Collection from Installments <br/> (in Rs.)</td>
                                    <td style="width:15%">Defaulted Amount <br/> (in Rs.)</td>
                                    <td style="width:15%">Bakijari Remarks</td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><span class="bold-text"> Name :</span> {{$data['acceptedBidderData']->b_f_name}} {{$data['acceptedBidderData']->b_m_name}} {{$data['acceptedBidderData']->b_l_name}}</p>

                                        <p><span class="bold-text"> Father's Name :</span> {{$data['acceptedBidderData']->b_father_name}}</p>

                                        <p><span class="bold-text"> PAN :</span> {{$data['acceptedBidderData']->b_pan_no}}</p>
                                        <p>
                                            <span class="bold-text"> Address :</span>
                                            {{$data['acceptedBidderData']->b_address}},
                                            {{"Police Station : ".$data['acceptedBidderData']->b_police_station}}
                                            {{"PIN  : ".$data['acceptedBidderData']->b_pin}}
                                        </p>
                                    </td>
                                    <td class="text-right">
                                        ₹ {{$data['finalRecordData']->settlement_amt}}
                                    </td>
                                    <td class="text-right">
                                        ₹ {{$data['finalRecordData']->tot_ins_collected_amt}}
                                    </td>

                                    <td class="text-right">
                                        ₹ {{$data['finalRecordData']->settlement_amt-($data['finalRecordData']->tot_ins_collected_amt)}}
                                    </td>

                                    <td class="">
										{{$data['finalRecordData']->bakijai_details}}
                                    </td>
                                </tr>
                            </table>
                            @endif
                        </div>
                        @else
                            <div class="alert alert-danger">
                                <p>Payment information is not available</p>
                            </div>
                        @endif
                    </div>
                </div>
</div>

                    {{--<div class="panel panel-primary">
                        <div class="panel-heading">
                            General Information
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                             <table class="table table-bordered">
                                    <tbody>
                                        <tr class="bold-color">
                                            <td></td>
                                            <td>Amount</td>
                                            <td>ZP Share</td>
                                            <td>AP Share</td>
                                            <td>GP Share</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">First Installment (Partial Payment)</td>
                                            <td>₹ 35,000.00</td>
                                            <td>₹ 7,000.00</td>
                                            <td>₹ 14,000.00</td>
                                            <td>₹ 14,000.00</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Second Installment (Partial Payment)</td>
                                            <td>₹ 35,000.00</td>
                                            <td>₹ 7,000.00</td>
                                            <td>₹ 14,000.00</td>
                                            <td>₹ 14,000.00</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Third Installment (Full Payment)</td>
                                            <td>₹ 20,000.00</td>
                                            <td>₹ 4,000.00</td>
                                            <td>₹ 8,000.00</td>
                                            <td>₹ 8,000.00</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Forfeited Earnest Money Deposit</td>
                                            <td>₹ 20,000.00</td>
                                            <td>NA</td>
                                            <td>NA</td>
                                            <td>NA</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Security Money Deposit</td>
                                            <td>₹ 30,000.00</td>
                                            <td>NA</td>
                                            <td>NA</td>
                                            <td>NA</td>
                                        </tr>
                                        <tr>
                                    </tbody>
                            </table>
                        </div>
                        </div>
                    </div>--}}
                </div>
            </div>
            {{--<div class="tab-pane" id="4">
                      <div  id="printDiv" class="table-responsive col-md-12" style="background-color: #fff;">
                             <table class="table table-bordered">
                                <tr>
                                    <th style="text-align: center;" colspan="4"><h3>BID EVALUATION REPORT</h3><br>
                                    District: Jorhat>Zila Parishad: Jorhat>Anchalik Parishad: Titabor>Gram Panchayat: Birpur>Village: Kuhipat
                                    </th>
                                </tr>
                                    <tbody>
                                    <tr>
                                    <td colspan="4"><b>ASSET INFORMATION:</b></td>
                                    </tr>
                                        <tr>
                                            <td class="bold-text">Asset Name</td>
                                            <td>{{$nonTaxAssets->asset_name}}</td>
                                            <td class="bold-text">Asset Code</td>
                                            <td>{{$nonTaxAssets->asset_code}}</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Location</td>
                                            <td>{{$district_name}} {{$ap_name}} {{$gp_name}}</td>
                                            <td class="bold-text">Listing Date</td>
                                            <td>{{$nonTaxAssets->asset_listing_date}}</td>
                                        </tr>
                                        <tr>
                                            <td class="bold-text">Brief Description</td>
                                            <td colspan="3">{{$nonTaxAssets->b_desc}}</td>
                                        </tr>
                                        <tr>
                                    <td colspan="4"><b>BID  INFORMATION:</b></td>
                                    </tr>
                                         <tr>
                                                <td class="bold-text">Bidder Name</td>
                                                <td>Rahul Hazarika</td>
                                                <td class="bold-text">Father's Name</td>
                                                <td>Debanan Hazarika</td>
                                            </tr>
                                            <tr>
                                                <td class="bold-text">Address</td>
                                                <td>Dehajaan, Majuli, Assam</td>
                                                <td class="bold-text">Mobile No</td>
                                                <td>+91-9876543210</td>
                                            </tr>
                                            <tr>
                                                <td class="bold-text">Email ID</td>
                                                <td>rahul99@gmail.com</td>
                                                <td class="bold-text">PAN</td>
                                                <td>AS123PAN356D</td>
                                            </tr>
                                        <tr>
                                            <td class="bold-text">BID Value</td>
                                            <td>₹ 1,00,000.00</td>
                                            <td class="bold-text">Tender Date</td>
                                            <td>25th May 2018</td>
                                        </tr>
                                            <tr>
                                                <td class="bold-text">Withdrawn Bidders</td>
                                                <td>10</td>
                                                <td class="bold-text">No. of BID Rejected</td>
                                                <td>5</td>
                                            </tr>
                                            <tr>
                                                <td class="bold-text">Forfieted Withdrawn Bidders</td>
                                                <td>1</td>
                                                <td class="bold-text">Forfieted Earnest Money Deposit</td>
                                                <td>₹ 30,000.00</td>
                                            </tr>
                                            <tr>
                                                <td class="bold-text">Settled Amount</td>
                                                <td>₹ 1,00,000.00</td>
                                                <td class="bold-text">Contract Awarded Date</td>
                                                <td>16/July/2018</td>
                                            </tr>
                                            <tr>
                                                <td class="bold-text">File No.</td>
                                                <td>AS-JOR-123</td>
                                                <td class="bold-text">Work Order No.</td>
                                                <td>AS-JOR-123</td>
                                            </tr>
                                    </tbody>
                            </table>
                        </div>
                        <button id="doPrint">Print</button>
                    </div>--}}
        </div>
    </div>

@endsection

@section('custom_js')
    <script>
        /*document.getElementById("doPrint").addEventListener("click", function() {
         var printContents = document.getElementById('printDiv').innerHTML;
         var originalContents = document.body.innerHTML;
         document.body.innerHTML = printContents;
         window.print();
         document.body.innerHTML = originalContents;
         });*/
    </script>
@endsection
