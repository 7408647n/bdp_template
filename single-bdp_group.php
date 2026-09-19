<?php
/**
 * Single group / Landesverband detail. Ported from Partials/Address/Lv/ListItem.html.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$fields = bdp_get_group_fields( get_the_ID() );
	?>
	<div class="ce-address__lv__items__item">
		<div class="ce-address__lv__items__item__box">
			<div class="ce-address__lv__items__item__box__headline">
				<h1 class="ce-address__lv__items__item__box__headline__title"><?php the_title(); ?></h1>
			</div>
			<?php if ( $fields['street'] || $fields['city'] ) : ?>
				<address class="ce-address__lv__items__item__box__address">
					<?php if ( $fields['organisation'] ) : ?>
						<div><?php echo esc_html( $fields['organisation'] ); ?></div>
					<?php endif; ?>
					<div><?php echo nl2br( esc_html( $fields['street'] ) ); ?></div>
					<div><?php echo esc_html( $fields['zip'] . ' ' . $fields['city'] ); ?></div>
				</address>
			<?php endif; ?>
			<?php if ( $fields['phone'] || $fields['fax'] ) : ?>
				<ul class="ce-address__lv__items__item__box__numbers">
					<?php if ( $fields['phone'] ) : ?>
						<li><?php echo esc_html__( 'Phone', 'bdp-template' ); ?>: <a href="tel:<?php echo esc_attr( $fields['phone'] ); ?>"><?php echo esc_html( $fields['phone'] ); ?></a></li>
					<?php endif; ?>
					<?php if ( $fields['fax'] ) : ?>
						<li><?php echo esc_html__( 'Fax', 'bdp-template' ); ?>: <?php echo esc_html( $fields['fax'] ); ?></li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
			<?php if ( $fields['email'] || $fields['website'] ) : ?>
				<ul class="ce-address__lv__items__item__box__internet">
					<?php if ( $fields['email'] ) : ?>
						<li>E-Mail: <a href="mailto:<?php echo esc_attr( $fields['email'] ); ?>"><?php echo esc_html( $fields['email'] ); ?></a></li>
					<?php endif; ?>
					<?php if ( $fields['website'] ) : ?>
						<li><?php echo esc_html__( 'Website', 'bdp-template' ); ?>: <a href="<?php echo esc_url( $fields['website'] ); ?>"><?php echo esc_html( $fields['website'] ); ?></a></li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
			<div class="ce-address__lv__items__item__box__description">
				<?php the_content(); ?>
			</div>
		</div>
	</div>
	<?php
endwhile;

get_footer();
