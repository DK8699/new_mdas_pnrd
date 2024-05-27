@php
    $page_title="Grievance System";
@endphp

@extends('layouts.app_admin_griev')

@section('custom_css')
 <link href="{{asset('mdas_assets/css/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css"/>
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

<div class="container-fluid mb40 mt20">
	
	<div class="row">
		<form action="" id="generateReport" method="POST" id="" autocomplete="off">
			 <div class="col-md-3 col-sm-4 col-xs-12">
                           <div class="form-group">
                               <label>Category <strong>*</strong></label>
                               <select class="form-control" name="cat_id" id="cat_id">
							 <option value="1">Date Wise</option>
							 <option value="2">Date Range</option>
                                </select>
                           </div>
                </div>
			<div class="col-md-3 col-sm-4 col-xs-12 startDiv">
                      <div class="form-group">
                           <label class="select_date_label"> SELECT DATE <strong>*</strong></label>
                           <label class="start_date_label" style="display:none"> START DATE <strong>*</strong></label>
                           <input type="text" class="form-control start_date" name="start_date" id="start_date" value="" style="height:35px"/>
                       </div>
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12 endDiv" style="display:none">
                      <div class="form-group">
                           <label> END DATE <strong>*</strong></label>
                           <input type="text" class="form-control end_date" name="end_date" id="end_date" value="" style="height:35px"/>
                       </div>
			</div>
			<div class="col-md-2 col-sm-12 col-xs-12">
				<button type="submit" class="btn-primary btn-sm" style="margin:20px;">
                             Grievance report
                    </button>
               </div>
		</form>	
	</div>
	
	<div class="panel">
           <div class="panel-heading">
			<h4 style="color:#fff; font-weight: 500; text-align:center;">Media Grievance Registration Form</h4>
		</div>
			 <div class="panel-body" style="border: 4px solid;">
				 <form action="" method="POST" id="addMediaGrievForm" autocomplete="off">
					 
					<div class="row">
						  <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Media <strong>*</strong></label>
                                            <select class="form-control" name="media_id" id="media_id">
                                                <option value="">---Select---</option>
									    @foreach($media as $value)
									    <option value="{{$value->id}}">{{$value->name}}</option>
									    @endforeach
                                            </select>
                                        </div>
                                </div>
						  <div class="col-md-4 col-sm-12 col-xs-12">
                                         <div class="form-group">
                                              <label>Name <strong>*</strong></label>
                                              <input type="text" class="form-control" name="name" id="name" value=""/>
                                          </div>
                                </div>
						  <div class="col-md-4 col-sm-12 col-xs-12">
                                         <div class="form-group">
                                              <label>Publish Date <strong>*</strong></label>
                                              <input type="text" class="form-control" name="date" id="date"/>
                                          </div>
                                </div>
						  <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>District </label>
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
                                            <select class="form-control" name="block_id" id="block_id">
                                                <option value="">---Select---</option>
                                            </select>
                                        </div>
                                </div>
						   <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>GP/VCDC/VDC</label>
                                            <select class="form-control" name="gp_id" id="gp_id">
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
						<div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Supporting Document</label>
                                            <input type="file" class="form-control" name="s_document"/>
                                        </div>
                              </div>
						<div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Supporting Link</label>
								    <input type="text" class="form-control" name="s_link" id="s_link"/>
                                        </div>
                                </div>
						  <div class="col-md-12 col-sm-12 col-xs-12">
							  <div class="form-group">
								 <label>Nature of Grievance <strong> (*Max length:500)</strong></label>
						 		<textarea name="details" rows="4" class="form-control" id="details"></textarea>
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
	    
	   $('#date').Zebra_DatePicker({});
	    
	   $('#start_date').Zebra_DatePicker({ });
	    
	    
	    //On Category Change
	    
	   $(document).on('change','#cat_id',function(){
		   
               var selectedRole = $(this).children("option:selected").val();
			  
                if(selectedRole == 2)
                 {
				 
				  $('.endDiv').show();
				  $('.select_date_label').hide();
				  $('.start_date_label').show();
				  $('#start_date').val('');
				  $('#end_date').val('');
				  $('#end_date').Zebra_DatePicker({
					  //direction: [s, false]
				  });  
			  }
		    	 else{
				   
				 $('.select_date_label').show();
				  $('.start_date_label').hide();
				  $('#end_date').val('');
				 
				  $('.endDiv').hide();
		       }

            });
	   
	    
	   $('#downloadReport').on('click', function(e) {
            e.preventDefault();
            $('.form_errors').remove();
            var search_date= $('#search_date').val();

            swal({
                title: "Are you sure?",
                text: "You are sure you want to download.",
                icon: "warning",
                buttons: {
                    cancel: "Cancel",
                    catch: {
                        text: "Proceed",
                        value: "catch",
                    }
                },
            }).then((value) => {
                switch (value) {
                case "catch":
                    $('.page-loader-wrapper').fadeIn();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "{{route('admin.grievance.media.download.permission')}}",
                        dataType: "json",
                        data: {search_date: search_date},
                        cache: false,
                        success: function (data) {
                            if (data.msgType == true){
						   //window.open('{{route('admin.grievance.media.download')}}'+'?date='+search_date);
						  // window.open(`{{route('admin.grievance.media.download')}}?date=${search_date}`);
						   //alert(`hello ${search_date}`);
						   
                            } 
					   else{
                                if (data.msg == "VE") {
                                    swal("Error", "Validation error.Please check the form correctly!", 'error');
                                    $.each(data.errors, function (index, value) {
                                        $('#' + index).after('<p class="text-danger form_errors">' + value + '</p>');
                                    });
                                } else {
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
                    break;

                default:
                    swal("Cancelled operation!");
                }
            })
        });
	    
	    
	    
	    //Download Report
	    
	    $('#generateReport').on('submit', function(e){
            e.preventDefault();

                $('.form_errors').remove();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.grievance.media.download.permission')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
				 
				 var start_date = data.data.start_date;
				 var end_date = data.data.end_date;
                       if (data.msgType == true){
					 if(!end_date){
							window.open('{{route('admin.grievance.media.download')}}'+'?date='+start_date);
						}
					 else{
							window.open('{{route('admin.grievance.media.download')}}'+'?date='+start_date+'&date2='+end_date);
						}
	    			}
				 else {
					if (data.msg == "VE") {
						swal("Error", "Validation error.Please check the form correctly!", 'error');
						$.each(data.errors, function (index, value) {
						$('#' + index).after('<p class="text-danger form_errors">' + value + '</p>');
						});
						} 
					 else {
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
	    

        $('#addMediaGrievForm').on('submit', function(e){
            e.preventDefault();
		   
		   /*alert($("#scheme").val());*/
                    var formData = new FormData(this);
                    formData.append('scheme', $("#scheme").val() );

            if($('#addMediaGrievForm').valid()){

                $('.page-loader-wrapper').fadeIn();

                $('.form_errors').remove();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.Grievance.media.save')}}',
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