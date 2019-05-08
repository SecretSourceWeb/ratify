<?php
/**
 * Ratify Test: RatTestNoWPGenerator class
 *
 * @package   Ratify
 * @author    Ted Stresen-Reuter <ted@secret-source.eu>
 * @copyright 2018 Secret Source Technology SL
 * @license   https://github.com/SecretSourceWeb/ratify/blob/master/LICENSE.txt GNU General Public License v2.0
 */

namespace Ratify\Models;

/**
 * Tests if the wp generator meta is included in the HTML.
 * Includes a method for removing the meta. This is enabled by default.
 */
class RatTestNoWPGenerator extends RatTestBase {

	/**
	 * Constructor.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 */
	public function __construct( $in = null ) {
		parent::__construct( $in );
		$this->out['title'] = __( 'WP Generator Meta Tag Removed', 'ratify' );
	}

	/**
	 * Tests if the wp generator meta is included in the HTML.
	 *
	 * @param string $in string HTML to be examined.
	 * @return boolean|array True if test passes, array otherwise
	 *    $out['title'] = string Title of this test
	 *    $out['error'] = string false or string if true
	 *    $out['data'] = array The data we are testing
	 */
	public function runtest( $in = '' ) {
		if ( '' != $in ) {
			$this->in = $in;
		}
		$res = $this->grep_html( $this->in, '@<meta name="generator" content="WordPress@i' );

		if ( 1 === $res['total'] ) {
			$this->out['error'] = __( 'The Generator Meta Tag is still appearing. Chances are it has been hard-coded in header.php.', 'ratify' );
			$this->out['data']  = [];
		} else {
			$this->out['error'] = false;
			$this->out['data']  = [];
		}
		return $this->out;
	}


	/**
	 * Removes the generator meta tag from the output.
	 */
	public static function remove_generator_meta_tag() {
		remove_action( 'wp_head', 'wp_generator' );
		add_filter( 'the_generator', '__return_empty_string' );
	}
}

