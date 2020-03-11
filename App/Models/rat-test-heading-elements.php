<?php
/**
 * Ratify Test: RatTestHeadingElements class
 *
 * @package   Ratify
 * @author    Ted Stresen-Reuter <ted@secret-source.eu>
 * @copyright 2018 Secret Source Technology SL
 * @license   https://github.com/SecretSourceWeb/ratify/blob/master/LICENSE.txt GNU General Public License v2.0
 */

namespace Ratify\Models;

/**
 * Tests if the home page contains H1, H2, etc. and if there are enough of them.
 * Also tests to make sure there is only one H1.
 */
class RatTestHeadingElements extends RatTestBase {

	/**
	 * Constructor.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 */
	public function __construct( $in = null ) {
		parent::__construct( $in );
		$this->out['title'] = __( 'H1 element exists', 'ratify' );
	}

	/**
	 * Runs (is) the actual test.
	 *
	 * Tests if the home page contains H1, H2, etc. and if there are enough of them.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 * @return array An associative array including the following elements: error, data, title, warning_url, and modify_url
	 */
	public function runtest( $in = '' ) {
		do_action( 'ratp_runtest_start' );
		if ( '' !== $in ) {
			$this->in = $in;
		}
		$res      = $this->grep_html( $this->in, '@<h([1-6])(.*?)>(.*?)</h(\\1)>@ims' );
		$headings = [];
		$h1s      = 0;

		if ( $res['total'] > 0 ) {
			// is there at least one h1?
			for ( $i = 0; $i < $res['total']; $i++ ) {
				if ( '1' === $res['out'][1][ $i ] ) {
					$this->out['error'] = false;
					$this->out['data']  = [ wp_strip_all_tags( $res['out'][3][ $i ] ) ];
					$h1s++;
				}
				$headings[] = 'H' . $res['out'][1][ $i ] . '. ' . wp_strip_all_tags( $res['out'][3][ $i ] );
			}

			if ( false !== $this->out['error'] ) {
				$this->out['error'] = __( 'Can\'t find any H1 elements.', 'ratify' );
			}
			if ( $h1s > 1 ) {
				$this->out['error'] .= __( 'There is more than 1 H1 element.', 'ratify' );
			}
			$this->out['data'] = $headings;
		} else {
			$this->out['error'] = __( 'There are no heading elements.', 'ratify' );
			$this->out['data']  = $headings;
		}
		do_action( 'ratp_runtest_end' );
		return $this->out;
	}
}

