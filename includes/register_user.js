// *****************************************************
/**
 *
 */
function validation( element, regEx, message )
{
	ok = false;
	if( !$( '#' + $( element ).attr( 'id' ) ).is( ':disabled' ) )
	{
		if( !regEx.test( $( element ).val() ) )
		{
			$( '#' + $( element ).attr( 'id' ) + '_exception' ).html( message );
			$( '#' + $( element ).attr( 'id' ) ).css( 'border-color', '#F00' );
		}
		else
		{
			$( '#' + $( element ).attr( 'id' ) + '_exception' ).html( '' );
			$( '#' + $( element ).attr( 'id' ) ).css( 'border-color', '#BBB' );
			ok = true;
		}
	}
	return ok;
}

// *************************************************************************
/**
 *
 */	
$( function()
{

// *****************************************************
/**
 *
 */	
	$( '#firstname' ).on( 'change', function()
	{
		validation( this, /^[a-zA-ZæøåÆØÅ\.\' \-]{2,30}$/, 'You entered an invalid first name.' );
	});

// *****************************************************
/**
 *
 */	
	$( '#lastname' ).on( 'change', function()
	{
		validation( this, /^[a-zA-ZæøåÆØÅ\.\' \-]{2,30}$/, 'You entered an invalid last name.' );
	});

// *****************************************************
/**
 *
 */	
	$( '#email' ).on( 'change', function()
	{
		validation( this, /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,4}$/, 'You entered an invalid email address.' );
	});

// *****************************************************
/**
 *
 */	
	$( '#password' ).on( 'change', function()
	{
		validation( this, /^[a-zA-Z0-9]{4,20}$/, 'You entered an invalid password.' );
	});

// *****************************************************
/**
 *
 */	
	$( '#confirmed_password' ).on( 'change', function()
	{
		if( $( "#confirmed_password" ).val() != $( "#password" ).val() )
		{
			$( '#' + $( this ).attr( 'id' ) + '_exception' ).html( "The passwords do not match." );
			$( '#' + $( this ).attr( 'id' ) ).css( 'border-color', '#F00' );
		}
		else
		{
			$( '#' + $( this ).attr( 'id' ) + '_exception' ).html( '' );
			$( '#' + $( this ).attr( 'id' ) ).css( 'border-color', '#BBB' );
		}
	});

// *****************************************************

});
