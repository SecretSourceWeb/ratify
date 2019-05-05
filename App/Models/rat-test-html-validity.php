<?php 
namespace Ratify\Models;

class RatTestHTMLValidity extends RatTestBase {

    public function __construct( $in = null ) {
        parent::__construct( $in );
        $this->out['title'] = __( 'HTML Code Quality', 'ratify' );
    }

    /*
     * Common problems and solutions. This should really be in a FAQ for this test
     * https://stackoverflow.com/questions/29123445/validation-error-the-itemprop-attribute-was-specified-but-the-element-is-not
     */
    public function runtest( $in = '' ) {
        if ( '' != $in ) {
            $this->in = $in;
        }
        $htmlvalidation = get_transient( 'ratp-htmlvalidation' );
        // sets the name of temporary storage to something unique for use on multisite
        $domain = preg_replace( '@\.@', '-', $_SERVER['HTTP_HOST'] );
        if ( false === $htmlvalidation or ! empty( $_GET['refresh'] ) ) {
            $homehtml = plugin_dir_path(__DIR__) . 'Storage/' . $domain . '-home.html';
            // would love to use more modern filesystem protocols here
            $fp = fopen( $homehtml, 'w' );
            if( $fp ) {
                if( ! fwrite( $fp, $this->in ) ) {
                    $this->out['error'] = __( 'Unable to write the home page HTML to a file for testing: ' . $homehtml, 'ratify' );
                    return $this->out;
                }
                fclose( $fp );
            } else {
                $this->out['error'] = __( 'Unable to open a file for saving the home page HTML to a file for testing.', 'ratify' );
                return $this->out;
            }

            // https://github.com/validator/validator/wiki/Service-%C2%BB-Input-%C2%BB-POST-body
            $cmd = 'curl -H "Content-Type: text/html; charset=utf-8" '
                . '--data-binary @' . $homehtml . ' https://validator.w3.org/nu/\?out\=json';
            exec($cmd, $stdout, $return_val);

            if ( 0 == $return_val ) {
                $htmlvalidation = $stdout[0];
                set_transient( 'ratp-htmlvalidation', $htmlvalidation, MINUTE_IN_SECONDS * 120 );
            } else {
                $this->out['error'] = __( 'There was an error trying to validate the home page HTML.', 'ratify' );
                $this->out['data'] = $stdout;
                return $this->out;
            }
        }

        $res = json_decode( $htmlvalidation );
        $total_messages = count( $res->messages );
        for ($i = 0; $i < $total_messages; $i++) {
            if ( 'error' == $res->messages[$i]->type ) {
                $this->out['data'][] = $res->messages[$i]->message;
            }
        }
        if ( count( $this->out['data'] ) > 0 ) {
            $this->out['error'] = 'Found ' . count( $this->out['data'] ) . ' error(s) in the HTML.'
                . ' Use the W3 Validator to see detailed results.';
        } else {
            // all tests have passed
            $this->out['error'] = false;
        }

        return $this->out;
    }
}

