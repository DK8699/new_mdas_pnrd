<!DOCTYPE html>
<html>
<head>
    <title>OSR</title>
</head>
<body style="font-size: 11px;">

    <style type="text/css">
        table tr td{
            border-color: #DCDCDC;
            border-style: solid;
            border-width: 0px 1px 1px 0;
        }
        table tr th{
            border-color: #DCDCDC;
            border-style: solid;
            border-width: 0px 1px 1px 0;
        }

        .head{
            width: 100%;
        }
        .head p{
            text-align: center;
        }

        .txt-upper{
            text-transform: uppercase;
        }

        .left{
           text-align: left;
        }

        td,th {
            padding-left: 5px;
            padding-right: 5px;
        }

        p{  margin:3px 5px 3px 5px;  }

        .table{
            width: 100%;
            margin-bottom: 0px;
            border-top: 1px solid #DCDCDC;
            border-left: 1px solid #DCDCDC;
            font-size: 14px;
        }
        .table1{
            width: 100%;
            margin-bottom: 0px;
            border-top: 1px solid #DCDCDC;
            border-left: 1px solid #DCDCDC;
            font-size: 14px;
        }
        .table2 td{
            border: none;
        }
        .table td{
            width:25%;
        }
        .table .main-heading{
            font-size: 16px;
            font-weight: 500;
        }
        .table .label, .table1 .label{
            font-size: 14px;
            font-weight: 700;
        }

        h1{
            margin-top:30px;
        }
        
          .page-break { 
            page-break-before: always;
            }

    </style>

    <table cellpadding="0" cellspacing="0" class="head">
        <tr>
            <td style="text-align: center;border:none">
                <img src="{{asset('mdas_assets/images/gov_assam_b.png')}}" alt="LOGO" style="width: 60px;"/>
            </td>
        </tr>
        <tr>
            <td style="border: 0px;">
                <p style="font-size:20px;" class="txt-upper">Bidding Report Of Non Tax Revenue Resource</p>
                <p style="font-size:14px;font-weight: 500">For the financial year : {{$osrFyYear->fy_name}}</p>
                <p class="txt-upper" style="font-size:14px;font-weight: 500">{{$assetData->asset_name}}</p>
            </td>
        </tr>
    </table>

    <hr style="border: 1px solid black" />

    <h1 style="font-weight: 500">Asset Summary</h1>

    <table cellspacing="0" class="table" style="margin-top:20px;">
        <tr>
            <td><p class="label">Asset Code</p></td>
            <td><p class="txt-upper">{{$assetData->asset_code}}</p></td>
            <td><p class="label">Asset Name</p></td>
            <td><p class="txt-upper">{{$assetData->asset_name}}</p></td>
        </tr>
        <tr>
            <td><p class="label">Zila Parishad</p></td>
            <td><p class="txt-upper">{{$assetData->zila_parishad_name}}</p></td>
            <td><p class="label">Anchalik Panchayat</p></td>
            <td><p class="txt-upper">{{$assetData->anchalik_parishad_name}}</p></td>

        </tr>
        <tr>
            <td><p class="label">Gram Panchayat</p></td>
            <td><p class="txt-upper">{{$assetData->gram_panchayat_name}}</p></td>
            <td><p class="label">Village</p></td>
            <td><p class="txt-upper">{{$assetData->village_name}}</p></td>
        </tr>
    </table>

    <h1 style="font-weight: 500">Bidding Summary</h1>

    <table cellspacing="0" class="table" style="margin-top:20px">
        <tr>
            <td><p class="label">Government Value</p></td>
            <td><p class="txt-upper money_txt">{{$generalDetail->govt_value}}</p></td>
            <td><p class="label">Date of Tender</p></td>
            <td><p>{{Carbon\Carbon::parse($generalDetail->date_of_tender)->format('d M Y')}}</p></td>
        </tr>
        <tr>
            <td><p class="label">Total Bidders</p></td>
            <td><p class="txt-upper">{{$totalBidder}}</p></td>
            <td><p class="label">Asset Managed By</p></td>
            <td><p class="txt-upper money_txt">{{$settlementData->managed_by}}</p></td>
        </tr>
            
        <tr>
            <td><p class="label">Total Bidder Forfeited</p></td>
            <td><p class="txt-upper">{{count($forfeitedBidderData)}}</p></td>
            <td><p class="label">Forfeited Amount</p></td>
            <td><p class="txt-upper">
                @php
                    $totalForfeitedAmt=0;
                    foreach($forfeitedBidderData AS $li)
                    {
                        $totalForfeitedAmt=$totalForfeitedAmt+$li->ernest_amt;
                    } 
                @endphp
                {{$totalForfeitedAmt}}
                </p>
            </td>
            <!--<td><p>{{Carbon\Carbon::parse($settlementData->awarded_date)->format('d M Y')}}</p></td>-->
        </tr>
    </table>
    <h1 style="font-weight: 500">Settlement Summary</h1>
     
    <table cellspacing="0" class="table" style="margin-top:20px">
        <tr>
            <td><p class="label">Work Order No.</p></td>
            <td><p class="txt-upper">{{$settlementData->work_order_no}}</p></td>
            <td><p class="label">File Number</p></td>
            <td><p>@if(isset($settlementData->file_no)){{$settlementData->file_no}}@endif</p></td>
        </tr>
        <tr>
            <td><p class="label">Settled Amount</p></td>
            <td><p class="txt-upper money_txt">{{$acceptedBidderData->bidding_amt}}</p></td>
            <td><p class="label">Settlement Date</p></td>
            <td><p>{{Carbon\Carbon::parse($settlementData->awarded_date)->format('d M Y')}}</p></td>
        </tr>
    </table>

    {{--<h1 style="font-weight: 500">Selected Bidder Information</h1>

    <table cellspacing="0" class="table" style="margin-top:20px">
        <tr>
            <td>
                @if($acceptedBidderData->b_pic_path)
                    <img src="{{$imgUrl.$acceptedBidderData->b_pic_path}}" alt="BIDDER IMAGE" style="width:110px;max-height: 150px"/>
                @else
                    <img src="{{asset('mdas_assets/images/user_add.png')}}" alt="BIDDER IMAGE" style="width:110px;max-height: 150px"/>
                @endif
            </td>
            <td colspan="3">
                <img src="{{$imgUrl.$acceptedBidderData->b_pan_path}}" alt="BIDDER IMAGE" style="width:250px;max-height: 150px"/>
            </td>
        </tr>
        <tr>
            <td><p class="label">Name</p></td>
            <td><p class="txt-upper">{{$acceptedBidderData->b_f_name." ".$acceptedBidderData->b_m_name." ".$acceptedBidderData->b_l_name}}</p></td>
            <td><p class="label">Father's Name</p></td>
            <td><p class="txt-upper">{{$acceptedBidderData->b_father_name}}</p></td>
        </tr>
        <tr>
            <td><p class="label">Gender</p></td>
            <td><p>{{$acceptedBidderData->gender_name}}</p></td>
            <td><p class="label">Mobile No.</p></td>
            <td><p>{{$acceptedBidderData->b_mobile}}</p></td>
        </tr>
        <tr>
            <td><p class="label">Email Id</p></td>
            <td><p>@if($acceptedBidderData->b_email){{$acceptedBidderData->b_email}}@else{{"NA"}}@endif</p></td>
            <td><p class="label">Alt Mobile No</p></td>
            <td><p>@if($acceptedBidderData->b_email){{$acceptedBidderData->b_alt_mobile}}@else{{"NA"}}@endif</p></td>
        </tr>
        <tr>
            <td><p class="label">PAN</p></td>
            <td><p>{{$acceptedBidderData->b_pan_no}}</p></td>
            <td><p class="label">GST No.</p></td>
            <td><p>{{$acceptedBidderData->b_gst_no}}</p></td>
        </tr>
         <tr>
            <td><p class="label">Bidding Amount</p></td>
            <td><p>{{$acceptedBidderData->bidding_amt}}</p></td>
            <td><p class="label">Security Amount</p></td>
            <td><p>{{$acceptedBidderData->security_amt}}</p></td>
        </tr>
        <tr>
            <td><p class="label">Address:</p></td>
            <td colspan="3"><p>{{$acceptedBidderData->b_address}}, PIN: {{$acceptedBidderData->b_pin}}, PS: {{$acceptedBidderData->b_police_station}}</p></td>
        </tr>
    </table>--}}

    <h1 style="font-weight: 500">Comparative Statement</h1>

    <table cellspacing="0" class="table1" style="margin-top:20px;">
        <tr style="background-color: deepskyblue">
            <td><p class="label">SL</p></td>
            <td><p class="label">Bidder Name</p></td>
            <td><p class="label">PAN</p></td>
            <td><p class="label">Bidding Amount</p></td>
            <td><p class="label">Security Amount</p></td>
            <td><p class="label">Status</p></td>
            <td><p class="label">Remarks</p></td>
        </tr>
        @php $i=1; @endphp
        @foreach($bidderDetail AS $bidder)
            <tr @if($bidder->bidder_status==1) style="background-color:lightgreen" @endif>
                <td style="width:2%">{{$i}}</td>
                <td style="width:23%"><p class="txt-upper">{{$bidder->b_f_name." ".$bidder->b_m_name." ".$bidder->b_l_name}}</p></td>
                <td style="width:14%"><p>{{$bidder->b_pan_no}}</p></td>
                <td style="width:13%"><p class="money_txt">Rs. {{$bidder->bidding_amt}}</p></td>
                <td style="width:10%"><p class="money_txt">Rs. {{$bidder->security_amt}}</p></td>
                <td style="width:9%">
                    @if($bidder->bidder_status==0)
                        <span class="badge badge-red">Rejected</span>
                    @elseif($bidder->bidder_status==1)
                        <span class="badge badge-green">Accepted</span>
                    @else
                        <span class="badge badge-green">Withdrawn</span>
                    @endif
                </td>
                <td style="width:30%">
                    @if($bidder->bidder_status==0)
                        @if($bidder->osr_master_bidder_remark_id==1)
                            {{$bidder->other_remark}}
                        @else
                            {{$bidder->remark}}
                        @endif
                    @endif
                </td>
            </tr>
        @php $i++; @endphp
        @endforeach
    </table>

