<?php

/*
Plugin Name: Feeligo
Plugin URI:http://www.feeligo.com/
Description: Feeligo is the new way to monetize, engage and expand a specialized social network. Feeligo enables users to buy virtual gifts and send them to each other. 
Version: 1.0
Author: Marieke Gueye, Davide Bonapersona <tech@feeligo.com
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/
// Define a constant that we can use to construct file paths throughout the component
define( 'BP_FEELIGO_PLUGIN_DIR', dirname( __FILE__ ) );

 /* Only load the component if BuddyPress is loaded and initialized. */
function bp_feeligo_init() {
	// Because our loader file uses BP_Component, it requires BP 1.5 or greater.
	if ( version_compare( BP_VERSION, '1.3', '>' ) )
		require( dirname( __FILE__ ) . '/includes.php' );
}
add_action( 'bp_include', 'bp_feeligo_init' );

?>
