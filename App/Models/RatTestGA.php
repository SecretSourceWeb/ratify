<?php 
namespace Ratify\Models;

class RatTestGA extends RatTestBase {

    public function __construct( $in = null ) {
        parent::__construct( $in );
        $this->out['title'] = __( 'Google Analytics', 'ratify' );
    }

    public function runtest( $in = '' ) {
        if ( '' != $in ) {
            $this->in = $in;
        }
        $res = $this->grep_html( $this->in, '@var _gaq|GoogleAnalyticsObject@ims' );

        if( 1 == $res['total'] ) {
            $this->out['error'] = false;
            $this->out['data'] = Array( __( 'Google Analytics is installed: ' . $res['out'][0], 'ratify' ) );
        } else {
            $this->out['error'] = __( 'Google Analytics does not appear to be configured. Please add the Google Analytics code to the site or we won\'t be able to track any user activity. We normally use MonsterInsights to do this.', 'ratify' );
            $this->out['data'] = Array();
        }
        return $this->out;
    }
}

