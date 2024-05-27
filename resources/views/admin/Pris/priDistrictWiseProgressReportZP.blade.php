@php
$page_title="dashboard";
@endphp

@extends('layouts.app_admin_home')

@section('custom_css')
<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('mdas_assets/css/multiple-select.min.css')}}" rel="stylesheet" type="text/css" />
<style>
    .count-style {
        font-weight: 700;
        font-size: 20px;
        color: #10436d;
        text-shadow: 0px 1px 4px #19191d4f;
    }

    .m-b-50 {
        margin-bottom: 50px;
    }
</style>
@endsection

@section('content')
<div class="row">
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="active"><a href="{{route('admin.Pris.priMenu')}}"> Back To PRIs Dashboard</a></li>
    </ol>
</div>
<div class="container-fluid">
    <div class="row m-b-50">
        <div class="row-md-8 col-sm-12 col-xs-12">
            @php $sumPRIs= 0; @endphp
            @foreach($submittedZPs AS $zp)
            @php $sumPRIs= $sumPRIs+ $zp->total; @endphp
            @endforeach
            <h4>Submitted Zila Parishads</h4>
            <p>Total Members uploaded : {{$sumPRIs}}</p>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable1">
                    <thead>
                        <tr class="bg-primary">
                            <td>SL</td>
                            <td>Zila Parishad Name</td>
                            <td>No of AP</td>
                            <td>No of GP</td>
                            <td>ZP Members(ALL)</td>
                            <td>AP Members(ALL)</td>
                            <td>GP Members(ALL)</td>
                            <td>Estimated Total</td>
                            <td>Submitted Total</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach($submittedZPs AS $zp)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$zp->zila_parishad_name}}</td>
                            <td>{{$finalGivenNos[$zp->id]['no_of_aps']}}</td>
                            <td>{{$finalGivenNos[$zp->id]['no_of_gps']}}</td>
                            <td>{{$finalGivenNos[$zp->id]['tot_zp_count']}}</td>
                            <td>{{$finalGivenNos[$zp->id]['tot_ap_count']}}</td>
                            <td>{{$finalGivenNos[$zp->id]['tot_gp_count']}}</td>
                            <td>{{$finalGivenNos[$zp->id]['grand_tot']}}</td>
                            <td>
                                <a
                                    href="{{url('/admin/Pris/priDistrictWiseProgressReportZP')}}/{{$zp->id}}">{{$zp->total}}</a>
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

<div class="col-md-4 row-sm-12 row-xs-12">
    <h4>Not Submitted Zila Parishads</h4>
    <p>Total Members not uploaded : --</p>
    <div class="table-responsive m-b-50">
        <table class="table table-bordered" id="dataTable2">
            <thead>
                <tr class="bg-primary">
                    <td>SL</td>
                    <td>Zila Parishad Name</td>
                    <td>Total PRIs</td>
                </tr>
            </thead>
            <tbody>
                @php $i=1; @endphp
                @foreach($notSubmittedZPs AS $zp)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{$zp->zila_parishad_name}}</td>
                    <td> 0</td>
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
    $(document).ready(function () {
        $('#dataTable1').DataTable();

        $('#dataTable2').DataTable();

        $('#dataTable3').DataTable();

        $('#dataTable4').DataTable();
    });

    @if (session() -> has('message'))
        swal("Information", "{{ session('message') }}", "info")
    @endif
</script>
@endsection