@php
    $page_title="Grievance System";
@endphp

@extends('layouts.app_admin_griev')

@section('custom_css')
<link href="{{asset('mdas_assets/css/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css"/>
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
	    .btn, label {
            font-size:12pt;
        }
	    .form-control {
		    height: 40px;
		}
    </style>
@endsection

@section('content')

<div class="container-fluid mb40 mt20">
	<div class="panel">
           <div class="panel-heading">
			<h4 style="color:#fff; font-weight: 500;">Individual Grievance Registration Form</h4>
		</div>
			 <div class="panel-body">
				 <form action="" method="POST" id="addGrievForm" autocomplete="off">
					 <div class="row">
                                   <div class="col-md-4 col-sm-12 col-xs-12">
                                       <div class="form-group">
                                            <label>District</label>
                                            <select class="form-control" id="district_id" name="district_id">
									     <option value="">---Select---</option>
									    @foreach($district as $data)
                                                        <option value="{{$data->id}}">{{$data->district_name}}</option>
									    @endforeach
                                               </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                       <div class="form-group">
                                             <label>Block</label>
                                             <select class="form-control" id="block_id" name="block_id">
											<option value="">---Select---</option>
                                                        <option value=""></option>
                                               </select>
                                        </div>
                                     </div>
                                     <div class="col-md-4 col-sm-12 col-xs-12">
                                         <div class="form-group">
                                              <label>G.P./VCDC/VDC</label>
                                              <select class="form-control" id="gp_id" name="gp_id">
										 <option value="">---Select---</option>
                                                        <option value=""></option>
                                               </select>
                                          </div>
                                     </div>
						 	<div class="col-md-4 col-sm-4 col-xs-12">
									<div class="form-group">
									    <label>Scheme<strong>*</strong></label>
										<select class="schemeList form-control" id="scheme" name="scheme" multiple data-selected-text-format="count" data-count-selected-text="Scheme ({0})">
                                                    @foreach($scheme as $data)
										    <option value="{{$data->id}}">{{$data->scheme_name}}</option>
										    @endforeach
                                                </select>
									</div>
							</div>
						 	<div class="col-md-4 col-sm-12 col-xs-12">
                                         <div class="form-group">
                                              <label>Name of the petitioner <strong>*</strong></label>
                                              <input type="text" class="form-control" name="name" id="name" value=""/>
                                          </div>
                                	</div>
						 	<div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Gender <strong>*</strong></label>
                                            <select class="form-control" name="gender" id="gender">
                                                <option value="">---Select---</option>
									    @foreach($gender as $data)
									    <option value="{{$data->id}}">{{$data->gender_name}}</option>
									    @endforeach
                                            </select>
                                        </div>
                                 	</div>
						  	<div class="col-md-4 col-sm-12 col-xs-12">
                                         <div class="form-group">
                                              <label>Mobile Number <strong>*</strong></label>
                                              <input type="number" class="form-control" name="mobile_no" id="mobile_no" value=""/>
                                          </div>
                                	</div>
						  	<div class="col-md-4 col-sm-12 col-xs-12">
                                         <div class="form-group">
                                              <label>Email <strong>*</strong></label>
                                              <input type="text" class="form-control" name="email" id="email" value=""/>
                                          </div>
                                	</div>
						 	<div class="col-md-4 col-sm-12 col-xs-12">
                                         <div class="form-group">
                                              <label>Document <strong>*</strong></label>
									 <input type="file" class="form-control" name="document"/>
                                          </div>
                                	</div>
						  	<div class="col-md-6 col-sm-12 col-xs-12">
							  <div class="form-group">
								 <label>Address <strong>*</strong></label>
						 		<textarea name="address" rows="4" class="form-control" id="address"></textarea>
							  </div>
						 	</div>
						  	<div class="col-md-6 col-sm-12 col-xs-12">
							  <div class="form-group">
								 <label>Grievance Details<strong> (*Max length:500)</strong></label>
						 		<textarea name="details" rows="4" class="form-control" id="details"></textarea>
							  </div>
						 	</div>
						  	<div class="col-md-4 col-sm-12 col-xs-12">
                                         <div class="form-group">
                                              <label>Reference Number (if any)</label>
                                              <input type="text" class="form-control" name="ref_code" id="ref_code"/>
                                          </div>
                                	</div>
						 	<div class="col-md-4 col-sm-12 col-xs-12">
                                         <div class="form-group">
                                              <label>Reference Date (if any)</label>
                                              <input type="text" class="form-control" name="ref_date" id="ref_date"/>
                                          </div>
                                </div>
					 </div>
					<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
							     <button type="submit" class="btn btn-primary btn-save pull-right">
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
<script src="{{asset('mdas_assets/js/bootstrap-select.min.js')}}"></script>
    <script>
	    
	   $('.schemeList').selectpicker(); 
	    
	   $('#ref_date').Zebra_DatePicker();
	    
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
	  
	    
	   $("#addGrievForm").validate({
            rules: {
                details: {
                    required: true,
				maxlength:4000,
                },
            },
        });

        $('#addGrievForm').on('submit', function(e){
            e.preventDefault();
		   
		   /*alert($("#scheme").val());*/
             var formData = new FormData(this);
             formData.append('scheme', $("#scheme").val() ); 
		   
            if($('#addGrievForm').valid()){

                $('.page-loader-wrapper').fadeIn();

                $('.form_errors').remove();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.Grievance.individual_griev_save')}}',
                    dataType: "json",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {
				 
                            swal("Success", data.msg, "success")
                                .then((value) => {
                          grievMsg(data.data.entry_id);
				 	 /*window.location.href='{{route('admin.Grievance.individual_griev_confirm_page')}}'+'?entry_id='+data.data.entry_id;*/
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
	    
	   function grievMsg(id)
        {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'get',
                    url: '{{route('admin.Grievance.Individual.entry_msg')}}',
                    data: '&id='+id,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
				 window.location.href='{{route('admin.Grievance.individual_griev_confirm_page')}}'+'?entry_id='+id;
                    }
                });
			  return false;
            }


	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
    </script>
@endsection