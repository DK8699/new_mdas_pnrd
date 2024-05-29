@php
    $page_title="Grievance System";
@endphp


@extends('layouts.app_website')


@section('custom_css')
    <style>
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
	    .mt40{
		    margin-top: 40px;
	    }
	    .btn, label {
            font-size:10pt;
        }
	   .form-control {
        width: 100%;
        height: 40px;
        padding: 6px 12px;
        font-size: 14px;
        background-color: #fff;
        border: 1px solid #ccc;

    		}
	    .form-control:focus {
		   color: #616161;
		   border-color: #beb6b6;
	    }
	   input[type=number]::-webkit-inner-spin-button,
		input[type=number]::-webkit-outer-spin-button {
		    -webkit-appearance: none;
		    -moz-appearance: none;
		    appearance: none;
		    margin: 0;
	   }
	    .btn::before{
		    background:#fff;
	    }
	    .bootstrap-select>.dropdown-toggle{
		    border:1px solid;
		    border-radius: 6px;
	    }
	    .btn-default.active, .btn-default:active, .open>.dropdown-toggle.btn-default{
		    background: #fff;
	    }
	    .dropdown-menu>li>a {
		    color: #151414;
		}
	    .btn-primary {
	    		background: #751641;
	    		color: #fff;
	    }
	    .btn-primary:hover::after{
		    background: #751641;
	    }
	    .btn-primary:hover::before{
		    background: #751641;
	    }
    </style>
@endsection

@section('content')
<!--<div class="container">
	<div class="row" id="container" style="margin: 10px 0 15px 0; height: 255px; position: relative">
		<div class="col-md-6 col-xs-6 col-sm-6">
			
		</div>
	</div>
	<div class="row">
		<div class="col-md-3 col-xs-6 col-sm-6">
			<input type="text" class="form-control start_date" name="start_date" id="start_date" value="" style="height:35px"/>
		</div>
	</div>
</div>-->
<div class="container mb40 mt40">
	 
	<div class="panel">
           <div class="panel-heading">
			<h4 style="color:#fff; font-weight: 500;">Need Based Training</h4>
		</div>
			 <div class="panel-body">
				 <form action="" method="POST" id="participantsForm" autocomplete="off">
					 	<input type="hidden" id="training_id" name="training_id" value="{{$data['training_id']}}"/>
					 	<input type="hidden" id="training_location_id" name="training_location_id" value="{{$data['location_id']}}"/>
					 	<div class="row">
						 	<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
									    <label>Course</label>
						 				<textarea name="course" rows="4" class="form-control" id="course" readonly>{{($data['getTrainingDetails'])->course}}</textarea>
									</div>
							</div>
					 	</div>
					     <div class="row">
						 	<div class="col-md-4 col-sm-12 col-xs-12">
                                       <div class="form-group">
                                            <label>Training Centre <strong>*</strong></label>
                                            <select class="form-control" id="centre_id" name="centre_id" readonly>
                                                        <option value="{{($data['getTrainingDetails'])->training_centre_id}}" >{{($data['getTrainingDetails'])->centre_name}} </option>
                                            </select>
                                        </div>
                                   </div>
						 	<div class="col-md-4 col-sm-4 col-xs-12">
									<div class="form-group">
									    <label>Programme<strong>*</strong></label>
										<select class="form-control" id="programme" name="programme" readonly>
										    <option value="{{($data['getTrainingDetails'])->programme}}" >{{($data['getTrainingDetails'])->programme_name}} </option>
                                                	</select>
									</div>
							</div>
						 	<div class="col-md-4 col-sm-12 col-xs-12">
                                         <div class="form-group">
                                              <label>No of Days of Training<strong>*</strong></label>
                                              <input type="text" class="form-control" name="no_of_days" id="no_of_days" value="{{($data['getTrainingDetails'])->no_of_days}}" readonly/>
                                          </div>
                                	</div>
					 	</div>
					     <div class="row">
						 	<div class="col-md-4 col-sm-12 col-xs-12">
                                         <div class="form-group">
                                              <label>Name<strong>*</strong></label>
                                              <input type="text" class="form-control" name="name" id="name" value=""/>
                                          </div>
                                	</div>
						 	<div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Gender <strong>*</strong></label>
                                            <select class="form-control" name="gender" id="gender">
                                                <option value="">---Select---</option>
									    @foreach($gender as $li)
									    <option value="{{$li->id}}">{{$li->gender_name}}</option>
									    @endforeach
                                            </select>
                                        </div>
                                 	</div>
						 	<div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Caste <strong>*</strong></label>
                                            <select class="form-control" name="caste" id="caste">
                                                <option value="">---Select---</option>
									    @foreach($caste as $li)
									    <option value="{{$li->id}}">{{$li->caste_name}}</option>
									    @endforeach
                                            </select>
                                        </div>
                                 	</div>
					 	</div>
					 	<div class="row">
						  	<div class="col-md-4 col-sm-12 col-xs-12">
                                         <div class="form-group">
                                              <label>Mobile Number <strong>*</strong></label>
                                              <input type="number" class="form-control" name="mobile_no" id="mobile_no" value=""/>
                                          </div>
                                	</div>
						 	<div class="col-md-4 col-sm-12 col-xs-12">
                                         <div class="form-group">
                                              <label>Alternative Mobile Number</label>
                                              <input type="number" class="form-control" name="alt_mobile_no" id="alt_mobile_no" value=""/>
                                          </div>
                                	</div>
						  	<div class="col-md-4 col-sm-12 col-xs-12">
                                         <div class="form-group">
                                              <label>Email <strong>*</strong></label>
                                              <input type="text" class="form-control" name="email" id="email" value=""/>
                                          </div>
                                	</div>
					 	</div>
					 	<div class="row">
						 	<div class="col-md-4 col-sm-4 col-xs-12">
									<div class="form-group">
									    <label>District<strong>*</strong></label>
										 <select class="form-control" name="district_id" id="district_id">
										   <option value="">---Select---</option>
										    	@foreach($district as $value)
										    	<option value="{{$value->id}}">{{$value->district_name}}</option>
											@endforeach
                                            		 </select>
									</div>
							</div>
						 	<div class="col-md-4 col-sm-4 col-xs-12">
									<div class="form-group">
									    <label>Block</label>
										<select class="form-control" id="block_id" name="block_id">
										    <option value=""></option>
                                                	</select>
									</div>
							</div>
						 	<div class="col-md-4 col-sm-4 col-xs-12">
									<div class="form-group">
									    <label>GP/VCDC</label>
										<select class="form-control" id="gp_id" name="gp_id">
										    <option value=""></option>
                                                	</select>
									</div>
							</div>
					 	</div>
					 	<div class="row">
						 	<div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Employee working under P&amp;RD ? <strong>*</strong></label>
                                            <select class="form-control" name="working_status" id="working_status">
                                                <option value="">---Select---</option>
									    <option value="1">YES</option>
									    <option value="2">NO</option>
                                            </select>
                                        </div>
                                 	</div>
						 	<div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group designationDiv" style="display:none">
                                            <label>Designation<strong>*</strong></label>
                                            <select class="form-control" name="designation" id="designation">
                                                <option value="">---Select---</option>
									    <option value="0">Others</option>
									    <option value="1">DPM</option>
									    <option value="2">ADPM</option>
									    
                                            </select>
                                        </div>
                                 	</div>
							 <div class="col-md-4 col-sm-4 col-xs-12">
									<div class="form-group oDesignationDiv" style="display:none">
								      <label>Designation <strong>*</strong></label>
                                              <input type="text" class="form-control" name="o_designation" id="o_designation" value=""/>
								 </div>
							 </div>
					 	</div>
						 	<!--<div class="col-md-4 col-sm-12 col-xs-12">
                                         <div class="form-group">
                                              <label>Document <strong>*</strong></label>
									 <input type="file" class="form-control" name="document"/>
                                          </div>
                                	</div>-->
					 	<div class="row">
						  	<div class="col-md-6 col-sm-12 col-xs-12">
							  <div class="form-group">
								 <label>Address <strong>*</strong></label>
						 		<textarea name="address" rows="4" class="form-control" id="address"></textarea>
							  </div>
						 	</div>
						  	<div class="col-md-6 col-sm-12 col-xs-12">
							  <div class="form-group">
								 <label>Description <strong> (*Max length:500)</strong></label>
						 		<textarea name="description" rows="4" class="form-control" id="description"></textarea>
							  </div>
						 	</div>
					 	</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
							     <button type="submit" class="btn btn-save btn-primary pull-right">
									<i class="fa fa-send"></i>
									Submit
								</button>
							</div>
						</div>				 
				 </form>
			</div>
	</div>
