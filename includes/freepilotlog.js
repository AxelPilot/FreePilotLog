// *************************************************************************
/**
 *
 */	
function notification_open_and_mark_as_read( notification_id )
{
	$.ajax(
	{
		url: "get_notification.ajax.php",
		data:
		{
			notification_ID: notification_id,
		},
		type: 'POST',
		dataType: "json",
		success: function( notification )
		{
			if( typeof notification.exception === 'undefined' )
			{
				if ( ( notification.url != "#" ) && ( notification.url != "" ) )
				{
					window.location = notification.url;
				}
				else
				{
					$( "#notification_" + notification_id ).remove();
					
					notification_count = ( $( "#notification_count" ).html() - 1 )
					if( notification_count < 0 )
					{
						notification_count = 0;
					}
					$( "#notification_count" ).html( notification_count );
					
					if( notification_count == 0 )
					{
						$( "#notification_title" ).html( '<h3>No unread messages.</h3>' );
					}
					
					display_notification_in_lightbox( notification.title, notification.message );
				}
			}
			else
			{
				alert( notification.exception );
			}
		}
	});
}

// *************************************************************************
/**
 *
 */	
function display_notification_in_lightbox( title, message )
{
	$( "#notification_lightbox_title" ).html( "<h2>" + title + "</h2>" );
	$( "#notification_lightbox_content" ).html( "<p>" + message + "</p>" );
	$( "#fade_background" ).fadeIn( 600 );
	$( "#notification_lightbox" ).fadeIn( 200 );
}

// *************************************************************************
/**
 *
 */	
function hide_and_empty_lightbox()
{
	$( "#notification_lightbox" ).fadeOut( 200, function()
	{
		$( "#notification_lightbox_title" ).html( "" );
		$( "#notification_lightbox_content" ).html( "" );
	});

	$( "#fade_background" ).fadeOut( 600, function()
	{
		if( location.href.indexOf( 'display_notification_in_lightbox' ) != -1 )
		{
			window.location.replace( 'index.php' );
		}
	});
}

// *************************************************************************
/**
 *
 */	
function approve_admin()
{
	$( "#admin_approved_status" ).val( "1" );
	$( "#approve_admin_form" ).submit();
}

// *************************************************************************
/**
 *
 */	
function deny_admin()
{
	$( "#admin_approved_status" ).val( "" );
	$( "#approve_admin_form" ).submit();
}

// *************************************************************************
