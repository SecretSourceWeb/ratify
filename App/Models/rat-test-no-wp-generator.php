<?php 
namespace Ratify\Models;

class RatTestNoWPGenerator extends RatTestBase {

    public function __construct( $in = null ) {
        parent::__construct( $in );
        $this->out['title'] = __( 'WP Generator Meta Tag Removed', 'ratify' );
    }

    public function runtest( $in = '' ) {
        if ( '' != $in ) {
            $this->in = $in;
        }
        $res = $this->grep_html( $this->in, '@<meta name="generator" content="WordPress@i' );

        if( 1 == $res['total'] ) {
            $this->out['error'] = __( 'The Generator Meta Tag is still appearing. Chances are it has been hard-coded in header.php.', 'ratify' );
            $this->out['data'] = Array();
        } else {
            $this->out['error'] = false;
            $this->out['data'] = Array();
        }
        return $this->out;
    }

    /*
     */
    public static function remove_generator_meta_tag() {
        remove_action('wp_head', 'wp_generator');
        add_filter('the_generator', '__return_empty_string');
    }
}