</div>




@endsection

@section('custom_js')
    <script>
	    
	   //$('#ref_date').Zebra_DatePicker();
	    
	   $('#start_date').Zebra_DatePicker({
		    always_visible: $('#container'),
		    format: 'm Y',
		});
	    
	   $(document).on('change','#working_status',function(){
		   
               var selectedRole = $(this).children("option:selected").val();
			
			 if(selectedRole == 1)
                 {
				 
				  $('.designationDiv').show();
				  
			  }
		    	 else{
				   
				 $('.designationDiv').hide();
				 $('.oDesignationDiv').hide();
		       }
			
			});
	    
	   $(document).on('change','#designation',function(){
		   
               var selectedRole = $(this).children("option:selected").val();
			
			 if(selectedRole == 0)
                 {
				 
				  $('.oDesignationDiv').show();
				  
			  }
		    	 else{
				   
				 $('.oDesignationDiv').hide();
		       }
			
			});
	    
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
                    url: '{{route('training.getBlockByDistrict')}}',
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
                    url: '{{route('training.getGPsByBlock')}}',
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
	    
	   $("#participantsForm").validate({
            rules: {
                name: {
                    required: true,
                },
			  name: {
                    required: true,
                },
			  gender: {
                    required: true,
                },
			  caste: {
                    required: true,
                },
			  mobile_no: {
                    required: true,
                },
			  email: {
                    required: true,
                },
			  district_id: {
                    required: true,
                },
            },
        });
	    

        $('#participantsForm').on('submit', function(e){
            e.preventDefault();
		   
             var formData = new FormData(this);
                    

            if($('#participantsForm').valid()){

                $('.page-loader-wrapper').fadeIn();

                $('.form_errors').remove();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url:'{{route('training.participants.save')}}',
                    dataType: "json",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {
                            swal("Success", data.msg, "success")
                                .then((value) => {
                           window.location.href='{{route('training.participants.confirmation')}}'+'?participant_id='+data.data.participant_id;
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