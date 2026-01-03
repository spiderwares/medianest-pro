<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'MDDR_PRO' ) ) :

/**
 * Main MDDR_PRO Class
 */
final class MDDR_PRO {

    /**
     * The single instance of the class.
     *
     * @var MDDR_PRO
     */
    protected static $instance = null;

    /**
     * Constructor for the class.
     * 
     */
    public function __construct() {
        $this->event_handler();
        $this->includes();
    }

    /**
     * Initialize hooks and filters.
     * 
     */
    private function event_handler() {
        // Register plugin activation hook
        register_activation_hook( MDDR_PRO_FILE, array( 'MDDR_Install_Pro', 'install' ) );
        add_action( 'plugins_loaded', array( $this, 'mddr_install' ), 11 );
        add_action( 'mddr_init', array( $this, 'includes' ), 11 );
    }

    /**
     * Get the single instance of MDDR_PRO
     *
     * @return MDDR_PRO
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) :
            self::$instance = new self();
            do_action( 'mddr_plugin_loaded' );
        endif;
        return self::$instance;
    }

    /**
     * Function to display admin notice if Swiper Slider is not active.
     * 
     */
    public function mddr_admin_notice() {
        ?>
        <div class="notice notice-error">
            <p><?php echo esc_html( 'Media Directory is activated but not effective — the required Media Directory  plugin free plugin is missing or inactive. Please install and activate the free Media Directory plugin to enable all Pro features.', 'media-directory-pro' ); ?></p>
        </div>
        <?php
    }

    /**
     * Function to initialize the plugin after checking free version.
     * 
     */
    public function mddr_install() {
        if ( ! class_exists( 'MDDR_Settings_Fields' ) ) :
            add_action( 'admin_notices', array( $this, 'mddr_admin_notice' ) );
        else:
            do_action( 'mddr_init' );
        endif;
    }

    /**
     * Include required files.
     * 
     */
    public function includes() {
        if ( is_admin() ) :
            $this->includes_admin();
        else:
            $this->includes_public();
        endif;

        require_once MDDR_PRO_PATH . 'includes/mddr-core-functions-pro.php';
        require_once MDDR_PRO_PATH . 'includes/admin/settings/PageBuilders/WPBakery/class-mddr-wpbakery.php';
        require_once MDDR_PRO_PATH . 'includes/admin/settings/PageBuilders/Divi/class-mddr-init.php';
    }

    /**
     * Include admin files.
     * 
     */
    public function includes_admin() {
        require_once MDDR_PRO_PATH . 'includes/class-mddr-install-pro.php';
        require_once MDDR_PRO_PATH . 'includes/admin/settings/class-mddr-settings-pro.php';
        require_once MDDR_PRO_PATH . 'includes/admin/settings/class-mddr-admin-menu-pro.php';
        require_once MDDR_PRO_PATH . 'includes/admin/settings/class-mddr-media-pro.php';
        require_once MDDR_PRO_PATH . 'includes/admin/settings/Download/class-mddr-download.php';
        require_once MDDR_PRO_PATH . 'includes/admin/settings/Duplicate/class-mddr-duplicate.php';
        require_once MDDR_PRO_PATH . 'includes/admin/settings/Color-Picker/class-mddr-color-picker.php';
        require_once MDDR_PRO_PATH . 'includes/admin/settings/Pin-Top/class-mddr-pin-top.php';
        require_once MDDR_PRO_PATH . 'includes/admin/settings/class-mddr-cpt-pro.php';
    }

    /**
     * Include public files.
     * 
     */
    public function includes_public() {
    }
}

endif;
