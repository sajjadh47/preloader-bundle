<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @since      2.0.0
 * @package    Preloader_Bundle
 * @author     Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}
