<?php
/**
 * Ratify Test: RatTestHTTPS class
 *
 * @package   Ratify
 * @author    Ted Stresen-Reuter <ted@secret-source.eu>
 * @copyright 2018 Secret Source Technology SL
 * @license   https://github.com/SecretSourceWeb/ratify/blob/master/LICENSE.txt GNU General Public License v2.0
 */

namespace Ratify\Models;

/**
 * Tests if the site is completely redirected to HTTPS.
 */
class RatTestHTTPS extends RatTestBase {

	/**
	 * Constructor.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 */
	public function __construct( $in = null ) {

		parent::__construct( $in );

		$this->out['title'] = __( 'Running on HTTPS', 'ratify' );
	}

	/**
	 * Runs (is) the actual test.
	 *
	 * Tests if the site is completely redirected to HTTPS.
	 * This is a very specific test. Sites must be served over HTTPS.
	 * Specifically, any request over HTTP must respond with a Location
	 * header redirecting to HTTPS. This means the SERVER needs to be
	 * configured to redirect ALL traffic.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 * @return array An associative array including the following elements: error, data, title, warning_url, and modify_url
	 */
	public function runtest( $in = '' ) {
		if ( '' !== $in ) {
			$this->in = $in;
		}

		$args = array(
			'sslverify'   => false,
			'redirection' => 0,
			'timeout'     => 100,
			'user-agent'  => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36',
		);

		// Is the home page on HTTPS?
		$homepageredirect = wp_remote_retrieve_header(
			wp_remote_get(
				home_url( '/', 'http' ),
				$args
			),
			'Location'
		);

		// Are scripts being redirected?
		$scriptredirect = wp_remote_retrieve_header(
			wp_remote_get(
				'http://' . preg_replace( '@^http(s)?://@i', '', RATIFY_PLUGIN_URL . ratify_get_versioned_asset( 'app.js' ) ),
				$args
			),
			'Location'
		);

		// Are stylesheets being redirected?
		$stylesheetredirect = wp_remote_retrieve_header(
			wp_remote_get(
				'http://' . preg_replace( '@^http(s)?://@i', '', get_stylesheet_uri() ),
				$args
			),
			'Location'
		);

		// Are images being redirected?
		$imageredirect = wp_remote_retrieve_header(
			wp_remote_get(
				'http://' . preg_replace( '@^http(s)?://@i', '', RATIFY_PLUGIN_URL . 'App/Views/public/assets/images/check.png' ),
				$args
			),
			'Location'
		);

		$tests = array(
			'homepage'    => $homepageredirect,
			'scripts'     => $scriptredirect,
			'stylesheets' => $stylesheetredirect,
			'images'      => $imageredirect,
		);

		if (
			'' === $homepageredirect
			|| '' === $scriptredirect
			|| '' === $stylesheetredirect
			|| '' === $imageredirect
			) {
			$this->out['error'] = __( 'HTTP traffic is not being redirected to HTTPS for one or more resources.', 'ratify' );
			foreach ( $tests as $key => $value ) {
				if ( empty( $value ) ) {
					if ( 'homepage' == $key ) {
						// link to the WordPress page that let's you modify the value.
						$this->out['modify_url'] = admin_url( 'options-general.php' );
					}
					$this->out['data'][] = $key . __( ' is (are) not being redirected to HTTPS.', 'ratify' );
				}
			}
		} else {
			$this->out['error'] = false;
			$this->out['data']  = array( __( 'Secure: it\'s on HTTPS but still have to check if all resources exist and are loaded over HTTPS.', 'ratify' ) );
		}

		return $this->out;
	}
}



