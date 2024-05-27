@php
    $page_title="dashboard";
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
            <li><a href="{{route('osr.osr_panel')}}">OSR</a></li>
            <li class="active">Other Resources</li>
        </ol>
    </div>

    <div class="container mb40"  id="team">
        <h4 class="section-heading">OTHER RESOURCES</h4>

        @foreach($categories AS $cat)
            <div class="col-xs-12 col-sm-6 col-md-3 mt10">
                <div class="frontside">
                    <div class="card mt20">
                        <div class="card-body text-center">
                            <img class="img-fluid" src="{{asset('mdas_assets/images/'.$cat->cat_name.'.png')}}"/>

                            <h4 class="text-primary mt20">{{$cat->cat_name}}</h4>

                            <a href="{{url('osr/non_tax/dw_other_asset_list/'.base64_encode(base64_encode($cat->id)))}}" class="btn-plus-icon btn-primary btn-sm">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection

@section('custom_js')
    <script type="application/javascript">

    </script>
@endsection
