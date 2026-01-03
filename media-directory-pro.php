<?php
/**
 * Plugin Name:       Media Directory Pro
 * Description:       Sort and organize media files with simple, powerful folder tools.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            cosmicinfosoftware
 * Author URI:        https://cosmicinfosoftware.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       media-directory-pro
 *
 * @package Media_Directory_Pro
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) :
    exit;
endif;

if ( ! defined( 'MDDR_PRO_FILE' ) ) :
    define( 'MDDR_PRO_FILE', __FILE__ ); // Define the plugin file path.
endif;

if ( ! defined( 'MDDR_PRO_BASENAME' ) ) :
    define( 'MDDR_PRO_BASENAME', plugin_basename( MDDR_PRO_FILE ) ); // Define the plugin basename.
endif;

if ( ! defined( 'MDDR_PRO_VERSION' ) ) :
    define( 'MDDR_PRO_VERSION', '1.0.0' ); // Plugin version
endif;

if ( ! defined( 'MDDR_PRO_PATH' ) ) :
    define( 'MDDR_PRO_PATH', plugin_dir_path( __FILE__ ) ); // Absolute path to plugin directory
endif;

if ( ! defined( 'MDDR_PRO_URL' ) ) :
    define( 'MDDR_PRO_URL', plugin_dir_url( __FILE__ ) ); // URL to plugin directory
endif;

if ( ! class_exists( 'MDDR_PRO', false ) ) :
    require_once MDDR_PRO_PATH . 'includes/class-mddr-pro.php';
endif;

register_activation_hook( __FILE__, array( 'MDDR_Install_Pro', 'install' ) );
register_deactivation_hook( __FILE__, array( 'MDDR_Install_Pro', 'deactivate' ) );

$GLOBALS['MDDR_Pro'] = MDDR_Pro::instance();