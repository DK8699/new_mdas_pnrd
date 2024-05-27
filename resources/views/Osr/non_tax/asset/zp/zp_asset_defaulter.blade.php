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
                Defaulter List of Zila Parishad : {{$data['zpData']->zila_parishad_name}} ({{$data['fyData']->fy_name}})
            </div>
            <div class="panel-body gray-back">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="dataTable1">
                        <thead>
                        <tr class="bg-primary text-center">
                            <td>SL</td>
                            <td>Category</td>
                            <td>Settled Asset</td>
                            <td>Defaulter</td>
                        </tr>
                        </thead>
                        <tbody class="text-center">
                        @php $i=1; @endphp
                        @foreach($data['branches'] as $branch)
                            <tr >
                                <td>{{$i}}</td>
                                <td><a href="{{url('osr/non_tax/asset/zp/zp_asset_defaulter_branch')}}/{{encrypt($data['fy_id'])}}/{{encrypt($branch->id)}}">{{$branch->branch_name}}</a></td>
                                <td>6</td>
								<td  data-toggle="modal" data-target="#defaulterModel" style="cursor: pointer;">1</td>
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
@foreach($data['branches'] as $branch)
<div class="modal fade" id="defaulterModel" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 33px 0 0 0;">
                <div class="modal-header" style="background-color: #ff9000">
                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    <h4 style="color: #fff;font-family: 'Old Standard TT', serif;">Defaulter List for the Financial Year FY-2019-2020</h4>
                </div>
                    <div class="modal-body">

                       <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable11" style="width:100%">
                                <thead>
                                <tr class="tr-row">
                                    <td>District</td>
                                    <td>Asset Code</td>
                                    <td>Asset Name</td>
                                    <td>Defaulter Name</td>
                                    <td>Defaulter's Father Name</td>
                                    <td>Asset Category</td>
                                    <td>Asset Level</td>
                                </tr>
                                </thead>
                                <tbody>
                                    
                                    <tr>
                                    <td>JORHAT</td>
                                    <td>JOR-290HAA-001</td>
                                    <td>Bootboriya Haat</td>
                                    <td>Rahul Pegu</td>
                                    <td>Debanan Pegu</td>
                                    <td>Haat</td>
                                    <td>ZP</td>
                                </tr>

                                
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="modal-footer">
                    </div>
            </div>
        </div>
 </div>
 @endforeach

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
