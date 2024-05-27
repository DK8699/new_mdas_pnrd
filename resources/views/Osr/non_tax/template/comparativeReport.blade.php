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
    .table td{
        width:25%;
    }
    .table .main-heading{
        font-size: 16px;
        font-weight: 500;
    }
    .table .label{
        font-size: 14px;
        font-weight: 700;
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
            <p style="font-size:20px;" class="txt-upper">Detail Bidding Report Of Non Tax Revenue Resource</p>
            <p style="font-size:14px;font-weight: 500">For the financial year : {{$osrFyYear->fy_name}}</p>
            <p class="txt-upper" style="font-size:14px;font-weight: 500">{{$assetData->asset_name}}</p>
        </td>
    </tr>
</table>

<hr style="border: 1px solid black" />
 <h1 style="font-weight: 500">Asset Summary</h1>
<table cellspacing="0" class="table" style="margin-top:20px">
   <!--  <tr>
        <td colspan="4" class="main-heading"><p>ASSET SUMMARY</p></td>
    </tr> -->
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

 
        <h1 style="font-weight: 500">Comparative Statements</h1>
   

    @php $i=1; @endphp
    @foreach($bidderDetail AS $bidder)
    <table cellspacing="0" class="table" style="margin-top:20px">
        <tr>
            <td colspan="4" class="main-heading"><p>BIDDER {{$i}}</p></td>
        </tr>
        <tr>
            <td>
                @if($bidder->b_pic_path)
                    <img src="{{$imgUrl.$bidder->b_pic_path}}" alt="BIDDER IMAGE" style="width:110px;max-height: 150px"/>
                @else
                    <img src="{{asset('mdas_assets/images/user_add.png')}}" alt="BIDDER IMAGE" style="width:110px;max-height: 150px"/>
                @endif
            </td>
            <td colspan="3">
                <img src="{{$imgUrl.$bidder->b_pan_path}}" alt="BIDDER IMAGE" style="width:250px;max-height: 150px"/>
            </td>
        </tr>
        <tr>
            <td><p class="label">Name</p></td>
            <td><p class="txt-upper">{{$bidder->b_f_name." ".$bidder->b_m_name." ".$bidder->b_l_name}}</p></td>
            <td><p class="label">Father's Name</p></td>
            <td><p class="txt-upper">{{$bidder->b_father_name}}</p></td>
        </tr>
        <tr>
            <td><p class="label">Gender</p></td>
            <td><p>{{$bidder->gender_name}}</p></td>
            <td><p class="label">Mobile No.</p></td>
            <td><p>{{$bidder->b_mobile}}</p></td>
        </tr>
        <tr>
            <td><p class="label">Email Id</p></td>
            <td><p>@if($bidder->b_email){{$bidder->b_email}}@else{{"NA"}}@endif</p></td>
            <td><p class="label">Alt Mobile No</p></td>
            <td><p>@if($bidder->b_email){{$bidder->b_alt_mobile}}@else{{"NA"}}@endif</p></td>
        </tr>
        <tr>
            <td><p class="label">PAN</p></td>
            <td><p>{{$bidder->b_pan_no}}</p></td>
            <td><p class="label">GST No.</p></td>
            <td><p>{{$bidder->b_gst_no}}</p></td>
        </tr>
        <tr>
            <td><p class="label">Bidding Status</p></td>
            <td>
                @if($bidder->bidder_status==0)
                    <span class="badge badge-red">Rejected</span>
                @else
                    <span class="badge badge-green">Accepted</span>
                @endif
            </td>
            <td><p class="label">Remark</p></td>
            <td>
                @if($bidder->bidder_status==0)
                    @if($bidder->osr_master_bidder_remark_id==1)
                        {{$bidder->other_remark}}
                    @else
                        {{$bidder->remark}}
                    @endif
                @endif
            </td>
        </tr>
        <tr>
            <td><p class="label">Bidding Amount</p></td>
            <td><p>{{$bidder->bidding_amt}}</p></td>
            <td><p class="label">Security Amount</p></td>
            <td><p>{{$bidder->security_amt}}</p></td>
        </tr>
        <tr>
            <td><p class="label">Address:</p></td>
            <td colspan="3"><p>{{$bidder->b_address}}, PIN: {{$bidder->b_pin}}, PS: {{$bidder->b_police_station}}</p></td>
        </tr>
    </table>
    @php $i++; @endphp
    @endforeach
</body>
</html>