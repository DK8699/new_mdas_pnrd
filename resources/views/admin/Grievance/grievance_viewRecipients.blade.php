@php
    $page_title="Grievance System";
@endphp

@extends('layouts.app_admin_griev')

@section('custom_css')
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
 
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
            background-color: #fff;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #66bb6a;
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
	strong{
		color:red;
	}
</style>
@endsection

@section('content')
    <!-- <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Home</a></li>
            {{--<li class="active"></li>--}}
        </ol>
    </div> -->
    <div class="container mb40">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12" style="border-bottom:3px solid #7c8487;margin:25px 0px 15px 0px;">
                <h3 style="text-transform: uppercase;font-weight:bold;">
                    List of All Recipients
                </h3>
            </div>
        </div>
        {{-----------------------DATA TABLE-----------------------------------------}}
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="dataTable1">
                        <thead>
                            <tr style="background-color:#5f146f; color:#fff">
                                <th>SL</th>
                                <th>Recipient Name</th>
                                <th>Mobile Number</th>
                                <th>Email</th>
                                <th>Designation</th>
                                <th>District</th>
                                <th>Block</th>
                                <th>Gram Panchayat</th>
                                <th>Head</th>
                                <th>Action</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($grievance_recipients as $recipients)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{$recipients->f_name}} {{$recipients->m_name}} {{$recipients->l_name}}</td>
                                <td>{{$recipients->mobile_no}}</td>
                                <td>{{$recipients->email_id}}</td>
                                <td>{{$recipients->designation}}</td>
                                <td>{{$recipients->district_name}}</td>
                                <td>{{$recipients->block_name}}</td>
                                <td>{{$recipients->gram_panchayat_name}}</td>
                                <td>{{$recipients->submitted_by}}</td>
                                <td class="text-center">
                                    <a href="#" class="editRecipient" type="button" data-rid="{{$recipients->id}}" title="Edit Recipients Details">
                                        <i class="fa fa-edit" style="color:#3a9fff; font-size:20px;"></i>
                                    </a>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input class="toggle-class" data-id="{{$recipients->id}}" type="checkbox" {{ $recipients->is_active ? 'checked' : '' }} >
                                        <span class="slider round">
                                        </span>
                                    </label>
                                </td>
                            </tr>
                        @php $i++; @endphp
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div><br /><br />
        {{------------------DATA TABLE ENDED-----------------------------------------}}
    

    <!-------------------------------------Edit Bidder----------------------------------------------------------------->
    <div class="modal fade" id="editRecipient">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header" style = "background-color:#4d112bfc!important; color:#fff">
                    <button type="button" class="btn bg-red modal-close pull-right" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close" style="color:#fff"></i>
                    </button>
                    <h3 class="modal-title">Edit Recipient Details</h3>
                    <p>Fields with asterisk (<strong>*</strong>) are required.</p>
                </div>
                <form id="editRecipientForm" action="#" method="POST">
			 <div class="row">
				<div class="modal-body">	 
					<input type="hidden" id="rid" name="rid" value=""/>
							  <div class="col-md-4 col-sm-12 col-xs-12">
									 <div class="form-group">
										 <label>First Name <strong>*</strong> </label>
										 <input type="text" class="form-control" name="ed_f_name" id="ed_f_name" value=""/>
									  </div>
							  </div>
							  <div class="col-md-4 col-sm-12 col-xs-12">
									 <div class="form-group">
										 <label>Middle Name </label>
										 <input type="text" class="form-control" name="ed_m_name" id="ed_m_name" value=""/>
									  </div>
							  </div>
							  <div class="col-md-4 col-sm-12 col-xs-12">
									 <div class="form-group">
										 <label>Last Name <strong>*</strong></label>
										 <input type="text" class="form-control" name="ed_l_name" id="ed_l_name" value=""/>
									  </div>
							  </div>
							  <div class="col-md-4 col-sm-12 col-xs-12">
									 <div class="form-group">
										 <label>Mobile Number <strong>*</strong></label>
										 <input type="text" class="form-control" name="ed_mobile_no" id="ed_mobile_no"/>
									  </div>
							  </div>
							  <div class="col-md-4 col-sm-12 col-xs-12">
									 <div class="form-group">
										 <label>Email Id <strong>*</strong></label>
										 <input type="text" class="form-control" name="ed_email_id" id="ed_email_id"/>
									  </div>
							  </div>
							   <div class="col-md-4 col-sm-12 col-xs-12">
									 <div class="form-group">
										 <label>Designation <strong>*</strong></label>
										 <input type="text" class="form-control" name="ed_designation" id="ed_designation"/>
									  </div>
							  </div>
							  <div class="col-md-4 col-sm-4 col-xs-12">
									<div class="form-group">
									    <label>Under <strong>*</strong></label>
									    <select class="form-control" name="ed_submitted_to" id="ed_submitted_to">
										   <option value="">---Select---</option>
										    @foreach($actionTakenBy as $value)
										    <option value="{{$value->id}}">{{$value->submitted_by}}</option>
										    @endforeach
									    </select>
									</div>
							  </div>
							  <div class="col-md-4 col-sm-4 col-xs-12 ed_ditrict_div" style="display:none">
									<div class="form-group">
									    <label>District <strong>*</strong></label>
									    <select class="form-control" name="ed_district_id" id="ed_district_id">
										   <option value="">---Select---</option>
										    @foreach($district as $value)
										    <option value="{{$value->id}}">{{$value->district_name}}</option>
										    @endforeach
									    </select>
									</div>
							  </div>
							  <div class="col-md-4 col-sm-4 col-xs-12 ed_block_div" style="display:none">
									<div class="form-group">
									    <label>Block<strong>*</strong></label>
									    <select class="form-control" name="ed_block_id" id="ed_block_id">
									    </select>
									</div>
							  </div>
							  <div class="col-md-4 col-sm-4 col-xs-12 ed_gp_div" style="display:none">
									<div class="form-group">
									    <label>GP/VCDC/VDC <strong>*</strong></label>
									    <select class="form-control" name="ed_gp_id" id="ed_gp_id">
									    </select>
									</div>
							  </div>
					</div>
				 </div>
					<div class="modal-footer">
						   <button type="submit" class="btn btn-primary pull-right">
							   <i class="fa fa-paper-plane" aria-hidden="true"></i>
							  Edit
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
    <script type="text/javascript" src="{{ asset('mdas_assets/mdbootstrap/js/mdb.min.js') }}"></script>
    <script type="text/javascript">
            
	    
	   $('#dataTable1').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend:    'excelHtml5',
                        title: 	   'Recipient List',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    },
                ],
           });
	    
	    //Change Status of Recipient

        $('.toggle-class').on('change', function(e) {
            
            var checkbox=$(this);

            var is_active = $(this).prop('checked') == true ? 1 : 0;
            var id = $(this).data('id');
            if (confirm('Are you sure you want to change the status of the Recipient?')) {
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{route('admin.Grievance.statusRecipient')}}',
                    data: {'is_active': is_active, 'id': id},
                    success: function(data){
                        console.log(data.success);
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
	    
         //-------------ON DISTRICT CHANGE-----------------------

        $('#ed_district_id').on('change', function(e){

            e.preventDefault();
		   

            $('#ed_block_id').empty();
            $('#ed_gp_id').empty();

            var district_id= $('#ed_district_id').val();
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

                            $('#ed_block_id')
                                .append($("<option></option>")
                                    .attr("value", '')
                                    .text('--Select--'));

                            $.each(data.data, function(key, value) {
                                $('#ed_block_id')
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

        $('#ed_block_id').on('change', function(e){

            e.preventDefault();
            
            $('#ed_gp_id').empty();

            var block_id= $('#ed_block_id').val();
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
                            $('#ed_gp_id')
                                .append($("<option></option>")
                                    .attr("value", '')
                                    .text('--Select--'));

                            $.each(data.data, function(key, value) {
                                $('#ed_gp_id')
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
	   
        /*------------------------------------ GET RECIPIENT INFO -------------------------------------------------------*/

        $('.editRecipient').on('click', function(e){
            e.preventDefault();

            var rid= $(this).data('rid');
            
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('admin.Grievance.getRecipientsByid')}}',
                dataType: "json",
                data: {rid: rid},
                cache: false,
                success: function (data) {
                    if (data.msgType == true) {
                        $('#rid').val(data.data.RecipientData.id);
                        $('#ed_f_name').val(data.data.RecipientData.f_name);
                        $('#ed_m_name').val(data.data.RecipientData.m_name);
                        $('#ed_l_name').val(data.data.RecipientData.l_name);
                        $('#ed_mobile_no').val(data.data.RecipientData.mobile_no);
                        $('#ed_email_id').val(data.data.RecipientData.email_id);
                        $('#ed_designation').val(data.data.RecipientData.designation);
                        $('#ed_district_id').val(data.data.RecipientData.district_id);
					$('#ed_block_id').append($("<option></option>")
								 .attr("value", data.data.RecipientData.block_id)
									.text(data.data.RecipientData.block_name));
					$('#ed_gp_id').append($("<option></option>")
								 .attr("value", data.data.RecipientData.gp_id)
									.text(data.data.RecipientData.gram_panchayat_name));
					
                        if( data.data.RecipientData.submitted_to == 1 || data.data.RecipientData.submitted_to == 2) {
					      $('.ed_ditrict_div').hide();
						 $('.ed_block_div').hide();
						 $('.ed_gp_div').hide();
                        }
                        else if( data.data.RecipientData.submitted_to == 3 || data.data.RecipientData.submitted_to == 4 ||
                            data.data.RecipientData.submitted_to == 5) {
					      $('.ed_ditrict_div').show();
						 $('.ed_block_div').hide();
						 $('.ed_gp_div').hide();
                        }
					else if( data.data.RecipientData.submitted_to == 6) {
						 $('.ed_ditrict_div').show();
						 $('.ed_block_div').show();
						 $('.ed_gp_div').hide();
                        }
					else{
						 $('.ed_ditrict_div').show();
						 $('.ed_block_div').show();
						 $('.ed_gp_div').show();
					}

                        $('#ed_submitted_to').val(data.data.RecipientData.submitted_to);
					
                        $('#editRecipient').modal('show');

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
            })});
        
        $("#editRecipientForm").validate({
            rules: {
                ed_f_name: {
                    required: true,
                    blank:true,
                    maxlength:100
                },
                ed_m_name: {
                    maxlength:100
                },
                ed_l_name: {
                    required: true,
                    blank:true,
                    maxlength:100
                },
               
                ed_mobile_no: {
                    required: true,
                    digits:true
                },
                ed_email_id: {
                    email:true,
                    maxlength:150
                },
               ed_designation:{
                   required: true,
                   maxlength:150
               },
               ed_district_id:{
                    digits:true
                },
			  ed_block_id:{
                    digits:true
                },
			  ed_gp_id:{
                    digits:true
                },
               ed_submitted_to:{
                    required: true,
                    digits:true
                },
            },
        });

        /*-----------------------------------  EDIT  -----------------------------------------------------------------*/

        $('#editRecipientForm').on('submit', function(e){
            e.preventDefault();

            var rid= $(this).data('rid');
            if($('#editRecipientForm').valid()){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.Grievance.editRecipient')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {
                            swal("Success", data.msg, "success")
                                .then((value) => {
                                $('#editRecipient').modal('hide');
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