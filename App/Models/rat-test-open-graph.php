<?php
/**
 * Ratify Test: RatTestOpenGraph class
 *
 * @package   Ratify
 * @author    Ted Stresen-Reuter <ted@secret-source.eu>
 * @copyright 2018 Secret Source Technology SL
 * @license   https://github.com/SecretSourceWeb/ratify/blob/master/LICENSE.txt GNU General Public License v2.0
 */

namespace Ratify\Models;

/**
 * Tests if the Facebook Open Graph meta are included in the HTML.
 */
class RatTestOpenGraph extends RatTestBase {

	/**
	 * Constructor.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 */
	public function __construct( $in = null ) {
		parent::__construct( $in );
		$this->out['title'] = __( 'Facebook (Open Graph) Tags Exist', 'ratify' );
	}

	/**
	 * Tests if the Facebook Open Graph meta are included in the HTML.
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
		$res = $this->grep_html( $this->in, '@<meta property=((\'|")og:.*?)/>@ims' );

		if ( $res['total'] > 0 ) {

			$this->out['error'] = false;
			$tags               = [];

			foreach ( $res['out'][1] as $item ) {
				$p = '@og:([a-z0-9\-_\:]+?)" content="(.*?)"@i';
				preg_match( $p, $item, $pcs );
				if ( count( $pcs ) > 0 ) {
					$tags[] = "{$pcs[1]}: " . html_entity_decode( $pcs[2] );
				}
				unset( $pcs );
			}
			$this->out['data'] = $tags;
		} else {
			$this->out['error'] = __( 'No OG: tags were found in the HTML.', 'ratify' );
			$this->out['data']  = [];
		}
		return $this->out;
	}
}

