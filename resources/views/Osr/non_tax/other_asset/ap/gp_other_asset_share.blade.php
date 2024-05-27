@php
    $page_title="OSR Dashboard";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <style>
        .panel
        {
            border: none;
            background: #98D3F6;
        }
        label{
            color: dodgerblue;
        }
        .mb40{
            margin-bottom: 40px;
        }
        .badge-red{
            background-color: orangered;
        }
        .badge-green{
            background-color: darkgreen;
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
        <a href="javascript:history.back()" style=""><button class="btn" onMouseOver="this.style.color='#fff'" style="border-radius: 18px;margin-bottom: 2px;"><i class="fa fa-arrow-left"></i> Back</button></a>
        <div class="panel panel-primary">
            <div class="panel-heading" style="text-align: center">
                Share distibution of various NON_TAX other asset categories of Gram Panchayat : {{$data['gpData']->gram_panchayat_name}} under Anchalik Panchayat : {{$data['apData']->anchalik_parishad_name}} ({{$data['fyData']->fy_name}})
            </div>
            <div class="panel-body gray-back">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable1">
                    <thead>
                    <tr class="bg-primary">
                        <td>SL</td>
                        <td>Category</td>
                        <td>Total Revenue Collection</td>
                        <td>ZP Share</td>
                        <td>AP Share</td>
                        <td>GP Share</td>
                        <td>Total</td>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i=1; @endphp
                    @foreach($data['assetCategory'] as $category)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$category->cat_name}}</td>
                         <td>1,00,000</td>
                         <td>20,000</td>
                         <td>40,000</td>
                         <td>40,000</td>
                         <td>1,00,000</td>
                    </tr>
                    @php $i++; @endphp
                    @endforeach
                    
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
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
    <script type="application/javascript">
        $(document).ready(function () {
            $('#dataTable1').DataTable({
                dom: 'Bfrtip',
                searching: false,
                ordering: false,
                paging: false,
                info: false,
                buttons: [
                    'excel', 'print', 'pdf'
                ]
            });
        });
    </script>
@endsection