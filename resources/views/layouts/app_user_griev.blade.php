<!DOCTYPE html>
<html>
<head>
	<link rel="icon" href="{{asset('mdas_assets/images/favicon.png')}}" type="image/gif" sizes="16x16">
    <title>SIPRD-MDAS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('mdas_assets/bootstrap/css/bootstrap.min.css')}}">

    <link rel="stylesheet" href="{{asset('mdas_assets/css/zebra_datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('mdas_assets/css/animate.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/fontawesome.min.css" integrity="sha512-giQeaPns4lQTBMRpOOHsYnGw1tGVzbAIHUyHRgn7+6FmiEgGGjaG0T2LZJmAPMzRCl+Cug0ItQ2xDZpTmEc+CQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{--  --}}
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"> --}}
    {{--  --}}
    <link href="https://fonts.googleapis.com/css?family=Livvic:300i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Old+Standard+TT&display=swap" rel="stylesheet">
    <script src="{{asset('mdas_assets/Chart.js-2.8.0/dist/Chart.min.js')}}"></script>
    <script src="{{asset('mdas_assets/Chart.js-2.8.0/samples/utils.js')}}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.css" rel="stylesheet" type="text/css"/>

	<link rel="stylesheet" href="{{asset('mdas_assets/css/style.css')}}">
    <!-- Ace Responsive -->
    <link rel="stylesheet" href="{{asset('mdas_assets/css/ace-responsive-menu.css')}}">
	@yield('custom_css')
    <style>
        .btn {
            background-color: #6b133d;
            color: #fff;
        }
        .dropdown-toggle {
            background-color: #eeeeee;
            color: #333;
        }
        .btn-primary:hover {
            color: #fff;
            background-color: #f13333;
            border-color: #f13333;
        }
        .panel-body {
            padding: 13px;
        }
        button.dt-button, div.dt-button, a.dt-button {
            background-image: linear-gradient(to bottom, #4fe14f 0%, green 100%);
            color: #fff;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: linear-gradient(to bottom, #4fe14f 0%, green 100%);
            color: #fff !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: linear-gradient(to bottom, #4fe14f 0%, green 100%);
            color: #fff;
        }
        .bg-pprimary {
            color: #fff;
            background-color: #337ab7 !important;
        }
        body {
            margin: 0px;
            padding: 0px;
            background-color: #fff;
        }
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding: 7px;
            line-height: 1.42857143;
            vertical-align: top;
            border-top: 1px solid #ddd;
        }
        .mt40{
            margin-top: 40px;
        }
        .mb40{
            margin-bottom: 40px;
        }
        .card-header{
            background-color: #6b133d;
            color: #fff;
            font-size: 20px;
            text-align: center;
            font-weight: 700;
            padding:2px 0px;
        }
        .card-body{
            padding:10px;
        }
        .card .number{
            font-weight: 500;
            font-size: 40px;
        }
        .card p{
            font-weight: 900;
            font-size: 20px;
        }
        .tr-row {
            background-color: #ebe7e7;
            color: rgb(255, 118, 15);
            font-weight: 600;
        }
        .panel-primary>.panel-heading {
            background-color: rgb(255, 118, 15);
            background-image: linear-gradient(to right, #FF5722 , #FF5722);
        }
        .bold-color {
            color: darkviolet;
            font-weight: 600;
        }
        .panel-primary {
            border-color: #F44336;
        }
        .panel-primary>.panel-heading {
            border-color: transparent;
        }
        .gray-back {
            background-color: #f3f2f2;
            border-bottom: solid 1px orangered;
        }
        .green-back {
            background-color: #a4114c;
            color: #fff
        }
        .bold-text {
            font-weight: 600;
        }

        body{
            background-color: #eee;
        }
        header{
            background: url({{asset('mdas_assets/images/header.jpg')}}) top repeat;
            color:#eee;
            padding-top: 10px;
            border-bottom: 1px solid red;
        }

        .li_logo{
            vertical-align: bottom;
        }

        .ul-logo{
            display: flex;
        }
        .navbar-brand {
                float: left;
                height: 50px;
                padding: 10px 15px;
                font-size: 18px;
                line-height: 20px;
        }
        .ace-responsive-menu > li > .active > a, .ace-responsive-menu > li > .active > a:focus, .ace-responsive-menu > li > .active > a:hover{
            color: #fff;
            background-color: #f13333;
        }

        /*---------------clock-------------*/
        #clock,.date,.day {
             font-family: 'Old Standard TT', serif;
             color: #fff;
             font-size: 15px;
        }
        .f-strip {
            background-color: #6e133c;
        }
        
        @media only screen and (max-width: 768px) {
            .login-md{
                display:none;
            }
            .navbar{
                margin-bottom:-35px;
            }
        }
        @media only screen and (min-width: 769px){
            .ace-responsive-menu .logout{
                display:none;
            }
            .navbar{
                margin-bottom:0px;
            }
			#main-logo {
				margin-top:-5.5px;
			}
        }
    </style>
