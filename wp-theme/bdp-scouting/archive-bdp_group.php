<?php
/**
 * Group / team directory.
 *
 * Ported from Resources/Private/Address/Templates/Address/List.html
 * ("displayMode_team" section, the only mode with real markup in this repo;
 * "list"/"single"/"map" were data-shaped placeholders around tt_address
 * that carried no bespoke design here).
 *
 * @package BdP_Scouting
 */

get_header();
?>
<div class="3xl:px-25 relative mx-auto w-full px-3 py-10 md:px-5 lg:px-10 xl:px-20">
	<h1 class="text-4xl font-bold md:text-5xl mb-8"><?php post_type_archive_title(); ?></h1>
	<div id="bdp-group-list" class="flex flex-wrap -mx-3 xl:-mx-6">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/address/team-item' );
			endwhile;
		else :
			?>
			<p><?php esc_html_e( 'No groups found.', 'bdp-scouting' ); ?></p>
			<?php
		endif;
		?>
	</div>
	<?php the_posts_pagination(); ?>
</div>
<?php
get_footer();
