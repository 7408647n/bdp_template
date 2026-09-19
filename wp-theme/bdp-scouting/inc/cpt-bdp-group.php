<?php
/**
 * "bdp_group" custom post type.
 *
 * Replaces the tt_address "Team"/directory rendering seen in
 * Resources/Private/Address/Templates/Address/List.html and
 * Resources/Private/Address/Partials/Team/ListItem.html. There is no
 * business logic to port (Classes/Domain/* are empty .gitkeep files in the
 * TYPO3 extension — tt_address itself owns the model), so this file only
 * registers the post type and its meta fields; the JS list/filter logic
 * lives in assets/src/js/entries/address.entry.ts.
 *
 * @package BdP_Scouting
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Meta fields kept on every bdp_group post, mirroring the tt_address
 * columns actually used by the ported templates (name/position/contact
 * details, address, geo-coordinates for the map display mode).
 *
 * @return array<string,array{type:string,single:bool}>
 */
function bdp_scouting_group_meta_fields() {
	return array(
		'bdp_group_position'  => array( 'type' => 'string', 'single' => true ),
		'bdp_group_street'    => array( 'type' => 'string', 'single' => true ),
		'bdp_group_zip'       => array( 'type' => 'string', 'single' => true ),
		'bdp_group_city'      => array( 'type' => 'string', 'single' => true ),
		'bdp_group_phone'     => array( 'type' => 'string', 'single' => true ),
		'bdp_group_mobile'    => array( 'type' => 'string', 'single' => true ),
		'bdp_group_fax'       => array( 'type' => 'string', 'single' => true ),
		'bdp_group_email'     => array( 'type' => 'string', 'single' => true ),
		'bdp_group_website'   => array( 'type' => 'string', 'single' => true ),
		'bdp_group_latitude'  => array( 'type' => 'string', 'single' => true ),
		'bdp_group_longitude' => array( 'type' => 'string', 'single' => true ),
	);
}

/**
 * Register the bdp_group post type.
 */
function bdp_scouting_register_group_cpt() {
	register_post_type( 'bdp_group', array(
		'labels'       => array(
			'name'          => __( 'Groups', 'bdp-scouting' ),
			'singular_name' => __( 'Group', 'bdp-scouting' ),
			'add_new_item'  => __( 'Add New Group', 'bdp-scouting' ),
			'edit_item'     => __( 'Edit Group', 'bdp-scouting' ),
			'all_items'     => __( 'Groups Directory', 'bdp-scouting' ),
		),
		'public'       => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-groups',
		'has_archive'  => true,
		'rewrite'      => array( 'slug' => 'gruppen' ),
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
	) );

	foreach ( bdp_scouting_group_meta_fields() as $key => $args ) {
		register_post_meta( 'bdp_group', $key, array(
			'type'              => $args['type'],
			'single'            => $args['single'],
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => function () {
				return current_user_can( 'edit_posts' );
			},
		) );
	}
}
add_action( 'init', 'bdp_scouting_register_group_cpt' );

/**
 * Register the meta box used to edit the group fields in the classic editor.
 */
function bdp_scouting_group_meta_box() {
	add_meta_box(
		'bdp_group_details',
		__( 'Group Details', 'bdp-scouting' ),
		'bdp_scouting_render_group_meta_box',
		'bdp_group',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'bdp_scouting_group_meta_box' );

/**
 * Render the group details meta box.
 *
 * @param WP_Post $post Current post.
 */
function bdp_scouting_render_group_meta_box( $post ) {
	wp_nonce_field( 'bdp_group_save', 'bdp_group_nonce' );

	$labels = array(
		'bdp_group_position'  => __( 'Position / Role', 'bdp-scouting' ),
		'bdp_group_street'    => __( 'Street', 'bdp-scouting' ),
		'bdp_group_zip'       => __( 'ZIP code', 'bdp-scouting' ),
		'bdp_group_city'      => __( 'City', 'bdp-scouting' ),
		'bdp_group_phone'     => __( 'Phone', 'bdp-scouting' ),
		'bdp_group_mobile'    => __( 'Mobile', 'bdp-scouting' ),
		'bdp_group_fax'       => __( 'Fax', 'bdp-scouting' ),
		'bdp_group_email'     => __( 'E-Mail', 'bdp-scouting' ),
		'bdp_group_website'   => __( 'Website', 'bdp-scouting' ),
		'bdp_group_latitude'  => __( 'Latitude', 'bdp-scouting' ),
		'bdp_group_longitude' => __( 'Longitude', 'bdp-scouting' ),
	);

	echo '<table class="form-table">';
	foreach ( $labels as $key => $label ) {
		$value = get_post_meta( $post->ID, $key, true );
		printf(
			'<tr><th><label for="%1$s">%2$s</label></th><td><input class="regular-text" type="text" id="%1$s" name="%1$s" value="%3$s"></td></tr>',
			esc_attr( $key ),
			esc_html( $label ),
			esc_attr( $value )
		);
	}
	echo '</table>';
}

/**
 * Persist the group details meta box.
 *
 * @param int $post_id Post ID.
 */
function bdp_scouting_save_group_meta( $post_id ) {
	if ( ! isset( $_POST['bdp_group_nonce'] ) || ! wp_verify_nonce( $_POST['bdp_group_nonce'], 'bdp_group_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( array_keys( bdp_scouting_group_meta_fields() ) as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
}
add_action( 'save_post_bdp_group', 'bdp_scouting_save_group_meta' );
