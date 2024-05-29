@extends('layouts.app_website')

@section('custom_title')
    OSR non-tax
@endsection

@section('custom_css')

        <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="{{asset('mdas_assets/css/style.css')}}">
        <link rel="stylesheet" href="{{asset('mdas_assets/css/multi-select.css')}}"/>

<style>
    .form-control {
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        background-color: #fff;
        border: 1px solid #ccc;

    }
    .form-control:focus {
        color: #616161;
        border-color: #beb6b6;
    }
    .btn{
        background-color: #005597;
        color: #fdfeff;
    }
    .btn::before{
        background-color: #005597;
    }
    .btn-group-sm>.btn, .btn-sm{
        line-height: 0.5;
    }
       
</style>

@endsection


@section('content')

    <div class="container mb40">
        <div class="row mt40">
            <form id="yr_wise_asset_table" action="" method="post">
                @csrf
                <!--<div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>District</label>
                        <select class="form-control" name="district_id" id="district_id" required>
                            <option value="">---Select---</option>
                            @foreach($data['zilas'] AS $li)
                                <option value="{{$li->id}}">
                                       {{$li->zila_parishad_name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>-->
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>Financial Year</label>
                        <select class="form-control" name="fy_id" id="fy_id" required>
                            <option value="">---Select---</option>
                            @foreach($data['fyList'] AS $li)
                                <option value="{{($li->id)}}" @if($data['yr_id']==$li->id)selected="selected"@endif>{{$li->fy_name}}</option>
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

        <div id="yr_wise_asset"></div>
        

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
        
        $('#yr_wise_asset_table').on('submit', function(e){
            e.preventDefault();

            var yid= $('#fy_id').val();
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "{{route('osr_yr_wise_asset_show')}}",
                contentType: false,
                data: "y_id="+yid,
                cache: false,
                processData: false,
                success: function (data) {
                    $('#yr_wise_asset').html(data)
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