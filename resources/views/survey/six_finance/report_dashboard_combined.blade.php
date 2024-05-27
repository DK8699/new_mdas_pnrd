@php
    $page_title="six_form";
@endphp

@extends('layouts.app_user')

@section('custom_css')
    <style>

    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li><a href="{{route('dashboard')}}">Sixth Assam State Finance</a></li>
            <li class="active">Report</li>
        </ol>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row text-center mt20">
            @if(Session::has('message'))
                <p class="alert alert-info">{{ Session::get('message') }}</p>
            @endif
            <div class="col-md-3 col-sm-3 col-xs-12">
                <form action="{{route('survey.six_finance.download_distCombined')}}" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" name="req_for" value="DWC_AP"/>
                    <input type="hidden" name="d_id" value="{{$district_id}}"/>
                    <button type="submit" class="btn btn-primary">Download Combined AP</button>
                </form>
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12">
                <form action="{{route('survey.six_finance.download_distCombined')}}" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" name="req_for" value="DWC_GP"/>
                    <input type="hidden" name="d_id" value="{{$district_id}}"/>
                    <button type="submit" class="btn btn-primary">Download Combined GP</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')

    <script type="application/javascript">



    </script>
@endsection
