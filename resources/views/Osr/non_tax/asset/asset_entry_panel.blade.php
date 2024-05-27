@php
    $page_title="OSR Dashboard";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <style>
        .frontside .card {
            min-height: 250px;
            max-height: 320px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Home</a></li>
            <li><a href="{{url('osr/osr_panel')}}/{{encrypt($data['fy_id'])}}">OSR</a></li>
            <li class="active">Asset</li>
        </ol>
    </div>
    <div class="container" id="team">
        <div class="row">
            <h4 class="section-heading">NON-TAX REVENUE SOURCES</h4>
            @foreach($branchList AS $branch)
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="frontside">
                        <div class="card mt20">
                            <div class="card-body text-center">
                                <img class="img-fluid" src="{{asset('mdas_assets/images/'.$branch->branch_name.'.png')}}"/>

                                <h4 class="text-primary mt20">{{$branch->branch_name}}</h4>

                                @if(Auth::user()->mdas_master_role_id==2)
                                    <h4 class="text-primary text-center">{{$assetCount[$branch->id]['zpAsset']}}</h4>
                                @elseif(Auth::user()->mdas_master_role_id==3)
                                    <h4 class="text-primary text-center">{{$assetCount[$branch->id]['apAsset']}}</h4>
                                @elseif(Auth::user()->mdas_master_role_id==4)
                                    <h4 class="text-primary text-center">{{$assetCount[$branch->id]['gpAsset']}}</h4>
                                @endif

                                <a href="{{url('osr/non_tax/dw_asset_list/'.base64_encode(base64_encode($branch->id)))}}" class="btn-plus-icon btn-primary btn-sm">
                                    <i class="fa fa-plus"></i>
                                </a>

                                {{--@if(Auth::user()->mdas_master_role_id==2)
                                    <table class="table">
                                        <tr>
                                            <td>Level</td>
                                            <td>Asset Count</td>
                                            <td>Action</td>
                                        </tr>
                                        <tr>
                                            <td>ZP</td>
                                            <td>{{$assetCount[$branch->id]['zpAsset']}}</td>
                                            <td>
                                                <a href="{{url('osr/non_tax/dw_asset_list/'.base64_encode(base64_encode($branch->id)))}}" class="btn-plus-icon btn-primary btn-sm">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>APs</td>
                                            <td>{{$assetCount[$branch->id]['apAsset']}}</td>
                                            <td>
                                                <a href="{{url('osr/non_tax/dw_asset_show_list/'.base64_encode(base64_encode($branch->id))).'/'.base64_encode(base64_encode("AP"))}}" class="btn-plus-icon btn-primary btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>GPs</td>
                                            <td>{{$assetCount[$branch->id]['gpAsset']}}</td>
                                            <td>
                                                <a href="{{url('osr/non_tax/dw_asset_show_list/'.base64_encode(base64_encode($branch->id))).'/'.base64_encode(base64_encode("GP"))}}" class="btn-plus-icon btn-primary btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                @elseif(Auth::user()->mdas_master_role_id==3)
                                    <table class="table">
                                        <tr>
                                            <td>Level</td>
                                            <td>Asset Count</td>
                                            <td>Action</td>
                                        </tr>
                                        <tr>
                                            <td>AP</td>
                                            <td>{{$assetCount[$branch->id]['apAsset']}}</td>
                                            <td>
                                                <a href="{{url('osr/non_tax/dw_asset_list/'.base64_encode(base64_encode($branch->id)))}}" class="btn-plus-icon btn-primary btn-sm">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>GPs</td>
                                            <td>{{$assetCount[$branch->id]['gpAsset']}}</td>
                                            <td>
                                                <a href="{{url('osr/non_tax/dw_asset_show_list/'.base64_encode(base64_encode($branch->id))).'/'.base64_encode(base64_encode("GP"))}}" class="btn-plus-icon btn-primary btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                @elseif(Auth::user()->mdas_master_role_id==4)
                                    <table class="table">
                                        <tr>
                                            <td>Level</td>
                                            <td>Asset Count</td>
                                            <td>Action</td>
                                        </tr>
                                        <tr>
                                            <td>GP</td>
                                            <td>{{$assetCount[$branch->id]['gpAsset']}}</td>
                                            <td>
                                                <a href="{{url('osr/non_tax/dw_asset_list/'.base64_encode(base64_encode($branch->id)))}}" class="btn-plus-icon btn-primary btn-sm">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                @endif--}}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('custom_js')
    <script>

    </script>
@endsection