<?php
/**
 * Ratify Test: RatTestGZIP class
 *
 * @package   Ratify
 * @author    Ted Stresen-Reuter <ted@secret-source.eu>
 * @copyright 2018 Secret Source Technology SL
 * @license   https://github.com/SecretSourceWeb/ratify/blob/master/LICENSE.txt GNU General Public License v2.0
 */

namespace Ratify\Models;

/**
 * Tests if the home page is being gzipped when sent over HTTP.
 */
class RatTestGZIP extends RatTestBase {

	/**
	 * Constructor.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 */
	public function __construct( $in = null ) {

		parent::__construct( $in );

		$this->out['title']       = __( 'GZIP compression', 'ratify' );
		$this->out['warning_url'] = '';

	}

	/**
	 * Runs (is) the actual test.
	 *
	 * Verifies that the server is sending gzipped content.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 * @return array An associative array including the following elements: error, data, title, warning_url, and modify_url
	 */
	public function runtest( $in = '' ) {
		parent::runtest( $in );
		$this->out['error'] = __( 'The Content-Encoding header was not found in the response. This is usually due to a misconfiguration of your web server.', 'ratify' );

		// is the site delivering with gzip?
		$cmd = 'curl -k -s -L -H "Accept-Encoding: gzip,deflate" -I ' . home_url() . ' | grep "Content-Encoding"';
		//phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.system_calls_exec -- We also require that curl be installed.
		exec( $cmd, $result, $return_var );
		if ( count( $result ) == 1 ) {
			$parts = preg_split( '@: *@i', $result[0] );
			$val   = trim( strtolower( $parts[1] ) );
			if ( strtolower( $parts[0] ) === 'content-encoding' ) {
				if ( 1 === preg_match( '@gzip|deflate@i', $val ) ) {
					$this->out['error'] = false;
					$this->out['data']  = [];
					return $this->out;
				} else {
					$this->out['error'] = __( 'Content-Encoding does not appear to be gzip', 'ratify' );
					$this->out['data']  = [ $parts[1] ];
				}
			}
		}
		return $this->out;
	}

	/**
	 * Checks to see if Expires rules are set in htaccess.
	 *
	 * @return boolean|string True if htaccess contains at least one mod_expires rule. String with explanation of problem otherwise (htaccess doesn't exist).
	 */
	public function htaccess_sets_expires() {
		if ( file_exists( get_home_path() . '.htaccess' ) ) {
			$cmd = 'grep ExpiresActive ' . get_home_path() . '.htaccess';
			//phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.system_calls_exec -- We also require that curl be installed.
			exec( $cmd, $result, $return_var );
			if ( 0 === $return_var ) {
				// that's good enough for me.
				return true;
			} else {
				return 'An .htaccess file exists but it does not appear to be setting ExpiresActive (caching).';
			}
		} else {
			return "Can't find .htaccess in the project root.";
		}
	}
}
