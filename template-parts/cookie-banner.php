<?php
/**
 * Thin wrapper so footer.php can `get_template_part()` the cookie banner,
 * matching Templates/Cookiemanager/CookiePanel/Show.html's role as a
 * stand-alone renderable.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}

bdp_render_cookie_banner();
