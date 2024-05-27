@php
$page_title="priMenu";
@endphp

@extends('layouts.app_admin')

@section('custom_css')
<link href="https://fonts.googleapis.com/css?family=Old+Standard+TT&display=swap" rel="stylesheet">
<script src="{{asset('mdas_assets/Chart.js-2.8.0/dist/Chart.min.js')}}"></script>
<script src="{{asset('mdas_assets/Chart.js-2.8.0/samples/utils.js')}}"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.css" rel="stylesheet" type="text/css" />
<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />

<link href="https://fonts.googleapis.com/css?family=Orbitron&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Sarpanch&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Fauna+One&display=swap" rel="stylesheet">
<style>
    .panel-body {
        padding: 13px;
    }

    button.dt-button,
    div.dt-button,
    a.dt-button {
        background-image: linear-gradient(to bottom, #4fe14f 0%, green 100%);
        color: #fff;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
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

    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        padding: 7px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #ddd;
    }

    .mt40 {
        margin-top: 40px;
    }

    .card {
        border: 1px solid #ff770f;
        background-color: #f3f2f2;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #6b133d;
        color: #fff;
        font-size: 20px;
        text-align: center;
        font-weight: 700;
        padding: 5px;
    }

    .card-body {
        padding: 10px;
        text-align: center;
    }

    .card .number {
        font-weight: 500;
        font-size: 40px;
        color: #f13333;
    }

    .card p {
        color: #f13333;
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

    .panel-primary>.panel-heading {
        background-color: rgb(255, 118, 15);
        background-image: linear-gradient(to right, #FF5722, #FF5722);
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

    table.data-header tr.data-header-head {
        font-size: 11px;
    }

    .head-txt {
        font-size: 11px;
    }
</style>
@endsection

@section('content')



<div class="container-fluid" style=" padding-bottom: 40px; padding-top: 40px;">
    <!----------------- CARDS -------------------------->
    <div class="container">
        <div class="row">
            <h2 style="text-align: center;color: #6b133d; font-family:inherit; font-weight: 700">Own Source of Revenue
                (OSR) Non-Tax Revenue Sources</h2>

            <div class="col-md-4 col-sm-4 col-xs-12 col-md-offset-8">
                <div class="form-group">
                    <label>
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        Select Financial Year
                        <select name="osr_fy_id" id="fy_year_id" class="form-control">
                            @foreach($master_fy_years as $fy_year)
                            <option value="{{base64_encode($fy_year->id)}}" @if($fy_year->id==end($lastfy)->id)
                                {{'selected'}} @endif>{{$fy_year->fy_name}}</option>

                            @endforeach
                        </select>
                    </label>

                </div>
            </div>
        </div>


        <!-- ------------------------- REVENUE COLLECTION -------------------------------------------------------------------->

        <div class="row mt40">
            
            <div class="tab-content">
                <div id="collection_table" class="tab-pane fade in active">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading" style="text-align: center">
                                {{-- Revenue Collection (Zilla Wise) for {{$fy_years}} --}}
                            </div>
                            <div class="panel-body gray-back">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="dataTable1">
                                        <thead>
                                            <tr class="tr-row head-txt">
                                                <td style="display: none">#</td>
                                                <td>Zilla Parishad</td>
                                                <td>OSR Non-Tax Asset Entries</td>
                                            </tr>
                                        </thead>

                                        <tbody id="asset-body">

                                            @foreach($datas as $data)
                                            <tr>
                                                <td>{{$data->zila_parishad_name}}</td>
                                                <td>{{$data->total}}</td>
                                            </tr>

                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="mt40"></div>

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
<script>
    $(document).on('change','#fy_year_id',function(){

        $.get("{{route('admin.Osr.nonCompletedAssetZps')}}",{
            fy:$(this).val()
        },function(j){
            var tr = "";
            for(var i = 1; i < j.length; i++){
                tr += "<tr><td>"+j[i].zp_name+"</td><td>"+j[i].asset_count+"</td></tr>";
            }
            $('#asset-body').html(tr);
        });

    })
</script>
@endsection