<?php
/**
 * Port of Classes/ViewHelpers/PipeBreakViewHelper.php.
 *
 * The original Fluid ViewHelper split a string on the pipe character "|"
 * (used to author a two-line heading such as "Landesverband | Hessen" in
 * a single backend field) and rendered each part as its own <span>.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Splits a string on "|" into its parts, optionally trimming whitespace
 * around each part.
 *
 * @param string $value The string to split.
 * @param bool   $trim  Whether to trim each part. Defaults to true, matching
 *                       the original ViewHelper's default argument.
 * @return string[] The parts, in order. A string without "|" returns a
 *                   single-element array.
 */
function bdp_pipe_break( string $value, bool $trim = true ): array {
	$parts = explode( '|', $value );

	if ( $trim ) {
		$parts = array_map( 'trim', $parts );
	}

	return $parts;
}

/**
 * Renders a pipe-separated string as one <span> per part, escaped for
 * HTML output. This is the direct WordPress equivalent of using the
 * ViewHelper inside a Fluid template.
 *
 * @param string $value     The string to split and render.
 * @param bool   $trim      Whether to trim each part.
 * @param string $separator Markup inserted between spans (default: none).
 * @return string Escaped HTML.
 */
function bdp_pipe_break_html( string $value, bool $trim = true, string $separator = '' ): string {
	$parts = bdp_pipe_break( $value, $trim );

	$spans = array_map(
		static function ( string $part ): string {
			return '<span>' . esc_html( $part ) . '</span>';
		},
		$parts
	);

	return implode( $separator, $spans );
}
