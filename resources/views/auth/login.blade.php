@extends('layouts.app_public')

@section('custom_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
        
        .pimage {
            background: url('{{asset('mdas_assets/images/leftside image.jpg')}}');
            background-repeat: no-repeat;
            background-size: cover;
        }
        .pcolor {
            color: #6b133d;
        }

        button.animated-button.thar-two {
            border: 2px solid #6b133d;
        }
        .btn {
            color: #6b133d;
        }
        button.animated-button.thar-two:before {
                background: #6b133d;
        }

        .form-control:focus {
             border-color: #6b133d;
        }
    </style>

@endsection

@section('content')
   <div class="container-fluid">
        <div class="row">
            <!-- LEFT SECTION -->
            <div class="col-md-6 col-sm-6 col-xs-12 pimage text-center" style="padding: 0px; height: 100vh;color:#fff">
                <div class="row" style="margin-top: 20%">
                    <img src="{{asset('mdas_assets/images/SPIRD_logo.png')}}" alt="logo" style="width:100px"/>
                    <h2 class="text-uppercase">Panchayat & Rural Development</h4>
                    <h4>STATE INSTITUTE OF PANCHAYAT AND RURAL DEVELOPMENT (SIPRD)</h6>
                </div>
                <div class="row" style="margin-top: 30px">
                    <img src="{{asset('mdas_assets/images/logo mdas white.png')}}" alt="logo" style="width:200px"/>
                    <h3 class="text-uppercase">Monitoring &amp; Data Analytics System</h4>
                </div>
                <div class="row" style="margin-top: 30px">
                    <h4>Designed, Developed & Maintained by</h4>
                        <img src="{{asset('mdas_assets/images/logo_digital.png')}}" alt="logo" width="25%" />
                </div>
            </div>

            <!-- RIGHT SECTION -->
            <div class="col-md-6 col-sm-6 col-xs-12" style="height: 100vh">
                    
                    

                    

                    <form class="mt20" method="POST" action="{{ route('login') }}" style="padding: 25% 15%;">
                        
                        <h2 class="text-center pcolor">User Login</h4>
                            <hr/>

                        @if ($errors->has('username'))
                            <p class="text-danger text-center">{{ $errors->first('username') }}</p>
                        @endif
						{{--@if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('password') }}</strong>
                                </span>
                        @endif--}}
						
						
                        @csrf
						
						<div class="form-group">
							<label class="pcolor">User ID</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus placeholder="Enter User ID">
							</div>
						</div>
						
						<div class="form-group">
							<label class="pcolor">Password</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
								<input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Enter Password">
							</div>
						</div>
						
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label pcolor" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
						
                        <div class="form-group">
                            <button type="submit" class="btn btn-block animated-button thar-two pcolor">SIGN IN</button>
                        </div>

                        <!--<div class="form-group text-center">
                            <a href="{{url('/')}}" class="pcolor" style="padding: 0px 5px;text-decoration: none;">-Back to Home Page-</a>
                        </div>-->
                    </form>
                  </div>
            </div>
        </div>
   </div>

@endsection

@section('custom_js')
    
@endsection