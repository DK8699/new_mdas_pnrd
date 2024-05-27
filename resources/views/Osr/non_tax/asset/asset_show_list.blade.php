@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <style>

        .well {
            margin: 0px;
        }

        .modal-body {
            background: #f5f5f5;
            padding: auto;
        }

        strong {
            color: red;
        }

        .Zebra_DatePicker_Icon_Wrapper {
            width: 100% !important;
        }

        .form-control {
            height: 28px;
            padding: 2px 5px;
            font-size: 12px;
        }

        label {
            font-size: 11px;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .mb40{
            margin-bottom: 40px;
        }

        .popover.top>.arrow:after {
            border-top-color: #0f436d;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Home</a></li>
            <li><a href="{{url('osr/osr_panel')}}/{{encrypt($data['fy_id'])}}">OSR</a></li>
            <li><a href="{{route('osr.non_tax.asset_entry_panel')}}">Asset</a></li>
        </ol>
    </div>

    @if(Auth::user()->mdas_master_role_id==2){{--ZP-------------------------------------------------------------------------}}
    <div class="container mb40">
        <div class="row mt40">
            <form action="{{route('osr.non_tax.asset_show_list')}}" method="post">
                @csrf
                <div class="col-md-3 col-sm-4 col-xs-12">
                   <div class="form-group">
                       <label>Category</label>
                       <select class="form-control" name="branch_id" id="branch_id" required>
                           <option value="">---Select---</option>
                           @foreach($data['branchList'] AS $li)
                               <option value="{{$li->id}}" @if($data['branch_id']==$li->id)selected="selected"@endif>{{$li->branch_name}}</option>
                           @endforeach
                       </select>
                   </div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-12">
                   <div class="form-group">
                       <label>Anchalik Panchayat</label>
                       <select class="form-control" name="ap_id" id="ap_id" required>
                           <option value="">---Select---</option>
                           @foreach($data['apList'] AS $li)
                               <option value="{{$li->id}}" @if($data['ap_id']==$li->id)selected="selected"@endif>{{$li->anchalik_parishad_name}}</option>
                           @endforeach
                       </select>
                   </div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>Gram Panchayat</label>
                        <select class="form-control" name="gp_id" id="gp_id">
                            <option value="">---Select---</option>

                            @foreach($data['gpList'] AS $li)
                                <option value="{{$li->id}}" @if($data['gp_id']==$li->id)selected="selected"@endif>{{$li->gram_panchayat_name}}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group mt20">
                        <button type="submit" class="btn btn-primary btn-save btn-sm">
                            <i class="fa fa-search"></i>
                            Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="row mt10">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h6 style="text-transform: uppercase;">
                    {{$data['searchText']}}
                </h6>
            </div>
        </div>

        <hr/>

        {{-----------------------DATA TABLE-----------------------------------------}}
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable1">
                        <thead>
                        <tr class="bg-primary">
                            <td>SL</td>
                            <td>Asset Code</td>
                            <td>Asset Name</td>
                            <td>Brief Description</td>
                            <td>Location</td>
                            <td>Geotag Status</td>
                            <td>Status</td>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($data['assetList'] AS $li)
                            <tr>
                                <td>{{$i}}</td>
                                <td>
                                    <a href="#">{{$li->asset_code}}</a>
                                </td>
                                <td>{{$li->asset_name}}</td>
                                <td>{{$li->b_desc}}</td>
                                <td>
                                    <label>ZP: </label>{{$li->zila_parishad_name}} <br/>
                                    <label> AP: </label>{{$li->anchalik_parishad_name}} <br/>
                                    <label> GP: </label>{{$li->gram_panchayat_name}} <br/>
                                    <label> Village: </label>{{$li->village_name}}
                                </td>
                                <td>
                                    @if($li->geo_status==1)
                                        <a href="javascript:void(0)" class="viewGeoTag" data-aid="{{$li->id}}"><span class="badge"  style="background-color: green">{{"Done"}}</span></a>
                                    @else
                                        <span class="badge" style="background-color: red">{{"Pending"}}</span>
                                    @endif
                                </td>
                                <td>@if($li->is_active==1)<span class="badge"  style="background-color: green">{{"Active"}}</span>@else<span class="badge" style="background-color: red">{{"Deactive"}}</span>@endif</td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{------------------DATA TABLE ENDED-----------------------------------------}}
    </div>
    @elseif(Auth::user()->mdas_master_role_id==3){{--AP---------------------------------------------------------------------}}
        <div class="container mb40">
            <div class="row mt40">
                <form action="{{route('osr.non_tax.asset_show_list')}}" method="post">
                    @csrf
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Branch</label>
                            <select class="form-control" name="branch_id" id="branch_id" required>
                                <option value="">---Select---</option>
                                @foreach($data['branchList'] AS $li)
                                    <option value="{{$li->id}}" @if($data['branch_id']==$li->id)selected="selected"@endif>{{$li->branch_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Gram Panchayat</label>
                            <select class="form-control" name="gp_id" id="gp_id">
                                <option value="">---Select---</option>
                                @foreach($data['gpList'] AS $li)
                                    <option value="{{$li->id}}" @if($data['gp_id']==$li->id)selected="selected"@endif>{{$li->gram_panchayat_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <div class="form-group mt20">
                            <button type="submit" class="btn btn-primary btn-save btn-sm">
                                <i class="fa fa-search"></i>
                                Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row mt10">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h6 style="text-transform: uppercase;">
                        {{$data['searchText']}}
                    </h6>
                </div>
            </div>

            <hr/>

            {{-----------------------DATA TABLE-----------------------------------------}}
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable1">
                            <thead>
                            <tr class="bg-primary">
                                <td>SL</td>
                                <td>Asset Code</td>
                                <td>Asset Name</td>
                                <td>Brief Description</td>
                                <td>Location</td>
                                <td>Geotag Status</td>
                                <td>Status</td>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i=1; @endphp
                            @foreach($data['assetList'] AS $li)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        <a href="#">{{$li->asset_code}}</a>
                                    </td>
                                    <td>{{$li->asset_name}}</td>
                                    <td>{{$li->b_desc}}</td>
                                    <td>
                                        <label>ZP: </label>{{$li->zila_parishad_name}} <br/>
                                        <label> AP: </label>{{$li->anchalik_parishad_name}} <br/>
                                        <label> GP: </label>{{$li->gram_panchayat_name}} <br/>
                                        <label> Village: </label>{{$li->village_name}}
                                    </td>
                                    <td>
                                        @if($li->geo_status==1)
                                            <a href="javascript:void(0)" class="viewGeoTag" data-aid="{{$li->id}}"><span class="badge"  style="background-color: green">{{"Done"}}</span></a>
                                        @else
                                            <span class="badge" style="background-color: red">{{"Pending"}}</span>
                                        @endif
                                    </td>
                                    <td>@if($li->is_active==1)<span class="badge"  style="background-color: green">{{"Active"}}</span>@else<span class="badge" style="background-color: red">{{"Deactive"}}</span>@endif</td>
                                </tr>
                                @php $i++; @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{------------------DATA TABLE ENDED-----------------------------------------}}
        </div>
    @endif
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
        // ------------DATA TABLE FOR GHATS--------------------------
        $(document).ready(function () {
            $('#dataTable1').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        messageTop: '{{$data["searchText"]}}'
                    },
                ],
                'columnDefs'        : [         // see https://datatables.net/reference/option/columns.searchable
                    {
                        'searchable'    : false,
                        'targets'       : [6]
                    },
                ]
            });
        });

        //-----------------------------------------------------------------------------------
        //======================= ON AP CHANGE ==============================================
        //-----------------------------------------------------------------------------------

        $('#ap_id').on('change', function(e){

            e.preventDefault();

            $('#gp_id').empty();
            $('#v_id').empty();

            var ap_id= $('#ap_id').val();

            if(ap_id){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('common.category.getGPsByAnchalikId')}}',
                    dataType: "json",
                    data: {anchalik_code : ap_id},
                    success: function (data) {
                        if (data.msgType == true) {

                            $('#gp_id')
                                .append($("<option></option>")
                                    .attr("value", '')
                                    .text('--Select--'));

                            $.each(data.data, function(key, value) {
                                $('#gp_id')
                                    .append($("<option></option>")
                                        .attr("value", value['id'])
                                        .text(value['gram_panchayat_name']));
                            });

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
            }
        });
    </script>
@endsection