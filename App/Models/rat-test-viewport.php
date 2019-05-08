<?php
/**
 * Ratify Test: RatTestViewport class
 *
 * @package   Ratify
 * @author    Ted Stresen-Reuter <ted@secret-source.eu>
 * @copyright 2018 Secret Source Technology SL
 * @license   https://github.com/SecretSourceWeb/ratify/blob/master/LICENSE.txt GNU General Public License v2.0
 */

namespace Ratify\Models;

/**
 * Tests if HTML contains a viewport meta element.
 */
class RatTestViewport extends RatTestBase {

	/**
	 * Constructor.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 */
	public function __construct( $in = null ) {
		parent::__construct( $in );
		$this->out['title'] = __( 'Viewport Setting', 'ratify' );
	}

	/**
	 * Tests if HTML contains a viewport meta element.
	 *
	 * @param string $in string HTML to be examined.
	 * @return boolean|array True if test passes, array otherwise
	 *    $this->out['title'] = string Title of this test
	 *    $this->out['error'] = string false or string if true
	 *    $this->out['data'] = array The data we are testing
	 */
	public function runtest( $in = '' ) {
		if ( '' !== $in ) {
			$this->in = $in;
		}
		$res = $this->grep_html( $this->in, '@<meta name=(\'|")viewport(\'|") content=(\'|")(.*?)(\'|")@ims' );
		if ( $res['total'] > 0 ) {
			$this->out['error'] = false;
			$this->out['data']  = [
				sprintf(
					/* translators: 1: Total number of viewport meta tags */
					__( 'Viewport tag exists: %s', 'ratify' ),
					$res['total']
				),
			];
		} else {
			$this->out['error'] = __( 'No viewport tags were found. This is normally bad.', 'ratify' );
		}
		return $this->out;
	}
}

