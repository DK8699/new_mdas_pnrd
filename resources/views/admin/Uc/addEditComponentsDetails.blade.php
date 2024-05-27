										<div class="row">
											<div class="col-md-12">
												<div id="resultset" class="row" style="border-bottom:3px solid #7c8487;margin:55px -15px 25px -15px;">
													<div class="col-md-12 col-sm-12 col-xs-12" style="margin:25px 0px 15px 0px;">
														<h2 style="text-transform: uppercase;font-weight:bold;">
															Add / Edit Component-Wise Expenditure
															<a href="{{ route('admin.Uc.viewEntityComponents', Crypt::encrypt($entity_id)) }}" target="_blank" class="btn waves-effect peach-gradient btn-sm" style="top:-10px;font-weight:bold;font-size:12pt;padding:5px 10px;width:255px;text-align:left;float:right"><i class="fa fa-eye"></i>&nbsp;&nbsp;View Component Details</a>
														</h2>
													</div>
												</div>
												<div class="portlet-body table table-responsive">
													<br />
													<div style="position:relative;width:100%;brder:1px solid black;">
														<?php
															$components_entity_name = DB::select('SELECT * FROM uc_components_entities WHERE id = ?', [$entity_id]);
														?>
														<center>
															<b><div style="font-size:25pt;margin:20px 0px;width:100%;">{{ $components_entity_name[0]->short_entity_name }}</div></b>
														</center>
														<?php
															$uc_gfr = DB::select('SELECT * FROM uc_gfrs WHERE entity_id = ?', [$entity_id])
														?>
														@if(empty($uc_gfr))
														<div style="position:absolute;top:0;right:0;top:-25px;"> 
															<p id="viewUc_{{$entity_id}}" style="display:none;right:0;padding-top:15px;">
																<a href="" target="_blank" class="btn btn-success btn-xs" id="uc_view_link_{{$entity_id}}">
																	<i class="fa fa-check"></i>
																	View UC
																</a>
																<button type="button" class="btn btn-warning btn-xs edit_uc" data-entity_id="{{$entity_id}}" style="padding:7px 14px;height:36px;">
																	<i class="fa fa-edit"></i>
																	Edit
																</button>
															</p>
															<form action="" method="POST" id="ucUploadForm" style="right:0;">
																<input type="hidden" name="entity_id" value="{{$entity_id}}"/>
																<input type="file" class="form-control" name="attachment" style="width:240px;border:2px solid #747474;margin:3px;padding:5.5px 5.5px;"/>
																<button type="submit" class="btn btn-primary btn-xs" id="uc_upload" style="margin-top: 4px;">
																	<i class="fa fa-upload"></i>
																	Upload UC
																</button>  
																<button type="button" class="btn btn-danger btn-xs cancel_uc_upload" id="cancel_uc_upload_{{$entity_id}}" data-entity_id="{{$entity_id}}" style="margin-top: 4px; display:none;">
																	<i class="fa fa-times" aria-hidden="true"></i>
																	Cancel
																</button>
															</form>
														</div>
														@else
														<div style="position:absolute;top:0;right:0;top:-25px;"> 
															<p id="viewUc1_{{$entity_id}}" style="right:0;padding-top:15px;">
																<a href="{{route('admin.uc.gfr.view', [Crypt::encrypt($entity_id)])}}" target="_blank" class="btn btn-success btn-xs" id="uc_view_link1_{{$entity_id}}">
																	<i class="fa fa-check"></i>
																	View UC
																</a>
																<button type="button" class="btn btn-warning btn-xs edit_uc1" data-entity_id="{{$entity_id}}" style="padding:7px 14px;height:36px;">
																	<i class="fa fa-edit"></i>
																	Edit
																</button>
															</p>
															<form action="" method="POST" id="ucUploadForm1" style="display:none;right:0;">
																<input type="hidden" name="entity_id" value="{{$entity_id}}"/>
																<input type="file" class="form-control" name="attachment" style="width:240px;border:2px solid #747474;margin:3px;padding:5.5px 5.5px;"/>
																<button type="submit" class="btn btn-primary btn-xs" id="uc_upload1" style="margin-top: 4px;">
																	<i class="fa fa-upload"></i>
																	Upload UC
																</button>  
																<button type="button" class="btn btn-danger btn-xs cancel_uc_upload1" id="cancel_uc_upload1_{{$entity_id}}" data-entity_id="{{$entity_id}}" style="margin-top: 4px; display:none;">
																	<i class="fa fa-times" aria-hidden="true"></i>
																	Cancel
																</button>
															</form>
														</div>
														@endif
														<?php
															$serial = "A";
															foreach($components_headers as $values) 
															{
																echo '<b style="font-size:14pt;color:grey;text-transform:uppercase;">'.$serial++.'. '.$values->header_name.'</b><br /><br />';
														?>
													</div>
													<form id="form_{{ $values->id }}" action="saveEntityComponents" method="POST" enctype="multipart/form-data" >
														{{ csrf_field() }}
														<input type="hidden" name="entity" value="{{ Crypt::encrypt($entity_id) }}" class="form-control" required />
														<input type="hidden" name="components_header" value="{{ Crypt::encrypt($values->id) }}" class="form-control" required />
														<table id="table-border" class="table table-bordered table-hover table-checkable order-column" >
														<style>
															#table-border th, #table-border td{
																border: 2px solid #83858f;
															}
															#table-border th{
																vertical-align:bottom;
																text-align:center;
															}
															.table-bordered>thead>tr>th {
																background-color: #384059;
																color: #ffffff;
															}
														</style>
															<thead>
																<tr>
																	<th rowspan="2" width="55px"> Sl. No. </th>
																	<th rowspan="2" width="285px"> Name of the Component </th>
																	<th colspan="2" width="85px"> Physical Target </th>
																	<th colspan="2" width="85px"> Physical Achievement </th>
																	<th rowspan="2" width="85px"> Opening Balance (E)</th>
																	<th rowspan="2" width="85px"> GoA Fund Received (F)</th>
																	<th rowspan="2" width="85px"> Other Receipts (G)</th>
																	<th rowspan="2" width="85px"> Total Amount (E+F+G)</th>
																	<th rowspan="2" width="85px"> Expenditure (H)</th>
																	<th rowspan="2" width="85px"> UC Submitted (I)</th>
																	<th rowspan="2" width="85px"> UC Balance (Total Amt. - I)</th>
																	<th rowspan="2" width="85px"> Closing Balance (Total Amt. - H)</th>
																</tr>
																<tr>
																	<th width="85px"> No. of Course(s) (A)</th>
																	<th width="85px"> No. of Person(s) (B)</th>
																	<th width="85px"> No. of Course(s) (C)</th>
																	<th width="85px"> No. of Person(s) (D)</th>
																</tr>
															</thead>
															<tbody id="tab_create_{{ $values->id }}">
																<?php
																$table_row = 0;
																$header_exists = 0;
																$p = 0;
																foreach($components_details as $comp)
																{
																	if($values->id == $comp->component_header_id)
																	{
																		$p = 1;
																		break;
																	}
																	else
																	{
																		$p = 0;
																		continue;
																	}
																}
																if( $p == 0 )
																{
																	$sl_table_row = ++$table_row;
																?>
																	<tr>
																		<td>
																			<div class="checkbox-animated">
																				<input id="checkbox_{{ $values->id.$sl_table_row }}" name="id[]" type="checkbox" class="checkbox" value="1" style="height:20px;" >
																				<label for="checkbox_{{ $values->id.$sl_table_row }}">
																					<span class="check"></span>
																					<span class="box"></span>
																					&nbsp;{{ $sl_table_row }}&nbsp;
																				</label>																			
																			</div>
																		</td>
																		<td>
																			<select id="components{{ $values->id.$table_row }}" name="components[]" class="selectpicker form-control select-margin" style="width:500px;" data-style="btn-info" data-live-search="true" required>
																				<option value="">Select</option>
																				<?php
																				foreach( $components as $values1 )
																				{
																					if( $values->id == $values1->component_header_id )
																					{
																				?>
																					<option value="{{ Crypt::encrypt($values1->id) }}">{{ $values1->component_name }}</option>
																				<?php
																					}
																				}
																				?>
																			</select>
																		</td>
																		<td><input id="pt_noc_{{ $values->id.$sl_table_row }}" type="text" name="pt_noc[]" onblur="isNumber('pt_noc_{{ $values->id.$table_row }}')" value="0" placeholder="" class="form-control" required /></td>
																		<td><input id="pt_nop_{{ $values->id.$sl_table_row }}" type="text" name="pt_nop[]" onblur="isNumber('pt_nop_{{ $values->id.$table_row }}')" value="0" placeholder="" class="form-control" required /></td>
																		<td><input id="pa_noc_{{ $values->id.$sl_table_row }}" type="text" name="pa_noc[]" onblur="isNumber('pa_noc_{{ $values->id.$table_row }}')" value="0" placeholder="" class="form-control" required /></td>
																		<td><input id="pa_nop_{{ $values->id.$sl_table_row }}" type="text" name="pa_nop[]" onblur="isNumber('pa_nop_{{ $values->id.$table_row }}')" value="0" placeholder="" class="form-control" required /></td>
																		<td><input id="opening_balance_{{ $values->id.$table_row }}" type="text" name="ob[]" placeholder="" class="form-control" onblur="calculate_total('opening_balance_', '{{ $values->id.$table_row }}')" value="0.000" maxlength="10" required /></td>
																		<td><input id="goa_fund_received_{{ $values->id.$table_row }}" type="text" name="goa_fund_received[]" placeholder="" onblur="calculate_total('goa_fund_received_', '{{ $values->id.$table_row }}')" class="form-control" value="0.000" maxlength="10" required /></td>
																		<td><input id="other_receipts_{{ $values->id.$table_row }}" type="text" name="other_receipts[]" placeholder="" onblur="calculate_total('other_receipts_', '{{ $values->id.$table_row }}')" class="form-control" value="0.000" maxlength="10" required /></td>
																		<td style="padding-top:15.5px;text-align:center;color:#0061f3;"><div id="create_total_{{ $values->id.$table_row }}">0.000</div></td>
																		<td><input id="expenditure_{{ $values->id.$table_row }}" type="text" name="expenditure[]" placeholder="" onblur="calculate_closing_balance('{{ $values->id.$table_row }}')" class="form-control" value="0.000" maxlength="10" required /></td>
																		<td><input id="UC_Submited_{{ $values->id.$sl_table_row }}" type="text" name="uc_submitted[]" onblur="calculate_uc_balance('{{ $values->id.$table_row }}')" value="0.000" placeholder="" class="form-control" required /></td>
																		<td style="padding-top:15.5px;text-align:center;color:#0061f3;"><div id="UC_Balance_{{ $values->id.$sl_table_row }}">0.000</div></td>
																		<td style="padding-top:15.5px;text-align:center;color:#0061f3;"><div id="Closing_Balance_{{ $values->id.$sl_table_row }}">0.000</div></td>
																	</tr>
																<?php
																} 
																else
																{
																	foreach($components_details as $val)
																	{
																		if($values->id == $val->component_header_id)
																		{
																			$sl_table_row = ++$table_row;
																?>
																	<tr>
																		<td>
																			<div class="checkbox-animated">
																				<input id="checkbox_{{ $values->id.$sl_table_row }}" name="id[]" type="checkbox" class="checkbox" value="1" >
																				<label for="checkbox_{{ $values->id.$sl_table_row }}">
																					<span class="check"></span>
																					<span class="box"></span>
																					&nbsp;{{ $sl_table_row }}&nbsp;
																				</label>
																			</div>
																		</td>
																		<td>
																			<select id="components{{ $values->id.$table_row }}" name="components[]" class="selectpicker form-control select-margin" data-style="btn-info" title="Select" data-live-search="true" required>
																				<option value="">Select</option>
																				<?php
																				foreach($components as $values1)
																				{
																					if( $values->id == $values1->component_header_id )
																					{
																				?>
																					<option value="{{ Crypt::encrypt($values1->id) }}" <?php if($values1->id == $val->component_id ) echo "selected";?>>{{ $values1->component_name }}</option>
																				<?php
																					}
																				}
																				?>
																			</select>
																		</td>
																		<td><input id="pt_noc_{{ $values->id.$sl_table_row }}" type="text" name="pt_noc[]" onblur="isNumber('pt_noc_{{ $values->id.$table_row }}')" value="<?php echo $val->pt_noc; ?>" placeholder="" class="form-control" required /></td>
																		<td><input id="pt_nop_{{ $values->id.$sl_table_row }}" type="text" name="pt_nop[]" onblur="isNumber('pt_nop_{{ $values->id.$table_row }}')" value="<?php echo $val->pt_nop; ?>" placeholder="" class="form-control" required /></td>
																		<td><input id="pa_noc_{{ $values->id.$sl_table_row }}" type="text" name="pa_noc[]" onblur="isNumber('pa_noc_{{ $values->id.$table_row }}')" value="<?php echo $val->pa_noc; ?>" placeholder="" class="form-control" required /></td>
																		<td>
																			<input id="pa_nop_{{ $values->id.$sl_table_row }}" type="text" name="pa_nop[]" onblur="isNumber('pa_nop_{{ $values->id.$table_row }}')" value="<?php echo $val->pa_nop; ?>" placeholder="" class="form-control" style="float:left;" required />
																			<!-- <a onClick="load_instruction_update();" data-toggle="modal" data-target="#add_update_people" class="btn btn-default waves-effect btn-lg" style="font-weight:bold;font-size:10pt;padding:12px 5%;width:25%;height:40px;float:left;"><i class="fa fa-plus"></i></a> -->
																		</td>
																		<td><input id="opening_balance_{{ $values->id.$table_row }}" type="text" name="ob[]" placeholder="" class="form-control" onblur="calculate_total('opening_balance_', '{{ $values->id.$table_row }}')" value="<?php echo $val->ob; ?>" maxlength="10" required /></td>
																		<td><input id="goa_fund_received_{{ $values->id.$table_row }}" type="text" name="goa_fund_received[]" placeholder="" onblur="calculate_total('goa_fund_received_', '{{ $values->id.$table_row }}')" class="form-control" value="<?php echo $val->goa_fund_received; ?>" maxlength="10" required /></td>
																		<td><input id="other_receipts_{{ $values->id.$table_row }}" type="text" name="other_receipts[]" placeholder="" onblur="calculate_total('other_receipts_', '{{ $values->id.$table_row }}')" class="form-control" value="<?php echo $val->other_receipts; ?>" maxlength="10" required /></td>
																		<td style="padding-top:15.5px;text-align:center;color:#0061f3;"><div id="create_total_{{ $values->id.$table_row }}"><?php echo $total_amount = round($val->ob + $val->goa_fund_received + $val->other_receipts, 3); ?></div></td>
																		<td><input id="expenditure_{{ $values->id.$table_row }}" type="text" name="expenditure[]" placeholder="" onblur="calculate_closing_balance('{{ $values->id.$table_row }}')" class="form-control" value="<?php echo $val->expenditure; ?>" maxlength="10" required /></td>
																		<td><input id="UC_Submited_{{ $values->id.$sl_table_row }}" type="text" name="uc_submitted[]" onblur="calculate_uc_balance('{{ $values->id.$table_row }}')" value="<?php echo $val->uc_submitted; ?>" placeholder="" class="form-control" required /></td>
																		<td style="padding-top:15.5px;text-align:center;color:#0061f3;"><div id="UC_Balance_{{ $values->id.$sl_table_row }}"><?php echo round($total_amount - $val->uc_submitted, 3); ?></div></td>
																		<td style="padding-top:15.5px;text-align:center;color:#0061f3;"><div id="Closing_Balance_{{ $values->id.$sl_table_row }}"><?php echo round($total_amount - $val->expenditure, 3); ?></div></td>
																	</tr>
																<?php
																		}
																	}
																}
																?>
																<script>
																	var max_fields = 50; //maximum input boxes allowed
																	var current_{{ $values->id }} = <?php echo $table_row; ?>;

																	var x = 1; //initlal text box count
																	function add_inputs_<?php echo $values->id; ?>()
																	{
																		current_{{ $values->id }}++;
																		if(x < max_fields){ //max input box allowed
																			x++; //text box increment
																			$('#tab_create_{{ $values->id }}').append('\n\
																				<tr>\n\
																					<td>\n\
																						<div class="checkbox-animated">\n\
																							<input id="checkbox_{{ $values->id }}'+current_{{ $values->id }}+'" name="id[]" type="checkbox" class="checkbox" value="1" style="height:20px;" >\n\
																							<label for="checkbox_{{ $values->id }}'+current_{{ $values->id }}+'">\n\
																								<span class="check"></span>\n\
																								<span class="box"></span>\n\
																								&nbsp;'+current_{{ $values->id }}+'&nbsp;\n\
																							</label>\n\
																						</div>\n\
																					</td>\n\
																					<td>\n\
																						<select id="components{{ $values->id.$table_row }}" name="components[]" class="selectpicker form-control select-margin" data-style="btn-info" title="Select" data-live-search="true" required >\n\
																						<?php
																							foreach($components as $values1)
																							{
																								if( $values->id == $values1->component_header_id )
																								{
																						?>'+
																								'<option value="{{ Crypt::encrypt($values1->id) }}">{{ $values1->component_name }}</option>'+
																						'<?php
																								}
																							}
																						?>'+
																						'</select>\n\
																					</td>\n\
																					<td><input id="pt_noc_{{ $values->id }}'+current_{{ $values->id }}+'" type="text" name="pt_noc[]" onblur="isNumber(\'pt_noc_{{ $values->id }}'+current_{{ $values->id }}+'\')" value="0" placeholder="" class="form-control" required /></td>\n\
																					<td><input id="pt_nop_{{ $values->id }}'+current_{{ $values->id }}+'" type="text" name="pt_nop[]" onblur="isNumber(\'pt_nop_{{ $values->id }}'+current_{{ $values->id }}+'\')" value="0" placeholder="" class="form-control" required /></td>\n\
																					<td><input id="pa_noc_{{ $values->id }}'+current_{{ $values->id }}+'" type="text" name="pa_noc[]" onblur="isNumber(\'pa_noc_{{ $values->id }}'+current_{{ $values->id }}+'\')" value="0" placeholder="" class="form-control" required /></td>\n\
																					<td><input id="pa_nop_{{ $values->id }}'+current_{{ $values->id }}+'" type="text" name="pa_nop[]" onblur="isNumber(\'pa_nop_{{ $values->id }}'+current_{{ $values->id }}+'\')" value="0" placeholder="" class="form-control" required /></td>\n\
																					<td><input id="opening_balance_{{ $values->id }}'+current_{{ $values->id }}+'" type="text" name="ob[]" placeholder="" class="form-control" onblur="calculate_total(\'opening_balance_\', \''+{{ $values->id }}+current_{{ $values->id }}+'\')" value="0.000" maxlength="10" required /></td>\n\
																					<td><input id="goa_fund_received_{{ $values->id }}'+current_{{ $values->id }}+'" type="text" name="goa_fund_received[]" placeholder="" onblur="calculate_total(\'goa_fund_received_\', \''+{{ $values->id }}+current_{{ $values->id }}+'\')" class="form-control" value="0.000" maxlength="10" required /></td>\n\
																					<td><input id="other_receipts_{{ $values->id }}'+current_{{ $values->id }}+'" type="text" name="other_receipts[]" placeholder="" onblur="calculate_total(\'other_receipts_\', \''+{{ $values->id }}+current_{{ $values->id }}+'\')" class="form-control" value="0.000" maxlength="10" required /></td>\n\
																					<td style="padding-top:15.5px;text-align:center;color:#0061f3;"><div id="create_total_{{ $values->id }}'+current_{{ $values->id }}+'">0.000</div></td>\n\
																					<td><input id="expenditure_{{ $values->id }}'+current_{{ $values->id }}+'" type="text" name="expenditure[]" placeholder="" onblur="calculate_closing_balance('+{{ $values->id }}+current_{{ $values->id }}+')" class="form-control" value="0.000" maxlength="10" required /></td>\n\
																					<td><input id="UC_Submited_{{ $values->id }}'+current_{{ $values->id }}+'" type="text" name="uc_submitted[]" onblur="calculate_uc_balance('+{{ $values->id }}+current_{{ $values->id }}+')" value="0.000" placeholder="" class="form-control" required /></td>\n\
																					<td style="padding-top:15.5px;text-align:center;color:#0061f3;"><div id="UC_Balance_{{ $values->id }}'+current_{{ $values->id }}+'">0.000</div></td>\n\
																					<td style="padding-top:15.5px;text-align:center;color:#0061f3;"><div id="Closing_Balance_{{ $values->id }}'+current_{{ $values->id }}+'">0.000</div></td>\n\
																				</tr>');
																		}
																		// $('.selectpicker').selectpicker();
																		// $('.selectpicker').selectpicker('refresh');
																		$('.selectpicker').selectpicker('render');
																	}
																	$(document).ready(function() {
																		$('#form_{{ $values->id }}').on('submit', function(e){
																			e.preventDefault();
																			$('.page-loader-wrapper').fadeIn();
																			$.ajax({
																				headers: {
																					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
																				},
																				type: "POST",
																				url: '{{ route('admin.Uc.saveEntityComponents') }}',
																				dataType: "json",
																				data: new FormData(this),
																				contentType: false,
																				cache: false,
																				processData: false,
																				success:function (data){
																					if (data.msgType == true) {
																						swal("Success", data.msg, "success");
																					}
																					else {
																						if(data.msg=="VE"){
																							swal("Error", "Validation error.Please check the form correctly!", 'error');
																							$.each(data.errors, function( index, value ) {
																								$('#'+index).after('<p class="text-danger form_errors">'+value+'</p>');
																							});
																						}else{
																							swal("Error", data.msg, 'error');

																						}
																					}
																					$('.page-loader-wrapper').fadeOut();
																				}
																			}); 
																		});
																	});
																</script>
																<?php $table_row++; ?>
															</tbody>
														</table>
														<a class="btn btn-success add_field_button" href="javascript:add_inputs_{{ $values->id }}();" style="float:left;"><i class="fa fa-plus"></i> Add More Rows</a>
														<a class="btn btn-danger add_field_button" href="javascript:delete_rows({{ $values->id }});" style="float:left;" title="Select the Check Boxes to Delete the Rows..."><i class="fa fa-trash"></i> Remove Rows </a>
														<button id="form_submit_{{ $values->id }}" type="submit" class="btn btn-primary blue-gradient add_field_button1" style="float:right;position:relative;padding:5px 15px;"><i class="fa fa-save"></i> Save </button>
													</form>
													<br /><br /><br /><br />
													<?php
														}
													?>
													<script>
														function isNumber(id){
															value_raw = $('#'+id).val();
															value = parseInt(value_raw);

															//Float
															if ( Number(value) == value && value % 1 != 0 )	{
																$('#'+id).val(value);
																return true;
															}
															//Integer
															else if ( Number(value) == value && value % 1 == 0 )	{
																$('#'+id).val(value);
																return true;
															}
															else {
																alert("Please Enter a Numeric Value");
																$('#'+id).val('0');
																return false;
															}
														}
														function isFloat(id){
															value_raw = $('#'+id).val();
															value = parseFloat(value_raw);

															//Float
															if ( Number(value) == value && value % 1 != 0 )	{
																$('#'+id).val(value.toFixed(3));
																return true;
															}
															//Integer
															else if ( Number(value) == value && value % 1 == 0 )	{
																$('#'+id).val(value.toFixed(3));
																return true;
															}
															
															// else if ( Number(value_raw) == value_raw && value_raw % 1 == 0 )	{
															// 	$('#'+id).val(value.toFixed(3));
															// 	return true;
															// }
															else {
																alert("Please Enter a Numeric Value");
																$('#'+id).val('0.000');
																return false;
															}
														}
														function calculate_total(value1, value2)
														{
															el1 = isFloat(value1+value2);

															if( el1 == true ) {
																goi = parseFloat($('#opening_balance_'+value2).val());
																goa = parseFloat($('#goa_fund_received_'+value2).val());
																ia = parseFloat($('#other_receipts_'+value2).val());
																var total = goi + goa + ia;
																$('#create_total_'+value2).html(total.toFixed(3));
																var UC_Balance = $('#create_total_'+value2).html() - $('#UC_Submited_'+value2).val();
																$('#UC_Balance_'+value2).html(UC_Balance.toFixed(3));
																var Closing_Balance = $('#create_total_'+value2).html() - $('#expenditure_'+value2).val();
																$('#Closing_Balance_'+value2).html(Closing_Balance.toFixed(3));
																return true;
															}
														}
														function calculate_uc_balance(value)
														{
															el2 = isFloat("UC_Submited_"+value);
															var UC_Balance = $('#create_total_'+value).html() - $('#UC_Submited_'+value).val();
															$('#UC_Balance_'+value).html(UC_Balance.toFixed(3));
															return;
														}
														function calculate_closing_balance(value)
														{
															el2 = isFloat("expenditure_"+value);
															var Closing_Balance = $('#create_total_'+value).html() - $('#expenditure_'+value).val();
															$('#Closing_Balance_'+value).html(Closing_Balance.toFixed(3));
															return;
														}
														function delete_rows(id)
														{
															jQuery('#tab_create_'+id+' input:checkbox:checked').parents("tr").remove();
														}
													</script>
													<style>
														.add_field_button {
															position:relative;top:-24px;border-color:#e7ecf1;left:-6px;border-radius:1px;
														}
														.add_field_button1 {
															position:relative;top:-24px;border-color:#e7ecf1;left:3px;border-radius:1px;
														}
														.bs-searchbox > input[type=search]{
															border:2px solid #06b4d7;
														}
													</style>
												</div>
											</div>
										</div>

										<script type="application/javascript">
    
    $('.edit_uc1').on('click', function(e){
            e.preventDefault();
            var entity_id= $(this).data('entity_id');
            if(entity_id){
                $('#viewUc1_'+entity_id).hide();
                $('#cancel_uc_upload1_'+entity_id).show();
                $('#ucUploadForm1').show();
            }

        });
        
        $(document).on("click",".cancel_uc_upload1",function(e){
            e.preventDefault();
            var entity_id= $(this).data('entity_id');
            
            if(entity_id){
                $('#viewUc1_'+entity_id).show();
                $('#ucUploadForm1').hide();
                $('#cancel_uc_upload1_'+entity_id).hide();
            }
        });
     $('#ucUploadForm1').validate({
            rules: {
                attachment: {
                    required: true,
                }
            },
        });
    
    $('#ucUploadForm1').on('submit', function(e){
            e.preventDefault();
            $('.form_errors').remove();
            
            if($('#ucUploadForm1').valid()) {
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.uc.gfr.save')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {
                            $('#viewUc1_'+data.data.entity_id).show();
                             $('#ucUploadForm1').hide();
                            $('#uc_upload1_'+data.data.entity_id).hide();
                            $('#uc_view_link1_'+data.data.entity_id).attr('href', data.data.imgUrl+data.data.attachment_path);
                            $('.cancel_uc_upload1_'+data.data.entity_id).remove();
                        } else {
                            if (data.msg == "VE") {
                                swal("Error", "Please select attachment to upload. The attachment must be in pdf format only. Maximum size is 400KB and minimum 10KB", 'error');
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
            }else{
                alert('Please select attachment.');
            }
        });
    
    
    
    
     $('.edit_uc').on('click', function(e){
            e.preventDefault();
            var entity_id= $(this).data('entity_id');
            if(entity_id){
                $('#viewUc_'+entity_id).hide();
                $('#cancel_uc_upload_'+entity_id).show();
                $('#ucUploadForm').show();
            }

        });
        
        $(document).on("click",".cancel_uc_upload",function(e){
            e.preventDefault();
            var entity_id= $(this).data('entity_id');
            
            if(entity_id){
                $('#viewUc_'+entity_id).show();
                $('#ucUploadForm').hide();
                $('#cancel_uc_upload_'+entity_id).hide();
            }
        });
    
    $('#ucUploadForm').validate({
            rules: {
                attachment: {
                    required: true,
                }
            },
        });
    
    $('#ucUploadForm').on('submit', function(e){
            e.preventDefault();
            $('.form_errors').remove();
            
            if($('#ucUploadForm').valid()) {
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.uc.gfr.save')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {
                            $('#viewUc_'+data.data.entity_id).show();
                             $('#ucUploadForm').hide();
                            $('#uc_upload_'+data.data.entity_id).hide();
                            $('#uc_view_link_'+data.data.entity_id).attr('href', data.data.imgUrl+data.data.attachment_path);
                            $('.cancel_uc_upload_'+data.data.entity_id).remove();
                        } else {
                            if (data.msg == "VE") {
                                swal("Error", "Please select attachment to upload. The attachment must be in pdf format only. Maximum size is 400KB and minimum 10KB", 'error');
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
            }else{
                alert('Please select attachment.');
            }
        });
</script>
										<!-- <div class="modal fade" id="add_update_people" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document" style="width:755px;">
                                                <div class="modal-content">
                                                    <form id="instruction-update-form" class="action-update" action="{{ route('admin.courtCases.addInstruction') }}" method="post">
                                                        <div class="modal-header aqua-gradient" style="height:75px;">
                                                            <div class="portlet-title">
                                                                <div class="caption caption-md">
                                                                    <center><h3 class="caption-subject font-blue-madison bold uppercase"><b>Achievement Details</b></h3></center>
                                                                    <button type="button" class="btn-outline-danger" data-dismiss="modal" style="position:absolute;height:38px;width:35px;right:15px;top:19px;border-radius:2px;">
                                                                        <span aria-hidden="true" >&times;</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="instruction_details_body" class="modal-body">
														<table id="table-border" class="table table-bordered table-hover table-checkable order-column" >
															<thead>
																<tr>
																	<th rowspan="2" width="55px"> Sl. No. </th>
																	<th rowspan="2" width="285px"> Target Group </th>
																	<th colspan="2" width="85px"> SC </th>
																	<th colspan="2" width="85px"> ST </th>
																	<th colspan="2" width="85px"> Others</th>
																</tr>
																<tr>
																	<th width="85px"> Male </th>
																	<th width="85px"> Female </th>
																	<th width="85px"> Male </th>
																	<th width="85px"> Female </th>
																	<th width="85px"> Male </th>
																	<th width="85px"> Female </th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td>
																		<div class="checkbox-animated">
																			<input id="checkbox_" name="id[]" type="checkbox" class="checkbox" value="1" style="height:20px;" >
																			<label for="checkbox_">
																				<span class="check"></span>
																				<span class="box"></span>
																				&nbsp; &nbsp;
																			</label>																			
																		</div>
																	</td>
																	<td><input id="" type="text" name="" onblur="" value="0" placeholder="" class="form-control" required /></td>
																	<td><input id="" type="text" name="" onblur="" value="0" placeholder="" class="form-control" required /></td>
																	<td><input id="" type="text" name="" onblur="" value="0" placeholder="" class="form-control" required /></td>
																	<td><input id="" type="text" name="" onblur="" value="0" placeholder="" class="form-control" required /></td>
																	<td><input id="" type="text" name="" onblur="" value="0" placeholder="" class="form-control" required /></td>
																	<td><input id="" type="text" name="" onblur="" value="0" placeholder="" class="form-control" required /></td>
																	<td><input id="" type="text" name="" onblur="" value="0" placeholder="" class="form-control" required /></td>
																</tr>
															</tbody>
														</table>
                                                        </div>
                                                        <div id="applicant_details_footer" class="modal-footer" style="padding:10px;">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal" style="float:left;color:white;padding:0px 12px;">Close</button>
                                                                        <button type="submit" class="btn btn-primary aqua-gradient" style="float:right;font-weight:bold;">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div> -->