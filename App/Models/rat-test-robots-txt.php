<?php
/**
 * Ratify Test: RatTestRobotstxt class
 *
 * @package   Ratify
 * @author    Ted Stresen-Reuter <ted@secret-source.eu>
 * @copyright 2018 Secret Source Technology SL
 * @license   https://github.com/SecretSourceWeb/ratify/blob/master/LICENSE.txt GNU General Public License v2.0
 */

namespace Ratify\Models;

/**
 * Tests if there is a robots.txt file.
 */
class RatTestRobotstxt extends RatTestBase {

	/**
	 * Constructor.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 */
	public function __construct( $in = null ) {
		parent::__construct( $in );
		$this->out['title'] = __( 'Site Indexing (robots.txt)', 'ratify' );
	}

	/**
	 * Tests if there is a robots.txt file.
	 *
	 * @param string $in string HTML to be examined.
	 * @return boolean|array True if test passes, array otherwise
	 *    $out['title'] = string Title of this test
	 *    $out['error'] = string false or string if true
	 *    $out['data'] = array The data we are testing
	 */
	public function runtest( $in = '' ) {
		if ( '' !== $in ) {
			$this->in = $in;
		}
		if ( '' !== $this->in ) {
			$this->out['error'] = false;
			$this->out['data']  = [
				__( 'A robots.txt file was found. It is up to you to determine if it is correct or not.', 'ratify' ),
				$this->in,
			];
		} else {
			$this->out['error'] = __( 'No robots.txt was found. Please use Yoast or some other tool to install robots.txt', 'ratify' );
			$this->out['data']  = [];
		}
		return $this->out;
	}
}

