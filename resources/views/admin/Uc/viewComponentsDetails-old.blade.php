        <!-- BEGIN PAGE LEVEL PLUGINS -->
<!--        <link href="log-theme/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="log-theme/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
					<div class="row">
                        <div class="col-md-12"><br />
								<!-- BEGIN PROFILE CONTENT -->
								<div class="profile-contnt">
									<div class="row">
										<div class="col-md-12">
											<div class="portlet light">
												<div class="portlet-title tabbable-line">
													<div class="caption caption-md" style="float:left;">
														<i class="icon-globe theme-font hide"></i>
														<span class="caption-subject font-blue-madison bold uppercase">View Components Details</span>
													</div>
													<div class="dt-buttons" style="margin:0px 0px 10px 0px;float:right;">
														<a tabindex="0" class="dt-button buttons-copy buttons-html5 btn red btn-outline" aria-controls="sample_1" href="{{ url('generateComponentsPDF', ['id' => Crypt::encrypt($components_table)]) }}" target="_blank"><span>Generate PDF</span></a>
													</div>
												</div>
												<div class="portlet-body table table-responsive" style="max-height:550px;">
													<?php
														$components_table_name = DB::select('SELECT * FROM components_table WHERE id = ?', [$components_table]);
													?>
													<center>
														<b><div style="font-size:15pt;margin:20px 0px;">{{ $components_table_name[0]->table_name }}</div></b>
													</center>
													<table class="table table-bordered table-hover table-checkable order-columntable table-fixed" id="sample_1" style="border:4px solid #05a5b3;max-height:600px;">
														<thead style="background-color:#3cd6e4;">
															<tr>
																<th width="55px" style="font-size:12pt;border:3px solid #05a5b3;"> Sl. No. </th>
																<th width="350px" style="font-size:12pt;border:3px solid #05a5b3;"> Name of the Component </th>
																<th width="50px" style="font-size:12pt;border:3px solid #05a5b3;"> Head </th>
																<th width="50px" style="font-size:12pt;border:3px solid #05a5b3;"> Unit </th>
																<th width="50px" style="font-size:12pt;border:3px solid #05a5b3;"> Unit Cost </th>
																<th width="50px" style="font-size:12pt;border:3px solid #05a5b3;"> Physical Target </th>
																<th width="50px" style="font-size:12pt;border:3px solid #05a5b3;"> Physical Achieved </th>
																<th width="70px" style="font-size:12pt;border:3px solid #05a5b3;"> GOI Share Released </th>
																<th width="70px" style="font-size:12pt;border:3px solid #05a5b3;"> UC Submitted by State  </th>
																<th width="70px" style="font-size:12pt;border:3px solid #05a5b3;"> UC Balance </th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td colspan="10" style="height:60px;"></td>
															</tr>
														<?php
															$serial = "A";

															$grand_total_target = 0;
															$grand_total_achievement = 0;

															$grand_total_GOI = 0;
															$grand_total_UC_Submitted = 0;
															$grand_total_UC_Balance = 0;

															$revenue_GOI = 0;
															$revenue_UC_Submitted = 0;
															$revenue_UC_Balance = 0;

															$capital_GOI = 0;
															$capital_UC_Submitted = 0;
															$capital_UC_Balance = 0;

															foreach($components_headers as $values) 
															{
																$header_contents = DB::select('SELECT * FROM components_details WHERE header_id = ? AND components_table_id = ?', [$values->id, $components_table]);
																if( Empty($header_contents) )
																{
																	continue;
																}
														?>
															<tr style="background:rgba(200,231,255,0.25);">
																<td style="border-top:3px solid #05a5b3;border-bottom:2px solid #05a5b3;"><b>{{ $serial++ }}.</b></td>
																<td colspan="9" style="border-top:3px solid #05a5b3;border-bottom:2px solid #05a5b3;"><b>{{ $values->header_name }}</b></td>
															</tr>
															<?php
																$table_row = 0;
																$total_GOI = 0;
																$total_UC_Submitted = 0;
																$total_UC_Balance = 0;
																foreach($components as $val)
																{
																	if($values->id == $val->header_id)
																	{
															?>
															<tr>
																<td> {{ ++$table_row }} </td>
																<td>
																<?php
																	foreach($components_sub_headers as $values1)
																	{
																		if($values1->id == $val->component_sub_header_id) echo $values1->sub_header_name;
																	}
																?>
																</td>
																<td>{{ $val->head }}</td>
																<td>{{ $val->unit }}</td>
																<td>{{ $val->unit_cost }}</td>
																<td><?php $grand_total_target+=$val->physical_target; echo $val->physical_target;?></td>
																<td><?php $grand_total_achievement+=$val->physical_achieved; echo $val->physical_achieved;?></td>
																<td>
																<?php
																	$total_GOI += $val->GOI_share; echo $val->GOI_share;
																	if( $val->head == "R" )
																		$revenue_GOI += $val->GOI_share;
																	else
																		$capital_GOI += $val->GOI_share;
																?>
																</td>
																<td>
																<?php
																	$total_UC_Submitted += $val->UC_submitted; echo $val->UC_submitted;
																	if( $val->head == "R")
																		$revenue_UC_Submitted += $val->UC_submitted;
																	else
																		$capital_UC_Submitted += $val->UC_submitted;
																?>
																</td>
																<td><?php $UC_balance = $val->GOI_share - $val->UC_submitted; $total_UC_Balance += $UC_balance; echo $UC_balance; ?></td>
															</tr>
															<?php
																	}
																}
															?>
															<tr id="table_sub_total" style="background:rgba(200,231,255,0.25);">
																<td style="border-bottom:3px solid #05a5b3;"></td>
																<td style="border-top:2px solid #05a5b3;border-bottom:3px solid #05a5b3;"><b>Total for {{ $values->header_name }} Sector : </b></td>
																<td colspan="5" style="border-top:2px solid #05a5b3;border-bottom:3px solid #05a5b3;"></td>
																<td style="border-top:2px solid #05a5b3;border-bottom:3px solid #05a5b3;"><b><?php $grand_total_GOI += $total_GOI; echo $total_GOI; ?></b></td>
																<td style="border-top:2px solid #05a5b3;border-bottom:3px solid #05a5b3;"><b><?php $grand_total_UC_Submitted += $total_UC_Submitted; echo $total_UC_Submitted; ?></b></td>
																<td style="border-top:2px solid #05a5b3;border-bottom:3px solid #05a5b3;"><b><?php $grand_total_UC_Balance += $total_UC_Balance; echo $total_UC_Balance; ?></b></td>
															</tr>
															<tr>
																<td colspan="10" style="height:60px;"></td>
															</tr>
														<?php
															}
														?>
															<tr style="background:rgba(150,235,255,0.7);">
																<td style="border-top:3px solid #05a5b3;"></td>
																<td style="border-top:3px solid #05a5b3;"><b>GRAND TOTAL</b></td>
																<td colspan="3" style="border-top:3px solid #05a5b3;"></td>
																<td style="border-top:3px solid #05a5b3;"><b><?php echo $grand_total_target; ?></b></td>
																<td style="border-top:3px solid #05a5b3;"><b><?php echo $grand_total_achievement; ?></b></td>
																<td style="border-top:3px solid #05a5b3;"><b><?php echo $grand_total_GOI; ?></b></td>
																<td style="border-top:3px solid #05a5b3;"><b><?php echo $grand_total_UC_Submitted; ?></b></td>
																<td style="border-top:3px solid #05a5b3;"><b><?php echo $grand_total_UC_Balance; ?></b></td>
															</tr>
															<tr style="background:rgba(150,235,255,0.7);">
																<td style="border-top:3px solid #05a5b3;"></td>
																<td style="border-top:3px solid #05a5b3;"><b>REVENUE</b></td>
																<td colspan="5" style="border-top:3px solid #05a5b3;"></td>
																<td style="border-top:3px solid #05a5b3;"><b><?php echo $revenue_GOI; ?></b></td>
																<td style="border-top:3px solid #05a5b3;"><b><?php echo $revenue_UC_Submitted; ?></b></td>
																<td style="border-top:3px solid #05a5b3;"><b><?php echo $revenue_UC_Balance = $revenue_GOI - $revenue_UC_Submitted; ?></b></td>
															</tr>
															<tr style="background:rgba(150,235,255,0.7);">
																<td style="border-top:3px solid #05a5b3;"></td>
																<td style="border-top:3px solid #05a5b3;"><b>CAPITAL</b></td>
																<td colspan="5" style="border-top:3px solid #05a5b3;"></td>
																<td style="border-top:3px solid #05a5b3;"><b><?php echo $capital_GOI; ?></b></td>
																<td style="border-top:3px solid #05a5b3;"><b><?php echo $capital_UC_Submitted; ?></b></td>
																<td style="border-top:3px solid #05a5b3;"><b><?php echo $capital_UC_Balance = $capital_GOI - $capital_UC_Submitted; ?></b></td>
															</tr>
														</tbody>
													</table>
													<style>
														.add_field_button {
															float:right;position:relative;right:4px;border-bottom:none;
														}
													</style>
													<script>
														$('.selectpicker_components').selectpicker({
															style: 'btn-default'
														});
													</script>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- END PROFILE CONTENT -->
								<!-- BEGIN PROFILE CONTENT -->
								<div class="profile-content">
									<div class="row">
										<div class="col-md-12">
											<div class="portlet light">
											<?php
												if ( $components_table_name[0]->info == "" || $components_table_name[0]->info == NULL )
													return;
											?>
												<div class="portlet-title tabbable-line">
													<div class="caption caption-md" style="float:left;">
														<i class="icon-globe theme-font hide"></i>
														<span class="caption-subject font-blue-madison bold uppercase"><?php echo $components_table_name[0]->table_name; ?></span>
													</div>
												</div>
												<div class="portlet-body table table-responsive"  style="margin-bottom:50px;">
													<?php echo $components_table_name[0]->info; ?>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- END PROFILE CONTENT -->
						</div>
					</div>
        <!-- BEGIN PAGE LEVEL PLUGINS -->
<!--        <script src="log-theme/assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="log-theme/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="log-theme/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
<!--        <script src="log-theme/assets/pages/scripts/table-datatables-buttons.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->