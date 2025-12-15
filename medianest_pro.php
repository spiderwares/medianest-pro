<?php
/**
 * Plugin Name:       Medianest Pro
 * Description:       Sort and organize media files with simple, powerful folder tools.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            cosmicinfosoftware
 * Author URI:        https://cosmicinfosoftware.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       medianest
 *
 * @package Medianest_Pro
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) :
    exit;
endif;

if ( ! defined( 'WPMN_PRO_FILE' ) ) :
    define( 'WPMN_PRO_FILE', __FILE__ ); // Define the plugin file path.
endif;

if ( ! defined( 'WPMN_PRO_BASENAME' ) ) :
    define( 'WPMN_PRO_BASENAME', plugin_basename( WPMN_PRO_FILE ) ); // Define the plugin basename.
endif;

if ( ! defined( 'WPMN_PRO_VERSION' ) ) :
    define( 'WPMN_PRO_VERSION', '1.0.0' ); // Plugin version
endif;

if ( ! defined( 'WPMN_PRO_PATH' ) ) :
    define( 'WPMN_PRO_PATH', plugin_dir_path( __FILE__ ) ); // Absolute path to plugin directory
endif;

if ( ! defined( 'WPMN_PRO_URL' ) ) :
    define( 'WPMN_PRO_URL', plugin_dir_url( __FILE__ ) ); // URL to plugin directory
endif;

if ( ! class_exists( 'WPMN_PRO', false ) ) :
    require_once WPMN_PRO_PATH . 'includes/class-wpmn-pro.php';
endif;

register_activation_hook( __FILE__, array( 'WPMN_Install_Pro', 'install' ) );

$GLOBALS['WPMN_Pro'] = WPMN_Pro::instance();