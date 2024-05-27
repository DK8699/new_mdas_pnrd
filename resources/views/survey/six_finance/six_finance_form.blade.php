@php
    $page_title="six_form";
@endphp

@extends('layouts.app_user')

@section('custom_css')
    <style>
        .m-bor{
            border:2px solid #ddd;
        }
        .fa-info{
            color:red;
        }
        .fa-ok{
            color:green;
        }
        .notcom{
            border:2px solid red;
            box-shadow: 0px 0px 8px 3px #cdc4c4;
            /* background-color: deepskyblue; */
            border-radius: 0;
        }
    </style>
@endsection

@section('content')

     <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="active">Sixth Assam State Finance</li>
        </ol>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 m-bor">
        <div class="col-md-12 mt20 text-center">
            <h4>Sixth Assam State Finance Commission</h4>
            <h4>Questionnaire for {{$applicable_name}}</h4>
            <h4>(Applicable to {{$applicable_name}})</h4>
            <hr/>
        </div>

        <div class="row">
            <form action="#" method="POST" id="empSelectSurverySixFinanceForm">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Select District</label>
                            <select class="form-control" name="district_code" id="district_code">
								@if(isset($districts->id))
									<option value="{{$districts->id}}">{{$districts->district_name}}</option>
								@endif
                            </select>
                        </div>
                    </div>
                    @if($applicable_id == 1 || $applicable_id == 2 || $applicable_id == 3)
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Select Zilla Parishad</label>
                            <select class="form-control" name="zilla_code" id="zilla_code">
							@if(isset($zillas->id))
                                <option value="{{$zillas->id}}">{{$zillas->zila_parishad_name}}</option>
							@endif
                            </select>
                        </div>
                    </div>
                    @endif
                    @if($applicable_id == 4 || $applicable_id == 6 || $applicable_id == 7)
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Select Council</label>
                            <select class="form-control" name="council_id" id="council_id">
                                <option value="{{$councils->id}}">{{$councils->council_name}}</option>
                            </select>
                        </div>
                    </div>
                    @endif
                    @if($applicable_id == 2 || $applicable_id == 3)
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Select Anchalik Panchayat</label>
                            <select class="form-control" name="anchalik_code" id="anchalik_code">
                                <option value="">--Select--</option>
                                @foreach($anchaliks AS $li_a)
                                    <option value="{{$li_a->id}}">{{$li_a->anchalik_parishad_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    @if($applicable_id == 6 || $applicable_id == 7)
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Select Block</label>
                            <select class="form-control" name="block_code" id="block_code">
                                <option value="">--Select--</option>
                                @foreach($blocks AS $li_a)
                                    <option value="{{$li_a->id}}">{{$li_a->block_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    @if($applicable_id == 3)
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Select GP</label>
                            <select class="form-control" name="gp_code" id="gp_code">

                            </select>
                        </div>
                    </div>
                    @endif
                    @if($applicable_id == 7)
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Select VCDC/VDC/MAC</label>
                            <select class="form-control" name="v_code" id="v_code">
                                <option value="">--Select--</option>
                            </select>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 mt20">
                    <button type="submit" class="btn btn-lg animated-button thar-two" style="margin: 0px auto 80px auto;">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_js')
<script type="application/javascript">

    $('#anchalik_code').on('change', function(e){

        e.preventDefault();
        $('#gp_code').empty();
        var anchalik_code= $('#anchalik_code').val();
		$('.page-loader-wrapper').fadeIn();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: 'getGPsByAnchalikId',
            dataType: "json",
            data: {anchalik_code : anchalik_code},
            success: function (data) {
                if (data.msgType == true) {

                    $('#gp_code')
                        .append($("<option></option>")
                            .attr("value", '')
                            .text('--Select--'));

                    $.each(data.data, function(key, value) {
                        $('#gp_code')
                            .append($("<option></option>")
                                .attr("value", value['id'])
                                .text(value['gram_panchayat_name']));
                    });
                }else{
                    swal(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
            },
            complete: function (data) {
                $('.page-loader-wrapper').fadeOut();
            }
        });

    });

    $('#block_code').on('change', function(e){

        e.preventDefault();
        $('#v_code').empty();
        var block_code= $('#block_code').val();
		$('.page-loader-wrapper').fadeIn();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: 'getGPsByBlockId',
            dataType: "json",
            data: {block_code : block_code},
            success: function (data) {
                if (data.msgType == true) {

                    $('#v_code')
                        .append($("<option></option>")
                            .attr("value", '')
                            .text('--Select--'));

                    $.each(data.data, function(key, value) {
                        $('#v_code')
                            .append($("<option></option>")
                                .attr("value", value['id'])
                                .text(value['gram_panchayat_name']));
                    });
                }else{
                    swal(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
            },
            complete: function (data) {
                $('.page-loader-wrapper').fadeOut();
            }
        });

    });


    $('#empSelectSurverySixFinanceForm').on('submit', function(e){
        e.preventDefault();

        $('.form_errors').remove();
		$('.page-loader-wrapper').fadeIn();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '{{route('survey.saveAndCheckSixFinance')}}',
            dataType: "json",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                if (data.msgType == true) {

                    window.location.replace("{{route('survey.six_finance_form_dashboard')}}");

                }else{

                    if(data.msg=="VE"){

                        $.each(data.errors, function( index, value ) {
                            $('#'+index).after('<p class="text-danger form_errors">'+value+'</p>');
                        });
                    }else{
                        swal(data.msg);
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
    })
</script>
@endsection