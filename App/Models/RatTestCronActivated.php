<?php
namespace Ratify\Models;

class RatTestCronActivated extends RatTestBase {

    public function __construct( $in = null ) {
        parent::__construct( $in );
        $this->out['title'] = __( 'Cron Activated', 'ratify' );
        $this->out['cron_activated'] = get_site_url().'/wp-cron.php';
    }

    public function runtest( $in = '' ) {
        parent::runtest();
        if ( defined('DISABLE_WP_CRON') and true !== DISABLE_WP_CRON ) {
            $this->out['error'] = __( 'Cron is not disabled for this installation.', 'ratify' );
            $this->out['data'] = Array();
        }
        return $this->out;
    }

    public static function disable_cron_thank_you () {
        if ( ! defined('DISABLE_WP_CRON') ) {
            define('DISABLE_WP_CRON', true);
        }
    }
}
