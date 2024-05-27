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
            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li><a href="{{route('osr.osr_panel')}}">OSR</a></li>
            <li><a href="{{route('osr.non_tax.dw_asset.other_assets')}}">Other Resources</a></li>
            <li class="active">{{$catData->cat_name}}</li>
        </ol>
    </div>

    <div class="container mb40">
        <div class="row">
            <div class="col-md-9 col-sm-10 col-xs-12">
                <h4 style="text-transform: uppercase">List Of {{$catData->cat_name}} in {{$zpData->zila_parishad_name}}</h4>
            </div>
            <div class="col-md-3 col-sm-2 col-xs-12">
                <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal">
                    <i class="fa fa-plus"></i> Create {{$catData->cat_name}}
                </button>
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
                            <td>Managed By</td>
                            <td>Address</td>
                            <td>Location</td>
                            <td>Remarks</td>
                            <td>Status</td>
                            <td>Edit</td>
                            <td>Payment</td>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($assetList AS $li)
                            <tr>
                                <td>{{$i}}</td>
                                <td>
                                    <a href="#">{{$li->other_asset_code}}</a>
                                </td>
                                <td>{{$li->other_asset_name}}</td>
                                <td>{{$li->managed_by}}</td>
                                <td>{{$li->asset_add}}</td>
                                <td>
                                    <label>ZP: </label>{{$li->zila_parishad_name}} <br/>
                                    <label> AP: </label>{{$li->anchalik_parishad_name}} <br/>
                                    <label> GP: </label>{{$li->gram_panchayat_name}} <br/>
                                    <label> Village: </label>{{$li->village_name}}
                                </td>
                                <td>
                                    @if($li->remarks)
                                        {{$li->remarks}}
                                    @else
                                        {{"--"}}
                                    @endif
                                </td>
                                <td>@if($li->is_active==1)<span class="badge"  style="background-color: green">{{"Active"}}</span>@else<span class="badge" style="background-color: red">{{"Deactive"}}</span>@endif</td>
                                <td>
                                    <button class="btn btn-warning btn-xs editModalBtn" data-aid="{{$li->id}}" data-fy_yr="@if(isset($osrFyYear->id)){{$osrFyYear->id}}@endif">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-xs osr_fy_payment" data-aid="{{$li->id}}" data-ac="{{$li->other_asset_code}}" data-an="{{$li->other_asset_name}}">
                                        <i class="fa fa-cog"></i>
                                        Track
                                    </button>
                                </td>
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


    <!--  --------------------ADD MODAL ASSETS DATA-----------------------------  -->

    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header  bg-primary">
                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    <h4 class="modal-title">Create {{$catData->cat_name}}</h4>
                    <p>Fields with asterisk (<strong>*</strong>) are required.</p>
                </div>

                <form action="#" method="POST" id="addAssetForm" autocomplete="off">
                    <input type="hidden" value="{{$catData->id}}" name="osr_cat_id" id="osr_cat_id"/>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Asset Name <strong>*</strong></label>
                                    <input type="text" class="form-control" name="asset_name" id="asset_name" placeholder=""/>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Managed By <strong>*</strong></label>
                                    <select class="form-control" name="managed_by" id="managed_by">
                                        <option value="">--Select--</option>
                                        <option value="ZP">Zila Parishad</option>
                                        <option value="AP">Anchalik Panchayat</option>
                                        <option value="GP">Gram Panchayat</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea class="form-control" rows="2" name="asset_add" id="asset_add"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Remarks if any</label>
                                    <textarea class="form-control" rows="2" name="remarks" id="remarks"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Zila Parishad <strong>*</strong></label>
                                    <select class="form-control" name="zp_id" id="zp_id">
                                        <option value="">---Select---</option>
                                        <option value="@if(isset($zpData->id)){{$zpData->id}}@endif">@if(isset($zpData->id)){{$zpData->zila_parishad_name}}@endif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Anchalik Panchayat<strong>*</strong></label>
                                    <select class="form-control" name="ap_id" id="ap_id">
                                        <option value="">---Select---</option>
                                        @foreach($apData AS $li_a)
                                            <option value="{{$li_a->id}}">{{$li_a->anchalik_parishad_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Gram Panchayat<strong>*</strong></label>
                                    <select class="form-control" name="gp_id" id="gp_id">
                                        <option value="">---Select---</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Village<strong>*</strong></label>
                                    <select class="form-control" name="v_id" id="v_id">
                                        <option value="">---Select---</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-save">
                            <i class="fa fa-send"></i>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--  --------------------ADD MODAL ASSETS DATA ENDED------------------------ -->

    <!----------------------- Edit Modal ------------------------------------------->

    <div class="modal fade" id="edit_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header  bg-primary">
                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    <h4 class="modal-title">Edit {{$catData->cat_name}}</h4>
                    <p>Fields with asterisk (<strong>*</strong>) are required.</p>
                </div>

                <form action="#" method="POST" id="editAssetForm" autocomplete="off">
                    <input type="hidden" class="form-control" name="aid" id="aid" value="" placeholder="" />
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Asset Name<strong>*</strong></label>
                                    <input type="text" class="form-control" name="ed_asset_name" id="ed_asset_name" value="" placeholder="" />
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Is the asset shared with PRIs<strong>*</strong></label>
                                    <select name="ed_shared_with" id="ed_shared_with" class="form-control" required>
                                        <option value="">--Select--</option>
                                        <option value="NO">NO</option>
                                        <option value="ZP">Another ZP</option>
                                        <option value="AP">Between AP</option>
                                        <option value="GP">Between GP</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Brief Description of asset if any</label>
                                    <textarea class="form-control" rows="2" name="ed_b_desc" id="ed_b_desc" value=""></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Zila Parishad <strong>*</strong></label>
                                    <select class="form-control" name="ed_zp_id" id="ed_zp_id">
                                        <option value="">---Select---</option>
                                        <option value="@if(isset($zpData->id)){{$zpData->id}}@endif">@if(isset($zpData->id)){{$zpData->zila_parishad_name}}@endif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Anchalik Panchayat<strong>*</strong></label>
                                    <select class="form-control" name="ed_ap_id" id="ed_ap_id">
                                        <option value="">---Select---</option>
                                        @foreach($apData AS $li_a)
                                            <option value="{{$li_a->id}}">{{$li_a->anchalik_parishad_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Gram Panchayat<strong>*</strong></label>
                                    <select class="form-control" name="ed_gp_id" id="ed_gp_id">
                                        <option value="">---Select---</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Village<strong>*</strong></label>
                                    <select class="form-control" name="ed_v_id" id="ed_v_id">
                                        <option value="">---Select---</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-save">
                            <i class="fa fa-send" aria-hidden="true"></i>
                            Edit &amp; Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!----------------------- Edit Modal Ended------------------------------------------->

    <!--------------------------TRACK PAYMENT------------------------------>

    <div class="modal fade" id="pay_fy_modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header  bg-primary">
                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    <h4 class="modal-title">Select Financial Year Of {{$catData->cat_name}}</h4>
                </div>
                <!-- Modal body -->
                <form action="#" method="POST" id="financialPaymentForm">
                    <div class="modal-body">
                        <input name="pay_asset_code" id="pay_asset_code" type="hidden"/>
                        {{csrf_field()}}
                        <div class="form-group">
                            <label>Asset Code</label>
                            <p id="pay_asset_code_txt"></p>
                        </div>
                        <div class="form-group">
                            <label>Asset Name</label>
                            <p id="pay_asset_name_txt"></p>
                        </div>
                        <div class="form-group">
                            <label>Financial Year</label>
                            <select name="pay_asset_fy" id="pay_asset_fy" class="form-control">
                                <option value="">--Select Financial Year--</option>
                                @foreach($allOsrFyYears AS $li_fy)
                                    <option value="{{$li_fy->id}}">{{$li_fy->fy_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-send" aria-hidden="true"></i>
                            Proceed
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--------------------------Assign Leasee------------------------------>

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
                    'excel', 'copy', 'pdf'
                ]
            });
        });

        $('#osr_dot').Zebra_DatePicker();

        $(".osr_fy_payment").on('click', function(e){
            e.preventDefault();

            $('#pay_asset_code_txt').text('');
            $('#pay_asset_name_txt').text('');
            $('#pay_asset_fy').val('');
            $('#pay_asset_code').val('');


            $('#pay_asset_code_txt').text($(this).data('ac'));
            $('#pay_asset_name_txt').text($(this).data('an'));
            $('#pay_asset_code').val($(this).data('ac'));

            $('#pay_fy_modal').modal('show');
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

        $('#ed_ap_id').on('change', function(e){

            e.preventDefault();

            $('#ed_gp_id').empty();
            $('#ed_v_id').empty();

            var ap_id= $('#ed_ap_id').val();

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

                            $('#ed_gp_id')
                                .append($("<option></option>")
                                    .attr("value", '')
                                    .text('--Select--'));

                            $.each(data.data, function(key, value) {
                                $('#ed_gp_id')
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

        //-----------------------------------------------------------------------------------
        //======================= ON GP CHANGE ==============================================
        //-----------------------------------------------------------------------------------

        $('#gp_id').on('change', function(e){

            e.preventDefault();

            $('#v_id').empty();

            var gp_id= $('#gp_id').val();

            if(gp_id){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: '{{route('common.getVillagesByGP')}}',
                    dataType: "json",
                    data: {gp_id : gp_id},
                    success: function (data) {
                        if (data.msgType == true) {

                            $('#v_id')
                                .append($("<option></option>")
                                    .attr("value", '')
                                    .text('--Select--'));

                            $.each(data.data, function(key, value) {
                                $('#v_id')
                                    .append($("<option></option>")
                                        .attr("value", value['id'])
                                        .text(value['village_name']));
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

        $('#ed_gp_id').on('change', function(e){

            e.preventDefault();

            $('#ed_v_id').empty();

            var gp_id= $('#ed_gp_id').val();

            if(gp_id){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: '{{route('common.getVillagesByGP')}}',
                    dataType: "json",
                    data: {gp_id : gp_id},
                    success: function (data) {
                        if (data.msgType == true) {

                            $('#ed_v_id')
                                .append($("<option></option>")
                                    .attr("value", '')
                                    .text('--Select--'));

                            $.each(data.data, function(key, value) {
                                $('#ed_v_id')
                                    .append($("<option></option>")
                                        .attr("value", value['id'])
                                        .text(value['village_name']));
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

        //-----------------------------------------------------------------------------------
        //======================= ON ASSET FORM SUBMIT ======================================
        //-----------------------------------------------------------------------------------

        $("#addAssetForm").validate({
            rules: {
                asset_name: {
                    required: true,
                    fullname: true,
                    blank:true,
                    maxlength:100
                },
                asset_add:{
                    required: true,
                    blank:true,
                    maxlength:150
                },
                remarks:{
                    required: true,
                    blank:true,
                    maxlength:150
                },
                zp_id:{
                    required: true,
                    digits:true

                },
                ap_id:{
                    required: true,
                    digits:true
                },
                gp_id:{
                    required: true,
                    digits:true
                },
                v_id:{
                    required: true,
                    digits:true
                }
            },
        });

        $('#addAssetForm').on('submit', function(e){
            e.preventDefault();

            if($('#addAssetForm').valid()){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('osr.non_tax.dw_other_asset.save')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {
                            swal("Success", data.msg, "success")
                                .then((value) => {
                                $('#myModalAddPri').modal('hide');
                            location.reload();
                        });

                        }else{
                            if(data.msg=="VE"){
                                swal("Error", "Validation error.Please check the form correctly!", 'error');
                                $.each(data.errors, function( index, value ) {
                                    $('#'+index).after('<p class="text-danger form_errors">'+value+'</p>');
                                });
                            }else{
                                swal("Error", data.msg, 'error');
                            }
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

        $('.editModalBtn').on('click', function(e){
            e.preventDefault();

            $('#ed_gp_id').empty();
            $('#ed_v_id').empty();

            var aid= $(this).data('aid');
            var fy_yr= $(this).data('fy_yr');


            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('osr.non_tax.dw_asset.getById')}}',
                dataType: "json",
                data: {aid: aid, fy_yr: fy_yr},
                cache: false,
                success: function (data) {
                    if (data.msgType == true) {

                        $('#aid').val(data.data.assetData.id);

                        $('#ed_shared_with').val(data.data.assetData.shared_with);

                        $('#ed_gp_id')
                            .append($("<option></option>")
                                .attr("value", '')
                                .text('--Select--'));

                        $.each(data.data.gps, function(key, value) {
                            $('#ed_gp_id')
                                .append($("<option></option>")
                                    .attr("value", value['id'])
                                    .text(value['gram_panchayat_name']));
                        });

                        $('#ed_v_id')
                            .append($("<option></option>")
                                .attr("value", '')
                                .text('--Select--'));

                        $.each(data.data.villages, function(key, value) {
                            $('#ed_v_id')
                                .append($("<option></option>")
                                    .attr("value", value['id'])
                                    .text(value['village_name']));
                        });


                        $('#ed_gp_id').val(data.data.assetData.gram_panchayat_id);
                        $('#ed_v_id').val(data.data.assetData.village_id);

                        $('#ed_asset_name').val(data.data.assetData.asset_name);
                        $('#ed_b_desc').val(data.data.assetData.b_desc);
                        $('#ed_zp_id').val(data.data.assetData.zila_id);
                        $('#ed_ap_id').val(data.data.assetData.anchalik_id);

                        $('#ed_consolidated_amt').val(data.data.assetPre.consolidated_amt);
                        $('#ed_collected_amt').val(data.data.assetPre.collected_amt);
                        $('#ed_settlement_to').val(data.data.assetPre.settlement_to);

                        $('#ed_est_zp_share').text((data.data.assetPre.collected_amt*20/100).toFixed(2));
                        $('#ed_est_ap_share').text((data.data.assetPre.collected_amt*40/100).toFixed(2));
                        $('#ed_est_gp_share').text((data.data.assetPre.collected_amt*40/100).toFixed(2));

                        $('#ed_zp_share').val(data.data.assetPre.zp_share);
                        $('#ed_ap_share').val(data.data.assetPre.ap_share);
                        $('#ed_gp_share').val(data.data.assetPre.gp_share);

                        $('#edit_modal').modal('show');

                    }else{
                        if(data.msg=="VE"){
                            swal("Error", "Validation error.Please check the form correctly!", 'error');
                            $.each(data.errors, function( index, value ) {
                                $('#'+index).after('<p class="text-danger form_errors">'+value+'</p>');
                            });
                        }else{
                            swal("Error", data.msg, 'error');
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                },
                complete: function (data) {
                    $('.page-loader-wrapper').fadeOut();
                }
            })});

        $("#editAssetForm").validate({

            rules: {
                ed_asset_name: {
                    required: true,
                    fullname: true,
                    blank:true,
                    maxlength:100
                },
                ed_b_desc:{
                    fullname: true,
                    blank:true,
                    maxlength:150
                },
                ed_asset_add:{
                    required: true,
                    blank:true,
                    maxlength:150
                },
                ed_zp_id:{
                    required: true,
                    digits:true,

                },
                ed_ap_id:{
                    required: true,
                    digits:true,
                },
                ed_gp_id:{
                    required: true,
                    digits:true,
                },
                ed_v_id:{
                    required: true,
                    digits:true,
                },
                ed_consolidated_amt:{
                    required: true,
                    number:true,
                    max:9999999999,
                    min:0
                },
                ed_collected_amt:{
                    required: true,
                    number:true,
                    max:9999999999,
                    min:0
                }
            },


        });

        //-----------------------------------------------------------------------------------
        //======================= ON ASSET FORM EDIT ======================================
        //-----------------------------------------------------------------------------------

        $('#editAssetForm').on('submit', function(e){
            e.preventDefault();

            if($('#editAssetForm').valid()){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('osr.non_tax.dw_asset.edit')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {
                            swal("Success", data.msg, "success")
                                .then((value) => {
                                $('#editAssetForm').modal('hide');
                            location.reload();
                        });

                        }else{
                            if(data.msg=="VE"){
                                swal("Error", "Validation error.Please check the form correctly!", 'error');
                                $.each(data.errors, function( index, value ) {
                                    $('#'+index).after('<p class="text-danger form_errors">'+value+'</p>');
                                });
                            }else{
                                swal("Error", data.msg, 'error');
                            }
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

        $('#collected_amt').on('keyup', function(e){
            e.preventDefault();
            var c_amt=$('#collected_amt').val();

            $('#est_zp_share').text((c_amt*20/100).toFixed(2));
            $('#est_ap_share').text((c_amt*40/100).toFixed(2));
            $('#est_gp_share').text((c_amt*40/100).toFixed(2));
        });

        $('#ed_collected_amt').on('keyup', function(e){
            e.preventDefault();
            var c_amt=$('#ed_collected_amt').val();

            $('#ed_est_zp_share').text((c_amt*20/100).toFixed(2));
            $('#ed_est_ap_share').text((c_amt*40/100).toFixed(2));
            $('#ed_est_gp_share').text((c_amt*40/100).toFixed(2));
        });

        $('#financialPaymentForm').on('submit', function(e){
            e.preventDefault();
            var asset_code= window.btoa(window.btoa(window.btoa($('#pay_asset_code').val())));
            var osr_fy_id= window.btoa(window.btoa(window.btoa($('#pay_asset_fy').val())));

            location.href = "{{url('osr/non_tax/dw_other_asset/track/fy')}}"+"/"+asset_code+"/"+osr_fy_id;
        });


    </script>
@endsection