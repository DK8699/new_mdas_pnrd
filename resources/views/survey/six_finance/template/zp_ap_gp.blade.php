<!DOCTYPE html>
<html>
<head>
    <title>Sixth Assam State Finance Commission</title>
</head>
<body style="font-size: 11px;">
<style type="text/css">
    .table{
        width: 100%;
        margin-bottom: 0px;
        border-top: 1px solid #DCDCDC;
        border-left: 1px solid #DCDCDC;

    }
    .table tr .hide{
        border-color: #DCDCDC;
        border-style: solid;
        border-width: 0px 0px 1px 0;
    }
    .table tr .hide_end{
        border-color: #DCDCDC;
        border-style: solid;
        border-width: 0px 1px 1px 0;
    }
    .table tr .center{
        text-align: center;
    }
    .table tr .right{
        text-align: right;
    }

    table tr td{
        border-color: #DCDCDC;
        border-style: solid;
        border-width: 0px 1px 1px 0;
    }
    table tr th{
        border-color: #DCDCDC;
        border-style: solid;
        border-width: 0px 1px 1px 0;
        text-align: center;
    }
    .slNumber{
        text-align:left;
        width:20px;
        font-weight:bold;
        vertical-align: top;
    }
    .slNumber p{
        text-align: center;
    }
    .infoTitle{
        vertical-align: top;
        text-align: left;
    }
    .head{
        width: 100%;
    }
    .head p{
        text-align: center; margin-top: -20px;font-weight: bold;
    }

    .txt-upper{
        text-transform: uppercase;
    }

    .left{
       text-align: left;
    }

    td,th {
        padding-left: 5px;
        padding-right: 5px;
    }

    p{
        margin:3px 5px 3px 5px;

    }


</style>
<table cellpadding="0" cellspacing="0" class="head">
    <tr>
        <td style="border: 0px;">
            <p style="font-size:18px">Sixth Assam State Finance Commission</p>
            <p style="font-size:14px;font-weight: 500">Questionnaire for {{$sixFinanceFinal->applicable_name}}</p>
            <p style="font-size:14px;margin-top: 5px">(Applicable to {{$sixFinanceFinal->applicable_name}})</p>
        </td>
    </tr>
