<?php
/**
 * Ratify Test: RatTestQueryStrings class
 *
 * @package   Ratify
 * @author    Ted Stresen-Reuter <ted@secret-source.eu>
 * @copyright 2018 Secret Source Technology SL
 * @license   https://github.com/SecretSourceWeb/ratify/blob/master/LICENSE.txt GNU General Public License v2.0
 */

namespace Ratify\Models;

/**
 * Tests if default query strings are being appended to css and js in the HTML.
 * Includes a method for removing them that runs by default.
 */
class RatTestQueryStrings extends RatTestBase {

	/**
	 * Constructor.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 */
	public function __construct( $in = null ) {
		parent::__construct( $in );
		$this->out['title'] = __( 'Query strings on static resources (CSS & JS)', 'ratify' );
	}

	/**
	 * Tests if default query strings are being appended to css and js in the HTML.
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

		$res = $this->grep_html( $this->in, '@\?ver=[0-9\.]+@ims' );

		if ( $res['total'] > 0 ) {

			$this->out['error'] = sprintf(
				/* translators: 1: Total number of css or js files with default query strings appended */
				__( '%s default ones exists. eg. ?ver=x.x.x', 'ratify' ),
				$res['total']
			);

		} else {

			$this->out['error'] = false;
			$this->out['data']  = [
				sprintf(
					/* translators: 1: Can't remember exactly what this string is. Sorry! */
					__( 'Default query strings are being stripped out. %s', 'ratify' ),
					$res['out'][0][0]
				),
			];

		}

		return $this->out;

	}

	/**
	 * Removes default query strings.
	 *
	 * @param string $link string URL that might need to have a query string removed.
	 * @return string The $link with the query string removed
	 *    $out['title'] = string Title of this test
	 *    $out['error'] = string false or string if true
	 *    $out['data'] = array The data we are testing
	 */
	public static function strip_default_query_stings( $link ) {
		global $wp_version;
		$newlink = $link;
		if ( false !== stripos( $link, '<link' ) ) {
			$tot  = preg_match( '@href=(\'|")([^\'"]+?)(\'|")@i', $link, $pcs );
			$href = $pcs[2];
		} else {
			$tot  = 1;
			$href = $link;
		}
		if ( $tot > 0 ) {
			if ( false !== stripos( $href, '?ver=' . $wp_version ) ) {
				// strip off the version.
				$newhref = remove_query_arg( 'ver', $href );
				$newlink = str_replace( $href, $newhref, $link );
			}
		}
		return $newlink;
	}

}



