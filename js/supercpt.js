function getJqueryUserLanguage()
{
	if ((navigator.language || navigator.browserLanguage).toLowerCase() == 'en-us')
	{
		return '';
	}
	else
	{
		var l = (navigator.language || navigator.browserLanguage).toLowerCase().split('-');
		if (l.length == 1)
		{
			if (jQuery.datepicker.regional[l[0]] != undefined) return l[0];
			else return '';
		}
		else if (l.length > 1)
		{
			if (jQuery.datepicker.regional[l[0] + '-' + l[1].toUpperCase()] != undefined) return l[0] + '-' + l[1].toUpperCase();
			else if (jQuery.datepicker.regional[l[0]] != undefined) return l[0];
			else return '';
		}
		else
		{
			return '';
		}
	}
}

jQuery( function( $ ){
	$.datepicker.setDefaults($.datepicker.regional[getJqueryUserLanguage()]);
	if ( $( "input[type='date'].scpt-field" ).length )
		$( "input[type='date'].scpt-field" ).datepicker( { dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true } );

	$( '#post-body' ).on( 'click', '.scpt-remove-thumbnail', function(e) {
		e.preventDefault();
		$( this ).parents( '.scpt-field-wrap' ).find( '.scpt-media-id' ).val( 0 );
		$( this ).parents( '.scpt-field-wrap' ).find( '.scpt-add-media' ).show();
		$( this ).parents( '.scpt-field-wrap' ).find( '.scpt-media-preview' ).html( '' );
	});

	$( '#post-body' ).on( 'click', '.scpt-add-media', function() {
		console.log('here');
		var old_send_to_editor = wp.media.editor.send.attachment;
		var input = this;
		wp.media.editor.send.attachment = function( props, attachment ) {
			props.size = 'thumbnail';
			props = wp.media.string.props( props, attachment );
			props.align = null;
			$(input).parents( '.scpt-field-wrap' ).find( '.scpt-media-id' ).val( attachment.id );
			if ( attachment.type == 'image' ) {
				var preview = 'Uploaded image:<br /><img src="' + props.src + '" />';
			} else {
				var preview = 'Uploaded file:&nbsp;' + wp.media.string.link( props );
			}
			preview += '<br /><a class="scpt-remove-thumbnail" href="#">Remove</a>';
			$( input ).parents( '.scpt-field-wrap' ).find( '.scpt-media-preview' ).html( preview );
			$( input ).parents( '.scpt-field-wrap' ).find( '.scpt-add-media' ).hide();
			wp.media.editor.send.attachment = old_send_to_editor;
		}
		wp.media.editor.open( input );
	} );

} );
