<?php
/**
 * Main-navigation walker.
 *
 * Reproduces Resources/Private/PageView/Partials/Header.html: top-level
 * items either link directly, or (when they have children) render as a
 * <button aria-expanded> that reveals a dropdown <ul> — this was driven in
 * TYPO3 by doktype 1775308392 (the "dropdown-group" ContentBlocks page
 * type). In WordPress the equivalent signal is simply "this nav item has
 * children", so every parent with children becomes a dropdown.
 *
 * @package BdP_Scouting
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BdP_Scouting_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * @inheritDoc
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$id     = $this->current_parent_id;
		$output .= sprintf(
			'<ul id="bdp-m-s-%1$d" class="lg:absolute lg:z-1000 lg:float-left lg:hidden lg:w-auto lg:bg-white lg:shadow-md dark:lg:bg-kohte lg:peer-aria-expanded/navbutton:block" aria-labelledby="bdp-m-b-%1$d">',
			(int) $id
		);
	}

	/**
	 * @inheritDoc
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</ul>';
	}

	/**
	 * @inheritDoc
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$has_children       = in_array( 'menu-item-has-children', $item->classes, true );
		$this->current_parent_id = $item->ID;
		$is_current         = in_array( 'current-menu-item', $item->classes, true ) || in_array( 'current-menu-ancestor', $item->classes, true );

		if ( 0 === $depth ) {
			if ( $has_children ) {
				$output .= sprintf(
					'<li class="group/navitem relative block"><button type="button" id="bdp-m-b-%1$d" class="group/navbutton peer/navbutton z-1 cursor-pointer bg-white whitespace-nowrap text-blue-500 hover:text-kluft focus-visible:text-kluft focus-visible:outline-kluft focus-visible:outline-solid lg:relative lg:block lg:h-20 lg:px-3 lg:leading-20 lg:duration-150 lg:ease-in-out xl:px-6 2xl:px-8 dark:bg-kohte dark:text-white dark:hover:text-yellow-light dark:focus-visible:text-yellow-light dark:focus-visible:outline-white" data-tid="%1$d" aria-expanded="false" aria-controls="bdp-m-s-%1$d">%2$s<span class="hidden overflow-hidden text-yellow-500 lg:absolute lg:right-3 lg:bottom-5 lg:left-3 group-hover/navbutton:lg:block group-focus-visible/navbutton:lg:block" aria-hidden="true">%3$s</span></button>',
					(int) $item->ID,
					esc_html( $item->title ),
					bdp_scouting_inline_svg( 'lines/line.svg', array( 'height' => '7px' ) )
				);
			} else {
				$output .= sprintf(
					'<li class="group/navitem relative block"><a href="%1$s" class="z-1 whitespace-nowrap hover:text-kluft focus-visible:text-kluft focus-visible:outline-kluft focus-visible:outline-solid lg:relative lg:block lg:h-20 lg:px-3 lg:leading-20 lg:duration-150 lg:ease-in-out xl:px-6 2xl:px-8 dark:hover:text-yellow-light dark:focus-visible:text-yellow-light dark:focus-visible:outline-white%2$s"%3$s>%4$s</a>',
					esc_url( $item->url ),
					$is_current ? ' font-semibold' : '',
					$is_current ? ' aria-current="page"' : '',
					esc_html( $item->title )
				);
			}
		} else {
			$output .= sprintf(
				'<li class="first:pt-3 last:pb-1.5 lg:relative lg:block 2xl:last:pb-5.5"><a href="%1$s" class="group/drowpdownitem whitespace-nowrap lg:relative lg:block lg:min-w-32 lg:px-4 lg:py-2.5 lg:leading-none lg:hover:text-kluft dark:hover:text-yellow-light"%2$s><span class="block">%3$s</span></a>',
				esc_url( $item->url ),
				$is_current ? ' aria-current="page"' : '',
				esc_html( $item->title )
			);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '</li>';
	}
}

/**
 * Same structure, tuned for the off-canvas mobile panel (plain buttons,
 * no aria-controls id collisions with the desktop walker).
 */
class BdP_Scouting_Mobile_Nav_Walker extends BdP_Scouting_Nav_Walker {

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$id      = $this->current_parent_id;
		$output .= sprintf( '<div id="bdp-mm-s-%1$d" class="m-submenu" style="display:none;"><ul aria-labelledby="bdp-mm-b-%1$d" class="overflow-hidden">', (int) $id );
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</ul></div>';
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$has_children             = in_array( 'menu-item-has-children', $item->classes, true );
		$this->current_parent_id  = $item->ID;
		$is_current               = in_array( 'current-menu-item', $item->classes, true );

		if ( 0 === $depth ) {
			if ( $has_children ) {
				$output .= sprintf(
					'<li class="group/navitem relative block"><button type="button" id="bdp-mm-b-%1$d" data-tid="%1$d" aria-expanded="false" aria-controls="bdp-mm-s-%1$d" class="group/mnavbutton w-full cursor-pointer px-4 py-4 text-left text-xl focus-visible:text-yellow-500 enabled:text-white enabled:hover:text-yellow-500">%2$s</button>',
					(int) $item->ID,
					esc_html( $item->title )
				);
			} else {
				$output .= sprintf(
					'<li class="group/navitem relative block"><div class="space-between flex"><a href="%1$s" class="w-full px-4 py-4 text-xl hover:text-yellow-500 focus-visible:text-yellow-500"%2$s><span>%3$s</span></a></div>',
					esc_url( $item->url ),
					$is_current ? ' aria-current="page"' : '',
					esc_html( $item->title )
				);
			}
		} else {
			$output .= sprintf(
				'<li><a href="%1$s" class="group/drowpdownitem relative block px-4 py-2.5 leading-none whitespace-nowrap hover:text-yellow-500"%2$s><span class="block">%3$s</span></a>',
				esc_url( $item->url ),
				$is_current ? ' aria-current="page"' : '',
				esc_html( $item->title )
			);
		}
	}
}
