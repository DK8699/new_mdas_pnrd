@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{asset('mdas_assets/css/multi-select.css')}}"/>
    <style>


        .custom-header{
            padding: 10px;
            background-color: #6d133c;
            color: #eee;
        }

        .form-control {
            height: 28px;
            padding: 2px 5px;
            font-size: 12px;
        }

        label {
            font-size: 11px;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .mb40{
            margin-bottom: 40px;
        }

        .popover.top>.arrow:after {
            border-top-color: #0f436d;
        }

        .ms-container .ms-selectable li.disabled, .ms-container .ms-selection li.disabled {
            background-color: white;
            color: #333;
        }

        .ms-container .ms-selectable li.disabled.ms-selected, .ms-container .ms-selection li.disabled.ms-selected {
            color: green !important;
        }



        .rotate{
            width:50px;
            text-align:center;
            white-space:nowrap;
            -webkit-transform: rotate(-90deg);
            -moz-transform: rotate(-90deg);
            -ms-transform: rotate(-90deg);
            -o-transform: rotate(-90deg);
            transform: rotate(-90deg);
        }
        .rotate p {
            margin: 14px 0;
            display:inline-block;
            color:#0c5460;
            font-weight: 600;
            font-size: 14px;
        }
        .rotate p:before{
            content:'';
            width:0;
            padding-top:110%;/* takes width as reference, + 10% for faking some extra padding */
            display:inline-block;
            vertical-align:middle;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Home</a></li>
            <li><a href="{{url('osr/osr_panel')}}/{{encrypt($data['fy_id'])}}">OSR</a></li>
            <li class="active">Year Wise Assets</li>
        </ol>
    </div>

    <div class="container mb40">
        <a href="{{url('osr/osr_panel')}}/{{encrypt($data['fy_id'])}}" class="btn btn-warning mt20"> <i class="fa fa-arrow-left"></i> Back</a>
        <div class="row mt20 text-center" style="background-color: #fff;box-shadow: 0px 0px 13px 6px #aca8a8;border:1px solid #fff">
            <h3 style="color:blue;text-align: center">Year wise non-tax assets (Haat, Ghat, Fishery, Animal Pound)</h3>
            <hr/>
            <table class="table table-bordered">
                <thead class="bg-primary">
                    <tr>
                        <td>FY</td>
                        <td>Category</td>
                        <td>Managed by ZP</td>
                        <td>Managed by AP</td>
                        <td>Managed by GP</td>
                        <td>Pending to assign</td>
                        <td>Asset Not Selected</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>
                @foreach($data['fyList'] AS $years)
                    <tr style="border-top:2px solid green;">
                        <td rowspan="5" class="rotate"><p>{{$years->fy_name}}</p></td>
                    </tr>
                    @foreach($data['cats'] AS $cats)

                        @php
                            $zpValue=0;
                            $apValue=0;
                            $gpValue=0;
                            $totValue=0;
                            $notAssignValue=0;
                            $totValue=0;
                            $pendingValue=0;

                            if(count($data['zp_asset'])){
                                foreach($data['zp_asset'] AS $li){
                                    if($li->fy_id==$years->id && $li->cat_id==$cats->id){
                                        $zpValue=$li->count;
                                    }
                                }
                            }

                            if(count($data['ap_asset'])){
                                foreach($data['ap_asset'] AS $li){
                                    if($li->fy_id==$years->id && $li->cat_id==$cats->id){
                                        $apValue=$li->count;
                                    }
                                }
                            }

                            if(count($data['gp_asset'])){
                                foreach($data['gp_asset'] AS $li){
                                    if($li->fy_id==$years->id && $li->cat_id==$cats->id){
                                        $gpValue=$li->count;
                                    }
                                }
                            }

                            if(count($data['notselected'])){
                                foreach($data['notselected'] AS $li){
                                    if($li->fy_id==$years->id && $li->cat_id==$cats->id){
                                        $notAssignValue=$li->count;
                                    }
                                }
                            }

                            if(count($data['totData'])){
                                foreach($data['totData'][$years->id] AS $li){
                                    if($li->cat_id==$cats->id){
                                         $totValue=$li->count;
                                    }
                                }
                            }

                            $pendingValue=$totValue-($zpValue+$apValue+$gpValue+$notAssignValue);

                        @endphp

                        <tr @if($cats->id==1)style="border-top:2px solid #333"@endif>
                            <td>{{$cats->branch_name}}</td>
                            <td>
                                {{$zpValue}}
                            </td>
                            <td>
                                {{$apValue}}
                            </td>
                            <td>
                                {{$gpValue}}
                            </td>
                            <td>
                                <span style="font-size:16px;color:red;">{{$pendingValue}}</span>{{-- / {{$totValue}}--}}
                            </td>
                            <td>
                                {{$notAssignValue}}
                            </td>
                            <td>
                                <a href="{{route('osr.non_tax.year_wise_asset_shortlist', [encrypt($years->id), encrypt($cats->id)])}}" class="btn btn-success btn-xs"><i class="fa fa-search"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection


@section('custom_js')

@endsection
