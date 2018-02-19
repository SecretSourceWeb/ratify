<?php

namespace Ratify\Models;

class RatifyNotifier {
    
    public static function success( $msg ) {
        self::getHTML( $msg, 'success' );
    }
    
    public static function getHTML ( $msg, $class ) {
        add_action( 'admin_notices', function( $msg, $class ) {
            ?>
            <div class="notice notice-<?php echo $class; ?> is-dismissible">
                <p><?php _e( $msg, 'ratify' ); ?></p>
            </div>
            <?php
        });
    }
}