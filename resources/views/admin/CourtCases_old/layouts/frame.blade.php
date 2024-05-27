<!DOCTYPE html>
<html>
<head>
    <title>SIPRD-MDAS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('mdas_assets/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('mdas_assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('mdas_assets/css/zebra_datepicker.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Livvic:300i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Old+Standard+TT&display=swap" rel="stylesheet">

    <style>
        .btn {
            background-color: #6b133d;
            color: #fff;
            border-radius: 17px;
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

        .card{
            border:1px solid #ff770f;
            background-color: #f3f2f2;
            box-shadow:0 1px 5px rgba(0, 0, 0, 0.1);
        }
        .card-header{
            background-color: #6b133d;
            color: #fff;
            font-size: 20px;
            text-align: center;
            font-weight: 700;
            padding:5px;
        }
        .card-body{
            padding:10px;
            text-align: center;
        }

        .card .number{
            font-weight: 900;
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
        
        .navbar-inverse {
           background-color: #6e133c8a;
           margin-bottom: auto;
        }
        .navbar-inverse .navbar-nav>li>a {
            color: #fbf9f9;
        }
        .navbar-inverse .navbar-nav>.active>a, .navbar-inverse .navbar-nav>.active>a:focus, .navbar-inverse .navbar-nav>.active>a:hover {
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
        .nav_s{
            float: none;
            clear: both;
            width: 30%;
            margin: 5% auto;
            background: #eee;
            font-family: 'Old Standard TT', serif;
        }

        .nav_s ul {
            list-style: none;
            margin: 0px;
            padding: 0px;
        }

        .nav_s li{
            float: none;
            width: 100%;
        }

        .nav_s li a{
            display: block;
            width: 100%;
            padding: 20px;
            border-left: 5px solid;
            position: relative;
            z-index: 2;
            text-decoration: none;
            color: #444;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            background-color: rgb(243, 235, 166);
            margin-bottom: 2px;
            font-size: 16px;
            font-weight: 500;
        }

        .nav_s li a:hover{ border-bottom: 0px; color: #fff;}
        .nav_s li:first-child a{ border-left: 10px solid #3498db;}
        .nav_s li:nth-child(2) a{ border-left: 10px solid #ffd071;}
        .nav_s li:nth-child(3) a{ border-left: 10px solid #f0776c;}
        .nav_s li:nth-child(4) a{ border-left: 10px solid orangered;}
        .nav_s li:nth-child(5) a{ border-left: 10px solid brown;}
        .nav_s li:nth-child(6) a{ border-left: 10px solid firebrick;}
        .nav_s li:nth-child(7) a{ border-left: 10px solid forestgreen;}
        .nav_s li:last-child a{ border-left: 10px solid #1abc9c;}
        .nav_s li a:after {
            content: "";
            height: 100%;
            left: 0;
            top: 0;
            width: 0px;
            position: absolute;
            transition: all 0.3s ease 0s;
            -webkit-transition: all 0.3s ease 0s;
            z-index: -1;
        }

        .nav_s li a:hover:after{ width: 100%;}
        .nav_s li:first-child a:after{ background: #3498db;}
        .nav_s li:nth-child(2) a:after{ background: #ffd071;}
        .nav_s li:nth-child(3) a:after{ background: #f0776c;}
        .nav_s li:nth-child(4) a:after{ background: orangered;}
        .nav_s li:nth-child(5) a:after{ background: brown;}
        .nav_s li:nth-child(6) a:after{ background: firebrick;}
        .nav_s li:nth-child(7) a:after{ background: forestgreen;}
        .nav_s li:last-child a:after{ background: #1abc9c;}

        .dangorbackgroundimage {
            background-image: url({{asset('mdas_assets/images/pri_village.jpg')}});
            padding: 4%;
            background-size: cover;
        }
    </style>
    @yield('custom_css')
</head>
<body style="background: url({{asset('mdas_assets/images/dash_back.jpg')}}); no-repeat; background-attachment: fixed; height: 100%; background-size: cover">

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
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

    <div class="nav-side-menu">
        <div class="brand">
            <img src="{{asset('mdas_assets/images/SPIRD_logo.png')}}" alt="logo" style="width:80px;margin:20px"/>
            <h6 class="text-uppercase">Monitoring Data Analytics System</h6>
        </div>
        <div class="menu-list">
            <ul id="menu-content" class="menu-content collapse out">
                <li class="@if($page_title=="dashboard") {{"active"}} @endif">
                    <a href="#"><i class="fa fa-dashboard fa-lg fa-fw sidebar-icon"></i> Dashboard</a>
                </li>

                <li data-toggle="collapse" data-target="#settings" class="collapsed">
                    <a href="#"><i class="fa fa-sliders fa-lg fa-fw sidebar-icon"></i> Six Finance <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="settings">
                    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-angle-double-right"></i> Form</a></li>
                </ul>

                {{--<li>
                    <a href="#"><i class="fa fa-calendar fa-lg fa-fw sidebar-icon"></i> Scheduler</a>
                </li>

                <li>
                    <a href="#"><i class="fa fa-bar-chart fa-lg fa-fw sidebar-icon"></i> Statistics</a>
                </li>

                <li  data-toggle="collapse" data-target="#manage" class="collapsed">
                    <a href="#"><i class="fa fa-puzzle-piece fa-lg fa-fw sidebar-icon"></i> Manage <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="manage">
                    <li><a href="#"><i class="fa fa-angle-double-right"></i> Devices</a></li>
                    <li><a href="#"><i class="fa fa-angle-double-right"></i> Groups</a></li>
                    <li><a href="#"><i class="fa fa-angle-double-right"></i> SIM Cards</a></li>
                    <li><a href="#"><i class="fa fa-angle-double-right"></i> Users</a></li>
                </ul>

                <li data-toggle="collapse" data-target="#settings" class="collapsed">
                    <a href="#"><i class="fa fa-sliders fa-lg fa-fw sidebar-icon"></i> Settings <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="settings">
                    <li><a href="#"><i class="fa fa-angle-double-right"></i> General</a></li>
                    <li class="active"><a href="#"><i class="fa fa-angle-double-right"></i> Security</a></li>
                    <li><a href="#"><i class="fa fa-angle-double-right"></i> Notifications</a></li>
                </ul>

                <li  data-toggle="collapse" data-target="#maintenance" class="collapsed">
                    <a href="#"><i class="fa fa-cogs fa-lg fa-fw sidebar-icon"></i> Maintenance <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="maintenance">
                    <li><a href="#"><i class="fa fa-angle-double-right"></i> Operation Logs</a></li>
                    <li><a href="#"><i class="fa fa-angle-double-right"></i> Events and Alarms</a></li>
                    <li><a href="#"><i class="fa fa-angle-double-right"></i> Backup and Restore</a></li>
                </ul>

                <li data-toggle="collapse" data-target="#tools" class="collapsed">
                    <a href="#"><i class="fa fa-wrench fa-lg fa-fw sidebar-icon"></i> Tools <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="tools">
                    <li><a href="#"><i class="fa fa-angle-double-right"></i> Manual SMS</a></li>
                    <li><a href="#"><i class="fa fa-angle-double-right"></i> Import</a></li>
                    <li><a href="#"><i class="fa fa-angle-double-right"></i> Export</a></li>
                </ul>

                <li  data-toggle="collapse" data-target="#help" class="collapsed">
                    <a href="#"><i class="fa fa-life-ring fa-lg fa-fw sidebar-icon"></i> Help <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="help">
                    <li><a href="#"><i class="fa fa-angle-double-right"></i> Documentation</a></li>
                    <li><a href="#"><i class="fa fa-angle-double-right"></i> Customer Support <small><i class="fa fa-external-link"></i></small></a></li>
                </ul>--}}
            </ul>
        </div>
    </div>
</div>


<header>
    <div class="container-fluid" style="">
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
        <ul class="pull-right list-unstyled">
            <li class="" id="clock"></li>
            <li class="date">{{\Carbon\Carbon::now()->format('d M Y')}}</li>
            <li class="day">
                @php 
                    $day=[0=>"Sunday",1=>"Monday", 2=>"Tuesday",3=>"Wednesday",4=>"Thursday",5=>"Friday",6=>"Saturday"];
                 @endphp
                {{$day[\Carbon\Carbon::now()->dayOfWeek]}}
            </li>
        </ul>
    </div>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
              <span>
                  @if(Auth::user()->mdas_master_role_id==1)
                      <a href="{{route('admin.dashboard')}}">
                          <img src="{{asset('mdas_assets/images/logo mdass.png')}}" alt="logo" style="width: 130px;margin-top: 5px;"/>
                      </a>
                  @else
                      <a href="{{route('admin.courtCases.dashboard')}}">
                          <img src="{{asset('mdas_assets/images/logo mdass.png')}}" alt="logo" style="width: 130px;margin-top: 5px;"/>
                      </a>
                  @endif
              </span>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
              <ul class="nav navbar-nav">
                  <li class="{{ (request()->is('admin/courtCases/dashboard')) ? 'active' : '' }}"><a href="{{route('admin.courtCases.dashboard')}}">Dashboard</a></li>
                  <li class="{{ (request()->is('admin/courtCases/addCourtCase')) ? 'active' : '' }}"><a  href="{{ route('admin.courtCases.addCourtCase') }}">Add Court Case</a></li>
                  <li class="{{ (request()->is('admin/courtCases/listCourtCase')) ? 'active' : '' }}"><a href="{{route('admin.courtCases.listCourtCase')}}">Track Court Case</a></li>
                  <li class="{{(request()->is('admin/courtCases/addRecipients')) ? 'active' : ''}}">
                      <a href="{{route('admin.courtCases.addRecipients')}}"> Add Recipients</a>
                  </li>
                  <li class="{{(request()->is('admin/courtCases/viewRecipients')) ? 'active' : ''}}">
                      <a href="{{route('admin.courtCases.viewRecipients')}}"> View Recipients</a>
                  </li>
                  <li class="{{ (request()->is('admin/courtCases/listStatusReport')) ? 'active' : '' }}"><a href="{{route('admin.courtCases.listStatusReport')}}">Case Status Reports</a></li>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown" href="#">Hi, {{ Auth::user()->username }}<span class="caret user"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="#"><i class="fa fa-user"></i> My Profile</a></li>
                        <li><a href="{{route('admin.UsersManagement.change_password')}}"><i class="fa fa-wrench"></i> Change Password</a></li>
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
            </div>
        </div>
    </nav>     
</header>
    

<div class="container-fluid">
    @yield('content')
</div>
    

<div class="f-strip">
    <div class="row">
        <div class="col-md-12" style="font-size:9pt;">Copyright © 2019, SIPRD.All rights reserved. Designed, Developed &amp; Maintained by <a href="https://gratiatechnology.com/">Gratia Technology</a></div>
    </div>
</div>

<script src="{{asset('mdas_assets/js/jquery.min.js')}}"></script>
<script src="{{asset('mdas_assets/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('mdas_assets/js/zebra_datepicker.min.js')}}"></script>
<script src="{{asset('mdas_assets/js/jqueryValidate.js')}}"></script>
<script src="{{asset('mdas_assets/js/jqueryAddValidate.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

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



    
    
</script>
</body>
</html>
