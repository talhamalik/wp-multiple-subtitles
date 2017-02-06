
/**
 * @package     Gandhara WP Subtitles
 * @subpackage  JavaScript > Pointers
 */

jQuery( document ).ready( function( $ ) {

	function gi_wp_subtitles_open_pointer( i ) {
		pointer = wpsSubtitlePointer.pointers[ i ];
		options = $.extend( pointer.options, {
			close : function() {
				$.post( ajaxurl, {
				    pointer : pointer.pointer_id,
				    action  : 'dismiss-wp-pointer'
				} );
			}
		});

		$( pointer.target ).pointer( options ).pointer( 'open' );
	}

    gi_wp_subtitles_open_pointer( 0 );

} );