</head>    
    
   {{--------------Loader----------------}}
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
    {{-----------Loader Ended------------}}
    
    

<body style="background: url({{asset('mdas_assets/images/dash_back.jpg')}}); no-repeat; background-attachment: fixed; height: 100%; background-size: cover">

<header>
    <div class="container-fluid">
        
        <ul class="list-inline pull-left ul-logo">
            <li class="li_logo">
                <img src="{{asset('mdas_assets/images/SPIRD_logo.png')}}" alt="logo" style="width:70px"/>
            </li>
            <li class="text-left">
                <ul class="list-unstyled">
                    <li class="em-title"><h4 class="text-uppercase">Panchayat & Rural Development</h4></li>
                    <li class="em-title">STATE INSTITUTE OF PANCHAYAT AND RURAL DEVELOPMENT (SIPRD)</li>
                    <li class="em-title"><h5 class="text-uppercase">Monitoring &amp; Data Analytics System</h5></li>
                </ul>
            </li>
        </ul>
        <ul class="pull-right list-unstyled">
            <li class="" id="clock"></li>
            <li class="date">{{\Carbon\Carbon::now()->format('d M Y')}}</li>
            <li class="day">
                @php 
                    $day=[1=>"Monday", 2=>"Tuesday",3=>"Wednesday",4=>"Thursday",5=>"Friday",6=>"Saturday",0=>"Sunday"]; 
                @endphp
                {{$day[\Carbon\Carbon::now()->dayOfWeek]}}
            </li>
        </ul>
    </div>
    <div class="container-fluid" style="border-top: 1px solid rgba(143, 58, 58, 0.26);background: #11123482;">
        <div class="navbar">
            <!-- Ace Responsive Menu -->
            <nav>
                <a class="navbar-brand" href="#" style="">
                    <img id="main-logo" src="{{asset('mdas_assets/index/assets/img/logos/mdas.png')}}" alt="logo" style="width:135.55px; margin-left: -21px;">
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
                    <li class="{{ (request()->is('dashboard')) ? 'active' : '' }}">
                        <a href="{{route('dashboard')}}">
                            <i class="fa fa-home" aria-hidden="true"></i>
                            <span class="title">Home</span>
                        </a>
                    </li>
                    <style>
                        #respMenu > .active {
                           background:#d70032; 
                        }
                        .sub-menu > .active {
                           background:#d70032;
                        }
                        .ace-responsive-menu li ul a {
                            color: #ffffff;
                        }
                        .navbar {
                            border: none;
                        }
                        body {
                            line-height:1.35em;
                        }
                        .ace-responsive-menu li.menu-active > a {
                            margin-top:-1px;
                        }
                        .ace-responsive-menu > li > a i {
                            color: #c6d9d0;
                        }
                        .ace-responsive-menu li a i {
                            color: #c6d9d0;
                        }
                        .ace-responsive-menu li ul.sub-menu li.menu-active > a {
                            margin-top:-1px;
                        }
                    </style>
				<li class="{{ (request()->is('grievance/dashboard')) ? 'active' : '' }} {{ (request()->is('grievance/Media/details')) ? 'active' : '' }}">
                        <a href="{{route('grievance.dashboard')}}">
                            <i class="fa fa-tachometer" aria-hidden="true"></i>
                            <span class="title">Dashboard</span>
                        </a>
                    </li>
                   <li class="{{ (request()->is('grievance/Individual/griev_entry')) ? 'active' : '' }} {{ (request()->is('grievance/Individual/individual_griev_list')) ? 'active' : '' }}">
                        <a class="" href="javascript:;">
                           <i class="fa fa-tasks" aria-hidden="true"></i>
                            <span class="title">Individual Grievance</span>
                        </a>
                        <ul>
                           <li class="{{ (request()->is('grievance/Individual/griev_entry')) ? 'active' : '' }}">
                                <a href="{{route('grievance.individual_griev_entry')}}">Lodge Grievance</a>
                           </li>
					   <li class="{{ (request()->is('grievance/Individual/individual_griev_list')) ? 'active' : '' }}">
                                <a href="{{route('grievance.Individual.individual_griev_list')}}">Action</a>
                           </li>
                        </ul>
                    </li>
                    {{--<li class="{{ (request()->is('Uc/componentWiseReports')) ? 'active' : '' }}">
                        <a href="{{route('Uc.componentWiseReports')}}">
                            <i class="fa fa-charts" aria-hidden="true"></i>
                            <span class="title">Component-Wise Reports</span>
                        </a>
                    </li>--}}
                    <!-- For mobile screen -->

                    <li class="logout">
                        <a class="" href="javascript:;">
                            <span class="title">Hi, {{ Auth::user()->username }}</span>
                        </a>
                        <ul>
                            <li>
                                <a href="{{route('UsersManagement.profile')}}"><i class="fa fa-user"></i> My Profile</a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form1').submit();">
                                        <i class="fa fa-sign-out"></i>
                                        Logout
                                </a>
                                <form id="logout-form1" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <!-- End of Responsive Menu -->

            <!--  For desktop and higher quality screens  -->
             <span class="pull-right login-md">
                 <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                      <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #fff; padding: 17px 35px 0px 0px; background: none;">
                          Hi, {{ Auth::user()->username }}
                          <span class="caret user"></span>
                      </a>
                      <ul class="dropdown-menu" style="padding: 6px 12px; margin-right: 19px; margin-top: 22px;">
                            <li><a href="{{route('UsersManagement.profile')}}"><i class="fa fa-user"></i> My Profile</a></li>
                            <li class="divider"></li>
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form1').submit();">
                                    <i class="fa fa-sign-out"></i>
                                    Logout
                                </a>
                                <form id="logout-form1" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                      </ul>
                    </li>
                  </ul>
            </span>
        </div>
    </div>
