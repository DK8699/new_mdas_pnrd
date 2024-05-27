@php
    $page_title="PRIs_Menbers";
@endphp

@extends('layouts.app_user')

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
        .form-control{
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
    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="active">PRIs</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-12 col-sm-12 col-xs-12">
			    
                <button type="button" class="btn btn-primary pull-right" id="addPriMember" style="margin-left:5px">
                    <i class="fa fa-plus"></i>
                    Add PRIs Member
                </button>
				@if($tier=="ZP")
				<a href="{{route('pris.district.report')}}" class="btn btn-primary pull-right" style="margin-left:5px">
                    <i class="fa fa-binoculars"></i>
                    Report
                </a>
				@endif
            </div>
        </div>
    </div>

    <div class="container">
		<h5>Showing PRIs Members</h5>
        <div class="row mt20">
            @foreach($priMembers AS $members)
            <div class="col-md-4 col-sm-6 col-xs-12 animated zoomIn">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row" style="border-top:4px solid {{$members->color_code}};margin-bottom: 27px;box-shadow: 0px 0px 8px 0px #a7a6a4;">
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
                                <td style="height:50px">{{$members->pri_f_name}} {{$members->pri_m_name}} {{$members->pri_l_name}}</td>
                            </tr>
                            <tr>
                                <th>Designation</th>
                                <td>{{$members->design_name}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row mt10">
            <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                <?php echo $priMembers->render(); ?>
            </div>
        </div>
    </div>



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
                <form action="#" method="POST" id="addPriForm">
                    <div class="modal-body">
                        <!------------------------- TOP BAND ------------------------------>
                        <h5 style="margin-top: -5px">A. General Details</h5>
                        <div class="row well">
                            <div class="col-md-3 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Designation <strong>*</strong></label>
                                    <select class="form-control" name="t_deg4" id="t_deg4">
                                        <option value="">--Select Designation--</option>
                                        @foreach($designs AS $design)
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
                            <div class="col-md-3 col-sm-4 col-xs-12 dis">
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
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-send"></i>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal ADD PRIs Ended -->

@endsection

@section('custom_js')
    <script type="application/javascript">
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

            $('.dis :input').val('');
            $('.dis-zp').hide();
            $('.dis-ap').hide();
			$('.dis-ward').hide();

            $('.form_errors').remove();

            $('#myModalAddPri .modal-footer').show();
            $('#myModalAddPri .modal-title').text('Add PRIs Member');
            $('#photo_i_proof_view').hide();
            $('#photo_i_proof_view a').attr('href', '#');

            $('#myModalAddPri').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

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
                $('.dis-zp').show();
            }else if(deg==3 || deg==4){
                $('.dis-ap').show();
                $('.dis-zp').hide();
            }else if(deg==5 || deg==6){
                $('.dis-ap').show();
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
                                swal("Success", data.msg, 'success');
                                $('#myModalAddPri').modal('hide');
                                setTimeout(function(){ location.reload(); }, 1500);
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

                        $('#t_deg1').val('');
                        $('#t_deg2').val('');
                        $('#t_deg3').val('');
                        $('#t_deg4').val('');

                        $.each(data.data.priMemberHistory, function(index, value) {
                            $('#t_deg'+value.master_pri_term_id).val(value.pri_master_designation_id);
                        });

                        $('#zilla_id').val(data.data.priMember.zilla_id);

                        if(data.data.priMember.anchalik_id){
                            $('.dis-ap').show();
                            $('#anchalik_code').val(data.data.priMember.anchalik_id);
                        }else{
                            $('#anchalik_code').val('');
                            $('.dis-ap').hide();
							
                        }
						
                        $('#seat_reserved').val(data.data.priMember.seat_reserved);
						
                        if(data.data.priMember.constituency){
                            $('.dis-zp').show();
                            $('#constituency').val(data.data.priMember.constituency);
                        }else{
                            $('#constituency').val('');
                            $('.dis-zp').hide();
                        }
						
						$('#ap_constituency').empty();

                        $('#ap_constituency')
                            .append($("<option></option>")
                                .attr("value", '')
                                .text('--Select--'));

                        $.each(data.data.ap_con_gps, function(index, value) {
                            $('#ap_constituency')
                                .append($("<option></option>")
                                    .attr("value", value['id'])
                                    .text(value['gram_panchayat_name']));

                        });

                        if(data.data.priMember.ap_constituency){
                            $('#ap_constituency').val(data.data.priMember.ap_constituency);
                        }else{
                            $('#ap_constituency').val('');
                        }
						
						// GP---------------------------------------------------
						
						$('#gp_code').empty();
						
						$('#gp_code')
                            .append($("<option></option>")
                                .attr("value", '')
                                .text('--Select--'));

                        $.each(data.data.selectedGpList, function(index, value) {
                            $('#gp_code')
                                .append($("<option></option>")
                                    .attr("value", value['id'])
                                    .text(value['gram_panchayat_name']));

                        });
                        
                        if(data.data.priMember.gram_panchayat_id){
                            $('#gp_code').val(data.data.priMember.gram_panchayat_id);
                        }else{
                            $('#gp_code').val('');
                        }
						
                        if(data.data.priMember.ward_id){
                            $('#ward_no').val(data.data.priMember.ward_id);
                            $('.dis-ward').show();
                        }else{
                            $('.dis-ward').hide();
                            $('#ward_no').val('');
                        }
						
						//GP ENDED----------------------
						
                        $('#party_id').val(data.data.priMember.party_id);

                        //-----------------------------------PERSONAL DETAILS-------------------------------------------
                        $('#pic').val('');
						
                        if(data.data.priMember.pri_pic){
                            $('#pri_image').attr('src', '{{$imgUrl}}'+data.data.priMember.pri_pic);
                        }else{
                            $('#pri_image').attr('src', '{{asset('mdas_assets/images/user_add.png')}}');
                        }

                        if(data.data.priMember.photo_i_proof){
                            $('#photo_i_proof_view').show();
                            $('#photo_i_proof_view a').attr('href', '{{$imgUrl}}'+data.data.priMember.photo_i_proof);
                        }else{
                            $('#photo_i_proof_view').hide();
                            $('#photo_i_proof_view a').attr('href', '#');
                            $('#photo_i_proof').val('');
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

                        $('#myModalAddPri .modal-footer').hide();
                        $('#myModalAddPri .modal-title').text('View PRIs Member');

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
@endsection