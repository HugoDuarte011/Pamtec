jQuery( document ).ready(function( $ ) {

	if ( true == isOnScreen( '.sg-header-area' ) ) {
		$( 'body' ).removeClass( 'animate-on-load' ).addClass( 'animate' );
	}
	
	$(window).scroll(function () {

		if ( $( 'body' ).hasClass( 'animate-on-load' ) && true == isOnScreen( '.sg-header-area' ) ) {
			$( 'body' ).removeClass( 'animate-on-load' ).addClass( 'animate' );
		}
		else if ( $( 'body' ).hasClass( 'restart-header' ) && true == isOutOfScreen( '.sg-header-area' ) ) {
			$( 'body' ).addClass( 'animate-on-load' );
		}
		
	});
	
	function isOnScreen( className ) {
		
		var curr = $( className );
		if( curr.size() <= 0)
			return false;

		var currTop = curr.offset().top;      
		if ( $( window ).scrollTop() > (currTop + curr.height()/2) ) {
			return false;
		}
		return true;
	}
	
	function isOutOfScreen( className ) {
		
		var curr = $( className );
		if( curr.size() <= 0)
			return false;

		var currTop = curr.offset().top;      
		if ( $( window ).scrollTop() < currTop + curr.height() ) {
			return false;
		}
		return true;
	}

});