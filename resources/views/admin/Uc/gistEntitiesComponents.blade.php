@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_admin_uc')

@section('custom_css')
	<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/al.css"> -->
    <!-- Bootstrap core CSS -->
    <!-- <link href="{{ asset('mdas_assets/mdbootstrap/css/bootstrap.mi.css') }}" rel="stylesheet"> -->
    <!-- Material Design Bootstrap -->
    <link href="{{ asset('mdas_assets/mdbootstrap/css/mdb.min.css') }}" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <!-- <link href="{{ asset('mdas_assets/mdbootstrap/css/style.css') }}" rel="stylesheet"> -->

    <link rel="stylesheet" href="{{ asset('mdas_assets/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('mdas_assets/css/style1.css') }}">
	<style>
						#table-border th, #table-border td{
							border: 1px solid #83858f;
							vertical-align:middle;
						}
						#table-border th{
							text-align:center;
						}
						.table-bordered>thead>tr>th {
							background-color: #384059;
							color: #ffffff;
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
@endsection

@section('content')
	<div class="container-fluid" style="padding:0px 25px;">
		<div class="row" style="position:relative;">
		<center><br /><br /><br /><br />
			<div style="text-align:center;">
			<?php
						$first = 1; $p=1;
						for($i=0;$i<count($project_ids);$i++)
						{
							$project_year = DB::select('SELECT project_id, project_year FROM uc_projects_years WHERE id = ?', [$project_ids[$i]]);
							$project = DB::select('SELECT project_name FROM uc_project_entries WHERE id = ?', [$project_year[0]->project_id]);
							for($j=0;$j<count($project_components_entities);$j++)
							{
								$project_components_table = DB::select('SELECT * FROM uc_components_entities WHERE id = ?', [$project_components_entities[$j]]);
								if($project_ids[$i] == $project_components_table[0]->project_year_id)
									$p = 1;
							}
							if($first == 1 && $p == 1) {
								$first = 0;

			?>
								<b style="font-size:14pt;margin:20px 0px;"><?php echo $project[0]->project_name.' '.$project_year[0]->project_year; ?></b>
			<?php
							}
							else if( $p == 1 ) {
			?>
								<b style="font-size:14pt;margin:20px 0px;">&nbsp&nbsp::&nbsp&nbsp<?php echo $project[0]->project_name.' '.$project_year[0]->project_year; ?></b>
			<?php
							}
							$p=0;
						}
			?>
			</div>
		</center>
		<br /><br />
		<!--<div style="margin:0px 0px 10px 0px;position:absolute;right:0px;top:55px;">
			<a target="_blank" href="gistallStatesComponentsExcel" class="btn btn-md red btn-outline peach-gradient" style="font-size:10pt;"><span>Convert to Excel</span></a>
		</div>!-->
		<div class="col-md-12" style="max-width:100%;max-height:95vh;overflow:scroll;">
			<style>
				.serial_no {
					width:30px;
				}
				.components {
					/* padding-top:10px;
					padding-bottom:10px; */
				}
			</style>
			<table id="table-border" class="fht-table table table-bordered table-hover table-checkable" style="border:4px solid #515a74;background:rgba(255,255,255,0.885);">
				<thead>
					<tr>
						<th class="celda_encabezado_general" rowspan="4"><div class="serial_no">Sl. No.</div></th>
						<th class="celda_encabezado_general" rowspan="4"><div style="width:300px;height:70px;padding-top:25px;">Name of the Component</div></th>
					<?php
						//$project = DB::select('SELECT DISTINCT a.project_name  FROM projects a, uc_components_entities b WHERE b.project_id = a.id');
						$rows = 0;
						for($i=0;$i<count($project_ids);$i++)
						{
							$project_year = DB::select('SELECT project_id, project_year FROM uc_projects_years WHERE id = ?', [$project_ids[$i]]);
							$project = DB::select('SELECT project_name FROM uc_project_entries WHERE id = ?', [$project_year[0]->project_id]);
							for($j=0;$j<count($project_components_entities);$j++)
							{
								$project_components_table = DB::select('SELECT * FROM uc_components_entities WHERE id = ?', [$project_components_entities[$j]]);
								if($project_ids[$i] == $project_components_table[0]->project_year_id)
									$rows = $rows + 2;
							}
							if($rows > 0)
							{
								echo '<th class="celda_encabezado_general" colspan="'.$rows.'" style="min-width:180px">'.$project[0]->project_name.' '.$project_year[0]->project_year.'</th>';
							}
							$rows = 0;
                        }
					?>
						<th class="celda_encabezado_general" rowspan="2" colspan="3"><div>Entities Total</div></th>
					</tr>
					<tr>
					<?php
						for($i=0;$i<count($project_components_entities);$i++)
						{
							// $entity_name = DB::select('SELECT extension_center_name FROM siprd_extension_centers WHERE id = ?', [$project_divisions[$i]->zilla_extension_id]);
							// 	if( !empty($entity_name) ) {
							// 		echo '<th class="celda_encabezado_general" colspan="2">'.$entity_name[0]->extension_center_name.'</th>';
							// 	}
							$cpt = DB::select('SELECT * FROM uc_components_entities WHERE id = ?', [$project_components_entities[$i]]);
							//if( !empty($entity_name) ) {
							$entity_cat = DB::select('SELECT division_type, zilla_extension_id FROM uc_project_divisions WHERE id = ?', [$cpt[0]->division_id]);
							if( $entity_cat[0]->division_type == 1 ) {
								$entity_name = DB::select('SELECT extension_center_name FROM siprd_extension_centers WHERE id = ?', [$entity_cat[0]->zilla_extension_id]);
								echo '<th class="celda_encabezado_general" colspan="2">'.$entity_name[0]->extension_center_name.' (Extension Center)</th>';
							}
							else {
								$entity_name = DB::select('SELECT zila_parishad_name FROM zila_parishads WHERE id = ?', [$entity_cat[0]->zilla_extension_id]);
								echo '<th class="celda_encabezado_general" colspan="2">'.$entity_name[0]->zila_parishad_name.' (District)</th>';
							}

							// echo '<th class="celda_encabezado_general" colspan="2">'.$cpt[0]->short_entity_name.'</th>';
						}
					?>
					</tr>
					<tr>
					<?php
						for($i=0;$i<count($project_components_entities);$i++)
						{
							echo   '<th class="celda_encabezado_general"><div style="width:70px;font-size:10pt;">Target</div></th>
									<th class="celda_encabezado_general"><div style="width:70px;font-size:10pt;">Achieved</div></th>';

							${"Atotal$i"} = 0;
							${"Btotal1$i"} = 0;
							${"Agrand_total$i"} = 0;
							${"Bgrand_total1$i"} = 0;
							// ${"Arevenue$i"} = 0;
							// ${"Brevenue1$i"} = 0;
							// ${"Acapital$i"} = 0;
							// ${"Bcapital1$i"} = 0;
						}
						$cut = 0;
					?>
						<th class="celda_encabezado_general"><div style="width:80px;font-size:10pt;">Target</div></th>
						<th class="celda_encabezado_general"><div style="width:80px;font-size:10pt;">Achieved</div></th>
						<th class="celda_encabezado_general"><div style="width:80px;font-size:10pt;">Unspent Balance</div></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="{{ ( count($project_components_entities) * 2 ) + 5 }}" style="height:60px;border-top:1px solid #05a5b3;border-left:1px solid lightgrey;border-right:1px solid lightgrey;"></td>
					</tr>
					<?php
															$serial = "A";

															$grand_total_GOI = 0;
															$grand_total_UC_Submitted = 0;
															$grand_total_UC_Balance = 0;

															$revenue_GOI = 0;
															$revenue_UC_Submitted = 0;
															$revenue_UC_Balance = 0;

															$capital_GOI = 0;
															$capital_UC_Submitted = 0;
															$capital_UC_Balance = 0;
															for($cp=0;$cp<count($components_headers);$cp++) 
															{
																for($i=0;$i<count($project_components_entities);$i++)
																{
																	$header_details = DB::select('SELECT * FROM uc_components_headers WHERE id = ? ', [$components_headers[$cp]]);
																	$header_contents = DB::select('SELECT id FROM uc_components_details WHERE component_header_id = ? AND components_entity_id = ?', [$components_headers[$cp], $project_components_entities[$i]]);
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
														?>
															<tr style="background:#ffe2e285;">
																<td class="celda_normal" style="border-top:3px solid #83858f;text-align:center;"><b>{{ $serial++ }}.</b></td>
																<td class="celda_normal" style="border-top:3px solid #83858f;height:25px;"><b>{{ $header_details[0]->header_name }}</b></td>
																<td class="celda_normal" colspan="{{ ( count($project_components_entities) * 2 ) + 3 }}" style="border-top:3px solid #83858f;"><b></b></td>
															</tr>
														<?php
																$table_row = 1;
																for($l=0;$l<count($components_sub_headers);$l++)
																{
																	$blank_row = 0;
																	$csh = DB::select('SELECT * FROM uc_components WHERE id = ?', [$components_sub_headers[$l]]);
																	if( $header_details[0]->id == $csh[0]->component_header_id )
																	{
																		for($i=0;$i<count($project_components_entities);$i++)
																		{
																			$header_contents = DB::select('SELECT * FROM uc_components_details WHERE component_id = ? AND components_entity_id = ?', [$csh[0]->id, $project_components_entities[$i]]);
																			if( Empty($header_contents) )
																			{
																				$blank_row = 1;
																				continue;
																			}
																			else
																			{
																				$blank_row = 0;
																				break;
																			}
																		}
																		if( $blank_row == 1 )
																			continue;
														?>
															<tr>
																<td class="celda_normal components" rwspan="2"><div class="serial_no" style="text-align:center;">{{ $table_row++ }}.</div></td>
																<td class="celda_normal components" rwspan="2" style="min-heght:50px;"><div class="components">{{ $csh[0]->component_name }}</div></td>
														<?php
																		$i = 0; $All_Total = 0; $All_Total1 = 0;
																		for($j=0;$j<count($project_components_entities);$j++)
																		{
																			$components = DB::select('SELECT * FROM uc_components_details WHERE components_entity_id = ? AND component_id = ?', [$project_components_entities[$j],$csh[0]->id]);
																			if( Empty($components) )
																			{
														?>
																<td class="celda_normal" style="text-align:right;padding:2px 5px;height:25px;"></td>
																<td class="celda_normal" style="text-align:right;padding:2px 5px;height:25px;"></td>
														<?php
																			}
																			else
																			{
														?>
																<td class="celda_normal" style="text-align:right;padding:2px 5px;height:25px;">
																	<?php
                                                                        $total_receipts = $components[0]->ob + $components[0]->goa_fund_received + $components[0]->other_receipts;

																		echo $total_receipts;
																		${"Atotal$i"} += $total_receipts;
																		$All_Total += $total_receipts;
																		// if( $components[0]->head == "R" )
																		// 	${"Arevenue$i"} += $components[0]->GOI_share;
																		// else if( $components[0]->head == "C" )
																		// 	${"Acapital$i"} += $components[0]->GOI_share;
																	?>
																</td>
																<td class="celda_normal" style="text-align:right;padding:2px 5px;height:25px;">
																	<?php
																		echo $components[0]->uc_submitted;
																		${"Btotal1$i"} += $components[0]->uc_submitted;
																		$All_Total1 += $components[0]->uc_submitted;
																		// if( $components[0]->head == "R" )
																		// 	${"Brevenue1$i"} += $components[0]->uc_submitted;
																		// else if( $components[0]->head == "C" )
																		// 	${"Bcapital1$i"} += $components[0]->uc_submitted;
																	?>
																</td>
														<?php
																			}
																			$i++;
																		}
														?>
																<td class="celda_normal" style="text-align:right;padding:2px 5px;height:25px;color:#0061f3;"><b><?php echo $All_Total; ?></b></td>
																<td class="celda_normal" style="text-align:right;padding:2px 5px;height:25px;color:#17934e;"><b><?php echo $All_Total1; ?></b></td>
																<td class="celda_normal" style="text-align:right;padding:2px 5px;height:25px;color:#f30f2e;"><b><?php echo $All_Total-$All_Total1; ?></b></td>
															</tr>
															<!-- <tr>
														<?php
																		$All_Total = 0; $All_Total1 = 0;
																		for($k=0;$k<count($project_components_entities);$k++)
																		{
																			$components = DB::select('SELECT pt_noc, pa_noc FROM uc_components_details WHERE components_entity_id = ? AND component_id = ?', [$project_components_entities[$k],$csh[0]->id]);

																			if( Empty($components) )
																			{
														?>
																<td class="celda_normal" style="text-align:right;padding:2px 5px;height:25px;"></td>
																<td class="celda_normal" style="text-align:right;padding:2px 5px;height:25px;"></td>
														<?php
																			}
																			else
																			{
														?>
																<td class="celda_normal" style="text-align:right;padding:2px 5px;height:25px;"><?php echo $components[0]->pt_noc; $All_Total += $components[0]->pt_noc; ?></td>
																<td class="celda_normal" style="text-align:right;padding:2px 5px;height:25px;"><?php echo $components[0]->pa_noc; $All_Total1 += $components[0]->pa_noc; ?></td>
														<?php
																			}
																		}
														?>
																<td class="celda_normal" style="text-align:right;padding:2px 5px;height:25px;"><b><?php echo $All_Total; ?></b></td>
																<td class="celda_normal" style="text-align:right;padding:2px 5px;height:25px;"><b><?php echo $All_Total1; ?></b></td>
															</tr> -->
														<?php
																	}
																}
														?>
															<tr id="table_sub_total" style="background:#ffe2e285">
																<td class="celda_normal components" style="border-bottom:3px solid #83858f;height:25px;"></td>
																<td class="celda_normal components" style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;height:25px;"><b>Total for {{ $header_details[0]->header_name }} Sector : </b></td>
														<?php
															$i=0; $All_Total = 0; $All_Total1 = 0;
															for($m=0;$m<count($project_components_entities);$m++)
															{
																$All_Total += ${"Atotal$i"};
																$All_Total1 += ${"Btotal1$i"};
																echo '<td class="celda_normal" style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;text-align:right;padding:2px 5px;height:25px;color:#0061f3;"><b>'.${"Atotal$i"}.'</b></td>';
																echo '<td class="celda_normal" style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;text-align:right;padding:2px 5px;height:25px;color:#17934e;"><b>'.${"Btotal1$i"}.'</b></td>';
																${"Agrand_total$i"} += ${"Atotal$i"};
																${"Bgrand_total1$i"} += ${"Btotal1$i"};
																${"Atotal$i"} = 0;
																${"Btotal1$i"} = 0;
																$i++;
															}
														?>
																<td class="celda_normal" style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;text-align:right;padding:2px 5px;height:25px;color:#0061f3;"><b><?php echo $All_Total; ?></b></td>
																<td class="celda_normal" style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;text-align:right;padding:2px 5px;height:25px;color:#17934e;"><b><?php echo $All_Total1; ?></b></td>
																<td class="celda_normal" style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;text-align:right;padding:2px 5px;height:25px;color:#f30f2e;"><b><?php echo $All_Total-$All_Total1; ?></b></td>
															</tr>
															<tr>
																<td class="celda_normal" colspan="{{ ( count($project_components_entities) * 2 ) + 5 }}" style="height:60px;"></td>
															</tr>
														<?php
															}
														?>
															<tr style="background:#ffe2e285;height:55px;">
																<td class="celda_normal" style="border-top:3px solid #83858f;height:25px;"></td>
																<td class="celda_normal components" style="border-top:3px solid #83858f;height:25px;"><b>GRAND TOTAL</b></td>
														<?php
															$i=0; $All_Total = 0; $All_Total1 = 0;
															for($m=0;$m<count($project_components_entities);$m++)
															{
																$All_Total += ${"Agrand_total$i"};
																$All_Total1 += ${"Bgrand_total1$i"};
																echo '<td class="celda_normal" style="border-top:3px solid #83858f;text-align:right;padding:2px 5px;color:#0061f3;"><b>'.${"Agrand_total$i"}.'</b></td>';
																echo '<td class="celda_normal" style="border-top:3px solid #83858f;text-align:right;padding:2px 5px;color:#17934e;"><b>'.${"Bgrand_total1$i"}.'</b></td>';
																$i++;
															}
							
							?>
																<td class="celda_normal" style="border-top:3px solid #83858f;text-align:right;color:#0061f3;"><b><?php echo $All_Total; ?></b></td>
																<td class="celda_normal" style="border-top:3px solid #83858f;text-align:right;color:#17934e;"><b><?php echo $All_Total1; ?></b></td>
																<td class="celda_normal" style="border-top:3px solid #83858f;text-align:right;color:#f30f2e;"><b><?php echo $All_Total-$All_Total1; ?></b></td>
															</tr>
				</tbody>
			</table>
		</div>
	</div><br /><br />
@endsection