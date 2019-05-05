<?php 
namespace Ratify\Models;

class RatTestFeaturedImage extends RatTestBase {

    public function __construct( $in = null ) {
        parent::__construct( $in );
        $this->out['title'] = __( 'Home Page Featured Image', 'ratify' );
    }

    public function runtest( $in = '' ) {
        if ( '' != $in ) {
            $this->in = $in;
        }
        $ID = get_option('page_on_front', true);
        $fi = get_the_post_thumbnail($ID);
        $fi = wp_get_attachment_image_src( get_post_thumbnail_id( $ID ), 'full' );

        if( '' != $fi ) {
            if( $fi[1] >= 1200 and $fi[2] >= 300 ) {
                $this->out['error'] = false;
                $this->out['data'] = $fi;
            } else {
                if( $fi[1] < 1200 ) {
                    $this->out['error'] = sprintf(
                        __( 'The home page has a featured image but it is not wide enough (%spx). It needs to be at least 1200px wide.', 'ratify' ),
                        $fi[1]
                    );
                    $this->out['data'] = $fi;
                } else {
                    $this->out['error'] = sprintf(
                        __( 'The home page has a featured image but it is not tall enough (%spx). It needs to be at least 300px high.', 'ratify' ),
                        $fi[2]
                    );
                    $this->out['data'] = $fi;
                }
            }
        } else {
            $this->out['error'] = __( 'No featured image was found on the front page.', 'ratify' );
        }
        return $this->out;
    }
}

