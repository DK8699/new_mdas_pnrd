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
    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Home</a></li>
            <li><a href="{{url('osr/osr_panel')}}/{{encrypt($data['fy_id'])}}">OSR</a></li>
            <li class="active">Shortlist Non-Tax Asset</li>
        </ol>
    </div>

    <div class="container mb40">
        <div class="row mt40">
            <form action="{{route('osr.non_tax.asset_shortlist_bidding')}}" method="post">
                @csrf
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-control" name="branch_id" id="branch_id" required>
                            <option value="">---Select---</option>
                            @foreach($data['branchList'] AS $li)
                                <option value="{{$li->id}}" @if($data['branch_id']==$li->id)selected="selected"@endif>{{$li->branch_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>Financial Year</label>
                        <select class="form-control" name="fy_id" id="fy_id" required>
                            <option value="">---Select---</option>
                            @foreach($data['fyList'] AS $li)
                                <option value="{{$li->id}}" @if($data['data_fy_id']==$li->id)selected="selected"@endif>{{$li->fy_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group mt20">
                        <button type="submit" class="btn btn-primary btn-save btn-sm">
                            <i class="fa fa-search"></i>
                            Search
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if($data['data'])
        <div class="row mt10">
            <div class="col-md-12 col-sm-12 col-xs-12">
                @if($data['shortlist']==0)
                    <div class="alert alert-danger">
                        <strong>{{$data['searchText']}}</strong>
                    </div>
                @else
                    <div class="alert alert-success">
                        <strong>{{$data['searchText']}}</strong>
                    </div>
                @endif
            </div>
        </div>


        <form action="#" method="POST" id="shortListForm">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <input type="hidden" name="branch_id" value="{{$data['branch_id']}}"/>
                    <input type="hidden" name="fy_id" value="{{$data['data_fy_id']}}"/>
                    <select multiple="multiple" id="my-select" name="my-select[]">
                        @foreach($data['assetList'] AS $li)
                            @if($data['shortlist']==0)
                                <option value='{{$li->asset_code}}'>{{$li->asset_code}}, {{$li->asset_name}}, Listing Date: {{\Carbon\Carbon::parse($li->asset_listing_date)->format('d M Y')}}</option>
                            @else
                                <option value='{{$li->asset_code}}' @if(in_array($li->asset_code, $data['shortlistAssetList'])) selected="selected" disabled="disabled" @else disabled="disabled"@endif>{{$li->asset_code}}, {{$li->asset_name}}, Listing Date: {{\Carbon\Carbon::parse($li->asset_listing_date)->format('d M Y')}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            @if($data['shortlist']==0)
            <div class="row mt10">
                <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                    <button type="submit">Confirm</button>
                </div>
            </div>
            @endif
        </form>
        @endif
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
    <script src="{{asset('mdas_assets/js/jquery.multi-select.js')}}"></script>
    <script type="application/javascript">
        // ------------DATA TABLE FOR GHATS--------------------------
        $(document).ready(function () {
            $('#dataTable1').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        messageTop: '{{$data["searchText"]}}'
                    },
                ],
                'columnDefs'        : [         // see https://datatables.net/reference/option/columns.searchable
                    {
                        'searchable'    : false,
                        'targets'       : [6]
                    },
                ]
            });
        });

        @if($data['data'])
        $('#my-select').multiSelect({
            selectableHeader: "<div class='custom-header'>List of {{$data['branchData']->branch_name}} on {{$data['fyData']->fy_name}}</div>",
            selectionHeader: "<div class='custom-header'>Selected for bidding and payment entry of {{$data['branchData']->branch_name}} on {{$data['fyData']->fy_name}}</div>",
        });
        @endif

        $('#shortListForm').on('submit', function(e){
            e.preventDefault();

            if($('#my-select').val()){
                if(confirm("Are you sure to shortlist?"))
				{
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('osr.non_tax.asset_shortlist_entry')}}',
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
			else{
				location.reload();
			}
            }
			
			
        });


    </script>
@endsection
