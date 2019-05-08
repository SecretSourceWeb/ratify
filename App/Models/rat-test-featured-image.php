<?php
/**
 * Ratify Test: RatTestFeaturedImage class
 *
 * @package   Ratify
 * @author    Ted Stresen-Reuter <ted@secret-source.eu>
 * @copyright 2018 Secret Source Technology SL
 * @license   https://github.com/SecretSourceWeb/ratify/blob/master/LICENSE.txt GNU General Public License v2.0
 */

namespace Ratify\Models;

/**
 * Checks to make sure the home page has a featured image. This is required
 * by sites like Facebook and LinkedIn and is used as the site preview (or
 * hero shot) in the post preview.
 */
class RatTestFeaturedImage extends RatTestBase {

	/**
	 * Constructor.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 */
	public function __construct( $in = null ) {
		parent::__construct( $in );
		$this->out['title'] = __( 'Home Page Featured Image', 'ratify' );
	}

	/**
	 * Runs (is) the actual test.
	 *
	 * Verifies that the front page has a featured image attached to it.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 * @return array An associative array including the following elements: error, data, title, warning_url, and modify_url
	 */
	public function runtest( $in = '' ) {
		if ( '' !== $in ) {
			$this->in = $in;
		}
		$id = get_option( 'page_on_front', true );
		$fi = get_the_post_thumbnail( $id );
		$fi = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'full' );

		if ( '' !== $fi ) {
			if ( $fi[1] >= 1200 && $fi[2] >= 300 ) {
				$this->out['error'] = false;
				$this->out['data']  = $fi;
			} else {
				if ( $fi[1] < 1200 ) {
					$this->out['error'] = sprintf(
						/* translators: %s: width of an image in pixels */
						esc_html__( 'The home page has a featured image but it is not wide enough (%spx). It needs to be at least 1200px wide.', 'ratify' ),
						$fi[1]
					);
					$this->out['data']  = $fi;
				} else {
					$this->out['error'] = sprintf(
						/* translators: %s: height of an image in pixels */
						esc_html__( 'The home page has a featured image but it is not tall enough (%spx). It needs to be at least 300px high.', 'ratify' ),
						$fi[2]
					);
					$this->out['data']  = $fi;
				}
			}
		} else {
			$this->out['error'] = __( 'No featured image was found on the front page.', 'ratify' );
		}
		return $this->out;
	}
}

