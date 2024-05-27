@php
$page_title="PRIs_Menbers";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
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

    .form-control {
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

    .btn_ning {
        background-color: green;
    }

    .btn_ning:hover {
        background-color: #c7ddb5;
    }

    .btn_ning:active {
        background-color: #c7ddb5;
    }
</style>
@endsection

@section('content')
<div class="row">
    <ol class="breadcrumb">
        <li><a href="{{route('dashboard')}}">Home</a></li>
        <li class="active">Member Entry</li>
    </ol>
</div>

<div class="row mt40">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-12 col-sm-12 col-xs-12">

            <button type="button" class="btn btn-primary pull-right" id="addPriMember"
                style="margin-left:5px; margin-top:5px">
                <i class="fa fa-plus"></i>
                Add PRIs Member
            </button>

        </div>
    </div>
</div>

<div class="container" style="margin-bottom: 40px">
    <div class="row">
        @if(count( $data['priList'] ) > 0)
        <div class="col-md-12 col-sm-12 col-xs-12">
            <marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();"> 
                <strong>
                Please download the manual to understand how to upload bank details ---> <a href="{{asset('mdas_assets/MDAS_Portal_guidelines.pdf')}}">Bank details Guidelines</a>
                </strong>
            </marquee>
            <h5>Showing PRIs Members</h5>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable1">
                    <thead>
                        <tr class="bg-primary">
                            <td>#</td>
                            <td>Photo</td>
                            <td>PRI Code</td>
                            <td>Name</td>
                            <td>Designation</td>
                            <td>Submitted By/On</td>
                            <td>Updated By/On</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1;$j=0;@endphp
                        @foreach($data['priList'] AS $members)

                        <tr>
                            <td style="padding: 2px">
                                <p
                                    style="border-left:5px solid {{$members->color_code}};margin-left: -2px;padding: 15px 15px;margin-bottom: 0;">
                                    {{$i++}}</p>
                            </td>
                            <td>
                                @if($members->pri_pic)
                                <img src="{{$imgUrl}}{{$members->pri_pic}}"
                                    style="border:1px solid #ddd;width:30px;height:40px;" />
                                @else
                                <img src="{{asset('mdas_assets/images/user_add.png')}}"
                                    style="border:1px solid #ddd;width:30px;height:40px;" />
                                @endif
                            </td>
                            <td>{{$members->pri_code}}</td>
                            <td>{{$members->pri_f_name}} {{$members->pri_m_name}} {{$members->pri_l_name}}</td>
                            <td>{{$members->design_name}}</td>
                            <td>
                                {{$members->created_by}}<br />
                                <span style="font-size: 11px">{{\Carbon\Carbon::parse($members->created_at)->format('d M
                                    Y')}}</span>
                            </td>
                            <td>
                                {{$members->updated_by}} <br />
                                @if($members->updated_by)
                                <span style="font-size: 11px">{{\Carbon\Carbon::parse($members->updated_at)->format('d M
                                    Y')}}</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-warning btn-round viewPri"
                                    data-pricode="{{$members->pri_code}}">
                                    <i class="fa fa-edit"></i>
                                </button>
                                {{-- bank details button --}}
                                @if($bankrecord[$j] == 0)
                                <button type="button" class="btn btn-round btn_ning viewBankPri"
                                    data-pricode="{{$members->pri_code}}">
                                    <i class="fa-solid fa-piggy-bank"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @php
                        $j++;
                        @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>






        {{--<div class="col-md-4 col-sm-6 col-xs-12 animated zoomIn" style="margin-bottom: 50px;">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="row pri"
                    style="border-top:4px solid {{$members->color_code}}; box-shadow: 0px 0px 8px 0px #a7a6a4;">
                    <table class="table table-condensed">
                        <tr>
                            <td rowspan="4" style="vertical-align: middle;padding: auto"
                                data-pricode="{{$members->pri_code}}">
                                @if($members->pri_pic)
                                <img src="{{$imgUrl}}{{$members->pri_pic}}"
                                    style="border:1px solid #ddd;width:80px;height:90px;" />
                                @else
                                <img src="{{asset('mdas_assets/images/user_add.png')}}"
                                    style="border:1px solid #ddd;width:80px;height:90px;" />
                                @endif
                            </td>
                            <th>PRI Code</th>
                            <td>{{$members->pri_code}}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td style="height:50px">{{$members->pri_f_name}} {{$members->pri_m_name}}
                                {{$members->pri_l_name}}</td>
                        </tr>
                        <tr>
                            <th>Designation</th>
                            <td>{{$members->design_name}}</td>
                        </tr>
                    </table>

                    <div class="overlay">
                        <button type="button" class="btn btn-warning btn-round viewPri" addPriForm
                            data-pricode="{{$members->pri_code}}">
                            <i class="fa fa-edit"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>--}}

        @else
        <div class="col-md-12 col-sm-12 col-xs-12 animated zoomIn" style="margin-bottom: 27px;margin-top: 50px;">
            <h4 class="text-center">No PRIs Members submitted by you yet. Please click the <label
                    style="font-size:20px">Add PRIs Member</label> button above to upload the PRIs Member to your list.
            </h4>
        </div>
        @endif
    </div>
</div>



<!-- Modal ADD PRIs & edit PRI (both)-->

<div class="modal fade" id="myModalAddPri" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-close"></i>
                </button>
                <h4 class="modal-title">Add PRIs Member</h4>
                <p>Fields with asterisk (<strong>*</strong>) are required.</p>
            </div>
            <form action="#" method="POST" id="addPriForm" autocomplete="off">
                <input type="hidden" name="editCode" id="editCodeAdd" value="" />
                <input type="hidden" name="editPriCode" id="editPriCodeAdd" value="" />
                <div class="modal-body">
                    <!------------------------- TOP BAND ------------------------------>
                    <h5 style="margin-top: -5px">A. General Details</h5>
                    <div class="row well">
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Designation <strong>*</strong></label>
                                <select class="form-control" name="t_deg4" id="t_deg4">
                                    <option value="">--Select Designation--</option>
                                    @foreach($data['designList'] AS $design)
                                    <option value="{{$design->id}}">{{$design->design_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-4 col-xs-12 dis">
                            <div class="form-group">
                                <label>Zilla Parishad <strong>*</strong></label>
                                <select class="form-control" name="zilla_id" id="zilla_id">
                                    <option value="">--Select--</option>
                                    @if($data['zpData'])
                                    <option value="{{$data['zpData']->id}}" selected="selected">
                                        {{$data['zpData']->zila_parishad_name}}</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-4 col-xs-12 dis dis-ap-main">
                            <div class="form-group">
                                <label>Anchalik Panchayat <strong>*</strong></label>
                                <select class="form-control" name="anchalik_code" id="anchalik_code">
                                    <option value="">--Select--</option>
                                    @if($data['level']=="ZP")
                                    @foreach($data['apList'] AS $ap)
                                    <option value="{{$ap->id}}">{{$ap->anchalik_parishad_name}}</option>
                                    @endforeach
                                    @else
                                    @if($data['apData'])
                                    <option value="{{$data['apData']->id}}" selected="selected">
                                        {{$data['apData']->anchalik_parishad_name}}</option>
                                    @endif
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-4 col-xs-12 dis dis-gp-main">
                            <div class="form-group">
                                <label>Gram Panchayat <strong>*</strong></label>
                                <select class="form-control" name="gp_code" id="gp_code">
                                    <option value="">--Select--</option>
                                    @if($data['level']=="AP")
                                    @foreach($data['gpList'] AS $gp)
                                    <option value="{{$gp->id}}">{{$gp->gram_panchayat_name}}</option>
                                    @endforeach
                                    @elseif($data['level']=="GP")
                                    @if($data['gpData'])
                                    <option value="{{$data['gpData']->id}}">{{$data['gpData']->gram_panchayat_name}}
                                    </option>
                                    @endif
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-4 col-xs-12 dis dis-ward" style="clear: both;display: none">
                            <div class="form-group">
                                <label>Ward Number <strong>*</strong></label>
                                <select class="form-control" name="ward_no" id="ward_no">
                                    <option value="">--Select--</option>
                                    @foreach($data['wardList'] AS $ward)
                                    <option value="{{$ward->id}}">{{$ward->ward_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-4 col-xs-12 dis">
                            <div class="form-group">
                                <label>Is the seat reserved? <strong>*</strong></label>
                                <select class="form-control" name="seat_reserved" id="seat_reserved">
                                    <option value="">--Select--</option>
                                    @foreach($data['rSeatList'] AS $seat)
                                    <option value="{{$seat->id}}">{{$seat->seat_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if($data['level']=="ZP" || $data['level']=="AP")

                        @if($data['level']=="ZP")
                        <div class="col-md-3 col-sm-4 col-xs-12 dis dis-zp">
                            <div class="form-group">
                                <label>Constituency <strong>*</strong></label>
                                <input type="text" class="form-control" name="constituency" id="constituency" />
                            </div>
                        </div>
                        @endif

                        <div class="col-md-3 col-sm-4 col-xs-12 dis dis-ap">
                            <div class="form-group">
                                <label>AP Constituency <strong>*</strong></label>
                                <select class="form-control" name="ap_constituency" id="ap_constituency">
                                    <option value="">--Select--</option>
                                    @if($data['level']=="AP")
                                    @foreach($data['gpList'] AS $gp)
                                    <option value="{{$gp->id}}">{{$gp->gram_panchayat_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-3 col-sm-4 col-xs-12 dis">
                            <div class="form-group">
                                <label>Political Party <strong>*</strong></label>
                                <select class="form-control" name="party_id" id="party_id">
                                    <option value="">--Select--</option>
                                    @foreach($data['politicalList'] AS $political)
                                    <option value="{{$political->id}}">{{$political->party_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!------------------------- MIDDLE BAND ------------------------------>
                    <h5>B. Personal Details</h5>
                    <div class="row well">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group text-center">
                                <img id="pri_image" src="{{asset('mdas_assets/images/user_add.png')}}"
                                    style="border:1px solid #ddd;width:120px;max-height:130px;cursor:pointer" />
                                <input type="file" name="pic" id="pic" style="display: none" /><br>
                                <label>Click the above image to upload passport photo</label>
                                <a href="#" data-toggle="tooltip"
                                    title="Note: Click on the photo to upload passport photo. Upload jpg, jpeg and png file only. Max image size should not exceed 100KB and not less than 10KB">
                                    <i class="fa fa-question-circle"></i>
                                </a>
                            </div>
                            <div class="form-group">
                                <label>Photo Identity Proof of Member (PAN, Voter ID etc.)</label>
                                <a href="#" data-toggle="tooltip"
                                    title="Note: Upload jpg, jpeg and png file only. Max image size should not exceed 200KB and not less than 10KB">
                                    <i class="fa fa-question-circle"></i>
                                </a>
                                <p id="photo_i_proof_view">
                                    <a class="btn btn-warning btn-xs" href="#" target="_blank">View uploded Id Proof</a>
                                </p>
                                <input type="file" name="photo_i_proof" id="photo_i_proof" />
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label>First Name <strong>*</strong></label>
                                        <input type="text" class="form-control" name="pri_f_name" id="pri_f_name" />
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label>Middle Name</label>
                                        <input type="text" class="form-control" name="pri_m_name" id="pri_m_name" />
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label>Last Name <strong>*</strong></label>
                                        <input type="text" class="form-control" name="pri_l_name" id="pri_l_name" />
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label>Mobile Number <strong>*</strong></label>
                                        <input type="tel" class="form-control" name="mobile_no" id="mobile_no" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Date Of Birth (Age: ) <strong>*</strong></label>
                                        <input type="text" class="form-control" name="pri_dob" id="pri_dob" />
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6 dis-profile">
                                    <div class="form-group">
                                        <label>Gender <strong>*</strong></label>
                                        <select class="form-control" name="gender_id" id="gender_id">
                                            <option value="">--Select--</option>
                                            @foreach($data['genderList'] AS $gender)
                                            <option value="{{$gender->id}}">{{$gender->gender_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6 dis-profile">
                                    <div class="form-group">
                                        <label>Caste <strong>*</strong></label>
                                        <select class="form-control" name="caste_id" id="caste_id">
                                            <option value="">--Select--</option>
                                            @foreach($data['casteList'] AS $caste)
                                            <option value="{{$caste->id}}">{{$caste->caste_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label>Religion <strong>*</strong></label>
                                        <select class="form-control" name="religion_id" id="religion_id">
                                            <option value="">--Select--</option>
                                            @foreach($data['religionList'] AS $religion)
                                            <option value="{{$religion->id}}">{{$religion->religion_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label>Blood Group </label>
                                        <select class="form-control" name="blood_group_id" id="blood_group_id">
                                            <option value="">--Select--</option>
                                            @foreach($data['bloodList'] AS $blood)
                                            <option value="{{$blood->id}}">{{$blood->blood_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label>Diffrently Abled ? <strong>*</strong></label>
                                        <select class="form-control" name="differently_abled" id="differently_abled">
                                            <option value="">--Select--</option>
                                            <option value="1">YES</option>
                                            <option value="0">NO</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label>Marital Status <strong>*</strong></label>
                                        <select class="form-control" name="marital_status_id" id="marital_status_id">
                                            <option value="">--Select--</option>
                                            @foreach($data['maritalList'] AS $marital)
                                            <option value="{{$marital->id}}">{{$marital->marital_status_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label>Occupation</label>
                                        <input type="text" class="form-control" name="occupation" id="occupation" />
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label>Alt Mobile Number</label>
                                        <input type="number" class="form-control" name="alt_mobile_no"
                                            id="alt_mobile_no" />
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label>Annual Income <strong>*</strong></label>
                                        <select class="form-control" name="income_id" id="income_id">
                                            <option value="">--Select--</option>
                                            @foreach($data['incomeList'] AS $income)
                                            <option value="{{$income->id}}">{{$income->income_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6 dis-profile">
                                    <div class="form-group">
                                        <label>Highest Qualification<strong>*</strong></label>
                                        <select class="form-control" name="qual_id" id="qual_id">
                                            <option value="">--Select--</option>
                                            @foreach($data['qualList'] AS $qual)
                                            <option value="{{$qual->id}}">{{$qual->qual_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!------------------------- LOWER BAND ------------------------------>
                    <h5>C. History and Address</h5>
                    <div class="row well">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Official Address <strong>*</strong></label>
                                    </div>
                                    <div class="form-group">
                                        <label>Address <strong>*</strong></label>
                                        <textarea class="form-control" name="o_add" id="o_add"
                                            placeholder="Full postal address of office."></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Pin Code <strong>*</strong></label>
                                                <input type="number" class="form-control" name="o_pin" id="o_pin" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Permanent Address <strong>*</strong></label>
                                    </div>
                                    <div class="form-group">
                                        <label>Address <strong>*</strong></label>
                                        <textarea class="form-control" name="p_add" id="p_add"></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <div class="form-group">
                                                <label>District <strong>*</strong></label>
                                                <select class="form-control" name="p_district" id="p_district">
                                                    <option value="">--Select--</option>
                                                    @foreach($data['districtList'] AS $district)
                                                    <option value="{{$district->id}}">{{$district->district_name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Pin Code <strong>*</strong></label>
                                                <input type="number" class="form-control" name="p_pin" id="p_pin" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!------------------------- Bank band ------------------------------>
                    <h5>D. Bank Details</h5>
                    <div class="row well">
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Bank Name <strong>*</strong></label>
                                <input type="text" class="form-control" name="bank_name_view" id="bank_name_view"
                                    disabled />

                            </div>
                        </div>

                        <div class="col-md-3 col-sm-4 col-xs-12 dis">
                            <div class="form-group">
                                <label>Bank Account No <strong>*</strong></label>
                                <input type="number" class="form-control" name="account_no_view" id="account_no_view"
                                    disabled />

                            </div>
                        </div>

                        <div class="col-md-3 col-sm-4 col-xs-12 dis">
                            <div class="form-group">
                                <label>IFSC Code<strong>*</strong></label>
                                <input type="text" class="form-control" name="ifsc_view" id="ifsc_view" disabled />
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-4 col-xs-12 dis">
                            <div class="form-group">
                                <label>Branch Name <strong>*</strong></label>
                                <input type="text" class="form-control" name="branch_name_view" id="branch_name_view"
                                    disabled />
                            </div>
                        </div>


                        <div class="col-md-5 col-sm-4 col-xs-12 dis">
                            <div class="form-group">
                                <label>Passbook Image<strong>*</strong></label>
                                <div>
                                    <a href="" target='_blank' id="passbook">
                                        <i class="fa fa-download"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">
                            <i class="fa fa-times"></i>
                            Close
                        </button>
                        <button type="button" class="btn btn-primary btn-save btn_ningt" id="btn_save_add_pri">
                            <i class="fa-sharp fa-solid fa-paper-plane"></i>
                            Submit PRI
                        </button>
                        <button type="button" class="btn btn-primary btn-edit btn_ningt" id="editAndSave">
                            <i class="fa-solid fa-check"></i>
                            Edit & Save
                        </button>
                    </div>

            </form>
        </div>
    </div>

</div>
</form>
</div>
</div>
</div>

<!-- Modal ADD PRIs Ended -->

{{-- Bank modal start --}}
<div class="modal fade" id="myModalAddPriBank" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-close"></i>
                </button>
                <h4 class="modal-title">Add Bank Details</h4>
                <p>Fields with asterisk (<strong>*</strong>) are required.</p>
            </div>
            <form action="#" method="POST" id="addPriBankForm" autocomplete="off">
                <input type="hidden" name="editCode" id="editCode" value="" />
                <input type="hidden" name="editPriCode" id="editPriCode" value="" />
                <div class="modal-body">
                    <!------------------------- only one BAND ------------------------------>
                    <h4>A. Bank Details</h4>
                    <div class="row well">
                        <div>
                            <div class="form-group">
                                <div class="col-md-5 dis">
                                    <label>Bank Name <strong>*</strong></label>
                                    <br>
                                    <select class="bank-select form-control" name="bank_name" id="bank_name">
                                        <option value="">---------Select Bank---------</option>
                                        @foreach($banks as $bank)
                                        <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <label class="text text-danger bank_name"></label> --}}
                            </div>
                        </div>

                        <div class="col-md-5 col-sm-4 col-xs-12 dis">
                            <div class="form-group">
                                <label>Branch Name <strong>*</strong></label>
                                <select class="form-control" name="branch_name_select" id="branch_name_select">
                                </select>
                                <input type="hidden" name="branch_name" id="branch_name">
                                <label class="text text-danger branch_name"></label>
                            </div>
                        </div>


                        <div class="col-md-5 col-sm-4 col-xs-12 dis">
                            <div class="form-group">
                                <label>IFSC Code<strong>*</strong></label>
                                <input type="text" class="form-control" name="ifsc" id="ifsc" readonly />
                                <label class="text text-danger ifsc"></label>
                            </div>
                        </div>

                        <div class="col-md-5 col-sm-4 col-xs-12 dis">
                            <div class="form-group">
                                <label>Bank Account No <strong>*</strong></label>
                                <input inputmode="numeric" oninput="this.value = this.value.replace(/\D+/g, '')"
                                    class="form-control" name="acc_no" id="acc_no" />

                                <label class="text text-danger acc_no"></label>
                            </div>
                        </div>

                        <div class="col-md-5 col-sm-4 col-xs-12 dis">
                            <div class="form-group">
                                <label>Bank Passbook (only front page) <strong>*</strong></label>
                                <label>(in pdf, png, jpg, jpeg format)</label>
                                <input type="file" class="form-control" name="bank_image" id="bank_image" />
                                <label class="text text-danger bank_image"></label>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-times"></i>
                        Close
                    </button>
                    <input type="hidden" name="pricode" id="pricode" />
                    <button type="button" class="btn btn-primary " id="btn-bank">
                        <i class="fa fa-check"></i>
                        Submit
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>
{{-- Bank modal end --}}

@endsection

@section('custom_js')
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="application/javascript">
    $(document).ready(function () {
            $('#dataTable1').DataTable();
        });

        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });

        // ----------------------- RESET FORM DATA AND OPEN MODAL -------------------------------

        $('#addPriMember').on('click', function(e){
            e.preventDefault();

            $('#addPriForm')[0].reset();
            $('.term-tr :input').prop("disabled", true);
            $('.term-tr :input').val('');
            $('#pic').val('');
            $('#pri_image').attr('src', '{{asset('mdas_assets/images/user_add.png')}}');
            $('#photo_i_proof').val('');
            $('#photo_i_proof_view').hide();
            $('#photo_i_proof_view a').attr('href', '#');

            $('.dis :input').val('');
            $('.dis-zp').hide();
            $('.dis-ap').hide();
            $('.dis-ward').hide();

            $('.form_errors').remove();

            $('#editCode').val('');
            $('#editCodeAdd').val('');
            $('#editPriCode').val('');
            $('#editPriCodeAdd').val('');

            $('#myModalAddPri .modal-footer .btn-save').show();
            $('#myModalAddPri .modal-footer .btn-edit').hide();
            $('#myModalAddPri .modal-title').text('Add PRIs Member');

            

            $('#myModalAddPri').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        $(document).on('click','.viewBankPri',function(){

        $("#pricode").val($(this).data('pricode'));
            $('#myModalAddPriBank').modal({
                backdrop: 'static',
                keyboard: false
            });

        })

        /*$('#earlier_pri').on('change', function(e){
            e.preventDefault();
            var value= $('#earlier_pri').val();

            if(value==1){
                $('.term-tr :input').prop("disabled", false);
            }else{
                $('.term-tr :input').prop("disabled", true);
                $('.term-tr :input').val('');
            }
        });*/

        $('#pri_dob').Zebra_DatePicker({
            direction: ['{{\Carbon\Carbon::parse('2018-01-01')->subYears(100)->format('Y-m-d')}}', '{{\Carbon\Carbon::parse('2018-01-01')->subYears(18)->format('Y-m-d')}}']
        });

        $('#pic').change(function () {
            if (this.files && this.files[0]) {
                checkImage(this.files[0]);
            }
        });

        function imageIsLoaded(e) {
            $('#pri_image').attr('src', e.target.result);
        }

        function checkImage(file){
            var extension = file.name.substr((file.name.lastIndexOf('.') + 1));

            if (extension === 'jpg' || extension === 'jpeg' || extension === 'png') {

                var img=file.size;
                var imgsize=img/1024;
                if(imgsize >= 10 && imgsize <=110){
                    var reader = new FileReader();
                    reader.onload = imageIsLoaded;
                    reader.readAsDataURL(file);
                }else{
                    swal("Information", "Image size must be less than or equal to 100 KB and greater than 10 KB!", "info");
                    $('#pic').val('');
                    $('#pri_image').attr('src', '{{asset('mdas_assets/images/user_add.png')}}');
                    exit();
                }

            } else {
                swal("Information", "Please select only jpeg, jpg and png format only!", "info");
                $('#pic').val('');
                $('#pri_image').attr('src', '{{asset('mdas_assets/images/user_add.png')}}');
                exit();
            }

        }

        $('#pri_image').click(function(e){
            e.preventDefault();
            $('#pic').click()
        });

        @if($data['level'] == "ZP")

        $('#anchalik_code').on('change', function(e){

            e.preventDefault();
            $('#gp_code').empty();
            $('#ap_constituency').empty();
            var anchalik_code= $('#anchalik_code').val();

            if(anchalik_code){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('common.category.getGPsByAnchalikId')}}',
                    dataType: "json",
                    data: {anchalik_code : anchalik_code},
                    success: function (data) {
                        if (data.msgType == true) {


                                $('#ap_constituency')
                                .append($("<option></option>")
                                    .attr("value", '')
                                    .text('--Select--'));

                            $.each(data.data, function(key, value) {
                                $('#ap_constituency')
                                    .append($("<option></option>")
                                        .attr("value", value['id'])
                                        .text(value['gram_panchayat_name']));
                            });

                                $('#gp_code')
                                .append($("<option></option>")
                                    .attr("value", '')
                                    .text('--Select--'));

                            $.each(data.data, function(key, value) {
                                $('#gp_code')
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

        @endif


        //Validation on Changes------------------------

        $('#t_deg4').on('change', function(e){
            e.preventDefault();
            var deg = $('#t_deg4').val();
            $('.dis :input').val('');
            $('.dis-profile :input').val('');

            @if($data['level'] !="AP")
                $('#ap_constituency').empty();
            @endif

            if(deg==1 || deg==2 || deg==7){
                $('.dis-zp').show();

                $('.dis-ap').hide();
                $('.dis-ap-main').hide();
                $('.dis-gp-main').hide();
                $('.dis-ward').hide();

            }else if(deg==3 || deg==4 || deg==8){
                $('.dis-ap').show();
                $('.dis-ap-main').show();

                $('.dis-zp').hide();
                $('.dis-gp-main').hide();
                $('.dis-ward').hide();
            }else if(deg==5 || deg==6 || deg==9){

                $('.dis-ap-main').show();
                $('.dis-gp-main').show();

                $('.dis-zp').hide();
                $('.dis-ap').hide();

                if(deg==5){// IF GP PRESIDENT HIDE WARD NO.
                    $('.dis-ward').hide();
                }else{
                    $('.dis-ward').show();
                }

            }else{
                $('.dis-ap').hide();
                $('.dis-zp').hide();
            }
        });

        $('#seat_reserved').on('change', function(e){
            e.preventDefault();
            $('.dis-profile :input').val('');
            $("#caste_id option").show();
            $("#gender_id option").show();

            var seat_reserved = $('#seat_reserved').val();

            if(seat_reserved==2){
                $("#caste_id option").hide();
                $("#caste_id option[value='']").show();
                $("#caste_id option[value='2']").show();
            }else if(seat_reserved==3){
                $("#caste_id option").hide();
                $("#caste_id option[value='']").show();
                $("#caste_id option[value='3']").show();
            }else if(seat_reserved==4){
                $("#gender_id option").hide();
                $("#gender_id option[value='']").show();
                $("#gender_id option[value='2']").show();
            }
        });

        $('#qual_id').on('change', function(e){
            e.preventDefault();

            var qual_id = $('#qual_id').val();
            var qualArray= ['1','2','3','4','5','6','7','8','9'];

            if($.inArray(qual_id, qualArray)>=0){
                var t_deg4 = $('#t_deg4').val();
                var caste_id = $('#caste_id').val();

                if(t_deg4=="" && caste_id==""){
                    alert("Please select designation and caste first.");
                    $('#qual_id').val('');
                }else if(t_deg4==""){
                    alert("Please select designation first.");
                    $('#qual_id').val('');
                }else if(caste_id==""){
                    alert("Please select caste first.");
                    $('#qual_id').val('');
                }
            }
        });

        $('#caste_id').on('change', function(e){
            e.preventDefault();

            var t_deg4 = $('#t_deg4').val();
            var seat_reserved = $('#seat_reserved').val();

            if(t_deg4==""){
                alert("Please select designation first.");
                $('#caste_id').val('');
            }else if(seat_reserved==""){
                alert("Please select Is the seat reserved first.");
                $('#caste_id').val('');
            }

            var caste_id = $('#caste_id').val();

            $("#qual_id option").show();

            if(caste_id==1 && (t_deg4==1 || t_deg4==2 || t_deg4==7)){ // ZP
                $("#qual_id option").hide();
                $("#qual_id option[value='']").show();
                $("#qual_id option[value='6']").show();
                $("#qual_id option[value='7']").show();
                $("#qual_id option[value='8']").show();
            }else if((caste_id==2 || caste_id==3 || caste_id==4 || caste_id==5) && (t_deg4==1 || t_deg4==2 || t_deg4==7)){ // ZP
                $("#qual_id option").hide();
                $("#qual_id option[value='']").show();
                $("#qual_id option[value='5']").show();
                $("#qual_id option[value='6']").show();
                $("#qual_id option[value='7']").show();
                $("#qual_id option[value='8']").show();
            }else if(caste_id==1 && (t_deg4==3 || t_deg4==4 || t_deg4==8)){ //AP
                $("#qual_id option").hide();
                $("#qual_id option[value='']").show();
                $("#qual_id option[value='5']").show();
                $("#qual_id option[value='6']").show();
                $("#qual_id option[value='7']").show();
                $("#qual_id option[value='8']").show();
            }else if((caste_id==2 || caste_id==3 || caste_id==4 || caste_id==5) && (t_deg4==3 || t_deg4==4 || t_deg4==8)){
                $("#qual_id option").hide();
                $("#qual_id option[value='']").show();
                $("#qual_id option[value='4']").show();
                $("#qual_id option[value='5']").show();
                $("#qual_id option[value='6']").show();
                $("#qual_id option[value='7']").show();
                $("#qual_id option[value='8']").show();
            }else if(caste_id==1 && (t_deg4==5 || t_deg4==6 || t_deg4==9)) { //GP

                $("#qual_id option[value='1']").hide();
                $("#qual_id option[value='2']").hide();

            }

        })


        // -------------------------- ON SUBMIT --------------------------
        $(document).on('click', '.btn_ningt', function() {
            console.log("save");
        
            Swal.fire({
                    title: 'Are you sure?',
                    text: "You are sure you want to save the pri data.v",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'Cancel'
            }).then((result) => {
                    if (result.isConfirmed) {
                        $('.form_errors').remove();
                        $('.page-loader-wrapper').fadeIn();


                        var form = $('#addPriForm')[0];
                        var formData = new FormData(form);
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '{{route('pris.members.save')}}',
                            dataType: "json",
                            data: formData,
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                if (data.msgType == true) {
                                    swal.fire({
                                        // need to do in sweetalert2
                                        title:'Success',
                                        icon:'success',
                                        type:'success',
                                        text:'Successfully Submitted'
                                        // ("Success", data.msg, "success")
                                    }).then((value) => {
                                        $('#myModalAddPri').modal('hide');
                                        location.reload();
                                        })
                                }
                                else{

                                    if(data.msg=="VE"){
                                        swal.fire("Error", "Validation error.Please check the form correctly!", "error");
                                        $.each(data.errors, function( index, value ) {
                                            $('#'+index).after('<p class="text-danger form_errors">'+value+'</p>');
                                        });
                                    }else{
                                        swal.fire("Error", data.msg, "error");
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
                    } else {
                        swal.fire({
                            title:'Cancelled',
                            text:'Your cancelled the operation! :)',
                            icon:'error'
                        }) 
                    }
                });
        });

        // -------------------------- ON SUBMIT --------------------------

        // -------------------------- VIEW PRI --------------------------

        $('.viewPri').on('click', function(e){
            e.preventDefault();

            var priCode = $(this).data('pricode');

            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('pris.members.view')}}',
                dataType: "json",
                data: {pri_code : priCode},
                success: function (data) {
                    if (data.msgType == true) {
                        var bank = data.data.bank;
                        $('.dis-zp').hide();
                        $('.dis-ap-main').hide();
                        $('.dis-ap').hide();
                        $('.dis-ward').hide();
                        $('#photo_i_proof_view').hide();

                        $('#pri_image').attr('src', '{{asset('mdas_assets/images/user_add.png')}}');
                        $('#photo_i_proof_view a').attr('href', '#');
                        $('#photo_i_proof').val('');
                        $('#pic').val('');

                        $('#t_deg1').val('');
                        $('#t_deg2').val('');
                        $('#t_deg3').val('');
                        $('#t_deg4').val('');

                        $('#zilla_id').val('');
                        $('#constituency').val('');

                        $('#anchalik_code').val('');

                        @if($data['level']!="AP")
                            $('#ap_constituency').empty();
                            $('#ap_constituency')
                                .append($("<option></option>")
                                    .attr("value", '')
                                    .text('--Select--'));

                            $('#gp_code').empty();
                            $('#gp_code')
                                .append($("<option></option>")
                                    .attr("value", '')
                                    .text('--Select--'));
                        @endif

                        $('#ap_constituency').val('');


                        $('#gp_code').val('');

                        $('#ward_no').val('');

                        $('#seat_reserved').val('');
                        $('#party_id').val('');

                        $('#pri_f_name').val('');
                        $('#pri_m_name').val('');
                        $('#pri_l_name').val('');

                        $('#mobile_no').val('');
                        $('#pri_dob').val('');
                        $('#gender_id').val('');
                        $('#caste_id').val('');
                        $('#religion_id').val('');
                        $('#blood_group_id').val('');
                        $('#differently_abled').val('');
                        $('#marital_status_id').val('');
                        $('#occupation').val('');
                        $('#alt_mobile_no').val('');
                        $('#income_id').val('');
                        $('#qual_id').val('');

                        $('#earlier_pri').val('');

                        $('#o_add').val('');
                        $('#o_pin').val('');

                        $('#p_district').val('');
                        $('#p_pin').val('');
                        $('#p_add').val('');

                        //MAIN STARTS FROM HERE--------------------------------------------

                        $.each(data.data.priMemberHistory, function(index, value) {

                            $('#t_deg'+value.master_pri_term_id).val(value.pri_master_designation_id);

                            if(value.master_pri_term_id==4){

                                $('#editCode').val(data.data.priMember.id);
                                $('#editCodeAdd').val(data.data.priMember.id);
                                $('#editPriCode').val(data.data.priMember.pri_code);
                                $('#editPriCodeAdd').val(data.data.priMember.pri_code);

                                var t_deg4=value.pri_master_designation_id;

                                if(t_deg4== 1 || t_deg4==2 || t_deg4==7){ //ZP--------------------------------------------------------
                                    $('.dis-zp').show();

                                    $('.dis-ap').hide();
                                    $('.dis-ap-main').hide();
                                    $('.dis-gp-main').hide();
                                    $('.dis-ward').hide();

                                    if(data.data.priMember.constituency){
                                        $('#constituency').val(data.data.priMember.constituency);
                                    }

                                }else if(t_deg4== 3 || t_deg4==4 || t_deg4==8){ //AP--------------------------------------------------


                                    $('.dis-ap').show();
                                    $('.dis-ap-main').show();

                                    $('.dis-zp').hide();
                                    $('.dis-gp-main').hide();
                                    $('.dis-ward').hide();

                                    @if($data['level']=="ZP")
                                        $.each(data.data.ap_con_gps, function(index, value) {
                                            $('#ap_constituency')
                                                .append($("<option></option>")
                                                    .attr("value", value['id'])
                                                    .text(value['gram_panchayat_name']));

                                        });
                                    @endif

                                    if(data.data.priMember.ap_constituency){
                                        $('#ap_constituency').val(data.data.priMember.ap_constituency);
                                    }

                                }else if(t_deg4== 5 || t_deg4==6 || t_deg4==9){ //GP--------------------------------------------------
                                    $('.dis-zp').hide();
                                    $('.dis-ap').hide();

                                    $('.dis-ap-main').show();
                                    $('.dis-gp-main').show();

                                    @if($data['level']=="ZP")
                                        $.each(data.data.selectedGpList, function(index, value) {
                                            $('#gp_code')
                                                .append($("<option></option>")
                                                    .attr("value", value['id'])
                                                    .text(value['gram_panchayat_name']));

                                        });
                                    @endif

                                    if(data.data.priMember.gram_panchayat_id){
                                        $('#gp_code').val(data.data.priMember.gram_panchayat_id);
                                    }

                                    if(t_deg4==6 || t_deg4==9){
                                        $('.dis-ward').show();
                                        if(data.data.priMember.ward_id){
                                            $('#ward_no').val(data.data.priMember.ward_id);
                                        }
                                    }else{
                                        $('.dis-ward').hide();
                                    }

                                }else{

                                }
                            }

                        });


                        $('#zilla_id').val(data.data.priMember.zilla_id);

                        if(data.data.priMember.anchalik_id){
                            $('#anchalik_code').val(data.data.priMember.anchalik_id);
                        }

                        $('#seat_reserved').val(data.data.priMember.seat_reserved);

                        $('#party_id').val(data.data.priMember.party_id);

                        //-----------------------------------PERSONAL DETAILS-------------------------------------------

                        if(data.data.priMember.pri_pic){
                            $('#pri_image').attr('src', '{{$imgUrl}}'+data.data.priMember.pri_pic);
                        }

                        if(data.data.priMember.photo_i_proof){
                            $('#photo_i_proof_view').show();
                            $('#photo_i_proof_view a').attr('href', '{{$imgUrl}}'+data.data.priMember.photo_i_proof);
                        }

                        $('#pri_f_name').val(data.data.priMember.pri_f_name);
                        $('#pri_m_name').val(data.data.priMember.pri_m_name);
                        $('#pri_l_name').val(data.data.priMember.pri_l_name);

                        $('#mobile_no').val(data.data.priMember.mobile_no);
                        $('#pri_dob').val(data.data.priMember.dob);
                        $('#gender_id').val(data.data.priMember.gender_id);
                        $('#caste_id').val(data.data.priMember.caste_id);
                        $('#religion_id').val(data.data.priMember.religion_id);
                        $('#blood_group_id').val(data.data.priMember.blood_group_id);
                        $('#differently_abled').val(data.data.priMember.differently_abled);
                        $('#marital_status_id').val(data.data.priMember.marital_status_id);
                        $('#occupation').val(data.data.priMember.occupation);
                        $('#alt_mobile_no').val(data.data.priMember.alt_mobile_no);
                        $('#income_id').val(data.data.priMember.annual_income_id);
                        $('#qual_id').val(data.data.priMember.qual_id);

                        $('#earlier_pri').val(data.data.priMember.earlier_pri);

                        $('#o_add').val(data.data.priMember.o_add);
                        $('#o_pin').val(data.data.priMember.o_pin);

                        $('#p_district').val(data.data.priMember.p_district);
                        $('#p_pin').val(data.data.priMember.p_pin);
                        $('#p_add').val(data.data.priMember.p_add);

                        if(data.data.priMember.earlier_pri==1){
                            $('.term-tr :input').prop("disabled", false);
                        }else{
                            $('.term-tr :input').prop("disabled", true);
                            $('.term-tr :input').val('');
                        }

                        $('#myModalAddPri .modal-footer .btn-save').hide();
                        $('#myModalAddPri .modal-footer .btn-edit').show();
                        $('#myModalAddPri .modal-title').text('View PRIs Member');

                        $('#myModalAddPri').modal({
                            backdrop: 'static',
                            keyboard: false
                        });

                        // bank details start
                            $('#bank_name_view').val(bank.bank_name);
                            $('#account_no_view').val(bank.account_no);
                            $('#branch_name_view').val(bank.branch_name);
                            $('#ifsc_view').val(bank.ifsc_code);
                            if(bank.pass_book != 'NA'){
                                $('#passbook').attr('href','https://pnrdassam.org/SPIRDMDAS/storage/app/'+bank.pass_book);

                            }else{
                                $('#passbook').attr('href','NA');
                            }
                        // bank details end

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


        });



</script>


{{-- sweet alert 2 start--}}
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).on('click', '#btn-bank', function() {
        var form = $('#addPriBankForm')[0];
        var formData = new FormData(form);
        Swal.fire({
        title: 'Are you sure?',
        text: "That you want to Submit.!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!',
        cancelButtonText: 'Cancel'
        }).then((result) => {
    if (result.isConfirmed) {
        $.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    type: "POST",
    url:"{{route('pris.members.bank_save')}}",
    dataType: "json",
    data: formData,
    contentType: false,
    cache: false,
    processData: false,
    statusCode: {
        422: function(data) {
            var object = "";
            $.each(data, function(key, val) {

                if (key === "responseText") {
                    object = jQuery.parseJSON(val);
                }

            });
            var error = "";
            $.each(object.errors, function(key, val) {

                $('.' + key).text(val);
                $('.' + key).removeAttr('style');

            });
            Swal.fire("In valid data has been provided please check!!", "", "error");

        }
    },
    success: function(data) {
        if (data[1].error) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data[1].error,
                footer: '<a href=""></a>'
            })
        } else {
            Swal.fire({
                title: 'Success!',
                text: data[1].success,
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK'
            }).then((result) => {
                location.reload();
                if (result.isConfirmed) {
                    location.reload();
                }
            })

        }
    }
});
    }

})
});

// ------------------sweet alert 2 end--------------------
</script>

<!-- {{-------------- select2 start----------------- --}} -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
    integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
    integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<script>
    // {{-- In your Javascript (external .js resource or <script> tag) --}}
    $(document).ready(function() {
        $('.bank-select').select2().on('change',function(e){
        //    alert( $(this).val());
        var bID = $(this).val();
       
        $.ajax({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
               type:'POST',
               url:'{{route('pris.members.sendBankBranch')}}',
               dataType: 'json',
               data: {
                id: bID
               },
               success:function(data) {

                var option = "<option value=''>Select branch name</option>";
                for(var i = 0; i < data[0].length; i++){
                    option += "<option value='"+data[0][i].id+"' data-ifsc='"+data[0][i].ifsc_code+"'>"+data[0][i].branch_name+"</option>";
                    
                    // var option = document.createElement("option");
                    // option.text = data[0][i].branch_name;
                    // option.value = data[0][i].id;
                    // var select = document.getElementById("branch_name");
                    // select.appendChild(option);
                }
                $('#branch_name_select').html(option);
                $('#branch_name_select').select2();

               }
            });
        });
    });

    // ning
    // restrictions on the length of account no
    $(document).on('keypress','#acc_no',function(e){
        // if($('.bank-select').val() == "921"){    //state bank of india
        //    if($(this).val().length == 11){
        //     alert("Account no. cannot exceed "+$(this).val().length);
        //     e.preventDefault();
        //    }
        // }
        if($('.bank-select').val() == "869"){
           if($(this).val().length == 16){
            alert("Account no. cannot exceed "+$(this).val().length);
            e.preventDefault();
           }
        }
        if($('.bank-select').val() == "33"){
           if($(this).val().length == 14){
            alert("Account no. cannot exceed "+$(this).val().length);
            e.preventDefault();
           }
        }
        // if($('.bank-select').val() == "100"){ //canara bank
        //    if($(this).val().length == 12){
        //     alert("Account no. cannot exceed "+$(this).val().length);
        //     e.preventDefault();
        //    }
        // }
        if($('.bank-select').val() == "1014"){
           if($(this).val().length == 15){
            alert("Account no. cannot exceed "+$(this).val().length);
            e.preventDefault();
           }
        }
        // if($('.bank-select').val() == "727"){   //indian bank
        //    if($(this).val().length == 10){
        //     alert("Account no. cannot exceed "+$(this).val().length);
        //     e.preventDefault();
        //    }
        // }
        if($('.bank-select').val() == "1017"){
           if($(this).val().length == 14){
            alert("Account no. cannot exceed "+$(this).val().length);
            e.preventDefault();
           }
        }
        if($('.bank-select').val() == "53"){
           if($(this).val().length == 10){
            alert("Account no. cannot exceed "+$(this).val().length);
            e.preventDefault();
           }
        }
        if($('.bank-select').val() == "190"){
           if($(this).val().length == 15){
            alert("Account no. cannot exceed "+$(this).val().length);
            e.preventDefault();
           }
        }
        if($('.bank-select').val() == "624"){
           if($(this).val().length == 12){
            alert("Account no. cannot exceed "+$(this).val().length);
            e.preventDefault();
           }
        }
        if($('.bank-select').val() == "186"){
           if($(this).val().length == 14){
            alert("Account no. cannot exceed "+$(this).val().length);
            e.preventDefault();
           }
        }
        if($('.bank-select').val() == "1023"){
           if($(this).val().length == 15){
            alert("Account no. cannot exceed "+$(this).val().length);
            e.preventDefault();
           }
        }
        if($('.bank-select').val() == "1169"){
           if($(this).val().length == 15){
            alert("Account no. cannot exceed "+$(this).val().length);
            e.preventDefault();
           }
        }
        // if($('.bank-select').val() == "41"){     //Bandhan Bank
        //    if($(this).val().length == 14){
        //     alert("Account no. cannot exceed "+$(this).val().length);
        //     e.preventDefault();
        //    }
        // }
        if($('.bank-select').val() == "817"){
           if($(this).val().length == 11){
            alert("Account no. cannot exceed "+$(this).val().length);
            e.preventDefault();
           }
        }
        if($('.bank-select').val() == "42"){
           if($(this).val().length == 15){
            alert("Account no. cannot exceed "+$(this).val().length);
            e.preventDefault();
           }
        }
    });
    
    $(document).on('change','#branch_name_select',function(e){
         $('#ifsc').val($(this).find(':selected').data('ifsc'));
         $('#branch_name').val($(this).find(':selected').text());
    })

//regex working one is in top
//     setInputFilter(document.getElementById("acc_no"), function(value) {
//   return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
// }, "Only digits and '.' are allowed");
</script>
<!-- {{-- select2 end --}} -->

@endsection