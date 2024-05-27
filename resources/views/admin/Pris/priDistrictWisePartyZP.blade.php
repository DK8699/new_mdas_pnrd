@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_admin_home')

@section('custom_css')
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{asset('mdas_assets/css/multiple-select.min.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .count-style{
            font-weight: 700;font-size: 20px;color: #10436d;text-shadow: 0px 1px 4px #19191d4f;
        }
        .m-b-50{
            margin-bottom: 50px;
        }
        table.dataTable thead th, table.dataTable thead td {
            font-weight: 500;
            text-align: center;
        }
        .partytd {
            font-size: 9px;
        }
        .olophoru {
            font-size: 12px;
        }
    /*    collapsible */
        .collapsible1 {
            background-color: #03A9F4;
            color: white;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
            margin-bottom: 1px;
        }

         .collapsible1:hover {
            background-color: #00BCD4;
        }

        .collapsible1:after {
            content: '\002B';
            color: white;
            font-weight: bold;
            float: right;
            margin-left: 5px;
        }

        .active:after {
            content: "\2212";
        }

        .content {
            padding: 0 0px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 1.5s ease-out;
            background-color: #f1f1f1;
        }

        
    </style>
@endsection

@section('content')
{{--******************************************< Calculation >*******************************************************--}}
                            @php
//===========================================< Initialisation >=========================================================
//------------------------------------------< ZP Initialisation >-------------------------------------------------------

                            // PRESIDENT
                            $grand_ZP__AGP__P = 0;
                            $grand_ZP__BJP__P = 0;
                            $grand_ZP__CON__P = 0;
                            $grand_ZP__AUIDF__P = 0;
                            $grand_ZP__IND__P = 0;
                            $grand_ZP__OTH__P = 0;
                            // VICE-PRESIDENT
                            $grand_ZP__AGP__V = 0;
                            $grand_ZP__BJP__V = 0;
                            $grand_ZP__CON__V = 0;
                            $grand_ZP__AUIDF__V = 0;
                            $grand_ZP__IND__V = 0;
                            $grand_ZP__OTH__V = 0;
                            // MEMBER
                            $grand_ZP__AGP__M = 0;
                            $grand_ZP__BJP__M = 0;
                            $grand_ZP__CON__M = 0;
                            $grand_ZP__AUIDF__M = 0;
                            $grand_ZP__IND__M = 0;
                            $grand_ZP__OTH__M = 0;
                            // ALL TOTAL PRIs
                            $all_grand_Total_ZP = 0;
                            // TOTAL PER PARTY
                            $total_AGP_PRI_ZP = 0;
                            $total_BJP_PRI_ZP = 0;
                            $total_CON_PRI_ZP = 0;
                            $total_AUIDF_PRI_ZP = 0;
                            $total_IND_PRI_ZP = 0;
                            $total_OTH_PRI_ZP = 0;
//------------------------------------------< AP Initialisation >-------------------------------------------------------

                            // PRESIDENT
                            $grand_AP__AGP__P = 0;
                            $grand_AP__BJP__P = 0;
                            $grand_AP__CON__P = 0;
                            $grand_AP__AUIDF__P = 0;
                            $grand_AP__IND__P = 0;
                            $grand_AP__OTH__P = 0;
                            // VICE-PRESIDENT
                            $grand_AP__AGP__V = 0;
                            $grand_AP__BJP__V = 0;
                            $grand_AP__CON__V = 0;
                            $grand_AP__AUIDF__V = 0;
                            $grand_AP__IND__V = 0;
                            $grand_AP__OTH__V = 0;
                            // MEMBER
                            $grand_AP__AGP__M = 0;
                            $grand_AP__BJP__M = 0;
                            $grand_AP__CON__M = 0;
                            $grand_AP__AUIDF__M = 0;
                            $grand_AP__IND__M = 0;
                            $grand_AP__OTH__M = 0;
                            // ALL TOTAL PRIs
                            $all_grand_Total_AP = 0;
                            // TOTAL PER PARTY
                            $total_AGP_PRI_AP = 0;
                            $total_BJP_PRI_AP = 0;
                            $total_CON_PRI_AP = 0;
                            $total_AUIDF_PRI_AP = 0;
                            $total_IND_PRI_AP = 0;
                            $total_OTH_PRI_AP = 0;

//------------------------------------------< GP Initialisation >-------------------------------------------------------

                            // PRESIDENT
                            $grand_GP__AGP__P = 0;
                            $grand_GP__BJP__P = 0;
                            $grand_GP__CON__P = 0;
                            $grand_GP__AUIDF__P = 0;
                            $grand_GP__IND__P = 0;
                            $grand_GP__OTH__P = 0;
                            // VICE-PRESIDENT
                            $grand_GP__AGP__V = 0;
                            $grand_GP__BJP__V = 0;
                            $grand_GP__CON__V = 0;
                            $grand_GP__AUIDF__V = 0;
                            $grand_GP__IND__V = 0;
                            $grand_GP__OTH__V = 0;
                            // MEMBER
                            $grand_GP__AGP__M = 0;
                            $grand_GP__BJP__M = 0;
                            $grand_GP__CON__M = 0;
                            $grand_GP__AUIDF__M = 0;
                            $grand_GP__IND__M = 0;
                            $grand_GP__OTH__M = 0;
                            // ALL TOTAL PRIs
                            $all_grand_Total_GP = 0;
                            // TOTAL PER PARTY
                            $total_AGP_PRI_GP = 0;
                            $total_BJP_PRI_GP = 0;
                            $total_CON_PRI_GP = 0;
                            $total_AUIDF_PRI_GP = 0;
                            $total_IND_PRI_GP = 0;
                            $total_OTH_PRI_GP = 0;
//===========================================< End Initialisation >=====================================================
                            @endphp

{{-------------------------------------------< Calculation for head total >-------------------------------------------}}
            @foreach($zpsParty AS $zp)
                @php
                //............................................ZP......................................................
                            // PRESIDENT
                           $grand_ZP__AGP__P = $grand_ZP__AGP__P + $finalPartyArr["ZP__AGP_id_P" . $zp->id];
                           $grand_ZP__BJP__P = $grand_ZP__BJP__P + $finalPartyArr["ZP__BJP_id_P" . $zp->id];
                           $grand_ZP__CON__P = $grand_ZP__CON__P + $finalPartyArr["ZP__CON_id_P" . $zp->id];
                           $grand_ZP__AUIDF__P = $grand_ZP__AUIDF__P + $finalPartyArr["ZP__AUIDF_id_P" . $zp->id];
                           $grand_ZP__IND__P = $grand_ZP__IND__P + $finalPartyArr["ZP__IND_id_P" . $zp->id];
                           $grand_ZP__OTH__P = $grand_ZP__OTH__P + $finalPartyArr["ZP__OTH_id_P" . $zp->id];
                            // VICE PRESIDENT
                           $grand_ZP__AGP__V = $grand_ZP__AGP__V + $finalPartyArr["ZP__AGP_id_V" . $zp->id];
                           $grand_ZP__BJP__V = $grand_ZP__BJP__V + $finalPartyArr["ZP__BJP_id_V" . $zp->id];
                           $grand_ZP__CON__V = $grand_ZP__CON__V + $finalPartyArr["ZP__CON_id_V" . $zp->id];
                           $grand_ZP__AUIDF__V = $grand_ZP__AUIDF__V + $finalPartyArr["ZP__AUIDF_id_V" . $zp->id];
                           $grand_ZP__IND__V = $grand_ZP__IND__V + $finalPartyArr["ZP__IND_id_V" . $zp->id];
                           $grand_ZP__OTH__V = $grand_ZP__OTH__V + $finalPartyArr["ZP__OTH_id_V" . $zp->id];
                            // MEMBER
                           $grand_ZP__AGP__M = $grand_ZP__AGP__M + $finalPartyArr["ZP__AGP_id_M" . $zp->id];
                           $grand_ZP__BJP__M = $grand_ZP__BJP__M + $finalPartyArr["ZP__BJP_id_M" . $zp->id];
                           $grand_ZP__CON__M = $grand_ZP__CON__M + $finalPartyArr["ZP__CON_id_M" . $zp->id];
                           $grand_ZP__AUIDF__M = $grand_ZP__AUIDF__M + $finalPartyArr["ZP__AUIDF_id_M" . $zp->id];
                           $grand_ZP__IND__M = $grand_ZP__IND__M + $finalPartyArr["ZP__IND_id_M" . $zp->id];
                           $grand_ZP__OTH__M = $grand_ZP__OTH__M + $finalPartyArr["ZP__OTH_id_M" . $zp->id];
                           // ALL TOTAL PRIs
                           $all_grand_Total_ZP = $all_grand_Total_ZP +
                                              $finalPartyArr["ZP__AGP_id_P" . $zp->id] +
                                              $finalPartyArr["ZP__BJP_id_P" . $zp->id] +
                                              $finalPartyArr["ZP__CON_id_P" . $zp->id] +
                                              $finalPartyArr["ZP__AUIDF_id_P" . $zp->id] +
                                              $finalPartyArr["ZP__IND_id_P" . $zp->id] +
                                              $finalPartyArr["ZP__OTH_id_P" . $zp->id] +

                                              $finalPartyArr["ZP__AGP_id_V" . $zp->id] +
                                              $finalPartyArr["ZP__BJP_id_V" . $zp->id] +
                                              $finalPartyArr["ZP__CON_id_V" . $zp->id] +
                                              $finalPartyArr["ZP__AUIDF_id_V" . $zp->id] +
                                              $finalPartyArr["ZP__IND_id_V" . $zp->id] +
                                              $finalPartyArr["ZP__OTH_id_V" . $zp->id] +

                                              $finalPartyArr["ZP__AGP_id_M" . $zp->id] +
                                              $finalPartyArr["ZP__BJP_id_M" . $zp->id] +
                                              $finalPartyArr["ZP__CON_id_M" . $zp->id] +
                                              $finalPartyArr["ZP__AUIDF_id_M" . $zp->id] +
                                              $finalPartyArr["ZP__IND_id_M" . $zp->id] +
                                              $finalPartyArr["ZP__OTH_id_M" . $zp->id];
                           // TOTAL PER PARTY
                           $total_AGP_PRI_ZP = $total_AGP_PRI_ZP +
                                               $finalPartyArr["ZP__AGP_id_P" . $zp->id] +
                                               $finalPartyArr["ZP__AGP_id_V" . $zp->id] +
                                               $finalPartyArr["ZP__AGP_id_M" . $zp->id];
                           $total_BJP_PRI_ZP = $total_BJP_PRI_ZP +
                                               $finalPartyArr["ZP__BJP_id_P" . $zp->id] +
                                               $finalPartyArr["ZP__BJP_id_V" . $zp->id] +
                                               $finalPartyArr["ZP__BJP_id_M" . $zp->id];
                           $total_CON_PRI_ZP = $total_CON_PRI_ZP +
                                               $finalPartyArr["ZP__CON_id_P" . $zp->id] +
                                               $finalPartyArr["ZP__CON_id_V" . $zp->id] +
                                               $finalPartyArr["ZP__CON_id_M" . $zp->id];
                           $total_AUIDF_PRI_ZP = $total_AUIDF_PRI_ZP +
                                               $finalPartyArr["ZP__AUIDF_id_P" . $zp->id] +
                                               $finalPartyArr["ZP__AUIDF_id_V" . $zp->id] +
                                               $finalPartyArr["ZP__AUIDF_id_M" . $zp->id];
                           $total_IND_PRI_ZP = $total_IND_PRI_ZP +
                                               $finalPartyArr["ZP__IND_id_P" . $zp->id] +
                                               $finalPartyArr["ZP__IND_id_V" . $zp->id] +
                                               $finalPartyArr["ZP__IND_id_M" . $zp->id];
                           $total_OTH_PRI_ZP = $total_OTH_PRI_ZP +
                                               $finalPartyArr["ZP__OTH_id_P" . $zp->id] +
                                               $finalPartyArr["ZP__OTH_id_V" . $zp->id] +
                                               $finalPartyArr["ZP__OTH_id_M" . $zp->id];

                //............................................AP......................................................
                    // PRESIDENT
                   $grand_AP__AGP__P = $grand_AP__AGP__P + $finalPartyArr["AP__AGP_id_P" . $zp->id];
                   $grand_AP__BJP__P = $grand_AP__BJP__P + $finalPartyArr["AP__BJP_id_P" . $zp->id];
                   $grand_AP__CON__P = $grand_AP__CON__P + $finalPartyArr["AP__CON_id_P" . $zp->id];
                   $grand_AP__AUIDF__P = $grand_AP__AUIDF__P + $finalPartyArr["AP__AUIDF_id_P" . $zp->id];
                   $grand_AP__IND__P = $grand_AP__IND__P + $finalPartyArr["AP__IND_id_P" . $zp->id];
                   $grand_AP__OTH__P = $grand_AP__OTH__P + $finalPartyArr["AP__OTH_id_P" . $zp->id];
                    // VICE PRESIDENT
                   $grand_AP__AGP__V = $grand_AP__AGP__V + $finalPartyArr["AP__AGP_id_V" . $zp->id];
                   $grand_AP__BJP__V = $grand_AP__BJP__V + $finalPartyArr["AP__BJP_id_V" . $zp->id];
                   $grand_AP__CON__V = $grand_AP__CON__V + $finalPartyArr["AP__CON_id_V" . $zp->id];
                   $grand_AP__AUIDF__V = $grand_AP__AUIDF__V + $finalPartyArr["AP__AUIDF_id_V" . $zp->id];
                   $grand_AP__IND__V = $grand_AP__IND__V + $finalPartyArr["AP__IND_id_V" . $zp->id];
                   $grand_AP__OTH__V = $grand_AP__OTH__V + $finalPartyArr["AP__OTH_id_V" . $zp->id];
                    // MEMBER
                   $grand_AP__AGP__M = $grand_AP__AGP__M + $finalPartyArr["AP__AGP_id_M" . $zp->id];
                   $grand_AP__BJP__M = $grand_AP__BJP__M + $finalPartyArr["AP__BJP_id_M" . $zp->id];
                   $grand_AP__CON__M = $grand_AP__CON__M + $finalPartyArr["AP__CON_id_M" . $zp->id];
                   $grand_AP__AUIDF__M = $grand_AP__AUIDF__M + $finalPartyArr["AP__AUIDF_id_M" . $zp->id];
                   $grand_AP__IND__M = $grand_AP__IND__M + $finalPartyArr["AP__IND_id_M" . $zp->id];
                   $grand_AP__OTH__M = $grand_AP__OTH__M + $finalPartyArr["AP__OTH_id_M" . $zp->id];
                   // ALL TOTAL PRIs
                   $all_grand_Total_AP = $all_grand_Total_AP +
                                      $finalPartyArr["AP__AGP_id_P" . $zp->id] +
                                      $finalPartyArr["AP__BJP_id_P" . $zp->id] +
                                      $finalPartyArr["AP__CON_id_P" . $zp->id] +
                                      $finalPartyArr["AP__AUIDF_id_P" . $zp->id] +
                                      $finalPartyArr["AP__IND_id_P" . $zp->id] +
                                      $finalPartyArr["AP__OTH_id_P" . $zp->id] +

                                      $finalPartyArr["AP__AGP_id_V" . $zp->id] +
                                      $finalPartyArr["AP__BJP_id_V" . $zp->id] +
                                      $finalPartyArr["AP__CON_id_V" . $zp->id] +
                                      $finalPartyArr["AP__AUIDF_id_V" . $zp->id] +
                                      $finalPartyArr["AP__IND_id_V" . $zp->id] +
                                      $finalPartyArr["AP__OTH_id_V" . $zp->id] +

                                      $finalPartyArr["AP__AGP_id_M" . $zp->id] +
                                      $finalPartyArr["AP__BJP_id_M" . $zp->id] +
                                      $finalPartyArr["AP__CON_id_M" . $zp->id] +
                                      $finalPartyArr["AP__AUIDF_id_M" . $zp->id] +
                                      $finalPartyArr["AP__IND_id_M" . $zp->id] +
                                      $finalPartyArr["AP__OTH_id_M" . $zp->id];
                   // TOTAL PER PARTY
                   $total_AGP_PRI_AP = $total_AGP_PRI_AP +
                                       $finalPartyArr["AP__AGP_id_P" . $zp->id] +
                                       $finalPartyArr["AP__AGP_id_V" . $zp->id] +
                                       $finalPartyArr["AP__AGP_id_M" . $zp->id];
                   $total_BJP_PRI_AP = $total_BJP_PRI_AP +
                                       $finalPartyArr["AP__BJP_id_P" . $zp->id] +
                                       $finalPartyArr["AP__BJP_id_V" . $zp->id] +
                                       $finalPartyArr["AP__BJP_id_M" . $zp->id];
                   $total_CON_PRI_AP = $total_CON_PRI_AP +
                                       $finalPartyArr["AP__CON_id_P" . $zp->id] +
                                       $finalPartyArr["AP__CON_id_V" . $zp->id] +
                                       $finalPartyArr["AP__CON_id_M" . $zp->id];
                   $total_AUIDF_PRI_AP = $total_AUIDF_PRI_AP +
                                       $finalPartyArr["AP__AUIDF_id_P" . $zp->id] +
                                       $finalPartyArr["AP__AUIDF_id_V" . $zp->id] +
                                       $finalPartyArr["AP__AUIDF_id_M" . $zp->id];
                   $total_IND_PRI_AP = $total_IND_PRI_AP +
                                       $finalPartyArr["AP__IND_id_P" . $zp->id] +
                                       $finalPartyArr["AP__IND_id_V" . $zp->id] +
                                       $finalPartyArr["AP__IND_id_M" . $zp->id];
                   $total_OTH_PRI_AP = $total_OTH_PRI_AP +
                                       $finalPartyArr["AP__OTH_id_P" . $zp->id] +
                                       $finalPartyArr["AP__OTH_id_V" . $zp->id] +
                                       $finalPartyArr["AP__OTH_id_M" . $zp->id];


                //............................................GP......................................................
                    // PRESIDENT
                   $grand_GP__AGP__P = $grand_GP__AGP__P + $finalPartyArr["GP__AGP_id_P" . $zp->id];
                   $grand_GP__BJP__P = $grand_GP__BJP__P + $finalPartyArr["GP__BJP_id_P" . $zp->id];
                   $grand_GP__CON__P = $grand_GP__CON__P + $finalPartyArr["GP__CON_id_P" . $zp->id];
                   $grand_GP__AUIDF__P = $grand_GP__AUIDF__P + $finalPartyArr["GP__AUIDF_id_P" . $zp->id];
                   $grand_GP__IND__P = $grand_GP__IND__P + $finalPartyArr["GP__IND_id_P" . $zp->id];
                   $grand_GP__OTH__P = $grand_GP__OTH__P + $finalPartyArr["GP__OTH_id_P" . $zp->id];
                    // VICE PRESIDENT
                   $grand_GP__AGP__V = $grand_GP__AGP__V + $finalPartyArr["GP__AGP_id_V" . $zp->id];
                   $grand_GP__BJP__V = $grand_GP__BJP__V + $finalPartyArr["GP__BJP_id_V" . $zp->id];
                   $grand_GP__CON__V = $grand_GP__CON__V + $finalPartyArr["GP__CON_id_V" . $zp->id];
                   $grand_GP__AUIDF__V = $grand_GP__AUIDF__V + $finalPartyArr["GP__AUIDF_id_V" . $zp->id];
                   $grand_GP__IND__V = $grand_GP__IND__V + $finalPartyArr["GP__IND_id_V" . $zp->id];
                   $grand_GP__OTH__V = $grand_GP__OTH__V + $finalPartyArr["GP__OTH_id_V" . $zp->id];
                    // MEMBER
                   $grand_GP__AGP__M = $grand_GP__AGP__M + $finalPartyArr["GP__AGP_id_M" . $zp->id];
                   $grand_GP__BJP__M = $grand_GP__BJP__M + $finalPartyArr["GP__BJP_id_M" . $zp->id];
                   $grand_GP__CON__M = $grand_GP__CON__M + $finalPartyArr["GP__CON_id_M" . $zp->id];
                   $grand_GP__AUIDF__M = $grand_GP__AUIDF__M + $finalPartyArr["GP__AUIDF_id_M" . $zp->id];
                   $grand_GP__IND__M = $grand_GP__IND__M + $finalPartyArr["GP__IND_id_M" . $zp->id];
                   $grand_GP__OTH__M = $grand_GP__OTH__M + $finalPartyArr["GP__OTH_id_M" . $zp->id];
                   // ALL TOTAL PRIs
                   $all_grand_Total_GP = $all_grand_Total_GP +
                                      $finalPartyArr["GP__AGP_id_P" . $zp->id] +
                                      $finalPartyArr["GP__BJP_id_P" . $zp->id] +
                                      $finalPartyArr["GP__CON_id_P" . $zp->id] +
                                      $finalPartyArr["GP__AUIDF_id_P" . $zp->id] +
                                      $finalPartyArr["GP__IND_id_P" . $zp->id] +
                                      $finalPartyArr["GP__OTH_id_P" . $zp->id] +

                                      $finalPartyArr["GP__AGP_id_V" . $zp->id] +
                                      $finalPartyArr["GP__BJP_id_V" . $zp->id] +
                                      $finalPartyArr["GP__CON_id_V" . $zp->id] +
                                      $finalPartyArr["GP__AUIDF_id_V" . $zp->id] +
                                      $finalPartyArr["GP__IND_id_V" . $zp->id] +
                                      $finalPartyArr["GP__OTH_id_V" . $zp->id] +

                                      $finalPartyArr["GP__AGP_id_M" . $zp->id] +
                                      $finalPartyArr["GP__BJP_id_M" . $zp->id] +
                                      $finalPartyArr["GP__CON_id_M" . $zp->id] +
                                      $finalPartyArr["GP__AUIDF_id_M" . $zp->id] +
                                      $finalPartyArr["GP__IND_id_M" . $zp->id] +
                                      $finalPartyArr["GP__OTH_id_M" . $zp->id];
                   // TOTAL PER PARTY
                   $total_AGP_PRI_GP = $total_AGP_PRI_GP +
                                       $finalPartyArr["GP__AGP_id_P" . $zp->id] +
                                       $finalPartyArr["GP__AGP_id_V" . $zp->id] +
                                       $finalPartyArr["GP__AGP_id_M" . $zp->id];
                   $total_BJP_PRI_GP = $total_BJP_PRI_GP +
                                       $finalPartyArr["GP__BJP_id_P" . $zp->id] +
                                       $finalPartyArr["GP__BJP_id_V" . $zp->id] +
                                       $finalPartyArr["GP__BJP_id_M" . $zp->id];
                   $total_CON_PRI_GP = $total_CON_PRI_GP +
                                       $finalPartyArr["GP__CON_id_P" . $zp->id] +
                                       $finalPartyArr["GP__CON_id_V" . $zp->id] +
                                       $finalPartyArr["GP__CON_id_M" . $zp->id];
                   $total_AUIDF_PRI_GP = $total_AUIDF_PRI_GP +
                                       $finalPartyArr["GP__AUIDF_id_P" . $zp->id] +
                                       $finalPartyArr["GP__AUIDF_id_V" . $zp->id] +
                                       $finalPartyArr["GP__AUIDF_id_M" . $zp->id];
                   $total_IND_PRI_GP = $total_IND_PRI_GP +
                                       $finalPartyArr["GP__IND_id_P" . $zp->id] +
                                       $finalPartyArr["GP__IND_id_V" . $zp->id] +
                                       $finalPartyArr["GP__IND_id_M" . $zp->id];
                   $total_OTH_PRI_GP = $total_OTH_PRI_GP +
                                       $finalPartyArr["GP__OTH_id_P" . $zp->id] +
                                       $finalPartyArr["GP__OTH_id_V" . $zp->id] +
                                       $finalPartyArr["GP__OTH_id_M" . $zp->id];

                @endphp
            @endforeach



    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            <li class="active"><a href="{{route('admin.Pris.priMenu')}}"> Back To PRIs Dashboard</a></li>
        </ol>
    </div>
    <div class="container-fluid">
        <h1 style="text-align: center; font-family: 'Old Standard TT', serif;"><u>PRIs View By Political Party</u></h1>
        <button class="collapsible1">Zila Parishads PRIs
                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total :</span> <span class="count-style"> {{$all_grand_Total_ZP}} </span> |
                <span class="olophoru">AGP :</span> <span class="count-style"> {{$total_AGP_PRI_ZP}}</span>
                <span class="olophoru">| BJP :</span> <span class="count-style"> {{$total_BJP_PRI_ZP}}</span>
                <span class="olophoru">| Congress :</span> <span class="count-style"> {{$total_CON_PRI_ZP}}</span>
                <span class="olophoru">| AUIDF :</span> <span class="count-style"> {{$total_AUIDF_PRI_ZP}}</span>
                <span class="olophoru">| Ind :</span> <span class="count-style"> {{$total_IND_PRI_ZP}}</span>
                <span class="olophoru">| Others :</span> <span class="count-style"> {{$total_OTH_PRI_ZP}}</span></button>
        <div class="content">
        <div class="row m-b-50">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable1">
                        <thead>
                        <tr class="bg-primary">

                            <td rowspan="2">SL</td>
                            <td rowspan="2">Zila</td>
                            <th colspan="6">President</th>
                            <th colspan="6">VicePresident</th>
                            <th colspan="6">Member</th>
                            <td rowspan="2">Total</td>
                        <tr class="bg-primary">
                            <td class="partytd">AGP</td>
                            <td class="partytd">BJP</td>
                            <td class="partytd">Congress</td>
                            <td class="partytd">AUIDF</td>
                            <td class="partytd">Ind</td>
                            <td class="partytd">Others</td>
                            <td class="partytd">AGP</td>
                            <td class="partytd">BJP</td>
                            <td class="partytd">Congress</td>
                            <td class="partytd">AUIDF</td>
                            <td class="partytd">Ind</td>
                            <td class="partytd">Others</td>
                            <td class="partytd">AGP</td>
                            <td class="partytd">BJP</td>
                            <td class="partytd">Congress</td>
                            <td class="partytd">AUIDF</td>
                            <td class="partytd">Ind</td>
                            <td class="partytd">Others</td>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($zpsParty AS $zp)
                            <tr>
                                <td class="partytd">{{$i}}</td>
                                <td class="partytd">{{$zp->zila_parishad_name}}</td>
                                {{--PRESIDENT--}}
                                <td>{{$finalPartyArr["ZP__AGP_id_P" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["ZP__BJP_id_P" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["ZP__CON_id_P" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["ZP__AUIDF_id_P" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["ZP__IND_id_P" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["ZP__OTH_id_P" . $zp->id]}}</td>
                                {{--VICE PRESIDENT--}}
                                <td>{{$finalPartyArr["ZP__AGP_id_V" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["ZP__BJP_id_V" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["ZP__CON_id_V" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["ZP__AUIDF_id_V" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["ZP__IND_id_V" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["ZP__OTH_id_V" . $zp->id]}}</td>
                                {{--MEMBER--}}
                                <td>{{$finalPartyArr["ZP__AGP_id_M" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["ZP__BJP_id_M" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["ZP__CON_id_M" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["ZP__AUIDF_id_M" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["ZP__IND_id_M" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["ZP__OTH_id_M" . $zp->id]}}</td>

                                {{--TOTAL--}}
                                <td>
                                    {{$finalPartyArr["ZP__AGP_id_P" . $zp->id]+
                                      $finalPartyArr["ZP__AGP_id_V" . $zp->id]+
                                      $finalPartyArr["ZP__AGP_id_M" . $zp->id]+

                                      $finalPartyArr["ZP__BJP_id_P" . $zp->id]+
                                      $finalPartyArr["ZP__BJP_id_V" . $zp->id]+
                                      $finalPartyArr["ZP__BJP_id_M" . $zp->id]+

                                      $finalPartyArr["ZP__CON_id_P" . $zp->id]+
                                      $finalPartyArr["ZP__CON_id_V" . $zp->id]+
                                      $finalPartyArr["ZP__CON_id_M" . $zp->id]+

                                      $finalPartyArr["ZP__AUIDF_id_P" . $zp->id]+
                                      $finalPartyArr["ZP__AUIDF_id_V" . $zp->id]+
                                      $finalPartyArr["ZP__AUIDF_id_M" . $zp->id]+

                                      $finalPartyArr["ZP__IND_id_P" . $zp->id]+
                                      $finalPartyArr["ZP__IND_id_V" . $zp->id]+
                                      $finalPartyArr["ZP__IND_id_M" . $zp->id]+

                                      $finalPartyArr["ZP__OTH_id_P" . $zp->id]+
                                      $finalPartyArr["ZP__OTH_id_V" . $zp->id]+
                                      $finalPartyArr["ZP__OTH_id_M" . $zp->id]
                                    }}
                                </td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                        </tbody>
                        <tfoot class="bg-danger">
                        <tr>
                            <td colspan="2">Total</td>
                            {{------------------------------------PRESIDENT-----------------------------------------------------------------------}}
                            <td> {{$grand_ZP__AGP__P}} </td>
                            <td> {{$grand_ZP__BJP__P}} </td>
                            <td> {{$grand_ZP__CON__P}} </td>
                            <td> {{$grand_ZP__AUIDF__P}} </td>
                            <td> {{$grand_ZP__IND__P}} </td>
                            <td> {{$grand_ZP__OTH__P}} </td>
                            {{------------------------------------VICE PRESIDENT------------------------------------------------------------------}}
                            <td> {{$grand_ZP__AGP__V}} </td>
                            <td> {{$grand_ZP__BJP__V}} </td>
                            <td> {{$grand_ZP__CON__V}} </td>
                            <td> {{$grand_ZP__AUIDF__V}} </td>
                            <td> {{$grand_ZP__IND__V}} </td>
                            <td> {{$grand_ZP__OTH__V}} </td>
                            {{------------------------------------MEMBER------------------------------------------------------------------}}
                            <td> {{$grand_ZP__AGP__M}} </td>
                            <td> {{$grand_ZP__BJP__M}} </td>
                            <td> {{$grand_ZP__CON__M}} </td>
                            <td> {{$grand_ZP__AUIDF__M}} </td>
                            <td> {{$grand_ZP__IND__M}} </td>
                            <td> {{$grand_ZP__OTH__M}} </td>
                            <td> {{$all_grand_Total_ZP}} </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        </div>
        <button class="collapsible1">Anchalik Parishads PRIs Total : <span class="count-style"> {{$all_grand_Total_AP}} </span> |
            <span class="olophoru">AGP :</span> <span class="count-style"> {{$total_AGP_PRI_AP}}</span>
            <span class="olophoru">| BJP :</span> <span class="count-style"> {{$total_BJP_PRI_AP}}</span>
            <span class="olophoru">| Congress :</span> <span class="count-style"> {{$total_CON_PRI_AP}}</span>
            <span class="olophoru">| AUIDF :</span> <span class="count-style"> {{$total_AUIDF_PRI_AP}}</span>
            <span class="olophoru">| Ind :</span> <span class="count-style"> {{$total_IND_PRI_AP}}</span>
            <span class="olophoru">| Others :</span> <span class="count-style"> {{$total_OTH_PRI_AP}}</span></button>
        <div class="content">
        <div class="row m-b-50">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable2">
                        <thead>
                        <tr class="bg-primary">

                            <td rowspan="2">SL</td>
                            <td rowspan="2">Zila</td>
                            <th colspan="6">President</th>
                            <th colspan="6">VicePresident</th>
                            <th colspan="6">Member</th>
                            <td rowspan="2">Total</td>
                        <tr class="bg-primary">
                            <td class="partytd">AGP</td>
                            <td class="partytd">BJP</td>
                            <td class="partytd">Congress</td>
                            <td class="partytd">AUIDF</td>
                            <td class="partytd">Ind</td>
                            <td class="partytd">Others</td>
                            <td class="partytd">AGP</td>
                            <td class="partytd">BJP</td>
                            <td class="partytd">Congress</td>
                            <td class="partytd">AUIDF</td>
                            <td class="partytd">Ind</td>
                            <td class="partytd">Others</td>
                            <td class="partytd">AGP</td>
                            <td class="partytd">BJP</td>
                            <td class="partytd">Congress</td>
                            <td class="partytd">AUIDF</td>
                            <td class="partytd">Ind</td>
                            <td class="partytd">Others</td>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($zpsParty AS $zp)
                            <tr>
                                <td class="partytd">{{$i}}</td>
                                <td class="partytd">{{$zp->zila_parishad_name}}</td>
                                {{--PRESIDENT--}}
                                <td>{{$finalPartyArr["AP__AGP_id_P" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["AP__BJP_id_P" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["AP__CON_id_P" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["AP__AUIDF_id_P" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["AP__IND_id_P" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["AP__OTH_id_P" . $zp->id]}}</td>
                                {{--VICE PRESIDENT--}}
                                <td>{{$finalPartyArr["AP__AGP_id_V" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["AP__BJP_id_V" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["AP__CON_id_V" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["AP__AUIDF_id_V" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["AP__IND_id_V" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["AP__OTH_id_V" . $zp->id]}}</td>
                                {{--MEMBER--}}
                                <td>{{$finalPartyArr["AP__AGP_id_M" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["AP__BJP_id_M" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["AP__CON_id_M" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["AP__AUIDF_id_M" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["AP__IND_id_M" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["AP__OTH_id_M" . $zp->id]}}</td>

                                {{--TOTAL--}}
                                <td>
                                    {{$finalPartyArr["AP__AGP_id_P" . $zp->id]+
                                      $finalPartyArr["AP__AGP_id_V" . $zp->id]+
                                      $finalPartyArr["AP__AGP_id_M" . $zp->id]+

                                      $finalPartyArr["AP__BJP_id_P" . $zp->id]+
                                      $finalPartyArr["AP__BJP_id_V" . $zp->id]+
                                      $finalPartyArr["AP__BJP_id_M" . $zp->id]+

                                      $finalPartyArr["AP__CON_id_P" . $zp->id]+
                                      $finalPartyArr["AP__CON_id_V" . $zp->id]+
                                      $finalPartyArr["AP__CON_id_M" . $zp->id]+

                                      $finalPartyArr["AP__AUIDF_id_P" . $zp->id]+
                                      $finalPartyArr["AP__AUIDF_id_V" . $zp->id]+
                                      $finalPartyArr["AP__AUIDF_id_M" . $zp->id]+

                                      $finalPartyArr["AP__IND_id_P" . $zp->id]+
                                      $finalPartyArr["AP__IND_id_V" . $zp->id]+
                                      $finalPartyArr["AP__IND_id_M" . $zp->id]+

                                      $finalPartyArr["AP__OTH_id_P" . $zp->id]+
                                      $finalPartyArr["AP__OTH_id_V" . $zp->id]+
                                      $finalPartyArr["AP__OTH_id_M" . $zp->id]
                                    }}
                                </td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                        </tbody>
                        <tfoot class="bg-danger">
                        <tr>
                            <td colspan="2">Total</td>
                            {{------------------------------------PRESIDENT-----------------------------------------------------------------------}}
                            <td> {{$grand_AP__AGP__P}} </td>
                            <td> {{$grand_AP__BJP__P}} </td>
                            <td> {{$grand_AP__CON__P}} </td>
                            <td> {{$grand_AP__AUIDF__P}} </td>
                            <td> {{$grand_AP__IND__P}} </td>
                            <td> {{$grand_AP__OTH__P}} </td>
                            {{------------------------------------VICE PRESIDENT------------------------------------------------------------------}}
                            <td> {{$grand_AP__AGP__V}} </td>
                            <td> {{$grand_AP__BJP__V}} </td>
                            <td> {{$grand_AP__CON__V}} </td>
                            <td> {{$grand_AP__AUIDF__V}} </td>
                            <td> {{$grand_AP__IND__V}} </td>
                            <td> {{$grand_AP__OTH__V}} </td>
                            {{------------------------------------MEMBER------------------------------------------------------------------}}
                            <td> {{$grand_AP__AGP__M}} </td>
                            <td> {{$grand_AP__BJP__M}} </td>
                            <td> {{$grand_AP__CON__M}} </td>
                            <td> {{$grand_AP__AUIDF__M}} </td>
                            <td> {{$grand_AP__IND__M}} </td>
                            <td> {{$grand_AP__OTH__M}} </td>
                            <td> {{$all_grand_Total_AP}} </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        </div>
        <button class="collapsible1">Gram Parishads PRIs
            <span>&nbsp;&nbsp;&nbsp;&nbsp; Total :</span> <span class="count-style"> {{$all_grand_Total_GP}} </span> |
            <span class="olophoru">AGP :</span> <span class="count-style"> {{$total_AGP_PRI_GP}}</span>
            <span class="olophoru">| BJP :</span> <span class="count-style"> {{$total_BJP_PRI_GP}}</span>
            <span class="olophoru">| Congress :</span> <span class="count-style"> {{$total_CON_PRI_GP}}</span>
            <span class="olophoru">| AUIDF :</span> <span class="count-style"> {{$total_AUIDF_PRI_GP}}</span>
            <span class="olophoru">| Ind :</span> <span class="count-style"> {{$total_IND_PRI_GP}}</span>
            <span class="olophoru">| Others :</span> <span class="count-style"> {{$total_OTH_PRI_GP}}</span></button>
        <div class="content" style="margin-bottom: 15px;">
        <div class="row m-b-50">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable3">
                        <thead>
                        <tr class="bg-primary">

                            <td rowspan="2">SL</td>
                            <td rowspan="2">Zila</td>
                            <th colspan="6">President</th>
                            <th colspan="6">VicePresident</th>
                            <th colspan="6">Member</th>
                            <td rowspan="2">Total</td>
                        <tr class="bg-primary">
                            <td class="partytd">AGP</td>
                            <td class="partytd">BJP</td>
                            <td class="partytd">Congress</td>
                            <td class="partytd">AUIDF</td>
                            <td class="partytd">Ind</td>
                            <td class="partytd">Others</td>
                            <td class="partytd">AGP</td>
                            <td class="partytd">BJP</td>
                            <td class="partytd">Congress</td>
                            <td class="partytd">AUIDF</td>
                            <td class="partytd">Ind</td>
                            <td class="partytd">Others</td>
                            <td class="partytd">AGP</td>
                            <td class="partytd">BJP</td>
                            <td class="partytd">Congress</td>
                            <td class="partytd">AUIDF</td>
                            <td class="partytd">Ind</td>
                            <td class="partytd">Others</td>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($zpsParty AS $zp)
                            <tr>
                                <td class="partytd">{{$i}}</td>
                                <td class="partytd">{{$zp->zila_parishad_name}}</td>
                                {{--PRESIDENT--}}
                                <td>{{$finalPartyArr["GP__AGP_id_P" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["GP__BJP_id_P" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["GP__CON_id_P" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["GP__AUIDF_id_P" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["GP__IND_id_P" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["GP__OTH_id_P" . $zp->id]}}</td>
                                {{--VICE PRESIDENT--}}
                                <td>{{$finalPartyArr["GP__AGP_id_V" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["GP__BJP_id_V" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["GP__CON_id_V" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["GP__AUIDF_id_V" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["GP__IND_id_V" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["GP__OTH_id_V" . $zp->id]}}</td>
                                {{--MEMBER--}}
                                <td>{{$finalPartyArr["GP__AGP_id_M" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["GP__BJP_id_M" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["GP__CON_id_M" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["GP__AUIDF_id_M" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["GP__IND_id_M" . $zp->id]}}</td>
                                <td>{{$finalPartyArr["GP__OTH_id_M" . $zp->id]}}</td>

                                {{--TOTAL--}}
                                <td>
                                    {{$finalPartyArr["GP__AGP_id_P" . $zp->id]+
                                      $finalPartyArr["GP__AGP_id_V" . $zp->id]+
                                      $finalPartyArr["GP__AGP_id_M" . $zp->id]+

                                      $finalPartyArr["GP__BJP_id_P" . $zp->id]+
                                      $finalPartyArr["GP__BJP_id_V" . $zp->id]+
                                      $finalPartyArr["GP__BJP_id_M" . $zp->id]+

                                      $finalPartyArr["GP__CON_id_P" . $zp->id]+
                                      $finalPartyArr["GP__CON_id_V" . $zp->id]+
                                      $finalPartyArr["GP__CON_id_M" . $zp->id]+

                                      $finalPartyArr["GP__AUIDF_id_P" . $zp->id]+
                                      $finalPartyArr["GP__AUIDF_id_V" . $zp->id]+
                                      $finalPartyArr["GP__AUIDF_id_M" . $zp->id]+

                                      $finalPartyArr["GP__IND_id_P" . $zp->id]+
                                      $finalPartyArr["GP__IND_id_V" . $zp->id]+
                                      $finalPartyArr["GP__IND_id_M" . $zp->id]+

                                      $finalPartyArr["GP__OTH_id_P" . $zp->id]+
                                      $finalPartyArr["GP__OTH_id_V" . $zp->id]+
                                      $finalPartyArr["GP__OTH_id_M" . $zp->id]
                                    }}
                                </td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                        </tbody>
                        <tfoot class="bg-danger">
                        <tr>
                            <td colspan="2">Total</td>
                            {{------------------------------------PRESIDENT-----------------------------------------------------------------------}}
                            <td> {{$grand_GP__AGP__P}} </td>
                            <td> {{$grand_GP__BJP__P}} </td>
                            <td> {{$grand_GP__CON__P}} </td>
                            <td> {{$grand_GP__AUIDF__P}} </td>
                            <td> {{$grand_GP__IND__P}} </td>
                            <td> {{$grand_GP__OTH__P}} </td>
                            {{------------------------------------VICE PRESIDENT------------------------------------------------------------------}}
                            <td> {{$grand_GP__AGP__V}} </td>
                            <td> {{$grand_GP__BJP__V}} </td>
                            <td> {{$grand_GP__CON__V}} </td>
                            <td> {{$grand_GP__AUIDF__V}} </td>
                            <td> {{$grand_GP__IND__V}} </td>
                            <td> {{$grand_GP__OTH__V}} </td>
                            {{------------------------------------MEMBER------------------------------------------------------------------}}
                            <td> {{$grand_GP__AGP__M}} </td>
                            <td> {{$grand_GP__BJP__M}} </td>
                            <td> {{$grand_GP__CON__M}} </td>
                            <td> {{$grand_GP__AUIDF__M}} </td>
                            <td> {{$grand_GP__IND__M}} </td>
                            <td> {{$grand_GP__OTH__M}} </td>
                            <td> {{$all_grand_Total_GP}} </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('mdas_assets/js/multiple-select.min.js')}}"></script>
    <script type="application/javascript">
        $(document).ready(function () {
            $('#dataTable1').DataTable();

            $('#dataTable2').DataTable();

            $('#dataTable3').DataTable();

            $('#dataTable4').DataTable();
        });

    //    collapsable
        var coll = document.getElementsByClassName("collapsible1");
        var i;

        for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var content = this.nextElementSibling;
                if (content.style.maxHeight){
                    content.style.maxHeight = null;
                } else {
                    content.style.maxHeight = content.scrollHeight +500+ "px";
                }
            });
        }
    </script>
@endsection
