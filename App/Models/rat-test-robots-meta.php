<?php
/**
 * Ratify Test: RatTestRobotsMeta class
 *
 * @package   Ratify
 * @author    Ted Stresen-Reuter <ted@secret-source.eu>
 * @copyright 2018 Secret Source Technology SL
 * @license   https://github.com/SecretSourceWeb/ratify/blob/master/LICENSE.txt GNU General Public License v2.0
 */

namespace Ratify\Models;

/**
 * Tests if the HTML contains a robots meta and if it is blocking indexing.
 */
class RatTestRobotsMeta extends RatTestBase {

	/**
	 * Constructor.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 */
	public function __construct( $in = null ) {
		parent::__construct( $in );
		$this->out['title'] = __( 'Robots Meta Tag', 'ratify' );
	}

	/**
	 * Tests if the HTML contains a robots meta and if it is blocking indexing.
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
		// <meta name="robots" content="noodp"/>
		$res = $this->grep_html( $this->in, '@name=(\'|")robots(\'|") content=(\'|")(.*?)(\'|")@ims' );
		if ( $res['total'] > 0 ) {
			if ( false !== stripos( $res['out'][1][4], 'noindex' )
				|| false !== stripos( $res['out'][1][4], 'nofollow' ) ) {
				$this->out['error'] = __( 'The page is being blocked by a meta robots tag', 'ratify' );
				$this->out['data']  = array( $res['out'][0] );
			} else {
				$this->out['error'] = false;
				$this->out['data']  = array( __( 'There is a robots meta tag but it is not blocking robots.', 'ratify' ) );
			}
		} else {
			$this->out['error'] = false;
			$this->out['data']  = array( __( 'No robots meta tags were found. This is normally good.', 'ratify' ) );
		}
		return $this->out;
	}
}
