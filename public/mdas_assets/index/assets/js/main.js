(function($){
	"use strict";
	jQuery(document).on('ready', function () {
		// Header Sticky
		$(window).on('scroll',function() {
            if ($(this).scrollTop() > 120){  
                $('.header-sticky').addClass("is-sticky");
            }
            else{
                $('.header-sticky').removeClass("is-sticky");
            }
        });
		
		// Mobile Menu
		var menuNav = $('nav.main-navbar-nav');
		menuNav.meanmenu({
			meanScreenWidth: '991',
			meanMenuContainer: '.main-menu-area',
		});
		
		// Search Btn
		$(".search-button").on('click', function(){
			$(".search-wrapper").toggleClass("is-active");
		});
        
        // Home Slides
        $(".home-slides").owlCarousel({
            items: 1,
            nav: true,
            dots: false,
            touchDrag: true,
            mouseDrag: true,
            autoplay: true,
			animateOut: 'slideOutDown',
			animateIn: 'slideInUp',
            smartSpeed: 700,
            loop: true,
            navText: [
            "<i class='icofont-rounded-left'></i>",
            "<i class='icofont-rounded-right'></i>"
            ]
        });
        
        // Counter
        $(".count").counterUp({
            delay: 20,
            time: 1500
        });
        
        // Popup Video
        $('.popup-video').magnificPopup({
            disableOn: 320,
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            fixedContentPos: false
        });
        
        // Achievement Slides
        $('.achievement-slides').owlCarousel({
            items:1,
            loop:true,
            autoplay:true,
            nav:true,
            animateOut: 'slideOutDown',
			animateIn: 'slideInRight',
            responsiveClass:true,
            dots:false,
            autoplayHoverPause:true,
            mouseDrag:true,
            navText: [
            "<i class='icofont-rounded-left'></i>",
            "<i class='icofont-rounded-right'></i>"
            ],
        });
        
        // Team Slides
        $(".team-slides").owlCarousel({
            nav: true,
            dots: false,
            center: false,
            touchDrag: false,
            mouseDrag: true,
            autoplay: true,
            smartSpeed: 750,
			autoplayHoverPause: true,
            loop: true,
            navText: [
            "<i class='icofont-rounded-left'></i>",
            "<i class='icofont-rounded-right'></i>"
            ],
            responsive:{
                0:{
                    items:1,
                },
                576:{
                    items:2,
                },
                768:{
                    items:3,
                },
                1200:{
                    items:4,
                }
            }
        });
        
        // News Slides
        $(".news-slides").owlCarousel({
            nav: true,
            dots: false,
            center: false,
            touchDrag: false,
            mouseDrag: true,
            autoplay: true,
            smartSpeed: 750,
			autoplayHoverPause: true,
            loop: true,
            navText: [
            "<i class='icofont-rounded-left'></i>",
            "<i class='icofont-rounded-right'></i>"
            ],
            responsive:{
                0:{
                    items:1,
                },
                768:{
                    items:2,
                },
                1200:{
                    items:3,
                }
            }
        });
        
        // Feedback Slides
        $(".feedback-slides").owlCarousel({
            nav: false,
            dots: true,
            items: 1,
            center: false,
            touchDrag: false,
            mouseDrag: true,
            autoplay: true,
            smartSpeed: 750,
			autoplayHoverPause: true,
            loop: true,
            navText: [
            "<i class='icofont-rounded-left'></i>",
            "<i class='icofont-rounded-right'></i>"
            ]
        });
		
		// WOW JS
		new WOW().init();
		
		// Shorting
		$(function(){
            $('.shorting').mixItUp();
        });
		
		// Popup Gallery
		$('.popup-gallery').magnificPopup({
            type: 'image',
            gallery:{
                enabled:true
            }
        });
        
        // News Details Img Slides
        $(".news-details-img").owlCarousel({
            nav: false,
            dots: false,
            items: 1,
            center: false,
            touchDrag: false,
            mouseDrag: true,
            autoplay: true,
            smartSpeed: 750,
			autoplayHoverPause: true,
            loop: true,
            navText: [
            "<i class='icofont-rounded-left'></i>",
            "<i class='icofont-rounded-right'></i>"
            ]
        });
        
        // Event Details Img Slides
        $(".events-details-img").owlCarousel({
            nav: false,
            dots: false,
            items: 1,
            center: false,
            touchDrag: false,
            mouseDrag: true,
            autoplay: true,
            smartSpeed: 750,
			autoplayHoverPause: true,
            loop: true,
            navText: [
            "<i class='icofont-rounded-left'></i>",
            "<i class='icofont-rounded-right'></i>"
            ]
        });
		
		// Go to Top
        $(function(){
            //Scroll event
            $(window).on('scroll', function(){
                var scrolled = $(window).scrollTop();
                if (scrolled > 300) $('.go-top').fadeIn('slow');
                if (scrolled < 300) $('.go-top').fadeOut('slow');
            });  
            //Click event
            $('.go-top').on('click', function() {
                $("html, body").animate({ scrollTop: "0" },  500);
            });
        });
    });
    
    /* Preloader
    ========================================================*/
    jQuery(window).on('load', function() {
        $('.preloader-area').fadeOut();
    });
}(jQuery));