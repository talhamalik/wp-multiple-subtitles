<?php

/**
 * @package     WP Multiple Subtitles
 * @subpackage  Admin
 */

add_action( 'plugins_loaded', array( 'WPMultipleSubtitles_Admin', '_setup' ) );

class WPMultipleSubtitles_Admin {

	/**
	 * Setup
	 *
	 * @since  0.9
	 * @internal
	 */
	public static function _setup() {

		// Language
		load_plugin_textdomain( 'wp-subtitle', false, dirname( GIWPSUBTITLES_BASENAME ) . '/languages' );

		add_action( 'admin_init', array( 'WPMultipleSubtitles_Admin', '_admin_init' ) );
		add_action( 'save_post', array( 'WPMultipleSubtitles_Admin', '_save_post' ) );
		add_action( 'admin_enqueue_scripts', array( 'WPMultipleSubtitles_Admin', '_add_admin_scripts' ) );
	}

	/**
	 * Admin Init
	 *
	 * @since  0.9
	 * @internal
	 */
	public static function _admin_init() {

		global $pagenow;

		// Get post type
		$post_type = '';

		if ( isset( $_REQUEST['post_type'] ) ) {
			$post_type = sanitize_text_field( $_REQUEST['post_type'] );
		} elseif ( isset( $_GET['post'] ) ) {
			$post_type = get_post_type( absint( $_GET['post'] ) );
		} elseif ( in_array( $pagenow, array( 'post-new.php', 'edit.php' ) ) ) {
			$post_type = 'post';
		}

		// Setup Field / Meta Box
		if ( WPSubtitle::is_supported_post_type( $post_type ) ) {
			if ( self::edit_form_after_title_supported( $post_type ) ) {
				add_action( 'admin_head', array( 'WPMultipleSubtitles_Admin', '_add_admin_styles' ) );
				add_action( 'edit_form_after_title', array( 'WPMultipleSubtitles_Admin', '_add_subtitle_fields' ) );
			} else {
				add_action( 'add_meta_boxes', array( 'WPMultipleSubtitles_Admin', '_add_meta_boxes' ) );
			}

			add_filter( 'manage_edit-' . $post_type . '_columns', array( 'WPMultipleSubtitles_Admin', 'manage_subtitle_columns' ) );
			add_action( 'manage_' . $post_type . '_posts_custom_column', array( 'WPMultipleSubtitles_Admin', 'manage_subtitle_columns_content' ), 10, 2 );
			add_action( 'quick_edit_custom_box', array( 'WPMultipleSubtitles_Admin', 'quick_edit_custom_box' ), 10, 2 );
		}

	}

	/**
	 * Add subtitle input to quick edit.
	 *
	 * @since  0.9
	 *
	 * @uses  add_action( 'quick_edit_custom_box' )
	 *
	 * @param  string  $column_name  Column name.
	 * @param  string  $post_type 	 Post type
	 */
	public static function quick_edit_custom_box( $column_name, $post_type ) {

		if ( $column_name !== 'gi_wp_subtitles' ) {
			return;
		}

		wp_nonce_field( 'wp-subtitle', 'wps_noncename' );

		?>
		<fieldset class="inline-edit-col-left inline-edit-col-left-wps-subtitle">
			<div class="inline-edit-col column-<?php echo $column_name; ?>">
				<label>
					<span class="title"><?php esc_html_e( 'Subtitle', 'wp-subtitle' ); ?></span>
					<span class="input-text-wrap"><input type="text" name="gi_wp_subtitles" class="gi_wp_subtitles" value=""></span>
				</label>
			</div>
		</fieldset>
		<?php
	}

	/**
	 * Add subtitle admin column.
	 *
	 * @since  0.9
	 *
	 * @param   array  $columns  A columns
	 * @return  array            Updated columns.
	 */
	public static function manage_subtitle_columns( $columns ) {

		$new_columns = array();

		foreach ( $columns as $column => $value ) {
			$new_columns[ $column ] = $value;
			if ( 'title' == $column ) {
				$new_columns['gi_wp_subtitles'] = __( 'Subtitle', 'wp-subtitle' );
			}
		}

		return $new_columns;
	}

	/**
	 * Display subtitle column.
	 *
	 * @since  0.9
	 *
	 * @param  string  $column_name  Column name.
	 * @param  int     $post_id      Post ID
	 */
	public static function manage_subtitle_columns_content( $column_name, $post_id ) {

		if ( $column_name == 'gi_wp_subtitles' ) {

			$subtitle = new GI_WP_Subtitle( $post_id );
			echo '<span data-gi_wp_subtitles="' . esc_attr( $subtitle->get_subtitles() ) . '">' . esc_html( $subtitle->get_subtitles() ) . '</span>';

		}

	}

