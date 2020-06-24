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
		validation( this, /^[a-zA-ZæøåÆØÅ\.\' \-]{2,30}$/, 'Ugyldig fornavn!' );
	});

// *****************************************************
/**
 *
 */	
	$( '#lastname' ).on( 'change', function()
	{
		validation( this, /^[a-zA-ZæøåÆØÅ\.\' \-]{2,30}$/, 'Ugyldig etternavn!' );
	});

// *****************************************************
/**
 *
 */	
	$( '#address' ).on( 'change', function()
	{
		validation( this, /^[a-zA-ZæøåÆØÅ0-9\.\' \-]{2,45}$/, 'Ugyldig adresse!' );
	});

// *****************************************************
/**
 *
 */	
	$( '#postal_code' ).on( 'change', function()
	{
		validation( this, /^[0-9]{4,5}$/, 'Ugyldig postnummer!' );
	});

// *****************************************************
/**
 *
 */	
	$( '#city' ).on( 'change', function()
	{
		validation( this, /^[a-zA-ZæøåÆØÅ\.\' \-]{2,40}$/, 'Ugyldig poststed!' );
	});

// *****************************************************
/**
 *
 */	
	$( '#phone' ).on( 'change', function()
	{
		validation( this, /^[0-9]{2,20}$/, 'Ugyldig telefonnummer!' );
	});

// *****************************************************
/**
 *
 */	
	$( '#email' ).on( 'change', function()
	{
		validation( this, /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,4}$/, 'Ugyldig e-postadresse!' );
	});

// *****************************************************
/**
 *
 */	
	$( '#password' ).on( 'change', function()
	{
		validation( this, /^[a-zA-Z0-9]{4,20}$/, 'Ugyldig passord!' );
	});

// *****************************************************
/**
 *
 */	
	$( '#confirmed_password' ).on( 'change', function()
	{
		if( $( "#confirmed_password" ).val() != $( "#password" ).val() )
		{
			$( '#' + $( this ).attr( 'id' ) + '_exception' ).html( "Passordene stemmer ikke overens!" );
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
