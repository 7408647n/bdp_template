<?php
/**
 * Minimal native Instagram feed fetcher, replacing the TYPO3
 * "InstagramBusiness" extension (Resources/Private/Templates/InstagramBusiness).
 * No OAuth flow is implemented (none existed in the source repo either) -
 * the admin pastes a long-lived Graph API access token in
 * Settings > BdP Instagram Feed, and this file fetches + caches the feed.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}

const BDP_INSTAGRAM_OPTION_GROUP = 'bdp_instagram';
const BDP_INSTAGRAM_TOKEN_OPTION = 'bdp_instagram_access_token';
const BDP_INSTAGRAM_TRANSIENT    = 'bdp_instagram_feed';
const BDP_INSTAGRAM_CACHE_TTL    = HOUR_IN_SECONDS;

/**
 * Registers the "Settings > BdP Instagram Feed" options page holding the
 * long-lived Graph API access token.
 */
function bdp_instagram_register_settings(): void {
	register_setting(
		BDP_INSTAGRAM_OPTION_GROUP,
		BDP_INSTAGRAM_TOKEN_OPTION,
		array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => '',
		)
	);

	add_settings_section(
		'bdp_instagram_main',
		__( 'Instagram Feed', 'bdp-template' ),
		static function (): void {
			echo '<p>' . esc_html__(
				'Paste a long-lived Instagram Graph API access token here. See README.md for how to obtain one, or install the "Instagram Feed" plugin instead if you would rather not manage a token manually.',
				'bdp-template'
			) . '</p>';
		},
		BDP_INSTAGRAM_OPTION_GROUP
	);

	add_settings_field(
		BDP_INSTAGRAM_TOKEN_OPTION,
		__( 'Access Token', 'bdp-template' ),
		static function (): void {
			printf(
				'<input type="text" class="regular-text" name="%1$s" value="%2$s" autocomplete="off" />',
				esc_attr( BDP_INSTAGRAM_TOKEN_OPTION ),
				esc_attr( get_option( BDP_INSTAGRAM_TOKEN_OPTION, '' ) )
			);
		},
		BDP_INSTAGRAM_OPTION_GROUP,
		'bdp_instagram_main'
	);
}
add_action( 'admin_init', 'bdp_instagram_register_settings' );

/**
 * Adds the settings page under Settings.
 */
function bdp_instagram_add_settings_page(): void {
	add_options_page(
		__( 'BdP Instagram Feed', 'bdp-template' ),
		__( 'BdP Instagram Feed', 'bdp-template' ),
		'manage_options',
		'bdp-instagram-feed',
		'bdp_instagram_render_settings_page'
	);
}
add_action( 'admin_menu', 'bdp_instagram_add_settings_page' );

/**
 * Renders the settings page.
 */
function bdp_instagram_render_settings_page(): void {
	if (! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'BdP Instagram Feed', 'bdp-template' ); ?></h1>
		<form method="post" action="options.php">
			<?php
			settings_fields( BDP_INSTAGRAM_OPTION_GROUP );
			do_settings_sections( BDP_INSTAGRAM_OPTION_GROUP );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Returns the configured access token, or an empty string when unset.
 */
function bdp_instagram_get_access_token(): string {
	return (string) get_option( BDP_INSTAGRAM_TOKEN_OPTION, '' );
}

/**
 * Fetches the visitor-facing Instagram post list, mirroring the fields the
 * original Partials/InstagramBusiness/Post/* templates rendered
 * (mediaType, caption, link, and media urls). Cached in a transient so the
 * Graph API is not called on every page view.
 *
 * @param int $limit Maximum number of posts to return.
 * @return array<int,array<string,mixed>> Empty array on any failure.
 */
function bdp_instagram_get_feed( int $limit = 12 ): array {
	$cached = get_transient( BDP_INSTAGRAM_TRANSIENT );
	if ( false !== $cached ) {
		return $cached;
	}

	$token = bdp_instagram_get_access_token();
	if ( '' === $token ) {
		return array();
	}

	$fields   = 'id,caption,media_type,media_url,thumbnail_url,permalink,timestamp';
	$endpoint = add_query_arg(
		array(
			'fields'       => $fields,
			'access_token' => $token,
			'limit'        => $limit,
		),
		'https://graph.instagram.com/me/media'
	);

	$response = wp_remote_get( $endpoint, array( 'timeout' => 10 ) );

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		// Cache the empty result briefly so a failing token doesn't hammer the API.
		set_transient( BDP_INSTAGRAM_TRANSIENT, array(), 5 * MINUTE_IN_SECONDS );
		return array();
	}

	$body = json_decode( wp_remote_retrieve_body( $response ), true );
	$posts = isset( $body['data'] ) && is_array( $body['data'] ) ? $body['data'] : array();

	$normalized = array_map(
		static function ( array $post ): array {
			return array(
				'id'        => $post['id'] ?? '',
				'mediaType' => $post['media_type'] ?? 'IMAGE',
				'caption'   => $post['caption'] ?? '',
				'link'      => $post['permalink'] ?? '',
				'mediaUrl'  => $post['media_url'] ?? '',
				'thumbUrl'  => $post['thumbnail_url'] ?? ( $post['media_url'] ?? '' ),
			);
		},
		$posts
	);

	set_transient( BDP_INSTAGRAM_TRANSIENT, $normalized, BDP_INSTAGRAM_CACHE_TTL );

	return $normalized;
}
