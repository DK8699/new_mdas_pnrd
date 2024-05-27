@php
    $page_title="profile";
@endphp
@extends('layouts.user_mngmnt')
@section('content')
<style type="text/css">
	
	.profile-userpic img {
  float: none;
  margin: 0 auto;
  width: 70%;
  height: 70%;
  -webkit-border-radius: 50% !important;
  -moz-border-radius: 50% !important;
  border-radius: 50% !important;
  border: 2mm ridge rgb(255, 118, 15);
}


.wrapper{
  width:70%;
}
@media(max-width:992px){
 .wrapper{
  width:100%;
} 
}
.panel-heading {
  padding: 0;
	border:0;
}
.panel-title>a, .panel-title>a:active{
	display:block;
	padding:15px;
  color:#555;
  font-size:16px;
  font-weight:bold;
	text-transform:uppercase;
	letter-spacing:1px;
  word-spacing:3px;
	text-decoration:none;
}
.panel-heading  a:before {
   font-family: 'Glyphicons Halflings';
   content: "\e114";
   float: right;
   transition: all 0.5s;
}
.panel-heading.active a:before {
	-webkit-transform: rotate(180deg);
	-moz-transform: rotate(180deg);
	transform: rotate(180deg);
} 

#pri_image{
            cursor: pointer;
        }

        .card{
            border:1px solid #ff770f;
            background-color: #f3f2f2;
            box-shadow:0 1px 5px rgba(0, 0, 0, 0.1);
        }
        .card-header{
            background-color: rgb(255, 118, 15);
            color: #fff;
            font-size: 20px;
            text-align: center;
            font-weight: 700;
            padding:5px;
        }
        .card-body{
            padding:10px;
            text-align: center;
        }

        .card .number{
            font-weight: 900;
            font-size: 40px;
        }

        .card p{
            font-weight: 900;
            font-size: 20px;
        }
</style>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-3">
            	<div class="card mt40">
                    <!-- <div class="card-header">Profile</div> -->
                    <div class="card-body" style="border: 2px solid rgb(255, 118, 15);">
                       <!-- SIDEBAR USERPIC -->
						<div class="profile-userpic">
							<a href="#" data-toggle="tooltip" title="Note: Click  to upload passport photo. Upload jpg, jpeg and png file only. Max image size should not exceed 100KB and not less than 10KB">
							@if($mdasUser->image)
							<img id="pri_image" src="{{$imgUrl}}{{$mdasUser->image}}" class="img-responsive" alt="" style="width:150px; height: 150px;">
							@else
							<img id="pri_image" src="{{asset('mdas_assets/images/user_add.png')}}" class="img-responsive" alt="" style="width:150px; height: 150px;">
							@endif
							</a>
							<form action="#" method="POST" id="profilePicUpdateForm" autocomplete="off">
                    				{{ csrf_field() }}
							<input type="file" name="image" id="pic" style="display: none"/>
							<button type="submit" id="update_dp" class="btn btn-primary btn-xs btn-save" style="display: none; margin: 5px auto;">
			                            <i class="fa fa-send"></i>
			                            Update
			                        </button>
							</form>
						</div>
						<div class="profile-usertitle">
							<div class="profile-usertitle-name">
								<h3>{{$mdasUser->f_name}} {{$mdasUser->m_name}} {{$mdasUser->l_name}}</h3>
							</div>
							<div class="profile-usertitle-job">
								<h6>{{$mdasUser->username}} ({{$mdasUser->designation}})</h6>
							</div>
						</div>
						<div class="profile-usermenu">
							<ul class="nav">
        						<li class="active"  style="text-align: left;">
            						<a href="#tab_1" data-toggle="tab"><i class="fa fa-user"></i> Profile</a>
        						</li>
        						<li class=""  style="text-align: left;">
            						<a href="#tab_2" data-toggle="tab"><i class="fa fa-unlock-alt"></i> Change Password</a>
        						</li>
    						</ul>
							
						</div>
						<!-- END MENU -->
                    </div>
                </div>
		</div>
            <div class="col-md-8">
                <div class="card mt40">
                	<div class="tab-content">
        				<div class="tab-pane active" id="tab_1">
        					<div class="card-header">Profile Details</div>
                    		<div class="card-body">
            					<form action="#" method="POST" id="profileUpdateForm" autocomplete="off">
                    				{{ csrf_field() }}
			                        <!------------------------- TOP BAND ------------------------------>
			                        <div class="row" style="margin-top: 15px;">
			                            <div class="col-md-4 col-sm-4 col-xs-12">
			                                <div class="form-group">
			                                    <label><i class="fa fa-user-circle"></i> First Name <strong>*</strong></label>
			                                    <input type="text" class="form-control" name="f_name" value="{{$mdasUser->f_name}}"/>
			                                </div>
			                             </div>
			                            <div class="col-md-4 col-sm-4 col-xs-12">
			                                <div class="form-group">
			                                    <label><i class="fa fa-user-circle"></i> Middle Name</label>
			                                    <input type="text" class="form-control" name="m_name" value="{{$mdasUser->m_name}}"/>
			                                </div>
			                            </div>
			                            <div class="col-md-4 col-sm-4 col-xs-12">
			                                <div class="form-group">
			                                    <label><i class="fa fa-user-circle"></i> Last Name <strong>*</strong></label>
			                                    <input type="text" class="form-control" name="l_name" value="{{$mdasUser->l_name}}"/>
			                                </div>
			                            </div>
			                        </div>
			                        <div class="row">
			                            <div class="col-md-4 col-sm-4 col-xs-12">
			                                <div class="form-group">
			                                    <label><i class="fa fa-phone"></i> Mobile Number <strong>*</strong></label>
			                                    <input type="number" class="form-control" name="mobile" value="{{$mdasUser->mobile}}"/>
			                                </div>
			                            </div>
			                            <div class="col-md-4 col-sm-4 col-xs-12">
			                                <div class="form-group">
			                                    <label><i class="fa fa-envelope"></i> Email <strong>*</strong></label>
			                                    <input type="email" class="form-control" name="email" value="{{$mdasUser->email}}"/>
			                                </div>
			                            </div>
			                            <div class="col-md-4 col-sm-4 col-xs-12">
			                                <div class="form-group">
			                                    <label><i class="fa fa-anchor"></i> Designation</label>
			                                    <input type="text" class="form-control" name="designation" value="{{$mdasUser->designation}}"/>
			                                </div>
			                            </div>
			                            <div class="col-md-12 col-sm-12 col-xs-12">
			                                <div class="form-group">
			                                    <label><i class="fa fa-map-marker"></i> Address</label>
			                                    <textarea cols="98" name="address">{{$mdasUser->address}}</textarea>
			                                </div>
			                            </div>
			                        </div>
			                        <button type="submit" class="btn btn-primary btn-save">
			                            <i class="fa fa-send"></i>
			                            Update
			                        </button>
                				</form>
        				</div>
        				</div>
        				<div class="tab-pane" id="tab_2">
            				<div class="card-header">Change Password</div>
                    		<div class="card-body">
                        	<form method="POST" id="changePasswordForm" action="">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input id="email" type="password" class="form-control" name="existing_password" value="" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="new_password" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="conform_password" required>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4 text-right">
                                    <button type="submit" class="btn btn-primary">Submit
                                    </button>
                                </div>
                            </div>
                        	</form>
                    	
                    		</div>
        				</div>
    				</div>
                	
                    </div>	
                    </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div  class="container">
    

    
