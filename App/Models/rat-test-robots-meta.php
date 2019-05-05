<?php 
namespace Ratify\Models;

class RatTestRobotsMeta extends RatTestBase {

    public function __construct( $in = null ) {
        parent::__construct( $in );
        $this->out['title'] = __( 'Robots Meta Tag', 'ratify' );
    }

    public function runtest( $in = '' ) {
        if ( '' != $in ) {
            $this->in = $in;
        }
        // <meta name="robots" content="noodp"/>
        $res = $this->grep_html( $this->in, '@name=(\'|")robots(\'|") content=(\'|")(.*?)(\'|")@ims' );
        if( $res['total'] > 0 ) {
            if( false !== stripos($res['out'][1][4], 'noindex' ) or
            	false !== stripos($res['out'][1][4], 'nofollow' ) ) {
                $this->out['error'] = __( 'The page is being blocked by a meta robots tag', 'ratify' );
                $this->out['data'] =  Array($res['out'][0]);
            } else {
                $this->out['error'] = false;
                $this->out['data'] = Array( __( 'There is a robots meta tag but it is not blocking robots.', 'ratify' ) );
            }
        } else {
            $this->out['error'] = false;
            $this->out['data'] = Array( __( 'No robots meta tags were found. This is normally good.', 'ratify' ) );
        }
        return $this->out;
    }
}

