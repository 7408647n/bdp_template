<?php
/**
 * Page shell header. Ported from Resources/Private/Layouts/Page/Default.html
 * and Resources/Private/Partials/Frontend/Header/*.html +
 * Resources/Private/Partials/Page/MainNavigation.html.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="page">
	<header class="page__header">
		<div class="mainheader">
			<div class="mainheader__branding">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mainheader__branding__link" rel="home">
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<span class="mainheader__branding__title"><?php bloginfo( 'name' ); ?></span>
					<?php endif; ?>
				</a>
			</div>
			<button
				id="mobile-bdp-menu-toggler"
				type="button"
				class="mobile-bdp-menu-toggler"
				aria-expanded="false"
				data-open="<?php echo esc_attr__( 'Open menu', 'bdp-template' ); ?>"
				data-close="<?php echo esc_attr__( 'Close menu', 'bdp-template' ); ?>"
				aria-label="<?php echo esc_attr__( 'Open menu', 'bdp-template' ); ?>"
			>
				<span class="mobile-bdp-menu-toggler__bar"></span>
				<span class="mobile-bdp-menu-toggler__bar"></span>
				<span class="mobile-bdp-menu-toggler__bar"></span>
			</button>
		</div>
	</header>
	<section class="page__navigation">
		<nav id="pfadfinden-menu" class="mainheader__navigation" aria-label="<?php echo esc_attr__( 'Main Navigation', 'bdp-template' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'mainheader__navigation__list',
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
		<?php if ( has_nav_menu( 'side' ) ) : ?>
			<nav class="page__navigation__side" aria-label="<?php echo esc_attr__( 'Side Navigation', 'bdp-template' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'side',
						'container'      => false,
						'menu_class'     => 'side-navigation__list',
						'fallback_cb'    => false,
					)
				);
				?>
			</nav>
		<?php endif; ?>
	</section>
	<main class="page__content">
		<section class="page__content__main container-bdp">
