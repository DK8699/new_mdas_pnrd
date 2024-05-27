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
						/* table th, table td {
							height:55px;
							v-align:middle;
							border: 1px solid #80819d;
						} */
						/* .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
							padding: 7px;
							line-height: 1.42857143;
							vertical-align: middle;
							border-top: 0px solid #ddd;
						}
						.table-bordered>thead>tr>th {
							border: 1px solid #80819d;
						} */

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
				// $project_divisions = DB::select('SELECT *, b.id as d_id FROM uc_components_entities a, uc_project_divisions b WHERE a.division_id = b.id order by b.division_type asc');
				$extension_center = DB::select('SELECT distinct c.zilla_extension_id, c.id as d_id, a.extension_center_name, c.division_type
				FROM siprd_extension_centers a, uc_components_entities b, uc_project_divisions c
				WHERE a.id = c.zilla_extension_id AND c.division_type = ? AND b.division_id = c.id', [1]);

				$zila_parishads = DB::select('SELECT distinct c.zilla_extension_id, c.id as d_id, a.zila_parishad_name, c.division_type
				FROM zila_parishads a, uc_components_entities b, uc_project_divisions c
				WHERE a.id = c.zilla_extension_id AND c.division_type = ? AND b.division_id = c.id', [2]);

				$project_divisions = array_merge($extension_center, $zila_parishads);

				// dd($project_divisions);return;
				$states_count = count($project_divisions);
				$count_components_headers = count($components_headers);
				$count_components_sub_headers = count($components_sub_headers);
				$p=0;
				for($j=0;$j<count($project_ids);$j++)
				{
					$project_year = DB::select('SELECT project_id, project_year FROM uc_projects_years WHERE id = ?', [$project_ids[$j]]);
					$project = DB::select('SELECT project_name FROM uc_project_entries WHERE id = ?', [$project_year[0]->project_id]);
					if($p==0)
					{
			?>
						<b style="font-size:14pt;margin:20px 0px;"><?php echo $project[0]->project_name.' '.$project_year[0]->project_year; ?></b>
			<?php
						$p=1;
					}
					else
					{
			?>
						<b style="font-size:14pt;margin:20px 0px;">&nbsp&nbsp::&nbsp&nbsp<?php echo $project[0]->project_name.' '.$project_year[0]->project_year; ?></b>
			<?php
					}
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
						$e_count = count($extension_center);
						// if( !empty($e_count) ) {
							$ecol = $e_count * 2;
							echo '<th class="celda_encabezado_general" colspan="'.$ecol.'">Extension Centers</th>';
						// }

						$d_count = count($zila_parishads);
						// if( !empty($e_count) ) {
							$dcol = $d_count * 2;
							echo '<th class="celda_encabezado_general" colspan="'.$dcol.'">Districts</th>';
						// }
						echo '<th class="celda_encabezado_general" colspan="3"></th>';
					?>
					<tr>
					</tr>
					<?php
						for($i=0;$i<$states_count;$i++)
						{
							if( $project_divisions[$i]->division_type == 1 ) {
								$entity_name = DB::select('SELECT extension_center_name FROM siprd_extension_centers WHERE id = ?', [$project_divisions[$i]->zilla_extension_id]);
								if( !empty($entity_name) ) {
									echo '<th class="celda_encabezado_general" colspan="2">'.$entity_name[0]->extension_center_name.'</th>';
								}
							}
							else {
								$entity_name = DB::select('SELECT zila_parishad_name FROM zila_parishads WHERE id = ?', [$project_divisions[$i]->zilla_extension_id]);
								if( !empty($entity_name) )
									echo '<th class="celda_encabezado_general" colspan="2">'.$entity_name[0]->zila_parishad_name.'</th>';
							}
						}
					?>
						<th class="celda_encabezado_general" colspan="3"><div>Entities Total</div></th>
					</tr>
					<tr>
					<?php
						for($i=0;$i<$states_count;$i++)
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
						<td colspan="{{ ( $states_count * 2 ) + 5 }}" style="height:60px;border-top:1px solid #05a5b3;border-left:1px solid lightgrey;border-right:1px solid lightgrey;"></td>
					</tr>
														<?php
															$serial = "A";

															// $grand_total_GOI = 0;
															// $grand_total_UC_Submitted = 0;
															// $grand_total_UC_Balance = 0;

															// $GOI = 0;
															// $UC_Submitted = 0;
															// $UC_Balance = 0;

															// $capital_GOI = 0;
															// $capital_UC_Submitted = 0;
															// $capital_UC_Balance = 0;

															for($cp=0;$cp<$count_components_headers;$cp++) 
															{
																for($i=0;$i<$states_count;$i++)
																{
																	$header_contents = DB::select('SELECT a.id FROM uc_components_details a, uc_components_entities b WHERE a.component_header_id = ? AND a.components_entity_id = b.id
																	AND b.division_id = ? AND b.project_year_id IN (SELECT id FROM uc_projects_years WHERE '.$project1.')', array_merge([$components_headers[$cp], $project_divisions[$i]->d_id], $project_ids));
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

																$header_details = DB::select('SELECT * FROM uc_components_headers WHERE id = ? ', [$components_headers[$cp]]);
														?>
															<tr style="background:#ffe2e285;">
																<td class="celda_normal" style="border-top:3px solid #83858f;text-align:center;"><b>{{ $serial++ }}.</b></td>
																<td class="celda_normal" style="border-top:3px solid #83858f;heigt:50px;"><b>{{ $header_details[0]->header_name }}</b></td>
																<td class="celda_normal" colspan="{{ ( count($project_divisions) * 2 ) + 3 }}" style="border-top:3px solid #83858f;"><b></b></td>
															</tr>
														<?php
																$table_row = 1;
																for($l=0;$l<$count_components_sub_headers;$l++)
																{
																	$blank_row = 0;
																	$csh = DB::select('SELECT * FROM uc_components WHERE id = ?', [$components_sub_headers[$l]]);
																	if( $header_details[0]->id == $csh[0]->component_header_id )
																	{
																		for($i=0;$i<$states_count;$i++)
																		{
																			$header_contents = DB::select('SELECT a.id FROM uc_components_details a, uc_components_entities b WHERE a.component_id = ?
																			AND a.components_entity_id = b.id AND b.division_id = ? AND b.project_year_id IN (SELECT id FROM uc_projects_years WHERE '.$project1.')', array_merge([$csh[0]->id, $project_divisions[$i]->d_id], $project_ids));
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
																<td class="celda_normal components" rowspan="1"><div class="serial_no" style="text-align:center;">{{ $table_row++ }}.</div></td>
																<td class="celda_normal components" rowspan="1" style="mi-height:50px;"><div class="components">{{ $csh[0]->component_name }}</div></td>
														<?php
																		$i = 0; $All_Total = 0; $All_Total1 = 0; $temp_value = 0;
																		for($j=0;$j<$states_count;$j++)
																		{
																			$components = DB::select('SELECT * FROM uc_components_details a, uc_components_entities b WHERE a.component_id = ?
																			AND a.components_entity_id = b.id AND b.division_id = ? AND b.project_year_id IN (SELECT id FROM uc_projects_years WHERE '.$project1.')', array_merge([$csh[0]->id, $project_divisions[$j]->d_id], $project_ids));

																			$count_components = count($components);
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
																		$temp_value = 0;
																		for($c=0;$c<$count_components;$c++)
																		{
																			$total_receipts = $components[$c]->ob + $components[$c]->goa_fund_received + $components[$c]->other_receipts;
																			$temp_value += $total_receipts;

																			// if( $components[$c]->head == "R" )
																				// ${"Arevenue$i"} += $temp_value;
																			// else if( $components[$c]->head == "C" )
																			// 	${"Acapital$i"} += $components[$c]->GOI_share;
																		}
																		echo $temp_value;
																		${"Atotal$i"} += $temp_value;
																		$All_Total += $temp_value;
																	?>
																</td>
																<td class="celda_normal" style="text-align:right;padding:2px 5px;height:25px;">
																	<?php
																		$temp_value = 0;

																		for($c=0;$c<$count_components;$c++)
																		{
																			$temp_value += $components[$c]->uc_submitted;

																			// if( $components[$c]->head == "R" )
																				// ${"Brevenue1$i"} += $temp_value;
																			// else if( $components[$c]->head == "C" )
																			// 	${"Bcapital1$i"} += $components[$c]->UC_submitted;
																		}
																		echo $temp_value;
																		${"Btotal1$i"} += $temp_value;
																		$All_Total1 += $temp_value;
																	?>
																</td>
														<?php 
																			}
																			$i++;
																		}
														?>
																<td class="celda_normal" style="text-align:right;padding:2px 5px;height:25px;color:#0061f3;"><b><?php echo $All_Total; ?></b></td>
																<td class="celda_normal" style="text-align:right;padding:2px 5px;height:25px;color:#17934e;"><b><?php echo $All_Total1; ?></b></td>
																<td class="celda_normal" style="text-align:right;padding:2px 5px;height:25px;color:#dd223c;"><b><?php echo $All_Total - $All_Total1; ?></b></td>
															</tr>
															<!-- <tr>
														<?php
																		$All_Total = 0; $All_Total1 = 0;
																		for($k=0;$k<$states_count;$k++)
																		{
																			$components = DB::select('SELECT pt_noc, pa_noc FROM uc_components_details a, uc_components_entities b WHERE a.component_id = ?
																			AND a.components_entity_id = b.id AND b.division_id = ? AND b.project_year_id IN (SELECT id FROM uc_projects_years WHERE '.$project1.')', array_merge([$csh[0]->id, $project_divisions[$k]->d_id], $project_ids));

																			$count_components = count($components);
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
																	$temp_value = 0;
																	for($c=0;$c<count($components);$c++)
																		$temp_value += $components[$c]->pt_noc;
																	echo $temp_value;
																	$All_Total += $temp_value; ?>
																</td>
																<td class="celda_normal" style="text-align:right;padding:2px 5px;height:25px;">
														<?php
																	$temp_value = 0;
																	for($c=0;$c<count($components);$c++)
																		$temp_value += $components[$c]->pa_noc;
																	echo $temp_value;
																	$All_Total1 += $temp_value; ?>
																</td>
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
															<tr id="table_sub_total" style="background:#ffe2e285;">
																<td class="celda_normal components" style="border-bottom:3px solid #83858f;height:25px;"></td>
																<td class="celda_normal components" style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;height:25px;"><b>Total for {{ $header_details[0]->header_name }} Sector : </b></td>
														<?php
															$i=0; $All_Total = 0; $All_Total1 = 0;
															for($m=0;$m<$states_count;$m++)
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
																<td class="celda_normal" style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;text-align:right;padding:2px 5px;height:25px;color:#dd223c;"><b><?php echo $All_Total - $All_Total1; ?></b></td>
															</tr>
															<tr>
																<td class="celda_normal" colspan="{{ ( $states_count * 2 ) + 5 }}" style="height:55px;"></td>
															</tr>
														<?php
															}
														?>
															<tr style="background:#ffe2e285;height:55px;">
																<td class="celda_normal" style="border-top:3px solid #83858f;height:35px;"></td>
																<td class="celda_normal components" style="border-top:3px solid #83858f;height:35px;"><b>GRAND TOTAL</b></td>
														<?php
															$i=0; $All_Total = 0; $All_Total1 = 0;
															for($m=0;$m<$states_count;$m++)
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
																<td class="celda_normal" style="border-top:3px solid #83858f;text-align:right;color:#dd223c;"><b><?php echo $All_Total - $All_Total1; ?></b></td>
															</tr>
															<!-- <tr style="background:rgba(150,235,255,0.7);">
																<td class="celda_normal" style="border-top:3px solid #05a5b3;"></td>
																<td class="celda_normal components" style="border-top:3px solid #05a5b3;"><b>REVENUE</b></td> -->
														<?php
															// $i=0; $All_Total = 0; $All_Total1 = 0;
															// for($m=0;$m<$states_count;$m++)
															// {
															// 	$All_Total += ${"Arevenue$i"};
															// 	$All_Total1 += ${"Brevenue1$i"};
															// 	echo '<td class="celda_normal" style="border-top:3px solid #05a5b3;text-align:right;padding:8px 5px;"><b>'.${"Arevenue$i"}.'</b></td>';
															// 	echo '<td class="celda_normal" style="border-top:3px solid #05a5b3;text-align:right;padding:8px 5px;"><b>'.${"Brevenue1$i"}.'</b></td>';
															// 	$i++;
															// }
														?>
																<!-- <td class="celda_normal" style="border-top:3px solid #05a5b3;text-align:right;"><b><?php echo $All_Total; ?></b></td>
																<td class="celda_normal" style="border-top:3px solid #05a5b3;text-align:right;"><b><?php echo $All_Total1; ?></b></td>
															</tr>
															<tr style="background:rgba(150,235,255,0.7);">
																<td class="celda_normal" style="border-top:3px solid #05a5b3;"></td>
																<td class="celda_normal components" style="border-top:3px solid #05a5b3;"><b>CAPITAL</b></td> -->
														<?php
															// $i=0; $All_Total = 0; $All_Total1 = 0;
															// for($m=0;$m<$states_count;$m++)
															// {
															// 	$All_Total += ${"Acapital$i"};
															// 	$All_Total1 += ${"Bcapital1$i"};
															// 	echo '<td class="celda_normal" style="border-top:3px solid #05a5b3;text-align:right;padding:2px 5px;"><b>'.${"Acapital$i"}.'</b></td>';
															// 	echo '<td class="celda_normal" style="border-top:3px solid #05a5b3;text-align:right;padding:2px 5px;"><b>'.${"Bcapital1$i"}.'</b></td>';
															// 	$i++;
															// }
														?>
											<!-- <td class="celda_normal" style="border-top:3px solid #05a5b3;text-align:right;"><b><?php echo $All_Total; ?></b></td>
											<td class="celda_normal" style="border-top:3px solid #05a5b3;text-align:right;"><b><?php echo $All_Total1; ?></b></td> -->
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<br /><br /><br /><br />
</body>
@endsection