@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{asset('mdas_assets/css/multi-select.css')}}"/>
    <style>


        .custom-header{
            padding: 10px;
            background-color: #6d133c;
            color: #eee;
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

        .mb40{
            margin-bottom: 40px;
        }

        .popover.top>.arrow:after {
            border-top-color: #0f436d;
        }

        .ms-container .ms-selectable li.disabled, .ms-container .ms-selection li.disabled {
            background-color: white;
            color: #333;
        }

        .ms-container .ms-selectable li.disabled.ms-selected, .ms-container .ms-selection li.disabled.ms-selected {
            color: green !important;
        }

        .form_errors{
            color:red;
            font-weight: 700;
        }

    </style>

@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Home</a></li>
		   <li><a href="{{url('osr/osr_panel')}}/{{encrypt($data['fy_id'])}}">OSR</a></li>
            <li class="active">Year Wise Shortlist Assets</li>
        </ol>
    </div>

    <div class="container mb40">
        <a href="{{url('osr/osr_panel')}}/{{encrypt($data['fy_id'])}}" class="btn btn-warning mt20"> <i class="fa fa-arrow-left"></i> Back</a>
        <div class="row mt20" style="background-color: #fff;box-shadow: 0px 0px 13px 6px #aca8a8;border:1px solid #fff">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h3 style="color:blue;text-align: center">Asset Confirmation of non-tax asset of {{$data['zpData']->zila_parishad_name}} for the financial year 2020-21</h3>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12" style="padding:10px 0">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <p class="text-left"><b>Financial Year:</b> {{$data['fyData']->fy_name}} </p>
                    <p class="text-left"><b>ZP:</b> {{$data['zpData']->zila_parishad_name}}</p>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <p class="text-left">
                        <b>Total: </b> <span style="font-size: 25px;color:#ff9000">{{$data['totAsset']}}</span>,
                        <b>Accepted By Previous Lessee: </b> <span style="font-size: 25px;color:#ff9000">{{$data['assetConfirmCount']}}</span>,
                        <b>Auctioned: </b> <span style="font-size: 25px;color:#ff9000">{{$data['assetAuctionedCount']}}</span>
                    </p>
                    <p class="text-left">
                        <b>Pending:</b> <span style="font-size: 25px;color:#f13333">{{$data['pending']}}</span>
                    </p>
                </div>
            </div>
            <hr/>
            <div class="col-md-12 col-sm-12 col-xs-12 mb40">
                <div class="table-responsive">
                    <table class="table table-bordered table-responsive" id="dataTable">
                        <thead class="bg-primary">
                        <tr>
                            <td>SL</td>
                            <td>Asset Code / Name / Listing Date</td>
                            <td>Location</td>
                            <td>Lessee Name/Settlement Amount<br>(2019-20)</td>
                            <td>Action</td>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($data['assetList'] AS $li)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$li->asset_code}}<br/>{{$li->asset_name}}<br/>({{'Managed By '.$li->level}})</td>
                                <td>
                                    {{--<label>ZP: </label>{{$li->zila_parishad_name}}<br/>--}}
                                    <label> AP: </label>{{$li->anchalik_parishad_name}}<br/>
                                    <label> GP: </label>{{$li->gram_panchayat_name}}
                                </td>
                                <td>
							  <span style="color:blue;font-weight: 700;font-size: 12px">Lessee Name:</span> <label  style="color:#333;font-weight: 900;font-size: 12px">{{$data['assetSettledData'][$li->asset_code]['bidder_name']}}</label><br>
						   	 <span style="color:blue;font-weight: 700;font-size: 12px">Settlement Amount:</span>  <label  class="money_txt" style="color:#333;font-weight: 900;font-size: 12px">{{$data['assetSettledData'][$li->asset_code]['settlement_amt']}}</label><br>
						   	 <span style="color:blue;font-weight: 700;font-size: 12px">Security Deposit:</span>  <label  class="money_txt" style="color:#333;font-weight: 900;font-size: 12px">{{$data['assetSettledData'][$li->asset_code]['security_deposit_amt']}}</label><br>
						  </td>
						   
						  @if(!(isset($data['assetCorfirmStatus'][$li->asset_code])))
                                <td>
                                     <form method="POST" action="#" class="confirmAsset">
                                            <input type="hidden" name="id" value="{{encrypt($li->id)}}"/>
                                            <input type="hidden" name="zp_id" value="{{encrypt($li->zila_id)}}"/>
                                            <input type="hidden" name="ap_id" value="{{encrypt($li->anchalik_id)}}"/>
                                            <input type="hidden" name="gp_id" value="{{encrypt($li->gram_panchayat_id)}}"/>
                                            <input type="hidden" name="assetCode" value="{{encrypt($li->asset_code)}}"/>
								    <input type="hidden" name="bidding_settlement_id" value="{{encrypt($data['assetSettledData'][$li->asset_code]['bidding_settlement_id'])}}"/>
                                            <input type="hidden" name="bidder_name" value="{{encrypt($data['assetSettledData'][$li->asset_code]['bidder_name'])}}"/>
									<input type="hidden" name="settlement_amt" value="{{encrypt($data['assetSettledData'][$li->asset_code]['settlement_amt'])}}"/>
								    <input type="hidden" name="security_amt" value="{{encrypt($data['assetSettledData'][$li->asset_code]['security_deposit_amt'])}}"/>
									
                                            <div class="col-md-8 col-sm-8 col-xs-8">
                                                <label class="radio-inline"><input class="radio_select" type="radio" data-id="{{$li->id}}"  name="level_{{$li->id}}" value="1">Accepted by previous bidder</label><br>
                                                <label class="radio-inline"><input class="radio_select" type="radio" data-id="{{$li->id}}"  name="level_{{$li->id}}" value="2">Auctioned</label><br>
									   <label class="radio-inline"><input class="radio_select" type="radio" data-id="{{$li->id}}"  name="level_{{$li->id}}" value="0">No action taken</label>
                                            </div>
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <button type="submit" class="btn btn-primary btn-xs">
                                                    <i class="fa fa-send"></i>
                                                    Assign
                                                </button>
                                            </div>
                                     </form>
						</td>  
						 @else
                                
						  <td>
							  @if(($data['assetCorfirmStatus'][$li->asset_code]['confirmation_status'])==1)
									    <span style="color:green;font-weight: 700;font-size: 12px">{{"Asset Accepted by previous lessee"}}</span>
							   @elseif(($data['assetCorfirmStatus'][$li->asset_code]['confirmation_status'])==2)
								   <span style="color:green;font-weight: 700;font-size: 12px">{{"Auctioned"}}</span>
							   @else
								    <span style="color:red;font-weight: 700;font-size: 12px">{{"No Action Taken"}}</span>
							   @endif
							   @if(isset($data['assetCorfirmStatus'][$li->asset_code]['confirmation_date']))
									 <p style="font-size: 9px">{{\Carbon\Carbon::parse($data['assetCorfirmStatus'][$li->asset_code]['confirmation_date'])->format('d M Y')}}</p>
							   @endif
						</td>
						 @endif
						   
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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

    <script type="application/javascript">
	    
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

	    
        // ------------DATA TABLE FOR Settlement Details--------------------------
      $(document).ready(function () {

            $('#dataTable').DataTable({
                dom: 'Bfrtip',
                "columnDefs": [
                    { "searchable": true, "targets": 4},
                ],
                buttons: [
                    {
                        extend:    'excelHtml5',
                        title: 	'Non-Tax assets Settlement details for 2020-2021',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
        });
	    
	 $('.confirmAsset').on('submit', function(e){
            e.preventDefault();

            if(confirm("Are you sure? Once done you can not change the status")){
                $('.page-loader-wrapper').fadeIn();
                $('.form_errors').remove();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('osr.non_tax.asset_confirmation.save')}}',
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
@endsection
