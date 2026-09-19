<?php
/**
 * The footer: contact block, social links, footer nav, "member of" line,
 * cookie banner and mobile off-canvas panel.
 *
 * Ported from Resources/Private/PageView/Partials/Footer.html and
 * Resources/Private/PageView/Partials/MobileMenuPanel.html.
 *
 * @package BdP_Scouting
 */

$infotype = bdp_scouting_get_infotype();
$has_group_logo = 'bund' !== $infotype && has_custom_logo();
$margin_mask    = $has_group_logo ? 'lg:-mt-56' : 'lg:-mt-30';
?>
</main>
<footer id="footer" class="pt-20 text-blue-500 dark:text-white forced-colors:border-t">
	<section class="relative z-1 text-base lg:min-h-45">
		<div class="3xl:px-25 relative mx-auto w-full px-3 md:px-5 lg:px-10 xl:px-20">
			<div class="grid grid-cols-1 grid-rows-2 md:grid-cols-2 lg:grid-cols-3">
				<div class="order-1 col-span-1 row-span-1 lg:row-span-2">
					<?php if ( $has_group_logo ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<?php echo bdp_scouting_inline_svg( 'brand/bundeszeichen_inline.svg', array( 'class' => 'h-16', 'width' => '238', 'height' => '64', 'aria-label' => __( 'Pfadfinden Logo', 'bdp-scouting' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php endif; ?>
				</div>
				<div class="order-2 col-span-1 row-span-1 pt-12 md:order-3 md:pt-4 lg:order-2 lg:row-span-2">
					<?php if ( bdp_scouting_contact( 'instagram' ) ) : ?>
						<a href="<?php echo esc_url( bdp_scouting_contact( 'instagram' ) ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-kluft dark:hover:text-yellow-light flex text-lg font-bold hover:underline md:ml-13.75 lg:ml-0">
							<?php echo bdp_scouting_inline_svg( 'sc/ig.svg', array( 'width' => '50', 'height' => '50', 'class' => 'mr-2', 'aria-hidden' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<span class="mt-2 block"><?php esc_html_e( 'Folge uns auf Instagram', 'bdp-scouting' ); ?></span>
						</a>
					<?php endif; ?>
					<?php if ( bdp_scouting_contact( 'youtube' ) ) : ?>
						<a href="<?php echo esc_url( bdp_scouting_contact( 'youtube' ) ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-kluft dark:hover:text-yellow-light mt-5 flex text-lg font-bold hover:underline md:ml-13.75 lg:ml-0">
							<?php echo bdp_scouting_inline_svg( 'sc/yt.svg', array( 'width' => '50', 'height' => '35', 'class' => 'mt-1.5 mr-2 block', 'aria-hidden' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<span class="mt-2 block"><?php esc_html_e( 'Folge uns auf YouTube', 'bdp-scouting' ); ?></span>
						</a>
					<?php endif; ?>
				</div>
				<div class="order-3 col-span-1 row-span-2 pt-10 pb-10 md:order-2 md:justify-self-end md:pt-2 lg:order-3 lg:pt-6 lg:pb-0">
					<?php if ( $has_group_logo ) : ?>
						<div class="pb-6 md:pb-14">
							<?php echo bdp_scouting_inline_svg( 'brand/bundeszeichen_inline.svg', array( 'class' => 'h-16', 'width' => '238', 'height' => '64', 'aria-label' => __( 'Pfadfinden Logo', 'bdp-scouting' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</div>
					<?php endif; ?>
					<div class="text-lg font-bold"><?php esc_html_e( 'Bund der Pfadfinder*innen e.V. (BdP)', 'bdp-scouting' ); ?></div>
					<div class="text-lg font-bold"><?php echo esc_html( bdp_scouting_contact( 'name' ) ); ?></div>
					<div>
						<?php echo esc_html( bdp_scouting_contact( 'street' ) . ' ' . bdp_scouting_contact( 'housenumber' ) ); ?>
						<br>
						<?php echo esc_html( bdp_scouting_contact( 'postcode' ) . ' ' . bdp_scouting_contact( 'city' ) ); ?>, DE
					</div>
					<?php if ( bdp_scouting_contact( 'phone' ) ) : ?>
						<div>
							<span><?php esc_html_e( 'Telefon:', 'bdp-scouting' ); ?> </span>
							<a href="tel:<?php echo esc_attr( bdp_scouting_contact( 'phone' ) ); ?>" class="hover:text-kluft dark:hover:text-yellow-light hover:underline"><?php echo esc_html( bdp_scouting_contact( 'phone' ) ); ?></a>
						</div>
					<?php endif; ?>
					<?php if ( bdp_scouting_contact( 'email' ) ) : ?>
						<div>
							<a href="mailto:<?php echo esc_attr( antispambot( bdp_scouting_contact( 'email' ) ) ); ?>" class="hover:text-kluft dark:hover:text-yellow-light hover:underline"><?php echo esc_html( antispambot( bdp_scouting_contact( 'email' ) ) ); ?></a>
						</div>
					<?php endif; ?>
					<?php if ( bdp_scouting_contact( 'website' ) ) : ?>
						<div>
							<a href="<?php echo esc_url( bdp_scouting_contact( 'website' ) ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-kluft dark:hover:text-yellow-light hover:underline"><?php echo esc_html( bdp_scouting_contact( 'website' ) ); ?></a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<section class="2x:aspect-1760/380 relative z-2 h-auto w-full bg-blue-500 text-yellow-500 <?php echo esc_attr( $margin_mask ); ?> lg:aspect-1760/464 xl:aspect-1760/380 dark:bg-black dark:text-white">
		<div class="3xl:px-25 relative mx-auto w-full px-3 md:px-5 lg:px-10 xl:px-20 pt-14 md:pt-18 lg:pt-30">
			<nav aria-label="<?php esc_attr_e( 'Footer', 'bdp-scouting' ); ?>">
				<?php bdp_scouting_footer_nav(); ?>
			</nav>
			<div class="mt-6 pb-4 text-sm font-semibold lg:mt-10 lg:text-sm xl:mt-16">
				<?php if ( 'bund' === $infotype ) : ?>
					<?php esc_html_e( 'Mitglied im', 'bdp-scouting' ); ?>
					<a href="https://pfadfinden-in-deutschland.de" target="_blank" rel="noopener noreferrer" class="hover:text-yellow-light hover:underline">
						Ring deutscher Pfadfinder*innenverbände (rdp)
					</a>
				<?php else : ?>
					<?php
					printf(
						/* translators: %s: infotype label (Landesverband, Projekt, Service, Stamm) */
						esc_html__( 'Ein %s im', 'bdp-scouting' ),
						esc_html( bdp_scouting_infotype_label( $infotype ) )
					);
					?>
					<a href="https://www.pfadfinden.de" target="_blank" rel="noopener noreferrer" class="hover:text-yellow-light hover:underline">
						Bund der Pfadfinder*innen e.V. (BdP)
					</a>
				<?php endif; ?>
			</div>
		</div>
	</section>
</footer>

<div id="mobile-sidebar" class="hidden transition-300 left-0 pointer-events-auto fixed top-27.5 right-0 z-1001 flex h-full w-full flex-row-reverse lg:hidden">
	<div id="mobile-main-menu-sidebar" class="pointer-events-auto flex h-full w-full max-w-[480px] flex-col border-r border-kluft bg-blue-500 text-white shadow-lg transition-all duration-300 sm:flex sm:w-[calc(14vw_+_16rem)] sm:max-w-[480px] dark:border-surface-700 dark:bg-black dark:text-white" role="complementary">
		<nav id="mobile-menu" class="block w-full lg:hidden" aria-label="<?php esc_attr_e( 'Main', 'bdp-scouting' ); ?>">
			<?php bdp_scouting_mobile_nav(); ?>
		</nav>
	</div>
</div>

<?php get_template_part( 'template-parts/cookie/banner' ); ?>

<?php wp_footer(); ?>
</body>
</html>
