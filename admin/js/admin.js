jQuery( document ).ready( function( $ )
{
	var close_preloader = $( '.close_preloader' );
	
	var seconds_to_close_the_preloader = $( '.seconds_to_close_the_preloader' );

	seconds_to_close_the_preloader.hide();

	if ( 'seconds_later' === close_preloader.find( 'select' ).val() )
	{
		seconds_to_close_the_preloader.show();
	}

	close_preloader.find( 'select' ).change( function ( e )
	{
		if ( 'seconds_later' === $( this ).val() )
		{
			seconds_to_close_the_preloader.show();
		}
		else
		{
			seconds_to_close_the_preloader.hide();
		}
	} );
} );