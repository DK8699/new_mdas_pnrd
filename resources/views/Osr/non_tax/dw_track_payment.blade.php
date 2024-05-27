@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <script src="{{asset('mdas_assets/Chart.js-2.8.0/dist/Chart.min.js')}}"></script>
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.css" rel="stylesheet" type="text/css"/>
    <link href="{{asset('mdas_assets/css/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        body {
            background-color: #ddd;
        }

        /*card {
            border: solid 2px #ddd;
            background-color: #f6f6f6;
            color: #606267;

        }*/

        .card span {
            font-weight: bolder;
        }

        strong {
            color: red;
        }

        .headd {
            margin-top: 0px;
            color: #333;
            text-transform: uppercase;
            padding: 10px;
            /*text-shadow: 1.1px 1.1px rgb(49, 44, 44);*/
            background: #1db1d3;
            /*box-shadow: 0 0 2px 1px #046a77;*/
        }

        .mb40 {
            margin-bottom: 40px;
        }

        .Zebra_DatePicker_Icon_Wrapper {
            width: 100% !important;
        }

        .form-control {
            height: 28px;
            padding: 2px 5px;
            font-size: 12px;
        }

        label {
            font-size: 11px;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .m-cl {

            padding: 20px;
        }

        .card1 {
            text-align: center;
            border: 2px solid #fff;
            padding: 5px 3px;
        }

        .horubtn {
            float: right;
            padding: 3px 10px;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .pdiv {
            padding: 17px;
        }

        .table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {
            border: 1px solid #ddd;
            font-size: 13px;
        }

        .apList button.btn, .gpList button.btn {
            background-color: #ddd;
            color: #333;
            border-radius: 0;
        }
    </style>

@endsection

@section('content')

    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li><a href="{{url('osr/osr_panel')}}/{{encrypt($data['fy_id'])}}">OSR</a></li>
            <li class="active">Payment</li>
        </ol>
    </div>

    <div class="container mb40">

        {{----------------------------- ASSET AND FINANCIAL YEAR SUMMARY ---------------------------------------------------}}

        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label style="color: #ddd;">Asset Code</label>
                                <input type="text" class="form-control" value="{{$assetData->asset_code}}"
                                       readonly="readonly"/>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label style="color: #ddd;">Asset Name:</label>
                                <input type="text" class="form-control" value="{{$assetData->asset_name}}"
                                       readonly="readonly"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            {{--<form action="#" method="POST" id="financialBiddingForm">
                                {{csrf_field()}}--}}
                                <div class="form-group">
                                    <label style="color: #ddd;">Select Financial Year</label>
                                    <select name="osr_fy_id" id="fy_year_id" class="form-control" disabled="disabled">
                                        <option value="{{$osrFyYear->id}}">{{$osrFyYear->fy_name}}</option>
                                    </select>
                                </div>
                            {{--</form>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{----------------------------- GRAPH AND CALENDER VIEW ------------------------------------------------------------}}

        <div class="row">
            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading text-center">{{$osrFyYear->fy_name}}</div>
                        <div class="panel-body">
                            <div class="row">
                                @foreach($monthArray AS $key=>$value)
                                    <div class="col-md-3 m-cl">
                                        <div class="card1" @if(isset($settlementData->awarded_date) && $key==\Carbon\Carbon::parse($settlementData->awarded_date)->format('m')) style="border:2px solid green" @endif>{{$value}}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <canvas id="bar-chart" style="width:100%;height:253px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{----------------------------- BIDDING SUMMARY VIEW ---------------------------------------------------------------------}}

        <div class="row">
                <hr/>

                <div class="panel panel-primary">
                    <div class="panel-heading text-center"><h4>Payment Information </h4></div>
                    <div class="panel-body">


                        {{--INFORMATION PART--}}
                        @if($settlementData && $data['finalRecordData'] && $acceptedBidderData)

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <table class="table table-bordered">
                                    <tr class="bg-info">
                                        <td>Awarded Date</td>
                                        <td>Bidder Name</td>
                                        <td>Settled Amount</td>
                                        <td>Security Money Deposit</td>
                                        <td>Managed By</td>
                                        <td>Forfeited Withdrawn Bidders EMD</td>
                                    </tr>
                                    <tr>
                                        <td>{{$settlementData->awarded_date}}</td>
                                        <td>
                                            <span>{{$acceptedBidderData->b_f_name}} {{$acceptedBidderData->b_m_name}} {{$acceptedBidderData->b_l_name}}</span>
                                        </td>
                                        <td>@if($acceptedBidderData)<span class="money_txt">{{$data['finalRecordData']->settlement_amt}}</span>@else{{"NA"}}@endif
                                        </td>
                                        <td><span class="money_txt">{{$data['finalRecordData']->security_deposit_amt}}</span>
                                        </td>
                                        <td>{{$settlementData->managed_by}}</td>
                                        <td><span class="money_txt">{{$data['finalRecordData']->total_forfeited_emd_amt}}</span>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @endif



                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">

                                <table class="table table-bordered">
                                    <tr class="bg-info">

                                        <td>Gap Period Collection</td>


								@if($settlementData && $data['finalRecordData'])
								 	 @php
                                                $firstInstallmentLastDate = \Carbon\Carbon::parse($settlementData->awarded_date)->addDay(7)->format('d M Y');

                                                $preYear=\Carbon\Carbon::parse($osrFyYear->fy_from)->format('Y');
                                                $preTo=\Carbon\Carbon::parse($osrFyYear->fy_to)->format('Y');

                                                $secondInstallmentLastDate = \Carbon\Carbon::parse("01-10-".$preYear)->format('d M Y');
                                                $thirdInstallmentLastDate = \Carbon\Carbon::parse("01-01-".$preTo)->format('d M Y');
                                            @endphp
                                       		 <td>Settlement Amount Collection @if($data['finalRecordData'] && $data['finalRecordData']->payment_completed_status==1) <span class="badge" style="background: green;color:#fff"><i class="fa fa-check"></i> Successful</span>@else <span class="badge" style="background: red;color:#fff">Pending</span>@endif</td>
								 @else
								 	<td></td>
								 @endif
                                       <td>Defaulter Status: @if($data['finalRecordData'] && $data['finalRecordData']->defaulter_status==1)<span class="badge" style="background: red;color:#fff"><i class="fa fa-check"></i>Marked</span>@else <span style="color:green; font-weight:900">No defaulter marked yet. </span>@endif</td>

                                    </tr>

                                    <tr>
                                        {{--GAP PEROID COLLECTION--}}
                                        <td>
                                            @if(count($data['gapPeriodList']) > 0)
                                                <table class="table table-bordered">
                                                    <tr class="bg-primary">
                                                        <td>From</td>
                                                        <td>To</td>
                                                        <td>Amount (in Rs.)</td>
                                                    </tr>
                                                    @foreach($data['gapPeriodList'] AS $gap)
                                                        <tr class="text-right">
                                                            <td>{{\Carbon\Carbon::parse($gap->gap_from_date)->format('d M Y')}}</td>
                                                            <td>{{\Carbon\Carbon::parse($gap->gap_to_date)->format('d M Y')}}</td>
                                                            <td>{{$gap->receipt_amt}} <br/>
                                                                <a href="{{url('osr/non_tax/payment/gap_view')}}/{{encrypt($gap->id)}}/{{encrypt($assetData->asset_code)}}/{{base64_encode(base64_encode(base64_encode($osrFyYear->id)))}}" target="_blank">View</a>
															</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr class="bg-primary">
                                                        <td colspan="2">Total Gap Period Collection</td>
                                                        <td class="text-right">
                                                            <span class="money_txt">@if($data['finalRecordData']){{$data['finalRecordData']->tot_gap_collected_amt}}@endif</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            @endif
                                            <button type="button" data-toggle="modal" data-target="#addGap">
                                                <i class="fa fa-plus"></i>
                                                Add Gap Collection
                                            </button>
                                        </td>

                                        {{-- GAP PEROID COLLECTION ENDED --}}

                                        {{--INSTALLMENT COLLECTION--}}
                                        {{--@if(isset($settlementData->final_report_path) && ($data['finalRecordData']->bidding_status == 1))--}}
								 @if(isset($settlementData) && ($data['finalRecordData']))
                                            <td>
                                                <table class="table table-bordered">
                                                    <tr class="bg-primary">
                                                        <td>
                                                            #
                                                        </td>
                                                        <td>
                                                            Last Date
                                                        </td>
                                                        <td>
                                                            Payment Due (in Rs.)
                                                        </td>
                                                        <td>
                                                            Amount Paid (in Rs.)
                                                        </td>
											  @if($users->mdas_master_role_id <> 4)
											  <td>
											  	Action
											  </td>
											  @endif
                                                    </tr>

                                                    @foreach($data['allIns'] AS $instalment)
                                                        @php
                                                            $intallmentId = encrypt($instalment->id);
                                                           
                                                            
                                                        @endphp
                                                       
                                                    <tr>
                                                        
                                                        <td>
                                                            @if(isset($data['subInsData'][$instalment->id]['data']->receipt_amt) || ($data['finalRecordData']->payment_completed_status == 1))
                                                                {{$instalment->name}}
                                                            @else
												 	@if($data['level']<>"GP")
                                                                <a href="#" data-id="{{($instalment->id)}}" data-ins="{{$instalment->name}}" class="addCollect" data-ip="{{$instalment->id}}">{{$instalment->name}}</a>
                                                                {{-- <a href="#" data-id="{{encrypt($instalment->id)}}" data-ins="{{$instalment->name}}" class="addCollect" data-ip="{{$instalment->id}}">{{$instalment->name}}</a> --}}
												 	@else
												 	{{-- <a href="#" data-id="{{$instalment->id}}" data-ins="{{$instalment->name}}" class="call_addInstallment" data-ip="{{$instalment->id}}">{{$instalment->name}}</a> --}}
												 	<a href="#" data-id="{{encrypt($instalment->id)}}" data-ins="{{$instalment->name}}" class="call_addInstallment" data-ip="{{$instalment->id}}">{{$instalment->name}}</a>
												 	@endif
                                                            @endif
                                                        </td>
                                                        {{-- <td>
                                                            @if($instalment->id==1)
                                                            {{$firstInstallmentLastDate}}
                                                            @elseif($instalment->id==2)
                                                            {{$secondInstallmentLastDate}}
                                                            @elseif($instalment->id==3)
                                                            {{$thirdInstallmentLastDate}}
                                                            @else
                                                            
                                                            @endif
                                                        </td> --}}
                                                        <td>
                                                            @if($instalment->id==1)
                                                            {{$firstInstallmentLastDate}}
                                                            @elseif($instalment->id==2)
                                                            {{$secondInstallmentLastDate}}
                                                            @else
                                                            {{$thirdInstallmentLastDate}}
                                                            @endif
                                                        </td>
                                                        <td>

                                                            @if($data['finalRecordData'])
                                                                @if($instalment->id==1)
                                                                        <span class="money_txt">{{$data['finalRecordData']->settlement_amt}}</span>
                                                                @elseif($instalment->id==2)

                                                                    @if(isset($data['subInsData'][1]['data']->receipt_amt))
                                                                        @php $insReceived= $data['subInsData'][1]['data']->receipt_amt; @endphp
                                                                            <span class="money_txt">{{$data['finalRecordData']->settlement_amt - $insReceived}}</span>
                                                                    @endif

                                                                @else

                                                                    @if(isset($data['subInsData'][1]['data']->receipt_amt) && isset($data['subInsData'][2]['data']->receipt_amt))
                                                                        @php $insReceived= $data['subInsData'][1]['data']->receipt_amt + $data['subInsData'][2]['data']->receipt_amt; @endphp
                                                                        <span class="money_txt">{{$data['finalRecordData']->settlement_amt - $insReceived}}</span>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td class="text-right">
                                                            @if(isset($data['subInsData'][$instalment->id]['data']->receipt_amt))
                                                                @php $insReceived= $data['subInsData'][$instalment->id]['data']->receipt_amt; @endphp
                                                                <span class="money_txt">{{$insReceived}}</span>

													@if($users->mdas_master_role_id <> 4)
												 		@if($data['subInsData'][$instalment->id]['data']->sharing_status == 1 )
															 <span class="badge" style="background: green;color:#fff"><i class="fa fa-check"></i> SHARING DONE</span>
												 		@else
												 <span class="badge" style="background: blue;color:#fff">
                                                    <a href="#" style="color: inherit;" data-id="{{encrypt($instalment->id)}}" data-ins="{{$instalment->name}}" class="call_addInstallment" data-ip="{{$instalment->id}}" data-ins_amt="{{$insReceived}}">Share</a>
                                                </span>
												 		@endif

													@endif

                                                            @endif
                                                        </td>
											  @if($users->mdas_master_role_id <> 4)
											  <td>
											   @if(isset($data['subInsData'][$instalment->id]['data']->receipt_amt))
											  	 @if($data['subInsData'][$instalment->id]['data']->sharing_status == 1 )

												   <a target="_blank" href="{{url('osr/non_tax/payment/view')}}/{{encrypt($instalment->id)}}/{{encrypt($assetData->asset_code)}}/{{base64_encode(base64_encode(base64_encode($osrFyYear->id)))}}">
													View
												   </a>
                                                            @else
                                                                Not Available
                                                            @endif
											  @endif
											  </td>
											  @endif
                                                    </tr>
                                                    @endforeach

                                                    <tr class="bg-info text-right">
                                                        <td colspan="3" class="text-right">
                                                            Collection From Installments
                                                        </td>
                                                        <td>
                                                            <span class="money_txt">{{$data['finalRecordData']->tot_ins_collected_amt}}</span>
                                                        </td>
														<td></td>
                                                    </tr>

                                                    {{--@if($data['finalRecordData']->defaulter_status==1)
                                                        <tr class="text-right">
                                                            <td colspan="3" class="text-right">
                                                                Forfeited Security Money Deposit
                                                            </td>
                                                            <td>
                                                                <span class="money_txt">{{$data['finalRecordData']->security_deposit_amt}}</span>
                                                            </td>
                                                        </tr>
                                                    @endif

                                                    @if($data['finalRecordData']->forfeited_emd_sharing_status==1)

                                                    <tr class="text-right">
                                                        <td colspan="3" class="text-right">
                                                            Forfeited Withdrawn Bidders EMD
                                                        </td>
                                                        <td>
                                                            <span class="money_txt">{{$data['finalRecordData']->total_forfeited_emd_amt}}</span>
                                                        </td>
                                                    </tr>

                                                    @endif--}}

                                                    {{--@if($data['finalRecordData']->rebate_status==1)

                                                        <tr class="text-right">
                                                            <td colspan="3">
                                                                Rebate Amount
                                                            </td>
                                                            <td>
                                                                <span class="money_txt">{{$data['finalRecordData']->settlement_amt-$data['finalRecordData']->tot_ins_collected_amt}}</span>
                                                            </td>
                                                        </tr>

                                                    @endif--}}
                                                </table>
                                               
                                                <script>

                                                

                                                </script>
                                            </td>
                                            <td>
                                                <table class="table table-bordered">

                                                    {{--@if($data['finalRecordData']->total_forfeited_emd_amt > 0)
                                                        <tr class="bg-primary">
                                                            <td>
                                                                <span title="Withdrawn bidders EMD higher than the settlement amount" style="cursor:pointer;">Forfeited EMD</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td>
                                                                @if($data['finalRecordData']->forfeited_emd_sharing_status==0)
                                                                    <button type="button" data-toggle="modal" data-target="#forfeit_earnest_money_modal">Distribute Payment</button>
                                                                @else
                                                                    <button type="button" class="bg-success">
                                                                        <i class="fa fa-check"></i>
                                                                        Distribution Done
                                                                    </button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif--}}

                                                    {{-------------------DEFAULTER SECTION----------------------------}}
                                                    @if($data['finalRecordData'])
                                                    <tr class="bg-primary">
                                                        <td>
                                                            Forfeit Security Money Deposit
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            @if($data['finalRecordData']->defaulter_status==0)
                                                                <form action="#" method="POST" id="mark_defaulter_save" autocomplete="off">
                                                                    <input type="hidden" name="asset_code" value="{{encrypt($assetData->asset_code)}}"/>
                                                                    <input type="hidden" name="fy_id" value="{{encrypt($osrFyYear->id)}}"/>

                                                                    <button type="submit">Mark As Defaulter</button>
                                                                </form>
                                                            @else
                                                                <button type="button" class="bg-success">
                                                                    <i class="fa fa-check"></i>
                                                                    Marked As Defaulter
                                                                </button>


                                                                <form action="#" method="POST" id="bakijai_save" autocomplete="off" style="margin-top:20px">
                                                                    <input type="hidden" name="asset_code" value="{{encrypt($assetData->asset_code)}}"/>
                                                                    <input type="hidden" name="fy_id" value="{{encrypt($osrFyYear->id)}}"/>

                                                                    <textarea class="form-control" rows="5" name="bakijai_details" id="bakijai_details" placeholder="Enter the detials of bakijai case">@if($data['finalRecordData']->bakijai_status==1){{$data['finalRecordData']->bakijai_details}}@endif</textarea>

                                                                    <button type="submit" style="margin-top:10px">Update Bakijai Details</button>
                                                                </form>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    {{-------------------DEFAULTER SECTION ENDED----------------------}}
                                                </table>
                                            </td>
								  @else
								 <td colspan="2">
									<div class="alert alert-info">
										<i class="fa fa-info-circle" aria-hidden="true"></i>
										<strong>Bidding</strong> process not completed yet!
									</div>
								</td>
								 @endif

                                    </tr>
                                </table>

                            </div>
                        </div>


                    </div>
                </div>


            @if($settlementData && $data['finalRecordData'])

		   {{------------------------------------------Add collection------------------------------------------------}}
		    <div id="addCollection" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" id="ins_modal_title1"></h4>
                        </div>
                        <form action="#" method="POST" id="collectionForm">
                        <div class="modal-body">
                            <input type="hidden" name="ins" id="ins_modal_id1" value=""/>
                            <input type="hidden" name="asset_code" value="{{encrypt($assetData->asset_code)}}"/>
                            <input type="hidden" name="fy_id" value="{{encrypt($osrFyYear->id)}}"/>

                            <div class="row" id="amt_receipt">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Date of receipt <strong>*</strong></label>
                                        <input type="text" name="ins_receipt_date" id="ins_receipt_date1" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Amount receipt <strong>*</strong></label>
                                        <input type="text" name="ins_receipt_amount1" id="ins_receipt_amount1" class="form-control money"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-block">
                                Submit
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>




                {{------------------------- ADD INSTALMENTS --------------------------------------------------------------}}
                <div id="addInstallment" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" id="ins_modal_title"></h4>
                        </div>
                        <form action="#" method="POST" id="installmentForm">
                        <div class="modal-body">
                            <input type="hidden" name="ins" id="ins_modal_id" value=""/>
                            <input type="hidden" name="asset_code" value="{{encrypt($assetData->asset_code)}}"/>
                            <input type="hidden" name="fy_id" value="{{encrypt($osrFyYear->id)}}"/>

                            {{--<div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group" id="payment_ins">
                                        <label class="radio-inline" id="partial_payment_radio">
                                            <input type="radio" name="ins_payment" value="P">Partial Payemnt
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="ins_payment" value="F">Full Payment
                                        </label>
                                    </div>
                                </div>
                            </div>--}}

                            <div class="row" id="amt_receipt">
						 @if(!($data['level']<>"GP"))
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Date of receipt <strong>*</strong></label>
                                        <input type="text" name="ins_receipt_date" id="ins_receipt_date" class="form-control"/>
                                    </div>

                                </div>
						   <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Amount receipt <strong>*</strong></label>
                                        <input type="text" name="ins_receipt_amount" id="ins_receipt_amount" value="" class="form-control money" />
                                    </div>
                                </div>
					   	 @endif
                            </div>

					@if($data['level']<>"GP")
					    <input type="hidden" name="ins_receipt_amount" id="ins_receipt_amount" value="" class="form-control money" />
					    <div class="row">
					    	  <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Receipt Amount <p style="font-size: 15px;color:#333;margin: 0;padding: 0"><span id="ins_receipt"></span></p></label>

                                    </div>
                                </div>
					    </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Estimated Amount <p style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">20% of Installent</p></label>
                                        <p style="margin: 0;padding: 0"><span id="ins_es_zp_share"></span></p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>ZP Share <strong>*</strong></label>
                                        <input type="text" name="ins_zp_share" id="ins_zp_share" class="form-control money"/>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Estimated Amount <p style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">40% of Installent</p></label>
                                        <p style="margin: 0;padding: 0"><span id="ins_es_ap_share"></span></p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>AP Share <strong>*</strong></label>
                                        <input type="text" name="ins_ap_share" id="ins_ap_share" class="form-control money"/>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Estimated Amount <p style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">40% of Installent</p></label>
                                        <p style="margin: 0;padding: 0"><span id="ins_es_gp_share"></span></p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>GP Share <strong>*</strong></label>
                                        <input type="text" name="ins_gp_share" id="ins_gp_share" class="form-control money"/>
                                    </div>
                                </div>
                            </div>

							@endif

                            <div class="row">
                                @if($data['level']=="ZP")
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label>AP Share Distributed Among <strong>*</strong></label>
                                        <div class="form-group">
                                            <select class="apList form-control" id="ins_ap_list" name="ins_ap_list" multiple data-selected-text-format="count" data-count-selected-text="AP ({0})">
                                                @foreach($data['apList'] AS $li_a)
                                                    <option value="{{$li_a->id}}">{{$li_a->anchalik_parishad_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label>GP Share Distributed Among <strong>*</strong></label>
                                        <div class="form-group">
                                            <select class="gpList form-control" id="ins_gp_list" name="ins_gp_list" data-live-search="true" multiple data-selected-text-format="count" data-count-selected-text="GP ({0})">
                                                @foreach($data['gpList'] AS $li_m)
                                                    <optgroup label="{{$li_m['ap_name']}}">
                                                        @foreach($li_m['list'] AS $li_g)
                                                            <option value="{{$li_g->gram_panchyat_id}}">{{$li_g->gram_panchayat_name}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @elseif($data['level']=="AP")
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <label>GP Share Distributed Among <strong>*</strong></label>
                                        <div class="form-group">
                                            <select class="gpList form-control" id="ins_gp_list" name="ins_gp_list" multiple data-selected-text-format="count" data-count-selected-text="GP ({0})">
                                                @foreach($data['gpList'] AS $li_g)
                                                    <option selected="selected" value="{{$li_g->gram_panchyat_id}}">{{$li_g->gram_panchayat_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>NEFT No./RTGS No. <strong>*</strong></label>
                                        <input type="text" name="ins_transaction_no" id="ins_transaction_no" class="form-control"/>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Transaction Date <strong>*</strong></label>
                                        <input type="text" name="ins_transaction_date" id="ins_transaction_date" class="form-control date"/>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Sharing remarks</label>
                                        <textarea row="3" name="ins_sharing_remark" id="ins_sharing_remark" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-block">
                                Submit
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

                {{------------------------- ADD FORFEITED EMD ---------------------------------------------------------}}
                @if($data['finalRecordData']->total_forfeited_emd_amt > 0)
                    <div class="modal fade" id="forfeit_earnest_money_modal" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-mm">
                        <div class="modal-content">
                            <div class="modal-header  bg-primary">
                                <button type="button" class="btn bg-red modal-close" data-dismiss="modal"
                                        aria-hidden="true">
                                    <i class="fa fa-close"></i>
                                </button>
                                <h5 class="modal-title text-center">Forfeited Withdrawn Bidders EMD</h5>
                            </div>
                            <form action="#" method="POST" id="forfeited_earnest_money_save" autocomplete="off">
                                <input type="hidden" name="asset_code" value="{{encrypt($assetData->asset_code)}}"/>
                                <input type="hidden" name="fy_id" value="{{encrypt($osrFyYear->id)}}"/>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label>Forfeited EMD</label>
                                                <p>
                                                    <span class="money_txt">{{$data['finalRecordData']->total_forfeited_emd_amt}}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label><span style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">Estimated ZP Share (20%)</span></label>
                                                <p>
                                                    <span class="money_txt">{{round($data['finalRecordData']->total_forfeited_emd_amt*20/100, 2)}}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label><span style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">Estimated AP Share (40%)</span></label>
                                                <p>
                                                    <span class="money_txt">{{round($data['finalRecordData']->total_forfeited_emd_amt*40/100, 2)}}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label><span style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">Estimated GP Share (40%)</span></label>
                                                <p>
                                                    <span class="money_txt">{{round($data['finalRecordData']->total_forfeited_emd_amt*40/100, 2)}}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>ZP Share <strong>*</strong></label>
                                                <input type="text" class="form-control money" id="f_zp_share" name="f_zp_share" value=""/>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>AP Share <strong>*</strong></label>
                                                <input type="text" class="form-control money" id="f_ap_share" name="f_ap_share" value=""/>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>GP Share <strong>*</strong></label>
                                                <input type="text" class="form-control money" id="f_gp_share" name="f_gp_share" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @if($data['level']=="ZP")
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label>AP Share Distributed Among <strong>*</strong></label>
                                                <div class="form-group">
                                                    <select class="apList form-control" id="f_ap_list" name="f_ap_list" multiple data-selected-text-format="count" data-count-selected-text="AP ({0})">
                                                        @foreach($data['apList'] AS $li)
                                                            <option selected="selected" disabled="disabled"
                                                                    value="{{$li->id}}">{{$li->anchalik_parishad_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label>GP Share Distributed Among <strong>*</strong></label>
                                                <div class="form-group">
                                                    <select class="gpList form-control" id="f_gp_list" name="f_gp_list" data-live-search="true" multiple data-selected-text-format="count" data-count-selected-text="GP ({0})">
                                                        @foreach($data['gpList'] AS $li_m)
                                                            <optgroup label="{{$li_m['ap_name']}}">
                                                                @foreach($li_m['list'] AS $li)
                                                                    <option selected="selected" disabled="disabled"
                                                                            value="{{$li->id}}">{{$li->gram_panchayat_name}}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @elseif($data['level']=="AP")
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label>GP Share Distributed Among <strong>*</strong></label>
                                                <div class="form-group">
                                                    <select class="gpList form-control" id="f_gp_list" name="f_gp_list" multiple data-selected-text-format="count" data-count-selected-text="GP ({0})">
                                                        @foreach($data['gpList'] AS $li)
                                                            <option selected="selected" disabled="disabled"
                                                                    value="{{$li->id}}">{{$li->gram_panchayat_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>RTGS No./NEFT No.</label>
                                                <input type="text" class="form-control" id="f_transaction_no" name="f_transaction_no"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Transaction Date</label>
                                                <input type="text" class="form-control date" id="f_transaction_date" name="f_transaction_date" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Remarks of Sharing</label>
                                                <textarea rows="3" type="text" class="form-control" id="f_remarks" name="f_remarks"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-save">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                {{--<div class="modal fade" id="defaulter_modal" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-mm">
                        <div class="modal-content">
                            <div class="modal-header  bg-primary">
                                <button type="button" class="btn bg-red modal-close" data-dismiss="modal"
                                        aria-hidden="true">
                                    <i class="fa fa-close"></i>
                                </button>
                                <h5 class="modal-title text-center">Security Money Deposit Sharing</h5>
                            </div>
                            <form action="#" method="POST" id="mark_defaulter_save" autocomplete="off">
                                <div class="modal-body">
                                    <div class="row">
                                        <input type="hidden" name="asset_code" value="{{encrypt($assetData->asset_code)}}"/>
                                        <input type="hidden" name="fy_id" value="{{encrypt($osrFyYear->id)}}"/>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label>Security Money Deposit</label>
                                                <p>
                                                    <span class="money_txt">{{$data['finalRecordData']->security_deposit_amt}}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label><span style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">Estimated ZP Share (20%)</span></label>
                                                <p>
                                                    <span class="money_txt">{{round($data['finalRecordData']->security_deposit_amt*20/100, 2)}}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label><span style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">Estimated AP Share (40%)</span></label>
                                                <p>
                                                    <span class="money_txt">{{round($data['finalRecordData']->security_deposit_amt*40/100, 2)}}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div class="form-group">
                                                <label><span style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">Estimated GP Share (40%)</span></label>
                                                <p>
                                                    <span class="money_txt">{{round($data['finalRecordData']->security_deposit_amt*40/100, 2)}}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>ZP Share <strong>*</strong></label>
                                                <input type="text" class="form-control money" id="d_zp_share" name="d_zp_share" required/>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>AP Share <strong>*</strong></label>
                                                <input type="text" class="form-control money" id="d_ap_share" name="d_ap_share" required/>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>GP Share <strong>*</strong></label>
                                                <input type="text" class="form-control money" id="d_gp_share" name="d_gp_share" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @if($data['level']=="ZP")
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label>AP Share Distributed Among <strong>*</strong></label>
                                                <div class="form-group">
                                                    <select class="apList form-control" id="d_ap_list" name="d_ap_list" multiple data-selected-text-format="count" data-count-selected-text="AP ({0})">
                                                        @foreach($data['apList'] AS $li)
                                                            <option selected="selected" disabled="disabled"
                                                                    value="{{$li->id}}">{{$li->anchalik_parishad_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label>GP Share Distributed Among <strong>*</strong></label>
                                                <div class="form-group">
                                                    <select class="gpList form-control" id="d_gp_list" name="d_gp_list" data-live-search="true" multiple data-selected-text-format="count" data-count-selected-text="GP ({0})">
                                                        @foreach($data['gpList'] AS $li_m)
                                                            <optgroup label="{{$li_m['ap_name']}}">
                                                                @foreach($li_m['list'] AS $li)
                                                                    <option selected="selected" disabled="disabled" value="{{$li->id}}">{{$li->gram_panchayat_name}}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @elseif($data['level']=="AP")
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label>GP Share Distributed Among <strong>*</strong></label>
                                                <div class="form-group">
                                                    <select class="gpList form-control" id="d_gp_list" name="d_gp_list" multiple data-selected-text-format="count" data-count-selected-text="GP ({0})">
                                                        @foreach($data['gpList'] AS $li)
                                                            <option selected="selected" disabled="disabled"
                                                                    value="{{$li->id}}">{{$li->gram_panchayat_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>RTGS No./NEFT No.</label>
                                                <input type="text" class="form-control" id="d_transaction_no" name="d_transaction_no" required/>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Transaction Date</label>
                                                <input type="text" class="form-control date" id="d_transaction_date" name="d_transaction_date" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Remarks of Sharing</label>
                                                <textarea rows="3" type="text" class="form-control" id="d_sharing_remarks" name="d_sharing_remarks"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-save">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>--}}
            @endif
           {{------------------------- GAP PERIOD COLLECTION --------------------------------------------------------------}}

            <div class="modal fade" id="addGap" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-mm">
                        <div class="modal-content">
                            <div class="modal-header  bg-primary">
                                <button type="button" class="btn bg-red modal-close" data-dismiss="modal"
                                        aria-hidden="true">
                                    <i class="fa fa-close"></i>
                                </button>
                                <h5 class="modal-title text-center">Gap Period Collection</h5>
                            </div>
                            <form class="" action="#" method="POST" id="gap_period" autocomplete="off">
                            <div class="modal-body">
                                <input type="hidden" name="fy_id" value="{{encrypt($osrFyYear->id)}}"/>
                                <input type="hidden" name="asset_code" value="{{encrypt($assetData->asset_code)}}"/>

                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>From <strong>*</strong></label>
                                            <input type="text" class="form-control date" name="gap_from_date" id="gap_from_date" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>To <strong>*</strong></label>
                                            <input type="text" class="form-control date" name="gap_to_date" id="gap_to_date" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label>Receipt Amount <strong>*</strong></label>
                                            <input type="text" class="form-control money" name="gap_receipt_amount" id="gap_receipt_amount" required/>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <label>Estimated ZP Share <span style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">(20%)</span></label>
                                        <p style="margin: 0;padding: 0"><span class="money_txt" id="gap_es_zp_share"></span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <label>Estimated AP Share <span style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">(40%)</span></label>
                                        <p style="margin: 0;padding: 0"><span class="money_txt" id="gap_es_ap_share"></span></p>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <label>Estimated GP Share <span style="font-size: 9px;font-weight: 300;color:#333;margin: 0;padding: 0">(40%)</span></label>
                                        <p style="margin: 0;padding: 0"><span class="money_txt" id="gap_es_gp_share"></span></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>ZP Share <strong>*</strong></label>
                                            <input type="text" class="form-control money" name="gap_zp_share" id="gap_zp_share" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>AP Share <strong>*</strong></label>
                                            <input type="text" class="form-control money" name="gap_ap_share" id="gap_ap_share" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>GP Share <strong>*</strong></label>
                                            <input type="text" class="form-control money" name="gap_gp_share" id="gap_gp_share" required/>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    @if($data['level']=="ZP")
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <label>AP Share Distributed Among <strong>*</strong></label>
                                            <div class="form-group">
                                                <select class="apList form-control" id="gap_ap_list" name="gap_ap_list" multiple data-selected-text-format="count" data-count-selected-text="AP ({0})">
                                                    @foreach($data['apList'] AS $li_a)
                                                        <option value="{{$li_a->id}}">{{$li_a->anchalik_parishad_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <label>GP Share Distributed Among <strong>*</strong></label>
                                            <div class="form-group">
                                                <select class="gpList form-control" id="gap_gp_list" name="gap_gp_list" data-live-search="true" multiple data-selected-text-format="count" data-count-selected-text="GP ({0})">
                                                    @foreach($data['gpList'] AS $li_m)
                                                        <optgroup label="{{$li_m['ap_name']}}">
                                                            @foreach($li_m['list'] AS $li_g)
                                                                <option value="{{$li_g->gram_panchyat_id}}">{{$li_g->gram_panchayat_name}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @elseif($data['level']=="AP")
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <label>GP Share Distributed Among <strong>*</strong></label>
                                            <div class="form-group">
                                                <select class="gpList form-control" id="gap_gp_list" name="gap_gp_list" multiple data-selected-text-format="count" data-count-selected-text="GP ({0})">
                                                    @foreach($data['gpList'] AS $li_g)
                                                        <option value="{{$li_g->gram_panchyat_id}}">{{$li_g->gram_panchayat_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label>RTGS No./NEFT No.</label>
                                            <input type="text" class="form-control" name="gap_transaction_no" id="gap_transaction_no" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Transaction Date</label>
                                            <input type="text" class="form-control date" name="gap_transaction_date" id="gap_transaction_date" required/>
                                        </div>
                                    </div>
                                    {{--<div class="col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Collected By <strong>*</strong></label>
                                            <select class="form-control" name="gap_collected_by" required>
                                                <option value="">--Select--</option>
                                                <option value="ZP">ZP</option>
                                                <option value="AP">AP</option>
                                                <option value="GP">GP</option>
                                                <option value="OTHER">Other</option>
                                            </select>
                                        </div>
                                    </div>--}}
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Remarks of Sharing</label>
                                            <textarea rows="3" type="text" class="form-control" name="gap_sharing_remarks" id="gap_sharing_remarks"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-save">
                                        Submit
                                    </button>
                                </div>

                            </form>
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
            <script src="{{asset('mdas_assets/js/bootstrap-select.min.js')}}"></script>
            <script type="application/javascript">

                $('.date').Zebra_DatePicker({
                    format: 'Y-m-d'
                });

                @if($settlementData && $data['finalRecordData'] && $acceptedBidderData)
                $('#ins_receipt_date1').Zebra_DatePicker({
                   // direction: ['{{$settlementData->awarded_date}}', '@if($osrFyYear){{$osrFyYear->fy_to}}@endif']
                });
			   $('#ins_receipt_date').Zebra_DatePicker({
                   // direction: ['{{$settlementData->awarded_date}}', '@if($osrFyYear){{$osrFyYear->fy_to}}@endif']
                });
                @endif

                $('.apList').selectpicker();
                $('.gpList').selectpicker();


                var indianRupeeFormatter = OSREC.CurrencyFormatter.getFormatter({
                    currency: 'INR',
                    symbol: '',
                });

                var indianRupeeFormatterText = OSREC.CurrencyFormatter.getFormatter({
                    currency: 'INR',
                    symbol: '₹',
                });

                $('.money').on('blur', function (e) {
                    e.preventDefault();
                    var value = OSREC.CurrencyFormatter.parse($(this).val(), {locale: 'en_IN'});
                    var formattedVal = indianRupeeFormatter(value);
                    $(this).val(formattedVal);
                });

                OSREC.CurrencyFormatter.formatAll({
                    selector: '.money_txt',
                    currency: 'INR'
                });


                //      Bar chart
                new Chart(document.getElementById("bar-chart"), {
                    type: 'bar',
                    data: {
                        labels: ["Gap Period Collection", "Forfeited EMD", "Forfeited Security Money Deposit", "BID Collection"],
                        datasets: [
                            {
                                label: "₹ (In Rupees)",
                                backgroundColor: ["#3e95cd", "#c45850", "#c45850", "#3cba9f"],
                                data: [
                                    @if($data['finalRecordData']){{$data['finalRecordData']->tot_gap_collected_amt}}@else{{0}}@endif,
                                    @if($data['finalRecordData'] && $data['finalRecordData']){{$data['finalRecordData']->total_forfeited_emd_amt}}@else{{0}}@endif,
                                    @if($data['finalRecordData'] && $data['finalRecordData']->defaulter_status==1){{$data['finalRecordData']->security_deposit_amt}}@else{{0}}@endif,
                                    @if($data['finalRecordData']){{$data['finalRecordData']->tot_ins_collected_amt}}@else{{0}}@endif
                                ]
                            }
                        ]
                    },
                    options: {
                        legend: {display: false},
                        title: {
                            display: true,
						    text: '₹ (In Rupees)'
                        }
                    }
                });




                //IF SETTLEMENT DATA EXISTS ****************************************************************************

                @if($settlementData && $data['finalRecordData'])


			  //----collection modal

			  $('.addCollect').on('click', function(e){
                    e.preventDefault();
                    var id= $(this).data('id');
                    var ins= $(this).data('ins');
                    var instalment_ip= $(this).data('ip');


                    $('#ins_modal_title1').text('');
                    $('#ins_modal_title1').text(ins);
                    $('#ins_modal_id1').val('');
                    $('#ins_modal_id1').val(id);

                    $('#collectionForm')[0].reset();

                    $('#addCollection').modal('show');
                });

			  $("#collectionForm").validate({
						rules: {
							ins_receipt_date: {
								required: true
							},
							ins_receipt_amount: {
								required: true
							},
						},
					});


			  $('#collectionForm').on('submit', function (e) {
                    e.preventDefault();

                    /*alert($("#ins_gp_list").val());*/
                    var formData = new FormData(this);

                    $('.form_errors').remove();

                    if ($('#collectionForm').valid()) {
                        $('.page-loader-wrapper').fadeIn();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '{{route('osr.non_tax.asset.payment.collection')}}',
                            dataType: "json",
                            data: formData,
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                console.log(data);
                                if (data.msgType == true) {
                                    swal("Success", data.msg, "success");
                                    location.reload();
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
                    }
                });

			  //--------Sharing modal

                $('.call_addInstallment').on('click', function(e){
                    console.log("Hiiiiiiiiiii");
                    e.preventDefault();
                    var id= $(this).data('id');
                    var ins= $(this).data('ins');
                    var instalment_ip= $(this).data('ip');
					var ins_amt = $(this).data('ins_amt');

				$('#ins_receipt_amount').val((ins_amt));
				$('#ins_receipt').text(indianRupeeFormatterText(ins_amt));
                    $('#ins_es_zp_share').text(indianRupeeFormatterText(20 / 100 * ins_amt));
                    $('#ins_es_ap_share').text(indianRupeeFormatterText(40 / 100 * ins_amt));
                    $('#ins_es_gp_share').text(indianRupeeFormatterText(40 / 100 * ins_amt));

                    $('#ins_modal_title').text('');
                    $('#ins_modal_title').text(ins);
                    $('#ins_modal_id').val('');
                    $('#ins_modal_id').val(id);


                    if(instalment_ip == 3 ){
                        $('#partial_payment_radio').hide();
                    }else{
                        $('#partial_payment_radio').show();
                    }

                    $('#installmentForm')[0].reset();

                    $('#addInstallment').modal('show');
                });


                $('#ins_receipt_amount').on('change', function (e) {
                    e.preventDefault();
                    

                    var amt = $('#ins_receipt_amount').val();
                    amt = OSREC.CurrencyFormatter.parse(amt, {locale: 'en_IN'});

                    $('#ins_es_zp_share').text(indianRupeeFormatterText(20 / 100 * amt));
                    $('#ins_es_ap_share').text(indianRupeeFormatterText(40 / 100 * amt));
                    $('#ins_es_gp_share').text(indianRupeeFormatterText(40 / 100 * amt));


                    var payment= $("input[name='ins_payment']:checked").val();

                    var req_amt= {{$data['finalRecordData']->settlement_amt-$data['finalRecordData']->tot_ins_collected_amt}};

                    if(amt > req_amt || amt==0){

                        if(amt==0){
                            alert("Amount can not be zero");
                        }else{
                            alert("Amount is greater than the amount required");
                        }

                        $('#ins_receipt_amount').val('');

                        $('#ins_es_zp_share').text('');
                        $('#ins_es_ap_share').text('');
                        $('#ins_es_gp_share').text('');
                    }

                    //call_rebate(payment, amt, req_amt);
                });


				@if($data['level']<>"GP")
					$("#installmentForm").validate({
						rules: {
							ins_receipt_date: {
								required: true
							},
							ins_receipt_amount: {
								required: true
							},
							ins_zp_share: {
								required: true
							},
							ins_ap_share: {
								required: true
							},
							ins_gp_share: {
								required: true
							},
							ins_transaction_no: {
								required: true
							},
							ins_transaction_date: {
								required: true
							}
						},
					});

				@else
                    $("#installmentForm").validate({
                        rules: {
                            ins_receipt_date: {
                                required: true
                            },
                            ins_receipt_amount: {
                                required: true
                            },
                            ins_transaction_no: {
                                required: true
                            },
                            ins_transaction_date: {
                                required: true
                            }
                        },
                    });

			   @endif

                $('#installmentForm').on('submit', function (e) {
                    e.preventDefault();

                    /*alert($("#ins_gp_list").val());*/
                    var formData = new FormData(this);
                    formData.append('ins_ap_list', $("#ins_ap_list").val() );
                    formData.append('ins_gp_list', $("#ins_gp_list").val() );



                    $('.form_errors').remove();

                    if ($('#installmentForm').valid()) {
                        $('.page-loader-wrapper').fadeIn();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '{{route('osr.non_tax.asset.payment.instalment')}}',
                            dataType: "json",
                            data: formData,
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                if (data.msgType == true) {
                                    swal("Success", data.msg, "success");
                                    location.reload();
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
                    }
                });


                //FORFEITED EMD-----------------------------------------------------------------------------------------

                $("#forfeited_earnest_money_save").validate({
                    rules: {
                        zp_share: {
                            required: true
                        },
                        ap_share: {
                            required: true
                        },
                        gp_share: {
                            required: true
                        },
                        transaction_no: {
                            required: true
                        },
                        transaction_date: {
                            required: true
                        }
                    }
                });

                $('#forfeited_earnest_money_save').on('submit', function (e) {
                    e.preventDefault();
                    if ($('#forfeited_earnest_money_save').valid()) {
                        $('.page-loader-wrapper').fadeIn();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '{{route('osr.non_tax.asset.payment.forfeited_earnest_money_save')}}',
                            dataType: "json",
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                if (data.msgType == true) {
                                    swal("Success", data.msg, "success")
                                        .then((value)=>{
                                        location.reload();
                                    });
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
                    }

                });

                //FORFEITED EMD-----------------------------------------------------------------------------------------

                //MARK DEFAULTER----------------------------------------------------------------------------------------

                $('#mark_defaulter_save').on('submit', function (e) {
                    e.preventDefault();

                    var r = confirm("Are you sure? You want to mark the bidder as defaulter.");
                    if (r == true) {
                        $('.page-loader-wrapper').fadeIn();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '{{route('osr.non_tax.asset.payment.mark_as_defaulter')}}',
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
                                } else {
                                    swal("Error", data.msg, 'error');
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                            },
                            complete: function (data) {
                                $('.page-loader-wrapper').fadeOut();
                            }
                        });
                    } else {
                        swal("Information", "Cancelled the action", 'info');
                    }

                });
                //MARK DEFAULTER  --------------------------------------------------------------------------------------

                // BAKI JAI UPDATE -------------------------------------------------------------------------------------

                $('#bakijai_save').on('submit', function (e) {
                    e.preventDefault();

                    var r = confirm("Are you sure? You want to update the bakijai details.");
                    if (r == true) {
                        $('.page-loader-wrapper').fadeIn();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '{{route('osr.non_tax.asset.payment.update_bakijai')}}',
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
                    } else {
                        swal("Information", "Cancelled the action", 'info');
                    }

                });
                // BAKI JAI UPDATE -------------------------------------------------------------------------------------

                @endif


                //IF SETTLEMENT DATA EXISTS ENDED **********************************************************************




                $('#gap_receipt_amount').on('change', function (e) {
                    e.preventDefault();

                    var amt = $('#gap_receipt_amount').val();
                    amt = OSREC.CurrencyFormatter.parse(amt, {locale: 'en_IN'});

                    $('#gap_es_zp_share').text(indianRupeeFormatterText(20 / 100 * amt));
                    $('#gap_es_ap_share').text(indianRupeeFormatterText(40 / 100 * amt));
                    $('#gap_es_gp_share').text(indianRupeeFormatterText(40 / 100 * amt));
                });

                $('#gap_period').on('submit', function (e) {
                    e.preventDefault();

                    var formData = new FormData(this);
                    formData.append('gap_ap_list', $("#gap_ap_list").val() );
                    formData.append('gap_gp_list', $("#gap_gp_list").val() );


                    if ($('#gap_period').valid()) {
                        $('.page-loader-wrapper').fadeIn();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '{{route('osr.non_tax.asset.payment.gapPeriod')}}',
                            dataType: "json",
                            data: formData,
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                if (data.msgType == true) {
                                    swal("Success", data.msg, "success")
                                        .then((value) => {
                                        $('#addGap').modal('hide');
                                        location.reload();
                                    });
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
                    }

                });


            </script>
@endsection
