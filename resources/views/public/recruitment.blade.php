

@extends('layouts.app_website')

@section('custom_title')
    Home
@endsection

@section('custom_css')
	<style>
		.mt40{
		margin-top:40px;
		}

	</style>
@endsection
	

@section('content')
<div class="container">
		<div class="panel panel-primary mt40">
			<div class="panel-heading">
				<h3>CAREER &amp; RECRUITMENT</h3>
			</div>
			<div class="panel-body">
			<ol>
				<li><h4>Online applications are invited for 1,238 numbers of temporal positions for MGNREGA in the State Institiute of Panchayat and Rural Development, Assam</h4></li>
				</ol>
			</div>
		</div>
	</div>

@endsection

@section('custom_js')

    <script src="{{asset('mdas_assets/index/assets/slider/jquery.flipster.min.js')}}"></script>
    <!-- Owl Carousel Min Js -->
    <script src="{{asset('mdas_assets/index/assets/js/owl.carousel.min.js')}}"></script>
    
    <script type="application/javascript">
        function increase_font()
            {
                var fontSizep = parseInt($("p").css("font-size"));
                var fontSizea = parseInt($("a").css("font-size"));
                fontSizep = fontSizep + 1 + "px";
                fontSizea = fontSizea + 1 + "px";
                if( fontSizep <= "23" ) {
                    $("p").css({'font-size':fontSizep});
                    $("a").css({'font-size':fontSizea});
                }
            }
            function decrease_font()
            {
                var fontSizep = parseInt($("p").css("font-size"));
                var fontSizea = parseInt($("a").css("font-size"));
                fontSizep = fontSizep - 1 + "px";
                fontSizea = fontSizea - 1 + "px";
                if( fontSizep >= "15" ) {
                    $("p").css({'font-size':fontSizep});
                    $("a").css({'font-size':fontSizea});
                }
            }
            
            var carousel = $("#carousel").flipster({
                                    style: 'carousel',
                                    spacing: -0.5,
                                    nav: false,
                                    buttons:true,
                                });



		$('#sonitpur').on('click', function(e){
		    e.preventDefault();
            $('#myModal2').modal('show');
		});

        /*$(document).ready(function(){
            $('[data-toggle="popover"]').popover();
        });*/
        
    </script>

@endsection










	