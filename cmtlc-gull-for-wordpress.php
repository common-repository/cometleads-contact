<?php

/**
 * The plugin bootstrap file
 *
 *
 * @link              https://cometleads.com
 * @since             1.0.0
 * @package           CometLeads_Contact
 *
 * @wordpress-plugin
 * Plugin Name:       CometLeads Contact
 * Plugin URI:        https://wordpress.org/plugins/cometleads-contact
 * Description:       Replace your static contact form with a conversational chatbot and you’ll see more leads coming your way.
 * Version:           1.0.0
 * Author:            CometLeads
 * Author URI:        https://cometleads.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cmtlc-gull-wordpress-plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * System environment variables
 */
// to avoid name confliction with other plugins, prepend
// `CMTLC_GULL` on defines & class names
// `cmtlc_gull` on functions
// and `cmtlc-gull` on file names
define( 'CMTLC_GULL_ROOT_FILE', __FILE__);
require_once plugin_dir_path(__FILE__) . 'config/env.base.php';
// conditional include additional environment-relevant variables
// default to include DEVELOPMENT environment
$additional_env_path = plugin_dir_path(__FILE__) . 'config/env.php';
if (file_exists($additional_env_path)) {
  require_once $additional_env_path;
} else {
  require_once plugin_dir_path(__FILE__) . 'config/env.dev.php';
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/cmtlc-gull-activator.php
 */
function cmtlc_gull_activate_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/cmtlc-gull-activator.php';
	CMTLC_GULL_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/cmtlc-gull-deactivator.php
 */
function cmtlc_gull_deactivate_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/cmtlc-gull-deactivator.php';
	CMTLC_GULL_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'cmtlc_gull_activate_plugin');
register_deactivation_hook( __FILE__, 'cmtlc_gull_deactivate_plugin');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/cmtlc-gull-wordpress-plugin.php';

function cmtlc_gull_run_wordpress_plugin() {

	$plugin = new CMTLC_GULL_Wordpress_Plugin();
	$plugin->run();

}
cmtlc_gull_run_wordpress_plugin();
