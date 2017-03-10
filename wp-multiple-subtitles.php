<?php

/*
Plugin Name: WP Multiple Subtitles
Plugin URI: http://github.org/talhamalik/wp-multiple-subtitles/
Description: Adds subtitle fields to pages and posts. These sub-headings are shown below the title of posts in short lines to attract audience and search engines.
Version: 0.9.2
Author: Talha Malik
Author URI: http://talhamalik.com/
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: wp-multiple-subtitles

*/

/*
Copyright 2017  Talha Malik  (email : mail@talhamalik.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Plugin directory and url paths.
define( 'GIWPSUBTITLES_BASENAME', plugin_basename( __FILE__ ) );
define( 'GIWPSUBTITLES_SUBDIR', '/' . str_replace( basename( __FILE__ ), '', GIWPSUBTITLES_BASENAME ) );
define( 'GIWPSUBTITLES_URL', plugins_url( GIWPSUBTITLES_SUBDIR ) );
define( 'GIWPSUBTITLES_DIR', plugin_dir_path( __FILE__ ) );

// Includes
include_once( GIWPSUBTITLES_DIR . 'includes/subtitles.php' );
include_once( GIWPSUBTITLES_DIR . 'includes/deprecated.php' );
include_once( GIWPSUBTITLES_DIR . 'includes/shortcode.php' );

// Include admin-only functionality
if ( is_admin() ) {
	require_once( GIWPSUBTITLES_DIR . 'admin/admin.php' );
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		// Load AJAX functions here if required...
	} else {
		require_once( GIWPSUBTITLES_DIR . 'admin/pointers.php' );
	}
}

add_action( 'init', array( 'WPSubtitle', '_add_default_post_type_support' ), 5 );

// Default subtitle filters
//add_filter( 'gi_wp_subtitles', 'wptexturize' );
//add_filter( 'gi_wp_subtitles', 'trim' );

class WPSubtitle {

	/**
	 * Add Default Post Type Support
	 *
	 * @since  1.0
	 * @internal
	 */
	public static function _add_default_post_type_support() {
		add_post_type_support( 'page', 'gi_wp_subtitles' );
		add_post_type_support( 'post', 'gi_wp_subtitles' );
	}

	/**
	 * Get Supported Post Types
	 *
	 * @since  0.9
	 *
	 * @return  array  Array of supported post types.
	 */
	public static function get_supported_post_types() {
		$post_types = (array) get_post_types( array(
			'_builtin' => false
		) );
		$post_types = array_merge( $post_types, array( 'post', 'page' ) );
		$supported = array();
		foreach ( $post_types as $post_type ) {
			if ( post_type_supports( $post_type, 'gi_wp_subtitles' ) ) {
				$supported[] = $post_type;
			}
		}
		return $supported;
	}

	/**
	 * Is Supported Post Type
	 *
	 * @since  0.9
	 *
	 * @param   string   $post_type  Post Type.
	 * @return  boolean
	 */
	public static function is_supported_post_type( $post_type ) {
		$post_types = self::get_supported_post_types();
		if ( in_array( $post_type, $post_types ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Get the Subtitle
	 *
	 * @since  0.9
	 *
	 * @uses  WP_Subtitle::get_subtitle()
	 *
	 * @param   int|object  $post  Post ID or object.
	 * @return  string             The filtered subtitle meta value.
	 */
	public static function get_the_subtitles( $post = 0 ) {

		$subtitle = new GI_WP_Subtitle( $post );

		return $subtitle->get_subtitles();

	}

	/**
	 * Get Post Meta
	 *
	 * @since  0.9
	 * @internal
	 *
	 * @uses  GI_WP_Subtitle::get_raw_subtitle()
	 *
	 * @param   int|object  $post  Post ID or object.
	 * @return  string             The subtitle meta value.
	 */
	public static function _get_post_meta( $post = 0 ) {

		$subtitle = new GI_WP_Subtitle( $post );

		return $subtitle->get_raw_subtitles();

	}

	/**
	 * Get Post Meta Key
	 *
	 * @since  0.9
	 * @internal
	 *
	 * @param   int     $post  Post ID.
	 * @return  string         The subtitle meta key.
	 */
	public static function _get_post_meta_key( $post_id = 0 ) {

		return apply_filters( 'gi_wp_subtitles_key', 'gi_wp_subtitles', $post_id );

	}

}

/**
 * The Subtitle
 *
 * @since  0.9
 *
 * @uses  GI_WP_Subtitle::get_subtitle()
 *
 * @param   string  $before  Before the subtitle.
 * @param   string  $after   After the subtitle.
 * @param   bool    $echo    Output if true, return if false.
 * @return  string           The subtitle string.
 */
function the_subtitle( $before = '', $after = '', $echo = true ) {

	$subtitle = new GI_WP_Subtitle( get_the_ID() );

	$output = $subtitle->get_subtitles( array(
		'before' => $before,
		'after'  => $after
	) );

	if ( ! $echo ) {
		return $output;
	}

	echo $output;

}

/**
 * Get the Subtitle
 *
 * @since  0.9
 *
 * @uses  GI_WP_Subtitle::get_subtitle()
 *
 * @param   int|object  $post    Post ID or object.
 * @param   string      $before  Before the subtitle.
 * @param   string      $after   After the subtitle.
 * @param   bool        $echo    Output if true, return if false.
 * @return  string               The subtitle string.
 */
function get_the_subtitles( $post = 0, $before = '', $after = '', $echo = true ) {

	$subtitle = new GI_WP_Subtitle( $post );

	$is_listItem    = false;
    if(!strlen(trim($before))>0 && !strlen(trim($after))>0){
        $before = '<li><strong>';
        $after = '</strong></li>';
        $is_listItem    = true;
    }

	$output = $subtitle->get_subtitles( array(
		'before' => $before,
		'after'  => $after
	) );

	if ( ! $echo ) {
		return $output;
	}

	if($is_listItem){
        echo '<ul class="gi-multiple-subtitles">';
    }
    foreach ($output as $outputItem) {
        echo $outputItem;
    }

    if($is_listItem){
        echo '</ul>';
    }

}
