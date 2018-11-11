<?php 
namespace Ratify\Models;

class RatTestGA extends RatTestBase {

    public function __construct( $in = null ) {
        parent::__construct( $in );
        $this->out['title'] = __( 'Google Analytics / Tag Manager', 'ratify' );
    }

    public function runtest( $in = '' ) {
        if ( '' != $in ) {
            $this->in = $in;
        }
        $ga = $this->grep_html( $this->in, '@var _gaq|GoogleAnalyticsObject@ims' );
        $gtm = $this->grep_html( $this->in, '@googletagmanager\.com|GTM\-@ims' );

        if( 1 == $ga['total'] or 1 == $gtm['total'] ) {
            $this->out['error'] = false;
            $this->out['data'] = Array( __( 'Google Analytics or Google Tag Manager is installed: ' . ($ga['total'] > 0 ? $ga['out'][0] : $gtm['out'][0]), 'ratify' ) );
        } else {
            $this->out['error'] = __( 'Google Analytics does not appear to be configured. Please add the Google Analytics code to the site or we won\'t be able to track any user activity. We normally use MonsterInsights to do this.', 'ratify' );
            $this->out['data'] = Array();
        }
        return $this->out;
    }
}

