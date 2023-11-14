( function( $ ) {
	"use strict";

	// Set Fixed Header
	var $cloneHeader = $('.main-header').clone().removeClass('main-header-original').addClass('main-header-clone').appendTo('body');
	$(window).on('load scroll', function() {
		var value = $(this).scrollTop();
		if ( value > 300 ) {
			$cloneHeader.addClass('main-header-clone-show');
		} else {
			$cloneHeader.removeClass('main-header-clone-show');
			$('.main-header').removeClass('drawer-opened');
		}
	} );

	// Set Drawer Menu
	$('.drawer-hamburger').on('click', function() {
		$(this).parent().parent().toggleClass('drawer-opened');
	} );
	$('.drawer-overlay').on('click',function() {
		$('.main-header').removeClass('drawer-opened');
	} );

	// Set Smooth Scroll
	$('a[href^="#"]').click(function() {
		var headerHight = $('.main-header-clone').outerHeight()+45;
		var href = $(this).attr('href');
		var target = $(href == '#' || href == '' ? 'html' : href);
		var position = target.offset().top-headerHight;
		$('html,body').animate({scrollTop:position}, 400, 'swing');
	} );

	// Set Back to Top
	$(function() {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 300) {
				$('.back-to-top').fadeIn();
			} else {
				$('.back-to-top').fadeOut();
			}
		});
		$('.back-to-top').click(function () {
			$('html,body').animate({scrollTop: 0}, 600, 'swing');
			return false;
		});
	});

	// Set Slick for Featured Posts.
	if( 1 < $('.slick-item').length ) {
		$('.featured-post').slick( {
			centerMode: true,
			centerPadding: '0',
			dots: true,
			mobileFirst: true,
			slidesToShow: 1,
		} );
	}

	// Set Stickyfill for Sticky Sidebar.
	var $sticky_sidebar = $('#sticky-sidebar');
	if( 0 < $sticky_sidebar.length ) {
		Stickyfill.add($sticky_sidebar);
	}

	// Set Fitvids
	$('.entry-content').fitVids();

} )( jQuery );