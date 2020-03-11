<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://secret-source.eu/
 * @since             1.0.4
 * @package           Ratify
 *
 * @wordpress-plugin
 * Plugin Name:       Ratify
 * Plugin URI:        https://secret-source.eu/plugins/ratify
 * Description:       Verify (ratify) the technical quality of your web site.
 * Version:           1.1.1
 * Author:            Ted Stresen-Reuter
 * Author URI:        https://secret-source.eu/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ratify
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once __DIR__ . '/vendor/autoload.php';

define( 'RATIFY_VERSION', '1.1.1' );
define( 'RATIFY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'RATIFY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'RATIFY_PLUGIN_FOLDER_NAME', dirname( plugin_basename( __FILE__ ) ) );

/**
 * The code that runs during plugin activation.
 */
function activate_ratify() {
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_ratify() {
}

register_activation_hook( __FILE__, 'activate_ratify' );
register_deactivation_hook( __FILE__, 'deactivate_ratify' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

\Ratify\Controllers\RatifyLoader::load();
