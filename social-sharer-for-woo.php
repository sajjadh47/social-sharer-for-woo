<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package           Social_Sharer_For_Woo
 * @author            Sajjad Hossain Sagor <sagorh672@gmail.com>
 *
 * Plugin Name:       Social Sharer For WooComerce
 * Plugin URI:        https://wordpress.org/plugins/social-sharer-for-woo/
 * Description:       Add attractive PluginDescription responsive social sharing icons with link to your woocommerce product pages.
 * Version:           2.0.2
 * Requires at least: 5.6
 * Requires PHP:      8.0
 * Author:            Sajjad Hossain Sagor
 * Author URI:        https://sajjadhsagor.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       social-sharer-for-woo
 * Domain Path:       /languages
 * Requires Plugins:  woocommerce
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'SOCIAL_SHARER_FOR_WOO_PLUGIN_VERSION', '2.0.2' );

/**
 * Define Plugin Folders Path
 */
define( 'SOCIAL_SHARER_FOR_WOO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

define( 'SOCIAL_SHARER_FOR_WOO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'SOCIAL_SHARER_FOR_WOO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-social-sharer-for-woo-activator.php
 *
 * @since    2.0.0
 */
function on_activate_social_sharer_for_woo() {
	require_once SOCIAL_SHARER_FOR_WOO_PLUGIN_PATH . 'includes/class-social-sharer-for-woo-activator.php';

	Social_Sharer_For_Woo_Activator::on_activate();
}

register_activation_hook( __FILE__, 'on_activate_social_sharer_for_woo' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-social-sharer-for-woo-deactivator.php
 *
 * @since    2.0.0
 */
function on_deactivate_social_sharer_for_woo() {
	require_once SOCIAL_SHARER_FOR_WOO_PLUGIN_PATH . 'includes/class-social-sharer-for-woo-deactivator.php';

	Social_Sharer_For_Woo_Deactivator::on_deactivate();
}

register_deactivation_hook( __FILE__, 'on_deactivate_social_sharer_for_woo' );

/**
 * The core plugin class that is used to define admin-specific and public-facing hooks.
 *
 * @since    2.0.0
 */
require SOCIAL_SHARER_FOR_WOO_PLUGIN_PATH . 'includes/class-social-sharer-for-woo.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_social_sharer_for_woo() {
	$plugin = new Social_Sharer_For_Woo();

	$plugin->run();
}

run_social_sharer_for_woo();
