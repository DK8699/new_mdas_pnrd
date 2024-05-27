@php
    $page_title="Grievance System";
@endphp

@extends('layouts.app_admin_griev')

@section('custom_css')
<style>
    label{
		  color: #337ab7;
	    }
        .panel-heading{
            border: 1px solid #6b133d33;
            background-color:rgb(117, 22, 65);
            max-height: 80px;
            min-height: 50px;
        }
        .card span {
            font-weight: bolder;
        }
	    
        strong {
            color: red;
        }
	    .mb40{
            margin-bottom: 40px;
        }
	    .mt20{
		    margin-top: 20px;
	    }
	    .btn, label {
            font-size:12pt;
        }
	    .form-control {
		    height: 40px;
		}
</style>
@endsection

@section('content')

 <div class="container  mb40 mt20">
        <div class="row">
           	<div class="panel">
				 <div class="panel-heading">
					<h4 style="color:#fff; font-weight: 500; text-align:center;">Media Grievance Recipients</h4>
				</div>
				 <div class="panel-body" style="border: 4px solid;">
					 <form action="" method="POST" id="addRecipients" autocomplete="off">

						<div class="row">
							  <div class="col-md-4 col-sm-12 col-xs-12">
									 <div class="form-group">
										 <label>First Name </label>
										 <input type="text" class="form-control" name="f_name" id="f_name" value=""/>
									  </div>
							  </div>
							  <div class="col-md-4 col-sm-12 col-xs-12">
									 <div class="form-group">
										 <label>Middle Name </label>
										 <input type="text" class="form-control" name="m_name" id="m_name" value=""/>
									  </div>
							  </div>
							  <div class="col-md-4 col-sm-12 col-xs-12">
									 <div class="form-group">
										 <label>Last Name </label>
										 <input type="text" class="form-control" name="l_name" id="l_name" value=""/>
									  </div>
							  </div>
							  <div class="col-md-4 col-sm-12 col-xs-12">
									 <div class="form-group">
										 <label>Mobile Number</label>
										 <input type="text" class="form-control" name="mobile_no" id="mobile_no"/>
									  </div>
							  </div>
							  <div class="col-md-4 col-sm-12 col-xs-12">
									 <div class="form-group">
										 <label>Email Id</label>
										 <input type="text" class="form-control" name="email_id" id="email_id"/>
									  </div>
							  </div>
							   <div class="col-md-4 col-sm-12 col-xs-12">
									 <div class="form-group">
										 <label>Designation</label>
										 <input type="text" class="form-control" name="designation" id="designation"/>
									  </div>
							  </div>
							  <div class="col-md-4 col-sm-4 col-xs-12">
									<div class="form-group">
									    <label>Under <strong>*</strong></label>
									    <select class="form-control" name="submitted_to" id="submitted_to">
										   <option value="">---Select---</option>
										    @foreach($actionTakenBy as $value)
										    <option value="{{$value->id}}">{{$value->submitted_by}}</option>
										    @endforeach
									    </select>
									</div>
							  </div>
							  <div class="col-md-4 col-sm-4 col-xs-12 ditrict_div" style="display:none">
									<div class="form-group">
									    <label>District <strong>*</strong></label>
									    <select class="form-control" name="district_id" id="district_id">
										   <option value="">---Select---</option>
										    @foreach($district as $value)
										    <option value="{{$value->id}}">{{$value->district_name}}</option>
										    @endforeach
									    </select>
									</div>
							  </div>
							  <div class="col-md-4 col-sm-4 col-xs-12 block_div" style="display:none">
									<div class="form-group">
									    <label>Block<strong>*</strong></label>
									    <select class="form-control" name="block_id" id="block_id">
										   <option value="">---Select---</option>
									    </select>
									</div>
							  </div>
							  <div class="col-md-4 col-sm-4 col-xs-12 gp_div" style="display:none">
									<div class="form-group">
									    <label>GP/VCDC/VDC <strong>*</strong></label>
									    <select class="form-control" name="gp_id" id="gp_id">
										   <option value="">---Select---</option>

										    <option value=""></option>

									    </select>
									</div>
							  </div>
						 </div>
						 <div class="row">
							   <div class="col-md-12 col-sm-12 col-xs-12">
								    <button type="submit" class="btn btn-primary btn-save pull-right">
									  <i class="fa fa-send"></i>
									   Add
									</button>
							    </div>
						  </div>				 
					 </form>
				</div>
			</div>
	 </div>
</div>
@endsection

@section('custom_js')
    <script type="text/javascript">

        $(document).on('change', '#submitted_to', function(e) {
		 e.preventDefault();
		  
            if(this.value == 1 || this.value == 2) {
                $('.ditrict_div').hide();
                $('.block_div').hide();
                $('.gp_div').hide();
            }
		   else if(this.value == 3 || this.value == 4 || this.value == 5) {
			 $('.ditrict_div').show();
			 $('.block_div').hide();
                $('.gp_div').hide();
            }
            else if(this.value == 6) {
                $('.ditrict_div').show();
			 $('.block_div').show();
                $('.gp_div').hide();
            }else {
               $('.ditrict_div').show();
			 $('.block_div').show();
                $('.gp_div').show();
            }
        } ).trigger( 'change' ); 
	    
	    
	    //-------------ON DISTRICT CHANGE-----------------------

        $('#district_id').on('change', function(e){

            e.preventDefault();
		   

            $('#block_id').empty();
            $('#gp_id').empty();

            var district_id= $('#district_id').val();
		   //alert(district_id);

            if(district_id){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.Grievance.getBlockByDistrict')}}',
                    dataType: "json",
                    data: {district_id : district_id},
                    success: function (data) {
                        if (data.msgType == true) {

                            $('#block_id')
                                .append($("<option></option>")
                                    .attr("value", '')
                                    .text('--Select--'));

                            $.each(data.data, function(key, value) {
                                $('#block_id')
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
	    
	    //-------------ON BLOCK CHANGE-----------------------

        $('#block_id').on('change', function(e){

            e.preventDefault();
            
            $('#gp_id').empty();

            var block_id= $('#block_id').val();
		   //alert(district_id);

            if(block_id){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.Grievance.getGPsByBlock')}}',
                    dataType: "json",
                    data: {block_id : block_id},
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
	    
	    
	   $("#addMediaGrievForm").validate({
            rules: {
                details: {
                    required: true,
				maxlength:4000,
                },
            },
        });
	    
	    
	    
        $("#addRecipients").validate({
            rules: {
                f_name: {
                    required: true,
                    blank:true,
                    maxlength:100
                },
                m_name: {
                    maxlength:100
                },
                l_name: {
                    required: true,
                    blank:true,
                    maxlength:100
                },
               
                mobile_no: {
                    required: true,
                    digits:true
                },
                email_id: {
                    email:true,
                    maxlength:150
                },
               designation:{
                   required: true,
                   maxlength:150
               },
               district_id:{
                    digits:true
                },
                submitted_to:{
                    required: true,
                    // digits:true
                },
            },
        });
       

        $('#addRecipients').on('submit', function(e){
            e.preventDefault();

            if($('#addRecipients').valid()){

                $('.page-loader-wrapper').fadeIn();

                $('.form_errors').remove();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.Grievance.saveRecipients')}}',
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
    </script> 
@endsection