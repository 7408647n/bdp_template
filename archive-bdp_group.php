<?php
/**
 * Group / Landesverband directory list. Ported from
 * Templates/Address/Address/List.html + Partials/Address/Mode_list.html
 * and List/ListItem.html.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}

get_header();
?>
<div class="ce-address__list">
	<div class="ce-address__list__filter">
		<label for="bdp-group-filter"><?php echo esc_html__( 'Filter groups', 'bdp-template' ); ?></label>
		<input type="search" id="bdp-group-filter" placeholder="<?php echo esc_attr__( 'Name or city…', 'bdp-template' ); ?>" />
	</div>
	<?php if ( have_posts() ) : ?>
		<div class="ce-address__list__items">
			<?php
			while ( have_posts() ) :
				the_post();
				$fields = bdp_get_group_fields( get_the_ID() );
				?>
				<div class="ce-address__list__items__item" data-group-name="<?php echo esc_attr( get_the_title() ); ?>" data-group-city="<?php echo esc_attr( $fields['city'] ); ?>">
					<div class="ce-address__list__items__item__box">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="ce-address__list__items__item__box__picture">
								<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'ce-address__list__items__item__box__picture__image' ) ); ?>
							</div>
						<?php endif; ?>
						<div class="ce-address__list__items__item__box__content">
							<h3 class="ce-address__list__items__item__box__content__name"><?php the_title(); ?></h3>
							<ul class="ce-address__list__items__item__box__content__info">
								<?php if ( $fields['email'] ) : ?>
									<li><a href="mailto:<?php echo esc_attr( $fields['email'] ); ?>"><?php echo esc_html( $fields['email'] ); ?></a></li>
								<?php endif; ?>
								<?php if ( $fields['phone'] ) : ?>
									<li><?php echo esc_html__( 'Phone', 'bdp-template' ); ?>: <a href="tel:<?php echo esc_attr( $fields['phone'] ); ?>"><?php echo esc_html( $fields['phone'] ); ?></a></li>
								<?php endif; ?>
							</ul>
							<div class="ce-address__list__items__item__box__content__description">
								<?php the_excerpt(); ?>
							</div>
						</div>
					</div>
				</div>
			<?php endwhile; ?>
		</div>
		<?php get_template_part( 'template-parts/pagination' ); ?>
	<?php else : ?>
		<p><?php echo esc_html__( 'No groups found.', 'bdp-template' ); ?></p>
	<?php endif; ?>
</div>
<?php
get_footer();
