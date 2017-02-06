<?php

/**
 * @package     WP Multiple Subtitles
 * @subpackage  Deprecated Functions
 */

/**
 * Query DB and echo page/post subtitles, if any
 *
 * @uses  WPSubtitle::_get_post_meta()
 *
 * @since  1.0
 * @deprecated  2.0  Use get_the_subtitles() instead.
 */
function gi_wp_get_the_subtitles() {
	_deprecated_function( 'gi_wp_get_the_subtitles()', '2.0', 'the_subtitle()' );

	$subtitle = new GI_WP_Subtitle( get_the_ID() );
	$subtitle->the_subtitles();

}

/**
 * Display XHTML for subtitles panel
 *
 * @since  0.9
 * @deprecated  2.0  Legacy function.
 */
function wps_addPanelXHTML() {
	_deprecated_function( 'wps_addPanelXHTML()', '2.0' );
}

/**
 * Include CSS for subtitle panel
 *
 * @since  1.0
 * @deprecated  2.0  Legacy function.
 */
function wps_addPanelCSS() {
	_deprecated_function( 'wps_addPanelCSS()', '2.0' );
}

/**
 * Include XHTML for form inside panel
 *
 * @since  1.0
 * @deprecated  0.9 Beta  Legacy function.
 */
function wps_showSubtitlePanel() {
	_deprecated_function( 'wps_addPanelCSS()', '2.0' );
}

/**
 * For pre-2.5, include shell for panel
 *
 * @since  0.9 Beta
 * @deprecated  0.9  Legacy function.
 */
function wps_showSubtitlePanelOld() {
	_deprecated_function( 'wps_showSubtitlePanelOld()', '2.0' );
}

/**
 * Store subtitle content in db as custom field
 *
 * @since  0.9 Beta
 * @deprecated  0.9 Beta  Legacy function.
 */
function wps_saveSubtitle( $post_id ) {
	_deprecated_function( 'wps_saveSubtitle()', '2.0' );
}
