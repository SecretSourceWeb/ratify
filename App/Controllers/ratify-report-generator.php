<?php
/**
 * Ratify Core API: RatifyReportGenerator class
 *
 * @package   Ratify
 * @author    Ted Stresen-Reuter <ted@secret-source.eu>
 * @copyright 2018 Secret Source Technology SL
 * @license   https://github.com/SecretSourceWeb/ratify/blob/master/LICENSE.txt GNU General Public License v2.0
 */

namespace Ratify\Controllers;

use Ratify\Models;

/**
 * Generates the report by running all of the tests and then calling the report view.
 */
class RatifyReportGenerator {

	/**
	 * The main function for this class. It runs all the tests and
	 * produces the report. It also supports refreshing the cached
	 * version of the page being tested.
	 */
	public function index() {

		$ratp_tests      = array();
		$break_cache_url = false;

		if ( empty( $_GET['refresh'] ) ) {
			$break_cache_url = esc_url( add_query_arg( [ 'refresh' => '1' ] ) );
		}

		$home_page_html = $this->get_home_page_html();

		$robotstxt = $this->get_robotstxt();

		// note that this must come AFTER the two lines above or the refresh confirmation will never display.
		if ( get_transient( 'ratp-cache-refreshed' ) === true && empty( $_GET['refresh'] ) ) {
			Models\RatifyNotifier::success( __( 'The cache has been refreshed!', 'ratify' ) );
			delete_transient( 'ratp-cache-refreshed' );
		}

		//phpcs:ignore Generic.Formatting.MultipleStatementAlignment.NotSameWarning -- it actually looks worse if we follow the standard
		$obj = new Models\RatTestTitle( $home_page_html );
		$ratp_tests['hasTitle'] = $obj->runtest();

		//phpcs:ignore Generic.Formatting.MultipleStatementAlignment.NotSameWarning -- it actually looks worse if we follow the standard
		$obj = new Models\RatTestMetaDescription( $home_page_html );
		$ratp_tests['hasDescription'] = $obj->runtest();

		//phpcs:ignore Generic.Formatting.MultipleStatementAlignment.NotSameWarning -- it actually looks worse if we follow the standard
		$obj = new Models\RatTestAltAttributesOnImages( $home_page_html );
		$ratp_tests['imagesHaveAlt'] = $obj->runtest();

		//phpcs:ignore Generic.Formatting.MultipleStatementAlignment.NotSameWarning -- it actually looks worse if we follow the standard
		$obj = new Models\RatTestHeadingElements( $home_page_html );
		$ratp_tests['hasH1'] = $obj->runtest();

		//phpcs:ignore Generic.Formatting.MultipleStatementAlignment.NotSameWarning -- it actually looks worse if we follow the standard
		$obj = new Models\RatTestOpenGraph( $home_page_html );
		$ratp_tests['OGtags'] = $obj->runtest();

		//phpcs:ignore Generic.Formatting.MultipleStatementAlignment.NotSameWarning -- it actually looks worse if we follow the standard
		$obj = new Models\RatTestHTMLValidity( $home_page_html );
		$ratp_tests['htmlIsValid'] = $obj->runtest();

		//phpcs:ignore Generic.Formatting.MultipleStatementAlignment.NotSameWarning -- it actually looks worse if we follow the standard
		$obj = new Models\RatTestGA( $home_page_html );
		$ratp_tests['hasGA'] = $obj->runtest();

		//phpcs:ignore Generic.Formatting.MultipleStatementAlignment.NotSameWarning -- it actually looks worse if we follow the standard
		$obj = new Models\RatTestRobotstxt( $robotstxt );
		$ratp_tests['hasRobots'] = $obj->runtest();

		//phpcs:ignore Generic.Formatting.MultipleStatementAlignment.NotSameWarning -- it actually looks worse if we follow the standard
		$obj = new Models\RatTestRobotsMeta( $home_page_html );
		$ratp_tests['hasRobotsMeta'] = $obj->runtest();

		//phpcs:ignore Generic.Formatting.MultipleStatementAlignment.NotSameWarning -- it actually looks worse if we follow the standard
		$obj = new Models\RatTestViewport( $home_page_html );
		$ratp_tests['hasViewport'] = $obj->runtest();

		//phpcs:ignore Generic.Formatting.MultipleStatementAlignment.NotSameWarning -- it actually looks worse if we follow the standard
		$obj = new Models\RatTestFeaturedImage( $home_page_html );
		$ratp_tests['hasFeaturedImage'] = $obj->runtest();

		//phpcs:ignore Generic.Formatting.MultipleStatementAlignment.NotSameWarning -- it actually looks worse if we follow the standard
		$obj = new Models\RatTestQueryStrings( $home_page_html );
		$ratp_tests['hasQueryStrings'] = $obj->runtest();

		//phpcs:ignore Generic.Formatting.MultipleStatementAlignment.NotSameWarning -- it actually looks worse if we follow the standard
		$obj = new Models\RatTestHTTPS( $home_page_html );
		$ratp_tests['hasHTTPS'] = $obj->runtest();

		//phpcs:ignore Generic.Formatting.MultipleStatementAlignment.NotSameWarning -- it actually looks worse if we follow the standard
		$obj = new Models\RatTestGZIP( $home_page_html );
		$ratp_tests['hasGZIP'] = $obj->runtest();

		//phpcs:ignore Generic.Formatting.MultipleStatementAlignment.NotSameWarning -- it actually looks worse if we follow the standard
		$obj = new Models\RatTestNoEmoji( $home_page_html );
		$ratp_tests['emojisRemoved'] = $obj->runtest();

		//phpcs:ignore Generic.Formatting.MultipleStatementAlignment.NotSameWarning -- it actually looks worse if we follow the standard
		$obj = new Models\RatTestNoWPGenerator( $home_page_html );
		$ratp_tests['generatorRemoved'] = $obj->runtest();

		//phpcs:ignore Generic.Formatting.MultipleStatementAlignment.NotSameWarning -- it actually looks worse if we follow the standard
		$obj = new Models\RatTestCronActivated();
		$ratp_tests['cronActivated'] = $obj->runtest();

		// this is how we can allow add-on plugins to add additional tests.
		$ratp_tests = apply_filters( 'ratp_before_view_test_results', $ratp_tests );

		return ratify_view(
			'admin.report',
			[
				'home_url'        => home_url(),
				'break_cache_url' => $break_cache_url,
				'title'           => 'Ratify Checklist',
				'tests'           => $ratp_tests,
			]
		);
	}

