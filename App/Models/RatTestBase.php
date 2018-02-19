<?php 
namespace Ratify\Models;

class RatTestBase {

    public $in = '';
    public $out = Array();

    public function __construct( $in = null ) {
        $this->in = $in;
        $this->out['title'] = 'Uninitialized test';
        $this->out['error'] = '(' . __( 'Tests must include a text description of what the error is', 'ratify' ) . ')';
        $this->out['data'] = Array();
        $this->out['modify_url'] = '';
        $this->out['warning_url'] = '';
        return true;
    }

    public function runtest( $in = '' ) {
        do_action('ratp_runtest_start');
        if ( '' != $in ) {
            $this->in = $in;
        }
        return $this->out;
    }

    public static function grep_html( $in = '', $p = '@<title[^>]*>(.*?)</title>@ims' ) {
        $matches = preg_match_all($p, $in, $pieces);

        return Array(
            'total' => $matches,
            'in' => $in,
            'p' => $p,
            'out' => $pieces
        );
    }
}
