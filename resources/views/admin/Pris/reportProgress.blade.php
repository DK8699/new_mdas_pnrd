@php
    $page_title="six_form";
@endphp

@extends('layouts.app_admin_home')

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
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            <li class="active">Progress Report</li>
        </ol>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h4>Zila Parishad Wise Progress Report</h4>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr class="bg-primary">
                            <td>SL</td>
                            <td>Zila Parishad Name</td>
                            <td>ZP President</td>
                            <td>ZP Vice-President</td>
                            <td>ZP Member</td>
                            <td>Total</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>{{$zps->zila_parishad_name}}</td>
                            <td>
                                {{$progressReport["ZP".$zps->id]['P']}}
                            </td>
                            <td>
                                {{$progressReport["ZP".$zps->id]['V']}}
                            </td>
                            <td>
                                {{$progressReport["ZP".$zps->id]['M']}}
                            </td>
                            <td>{{$progressReport["ZP".$zps->id]['P'] + $progressReport["ZP".$zps->id]['V'] + $progressReport["ZP".$zps->id]['M']}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h4>Anchal Parishad Wise Progress Report</h4>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr class="bg-primary">
                            <td>SL</td>
                            <td>Anchalik Parishad Name</td>
                            <td>AP President</td>
                            <td>AP Vice-President</td>
                            <td>AP Member</td>
                            <td>Total</td>

                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($aps AS $ap)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$ap->anchalik_parishad_name}}</td>
                                <td>
                                    {{$progressReport["AP".$ap->id]['P']}}
                                </td>
                                <td>
                                    {{$progressReport["AP".$ap->id]['V']}}
                                </td>
                                <td>
                                    {{$progressReport["AP".$ap->id]['M']}}
                                </td>
                                <td>{{$progressReport["AP".$ap->id]['P']+$progressReport["AP".$ap->id]['M']}}</td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row" style="margin-bottom:40px">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h4>Gram Panchayat Wise Progress Report</h4>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable1">
                        <thead>
                        <tr class="bg-primary">
                            <td>SL</td>
                            <td>Anchalik Parishad Name</td>
                            <td>Gram Panchayat Name</td>
                            <td>GP President</td>
                            <td>GP Vice-President</td>
                            <td>GP Member</td>
                            <td>Total</td>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($gps AS $gp)
                            <tr>
                                <td>{{$i}}</td>

                                <td>{{$gp->anchalik_parishad_name}}</td>

                                <td>{{$gp->gram_panchayat_name}}</td>

                                <td>
                                    {{$progressReport["GP".$gp->id]['P']}}
                                </td>
                                <td>
                                    {{$progressReport["GP".$gp->id]['V']}}
                                </td>
                                <td>
                                    {{$progressReport["GP".$gp->id]['M']}}
                                </td>
                                <td>{{$progressReport["GP".$gp->id]['P'] + $progressReport["GP".$gp->id]['V'] + $progressReport["GP".$gp->id]['M']}}</td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
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
