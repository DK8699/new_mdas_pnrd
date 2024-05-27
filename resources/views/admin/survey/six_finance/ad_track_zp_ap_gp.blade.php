@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_admin')

@section('custom_css')
<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <style>
        span{
            padding:5px;
            font-weight: 500;
            font-size: 16px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            <li class="active">Sixth Assam State Finance</li>
        </ol>
    </div>

    <div class="container mt20">
        <div class="row" style="margin-bottom:20px">
            <div class="col-xs-6 col-md-3 col-sm-4">
                <a href="{{route('admin.survey.six_finance.track_zp')}}" class="thumbnail text-center">
                    <i class="fa fa-users fa-4x"></i>
                    <h6>ZP</h6>
                    <p><span>T: {{$zp}}</span><span>F: {{$sFV[0]['final']}}</span><span>V: {{$sFV[0]['verify']}}</span></p>
                </a>
				<a href="{{url('/admin/survey/six_finance/downloadCombined')}}/COMBINED_ZP" class="btn btn-primary">
                    Download ZP Combined
                </a>
            </div>

            <div class="col-xs-6 col-md-3 col-sm-4">
                <a href="{{route('admin.survey.six_finance.track_ap')}}" class="thumbnail text-center">
                    <i class="fa fa-users fa-4x"></i>
                    <h6>AP</h6>
                    <p><span>T: {{$ap}}</span><span>F: {{$sFV[1]['final']}}</span><span>V: {{$sFV[1]['verify']}}</span></p>
                </a>
				
				 <a href="{{url('/admin/survey/six_finance/downloadCombined')}}/COMBINED_AP" class="btn btn-primary">
                    Download AP Combined
                </a>
            </div>

            <div class="col-xs-6 col-md-3 col-sm-4">
                <a href="{{route('admin.survey.six_finance.track_gp')}}" class="thumbnail text-center">
                    <i class="fa fa-users fa-4x"></i>
                    <h6>GP</h6>
                    <p><span>T: {{$gp}}</span><span>F: {{$sFV[2]['final']}}</span><span>V: {{$sFV[2]['verify']}}</span></p>
                </a>
				<a href="{{url('/admin/survey/six_finance/downloadCombined')}}/COMBINED_GP" class="btn btn-primary">
                    Download GP Combined
                </a>
            </div>
        </div>
<div class="row">
        <div class="col-md-12" style='margin-bottom:10em;'>
            <table class="table table-responsive table-bordered" id="trackTable" >
                <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th>District</th>
                        <th>ZP</th>
                        <th>AP Total</th>
                        <th>AP Submitted</th>
                        <th>GP Total</th>
                        <th>GP Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                        $j = 1;
                    @endphp
                    @foreach($districts AS $district)
                    <tr>
                        <td>{{$j}}</td>
                        <td>{{$district['district_name']}}</td>
                        <td>
                           
                            @if($zpTrackCount[$i] > 0)
                                Yes
                            @else
                                No
                            @endif
                            
                        </td>
                        <td>
                            @if(isset($apTotalTrackCount[$i]))
                                                 {{$apTotalTrackCount[$i]}}
                                @else
                                          0
                                @endif
                        </td>
                        <td>
                                 @if(isset($apTrackCount[$i]))
                                                    {{$apTrackCount[$i]}}
                                                @else
                                                        0
                                     @endif
                        </td>
                        <td>
                             @if(isset($gpTotalTrackCount[$i]))
                                                        {{$gpTotalTrackCount[$i]}}
                             @else
                                                            0
                             @endif
                        </td>
                        <td>
                                                     @if(isset($gpTrackCount[$i]))
                                                        {{$gpTrackCount[$i]}}
                                                    @else
                                                            0
                                                    @endif
                       </td>
                    </tr>
                    @php
                        $i++;
                        $j++;
                    @endphp
                    @endforeach
                </tbody>
            </table>
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
    $(document).ready(function () {
        $('#trackTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ]
            });
    });
</script>
@endsection
