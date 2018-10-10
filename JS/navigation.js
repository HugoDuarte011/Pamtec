jQuery( document ).ready(function( $ ) {

	$('.menu-1 .menu-toggle').click( function(){
		$( '.menu-1 ul.nav-horizontal, .menu-1 div.nav-horizontal > ul' ).toggleClass( 'visible' );
		return false;
	});

	$('.nav-horizontal li').bind('mouseover', meditation_openSubMenu);
	var is_scroll = false;

	function meditation_openSubMenu() {
		var all = $(window).width();
		var height = $(document).height();
		
		if(parseInt(all) < 680) 
			return;
			
		var left = $(this).offset().left;
		var width = $(this).outerWidth(true);
		
		var offset = all - (left + width + 250);
		if( offset < 0 ) {
			$(this).find( 'ul' ).css('left','auto').css('right','0').css('top','100%').css('width','300');
			$(this).find( 'ul ul' ).css('left','auto').css('right','100%').css('top','-2px').css('width','300');
		}
		
		if( $(this).offset().top + parseInt($(this).height()) + parseInt($(this).find( '> ul' ).height()) > height ) {
			$(this).find( '> ul' ).css('top','-'+parseInt($(this).find( '> ul' ).height())+'px').css('left','auto').css('right','0');
		}
	}
	
	$('.scrollup').click( function(){
		$('html, body').animate({scrollTop : 0}, 1000);
		return false;
	});
	
	var adm = 0;
	adm = getTop();

	$(window).scroll(function () {
		if ( $( 'body' ).hasClass( 'sticky-menu' ) )
			stickIt();
		if ( $(this).scrollTop() > 200 ) {
			if( $('.scrollup').hasClass( 'visible' ) )
				return;
			$('.scrollup').addClass( 'visible' ).fadeIn();
		} else {
			$('.scrollup').removeClass( 'visible' ).fadeOut();
		}
	});

	$(window).resize( function(){
		if ( $( 'body' ).hasClass( 'sticky-menu' ) )
			resizeIt();

		if ( $(window).width() > 680 ) 
			$('.menu-1 ul.nav-horizontal, .menu-1 div.nav-horizontal > ul').removeClass( 'visible' );
	});
	
	$('.scrollup').click( function(){
		$('html, body').animate({scrollTop : 0}, 1000);
		return false;
	});
	
	if ( $( '.meditation_sidebar_nav' ).size() > 0 )
		return;
		
	//Sticky Menu
	if ( $( 'body' ).hasClass( 'sticky-menu' ) ) {
		$('.top-1-navigation')
		.addClass('original')
		.clone()
		.insertAfter('.top-1-navigation')
		.addClass('cloned')
		.css('position','fixed')
		.css('top','0')
		.css('margin-top',adm)
		.css('margin-left','0')
		.css('z-index','500')
		.removeClass('original')
		.hide();
		
		stickIt();
	}

	function getTop() {
		if(parseInt($('#wpadminbar')) != 'undefined')
			adm = parseInt($('#wpadminbar').css('height'));	
		if ( isNaN( adm ) )
			adm = 0;
		return adm;
	}

	function stickIt() {

		var orgElement = $('.original');
		if( orgElement.size() <= 0)
			return;	
		adm = getTop();
		var orgElementTop = $('.original').offset().top;         
		var offset = 0;
		if ( $( 'body' ).hasClass( 'menu-effect-1' ) )
			offset = 0;
		else
			offset = $('.sg-header-area').height();
	
		if ($(window).scrollTop() + adm > (orgElementTop) + offset && $(window).width() > 680 ) {
						
			if ( $('.cloned').hasClass( 'menu-visible' ) )
				return;		
				
			var coordsOrgElement = orgElement.offset();
			var leftOrgElement = coordsOrgElement.left;  
			var widthOrgElement = orgElement.width();

			$('.cloned')
				.css('left',leftOrgElement+'px')
				.css('top',0)
				.css('margin-top',adm)
				.css('width',widthOrgElement)
				.show();
			setTimeout( function() {
				$('.cloned').addClass( 'menu-visible' );
			}, 10 );

		} else if ( $(window).scrollTop() + adm <= (orgElementTop) && $(window).width() > 960 ) {
			$('.cloned')
				.hide()
				.removeClass( 'menu-visible' );
		}
	}
	function resizeIt() {
		var orgElement = $('.original');
		if( orgElement.size() <= 0)
			return;
		var orgElementTop = $('.original').offset().top;      

		adm = getTop();		

		if ($(window).scrollTop() + adm > (orgElementTop) && $(window).width() > 960 ) {

			var coordsOrgElement = orgElement.offset();
			var leftOrgElement = coordsOrgElement.left;  
			var widthOrgElement = parseInt(orgElement.css('width'));

			$('.cloned')
				.css('left',0)
				.css('top',0)
				.css('margin-top',adm)
				.css('width',widthOrgElement)
				.show()
				.addClass( 'on-sceen' );

		} else {
			$('.cloned')
				.hide()
				.removeClass( 'on-sceen' );
		}
	}
});