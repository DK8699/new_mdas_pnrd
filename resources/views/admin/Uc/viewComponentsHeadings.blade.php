				<form id="gistForm" method="POST" target="_blank">
					{{ csrf_field() }}
					<div class="row">
                        <div class="col-md-12"><br /><br />
							<div id="resultset" class="row" style="border-bottom:3px solid #7c8487;margin:55px -15px 25px -15px;">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<h2 style="text-transform: uppercase;font-weight:bold;">
										Select Project Components and Entities
									</h2>
								</div>
							</div>
							<div class="portlet-body">
								<center><br />
									<div style="text-align:center;">
										<?php
											$p=0;
											for($j=0;$j<count($project_year_ids);$j++)
											{
												$project_year = DB::select('SELECT project_id, project_year FROM uc_projects_years WHERE id = ?', [$project_year_ids[$j]]);
												$project = DB::select('SELECT project_name FROM uc_project_entries WHERE id = ?', [$project_year[0]->project_id]);
												if($p==0)
												{
										?>
													<b style="font-size:13pt;margin:20px 0px;"><?php echo $project[0]->project_name.' '.$project_year[0]->project_year ?></b>
										<?php
													$p=1;
												}
										else
												{
										?>
													<b style="font-size:13pt;margin:20px 0px;">&nbsp&nbsp::&nbsp&nbsp<?php echo $project[0]->project_name.' '.$project_year[0]->project_year ?></b>
										<?php
												}
											}
										?>
									</div><br /><br />
								</center>
											<div class="row">
												<div class="col-md-8">
													<!--BEGIN EXAMPLE TABLE PORTLET-->
													<div class="portlet light portlet-fit" style="border:2.5px solid #80819d;background:white;">
														<div class="portlet-body table table-responsive" style="max-height:450px;">
															<table class="table table-bordered table-hover table-checkable order-column" id="sample_1">
																<thead style="background:#e8fdff;">
																	<tr>
																		<th width="1px" style="font-size:12pt;border-bottom:3px solid #05a5b3;border-top:1px solid #80819d;" valign="bottom">
																			<div class="checkbox-animated">
																				<input id="checkbox_all" type="checkbox" class="components_checkboxes checkbox group-checkable" onchange="checkAll(this, 'components_checkboxes')" value="1" style="height:20px;" >
																				<label for="checkbox_all">
																					<span class="check"></span>
																					<span class="box"></span>
																				</label>																			
																			</div>
																		</th>
																		<th width="1px" style="font-size:12pt;border-bottom:3px solid #05a5b3;border-top:1px solid #80819d;" valign="bottom"> Sl. </th>
																		<th width="555px" style="font-size:12pt;border-bottom:3px solid #05a5b3;border-top:1px solid #80819d;" valign="bottom"> Name of the Component </th>
																	</tr>
																</thead>
																<tbody>
																	<?php
																		$serial = "A"; $table_row = 0; $count = 0;

																		foreach($components_headers as $values) 
																		{
																			$blank_header = 0;
																			foreach($Project_Components_Entities as $check)
																			{
																				$header_contents = DB::select('SELECT * FROM uc_components_details WHERE component_header_id = ? AND components_entity_id = ?', [$values->id, $check->id]);
																				if( Empty($header_contents) )
																				{
																					$blank_header = 1;
																					continue;
																				}
																					else
																					{
																						$blank_header = 0;
																						break;
																					}
																				}
																				if( $blank_header == 1 )
																					continue;
																				$rn = rand(10,10000).$values->id.rand(10,10000);
																		?>
																			<tr class="odd gradeX">
																				<td style="display:none;"></td>
																				<td style="display:none;"></td>
																				<td colspan="3" style="height:40px;"></td>
																			</tr>
																			<tr class="odd gradeX" style="background:#ffe2e285;">
																				<td>
																					<div class="checkbox-animated">
																						<input id="checkbox_{{ $rn }}" type="checkbox" class="components_checkboxes checkbox group-checkable" onchange="checkAll(this, '{{ $rn }}')" value="1" style="height:20px;" >
																						<label for="checkbox_{{ $rn }}">
																							<span class="check"></span>
																							<span class="box"></span>
																						</label>																			
																					</div>

																					<!-- <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
																						<input type="checkbox" class="checkboxes group-checkable" data-set="#sample_1 .{{ $rn }}" value="1" />
																						<span></span>
																					</label> -->
																				</td>
																				<td style="display:none;"></td>
																				<td colspan="2"><b>{{ $serial++ }}. {{ $values->header_name }}</b></td>
																			</tr>
																			<?php
																				$components = DB::select('SELECT * FROM uc_components WHERE component_header_id = ?', [$values->id]);
																				foreach($components as $val)
																				{
																					foreach($Projects_Components as $pro_val)
																					{
																						if( $pro_val->id == $val->id)
																						{
																			?>
																				<tr class="odd gradeX">
																					<td>
																						<div class="checkbox-animated">
																							<input id="checkbox_{{ $pro_val->id }}" name="project_entities_components[]" type="checkbox" class="components_checkboxes checkbox {{ $rn }} components_select" value="{{ $val->id }}" style="height:20px;" >
																							<label for="checkbox_{{ $pro_val->id }}">
																								<span class="check"></span>
																								<span class="box"></span>
																							</label>																			
																						</div>
																					</td>
																					<td align="center"> {{ ++$table_row }} </td>
																					<td>
																						<?php
																							echo $val->component_name;
																						?>
																					</td>
																				</tr>
																					<?php
																							break;
																						}
																					}
																				}
																			}
																		?>
																		</tbody>
																	</table>
																</div>
															</div>

															<!-- END EXAMPLE TABLE PORTLET-->
															<div class="col-md-12 portlet light" style="padding:0;margin-left:-1.5px;">
																<a href="javascript:all_States()" class="btn btn-primary blue-gradient" style="font-weight:bold;margin-top:4px;padding:8px;width:100%;"><i class="fa fa-line-chart"></i>&nbsp;&nbsp;Zila-Wise Projects Stats</a>
															</div>
														</div>
														<div class="col-md-4">
															<!-- BEGIN EXAMPLE TABLE PORTLET-->
															<div class="portlet light portlet-fit" style="border:2.5px solid #80819d;background:white;">
																<div class="portlet-body table table-responsive" style="max-height:450px;">
																	<table class="table table-bordered table-hover table-checkable order-column" id="sample_state">
																		<thead style="background:#e8fdff;">
																			<tr>
																				<th width="35px" style="font-size:12pt;border-bottom:3px solid #05a5b3;border-top:1px solid #80819d;">
																					<div class="checkbox-animated">
																						<input id="checkbox_all1" type="checkbox" class="entities_checkboxes checkbox group-checkable" onchange="checkAll(this, 'entities_checkboxes')" value="1" style="height:20px;" >
																						<label for="checkbox_all1">
																							<span class="check"></span>
																							<span class="box"></span>
																						</label>																			
																					<!-- </div>
																					<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
																						<input type="checkbox" class="group-checkable" data-set="#sample_state .entities_checkboxes" />
																						<span></span>
																					</label> -->
																				</th>
																				<th width="1px" style="font-size:12pt;border-bottom:3px solid #05a5b3;border-top:1px solid #80819d;"> Sl. </th>
																				<th widh="245px" style="font-size:12pt;border-bottom:3px solid #05a5b3;border-top:1px solid #80819d;"> Entities </th>
																			</tr>
																		</thead>
																		<tbody>
																		<?php
																			$serial = "A"; $table_row = 0; $count = 0; $temp = 0;
																			for($i=0;$i<count($project_year_ids);$i++)
																			{
																				$project_year = DB::select('SELECT id, project_id, project_year FROM uc_projects_years WHERE id = ?', [$project_year_ids[$i]]);
																				$project = DB::select('SELECT project_name FROM uc_project_entries WHERE id = ?', [$project_year[0]->project_id]);

																				$project_entities = DB::select('SELECT * FROM uc_components_entities WHERE project_year_id = ?', [$project_year_ids[$i]]);
																				if( Empty($project_entities) )
																				{
																					continue;
																				}
																				$rn1 = rand(10,10000).$project_year_ids[$i].rand(10,10000);
																		?>
																			<tr class="odd gradeX">
																				<td style="display:none;"></td>
																				<td style="display:none;"></td>
																				<td colspan="3" style="height:40px;"></td>
																			</tr> 
																			<tr class="odd gradeX" style="background:#ffe2e285;">
																				<td>
																					<div class="checkbox-animated">
																						<input id="checkbox_{{ $rn1 }}" type="checkbox" class="entities_checkboxes checkbox group-checkable" onchange="checkAll(this, '{{ $rn1 }}')" value="1" style="height:20px;" >
																						<label for="checkbox_{{ $rn1 }}">
																							<span class="check"></span>
																							<span class="box"></span>
																						</label>																			
																					</div>
																					<!-- <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
																						<input type="checkbox" class="entities_checkboxes group-checkable" data-set="#sample_state .{{ $rn1 }}" value="1" />
																						<span></span>
																					</label> -->
																				</td>
																				<td style="display:none;"></td>
																				<td colspan="2"><b>{{ $serial++ }}. {{ $project[0]->project_name.' '.$project_year[0]->project_year }}</b></td>
																			</tr>
																		<?php
																				for($j=0;$j<count($project_entities);$j++) 
																				{
																					// if($Project_Components_Entities[$j]->project_year_id == $project_year_ids[$i])
																					// {
																		?>
																			<tr class="odd gradeX">
																				<td>
																					<div class="checkbox-animated">
																						<input id="checkbox_{{ $project_year_ids[$i] }}{{ $j }}" name="project_entities[]" type="checkbox" class="entities_checkboxes checkbox {{ $rn1 }} entities_select" value="{{ $project_entities[$j]->id }}" style="height:20px;" >
																						<!-- <input id="checkbox_{{ $project_year_ids[$i] }}{{ $j }}" name="project_entities[]" type="checkbox" class="entities_checkboxes checkbox {{ $rn1 }}" value="{{ $Project_Components_Entities[$j]->id }}" style="height:20px;" > -->
																						<label for="checkbox_{{ $project_year_ids[$i] }}{{ $j }}">
																							<span class="check"></span>
																							<span class="box"></span>
																						</label>																			
																					</div>
																					<!-- <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
																						<input type="checkbox" name="project_entities[]" class="entities_checkboxes {{ $rn1 }}" value="{{ $Project_Components_Entities[$j]->id }}" />
																						<span></span>
																					</label> -->
																				</td>
																				<td align="center"> {{ ++$table_row }} </td>
																				<td>
																					<?php
																						echo $project_entities[$j]->short_entity_name;
																						$project_division = DB::select('SELECT * FROM uc_project_divisions WHERE id = ?', [$project_entities[$j]->division_id]);
																						if( $project_division[0]->division_type == 1 )
																							echo " (EC)";
																						else
																							echo " (Dist.)";
																					?>
																				</td>
																			</tr>
																		<?php
																					// }
																				}
																			}
																		?>
																		</tbody>
																	</table>
																</div>
															</div>
															<!-- END EXAMPLE TABLE PORTLET-->
															<div class="col-md-12 portlet light" style="padding:0;margin:0;">
																<a href="javascript:seperate_States()" type="button" class="btn btn-primary blue-gradient" style="font-weight:bold;margin-top:4px;padding:8px;width:100%;"><i class="fa fa-bar-chart"></i>&nbsp;&nbsp;Year-Wise Projects Stats</a>
															</div>
														</div>
													</div>
												</div>					
								<br /><br /><br /><br />
								<!-- END PROFILE CONTENT -->
						</div>
					</div>
					<style>
						table th {
							height:55px;
							v-align:middle;
						}
						.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
							padding: 7px;
							line-height: 1.42857143;
							vertical-align: middle;
							border-top: 0px solid #ddd;
						}
						.table-bordered>thead>tr>th {
							border: 1px solid #80819d;
						}

						.checkbox-animated {
							position: relative;
							width: 16px;
							margin-top: 0px;
							margin-bottom: 0px;
						}
						.checkbox-animated label .check {
							top: -5px;
						}
						.checkbox-animated label .box {
							border: 2px solid #f1849a;
							margin-top: -16px;
						}
					</style>
					<script>
						function checkAll(ele, cl) {
							var checkboxes = document.getElementsByClassName(cl);
							if (ele.checked) {
								for (var i = 0; i < checkboxes.length; i++) {
									if (checkboxes[i].type == 'checkbox') {
										checkboxes[i].checked = true;
									}
								}
							}
							else {
								for (var i = 0; i < checkboxes.length; i++) {
									console.log(i)
									if (checkboxes[i].type == 'checkbox') {
									checkboxes[i].checked = false;
									}
								}
							}
						}
						function seperate_States()
						{
							var check_var1 = 0;
							var check_var2 = 0;
							if( $('.components_select').is(':checked') )
								check_var1 = 1;
							else
							{
								alert("Please Select atleast one component...");
								return false;
							}

							if( $('.entities_select').is(':checked') )
								check_var2 = 1;
							else
							{
								alert("Please Select atleast one Entity...");
								return false;
							}

							if( check_var1 == 1 && check_var2 == 1 )
							{
								$('#gistForm').attr('action', "gistEntitiesComponents");
								$("#gistForm").submit();
							}
						}
						function all_States()
						{
							var check_var1 = 0;
							if( $('.components_select').is(':checked') )
								check_var1 = 1;
							else
							{
								alert("Please Select atleast one component...");
								return false;
							}

							if( check_var1 == 1 )
							{
								$('#gistForm').attr('action', "gistallEntitiesComponents");
								$("#gistForm").submit();
							}
						}
					</script>
		</form>