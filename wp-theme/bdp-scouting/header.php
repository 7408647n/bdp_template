<?php
/**
 * The header: top meta bar + main navigation.
 *
 * Ported from Resources/Private/PageView/Layouts/Default.html and
 * Resources/Private/PageView/Partials/Header.html.
 *
 * @package BdP_Scouting
 */

$infotype = bdp_scouting_get_infotype();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'flex min-h-dvh flex-col text-blue-500 dark:bg-kohte font-medium dark:text-white' ); ?>>
<?php wp_body_open(); ?>
<header class="relative box-border lg:static">
	<div class="box-border flex h-7.5 w-full flex-nowrap justify-between bg-blue-500 text-xs leading-7.5 font-medium text-yellow-500 dark:bg-black dark:text-white">
		<div class="3xl:pl-23 pl-3 md:pl-3 lg:pl-8 xl:pl-18">
			<?php bdp_scouting_meta_nav(); ?>
		</div>
		<div>
			<ul>
				<li class="hover:bg-yellow-light dark:hover:bg-kluft h-7.5 w-full max-w-103.5 sm:w-[21.5234375vw] bg-yellow-500 text-blue-500 duration-150 ease-in-out dark:bg-blue-500 dark:text-white">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="uppercase block w-full pr-2 pl-20 text-center hover:text-blue-500 dark:hover:text-white overflow-hidden text-clip">
						<?php echo esc_html( bdp_scouting_uri_host( home_url( '/' ) ) ); ?>
					</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="3xl:pl-25 relative mx-auto flex w-full px-3 text-blue-500 md:px-5 md:py-0 lg:px-10 xl:pl-20 bg-white dark:bg-kohte dark:text-white forced-colors:border-y">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="focus-visible:outline-kluft z-1 mr-0 block h-20 flex-none py-3 focus-visible:outline-solid lg:mr-6 dark:focus-visible:outline-white">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<?php echo bdp_scouting_inline_svg( 'brand/bundeszeichen_inline.svg', array(
					'class'       => 'h-14',
					'width'       => '208',
					'height'      => '56',
					'aria-hidden' => false,
					'aria-label'  => __( 'Logo des Bund der Pfadfinder*innen e.V.', 'bdp-scouting' ),
				) ); // phpcs:ignore WordPress.Security.EscapeOutput -- sanitized in bdp_scouting_inline_svg(). ?>
			<?php endif; ?>
		</a>
		<div class="justify-end flex w-full lg:justify-between">
			<nav id="main-menu" class="mx-4 hidden w-full lg:block" aria-label="<?php esc_attr_e( 'Main', 'bdp-scouting' ); ?>">
				<?php bdp_scouting_main_nav(); ?>
			</nav>
			<button
				id="mobile-main-menu-toggle"
				type="button"
				aria-controls="mobile-sidebar"
				aria-haspopup="true"
				class="block cursor-pointer lg:hidden"
				aria-expanded="false"
				aria-label="<?php esc_attr_e( 'Menu', 'bdp-scouting' ); ?>">
				<span aria-hidden="true">
					<?php echo bdp_scouting_inline_svg( 'menu.svg', array( 'width' => '50', 'height' => '50', 'class' => 'mt-1.5 mr-2 block', 'aria-hidden' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</span>
			</button>
		</div>
	</div>
</header>
<main>