</header>

@yield('content')

<div class="f-strip">
    <div class="row">
        <div class="col-md-12">Copyright © 2019, SIPRD.All rights reserved. Designed, Developed &amp; Maintained by <a href="https://gratiatechnology.com/" target="_blank">Gratia Technology</a></div>
    </div>
</div>

<script src="{{asset('mdas_assets/js/jquery.min.js')}}"></script>
<script src="{{asset('mdas_assets/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('mdas_assets/js/zebra_datepicker.min.js')}}"></script>
<script src="{{asset('mdas_assets/js/jqueryValidate.js')}}"></script>
<script src="{{asset('mdas_assets/js/jqueryAddValidate.js')}}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{asset('mdas_assets/js/ace-responsive-menu-min.js')}}"></script>
<script src="{{asset('mdas_assets/js/currencyFormatter.min.js')}}"></script>

@yield('custom_js')

<script type="application/javascript">
    $('.page-loader-wrapper').fadeIn();
    setTimeout(function(){ $('.page-loader-wrapper').fadeOut(); }, 1000);
    openActiveList();
    function openNav() {
        document.getElementById("mySidenav").style.display = "block";
        document.getElementById("mySidenav").classList.remove('animated', 'fadeInLeft');
        document.getElementById("mySidenav").classList.remove('animated', 'fadeOutLeft');
        document.getElementById("mySidenav").classList.add('animated', 'fadeInLeft');
    }
    function closeNav() {
        /*document.getElementById("mySidenav").style.display = "none";*/
        document.getElementById("mySidenav").classList.remove('animated', 'fadeInLeft');
        document.getElementById("mySidenav").classList.remove('animated', 'fadeOutLeft');
        document.getElementById("mySidenav").classList.add('animated', 'fadeOutLeft');
    }
    function openActiveList(){
        $('.sub-menu li.active').parent('ul').removeClass('collapse');
        $('.sub-menu li.active').parent('ul').addClass('collapse in');
    }
    function myFunction() {
      var x = document.getElementById("myTopnav");
      if (x.className === "topnav") {
        x.className += " responsive";
      } else {
        x.className = "topnav";
      }
    }
    //---------------clock----------------------//
    function currentTime() {
      hour = updateTime(hour);
      min = updateTime(min);
      sec = updateTime(sec);
    }

    function currentTime() {
      var date = new Date(); /* creating object of Date class */
      var hour = date.getHours();
      var min = date.getMinutes();
      var sec = date.getSeconds();
      var midday = "AM";
      midday = (hour >= 12) ? "PM" : "AM"; /* assigning AM/PM */
      hour = (hour == 0) ? 12 : ((hour > 12) ? (hour - 12): hour); /* assigning hour in 12-hour format */
      hour = updateTime(hour);
      min = updateTime(min);
      sec = updateTime(sec);
      document.getElementById("clock").innerText = hour + " : " + min + " : " + sec + " " + midday; /* adding time to the div */
        var t = setTimeout(currentTime, 1000); /* setting timer */
    }

    function updateTime(k) { /* appending 0 before time elements if less than 10 */
      if (k < 10) {
        return "0" + k;
      }
      else {
        return k;
      }
    }currentTime();
    //---------clock ends------------------//

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