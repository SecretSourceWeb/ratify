<?php
namespace Ratify\Models;

class RatTestAltAttributesOnImages extends RatTestBase {

    public function __construct( $in = null ) {
        parent::__construct( $in );
        $this->out['title'] = __( 'IMAGE Elements Have ALT Attributes', 'ratify' );
    }

    public function runtest( $in = '' ) {
        parent::runtest( $in );

        $imagesWithoutAlts = Array();
        $res = $this->grep_html( $this->in, '@<img (.+?)(?=/>)@ims' );

        if( $res['total'] > 0 ) {
            // test each image for ALT attributes
            foreach ($res['out'][1] as $item) {
                $p = '@alt=(\'|")(.*?)(\'|")@ims';
                $alts = preg_match($p, $item, $pieces);
                if ( '' == trim( $pieces[2] ) ) {
                    preg_match( '@src=(\'|")(.*?)(\'|")@i', $item, $src );
                    $imagesWithoutAlts[] = admin_url( 'upload.php?item='
                        . ratify_get_attachment_id_from_src( $src[2] )
                    );
                }
            }

            if( count( $imagesWithoutAlts ) > 0 ) {
                $this->out['error'] = __( 'Some images were found without ALT attributes.', 'ratify' );
                $this->out['data'] = $imagesWithoutAlts;
            } else {
                $this->out['error'] = false;
                $this->out['data'] = '';
            }

        } else {

            $this->out['error'] = __( 'No images were found', 'ratify' );
            $this->out['data'] = $res['out'][0];

        }
        do_action('ratp_runtest_end');
        return $this->out;
    }
}

