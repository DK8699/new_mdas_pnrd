@php
    $page_title="six_form";
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
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li><a href="{{route('dashboard')}}">Sixth Assam State Finance</a></li>
            <li class="active">Report</li>
        </ol>
    </div>
    <div class="container-fuild" style="margin-bottom: 40px">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h4>Attempted/Submitted List</h4>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable1">
                        <thead>
                        <tr class="bg-primary">
                            <td>#</td>
                            <td>Employee Code</td>
                            <td>Applicable Name</td>
                            <td>Location</td>
                            {{--<td>Basic Info</td>
                            <td>Staff Info</td>
                            <td>Revenue Info</td>
                            <td>Expenditure Info</td>
                            <td>Balance Info</td>
                            <td>Other Info</td>
                            <td>Next 5 Year Info</td>--}}
                            <td>Remaining Form Parts to filled up <br> Total: 7 parts</td>
                            <td>Final Submit</td>
                            <td>Action</td>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($results AS $res)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$res->employee_code}}</td>
                                <td>{{$res->applicable_name}}</td>
                                <td>
                                    <label>District :</label> {{$res->district_name}}
                                    @if($res->zila_parishad_name) <label> ZP :</label> {{$res->zila_parishad_name}} @endif
                                    @if($res->anchalik_parishad_name) <label> AP :</label> {{$res->anchalik_parishad_name}} @endif
                                    @if($res->gram_panchayat_name) <label> GP :</label> {{$res->gram_panchayat_name}} @endif
                                </td>
                                <td class="text-center">
                                    {{7-($res->basic_info+$res->staff_info+$res->revenue_info+
                                    $res->expenditure_info+$res->balance_info+$res->other_info+$res->five_year_info)}}
                                </td>
                                <td> @if($res->final_submission_status){{"YES"}}@else {{"NO"}} @endif</td>
                                <td>
                                   {{-- @if($res->final_submission_status)--}}
                                        <a class="btn btn-primary" href="{{url('survey/six_finance/report/download_zp_ap_gp')}}/{{$res->id}}/{{$res->employee_code}}">
                                            <i class="fa fa-download"></i> PDF
                                        </a>
                                    {{--@else
                                        <button class="btn btn-warning" type="button" data-fan="{{$res->id}}" data-df="OTH">
                                            Not Completed
                                        </button>
                                    @endif--}}
                                </td>
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
