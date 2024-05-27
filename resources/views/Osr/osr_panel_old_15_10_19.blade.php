@php
    $page_title="OSR Dashboard";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <link href="https://fonts.googleapis.com/css?family=Old+Standard+TT&display=swap" rel="stylesheet">
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
        
        .cart{
            background: #ffffff;
            border: #c13b13 solid 1px;
        }

        
        .money_txt{
            font-size: 12px;
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
                    <div class="card-body cart">
                        <div class="table-responsive" style="background-color: #fdfdfd">
                            <table class="table table-bordered table-condensed">
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
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="card-header">Non-Tax Revenue Sources (Haat, Ghat, Fisheries, Animal Pound)</div>
                    <div class="card-body cart">
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
                <ul class="nav nav-pills">
                    <li class="active"><a data-toggle="tab" href="#settlement">Settlement Percentage</a></li>
                    <li><a data-toggle="tab" href="#defaulter">Defaulter</a></li>
                    <li><a data-toggle="tab" href="#collection">Revenue Collection</a></li>
                    <li><a data-toggle="tab" href="#share">Share Distribution</a></li>
                </ul>
            </div>
        </div>
        <div class="row mt40" style="margin-bottom:40px;">
            @if(Auth::user()->mdas_master_role_id==2) {{--ZP Admin----------------------------------------------------------}}
                <div class="tab-content">
                    <div id="settlement" class="tab-pane fade in active">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Settlement Percentage (ZP)
                                </div>
                                <div class="card-body cart">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar"
                                                 aria-valuemin="0" aria-valuemax="100" style="width:{{$data['zp_settlement']['zp']['percent']}}%">
                                                {{$data['zp_settlement']['zp']['percent']."%"}}
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-condensed">
                                        <tr class="bg-info">
                                            <td>Total scope</td>
                                            <td>Shorlisted</td>
                                            <td>Settled</td>
                                        </tr>
                                        <tr>
                                            <td>{{$data['zp_settlement']['zp']['totalScope']}}</td>
                                            <td>{{$data['zp_settlement']['zp']['shortlist']}}</td>
                                            <td>{{$data['zp_settlement']['zp']['settled']}}</td>
                                        </tr>
                                    </table>
                                    <div class="text-right">
                                        <a href="{{route('osr.non_tax.asset.common.branch_list_settlement_defaulter', [encrypt($data['fy_id']), encrypt('SETTLEMENT'), encrypt('ZP'), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">
                                            more details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Settlement Percentage (AP)
                                </div>
                                <div class="card-body cart">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar"
                                                 aria-valuemin="0" aria-valuemax="100" style="width:{{$data['zp_settlement']['aps']['percent']}}%">
                                            {{$data['zp_settlement']['aps']['percent']."%"}}
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <table class="table table-bordered table-condensed">
                                            <tr class="bg-info">
                                                <td>Total scope</td>
                                                <td>Shorlisted</td>
                                                <td>Settled</td>
                                            </tr>
                                            <tr>
                                                <td>{{$data['zp_settlement']['aps']['totalScope']}}</td>
                                                <td>{{$data['zp_settlement']['aps']['shortlist']}}</td>
                                                <td>{{$data['zp_settlement']['aps']['settled']}}</td>
                                            </tr>
                                        </table>
                                        <div class="text-right">
                                            <a href="{{url('osr/non_tax/asset/zp/ap_list_asset_settlement_percent')}}/{{encrypt($data['fy_id'])}}}}">
                                                more details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Settlement Percentage (GP)
                                </div>

                                <div class="card-body cart">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar"
                                             aria-valuemin="0" aria-valuemax="100" style="width:{{$data['zp_settlement']['gps']['percent']}}%">
                                            {{$data['zp_settlement']['gps']['percent']."%"}}
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <table class="table table-bordered table-condensed">
                                            <tr class="bg-info">
                                                <td>Total scope</td>
                                                <td>Shorlisted</td>
                                                <td>Settled</td>
                                            </tr>
                                            <tr>
                                                <td>{{$data['zp_settlement']['gps']['totalScope']}}</td>
                                                <td>{{$data['zp_settlement']['gps']['shortlist']}}</td>
                                                <td>{{$data['zp_settlement']['gps']['settled']}}</td>
                                            </tr>
                                        </table>
                                        <div class="text-right">
                                            <a href="{{url('osr/non_tax/asset/zp/gp_list_asset_settlement_percent')}}/{{encrypt($data['fy_id'])}}">
                                                more details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="defaulter" class="tab-pane fade">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Defaulter (ZP)
                                </div>
                                <div class="card-body cart">
                                    <table class="table table-bordered table-condensed">
                                            <tr class="bg-info">
                                                <td>Settled Asset</td>
                                                <td>Defaulter</td>
                                            </tr>
                                            <tr>
                                                <td>{{$data['zp_settlement']['zp']['settled']}}</td>
                                                <td>{{$data['zp_defaulter']['zp']['defaulter']}}</td>
                                            </tr>
                                    </table>
                                    <div class="text-right">
                                        <a href="{{route('osr.non_tax.asset.common.branch_list_settlement_defaulter', [encrypt($data['fy_id']), encrypt('DEFAULTER'), encrypt('ZP'), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">
                                            more details
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Defaulter (AP)
                                </div>

                                <div class="card-body cart">

                                        <table class="table table-bordered table-condensed">
                                            <tr class="bg-info">
                                                <td>Settled Asset</td>
                                                <td>Defaulter</td>
                                            </tr>
                                            <tr>
                                                <td>{{$data['zp_settlement']['aps']['settled']}}</td>
                                                <td>{{$data['zp_defaulter']['aps']['defaulter']}}</td>
                                            </tr>
                                        </table>
                                        <div class="text-right">
                                            <a href="{{url('osr/non_tax/asset/zp/ap_list_asset_defaulter')}}/{{encrypt($data['fy_id'])}}">
                                                more details
                                            </a>
                                        </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Defaulter (GP)
                                </div>
                                <div class="card-body cart">

                                        <table class="table table-bordered table-condensed">
                                            <tr class="bg-info">
                                                <td>Settled Asset</td>
                                                <td>Defaulter</td>
                                            </tr>
                                            <tr>
                                                <td>{{$data['zp_settlement']['gps']['settled']}}</td>
                                                <td>{{$data['zp_defaulter']['gps']['defaulter']}}</td>
                                            </tr>
                                        </table>
                                        <div class="text-right">
                                            <a href="{{url('osr/non_tax/asset/zp/gp_list_asset_defaulter')}}/{{encrypt($data['fy_id'])}}">
                                                more details
                                            </a>
                                        </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="collection" class="tab-pane fade">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Revenue Collection (ZP)
                                </div>
                                <div class="card-body cart">
                                    <table class="table table-bordered table-condensed">
                                        <tr class="bg-info">
                                            <td>Gap Period Revenue Collection <br>(in ₹.)</td>
                                            <td>Revenue Collection from BID <br>(in ₹.)</td>
                                            <td>Total Revenue Collection <br>(in ₹.)</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['zp_revenue']['zp'][$data['fy_id']]['gap_c']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['zp_revenue']['zp'][$data['fy_id']]['bid_c']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['zp_revenue']['zp'][$data['fy_id']]['tot_c']}}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="text-right">
                                        <a href="{{route('osr.non_tax.asset.common.branch_list_revenue_share', [encrypt($data['fy_id']), encrypt("REVENUE"), encrypt("ZP"), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">
                                            more details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Revenue Collection (AP)
                                </div>
                                <div class="card-body cart">
                                    <table class="table table-bordered table-condensed">
                                                <tr class="bg-info">
                                                    <td>Gap Period Revenue Collection <br>(in ₹.)</td>
                                                    <td>Revenue Collection from BID <br>(in ₹.)</td>
                                                    <td>Total Revenue Collection <br>(in ₹.)</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span class="money_txt">
                                                            {{$data['zp_revenue']['aps'][$data['fy_id']]['gap_c']}}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="money_txt">
                                                            {{$data['zp_revenue']['aps'][$data['fy_id']]['bid_c']}}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="money_txt">
                                                            {{$data['zp_revenue']['aps'][$data['fy_id']]['tot_c']}}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                    <div class="text-right">
                                        <a href="{{url('osr/non_tax/asset/zp/ap_list_asset_collection')}}/{{encrypt($data['fy_id'])}}">
                                            more details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Revenue Collection (GP)
                                </div>
                                <div class="card-body cart">
                                        <table class="table table-bordered table-condensed">
                                            <tr class="bg-info">
                                                <td>Gap Period Revenue Collection <br>(in ₹.)</td>
                                                <td>Revenue Collection from BID <br>(in ₹.)</td>
                                                <td>Total Revenue Collection <br>(in ₹.)</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['zp_revenue']['gps'][$data['fy_id']]['gap_c']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['zp_revenue']['gps'][$data['fy_id']]['bid_c']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['zp_revenue']['gps'][$data['fy_id']]['tot_c']}}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="text-right">
                                            <a href="{{url('osr/non_tax/asset/zp/gp_list_asset_collection')}}/{{encrypt($data['fy_id'])}}">
                                                more details
                                            </a>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="share" class="tab-pane fade">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Share Distributed By ZP
                                </div>

                                    <div class="card-body cart">
                                        <table class="table table-bordered table-condensed">
                                            <tr class="bg-info">
                                                <td>Total Revenue Collection<br>(in ₹.)</td>
                                                <td>ZP Share<br>(in ₹.)</td>
                                                <td>AP Share<br>(in ₹.)</td>
                                                <td>GP Share<br>(in ₹.)</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['zp_share_dis']['zp'][$data['fy_id']]['tot_r_c']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['zp_share_dis']['zp'][$data['fy_id']]['zp_share']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['zp_share_dis']['zp'][$data['fy_id']]['ap_share']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['zp_share_dis']['zp'][$data['fy_id']]['gp_share']}}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="text-right">
                                            <a href="{{route('osr.non_tax.asset.common.branch_list_revenue_share', [encrypt($data['fy_id']), encrypt("SHARE"), encrypt("ZP"), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">
                                                more details
                                            </a>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Share Distributed By AP
                                </div>

                                    <div class="card-body cart">
                                        <table class="table table-bordered table-condensed">
                                            <tr class="bg-info">
                                                <td>Total Revenue Collection<br>(in ₹.)</td>
                                                <td>ZP Share<br>(in ₹.)</td>
                                                <td>AP Share<br>(in ₹.)</td>
                                                <td>GP Share<br>(in ₹.)</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['zp_share_dis']['aps'][$data['fy_id']]['tot_r_c']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['zp_share_dis']['aps'][$data['fy_id']]['zp_share']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['zp_share_dis']['aps'][$data['fy_id']]['ap_share']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['zp_share_dis']['aps'][$data['fy_id']]['gp_share']}}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="text-right">
                                            <a href="{{url('osr/non_tax/asset/zp/ap_list_asset_share')}}/{{encrypt($data['fy_id'])}}">
                                                more details
                                            </a>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="cardd animated zoomIn mt30">
                                <div class="card-header">
                                    Share Distributed By GP
                                </div>
                                    <div class="card-body cart">
                                        <table class="table table-bordered table-condensed">
                                            <tr class="bg-info">
                                                <td>Total Revenue Collection<br>(in ₹.)</td>
                                                <td>ZP Share<br>(in ₹.)</td>
                                                <td>AP Share<br>(in ₹.)</td>
                                                <td>GP Share<br>(in ₹.)</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['zp_share_dis']['gps'][$data['fy_id']]['tot_r_c']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['zp_share_dis']['gps'][$data['fy_id']]['zp_share']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['zp_share_dis']['gps'][$data['fy_id']]['ap_share']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['zp_share_dis']['gps'][$data['fy_id']]['gp_share']}}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="text-right">
                                            <a href="{{url('osr/non_tax/asset/zp/gp_list_asset_share')}}/{{encrypt($data['fy_id'])}}">
                                                more details
                                            </a>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(Auth::user()->mdas_master_role_id==3) {{--AP Admin------------------------------------------------------}}
                <div class="tab-content">
                    <div id="settlement" class="tab-pane fade in active">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Settlement Percentage (AP)
                                </div>

                                <div class="card-body cart">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar"
                                             aria-valuemin="0" aria-valuemax="100" style="width:{{$data['ap_settlement']['ap']['percent']}}%">
                                            {{$data['ap_settlement']['ap']['percent']."%"}}
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <table class="table table-bordered table-condensed">
                                            <tr>
                                                <td>Total scope</td>
                                                <td>Shorlisted</td>
                                                <td>Settled</td>
                                            </tr>
                                            <tr>
                                                <td>{{$data['ap_settlement']['ap']['totalScope']}}</td>
                                                <td>{{$data['ap_settlement']['ap']['shortlist']}}</td>
                                                <td>{{$data['ap_settlement']['ap']['settled']}}</td>
                                            </tr>
                                        </table>
                                        <div class="text-right">
                                            <a href="{{route('osr.non_tax.asset.common.branch_list_settlement_defaulter', [encrypt($data['fy_id']), encrypt('SETTLEMENT'), encrypt('AP'), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">
                                                more details
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Settlement Percentage (GP)
                                </div>

                                <div class="card-body cart">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar"
                                             aria-valuemin="0" aria-valuemax="100" style="width:{{$data['ap_settlement']['gps']['percent']}}%%">
                                            {{$data['ap_settlement']['gps']['percent']."%"}}
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <table class="table table-bordered table-condensed">
                                            <tr>
                                                <td>Total scope</td>
                                                <td>Shorlisted</td>
                                                <td>Settled</td>
                                            </tr>
                                            <tr>
                                                <td>{{$data['ap_settlement']['gps']['totalScope']}}</td>
                                                <td>{{$data['ap_settlement']['gps']['shortlist']}}</td>
                                                <td>{{$data['ap_settlement']['gps']['settled']}}</td>
                                            </tr></table><div class="text-right">
                                            <a href="{{url('osr/non_tax/asset/ap/gp_list_asset_settlement_percent')}}/{{encrypt($data['fy_id'])}}">
                                                more details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="defaulter" class="tab-pane fade">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Defaulter (AP)
                                </div>
                                <div class="card-body cart">
                                    <table class="table table-bordered table-condensed">
                                        <tr class="bg-info">
                                            <td>Settled Asset</td>
                                            <td>Defaulter</td>
                                        </tr>
                                        <tr>
                                            <td>{{$data['ap_settlement']['ap']['settled']}}</td>
                                            <td>{{$data['ap_defaulter']['ap']['defaulter']}}</td>
                                        </tr>
                                    </table>
                                    <div class="text-right">
                                        <a href="{{route('osr.non_tax.asset.common.branch_list_settlement_defaulter', [encrypt($data['fy_id']), encrypt('DEFAULTER'), encrypt('AP'), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">
                                            more details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Defaulter (GP)
                                </div>
                                <div class="card-body cart">
                                    <table class="table table-bordered table-condensed">
                                        <tr class="bg-info">
                                            <td>Settled Asset</td>
                                            <td>Defaulter</td>
                                        </tr>
                                        <tr>
                                            <td>{{$data['ap_settlement']['gps']['settled']}}</td>
                                            <td>{{$data['ap_defaulter']['gps']['defaulter']}}</td>
                                        </tr>
                                    </table>
                                    <div class="text-right">
                                        <a href="{{url('osr/non_tax/asset/ap/gp_list_asset_defaulter')}}/{{encrypt($data['fy_id'])}}">
                                                more details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="collection" class="tab-pane fade">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Revenue Collection (AP)
                                </div>

                                <div class="card-body cart">
                                    <table class="table table-bordered table-condensed">
                                        <tr class="bg-info">
                                            <td>Total Collection From Asset <br>(in ₹.)</td>
                                            <td>Total Gap Period Collection <br>(in ₹.)</td>
                                            <td>Grand Total <br>(in ₹.)</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                        <span class="money_txt">
                                                            {{$data['ap_revenue']['ap'][$data['fy_id']]['gap_c']}}
                                                        </span>
                                            </td>
                                            <td>
                                                        <span class="money_txt">
                                                            {{$data['ap_revenue']['ap'][$data['fy_id']]['bid_c']}}
                                                        </span>
                                            </td>
                                            <td>
                                                        <span class="money_txt">
                                                            {{$data['ap_revenue']['ap'][$data['fy_id']]['tot_c']}}
                                                        </span>
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="text-right">
                                        <a href="{{route('osr.non_tax.asset.common.branch_list_revenue_share', [encrypt($data['fy_id']), encrypt("REVENUE"), encrypt("AP"), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">
                                            more details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Revenue Collection (GP)
                                </div>

                                    <div class="card-body cart">
                                            <table class="table table-bordered table-condensed">
                                                <tr>
                                                    <td>Total Collection From Asset <br>(in ₹.)</td>
                                                    <td>Total Gap Period Collection <br>(in ₹.)</td>
                                                    <td>Grand Total <br>(in ₹.)</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span class="money_txt">
                                                            {{$data['ap_revenue']['gps'][$data['fy_id']]['gap_c']}}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="money_txt">
                                                            {{$data['ap_revenue']['gps'][$data['fy_id']]['bid_c']}}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="money_txt">
                                                            {{$data['ap_revenue']['gps'][$data['fy_id']]['tot_c']}}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                            <div class="text-right">
                                                <a href="{{url('osr/non_tax/asset/ap/gp_list_asset_collection')}}/{{encrypt($data['fy_id'])}}">
                                                    more details
                                                </a>
                                            </div>
                                    </div>

                            </div>
                        </div>
                    </div>

                    <div id="share" class="tab-pane fade">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Share Distributed By AP
                                </div>

                                    <div class="card-body cart">
                                        <table class="table table-bordered table-condensed">
                                            <tr class="bg-info">
                                                <td>Total Revenue Collection<br>(in ₹.)</td>
                                                <td>ZP Share<br>(in ₹.)</td>
                                                <td>AP Share<br>(in ₹.)</td>
                                                <td>GP Share<br>(in ₹.)</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['ap_share_dis']['ap'][$data['fy_id']]['tot_r_c']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['ap_share_dis']['ap'][$data['fy_id']]['zp_share']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['ap_share_dis']['ap'][$data['fy_id']]['ap_share']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['ap_share_dis']['ap'][$data['fy_id']]['gp_share']}}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="text-right">
                                            <a href="{{route('osr.non_tax.asset.common.branch_list_revenue_share', [encrypt($data['fy_id']), encrypt("SHARE"), encrypt("AP"), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">
                                                more details
                                            </a>
                                        </div>

                                    </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Share Distributed By GP
                                </div>

                                    <div class="card-body cart">
                                        <table class="table table-bordered table-condensed">
                                            <tr class="bg-info">
                                                <td>Total Revenue Collection<br>(in ₹.)</td>
                                                <td>ZP Share<br>(in ₹.)</td>
                                                <td>AP Share<br>(in ₹.)</td>
                                                <td>GP Share<br>(in ₹.)</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['ap_share_dis']['gps'][$data['fy_id']]['tot_r_c']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['ap_share_dis']['gps'][$data['fy_id']]['zp_share']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['ap_share_dis']['gps'][$data['fy_id']]['ap_share']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['ap_share_dis']['gps'][$data['fy_id']]['gp_share']}}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="text-right">
                                            <a href="{{url('osr/non_tax/asset/ap/gp_list_asset_share')}}/{{encrypt($data['fy_id'])}}">
                                                more details
                                            </a>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(Auth::user()->mdas_master_role_id==4) {{--GP Admin------------------------------------------------------}}
                <div class="tab-content">
                    <div id="settlement" class="tab-pane fade in active">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Settlement Percentage(GP)
                                </div>
                                <div class="card-body cart">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar"
                                                 aria-valuemin="0" aria-valuemax="100" style="width:{{$data['gp_settlement']['gp']['percent']}}%">
                                                {{$data['gp_settlement']['gp']['percent']."%"}}
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <table class="table table-bordered table-condensed">
                                                <tr class="bg-info">
                                                    <td>Total scope</td>
                                                    <td>Shorlisted</td>
                                                    <td>Settled</td>
                                                </tr>
                                                <tr>
                                                    <td>{{$data['gp_settlement']['gp']['totalScope']}}</td>
                                                    <td>{{$data['gp_settlement']['gp']['shortlist']}}</td>
                                                    <td>{{$data['gp_settlement']['gp']['settled']}}</td>
                                                </tr>
                                            </table>
                                            <div class="text-right">
                                                <a href="{{route('osr.non_tax.asset.common.branch_list_settlement_defaulter', [encrypt($data['fy_id']), encrypt('SETTLEMENT'), encrypt('NULL'), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">
                                                    more details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>

                    <div id="defaulter" class="tab-pane fade">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Defaulter(GP)
                                </div>
                                <div class="card-body cart">
                                    <table class="table table-bordered table-condensed">
                                        <tr class="bg-info">
                                                <td>Settled Asset</td>
                                                <td>Defaulter</td>
                                            </tr>
                                            <tr>
                                                <td>{{$data['gp_settlement']['gp']['settled']}}</td>
                                                <td>{{$data['gp_defaulter']['gp']['defaulter']}}</td>
                                            </tr>
                                    </table>
                                    <div class="text-right">
                                        <a href="{{route('osr.non_tax.asset.common.branch_list_settlement_defaulter', [encrypt($data['fy_id']), encrypt('DEFAULTER'), encrypt('NULL'), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}" style="text-decoration:none; color:#333">
                                            more details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="collection" class="tab-pane fade">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Revenue Collection (GP)
                                </div>
                                    <div class="card-body cart">
                                            <table class="table table-bordered table-condensed">
                                                <tr class="bg-info">
                                                    <td>Gap Period Revenue Collection <br>(in ₹.)</td>
                                                    <td>Revenue Collection from BID <br>(in ₹.)</td>
                                                    <td>Total Revenue Collection <br>(in ₹.)</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span class="money_txt">
                                                            {{$data['gp_revenue']['gp'][$data['fy_id']]['gap_c']}}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="money_txt">
                                                            {{$data['gp_revenue']['gp'][$data['fy_id']]['bid_c']}}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="money_txt">
                                                            {{$data['gp_revenue']['gp'][$data['fy_id']]['tot_c']}}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                            <div class="text-right">
                                                <a href="{{route('osr.non_tax.asset.common.branch_list_revenue_share', [encrypt($data['fy_id']), encrypt("REVENUE"), encrypt("GP"), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">
                                                    more details
                                                </a>
                                            </div>
                                    </div>
                            </div>
                        </div>
                    </div>

                    <div id="share" class="tab-pane fade">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Share Distributed By GP
                                </div>

                                    <div class="card-body cart">
                                        <table class="table table-bordered table-condensed">
                                            <tr class="bg-info">
                                                <td>Total Revenue Collection<br>(in ₹.)</td>
                                                <td>ZP Share<br>(in ₹.)</td>
                                                <td>AP Share<br>(in ₹.)</td>
                                                <td>GP Share<br>(in ₹.)</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['gp_share_dis']['gp'][$data['fy_id']]['tot_r_c']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['gp_share_dis']['gp'][$data['fy_id']]['zp_share']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['gp_share_dis']['gp'][$data['fy_id']]['ap_share']}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['gp_share_dis']['gp'][$data['fy_id']]['gp_share']}}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>

                                            <div class="text-right">
                                                <a href="{{route('osr.non_tax.asset.common.branch_list_revenue_share', [encrypt($data['fy_id']), encrypt("SHARE"), encrypt("GP"), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">
                                                    more details
                                                </a>
                                            </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-------------------------------------------Other Assets--------------------------------------------------->

        <h4>OSR Non-Tax Other Assets for the year {{$data['fyData']->fy_name}}</h4>
        <hr/>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <ul class="nav nav-pills">
                    <li class="active"><a data-toggle="tab" href="#other_collection">Revenue Collection</a></li>
                    <li><a data-toggle="tab" href="#other_share">Share Distribution</a></li>
                </ul>
            </div>
        </div>

        <div class="row mt40" style="margin-bottom:40px;">
            @if(Auth::user()->mdas_master_role_id==2) {{--ZP Admin----------------------------------------------------------}}
            <div class="tab-content">
                <div id="other_collection" class="tab-pane fade in active">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                            <div class="card-header">
                                Revenue Collection (ZP)
                            </div>

                            <div class="card-body cart">
                                <table class="table table-bordered table-condensed">
                                    <tr class="bg-info">
                                        <td>Total Collection<br>(in ₹.)</td>
                                    <tr>
                                        <td>
                                                    <span class="money_txt">
                                                        {{$data['zp_other_revenue']['zp'][$data['fy_id']]['other_c']}}
                                                    </span>
                                        </td>
                                    </tr>
                                </table>
                                <div class="text-right">
                                    <a href="{{route('osr.non_tax.other_asset.common.cat_list_revenue_share', [encrypt($data['fy_id']), encrypt("REVENUE"), encrypt("ZP"), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">
                                        more details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Revenue Collection (AP)
                                </div>

                                <div class="card-body cart">

                                        <table class="table table-bordered table-condensed">
                                            <tr class="bg-info">
                                                <td>Total Collection <br> (in ₹.)</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['zp_other_revenue']['aps'][$data['fy_id']]['other_c']}}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="text-right">
                                            <a href="{{url('osr/non_tax/other_asset/zp/ap_list_other_asset_collection')}}/{{encrypt($data['fy_id'])}}">
                                                more details
                                            </a>
                                        </div>

                                </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Revenue Collection (GP)
                                </div>

                                <div class="card-body cart">
                                        <table class="table table-bordered table-condensed">
                                            <tr class="bg-info">
                                                <td>Total Collection<br>(in ₹.)</td>

                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['zp_other_revenue']['gps'][$data['fy_id']]['other_c']}}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="text-right">
                                            <a href="{{url('osr/non_tax/other_asset/zp/gp_list_other_asset_collection')}}/{{encrypt($data['fy_id'])}}">
                                                more details
                                            </a>
                                        </div>
                                </div>
                        </div>
                    </div>
                </div>

                <div id="other_share" class="tab-pane fade">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Share Distributed By ZP
                                </div>

                                <div class="card-body cart">

                                    <table class="table table-bordered table-condensed">
                                        <tr class="bg-info">
											<td>Total Revenue Collection <br>(in ₹.)</td>
                                            <td>ZP Share <br>(in ₹.)</td>
                                            <td>AP Share <br>(in ₹.)</td>
                                            <td>GP Share <br>(in ₹.)</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['zp_other_share_dis']['zp'][$data['fy_id']]['tot_r_c']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['zp_other_share_dis']['zp'][$data['fy_id']]['zp_share']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['zp_other_share_dis']['zp'][$data['fy_id']]['ap_share']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['zp_other_share_dis']['zp'][$data['fy_id']]['gp_share']}}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="text-right">
                                        <a href="{{route('osr.non_tax.other_asset.common.cat_list_revenue_share', [encrypt($data['fy_id']), encrypt("SHARE"), encrypt("ZP"), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">
                                            more details
                                        </a>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Share Distributed By AP
                                </div>

                                <div class="card-body cart">
                                    <table class="table table-bordered table-condensed">
                                        <tr class="bg-info">
											<td>Total Revenue Collection <br>(in ₹.)</td>
                                            <td>ZP Share <br>(in ₹.)</td>
                                            <td>AP Share <br>(in ₹.)</td>
                                            <td>GP Share <br>(in ₹.)</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['zp_other_share_dis']['aps'][$data['fy_id']]['tot_r_c']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['zp_other_share_dis']['aps'][$data['fy_id']]['zp_share']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['zp_other_share_dis']['aps'][$data['fy_id']]['ap_share']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['zp_other_share_dis']['aps'][$data['fy_id']]['gp_share']}}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="text-right">
                                        <a href="{{url('osr/non_tax/other_asset/zp/ap_list_other_asset_share')}}/{{encrypt($data['fy_id'])}}">
                                            more details
                                        </a>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="cardd animated zoomIn mt30">
                            <div class="card-header">
                                Share Distributed By GP
                            </div>

                                <div class="card-body cart">
                                    <table class="table table-bordered table-condensed">
                                        <tr class="bg-info">
											<td>Total Revenue Collection <br>(in ₹.)</td>
                                            <td>ZP Share <br>(in ₹.)</td>
                                            <td>AP Share <br>(in ₹.)</td>
                                            <td>GP Share <br>(in ₹.)</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['zp_other_share_dis']['gps'][$data['fy_id']]['tot_r_c']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['zp_other_share_dis']['gps'][$data['fy_id']]['zp_share']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['zp_other_share_dis']['gps'][$data['fy_id']]['ap_share']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['zp_other_share_dis']['gps'][$data['fy_id']]['gp_share']}}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="text-right">
                                        <a href="{{url('osr/non_tax/other_asset/zp/gp_list_other_asset_share')}}/{{encrypt($data['fy_id'])}}">
                                            more details
                                        </a>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
             @elseif(Auth::user()->mdas_master_role_id==3) {{--AP Admin------------------------------------------------------}}
            <div class="tab-content">
                <div id="other_collection" class="tab-pane fade in active">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Revenue Collection (AP)
                                </div>

                                <div class="card-body cart">
                                    <div class="card-body">
                                        <table class="table table-bordered table-condensed">
                                            <tr class="bg-info">
                                                <td>Total Collection<br>(in ₹.)</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['ap_other_revenue']['ap'][$data['fy_id']]['other_c']}}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>

                                        <div class="text-right">
                                            <a href="{{route('osr.non_tax.other_asset.common.cat_list_revenue_share', [encrypt($data['fy_id']), encrypt("REVENUE"), encrypt("AP"), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">
                                                more details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Revenue Collection (GP)
                                </div>

                                <div class="card-body cart">
                                    <div class="card-body">
                                        <table class="table table-bordered table-condensed">
                                            <tr class="bg-info">
                                                <td>Total Collection<br>(in ₹.)</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['ap_other_revenue']['gps'][$data['fy_id']]['other_c']}}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>

                                        <div class="text-right">
                                            <a href="{{url('osr/non_tax/other_asset/ap/gp_list_other_asset_collection')}}/{{encrypt($data['fy_id'])}}">
                                                more details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>

                <div id="other_share" class="tab-pane fade">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Share Distributed By AP
                                </div>

                                <div class="card-body cart">
                                    <table class="table table-bordered table-condensed">
                                        <tr>
                                            <td>Total Revenue Collection <br>(in ₹.)</td>
                                            <td>ZP Share <br>(in ₹.)</td>
                                            <td>AP Share <br>(in ₹.)</td>
                                            <td>GP Share <br>(in ₹.)</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['ap_other_share_dis']['ap'][$data['fy_id']]['tot_r_c']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['ap_other_share_dis']['ap'][$data['fy_id']]['zp_share']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['ap_other_share_dis']['ap'][$data['fy_id']]['ap_share']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['ap_other_share_dis']['ap'][$data['fy_id']]['gp_share']}}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="text-right">
                                        <a href="{{route('osr.non_tax.other_asset.common.cat_list_revenue_share', [encrypt($data['fy_id']), encrypt("SHARE"), encrypt("AP"), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">
                                            more details
                                        </a>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Share Distributed By GP
                                </div>

                                <div class="card-body cart">
                                    <table class="table table-bordered table-condensed">
                                        <tr>
                                            <td>Total Revenue Collection <br>(in ₹.)</td>
                                            <td>ZP Share <br>(in ₹.)</td>
                                            <td>AP Share <br>(in ₹.)</td>
                                            <td>GP Share <br>(in ₹.)</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['ap_other_share_dis']['gps'][$data['fy_id']]['tot_r_c']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['ap_other_share_dis']['gps'][$data['fy_id']]['zp_share']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['ap_other_share_dis']['gps'][$data['fy_id']]['ap_share']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['ap_other_share_dis']['gps'][$data['fy_id']]['gp_share']}}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="text-right">
                                        <a href="{{url('osr/non_tax/other_asset/ap/gp_list_other_asset_share')}}/{{encrypt($data['fy_id'])}}">
                                            more details
                                        </a>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            @elseif(Auth::user()->mdas_master_role_id==4) {{--GP Admin------------------------------------------------------}}
            <div class="tab-content">
                <div id="other_collection" class="tab-pane fade in active">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Revenue Collection (GP)
                                </div>

                                <div class="card-body cart">
                                    <div class="card-body">
                                        <table class="table table-bordered table-condensed">
                                            <tr class="bg-info">
                                                <td>Total Collection<br>(in ₹.)</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="money_txt">
                                                        {{$data['gp_other_revenue']['gp'][$data['fy_id']]['other_c']}}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>

                                        <div class="text-right">
                                            <a href="{{route('osr.non_tax.other_asset.common.cat_list_revenue_share', [encrypt($data['fy_id']), encrypt("REVENUE"), encrypt("GP"), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">
                                                more details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>

                <div id="other_share" class="tab-pane fade">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="cardd animated zoomIn">
                                <div class="card-header">
                                    Share Distributed By GP
                                </div>

                                <div class="card-body cart">
                                    <table class="table table-bordered table-condensed">
                                        <tr>
                                            <td>Total Revenue Collection <br>(in ₹.)</td>
                                            <td>ZP Share <br>(in ₹.)</td>
                                            <td>AP Share <br>(in ₹.)</td>
                                            <td>GP Share <br>(in ₹.)</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['gp_other_share_dis']['gp'][$data['fy_id']]['tot_r_c']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['gp_other_share_dis']['gp'][$data['fy_id']]['zp_share']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['gp_other_share_dis']['gp'][$data['fy_id']]['ap_share']}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="money_txt">
                                                    {{$data['gp_other_share_dis']['gp'][$data['fy_id']]['gp_share']}}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="text-right">
                                        <a href="{{route('osr.non_tax.other_asset.common.cat_list_revenue_share', [encrypt($data['fy_id']), encrypt("SHARE"), encrypt("GP"), encrypt($data['zp_id']), encrypt($data['ap_id']), encrypt($data['gp_id'])])}}">
                                            more details
                                        </a>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{----------------------------  Zila Parishad Panel Ended ----------------------------------------------------}}
    </div>
@endsection

@section('custom_js')

    <script type="application/javascript">
        $('#search_fyYr_id').on('change', function(e){
            e.preventDefault();
            var fy_id= $('#search_fyYr_id').val();
            window.location.href = '{{url('osr/osr_panel')}}/'+fy_id;
        });

        var indianRupeeFormatter = OSREC.CurrencyFormatter.getFormatter({
            currency: 'INR',
            symbol: ''
        });

        var indianRupeeFormatterText = OSREC.CurrencyFormatter.getFormatter({
            currency: 'INR',
            symbol: ''
        });

        $('.money').on('blur', function (e){
            e.preventDefault();
            var value= OSREC.CurrencyFormatter.parse($(this).val(), { locale: 'en_IN' });
            var formattedVal = indianRupeeFormatter(value);
            $(this).val(formattedVal);
        });

        OSREC.CurrencyFormatter.formatAll({
            selector: '.money_txt',
            currency: 'INR',
            symbol: ''
        });

    </script>
@endsection