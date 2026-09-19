<?php

require_once __DIR__ . '/BdpTestCase.php';

/**
 * @covers ::bdp_pipe_break
 * @covers ::bdp_pipe_break_html
 */
final class PipeBreakTest extends BdpTestCase {

	protected function setUp(): void {
		parent::setUp();
		require_once dirname( __DIR__, 2 ) . '/inc/pipe-break.php';
	}

	public function test_splits_on_pipe_and_trims_by_default(): void {
		$this->assertSame(
			array( 'Landesverband', 'Hessen' ),
			bdp_pipe_break( 'Landesverband | Hessen' )
		);
	}

	public function test_without_pipe_returns_single_element_array(): void {
		$this->assertSame( array( 'Landesverband' ), bdp_pipe_break( 'Landesverband' ) );
	}

	public function test_trim_false_keeps_whitespace(): void {
		$this->assertSame(
			array( 'Landesverband ', ' Hessen' ),
			bdp_pipe_break( 'Landesverband | Hessen', false )
		);
	}

	public function test_handles_multiple_pipes(): void {
		$this->assertSame(
			array( 'a', 'b', 'c' ),
			bdp_pipe_break( 'a|b|c' )
		);
	}

	public function test_empty_string_returns_single_empty_part(): void {
		$this->assertSame( array( '' ), bdp_pipe_break( '' ) );
	}

	public function test_html_wraps_each_part_in_a_span_and_escapes(): void {
		$html = bdp_pipe_break_html( 'Title <b> | Sub & Co' );

		$this->assertSame(
			'<span>Title &lt;b&gt;</span><span>Sub &amp; Co</span>',
			$html
		);
	}

	public function test_html_accepts_a_separator(): void {
		$html = bdp_pipe_break_html( 'A | B', true, '<br>' );

		$this->assertSame( '<span>A</span><br><span>B</span>', $html );
	}
}
