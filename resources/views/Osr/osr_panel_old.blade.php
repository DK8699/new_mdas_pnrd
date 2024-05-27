@php
    $page_title="OSR Dashboard";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <link href="https://fonts.googleapis.com/css?family=Old+Standard+TT&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.css" rel="stylesheet" type="text/css"/>
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <style>

        .mt40 {
            margin-top: 40px;
        }

        .card {
            border: 1px solid #ff770f;
            background-color: #f3f2f2;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }

        .panel-heading {
            background-color: #6b133d;
            color: #fff;
            text-align: center;
            font-weight: 700;
            padding: 5px;
        }

        .card-header {
            background-color: rgb(255, 118, 15);
            color: #fff;
            text-align: center;
            font-weight: 700;
            padding: 5px;
        }

        .card-body {
            padding: 10px;
            text-align: center;
        }

        .card .number {
            font-weight: 900;
            font-size: 40px;
        }

        .card p {
            font-weight: 900;
            font-size: 20px;
        }

        .tr-row {
            background-color: #ebe7e7;
            color: rgb(255, 118, 15);
            font-weight: 600;
        }

        .bold-color {
            color: darkviolet;
            font-weight: 600;
        }

        .gray-back {
            background-color: #f3f2f2;
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
            <div class="col-md-6 col-sm-6 col-xs-12 animated zoomIn">
                <div class="card">
                    <div class="card-header">
                        Non-Tax Asset
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="background-color: #fdfdfd">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Haat, Ghat, Fisheries, Animal Pound</th>
                                    <th class="text-center">Other Assets</th>
                                </tr>
                                @if(Auth::user()->mdas_master_role_id==2)

                                    {{------------------------ ZP -----------------------------------------------------}}
                                    <tr>
                                        <td>{{$data['originName']}} (ZP)</td>
                                        <td>
                                            <a href="{{route('osr.non_tax.asset_entry_panel')}}">{{$data['assetCount']['zpAsset']}}</a>
                                        </td>
                                        <td>
                                            <a href="{{route('osr.non_tax.dw_asset.other_assets')}}">{{$data['otherAssetCount']['zpAsset']}}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{$data['levelCount']['zpwApCount']}} APs</td>
                                        <td>
                                            <a href="{{route('osr.non_tax.asset_show_list')}}">{{$data['assetCount']['apAsset']}}</a>
                                        </td>
                                        <td>
                                            <a href="{{route('osr.non_tax.other_asset_show_list')}}">{{$data['otherAssetCount']['apAsset']}}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{$data['levelCount']['zpwGpCount']}} GPs</td>
                                        <td>
                                            <a href="{{route('osr.non_tax.asset_show_list')}}">{{$data['assetCount']['gpAsset']}}</a>
                                        </td>
                                        <td>
                                            <a href="{{route('osr.non_tax.other_asset_show_list')}}">{{$data['otherAssetCount']['gpAsset']}}</a>
                                        </td>
                                    </tr>
                                @elseif(Auth::user()->mdas_master_role_id==3)

                                    {{------------------------ AP -----------------------------------------------------}}
                                    <tr>
                                        <td>{{$originName}} (AP)</td>
                                        <td>
                                            <a href="{{route('osr.non_tax.asset_entry_panel')}}">{{$data['assetCount']['apAsset']}}</a>
                                        </td>
                                        <td>
                                            <a href="{{route('osr.non_tax.dw_asset.other_assets')}}">{{$data['otherAssetCount']['apAsset']}}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{$data['levelCount']['apwGpCount']}} GPs</td>
                                        <td>
                                            <a href="{{route('osr.non_tax.asset_show_list')}}">{{$data['assetCount']['gpAsset']}}</a>
                                        </td>
                                        <td>
                                            <a href="{{route('osr.non_tax.other_asset_show_list')}}">{{$data['otherAssetCount']['gpAsset']}}</a>
                                        </td>
                                    </tr>
                                @elseif(Auth::user()->mdas_master_role_id==4)

                                    {{------------------------ GP -----------------------------------------------------}}
                                    <tr>
                                        <td>{{$data['originName']}} (GP)</td>
                                        <td>
                                            <a href="{{route('osr.non_tax.asset_entry_panel')}}">{{$data['assetCount']['gpAsset']}}</a>
                                        </td>
                                        <td>
                                            <a href="{{route('osr.non_tax.dw_asset.other_assets')}}">{{$data['otherAssetCount']['gpAsset']}}</a>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{--<div class="panel panel-primary">
                    <div class="panel-body">
                        <div id="canvas-holder" style="width:100%;">
                            <canvas id="count-3years"></canvas>
                        </div>
                    </div>
                </div>--}}
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="card-header">Non-Tax Revenue Sources (Haat, Ghat, Fisheries, Animal Pound)</div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item text-left"><a href="{{route('osr.non_tax.asset_shortlist_bidding')}}">Shortlist non-tax asset for bidding.</a></li>
                            {{--<li class="list-group-item text-left"><a href="{{route('osr.non_tax.asset_shortlist_bidding')}}">Add reasons to not shortlist asset.</a></li>--}}
                            <li class="list-group-item text-left"><a href="{{route('osr.non_tax.asset_shortlist_bidding_update_payment')}}">Update tender details and payment.</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!---------------------------------- SEARCH BAR --------------------------------------------------------------->

        <div class="row mt40">
            <div class="col-md-4 col-sm-4 col-xs-12 col-md-offset-8 col-sm-offset-8">
                <div class="form-group">
                    <label><i class="fa fa-calendar" aria-hidden="true"></i> Financial year</label>
                    <select class="form-control" name="search_fyYr_id" id="search_fyYr_id" required>
                        <option value="">--Select--</option>
                            @foreach($data['fyList'] AS $os_list)
                                <option value="{{encrypt($os_list->id)}}" @if($os_list->id==$data['fy_id'])selected="selected"@endif>{{$os_list->fy_name}}</option>
                            @endforeach
                    </select>
                </div>
            </div>
        </div>

    {{----------------------------------------------------------------------------------------------------------------}}
    {{----------------------------  Zila Parishad Panel --------------------------------------------------------------}}
    {{----------------------------------------------------------------------------------------------------------------}}

        <!----------------------------------Haat,Ghat,Fisheries,Animal Pound---------------------------------------->
        <h4>OSR Non-Tax Asset (Haat, Ghat, Fisheries, Animal Pound) for the year {{$data['fyData']->fy_name}}</h4>

        <hr/>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#settlement">Settlement Percentage</a></li>
                    <li><a data-toggle="tab" href="#collection">Revenue Collection</a></li>
                    <li><a data-toggle="tab" href="#defaulter">Defaulter</a></li>
                    <li><a data-toggle="tab" href="#share">Share Distribution</a></li>
                </ul>
            </div>
        </div>
        <div class="row mt40" style="margin-bottom:40px;">
            <div class="tab-content">

                <div id="settlement" class="tab-pane fade in active">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                            <a href="{{url('osr/non_tax/asset/zp/zp_asset_settlement_percent')}}/{{encrypt($data['fy_id'])}}" style="text-decoration:none; color:#333">
                                <div class="card-body" style="background:#e7d6cf;">
                                    <p style="background:#c8a186;">Settlement Percentage(ZP)</p>
                                    <div class="progress mt40">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar"
                                             aria-valuemin="0" aria-valuemax="100" style="width:80%">
                                            80%
                                        </div>
                                    </div>
                                    <table class="table">
                                        <tr>
                                            <td>Total scope</td>
                                            <td>Shorlisted</td>
                                            <td>Settled</td>
                                        </tr>
                                        <tr class="value" style="font-size:15px">
                                            <td>120</td>
                                            <td>100</td>
                                            <td>80</td>
                                        </tr>
                                    </table>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                            <a href="{{url('osr/non_tax/asset/zp/ap_list_asset_settlement_percent')}}/{{encrypt($data['fy_id'])}}}}" style="text-decoration:none; color:#333">
                                <div class="card-body" style="background:#e7d6cf;">
                                    <p style="background:#c8a186;">Settlement Percentage(AP)</p>
                                    <div class="progress mt40">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar"
                                             aria-valuemin="0" aria-valuemax="100" style="width:80%">
                                            80%
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <table class="table">
                                            <tr>
                                                <td>Total scope</td>
                                                <td>Shorlisted</td>
                                                <td>Settled</td>
                                            </tr>
                                            <tr class="value" style="font-size:15px">
                                                <td>120</td>
                                                <td>100</td>
                                                <td>80</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                            <a href="{{url('osr/non_tax/asset/zp/gp_list_asset_settlement_percent')}}/{{encrypt($data['fy_id'])}}" style="text-decoration:none; color:#333">
                                <div class="card-body" style="background:#e7d6cf;">
                                    <p style="background:#c8a186;">Settlement Percentage(GP)</p>
                                    <div class="progress mt40">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar"
                                             aria-valuemin="0" aria-valuemax="100" style="width:80%">
                                            80%
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <table class="table">
                                            <tr>
                                                <td>Total scope</td>
                                                <td>Shorlisted</td>
                                                <td>Settled</td>
                                            </tr>
                                            <tr class="value" style="font-size:15px">
                                                <td>120</td>
                                                <td>100</td>
                                                <td>80</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>


                <div id="collection" class="tab-pane fade">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                            <a href="{{url('osr/non_tax/asset/zp/zp_asset_collection')}}/{{encrypt($data['fy_id'])}}" style="text-decoration:none; color:#333">
                                <div class="card-body" style="background:#e7d6cf;">
                                    <p style="background:#c8a186;">Collection(ZP)</p>
                                    <div class="card-body">
                                        <table class="table">
                                            <tr>
                                                <td>Total Collection From Asset <br>(in ₹.)</td>
                                                <td>Total Gap Period Collection <br>(in ₹.)</td>
                                                <td>Grand Total <br>(in ₹.)</td>
                                            </tr>
                                            <tr class="value" style="font-size:15px">
                                                <td>50000</td>
                                                <td>50000</td>
                                                <td>100000</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                            <a href="{{url('osr/non_tax/asset/zp/ap_list_asset_collection')}}/{{encrypt($data['fy_id'])}}" style="text-decoration:none; color:#333">
                                <div class="card-body" style="background:#e7d6cf;">
                                    <p style="background:#c8a186;">Collection(AP)</p>
                                    <div class="card-body">
                                        <table class="table">
                                            <tr>
                                                <td>Total Collection From Asset <br>(in ₹.)</td>
                                                <td>Total Gap Period Collection <br>(in ₹.)</td>
                                                <td>Grand Total <br>(in ₹.)</td>
                                            </tr>
                                            <tr class="value" style="font-size:15px">
                                                <td>50000</td>
                                                <td>50000</td>
                                                <td>100000</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                            <a href="{{url('osr/non_tax/asset/zp/gp_list_asset_collection')}}/{{encrypt($data['fy_id'])}}" style="text-decoration:none; color:#333">
                                <div class="card-body" style="background:#e7d6cf;">
                                    <p style="background:#c8a186;">Collection(GP)</p>
                                    <div class="card-body">
                                        <table class="table">
                                            <tr>
                                                <td>Total Collection From Asset <br>(in ₹.)</td>
                                                <td>Total Gap Period Collection <br>(in ₹.)</td>
                                                <td>Grand Total <br>(in ₹.)</td>
                                            </tr>
                                            <tr class="value" style="font-size:15px">
                                                <td>50000</td>
                                                <td>50000</td>
                                                <td>100000</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div id="defaulter" class="tab-pane fade">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                            <a href="{{url('osr/non_tax/asset/zp/zp_asset_defaulter')}}/{{encrypt($data['fy_id'])}}"  style="text-decoration:none; color:#333">
                                <div class="card-body" style="background:#e7d6cf;">
                                    <p style="background:#c8a186;">Defaulter(ZP)</p>
                                    <div class="card-body">
                                        <table class="table">
                                            <tr>
                                                <td>Settled Asset</td>
                                                <td>Defaulter</td>
                                            </tr>
                                            <tr class="value" style="font-size:15px">
                                                <td>80</td>
                                                <td>6</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                            <a href="{{url('osr/non_tax/asset/zp/ap_list_asset_defaulter')}}/{{encrypt($data['fy_id'])}}" style="text-decoration:none; color:#333">
                                <div class="card-body" style="background:#e7d6cf;">
                                    <p style="background:#c8a186;">Defaulter(AP)</p>
                                    <div class="card-body">
                                        <table class="table">
                                            <tr>
                                                <td>Settled Asset</td>
                                                <td>Defaulter</td>
                                            </tr>
                                            <tr class="value" style="font-size:15px">
                                                <td>80</td>
                                                <td>6</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                            <a href="{{url('osr/non_tax/asset/zp/gp_list_asset_defaulter')}}/{{encrypt($data['fy_id'])}}" style="text-decoration:none; color:#333">
                                <div class="card-body" style="background:#e7d6cf;">
                                    <p style="background:#c8a186;">Defaulter(GP)</p>
                                    <div class="card-body">
                                        <table class="table">
                                            <tr>
                                                <td>Settled Asset</td>
                                                <td>Defaulter</td>
                                            </tr>
                                            <tr class="value" style="font-size:15px">
                                                <td>80</td>
                                                <td>6</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div id="share" class="tab-pane fade">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                            <a href="{{url('osr/non_tax/asset/zp/zp_asset_share')}}/{{encrypt($data['fy_id'])}}" style="text-decoration:none; color:#333">
                                <div class="card-body" style="background:#e7d6cf;">
                                    <p style="background:#c8a186;">Share Distributed By ZP</p>
                                    <table class="table">
                                        <tr>
											<td>Total Revenue Collection<br>(in ₹.)</td>
                                            <td>ZP Share<br>(in ₹.)</td>
                                            <td>AP Share<br>(in ₹.)</td>
                                            <td>GP Share<br>(in ₹.)</td>
                                        </tr>
                                        <tr class="value" style="font-size:15px">
											<td>1,00,000</td>
                                            <td>20,000</td>
                                            <td>40,000</td>
                                            <td>40,000</td>
                                        </tr>
                                    </table>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                            <a href="{{url('osr/non_tax/asset/zp/ap_list_asset_share')}}/{{encrypt($data['fy_id'])}}" style="text-decoration:none; color:#333">
                                <div class="card-body" style="background:#e7d6cf;">
                                    <p style="background:#c8a186;">Share Distributed By AP</p>
                                    <table class="table">
                                        <tr>
											<td>Total Revenue Collection<br>(in ₹.)</td>
                                            <td>ZP Share<br>(in ₹.)</td>
                                            <td>AP Share<br>(in ₹.)</td>
                                            <td>GP Share<br>(in ₹.)</td>
                                        </tr>
                                        <tr class="value" style="font-size:15px">
											<td>1,00,000</td>
                                            <td>20,000</td>
                                            <td>40,000</td>
                                            <td>40,000</td>
                                        </tr>
                                    </table>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                            <a href="{{url('osr/non_tax/asset/zp/gp_list_asset_share')}}/{{encrypt($data['fy_id'])}}" style="text-decoration:none; color:#333">
                                <div class="card-body" style="background:#e7d6cf;">
                                    <p style="background:#c8a186;">Share Distributed By GP</p>
                                    <table class="table">
                                        <tr>
											<td>Total Revenue Collection<br>(in ₹.)</td>
                                            <td>ZP Share<br>(in ₹.)</td>
                                            <td>AP Share<br>(in ₹.)</td>
                                            <td>GP Share<br>(in ₹.)</td>
                                        </tr>
                                        <tr class="value" style="font-size:15px">
											<td>1,00,000</td>
                                            <td>20,000</td>
                                            <td>40,000</td>
                                            <td>40,000</td>
                                        </tr>
                                    </table>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-------------------------------------------Other Assets--------------------------------------------------->
        <h4>OSR Non-Tax Other Assets for the year {{$data['fyData']->fy_name}}</h4>
        <hr/>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#other_collection">Revenue Collection</a></li>
                    <li><a data-toggle="tab" href="#other_share">Share Distribution</a></li>
                </ul>
            </div>
        </div>
        <div class="row mt40" style="margin-bottom:40px;">

            <div class="tab-content">

                <div id="other_collection" class="tab-pane fade in active">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                            <a href="{{url('osr/non_tax/other_asset/zp/zp_other_asset_collection')}}/{{encrypt($data['fy_id'])}}" style="text-decoration:none; color:#333">
                                <div class="card-body" style="background:#e7d6cf;">
                                    <p style="background:#c8a186;">Collection(ZP)</p>
                                    <div class="card-body">
                                        <table class="table">
                                            <tr>
                                                <td>Total Collection<br>(in ₹.)</td>

                                            <tr class="value" style="font-size:15px">
                                                <td>50,000</td>

                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                            <a href="{{url('osr/non_tax/other_asset/zp/ap_list_other_asset_collection')}}/{{encrypt($data['fy_id'])}}" style="text-decoration:none; color:#333">
                                <div class="card-body" style="background:#e7d6cf;">
                                    <p style="background:#c8a186;">Collection(AP)</p>
                                    <div class="card-body">
                                        <table class="table">
                                            <tr>
                                                <td>Total Collection<br>(in ₹.)</td>

                                            </tr>
                                            <tr class="value" style="font-size:15px">
                                                <td>50,000</td>

                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                            <a href="{{url('osr/non_tax/other_asset/zp/gp_list_other_asset_collection')}}/{{encrypt($data['fy_id'])}}" style="text-decoration:none; color:#333">
                                <div class="card-body" style="background:#e7d6cf;">
                                    <p style="background:#c8a186;">Collection(GP)</p>
                                    <div class="card-body">
                                        <table class="table">
                                            <tr>
                                                <td>Total Collection<br>(in ₹.)</td>

                                            </tr>
                                            <tr class="value" style="font-size:15px">
                                                <td>50,000</td>

                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div id="other_share" class="tab-pane fade">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                            <a href="{{url('osr/non_tax/other_asset/zp/zp_other_asset_share')}}/{{encrypt($data['fy_id'])}}" style="text-decoration:none; color:#333">
                                <div class="card-body" style="background:#e7d6cf;">
                                    <p style="background:#c8a186;">Share Distributed By ZP</p>
                                    <table class="table">
                                        <tr>
											<td>Total Revenue Collection <br>(in ₹.)</td>
                                            <td>ZP Share <br>(in ₹.)</td>
                                            <td>AP Share <br>(in ₹.)</td>
                                            <td>GP Share <br>(in ₹.)</td>
                                        </tr>
                                        <tr class="value" style="font-size:15px">
                                            <td>50,000</td>
                                            <td>10,000</td>
                                            <td>20,000</td>
                                            <td>20,000</td>
                                        </tr>
                                    </table>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                            <a href="{{url('osr/non_tax/other_asset/zp/ap_list_other_asset_share')}}/{{encrypt($data['fy_id'])}}" style="text-decoration:none; color:#333">
                                <div class="card-body" style="background:#e7d6cf;">
                                    <p style="background:#c8a186;">Share Distributed By AP</p>
                                    <table class="table">
                                        <tr>
											<td>Total Revenue Collection <br>(in ₹.)</td>
                                            <td>ZP Share <br>(in ₹.)</td>
                                            <td>AP Share <br>(in ₹.)</td>
                                            <td>GP Share <br>(in ₹.)</td>
                                        </tr>
                                        <tr class="value" style="font-size:15px">
                                            <td>50,000</td>
                                            <td>10,000</td>
                                            <td>20,000</td>
                                            <td>20,000</td>
                                        </tr>
                                    </table>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                            <a href="{{url('osr/non_tax/other_asset/zp/gp_list_other_asset_share')}}/{{encrypt($data['fy_id'])}}" style="text-decoration:none; color:#333">
                                <div class="card-body" style="background:#e7d6cf;">
                                    <p style="background:#c8a186;">Share Distributed By GP</p>
                                    <table class="table">
                                        <tr>
											<td>Total Revenue Collection <br>(in ₹.)</td>
                                            <td>ZP Share <br>(in ₹.)</td>
                                            <td>AP Share <br>(in ₹.)</td>
                                            <td>GP Share <br>(in ₹.)</td>
                                        </tr>
                                        <tr class="value" style="font-size:15px">
                                            <td>50,000</td>
                                            <td>10,000</td>
                                            <td>20,000</td>
                                            <td>20,000</td>
                                        </tr>
                                    </table>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{----------------------------  Zila Parishad Panel Ended ----------------------------------------------------}}
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

    <script src="{{asset('mdas_assets/Chart.js-2.8.0/dist/Chart.min.js')}}"></script>
    <script src="{{asset('mdas_assets/Chart.js-2.8.0/samples/utils.js')}}"></script>
    <script type="application/javascript">


        $('#search_fyYr_id').on('change', function(e){
            e.preventDefault();
            var fy_id= $('#search_fyYr_id').val();
            window.location.href = '{{url('osr/osr_panel')}}/'+fy_id;
        });

        /*$(document).ready(function () {
            $('#dataTable1').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
        });

        //-------------------------------------3 years count--------------------------------------//
        var color = Chart.helpers.color;
        var barLineYearWiseAssetCountData = {
            labels: ['FY-2017-18', 'FY-2018-19', 'FY-2019-20'],
            datasets: [{
                label: 'Ghat',
                backgroundColor: color(window.chartColors.red).rgbString(),
                borderColor: window.chartColors.red,
                borderWidth: 0.5,
                data: [
                    10,
                    20,
                    30
                ]
            }, {
                label: 'Haat',
                backgroundColor: color(window.chartColors.blue).rgbString(),
                borderColor: window.chartColors.blue,
                borderWidth: 0.5,
                data: [
                    20,
                    10,
                    30
                ]
            }, {
                label: 'Fisheries',
                backgroundColor: color(window.chartColors.orange).rgbString(),
                borderColor: window.chartColors.blue,
                borderWidth: 0.5,
                data: [
                    30,
                    20,
                    10
                ]
            }, {
                label: 'Animal Pound',
                backgroundColor: color(window.chartColors.green).rgbString(),
                borderColor: window.chartColors.blue,
                borderWidth: 0.5,
                data: [
                    50,
                    20,
                    10
                ]
            }, {
                label: 'Other Assets',
                backgroundColor: color(window.chartColors.purple).rgbString(),
                borderColor: window.chartColors.blue,
                borderWidth: 0.5,
                data: [
                    40,
                    30,
                    10
                ]
            }]
        };


        var ctx1 = document.getElementById('count-3years').getContext('2d');
        window.myBar = new Chart(ctx1, {
            type: 'bar',
            data: barLineYearWiseAssetCountData,
            options: {
                responsive: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Revenue Resources Count(Graph View)'
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
                            labelString: 'Count(in Quantity)'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }

            }
        });


        //----------------------------------------3 years share----------------------------------------------
        var color = Chart.helpers.color;
        var barLineYearWiseShareCollectionData = {
            labels: ['FY-2017-18', 'FY-2018-19', 'FY-2019-20'],
            datasets: [{
                label: 'ZP Share',
                backgroundColor: color(window.chartColors.red).rgbString(),
                borderColor: window.chartColors.red,
                borderWidth: 0.5,
                data: [
                    10,
                    20,
                    30
                ]
            }, {
                label: 'AP Share',
                backgroundColor: color(window.chartColors.blue).rgbString(),
                borderColor: window.chartColors.blue,
                borderWidth: 0.5,
                data: [
                    20,
                    10,
                    30
                ]
            }, {
                label: 'GP Share',
                backgroundColor: color(window.chartColors.orange).rgbString(),
                borderColor: window.chartColors.blue,
                borderWidth: 0.5,
                data: [
                    30,
                    20,
                    10
                ]
            }]
        };


        var ctx = document.getElementById('share-3years').getContext('2d');
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barLineYearWiseShareCollectionData,
            options: {
                responsive: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Share Disbursed(Graph View)'
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
                            labelString: 'Amount(in Rs.)'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }

            }
        });*/

    </script>
@endsection