@php
    $page_title="six_form_revenue";
@endphp

@extends('layouts.app_user')

@section('custom_css')

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
        <form action="#" method="POST" id="revenueInfoForm">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <a href="{{route('survey.six_finance_form_dashboard')}}" class="btn btn-warning animated-button" style="margin-bottom: 10px;">
                        <i class="fa fa-arrow-left"></i>
                        Back
                    </a>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>1. Revenue of {{$applicable_name}}</label>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>A. Own Revenue {{$applicable_name}} Yearly</label>
                    </div>

                    <!--################################# OWN REVENUE #################################### -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr class="bg-primary">
                                <th>Revenue Name</th>
                                @foreach($acts AS $li_act)
                                    <td>
                                        {{$li_act->financial_year}} ( in <i class="fa fa-rupee"></i> )
                                    </td>
                                    @php
                                        $tot_own["A_".$li_act->id]= 0;
                                        $tot_tr["A_".$li_act->id]= 0;
                                    @endphp
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @php $i= 'a'; @endphp
                            @foreach($own_revenue_cats AS $li_o)
                                <tr>
                                    <td>
                                        {{$i}}) {{$li_o->own_revenue_name}}
                                    </td>
                                    @foreach($acts AS $li_act)
                                        <td>
                                            <input type="number" data-act='{{$li_act->id}}' name="own_revenue_a_{{$li_act->id}}_o_{{$li_o->id}}" id="own_revenue_a_{{$li_act->id}}_o_{{$li_o->id}}" class="form-control a__{{$li_act->id}}" min="0" step=".01" value="@if(isset($revenueInfoOwnFillFinal["O_".$li_o->id]["A_".$li_act->id])){{$revenueInfoOwnFillFinal["O_".$li_o->id]["A_".$li_act->id]}}@elseif($alreadySubmitted){{"0"}}@endif"/>
                                        </td>

                                        @if(isset($revenueInfoOwnFillFinal["O_".$li_o->id]["A_".$li_act->id]))
                                            @php
                                                $tot_own["A_".$li_act->id]= $tot_own["A_".$li_act->id]+(float)$revenueInfoOwnFillFinal["O_".$li_o->id]["A_".$li_act->id];
                                            @endphp
                                        @endif
                                    @endforeach
                                </tr>
                                @php $i++; @endphp
                            @endforeach

                            @if(!$alreadySubmitted)
                                <tr>
                                    <td colspan="6">
                                        <button type="button" class="btn animated-button thar-two addOwnRevenue"><i class="fa fa-plus"></i> Add More To Own Revenue</button>
                                    </td>
                                </tr>
                            @endif
                            <tr class="bg-danger">
                                <td>
                                    <label>Sub Total (1 A)</label>
                                </td>
                                @foreach($acts AS $li_act)
                                    <td>
                                        <input type="number" name="total_own_revenue_a_{{$li_act->id}}" id="total_own_revenue_a_{{$li_act->id}}" class="form-control" min="0" step=".01" readonly="readonly" value="@if(isset($tot_own["A_".$li_act->id])){{$tot_own["A_".$li_act->id]}}@endif"/>
                                    </td>
                                @endforeach
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--################################# OWN REVENUE ENDED #################################### -->


                    <!--################################# ARREAR TAXES #################################### -->
                    <div class="form-group">
                        <label>B. Arrear taxes / duties at the end of the year</label>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>

                            <tr class="bg-primary">
                                <th></th>
                                @foreach($acts AS $li_act)
                                    <td>
                                        {{$li_act->financial_year}} ( in <i class="fa fa-rupee"></i> )
                                    </td>
                                @endforeach
                            </tr>

                            </thead>
                            <tbody>
                            <tr class="bg-danger">
                                <td>
                                    <label>Sub-Total (1B)</label>
                                </td>
                                @foreach($acts AS $li_act)
                                    <td>
                                        <input type="number" name="total_arrear_taxes_a_{{$li_act->id}}" id="total_arrear_taxes_a_{{$li_act->id}}" class="form-control b__{{$li_act->id}}" min="0" step="0.01" value="@if(isset($revenueInfoArrearFillFinal["A_".$li_act->id])){{$revenueInfoArrearFillFinal["A_".$li_act->id]}}@elseif($alreadySubmitted){{"0"}}@endif"/>
                                    </td>
                                @endforeach
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--################################# ARREAR TAXES ENDED #################################### -->

                    <!--################################# Transferred Resources #################################### -->
                    <div class="form-group">
                        <label>C. Transferred Resources Yearly</label>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr class="bg-primary">
                                <th></th>
                                @foreach($acts AS $li_act)
                                    <td>
                                        {{$li_act->financial_year}} ( in <i class="fa fa-rupee"></i> )
                                    </td>
                                @endforeach
                            </tr>

                            </thead>
                            <tbody>
                            <!-- ##################################### Central share of CSS #######################################-->
                            <tr class="bg-info">
                                <th colspan="6">
                                    1. Central share of CSS yearly
                                </th>
                            </tr>
                            @php $i='a'; @endphp
                            @foreach($css_shares AS $li_sh)
                                <tr>
                                    <td>
                                        {{$i}}) {{$li_sh->scheme_name}}
                                    </td>
                                    @foreach($acts AS $li_act)
                                        <td>
                                            <input type="number" data-act='{{$li_act->id}}' name="central_share_of_css_a_{{$li_act->id}}_s_{{$li_sh->id}}" id="central_share_of_css_a_{{$li_act->id}}_s_{{$li_sh->id}}" class="form-control c__{{$li_act->id}}" min="0" step="0.01" value="@if(isset($revenueInfoShareFillFinal["S_0"]["C_".$li_sh->id]["A_".$li_act->id])){{$revenueInfoShareFillFinal["S_0"]["C_".$li_sh->id]["A_".$li_act->id]}}@elseif($alreadySubmitted){{"0"}}@endif"/>
                                        </td>

                                        @if(isset($revenueInfoShareFillFinal["S_0"]["C_".$li_sh->id]["A_".$li_act->id]))
                                            @php
                                                $tot_tr["A_".$li_act->id]= $tot_tr["A_".$li_act->id]+(float)$revenueInfoShareFillFinal["S_0"]["C_".$li_sh->id]["A_".$li_act->id];
                                            @endphp
                                        @endif
                                    @endforeach
                                </tr>
                                @php $i++; @endphp
                            @endforeach

                            @if(!$alreadySubmitted)
                                <tr>
                                    <td colspan="6">
                                        <button type="button" class="btn animated-button thar-two addShares"><i class="fa fa-plus"></i> Add More To Share of CSS</button>
                                    </td>
                                </tr>
                            @endif

                            <!-- ##################################### State share of CSS #######################################-->
                            <tr class="bg-info">
                                <th colspan="6">
                                    2. State share of CSS yearly
                                </th>
                            </tr>
                            @php $i='a'; @endphp
                            @foreach($css_shares AS $li_sh)
                                <tr>
                                    <td>
                                        {{$i}}) {{$li_sh->scheme_name}}
                                    </td>
                                    @foreach($acts AS $li_act)

                                        <td>
                                            <input type="number" data-act="{{$li_act->id}}" name="state_share_of_css_a_{{$li_act->id}}_s_{{$li_sh->id}}" id="state_share_of_css_a_{{$li_act->id}}_s_{{$li_sh->id}}" class="form-control c__{{$li_act->id}}" min="0" step="0.01" value="@if(isset($revenueInfoShareFillFinal["S_1"]["C_".$li_sh->id]["A_".$li_act->id])){{$revenueInfoShareFillFinal["S_1"]["C_".$li_sh->id]["A_".$li_act->id]}}@elseif($alreadySubmitted){{"0"}}@endif"/>
                                        </td>
                                        @if(isset($revenueInfoShareFillFinal["S_1"]["C_".$li_sh->id]["A_".$li_act->id]))
                                            @php
                                                $tot_tr["A_".$li_act->id]= $tot_tr["A_".$li_act->id]+(float)$revenueInfoShareFillFinal["S_1"]["C_".$li_sh->id]["A_".$li_act->id];
                                            @endphp
                                        @endif
                                    @endforeach
                                </tr>
                                @php $i++; @endphp
                            @endforeach

                            @if(!$alreadySubmitted)
                                <tr>
                                    <td colspan="6">
                                        <button class="btn animated-button thar-two addShares"><i class="fa fa-plus"></i> Add More To Share of CSS</button>
                                    </td>
                                </tr>
                            @endif


                            <!--################################# Transferred Resources #################################### -->

                            @php $j=3; @endphp

                            @foreach($tr_cats_final AS $li_tr)
                                @if(count($li_tr['sublist']) == 0)
                                    <tr>
                                        <td>
                                            <label>{{$j}}. {{$li_tr['transferred_resource_cat_name']}}</label>
                                        </td>
                                        @foreach($acts AS $li_act)
                                            <td>
                                                <input type="number" data-act="{{$li_act->id}}" name="transfer_a_{{$li_act->id}}_t_{{$li_tr['id']}}" id="transfer_a_{{$li_act->id}}_t_{{$li_tr['id']}}" class="form-control c__{{$li_act->id}}" min="0" step="0.01" value="@if(isset($revenueInfoTRFillFinal["C_".$li_tr['id']]["A_".$li_act->id])){{$revenueInfoTRFillFinal["C_".$li_tr['id']]["A_".$li_act->id]}}@elseif($alreadySubmitted){{"0"}}@endif"/>
                                            </td>

                                            @if(isset($revenueInfoTRFillFinal["C_".$li_tr['id']]["A_".$li_act->id]))
                                                @php
                                                    $tot_tr["A_".$li_act->id]= $tot_tr["A_".$li_act->id]+(float)$revenueInfoTRFillFinal["C_".$li_tr['id']]["A_".$li_act->id];
                                                @endphp
                                            @endif
                                        @endforeach
                                    </tr>
                                @else
                                    <tr class="bg-info">
                                        <th colspan="6">
                                            {{$j}}. {{$li_tr['transferred_resource_cat_name']}}
                                        </th>
                                    </tr>
                                @endif
                                @php $i='a'; @endphp
                                @foreach($li_tr['sublist'] AS $li_sub)
                                    <tr>
                                        <td>
                                            {{$i}}) {{$li_sub->transferred_resource_cat_name}}
                                        </td>
                                        @foreach($acts AS $li_act)
                                            <td>
                                                <input type="number" data-act="{{$li_act->id}}" name="transfer_a_{{$li_act->id}}_t_{{$li_sub->id}}" id="transfer_a_{{$li_act->id}}_t_{{$li_sub->id}}" class="form-control c__{{$li_act->id}}" min="0" step="0.01" value="@if(isset($revenueInfoTRFillFinal["C_".$li_sub->id]["A_".$li_act->id])){{$revenueInfoTRFillFinal["C_".$li_sub->id]["A_".$li_act->id]}}@elseif($alreadySubmitted){{"0"}}@endif"/>
                                            </td>

                                            @if(isset($revenueInfoTRFillFinal["C_".$li_sub->id]["A_".$li_act->id]))
                                                @php
                                                    $tot_tr["A_".$li_act->id]= $tot_tr["A_".$li_act->id]+(float)$revenueInfoTRFillFinal["C_".$li_sub->id]["A_".$li_act->id];
                                                @endphp
                                            @endif
                                        @endforeach
                                    </tr>
                                    @php $i++; @endphp
                                @endforeach
                                @php $j++; @endphp
                            @endforeach

                            @if(!$alreadySubmitted)
                                <tr>
                                    <td colspan="6">
                                        <button type="button" class="btn animated-button thar-two addTransferResource"><i class="fa fa-plus"></i> Add More To Transferred Resources</button>
                                    </td>
                                </tr>
                            @endif

                            <tr class="bg-danger">
                                <td>
                                    <label>Sub-Total yearly (1C)</label>
                                </td>
                                @foreach($acts AS $li_act)
                                    <td>
                                        <input type="number" name="total_transferred_resources_a_{{$li_act->id}}" id="total_transferred_resources_a_{{$li_act->id}}" class="form-control" min="0" step="0.01" readonly="readonly" @if(isset($tot_tr["A_".$li_act->id]))value="{{$tot_tr["A_".$li_act->id]}}"@endif/>
                                    </td>
                                @endforeach
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <!--################################# Transferred Resources ENDED #################################### -->


                    <div class="form-group">
                        <label>2. Grand Total yearly</label>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr class="bg-primary">
                                <th></th>
                                @foreach($acts AS $li_act)
                                    <td>
                                        {{$li_act->financial_year}} ( in <i class="fa fa-rupee"></i> )
                                    </td>
                                @endforeach
                            </tr>
                            <tr class="bg-danger">
                                <th>Grand Total Revenue of {{$applicable_name}} <br/>  1 (A + B + C )</th>
                                @foreach($acts AS $li_act)
                                    <th>
                                        <input type="number" name="grand_total_a_{{$li_act->id}}" id="grand_total_a_{{$li_act->id}}" class="form-control" min="0" step="0.01" readonly="readonly" value="@if(isset($tot_own["A_".$li_act->id]) && isset($revenueInfoArrearFillFinal["A_".$li_act->id]) && isset($tot_tr["A_".$li_act->id])){{(float)$tot_own["A_".$li_act->id]+(float)$revenueInfoArrearFillFinal["A_".$li_act->id]+(float)$tot_tr["A_".$li_act->id]}}@endif"/>
                                    </th>
                                @endforeach
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    @if($alreadySubmitted)
                        {{--<button type="button" class="btn btn-danger animated-button" id="deleteRequest" data-df="REV" style="margin-bottom: 40px">
                            <i class="fa fa-trash-o"></i>
                            Request to delete Revenue Info
                        </button>--}}
                        <p style="font-weight: 700;color:red">Note: If you resubmit the revenue info you have to fill the balance info again.</p>

                        <button type="submit" class="btn animated-button thar-two" style="margin-bottom: 40px">Resubmit</button>
                    @else
                        <button type="submit" class="btn animated-button thar-two" style="margin-bottom: 40px">Submit</button>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    <h4 class="modal-title"><label>Add Tax To Own Revenue</label></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form action="#" method="POST" id="addOwnRevenueTaxForm">
                            <div class="col-md-12 col-sm-12 col-sm-12">
                                <div class="form-group">
                                    <label> Tax Name</label>
                                    <input type="text" class="form-control" name="tax_name" id="tax_name" placeholder="Enter Tax Name"/>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-block animated-button thar-two">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ADD SHARES -->
    <div class="modal fade" id="myShares" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    <h4 class="modal-title"><label>Add Shares</label></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form action="#" method="POST" id="addShareForm">
                            <div class="col-md-12 col-sm-12 col-sm-12">
                                <div class="form-group">
                                    <label> Share Name</label>
                                    <input type="text" class="form-control" name="share_name" id="share_name" placeholder="Enter Share Name"/>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-block animated-button thar-two">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ADD TRANSFER RESOURCES -->
    <div class="modal fade" id="myTransferResource" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    <h4 class="modal-title"><label>Add Transferred Resource</label></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form action="#" method="POST" id="addTransferResourceForm">
                            <div class="col-md-12 col-sm-12 col-sm-12">
                                <div class="form-group">
                                    <label> Transferred Resource Name</label>
                                    <input type="text" class="form-control" name="transfer_resource_name" id="transfer_resource_name" placeholder="Enter Share Name"/>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-block animated-button thar-two">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script type="application/javascript">

        $('.addOwnRevenue').on('click', function(e){
            e.preventDefault();
            $('#myModal').modal('show');
        });

        $('.addShares').on('click', function(e){
            e.preventDefault();
            $('#myShares').modal('show');
        });

        $('.addTransferResource').on('click', function(e){
            e.preventDefault();
            $('#myTransferResource').modal('show');
        });

        $('#addOwnRevenueTaxForm').on('submit', function(e){
            e.preventDefault();
            /*$('.page-loader-wrapper').fadeIn();

             $.ajax({
             headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             type: "POST",
             url: '{{route('survey.six_finance_form_revenue.add_tax_own_revenue')}}',
             dataType: "json",
             data: new FormData(this),
             contentType: false,
             cache: false,
             processData: false,
             success: function (data) {
             if (data.msgType == true) {
             swal("Success", data.msg, 'success');
             $('#addOwnRevenueTaxForm')[0].reset();
             $('#myModal').modal('hide');
             }else{
             swal("Error", data.msg, 'error');
             }
             },
             error: function (jqXHR, textStatus, errorThrown) {
             callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
             },
             complete: function (data) {
             $('.page-loader-wrapper').fadeOut();
             }
             });*/
            swal("Information", "Add Own Revenue is currently disabled by admin. Please contact admin for more details", "info");
        });

        $('#addShareForm').on('submit', function(e){
            e.preventDefault();
            /*$('.page-loader-wrapper').fadeIn();

             $.ajax({
             headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             type: "POST",
             url: '{{route('survey.six_finance_form_revenue.add_share')}}',
             dataType: "json",
             data: new FormData(this),
             contentType: false,
             cache: false,
             processData: false,
             success: function (data) {
             if (data.msgType == true) {
             swal("Success", data.msg, 'success');
             $('#addShareForm')[0].reset();
             $('#myShares').modal('hide');
             }else{
             swal("Error", data.msg, 'error');
             }
             },
             error: function (jqXHR, textStatus, errorThrown) {
             callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
             },
             complete: function (data) {
             $('.page-loader-wrapper').fadeOut();
             }
             });*/

            swal("Information", "Add CSS share is currently disabled by admin. Please contact admin for more details", "info");
        });

        $('#addTransferResourceForm').on('submit', function(e){
            e.preventDefault();
            /*$('.page-loader-wrapper').fadeIn();

             $.ajax({
             headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             type: "POST",
             url: '{{route('survey.six_finance_form_revenue.addTransferResource')}}',
             dataType: "json",
             data: new FormData(this),
             contentType: false,
             cache: false,
             processData: false,
             success: function (data) {
             if (data.msgType == true) {
             swal("Success", data.msg, 'success');
             $('#addTransferResourceForm')[0].reset();
             $('#myShares').modal('hide');
             }else{
             swal("Error", data.msg, 'error');
             }
             },
             error: function (jqXHR, textStatus, errorThrown) {
             callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
             },
             complete: function (data) {
             $('.page-loader-wrapper').fadeOut();
             }
             });*/

            swal("Information", "Add Transfer Resource is currently disabled by admin. Please contact admin for more details", "info");
        });


        $('#revenueInfoForm').on('submit', function(e){
            e.preventDefault();

            swal({
                title: "Are you sure?",
                text: "You are sure you want to @if(!$alreadySubmitted){{"submit"}}@else{{"resubmit"}}@endif revenue info.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willStore) => {
                if (willStore) {

                    $('.form_errors').remove();
                    $('.page-loader-wrapper').fadeIn();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: '{{route('survey.six_finance_form_revenue.save')}}',
                        dataType: "json",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data) {
                            if (data.msgType == true) {
                                $('#revenueInfoForm')[0].reset();
                                @if(!$alreadySubmitted)
                                    window.location.replace("{{route('survey.six_finance_form_dashboard')}}");
                                @else
                                    swal("success", "Successfully re-submitted the Revenue Info!","success");
                                setTimeout(function(){ location.reload(); }, 1200);
                                @endif
                            }else{

                                if(data.msg=="VE"){
                                    swal("Vadidation Error", "Please fill up the form correctly. If no error is showing kindly refresh the page and try again.", 'info');
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
                        } else {
                            swal("You have canceled your operation!");
                        }
                });
        });


        //OWN REVENUE


        @foreach($acts AS $li_act)
        //OWN REVENUE
        $('.a__{{$li_act->id}}').on('blur', function(e){
            e.preventDefault();

            var act= $(this).data('act');

            var inputs = $(".a__"+act);

            var sum=0;

            for(var i = 0; i < inputs.length; i++){
                var input_value=$(inputs[i]).val();

                if(input_value==''){
                    input_value=0;
                }
                sum = sum + parseFloat(input_value);
            }

            $('#total_own_revenue_a_{{$li_act->id}}').val(sum.toFixed(2));

            calculateGrandTotal__{{$li_act->id}}()
        });

        $('.b__{{$li_act->id}}').on('blur', function(e){
            e.preventDefault();

            calculateGrandTotal__{{$li_act->id}}()
        });

        $('.c__{{$li_act->id}}').on('blur', function(e){
            e.preventDefault();

            var act= $(this).data('act');

            var inputs = $(".c__"+act);

            var sum=0;

            for(var i = 0; i < inputs.length; i++){
                var input_value=$(inputs[i]).val();

                if(input_value==''){
                    input_value=0;
                }
                sum = sum + parseFloat(input_value);
            }

            $('#total_transferred_resources_a_{{$li_act->id}}').val(sum.toFixed(2));

            calculateGrandTotal__{{$li_act->id}}()
        });

        function calculateGrandTotal__{{$li_act->id}}(){
            var sum_a = $('#total_own_revenue_a_{{$li_act->id}}').val();
            var sum_b = $('#total_arrear_taxes_a_{{$li_act->id}}').val();
            var sum_c = $('#total_transferred_resources_a_{{$li_act->id}}').val();

            if(sum_a==''){
                sum_a=0;
            }if(sum_b==''){
                sum_b=0;
            }if(sum_c==''){
                sum_c=0;
            }

            $("#grand_total_a_{{$li_act->id}}").val((parseFloat(sum_a)+parseFloat(sum_b)+parseFloat(sum_c)).toFixed(2));

            //console.log(parseFloat(sum_a)+parseFloat(sum_b)+parseFloat(sum_c));
        }
        @endforeach


    </script>
@endsection