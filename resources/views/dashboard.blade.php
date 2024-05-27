@php
    $page_title = 'dashboard';
@endphp

@extends('layouts.app_user_osr')

@section('custom_css')
    <style>
        .cardd {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
        }

        .cardd a {
            color: #6e133c;
        }

        .cardd:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
            transform: scale(1.1);
        }

        a.thumbnail.active,
        a.thumbnail:focus,
        a.thumbnail:hover {
            border-color: #6e133c;
            color: #6e133c;
        }

        a:focus,
        a:hover {
            color: #6e133c;
            text-decoration: underline;
        }

        .thumbnail a>img,
        .thumbnail>img {
            margin-right: auto;
            margin-left: auto;
            width: 20%;
        }
    </style>
@endsection

@section('content')

    <div class="container" id="team">
        <div class="row">
            {{-- EX=Extension Centre Admin, DCA=District Council Admin, BCA=Block Council Admin, GCA=GP Council Admin --}}
            @if ($data['level'] != 'EX')
                @if ($data['level'] != 'DCA' && $data['level'] != 'BCA' && $data['level'] != 'GCA')
                    <div class="col-md-3 col-sm-4 col-xs-6 mt40">
                        <div class="cardd animated zoomIn">
                            <a href="{{ url('osr/osr_panel') }}/{{ encrypt($data['fy_id']) }}" class="thumbnail text-center">
                                <img src="{{ asset('mdas_assets/images/OSR.png') }}" />
                                <p class="mt10">Own Source of Revenue <br /> (OSR)</p>
                            </a>
                        </div>
                    </div>
                    @if ($data['level'] == 'GP')
                        <div class="col-md-3 col-sm-4 col-xs-6 mt40">
                            <div class="cardd animated zoomIn">
                                <a href="{{ route('panchayat_profile') }}" class="thumbnail text-center">
                                    <img src="{{ asset('mdas_assets/images/Gram Panchayat.png') }}" />
                                    <p class="mt10">Panchayat Profile <br />&nbsp;</p>
                                </a>
                            </div>
                        </div>
                    @endif
                @endif
                <div class="col-xs-6 col-md-3 col-sm-4 mt40 ">
                    <div class="cardd animated zoomIn">
                        <a href="{{ route('grievance.dashboard') }}" class="thumbnail text-center">
                            <img src="{{ asset('mdas_assets/images/grievance.png') }}" />
                            <p class="mt10">Grievance <br />System</p>
                        </a>
                    </div>
                </div>
                {{-- --}}
                <div class="col-md-3 col-sm-4 col-xs-6 mt40">
                    <div class="cardd animated zoomIn">
                        <a href="{{ route('pris.members') }}" class="thumbnail text-center">
                            <img src="{{ asset('mdas_assets/images/PRI.png') }}" />
                            <p class="mt10">Panchayati Raj Institution <br /> (PRI)</p>
                        </a>
                    </div>
                </div>
                {{-- --}}
                @if ($data['level'] == 'ZP')
                    <div class="col-xs-6 col-md-3 col-sm-4 mt40 ">
                        <div class="cardd animated zoomIn">
                            <a href="{{ route('courtCases.dashboard') }}" class="thumbnail text-center">
                                <img src="{{ asset('mdas_assets/images/courtcase.png') }}" />
                                <p class="mt10">Court <br />Cases</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-xs-6 col-md-3 col-sm-4 mt40 ">
                        <div class="cardd animated zoomIn">
                            <a href="{{ route('Uc.dashboard') }}" class="thumbnail text-center">
                                <img src="{{ asset('mdas_assets/images/uc.png') }}" />
                                <p class="mt10">Utilization Certificate <br />(UC)</p>
                            </a>
                        </div>
                    </div>
                @endif

                
                {{-- ning start --}}
                <div class="col-md-3 col-sm-4 col-xs-6 mt40">
                    <div class="cardd animated zoomIn">
                        <a href="{{ route('pris.members.bankReport') }}" class="thumbnail text-center">
                            <i class="fa-solid fa-piggy-bank fa-3x" aria-hidden="true"></i>
                            <p class="mt10">Bank Progress Report</p>
                            <br>
                        </a>
                    </div>
                </div>
                {{--  ning end --}}

                <!-- {{-- Mishra wants bank Detail --}}
                <div class="col-md-3 col-sm-4 col-xs-6 mt40">
                    <div class="cardd animated zoomIn">
                        <a href="{{ route('pris.members.filledBankDetail') }}" class="thumbnail text-center">
                            <img src="{{ asset('mdas_assets/images/bankdetails.png') }}" />
                            <p class="mt10">Filled Bank Account Details</p>
                            <br>
                        </a>
                    </div>
                </div>
                {{-- Mishra wants bank Detail --}} -->
                
            @elseif($data['level'] == 'AP')
            @else
                <div class="col-xs-6 col-md-3 col-sm-4 mt40 ">
                    <div class="cardd animated zoomIn">
                        <a href="{{ route('Uc.dashboard') }}" class="thumbnail text-center">
                            <img src="{{ asset('mdas_assets/images/uc.png') }}" />
                            <p class="mt10">Utilization Certificate <br />(UC)</p>
                        </a>
                    </div>
                </div>
                @if ($data['level'] == 'EX')
                    <div class="col-md-3 col-sm-4 col-xs-6 mt40">
                        <div class="cardd animated zoomIn">
                            <a href="{{ route('training.dashboard') }}" class="thumbnail text-center">
                                <img src="{{ asset('mdas_assets/images/training.png') }}" />
                                <p class="mt10">Need Based Training <br /> (NBT)</p>
                            </a>
                        </div>
                    </div>
                @endif
            @endif


        </div>

    </div>
@endsection

@section('custom_js')
@endsection
