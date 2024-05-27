@extends('layouts.app_public')

@section('custom_css')
    <style type="text/css">
        body {
            margin: 0px;
            padding: 0px;
            background: url({{asset('mdas_assets/images/bg.jpg')}}) no-repeat center center fixed;
            background-size: cover;
        }

    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="container">
            <div class="col-md-4 col-sm-8 col-xs-12 col-md-offset-4 col-sm-offset-2 login-area">
                <div class="row text-center bg-logo">
                    <img src="{{asset('mdas_assets/images/SPIRD_logo.png')}}" alt="logo" style="width:70px"/>
                    <h4 class="text-uppercase">Panchayat & Rural Development</h4>
                    <h6>STATE INSTITUTE OF PANCHAYAT AND RURAL DEVELOPMENT (SIPRD)</h6>
                </div>
                <div class="row text-center bg-mds">
                    <h4 class="text-uppercase">Monitoring &amp; Data Analytics System</h4>
                </div>

                @if ($errors->has('username'))
                    <p class="text-danger text-center">{{ $errors->first('username') }}</p>
                @endif

                <form class="mt20" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label>Username</label>
                        <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus placeholder="Enter Username">
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Enter Password">

                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                 <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-block animated-button thar-two">LOGIN</button>
                    </div>

                    <div class="form-group text-center">
                        <a href="{{url('/')}}" style="color: orangered;padding: 0px 5px;text-decoration: none;">-Back to Home Page-</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    {{--<script type="application/javascript">

        /*$(document).on('submit', '#loginForm', function (e) {
            e.preventDefault();
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                /!* headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },*!/
                type: "POST",
                url: 'http://103.210.73.41/PNRDHCMS/remotelogin',
                dataType: "json",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.msgType == true) {
                        callLogin(data);
                        //window.location =redirect_url+'?loginkey='+data.loginkey+'&userData='+JSON.stringify(data.data)+'';
                    } else {
                        alert(data.msg);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                },
                complete: function (data) {
                    $('.page-loader-wrapper').fadeOut();
                }
            });

        });


        function callLogin(data1) {
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('loginwithdata')}}',
                dataType: "json",
                data: {userData: data1},
                cache: false,
                success: function (data) {
                    if (data.msgType == true) {
                        window.location = '{{route('home')}}';
                        //window.location =redirect_url+'?loginkey='+data.loginkey+'&userData='+JSON.stringify(data.data)+'';
                    } else {
                        alert(data.msg);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                },
                complete: function (data) {
                    $('.page-loader-wrapper').fadeOut();
                }
            });
        }*/
    </script>--}}
@endsection