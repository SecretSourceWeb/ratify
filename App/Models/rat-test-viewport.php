<?php 
namespace Ratify\Models;

class RatTestViewport extends RatTestBase {

    public function __construct( $in = null ) {
        parent::__construct( $in );
        $this->out['title'] = __( 'Viewport Setting', 'ratify' );
    }

    public function runtest( $in = '' ) {
        if ( '' != $in ) {
            $this->in = $in;
        }
        $res = $this->grep_html( $this->in, '@<meta name=(\'|")viewport(\'|") content=(\'|")(.*?)(\'|")@ims' );
        if( $res['total'] > 0 ) {
            $this->out['error'] = false;
            $this->out['data'] = Array( __( 'Viewport tag exists: ' . $res['out'][0][0], 'ratify' ) );
        } else {
            $this->out['error'] = __( 'No viewport tags were found. This is normally bad.', 'ratify' );
        }
        return $this->out;
    }
}

