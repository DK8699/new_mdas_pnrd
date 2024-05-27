<head>
    <link rel="icon" href="{{asset('./mdas_assets/images/favicon.png')}}" type="image/gif" sizes="16x16">
</head>
@php
$page_title="dashboard";
@endphp

@extends('layouts.app_admin_home')

@section('custom_css')
<style>
    .mt10 {
        margin-top: 10px;
    }

    .mt20 {
        margin-top: 20px;
    }

    .mt30 {
        margin-top: 30px;
    }

    strong {
        color: red;
    }

    #myModalAddPri .form-control {
        height: 25px;
        padding: 2px 5px;
        font-size: 12px;
    }

    label {
        font-size: 11px;
    }

    .Zebra_DatePicker_Icon_Wrapper {
        width: 100% !important;
    }

    .table {
        margin-bottom: 0px;
        border: 0px;
    }

    body {
        background-color: #eee;
    }

    #myModalAddPri .modal-body {
        padding-bottom: 0px;
        background-color: rgba(125, 210, 235, 0.93);
    }

    .well {
        margin-bottom: 0px;
    }

    .overlay {
        position: absolute;
        bottom: 0;
        background: rgb(0, 0, 0);
        background: rgba(0, 0, 0, 0.5);
        /* Black see-through */
        color: #f1f1f1;
        width: 100%;
        transition: .5s ease;
        opacity: 0;
        color: white;
        font-size: 20px;
        padding: 20px;
        text-align: center;
    }

    /* When you mouse over the container, fade in the overlay title */
    .pri:hover .overlay {
        opacity: 1;
    }

    .btn-round {
        border-radius: 50%;
    }

    /*css for model view of PRI*/

    .dangor-success {
        color: #66a1d6;
    }

    .efale {
        margin-left: 20px;
    }

    #priContent {
        position: relative;
        width: 100%;
        z-index: 5;
        overflow: hidden;
    }

    .dangor {
        background: -webkit-linear-gradient(left, #1143a6, #00c6ff);
        margin-top: 3%;
        padding: 3%;
    }

    .dangor-left {
        text-align: center;
        color: #fff;
    }

    .dangor-left input {
        border: none;
        border-radius: 1.5rem;
        padding: 2%;
        width: 60%;
        background: #f8f9fa;
        font-weight: bold;
        color: #383d41;
        margin-top: 30%;
        margin-bottom: 3%;
        cursor: pointer;
    }

    .dangor-right {
        background: #00ff7f1c;
        border-top-left-radius: 15% 50%;
        border-bottom-left-radius: 15% 50%;
    }

    .dango-right {
        background: #f8f9fa;
        border-top-left-radius: 15% 50%;
        border-bottom-left-radius: 15% 50%;
        border-color: solid 2px black;
    }

    .nasoni {
        -webkit-animation: mover 5s infinite alternate;
        animation: mover 1s infinite alternate;
    }

    @keyframes mover {
        0% {
            transform: translateY(0);
        }

        100% {
            transform: translateY(-10px);
        }
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

<div class="container">
    <div class="row" style="background-color: #80d8ff">
        <form action="{{route('admin.Pris.reportAdmin')}}" method="POST">
            {{ csrf_field() }}
            <h3 class=text-center text-uppercase></h3>
            <div class="col-md-2 col-sm-2 col-xs-12">
                <div class="form-group">
                    <label>Choose View Type</label>
                    <select class="form-control" name="tier" id="filter_tier" onChange="checkOption(this.value)"
                        required>
                        <option value="">--Select--</option>
                        <option value="ZP">ZP Wise</option>
                        <option value="AP">AP Wise</option>
                        <option value="GP">GP Wise</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-sm-2 col-xs-12">
                <div class="form-group">
                    <label>Select Zilla Parishad</label>
                    <select class="form-control" name="zp_id" id="filter_zila_id" required>
                        <option value="">--Select--</option>
                        @foreach($zilas AS $zil)
                        <option value="{{$zil->id}}">{{$zil->zila_parishad_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-sm-2 col-xs-12">
                <div class="form-group">
                    <label>Select Anchalik Panchayat</label>
                    <select class="form-control zp_cmd" name="ap_id" id="filter_anchalik_id" required>
                        <option value="">--Select--</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-sm-2 col-xs-12">
                <div class="form-group">
                    <label>Select Gram Panchayat</label>
                    <select class="form-control ap_cmd AZcmd" name="gp_id" id="filter_gram_panchyat_id" required>
                        <option value="">--Select--</option>
                    </select>
                </div>
            </div>
            <div class="col-md-1 col-sm-2 col-xs-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block" style="margin-top: 22px">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <h5>Showing PRIs Members of <span>@if(isset($zillaForFilters->id))
            ZP: {{$zillaForFilters->zila_parishad_name}} @endif</span>
        <span>@if(isset($anchaliks->id)) AP: {{$anchaliks->anchalik_parishad_name}} @endif</span>
    </h5>
    <div class="row mt20">
        @foreach($priMembers AS $members)
        <div class="col-md-4 col-sm-6 col-xs-12 animated zoomIn" style="margin-bottom: 27px;">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="row pri"
                    style="border-top:4px solid {{$members->color_code}};box-shadow: 0px 0px 8px 0px #a7a6a4;background-color:#fff">
                    <table class="table table-condensed">
                        <tr>
                            <td rowspan="4" style="vertical-align: middle;padding: auto" class="viewPri"
                                data-pricode="{{$members->pri_code}}">
                                @if($members->pri_pic)
                                <img src="{{$imgUrl}}{{$members->pri_pic}}"
                                    style="border:1px solid #ddd;width:80px;height:90px;cursor:pointer;" />
                                @else
                                <img src="{{asset('mdas_assets/images/user_add.png')}}"
                                    style="border:1px solid #ddd;width:80px;height:90px;cursor:pointer;" />
                                @endif
                            </td>
                            <th>PRI Code</th>
                            <td>{{$members->pri_code}}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <!-- ning -->
                            <td style="height:50px; font-size:12px;">{{ucwords($members->pri_f_name)}} {{ucwords($members->pri_m_name)}}
                                {{ucwords($members->pri_l_name)}}</td>
                        </tr>
                        <tr>
                            <th>Designation</th>
                            <td>{{$members->design_name}}</td>
                        </tr>
                    </table>

                    <div class="overlay">
                        <button type="button" class="btn btn-info btn-round viewPri"
                            data-pricode="{{$members->pri_code}}">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="row mt10">
        <div class="col-md-12 col-sm-12 col-xs-12 text-right">
            <!-- <?php //echo $priMembers->render(); ?> -->
        </div>
    </div>
</div>
</div>

<!-- Modal ADD PRIs -->
<div class="modal fade" id="myModalPri" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body" style="background: -webkit-linear-gradient(left,  #1143a6, #00c6ff); padding: 3%;">
                <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-close"></i>
                </button>
                <div class="row viewPriData">
                    <div class="col-md-3 dangor-left">
                        <img id="pri_image" src="{{asset('mdas_assets/images/user_add.png')}}"
                            style="border: 5px solid white;width: 90%;" alt="" />
                        <h3 class="nasoni"><span id="v_pri_name"></span></h3>
                        <p><i class="fa fa-birthday-cake"></i> <span id="v_dob"></span></p>
                        <p><span class="v_dign_name"></span></p>
                    </div>
                    <div class="col-md-9 dangor-right">
                        <div class="tab-content dango-right" id="priContent"
                            style="padding-left: 50px;padding-top: 50px; padding-bottom: 20px;">
                            <div class="">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#detail1" class="dangor-success"><i
                                                class="fa fa-indent"></i>
                                            General Details</a>
                                    </li>
                                    <li><a data-toggle="tab" href="#detail2" class="dangor-success"><i
                                                class="fa fa-bookmark-o"></i> Personal</a>
                                    </li>
                                    <li><a data-toggle="tab" href="#detail3" class="dangor-success"><i
                                                class="fa fa-home"></i> Address</a>
                                    </li>
                                    <li><a data-toggle="tab" href="#detail4" class="dangor-success">
                                            <i class="fa-solid fa-clock-rotate-left"></i>
                                            History</a>
                                    </li>
                                    <li><a data-toggle="tab" href="#detail5" class="dangor-success"><i
                                                class="fa-solid fa-building-columns"></i> Bank Details</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="detail1" class="tab-pane fade in active">
                                        <div class="table-responsive panel">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td class="dangor-success"><i class="fa fa-address-card-o"></i>
                                                            Designation *
                                                        </td>
                                                        <td><span class="v_dign_name"></span></td>
                                                    </tr>
                                                    <tr class="v_zp_tr">
                                                        <td class="dangor-success"><i class="fa fa-map-marker"></i>
                                                            <span id="v_pri_post"></span> Zilla Prarisad *
                                                        </td>
                                                        <td><span id="v_zilaName"></span></td>
                                                    </tr>
                                                    <tr class="v_ap_tr">
                                                        <td class="dangor-success"><i class="fa fa-map-marker"></i>
                                                            <span id="v_pri_post"></span> Anchalik Prarisad *
                                                        </td>
                                                        <td id="v_anchalikName">NA</td>
                                                    </tr>
                                                    <tr class="v_gp_tr">
                                                        <td class="dangor-success"><i class="fa fa-map-marker"></i>
                                                            <span id="v_pri_post"></span> Gram Panchayat *
                                                        </td>
                                                        <td id="v_gramName">NA</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="dangor-success"><i class="fa fa-anchor"></i> Is the
                                                            seat reserved? *
                                                        </td>
                                                        <td><span id="v_seat_name"></span></td>
                                                    </tr>
                                                    <tr class="v_zp_tr_only">
                                                        <td class="dangor-success"><i class="fa fa-map-marker"></i>
                                                            Constituency *
                                                        </td>
                                                        <td><span id="v_constituency">NA</span></td>
                                                    </tr>
                                                    <tr class="v_ap_tr_only">
                                                        <td class="dangor-success"><i class="fa fa-map-marker"></i>
                                                            AP Constituency *
                                                        </td>
                                                        <td><span id="v_ap_constituency">NA</span></td>
                                                    </tr>
                                                    <tr class="v_gp_tr_only">
                                                        <td class="dangor-success"><i class="fa fa-map-marker"></i>
                                                            Ward No *
                                                        </td>
                                                        <td><span id="v_gp_ward">NA</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="dangor-success"><i class="fa fa-university"></i>
                                                            Political Party *
                                                        </td>
                                                        <td><span id="v_party_name">NA</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div id="detail2" class="tab-pane fade">
                                        <div class="table-responsive panel">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td class="dangor-success">
                                                            <i class="fa fa-phone"></i>
                                                            Phone Number *
                                                        </td>
                                                        <td><span id="v_mobile_no"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="dangor-success"><i class="fa fa-male"></i>
                                                            Gender *
                                                        </td>
                                                        <td><span id="v_gender_name"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="dangor-success"><i class="fa fa-book"></i>
                                                            Religion *
                                                        </td>
                                                        <td><span id="v_religion_name"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="dangor-success"><i class="fa fa-wheelchair"></i>
                                                            Diffrently Abled ? *
                                                        </td>
                                                        <td><span id="v_diffrently_abled"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="dangor-success"><i class="fa fa-mortar-board"></i>
                                                            Highest Qualification *
                                                        </td>
                                                        <td><span id="v_qual_name"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="dangor-success"><i class="fa fa-user-plus"></i> Caste
                                                            *
                                                        </td>
                                                        <td><span id="v_caste_name"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="dangor-success"><i class="fa fa-money"></i>
                                                            Occupation
                                                        </td>
                                                        <td><span id="v_occupation"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="dangor-success"><i class="fa fa-tint"></i>
                                                            Blood Group
                                                        </td>
                                                        <td><span id="v_blood_name"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="dangor-success"><i class="fa fa-life-ring"></i>
                                                            Marital Status *
                                                        </td>
                                                        <td><span id="v_marital_status_name"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="dangor-success"><i class="fa fa-money"></i>
                                                            Annual Income *
                                                        </td>
                                                        <td><span id="v_income_name"></span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div id="detail3" class="tab-pane fade">
                                        <div class="table-responsive panel">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td class="dangor-success">
                                                            <label class="dangor-success"><i
                                                                    class="fa fa-address-card-o"></i> Official Address
                                                                *</label>
                                                            <p><b>Address :</b> <span id="v_o_add"></span></p>
                                                            <p><b>PIN :</b> <span id="v_o_pin"></span></p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="dangor-success">
                                                            <label class="dangor-success"><i
                                                                    class="fa fa-address-card-o"></i> Permanent Address
                                                                *</label>
                                                            <p><b>Address :</b> <span id="v_p_add"></span></p>
                                                            <p><b>District :</b> <span id="v_p_district"></span></p>
                                                            <p><b>PIN :</b> <span id="v_p_pin"></span></p>
                                                        </td>

                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div id="detail4" class="tab-pane fade">
                                        <div class="table-responsive panel">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr class="bg-primary"
                                                                    style="background-color: #86bbe8;">
                                                                    <td>
                                                                        Term
                                                                    </td>
                                                                    <td>
                                                                        Designation
                                                                    </td>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="term_display">

                                                            </tbody>
                                                        </table>

                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    {{-- bank details start--}}
                                    <div id="detail5" class="tab-pane fade">
                                        <div class="table-responsive panel">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td class="dangor-success">
                                                            <i class="fa-solid fa-money-check"></i>
                                                            Bank Account No *
                                                        </td>
                                                        <td><span id="account_no"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="dangor-success">
                                                            <i class="fa-solid fa-circle-user"></i>
                                                            Bank Name *
                                                        </td>
                                                        <td><span id="bank_name"></span></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="dangor-success">
                                                            <i class="fa-solid fa-circle-user"></i>
                                                            Bank Branch Name *
                                                        </td>
                                                        <td><span id="branch_name"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="dangor-success"><i class="fa fa-book"></i>
                                                            IFSC Code *
                                                        </td>
                                                        <td><span id="ifsc"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="dangor-success">
                                                            <i class="fa-solid fa-file-invoice-dollar"></i>
                                                            Passbook Image *
                                                        </td>
                                                        <td><a href="" target='_blank' id="passbook"><i
                                                                    class="fa fa-download"></i></a></td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    {{-- bank details end --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal ADD PRIs Ended -->

@endsection

@section('custom_js')
<script type="application/javascript">
    @if(isset($filterArray))
        $('#filter_gram_panchyat_id').empty();
        $('#filter_gram_panchyat_id')
            .append($("<option></option>")
                .attr("value", '')
                .text('--Select--'));
        //------------------------ used common in all Search Type ---------------------------------------------------------------------
        @php $filter_tier = $filterArray['filterTier'];@endphp

        //------------------- for ZP Search --------------------------------------------------------------------------------

        @if($filter_tier=='ZP')
        @php $filterZP= $filterArray['filterZP']; @endphp
        $('#filter_tier').val('{{$filter_tier}}');
        $('#filter_zila_id').val('{{$filterZP}}');
        $('#filter_anchalik_id').prop('disabled', true);
        $('#filter_gram_panchyat_id').prop('disabled', true);

        //------------------- for AP Search --------------------------------------------------------------------------------

        @elseif($filter_tier=='AP')
        @php $filterZP= $filterArray['filterZP']; @endphp
        @php $filterAP= $filterArray['filterAP']; @endphp
        $('#filter_tier').val('{{$filter_tier}}');
        $('#filter_zila_id').val('{{$filterZP}}');
        $('#filter_anchalik_id').val('{{$filterAP}}');
        $('#filter_gram_panchyat_id').prop('disabled', true);

        //------------------- for GP Search --------------------------------------------------------------------------------

        @elseif($filter_tier=='GP')
        @php $filterZP= $filterArray['filterZP']; @endphp
        @php $filterAP= $filterArray['filterAP']; @endphp
        @php $filterGP= $filterArray['filterGP']; @endphp
        @php $filterAPList= $filterArray['filterAPList']; @endphp
        @php $filterGPList= $filterArray['filterGPList']; @endphp
        $('#filter_tier').val('{{$filter_tier}}');

        @foreach($filterAPList AS $f_ap)
        $('#filter_anchalik_id')
            .append($("<option></option>")
                .attr("value", '{{$f_ap->id}}')
                .text('{{$f_ap->anchalik_parishad_name}}'));
        @endforeach

        @foreach($filterGPList AS $f_gp)
        $('#filter_gram_panchyat_id')
            .append($("<option></option>")
                .attr("value", '{{$f_gp->id}}')
                .text('{{$f_gp->gram_panchayat_name}}'));
        @endforeach

        $('#filter_zila_id').val('{{$filterZP}}');
        $('#filter_anchalik_id').val('{{$filterAP}}');
        $('#filter_gram_panchyat_id').val('{{$filterGP}}');
        @endif
        @endif

        //-----------desable select box-------------------------------------------------------------------------------------
        function checkOption(obj) {
            // alert(obj);
            if (obj == "AP") {
                $(".ap_cmd").prop('disabled', true);
            } else {
                $(".ap_cmd").prop('disabled', false);
            }
            if (obj == "ZP") {
                $(".zp_cmd").prop('disabled', true);
            } else {
                $(".zp_cmd").prop('disabled', false);
            }
            if (obj == "ZP" || obj == "AP") {
                $(".AZcmd").prop('disabled', true);
            } else {
                $(".AZcmd").prop('disabled', false);
            }
        }

        //-------------------script for APs of Selected Zila Parishad ---------------------------------------------------------------
        $(document).on('change', '#filter_zila_id', function (e) {
            e.preventDefault();
            $('#filter_anchalik_id').empty();

            var zila_id = $(this).val();

            if (zila_id) {
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('selectAnchalAjax')}}',
                    dataType: "json",
                    data: {zila_id: zila_id},
                    success: function (data) {
                        if (data.msgType == true) {

                            $('#filter_anchalik_id')
                                .append($("<option></option>")
                                    .attr("value", '')
                                    .text('--Select--'));

                            $.each(data.data, function (key, value) {
                                $('#filter_anchalik_id')
                                    .append($("<option></option>")
                                        .attr("value", value['id'])
                                        .text(value['anchalik_parishad_name']));
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
        //-------------------script for GPs of Selected Anchalik Parishad ---------------------------------------------------------------

        $(document).on('change', '#filter_anchalik_id', function (e) {
            e.preventDefault();
            $('#filter_gram_panchyat_id').empty();

            var anchalik_id = $(this).val();

            if (anchalik_id) {
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('selectGramAjax')}}',
                    dataType: "json",
                    data: {anchalik_id: anchalik_id},
                    success: function (data) {
                        if (data.msgType == true) {

                            $('#filter_gram_panchyat_id')
                                .append($("<option></option>")
                                    .attr("value", '')
                                    .text('--Select--'));

                            $.each(data.data, function (key, value) {
                                $('#filter_gram_panchyat_id')
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

        $('.viewPri').on('click', function (e) {
            e.preventDefault();

            var priCode = $(this).data('pricode');

            $('.page-loader-wrapper').fadeIn();

            $('.v_zp_tr').hide();
            $('.v_zp_tr_only').hide();
            $('.v_ap_tr').hide();
            $('.v_ap_tr_only').hide();
            $('.v_gp_tr').hide();
            $('.v_gp_tr_only').hide();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route("admin.Pris.viewPri")}}',
                dataType: "json",
                data: {pri_code: priCode},
                success: function (data) {
                    if (data.msgType == true) {
                        $('.viewPriData span').text('');

                        var res = data.data.res;
                        var bank=data.data.bank;
                        var priName = '';
                        var priPost = '';
                        var zilaName = 'NA';
                        var anchalikName = 'NA';
                        var gramName = 'NA';
                        if (res.pri_m_name != null) {
                            priName = res.pri_f_name + " " + res.pri_m_name + " " + res.pri_l_name;
                        } else {
                            priName = res.pri_f_name + " " + res.pri_l_name;
                        }


                        $('#v_pri_name').text(priName);

                        if(res.ap_constituency){
                            $('#v_ap_constituency').text(res.ap_constituency);
                        }

                        if(res.ward_name){
                            $('#v_gp_ward').text(res.ward_name);
                        }

                        if(res.differently_abled==1){
                            $('#v_diffrently_abled').text("YES");
                        }else{
                            $('#v_diffrently_abled').text("NO");
                        }

                        $('#v_dob').text(formatDate(res.dob));
                        $('#v_o_add').text(res.o_add);
                        $('#v_o_pin').text(res.o_pin);
                        $('#v_p_add').text(res.p_add);
                        $('#v_p_district').text(res.p_district_name);
                        $('#v_p_pin').text(res.p_pin);
                        $('#v_mobile_no').text(res.mobile_no);
                        $('#v_seat_name').text(res.seat_name);
                        $('#v_constituency').text(res.constituency);
                        $('#v_party_name').text(res.party_name);
                        $('#v_gender_name').text(res.gender_name);
                        $('#v_religion_name').text(res.religion_name);
                        $('#v_blood_name').text(res.blood_name);
                        $('#v_qual_name').text(res.qual_name);
                        $('#v_caste_name').text(res.caste_name);
                        $('#v_marital_status_name').text(res.marital_status_name);
                        $('#v_occupation').text(res.occupation);
                        $('#v_income_name').text(res.income_name);
                        $('#v_mt_id').text(res.mt_id);

                        // ning bank details start
                        $('#account_no').text(bank.account_no);
                        $('#bank_name').text(bank.bank_name);
                        $('#branch_name').text(bank.branch_name);
                        $('#ifsc').text(bank.ifsc_code);
                        if(bank.pass_book != 'NA'){
                            $('#passbook').attr('href','https://pnrdassam.org/SPIRDMDAS/storage/app/'+bank.pass_book);

                        }else{
                            $('#passbook').attr('href','NA');

                        }

                        // ning bank details end

                        // designation as per terms start
                        /* all_terms*/
                        $('#term_display').empty();

                        $.each(data.data.all_terms, function (key, value) {
                            var i = 0;
                            $.each(data.data.terms, function (key1, value1) {
                                if (value1.pmt_id == value.id) {
                                    i = 1;
                                    $('#term_display').append('<tr class="term-tr">' +
                                        '<td style="border: 1px solid #86bbe8;text-align: center;"><span>' + value.term_name + '</span></td>' +
                                        '<td style="border: 1px solid #86bbe8;text-align: center;"><span>' + value1.pmd_d_name + '</span></td>' +
                                        '</tr>');
                                }
                            });

                            if (i == 0) {
                                $('#term_display').append('<tr class="term-tr">' +
                                    '<td style="border: 1px solid #86bbe8;text-align: center;"><span>' + value.term_name + '</span></td>' +
                                    '<td style="border: 1px solid #86bbe8;text-align: center;"><span></span></td>' +
                                    '</tr>');
                            }
                        });

                        if (res.pri_pic) {
                            $('#pri_image').attr('src', '{{$imgUrl}}' + res.pri_pic);
                        }

                        // disignation start

                        if (res.design_id ==1 || res.design_id ==2 || res.design_id ==7) {
                            $('.v_zp_tr').show();
                            $('.v_zp_tr_only').show();
                        }else if (res.design_id ==3 || res.design_id ==4 || res.design_id ==8) {
                            $('.v_zp_tr').show();
                            $('.v_ap_tr').show();
                            $('.v_ap_tr_only').show();
                        }else if (res.design_id ==5 || res.design_id ==6 || res.design_id ==9) {
                            $('.v_zp_tr').show();
                            $('.v_ap_tr').show();
                            $('.v_gp_tr').show();

                            if (res.design_id ==6){
                                $('.v_gp_tr_only').show();
                            }
                        }

                        priPost = res.design_name;

                        $('.v_dign_name').text(priPost);

                        //disignation end//

                        if (res.zila_parishad_name) {
                            zilaName = res.zila_parishad_name;
                        }

                        $('#v_zilaName').text(zilaName);

                        if (res.anchalik_parishad_name) {
                            anchalikName = res.anchalik_parishad_name;
                        }

                        $('#v_anchalikName').text(anchalikName);

                        if (res.gram_panchayat_name) {
                            gramName = res.gram_panchayat_name;
                        }

                        $('#v_gramName').text(gramName);

                        //
                        $('#myModalPri').modal({
                            backdrop: 'static',
                            keyboard: false
                        });

                    } else {
                        swal("Information", data.msg, "info");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                },
                complete: function (data) {
                    $('.page-loader-wrapper').fadeOut();
                }
            });
        });

        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();
            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;
            return [day, month, year].join('-');
        }
</script>
@endsection