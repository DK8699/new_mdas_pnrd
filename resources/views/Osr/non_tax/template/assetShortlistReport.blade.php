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
                border:none;
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
            <td style="padding-left:310px; border:none;">
                <img src="{{asset('mdas_assets/images/gov_assam_b.png')}}" alt="LOGO" style="width: 80px;"/>
            </td>
			
        </tr>
        <tr>
            <td style="border: 0px;">
                <p style="font-size:20px;" class="txt-upper">OSR ASSET REPORT(Non Tax Revenue Source)</p>
                <p style="font-size:14px;font-weight: 500; ">For the financial year : {{$osrFyYear->fy_name}}</p>
                <p class="txt-upper" style="font-size:14px;font-weight: 500; ">District Name: {{$zpData->zila_parishad_name}} </p>
            </td>
        </tr>
    </table>
    <hr style="border: 1px solid black" />
    
    <table cellspacing="0" class="table" style="margin-top:20px;">
        <tr>
            <td><p class="label">Total Asset</p></td>
            <td><p class="txt-upper">{{$tot_asset}}</p></td>
            <td><p class="label">Asset Shortlisted</p></td>
            <td><p class="txt-upper">{{$tot_asset_short}}</p></td>
        </tr>
        <tr>
            <td><p class="label">ZP Asset</p></td>
            <td><p class="txt-upper">
                @if(isset($level_wise_short['ZP']))
                {{$level_wise_short['ZP']}}
                @else
                {{'0'}}
                @endif
                </p></td>
            <td><p class="label">AP Asset</p></td>
            <td><p class="txt-upper">
                @if(isset($level_wise_short['AP']))
                {{$level_wise_short['AP']}}
                @else
                {{'0'}}
                @endif
                </p></td>
        </tr>
        <tr>
            <td><p class="label">GP Asset</p></td>
            <td><p class="txt-upper">
                @if(isset($level_wise_short['GP']))
                {{$level_wise_short['GP']}}
                @else
                {{'0'}}
                @endif
                </p>
            </td>
            <td><p class="label">Asset Not Selected</p></td>
            <td><p class="txt-upper">
                @if(isset($level_wise_short['NA']))
                {{$level_wise_short['NA']}}
                @else
                {{'0'}}
                @endif
                </p></td>
        </tr>
    </table>
    
    <h1 style="font-weight: 500">Assets under ZP</h1>
    <table cellspacing="0" class="table1" style="margin-top:20px;">
        <tr>
            <td><p class="label">SL</p></td>
            <td><p class="label">Category</p></td>
            <td><p class="label">Asset Name</p></td>
            <td><p class="label">Asset Code</p></td>
            <td><p class="label">Listing Date</p></td>
        </tr>
        @php $i=1; @endphp
            @foreach($assetShortlistListUnderZP as $li)
            <tr>
                <td style="width:2%">{{$i}}</td>
                <td style="width:23%"><p class="txt-upper">{{$li->branch_name}}</p></td>
                <td style="width:23%"><p class="txt-upper">{{$li->asset_name}}</p></td>
                <td style="width:14%"><p>{{$li->asset_code}}</p></td>
                <td style="width:13%"><p>{{Carbon\Carbon::parse($li->asset_listing_date)->format('d M Y')}}</p></td>
            </tr>
        
        @php $i++; @endphp
        @endforeach
    </table>
    
    <h1 style="font-weight: 500">Assets under AP</h1>
    <table cellspacing="0" class="table1" style="margin-top:20px;">
        <tr>
            <td><p class="label">SL</p></td>
            <td><p class="label">Category</p></td>
            <td><p class="label">Asset Name</p></td>
            <td><p class="label">Asset Code</p></td>
            <td><p class="label">Listing Date</p></td>
        </tr>
        @php $i=1; @endphp
            @foreach($assetShortlistListUnderAP as $li)
            <tr>
                <td style="width:2%">{{$i}}</td>
                <td style="width:23%"><p class="txt-upper">{{$li->branch_name}}</p></td>
                <td style="width:23%"><p class="txt-upper">{{$li->asset_name}}</p></td>
                <td style="width:14%"><p>{{$li->asset_code}}</p></td>
                <td style="width:13%"><p>{{Carbon\Carbon::parse($li->asset_listing_date)->format('d M Y')}}</p></td>
            </tr>
        
        @php $i++; @endphp
        @endforeach
    </table>
    
    
    <h1 style="font-weight: 500">Assets under GP</h1>
    <table cellspacing="0" class="table1" style="margin-top:20px;">
        <tr>
            <td><p class="label">SL</p></td>
            <td><p class="label">Category</p></td>
            <td><p class="label">Asset Name</p></td>
            <td><p class="label">Asset Code</p></td>
            <td><p class="label">Listing Date</p></td>
        </tr>
        @php $i=1; @endphp
            @foreach($assetShortlistListUnderGP as $li)
            <tr>
                <td style="width:2%">{{$i}}</td>
                <td style="width:23%"><p class="txt-upper">{{$li->branch_name}}</p></td>
                <td style="width:23%"><p class="txt-upper">{{$li->asset_name}}</p></td>
                <td style="width:14%"><p>{{$li->asset_code}}</p></td>
                <td style="width:13%"><p>{{Carbon\Carbon::parse($li->asset_listing_date)->format('d M Y')}}</p></td>
            </tr>
        
        @php $i++; @endphp
        @endforeach
    </table>
    
    <h1 style="font-weight: 500">Not Selelcted Assets</h1>
    <table cellspacing="0" class="table1" style="margin-top:20px;">
        <tr>
            <td><p class="label">SL</p></td>
            <td><p class="label">Category</p></td>
            <td><p class="label">Asset Name</p></td>
            <td><p class="label">Asset Code</p></td>
            <td><p class="label">Listing Date</p></td>
            <td><p class="label">Reasons</p></td>
        </tr>
        @php $i=1; @endphp
            @foreach($assetShortlistListUnderNA as $li)
                <tr>
                    <td style="width:2%">{{$i}}</td>
                    <td style="width:23%"><p class="txt-upper">{{$li->branch_name}}</p></td>
                    <td style="width:23%"><p class="txt-upper">{{$li->asset_name}}</p></td>
                    <td style="width:14%"><p>{{$li->asset_code}}</p></td>
                    <td style="width:13%"><p>{{Carbon\Carbon::parse($li->asset_listing_date)->format('d M Y')}}</p></td>
                    <td style="width:14%"><p>{{$li->reason}}</p></td>
                </tr>
        @php $i++; @endphp
        @endforeach
        
    </table>
    
   <div class="page-break">
    <table class="table" style="margin-top:20px; border:1px solid black;">
            <tr>
               <td><p class="label">Remarks</p></td>
            </tr>
            <tr>
                <td rowspan="30"></td>
            </tr>
    </table>
    <table class="table2" style="margin-top:100px; margin-left:350px">
            <tr>
                <td colspan="2">
                    <p>...............................................................................</p>
                    <h3 style="text-align:center">(Signature of CEO)</h3>
                </td>
            </tr>
    </table>
    </div>
</body>
</html>