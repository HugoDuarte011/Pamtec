jQuery( document ).ready(function( $ ) {

	bloganimate( 0.9 );

	$(window).scroll(function () {

		bloganimate( 0.6 );
		
	});
	
	function bloganimate( place ) {
		if ( $( 'body' ).hasClass( 'animate-blog' ) ) {
			$( '.flex-container' ).each( function() {	
				if ( true == isOnScreenBlog( $( this ), place ) ) {
					$( this ).removeClass( 'animate-block' ).addClass( 'start-animation' );
				}
				else if ( $( 'body' ).hasClass( 'restart-blog' ) && true == isOutOfScreenBlog( $( this ) ) ) {
					$( this ).addClass( 'animate-block' ).removeClass( 'start-animation' );
				}
			});
		}
		else if ( $( 'body' ).hasClass( 'restart-blog' ) ) {
			$( '.flex-container' ).addClass( 'animate-block' );
		}
	}
	
	function isOnScreenBlog( className, offset ) {
		
		var curr = $( className );
		if( curr.size() <= 0)
			return false;
		var currTop = curr.offset().top;   
		
		if (  $( window ).scrollTop() + $( window ).height() * offset > ( currTop ) && 
			( $( window ).scrollTop() < currTop + curr.height() ) 
			||  
			( $( window ).scrollTop() + $( window ).height()>= $( document ).height &&
			  $( window ).scrollTop() + $( window ).height() > ( currTop ) &&
			( $( window ).scrollTop() < currTop + curr.height() ) )
			) {
			return true;
		}
		return false;
	}
	
	function isOutOfScreenBlog( className ) {
		
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