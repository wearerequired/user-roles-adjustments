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
	// Editing.
	if ( 'edit_user' === $cap && isset( $args[0] ) ) {
		$edit_user_id = (int) $args[0];

		// Multisite - do not allowing editing site administrators.
		if ( $user_id !== $edit_user_id && user_can( $edit_user_id, 'remove_users' ) && ! user_can( $user_id, 'remove_users' ) ) {
			$caps[] = 'do_not_allow';
		}

		// Single site.
		if ( $user_id !== $edit_user_id && user_can( $edit_user_id, 'delete_users' ) && ! user_can( $user_id, 'delete_users' ) ) {
			$caps[] = 'do_not_allow';
		}
	}

	// Promoting and switching.
	if ( ( 'promote_user' === $cap || 'switch_to_user' === $cap ) && isset( $args[0] ) ) {
		$edit_user_id = (int) $args[0];

		if ( $user_id !== $edit_user_id && user_can( $edit_user_id, 'promote_users' ) && ! user_can( $user_id, 'delete_users' ) ) {
			$caps[] = 'do_not_allow';
		}
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
