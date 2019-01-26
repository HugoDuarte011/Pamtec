jQuery( document ).ready(function( $ ) {

	var classList = $( 'body' ).attr( 'class' ).split(/\s+/);
	var num1 = 1;
	var num2 = 1;
	var topOffset = 124;
	for ( var i = 0; i < classList.length; i++ ) {
		if ( 0 == classList[i].indexOf( 'widget-1-num-' ) ) {
			num1 = parseInt( classList[i].substr( 13, 2 ) );
		} else if ( 0 == classList[i].indexOf( 'widget-2-num-' ) ) {
			num2 = parseInt( classList[i].substr( 13, 2 ) );
		}
	}

	//clone widget
	if ( $( 'body' ).hasClass( 'widget-1-fixed' ) ) {
		var widgetNum = $('*[class^="text"]'); 'widget-1-num-';
		cloneWidget( '.sidebar-1', num1, topOffset );
	}	
	if ( $( 'body' ).hasClass( 'widget-2-fixed' ) ) {
		var widgetNum = 'widget-2-num-';
		cloneWidget( '.sidebar-2', num2, topOffset );
	}

	showFixedWidget( '.sidebar-1' );
	showFixedWidget( '.sidebar-2' );
	
	// show widget
	$( window ).scroll(function () {

		showFixedWidget( '.sidebar-1' );
		showFixedWidget( '.sidebar-2' );
		
	});

	// on change window size
	$( window ).resize( function(){
		if ( $( 'body' ).hasClass( 'widget-1-fixed' ) || $( 'body' ).hasClass( 'widget-2-fixed' ) )
			resizeIt();
	});

	function showFixedWidget( sidebar ) {
		if ( isOnScreen( sidebar ) ) {
			$( sidebar + ' .fixed-widget' ).addClass( 'visible' ).removeClass( 'animate-widget' ).addClass( 'start-widget' );
		} else {
			$( sidebar + ' .fixed-widget' ).removeClass( 'visible' ).addClass( 'animate-widget' ).removeClass( 'start-widget' );
		}
	}
	
	function cloneWidget( sidebar, num, top ) {
		//Fixed Widget
			var widget = $( sidebar + ' .widget:nth-child(' + num + ')' );
			if( widget.size() <= 0 )
				widget =  $( sidebar + ' .widget:last-child' );
			if( widget.size() <= 0 )
				return false;
			var width = widget.width();
			widget.clone()
			.insertBefore( sidebar + ' .widget:first-child' )
			.addClass( 'fixed-widget' )
			.addClass( 'animate-widget' )
			.css( 'margin-top', top )
			.css( 'width', width );
	}
	
	function isOnScreen( sidebar ) {
		
		var sidebarO = $( sidebar );
		if ( sidebarO.size() <= 0 )
			return false;			
		var curr = $( sidebar + ' .widget:last-child' );
		if ( curr.size() <= 0 )
			return false;		
		var widget = $( sidebar + ' .widget.fixed-widget' );
		if ( widget.size() <= 0 )
			return false;
		var currTop = curr.offset().top + curr.height();   
		if (  $( window ).scrollTop() > currTop && 
			( $( window ).scrollTop() <  $( sidebar ).height() + curr.height() ) &&
			  widget.height() < ( sidebarO.offset().top + sidebarO.height() - $( window ).scrollTop() - topOffset ) ) {
			return true;
		}
		return false;
	}
	
	function resizeIt() {
		var widget1 = $('.sidebar-1 .widget.fixed-widget');
		var widget2 = $('.sidebar-2 .widget.fixed-widget');
		if( widget1.size() > 0 ) {
			var widget = $('.sidebar-1 .widget:last-child');
			widget1.css( 'width', widget.width() );
		}
		if( widget2.size() > 0 ) {
			var widget = $('.sidebar-2 .widget:last-child');
			widget2.css( 'width', widget.width() );
		}
	}

});