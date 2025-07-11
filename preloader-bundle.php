<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package           Preloader_Bundle
 * @author            Sajjad Hossain Sagor <sagorh672@gmail.com>
 *
 * Plugin Name:       Preloader Bundle
 * Plugin URI:        https://wordpress.org/plugins/preloader-bundle/
 * Description:       Add Preloader To Your Site With Just One Click! 150+ Preloader To Select From.
 * Version:           2.0.1
 * Requires at least: 5.6
 * Requires PHP:      8.0
 * Author:            Sajjad Hossain Sagor
 * Author URI:        https://sajjadhsagor.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       preloader-bundle
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'PRELOADER_BUNDLE_PLUGIN_VERSION', '2.0.1' );

/**
 * Define Plugin Folders Path
 */
define( 'PRELOADER_BUNDLE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

define( 'PRELOADER_BUNDLE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'PRELOADER_BUNDLE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-preloader-bundle-activator.php
 *
 * @since    2.0.0
 */
function on_activate_preloader_bundle() {
	require_once PRELOADER_BUNDLE_PLUGIN_PATH . 'includes/class-preloader-bundle-activator.php';

	Preloader_Bundle_Activator::on_activate();
}

register_activation_hook( __FILE__, 'on_activate_preloader_bundle' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-preloader-bundle-deactivator.php
 *
 * @since    2.0.0
 */
function on_deactivate_preloader_bundle() {
	require_once PRELOADER_BUNDLE_PLUGIN_PATH . 'includes/class-preloader-bundle-deactivator.php';

	Preloader_Bundle_Deactivator::on_deactivate();
}

register_deactivation_hook( __FILE__, 'on_deactivate_preloader_bundle' );

/**
 * The core plugin class that is used to define admin-specific and public-facing hooks.
 *
 * @since    2.0.0
 */
require PRELOADER_BUNDLE_PLUGIN_PATH . 'includes/class-preloader-bundle.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_preloader_bundle() {
	$plugin = new Preloader_Bundle();

	$plugin->run();
}

run_preloader_bundle();
