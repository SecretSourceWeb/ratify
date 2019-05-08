<?php
/**
 * Ratify Core API: report view template
 *
 * @package   Ratify
 * @author    Ted Stresen-Reuter <ted@secret-source.eu>
 * @copyright 2018 Secret Source Technology SL
 * @license   https://github.com/SecretSourceWeb/ratify/blob/master/LICENSE.txt GNU General Public License v2.0
 */

?>
<div id="ratify-container">
	<h1><?php esc_html_e( 'Ratify Checklist', 'ratify' ); ?></h1>
	<hr>
	<p>
		<?php
		esc_html_e( 'The Ratify Checklist helps you spot common technical issues with the home page of your web site. ', 'ratify' );
		esc_html_e( 'Under normal circumstances, the plugin will try to fix the issues for you automatically but where it can\'t, it will flag them for you here. ', 'ratify' );
		printf(
			esc_html( 'This plugin was created by %s. We provide web application development services to clients throughout the world :-)', 'ratify' ),
			'<a href="https://secret-source.eu/">Secret Source Technolgy</a>'
		)
		?>
	</p>
	<b>
	<?php
	printf(
		esc_html( 'Test results for %s.', 'ratify' ),
		'<a href="' . esc_attr( home_url() ) . '">' . esc_html( home_url() ) . '</a>'
	)
	?>
	</b>

	<?php if ( $break_cache_url ) : ?>
		<p><?php esc_html_e( 'Test input is cached.', 'ratify' ); ?> <a href="<?php echo esc_html( $break_cache_url ); ?>"><?php esc_html_e( 'Click here to refresh the cache.', 'ratify' ); ?></a></p>
	<?php endif; ?>

	<?php foreach ( $tests as $test ) : ?>
		<div class="ratify-panel <?php echo ! empty( $test['error'] ) ? 'error' : 'ok'; ?>">
			<h2><?php echo esc_html( $test['title'] ); ?></h2>
			<?php if ( ! empty( $test['error'] ) ) : ?>
				<p><?php echo esc_html( $test['error'] ); ?></p>
				<?php if ( 'IMAGE Elements Have ALT Attributes' === $test['title'] ) : ?>
					<p><?php esc_html_e( 'Remember that you need to edit the page that the images appear in to change the ALT attributes.', 'ratify' ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $test['modify_url'] ) ) : ?>
					<p><a href="<?php echo esc_attr( esc_url( $test['modify_url'] ) ); ?>" target="_blank"><?php esc_html_e( 'Modify this setting', 'ratify' ); ?></a></p>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ( is_array( $test['data'] ) ) : ?>
			<ul>
				<?php foreach ( $test['data'] as $result ) : ?>
					<?php if ( 'IMAGE Elements Have ALT Attributes' === $test['title'] ) : ?>
					<li><a href="<?php echo esc_attr( esc_url( $result ) ); ?>" target="_blank"><?php echo esc_html( $result ); ?></a></li>
				<?php else : ?>
					<li class="code">"<?php echo esc_html( $result ); ?>"</li>
				<?php endif; ?>
			<?php endforeach; ?>
			</ul>
			<?php endif; ?>
			<?php if ( ! empty( $test['warning_url'] ) ) : ?>
				<p><a href="<?php echo esc_attr( esc_url( $test['warning_url'] ) ); ?>" target="_blank"><?php esc_html_e( 'Background and Suggestions', 'ratify' ); ?></a></p>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>
