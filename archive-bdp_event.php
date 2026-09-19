<?php
/**
 * Event list. Ported from Templates/Calendarize/Calendar/List.html +
 * Partials/Calendarize/List.html and Event/ListItem.html.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}

get_header();
?>
<div class="bdp-cal__event-list">
	<?php if ( have_posts() ) : ?>
		<div class="bdp-cal__event-list__events">
			<?php
			$current_month = '';
			while ( have_posts() ) :
				the_post();
				$fields = bdp_get_event_fields( get_the_ID() );
				$start  = $fields['start'] ? strtotime( $fields['start'] ) : null;
				$end    = $fields['end'] ? strtotime( $fields['end'] ) : null;
				$month  = $start ? gmdate( 'Y.m', $start ) : '';

				if ( $month && $month !== $current_month ) :
					if ( '' !== $current_month ) {
						echo '</div><div class="bdp-cal__event-list__events__month">';
					} else {
						echo '<div class="bdp-cal__event-list__events__month">';
					}
					$current_month = $month;
					?>
					<h3 class="bdp-cal__event-list__events__month__title"><?php echo esc_html( $start ? date_i18n( 'F Y', $start ) : '' ); ?></h3>
					<?php
				endif;
				?>
				<div class="bdp-cal__event-list__events__event<?php echo $fields['canceled'] ? ' bdp-cal__event-list__events__event--state-canceled' : ''; ?>">
					<div class="bdp-cal__event-list__events__event__date">
						<?php
						if ( $start ) {
							echo esc_html( date_i18n( 'd.m.Y', $start ) );
							if ( $end && gmdate( 'd.m.Y', $end ) !== gmdate( 'd.m.Y', $start ) ) {
								echo ' - ' . esc_html( date_i18n( 'd.m.Y', $end ) );
							}
							if ( ! $fields['all_day'] ) {
								echo ' ' . esc_html( date_i18n( 'H:i', $start ) );
								if ( $end ) {
									echo ' - ' . esc_html( date_i18n( 'H:i', $end ) );
								}
							}
						}
						?>
					</div>
					<div class="bdp-cal__event-list__events__event__content">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</div>
				</div>
			<?php endwhile; ?>
			</div>
		</div>
		<?php get_template_part( 'template-parts/pagination' ); ?>
	<?php else : ?>
		<p><?php echo esc_html__( 'No events found.', 'bdp-template' ); ?></p>
	<?php endif; ?>
</div>
<?php
get_footer();
