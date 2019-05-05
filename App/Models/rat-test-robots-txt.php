<?php 
namespace Ratify\Models;

class RatTestRobotstxt extends RatTestBase {

    public function __construct( $in = null ) {
        parent::__construct( $in );
        $this->out['title'] = __( 'Site Indexing (robots.txt)', 'ratify' );
    }

    public function runtest( $in = '' ) {
        if ( '' != $in ) {
            $this->in = $in;
        }
        if( '' != $this->in ) {
            $this->out['error'] = false;
            $this->out['data'] = Array(
                __( 'A robots.txt file was found. It is up to you to determine if it is correct or not.', 'ratify' ),
                $this->in
            );
        } else {
            $this->out['error'] = __( 'No robots.txt was found. Please use Yoast or some other tool to install robots.txt', 'ratify' );
            $this->out['data'] = Array();
        }
        return $this->out;
    }
}

