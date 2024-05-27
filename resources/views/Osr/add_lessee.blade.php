@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_user')

@section('custom_css')

@endsection


@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li><a href="{{route('osr.dashboard')}}">OSR</a></li>
        </ol>
    </div>

    <div class="container">
         <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <a href="{{route('osr.lessee.details')}}" class="btn btn-primary pull-right">
                   <i class="fa fa-plus"></i>ADD LESSEE
                </a>
            </div>
        </div>
   </div>
@endsection
