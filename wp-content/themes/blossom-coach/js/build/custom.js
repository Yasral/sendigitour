jQuery(document).ready(function($) {

	var rtl, mrtl, winWidth, stickyBarHeight;
	
	winWidth = $(window).width();
	
	$('.sticky-t-bar').addClass('active');
	$('.sticky-t-bar .sticky-bar-content').show();
	$('.sticky-t-bar .close').on( 'click', function(){
		if($('.sticky-t-bar').hasClass('active')){
			$('.sticky-t-bar').removeClass('active');
			$('.sticky-t-bar .sticky-bar-content').stop(true, false, true).slideUp();
		}else{
			$('.sticky-t-bar').addClass('active');
			$('.sticky-t-bar .sticky-bar-content').stop(true, false, true).slideDown();
		}
	});
	
	$(window).on('load resize', function() {
		stickyBarHeight = $('.sticky-t-bar .sticky-bar-content').innerHeight();
		$('.site-header').css('padding-top', stickyBarHeight);
	});

	var siteWidth = $('.site').width();
	var containerWidth = $('.site-header .wrapper').width();
	var halfwidth = (parseInt(siteWidth) - parseInt(containerWidth)) / 2;
	$('.custom-background .sticky-t-bar span.close').css('right', halfwidth);

	//search toggle js
	$('.header-search > button').on( 'click', function(){
		$('.header-search-form').fadeIn();
	});
	
	$('.header-search .close').on( 'click', function(){
		$('.header-search-form').fadeOut();
	});

	$(window).on( 'keyup', function(event) {
		if(event.key == 'Escape') {
			$('.header-search-form').fadeOut();
		}
	});
	
	$('.main-navigation .toggle-button').on( 'click', function(){
		$('.main-navigation .primary-menu-list').animate({
			width: 'toggle',
		});
	});
	$('.main-navigation .close-main-nav-toggle').on( 'click', function(){
		$('.main-navigation .primary-menu-list').animate({
			width: 'toggle',
		});
	});
	$('.main-navigation ul li.menu-item-has-children').find('> a').after('<button class="submenu-toggle"><i class="fa fa-angle-down"></i></button>');
    $('.main-navigation ul li button').on('click', function(){
        $(this).toggleClass('active');
        $(this).siblings('.main-navigation ul ul').slideToggle();
    });

	//for accessibility
	$('.main-navigation ul li a, .main-navigation ul li button').on( 'focus', function() {
		$(this).parents('li').addClass('focused');
	}).on( 'blur', function() {
		$(this).parents('li').removeClass('focused');
	});
	
    //back to top js	
    $(window).on( 'scroll', function(){
    	if($(this).scrollTop() > 200) {
    		$('.back-to-top').addClass('show');
    	}else {
    		$('.back-to-top').removeClass('show');
    	}
    });

    $('.back-to-top').on( 'click', function(){
    	$('html, body').animate({
    		scrollTop:0
    	}, 1000);
    });
    
    if( blossom_coach_data.rtl == '1' ){
    	rtl  = true;
    	mrtl = false;
    }else{
    	rtl  = false;
    	mrtl = true;
    }
    
    $('.grid-view .site-main').imagesLoaded( function(){
    	$('.grid-view:not(.woocommerce):not(.search-no-results):not(.no-post) .site-main').masonry({
    		itemSelector : '.hentry',
    		isOriginLeft : mrtl,
    	});
    });

    // banner slider
    $('#banner-slider').owlCarousel({
    	loop       : false,
    	mouseDrag  : false,
    	margin     : 0,
    	nav        : true,
    	items      : 1,
    	dots       : false,
    	autoplay   : false,
    	navText    : '',
    	rtl        : rtl,
    	lazyLoad   : true,
    	animateOut : blossom_coach_data.animation,
    });
    
});