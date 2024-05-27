@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_admin')

@section('custom_css')
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <style>

        .well {
            margin: 0px;
        }

        .modal-body {
            background: #f5f5f5;
            padding: auto;
        }

        strong {
            color: red;
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

        .mb40{
            margin-bottom: 40px;
        }

        .popover.top>.arrow:after {
            border-top-color: #0f436d;
        }
    </style>
@endsection

@section('content')
    {{--<div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Home</a></li>
            <li><a href="{{url('osr/osr_panel')}}/{{encrypt($data['fy_id'])}}">OSR</a></li>
            <li><a href="{{route('osr.non_tax.asset_entry_panel')}}">Asset</a></li>
        </ol>
    </div>--}}
    <div class="container mb40">
        
        <div class="row mt40">
            <form id="getAssetStatus"action="{{route('admin.Osr.asset.asset_status')}}" method="post">
                @csrf
                <div class="col-md-3 col-sm-4 col-xs-12">
                   <div class="form-group">
                       <label>District</label>
                       <select class="form-control" name="zila_id" id="zila_id" required>
                           <option value="">---Select---</option>
                           @foreach($zilas AS $li)
                               <option value="{{$li->id}}" @if($data['zila_id']==$li->id)selected="selected"@endif>{{$li->zila_parishad_name}}</option>
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
        {{--<div class="row mt10">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h6 style="text-transform: uppercase;">
                    {{$data['searchText']}}
                </h6>
            </div>
        </div>--}}
        
        
        <div id="asset_status"></div>
        
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
       
         $('#getAssetStatus').on('submit', function(e){
            e.preventDefault();

            var zid= $('#zila_id').val();
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "{{route('admin.Osr.asset.asset_status_show')}}",
                contentType: false,
                data: "z_id="+zid,
                cache: false,
                processData: false,
                success: function (data) {
                    $('#asset_status').html(data)
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                },
                complete: function (data) {
                    $('.page-loader-wrapper').fadeOut();
                }
            });
        });
       
    </script>
@endsection