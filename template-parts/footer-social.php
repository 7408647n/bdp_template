<?php
/**
 * Ported from Resources/Private/Partials/Page/FooterSocial.html.
 * Social links come from the Customizer (Theme Options > Social Links),
 * mirroring the original's `settings.social.*` TypoScript constants.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}

$socials = array(
	'instagram' => array(
		'url'   => get_theme_mod( 'bdp_social_instagram', '' ),
		'icon'  => 'sc/ig.svg',
		'label' => 'Instagram',
	),
	'facebook'  => array(
		'url'   => get_theme_mod( 'bdp_social_facebook', '' ),
		'icon'  => 'sc/fb.svg',
		'label' => 'Facebook',
	),
	'youtube'   => array(
		'url'   => get_theme_mod( 'bdp_social_youtube', '' ),
		'icon'  => 'sc/yt.svg',
		'label' => 'YouTube',
	),
);
?>
<div class="page__footer__content__section__social">
	<?php foreach ( $socials as $key => $social ) : ?>
		<?php if ( ! empty( $social['url'] ) ) : ?>
			<div class="page__footer__content__section__social__item">
				<a
					href="<?php echo esc_url( $social['url'] ); ?>"
					target="_blank"
					rel="noopener"
					title="<?php echo esc_attr( $social['label'] ); ?>"
					class="page__footer__content__section__social__item__link"
				>
					<img
						src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/icons/' . $social['icon'] ); ?>"
						alt="<?php echo esc_attr( $social['label'] . ' Icon' ); ?>"
						class="page__footer__content__section__social__item__icon"
						loading="lazy"
						width="50"
						height="50"
					/>
				</a>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
