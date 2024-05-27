<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="icon" href="{{asset('mdas_assets/images/favicon.png')}}" type="image/gif" sizes="16x16">
    <title>SIPRD-MDAS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="{{asset('mdas_assets/images/favicon.png')}}" type="image/gif" sizes="16x16">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('mdas_assets/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('mdas_assets/css/animate.css')}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('mdas_assets/css/style.css')}}">

    @yield('custom_css')
	
	<noscript>
        <style>

            #u1
            {
                visibility:visible;
                margin-top: 40px;
            }
            body
            {
                visibility: hidden;
				background:none;
            }
            #r1
            {
                margin-top: 60px;
            }
        </style>

        <div class="container text-center" id="u1">
            <div class="row">
                <div class="col col-md-12 col-xs-12 col-sm-12 col-lg-12">
                    <p class="error"><i>Javascript is not enabled in your browser</i></p>
                    <p>Please use the latest version of any one of the browsers for best response.</p>
                </div>
            </div>
            <div class="row" id="r1" align="middle">
                <div class="col-md-4 col-xs-4 col-sm-2">
                    <img src="{{asset('mdas_assets/images/mozilla_icon.png')}}" style="width:80px;height:60px;" class="img-responsive">
                    <p><label>Mozilla Firefox</label></p>
                </div>
                <div class="col-md-4 col-xs-4 col-sm-2">
                    <img src="{{asset('mdas_assets/images/google_chrome.jpg')}}" style="width:80px;height:60px;" class="img-responsive">
                    <p><label>Google Chrome</label></p>
                </div>
                <div class="col-md-4 col-xs-4 col-sm-2">
                    <img src="{{asset('mdas_assets/images/explorer_icon.png')}}" style="width:80px;height:60px;" class="img-responsive">
                    <p><label>Internet Explorer</label></p>
                </div>
                <br><br>
            </div>
        </div>
    </noscript>
</head>
<body>
<div class="page-loader-wrapper" style="display: none;">
    <div class="loader">
        <div class="preloader">
            <div class="spinner-layer pl-red">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
        <p>Please wait...</p>
    </div>
</div>
@yield('content')

<!--<div class="f-strip">
    <div class="row">
        <div class="col-md-12" style="text-align: center">
            Copyright © 2019, SIPRD.All rights reserved. Designed, Developed &amp; Maintained by <a href="https://gratiatechnology.com/" target="_blank">Gratia Technology</a><br>
            <a href="https://gratiatechnology.com/"><img src="{{asset('mdas_assets/images/gratia.png')}}" alt="logo" style="width: 30px"/></a>
        </div>
    </div>
</div>-->

<script src="{{asset('mdas_assets/js/jquery.min.js')}}"></script>
<script src="{{asset('mdas_assets/bootstrap/js/bootstrap.min.js')}}"></script>
@yield('custom_js')
</body>
</html>
