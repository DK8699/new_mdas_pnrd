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

</style>

@endsection

@section('content')
<body>
									<div class="container-fluid" style="padding:0px 25px;">
                                        <div class="row">
											<div class="col-md-12">
												<div class="portlet-body table table-responsive">
													<br /><br /><br />
													<?php
														$components_entity_name = DB::select('SELECT * FROM uc_components_entities WHERE id = ?', [$entity_id]);
													?>
													<center>
														<b><div style="font-size:25pt;margin:20px 0px;width:100%;">{{ $components_entity_name[0]->short_entity_name }}</div></b>
													</center>
													<?php
															$uc_gfr = DB::select('SELECT * FROM uc_gfrs WHERE entity_id = ?', [$entity_id])
													?>
													@if(isset($uc_gfr[0]->attachment))
													<a href="{{route('admin.uc.gfr.view', [Crypt::encrypt($entity_id)])}}" target="_blank" class="btn btn-success btn-xs" id="uc_view_link1_{{$entity_id}}" style="position:absolute;right:13px;top:55.5px;padding:8.5px 15px;
																	<i class="fa fa-check"></i>
																	View UC
													</a>
													@endif
														<br />
														<table id="table-border" class="table table-bordered table-hover table-checkable order-column" style="border: 3.5px solid #515a74;">
														<style>
															#table-border th, #table-border td{
																border: 1px solid #83858f;
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
															<tbody>
                                                                <tr>
                                                                    <td colspan="14" style="height:50px;"></td>
                                                                </tr>
                                                            <?php
                                                                $serial = "A";
                                                                $grand_total_target = 0;
                                                                $grand_total_achievement = 0;

																$grand_total_pt_noc = 0;
																$grand_total_pt_nop = 0;
																$grand_total_pa_noc = 0;
																$grand_total_pa_nop = 0;
																$grand_total_ob = 0;
																$grand_total_goa_fund_received = 0;
																$grand_total_other_receipts = 0;
                                                                $grand_total_amount = 0;
                                                                $grand_total_expenditure = 0;
                                                                $grand_total_uc_submitted = 0;
                                                                $grand_total_uc_balance = 0;
                                                                $grand_total_closing_balance = 0;

                                                                foreach($components_headers as $values) 
                                                                {
                                                                    $header_contents = DB::select('SELECT * FROM uc_components_details WHERE component_header_id = ? AND components_entity_id = ?', [$values->id, $entity_id]);
                                                                    if( Empty($header_contents) )
                                                                    {
                                                                        continue;
                                                                    }
                                                            ?>
                                                                <tr style="background:#ffe2e285;">
                                                                    <td style="border-top:3px solid #83858f;border-bottom:2px solid #83858f;"><b>{{ $serial++ }}.</b></td>
                                                                    <td colspan="13" style="text-align:left;border-top:3px solid #83858f;border-bottom:2px solid #83858f;"><b>{{ $values->header_name }}</b></td>
                                                                </tr>
															<?php
																$table_row = 0;
                                                                $header_exists = 0;

																$total_pt_noc = 0;
																$total_pt_nop = 0;
																$total_pa_noc = 0;
																$total_pa_nop = 0;
																$total_ob = 0;
																$total_goa_fund_received = 0;
																$total_other_receipts = 0;
																$total_amount = 0;
																$total_expenditure = 0;
																$total_uc_submitted = 0;
																$total_uc_balance = 0;
																$total_closing_balance = 0;
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
																		<td>{{ $sl_table_row }}</td>
																		<td style="text-align:left;">
																				<?php
																				foreach($components as $values1)
																				{
																					if( $values->id == $values1->component_header_id )
																					{
                                                                                        if($values1->id == $val->component_id )
                                                                                            echo $values1->component_name;
																					}
																				}
																				?>
																		</td>
																		<td><?php echo $val->pt_noc; $total_pt_noc += $val->pt_noc; ?></td>
																		<td><?php echo $val->pt_nop; $total_pt_nop += $val->pt_nop; ?></td>
																		<td><?php echo $val->pa_noc; $total_pa_noc += $val->pa_noc; ?></td>
																		<td><?php echo $val->pa_nop; $total_pa_nop += $val->pa_nop; ?></td>
																		<td><?php echo $val->ob; $total_ob += $val->ob; ?></td>
																		<td><?php echo $val->goa_fund_received; $total_goa_fund_received += $val->goa_fund_received; ?></td>
																		<td><?php echo $val->other_receipts; $total_other_receipts += $val->other_receipts; ?></td>
																		<td style="color:#0061f3;">
																			<div id="tab_create_total_{{ $values->id.$table_row }}">
																			<?php 
																				echo $amount = round($val->ob + $val->goa_fund_received + $val->other_receipts, 3);
																				$total_amount += $amount;
																			?>
																			</div>
																		</td>
																		<td><?php echo $val->expenditure; $total_expenditure += $val->expenditure; ?></td>
																		<td><?php echo $val->uc_submitted; $total_uc_submitted += $val->uc_submitted; ?></td>
																		<td style="color:#0061f3;"><div id="tab_create_UC_Balance_{{ $values->id.$table_row }}"><?php echo $uc_balance = round($amount - $val->uc_submitted, 3); $total_uc_balance += $uc_balance; ?></div></td>
																		<td style="color:#0061f3;"><div id="tab_create_UC_Balance_{{ $values->id.$table_row }}"><?php echo $closing_balance = round($amount - $val->expenditure, 3); $total_closing_balance += $closing_balance; ?></div></td>
																	</tr>
													    <?php
																		}
                                                                    }
                                                        ?>
															<tr id="table_sub_total" style="background:#ffe2e285;">
																<td style="border-bottom:3px solid #83858f;"></td>
																<td style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;text-align:left;"><b>Total for {{ $values->header_name }} Sector : </b></td>
																<td style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;color:#0061f3;"><?php $grand_total_pt_noc += $total_pt_noc; echo $total_pt_noc; ?></td>
																<td style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;color:#0061f3;"><?php $grand_total_pt_nop += $total_pt_nop; echo $total_pt_nop; ?></td>
																<td style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;color:#0061f3;"><?php $grand_total_pa_noc += $total_pa_noc; echo $total_pa_noc; ?></td>
																<td style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;color:#0061f3;"><?php $grand_total_pa_nop += $total_pa_nop; echo $total_pa_nop; ?></td>
																<td style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;color:#0061f3;"><?php $grand_total_ob += $total_ob; echo number_format((float)$total_ob, 3, '.', ''); ?></td>
																<td style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;color:#0061f3;"><?php $grand_total_goa_fund_received += $total_goa_fund_received; echo number_format((float)$total_goa_fund_received, 3, '.', ''); ?></td>
																<td style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;color:#0061f3;"><?php $grand_total_other_receipts += $total_other_receipts; echo number_format((float)$total_other_receipts, 3, '.', ''); ?></td>
																<td style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;color:#0061f3;"><b><?php $grand_total_amount += $total_amount; echo number_format((float)$total_amount, 3, '.', ''); ?></b></td>
																<td style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;color:#0061f3;"><b><?php $grand_total_expenditure += $total_expenditure; echo number_format((float)$total_expenditure, 3, '.', ''); ?></b></td>
																<td style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;color:#0061f3;"><b><?php $grand_total_uc_submitted += $total_uc_submitted; echo number_format((float)$total_uc_submitted, 3, '.', ''); ?></b></td>
                                                                <td style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;color:#0061f3;"><b><?php $grand_total_uc_balance += $total_uc_balance; echo number_format((float)$total_uc_balance, 3, '.', ''); ?></b></td>
                                                                <td style="border-top:2px solid #83858f;border-bottom:3px solid #83858f;color:#0061f3;"><b><?php $grand_total_closing_balance += $total_closing_balance; echo number_format((float)$total_closing_balance, 3, '.', ''); ?></b></td>
															</tr>
															<tr>
																<td colspan="14" style="height:50px;"></td>
															</tr>
                                                        <?php
                                                                }
                                                                $table_row++;
                                                            }
                                                        ?>
															<tr style="background:#384059;" class="table-foot">
																<td></td>
																<td style="text-align:left;color:#fff;padding-top:15px;"><b>GRAND TOTAL</b></td>
																<td style="color:#fff;padding-top:15px;"><b><?php echo $grand_total_pt_noc; ?></b></td>
																<td style="color:#fff;padding-top:15px;"><b><?php echo $grand_total_pt_nop; ?></b></td>
																<td style="color:#fff;padding-top:15px;"><b><?php echo $grand_total_pa_noc; ?></b></td>
																<td style="color:#fff;padding-top:15px;"><b><?php echo $grand_total_pa_nop; ?></b></td>
																<td style="color:#fff;padding-top:15px;"><b><?php echo $grand_total_ob; ?></b></td>
																<td style="color:#fff;padding-top:15px;"><b><?php echo $grand_total_goa_fund_received; ?></b></td>
																<td style="color:#fff;padding-top:15px;"><b><?php echo $grand_total_other_receipts; ?></b></td>
																<td style="color:#fff;padding-top:15px;"><b><?php echo $grand_total_amount; ?></b></td>
																<td style="color:#fff;padding-top:15px;"><b><?php echo $grand_total_expenditure; ?></b></td>
																<td style="color:#fff;padding-top:15px;"><b><?php echo $grand_total_uc_submitted; ?></b></td>
                                                                <td style="color:#fff;padding-top:15px;"><b><?php echo $grand_total_uc_balance; ?></b></td>
                                                                <td style="color:#fff;padding-top:15px;"><b><?php echo $grand_total_closing_balance; ?></b></td>
															</tr>
															</tbody>
														</table>
													<br /><br /><br /><br />

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
                                                        table th, table td {
                                                            text-align:center;
                                                        }
														.table-foot > td {
															height:55px;
														
														}
													</style>
                                                </div>
                                            </div>
                                        </div>
									</div>
</body>
@endsection