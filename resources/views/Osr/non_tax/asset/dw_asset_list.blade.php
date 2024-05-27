@php
$page_title="dashboard";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
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

    .mb40 {
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
        <li class="active">{{$branchData->branch_name}}</li>
    </ol>
</div>

<div class="container mt40 mb40">
    <div class="row">
        <div class="col-md-9 col-sm-10 col-xs-12">
            <h4 style="text-transform: uppercase">List Of {{$branchData->branch_name}} in {{$data['parent_name']}}</h4>
        </div>
        <div class="col-md-3 col-sm-2 col-xs-12">
            <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal">
                <i class="fa fa-plus"></i> Create {{$branchData->branch_name}}
            </button>
        </div>
    </div>
    <hr />

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
                            <td>Edit</td>

                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach($assetList AS $li)
                        <tr>
                            <td>{{$i}}</td>
                            <td>
                                <a href="#">{{$li->asset_code}}</a>
                            </td>
                            <td>{{$li->asset_name}}</td>
                            <td>{{$li->b_desc}}</td>
                            <td>
                                <label>ZP: </label>{{$li->zila_parishad_name}} <br />
                                <label> AP: </label>{{$li->anchalik_parishad_name}} <br />
                                <label> GP: </label>{{$li->gram_panchayat_name}} <br />
                                <label> Village: </label>{{$li->village_name}}
                            </td>
                            <td>
                                @if($li->geo_status==1)
                                <a href="javascript:void(0)" class="viewGeoTag" data-aid="{{$li->id}}"><span
                                        class="badge" style="background-color: green">{{"Done"}}</span></a>
                                @else
                                <span class="badge" style="background-color: red">{{"Pending"}}</span>
                                @endif
                            </td>
                            <td>@if($li->is_active==1)<span class="badge"
                                    style="background-color: green">{{"Active"}}</span>@else<span class="badge"
                                    style="background-color: red">{{"Deactive"}}</span>@endif</td>
                            <td>
                                <button class="btn btn-warning btn-xs editModalBtn" data-aid="{{$li->id}}"
                                    data-fy_yr="@if(isset($osrFyYear->id)){{$osrFyYear->id}}@endif">
                                    <i class="fa fa-edit"></i>
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
                <h4 class="modal-title">Create {{$branchData->branch_name}}</h4>
                <p>Fields with asterisk (<strong>*</strong>) are required.</p>
            </div>

            <form action="#" method="POST" id="addAssetForm" autocomplete="off">
                <input type="hidden" value="{{$branchData->id}}" name="osr_asset_branch_id" id="osr_asset_branch_id" />
                <!-- Modal body -->
                <div class="modal-body">
                    @if($branch_id == 1)

                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Market Category<strong>*</strong>
                                    <select class="form-control" name="market_category" id="market_category">
                                        <option value="">Select Market Category</option>
                                        @foreach($marketCategories AS $value)
                                        <option value="{{$value->id}}">{{$value->category_name}}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Nature Of Market<strong>*</strong>
                                    <select class="form-control" name="market_natures" id="market_natures">
                                        <option value="">Select Market Nature</option>
                                        @foreach($marketNatures AS $value)
                                        <option value="{{$value->id}}">{{$value->nature_name}}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>

                    @endif
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Asset Name<strong>*</strong></label>
                                <input type="text" class="form-control" name="asset_name" id="asset_name"
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Asset Listing Date<strong>*</strong></label>
                                <input type="text" class="form-control" name="asset_listing_date"
                                    id="asset_listing_date" />
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-6">
                            <div class="form-group">
                                <label>Scope(Area/Capacity)</label>
                                <input type="text" class="form-control" name="asset_scope" id="asset_scope" />
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-6">
                            <div class="form-group">
                                <label>Scope Unit</label>
                                <input type="text" class="form-control" name="asset_scope_unit" id="asset_scope_unit" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Brief Description of asset if any</label>
                                <textarea class="form-control" rows="2" name="b_desc" id="b_desc"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Zila Parishad <strong>*</strong></label>
                                <select class="form-control" name="zp_id" id="zp_id">
                                    <option value="{{$zpData->id}}">{{$zpData->zila_parishad_name}}</option>
                                </select>
                            </div>
                        </div>
                        @if(Auth::user()->mdas_master_role_id==2)
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
                                <label>Village</label>
                                <select class="form-control" name="v_id" id="v_id">
                                    <option value="">---Select---</option>
                                </select>
                            </div>
                        </div>
                        @elseif(Auth::user()->mdas_master_role_id==3)
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Anchalik Panchayat<strong>*</strong></label>
                                <select class="form-control" name="ap_id" id="ap_id">
                                    <option value="">---Select---</option>
                                    <option value="{{$apData->id}}">{{$apData->anchalik_parishad_name}}</option>

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
                                <label>Village</label>
                                <select class="form-control" name="v_id" id="v_id">
                                    <option value="">---Select---</option>
                                </select>
                            </div>
                        </div>
                        @elseif(Auth::user()->mdas_master_role_id==4)
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Anchalik Panchayat<strong>*</strong></label>
                                <select class="form-control" name="ap_id" id="ap_id">
                                    <option value="{{$apData->id}}">{{$apData->anchalik_parishad_name}}</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Gram Panchayat<strong>*</strong></label>
                                <select class="form-control" name="gp_id" id="gp_id">
                                    <option value="">---Select---</option>
                                    <option value="{{$gpData->gram_panchyat_id}}">{{$gpData->gram_panchayat_name}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Village</label>
                                <select class="form-control" name="v_id" id="v_id">
                                    <option value="">---Select---</option>
                                </select>
                            </div>
                        </div>
                        @endif
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
                <h4 class="modal-title">Edit {{$branchData->branch_name}}</h4>
                <p>Fields with asterisk (<strong>*</strong>) are required.</p>
            </div>

            <form action="#" method="POST" id="editAssetForm" autocomplete="off">
                <input type="hidden" class="form-control" name="aid" id="aid" value="" placeholder="" />
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Asset Name<strong>*</strong></label>
                                <input type="text" class="form-control" name="ed_asset_name" id="ed_asset_name" value=""
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Asset Listing Date<strong>*</strong></label>
                                <input type="text" class="form-control" name="ed_asset_listing_date"
                                    id="ed_asset_listing_date" />
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-6">
                            <div class="form-group">
                                <label>Scope(Area/Capacity)</label>
                                <input type="text" class="form-control" name="ed_asset_scope" id="ed_asset_scope" />
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-6">
                            <div class="form-group">
                                <label>Scope Unit</label>
                                <input type="text" class="form-control" name="ed_asset_scope_unit"
                                    id="ed_asset_scope_unit" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Brief Description of asset if any</label>
                                <textarea class="form-control" rows="2" name="ed_b_desc" id="ed_b_desc"
                                    value=""></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Zila Parishad <strong>*</strong></label>
                                <select class="form-control" name="ed_zp_id" id="ed_zp_id">
                                    <option value="">---Select---</option>
                                    <option value="@if(isset($zpData->id)){{$zpData->id}}@endif">
                                        @if(isset($zpData->id)){{$zpData->zila_parishad_name}}@endif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Anchalik Panchayat<strong>*</strong></label>
                                <select class="form-control" name="ed_ap_id" id="ed_ap_id">
                                    <option value="">---Select---</option>
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
                                <label>Village</label>
                                <select class="form-control" name="ed_v_id" id="ed_v_id">
                                    <option value="">---Select---</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr />
                    {{--<div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h4>Previous 3 year's brief summary</h4>
                        </div>
                    </div>--}}
                    <!------------------------------------------ LOOP FOR PREVIOUS YEARS ASSET ENTRY DETAILS ------------------------------------>
                    {{--@foreach($osrFyThreeYears AS $threeYearFY)
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Financial Year<strong>*</strong></label>
                                        <select class="form-control" name="ed_fy_yr{{$threeYearFY->id}}"
                                            id="ed_fy_yr{{$threeYearFY->id}}">
                                            <option value="">--Select--</option>
                                            <option value="@if(isset($threeYearFY->id)){{$threeYearFY->id}}@endif">
                                                @if(isset($threeYearFY->fy_name)){{$threeYearFY->fy_name}}@endif
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Managed By Tier<strong>*</strong></label>
                                        <select class="form-control" name="ed_settlement_to{{$threeYearFY->id}}"
                                            id="ed_settlement_to{{$threeYearFY->id}}" required>
                                            <option value="">--Select--</option>
                                            <option value="ZP">ZP</option>
                                            <option value="AP">AP</option>
                                            <option value="GP">GP</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Settled Amount</label> (<i class="fa fa-rupee"></i>)<strong>*</strong>
                                        <input type="number" class="form-control"
                                            name="ed_settled_amt{{$threeYearFY->id}}"
                                            id="ed_settled_amt{{$threeYearFY->id}}" min="0" step="0.01" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>First Name <strong>*</strong></label>
                                        <input type="text" class="form-control" name="ed_f_name{{$threeYearFY->id}}"
                                            id="ed_f_name{{$threeYearFY->id}}" placeholder="" />
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Middle Name<strong>*</strong></label>
                                        <input type="text" class="form-control" name="ed_m_name{{$threeYearFY->id}}"
                                            id="ed_m_name{{$threeYearFY->id}}" placeholder="" />
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Last Name<strong>*</strong></label>
                                        <input type="text" class="form-control" name="ed_l_name{{$threeYearFY->id}}"
                                            id="ed_l_name{{$threeYearFY->id}}" placeholder="" />
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Pan Card Number<strong>*</strong></label>
                                        <input type="text" class="form-control" name="ed_pan_no{{$threeYearFY->id}}"
                                            id="ed_pan_no{{$threeYearFY->id}}" placeholder="" />
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <h6
                                        style="font-size:10px; border-left:2px solid orangered; padding:5px; background-color: #ddd">
                                        Distribution of share of resource generated by the asset:</h6>
                                </div>
                                <div class="col-md-3 col-sm-4 col-xs-12">
                                    <label>Collected Amount<strong>*</strong></label>
                                    <input type="text" class="form-control" name="ed_collected_amt{{$threeYearFY->id}}"
                                        id="ed_collected_amt{{$threeYearFY->id}}" min="0" step="0.01" required />
                                </div>
                                <div class="col-md-3 col-sm-4 col-xs-12">
                                    <label>ZP Share<strong>*</strong></label>
                                    <input type="number" class="form-control" name="ed_zp_share{{$threeYearFY->id}}"
                                        id="ed_zp_share{{$threeYearFY->id}}" min="0" step="0.01" required />
                                    <i class="fa fa-rupee"></i> <span id="ed_est_zp_share{{$threeYearFY->id}}">0</span>
                                    <p style="font-size: 8px;">Estimated Amount (20%)</p>
                                </div>
                                <div class="col-md-3 col-sm-4 col-xs-12">
                                    <label>AP Share<strong>*</strong></label>
                                    <input type="number" class="form-control" name="ed_ap_share{{$threeYearFY->id}}"
                                        id="ed_ap_share{{$threeYearFY->id}}" min="0" step="0.01" required />
                                    <i class="fa fa-rupee"></i> <span id="ed_est_ap_share{{$threeYearFY->id}}">0</span>
                                    <p style="font-size: 8px;">Estimated Amount (40%)</p>
                                </div>
                                <div class="col-md-3 col-sm-4 col-xs-12">
                                    <label>GP Share<strong>*</strong></label>
                                    <input type="number" class="form-control" name="ed_gp_share{{$threeYearFY->id}}"
                                        id="ed_gp_share{{$threeYearFY->id}}" min="0" step="0.01" required />
                                    <i class="fa fa-rupee"></i> <span id="ed_est_gp_share{{$threeYearFY->id}}">0</span>
                                    <p style="font-size: 8px;">Estimated Amount (40%)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach--}}
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



<!--------------------view geo-tag data-------------------------->

<div class="modal fade" id="viewGeoTag">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header  bg-primary">
                <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-close"></i>
                </button>
                <h4 class="modal-title">Geo-Tag Data</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="col-md-12 col-sm-4 col-xs-12">
                            <img id="geo_image" src="{{asset('mdas_assets/images/user_add.png')}}"
                                style="border:1px solid #ddd;width:120px;max-height:130px;cursor:pointer" />
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="col-md-12 col-sm-4 col-xs-12">
                            <label id="asset_code"> Geo-Tag Adrress:</label>
                            <span style="color:#ee0a54;font-weight: bold;font-size: 14px;text-transform: uppercase"
                                id="geo_add" name="geo_add"></span>
                        </div>
                        <div class="col-md-6 col-sm-4 col-xs-12">
                            <label id="asset_code"> Geo-Tag At:</label>
                            <span style="color:#ee0a54;font-weight: bold;font-size: 14px;text-transform: uppercase"
                                id="geo_at" name="geo_at"></span>
                        </div>
                        <div class="col-md-6 col-sm-4 col-xs-12">
                            <label id="asset_code"> Geo-Tag By:</label>
                            <span style="color:#ee0a54;font-weight: bold;font-size: 14px;text-transform: uppercase"
                                id="geo_by" name="geo_by"></span>
                        </div>
                        <div class="col-md-6 col-sm-4 col-xs-12">
                            <label id="asset_code"> Latitude:</label>
                            <span style="color:#ee0a54;font-weight: bold;font-size: 14px;text-transform: uppercase"
                                id="geo_lat" name="geo_lat"></span>
                        </div>
                        <div class="col-md-6 col-sm-4 col-xs-12">
                            <label id="asset_name"> Longitude:</label>
                            <span style="color:#ee0a54;font-weight: bold;font-size: 14px;text-transform: uppercase"
                                id="geo_long" name="geo_long"></span>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Modal footer -->
            <form action="#" method="POST" id="geoTagApprove" autocomplete="off">
                <input type="hidden" class="form-control" name="as_id" id="as_id" value="" placeholder="" />
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-save">
                        <i class="fa fa-check" aria-hidden="true"></i>
                        Approve
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--------------------view geo-tag data ends-------------------------->
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

    $('#asset_listing_date').Zebra_DatePicker();
    $('#ed_asset_listing_date').Zebra_DatePicker();
    $('#osr_dot').Zebra_DatePicker();

    $(".osr_fy_assign").on('click', function (e) {
        e.preventDefault();

        $('#assign_asset_code').text('');
        $('#assign_asset_name').text('');
        $('#assign_asset_fy').val('');

        var asset_code = $(this).data('ac');
        var asset_name = $(this).data('an');

        $('#assign_asset_code').text(asset_code);
        $('#assign_asset_name').text(asset_name);

        $('#assign_asset_id').val($(this).data('aid'));

        $('#assign_fy_modal').modal('show');
    });

    $(".osr_fy_payment").on('click', function (e) {
        e.preventDefault();

        $('#pay_asset_code').text('');
        $('#pay_asset_name').text('');
        $('#pay_asset_fy').val('');

        var asset_code = $(this).data('ac');
        var asset_name = $(this).data('an');

        $('#pay_asset_code').text(asset_code);
        $('#pay_asset_name').text(asset_name);

        $('#pay_asset_id').val($(this).data('aid'));

        $('#pay_fy_modal').modal('show');
    });

    $('#btn-assign-asset').on('click', function (e) {
        e.preventDefault();

        var fy_yr = $('#assign_asset_fy').val();
        var a_id = $('#assign_asset_id').val();


        alert(fy_yr);
        alert(a_id);

        if (!fy_yr) {
            return;
        }

        window.href = '{{url("osr/osr_asset_details/list/")}}' + fy_yr;

    });


    //-----------------------------------------------------------------------------------
    //======================= ON AP CHANGE ==============================================
    //-----------------------------------------------------------------------------------

    $('#ap_id').on('change', function (e) {

        e.preventDefault();

        $('#gp_id').empty();
        $('#v_id').empty();

        var ap_id = $('#ap_id').val();

        if (ap_id) {
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('common.category.getGPsByAnchalikId')}}',
                dataType: "json",
                data: { anchalik_code: ap_id },
                success: function (data) {
                    if (data.msgType == true) {

                        $('#gp_id')
                            .append($("<option></option>")
                                .attr("value", '')
                                .text('--Select--'));

                        $.each(data.data, function (key, value) {
                            $('#gp_id')
                                .append($("<option></option>")
                                    .attr("value", value['id'])
                                    .text(value['gram_panchayat_name']));
                        });

                    } else {
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

    $('#ed_ap_id').on('change', function (e) {

        e.preventDefault();

        $('#ed_gp_id').empty();
        $('#ed_v_id').empty();

        var ap_id = $('#ed_ap_id').val();

        if (ap_id) {
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('common.category.getGPsByAnchalikId')}}',
                dataType: "json",
                data: { anchalik_code: ap_id },
                success: function (data) {
                    if (data.msgType == true) {

                        $('#ed_gp_id')
                            .append($("<option></option>")
                                .attr("value", '')
                                .text('--Select--'));

                        $.each(data.data, function (key, value) {
                            $('#ed_gp_id')
                                .append($("<option></option>")
                                    .attr("value", value['id'])
                                    .text(value['gram_panchayat_name']));
                        });

                    } else {
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

    $('#gp_id').on('change', function (e) {

        e.preventDefault();

        $('#v_id').empty();

        var gp_id = $('#gp_id').val();

        if (gp_id) {
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: '{{route('common.getVillagesByGP')}}',
                dataType: "json",
                data: { gp_id: gp_id },
                success: function (data) {
                    if (data.msgType == true) {

                        $('#v_id')
                            .append($("<option></option>")
                                .attr("value", '')
                                .text('--Select--'));

                        $.each(data.data, function (key, value) {
                            $('#v_id')
                                .append($("<option></option>")
                                    .attr("value", value['id'])
                                    .text(value['village_name']));
                        });

                    } else {
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

    $('#ed_gp_id').on('change', function (e) {

        e.preventDefault();

        $('#ed_v_id').empty();

        var gp_id = $('#ed_gp_id').val();

        if (gp_id) {
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: '{{route('common.getVillagesByGP')}}',
                dataType: "json",
                data: { gp_id: gp_id },
                success: function (data) {
                    if (data.msgType == true) {

                        $('#ed_v_id')
                            .append($("<option></option>")
                                .attr("value", '')
                                .text('--Select--'));

                        $.each(data.data, function (key, value) {
                            $('#ed_v_id')
                                .append($("<option></option>")
                                    .attr("value", value['id'])
                                    .text(value['village_name']));
                        });

                    } else {
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
                blank: true,
                maxlength: 100
            },
            b_desc: {
                fullname: true,
                blank: true,
                maxlength: 150
            },
            asset_add: {
                required: true,
                blank: true,
                maxlength: 150
            },
            zp_id: {
                required: true,
                digits: true,

            },
            ap_id: {
                required: true,
                digits: true,
            },
            gp_id: {
                required: true,
                digits: true,
            },
            /*settled_amt:{
                required: true,
                number:true,
                max:9999999999,
                min:0
            },
            collected_amt:{
                required: true,
                number:true,
                max:9999999999,
                min:0
            },
            zp_share:{
                required: true,
                number:true,
                max:9999999999,
                min:0
            },
            ap_share:{
                required: true,
                number:true,
                max:9999999999,
                min:0
            },
            gp_share:{
                required: true,
                number:true,
                max:9999999999,
                min:0
            }*/
        },
    });

    $('#addAssetForm').on('submit', function (e) {
        e.preventDefault();

        if ($('#addAssetForm').valid()) {
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('osr.non_tax.dw_asset.save')}}',
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

                    } else {
                        if (data.msg == "VE") {
                            swal("Error", "Validation error.Please check the form correctly!", 'error');
                            $.each(data.errors, function (index, value) {
                                $('#' + index).after('<p class="text-danger form_errors">' + value + '</p>');
                            });
                        } else {
                            swal("Error", data.msg, 'error');
                        }
                    }
                    console.log(data);
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

    $('.editModalBtn').on('click', function (e) {
        e.preventDefault();


        var aid = $(this).data('aid');



        $('.page-loader-wrapper').fadeIn();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '{{route('osr.non_tax.dw_asset.getById')}}',
            dataType: "json",
            data: { aid: aid },
            cache: false,
            success: function (data) {
                if (data.msgType == true) {
                    $('#aid').val(data.data.assetData.id);

                    $.each(data.data.aps, function (key, value) {
                        $('#ed_ap_id')
                            .append($("<option></option>")
                                .attr("value", value['id'])
                                .text(value['anchalik_parishad_name']));
                    });

                    $.each(data.data.gps, function (key, value) {
                        $('#ed_gp_id')
                            .append($("<option></option>")
                                .attr("value", value['id'])
                                .text(value['gram_panchayat_name']));
                    });

                    $.each(data.data.villages, function (key, value) {
                        $('#ed_v_id')
                            .append($("<option></option>")
                                .attr("value", value['id'])
                                .text(value['village_name']));
                    });


                    $('#ed_gp_id').val(data.data.assetData.gram_panchayat_id);
                    $('#ed_v_id').val(data.data.assetData.village_id);

                    $('#ed_asset_name').val(data.data.assetData.asset_name);
                    $('#ed_asset_listing_date').val(data.data.assetData.asset_listing_date);
                    $('#ed_asset_scope').val(data.data.assetData.asset_scope);
                    $('#ed_asset_scope_unit').val(data.data.assetData.scope_unit);
                    $('#ed_b_desc').val(data.data.assetData.b_desc);
                    $('#ed_zp_id').val(data.data.assetData.zila_id);
                    $('#ed_ap_id').val(data.data.assetData.anchalik_id);

                    $('#edit_modal').modal('show');

                } else {
                    if (data.msg == "VE") {
                        swal("Error", "Validation error.Please check the form correctly!", 'error');
                        $.each(data.errors, function (index, value) {
                            $('#' + index).after('<p class="text-danger form_errors">' + value + '</p>');
                        });
                    } else {
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
        })
    });

    $("#editAssetForm").validate({

        rules: {
            ed_asset_name: {
                required: true,
                blank: true,
                maxlength: 100
            },
            ed_b_desc: {
                fullname: true,
                blank: true,
                maxlength: 150
            },
            ed_asset_add: {
                required: true,
                blank: true,
                maxlength: 150
            },
            ed_zp_id: {
                required: true,
                digits: true,

            },
            ed_ap_id: {
                required: true,
                digits: true,
            },
            ed_gp_id: {
                required: true,
                digits: true,
            },
            /*ed_settled_amt:{
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
            }*/
        },


    });

    //-----------------------------------------------------------------------------------
    //======================= ON ASSET FORM EDIT ========================================
    //-----------------------------------------------------------------------------------

    $('#editAssetForm').on('submit', function (e) {
        e.preventDefault();

        if ($('#editAssetForm').valid()) {
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

                    } else {
                        if (data.msg == "VE") {
                            swal("Error", "Validation error.Please check the form correctly!", 'error');
                            $.each(data.errors, function (index, value) {
                                $('#' + index).after('<p class="text-danger form_errors">' + value + '</p>');
                            });
                        } else {
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



    $('#financialBiddingForm').on('submit', function (e) {
        e.preventDefault();
        var asset_id = window.btoa(window.btoa(window.btoa($('#assign_asset_id').val())));
        var osr_fy_id = window.btoa(window.btoa(window.btoa($('#assign_asset_fy').val())));

        location.href = "{{url('osr/non_tax/dw_asset/bidding/fy')}}" + "/" + asset_id + "/" + osr_fy_id;
    });


    $('#financialBiddingForm').on('submit', function (e) {
        e.preventDefault();
        var asset_id = window.btoa(window.btoa(window.btoa($('#assign_asset_id').val())));
        var osr_fy_id = window.btoa(window.btoa(window.btoa($('#assign_asset_fy').val())));

        location.href = "{{url('osr/non_tax/dw_asset/bidding/fy')}}" + "/" + asset_id + "/" + osr_fy_id;
    });
    $('#financialPaymentForm').on('submit', function (e) {
        e.preventDefault();
        var asset_id = window.btoa(window.btoa(window.btoa($('#pay_asset_id').val())));
        var osr_fy_id = window.btoa(window.btoa(window.btoa($('#pay_asset_fy').val())));

        location.href = "{{url('osr/non_tax/dw_asset/track/fy')}}" + "/" + asset_id + "/" + osr_fy_id;
    });


    //-----------------------------------------------------------------------------------
    //======================= ASSET GEO-TAG ======================================
    //-----------------------------------------------------------------------------------
    $('.viewGeoTag').on('click', function (e) {
        e.preventDefault();
        var aid = $(this).data('aid');

        $('.page-loader-wrapper').fadeIn();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '{{route('osr.non_tax.dw_asset.geo_tag_details')}}',
            dataType: "json",
            data: { aid: aid },
            cache: false,
            success: function (data) {
                if (data.msgType == true) {
                    $('#geo_image').text(data.data.geoTagData.geotag_img_path);
                    $('#geo_add').text(data.data.geoTagData.geotag_add);
                    $('#geo_at').text(data.data.geoTagData.geotag_at);
                    $('#geo_by').text(data.data.geoTagData.geotag_by);
                    $('#geo_lat').text(data.data.geoTagData.geotag_lat);
                    $('#geo_long').text(data.data.geoTagData.geotag_long);
                    $('#as_id').val(data.data.geoTagData.id);
                    $('#viewGeoTag').modal('show');
                }
                else {
                    if (data.msg == "VE") {
                        swal("Error", "Validation error.Please check the form correctly!", 'error');
                        $.each(data.errors, function (index, value) {
                            $('#' + index).after('<p class="text-danger form_errors">' + value + '</p>');
                        });
                    } else {
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
        })
    });

    $('#geoTagApprove').on('submit', function (e) {
        e.preventDefault();
        $('.page-loader-wrapper').fadeIn();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '{{route('osr.non_tax.dw_asset.geo_tag_approve')}}',
            dataType: "json",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                if (data.msgType == true) {
                    swal("Success", data.msg, "success")
                        .then((value) => {
                            $('#viewGeoTag').modal('hide');
                            location.reload();
                        });
                } else {
                    if (data.msg == "VE") {
                        swal("Error", "Validation error.Please check the form correctly!", 'error');
                        $.each(data.errors, function (index, value) {
                            $('#' + index).after('<p class="text-danger form_errors">' + value + '</p>');
                        });
                    } else {
                        swal("Error", data.msg, 'error');
                    }
                }
            },

            complete: function (data) {
                $('.page-loader-wrapper').fadeOut();
            }


        })
    });
</script>
@endsection