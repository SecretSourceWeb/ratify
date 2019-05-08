<?php
/**
 * Ratify Test: RatTestGA class
 *
 * @package   Ratify
 * @author    Ted Stresen-Reuter <ted@secret-source.eu>
 * @copyright 2018 Secret Source Technology SL
 * @license   https://github.com/SecretSourceWeb/ratify/blob/master/LICENSE.txt GNU General Public License v2.0
 */

namespace Ratify\Models;

/**
 * Checks to that Google Analytics has been installed (is present on
 * the page). This test simply looks for the existence of the typical
 * Google Analytics JavaScript code.
 */
class RatTestGA extends RatTestBase {

	/**
	 * Constructor.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 */
	public function __construct( $in = null ) {
		parent::__construct( $in );
		$this->out['title'] = __( 'Google Analytics / Tag Manager', 'ratify' );
	}

	/**
	 * Runs (is) the actual test.
	 *
	 * Verifies that the page includes the GA JavaScript.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 * @return array An associative array including the following elements: error, data, title, warning_url, and modify_url
	 */
	public function runtest( $in = '' ) {
		if ( '' !== $in ) {
			$this->in = $in;
		}
		$ga  = $this->grep_html( $this->in, '@var _gaq|GoogleAnalyticsObject@ims' );
		$gtm = $this->grep_html( $this->in, '@googletagmanager\.com|GTM\-@ims' );

		if ( 1 === $ga['total'] || 1 === $gtm['total'] ) {
			$gcode              = $ga['total'] > 0 ? $ga['out'][0] : $gtm['out'][0];
			$this->out['error'] = false;
			$this->out['data']  = [
				sprintf(
					/* translators: 1: The google analytics or GTM code used in JavaScript */
					esc_html__( 'Google Analytics or Google Tag Manager is installed: %s.', 'ratify' ),
					$gcode
				),
			];
		} else {
			$this->out['error'] = __( 'Google Analytics does not appear to be configured. Please add the Google Analytics code to the site or we won\'t be able to track any user activity. We normally use MonsterInsights to do this.', 'ratify' );
			$this->out['data']  = [];
		}
		return $this->out;
	}
}

