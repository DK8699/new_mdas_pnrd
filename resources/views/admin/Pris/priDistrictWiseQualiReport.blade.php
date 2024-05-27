@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_admin_home')

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
                    url: '{{route('admin.Pris.priDistrictWiseQualiReport')}}',
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
