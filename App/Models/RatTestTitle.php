<?php 
namespace Ratify\Models;

class RatTestTitle extends RatTestBase {

    const MIN_TITLE_LEN = 15;

    const MAX_TITLE_LEN = 71;

    public function __construct( $in = null ) {
        parent::__construct( $in );
        $this->out['title'] = __( 'TITLE Element', 'ratify' );
    }

    /**
     * Based on https://moz.com/learn/seo/title-tag
     *  and on http://www.thesempost.com/new-title-description-lengths-for-google-seo/
     * Tests to make sure there is a TITLE element in the input
     *
     * If there is no title element, or the element is empty or there is
     * more than one title element, or if the one title element has less
     * than 3 or more than 8 words, this test fails.
     *
     *
     * @param $in string HTML to be examined
     * @return $result True if test passes, array otherwise
     *    $this->out['title'] = string Title of this test
     *    $this->out['error'] = string false or string if true
     *    $this->out['data'] = array The data we are testing
     *
     */
    public function runtest( $in = '' ) {
        if ( '' != $in ) {
            $this->in = $in;
        }
        $res = $this->grep_html( $this->in );

        if( 1 == $res['total'] ) {
            $chars = strlen( $res['out'][1][0] );
            if( $chars >= self::MIN_TITLE_LEN and $chars <= self::MAX_TITLE_LEN ) {

                $this->out['error'] = false;
                $this->out['data'] = Array( $res['out'][1][0] );

            } else {

                $this->out['error'] = sprintf(
                    __( 'The title element is either too long or too '
                        . 'short. Titles should be between %1$s and %2$s '
                        . 'characters.', 'ratify' ),
                    self::MIN_TITLE_LEN,
                    self::MAX_TITLE_LEN
                );
                $this->out['data'] = Array( $res['out'][1][0] );

            }
        } else {
            if( 0 == $res['total'] ) {

                $this->out['error'] = __( 'No title element could be found.', 'ratify' );
                $this->out['data'] = '';

            } else {

                $this->out['error'] = __( 'More than one title element was found.', 'ratify' );
                $this->out['data'] = $res['out'][1];

            }
        }
        return $this->out;
    }
}

