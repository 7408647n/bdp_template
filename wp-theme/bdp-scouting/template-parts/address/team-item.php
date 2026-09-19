<?php
/**
 * A single entry in the group/team directory.
 *
 * Ported from Resources/Private/Address/Partials/Team/ListItem.html.
 *
 * @package BdP_Scouting
 */

$position = get_post_meta( get_the_ID(), 'bdp_group_position', true );
$email    = get_post_meta( get_the_ID(), 'bdp_group_email', true );
$phone    = get_post_meta( get_the_ID(), 'bdp_group_phone', true );
$mobile   = get_post_meta( get_the_ID(), 'bdp_group_mobile', true );
$fax      = get_post_meta( get_the_ID(), 'bdp_group_fax', true );
?>
<div class="w-full lg:w-1/2 px-3 xl:px-6 mt-6 xl:mt-12">
	<div class="flex flex-wrap items-stretch bg-blue-50 dark:bg-kohte">
		<div class="relative w-full min-h-[300px] md:w-1/3 lg:w-5/12 2xl:min-h-[350px] md:order-1">
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="block absolute left-0 top-0 bottom-0 right-0">
					<?php the_post_thumbnail( 'medium_large', array( 'class' => 'block relative w-full h-full object-cover', 'loading' => 'lazy' ) ); ?>
				</figure>
			<?php endif; ?>
		</div>
		<div class="flex relative py-8 px-[5%] min-h-[1px] w-full flex-col justify-between md:w-2/3 lg:w-7/12 md:order-0">
			<div class="overflow-hidden">
				<h2 class="text-2xl font-semibold"><?php the_title(); ?></h2>
				<?php if ( $position ) : ?>
					<div class="font-semibold text-lg mt-3"><?php echo esc_html( $position ); ?></div>
				<?php endif; ?>
				<ul class="mt-5">
					<?php if ( $email ) : ?>
						<li class="mb-2"><a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>" class="text-blue-500 dark:text-yellow-light hover:underline"><?php echo esc_html( antispambot( $email ) ); ?></a></li>
					<?php endif; ?>
					<?php if ( $phone ) : ?>
						<li><?php esc_html_e( 'Telephone', 'bdp-scouting' ); ?>: <a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone ) ); ?>" class="hover:underline"><?php echo esc_html( $phone ); ?></a></li>
					<?php endif; ?>
					<?php if ( $mobile ) : ?>
						<li><?php esc_html_e( 'Mobile', 'bdp-scouting' ); ?>: <a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $mobile ) ); ?>" class="hover:underline"><?php echo esc_html( $mobile ); ?></a></li>
					<?php endif; ?>
					<?php if ( $fax ) : ?>
						<li><?php esc_html_e( 'Fax', 'bdp-scouting' ); ?>: <?php echo esc_html( $fax ); ?></li>
					<?php endif; ?>
				</ul>
				<?php if ( get_the_excerpt() ) : ?>
					<div class="description pt-4"><?php the_excerpt(); ?></div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
