@php
    $page_title="OSR Dashboard";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <style>

    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="active">OSR</li>
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

                                <a href="{{url('osr/non_tax/dw_asset_list/'.base64_encode(base64_encode($branch->id)))}}" class="btn-plus-icon btn-primary btn-sm">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="col-xs-12 col-sm-6 col-md-3 mt10">
                    <div class="frontside">
                        <div class="card mt20">
                            <div class="card-body text-center">
                                <img class="img-fluid" src="{{asset('mdas_assets/images/other_resources.png')}}"/>

                                <h4 class="text-primary mt20">Other Resources</h4>

                                <a href="{{route('osr.non_tax.dw_asset.other_assets')}}" class="btn-plus-icon btn-primary btn-sm">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('custom_js')
<script>

</script>
@endsection