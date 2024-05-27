@extends('layouts.app_public')

@section('custom_css')
    <style type="text/css">
        body{
            margin: 0px;
            padding: 0px;
            background-color: #ddd;
        }

        header{
            background-image: linear-gradient(to right, #6913cc 0%, #2575fc 100%);
            color:#eee;
            padding-top: 10px;
            margin-bottom: 40px;
            border-bottom: 1px solid red;
        }

        .li_logo{
            vertical-align: bottom;
        }

        .ul-logo{
            display: flex;
        }
        
       

    </style>
@endsection

@section('content')
    <header>
        <div class="container-fluid">
            <div class="container">
                <ul class="list-inline pull-left ul-logo">
                    <li class="li_logo">
                        <img src="{{asset('mdas_assets/images/SPIRD_logo.png')}}" alt="logo" style="width:70px"/>
                    </li>
                    <li class="text-left">
                        <ul class="list-unstyled">
                            <li class="em-title"><h4 class="text-uppercase">Panchayat & Rural Development</h4></li>
                            <li class="em-title">STATE INSTITUTE OF PANCHAYAT AND RURAL DEVELOPMENT (SIPRD)</li>
                            <li class="em-title"><h5 class="text-uppercase">Monitoring Data Analytics System</h5></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <div class="container-fuild mt20">
        <div class='container'>

            <div class="col-md-3 col-sm-4 col-xs-6">
                <div class="col-md-12 col-sm-12 col-xs-12 front">
                    <h4>Employee <br>Login</h4>

                    <div class='r_wrap'>
                        <a href="{{route('login')}}">
                            <div class='s_round'>
                                <div class='s_arrow'></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-4 col-xs-6">
                <div class="col-md-12 col-sm-12 col-xs-12 front-map">
                    <h4>Assam <br>Map</h4>
                    <div class='r_wrap'>
                        <a href="{{route('assam-map')}}">
                            <div class='s_round'>
                                <div class='s_arrow'></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('custom_js')
    <script type="application/javascript">

    </script>
@endsection