<div class="page-break">
    <p style="margin-top:30px">
    ******************************************************FOR OFFICE USE ONLY*****************************************************
    </p>
    @if($settlementData->managed_by == "ZP")
    <table class="table2" style="margin-top:15px">
            <tr>
                <td><p>1.Name</p></td>
                <td><p>.........................................................</p></td>
                <td><p>1.Name</p></td>
                <td><p>.........................................................</p></td>
                <td><p>1.Name</p></td>
                <td><p>.........................................................</p></td>
                
            </tr>
            <tr>
                <td><p>2.Phone.</p></td>
                <td><p>.........................................................</p></td>  
                <td><p>2.Phone.</p></td>
                <td><p>.........................................................</p></td> 
                <td><p>2.Phone.</p></td>
                <td><p>.........................................................</p></td>             
            </tr>
    </table>
    <table class="table2" style="margin-top:15px">
            <tr>
                <td colspan="2">
                    <p>...............................................................................</p>
                    <h3 style="text-align:center">(Signature of Chairman)</h3>
                </td>
                <td colspan="2">
                    <p>...............................................................................</p>
                    <h3 style="text-align:center">(Signature of Finance Officer)</h3>
                </td>
                <td colspan="2">
                    <p>...............................................................................</p>
                    <h3 style="text-align:center">(Signature of CEO)</h3>
                </td>
           </tr>
        
    </table>
    <table class="table2" style="margin-top:15px">
            <tr>
                <td><p>1.Name</p></td>
                <td><p>.........................................................</p></td>
                <td><p>1.Name</p></td>
                <td><p>.........................................................</p></td>
                <td><p>1.Name</p></td>
                <td><p>.........................................................</p></td>
            </tr>
            <tr>
                <td><p>2.Phone.</p></td>
                <td><p>.........................................................</p></td>
                <td><p>2.Phone.</p></td>
                <td><p>.........................................................</p></td>
                <td><p>2.Phone.</p></td>
                <td><p>.........................................................</p></td>
            </tr>
    </table>
    <table class="table2" style="margin-top:15px">
            <tr>
                <td colspan="2">
                    <p>...............................................................................</p>
                    <h3 style="text-align:center">(Signature of Member)</h3>
                </td>
                <td colspan="2">
                    <p>...............................................................................</p>
                    <h3 style="text-align:center">(Signature of Member)</h3>
                </td>
                <td colspan="2">
                    <p>...............................................................................</p>
                    <h3 style="text-align:center">(Signature of Member)</h3>
                </td>
           </tr>
        
    
    </table>
    
    @elseif($settlementData->managed_by == "AP")
    <table class="table2" style="margin-top:15px">
            <tr>
                <td><p>1.Name</p></td>
                <td><p>.........................................................</p></td>
                <td><p>1.Name</p></td>
                <td><p>.........................................................</p></td>
                <td><p>1.Name</p></td>
                <td><p>.........................................................</p></td>
                
            </tr>
            <tr>
                <td><p>2.Phone.</p></td>
                <td><p>.........................................................</p></td>  
                <td><p>2.Phone.</p></td>
                <td><p>.........................................................</p></td> 
                <td><p>2.Phone.</p></td>
                <td><p>.........................................................</p></td>             
            </tr>
    </table>
    <table class="table2" style="margin-top:15px">
            <tr>
                <td colspan="2">
                    <p>...............................................................................</p>
                    <h3 style="text-align:center">(Signature of AP Chairman)</h3>
                </td>
                <td colspan="2">
                    <p>...............................................................................</p>
                    <h3 style="text-align:center">(Signature of BPO Member Secretary)</h3>
                </td>
                <td colspan="2">
                    <p>...............................................................................</p>
                    <h3 style="text-align:center">(Signature of Executive Officer)</h3>
                </td>
           </tr>
        
    </table>
     <table class="table2" style="margin-top:15px">
            <tr>
                <td><p>1.Name</p></td>
                <td><p>.........................................................</p></td>
                <td><p>1.Name</p></td>
                <td><p>.........................................................</p></td>
                <td><p>1.Name</p></td>
                <td><p>.........................................................</p></td>
            </tr>
            <tr>
                <td><p>2.Phone.</p></td>
                <td><p>.........................................................</p></td>
                <td><p>2.Phone.</p></td>
                <td><p>.........................................................</p></td>
                <td><p>2.Phone.</p></td>
                <td><p>.........................................................</p></td>
            </tr>
    </table>
    <table class="table2" style="margin-top:15px">
            <tr>
                <td colspan="2">
                    <p>...............................................................................</p>
                    <h3 style="text-align:center">(Signature of Member)</h3>
                </td>
                <td colspan="2">
                    <p>...............................................................................</p>
                    <h3 style="text-align:center">(Signature of Member)</h3>
                </td>
                <td colspan="2">
                    <p>...............................................................................</p>
                    <h3 style="text-align:center">(Signature of Member)</h3>
                </td>
           </tr>
        
    
    </table>
    
    @else
    <table class="table2" style="margin-top:15px">
            <tr>
                <td><p>1.Name</p></td>
                <td><p>.........................................................</p></td>
                <td><p>1.Name</p></td>
                <td><p>.........................................................</p></td>
                
            </tr>
            <tr>
                <td><p>2.Phone.</p></td>
                <td><p>.........................................................</p></td>  
                <td><p>2.Phone.</p></td>
                <td><p>.........................................................</p></td> 
    </table>
    <table class="table2" style="margin-top:15px">
            <tr>
                <td colspan="2">
                    <p>...............................................................................</p>
                    <h3>(Signature of Chairman)</h3>
                </td>
                <td colspan="2">
                    <p>...............................................................................</p>
                    <h3>(Signature of GP Secretary)</h3>
                </td>
           </tr>
    </table>
     <table class="table2" style="margin-top:15px">
            <tr>
                <td><p>1.Name</p></td>
                <td><p>.........................................................</p></td>
                <td><p>1.Name</p></td>
                <td><p>.........................................................</p></td>
                <td><p>1.Name</p></td>
                <td><p>.........................................................</p></td>
            </tr>
            <tr>
                <td><p>2.Phone.</p></td>
                <td><p>.........................................................</p></td>
                <td><p>2.Phone.</p></td>
                <td><p>.........................................................</p></td>
                <td><p>2.Phone.</p></td>
                <td><p>.........................................................</p></td>
            </tr>
    </table>
    <table class="table2" style="margin-top:15px">
            <tr>
                <td colspan="2">
                    <p>...............................................................................</p>
                    <h3 style="text-align:center">(Signature of Member)</h3>
                </td>
                <td colspan="2">
                    <p>...............................................................................</p>
                    <h3>(Signature of Member)</h3>
                </td>
                <td colspan="2">
                    <p>...............................................................................</p>
                    <h3>(Signature of Member)</h3>
                </td>
           </tr>
        
    
    </table>
</div>
    
    
    @endif
    
    
    
</body>
</html>