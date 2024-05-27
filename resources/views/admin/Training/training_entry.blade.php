@php
    $page_title="Need Based Training";
@endphp

@extends('layouts.app_admin_training')

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
			<h4 style="color:#fff; font-weight: 500;">Training Details</h4>
		</div>
			 <div class="panel-body">
				 <form action="" method="POST" id="addTrainingForm" autocomplete="off">
					 <div class="row">
						  	<div class="col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Training Centre<strong>*</strong></label>
										<select class="centreList form-control" id="t_centre" name="t_centre" multiple data-selected-text-format="count" data-count-selected-text="Training Centre ({0})">
										@foreach($training_centre as $li)
										    <option value="{{$li->centre_id}}">{{$li->centre_name}}</option>
										@endforeach
                                               	</select>
                                        </div>
                                   </div>
						 	<div class="col-md-4 col-sm-12 col-xs-12">
                                       <div class="form-group">
                                            <label>Programme</label>
                                           <select class="form-control" id="programme" name="programme">
											<option value="">---Select---</option>
									   		@foreach($training_programme as $li)
									   			<option value="{{$li->id}}">{{$li->programme_name}}</option>
									   		@endforeach
                                               </select>
                                        </div>
                                   </div>
						  	<div class="col-md-4 col-sm-12 col-xs-12">
                                       <div class="form-group">
                                             <label>Year</label>
                                             <select class="form-control" id="year_id" name="year_id">
											<option value="">---Select---</option>
                                                        <option value="1">2018-19</option>
                                                        <option value="2">2019-20</option>
                                                        <option value="3">2020-21</option>
                                               </select>
                                        </div>
                                   </div>
                                  <div class="col-md-4 col-sm-12 col-xs-12">
								  <div class="form-group">
									  <label> Start Date <strong>*</strong></label>
									  <input type="text" class="form-control start_date" name="start_date" id="start_date" value=""/>
								   </div>
						    </div>
						    <div class="col-md-4 col-sm-12 col-xs-12">
								  <div class="form-group">
									  <label> End Date <strong>*</strong></label>
									  <input type="text" class="form-control end_date" name="end_date" id="end_date" value=""/>
								   </div>
						    </div>
						    <div class="col-md-12 col-sm-12 col-xs-12">
                                       <div class="form-group">
                                            <label>Course <strong> (*Max length:500)</strong></label>
                                            <textarea name="course" rows="4" class="form-control" id="course"></textarea>
                                        </div>
                                   </div>
						
						 	<!--<div class="col-md-4 col-sm-12 col-xs-12">
                                         <div class="form-group">
                                              <label>Document <strong>*</strong></label>
									 <input type="file" class="form-control" name="document"/>
                                          </div>
                                </div>-->
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
	    
	  $('.centreList').selectpicker(); 
	    
	  $('#start_date').Zebra_DatePicker({});

	  $('#end_date').Zebra_DatePicker({});   
	    
	    
	  $("#addTrainingForm").validate({
            rules: {
                course: {
                    required: true,
				maxlength:4000,
                },
            },
        });
	    

       $('#addTrainingForm').on('submit', function(e){
            e.preventDefault();
		   
		   /*alert($("#scheme").val());*/
                    var formData = new FormData(this);
                    formData.append('t_centre', $("#t_centre").val() );

            if($('#addTrainingForm').valid()){

                $('.page-loader-wrapper').fadeIn();

                $('.form_errors').remove();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.Training.save')}}',
                    dataType: "json",
                    data: formData,
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