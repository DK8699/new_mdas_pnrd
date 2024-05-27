@php
    $page_title="priMenu";
@endphp

@extends('layouts.app_admin')

@section('custom_css')
    <link href="https://fonts.googleapis.com/css?family=Old+Standard+TT&display=swap" rel="stylesheet">
    <script src="{{asset('mdas_assets/Chart.js-2.8.0/dist/Chart.min.js')}}"></script>
    <script src="{{asset('mdas_assets/Chart.js-2.8.0/samples/utils.js')}}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.css" rel="stylesheet" type="text/css"/>
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    
    <link href="https://fonts.googleapis.com/css?family=Orbitron&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Sarpanch&display=swap" rel="stylesheet">
    <style>
        .panel-body {
            padding: 13px;
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
        body {
            margin: 0px;
            padding: 0px;
            background-color: #fff;
        }
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding: 7px;
            line-height: 1.42857143;
            vertical-align: top;
            border-top: 1px solid #ddd;
        }

        .mt40{
            margin-top: 40px;
        }

        .card{
            border:1px solid #ff770f;
            background-color: #f3f2f2;
            box-shadow:0 1px 5px rgba(0, 0, 0, 0.1);
        }
        .card-header{
            background-color: #6b133d;
            color: #fff;
            font-size: 20px;
            text-align: center;
            font-weight: 700;
            padding:5px;
        }
        .card-body{
            padding:10px;
            text-align: center;
        }

        .card .number{
            font-weight: 900;
            font-size: 40px;
            color: #f13333;
        }
        .card p{
            color: #f13333;
        }

        .card p{
            font-weight: 900;
            font-size: 20px;
        }
        .tr-row {
            background-color: #ebe7e7;
            color: rgb(255, 118, 15);
            font-weight: 600;
        }
        .panel-primary>.panel-heading {
            background-color: rgb(255, 118, 15);
            background-image: linear-gradient(to right, #FF5722 , #FF5722);
        }
        .bold-color {
            color: darkviolet;
            font-weight: 600;
        }
        .panel-primary {
            border-color: #F44336;
        }
        .panel-primary>.panel-heading {
            border-color: transparent;
        }
        .gray-back {
            background-color: #f3f2f2;
            border-bottom: solid 1px orangered;
        }
        .green-back {
            background-color: #a4114c;
            color: #fff
        }
        .bold-text {
            font-weight: 600;
        }
		
		table.data-header tr.data-header-head{
			font-size:11px;
		}

        .head-txt{
            font-size: 11px;
        }
		
		

    </style>
@endsection

@section('content')
    {{--<div class="row">
        <ol class="breadcrumb" style="margin-bottom: 0px">
            <li><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        </ol>
    </div>--}}


    <div class="container-fluid" style=" padding-bottom: 40px; padding-top: 40px;">
        <!----------------- CARDS -------------------------->
        <div class="container">
            <!--<div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <p class="text-center" style="font-family: 'Livvic', sans-serif; font-size:30px; color: #ff9000;font-weight: 900;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black">Non Tax Revenue Resources Three Year's Collection</p>
                </div>
            </div>-->
            

            
           <div class="row">
                <div class="col-md2"></div>
                <div class="col-md10">
                    <h2 style="text-align: center;color: #6b133d;font-family: 'Old Standard TT', serif; font-weight: 700">Own Source of Revenue</h2>
                </div>
                <div class="col-md-2" style="float: right">
                    <div class="form-group">
                        <label style="color: #6b133d">Select Financial Year</label>
                        <select name="osr_fy_id" id="fy_year_id" class="form-control">
                          @foreach($master_fy_years as $fy_year)
                            <option value="{{base64_encode($fy_year->id)}}" @if($fy_year->id==$max_osr_fy_year) {{'selected'}} @endif>{{$fy_year->fy_name}}</option>
                          @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mt40">
                
            @foreach($card_data as $cards)
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="card animated zoomIn">
                        <div class="card-header">{{$cards['FY']}}</div>
                        <div class="card-body">
                            <p><span class="number circle blinking" style="font-family: 'Sarpanch', sans-serif;">{{$cards['Total']}}</span> Cr</p>
                            <div class="table-responsive" style="background-color: #fff;">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td colspan="5" class="green-back">Revenue Collection Status</td>
                                    </tr>
                                    <tr class="bold-color">
                                        <td>Asset Category</td>
                                        <td>ZP (Cr.)</td>
                                        <td>AP (Cr.)</td>
                                        <td>GP (Cr.)</td>
                                        <td>Total Amt. (Cr.)</td>
                                    </tr>
                                    @foreach($cards['data'] as $assetData)
                                            <tr>
                                                <td class="bold-text">{{$assetData['AC']}}</td>
                                                <td>{{$assetData['data']['ZP']}}</td>
                                                <td>{{$assetData['data']['AP']}}</td>
                                                <td>{{$assetData['data']['GP']}}</td>
                                                <td>{{$assetData['data']['ZP']+$assetData['data']['AP']+$assetData['data']['GP']}}</td>
                                            </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
             @endforeach
                
            </div>
            

        <div class="row mt40">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading" style="text-align: center">
                       Year Wise Revenue Collection Analysis (in Cr.) (Graph View)
                    </div>
                    <div class="panel-body">
                        <div id="canvas-holder" style="width:100%;">
                            <canvas id="compYearCollection"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading" style="text-align: center">
                       Asset Wise Revenue Collection Analysis For The {{$fy_years}} (in % Percentage)
                    </div>
                    <div class="panel-body">
                        <div id="canvas-holder" style="width:100%;">
                            <canvas id="chart-area"></canvas>
                        </div>
                    </div>
                </div>
            </div>
         </div>
        
        
          <div class="row mt40">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading" style="text-align: center">
                        Settlement List (Zila Wise) for {{$fy_years}}
                    </div>
                    <div class="panel-heading1 gray-back" style="text-align: center">
                        <table class="table text-center data-header">
                            <tr class="data-header-head">
                                <th class="text-center">Total Asset</th>
                                <th class="text-center">Settled Asset</th>
                                <th class="text-center">Percentage of Settlement(%)</th>
                            </tr>
                            <tr class="data-header-value">
                                <td>{{$dataCount['totalStateCount']['totalAsset']}}</td>
                                <td>{{$dataCount['totalStateCount']['settledAsset']}}</td>
                                <td>{{$dataCount['totalStateCount']['settledPercent']}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="panel-body gray-back">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable4">
                                <thead>
                                <tr class="tr-row head-txt">
                                    <td style="display: none">#</td>
                                    <td>Zila Parishad</td>
                                    <td>Total Assets</td>
                                    <td>Settled Assets</td>
                                    <td>Percentage of Settlement(%)</td>
                                </tr>
                                </thead>
                                <tbody>
                                @php $i=0; @endphp
                                @foreach($zilas AS $list)
                                    <tr>
                                        <td style="display: none">{{$i++}}</td>
                                        <td>
                                            <a href="{{route('admin.Osr.subDistrictWiseAssetSettlement',[$list->id,$max_osr_fy_year])}}">
                                                {{$list->zila_parishad_name}}
                                            </a>
                                        </td>
                                        <td>
                                            @if(isset($dataCount['totalAssetCount'][$list->id]))
                                                {{$dataCount['totalAssetCount'][$list->id]}}
                                            @else
                                                {{0}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($dataCount['zpYrWiseSettledAssetCount'][$list->id]))
                                                {{$dataCount['zpYrWiseSettledAssetCount'][$list->id]}}
                                            @else
                                                {{0}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($dataCount['totalAssetCount'][$list->id]))
                                                @if(isset($dataCount['zpYrWiseSettledAssetCount'][$list->id]))
                                                    {{round((($dataCount['zpYrWiseSettledAssetCount'][$list->id]/$dataCount['totalAssetCount'][$list->id])*100), 2)}}
                                                @else
                                                    {{'0'}}
                                                @endif
                                            @else
                                                {{'0'}}
                                            @endif
                                        
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading" style="text-align: center">
                        List of Defaulters (Zila Wise) for {{$fy_years}}
                    </div>
                    <div class="panel-heading1 gray-back" style="text-align: center">
                        <table class="table text-center data-header">
                            <tr class="data-header-head">
                                <th class="text-center">Settled Assets</th>
                                <th class="text-center">Defaulters</th>
                                <td>Percentage of Defaulters(%)</td>
                            </tr>
                            <tr class="data-header-value">
                                <td>{{$dataCount['totalStateCount']['settledAsset']}}</td>
                                <td data-zfyyear="{{$max_osr_fy_year}}" class="listOfTotalZPDefaulterModalView" style="cursor: pointer;">
                                    {{$dataCount['totalStateCount']['totalDefaulter']}}
                                </td>
                                <td>{{$dataCount['totalStateCount']['defaulterPercent']}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="panel-body gray-back">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable3">
                                <thead>
                                <tr class="tr-row head-txt">
                                    <td style="display: none">#</td>
                                    <td>Zila Parishad</td>
                                    <td>Settled Assets</td>
                                    <td>Defaulters</td>
                                    <td>Percentage of Defaulters(%)</td>
                                </tr>
                                </thead>
                                <tbody>
                                @php $i=0; @endphp
                                @foreach($zilas AS $list)
                                    <tr>
                                        <td style="display: none">{{$i++}}</td>
                                        <td>
                                            <a href="{{route('admin.Osr.subDistrictWiseDefaulterReport',[$list->id,$max_osr_fy_year])}}" class="districtSelect" data-id="{{$list->id}}">
                                                {{$list->zila_parishad_name}}
                                            </a>
                                        </td>
                                        <td> 
                                            @if(isset($dataCount['zpYrWiseSettledAssetCount'][$list->id]))
                                                {{$dataCount['zpYrWiseSettledAssetCount'][$list->id]}}
                                            @else
                                            {{0}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($dataCount['zpYrWiseDefaulterCount'][$list->id]))
                                                <a href="#"  data-zfyyear="{{$max_osr_fy_year}}" data-zid="{{$list->id}}" class="listOfZPDefaulterModalViewC">
                                                    {{$dataCount['zpYrWiseDefaulterCount'][$list->id]}}
                                                </a>
                                            @else
                                            {{0}}
                                            @endif
                                        </td>
                                        <td>
                                             @if(isset($dataCount['zpYrWiseSettledAssetCount'][$list->id]))
                                            
                                                @if(isset($dataCount['zpYrWiseDefaulterCount'][$list->id]))
                                                    {{round((($dataCount['zpYrWiseDefaulterCount'][$list->id]/$dataCount['zpYrWiseSettledAssetCount'][$list->id])*100), 2)}}
                                                @else
                                                    {{'0'}}
                                                @endif
                                            @else
                                                {{'0'}}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>  
        </div>        
        
        
        <!-- ------------------------- REVENUE COLLECTION -------------------------------------------------------------------->

        <div class="row mt40">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading" style="text-align: center">
                         Revenue Collection After Sharing (Zila Wise) for {{$fy_years}}
                    </div>
                    <div class="panel-heading1 gray-back" style="text-align: center">
                        <table class="table text-center data-header">
                            <tr class="data-header-head">
                                <th class="text-center">Gap Period Revenue Collection from Assets</th>
                                <th class="text-center">Revenue Collection from Bids</th>
                                <th class="text-center">Revenue Collection from Other Assets</th>
                                <th class="text-center">Total Revenue Collection</th>
                            </tr>
                            <tr class="data-header-value">
                                <td class="text-center">{{$dataCount['totalStateCount']['totalRevenueCollection']['gap_c']}} <span class="f-12">Cr</span> </td>
                                <td class="text-center">{{$dataCount['totalStateCount']['totalRevenueCollection']['bid_c']}} <span class="f-12">Cr</span></td>
                                <td class="text-center">{{$dataCount['totalStateCount']['totalRevenueCollection']['other_c']}} <span class="f-12">Cr</span></td>
                                <td class="text-center">{{$dataCount['totalStateCount']['totalRevenueCollection']['tot_c']}} <span class="f-12">Cr</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="panel-body gray-back">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable1">
                                <thead>
                                <tr class="tr-row head-txt">
                                    <td style="display: none">#</td>
                                    <td>Zila Parishad</td>
                                    <td>Gap Period Revenue Collection from Assets (Cr.)</td>
                                    <td>Revenue Collection from Bids (Cr.)</td>
                                    <td>Revenue Collection from Other Assets (Cr.)</td>

                                    <td>Shared Amount Received From APs (Cr.)</td>
                                    <td>Shared Amount Received From GPs (Cr.)</td>

                                    <td>Total Revenue Collection (Cr.)</td>
                                </tr>
                                </thead>
                                <tbody>
                                @php $i=1; @endphp
                                @foreach($zilas AS $list)
									<tr>
                                        <td style="display: none">{{$i++}}</td>
										<td>
                                            <a href="{{route('admin.Osr.subDistrictWiseRevenue',[$list->id,$max_osr_fy_year])}}"> {{$list->zila_parishad_name}}</a>
										</td>
										<td>
                                            {{$dataCount['zpYrWiseRevenueList'][$list->id]['gap_c']}}
                                        </td>
										<td>
                                            {{$dataCount['zpYrWiseRevenueList'][$list->id]['bid_c']}}
                                        </td>
										<td>
                                            {{$dataCount['zpYrWiseRevenueList'][$list->id]['other_c']}}
                                        </td>
                                        <td>
                                            {{$dataCount['zpYrWiseRevenueList'][$list->id]['aps_share_to_zp']}}
                                        </td>
                                        <td>
                                            {{$dataCount['zpYrWiseRevenueList'][$list->id]['gps_share_to_zp']}}
                                        </td>
                                        <td>
                                            {{$dataCount['zpYrWiseRevenueList'][$list->id]['tot_c']}}
                                        </td>
									</tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
			
			<!-- ------------------------- Share Distribution -------------------------------------------------------------------->
			
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading" style="text-align: center">
                        Zila Wise Share Distribution for {{$fy_years}}
                    </div>
                    <div class="panel-heading1 gray-back" style="text-align: center">
                        <table class="table text-center data-header">
                            <tr class="data-header-head" >
                                <th class="text-center pt5">Total Revenue Collection</th>
                                <th class="text-center pt5">ZP <br> Share</th>
                                <th class="text-center">AP <br> Share</th>
                                <td>GP <br> Share</td>
                            </tr>
                            <tr class="data-header-value">
                                <td>{{$dataCount['totalStateCount']['totalRevenueShareCollection']['tot_r_c']}} <span class="f-12">Cr</span> </td>
                               
                                <td>{{$dataCount['totalStateCount']['totalRevenueShareCollection']['zp_share']}} <span class="f-12">Cr</span></td>
                                <td>{{$dataCount['totalStateCount']['totalRevenueShareCollection']['ap_share']}} <span class="f-12">Cr</span></td>
                                <td>{{$dataCount['totalStateCount']['totalRevenueShareCollection']['gp_share']}} <span class="f-12">Cr</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="panel-body gray-back">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable2">
                                <thead>
                                <tr class="tr-row head-txt">
                                    <td style="display: none">#</td>
                                    <td>Zila Parishad</td>
                                    <td>Total Revenue Collection <br/> (Gap Period Collection From Asset+<br/>Collection From Bid+<br/>Other Asset Collection)<br>(Cr.)</td>
                                    <td>ZP Share <br>(Cr.)</td>
                                    <td>AP Share <br>(Cr.)</td>
                                    <td>GP Share <br>(Cr.)</td>
                                </tr>
                                </thead>
                                <tbody>
                                @php $i=1; @endphp
                                @foreach($zilas AS $list)
                                <tr>
                                    <td style="display: none">{{$i++}}</td>
                                    <td>
                                        <a href="{{route('admin.Osr.subDistrictWiseShare',[$list->id,$max_osr_fy_year])}}">
                                            {{$list->zila_parishad_name}}
                                        </a>
                                    </td>
                                    <td>
                                        {{$dataCount['zpYrWiseShareList'][$list->id]['tot_r_c']}}
                                    </td>
                                    <td>
                                        {{$dataCount['zpYrWiseShareList'][$list->id]['zp_share']}}
                                    </td>
                                    <td>
                                        {{$dataCount['zpYrWiseShareList'][$list->id]['ap_share']}}
                                    </td>
                                    <td>
                                        {{$dataCount['zpYrWiseShareList'][$list->id]['gp_share']}}
                                    </td>
                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
		
		
		<div class="row mt40">

		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-primary">
                <div class="panel-heading" style="text-align: center">
                    Zila Wise Revenue Collection After Sharing for {{$fy_years}} (in Cr.)
                </div>
				<div class="panel-body">
					<div id="canvas-holder" style="width:100%;">
						<canvas id="revenue-collection-district-wise"></canvas>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-primary">
                <div class="panel-heading" style="text-align: center">
                    Zila Wise Share Distribution {{$fy_years}} (in Cr.)
                </div>
				<div class="panel-body">
					<div id="canvas-holder" style="width:100%;">
						<canvas id="canvasZilaWiseShares"></canvas>
					</div>
				</div>
			</div>
		</div>
		
		
		</div>

        <div class="row mt40">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading" style="text-align: center">
                        Financial Year Wise Assets
                    </div>
                    <div class="panel-body gray-back">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable5">
                                <thead>
                                <tr class="tr-row head-txt">
                                    <td>Financial Year</td>
                                    <td>Haat</td>
                                    <td>Ghat</td>
                                    <td>Fisheries</td>
                                    <td>Animal Pound</td>
                                    <td>Other Asset</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($card_data as $cards)
                                <tr>
                                    <td>{{$cards['FY']}}</td>
                                    <td>{{$yrWiseAssetCount[$cards['id']][1]}}</td>
                                    <td>{{$yrWiseAssetCount[$cards['id']][2]}}</td>
                                    <td>{{$yrWiseAssetCount[$cards['id']][3]}}</td>
                                    <td>{{$yrWiseAssetCount[$cards['id']][4]}}</td>
                                    <td>0</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="panel-body">
                        <div id="canvas-holder" style="width:100%;">
                            <canvas id="asset-3years"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading" style="text-align: center">
                        Financial Year Wise Defaulters
                    </div>
                    <div class="panel-body gray-back">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable6">
                                <thead>
                                <tr class="tr-row">
                                    <td>Financial Year</td>
                                    <td>Defaulter</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($card_data as $cards)
                                    <tr>
                                        <td>{{$cards['FY']}}</td>
                                        <td>{{$dataCount['yrWiseDefaulterCount'][$cards['id']]}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="panel-body">
                        <div id="canvas-holder" style="width:100%;">
                            <canvas id="defaulter-yr-wise"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt40"></div>
            
     </div>
 </div>
    <!-- Model Total ZP Defaulters -->
    <div class="modal fade" id="listOfTotalZPDefaulterModalView" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 33px 0 0 0;">
                <div class="modal-header" style="background-color: #ff9000">
                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    <h4 style="color: #fff;font-family: 'Old Standard TT', serif;">Defaulter List for the Financial Year {{$fy_years}}</h4>
                </div>
                <div class="modal-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable11" style="width:100%">
                            <thead>

                            <tr class="tr-row">
                                <td>SL</td>
                                <td>Zila Parishad</td>
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
    <!-- Model  ZP Defaulters -->
    <div class="modal fade listOfZPDefaulterModalView" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 33px 0 0 0;">
                <div class="modal-header" style="background-color: #ff9000">
                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    <h4 style="color: #fff;font-family: 'Old Standard TT', serif;">Defaulter List for the Financial Year {{$fy_years}}</h4>
                </div>
                <div class="modal-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped dataTable12" id="" style="width:100%">
                            <thead>
                            <tr class="tr-row">
                                <td>SL</td>
                                <td>Zila Parishad</td>
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
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

    <script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>
    <script type="application/javascript">
        $(document).ready(function () {
            $('#dataTable1').DataTable({
                dom: 'Bfrtip',
                searching:false,
                buttons: [
                    {
                        extend:    'excelHtml5',
                        title: 	   'Revenue Collection (Zila Wise) for FY-2019-2020',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
            $('#dataTable2').DataTable({
                dom: 'Bfrtip',
                searching:false,
                buttons: [
                    {
                        extend:    'excelHtml5',
                        title: 	   'Zila Wise Shares (ZP, AP, GP) for FY 2019-2020',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel'
                    }
                ]
            });
            $('#dataTable3').DataTable({
                dom: 'Bfrtip',
                searching:false,
                buttons: [
                    {
                        extend:    'excelHtml5',
                        title:     'List of Defaulters (Zila Wise) for FY-2019-2020',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel'
                    }
                ]
            });
            $('#dataTable4').DataTable({
                dom: 'Bfrtip',
                searching:false,
                buttons: [
                    {
                        extend:    'excelHtml5',
                        title:     'Settlement List (Zila Wise) for FY-2019-2020',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel'
                    }
                ]
            });
            $('#dataTable11').DataTable({
                dom: 'Bfrtip',
                searching:false,
                ordering: false,
                buttons: [
                    {
                        extend:    'excelHtml5',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel'
                    }
                ]
            });
            $('.dataTable12').DataTable({
                dom: 'Bfrtip',
                searching:false,
                ordering: false,
                buttons: [
                    {
                        extend:    'excelHtml5',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel'
                    }
                ]
            });
        });
        {{------------------------------------XXXXXXXXXXXXXXXXXXXXXXXXXXXXX---------------------------------------------------}}
        //      District Wise Collection Bar Graph



        var barLineCompYearCollectionData = {
            type: 'line',
            data: {
                labels: [
                    @foreach($card_data as $cards)
                        '{{$cards['FY']}}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Amount',
                    backgroundColor: "#ac205e",
                    borderColor: window.chartColors.red,
                    data: [
                        @foreach($card_data as $cards)
                        {{$cards['Total']}},
                        @endforeach
                    ],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: false,
                    text: 'Last 3 Years Revenue Trend (Graph View)'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Years'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Amount in Cr.'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        };

        var color = Chart.helpers.color;
        var barLineYearWiseAssetCount = {
            labels: [
                @foreach($card_data as $cards)
                    '{{$cards['FY']}}',
                @endforeach
                // 'FY-2017-18', 'FY-2018-19', 'FY-2019-20'
            ],
            datasets: [{
                label: 'Haat',
                backgroundColor: "#ff6b77",
//                borderColor: "green",
//                borderWidth: 1,
                data: [
                    @foreach($card_data as $cards)
                    {{$yrWiseAssetCount[$cards['id']][1]}},
                    @endforeach
                ]
            }, {
                label: 'Ghat',
                backgroundColor: "#d87cd3",
//                borderColor: window.chartColors.blue,
//                borderWidth: 1,
                data: [
                    @foreach($card_data as $cards)
                    {{$yrWiseAssetCount[$cards['id']][2]}},
                    @endforeach
                ]
            }, {
                label: 'Fishery',
                backgroundColor: "#a5cf5f",
//                borderColor: "",
//                borderWidth: 1,
                data: [
                    @foreach($card_data as $cards)
                    {{$yrWiseAssetCount[$cards['id']][3]}},
                    @endforeach
                ]
            }, {
                label: 'Animal Pound',
                backgroundColor: "#27a4de",
//                borderColor: window.chartColors.green,
//                borderWidth: 1,
                data: [
                    @foreach($card_data as $cards)
                    {{$yrWiseAssetCount[$cards['id']][4]}},
                    @endforeach
                ]
            }]

        };
        var color = Chart.helpers.color;
        var barLineYearWiseDefaulterData = {
            labels: [
                @foreach($card_data as $cards)
                    '{{$cards['FY']}}',
                @endforeach
                // 'FY-2017-18', 'FY-2018-19', 'FY-2019-20'
            ],
            datasets: [{
                label: 'Defaulter',
                backgroundColor: color(window.chartColors.red).rgbString(),
                borderColor: window.chartColors.red,
                borderWidth: 1,
                data: [
                    @foreach($card_data as $cards)
                    {{$dataCount['yrWiseDefaulterCount'][$cards['id']]}},
                    @endforeach
                ]
            }]
        };
        // var randomScalingFactor = function() {
        //     return Math.round(Math.random() * 100);
        // };




        var config = {
            type: 'pie',
            data: {
                datasets: [{
                    data:[
                        @foreach ($dataPaiChart as $key => $value)
                        {{$value}},
                        @endforeach
                    ],
                    backgroundColor: [
                        window.chartColors.red,
                        window.chartColors.orange,
                        window.chartColors.yellow,
                        window.chartColors.green,
                        window.chartColors.blue,
                    ]
                }],
                labels: [
                    @foreach($dataPaiChart as $key => $value)
                        "{{$key}}",
                    @endforeach
                ]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: false,
                    text: 'Chart.js Doughnut Chart'
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                                return previousValue + currentValue;
                            });
                            var currentValue = dataset.data[tooltipItem.index];
                            var percentage = Math.floor(((currentValue/total) * 100)+0.5);
                            return currentValue + " Cr";
                        }
                    }
                },
                plugins: {
                    labels: {
                        render: 'percentage',
                        fontColor:'white',
                        fontSize: 18
                    }
                }
            }
        };

        /************************** REVENUE COLLECTION DISTRICT WISE **************************************************/

        var revenue_collection_district_wise = {
            labels: [
                @foreach($zilas As $list)
                    "{{$list->zila_parishad_name}}",
                @endforeach
            ],
            datasets: [{
                label: 'Gap Period Revenue Collection from Assets',
                backgroundColor: "#ff6b77",
                data: [
                    @foreach($zilas As $list)
                    {{$dataCount['zpYrWiseRevenueList'][$list->id]['gap_c']}},
                    @endforeach
                ]
            }, {
                label: 'Revenue Collection from Bids',
                backgroundColor: "#d87cd3",
                data: [
                    @foreach($zilas As $list)
                    {{$dataCount['zpYrWiseRevenueList'][$list->id]['bid_c']}},
                    @endforeach
                ]
            }, {
                label: 'Revenue Collection from Other Assets',
                backgroundColor: "#a5cf5f",
                data: [
                    @foreach($zilas As $list)
                    {{$dataCount['zpYrWiseRevenueList'][$list->id]['other_c']}},
                    @endforeach
                ]
            }]

        };


        //********************* Zila Wise Shares******************************
        var barChartDataZilaWiseShares = {
            labels: [
                @foreach($zilas As $list)
                    '{{$list->zila_parishad_name}}',
                @endforeach
            ],
            datasets: [{
                label: 'ZP Share',
                backgroundColor: color(window.chartColors.blue).rgbString(),
                borderColor: window.chartColors.blue,
                borderWidth: 1,
                data: [
                    @foreach($zilas As $list)
                    {{$dataCount['zpYrWiseShareList'][$list->id]['zp_share']}},
                    @endforeach
                ]
            }, {
                label: 'AP Share',
                backgroundColor: color(window.chartColors.yellow).rgbString(),
                borderColor: window.chartColors.yellow,
                borderWidth: 1,
                data: [
                    @foreach($zilas As $list)
                    {{$dataCount['zpYrWiseShareList'][$list->id]['ap_share']}},
                    @endforeach
                ]
            }, {
                label: 'GP Share',
                backgroundColor: color(window.chartColors.red).rgbString(),
                borderColor: window.chartColors.red,
                borderWidth: 1,
                data: [
                    @foreach($zilas As $list)
                    {{$dataCount['zpYrWiseShareList'][$list->id]['gp_share']}},
                    @endforeach
                ]
            }]

        };





        var ctxZilaWiseShares = document.getElementById('canvasZilaWiseShares').getContext('2d');
        window.myBar = new Chart(ctxZilaWiseShares, {
            type: 'bar',
            data: barChartDataZilaWiseShares,
            options: {
                title: {
                    display: true,
                    text: 'Zila Wise Share Distribution'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                responsive: true,
                scales: {
                    xAxes: [{
                        stacked: true,
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                },
                plugins: {
                    labels:false
                }

            }
        });


        var ctx4 = document.getElementById('revenue-collection-district-wise').getContext('2d');
        window.myBar = new Chart(ctx4, {
            type: 'bar',
            data: revenue_collection_district_wise,
            options: {
                title: {
                    display: true,
                    text: 'Zila Wise Revenue Collection (Graph View)'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                responsive: true,
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Districts'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Amount in Cr.'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                plugins: {
                    labels:false
                }
            }
        });




        var ctx2 = document.getElementById('compYearCollection').getContext('2d');
        // var ctx3 = document.getElementById('share-line').getContext('2d');
        window.myLine = new Chart(ctx2, barLineCompYearCollectionData);
        // window.myLine = new Chart(ctx3, barLineShareData);
        var ctx = document.getElementById('asset-3years').getContext('2d');
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barLineYearWiseAssetCount,
            options: {
                responsive: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Last 3 Years Asset Analysis (Graph View)'
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Years'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Number of Asset'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                plugins: {
                    labels:false
                }

            }
        });
        var ctx5 = document.getElementById('chart-area').getContext('2d');
        window.myPie = new Chart(ctx5, config);




        var ctx6 = document.getElementById('defaulter-yr-wise').getContext('2d');
        window.myBar = new Chart(ctx6, {
            type: 'bar',
            data: barLineYearWiseDefaulterData,
            options: {
                responsive: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Year Wise Defaulter Analysis (Graph View)'
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Years'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Share(in Cr.)'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                plugins: {
                    labels:{
                        render: 'value'
                    }
                }

            }
        });




        $('#fy_year_id').on('change',function(e){
            e.preventDefault();
            var id=$(this).val();
            var current_url='{{route('admin.Osr.osr_dashboard')}}'+'?id='+id;
            window.location.replace(current_url);
        })


        /*        $('.districtSelect').on('click',function(e){
         e.preventDefault();
         var district_id=$(this).data('id');
         alert(district_id);
         })*/

        //        List of Defaulter Total Zila Wise Model
        $('.listOfTotalZPDefaulterModalView').on('click', function(e){
            e.preventDefault();
            if ($.fn.DataTable.isDataTable('#dataTable11') ) {
                $('#dataTable11').dataTable().fnClearTable();
                $('#dataTable11').dataTable().fnDestroy()

            }

            $('#listOfTotalZPDefaulterModalView').modal('hide');

            var zfyyear = $(this).data('zfyyear');

            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('admin.Osr.dashboard.listOfTotalZPDefaulterZilaWise')}}',
                dataType: "json",
                data: {zfyyear : zfyyear},
                success: function (data) {
                    if (data.msgType == true) {

                        var dataSet=data.data;
                        $('#dataTable11').DataTable( {
                            data: dataSet,
                            columns: [
                                { title: "SL" },
                                { title: "Zila Parishad" },
                                { title: "Asset Category" },
                                { title: "Asset Code" },
                                { title: "Asset Name" },
                                { title: "Defaulter Name" },
                                { title: "Defaulter Father Name" },
                                { title: "Mobile" },
                                { title: "Asset Under" }
                            ]
                        } );


                        $('#listOfTotalZPDefaulterModalView').modal('show');

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


        //        List of Defaulter Zila Wise Model
        $('.listOfZPDefaulterModalViewC').on('click', function(e){
            e.preventDefault();
            if ($.fn.DataTable.isDataTable('.dataTable12') ) {
                $('.dataTable12').dataTable().fnClearTable();
                $('.dataTable12').dataTable().fnDestroy()

            }

            $('.listOfZPDefaulterModalView').modal('hide');

            var zfyyear = $(this).data('zfyyear');
            var zid = $(this).data('zid');

            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('admin.Osr.dashboard.listOfZPDefaulterZilaWise')}}',
                dataType: "json",
                data: {zfyyear : zfyyear, zid : zid},
                success: function (data) {
                    if (data.msgType == true) {

                        var dataSet=data.data;
                        $('.dataTable12').DataTable( {
                            data: dataSet,
                            columns: [
                                { title: "SL" },
                                { title: "Zila Parishad" },
                                { title: "Asset Category" },
                                { title: "Asset Code" },
                                { title: "Asset Name" },
                                { title: "Defaulter Name" },
                                { title: "Defaulter Father Name" },
                                { title: "Mobile" },
                                { title: "Asset Under" }
                            ]
                        } );


                        $('.listOfZPDefaulterModalView').modal('show');

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

