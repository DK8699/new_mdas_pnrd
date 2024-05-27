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
        <a href="{{url('osr/non_tax/year_wise_assets')}}" class="btn btn-warning mt20"> <i class="fa fa-arrow-left"></i> Back</a>
        <div class="row mt20" style="background-color: #fff;box-shadow: 0px 0px 13px 6px #aca8a8;border:1px solid #fff">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h3 style="color:blue;text-align: center">Shortlisting of non-tax asset {{$data['catData']->branch_name}} of {{$data['zpData']->zila_parishad_name}} for the financial year {{$data['fyData']->fy_name}}</h3>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12" style="padding:10px 0">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <p class="text-left"><b>Financial Year:</b> {{$data['fyData']->fy_name}}</p>
                    <p class="text-left"><b>Category:</b> {{$data['catData']->branch_name}}</p>
                    <p class="text-left"><b>ZP:</b> {{$data['zpData']->zila_parishad_name}}</p>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <p class="text-left">
                        <b>ZP: </b> <span style="font-size: 25px;color:#ff9000">@if(isset($data['levelCount']['ZP'])){{$data['levelCount']['ZP']}}@else{{"0"}}@endif</span>,
                        <b>AP: </b> <span style="font-size: 25px;color:#ff9000">@if(isset($data['levelCount']['AP'])){{$data['levelCount']['AP']}}@else{{"0"}}@endif</span>,
                        <b>GP: </b> <span style="font-size: 25px;color:#ff9000">@if(isset($data['levelCount']['GP'])){{$data['levelCount']['GP']}}@else{{"0"}}@endif</span>,
                        <b>Not Selected:</b> <span style="font-size: 25px;color:#ff9000">@if(isset($data['levelCount']['NA'])){{$data['levelCount']['NA']}}@else{{"0"}}@endif</span>
                    </p>
                    <p class="text-left">
                        <b>Pending:</b> <span style="font-size: 25px;color:#f13333">{{$data['pendingCount']}}</span>
                    </p>
                </div>
            </div>
            <hr/>
            <div class="col-md-12 col-sm-12 col-xs-12 mb40">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable">
                        <thead class="bg-primary">
                        <tr>
                            <td>SL</td>
                            <td>Asset Code / Name / Listing Date</td>
                            <td>Location</td>
                            <td>Status</td>
                            <td>Managed By</td>
                            <td>Action</td>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($data['assetList'] AS $li)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$li->asset_code}}<br/>{{$li->asset_name}}<br/><span style="font-size: 9px">{{\Carbon\Carbon::parse($li->asset_listing_date)->format('d M Y')}}</span></td>
                                <td>
                                    {{--<label>ZP: </label>{{$li->zila_parishad_name}}<br/>--}}
                                    <label> AP: </label>{{$li->anchalik_parishad_name}}<br/>
                                    <label> GP: </label>{{$li->gram_panchayat_name}}
                                </td>
                                <td>
                                    @if(isset($data['assetShortList'][$li->asset_code]))
                                        @if(in_array($data['assetShortList'][$li->asset_code]['level'], ["ZP", "AP", "GP", "NA"]) )
                                            <span style="color:green;font-weight: 700;font-size: 12px">{{"Shorlisted"}}</span>
                                        @endif
                                        @if(isset($data['assetShortList'][$li->asset_code]['created_at']))
                                            <p style="font-size: 9px">{{\Carbon\Carbon::parse($data['assetShortList'][$li->asset_code]['created_at'])->format('d M Y')}}</p>
                                        @endif
                                    @else
                                        <span style="color:red;font-weight: 700;font-size: 12px">{{"Pending"}}</span>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($data['assetShortList'][$li->asset_code]))
                                        @if(in_array($data['assetShortList'][$li->asset_code]['level'], ["ZP", "AP", "GP"]) )
                                            {{$data['assetShortList'][$li->asset_code]['level']}}
                                        @else
                                            {{"Not Selected"}}
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if(isset($data['assetShortList'][$li->asset_code]))
                                        @if(!in_array($data['assetShortList'][$li->asset_code]['level'], ["ZP", "AP", "GP"]) )
                                            <b>{{"Reason : "}}</b>  {{$data['assetShortList'][$li->asset_code]['reason']}}
                                        @endif
                                    @else
                                        <form method="POST" action="#" class="assignPost">
                                            <input type="hidden" name="id" value="{{encrypt($li->id)}}"/>
                                            <input type="hidden" name="zp_id" value="{{encrypt($li->zila_id)}}"/>
                                            <input type="hidden" name="ap_id" value="{{encrypt($li->anchalik_id)}}"/>
                                            <input type="hidden" name="gp_id" value="{{encrypt($li->gram_panchayat_id)}}"/>
                                            <input type="hidden" name="assetCode" value="{{encrypt($li->asset_code)}}"/>
                                            <input type="hidden" name="cat_id" value="{{encrypt($data['catData']->id)}}"/>
                                            <input type="hidden" name="fy_id" value="{{encrypt($data['fyData']->id)}}"/>

                                            <div class="col-md-8 col-sm-8 col-xs-8">
                                                <label class="radio-inline"><input class="radio_select" type="radio" data-id="{{$li->id}}"  name="level_{{$li->id}}" value="ZP">ZP</label>
                                                <label class="radio-inline"><input class="radio_select" type="radio" data-id="{{$li->id}}"  name="level_{{$li->id}}" value="AP">AP</label>
                                                <label class="radio-inline"><input class="radio_select" type="radio" data-id="{{$li->id}}"  name="level_{{$li->id}}" value="GP">GP</label>
                                                <label class="radio-inline" id="level_{{$li->id}}"><input class="radio_select" type="radio" data-id="{{$li->id}}"  name="level_{{$li->id}}" value="NA">Not Selected</label>
                                                <textarea class="form-control mt10" rows="4" id="reason_{{$li->id}}" name="reason_{{$li->id}}" placeholder="Enter the reason for not selecting the asset in this year" style="display: none"></textarea>
                                            </div>
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <button type="submit" class="btn btn-primary btn-xs">
                                                    <i class="fa fa-send"></i>
                                                    Assign
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </td>
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
        // ------------DATA TABLE FOR GHATS--------------------------
        $(document).ready(function () {

            $('#dataTable').DataTable({
                dom: 'Bfrtip',
                "columnDefs": [
                    { "searchable": false, "targets": 5},
                ],
                buttons: [
                    {
                        extend:    'excelHtml5',
                        title: 	   'Non-Tax assets (Haat, Ghat, Fishery, Animal Pound) migration for {{$data['fyData']->fy_name}}',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
        });

        $('.radio_select').on('change', function(e) {
            $('.form_errors').remove();
            var val= $(this).data('id');
            if (this.value == 'NA') {
                $('#reason_'+val).show();
            }else{
                $('#reason_'+val).val('');
                $('#reason_'+val).hide();
            }
        });


        $('.assignPost').on('submit', function(e){
            e.preventDefault();

            if(confirm("Are you sure? Once done you can not change the status")){
                $('.page-loader-wrapper').fadeIn();
                $('.form_errors').remove();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('osr.non_tax.year_wise_asset_shortlist.save')}}',
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
                                //swal("Error", "Validation error.Please check the form correctly!", 'error');
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
