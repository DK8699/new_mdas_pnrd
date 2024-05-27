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
            <form action="{{route('admin.Grievance.media.action_panel')}}" method="post">
                @csrf
                <div class="col-md-4 col-sm-6 col-xs-6">
                      <div class="form-group">
                           <label> SELECT DATE <strong>*</strong></label>
                           <input type="text" class="form-control" name="search_date" id="search_date" value="{{$data['date']}}" style="height:35px"/>
                       </div>
			</div>
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group mt20">
                        <button type="submit" class="btn btn-primary btn-save btn-sm">
                            <i class="fa fa-search"></i>
                            Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
	
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
					   <td>Report Confirmation</td>
                            <td>Action</td>
                        </tr>
                        </thead>
                        <tbody>
					    @php $i=1; @endphp
					    @foreach($data['yearWiseMediaData'] as $list)
                            <tr>
                               <td>{{$i}}</td>
                               <td>{{$list->name}}</td>
                               <td>{{$list->name_of_media_publisher}}</td>
                               <td>{{$list->description}}</td>
							 @if(isset($list->supporting_doc_path))
							 	<td><a target="_blank" href="{{$imgUrl.$list->supporting_doc_path}}">Link</a></td>
							 @else
							 	<td>No document uploaded</td>
							 @endif
						   	@if(isset($list->supporting_link))
						 		<td><a target="_blank" href="{{$list->supporting_link}}">Link</a></td>
						   	@else
						   		<td>No supporting link</td>
						   	@endif
                               <td>{{$list->level_name}}</td>
						   <td><span style="color:#ef3308;font-weight:600;">District:</span> {{$list->district_name}}<br>
							<span style="color:#ef3308;font-weight:600;">Block:</span> {{$list->block_name}}<br>
							<span style="color:#ef3308;font-weight:600;">G.P/VCDC/VDC:</span> {{$list->gram_panchayat_name}}
						 </td>
						   
						   
						 @if(isset($list->action_file_path))
								<td>
									<a href="{{route('grievance.Media.Action.report.view', [encrypt($list->id)])}}" 
												 target="_blank" class="btn btn-success btn-xs" style ="padding:1px 10px"id="attachment_view_link1">
												  <i class="fa fa-check"></i>
												  View
									</a>
								</td>
							@else
								<td>No file attached</td>
						    @endif
						    
						    @if(isset($list->report_file_path))
						    <td>
						    		<a href="{{route('grievance.Media.Reply.view', [encrypt($list->id)])}}" 
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
						 <td>
						   <label class="switch">
                                            <input class="toggle-class" data-id="{{$list->media_code}}" type="checkbox" {{ $list->report_file_path ? 'checked' : '' }}>
                                            <span class="slider round">
                                            @if ($list->report_file_path)
                                                    {{--<i class="fa fa-warning" style="color: red;font-size: 14px;padding: 10px"></i>--}}
                                                @else <i class="fa fa-lock" style="color: #0be50b;font-size: 18px;padding: 10px"></i>
                                                @endif
                                        </span>
                                        </label>
						 </td>
                               <td style="text-align:center">
							 @if($list->sent_status == 1)
							 	<h6>Sent:<i style="color:green"class="fa fa-2x fa-check" aria-hidden="true"></i></h6>
							 	@if($list->action_taken_status == 0)
							 		<h6>Action Taken:<i style="color:red" class="fa fa-times fa-2x"></i></h6>
								@else
									<h6>Action Taken:<i style="color:green"class="fa fa-2x fa-check" aria-hidden="true"></i></h6>
							 	@endif
							 @else
							 <a href="" class="setAction" data-mid="{{$list->id}}"><i class="fa fa-tasks fa-2x" aria-hidden="true"></i></a>
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
												    @foreach($data['actionLevel'] as $li)
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
												    @foreach($data['actionTakenBy'] as $li_a)
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


        <div id="date_wise_media_report"></div>
        

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
	    $('#date_wise_media_report').show();
	    
	   $('#search_date').Zebra_DatePicker({
	    });
	    
	    
	   $('.toggle-class').change(function(e) {
            e.preventDefault();

            var checkbox=$(this);

            var status = $(this).prop('checked') == true ? 1 : 0;
			
            var griev_code = $(this).data('id');
            if (confirm('Are you sure you want to change the status?')) {
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{route('admin.Grievance.reportStatus')}}',
                    data: {'status': status, 'griev_code': griev_code},
                    success: function(data){
                        if (data.msgType == true) {
                            swal("Success", data.msg, "success")
                                .then((value) => {
				 		checkbox.prop('checked', false);
						location.reload();
				 		
                        });
				    }
				     else{
                              swal("Error", data.msg, 'error')
					    .then((value) => {
				 		checkbox.prop('checked', false);
						location.reload();
				 		
                        });
                        }

                    },
                    error: function(data){
                        if(checkbox.prop("checked") == true){
                            checkbox.prop('checked', false);
                        } else if(checkbox.prop("checked") == false){
                            checkbox.prop('checked', true);
                        }
                    }
                });
            } else{
                if(checkbox.prop("checked") == true){
                    checkbox.prop('checked', false);
                } else if(checkbox.prop("checked") == false){
                    checkbox.prop('checked', true);
                }
            }



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
	     
	    
	    /*$('#date_select').on('submit', function(e){
            e.preventDefault();

            var id= $('#search_date').val();
		    
		  //alert(id);
		    
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "{{route('admin.Grievance.media.action_list')}}",
                contentType: false,
                data: "id="+id,
                cache: false,
                processData: false,
                success: function (data) {
                    $('#date_wise_media_report').html(data)
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                },
                complete: function (data) {
                    $('.page-loader-wrapper').fadeOut();
                }
            });
        });
	    */
	    
    </script>
@endsection