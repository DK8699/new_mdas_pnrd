@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_admin')

@section('custom_css')
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{asset('mdas_assets/css/multiple-select.min.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .count-style{
            font-weight: 700;font-size: 20px;color: #10436d;text-shadow: 0px 1px 4px #19191d4f;
        }
        .m-b-50{
            margin-bottom: 50px;
        }
        table.dataTable thead th, table.dataTable thead td {
            padding: 2px 5px;
            font-weight: 500;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            {{--<li class="active"></li>--}}
        </ol>
    </div>
    <div class="container-fluid">
        <div class="row m-b-50">
            <div class="col-md-6 col-sm-6 col-xs-12">
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
                            <td>Total PRIs</td>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($submittedZPs AS $zp)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$zp->zila_parishad_name}}</td>
                                <td>{{$zp->total}}
                                </td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <h4>Not Submitted Zila Parishads</h4>
                <p>Total Members not uploaded : --</p>
                <div class="table-responsive">
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

        <div class="row m-b-50">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h4>PRIs View By Gender</h4>

                @php
                    /*INITIALIZATION*/
                    $sumMenPRIs= 0;
                    $sumWomenPRIs= 0;
                    $sumAll = 0;

                    $grand_ZP_zp_M_id_P=0;
                    $grand_ZP_zp_M_id_V=0;
                    $grand_ZP_zp_M_id_M=0;

                    $grand_AP_zp_M_id_P=0;
                    $grand_AP_zp_M_id_V=0;
                    $grand_AP_zp_M_id_M=0;

                    $grand_GP_zp_M_id_P=0;
                    $grand_GP_zp_M_id_V=0;
                    $grand_GP_zp_M_id_M=0;

                    $grand_ZP_zp_W_id_P=0;
                    $grand_ZP_zp_W_id_V=0;
                    $grand_ZP_zp_W_id_M=0;

                    $grand_AP_zp_W_id_P=0;
                    $grand_AP_zp_W_id_V=0;
                    $grand_AP_zp_W_id_M=0;

                    $grand_GP_zp_W_id_P=0;
                    $grand_GP_zp_W_id_V=0;
                    $grand_GP_zp_W_id_M=0;
                @endphp

                {{--CALCULATION FOR HEAD TOTALS--}}
                @foreach($zpsG AS $zp)
                    @php

                        $sumMenPRIs= $sumMenPRIs +
                                    $finalGenderArr["ZP_zp_M_id_P".$zp->id]+
                                    $finalGenderArr["ZP_zp_M_id_V".$zp->id]+
                                    $finalGenderArr["ZP_zp_M_id_M".$zp->id]+
                                    $finalGenderArr["AP_zp_M_id_P".$zp->id]+
                                    $finalGenderArr["AP_zp_M_id_V".$zp->id]+
                                    $finalGenderArr["AP_zp_M_id_M".$zp->id]+
                                    $finalGenderArr["GP_zp_M_id_P".$zp->id]+
                                    $finalGenderArr["GP_zp_M_id_V".$zp->id]+
                                    $finalGenderArr["GP_zp_M_id_M".$zp->id];

                        $sumWomenPRIs= $sumWomenPRIs +
                                    $finalGenderArr["ZP_zp_W_id_P".$zp->id]+
                                    $finalGenderArr["ZP_zp_W_id_V".$zp->id]+
                                    $finalGenderArr["ZP_zp_W_id_M".$zp->id]+
                                    $finalGenderArr["AP_zp_W_id_P".$zp->id]+
                                    $finalGenderArr["AP_zp_W_id_V".$zp->id]+
                                    $finalGenderArr["AP_zp_W_id_M".$zp->id]+
                                    $finalGenderArr["GP_zp_W_id_P".$zp->id]+
                                    $finalGenderArr["GP_zp_W_id_V".$zp->id]+
                                    $finalGenderArr["GP_zp_W_id_M".$zp->id];

                    //===================================Men===========================================
                    $grand_ZP_zp_M_id_P = $grand_ZP_zp_M_id_P + $finalGenderArr["ZP_zp_M_id_P".$zp->id];
                    $grand_ZP_zp_M_id_V = $grand_ZP_zp_M_id_V + $finalGenderArr["ZP_zp_M_id_V".$zp->id];
                    $grand_ZP_zp_M_id_M = $grand_ZP_zp_M_id_M + $finalGenderArr["ZP_zp_M_id_M".$zp->id];

                    $grand_AP_zp_M_id_P = $grand_AP_zp_M_id_P + $finalGenderArr["AP_zp_M_id_P".$zp->id];
                    $grand_AP_zp_M_id_V = $grand_AP_zp_M_id_V + $finalGenderArr["AP_zp_M_id_V".$zp->id];
                    $grand_AP_zp_M_id_M = $grand_AP_zp_M_id_M + $finalGenderArr["AP_zp_M_id_M".$zp->id];

                    $grand_GP_zp_M_id_P = $grand_GP_zp_M_id_P + $finalGenderArr["GP_zp_M_id_P".$zp->id];
                    $grand_GP_zp_M_id_V = $grand_GP_zp_M_id_V + $finalGenderArr["GP_zp_M_id_V".$zp->id];
                    $grand_GP_zp_M_id_M = $grand_GP_zp_M_id_M + $finalGenderArr["GP_zp_M_id_M".$zp->id];
                    //===================================Women===========================================
                    $grand_ZP_zp_W_id_P = $grand_ZP_zp_W_id_P + $finalGenderArr["ZP_zp_W_id_P".$zp->id];
                    $grand_ZP_zp_W_id_V = $grand_ZP_zp_W_id_V + $finalGenderArr["ZP_zp_W_id_V".$zp->id];
                    $grand_ZP_zp_W_id_M = $grand_ZP_zp_W_id_M + $finalGenderArr["ZP_zp_W_id_M".$zp->id];

                    $grand_AP_zp_W_id_P = $grand_AP_zp_W_id_P + $finalGenderArr["AP_zp_W_id_P".$zp->id];
                    $grand_AP_zp_W_id_V = $grand_AP_zp_W_id_V + $finalGenderArr["AP_zp_W_id_V".$zp->id];
                    $grand_AP_zp_W_id_M = $grand_AP_zp_W_id_M + $finalGenderArr["AP_zp_W_id_M".$zp->id];

                    $grand_GP_zp_W_id_P = $grand_GP_zp_W_id_P + $finalGenderArr["GP_zp_W_id_P".$zp->id];
                    $grand_GP_zp_W_id_V = $grand_GP_zp_W_id_V + $finalGenderArr["GP_zp_W_id_V".$zp->id];
                    $grand_GP_zp_W_id_M = $grand_GP_zp_W_id_M + $finalGenderArr["GP_zp_W_id_M".$zp->id];

                    @endphp
                @endforeach
                @php
                        $sumAll = $sumWomenPRIs + $sumMenPRIs;
                @endphp
                <p>Total Men Members : <span class="count-style"> {{$sumMenPRIs}} </span> |
                    Total Women Members : <span class="count-style"> {{$sumWomenPRIs}}</span> |
                    Total PRI Members : <span class="count-style"> {{$sumAll}}</span></p>

                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable3">
                        <thead>
                        <tr class="bg-primary">

                            <td rowspan="3">SL</td>
                            <td rowspan="3">Zila Parishad</td>
                            <th colspan="6">ZP</th>
                            <th colspan="6">AP </th>
                            <th colspan="6">GP </th>
                            <td rowspan="3">Total PRIs</td>
                        </tr>
                        <tr class="bg-primary">
                            <th colspan="2">President</th>
                            <th colspan="2">VicePresident</th>
                            <th colspan="2">Member</th>

                            <th colspan="2">President</th>
                            <th colspan="2">VicePresident</th>
                            <th colspan="2">Member</th>

                            <th colspan="2">President</th>
                            <th colspan="2">VicePresident</th>
                            <th colspan="2">Member</th>
                        </tr>
                        <tr class="bg-primary">
                            <td>M</td>
                            <td>F</td>
                            <td>M</td>
                            <td>F</td>
                            <td>M</td>
                            <td>F</td>
                            <td>M</td>
                            <td>F</td>
                            <td>M</td>
                            <td>F</td>
                            <td>M</td>
                            <td>F</td>
                            <td>M</td>
                            <td>F</td>
                            <td>M</td>
                            <td>F</td>
                            <td>M</td>
                            <td>F</td>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($zpsG AS $zp)

                            <tr>
                                <td> {{$i}} </td>
                                <td> {{$zp->zila_parishad_name}} </td>
                                <td> {{$finalGenderArr["ZP_zp_M_id_P".$zp->id]}} </td>
                                <td> {{$finalGenderArr["ZP_zp_W_id_P".$zp->id]}} </td>
                                <td> {{$finalGenderArr["ZP_zp_M_id_V".$zp->id]}} </td>
                                <td> {{$finalGenderArr["ZP_zp_W_id_V".$zp->id]}} </td>
                                <td> {{$finalGenderArr["ZP_zp_M_id_M".$zp->id]}} </td>
                                <td> {{$finalGenderArr["ZP_zp_W_id_M".$zp->id]}} </td>

                                <td> {{$finalGenderArr["AP_zp_M_id_P".$zp->id]}} </td>
                                <td> {{$finalGenderArr["AP_zp_W_id_P".$zp->id]}} </td>
                                <td> {{$finalGenderArr["AP_zp_M_id_V".$zp->id]}} </td>
                                <td> {{$finalGenderArr["AP_zp_W_id_V".$zp->id]}} </td>
                                <td> {{$finalGenderArr["AP_zp_M_id_M".$zp->id]}} </td>
                                <td> {{$finalGenderArr["AP_zp_W_id_M".$zp->id]}} </td>

                                <td> {{$finalGenderArr["GP_zp_M_id_P".$zp->id]}} </td>
                                <td> {{$finalGenderArr["GP_zp_W_id_P".$zp->id]}} </td>
                                <td> {{$finalGenderArr["GP_zp_M_id_V".$zp->id]}} </td>
                                <td> {{$finalGenderArr["GP_zp_W_id_V".$zp->id]}} </td>
                                <td> {{$finalGenderArr["GP_zp_M_id_M".$zp->id]}} </td>
                                <td> {{$finalGenderArr["GP_zp_W_id_M".$zp->id]}} </td>

                                <td>M={{
                                    $finalGenderArr["ZP_zp_M_id_P".$zp->id]+
                                    $finalGenderArr["ZP_zp_M_id_V".$zp->id]+
                                    $finalGenderArr["ZP_zp_M_id_M".$zp->id]+
                                    $finalGenderArr["AP_zp_M_id_P".$zp->id]+
                                    $finalGenderArr["AP_zp_M_id_V".$zp->id]+
                                    $finalGenderArr["AP_zp_M_id_M".$zp->id]+
                                    $finalGenderArr["GP_zp_M_id_P".$zp->id]+
                                    $finalGenderArr["GP_zp_M_id_V".$zp->id]+
                                    $finalGenderArr["GP_zp_M_id_M".$zp->id]
                                    }}
                                    W={{
                                    $finalGenderArr["ZP_zp_W_id_P".$zp->id]+
                                    $finalGenderArr["ZP_zp_W_id_V".$zp->id]+
                                    $finalGenderArr["ZP_zp_W_id_M".$zp->id]+
                                    $finalGenderArr["AP_zp_W_id_P".$zp->id]+
                                    $finalGenderArr["AP_zp_W_id_V".$zp->id]+
                                    $finalGenderArr["AP_zp_W_id_M".$zp->id]+
                                    $finalGenderArr["GP_zp_W_id_P".$zp->id]+
                                    $finalGenderArr["GP_zp_W_id_V".$zp->id]+
                                    $finalGenderArr["GP_zp_W_id_M".$zp->id]
                                    }}

                                </td>
                            </tr>
                            @php $i++; @endphp

                            @php

                            @endphp
                        @endforeach
                        </tbody>
                        <tfoot class="bg-danger">
                        <tr>
                            <td colspan="2">Total</td>
                            <td>{{$grand_ZP_zp_M_id_P}}</td>
                            <td>{{$grand_ZP_zp_W_id_P}}</td>

                            <td>{{$grand_ZP_zp_M_id_V}}</td>
                            <td>{{$grand_ZP_zp_W_id_V}}</td>

                            <td>{{$grand_ZP_zp_M_id_M}}</td>
                            <td>{{$grand_ZP_zp_W_id_M}}</td>

                            <td>{{$grand_AP_zp_M_id_P}}</td>
                            <td>{{$grand_AP_zp_W_id_P}}</td>

                            <td>{{$grand_AP_zp_M_id_V}}</td>
                            <td>{{$grand_AP_zp_W_id_V}}</td>

                            <td>{{$grand_AP_zp_M_id_M}}</td>
                            <td>{{$grand_AP_zp_W_id_M}}</td>

                            <td>{{$grand_GP_zp_M_id_P}}</td>
                            <td>{{$grand_GP_zp_W_id_P}}</td>

                            <td>{{$grand_GP_zp_M_id_V}}</td>
                            <td>{{$grand_GP_zp_W_id_V}}</td>

                            <td>{{$grand_GP_zp_M_id_M}}</td>
                            <td>{{$grand_GP_zp_W_id_M}}</td>
                            <td>M={{$sumMenPRIs}} W={{$sumWomenPRIs}}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="row m-b-50">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h4>PRIs View By Qualification</h4>
                <div class="row">
                    <div class="col-md-8 col-sm-6 col-xs-12">
                        <p>Total : <span class="count-style hQSum"> 0</span> | Qualification Selected: <label id="hQList"> Please select qualification..</label></p>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group pull-right">
                            <form id="quali">
                            <select name="h_q_select[]" id="h_q_select" multiple="multiple" placeholder="Choose Qualifications" required>
                                @foreach($qualifications AS $qual)
                                    <option value="{{$qual->id}}">{{$qual->qual_name}}</option>
                                @endforeach
                            </select>
                                <button type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable4">
                        <thead>
                        <tr class="bg-primary">
                            <td>SL</td>
                            <td>Zila Parishad</td>
                            <td>ZP</td>
                            <td>AP</td>
                            <td>GP</td>
                            <td>Total</td>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('mdas_assets/js/multiple-select.min.js')}}"></script>
    <script type="application/javascript">
        $('#h_q_select').multipleSelect({
            width: 230
        });

        $(document).ready(function () {
            $('#dataTable1').DataTable();

            $('#dataTable2').DataTable();

            $('#dataTable3').DataTable();

            $('#dataTable4').DataTable();
        });

        $('#quali').submit(function(e){

            e.preventDefault();

            if ($.fn.DataTable.isDataTable('#dataTable4') ) {
                $('#dataTable4').dataTable().fnClearTable();
                $('#dataTable4').dataTable().fnDestroy()

            }

            var q_ids= $('#h_q_select').val();
            $('.hQSum').text('0');
            $('#hQList').text('Please select qualification..');

            if(q_ids.length > 0){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.Pris.priDistrictWiseProgressReport')}}',
                    dataType: "json",
                    data: {h_q_select : q_ids},
                    success: function (data) {
                        if (data.msgType == true) {

                            var dataSet=data.data.results;

                            $('.hQSum').text(data.data.hQSum);

                            var hQList = data.data.hQList;
                            document.getElementById("hQList").innerHTML = hQList.join();

                            $('#dataTable4').DataTable( {
                                data: dataSet,
                                columns: [
                                    { title: "SL" },
                                    { title: "Zila Parishad" },
                                    { title: "ZP" },
                                    { title: "AP" },
                                    { title: "GP" },
                                    { title: "Total" }
                                ]
                            } );
                        }else{
                            swal(data.msg);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                    },
                    complete: function (data) {
                        $('.page-loader-wrapper').fadeOut();
                    }
                });
            }else{
                $('#dataTable4').DataTable();
            }
        });

        @if (session()->has('message'))
        swal("Information", "{{ session('message') }}", "info")
        @endif
    </script>
@endsection
