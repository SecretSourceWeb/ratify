<?php

namespace Ratify\Controllers;

class RatifyLoader {

    public static function load () {
        self::load_plugin_textdomain();
        include_once( plugin_dir_path( __DIR__ ) . 'helpers.php' );
        add_action( 'admin_init', array( __NAMESPACE__ . '\RatifyLoader', 'settings_page' ) );
        add_action( 'admin_menu', array( __NAMESPACE__ . '\RatifyLoader', 'menu_item' ) );
        self::enqueueStuff();

        // only execute on front end
        if ( ! is_admin() ) {
            add_action( 'init', Array( '\Ratify\Models\RatTestNoEmoji', 'disable_wp_emojicons') );
            add_action( 'init', Array( '\Ratify\Models\RatTestNoWPGenerator', 'remove_generator_meta_tag') );
            add_action( 'init', Array( '\Ratify\Models\RatTestCronActivated', 'disable_cron_thank_you') );

            add_filter( 'style_loader_tag', Array( '\Ratify\Models\RatTestQueryStrings', 'strip_default_query_stings') );
            add_filter( 'script_loader_src', Array( '\Ratify\Models\RatTestQueryStrings', 'strip_default_query_stings') );

            do_action('ratp_running_actions_and_filters');
        }

    }

    public static function settings_page () {
        return register_setting( 'ratify', 'ratify_options' );
    }

    public static function menu_item () {
        $r = new RatifyReportGenerator();
        add_menu_page(
            'Ratify',
            'Ratify',
            'manage_options',
            'ratify-report',
            \Ratify\Controllers\RatifyLoader::makeCallable($r),
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

    public static function ratify_addons(){
        /* Placeholder */
    }

    public static function makeCallable($obj) {
        return function () use ($obj) {
            return $obj->index();
        };
    }

    public static function enqueueStuff() {
        $stylesheet = ratify_get_versioned_asset();
        add_action( 'admin_enqueue_scripts', function() use ($stylesheet) {
            wp_enqueue_style( 'ratify-admin', RATIFY_PLUGIN_URL . $stylesheet, null, null, 'screen' );
        });
    }

	public static function load_plugin_textdomain() {
		load_plugin_textdomain(
			'ratify',
			false,
			RATIFY_PLUGIN_FOLDER_NAME . '/languages/'
		);
	}
}

