@php
    $page_title="PRIs_Menbers";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <style>
        .mt10{
            margin-top: 10px;
        }
		.mt20{
            margin-top: 20px;
        }
        .mt30{
            margin-top: 30px;
        }
		strong{
			color:red;
		}
        #myModalAddPri .form-control{
            height:25px;
            padding:2px 5px;
            font-size: 12px;
        }
        label{
            font-size: 11px;
        }
        .Zebra_DatePicker_Icon_Wrapper{
            width:100% !important;
        }
        .table{
            margin-bottom: 0px;
            border:0px;
        }
        body{
            background-color: #eee;
        }

        #myModalAddPri .modal-body{
            padding-bottom:0px;
            background-color: rgba(125, 210, 235, 0.93);
        }
        .well{
            margin-bottom: 0px;
        }

        .overlay {
          position: absolute; 
          bottom: 0; 
          background: rgb(0, 0, 0);
          background: rgba(0, 0, 0, 0.5); /* Black see-through */
          color: #f1f1f1; 
          width: 100%;
          transition: .5s ease;
          opacity:0;
          color: white;
          font-size: 20px;
          padding: 20px;
          text-align: center;
        }

        /* When you mouse over the container, fade in the overlay title */
        .pri:hover .overlay {
          opacity: 1;
        }
        .btn-round{
            border-radius: 50%;
        }

    </style>