</table>
<!-- border: 1px solid black;text-align: center; border-bottom: 0px; -->
<div style="width: 100%;border: 1px solid black">
    <table cellspacing="0" cellspacing="0" class="table"  >
        <tr>
            <td class="slNumber" style="text-align: center">
                1
            </td>
            <td>Name of the:-</td>
            <td colspan="2">
                (i) District:
            </td>
            <td colspan="3" class="hide_end txt-upper">
                @if(isset($sixFinanceFinal->district_name))
                    {{$sixFinanceFinal->district_name}}
                @endif
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="2">
                (ii) Zila Parishad:
            </td>
            <td colspan="3" class="hide_end txt-upper">
                @if(isset($sixFinanceFinal->zila_parishad_name))
                    {{$sixFinanceFinal->zila_parishad_name}}
                @endif
            </td>
        </tr>
        @if(isset($sixFinanceFinal->anchalik_parishad_name))
        <tr>
            <td></td>
            <td></td>
            <td colspan="2">
                (iii) Anchalik Panchayat:
            </td>
            <td colspan="3" class="hide_end txt-upper">{{$sixFinanceFinal->anchalik_parishad_name}}</td>
        </tr>
        @endif
        @if(isset($sixFinanceFinal->gram_panchayat_name))
        <tr>
            <td></td>
            <td></td>
            <td colspan="2">
                (iv) Gram Panchayat:
            </td>
            <td colspan="3" class="hide_end txt-upper">{{$sixFinanceFinal->gram_panchayat_name}}</td>
        </tr>
        @endif
        <tr>
            <td class="slNumber">
                <p>2</p>
            </td>
            <td class="infoTitle" colspan="2">
                <p>Name of the {{$sixFinanceFinal->applicable_name}}</p>
            </td>
            <td colspan="4" class="hide_end txt-upper">
                @if(isset($basicInfoFill->app_name)){{$basicInfoFill->app_name}}@endif
            </td>
        </tr>
        <tr>
            <td class="slNumber">
                <p>3</p>
            </td>
            <td class="infoTitle" colspan="2">
                <p>Area of {{$sixFinanceFinal->applicable_name}} (in Square KM)</p>
            </td>
            <td class="hide_end" colspan="4">
                @if(isset($basicInfoFill->app_area)){{$basicInfoFill->app_area}}@endif Sq. km
            </td>
        </tr>
        <tr>
            <td class="slNumber">
                <p>4</p>
            </td>
            <td class="infoTitle" colspan="2">
                <p>No. of household in the {{$sixFinanceFinal->applicable_name}}</p>
            </td>
            <td class="hide_end" colspan="4">
                @if(isset($basicInfoFill->app_house_nos)){{$basicInfoFill->app_house_nos}}@endif House hold
            </td>
        </tr>
        <tr>
            <td class="slNumber">
                <p>5</p>
            </td>
            <td class="infoTitle">
                <p>Population of the {{$sixFinanceFinal->applicable_name}} (as per 2011 census)</p>
            </td>
            <td class="center">
                Male
            </td>
            <td class="center">
                Female
            </td>
            <td class="center" >
                Total
            </td>
            <td class="center" >
                SC
            </td>
            <td class="center" >
                ST
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <th class="right" >
                @if(isset($basicInfoFill->pop_male)){{$basicInfoFill->pop_male}}@endif
            </th>
            <th class="right" >
                @if(isset($basicInfoFill->pop_female)){{$basicInfoFill->pop_female}}@endif
            </th>
            <th class="right" >
                @if(isset($basicInfoFill->pop_total)){{$basicInfoFill->pop_total}}@endif
            </th>
            <th class="right" >
                @if(isset($basicInfoFill->pop_sc)){{$basicInfoFill->pop_sc}}@endif
            </th>
            <th class="right" >
                @if(isset($basicInfoFill->pop_st)){{$basicInfoFill->pop_st}}@endif
            </th>
        </tr>
        <tr>
            <td class="slNumber">
                <p>6</p>
            </td>
            <td class="infoTitle" colspan="2">
                <p>Date of last Panchayat election held</p>
            </td>
            <td class="hide_end" colspan="4">@if(isset($basicInfoFill->election_date)){{\Carbon\Carbon::parse($basicInfoFill->election_date)->format("d-m-Y")}}@endif</td>
        </tr>
        <tr>
            <td class="slNumber">
                <p>7</p>
            </td>
            <td class="infoTitle" colspan="2">
                <p>Whether the {{$sixFinanceFinal->applicable_name}} is housed in its own building, if not the amount of rent paid monthly</p>
            </td>
            <td class="center">
                @if(isset($basicInfoFill->app_household_rented)) @if($basicInfoFill->app_household_rented==1) YES @endif @endif
            </td>
            <td class="center">
                @if(isset($basicInfoFill->app_household_rented)) @if($basicInfoFill->app_household_rented==2) NO @endif @endif
            </td>
            <td class="center" colspan="2">
                Amount(Rs.)<br/>
                @if(isset($basicInfoFill->app_household_rented)) @if($basicInfoFill->app_household_rented==2)
                    @if(isset($basicInfoFill->app_monthly_rent)){{$basicInfoFill->app_monthly_rent}}@endif
                @endif @endif
            </td>
        </tr>
        <!----------------------------------------  STAFF SECTION	--------------------------------------------------->
        <tr>
            <td class="slNumber" ><p>8</p></td>
            <td class="infoTitle"><p>Details of Staff as on 31-03-2018</p></td>
            <th><p>Designation</p></th>
            <th><p>No. of sanctioned post</p></th>
            <th><p>Scale of pay</p></th>
            <th><p>Consolidated pay</p></th>
            <th><p>Vacant Post</p></th>
        </tr>
        @if(isset($staffInfos['final_cats']))
            @if(!empty($staffInfos['final_cats']) && !empty($staffInfos['staffInfoDetailsFillFinal']))
                @php
                    $staffInfoDetailsFillFinal=$staffInfos['staffInfoDetailsFillFinal'];
                    $staffInfoSalaryFillFinal=$staffInfos['staffInfoSalaryFillFinal'];
                    $staffInfoFill=$staffInfos['staffInfoFill'];
                @endphp
                @foreach($staffInfos['final_cats'] AS $li_cat)
                    <tr>
                        <td></td>
                        <td colspan="6" class="infoTitle">@if($li_cat['id']==1){{"A."}}@else{{"B."}}@endif {{$li_cat['category_name']}}</td>
                    </tr>
                @php
                    $tot_sp= 0;
                    $tot_vp= 0;
                    $i=1;
                @endphp
                @foreach($li_cat['designations'] AS $li_d)
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="infoTitle">{{$i}}. {{$li_d->designation_name}}</td>
                        <td class="right">@if(isset($staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["SC"])){{$staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["SC"]}}@else{{"0"}}@endif</td>
                        <td class="right">@if(isset($staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["SP"])){{$staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["SP"]}}@else{{"NA"}}@endif</td>
                        <td class="right">@if(isset($staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["CP"])){{$staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["CP"]}}@else{{"0"}}@endif</td>
                        <td class="right">@if(isset($staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["VP"])){{$staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["VP"]}}@else{{"0"}}@endif</td>
                    </tr>
                    @if(isset($staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["SC"]))
                        @php $tot_sp= $tot_sp+(int)$staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["SC"]; @endphp
                    @endif

                    @if(isset($staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["VP"]))
                         @php $tot_vp= $tot_vp+(int)$staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["VP"]; @endphp
                    @endif
                    @php $i++; @endphp
                @endforeach
                    <tr>
                        <td></td>
                        <td class="infoTitle"><strong><p>Total @if($li_cat['id']==1){{" (a)"}}@else{{" (b)"}}@endif</p></strong></td>
                        <td></td>
                        <td class="right"><strong>{{$tot_sp}}</strong></td>
                        <td class="right"></td>
                        <td class="right"></td>
                        <td class="right"><strong>{{$tot_vp}}</strong></td>
                    </tr>
                @endforeach
            @endif
        @endif
        <tr>
            <td class="slNumber"><p>9</p></td>
            <td class="infoTitle">
                <strong><p>Summery of Staff Salary</p></strong>
            </td>
            @foreach($acts AS $li_act)
            <th><p>{{$li_act->financial_year}} <br/>( in Rs. )</p></th>
            @endforeach
        </tr>
        @foreach($staffInfos['final_cats'] AS $li_dm)
            <tr>
                <td></td>
                <td colspan="6" class="infoTitle">@if($li_dm['id']==1){{"A."}}@else{{"B."}}@endif {{$li_dm['category_name']}}<span style="float:right;vertical-align:bottom">Yearly</span></td>
            </tr>
            @foreach($acts AS $li_act)
            @php $tot_salary_A["C_".$li_dm['id']]["A_".$li_act->id]= 0; @endphp
            @endforeach
            @php $i=1; @endphp
            @foreach($li_dm['designations'] AS $li_d)
            <tr>
                <td></td>
                <td class="infoTitle">{{$i}}. {{$li_d->designation_name}}</td>
                @foreach($acts AS $li_act)
                    <td class="right">@if(isset($staffInfoSalaryFillFinal["C_".$li_dm['id']]["D_".$li_d->id]["A_".$li_act->id])){{$staffInfoSalaryFillFinal["C_".$li_dm['id']]["D_".$li_d->id]["A_".$li_act->id]}}@else{{"0"}}@endif</td>
                    @if(isset($staffInfoSalaryFillFinal["C_".$li_dm['id']]["D_".$li_d->id]["A_".$li_act->id]))
                        @php
                            $tot_salary_A["C_".$li_dm['id']]["A_".$li_act->id]= $tot_salary_A["C_".$li_dm['id']]["A_".$li_act->id]+(float)$staffInfoSalaryFillFinal["C_".$li_dm['id']]["D_".$li_d->id]["A_".$li_act->id];
                        @endphp
                    @endif
                @endforeach
            </tr>
            @php $i++; @endphp
            @endforeach
            <tr>
                <td></td>
                <td class="infoTitle"><strong><p>Total</p></strong></td>
                @foreach($acts AS $li_act)
                <td class="right"><strong>{{$tot_salary_A["C_".$li_dm['id']]["A_".$li_act->id]}}</strong></td>
                @endforeach
            </tr>
        @endforeach
        <tr>
            <td class="slNumber"><p>10</p></td>
            <td class="infoTitle" colspan="2">
                <p>Arrear salary, if any, as on 31-03-2018</p>
            </td>
            <td class="infoTitle hide"><p>Rs. @if(isset($staffInfoFill->arrear_salary)){{$staffInfoFill->arrear_salary}}@endif</p></td>
            <td class="hide"></td>
            <td class="hide"></td>
            <td class="hide_end"></td>

        </tr>
        <tr>
            <td class="slNumber"><p>11</p></td>
            <td class="infoTitle" colspan="2">
                <p>Number of Muster Roll and fixed pay emplyees as on 31-03-2018</p>
            </td>
            <td class="hide"><p> @if(isset($staffInfoFill->number_of_muster_roll_fixed_pay_emp)){{$staffInfoFill->number_of_muster_roll_fixed_pay_emp}}@endif</p></td>
            <td class="hide"></td>
            <td class="hide"></td>
            <td class="hide_end"></td>
        </tr>
        <!----------------------------------------  REVENUE SECTION	--------------------------------------------------->
        <tr>
            <td class="slNumber"> <p>12</p></td>
            <th class="infoTitle hide"><p>Revenue of {{$sixFinanceFinal->applicable_name}}</p></th>
            <td class="hide"></td>
            <td class="hide"></td>
            <td class="hide"></td>
            <td class="hide"></td>
            <td class="hide_end"></td>
        </tr>

        <tr>
            <td></td>
            <th class="infoTitle"><p>A. Own revenue of {{$sixFinanceFinal->applicable_name}}</p></th>
            @foreach($acts AS $li_act)
                <th><p>{{$li_act->financial_year}} <br/>( in Rs. )</p></th>
                @php
                    $tot_own["A_".$li_act->id]= 0;
                    $tot_tr["A_".$li_act->id]= 0;
                @endphp
            @endforeach
        </tr>

        @php $i= 'a'; @endphp
        @foreach($revenueInfos['own_revenue_cats'] AS $li_o)
        <tr>
            <td></td>
            <td class="infoTitle">{{$i}}) {{$li_o->own_revenue_name}}</td>
            @foreach($acts AS $li_act)
                <td class="right">
                    @if(isset($revenueInfos['revenueInfoOwnFillFinal']["O_".$li_o->id]["A_".$li_act->id]))
                        {{$revenueInfos['revenueInfoOwnFillFinal']["O_".$li_o->id]["A_".$li_act->id]}}
                    @else
                        {{"0"}}
                    @endif
                </td>

                @if(isset($revenueInfos['revenueInfoOwnFillFinal']["O_".$li_o->id]["A_".$li_act->id]))
                    @php
                        $tot_own["A_".$li_act->id]= $tot_own["A_".$li_act->id]+(float)$revenueInfos['revenueInfoOwnFillFinal']["O_".$li_o->id]["A_".$li_act->id];
                    @endphp
                @endif
            @endforeach
        </tr>
        @php $i++; @endphp
        @endforeach

        <tr>
            <td></td>
            <td class="infoTitle">
                <strong><p>Sub-Total 12 A</p></strong>
            </td>
            @foreach($acts AS $li_act)
                <td class="right">
                    @if(isset($tot_own["A_".$li_act->id]))
                        {{$tot_own["A_".$li_act->id]}}
                    @endif
                </td>
            @endforeach
        </tr>

        <tr>
            <td></td>
            <th class="infoTitle">
                <p>B. Arrear taxes/duties at the end of the year</p>
            </th>
            @foreach($acts AS $li_act)
                <td class="right">
                    @if(isset($revenueInfos['revenueInfoArrearFillFinal']["A_".$li_act->id]))
                        {{$revenueInfos['revenueInfoArrearFillFinal']["A_".$li_act->id]}}
                    @else
                        {{"0"}}
                    @endif
                </td>
            @endforeach
        </tr>
        <tr>
            <td></td>
            <td class="infoTitle">
                <strong><p>C. Transferred Resources</p></strong>
            </td>
            @foreach($acts AS $li_act)
                <th><p>{{$li_act->financial_year}} <br/>( in Rs. )</p></th>
            @endforeach
        </tr>
        <tr>
            <td></td>
            <td colspan="6" class="left">
                <strong>(1) Central Share of CSS annually</strong>
            </td>
        </tr>
        @php $i='a'; @endphp
        @foreach($revenueInfos['css_shares'] AS $li_sh)
            <tr>
                <td></td>
                <td>
                    {{$i}}) {{$li_sh->scheme_name}}
                </td>
                @foreach($acts AS $li_act)
                    <td class="right">
                        @if(isset($revenueInfos['revenueInfoShareFillFinal']["S_0"]["C_".$li_sh->id]["A_".$li_act->id]))
                            {{$revenueInfos['revenueInfoShareFillFinal']["S_0"]["C_".$li_sh->id]["A_".$li_act->id]}}
                        @else
                            {{"0"}}
                        @endif
                    </td>

                    @if(isset($revenueInfos['revenueInfoShareFillFinal']["S_0"]["C_".$li_sh->id]["A_".$li_act->id]))
                        @php
                            $tot_tr["A_".$li_act->id]= $tot_tr["A_".$li_act->id]+(float)$revenueInfos['revenueInfoShareFillFinal']["S_0"]["C_".$li_sh->id]["A_".$li_act->id];
                        @endphp
                    @endif
                @endforeach
            </tr>
            @php $i++; @endphp
        @endforeach
        <tr>
            <td></td>
            <td colspan="6" class="left">
                <strong>(2) State Share of CSS annually</strong>
            </td>
        </tr>
        @php $i='a'; @endphp
        @foreach($revenueInfos['css_shares'] AS $li_sh)
            <tr>
                <td></td>
                <td>
                    {{$i}}) {{$li_sh->scheme_name}}
                </td>
                @foreach($acts AS $li_act)

                    <td class="right">
                        @if(isset($revenueInfos['revenueInfoShareFillFinal']["S_1"]["C_".$li_sh->id]["A_".$li_act->id]))
                            {{$revenueInfos['revenueInfoShareFillFinal']["S_1"]["C_".$li_sh->id]["A_".$li_act->id]}}
                        @else
                            {{"0"}}
                        @endif
                    </td>
                    @if(isset($revenueInfos['revenueInfoShareFillFinal']["S_1"]["C_".$li_sh->id]["A_".$li_act->id]))
                        @php
                            $tot_tr["A_".$li_act->id]= $tot_tr["A_".$li_act->id]+(float)$revenueInfos['revenueInfoShareFillFinal']["S_1"]["C_".$li_sh->id]["A_".$li_act->id];
                        @endphp
                    @endif
                @endforeach
            </tr>
            @php $i++; @endphp
        @endforeach


        @php $j=3; @endphp
        @foreach($revenueInfos['tr_cats_final'] AS $li_tr)
            @if(count($li_tr['sublist']) == 0)
                <tr>
                    <td></td>
                    <td>
                        <label>{{$j}}. {{$li_tr['transferred_resource_cat_name']}}</label>
                    </td>
                    @foreach($acts AS $li_act)
                        <td class="right">
                            @if(isset($revenueInfos['revenueInfoTRFillFinal']["C_".$li_tr['id']]["A_".$li_act->id]))
                                {{$revenueInfos['revenueInfoTRFillFinal']["C_".$li_tr['id']]["A_".$li_act->id]}}
                            @else
                                {{"0"}}
                            @endif
                        </td>

                        @if(isset($revenueInfos['revenueInfoTRFillFinal']["C_".$li_tr['id']]["A_".$li_act->id]))
                            @php
                                $tot_tr["A_".$li_act->id]= $tot_tr["A_".$li_act->id]+(float)$revenueInfos['revenueInfoTRFillFinal']["C_".$li_tr['id']]["A_".$li_act->id];
                            @endphp
                        @endif
                    @endforeach
                </tr>
            @else
                <tr class="bg-info">
                    <td></td>
                    <th colspan="6" class="left">
                        <strong> ({{$j}}) {{$li_tr['transferred_resource_cat_name']}} </strong>
                    </th>
                </tr>
            @endif
            @php $i='a'; @endphp
            @foreach($li_tr['sublist'] AS $li_sub)
                <tr>
                    <td></td>
                    <td>
                        {{$i}}) {{$li_sub->transferred_resource_cat_name}}
                    </td>
                    @foreach($acts AS $li_act)
                        <td class="right">
                            @if(isset($revenueInfos['revenueInfoTRFillFinal']["C_".$li_sub->id]["A_".$li_act->id]))
                                {{$revenueInfos['revenueInfoTRFillFinal']["C_".$li_sub->id]["A_".$li_act->id]}}
                            @else
                                {{"0"}}
                            @endif
                        </td>

                        @if(isset($revenueInfos['revenueInfoTRFillFinal']["C_".$li_sub->id]["A_".$li_act->id]))
                            @php
                                $tot_tr["A_".$li_act->id]= $tot_tr["A_".$li_act->id]+(float)$revenueInfos['revenueInfoTRFillFinal']["C_".$li_sub->id]["A_".$li_act->id];
                            @endphp
                        @endif
                    @endforeach
                </tr>
                @php $i++; @endphp
            @endforeach
            @php $j++; @endphp
        @endforeach
        <tr>
            <td></td>
            <th class="infoTitle"><p>Sub-Total 12 C</p></th>
            @foreach($acts AS $li_act)
                <th class="right">
                    @if(isset($tot_tr["A_".$li_act->id]))
                        {{$tot_tr["A_".$li_act->id]}}
                    @endif
                </th>
            @endforeach
        </tr>
        <tr>
            <td class="slNumber"><p>13</p></td>
            <th class="infoTitle"><p>Grand Total Revenue of {{$sixFinanceFinal->applicable_name}} 12 (A+B+C)</p></th>
            @foreach($acts AS $li_act)
                <th class="right">
                     @if(isset($tot_own["A_".$li_act->id]) && isset($revenueInfos['revenueInfoArrearFillFinal']["A_".$li_act->id]) && isset($tot_tr["A_".$li_act->id]))
                        {{(float)$tot_own["A_".$li_act->id]+(float)$revenueInfos['revenueInfoArrearFillFinal']["A_".$li_act->id]+(float)$tot_tr["A_".$li_act->id]}}
                     @endif
                </th>
            @endforeach
        </tr>
        <!----------------------------------- EXPENDITURE INFO SECTION ------------------------------------------------>
        <tr>
            <td class="slNumber"><p>14</p></td>
            <th class="infoTitle hide_end" colspan="6"><p>Revenue Expenditure of {{$sixFinanceFinal->applicable_name}}</p></th>
        </tr>

        @foreach($expInfos['category'] AS $main_cat)
            @php $i = 'a'; @endphp
            @if($main_cat['id'] != 2 && $main_cat['id'] != 3)
                @if($main_cat['list_order'] == 1)
                    <tr>
                        <td></td>
                        <th class="infoTitle"><p>A. Revenue Account</p></th>
                        @foreach($acts AS $li_act)
                            <th><p>{{$li_act->financial_year}} <br/>( in Rs. )</p></th>
                        @endforeach
                    </tr>
                @endif

                @foreach($acts AS $li_act)
                    @php
                        $ad_e_tot[$main_cat['list_order']][$li_act->id]=0;
                    @endphp
                @endforeach

                @if($main_cat['list_order'] == 1)
                    <tr class="bg-info">
                        <td></td>
                        <td colspan="6">
                            <strong> A. {{$main_cat['list_order']}}) {{$main_cat['category_name']}}</strong>
                        </td>
                    </tr>
                @else
                    <tr class="bg-info">
                        <td></td>
                        <td colspan="6">
                            <strong> A. {{$main_cat['list_order']}}) {{$main_cat['category_name']}}</strong>
                        </td>
                    </tr>
                @endif

                    @foreach($expInfos['expenditure'] AS $value)
                        @if($main_cat['id'] == $value['category'])
                            <tr>
                                <td></td>
                                <td>
                                    {{$i++}}) {{$value['expenditure_name']}}
                                </td>
                                @foreach($acts AS $li_act)
                                    <td class="right">
                                        @if(isset($expInfos['dataFillFinal']["E_".$value['category_expenditure_id']]["A_".$li_act->id]))
                                            {{$expInfos['dataFillFinal']["E_".$value['category_expenditure_id']]["A_".$li_act->id]}}
                                        @else
                                            {{"0"}}
                                        @endif
                                    </td>
                                    @if(isset($expInfos['dataFillFinal']["E_".$value['category_expenditure_id']]["A_".$li_act->id]))
                                        @php
                                            $ad_e_tot[$main_cat['list_order']][$li_act->id]=$ad_e_tot[$main_cat['list_order']][$li_act->id]+(float)$expInfos['dataFillFinal']["E_".$value['category_expenditure_id']]["A_".$li_act->id];
                                        @endphp
                                    @endif
                                @endforeach
                            </tr>
                        @endif
                    @endforeach
                    {{------------------------------SUB TOTAL 1 A1------------------------------------------------}}
                    @if($main_cat['list_order'] == 1)

                        <tr>
                            <td></td>
                            <th class="left">
                                <p>Sub-Total (14. A{{$main_cat['list_order']}})</p>
                            </th>
                            @foreach($acts AS $li_act)
                                <td class="right">
                                    @if(isset($ad_e_tot[$main_cat['list_order']][$li_act->id]))
                                        {{$ad_e_tot[$main_cat['list_order']][$li_act->id]}}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endif
                    {{------------------------------SUB TOTAL 1 A2------------------------------------------------}}
                    @if($main_cat['list_order'] == 2)
                        <tr class="bg-danger">
                            <td></td>
                            <th class="left">
                                <p>Sub-Total (14. A{{$main_cat['list_order']}})</p>
                            </th>
                            @foreach($acts AS $li_act)
                                <th class="right">
                                    @if(isset($ad_e_tot[$main_cat['list_order']][$li_act->id]))
                                        {{$ad_e_tot[$main_cat['list_order']][$li_act->id]}}
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    @endif
            @endif
            {{--------------------------------------- Expenditure Against CSS ----------------------------------------}}
            @if($main_cat['id'] == 2)
                @foreach($acts AS $li_act)
                    @php $ad_css_tot[$main_cat['list_order']][$li_act->id]=0; @endphp
                @endforeach
                <tr>
                    <td></td>
                    <th class="infoTitle">
                        <p>A. {{$main_cat['list_order']}} {{$main_cat['category_name']}}</p>
                    </th>
                    @foreach($acts AS $li_act)
                        <th class="right">
                            @if(isset($expInfos['dataFillFinal']["E_34"]["A_".$li_act->id]))
                                {{$expInfos['dataFillFinal']["E_34"]["A_".$li_act->id]}}
                            @else
                                {{"0"}}
                            @endif
                        </th>
                        @if(isset($expInfos['dataFillFinal']["E_34"]["A_".$li_act->id]))
                            @php
                                $ad_css_tot[$main_cat['list_order']][$li_act->id]=$ad_css_tot[$main_cat['list_order']][$li_act->id]+(float)$expInfos['dataFillFinal']["E_34"]["A_".$li_act->id];
                            @endphp
                        @endif
                    @endforeach
                </tr>

                <tr>
                    <td class="slNumber"><p>15</p></td>
                    <th class="left">
                        <p>Total Revenue Expenditure (14.A.1+14.A.2+14.A.3)</p>
                    </th>
                    @foreach($acts AS $li_act)
                        <th class="right">
                            @if(isset($ad_e_tot[1][$li_act->id]) && isset($ad_e_tot[2][$li_act->id]) && isset($ad_css_tot[$main_cat['list_order']][$li_act->id]))
                                {{(float)$ad_e_tot[1][$li_act->id]+(float)$ad_e_tot[2][$li_act->id]+(float)$ad_css_tot[$main_cat['list_order']][$li_act->id]}}
                            @endif
                        </th>
                    @endforeach
                </tr>
            @endif

            @if($main_cat['id'] == 3)
                @foreach($acts AS $li_act)
                    @php $cap_e_tot[$li_act->id]= 0;@endphp
                @endforeach
                <tr>
                    <td class="slNumber"><p>16</p></td>
                    <th class="infoTitle"><p>{{$main_cat['category_name']}}</p></th>
                    <td class="hide"></td>
                    <td class="hide"></td>
                    <td class="hide"></td>
                    <td class="hide"></td>
                    <td class="hide_end"></td>
                </tr>

                    @foreach($expInfos['expenditure'] AS $value)
                        @if($main_cat['id'] == $value['category'])
                            <tr>
                                <td></td>
                                <td>{{$i++}}) {{$value['expenditure_name']}}
                                @foreach($acts AS $li_act)
                                    <td class="right">
                                        @if(isset($expInfos['dataFillFinal']["E_".$value['category_expenditure_id']]["A_".$li_act->id]))
                                            {{$expInfos['dataFillFinal']["E_".$value['category_expenditure_id']]["A_".$li_act->id]}}
                                        @else
                                            {{"0"}}
                                        @endif
                                    </td>

                                    @if(isset($expInfos['dataFillFinal']["E_".$value['category_expenditure_id']]["A_".$li_act->id]))
                                        @php
                                            $cap_e_tot[$li_act->id]=$cap_e_tot[$li_act->id]+(float)$expInfos['dataFillFinal']["E_".$value['category_expenditure_id']]["A_".$li_act->id];
                                        @endphp
                                    @endif
                                @endforeach
                            </tr>
                        @endif
                    @endforeach

                    @if($main_cat['list_order'] == 4)
                        <tr>
                            <td class="slNumber"><p>17</p></td>
                            <th class="left">
                                <strong>Total Capital Expenditure (Sub-Total 16)</strong>
                            </th>
                            @foreach($acts AS $li_act)
                                <th class="right">
                                    @if(isset($cap_e_tot[$li_act->id]))
                                        {{$cap_e_tot[$li_act->id]}}
                                    @endif
                                </th>
                            @endforeach
                        </tr>

                        <tr>
                            <td class="slNumber"><p>18</p></td>
                            <td class="left">
                                <strong>Grand Total Expenditure (15+16)</strong>
                            </td>
                            @foreach($acts AS $li_act)
                                <th class="right">
                                    @if(isset($ad_e_tot[1][$li_act->id]) && isset($ad_e_tot[2][$li_act->id]) && isset($ad_css_tot[3][$li_act->id]) && isset($cap_e_tot[$li_act->id]))
                                        {{(float)$ad_e_tot[1][$li_act->id]+(float)$ad_e_tot[2][$li_act->id]+(float)$ad_css_tot[3][$li_act->id]+(float)$cap_e_tot[$li_act->id]}}
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    @endif
            @endif
        @endforeach
        <!----------------------------------------  BALANCE INFO SECTION	------------------------------------------->
        <tr>
            <td class="slNumber"><p>19</p></td>
            <td class="infoTitle"><p>Opening Balance at the beginning of the year</p></td>
            @foreach($acts AS $li_act)
                <th class="right">
                    @if(isset($balanceInfos["Op_A_" . $li_act->id])){{$balanceInfos["Op_A_" . $li_act->id]}}@else{{"0"}}@endif
                </th>
            @endforeach
        </tr>
        <tr>
            <td class="slNumber"><p>20</p></td>
            <td class="infoTitle"><p>Inflow during the year (as the item 13 above)</p></td>
            @foreach($acts AS $li_act)
                <th class="right">
                    @if(isset($balanceInfos["In_A_" . $li_act->id])){{$balanceInfos["In_A_" . $li_act->id]}}@else{{"0"}}@endif
                </th>
            @endforeach
        </tr>
        <tr>
            <td class="slNumber"><p>21</p></td>
            <td class="infoTitle"><p>Outflow during the year (as the item 18 above)</p></td>
            @foreach($acts AS $li_act)
                <th class="right">
                    @if(isset($balanceInfos["Ou_A_" . $li_act->id])){{$balanceInfos["Ou_A_" . $li_act->id]}}@else{{"0"}}@endif
                </th>
            @endforeach
        </tr>
        <tr>
            <td class="slNumber"><p>22</p></td>
            <th class="infoTitle"><p>Closing balance (20-21+19)<br/> [Inflow-Outflow+Opening Balance]</p></th>
            @foreach($acts AS $li_act)
                <th class="right">
                    @if(isset($balanceInfos["Cl_A_" . $li_act->id])){{$balanceInfos["Cl_A_" . $li_act->id]}}@else{{"0"}}@endif
                </th>
            @endforeach
        </tr>

        <!----------------------------------------  OTHER INFO SECTION	----------------------------------------------->

        <tr>
            <td class="slNumber"><p>23</p></td>
            <td style="text-align: left;">
                <p>Length of different types of roads maintained by {{$sixFinanceFinal->applicable_name}}</p>
            </td>
            @foreach($acts AS $li_act)
                <th><p>{{$li_act->financial_year}} <br/>( in km )</p></th>
            @endforeach
        </tr>

        @php $i="a"; @endphp
        @foreach($otherInfos['cats'] AS $li_cat)
            <tr>
                <td></td>
                <td>{{$i}}. {{$li_cat->road_cat_name}}</td>
                @foreach($acts AS $li_act)
                    <td class="right">
                        @if(isset($otherInfos['otherFinalSub']["SIX_".$six_finance_final_id]["A_".$li_act->id]["C_".$li_cat->id]))
                            {{$otherInfos['otherFinalSub']["SIX_".$six_finance_final_id]["A_".$li_act->id]["C_".$li_cat->id]}}
                        @endif
                    </td>
                @endforeach
            </tr>
            @php $i++; @endphp
        @endforeach

        <tr>
            <td class="slNumber"><p>24</p></td>
            <td class="infoTitle" colspan="2"><p>Present status of Account & Audit of {{$sixFinanceFinal->applicable_name}}</p></td>
            <td colspan="4">
                @if(isset($otherInfos['otherInfoVal']->present_account_audit_status))
                    {{$otherInfos['otherInfoVal']->present_account_audit_status}}
                @endif
            </td>
        </tr>

        <tr>
            <td class="slNumber"><p>25</p></td>
            <td class="infoTitle" colspan="2"><p>Whether {{$sixFinanceFinal->applicable_name}} have trained Accounts staff of its own?</p></td>
            <td class="hide">
                @if(isset($otherInfos['otherInfoVal']->trained_account_staff))
                    @if($otherInfos['otherInfoVal']->trained_account_staff==1)
                        {{"YES"}}
                    @elseif($otherInfos['otherInfoVal']->trained_account_staff==2)
                        {{"NO"}}
                    @endif
                @endif
            </td>
            <td class="hide"></td>
            <td class="hide"></td>
            <td class="hide_end"></td>
        </tr>
        <tr>
            <td class="slNumber"><p>26</p></td>
            <td style="text-align: left;" colspan="6"><p>Whether the following registers maintained in {{$sixFinanceFinal->applicable_name}}?</p></td>
        </tr>
        @php $k='a'; @endphp
        @foreach($otherInfos['register_cats'] AS $reg)
        <tr>
            <td></td>
            <td style="text-align: left;">{{$k}}. {{$reg->register_cat_name}}</td>
            <td class="hide">
                @if(isset($otherInfos['otherFinalReg']["R_".$reg->id]))
                    @if($otherInfos['otherFinalReg']["R_".$reg->id]==1)
                        {{"YES"}}
                    @elseif($otherInfos['otherFinalReg']["R_".$reg->id]==2)
                        {{"NO"}}
                    @endif
                @endif
            </td>
            <td class="hide"></td>
            <td class="hide"></td>
            <td class="hide"></td>
            <td class="hide_end"></td>
        </tr>
        @php $k++; @endphp
        @endforeach

        <tr>
            <td class="slNumber"><p>27</p></td>
            <td style="text-align: left;" colspan="2"><p>Whether separate cash book is maintained for devolution of funds?</p></td>
            <td class="hide">
                @if(isset($otherInfos['otherInfoVal']->seperate_cashbook_maintained))
                    @if($otherInfos['otherInfoVal']->seperate_cashbook_maintained==1)
                        {{"YES"}}
                    @elseif($otherInfos['otherInfoVal']->seperate_cashbook_maintained==2)
                        {{"NO"}}
                    @endif
                @endif
            </td>
            <td class="hide"></td>
            <td class="hide"></td>
            <td class="hide_end"></td>
        </tr>

        <!----------------------------------------  NEXT FIVE YEAR SECTION	------------------------------------------->
        <tr>
            <td class="slNumber"><p>28</p></td>
            <td class="infoTitle"><p>New Schemes proposed for next 5 years (Showing details with estimated cost)</p></td>
            @foreach($financial_years AS $year)
                <th><p>{{$year['financial_year']}} <br/>( in Rs. )</p></th>
            @endforeach
        </tr>
        @php $k=1; @endphp
        @foreach($nextFiveYears['entities'] AS $value)
        <tr>
            <td></td>
            <td>{{$k}}. {{$value['entity_name']}}</td>
            @foreach($financial_years AS $year)
                <td class="right">
                    @if(isset($nextFiveYears['nextFiveYearsFill']["E_".$value->id]["A_".$year->id])){{$nextFiveYears['nextFiveYearsFill']["E_".$value->id]["A_".$year->id]}}@else{{"0"}}@endif
                </td>
            @endforeach
        </tr>
        @php $k++; @endphp
        @endforeach
    </table>
</div>

</body>
</html>