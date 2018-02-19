<?php
namespace Ratify\Models;

class RatTestGZIP extends RatTestBase {

    public function __construct( $in = null ) {

        parent::__construct( $in );

        $this->out['title'] = __( 'GZIP compression', 'ratify' );
//        $this->out['modify_url'] = 'https://sites.google.com/a/secret-source.eu/wiki/projects/ratp-plugin-solutions-fixes#gzip';
        $this->out['warning_url'] = '';

    }

    public function runtest( $in = '' ) {
        parent::runtest( $in );
        $this->out['error'] = __( 'The Content-Encoding header was not found in the response. This is usually due to a misconfiguration of your web server.', 'ratify' );

        // is the site delivering with gzip?
        $cmd = 'curl -k -s -L -H "Accept-Encoding: gzip,deflate" -I ' . home_url() . ' | grep "Content-Encoding"';
        exec( $cmd, $result, $return_var );
        if ( count( $result ) == 1 ) {
            $parts = preg_split( '@: *@i', $result[0] );
            $val = trim( strtolower( $parts[1] ) );
            if ( strtolower( $parts[0] ) == 'content-encoding' ) {
                if( 1 === preg_match( '@gzip|deflate@i', $val ) ) {
                    $this->out['error'] = false;
                    $this->out['data'] = Array();
                    return $this->out;
                } else {
                    $this->out['error'] = __( 'Content-Encoding does not appear to be gzip', 'ratify' );
                    $this->out['data'] = Array( $parts[1] );
                }
            }
        }
        return $this->out;
    }

    /*
     * returns true if htaccess contains at least one mod_expires rule
     * returns string with explanation of problem otherwise (htaccess doesn't exist)
     */
    public function htaccess_sets_expires () {
        if( file_exists( get_home_path() . '.htaccess' ) ) {
            $cmd = "grep ExpiresActive " . get_home_path() . '.htaccess';
            exec( $cmd, $result, $return_var);
            if( 0 === $return_var ) {
                // that's good enough for me
                return true;
            } else {
                return 'An .htaccess file exists but it does not appear to be setting ExpiresActive (caching).';
            }
        } else {
            return "Can't find .htaccess in the project root.";
        }
    }
}