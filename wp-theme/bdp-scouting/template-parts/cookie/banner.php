<?php
/**
 * Cookie consent banner markup.
 *
 * See inc/cookie-consent.php for why this is a from-scratch design (the
 * TYPO3 branch's own CookieManager template is an empty stub). Behaviour
 * lives in assets/src/js/entries/cookie-consent.entry.ts.
 *
 * @package BdP_Scouting
 */

$categories = bdp_scouting_cookie_categories();
?>
<div id="bdp-cookie-banner" class="fixed inset-x-0 bottom-0 z-1002 hidden p-3 md:p-6" role="dialog" aria-modal="true" aria-labelledby="bdp-cookie-title" hidden>
	<div class="mx-auto max-w-3xl rounded-lg bg-blue-500 p-6 text-white shadow-lg dark:bg-black">
		<h2 id="bdp-cookie-title" class="text-xl font-bold"><?php esc_html_e( 'Cookie settings', 'bdp-scouting' ); ?></h2>
		<p class="mt-2 text-sm">
			<?php esc_html_e( 'We use cookies to operate this website and, if you agree, to understand how it is used and to embed content like our Instagram feed.', 'bdp-scouting' ); ?>
		</p>
		<div id="bdp-cookie-categories" class="mt-4 hidden space-y-3" hidden>
			<?php foreach ( $categories as $key => $category ) : ?>
				<label class="flex items-start gap-3">
					<input
						type="checkbox"
						class="mt-1"
						data-bdp-cookie-category="<?php echo esc_attr( $key ); ?>"
						<?php checked( true, $category['locked'] ); ?>
						<?php disabled( true, $category['locked'] ); ?>
					>
					<span>
						<span class="block font-semibold"><?php echo esc_html( $category['label'] ); ?></span>
						<span class="block text-sm text-blue-light"><?php echo esc_html( $category['description'] ); ?></span>
					</span>
				</label>
			<?php endforeach; ?>
		</div>
		<div class="mt-4 flex flex-wrap gap-3">
			<button type="button" data-bdp-cookie-accept-all class="rounded bg-yellow-500 px-4 py-2 font-semibold text-blue-500 hover:bg-yellow-light">
				<?php esc_html_e( 'Accept all', 'bdp-scouting' ); ?>
			</button>
			<button type="button" data-bdp-cookie-accept-none class="rounded border border-white px-4 py-2 font-semibold text-white hover:bg-white/10">
				<?php esc_html_e( 'Necessary only', 'bdp-scouting' ); ?>
			</button>
			<button type="button" data-bdp-cookie-settings aria-controls="bdp-cookie-categories" aria-expanded="false" class="rounded px-4 py-2 font-semibold text-white underline hover:no-underline">
				<?php esc_html_e( 'Settings', 'bdp-scouting' ); ?>
			</button>
			<button type="button" data-bdp-cookie-save hidden class="rounded bg-yellow-500 px-4 py-2 font-semibold text-blue-500 hover:bg-yellow-light">
				<?php esc_html_e( 'Save selection', 'bdp-scouting' ); ?>
			</button>
		</div>
	</div>
</div>
