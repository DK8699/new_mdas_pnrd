@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_admin')

@section('custom_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #6b133d;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }


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
        .d-header {
            height: 59px;
            color: #fff;
            background-color: #10436d; /* For browsers that do not support gradients */
            background-image: linear-gradient(90deg, #10436d, #1eccbc); /* Standard syntax (must be last) */
            /*border-radius: 31px 0 0 0;*/
        }
        label {
            color: #2575fc;
            font-family: 'Playfair Display', serif;
            font-size: 12px;
        }
        .d-font {
            font-family: 'Playfair Display', serif;
        }
        .modal-body {
            position: relative;
            padding: 15px;
            background-color: aliceblue;
        }
        .modal-footer {
            padding: 5px;
            text-align: right;
            border-top: 1px solid #e5e5e5;
        }


        .profile-modal-row{
            background-color: #ff9000;
            margin-top: -16px;
            margin-bottom: -30px;
            border-bottom: 5px solid #fff;
        }

        #pri_image{
            cursor: pointer;
            border-radius: 50%;
            border: 4px solid #fff;
            margin-bottom: -37px;
            background-color: #fff;
        }
        .badge-primary {
            background-color: #120634;
        }
        .badge-red {
            background-color: red;
        }


        .cardd {
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            transition: 0.3s;
        }
        .cardd a {
            color: #6e133c;
        }


        .cardd:hover {
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
            transform: scale(1.1);
        }
        a.thumbnail.active, a.thumbnail:focus, a.thumbnail:hover {
            border-color: #6e133c;
            color: #6e133c;
        }
        a:focus, a:hover {
            color: #6e133c;
            text-decoration: underline;
        }
        .thumbnail a>img, .thumbnail>img {
            margin-right: auto;
            margin-left: auto;
            width: 20%;
        }

    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Home</a></li>
            <li class="active">User Management</li>
        </ol>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <button type="button" class="btn btn-primary pull-right" style="margin-left:5px; margin-top:5px" data-toggle="modal" data-target="#createUser">
                    <i class="fa fa-plus"></i>
                    Create New User
                </button>
            </div>
        </div>
        <div class="row mt20">
            @foreach($data['userCountList'] AS $user)
            <div class="col-xs-12 col-md-3 col-sm-4">
                <div class="cardd animated zoomIn">
                    <a href="{{route('admin.UsersManagement.'.strtolower($user->authority).'_user_management')}}" class="thumbnail text-center">
                        <img src="{{asset('mdas_assets/images/user management.png')}}"/>
                        <p style="font-size: 25px;" class="mt10">{{$user->count}}</p>
                        <p>{{$user->role_name}}</p>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{--------------------------------Create User-------------------------------------------------------------}}

    <div class="modal fade" id="createUser" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 33px 0 0 0;">
                <div class="modal-header" style="background-color: #ff9000">
                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                </div>
                <form action="#" method="POST" id="mdasUserForm" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <!------------------------- TOP BAND ------------------------------>

                        <div class="row profile-modal-row">
                            <div class="col-md-4 col-sm-4 col-xs-4 text-center col-xs-offset-4">
                                <img id="pri_image" src="{{asset('mdas_assets/images/user_add.png')}}" style="width:120px;max-height:130px;" />
                            </div>
                        </div>
                        <div class="row" style="margin-top:60px">
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                <label style="font-size: 10px">Click the above image to upload passport photo</label>
                                <input type="file" name="image" id="pic" style="display: none"/><br>
                                <a href="#" data-toggle="tooltip" title="Note: Click on the photo to upload passport photo. Upload jpg, jpeg and png file only. Max image size should not exceed 100KB and not less than 10KB">
                                    <i class="fa fa-question-circle"></i>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <p style="font-size: 11px;font-style: italic">Fields with asterisk (<strong>*</strong>) are mandatory.</p>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 15px;">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label><i class="fa fa-user-circle"></i> First Name <strong>*</strong></label>
                                    <input type="text" class="form-control" name="f_name" id="mdas_user_f_name"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label><i class="fa fa-user-circle"></i> Middle Name</label>
                                    <input type="text" class="form-control" name="m_name" id="mdas_user_m_name"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label><i class="fa fa-user-circle"></i> Last Name <strong>*</strong></label>
                                    <input type="text" class="form-control" name="l_name" id="mdas_user_l_name"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label><i class="fa fa-phone"></i> Mobile Number <strong>*</strong></label>
                                    <input type="number" class="form-control" name="mobile" id="mobile"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label><i class="fa fa-envelope"></i> Email <strong>*</strong></label>
                                    <input type="email" class="form-control" name="email" id="email"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label><i class="fa fa-anchor"></i> Designation<strong>*</strong></label>
                                    <input type="text" class="form-control" name="designation" id="designation"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label><i class="fa fa-sitemap"></i> Level <strong>*</strong></label>
                                        <select class="form-control" name="mdas_master_level_id" id="select1">
                                            <option value="">--Select Level--</option>
                                            @foreach($data['levelList'] AS $list)
                                                <option value="{{$list->id}}">{{$list->level_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                             </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label><i class="fa fa-universal-access"></i> Role <strong>*</strong></label>
                                    <select class="form-control" name="mdas_master_role_id" id="select2">
                                        <option value="">--Select Role--</option>
                                        @foreach($data['roleList'] AS $list)
                                            <option value="{{$list->id}}">{{$list->role_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label><i class="fa fa-briefcase"></i> Employee Code (If Any)</label>
                                    <input type="text" class="form-control" name="employee_code" id="employee_code"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
					     <div class="col-md-4 col-sm-4 col-xs-12 DistrictDiv">
                                <div class="form-group">
                                    <label><i class="fa fa-institution"></i> District(Council)<strong>*</strong></label>
                                    <select class="form-control" name="d_id" id="filter_d_id">
                                        <option value="">--Select--</option>
                                        @foreach($data['districtCouncilList'] AS $li_district)
                                            <option value="{{$li_district->id}}">{{$li_district->district_name}}</option>
                                        @endforeach
                                      
                                    </select>
                                </div>
                            </div>
					   <div class="col-md-4 col-sm-4 col-xs-12 BlockDiv">
                                <div class="form-group">
                                    <label><i class="fa fa-institution"></i> Block(Council) <strong>*</strong></label>
                                    <select class="form-control" name="b_id" id="filter_b_id">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 zilaDiv">
                                <div class="form-group">
                                    <label><i class="fa fa-institution"></i> Zilla Prarisad <strong>*</strong></label>
                                    <select class="form-control" name="zp_id" id="filter_zp_id">
                                        <option value="">--Select--</option>
                                        @foreach($data['zpList'] AS $li_z)
                                            <option value="{{$li_z->id}}">{{$li_z->zila_parishad_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 anchalDiv">
                                <div class="form-group">
                                    <label><i class="fa fa-institution"></i> Anchalik Panchayat <strong>*</strong></label>
                                    <select class="form-control" name="ap_id" id="filter_anchalik_id">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 gramDiv">
                                <div class="form-group">
                                    <label><i class="fa fa-institution"></i> Gram Panchayat <strong class="dis-ap">*</strong></label>
                                    <select class="form-control" name="gp_id" id="filter_gram_panchyat_id">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 exDiv">
                                <div class="form-group">
                                    <label><i class="fa fa-institution"></i> Extension Centre <strong class="dis-ap">*</strong></label>
                                    <select class="form-control" name="ex_id" id="ex_id">
                                        <option value="">--Select--</option>
                                         @foreach($data['extension_centre'] AS $li_ex)
                                            <option value="{{$li_ex->id}}">{{$li_ex->extension_center_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
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

@endsection
@section('custom_js')
<script type="text/javascript">
    //************************** JQuery to enable and disable **************************************************************
        var $select1 = $( '#select1' ),
            $select2 = $( '#select2' ),
            $options = $select2.find( 'option' );

        $select1.on( 'change', function() {
            $select2.html( $options.filter( '[value="' + this.value + '"]' ) );
            if(this.value == 1) {
                $('.zilaDiv').hide();
                $('.anchalDiv').hide();
                $('.DistrictDiv').hide();
                $('.BlockDiv').hide();
                $('.gramDiv').hide();
                $('.exDiv').hide();
                $select2.html( $options.filter( '[value=""],[value="1"],[value="5"],[value="6"]') );
                $( '#select2' ).val('');
            }else if(this.value == 2) {
			  $('.zilaDiv').hide();
			  $('.DistrictDiv').hide();
                $('.anchalDiv').hide();
			 $('.BlockDiv').hide();
                $('.gramDiv').hide();
                $('.exDiv').hide();
			  $select2.html( $options.filter( '[value=""],[value="2"],[value="7"]') );
                $( '#select2' ).val('');
            }
            else if(this.value == 3) {
                $('.zilaDiv').hide();
                $('.anchalDiv').hide();
			 $('.DistrictDiv').hide();
 			 $('.BlockDiv').hide();
                $('.gramDiv').hide();
                $('.exDiv').hide();
			 $select2.html( $options.filter( '[value=""],[value="3"],[value="8"]') );
                $( '#select2' ).val('');
            }else if(this.value == 4) {
                $('.zilaDiv').hide();
                $('.anchalDiv').hide();
			 $('.DistrictDiv').hide();
 			 $('.BlockDiv').hide();
                $('.gramDiv').hide();
                $('.exDiv').hide();
			  $select2.html( $options.filter( '[value=""],[value="4"],[value="9"]') );
                $( '#select2' ).val('');
            }else {
                $('.zilaDiv').hide();
			  $('.DistrictDiv').hide();
			  $('.BlockDiv').hide();
                $('.anchalDiv').hide();
                $('.gramDiv').hide();
                $('.exDiv').hide();
            }
        } ).trigger( 'change' );
	
	 
	
	
	//For Level 1 user
        $(document).ready(function(){
            $("select#select2").change(function(){
                var selectedRole = $(this).children("option:selected").val();
			  
                if(selectedRole == 6)
                    {
                        $('.exDiv').show();
                    }
			  else if(selectedRole == 2){
				  $('.DistrictDiv').hide();
				  $('.zilaDiv').show();
			  }
			   else if(selectedRole == 7){
				    $('.zilaDiv').hide();
				 $('.DistrictDiv').show();
			  }
			  else if(selectedRole == 3){
				 $('.zilaDiv').show();
				 $('.DistrictDiv').hide();
				 $('.BlockDiv').hide();
                	 $('.anchalDiv').show();
			  }
			  else if(selectedRole == 8){
				 $('.zilaDiv').hide();
				 $('.DistrictDiv').show();
				 $('.BlockDiv').show();
                	 $('.anchalDiv').hide();
			  }
			  else if(selectedRole == 4){
				 $('.zilaDiv').show();
				 $('.DistrictDiv').hide();
				 $('.BlockDiv').hide();
                	 $('.anchalDiv').show();
				  $('.gramDiv').show();
			  }
			   else if(selectedRole == 9){
				 $('.zilaDiv').hide();
				 $('.DistrictDiv').show();
				 $('.BlockDiv').show();
                	 $('.anchalDiv').hide();
				   $('.gramDiv').show();
			  }
			  else{
				  $('.exDiv').hide();
			  }

            });
        });

//************************* End ****************************************************************************************
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
                    $('#pri_image').attr('src', '{{asset('mdas_assets/images/d-user.jpg')}}');
                    exit();
                }

            } else {
                swal("Information", "Please select only jpeg, jpg and png format only!", "info");
                $('#pic').val('');
                $('#pri_image').attr('src', '{{asset('mdas_assets/images/d-user.jpg')}}');
                exit();
            }

        }

        $('#pri_image').click(function(e){
            e.preventDefault();
            $('#pic').click()
        });
	
	
	//On district council change block council list
	
	$(document).on('change', '#filter_d_id', function(e){
            e.preventDefault();
            $('#filter_b_id').empty();

            var district_id = $(this).val();

            if(district_id){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.userManagement.select_block_council')}}',
                    dataType: "json",
                    data: {district_id : district_id},
                    success: function (data) {
                        if (data.msgType == true) {

                            $('#filter_b_id')
                                .append($("<option></option>")
                                    .attr("value", '')
                                    .text('--Select--'));

                            $.each(data.data, function(key, value) {
                                $('#filter_b_id')
                                    .append($("<option></option>")
                                        .attr("value", value['id'])
                                        .text(value['block_name']));
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
	
	//On Zilla change anchalik list
	
     $(document).on('change', '#filter_zp_id', function(e){
            e.preventDefault();
            $('#filter_anchalik_id').empty();

            var zila_id = $(this).val();

            if(zila_id){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.userManagement.select_ap')}}',
                    dataType: "json",
                    data: {zila_id : zila_id},
                    success: function (data) {
                        if (data.msgType == true) {

                            $('#filter_anchalik_id')
                                .append($("<option></option>")
                                    .attr("value", '')
                                    .text('--Select--'));

                            $.each(data.data, function(key, value) {
                                $('#filter_anchalik_id')
                                    .append($("<option></option>")
                                        .attr("value", value['id'])
                                        .text(value['anchalik_parishad_name']));
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

	
	// Gram pachayat display with VCDC/VDC
	
	$(document).on('change', '#filter_b_id', function(e){
            e.preventDefault();
            $('#filter_gram_panchyat_id').empty();

            var block_id = $(this).val();

            if(block_id){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.userManagement.select_vcdc_ajax')}}',
                    dataType: "json",
                    data: {block_id : block_id},
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

	
	// Gram pachayat display with no VCDC/VDC
	
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
                    url: '{{route('admin.userManagement.select_ajax')}}',
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

//----------------------------------------------Mdas User Entry Form---------------------------------------------------
        $("#mdasUserForm").validate({
            rules: {
                mdas_master_level_id:{
                    required: true
                },
                mdas_master_role_id:{
                    required: true
                },
                designation:{
                    required: true
                },
                mdas_user_f_name:{
                    required: true
                },
                mdas_user_l_name:{
                    required: true
                },
                mobile:{
                    required: true
                },
                email:{
                    required: true
                }
            }
        });

        $('#mdasUserForm').on('submit', function(e){
            e.preventDefault();
            if($('#mdasUserForm').valid()){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.userManagement.createMdasUser')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {
                            swal("Success", data.msg, "success")
                                .then((value) => {
                                $('#createUser').modal('hide');
                            location.reload();
                        });
                        }else{
                            if(data.msg == "VE"){
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
</script>
@endsection