	/**
	 * Add Admin scripts.
	 *
	 * @since  0.9
	 * @internal
	 */
	public static function _add_admin_scripts( $hook ) {
//var_dump($hook);exit();
        if($hook=="post-new.php" || $hook=="post.php"){
            wp_enqueue_script( 'gi_wp_subtitles', plugins_url( 'js/main-admin.js', __FILE__ ), false, null, true );
        }
		if ( 'edit.php' != $hook ) {
			return;
		}

		wp_enqueue_script( 'gi_wp_subtitles', plugins_url( 'js/admin-edit.js', __FILE__ ), false, null, true );


	}

	/**
	 * Add Admin Styles
	 *
	 * @since  0.9
	 * @internal
	 */
	public static function _add_admin_styles() {
		?>
		<style>
		#subtitlediv.top {
			margin-top: 5px;
			margin-bottom: 15px;
			position: relative;
		}
		#subtitlediv.top #subtitlewrap {
			border: 0;
			padding: 0;
		}
		#subtitlediv.top #gi_wpsubtitle {
			background-color: #fff;
			font-size: 1.4em;
			line-height: 1em;
			margin: 0;
			outline: 0;
			padding: 3px 8px;
			width: 100%;
			height: 1.7em;
		}
		#subtitlediv.top #gi_wpsubtitle::-webkit-input-placeholder { padding-top: 3px; }
		#subtitlediv.top #gi_wpsubtitle:-moz-placeholder { padding-top: 3px; }
		#subtitlediv.top #gi_wpsubtitle::-moz-placeholder { padding-top: 3px; }
		#subtitlediv.top #gi_wpsubtitle:-ms-input-placeholder { padding-top: 3px; }
		#subtitlediv.top #subtitledescription {
			margin: 5px 10px 0 10px;
		}
		.quick-edit-row-post .inline-edit-col-left-wps-subtitle {
			clear: left;
		}
        .gi-add-more-container{
            float: right;
            margin-top: -30px;
        }
        .gi-input-container{
            width: 85%;
        }
            .gi-input-container{
                margin-top: 10px;
            }
		</style>
		<?php
	}

	/**
	 * Get Meta Box Title
	 *
	 * @since  0.9
	 *
	 * @uses  apply_filters( 'wps_meta_box_title' )
	 */
	public static function get_meta_box_title( $post_type ) {
		return apply_filters( 'wps_meta_box_title', __( 'Subtitle', 'wp-subtitle' ), $post_type );
	}

	/**
	 * Add Meta Boxes
	 *
	 * @since  0.9
	 * @internal
	 *
	 * @uses  WPSubtitle::get_supported_post_types()
	 * @uses  apply_filters( 'wps_meta_box_title' )
	 * @uses  WPMultipleSubtitles_Admin::_add_subtitle_meta_box()
	 */
	public static function _add_meta_boxes() {
		$post_types = WPSubtitle::get_supported_post_types();
		foreach ( $post_types as $post_type ) {
			add_meta_box( 'gi_wp_subtitles_panel',  self::get_meta_box_title( $post_type ), array( 'WPMultipleSubtitles_Admin', '_add_subtitle_meta_box' ), $post_type, 'normal', 'high' );
		}
	}

	/**
	 * Add Subtitle Meta Box
	 *
	 * @since  0.9
	 * @internal
	 *
	 * @uses  WPSubtitle::_get_post_meta()
	 * @uses  apply_filters( 'gi_wp_subtitles_field_description' )
	 */
	public static function _add_subtitle_meta_box() {

		global $post;

		$values = self::get_admin_subtitle_values( $post );

		echo '<input type="hidden" name="wps_noncename" id="wps_noncename" value="' . wp_create_nonce( 'wp-subtitle' ) . '" />';
		if(count($values)>0){
		    foreach ($values as $value) {
                echo '<input type="text" id="wpsubtitle" name="gi_wp_subtitles" value="' . esc_attr(htmlentities($value)) . '" autocomplete="off" placeholder="' . esc_attr(apply_filters('gi_wp_subtitles_field_placeholder', __('Enter subtitle here', 'wp-subtitle'))) . '" style="width:99%;" />';
            }
        } else {
            echo '<input type="text" id="wpsubtitle" name="gi_wp_subtitles" value="" autocomplete="off" placeholder="' . esc_attr( apply_filters( 'gi_wp_subtitles_field_placeholder', __( 'Enter subtitle here', 'wp-subtitle' ) ) ) . '" style="width:99%;" />';
        }
		echo apply_filters( 'gi_wp_subtitles_field_description', '', $post );
	}

	/**
	 * Add Subtitle Field
	 *
	 * @since  0.9
	 * @internal
	 *
	 * @uses  WPSubtitle::_get_post_meta()
	 * @uses  apply_filters( 'gi_wp_subtitles_field_description' )
	 */
	public static function _add_subtitle_fields() {

		global $post;

		$values = self::get_admin_subtitle_values( $post );

		echo '<input type="hidden" name="wps_noncename" id="wps_noncename" value="' . wp_create_nonce( 'wp-subtitle' ) . '" />';

		echo '<div id="subtitlediv" class="top gi-subtitles-container">';
		if(count($values)>0){
            foreach ($values as $value){
                self::_gi_add_subtitle_field($value);
            }
        } else {
            self::_gi_add_subtitle_field();
        }

		echo '<div class="gi-add-more-container"><button type="button" name="btn-gi-add-more-subtitle" id="btn-gi-add-more-subtitle" class="button button-primary button-large btn-gi-add-more-subtitle" > + </button></div>';

		// Description
		$description = apply_filters( 'gi_wp_subtitles_field_description', '', $post );
		if ( ! empty( $description ) ) {
			echo '<div id="subtitledescription">' . $description . '</div>';
		}
		echo '</div>';
	}

	public static function _gi_add_subtitle_field($value=''){
        echo '<div id="subtitlewrap" class="gi-input-container" >';
        echo '<input type="text" id="gi_wpsubtitle" name="gi_wp_subtitles[]" value="' . esc_attr( htmlentities( $value ) ) . '" autocomplete="off" placeholder="' . esc_attr( apply_filters( 'gi_wp_subtitles_field_placeholder', __( 'Enter subtitle here', 'wp-subtitle' ) ) ) . '" />';
        echo '</div>';
    }

	/**
	 * Get Admin Subtitle Value
	 *
	 * @since  0.9
	 * @internal
	 *
	 * @param   WP_Post  $post  Post object.
	 * @return  string          Subtitle value.
	 */
	private static function get_admin_subtitle_values( $post ) {

		$subtitle = new GI_WP_Subtitle( $post );

        $values = [];
        $values = $subtitle->get_raw_subtitles();

		// Default subtitle if adding new post
		if ( function_exists( 'get_current_screen' )  && count($values)>0 ) { // && empty( $value )
			$screen = get_current_screen();
			if ( isset( $screen->action ) && 'add' == $screen->action ) {
                $values[] = $subtitle->get_default_subtitle( $post );
			}
		}

		return $values;

	}

	/**
	 * Save Subtitle
	 *
	 * @since  0.9
	 * @internal
	 *
	 * @uses  WPSubtitle::get_supported_post_types()
	 *
	 * @param  int  $post_id  Post ID or object.
	 */
	public static function _save_post( $post_id ) {

		// Verify if this is an auto save routine. 
		// If it is our form has not been submitted, so we dont want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Verify nonce
		if ( ! self::_verify_posted_nonce( 'wps_noncename', 'wp-subtitle' ) ) {
			return;
		}

		// Check data and save
		if ( isset( $_POST['gi_wp_subtitles'] ) && count($_POST['gi_wp_subtitles'])>0 ) {

			$subtitle = new GI_WP_Subtitle( $post_id );

			$subtitles  = $_POST['gi_wp_subtitles'];
            delete_post_meta( $post_id, 'gi_wp_subtitles' );

            $meta_key   = 'gi_wp_subtitles';
			foreach ($subtitles as $subtitle)
            {
                $subtitle = trim($subtitle);
                if(strlen($subtitle)>0){
                    add_post_meta( $post_id, $meta_key, $subtitle);
                }

            }

//			if ( $subtitle->current_user_can_edit() ) {
//			//	$subtitle->update_subtitle( $_POST['gi_wp_subtitles'] );
//			}

		}

	}

	/**
	 * Verify Post Edit Capability
	 *
	 * @since        0.9
	 * @deprecated   0.9 Beta    Use GI_WP_Subtitle->current_user_can_edit() instead.
	 * @internal
	 *
	 * @param   int  $post_id  Post ID.
	 * @return  bool
	 */
	private static function _verify_post_edit_capability( $post_id ) {

		_deprecated_function( '_verify_post_edit_capability()', '2.7', 'GI_WP_Subtitle->current_user_can_edit()' );

		$subtitle = new GI_WP_Subtitle( $post_id );

		return $subtitle->current_user_can_edit();

	}

	/**
	 * Verify Posted Nonce
	 *
	 * @since  0.9
	 * @internal
	 *
	 * @param   string  $nonce   Posted nonce name.
	 * @param   string  $action  Nonce action.
	 * @return  bool
	 */
	private static function _verify_posted_nonce( $nonce, $action ) {
		if ( isset( $_POST[ $nonce ] ) && wp_verify_nonce( $_POST[ $nonce ], $action ) ) {
			return true;
		}
		return false;
	}

	/**
	 * edit_form_after_title Supported
	 *
	 * @since  0.9
	 *
	 * @param   string  $post_type  Post type.
	 * @return  bool
	 */
	private static function edit_form_after_title_supported( $post_type = '' ) {
		global $wp_version;

		if ( version_compare( $wp_version, '3.5', '<' ) ) {
			return false;
		}
		return ! apply_filters( 'gi_wp_subtitles_use_meta_box', false, $post_type );
	}

}
