<?php
/**
 * Ratify Core API: RatifyNotifier class
 *
 * @package   Ratify
 * @author    Ted Stresen-Reuter <ted@secret-source.eu>
 * @copyright 2018 Secret Source Technology SL
 * @license   https://github.com/SecretSourceWeb/ratify/blob/master/LICENSE.txt GNU General Public License v2.0
 */

namespace Ratify\Models;

/**
 * Core class used to display notices in the WordPress admin.
 */
class RatifyNotifier {

	/**
	 * Can display a notice in the WordPress admin.
	 *
	 * Given some text, displays a success message in the WordPress admin.
	 * This is normally used to confirm that the home page has been saved
	 * in the local cache. Future versions will support different message
	 * types.
	 *
	 * @param string $msg The text of the confirmation to display.
	 */
	public static function success( $msg ) {
		self::get_HTML( $msg, 'success' );
	}

	/**
	 * Adds the message to the admin notices queue.
	 *
	 * Given a message to display as a WordPress admin notice, this
	 * method adds a $msg to the notice queue with the notice type of
	 * $class.
	 *
	 * Note that the $msg needs to be translated before entering this
	 * method. See this link for an explanation why:
	 * https://wordpress.stackexchange.com/questions/307345/translate-a-constant-while-appeasing-wordpress-phpcs
	 *
	 * @param string $msg The translated text of the notice to display.
	 * @param string $class The notice class to use for display. Default is 'success'.
	 */
	public static function get_HTML( $msg, $class ) {
		add_action(
			'admin_notices',
			function( $msg, $class ) {
				?>
				<div class="notice notice-<?php echo esc_attr( $class ); ?> is-dismissible">
					<p><?php echo esc_html( $msg ); ?></p>
				</div>
				<?php
			}
		);
	}
}
