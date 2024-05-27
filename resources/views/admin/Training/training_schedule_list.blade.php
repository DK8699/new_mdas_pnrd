@php
    $page_title="Need Based Training";
@endphp

@extends('layouts.app_admin_training')

@section('custom_css')
	<link href="{{asset('mdas_assets/css/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css"/>
	<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
	<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
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

<div class="container mt20 mb40">
<div class="row mt40">
	
	   <!--Table to display the Media Entry date wise-->   
	
        <div class="col-md-12 col-sm-12 col-xs-12">
               <!-- <h3 style="background-color:#d4b3c7; padding:5px;"></h3><br/>-->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped no-wrap" id="dataTable">
                        <thead>
					    <tr style="background-color:#5f146f; color:#fff">
						   <td>Sl No.</td>
						   <td>Training Centre</td>
						   <td>Programme</td>
						   <td>Course</td>
						   <td>Start Date</td>
						   <td>End Date</td>
						   <td>Duration</td>
						   <td>Interested participants</td>
						   <td>No. of participants</td>
						   <td>Level of participants</td>
						   <td>Action</td>
						</tr>
                        </thead>
				    <tbody>
					    @php $i=1; @endphp
					    @foreach($data['trainingList'] as $list)
						<tr>
						   <td>{{$i}}</td>
						   <td>{{$list->centre_name}}</td>
						   <td>{{$list->programme_name}}</td>
						   <td>{{$list->course}}</td>
						   <td>{{$list->start_date}}</td>
						   <td>{{$list->end_date}}</td>
						   <td>{{$list->no_of_days}}</td>
						   <td>
								@if(isset($data['participantCount'][$list->loc_id]))
							   	<a href="{{route('admin.training.getParticipantDetails',[Crypt::encrypt($list->loc_id),Crypt::encrypt($list->details_id)])}}" target="_blank">{{$data['participantCount'][$list->loc_id]}}</a>
								@else
									{{0}}
								@endif
						  </td>
						   @if(isset($list->participants_no))
						   <td>{{$list->participants_no}}</td>
						   @else
							<td><span class="badge" style="background: red;color:#fff"><i class="fa fa-times" aria-hidden="true"></i> Training Pending</span></td>
						   @endif
							
						   @if(isset($list->level_of_participants))
						   <td>{{$list->level_of_participants}}</td>
						   @else
							<td><span class="badge" style="background: red;color:#fff"><i class="fa fa-times" aria-hidden="true"></i> Training Pending</span></td>
						   @endif
							
						   @if($list->training_centre_id == 0)
							@if($list->is_training_conducted == 1)
								<td><a href="{{$imgUrl.$list->attendance_report}}">Attendance</a></td>
							@else
						    		<td><button class="trainingAction btn-primary" data-tid="{{$list->details_id}}"> Action</button></td>
							@endif
						   @elseif($list->is_training_conducted == 1)
							<td><a href="{{$imgUrl.$list->attendance_report}}">Attendance</a></td>
						   @else
							<td><span class="badge" style="background: blue;color:#fff"><i class="fa fa-info-circle" aria-hidden="true"></i> Training to be done at {{$list->centre_name}}</span></td>
						   @endif
						</tr>
					    @php $i++; @endphp
					    @endforeach
				    </tbody>
                    </table>
                </div>
            </div>
	
	   <!--Model box-->	
	
			<div id="trainingActionModal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div style="background-color:#6b133d;" class="modal-header bg-primary">
                             <button type="button" style="background-color:red" class="btn modal-close" data-dismiss="modal" aria-hidden="true">
						    <i class="fa fa-close"></i>
						</button>
					     <h3 class="modal-title">Training Details</h3>
                            <h4 class="modal-title" id="ins_modal_title1"></h4>
                        </div>
                        <form action="#" method="POST" id="trainingActionForm">
					    <div class="modal-body">
							     <input type="hidden" name="training_id" class="training_id" id="training_id" value=""/>
						    		   <div class="row">
										 <div class="col-md-4 col-xs-4 col-sm-4">
										   <label>Programme</label>
										   <h6 style="font-weight:bold" class="programme" id="programme"></h6>
										</div>
								   </div>
						    		   <br>
						    		   <div class="row">
										 <div class="col-md-12 col-xs-12 col-sm-12">
										   <label>Course</label>
										   <h6 style="font-weight:bold" class="course" id="course"></h6>
										</div>
								   </div> 
								   <div class="row" style="margin-top:20px;">
									  <div class="col-md-4 col-sm-6 col-xs-12">
										<div class="form-group">
											  <label>No. of Paticipants<strong>*</strong></label>
											 <input type="number" class="form-control" name="no_of_participants"/>
										</div>
									   </div>
									   <div class="col-md-4 col-sm-6 col-xs-12">
										<div class="form-group">
											  <label>Level of Paticipants<strong>*</strong></label>
											 <input type="text" class="form-control" name="level_of_participants"/>
										</div>
									   </div>
									   <div class="col-md-6 col-sm-6 col-xs-12">
										   <div class="form-group">
											<label>Attendence Report (<strong>*xls/xlsx format/max-size:400KB</strong>)</label>
											<input type="file" class="form-control" name="report_attachment"/>
										   </div>
									  </div>
								    </div>
					    	</div>
						    <div class="modal-footer">
							   <button type="submit" class="btn btn-primary pull-right">
								   <i class="fa fa-paper-plane" aria-hidden="true"></i>
								  Submit
							   </button>
						    </div>
                        		</form>
                    	</div>
				 </div>
			 </div>	
	
	   <!---->
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
	    
	    $('.trainingAction').on('click', function(e){
                    e.preventDefault();
                    var id= $(this).data('tid');
                    $('.training_id').val('');
                    $('.training_id').val(id);
		          $('#trainingActionForm')[0].reset();
		 		$.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "{{route('admin.training.getTrainingData')}}",
                        dataType: "json",
                        data: {id:id},
                        cache: false,
                        success: function (data) {
                            if (data.msgType == true) {
                                    $('#trainingActionModal').modal('show');
						      $('.programme').text(data.data.trainingData.programme_name);
						      $('.course').text(data.data.trainingData.course);
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
	    
	    $("#trainingActionForm").validate({
            rules: {
                no_of_participants: {
                    required: true,
                },
			  level_of_participants:{
				required: true,
			  },
			  report_attachment:{
				required: true,
				extension: "xls|xlsx"
			  },
            },
        });
	    
	    $('#trainingActionForm').on('submit', function(e){
			  e.preventDefault();

			  if($('#trainingActionForm').valid()){

				 $('.page-loader-wrapper').fadeIn();

				 $('.form_errors').remove();

				 $.ajax({
					headers: {
					    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					type: "POST",
					url: '{{route('admin.training.setTrainingAction')}}',
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
	    
    </script>
@endsection