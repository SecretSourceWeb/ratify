<?php

namespace Ratify\Controllers;

use Ratify\Models;

class RatifyReportGenerator {

    public function index() {

        $ratp_tests = Array();
        $break_cache_url = false;

        if ( empty( $_GET['refresh'] ) ) {
            $break_cache_url = admin_url('admin.php?page=ratify-report&refresh=1');
        }

        $home_page_html = $this->get_home_page_html();

        $robotstxt = $this->get_robotstxt();

        // note that this must come AFTER the two lines above or the refresh confirmation will never display
        if ( get_transient( 'ratp-cache-refreshed' ) == true and empty( $_GET['refresh'] ) ) {
            Models\RatifyNotifier::success(__( 'The cache has been refreshed!', 'ratify' ) );
            delete_transient( 'ratp-cache-refreshed' );
        }

        $obj = new Models\RatTestTitle( $home_page_html );
        $ratp_tests['hasTitle'] = $obj->runtest();

        $obj = new Models\RatTestMetaDescription( $home_page_html );
        $ratp_tests['hasDescription'] = $obj->runtest();

        $obj = new Models\RatTestAltAttributesOnImages( $home_page_html );
        $ratp_tests['imagesHaveAlt'] = $obj->runtest();

        $obj = new Models\RatTestHeadingElements( $home_page_html );
        $ratp_tests['hasH1'] = $obj->runtest();

        $obj = new Models\RatTestOpenGraph( $home_page_html );
        $ratp_tests['OGtags'] = $obj->runtest();

        $obj = new Models\RatTestHTMLValidity( $home_page_html );
        $ratp_tests['htmlIsValid'] = $obj->runtest();

        $obj = new Models\RatTestGA( $home_page_html );
        $ratp_tests['hasGA'] = $obj->runtest();

        $obj = new Models\RatTestRobotstxt( $robotstxt );
        $ratp_tests['hasRobots'] = $obj->runtest();

        $obj = new Models\RatTestRobotsMeta( $home_page_html );
        $ratp_tests['hasRobotsMeta'] = $obj->runtest();

        $obj = new Models\RatTestViewport( $home_page_html );
        $ratp_tests['hasViewport'] = $obj->runtest();

        $obj = new Models\RatTestFeaturedImage( $home_page_html );
        $ratp_tests['hasFeaturedImage'] = $obj->runtest();

        $obj = new Models\RatTestQueryStrings( $home_page_html );
        $ratp_tests['hasQueryStrings'] = $obj->runtest();

        $obj = new Models\RatTestHTTPS( $home_page_html );
        $ratp_tests['hasHTTPS'] = $obj->runtest();

        $obj = new Models\RatTestGZIP( $home_page_html );
        $ratp_tests['hasGZIP'] = $obj->runtest();

        $obj = new Models\RatTestNoEmoji( $home_page_html );
        $ratp_tests['emojisRemoved'] = $obj->runtest();

        $obj = new Models\RatTestNoWPGenerator( $home_page_html );
        $ratp_tests['generatorRemoved'] = $obj->runtest();

        $obj = new Models\RatTestCronActivated();
        $ratp_tests['cronActivated'] = $obj->runtest();

        // this is how we can allow add-on plugins to add additional tests
        $ratp_tests = apply_filters( 'ratp_before_view_test_results', $ratp_tests );

        return ratify_view('admin.report', [
            'home_url' => home_url(),
            'break_cache_url' => $break_cache_url,
            'title'   => 'Ratify Checklist',
            'tests' => $ratp_tests
        ]);
    }

    protected function get_home_page_html() {
        $home_page_html = get_transient( 'ratp-home-page-html' );
        $args = array(
            'sslverify' => false
        );
        if( false === $home_page_html or '' == $home_page_html or ! empty( $_GET['refresh'] ) ) {
            $home_page_html = wp_remote_retrieve_body(
                wp_remote_get( home_url(), $args )
            );
            // did we actually get a page?
            if( '' == $home_page_html ) {
                $res = wp_remote_get( home_url(), $args );
                ?>
                <h2><?php _e( 'Ratify Error', 'ratify' ); ?></h2>
                <div class="notice notice-error">
                    <p><?php _e( 'Oops! We were unable to retrieve the home page.', 'ratify' ); ?></p>
                </div><?php
                wp_die($res);
            }
            set_transient( 'ratp-home-page-html', $home_page_html, MINUTE_IN_SECONDS * 120 );
            set_transient( 'ratp-cache-refreshed', true, MINUTE_IN_SECONDS );
            // the following is a hack
            // the proper solution would use admin hooks, but this is good enough for now.
            ?>
            <script>window.location.href = "<?php echo admin_url('admin.php?page=ratify-report'); ?>";</script>
            <?php
            exit;
        }
        return $home_page_html;
    }

    protected function get_robotstxt () {
        $robotstxt = get_transient( 'ratp-robotstxt' );
        $args = array(
            'sslverify' => false
        );
        if ( false === $robotstxt or ! empty( $_GET['refresh'] ) ) {
            $rtr = wp_remote_get( home_url() . '/robots.txt', $args );
            $rtrcode = wp_remote_retrieve_response_code( $rtr );
            if( $rtrcode < 200 or $rtrcode > 302 ) {
                $robotstxt = '';
            } else {
                $robotstxt = wp_remote_retrieve_body( $rtr );
            }
            set_transient( 'ratp-robotstxt', $robotstxt, MINUTE_IN_SECONDS * 120 );
        }
        return $robotstxt;
    }
}
