<?php
namespace Ratify\Models;

// to do in the premium version of the plugin RATp Actioner
// add fix
// the benefit of this is that it will increase cacheability of these resources for sites that use SuperCache or Cloudflare.
// open the functions.php file of the active theme
// add this code to the end of the file
// function ratp_remove_ver_from_resources( $src ) {
    // if( strpos( $src, 'ver=' ) ) {
        // $src = remove_query_arg( 'ver', $src );
    // }
    // return $src;
// }
// add_filter( 'style_loader_src', 'ratp_remove_ver_from_resources', 9999 );
// add_filter( 'script_loader_src', 'ratp_remove_ver_from_resources', 9999 );

class RatTestQueryStrings extends RatTestBase {

    public function __construct( $in = null ) {

        parent::__construct( $in );

        $this->out['title'] = __( 'Query strings on static resources (CSS & JS)', 'ratify' );
//        $this->out['modify_url'] = 'https://sites.google.com/a/secret-source.eu/wiki/projects/ratp-plugin-solutions-fixes#query';

    }

    public function runtest( $in = '' ) {

        if ( '' != $in ) {

            $this->in = $in;

        }

        $res = $this->grep_html( $this->in, '@\?ver=[0-9\.]+@ims' );

        if( $res['total'] > 0 ) {

            $this->out['error'] = sprintf(
                __( '%s default ones exists. eg. ?ver=x.x.x', 'ratify' ),
                $res['total']
            );

        } else {

            $this->out['error'] = false;

            $this->out['data'] = Array( __( 'Default query strings are being stripped out.' . $res['out'][0][0], 'ratify' ) );

        }

        return $this->out;

    }

    public static function strip_default_query_stings( $link ) {
        global $wp_version;
        $newlink = $link;
        if ( false !== stripos( $link, '<link' ) ) {
            $tot = preg_match( '@href=(\'|")([^\'"]+?)(\'|")@i', $link, $pcs );
            $href = $pcs[2];
        } else {
            $tot = 1;
            $href = $link;
        }
        if ( $tot > 0 ) {
            if ( false !== stripos( $href, '?ver=' . $wp_version ) ) {
                // strip off the version
                $newhref = remove_query_arg( 'ver', $href );
                $newlink = str_replace( $href, $newhref, $link );
            }
        }
        return $newlink;
    }

}



