<?php
/**
 * Ratify Core API: RatifyLoader class
 *
 * @package   Ratify
 * @author    Ted Stresen-Reuter <ted@secret-source.eu>
 * @copyright 2018 Secret Source Technology SL
 * @license   https://github.com/SecretSourceWeb/ratify/blob/master/LICENSE.txt GNU General Public License v2.0
 */

namespace Ratify\Controllers;

/**
 * Loads all the parts of this plugin (settings page, etc.).
 */
class RatifyLoader {

	/**
	 * Sets up the plugin features.
	 */
	public static function load() {
		self::load_plugin_textdomain();
		include_once plugin_dir_path( __DIR__ ) . 'helpers.php';
		add_action( 'admin_init', array( __NAMESPACE__ . '\RatifyLoader', 'settings_page' ) );
		add_action( 'admin_menu', array( __NAMESPACE__ . '\RatifyLoader', 'menu_item' ) );
		self::enqueue_stuff();

		// only execute on front end.
		if ( ! is_admin() ) {
			add_action( 'init', array( '\Ratify\Models\RatTestNoEmoji', 'disable_wp_emojicons' ) );
			add_action( 'init', array( '\Ratify\Models\RatTestNoWPGenerator', 'remove_generator_meta_tag' ) );
			add_action( 'init', array( '\Ratify\Models\RatTestCronActivated', 'disable_cron_thank_you' ) );

			add_filter( 'style_loader_tag', array( '\Ratify\Models\RatTestQueryStrings', 'strip_default_query_stings' ) );
			add_filter( 'script_loader_src', array( '\Ratify\Models\RatTestQueryStrings', 'strip_default_query_stings' ) );

			do_action( 'ratp_running_actions_and_filters' );
		}

	}

	/**
	 * Registers the report page.
	 */
	public static function settings_page() {
		return register_setting( 'ratify', 'ratify_options' );
	}

	/**
	 * Add the menu item to the admin menu so users can view the report.
	 */
	public static function menu_item() {
		$r = new RatifyReportGenerator();
		add_menu_page(
			'Ratify',
			'Ratify',
			'manage_options',
			'ratify-report',
			self::make_callable( $r ),
			'dashicons-clipboard',
			'35.555'
		);

		add_submenu_page(
			'ratify-report',
			'View Ratify Form',
			'Ratify Form',
			'manage_options',
			'ratify-report'
		);
		/*
		$ratiddons = add_submenu_page(
			'ratify-report',
			'View Ratify Addons',
			'Ratify Addons',
			'manage_options',
			'ratify-add',
			'ratify_addons'
		);
		add_action('load-', $ratiddons, 'ratify_addons');
		*/
	}

	/**
	 * Constructor.
	 */
	public static function ratify_addons() {
		/* Placeholder */
	}

	/**
	 * Workaround function used for defining menu item in admin menu.
	 *
	 * @param Object $obj The menu item to make callable.
	 */
	public static function make_callable( $obj ) {
		return function () use ( $obj ) {
			return $obj->index();
		};
	}

	/**
	 * Enqueues the stylesheet for the report.
	 */
	public static function enqueue_stuff() {
		$stylesheet = ratify_get_versioned_asset();
		add_action(
			'admin_enqueue_scripts',
			function() use ( $stylesheet ) {
				wp_enqueue_style( 'ratify-admin', RATIFY_PLUGIN_URL . $stylesheet, null, '20190505', 'screen' );
			}
		);
	}

	/**
	 * Registers the plugin text domain.
	 */
	public static function load_plugin_textdomain() {
		load_plugin_textdomain(
			'ratify',
			false,
			RATIFY_PLUGIN_FOLDER_NAME . '/languages/'
		);
	}
}

