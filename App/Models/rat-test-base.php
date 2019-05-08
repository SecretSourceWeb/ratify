<?php
/**
 * Ratify Core API: RatTestBase class
 *
 * @package   Ratify
 * @author    Ted Stresen-Reuter <ted@secret-source.eu>
 * @copyright 2018 Secret Source Technology SL
 * @license   https://github.com/SecretSourceWeb/ratify/blob/master/LICENSE.txt GNU General Public License v2.0
 */

namespace Ratify\Models;

/**
 * Base class for all tests. Chose not to make abstract to keep thing simple.
 */
class RatTestBase {

	/**
	 * Normally, the HTML to test but can be any string.
	 *
	 * @var string $in
	 */
	public $in = '';

	/**
	 * The results of the test as an associative array. The member
	 * elements include:
	 * - title: The name of the test
	 * - error: A text description of what the error is
	 * - data: An array of lines that are printed to the output to demonstrate the error
	 * - modify_url: The URL to the WordPress admin where the user can modify a setting to so that the test can pass
	 * - warning_url: A URL where the user can learn more about the issue
	 *
	 * @var array $out
	 */
	public $out = array();

	/**
	 * Base class for all tests. Chose not to make abstract to keep things simple.
	 *
	 * @param string $in The HTML to test. Can also be a string of any content.
	 */
	public function __construct( $in = null ) {
		$this->in = $in;
		$this->out['title']       = 'Uninitialized test';
		$this->out['error']       = '(' . __( 'Tests must include a text description of what the error is', 'ratify' ) . ')';
		$this->out['data']        = [];
		$this->out['modify_url']  = '';
		$this->out['warning_url'] = '';
		return true;
	}

	/**
	 * Placeholder function (a signature, of sorts) that is the actual
	 * test to run (ergo, the name).
	 *
	 * @param string $in The HTML to test. Can also be a string of any content.
	 * @return array An associative array including the following elements: error, data, title, warning_url, and modify_url
	 */
	public function runtest( $in = '' ) {
		do_action( 'ratp_runtest_start' );
		if ( '' !== $in ) {
			$this->in = $in;
		}
		do_action( 'ratp_runtest_end' );
		return $this->out;
	}

	/**
	 * Utility function for doing regex searches on a (multiline) string.
	 * By default this function looks for the Title element.
	 *
	 * @param string $in The string to search (the haystack).
	 * @param string $p The regex pattern to match (the needle).
	 * @return array An associative array with the members "total", "in", "p", and "out".
	 */
	public static function grep_html( $in = '', $p = '@<title[^>]*>(.*?)</title>@ims' ) {
		$matches = preg_match_all( $p, $in, $pieces );

		return array(
			'total' => $matches,
			'in'    => $in,
			'p'     => $p,
			'out'   => $pieces,
		);
	}
}