@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Home</a></li>
            <li class="active">Member Search</li>
        </ol>
    </div>

    <div class="container">
        <div class="row mt40" style="background-color: #80d8ff">
            <form action="{{route('pris.district.reportDist')}}" method="POST">
                {{csrf_field()}}
                
                <div class="col-md-2 col-sm-2 col-xs-12">
                   <div class="form-group">
                        <label>Choose View Type</label>
                        <select class="form-control" name="tier" id="filter_tier" onChange="checkOption(this.value)" required>
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
                        <select class="form-control" name="zp_id" id="filter_zp_id" required>
                            <option value="">--Select--</option>
                            <option value="@if(isset($zillaForFilters->id)){{$zillaForFilters->id}}@endif">
                                @if(isset($zillaForFilters->id)){{$zillaForFilters->zila_parishad_name}}@endif
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3 col-sm-2 col-xs-12">
                    <div class="form-group">
                        <label>Select Anchalik Panchayat</label>
                        <select class="form-control anchalik  zp_cmd" name="ap_id" id="filter_anchalik_id" required>
                            <option value="">--Select--</option>
                            @foreach($anchalikForFilters AS $li_a)
                                <option value="{{$li_a->id}}">{{$li_a->anchalik_parishad_name}}</option>
                            @endforeach
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
                    <button  type="submit" class="btn btn-primary btn-block" style="margin-top: 22px"> 
                        <i class="fa fa-search"></i> 
                    </button>
                </div>
                </div>
            </form>
        </div>

        <div class="row mt20">
            @if(count( $priMembers ) > 0)
            @foreach($priMembers AS $members)
            <div class="col-md-4 col-sm-6 col-xs-12 animated zoomIn" style="margin-bottom: 50px;">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row pri" style="border-top:4px solid {{$members->color_code}};box-shadow: 0px 0px 8px 0px #a7a6a4;">
                        <table class="table table-condensed">
                            <tr>
                                <td rowspan="4" style="vertical-align: middle;padding: auto" class="viewPri" data-pricode="{{$members->pri_code}}">
                                    @if($members->pri_pic)
                                        <img src="{{$imgUrl}}{{$members->pri_pic}}" style="border:1px solid #ddd;width:80px;height:90px;cursor:pointer;" />
                                    @else
                                        <img src="{{asset('mdas_assets/images/user_add.png')}}" style="border:1px solid #ddd;width:80px;height:90px;cursor:pointer;" />
                                    @endif
                                </td>
                                <th>PRI Code</th>
                                <td>{{$members->pri_code}}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td style="height:50px;text-transform: uppercase">{{$members->pri_f_name}} {{$members->pri_m_name}} {{$members->pri_l_name}}</td>
                            </tr>
                            <tr>
                                <th>Designation</th>
                                <td>{{$members->design_name}}</td>
                            </tr>
                        </table>

                        <div class="overlay">
                            {{--<button type="button" class="btn btn-primary btn-round"><i class="fa fa-edit"></i></button>--}}
                            <button type="button" class="btn btn-info btn-round viewPri" data-pricode="{{$members->pri_code}}">
                                <i class="fa fa-eye"></i>
                            </button>
                            <button type="submit" class="btn btn-danger btn-round destroyPRI" data-p="{{$members->id}}" data-c="{{$members->pri_code}}" data-n="{{$members->pri_f_name}} {{$members->pri_m_name}} {{$members->pri_l_name}}">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @else
                <div class="col-md-12 col-sm-12 col-xs-12 animated zoomIn" style="margin-bottom: 27px;">
                    <h4 class="text-center"> @if($searchResult)No PRIs Members found against your search...@else Please search to view the PRIs Members...@endif</h4>
                </div>
            @endif
        </div>
    </div>


    @if($searchResult)
    <!-- Modal ADD PRIs -->
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
                    <input type="hidden" name="editCode" id="editCode" value=""/>
                    <input type="hidden" name="editPriCode" id="editPriCode" value=""/>
                    <div class="modal-body">
                        <!------------------------- TOP BAND ------------------------------>
                        <h5 style="margin-top: -5px">A. General Details</h5>
                        <div class="row well">
                            <div class="col-md-3 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Designation <strong>*</strong></label>
                                    <select class="form-control" name="t_deg4" id="t_deg4">
                                        <option value="">--Select Designation--</option>
                                        @foreach($all_designs AS $design)
                                        <option value="{{$design->id}}">{{$design->design_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 col-xs-12 dis">
                                <div class="form-group">
                                    <label>Zilla Prarisad <strong>*</strong></label>
                                    <select class="form-control" name="zilla_id" id="zilla_id">
                                        <option value="">--Select--</option>
                                        <option value="@if(isset($zillas->id)){{$zillas->id}}@endif">@if(isset($zillas->zila_parishad_name)){{$zillas->zila_parishad_name}}@endif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 col-xs-12 dis dis-ap-main">
                                <div class="form-group">
                                    <label>Anchalik Panchayat <strong class="dis-ap">*</strong></label>
                                    <select class="form-control" name="anchalik_code" id="anchalik_code">
                                        <option value="">--Select--</option>
                                        @foreach($anchaliks AS $anchalik)
                                        <option value="{{$anchalik->id}}">{{$anchalik->anchalik_parishad_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if($tier=="GP")
                            <div class="col-md-3 col-sm-4 col-xs-12 dis">
                                <div class="form-group">
                                    <label>Gram Panchayat <strong>*</strong></label>
                                    <select class="form-control" name="gp_code" id="gp_code">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-4 col-xs-12 dis dis-ward" @if($tier=="GP")style="clear:both"@endif>
                                <div class="form-group">
                                    <label>Ward Number <strong>*</strong></label>
                                    <select class="form-control" name="ward_no" id="ward_no">
                                        <option value="">--Select--</option>
                                        @foreach($wards AS $ward)
                                        <option value="{{$ward->id}}">{{$ward->ward_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-3 col-sm-4 col-xs-12 dis">
                                <div class="form-group">
                                    <label>Is the seat reserved? <strong>*</strong></label>
                                    <select class="form-control" name="seat_reserved" id="seat_reserved">
                                        <option value="">--Select--</option>
                                        @foreach($reserveSeats AS $seat)
                                        <option value="{{$seat->id}}">{{$seat->seat_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if($tier=="ZP")
                            <div class="col-md-3 col-sm-4 col-xs-12 dis dis-zp">
                                <div class="form-group">
                                    <label>Constituency <strong>*</strong></label>
                                    <input type="text" class="form-control" name="constituency" id="constituency"/>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4 col-xs-12 dis dis-ap">
                                <div class="form-group">
                                    <label>AP Constituency <strong>*</strong></label>
                                    <select class="form-control" name="ap_constituency" id="ap_constituency">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-3 col-sm-4 col-xs-12 dis">
                                <div class="form-group">
                                    <label>Political Party <strong>*</strong></label>
                                    <select class="form-control" name="party_id" id="party_id">
                                        <option value="">--Select--</option>
                                        @foreach($politicals AS $political)
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
                                    <img id="pri_image" src="{{asset('mdas_assets/images/user_add.png')}}" style="border:1px solid #ddd;width:120px;max-height:130px;cursor:pointer" />
                                    <input type="file" name="pic" id="pic" style="display: none"/><br>
                                    <label>Click the above image to upload passport photo</label>
                                    <a href="#" data-toggle="tooltip" title="Note: Click on the photo to upload passport photo. Upload jpg, jpeg and png file only. Max image size should not exceed 100KB and not less than 10KB">
                                        <i class="fa fa-question-circle"></i>
                                    </a>
                                </div>
                                <div class="form-group">
                                    <label>Photo Identity Proof of Member (PAN, Voter ID etc.)</label>
                                    <a href="#" data-toggle="tooltip" title="Note: Upload jpg, jpeg and png file only. Max image size should not exceed 200KB and not less than 10KB">
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
                                            <input type="text" class="form-control" name="pri_f_name" id="pri_f_name"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Middle Name</label>
                                            <input type="text" class="form-control" name="pri_m_name" id="pri_m_name"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Last Name <strong>*</strong></label>
                                            <input type="text" class="form-control" name="pri_l_name" id="pri_l_name"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Mobile Number <strong>*</strong></label>
                                            <input type="number" class="form-control" name="mobile_no" id="mobile_no"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Date Of Birth (Age: ) <strong>*</strong></label>
                                            <input type="text" class="form-control" name="pri_dob" id="pri_dob"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-6 dis-profile">
                                        <div class="form-group">
                                            <label>Gender <strong>*</strong></label>
                                            <select class="form-control" name="gender_id" id="gender_id">
                                                <option value="">--Select--</option>
                                                @foreach($genders AS $gender)
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
                                                @foreach($castes AS $caste)
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
                                                @foreach($religions AS $religion)
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
                                                @foreach($bloods AS $blood)
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
                                                @foreach($maritals AS $marital)
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
                                            <input type="text" class="form-control" name="occupation" id="occupation"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Alt Mobile Number</label>
                                            <input type="number" class="form-control" name="alt_mobile_no" id="alt_mobile_no"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Annual Income <strong>*</strong></label>
                                            <select class="form-control" name="income_id" id="income_id">
                                                <option value="">--Select--</option>
                                                @foreach($incomes AS $income)
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
                                                @foreach($qualifications AS $qual)
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
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <table class="table table-bordered table-condensed">
                                            <tr>
                                                <td colspan="2">
                                                    <div class="form-group">
                                                        <label>If Earlier Elected as PRI members ? <strong>*</strong></label>
                                                        <select class="form-control" name="earlier_pri" id="earlier_pri">
                                                            <option value="">--Select--</option>
                                                            <option value="1">YES</option>
                                                            <option value="0">NO</option>
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="bg-primary">
                                                <td>
                                                    Term
                                                </td>
                                                <td>
                                                    Designation
                                                </td>
                                            </tr>
                                            <tr class="term-tr">
                                                <td>
                                                    2012-13
                                                </td>
                                                <td>
                                                    <select class="form-control" name="t_deg3" id="t_deg3" disabled="disabled">
                                                        <option value="">Not Elected</option>
                                                        @foreach($all_designs AS $design)
                                                        <option value="{{$design->id}}">{{$design->design_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr class="term-tr">
                                                <td>
                                                    2007-08
                                                </td>
                                                <td>
                                                    <select class="form-control" name="t_deg2" id="t_deg2" disabled="disabled">
                                                        <option value="">Not Elected</option>
                                                        @foreach($all_designs AS $design)
                                                        <option value="{{$design->id}}">{{$design->design_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr class="term-tr">
                                                <td>
                                                    2002-03
                                                </td>
                                                <td>
                                                    <select class="form-control" name="t_deg1" id="t_deg1" disabled="disabled">
                                                        <option value="">Not Elected</option>
                                                        @foreach($all_designs AS $design)
                                                        <option value="{{$design->id}}">{{$design->design_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Official Address <strong>*</strong></label>
                                        </div>
                                        <div class="form-group">
                                            <label>Address <strong>*</strong></label>
                                            <textarea class="form-control" name="o_add" id="o_add" placeholder="Full postal address of office."></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label>Pin Code <strong>*</strong></label>
                                                    <input type="number" class="form-control" name="o_pin" id="o_pin"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
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
                                                        @foreach($districts AS $district)
                                                        <option value="{{$district->id}}">{{$district->district_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <label>Pin Code <strong>*</strong></label>
                                                    <input type="number" class="form-control" name="p_pin" id="p_pin"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">
                            <i class="fa fa-times"></i>
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary btn-save">
                            <i class="fa fa-send"></i>
                            Submit
                        </button>
                        <button type="submit" class="btn btn-primary btn-edit">
                            <i class="fa fa-send"></i>
                            Edit & Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal ADD PRIs Ended -->
    @endif


@endsection

@section('custom_js')


  <script type="text/javascript">

    @if(isset($filterArray)) 
     $('#filter_gram_panchyat_id').empty();
     $('#filter_gram_panchyat_id')
                        .append($("<option></option>")
                        .attr("value", '')
                        .text('--Select--'));
                        
        @php $filter_tier= $filterArray['filterTier'];@endphp
            @if($filter_tier=='ZP')
                @php $filterZP= $filterArray['filterZP']; @endphp
                $('#filter_tier').val('{{$filter_tier}}');
                $('#filter_zp_id').val('{{$filterZP}}');
                $('#filter_anchalik_id').prop('disabled', true);
                $('#filter_gram_panchyat_id').prop('disabled', true); 
            @elseif($filter_tier=='AP')
                @php $filterZP= $filterArray['filterZP']; @endphp
                @php $filterAP= $filterArray['filterAP']; @endphp
                $('#filter_tier').val('{{$filter_tier}}');
                $('#filter_zp_id').val('{{$filterZP}}');
                $('#filter_anchalik_id').val('{{$filterAP}}');
                $('#filter_gram_panchyat_id').prop('disabled', true); 
            @elseif($filter_tier=='GP')
                @php $filterZP= $filterArray['filterZP']; @endphp
                @php $filterAP= $filterArray['filterAP']; @endphp
                @php $filterGP= $filterArray['filterGP']; @endphp
                @php $filterGPList= $filterArray['filterGPList']; @endphp
                $('#filter_tier').val('{{$filter_tier}}');
                $('#filter_zp_id').val('{{$filterZP}}');
                $('#filter_anchalik_id').val('{{$filterAP}}');

                

                @foreach($filterGPList AS $f_gp)
                    $('#filter_gram_panchyat_id')
                            .append($("<option></option>")
                                        .attr("value", '{{$f_gp->id}}')
                                        .text('{{$f_gp->gram_panchayat_name}}'));
                @endforeach        

                $('#filter_gram_panchyat_id').val('{{$filterGP}}');    
            @endif
    @endif

    $(document).on('change', '#filter_anchalik_id', function(e){
            e.preventDefault();
            $('#filter_gram_panchyat_id').empty();

            var anchalik_id = $(this).val();

            if(anchalik_id){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('select_ajax')}}',
                    dataType: "json",
                    data: {anchalik_id : anchalik_id},
                    success: function (data) {
                        if (data.msgType == true) {

                            $('#filter_gram_panchyat_id')
                                .append($("<option></option>")
                                    .attr("value", '')
                                    .text('--Select--'));

                            $.each(data.data, function(key, value) {
                                $('#filter_gram_panchyat_id')
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

    function checkOption(obj){
    // alert(obj);
    if(obj == "AP"){
        $(".ap_cmd").prop('disabled', true);
    }else{
      $(".ap_cmd").prop('disabled', false);
    }
    if(obj == "ZP"){
        $(".zp_cmd").prop('disabled', true);
    }else{
     $(".zp_cmd").prop('disabled', false);
    }
    if (obj == "ZP" || obj == "AP") {
        $(".AZcmd").prop('disabled', true);
    }else{
        $(".AZcmd").prop('disabled', false);
    }
 }

    $('.destroyPRI').on('click', function(e){

        e.preventDefault();

        var pri_id= $(this).data('p');
        var pri_code= $(this).data('c');
        var pri_name= $(this).data('n');

        swal({
            title: "Are you sure?",
            text: "You are sure you want to delete the pri "+ pri_name+ ", Once deleted can not be revert.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willStore) => {
            if (willStore) {

        if(pri_id && pri_code){

            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('pris.district.destroyPRI')}}',
                dataType: "json",
                data: {pri_id : pri_id, pri_code : pri_code},
            success: function (data) {
                if (data.msgType == true) {
                    swal("Success", data.msg, "success")
                        .then((value) => {
                        $('#myModalAddPri').modal('hide');
                    location.reload();
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

            } else {
                swal("You have canceled your operation!");
            }
    });
    });

</script>

@if($searchResult)
<script type="application/javascript">
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

    // ----------------------- RESET FORM DATA AND OPEN MODAL -------------------------------

    $('#earlier_pri').on('change', function(e){
        e.preventDefault();
        var value= $('#earlier_pri').val();

        if(value==1){
            $('.term-tr :input').prop("disabled", false);
        }else{
            $('.term-tr :input').prop("disabled", true);
            $('.term-tr :input').val('');
        }
    });

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
                url: '@if($tier=="ZP"){{route('common.category.getGPsByAnchalikId')}}@elseif($tier=="GP"){{route('survey.getGPsByAnchalikId')}}@endif',
                dataType: "json",
                data: {anchalik_code : anchalik_code},
            success: function (data) {
                if (data.msgType == true) {

                @if($tier=="ZP")
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
                @elseif($tier=="GP")

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
                @endif

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


    //Validation on Changes------------------------

    $('#t_deg4').on('change', function(e){
        e.preventDefault();
        var deg = $('#t_deg4').val();
        $('.dis :input').val('');
        $('.dis-profile :input').val('');
        $('#ap_constituency').empty();

        if(deg==1 || deg==2){
            $('.dis-ap').hide();
            $('.dis-ap-main').hide();
            $('.dis-zp').show();
        }else if(deg==3 || deg==4){
            $('.dis-ap').show();
            $('.dis-ap-main').show();
            $('.dis-zp').hide();
        }else if(deg==5 || deg==6){
            $('.dis-ap').show();
            $('.dis-ap-main').show();
            $('.dis-zp').hide();

            if(deg==5){
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

        if(caste_id==1 && (t_deg4==1 || t_deg4==2)){ // ZP
            $("#qual_id option").hide();
            $("#qual_id option[value='']").show();
            $("#qual_id option[value='6']").show();
            $("#qual_id option[value='7']").show();
            $("#qual_id option[value='8']").show();
        }else if((caste_id==2 || caste_id==3 || caste_id==4 || caste_id==5) && (t_deg4==1 || t_deg4==2)){
            $("#qual_id option").hide();
            $("#qual_id option[value='']").show();
            $("#qual_id option[value='5']").show();
            $("#qual_id option[value='6']").show();
            $("#qual_id option[value='7']").show();
            $("#qual_id option[value='8']").show();
        }else if(caste_id==1 && (t_deg4==3 || t_deg4==4)){ //AP
            $("#qual_id option").hide();
            $("#qual_id option[value='']").show();
            $("#qual_id option[value='5']").show();
            $("#qual_id option[value='6']").show();
            $("#qual_id option[value='7']").show();
            $("#qual_id option[value='8']").show();
        }else if((caste_id==2 || caste_id==3 || caste_id==4 || caste_id==5) && (t_deg4==3 || t_deg4==4)){
            $("#qual_id option").hide();
            $("#qual_id option[value='']").show();
            $("#qual_id option[value='4']").show();
            $("#qual_id option[value='5']").show();
            $("#qual_id option[value='6']").show();
            $("#qual_id option[value='7']").show();
            $("#qual_id option[value='8']").show();
        }else if(caste_id==1 && (t_deg4==5 || t_deg4==6)) { //GP

            $("#qual_id option[value='1']").hide();
            $("#qual_id option[value='2']").hide();

        }

    })

    // -------------------------- ON SUBMIT --------------------------

    $('#addPriForm').on('submit', function(e){
        e.preventDefault();

        swal({
            title: "Are you sure?",
            text: "You are sure you want to save the pri data.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willStore) => {
            if (willStore) {

                $('.form_errors').remove();
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('pris.members.save')}}',
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
            } else {
                swal("You have canceled your operation!");
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
                $('#ap_constituency').empty();
                $('#ap_constituency')
                    .append($("<option></option>")
                        .attr("value", '')
                        .text('--Select--'));
                $('#ap_constituency').val('');

                $('#gp_code').empty();
                $('#gp_code')
                    .append($("<option></option>")
                        .attr("value", '')
                        .text('--Select--'));
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
                        $('#editPriCode').val(data.data.priMember.pri_code);

                        var t_deg4=value.pri_master_designation_id;

                        if(t_deg4== 1 || t_deg4==2 || t_deg4==7){ //ZP--------------------------------------------------------
                            $('.dis-zp').show();

                            if(data.data.priMember.constituency){
                                $('#constituency').val(data.data.priMember.constituency);
                            }

                        }else if(t_deg4== 3 || t_deg4==4 || t_deg4==8){ //AP--------------------------------------------------

                            $('.dis-ap').show();
                            $('.dis-ap-main').show();

                            $.each(data.data.ap_con_gps, function(index, value) {
                                $('#ap_constituency')
                                    .append($("<option></option>")
                                        .attr("value", value['id'])
                                        .text(value['gram_panchayat_name']));

                            });

                            if(data.data.priMember.ap_constituency){
                                $('#ap_constituency').val(data.data.priMember.ap_constituency);
                            }

                        }else if(t_deg4== 5 || t_deg4==6 || t_deg4==9){ //GP--------------------------------------------------
                            $('.dis-ap-main').show();
                            $('.dis-ap').show();
                            $.each(data.data.selectedGpList, function(index, value) {
                                $('#gp_code')
                                    .append($("<option></option>")
                                        .attr("value", value['id'])
                                        .text(value['gram_panchayat_name']));

                            });

                            if(data.data.priMember.gram_panchayat_id){
                                $('#gp_code').val(data.data.priMember.gram_panchayat_id);
                            }

                            if(t_deg4==6 || t_deg4==9){
                                $('.dis-ward').show();
                                if(data.data.priMember.ward_id){
                                    $('#ward_no').val(data.data.priMember.ward_id);
                                }
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

                /*$('#myModalAddPri .modal-footer .btn-save').hide();
                $('#myModalAddPri .modal-footer .btn-edit').show();*/

                $('#myModalAddPri .modal-footer').remove();

                $('#myModalAddPri .modal-title').text('View PRIs Member');

                $('#addPriForm :input').prop("disabled", true);

                $('#myModalAddPri').modal({
                    backdrop: 'static',
                    keyboard: false
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


    });

</script>
@endif

@endsection