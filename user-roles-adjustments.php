<?php
/**
 * Plugin Name: User Roles Adjustments
 * Description: Custom functionality for user editing in combination with the Members plugin. Retains user levels and limits user editing capabilities.
 * Author: required
 * Author URI: https://required.com
 */

namespace Required\UserRoles;

// See https://core.trac.wordpress.org/ticket/16841.
add_filter( 'members_remove_old_levels', '__return_false' );

/**
 * Filters a user's capabilities depending on specific context and/or privilege.
 *
 * Only administrators are allowed to edit, delete or switch to other administrators.
 *
 * @param array  $caps    Returns the user's actual capabilities.
 * @param string $cap     Capability name.
 * @param int    $user_id The user ID.
 * @param array  $args    Adds the context to the cap. Typically the object ID.
 * @return array The user's actual capabilities.
 */
function map_meta_cap( $caps, $cap, $user_id, $args ) {
	if ( ! isset( $args[0] ) ) {
		return $caps;
	}
	$edit_user_id = (int) $args[0];

	// Don't make changes to users own caps or if user can delete users.
	if ( $user_id === $edit_user_id || user_can( $user_id, 'delete_users' ) ) {
		return $caps;
	}

	// Multisite - do not allowing removing users who can remove users.
	if ( 'remove_user' === $cap ) {

		if ( user_can( $edit_user_id, 'remove_users' ) ) {
			$caps[] = 'do_not_allow';
		}
	}

	// Promoting, switching and editing.
	if ( 'promote_user' === $cap || 'switch_to_user' === $cap || 'edit_user' === $cap ) {

		if ( user_can( $edit_user_id, 'promote_users' ) ) {
			$caps[] = 'do_not_allow';
		}
	}

	// Remove default manage_* cap.
	if ( 'manage_privacy_options' === $cap ) {
		$manage_cap = is_multisite() ? 'manage_network' : 'manage_options';
		$caps       = array_diff( $caps, [ $manage_cap ] );

		// Require manage_privacy_options.
		$caps[] = $cap;
	}

	return $caps;
}
add_filter( 'map_meta_cap', __NAMESPACE__ . '\map_meta_cap', 10, 4 );

/**
 * Removes Administrator from roles list if user isn't an admin themselves.
 *
 * @param array $all_roles List of roles.
 * @return array Modified list of roles.
 */
function filter_editable_roles( $all_roles ) {
	if ( ! is_super_admin( get_current_user_id() ) ) {
		unset( $all_roles['administrator'], $all_roles['site_manager'] );
	}

	return $all_roles;
}
add_filter( 'editable_roles', __NAMESPACE__ . '\filter_editable_roles' );
