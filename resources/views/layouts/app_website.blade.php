<!DOCTYPE html>
<html lang="en">
<head>
    <title> SIPRD-MDAS | @yield('custom_title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="{{asset('mdas_assets/images/favicon.png')}}" type="image/gif" sizes="16x16">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="stylesheet" href="{{asset('mdas_assets/css/zebra_datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('mdas_assets/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    
    <!-- IcoFont CSS -->
    <link rel="stylesheet" href="{{asset('mdas_assets/index/assets/css/icofont.min.css')}}">
    
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{asset('mdas_assets/index/assets/css/style.css')}}">
	
	<!-- Ace Responsive -->
    <link rel="stylesheet" href="{{asset('mdas_assets/css/ace-responsive-menu.css')}}">
	
	<!--Gallery Viewer-->
    <link rel="stylesheet" href="{{asset('mdas_assets/index/assets/fancybox-master/dist/jquery.fancybox.min.css')}}" type="text/css" media="screen" />
    
    <link rel="stylesheet" href="{{asset('mdas_assets/index/assets/slider/jquery.flipster.min.css')}}">
    
    <style>
        header {
                background: url('{{asset('mdas_assets/images/header.jpg')}}') top repeat;
                color: #eee;
                padding-top: 10px;
                /*border-bottom: 1px solid red;*/

                z-index: 99;
                width: 100%;
            }

            header .em-title h4 {
                margin: 0px;
                padding: 0px
            }

            .li_logo {
                vertical-align: bottom;
            }

            .ul-logo {
                display: flex;
            }
        .flag-header{
            background-color: #120320;
            
        }
            .flag-header ul, header ul {
                margin-bottom: 0;
                
            }

            .flag-header a {
                padding: 0px 2px;
                color:#fff;
            }
            
            .navbar-brand {
                float: left;
                height: 50px;
                padding: 10px 15px;
                font-size: 18px;
                line-height: 20px;
            }
            .navbar-inverse .navbar-nav>.active>a, .navbar-inverse .navbar-nav>.active>a:focus, .navbar-inverse .navbar-nav>.active>a:hover {
                color: #fff;
                background-color: #f50909;
            }
            .dropdown-menu>li>a {
                color: #fff;
            }
        
        .mtb-40{
            margin-top: 40px;
            margin-bottom: 40px;
        }
		.mb-40{
            margin-bottom: 40px;
        }
        
        
        @media only screen and (max-width: 768px) {
            .login-md{
                display:none;
            }
        }
        @media only screen and (min-width: 769px){
            .ace-responsive-menu .login-xs{
                display:none;
            }
        }
    </style>

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
<!--LOADER-->
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
<!--LOADER ENDED-->  
    
<div class="container-fluid">
    <div class="row flag-header">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <ul class="list-inline pull-left">
                    <li class="indianflag" style="padding: 4px;border-right: 1px solid #8080808f;
                font-size: 0.929em;color:#fff">
                        <img src="{{asset('mdas_assets/images/indianflag.jpg')}}" alt="India Flag" height="15" width="22"/>
                        Government of India
                    </li>
            </ul>

            <ul class="list-inline pull-right">
                    <li class="" style="padding: 4px;font-size: 1em;">
                        <select>
                            <option value="en">English</option>
                        </select>
                    </li>
                    <li class="font-limit">
                        <a id="a0" style="text-decoration: none" href="#" data-toggle="tooltip" data-placement="bottom"
                           title="Decrease font size">A-</a>
                        <a id="a1" style="text-decoration: none" href="#" data-toggle="tooltip" data-placement="bottom"
                           title="Original font size">A</a>
                        <a id="a2" style="text-decoration: none" href="#" data-toggle="tooltip" data-placement="bottom"
                           title="Increase font size">A+</a>
                    </li>
            </ul>
        </div>
    </div>
</div>
<header>
    <div class="container-fluid">
            <ul class="list-inline pull-left ul-logo">
                <li class="li_logo">
                    <img src="{{asset('mdas_assets/images/SPIRD_logo.png')}}" alt="logo" style="width:50px"/>
                </li>
                <li class="text-left">
                    <ul class="list-unstyled">
                        <li class="em-title"><h4 class="text-uppercase">Panchayat &amp; Rural Development</h4></li>
                        <li class="em-title">STATE INSTITUTE OF PANCHAYAT AND RURAL DEVELOPMENT (SIPRD)</li>
                        <li class="em-title"><h5 class="text-uppercase">Monitoring Data Analytics System</h5></li>
                    </ul>
                </li>
            </ul>
    </div>
    <div class="container-fluid">
        <div class="row" style="border: 1px solid rgba(143, 58, 58, 0.26); background: #11123482;">
            
            <!-- Ace Responsive Menu -->
            <nav>
                <a class="navbar-brand" href="#" style="">
                    <img id="main-logo" src="{{asset('mdas_assets/index/assets/img/logos/mdas.png')}}" alt="logo" style="width:135.55px">
                </a>
                <!-- Menu Toggle btn-->
                <div class="menu-toggle">
                
                    <button type="button" id="menu-btn">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <!-- Responsive Menu Structure-->
                <!--Note: declare the Menu style in the data-menu-style="horizontal" (options: horizontal, vertical, accordion) -->
                <ul id="respMenu" class="ace-responsive-menu" data-menu-style="horizontal">
                    <li>
                        <a href="{{url('/')}}">
                            <i class="fa fa-home" aria-hidden="true"></i>
                            <span class="title">Home</span>
                        </a>
                    </li>
                    <li>
                        <a class="" href="javascript:;">
                           <i class="fa fa-money" aria-hidden="true"></i>
                            <span class="title">OSR</span>

                        </a>
                        <ul>
                            <li>
                                <a href="javascript:;">Non-Tax Asset Report							
                                </a>
                                <ul>
                                    <li><a href="{{route('osr_asset_settlement')}}">Category Wise Assets</a></li>
                                    <li><a href="{{route('osr_defaulter')}}">Defulater List</a></li>
                                    <li><a href="{{route('osr_year_wise')}}">Year Wise Assets</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <span class="title">PRI</span>
                        </a>
                    </li>
                    <li class="last ">
                        <a href="javascript:;">
                            <i class="fa fa-gavel" aria-hidden="true"></i>
                            <span class="title">Court Case</span>
                        </a>
                    </li>
					<li class="last ">
                        <a href="{{route('training.index')}}">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span class="title">Need Based Training</span>
                        </a>
                    </li>
					<!--<li class="last ">
                        <a href="{{route('recruitment')}}">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span class="title">Career &amp; Recruitmant</span>
                        </a>
                    </li>-->
                    <!-- For mobile screen -->
                    <li class="login-xs"><a href="{{route('login')}}" style="background: #ed0303; "><span class="glyphicon glyphicon-log-in"></span> Login</a></li>


                </ul>
            </nav>
            <!-- End of Responsive Menu -->
            
            <!--  For desktop and higher quality screens  -->
             <span class="pull-right login-md" style="background: #ed0303; padding: 19px; margin-right: 9px;"><a href="{{route('login')}}" style="color:#fff"><span class="glyphicon glyphicon-log-in"></span> Login</a></span>

        </div>
    </div>
</header>
    

@yield('content')

<script src="{{asset('mdas_assets/js/jquery.min.js')}}"></script>
<script src="{{asset('mdas_assets/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('mdas_assets/js/ace-responsive-menu-min.js')}}"></script>
<script src="{{asset('mdas_assets/js/zebra_datepicker.min.js')}}"></script>
<script src="{{asset('mdas_assets/js/jqueryValidate.js')}}"></script>
<script src="{{asset('mdas_assets/js/jqueryAddValidate.js')}}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{asset('mdas_assets/js/currencyFormatter.min.js')}}"></script>
<!--Gallery Viewer-->
<script src="{{asset('mdas_assets/index/assets/fancybox-master/dist/jquery.fancybox.min.js')}}"></script>

@yield('custom_js')

 <script type="text/javascript">
    $(document).ready(function () {
             /*$("#respMenu").aceResponsiveMenu({
                 resizeWidth: '768', // Set the same in Media query       
                 animationSpeed: 'fast', //slow, medium, fast
                 accoridonExpAll: false //Expands all the accordion menu on click
             });*/
        
        $("#respMenu").aceResponsiveMenu();

         });
    </script>
</body>
</html>
