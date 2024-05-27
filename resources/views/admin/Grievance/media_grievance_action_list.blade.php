<div class="row mt40">
	
	   <!--Table to display the Media Entry date wise-->   
	
        <div class="col-md-12 col-sm-12 col-xs-12">
               <!-- <h3 style="background-color:#d4b3c7; padding:5px;"></h3><br/>-->
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                        <tr style="background-color:#5f146f; color:#fff">
                            <td>SL</td>
                            <td>Media</td>
                            <td>Publisher</td>
                            <td>Nature of Grievance</td>
                            <td>Supporting Document</td>
                            <td>Supporting Link</td>
                            <td>Action to be taken by</td>
                            <td>Location</td>
					   <td>Order Copy</td>
					   <td>Report Copy</td>
                            <td>Action</td>
                        </tr>
                        </thead>
                        <tbody>
					    @php $i=1; @endphp
					    @foreach($yearWiseMediaData as $data)
                            <tr>
                               <td>{{$i}}</td>
                               <td>{{$data->name}}</td>
                               <td>{{$data->name_of_media_publisher}}</td>
                               <td>{{$data->description}}</td>
							 @if(isset($data->supporting_doc_path))
							 	<td><a target="_blank" href="{{$imgUrl.$data->supporting_doc_path}}">Link</a></td>
							 @else
							 	<td>No document uploaded</td>
							 @endif
						   	@if(isset($data->supporting_link))
						 		<td><a target="_blank" href="{{$data->supporting_link}}">Link</a></td>
						   	@else
						   		<td>No supporting link</td>
						   	@endif
                               <td>{{$data->level_name}}</td>
						   <td><span style="color:#ef3308;font-weight:600;">District:</span> {{$data->district_name}}<br>
							<span style="color:#ef3308;font-weight:600;">Block:</span> {{$data->block_name}}<br>
							<span style="color:#ef3308;font-weight:600;">G.P/VCDC/VDC:</span> {{$data->gram_panchayat_name}}
						 </td>
						   
						   
						 @if(isset($data->action_file_path))
								<td>
									<a href="{{route('grievance.Media.Action.report.view', [encrypt($data->id)])}}" 
												 target="_blank" class="btn btn-success btn-xs" style ="padding:1px 10px"id="attachment_view_link1">
												  <i class="fa fa-check"></i>
												  View
									</a>
								</td>
							@else
								<td>No file attached</td>
						    @endif
						    
						    @if(isset($data->report_file_path))
						    <td>
						    		<a href="{{route('grievance.Media.Reply.view', [encrypt($data->id)])}}" 
												 target="_blank" class="btn btn-success btn-xs" style ="padding:1px 10px"id="attachment_view_link1">
												  <i class="fa fa-check"></i>
												  View
								</a>
						    </td>
						    @else
						    <td>
							    No file attached
						    </td>
						    @endif  
						   
                               <td style="text-align:center">
							 @if($data->sent_status == 1)
							 	<h6>Sent:<i style="color:green"class="fa fa-2x fa-check" aria-hidden="true"></i></h6>
							 	@if($data->action_taken_status == 0)
							 		<h6>Action Taken:<i style="color:red" class="fa fa-times fa-2x"></i></h6>
							 	@else
								 	<h6>Action Taken:<i style="color:green"class="fa fa-2x fa-check" aria-hidden="true"></i></h6>
							 	@endif
							 @else
							 <a href="" class="setAction" data-mid="{{$data->id}}"><i class="fa fa-tasks fa-2x" aria-hidden="true"></i></a>
							 @endif
						 </td>
                            </tr>
					    @php $i++; @endphp
					    @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
	
	   <!--Model box-->	
	
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
								   <label>District</label>
								   <h6 class="district" id="district"></h6>
								</div>
								<div class="col-md-4 col-xs-4 col-sm-4">
								    <label>Block</label>
								   <h6 class="block" id="block"></h6>
								</div>
								<div class="col-md-4 col-xs-4 col-sm-4">
								    <label>G.P./VCDC/VDC</label>
								   <h6 class="gp" id="gp"></h6>
								</div>
								<div class="col-md-4 col-xs-4 col-sm-4">
								   <label>Media</label>
								   <h6 class="media_name" id="media_name"></h6>
								</div>
								<div class="col-md-8 col-xs-8 col-sm-8">
								    <label>Publisher</label>
								   <h6 class="publisher" id="publisher"></h6>
								</div>
								<div class="col-md-12 col-xs-12 col-sm-12">
								    <label>Nature of Grievance</label>
								   <h6 class="nature_of_griev" id="nature_of_griev"></h6>
								</div>
						   </div> 
							     <input type="hidden" name="m_e_id" class="m_e_id" id="m_e_id" value=""/>
								    <div class="row" style="margin-top:20px;">
									  <div class="col-md-6 col-sm-6 col-xs-12">
											<div class="form-group">
											    <label>Action to be taken at<strong>*</strong></label>
											    <select class="form-control" name="action_level" id="action_level">
												    <option value="">---Select---</option>
												    @foreach($actionLevel as $li)
												    <option value="{{$li->id}}">{{$li->level_name}}</option>
												    @endforeach
											    </select>
											</div>
									  </div>
									  <div class="col-md-6 col-sm-6 col-xs-12">
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
									  <div class="col-md-6 col-sm-6 col-xs-12 attachDiv" style="display:none">
											<label>Order Copy (<strong>*pdf format/max-size:400KB</strong>)</label>
											<input type="file" class="form-control" name="attachment"/>
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
	   

<script type="application/javascript">
	
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
	
	
	   $("#action_level").change(function(){
                var selectedRole = $(this).children("option:selected").val();
			  
                if(selectedRole == 1)
                    {
				    $('.attachDiv').show();
                        $('.reportDiv').show();
                    }
			  else{
				  $('.reportDiv').hide();
				  $('.attachDiv').show();
			  }

            });
	
	 $('.setAction').on('click', function(e){
                    e.preventDefault();
                    var id= $(this).data('mid');
				
				  
                   /* $('#ins_modal_title1').text('');
                    $('#ins_modal_title1').text(ins);*/
                    $('.m_e_id').val('');
                    $('.m_e_id').val(id);

                    $('#setActionForm')[0].reset();
		 
		 
		 		$.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "{{route('admin.Grievance.media.getMediaData')}}",
                        dataType: "json",
                        data: {id:id},
                        cache: false,
                        success: function (data) {
                            if (data.msgType == true) {
                                    $('#setActionModal').modal('show');
						   	 $('#district').text(data.data.district_name);
						   	 $('#block').text(data.data.block_name);
						   	 $('#gp').text(data.data.gram_panchayat_name);
						   	 $('#media_name').text(data.data.name);
						   	 $('#publisher').text(data.data.name_of_media_publisher);
						   	 $('#nature_of_griev').text(data.data.description);
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
	
	 $("#setActionForm").validate({
            rules: {
                action_level: {
                    required: true,
                },
			  action_taken_by: {
				  required: true,
			  },
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
                    url: '{{route('admin.Grievance.media.action')}}',
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