<?php 
namespace Ratify\Models;

class RatTestHeadingElements extends RatTestBase {

    public function __construct( $in = null ) {
        parent::__construct( $in );
        $this->out['title'] = __( 'H1 element exists', 'ratify' );
    }

    public function runtest( $in = '' ) {
        if ( '' != $in ) {
            $this->in = $in;
        }
        $res = $this->grep_html( $this->in, '@<h([1-6])(.*?)>(.*?)</h(\\1)>@ims' );
        $headings = Array();

        if( $res['total'] > 0 ) {
            // is there at least one h1?
            for ($i = 0; $i < $res['total']; $i++) {
                if ( 1 == $res['out'][1][$i] ) {
                    $this->out['error'] = false;
                    $this->out['data'] = Array( strip_tags( $res['out'][3][$i] ) );
                }
                $headings[] = 'H' . $res['out'][1][$i] . '. ' . strip_tags( $res['out'][3][$i] );
            }

            if ( false !== $this->out['error'] ) {
                $this->out['error'] = __( 'Can\'t find any H1 elements.', 'ratify' );
            }
            $this->out['data'] = $headings;
        } else {
            $this->out['error'] = __( 'There are no heading elements.', 'ratify' );
            $this->out['data'] = $headings;
        }
        return $this->out;
    }
}

