<?php
/**
 * Ratify Test: RatTestAltAttributesOnImages class
 *
 * @package   Ratify
 * @author    Ted Stresen-Reuter <ted@secret-source.eu>
 * @copyright 2018 Secret Source Technology SL
 * @license   https://github.com/SecretSourceWeb/ratify/blob/master/LICENSE.txt GNU General Public License v2.0
 */

namespace Ratify\Models;

/**
 * Checks to see that all images on the page have non-empty ALT attributes.
 */
class RatTestAltAttributesOnImages extends RatTestBase {

	/**
	 * Constructor.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 */
	public function __construct( $in = null ) {
		parent::__construct( $in );
		$this->out['title'] = __( 'IMAGE Elements Have ALT Attributes', 'ratify' );
	}

	/**
	 * Runs (is) the actual test.
	 *
	 * Gets a list of every IMG element on the page and tests to make sure
	 * they all have non-empty ALT attributes.
	 *
	 * @return array An associative array including the following elements: error, data, title, warning_url, and modify_url
	 * @param string $in The HTML to test. Normally the front page.
	 */
	public function runtest( $in = '' ) {
		parent::runtest( $in );

		$images_without_alts = array();
		$res                 = $this->grep_html( $this->in, '@<img (.+?)(?=/>)@ims' );

		if ( $res['total'] > 0 ) {
			// test each image for ALT attributes.
			foreach ( $res['out'][1] as $item ) {
				$p    = '@alt=(\'|")(.*?)(\'|")@ims';
				$alts = preg_match( $p, $item, $pieces );
				if ( '' === trim( $pieces[2] ) ) {
					preg_match( '@src=(\'|")(.*?)(\'|")@i', $item, $src );
					$images_without_alts[] = admin_url(
						'upload.php?item='
						. ratify_get_attachment_id_from_src( $src[2] )
					);
				}
			}

			if ( count( $images_without_alts ) > 0 ) {
				$this->out['error'] = __( 'Some images were found without ALT attributes.', 'ratify' );
				$this->out['data']  = $images_without_alts;
			} else {
				$this->out['error'] = false;
				$this->out['data']  = '';
			}
		} else {

			$this->out['error'] = __( 'No images were found', 'ratify' );
			$this->out['data']  = $res['out'][0];

		}
		do_action( 'ratp_runtest_end' );
		return $this->out;
	}
}

