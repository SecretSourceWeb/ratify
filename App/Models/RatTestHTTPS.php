<?php
namespace Ratify\Models;

class RatTestHTTPS extends RatTestBase {

    public function __construct( $in = null ) {

        parent::__construct( $in );

        $this->out['title'] = __( 'Running on HTTPS', 'ratify' );
        // link to more information
//        $this->out['warning_url'] = 'https://sites.google.com/a/secret-source.eu/wiki/projects/ratp-plugin-solutions-fixes#https';

    }

    /*
     * This is a very specific test. Sites must be served over HTTPS.
     * Specifically, any request over HTTP must respond with a Location
     * header redirecting to HTTPS. This means the SERVER needs to be
     * configured to redirect ALL traffic.
     */
    public function runtest( $in = '' ) {
        if ( '' != $in ) {
            $this->in = $in;
        }

        $args = array(
            'sslverify' => false,
            'redirection' => 0
        );

        // Is the home page on HTTPS?
        $homepageredirect = wp_remote_retrieve_header(
            wp_remote_get(
                'http://' . $_SERVER['HTTP_HOST'] . '/',
                $args
            ),
            'Location'
        );

        // Are scripts being redirected?
        $scriptredirect = wp_remote_retrieve_header(
            wp_remote_get(
                'http://' . preg_replace( '@^http(s)?://@i', '', RATIFY_PLUGIN_URL . ratify_get_versioned_asset('app.js') ),
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
                'http://' . preg_replace( '@^http(s)?://@i', '', RATIFY_PLUGIN_URL . 'App/Views/public/assets/images/check.png'),
                $args
            ),
            'Location'
        );

        $tests = Array(
            'homepage' => $homepageredirect,
            'scripts' => $scriptredirect,
            'stylesheets' => $stylesheetredirect,
            'images' => $imageredirect
        );

        if(
            '' == $homepageredirect
            or '' == $scriptredirect
            or '' == $stylesheetredirect
            or '' == $imageredirect
            ) {
            $this->out['error'] = __('HTTP traffic is not being redirected to HTTPS for one or more resources.', 'ratify' );
            foreach ($tests as $key=>$value) {
                if ( empty( $value ) ) {
                    if ( 'homepage' == $key ) {
                        // link to the WordPress page that let's you modify the value
                        $this->out['modify_url'] = admin_url('options-general.php');
                    }
                    $this->out['data'][] = $key . __( ' is (are) not being redirected to HTTPS.', 'ratify' );
                }
            }
        } else {
            $this->out['error'] = false;
            $this->out['data'] = Array( __('Secure: it\'s on HTTPS but still have to check if all resources exist and are loaded over HTTPS.', 'ratify' ) );
        }

        return $this->out;
    }
}



