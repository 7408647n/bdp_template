<?php
/**
 * Page shell footer. Ported from Resources/Private/Partials/Page/Footer.html
 * and FooterSocial.html.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
		</section>
	</main>
	<footer class="page__footer">
		<div class="page__footer__title"><?php bloginfo( 'name' ); ?></div>
		<div class="page__footer__content">
			<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
				<div class="page__footer__content__section page__footer__content__section--widgets">
					<?php dynamic_sidebar( 'footer-1' ); ?>
				</div>
			<?php endif; ?>
			<div class="page__footer__content__section page__footer__content__section--nav">
				<nav aria-label="<?php echo esc_attr__( 'Footer', 'bdp-template' ); ?>" class="page__footer__content__section__nav">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'menu_class'     => 'page__footer__content__section__nav__list',
							'fallback_cb'    => false,
						)
					);
					?>
				</nav>
			</div>
			<div class="page__footer__content__section page__footer__content__section--social">
				<?php get_template_part( 'template-parts/footer-social' ); ?>
			</div>
		</div>
	</footer>
</div>
<?php get_template_part( 'template-parts/cookie-banner' ); ?>
<?php wp_footer(); ?>
</body>
</html>
