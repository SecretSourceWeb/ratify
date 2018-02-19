<div id="ratify-container">
    <h1><?php _e( 'Ratify Checklist', 'ratify' ) ?></h1>
    <hr>
    <p>
        <?php
        _e( 'The Ratify Checklist helps you spot common technical issues with the home page of your web site. ', 'ratify' );
        _e( 'Under normal circumstances, the plugin will try to fix the issues for you automatically but where it can\'t, it will flag them for you here. ', 'ratify' );
        _e( 'This plugin was created by <a href="https://secret-source.eu/">Secret Source Technolgy</a>. We provide web application development services to clients throughout the world :-)', 'ratify' );
        ?>
    </p>
    <b><?php
    printf(
        __( 'Test results for <a href="%1$s">%2$s</a>.', 'ratify' ),
        home_url(),
        home_url()
    )
    ?></b>

    <?php if ($break_cache_url): ?>
        <p><?php _e( 'Test input is cached.', 'ratify' ); ?> <a href="<?php echo $break_cache_url; ?>"><?php _e( 'Click here to refresh the cache.', 'ratify' ); ?></a></p>
    <?php endif; ?>

    <?php foreach ($tests as $test): ?>
        <div class="ratify-panel <?php echo ! empty($test['error']) ? 'error' : 'ok'; ?>">
            <h2><?php echo $test['title']; ?></h2>
            <?php if ( ! empty( $test['error'] ) ): ?>
                <p><?php echo $test['error']; ?></p>
                <?php if ('IMAGE Elements Have ALT Attributes' == $test['title']): ?>
                    <p><?php _e( 'Remember that you need to edit the page that the images appear in to change the ALT attributes.', 'ratify' ); ?></p>
                <?php endif; ?>
                <?php if ( ! empty( $test['modify_url'] ) ): ?>
                    <p><a href="<?php echo $test['modify_url']; ?>" target="_blank"><?php _e( 'Modify this setting', 'ratify' ); ?></a></p>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ( is_array( $test['data'] ) ): ?>
            <ul>
            <?php foreach ($test['data'] as $result): ?>
                <?php if ($test['title'] == 'IMAGE Elements Have ALT Attributes'): ?>
                    <li><a href="<?php echo $result; ?>" target="_blank"><?php echo $result; ?></a></li>
                <?php else: ?>
                    <li class="code">"<?php echo $result; ?>"</li>
                <?php endif; ?>
            <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <?php if ( ! empty($test['warning_url']) ): ?>
                <p><a href="<?php echo $test['warning_url']; ?>" target="_blank"><?php _e( 'Background and Suggestions', 'ratify' ); ?></a></p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
