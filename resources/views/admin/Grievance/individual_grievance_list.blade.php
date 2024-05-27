@php
    $page_title="Grievance System";
@endphp

@extends('layouts.app_admin_griev')

@section('custom_css')

	   <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="{{asset('mdas_assets/css/style.css')}}">
        <link rel="stylesheet" href="{{asset('mdas_assets/css/multi-select.css')}}"/>
    <style>
	    label{
		    color:#337ab7;
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
	    button.dt-button, div.dt-button, a.dt-button {
    background-image: linear-gradient(to bottom, #4357e4 0%, #240e29 100%);
    color: #fff;
	    }
    </style>
@endsection

@section('content')

<div class="container mb40">
        <div class="row mt40">
           <table class="table table-bordered" id="dataTable">
                        <thead>
                        <tr style="background-color:#5f146f; color:#fff">
                            <td>SL</td>
                            <td>Grievance Code</td>
                            <td>Name of Petitioner</td>
                            <td>Description</td>
                            <td>Date of Entry</td>
                            <td>Action</td>
                        </tr>
                        </thead>
                        <tbody>
					  @php $i=1; @endphp
					  @foreach($grievList as $list)
					    <tr>
						  <td>{{$i}}</td>
						  <td>{{$list->grievance_code}}</td>
						  <td>{{$list->name}}</td>
						  <td>{{substr($list->grievance_details,0,5)}}....<a target="_blank" href="{{route('admin.grievance.Individual.details', Crypt::encrypt($list->id))}}">more</a></td>
						  <td>{{$list->entry_date}}</td>
						   	   @if($list->reply_status == 1)
									<td><span class="badge" style="background: green;color:#fff"><i class="fa fa-check" aria-hidden="true"></i> Action taken at {{$list->level_name}} level</span></td>
							   @elseif($list->action_level != 1)
						    			<td><span class="badge" style="background: blue;color:#fff"><i class="fa fa-info-circle" aria-hidden="true"></i> Action to be taken at {{$list->level_name}} level</span></td>
						        @else
									    <td><button class="setAction btn-primary" data-gid="{{$list->id}}"> <i class="fa fa-tasks" aria-hidden="true"></i> Action</button></td>
							  @endif
					   </tr>
					    @php $i++; @endphp
					  @endforeach
                        </tbody>
           </table>
        </div>
	
	  <!--------------- Action Modal------------------->	
	
   	  <div id="setActionModal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div style="background-color:#6b133d;" class="modal-header bg-primary">
                             <button type="button" style="background-color:red" class="btn modal-close" data-dismiss="modal" aria-hidden="true">
						    <i class="fa fa-close"></i>
						</button>
					     <h3 class="modal-title">Action Section</h3>
                            <h4 class="modal-title" id="ins_modal_title1"></h4>
                        </div>
                        <form action="#" method="POST" id="setActionForm">
					    <div class="modal-body">
						    <div class="row">
								 <div class="col-md-4 col-xs-4 col-sm-4">
								   <label>Grievance Code</label>
								   <h6 style="font-weight:bold" class="griev_code" id="griev_code"></h6>
								</div>
						   </div> 
							     <input type="hidden" name="griev_id" class="griev_id" id="griev_id" value=""/>
								    <div class="row" style="margin-top:20px;">
									  <div class="col-md-6 col-sm-6 col-xs-12">
											<label>Action to be taken at <strong>*</strong></label>
											 <select class="form-control" name="action_level" id="action_level">
											    <option value="">---Select---</option>
												 @foreach($actionLevel as $data)
												 	 <option value="{{$data->id}}">{{$data->level_name}}</option>
												 @endforeach
											    
										    </select>
									  </div>
									  <div class="col-md-4 col-sm-6 col-xs-12">
										<div class="form-group">
											  <label>Action to be taken by<strong>*</strong></label>
											  <select class="form-control" name="action_taken_by" id="action_taken_by">
												    <option value="">---Select---</option>
												    @foreach($actionTakenBy as $li_a)
												    	<option value="{{$li_a->id}}">{{$li_a->submitted_by}}</option>
												    @endforeach
											  </select>
										</div>
									   </div>
									   <div class="col-md-6 col-sm-6 col-xs-12 reportDiv" style="display:none">
											<label>Report Copy (<strong>*pdf format/max-size:400KB</strong>)</label>
											<input type="file" class="form-control" name="report_attachment"/>
									  </div>
								    </div>
					    </div>
					    <div class="modal-footer">
						   <button type="submit" class="btn btn-primary pull-right">
							   <i class="fa fa-paper-plane" aria-hidden="true"></i>
							  Assign
						   </button>
					    </div>
                        </form>
                    </div>
                </div>
           </div>	
	
 </div>




@endsection

@section('custom_js')

    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <script>
	   $(document).ready(function () {
            $('#dataTable').DataTable({
                dom: 'Bfrtip',
                ordering: false,
                paging: true,
                buttons: [
                    {
                        extend:    'excelHtml5',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
        });
	    
	   $('.setAction').on('click', function(e){
                    e.preventDefault();
                    var id= $(this).data('gid');
                    $('.griev_id').val('');
                    $('.griev_id').val(id);

		 		$.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "{{route('admin.Grievance.Individual.getGrievData')}}",
                        dataType: "json",
                        data: {id:id},
                        cache: false,
                        success: function (data) {
                            if (data.msgType == true) {
                                    $('#setActionModal').modal('show');
						   	 $('.griev_code').text(data.data.grievData.grievance_code);
                            } else {

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

                    
                });
	
	   $("#action_level").change(function(){
                var selectedRole = $(this).children("option:selected").val();
			  
                if(selectedRole == 1)
                    {
                        $('.reportDiv').show();
                    }
			  else{
				  $('.reportDiv').hide();
			  }

            });
	    
	   $("#setActionForm").validate({
            rules: {
			  action_taken:{
				required: true,
			  }
            },
        });
	    
	   $('#setActionForm').on('submit', function(e){
			  e.preventDefault();

			  if($('#setActionForm').valid()){

				 $('.page-loader-wrapper').fadeIn();

				 $('.form_errors').remove();

				 $.ajax({
					headers: {
					    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					type: "POST",
					url: '{{route('admin.Grievance.Individual.action')}}',
					dataType: "json",
					data: new FormData(this),
					contentType: false,
					cache: false,
					processData: false,
					success: function (data) {
					    if (data.msgType == true) {
							grievActionMsg(data.data.grievance_code);
						   swal("Success", data.msg, "success")
							  .then((value) => {
							location.reload();
							//window.location.href = "{{route('grievance.dashboard')}}";
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
		   
		    function grievActionMsg(code)
        		{
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'get',
                    url: '{{route('admin.Grievance.Individual.action_msg')}}',
                    data: '&code='+code,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
				 location.reload();
                    }
                });
			  return false;
            }
	    
	    
    </script>
@endsection