</div>
@endsection
@section('custom_js')
    <script>
        $('#pic').change(function () {
            if (this.files && this.files[0]) {
                checkImage(this.files[0]);
            }
        });

        function imageIsLoaded(e) {
            $('#pri_image').attr('src', e.target.result);
        }

        function checkImage(file){
        	$('#update_dp').css({'display': 'none'});
            var extension = file.name.substr((file.name.lastIndexOf('.') + 1));

            if (extension === 'jpg' || extension === 'jpeg' || extension === 'png') {

                var img=file.size;
                var imgsize=img/1024;
                if(imgsize >= 10 && imgsize <=110){
                    var reader = new FileReader();
                    reader.onload = imageIsLoaded;
                    reader.readAsDataURL(file);
                    $('#update_dp').css({'display': 'block'});
                }else{
                    swal("Information", "Image size must be less than or equal to 100 KB and greater than 10 KB!", "info");
                    $('#pic').val('');
                    @if($mdasUser->image)
							$('#pri_image').attr('src', '{{$imgUrl}}{{$mdasUser->image}}');
					@else
							$('#pri_image').attr('src', '{{asset('mdas_assets/images/d-user.jpg')}}');
					@endif
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

//----------------------------------------------# changePasswordForm #---------------------------------------------------
                $("#changePasswordForm").validate({
            rules: {
                existing_password:{
                required: true
                },
                new_password:{
                required: true
                },
                conform_password:{
                required: true
                }
                }
                });
                

                $('#changePasswordForm').on('submit', function(e){
                e.preventDefault();
                if($('#changePasswordForm').valid()){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('update_password')}}',
                dataType: "json",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                if (data.msgType == true) {
                swal("Success", data.msg, "success")
                .then((value) => {
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

//----------------------------------------------# Update Profile #---------------------------------------------------
           $("#profileUpdateForm").validate({
             rules: {
                f_name:{
                required: true
                },
                // m_name:{
                // required: true
                // },
                l_name:{
                required: true
                },
                mobile:{
                required: true
                },
                email:{
                required: true
                },
                designation:{
                required: true
                },
                address:{
                required: true
                }
                
                }
                });

                $('#profileUpdateForm').on('submit', function(e){
                e.preventDefault();
                if($('#profileUpdateForm').valid()){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('update_profile')}}',
                dataType: "json",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                if (data.msgType == true) {
                swal("Success", data.msg, "success")
                .then((value) => {
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

//----------------------------------------------# Update Profile Picture #---------------------------------------------------

                $('#profilePicUpdateForm').on('submit', function(e){
                e.preventDefault();
                
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('update_profile_pic')}}',
                dataType: "json",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                if (data.msgType == true) {
                swal("Success", data.msg, "success")
                .then((value) => {
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

                });
    </script>
@endsection