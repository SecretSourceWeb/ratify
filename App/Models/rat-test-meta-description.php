<?php
/**
 * Ratify Test: RatTestMetaDescription class
 *
 * @package   Ratify
 * @author    Ted Stresen-Reuter <ted@secret-source.eu>
 * @copyright 2018 Secret Source Technology SL
 * @license   https://github.com/SecretSourceWeb/ratify/blob/master/LICENSE.txt GNU General Public License v2.0
 */

namespace Ratify\Models;

/**
 * Tests if the Meta Description exists and if it is of the right length.
 */
class RatTestMetaDescription extends RatTestBase {

	const MIN_DESC_LEN = 42;

	const MAX_DESC_LEN = 160;

	/**
	 * Constructor.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 */
	public function __construct( $in = null ) {
		parent::__construct( $in );
		$this->out['title'] = __( 'META DESCRIPTION Element', 'ratify' );
	}

	/**
	 * Tests to make sure there is a META DESCRIPTION element in the input
	 *
	 * If there is no META DESCRIPTION element, or the element is empty or there is
	 * more than one META DESCRIPTION element, or if the one META DESCRIPTION element has less
	 * than the minimum or more than the maximum words, this test fails.
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
		$res = $this->grep_html( $this->in, '@<meta name="description" content="(.*?)" */>@ims' );

		if ( 1 === $res['total'] ) {
			$chars = strlen( $res['out'][1][0] );
			if ( $chars >= self::MIN_DESC_LEN && $chars <= self::MAX_DESC_LEN ) {

				$this->out['error'] = false;
				$this->out['data']  = array( html_entity_decode( $res['out'][1][0], ENT_QUOTES ) );

			} else {
				$metadesclen        = strlen( html_entity_decode( $res['out'][1][0], ENT_QUOTES ) );
				$this->out['error'] = sprintf(
					/* translators: %1: Meta description minimum lenght. %2: meta description maximum length. %3: meta description actual length  */
					__(
						'The META DESCRIPTION element is either too long or too short. Descriptions should be between %1$s and %2$s characters. This meta description is %3$s character(s)',
						'ratify'
					),
					self::MIN_DESC_LEN,
					self::MAX_DESC_LEN,
					$metadesclen
				);
				$this->out['data'] = [ html_entity_decode( $res['out'][1][0], ENT_QUOTES ) ];

			}
		} else {
			if ( 0 === $res['total'] ) {

				$this->out['error'] = __( 'No META DESCRIPTION element could be found.', 'ratify' );
				$this->out['data']  = '';

			} else {

				$this->out['error'] = __( 'More than one META DESCRIPTION element was found.', 'ratify' );
				$this->out['data']  = $res['out'][1];

			}
		}
		return $this->out;
	}
}

