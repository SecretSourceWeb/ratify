<?php 
namespace Ratify\Models;

class RatTestOpenGraph extends RatTestBase {

    public function __construct( $in = null ) {
        parent::__construct( $in );
        $this->out['title'] = __( 'Facebook (Open Graph) Tags Exist', 'ratify' );
    }

    public function runtest( $in = '' ) {
        if ( '' != $in ) {
            $this->in = $in;
        }
        $res = $this->grep_html( $this->in, '@<meta property=((\'|")og:.*?)/>@ims' );

        if( $res['total'] > 0 ) {
            $this->out['error'] = false;
            $tags = Array();
            foreach ($res['out'][1] as $item) {
                $p = '@og:([a-z0-9\-_\:]+?)" content="(.*?)"@i';
                preg_match( $p, $item, $pcs );
                if ( count( $pcs ) > 0 ) {
                    $tags[] = "{$pcs[1]}: " . html_entity_decode( $pcs[2] );
                }
                unset( $pcs );
            }
            $this->out['data'] = $tags;
        } else {
            $this->out['error'] = __( 'No OG: tags were found in the HTML.', 'ratify' );
            $this->out['data'] = Array();
        }
        return $this->out;
    }
}

