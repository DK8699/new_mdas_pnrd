@php
    $page_title="six_form";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="//cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>

    <style>
        .count-style{
            font-weight: 700;font-size: 20px;color: #10436d;text-shadow: 0px 1px 4px #19191d4f;
        }
        .m-b-50{
            margin-bottom: 50px;
        }
        table.dataTable thead th, table.dataTable thead td {
            font-weight: 500;
            text-align: center;
        }
        .partytd {
            font-size: 9px;
        }
        .olophoru {
            font-size: 12px;
        }
        /*    collapsible */
        .collapsible1 {
            background-color: #03A9F4;
            color: white;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
            margin-bottom: 1px;
        }

        .collapsible1:hover {
            background-color: #00BCD4;
        }

        .collapsible1:after {
            content: '\002B';
            color: white;
            font-weight: bold;
            float: right;
            margin-left: 5px;
        }

        .content {
            padding: 0 0px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 1.5s ease-out;
            background-color: #f1f1f1;
        }

    </style>
@endsection

@section('content')

    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Home</a></li>
           
            <li class="active">Download Member</li>
        </ol>
    </div>
    <div class="container-fluid">
        <h1 style="text-align: center; font-family: 'Old Standard TT', serif;"><u>PRIs View Details View And Download</u></h1>
        <button class="collapsible1">Zila Parishad PRIs Details</button>
        <div class="content">
            <div class="row m-b-50">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable1">
                            <thead>
                            <tr class="bg-primary">
                                <td>SL</td>
                                <td>Zila Parishad</td>
                                <td>Name</td>
                                <td>Gender</td>
                                <td>Mobile</td>
                                <td>Designation</td>
                                <td>Political Party</td>
                                <td>Constituency</td>
                                <td>Caste</td>
								
								<td>Qualification</td>
                                <td>DOB</td>
                                <td>Age (as on 7 Feb 2020)</td>
								
								<td>Annual Income</td>
								
                                <td>Reserved Seat</td>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach ($zpsDownload AS $zps)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$zps->zila_parishad_name}}</td>
                                    <td>{{$zps->pri_f_name}} {{$zps->pri_m_name}} {{$zps->pri_l_name}}</td>
                                    <td>{{$zps->gender_name}}</td>
                                    <td>{{$zps->mobile_no}}</td>
                                    <td>{{$zps->design_name}}</td>
                                    <td>{{$zps->party_name}}</td>
                                    <td>{{$zps->constituency}}</td>
                                    <td>{{$zps->caste_name}}</td>
									
									<td>{{$zps->qual_name}}</td>
                                    <td>{{Carbon\Carbon::parse($zps->dob)->format('d M Y')}}</td>
                                    <td>{{Carbon\Carbon::parse($zps->dob)->diff(\Carbon\Carbon::parse("2020-02-07"))->format('%y years')}}</td>
									
									<td>{{$zps->income_name}}</td>
									
                                    <td>{{$zps->seat_name}}</td>
                                </tr>
                                @php $i++; @endphp
                                @endforeach
                            </tbody>
                            <tfoot class="bg-danger">
                            <tr>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <button class="collapsible1">Anchalik Panchayat PRIs Details</button>
        <div class="content">
            <div class="row m-b-50">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable2">
                            <thead>
                            <tr class="bg-primary">
                                <td>SL</td>
                                <td>Zila Parishad</td>
                                <td>Anchalik Panchayat</td>
                                <td>Name</td>
                                <td>Gender</td>
                                <td>Mobile</td>
                                <td>Designation</td>
                                <td>Political Party</td>
                                <td>AP Constituency</td>
                                <td>Caste</td>
								<td>Qualification</td>
                                <td>DOB</td>
                                <td>Age (as on 7 Feb 2020)</td>
								<td>Annual Income</td>
                                <td>Reserved Seat</td>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach ($apsDownload AS $aps)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$aps->zila_parishad_name}}</td>
                                    <td>{{$aps->anchalik_parishad_name}}</td>
                                    <td>{{$aps->pri_f_name}} {{$aps->pri_m_name}} {{$aps->pri_l_name}}</td>
                                    <td>{{$aps->gender_name}}</td>
                                    <td>{{$aps->mobile_no}}</td>
                                    <td>{{$aps->design_name}}</td>
                                    <td>{{$aps->party_name}}</td>
                                    <td>{{$aps->gram_panchayat_name}}</td>
                                    <td>{{$aps->caste_name}}</td>
									
									<td>{{$aps->qual_name}}</td>
                                    <td>{{Carbon\Carbon::parse($aps->dob)->format('d M Y')}}</td>
                                    <td>{{Carbon\Carbon::parse($aps->dob)->diff(\Carbon\Carbon::parse("2020-02-07"))->format('%y years')}}</td>
									
									<td>{{$aps->income_name}}</td>
									
                                    <td>{{$aps->seat_name}}</td>
                                </tr>
                                @php $i++; @endphp
                                @endforeach
                            </tbody>
                            <tfoot class="bg-danger">
                            <tr>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <button class="collapsible1">Gram Panchayat PRIs Details</button>
        <div class="content">
            <div class="row m-b-50">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable3">
                            <thead>
                            <tr class="bg-primary">
                                <td>SL</td>
                                <td>Zila Parishad</td>
                                <td>Anchalik Panchayat</td>
                                <td>Gram Panchayat</td>
                                <td>Name</td>
                                <td>Gender</td>
                                <td>Mobile</td>
                                <td>Designation</td>
                                <td>Political Party</td>
                                <td>Ward No.</td>
                                <td>Caste</td>
								<td>Qualification</td>
                                <td>DOB</td>
                                <td>Age (as on 7 Feb 2020)</td>
								<td>Annual Income</td>
                                <td>Reserved Seat</td>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach ($gpsDownload AS $gps)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$gps->zila_parishad_name}}</td>
                                    <td>{{$gps->anchalik_parishad_name}}</td>
                                    <td>{{$gps->gram_panchayat_name}}</td>
                                    <td>{{$gps->pri_f_name}} {{$gps->pri_m_name}} {{$gps->pri_l_name}}</td>
                                    <td>{{$gps->gender_name}}</td>
                                    <td>{{$gps->mobile_no}}</td>
                                    <td>{{$gps->design_name}}</td>
                                    <td>{{$gps->party_name}}</td>
                                    <td>{{$gps->ward_name}}</td>
                                    <td>{{$gps->caste_name}}</td>
									
									<td>{{$gps->qual_name}}</td>
                                    <td>{{Carbon\Carbon::parse($gps->dob)->format('d M Y')}}</td>
                                    <td>{{Carbon\Carbon::parse($gps->dob)->diff(\Carbon\Carbon::parse("2020-02-07"))->format('%y years')}}</td>
									
									<td>{{$gps->income_name}}</td>
									
                                    <td>{{$gps->seat_name}}</td>
                                </tr>
                                @php $i++; @endphp
                                @endforeach
                                
                            </tbody>
                            <tfoot class="bg-danger">
                            <tr>
                            </tr>
                            </tfoot>
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
<script>
        //    collapsable
        var coll = document.getElementsByClassName("collapsible1");
        var i;

        for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var content = this.nextElementSibling;
                if (content.style.maxHeight){
                    content.style.maxHeight = null;
                } else {
                    content.style.maxHeight = content.scrollHeight +500+ "px";
                }
            });
        }
    $(document).ready(function () {
        $('#dataTable1').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ]
            });
        $('#dataTable2').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ]
            });
        $('#dataTable3').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ]
            });
    });
</script>
@endsection