	/**
	 * Gets the target (home) page HTML and saves it.
	 */
	protected function get_home_page_html() {
		$home_page_html = get_transient( 'ratp-home-page-html' );
		$args           = array(
			'sslverify' => false,
		);
		if ( false === $home_page_html || '' === $home_page_html || ! empty( $_GET['refresh'] ) ) {
			$home_page_html = wp_remote_retrieve_body(
				wp_remote_get( home_url(), $args )
			);
			// did we actually get a page?
			if ( '' === $home_page_html ) {
				$res = wp_remote_get( home_url(), $args );
				?>
				<h2><?php esc_html_e( 'Ratify Error', 'ratify' ); ?></h2>
				<div class="notice notice-error">
					<p><?php esc_html_e( 'Oops! We were unable to retrieve the home page.', 'ratify' ); ?></p>
				</div>
				<?php
				wp_die( esc_html( var_dump( $res ) ) );
			}
			set_transient( 'ratp-home-page-html', $home_page_html, MINUTE_IN_SECONDS * 120 );
			set_transient( 'ratp-cache-refreshed', true, MINUTE_IN_SECONDS );
			// the following is a hack
			// the proper solution would use admin hooks, but this is good enough for now.
			?>
			<script>window.location.href = "<?php echo esc_attr( admin_url( 'admin.php?page=ratify-report' ) ); ?>";</script>
			<?php
			exit;
		}
		return $home_page_html;
	}

	/**
	 * Gets the value of robots.txt. We use HTTP instead of just
	 * reading from the filesystem as some plugins generate the response
	 * on the fly (the file doesn't really live on the filesystem).
	 */
	protected function get_robotstxt() {
		$robotstxt = get_transient( 'ratp-robotstxt' );
		$args      = array(
			'sslverify' => false,
		);
		if ( false === $robotstxt || ! empty( $_GET['refresh'] ) ) {
			$rtr     = wp_remote_get( home_url() . '/robots.txt', $args );
			$rtrcode = wp_remote_retrieve_response_code( $rtr );
			if ( $rtrcode < 200 || $rtrcode > 302 ) {
				$robotstxt = '';
			} else {
				$robotstxt = wp_remote_retrieve_body( $rtr );
			}
			set_transient( 'ratp-robotstxt', $robotstxt, MINUTE_IN_SECONDS * 120 );
		}
		return $robotstxt;
	}
}
