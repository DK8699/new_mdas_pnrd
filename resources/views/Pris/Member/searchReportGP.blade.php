@php
    $page_title="PRIs_Menbers";
@endphp

@extends('layouts.app_user')

@section('custom_css')
<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <style>
        .ml-5{
            margin-left: 5px;
        }

        .w-85{
            width: 85px;
        }
    </style>
    <style>
        .mt10{
            margin-top: 10px;
        }
		.mt20{
            margin-top: 20px;
        }
        .mt30{
            margin-top: 30px;
        }
		strong{
			color:red;
		}
        #myModalAddPri .form-control{
            height:25px;
            padding:2px 5px;
            font-size: 12px;
        }
        label{
            font-size: 11px;
        }
        .Zebra_DatePicker_Icon_Wrapper{
            width:100% !important;
        }
        .table{
            margin-bottom: 0px;
            border:0px;
        }
        body{
            background-color: #eee;
        }

        #myModalAddPri .modal-body{
            padding-bottom:0px;
            background-color: rgba(125, 210, 235, 0.93);
        }
        .well{
            margin-bottom: 0px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
        </ol>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h4>GP WISE REPORT OF ZP: {{$zillas->zila_parishad_name}} , AP: {{$aps->anchalik_parishad_name}}</h4>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable1">
                        <thead>
                        <tr class="bg-primary">
                            <td>Sl</td>
                            <td>GP Name</td>
                            <td>GP President</td>
                            <td>GP Member</td>
                        </tr>
                        </thead>
                        <tbody>
                            @php $i=1; @endphp
                            @foreach($gps AS $gp)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$gp->gram_panchayat_name}}</td>
                                <td>{{$result_gp[$gp->id]['gp_president']}}</td>
                                <td>{{$result_gp[$gp->id]['gp_member']}}</td>
                            </tr>
                            @php $i++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal ADD PRIs -->



    <!-- Modal ADD PRIs Ended -->

@endsection

@section('custom_js')
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>




    <script type="application/javascript">
        $(document).ready( function () {
            $('#dataTable1').DataTable();
        } );

        @if (session()->has('message'))
            swal("Information", "{{ session('message') }}", "info")
        @endif
    </script>
@endsection