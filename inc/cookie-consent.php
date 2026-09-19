<?php
/**
 * Native cookie consent banner, replacing the TYPO3 extension
 * opfaff/om-cookie-manager (Resources/Private/Templates/Cookiemanager).
 * The panel markup and JS wiring (assets/src/ts/cookieconsent/om.ts) are
 * ported 1:1; this file supplies the group configuration that used to
 * live in the extension's backend records.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Returns the configured cookie groups. Filterable so a child theme or
 * mu-plugin can add groups (e.g. once a marketing pixel is added) without
 * touching this file.
 *
 * Each group's `assets` array may contain `header`/`body` HTML snippets
 * that get injected once the visitor opts in, matching the behaviour of
 * assets/src/ts/cookieconsent/om.ts's `omCookieEnableCookieGrp()`.
 *
 * @return array<string,array<string,mixed>>
 */
function bdp_cookie_groups(): array {
	$groups = array(
		'group-essential' => array(
			'name'      => __( 'Essential', 'bdp-template' ),
			'essential' => true,
			'assets'    => array(),
		),
		'group-statistics' => array(
			'name'      => __( 'Statistics', 'bdp-template' ),
			'essential' => false,
			'assets'    => array(),
		),
	);

	return apply_filters( 'bdp_cookie_groups', $groups );
}

/**
 * Renders the `<script id="om-cookie-consent">` JSON payload consumed by
 * assets/src/ts/cookieconsent/om.ts, plus the visible consent panel.
 * Enqueued via template-parts/cookie-banner.php in footer.php.
 */
function bdp_render_cookie_banner(): void {
	$groups = bdp_cookie_groups();
	?>
	<script type="application/json" id="om-cookie-consent">
		<?php echo wp_json_encode( $groups ); ?>
	</script>
	<div class="om-cookie-panel" data-omcookie-panel="1">
		<h3><?php echo esc_html__( 'Cookie settings', 'bdp-template' ); ?></h3>
		<div class="cookie-panel__selection">
			<form>
				<?php foreach ( $groups as $key => $group ) : ?>
					<div class="cookie-panel__checkbox-wrap">
						<input
							class="cookie-panel__checkbox<?php echo ! empty( $group['essential'] ) ? ' cookie-panel__checkbox--state-inactiv' : ''; ?>"
							data-omcookie-panel-grp="1"
							id="<?php echo esc_attr( $key ); ?>"
							type="checkbox"
							<?php if ( ! empty( $group['essential'] ) ) : ?>
								checked data-omcookie-panel-essential="1" disabled="disabled"
							<?php endif; ?>
							value="<?php echo esc_attr( $key ); ?>"
						/>
						<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $group['name'] ); ?></label>
					</div>
				<?php endforeach; ?>
			</form>
		</div>
		<div class="cookie-panel__description">
			<?php echo esc_html__( 'We use cookies to operate this site and, if you consent, to understand how it is used.', 'bdp-template' ); ?>
		</div>
		<div class="cookie-panel__control">
			<button data-omcookie-panel-save="all" class="cookie-panel__button cookie-panel__button--color--green"><?php echo esc_html__( 'Accept all', 'bdp-template' ); ?></button>
			<button data-omcookie-panel-save="min" class="cookie-panel__button"><?php echo esc_html__( 'Essential only', 'bdp-template' ); ?></button>
			<button data-omcookie-panel-save="save" class="cookie-panel__button"><?php echo esc_html__( 'Save selection', 'bdp-template' ); ?></button>
		</div>
		<div class="cookie-panel__link">
			<a href="<?php echo esc_url( get_privacy_policy_url() ); ?>"><?php echo esc_html__( 'Privacy policy', 'bdp-template' ); ?></a>
		</div>
	</div>
	<?php
}
