<?php
/**
 * Ratify Test: RatTestNoEmoji class
 *
 * @package   Ratify
 * @author    Ted Stresen-Reuter <ted@secret-source.eu>
 * @copyright 2018 Secret Source Technology SL
 * @license   https://github.com/SecretSourceWeb/ratify/blob/master/LICENSE.txt GNU General Public License v2.0
 */

namespace Ratify\Models;

/**
 * Tests if comment emoji support scripts are being loaded.
 * Includes a function for disabling comment emoji support.
 * Comment emoji support is considered superflous and therefore disabled by Ratify by default (sorry!).
 */
class RatTestNoEmoji extends RatTestBase {

	/**
	 * Constructor.
	 *
	 * @param string $in The HTML to test. Normally the front page.
	 */
	public function __construct( $in = null ) {
		parent::__construct( $in );
		$this->out['title'] = __( 'Post Comment Emojis Removed', 'ratify' );
	}

	/**
	 * Tests if comment emoji support scripts are being loaded.
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
		$res = $this->grep_html( $this->in, '@window\._wpemojiSettings@i' );

		if ( 1 === $res['total'] ) {
			$this->out['error'] = __( 'Emojis support scripts are being loaded. WordPress must have changed or disable_wp_emojicons is not being called in the actionsAndFilters.php init sequence.', 'ratify' );
			$this->out['data']  = [];
		} else {
			$this->out['error'] = false;
			$this->out['data']  = [];
		}
		return $this->out;
	}

	/**
	 * This code comes from https://wordpress.stackexchange.com/questions/185577/disable-emojicons-introduced-with-wp-4-2
	 * I've disabled everything that affects the front end as we don't
	 * care about what's happening on the admin or in wp_mail.
	 */
	public static function disable_wp_emojicons() {
		// all front end actions related to emojis.
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

		// filter to remove TinyMCE emojis.
		add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );

		// filter to remove preload.
		add_filter( 'emoji_svg_url', '__return_false' );
	}
}

