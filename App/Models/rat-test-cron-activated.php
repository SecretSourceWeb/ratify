<?php
/**
 * Ratify Test: RatTestCronActivated class
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
class RatTestCronActivated extends RatTestBase {

	/**
	 * Constructor.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 */
	public function __construct( $in = null ) {
		parent::__construct( $in );
		$this->out['title']          = __( 'Cron Activated', 'ratify' );
		$this->out['cron_activated'] = get_site_url() . '/wp-cron.php';
	}

	/**
	 * Runs (is) the actual test.
	 *
	 * Checks to see if the WordPress cron is disabled. The only way
	 * to disabled that we're testing is if the DISABLE_WP_CRON is
	 * defined and set to true.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 * @return array An associative array including the following elements: error, data, title, warning_url, and modify_url
	 */
	public function runtest( $in = '' ) {
		parent::runtest();
		if ( defined( 'DISABLE_WP_CRON' ) && true !== DISABLE_WP_CRON ) {
			$this->out['error'] = __( 'Cron is not disabled for this installation.', 'ratify' );
			$this->out['data']  = array();
		}
		return $this->out;
	}

	/**
	 * Utility function to disable the WordPress cron. By default
	 * the WordPress cron is disabled (see the loader file). In a future
	 * version the code that disables the cron will be turned into a
	 * plugin option with more features (like, setting it to run during
	 * specific time windows or only on the back end, or both).
	 */
	public static function disable_cron_thank_you() {
		if ( ! defined( 'DISABLE_WP_CRON' ) ) {
			define( 'DISABLE_WP_CRON', true );
		}
	}
}
