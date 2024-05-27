@extends('layouts.app_public')

@section('custom_css')
    <style type="text/css">
        body {
            margin: 0px;
            padding: 0px;
            background-color: #fff;
        }

        header {
            background-image: linear-gradient(to right, #6913cc 0%, #2575fc 100%);
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

        .flag-header ul, header ul {
            margin-bottom: 0;
        }

        .flag-header a {
            padding: 0px 2px;
        }


    </style>
@endsection

@section('content')
    <div class="container-fluid flag-header">

        <ul class="list-inline pull-left">
            <li class="indianflag" style="padding: 4px;border-right: 1px solid #8080808f;
        font-size: 0.929em;">
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
    <header>
        <div class="container-fluid">
            <ul class="list-inline pull-left ul-logo">
                <li class="li_logo">
                    <img src="{{asset('mdas_assets/images/SPIRD_logo.png')}}" alt="logo" style="width:50px"/>
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
    </header>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <a href="#">
                    <img style="padding: 5px 0;" src="{{asset('mdas_assets/images/rurbansoft_logo.png')}}" class="img img-responsive rurban-logo rurban_logo_ch"/>
                </a>
            </div>
            <div class="col-md-9">
                <nav class="menu-pull">
                    <!-- Menu Toggle btn-->
                    <div class="menu-toggle">
                        <a href="#">
                            <img style="float:left;padding: 5px 0;" src="{{asset('mdas_assets/images/rurbansoft_logo.png')}}" class="img img-responsive rurban_logo_ch"/>
                        </a>
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
                            <a href="#">
                                <i class="fa fa-home" aria-hidden="true"></i>
                                <span class="title">Home</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="title">About Us</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <span class="title">Media</span>
                            </a>
                            <ul>
                                <li><a href="#">Media Gallery</a></li>
                                <li><a href="#">Social Media</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <span class="title">Citizen</span>
                            </a>
                            <ul>
                                <li><a href="#">FAQs</a></li>
                                <li><a href="#">What's New</a></li>
                                <li><a href="#">Discussion Forum</a></li>
                            </ul>
                        </li>
                        <li class="last">
                            <a href="#">
                                <span class="title">Contact Us</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item active">
                <img src="{{asset('mdas_assets/images/slider-1.jpg')}}" alt="First Slider">
            </div>

            <div class="item">
                <img src="{{asset('mdas_assets/images/slider-2.jpg')}}" alt="Second Slider">
            </div>
        </div>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div class="container-fuild mt20" style="margin-bottom: 40px">
        <div class='container'>

            <div class="col-md-3 col-sm-4 col-xs-6">
                <div class="col-md-12 col-sm-12 col-xs-12 front">
                    <h4>Employee<br>Login</h4>

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


    <div class="row">
        <div class="col-md-12">
            <div id="chartContainerMap">FusionMaps XT will load s map here!</div>
        </div>

    </div>

@endsection

@section('custom_js')

    <script type="text/javascript" src="{{asset('mdas_assets/fusioncharts/js/fusioncharts.js')}}"></script>
    <script type="text/javascript" src="{{asset('mdas_assets/fusioncharts/js/themes/fusioncharts.theme.ocean.js')}}"></script>

    <script type="text/javascript">
        FusionCharts.ready(function(){
            var perSan = new FusionCharts({
                "type": "maps/assam",
                "renderAt": "chartContainerMap",
                "width": "900",
                "height": "600",
                "dataFormat": "json",
                "dataSource": {
                    "chart": {
                        "caption": "Pradhan Mantri Awaas Yojana - Gramin",
                        "subcaption": "Percentage of Sanction",
                        "entityFillHoverColor": "#cccccc",
                        "numberScaleValue": "1,10000,1000",
                        "numberSufix": " %",
                        "showLabels": "1",
                        "theme": "ocean"
                    },
                    "colorrange": {
                        "minvalue": "0",
                        "startlabel": "Low",
                        "endlabel": "High",
                        "code": "#E63946",
                        "gradient": "1",
                        "color": [
                            {
                                "maxvalue": "50",
                                "displayvalue": "Average",
                                "code": "#ffffff"
                            },
                            {
                                "maxvalue": "100",
                                "code": "#1D3557"
                            }
                        ]
                    },
                    "data": [
                        {'id': 'IN.AS.BA','value': '89'},{'id': 'IN.AS.BK','value': '67'},{'id': 'IN.AS.BO','value': '78'},{'id': 'IN.AS.BS','value': '67'},{'id': 'IN.AS.CA','value': '54'},{'id': 'IN.AS.CD','value': '90'},{'id': 'IN.AS.CH','value': '89'},{'id': 'IN.AS.DA','value': '73'},{'id': 'IN.AS.DB','value': '80'},{'id': 'IN.AS.DI','value': '80'},{'id': 'IN.AS.DM','value': '95'},{'id': 'IN.AS.GG','value': '85'},{'id': 'IN.AS.GP','value': '84'},{'id': 'IN.AS.HA','value': '25'},{'id': 'IN.AS.HJ','value': '35'},{'id': 'IN.AS.JO','value': '80'},{'id': 'IN.AS.KA','value': '50'},{'id': 'IN.AS.KK','value': '88'},{'id': 'IN.AS.KM','value': '84'},{'id': 'IN.AS.KP','value': '87'},{'id': 'IN.AS.KR','value': '62'},{'id': 'IN.AS.LA','value': '73'},{'id': 'IN.AS.MA','value': '89'},{'id': 'IN.AS.MJ','value': '85'},{'id': 'IN.AS.NC','value': '37'},{'id': 'IN.AS.NG','value': '56'},{'id': 'IN.AS.NL','value': '89'},{'id': 'IN.AS.SI','value': '92'},{'id': 'IN.AS.SM','value': '63'},{'id': 'IN.AS.SO','value': '46'},{'id': 'IN.AS.TI','value': '64'},{'id': 'IN.AS.UD','value': '79'},{'id': 'IN.AS.WK','value': '29'},			            ]
                }
            });
            perSan.render();
        });
    </script>

    <script type="application/javascript">
        /*$("#respMenu").aceResponsiveMenu({
            resizeWidth: '768', // Set the breakpoint same in Media query
            animationSpeed: 'fast', //slow, medium, fast
            accoridonExpAll: false //Expands all the accordion menu on click
        });*/
    </script>
@endsection