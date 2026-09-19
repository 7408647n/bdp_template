<?php
/**
 * Event detail. Ported from Templates/Calendarize/Calendar/Detail.html +
 * Partials/Calendarize/Event/Detail.html.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$fields = bdp_get_event_fields( get_the_ID() );
	$start  = $fields['start'] ? strtotime( $fields['start'] ) : null;
	$end    = $fields['end'] ? strtotime( $fields['end'] ) : null;
	?>
	<div class="bdp-cal__detail__event">
		<header class="header">
			<h1 class="ce-header"><?php the_title(); ?></h1>
		</header>
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="bdp-cal__detail__event__asset">
				<div class="bdp-cal__detail__event__asset__media">
					<?php the_post_thumbnail( 'large', array( 'class' => 'bdp-cal__detail__event__asset__media__image' ) ); ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="bdp-cal__detail__event__information">
			<div class="bdp-cal__detail__event__information__data">
				<div class="bdp-cal__detail__event__box">
					<?php if ( $fields['canceled'] ) : ?>
						<div class="bdp-cal__detail__event__information__data__section">
							<h3 class="bdp-cal__detail__event__information__data__section__heading__title bdp-cal__detail__event__information__data__section__heading__title--canceled">
								<?php echo esc_html__( 'Canceled', 'bdp-template' ); ?>
							</h3>
						</div>
					<?php endif; ?>
					<div class="bdp-cal__detail__event__information__data__section">
						<h3 class="bdp-cal__detail__event__information__data__section__heading__title"><?php echo esc_html__( 'Date', 'bdp-template' ); ?></h3>
						<div class="bdp-cal__detail__event__information__data__section__content">
							<strong>
								<?php
								if ( $start ) {
									echo esc_html( date_i18n( 'd.m.Y', $start ) );
									if ( $end && gmdate( 'd.m.Y', $end ) !== gmdate( 'd.m.Y', $start ) ) {
										echo ' - ' . esc_html( date_i18n( 'd.m.Y', $end ) );
									}
								}
								?>
							</strong>
						</div>
					</div>
					<div class="bdp-cal__detail__event__information__data__section">
						<h3 class="bdp-cal__detail__event__information__data__section__heading__title"><?php echo esc_html__( 'Time', 'bdp-template' ); ?></h3>
						<div class="bdp-cal__detail__event__information__data__section__content">
							<?php
							if ( $fields['all_day'] ) {
								echo esc_html__( 'All day', 'bdp-template' );
							} elseif ( $start ) {
								echo esc_html( date_i18n( 'H:i', $start ) );
								if ( $end ) {
									echo ' - ' . esc_html( date_i18n( 'H:i', $end ) );
								}
							}
							?>
						</div>
					</div>
					<?php if ( $fields['location'] ) : ?>
						<div class="bdp-cal__detail__event__information__data__section">
							<h3 class="bdp-cal__detail__event__information__data__section__heading__title"><?php echo esc_html__( 'Location', 'bdp-template' ); ?></h3>
							<div class="bdp-cal__detail__event__information__data__section__content"><?php echo esc_html( $fields['location'] ); ?></div>
						</div>
					<?php endif; ?>
					<?php if ( $fields['organizer'] ) : ?>
						<div class="bdp-cal__detail__event__information__data__section">
							<h3 class="bdp-cal__detail__event__information__data__section__heading__title"><?php echo esc_html__( 'Organizer', 'bdp-template' ); ?></h3>
							<div class="bdp-cal__detail__event__information__data__section__content"><?php echo esc_html( $fields['organizer'] ); ?></div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="bdp-cal__detail__event__information__content">
				<div class="bdp-cal__detail__event__box">
					<div class="bdp-cal__detail__event__box__content">
						<?php if ( has_excerpt() ) : ?>
							<div class="bdp-cal__detail__event__box__content__teaser"><?php echo wp_kses_post( get_the_excerpt() ); ?></div>
						<?php endif; ?>
						<div class="bdp-cal__detail__event__box__content__main"><?php the_content(); ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
endwhile;

get_footer();
