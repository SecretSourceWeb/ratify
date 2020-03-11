<?php

define( 'RATIFY_PATH_TO_VIEWS', plugin_dir_path( __FILE__ ) . 'Views/' );

// self-update



function ratify_view( $view, array $vars ) {
	$path2view = RATIFY_PATH_TO_VIEWS . str_replace( '.', DIRECTORY_SEPARATOR, $view ) . '.php';
	if ( file_exists( $path2view ) ) {
		extract( $vars );
		include( $path2view );
	} else {
		throw new Exception( 'Can not find the view ' . $path2view );
	}
}

// https://gist.github.com/SubZane/3489225
function ratify_get_attachment_id_from_src( $src ) {
	global $wpdb;
	$reg = '/-[0-9]+x[0-9]+?.(jpg|jpeg|png|gif)$/i';
	$src1 = preg_replace( $reg, '', $src );
	if ( $src1 != $src ) {
		$ext = pathinfo( $src, PATHINFO_EXTENSION );
		$src = $src1 . '.' . $ext;
	}
	$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$src'";
	$id = $wpdb->get_var( $query );
	return $id;
}

function ratify_get_versioned_asset( $asset = 'style.css' ) {
	// gets the versioned versions of style.css
	$target = false;
	$file = json_decode( @file_get_contents( RATIFY_PLUGIN_DIR . 'mix-manifest.json' ), true );
	if ( false == $file or null == $file ) {
		// just return the normal asset, don't bother figuring out if there is a version
		switch ( $asset ) {
			case 'app.js':
				$target = '/js/app.js';
				break;
			default:
				$target = '/css/style.css';
				break;
		}
		$target = 'App/Views/public' . $target;
	} else {
		foreach ( $file as $key => $val ) {
			$pcs = preg_split( '@/@', $key );
			if ( $asset == $pcs[ count( $pcs ) - 1 ] ) {
				switch ( $pcs[ count( $pcs ) - 1 ] ) {
					case 'style.css':
						$target = substr( $val, 1 );
						break;
					case 'app.js':
						$target = substr( $val, 1 );
						break;
				}
			}
		}
	}
	return $target;
}
