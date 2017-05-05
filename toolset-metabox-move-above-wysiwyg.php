<?php
/**
 * Plugin Name: Move Toolset Metabox Above WYSIWYG Editor
 * Plugin URI: http://salferrarello.com/move-toolset-metabox-above-wysiwyg-editor/
 * Description: Allows moving metaboxes created with the Toolset plugin above the WYSIWYG editor, with the filter <strong>fe_tmm_move_metabox_after_title_ids</strong>. Example: <code>add_filter( 'fe_tmm_move_metabox_after_title_ids', function ( $ids ) { $ids[] = 1813; return $group_ids; } );</code>
 *
 * Version: 0.1.0
 * Author: Sal Ferrarello
 * Author URI: http://salferrarello.com/
 * Text Domain: toolset-metabox-move-above-wysiwyg
 * Domain Path: /languages
 *
 * @package toolset-metabox-move-above-wysiwyg
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Example filter to modify which Toolset metaboxes to move after the title.
add_filter( 'fe_tmm_move_metabox_after_title_ids', function ( $group_ids ) {
	$group_ids[] = 1813;  // Move group id 1813 above the WYSIWYG editor.
	$group_ids[] = 1820;  // Move group id 1820 above the WYSIWYG editor.
	return $group_ids;
} );

add_action( 'edit_form_after_title', 'fe_tmm_move_metabox_after_title' );
/**
 * Move a Metabox created with the Toolset plugin after the title on the editor page.
 *
 * In other words, move the metabox above the WYSIWYG editor.
 *
 * @hook filter fe_tmm_move_metabox_after_title_ids An array of metabox Group IDs to move below the title.
 */
function fe_tmm_move_metabox_after_title() {
	global $post, $wp_meta_boxes;
	$group_ids = apply_filters( 'fe_tmm_move_metabox_after_title_ids', array() );

	$post_type = get_post_type( $post );
	// Contexts are: [ 'normal', 'advanced' ].
	$context = 'normal'; // Toolset Types uses a context of 'normal', from available context
	// Priorities are: [ 'default', 'high', 'low' ].
	$priority = 'high';  // Toolset Types uses a priority of 'high'.

	foreach ( $wp_meta_boxes[ $post_type ][ $context ][ $priority ] as $key => $metabox ) {
		if ( 'wpcf-group-' === substr( $key, 0, 11 ) && in_array( $wp_meta_boxes[ $post_type ][ $context ][ $priority ][ $key ]['args']['id'], $group_ids, true ) ) {
			$box = $wp_meta_boxes[ $post_type ][ $context ][ $priority ][ $key ];
			// Render the single metabox.
			fe_tmm_do_single_meta_box( $box, get_current_screen(), $post );
			// Remove the metabox we rendered for $wp_meta_boxes, to prevent rendering a second time.
			unset( $wp_meta_boxes[ $post_type ][ $context ][ $priority ][ $key ] );
		}
	}
}

/**
 * Display a Single Metabox
 *
 * This code is taken from the do_meta_boxes() function in
 * /wp-admin/includes/template.php
 *
 * @param array $box An associative array defining a single metabox.
 * @param string|WP_Screen $screen  Screen identifier
 * @param mixed $object  gets passed to the box callback function as first parameter.
 */
function fe_tmm_do_single_meta_box( $box, $screen, $object ) {
	$page = $screen->id;
	$hidden_class = '';
	echo '<div id="' . $box['id'] . '" class="postbox ' . postbox_classes($box['id'], $page) . $hidden_class . '" ' . '>' . "\n";
	if ( 'dashboard_browser_nag' != $box['id'] ) {
		$widget_title = $box[ 'title' ];

		if ( is_array( $box[ 'args' ] ) && isset( $box[ 'args' ][ '__widget_basename' ] ) ) {
			$widget_title = $box[ 'args' ][ '__widget_basename' ];
			// Do not pass this parameter to the user callback function.
			unset( $box[ 'args' ][ '__widget_basename' ] );
		}

		echo '<button type="button" class="handlediv button-link" aria-expanded="true">';
		echo '<span class="screen-reader-text">' . sprintf( __( 'Toggle panel: %s' ), $widget_title ) . '</span>';
		echo '<span class="toggle-indicator" aria-hidden="true"></span>';
		echo '</button>';
	}
	echo "<h2 class='hndle'><span>{$box['title']}</span></h2>\n";
	echo '<div class="inside">' . "\n";
	call_user_func($box['callback'], $object, $box);
	echo "</div>\n";
	echo "</div>\n";
}
