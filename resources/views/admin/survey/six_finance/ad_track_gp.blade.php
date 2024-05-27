@php
    $page_title="six_form";
@endphp

@extends('layouts.app_admin')

@section('custom_css')
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <style>
        .ml-5{
            margin-left: 5px;
        }

        .w-85{
            width: 85px;
        }
        .text-danger{
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            <li><a href="{{route('admin.survey.six_finance')}}">Sixth Assam State Finance</a></li>
            <li><a href="{{route('admin.survey.six_finance.track_zp_ap_gp')}}">Track Report</a></li>
            <li class="active">GP List</li>
        </ol>
    </div>
    <div class="container-fuild" style="margin-bottom: 40px">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h4>GP List</h4>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable1">
                        <thead>
                        <tr class="bg-primary">
                            <td>#</td>
                            <td>Employee Code</td>
                            <td>ZP</td>
                            <td>AP</td>
                            <td>GP</td>
                            {{--<td>Basic Info</td>
                            <td>Staff Info</td>
                            <td>Revenue Info</td>
                            <td>Expenditure Info</td>
                            <td>Balance Info</td>
                            <td>Other Info</td>
                            <td>Next 5 Year Info</td>--}}
                            <td>Remaining Form Parts to filled up <br> Total: 7 parts</td>
                            <td>Final Submit</td>
                            <td>Verify</td>
                            <td>Action</td>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($results AS $res)
                            @php
                                $final=$res->basic_info+$res->staff_info+$res->revenue_info+
                                $res->expenditure_info+$res->balance_info+$res->other_info+$res->five_year_info;

                                $verify=$res->basic_info+$res->staff_info+$res->revenue_info+
                                $res->expenditure_info+$res->balance_info+$res->other_info+$res->five_year_info+$res->final_submission_status;
                            @endphp
                            <tr>
                                <td>{{$i}}</td>
                                <td>@if($res->employee_code){{$res->employee_code}}@else<span class="text-danger">{{"Not Started"}}</span>@endif</td>
                                <td>
                                    @if($res->zila_parishad_name) <label> ZP :</label> {{$res->zila_parishad_name}} @endif
                                </td>
								<td>
                                    @if($res->anchalik_parishad_name) <label> AP :</label> {{$res->anchalik_parishad_name}} @endif
                                </td>
								<td>
                                    @if($res->gram_panchayat_name) <label> GP :</label> {{$res->gram_panchayat_name}} @endif
                                </td>
                                <td class="text-center">
                                    F_{{7-$final}}
                                </td>
                                <td> @if($res->final_submission_status){{"YES"}}@else {{"NO"}} @endif</td>
                                <td> @if($res->verify){{"YES"}}@else {{"NO"}} @endif</td>
                                <td>
                                    @if($final==7)
                                        <a class="btn btn-primary" href="{{url('survey/six_finance/report/download_zp_ap_gp')}}/{{$res->id}}/{{$res->employee_code}}">
                                            <i class="fa fa-download"></i> PDF
                                        </a>
                                    @endif
                                    @if($verify==8)
                                        <button class="btn btn-warning" type="button" data-fan="{{$res->id}}" data-df="OTH">
                                            Verify
                                        </button>

                                        <button class="btn btn-danger" type="button" data-fan="{{$res->id}}" data-df="OTH">
                                            Reject
                                        </button>
                                    @endif
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
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>


    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>


    <script type="application/javascript">
        $(document).ready( function () {
            $('#dataTable1').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ]
            });
        } );

        @if (session()->has('message'))
            swal("Information", "{{ session('message') }}", "info")
        @endif
    </script>
@endsection
