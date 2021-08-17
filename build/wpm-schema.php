<?php
/**
 * The main file of the <%= pkg.title %> plugin
 *
 * @package wpmschema
 * @version <%= pkg.version %>
 *
 * Plugin Name: <%= pkg.title %>
 * Plugin URI: <%= pkg.pluginUrl %>
 * Description: <%= pkg.description %>
 * Author: <%= pkg.author %>
 * Author URI: <%= pkg.authorUrl %>
 * Version: <%= pkg.version %>
 * Text Domain: wpmschema
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'WPM_SCHEMA_SLUG' ) ) {
	define( 'WPM_SCHEMA_SLUG', '<%= pkg.slug %>' );
}

if ( ! defined( 'WPM_SCHEMA_VERSION' ) ) {
	define( 'WPM_SCHEMA_VERSION', '<%= pkg.version %>' );
}

if ( ! defined( 'WPM_SCHEMA_URL' ) ) {
	define( 'WPM_SCHEMA_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'WPM_SCHEMA_PATH' ) ) {
	define( 'WPM_SCHEMA_PATH', plugin_dir_path( __FILE__ ) );
}

// Load the autoloader.
require WPM_SCHEMA_PATH . 'vendor/autoload.php';

// Load the `wp_wpmschema()` entry point function.
require WPM_SCHEMA_PATH . 'inc/functions.php';

if ( wp_get_environment_type() === 'development' ) {
	require WPM_SCHEMA_PATH . 'inc/test.php';
}

// Initialize the plugin.
call_user_func( 'WpMunich\wpmschema\wp_wpmschema' );
