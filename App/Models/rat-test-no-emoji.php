<?php
namespace Ratify\Models;

class RatTestNoEmoji extends RatTestBase {

    public function __construct( $in = null ) {
        parent::__construct( $in );
        $this->out['title'] = __( 'Post Comment Emojis Removed', 'ratify' );
    }

    public function runtest( $in = '' ) {
        if ( '' != $in ) {
            $this->in = $in;
        }
        $res = $this->grep_html( $this->in, '@window\._wpemojiSettings@i' );

        if( 1 == $res['total'] ) {
            $this->out['error'] = __( 'Emojis support scripts are being loaded. WordPress must have changed or disable_wp_emojicons is not being called in the actionsAndFilters.php init sequence.', 'ratify' );
            $this->out['data'] = Array();
        } else {
            $this->out['error'] = false;
            $this->out['data'] = Array();
        }
        return $this->out;
    }

    /*
     * This code comes from https://wordpress.stackexchange.com/questions/185577/disable-emojicons-introduced-with-wp-4-2
     * I've disabled all but that that affects the front end as we don't
     * care about what's happening on the admin or in wp_mail.
     */
    public static function disable_wp_emojicons() {
        // all front end actions related to emojis
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

        // filter to remove TinyMCE emojis
        add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );

        // filter to remove preload
        add_filter( 'emoji_svg_url', '__return_false' );
    }
}

