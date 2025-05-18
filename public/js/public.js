jQuery( document ).ready( function( $ )
{
	if ( 'document_load' === PreloaderBundle.closePreloader )
	{
		$( "#preloader-bundle" ).remove();

		$( 'body, html' ).css( 'overflow', 'initial' );
	}
	else if( 'seconds_later' === PreloaderBundle.closePreloader )
	{
		setTimeout( function()
		{
			document.getElementById( "preloader-bundle" ).remove();

			$( 'body, html' ).css( 'overflow', 'initial' );

		}, PreloaderBundle.secondsToCloseThePreloader * 1000 );
	}
} );