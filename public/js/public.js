jQuery( document ).ready( function( $ )
{
	if ( 'document_load' === PreloaderBundle.closePreloader )
	{
		$( 'body, html' ).css( 'overflow', 'initial' );
		
		$( "#preloader-bundle" ).remove();
	}
	else if( 'seconds_later' === PreloaderBundle.closePreloader )
	{
		setTimeout( function()
		{
			$( 'body, html' ).css( 'overflow', 'initial' );
			
			$( "#preloader-bundle" ).remove();

		}, PreloaderBundle.secondsToCloseThePreloader * 1000 );
	}
} );