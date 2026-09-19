<?php
/**
 * Custom post types replacing the TYPO3 extensions "calendarize" (events)
 * and "tt_address" (local group / Landesverband directory), which had no
 * business logic in the source repo beyond their Fluid presentation
 * templates. No recurring-event engine is implemented here, matching the
 * source: calendarize's own recurrence logic is not part of this repo.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Registers the bdp_event post type (Resources/Private/Templates/Calendarize).
 */
function bdp_register_event_post_type(): void {
	register_post_type(
		'bdp_event',
		array(
			'labels'       => array(
				'name'               => __( 'Events', 'bdp-template' ),
				'singular_name'      => __( 'Event', 'bdp-template' ),
				'add_new_item'       => __( 'Add New Event', 'bdp-template' ),
				'edit_item'          => __( 'Edit Event', 'bdp-template' ),
				'all_items'          => __( 'Events', 'bdp-template' ),
				'search_items'       => __( 'Search Events', 'bdp-template' ),
				'not_found'          => __( 'No events found', 'bdp-template' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-calendar-alt',
			'rewrite'      => array( 'slug' => 'events' ),
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
		)
	);

	$meta_fields = array(
		'bdp_event_start'    => 'string', // ISO 8601 datetime.
		'bdp_event_end'      => 'string', // ISO 8601 datetime.
		'bdp_event_all_day'  => 'boolean',
		'bdp_event_location' => 'string',
		'bdp_event_canceled' => 'boolean',
		'bdp_event_organizer'=> 'string',
	);

	foreach ( $meta_fields as $key => $type ) {
		register_post_meta(
			'bdp_event',
			$key,
			array(
				'type'         => $type,
				'single'       => true,
				'show_in_rest' => true,
			)
		);
	}
}
add_action( 'init', 'bdp_register_event_post_type' );

/**
 * Registers the bdp_group post type (Resources/Private/Templates/Address),
 * used for both the local-group "list" mode and the "lv" (Landesverband)
 * directory mode of the original address partials.
 */
function bdp_register_group_post_type(): void {
	register_post_type(
		'bdp_group',
		array(
			'labels'       => array(
				'name'          => __( 'Groups', 'bdp-template' ),
				'singular_name' => __( 'Group', 'bdp-template' ),
				'add_new_item'  => __( 'Add New Group', 'bdp-template' ),
				'edit_item'     => __( 'Edit Group', 'bdp-template' ),
				'all_items'     => __( 'Groups', 'bdp-template' ),
				'search_items'  => __( 'Search Groups', 'bdp-template' ),
				'not_found'     => __( 'No groups found', 'bdp-template' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-groups',
			'rewrite'      => array( 'slug' => 'groups' ),
			'supports'     => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		)
	);

	$meta_fields = array(
		'bdp_group_street'      => 'string',
		'bdp_group_zip'         => 'string',
		'bdp_group_city'        => 'string',
		'bdp_group_organisation'=> 'string',
		'bdp_group_email'       => 'string',
		'bdp_group_phone'       => 'string',
		'bdp_group_fax'         => 'string',
		'bdp_group_website'     => 'string',
		'bdp_group_lat'         => 'number',
		'bdp_group_lng'         => 'number',
	);

	foreach ( $meta_fields as $key => $type ) {
		register_post_meta(
			'bdp_group',
			$key,
			array(
				'type'         => $type,
				'single'       => true,
				'show_in_rest' => true,
			)
		);
	}
}
add_action( 'init', 'bdp_register_group_post_type' );

/**
 * Convenience accessor mirroring the Fluid partials' `address.*` fields.
 *
 * @param int|WP_Post $post Group post or ID.
 * @return array<string,mixed>
 */
function bdp_get_group_fields( $post ): array {
	$post_id = is_object( $post ) ? $post->ID : $post;

	return array(
		'street'       => get_post_meta( $post_id, 'bdp_group_street', true ),
		'zip'          => get_post_meta( $post_id, 'bdp_group_zip', true ),
		'city'         => get_post_meta( $post_id, 'bdp_group_city', true ),
		'organisation' => get_post_meta( $post_id, 'bdp_group_organisation', true ),
		'email'        => get_post_meta( $post_id, 'bdp_group_email', true ),
		'phone'        => get_post_meta( $post_id, 'bdp_group_phone', true ),
		'fax'          => get_post_meta( $post_id, 'bdp_group_fax', true ),
		'website'      => get_post_meta( $post_id, 'bdp_group_website', true ),
		'lat'          => get_post_meta( $post_id, 'bdp_group_lat', true ),
		'lng'          => get_post_meta( $post_id, 'bdp_group_lng', true ),
	);
}

/**
 * Convenience accessor mirroring the Fluid partials' `index.*` fields.
 *
 * @param int|WP_Post $post Event post or ID.
 * @return array<string,mixed>
 */
function bdp_get_event_fields( $post ): array {
	$post_id = is_object( $post ) ? $post->ID : $post;

	return array(
		'start'     => get_post_meta( $post_id, 'bdp_event_start', true ),
		'end'       => get_post_meta( $post_id, 'bdp_event_end', true ),
		'all_day'   => (bool) get_post_meta( $post_id, 'bdp_event_all_day', true ),
		'location'  => get_post_meta( $post_id, 'bdp_event_location', true ),
		'canceled'  => (bool) get_post_meta( $post_id, 'bdp_event_canceled', true ),
		'organizer' => get_post_meta( $post_id, 'bdp_event_organizer', true ),
	);
}
