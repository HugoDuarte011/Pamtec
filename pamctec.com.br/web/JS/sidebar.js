jQuery( document ).ready(function( $ ) {

	addClasses();
	sidebaranimate( 0.9 );

	$(window).scroll(function () {

		sidebaranimate( 0.6 );
		
	});
	
	function addClasses() {
		if ( $( 'body' ).hasClass( 'animate-sidebar' ) && $(window).width() ) {
			$( '.widget' ).each( function() {	
				$( this ).addClass( 'animate-widget' );
			});
		}
	}
	
	function sidebaranimate( place ) {
		if ( $( 'body' ).hasClass( 'animate-sidebar' ) ) {
			$( '.widget' ).each( function() {	
				if ( true == isOnScreensidebar( $( this ), place ) ) {
					$( this ).removeClass( 'animate-widget' ).addClass( 'start-widget' );
				}
				else if ( $( 'body' ).hasClass( 'restart-sidebar' ) && true == isOutOfScreensidebar( $( this ) ) ) {
					$( this ).addClass( 'animate-widget' ).removeClass( 'start-widget' );
				}
			});
		}
		else if ( $( 'body' ).hasClass( 'restart-sidebar' ) ) {
			$( '.widget' ).addClass( 'animate-widget' );
		}
	}
	
	function isOnScreensidebar( className, offset ) {
		
		var curr = $( className );
		if( curr.size() <= 0)
			return false;
		var currTop = curr.offset().top;
		
		if (  $( window ).scrollTop() + $( window ).height() * offset > ( currTop ) && 
			( $( window ).scrollTop() < currTop + curr.height() ) 
			||  
			( $( window ).scrollTop() + $( window ).height()>= $( document ).height() &&
			  $( window ).scrollTop() + $( window ).height() >= ( currTop ) &&
			( $( window ).scrollTop() < currTop + curr.height() ) )
			) {
			return true;
		}
		return false;
	}
	
	function isOutOfScreensidebar( className ) {
		
		var curr = $( className );
		if( curr.size() <= 0)
			return false;

		var currTop = curr.offset().top;      
		if ( $( window ).scrollTop() + $( window ).height() < currTop || $( window ).scrollTop() > currTop + curr.height() ) {
			return true;
		}
		return false;
	}

});