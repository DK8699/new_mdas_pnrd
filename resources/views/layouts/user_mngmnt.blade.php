<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="{{asset('mdas_assets/images/favicon.png')}}" type="image/gif" sizes="16x16">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('mdas_assets/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('mdas_assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('mdas_assets/css/zebra_datepicker.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="//cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <style>

        .form-control {
            height: 28px;
            padding: 2px 5px;
            font-size: 12px;
        }

        label {
            font-size: 11px;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .error{
            color:red;
        }
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

        .mt40{
            margin-top: 40px;
        }

        /*.card{*/
        /*border:1px solid #ff770f;*/
        /*background-color: #f3f2f2;*/
        /*box-shadow:0 1px 5px rgba(0, 0, 0, 0.1);*/
        /*}*/
        .card-header{
            background-color: #6b133d;
            color: #fff;
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

        .bold-color {
            color: darkviolet;
            font-weight: 600;
        }
        .panel{
            border:1px solid #ff770f;
            background-color: #f3f2f2;
            box-shadow:0 1px 5px rgba(0, 0, 0, 0.1);
			margin-top: 25px;
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
        .watch#clock,.watch.date,.watch.day {
            font-family: 'Old Standard TT', serif;
            color: #fff;
            font-size: 15px;

        }
        .f-strip {
            background-color: #6e133c;
        }
        nav1{
            float: none;
            clear: both;
            width: 30%;
            margin: 5% auto;
            background: #eee;
            font-family: 'Old Standard TT', serif;
        }

        nav1 ul {
            list-style: none;
            margin: 0px;
            padding: 0px;
        }

        nav1 li{
            float: none;
            width: 100%;
        }

        nav1 li a{
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
        .breadcrumb
        {
            margin:0px;
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
            }
            #r1
            {
                margin-top: 60px;
            }
        </style>

        <div class="container" id="u1">
            <div class="row">
                <div class="col col-md-12 col-xs-12 col-sm-12 col-lg-12">
                    <p class="error"><i>Javascript is not enabled in your browser</i></p>
                    <p>Please use the latest version of any one of the browsers for best response.</p>
                </div>
            </div>
            <div class="row" id="r1">
                <div class="col-md-4 col-xs-4 col-sm-2 col-lg-2">
                    <img src="{{asset('mdas_assets/images/mozilla_icon.png')}}" style="width:80px;height:60px;" class="img-responsive">
                    <p><label>Mozilla Firefox</label></p>
                </div>
                <div class="col-md-4 col-xs-4 col-sm-2 col-lg-2">
                    <img src="{{asset('mdas_assets/images/google_chrome.jpg')}}" style="width:80px;height:60px;" class="img-responsive">
                    <p><label>Google Chrome</label></p>
                </div>
                <div class="col-md-4 col-xs-4 col-sm-2 col-lg-2">
                    <img src="{{asset('mdas_assets/images/explorer_icon.png')}}" style="width:80px;height:60px;" class="img-responsive">
                    <p><label>Internet Explorer</label></p>
                </div>
                <br><br>
            </div>
        </div>
    </noscript>
</head>
<body>
{{----------- LOADER ----------------------------}}
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
{{----------- LOADER ENDED ----------------------}}

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
                    <li class="em-title"><h5 class="text-uppercase">Monitoring &amp; Data Analytics System</h5></li>
                </ul>
            </li>
        </ul>
        <ul class="pull-right list-unstyled watch">
                 <li class="" id="clock"></li>
                 <li class="date1">{{\Carbon\Carbon::now()->format('d M Y')}}</li>
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
              
              <span><img src="{{asset('mdas_assets/images/logo mdass.png')}}" alt="logo" style="width: 130px;margin-top: 5px;"/></span>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
              <ul class="nav navbar-nav">
                  <li class="{{ (request()->is('dashboard')) ? 'active' : '' }}"><a href="{{route('dashboard')}}">Home</a></li>
                  <li class="{{ (request()->is('UsersManagement/user_dashboard')) ? 'active' : '' }}"><a href="{{route('UsersManagement.user_dashboard')}}">User Management</a></li>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown" href="#">Hi, {{ Auth::user()->username }}<span class="caret user"></span></a>
                  <ul class="dropdown-menu">
                      <li><a href="{{route('UsersManagement.profile')}}"><i class="fa fa-user"></i> <span >Profile</span></a></li>
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
        <div class="col-md-12" style="text-align: center">
            Copyright © 2019, SIPRD.All rights reserved. Designed, Developed &amp; Maintained by <a href="https://gratiatechnology.com/" target="_blank">Gratia Technology</a><br>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="changePassword" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-close"></i>
                </button>
                <h4 class="modal-title">Change Password</h4>
                <p>Fields with asterisk (<strong>*</strong>) are required.</p>
            </div>
            <form action="#" method="post" id="changePasswordModel" class="needs-validation" novalidate >
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-sm-12">
                            {{ csrf_field() }}
                            <input type="hidden" value="{{ Auth::user()->username }}" name="employee_code">
                            <div class="form-group">
                                <label>
                                    Old Password <strong>*</strong>
                                </label>
                                <a href="#" data-toggle="tooltip" title="Please provide old password. Length should be 4-to-20.">
                                    <i class="fa fa-question-circle"></i>
                                </a>
                                <input type="password" maxlength="20" minlength="4" name="oldpassword" id="oldpassword" placeholder="Old Password" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>New Password <strong>*</strong></label>
                                <a href="#" data-toggle="tooltip" title="Please provide new password. Length should be 4-to-20.">
                                    <i class="fa fa-question-circle"></i>
                                </a>
                                <input type="password" maxlength="20" minlength="4" value="" name="newpassword" id="newpassword" placeholder="New Password" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Retype New Password <strong>*</strong></label>
                                <a href="#" data-toggle="tooltip" title="Retype the new password again.">
                                    <i class="fa fa-question-circle"></i>
                                </a>
                                <input type="password" maxlength="20" minlength="4" value="" name="repassword" id="repassword" placeholder="Confirm New Password" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="reset" class="btn btn-warning">
                        <i class="fa fa-refresh"></i>
                        Reset
                    </button>
                    <button type="submit" class="btn btn-default">
                        <i class="fa fa-send"></i>
                        Change Password
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="{{asset('mdas_assets/js/jquery.min.js')}}"></script>
<script src="{{asset('mdas_assets/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('mdas_assets/js/zebra_datepicker.min.js')}}"></script>
<script src="{{asset('mdas_assets/js/jqueryValidate.js')}}"></script>
<script src="{{asset('mdas_assets/js/jqueryAddValidate.js')}}"></script>
<script src="{{asset('mdas_assets/js/currencyFormatter.min.js')}}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@yield('custom_js')

<script type="application/javascript">
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
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

    //---------------------- Delete Request Six Finance ------------------------------------------

    $('#deleteRequest').on('click', function(e){
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "You are sure you want to delete!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willStore) => {
            if (willStore) {
                var df= $(this).data('df');
                if(df){
                    $('.page-loader-wrapper').fadeIn();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: '{{route('survey.six_finance.delete_request')}}',
                        dataType: "json",
                        data: {df: df},
                        cache: false,
                        success: function (data) {
                            if (data.msgType == true) {
                                swal('Success', data.msg, 'success');
                            }else{
                                swal('Message', data.msg, 'info');
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                        },
                        complete: function (data) {
                            $('.page-loader-wrapper').fadeOut();
                        }
                    });
                }
            } else {
                swal("You have canceled your operation!");
    }
    })
    });

    //---------------------- Delete Request Six Finance Ended ------------------------------------------

    $('#passwordChange').on('click',function(e){
        e.preventDefault();
        $('#changePasswordModel')[0].reset();
        $('#changePassword').modal({backdrop: 'static', keyboard: false});
    });


    $('#changePasswordModel').validate({
        rules: {
            oldpassword: {
                required: true,
                minlength: 5
            },
            newpassword: {
                required: true,
                minlength: 5
            },
            repassword: {
                required: true,
                minlength: 5,
                equalTo: "#newpassword"
            }
        }
    });

    $('#changePasswordModel').on('submit',function(e){
        e.preventDefault();
        // $('#changePasswordModel').validate();


        if($('#changePasswordModel').valid()){
            var form = $('#changePasswordModel')[0];

            $.ajax({
                /* headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },*/
                type: "POST",
                url: 'http://103.210.73.41/PNRDHCMS/changePasswordRemote',
                dataType: "json",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.msgType==true) {
                        $('#changePassword').modal('hide');
                        toastr.success(data.msg);
                    } else {
                        if(data.oldpassword){
                            $('#oldpassword-error').remove();
                            $('#oldpassword').after('<label id="oldpassword-error" class="error" for="oldpassword">'+data.oldpassword+'</label>');

                        }
                        if(data.newpassword){
                            $('#newpassword').after('<label id="newpassword-error" class="error" for="newpassword">'+data.newpassword+'</label>');
                        }
                        if(data.repassword){
                            $('#repassword').after('<label id="repassword-error" class="error" for="repassword">'+data.repassword+'</label>');
                        }


                        toastr.error(data.msg);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {

                    callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                },
                complete: function (data) {
                    $('.page-loader-wrapper').fadeOut();
                }
            });
        }

    });
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
