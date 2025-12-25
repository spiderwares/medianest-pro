<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPMN_PRO' ) ) :

/**
 * Main WPMN_PRO Class
 */
final class WPMN_PRO {

    /**
     * The single instance of the class.
     *
     * @var WPMN_PRO
     */
    protected static $instance = null;

    /**
     * Constructor
     */
    public function __construct() {
        $this->event_handler();
        $this->includes();
    }

    /**
     * Hook initialization
     */
    private function event_handler() {
        // Register plugin activation hook
        register_activation_hook( WPMN_PRO_FILE, array( 'WPMN_Install_Pro', 'install' ) );
        add_action( 'plugins_loaded', array( $this, 'wpmn_install' ), 11 );
        add_action( 'wpmn_init', array( $this, 'includes' ), 11 );
    }

    /**
     * Get the single instance of WPMN_PRO
     *
     * @return WPMN_PRO
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) :
            self::$instance = new self();
            do_action( 'wpmn_plugin_loaded' );
        endif;
        return self::$instance;
    }

    /**
     * Function to display admin notice if Swiper Slider is not active.
     */
    public function wpmn_admin_notice() {
        ?>
        <div class="notice notice-error">
            <p><?php echo esc_html( 'Medianest Pro is activated but not effective — the required Medianest plugin free plugin is missing or inactive. Please install and activate the free Medianest plugin to enable all Pro features.', 'medianest-pro' ); ?></p>
        </div>
        <?php
    }

    /**
     * Function to initialize the plugin after checking free version.
     */
    public function wpmn_install() {
        if ( ! class_exists( 'WPMN_Settings_Fields' ) ) :
            add_action( 'admin_notices', array( $this, 'wpmn_admin_notice' ) );
        else:
            do_action( 'wpmn_init' );
        endif;
    }

    /**
     * Include required files.
     */
    public function includes() {
        if ( is_admin() ) :
            $this->includes_admin();
        else:
            $this->includes_public();
        endif;

        require_once WPMN_PRO_PATH . 'includes/wpmn-core-functions-pro.php';
    }

    /**
     * Include admin files.
     */
    public function includes_admin() {
        require_once WPMN_PRO_PATH . 'includes/class-wpmn-install-pro.php';
        require_once WPMN_PRO_PATH . 'includes/admin/settings/class-wpmn-settings-pro.php';
        require_once WPMN_PRO_PATH . 'includes/admin/settings/class-admin-menu-pro.php';
        require_once WPMN_PRO_PATH . 'includes/admin/settings/class-wpmn-media-pro.php';
        require_once WPMN_PRO_PATH . 'includes/admin/download/class-wpmn-download.php';
        require_once WPMN_PRO_PATH . 'includes/admin/duplicate/class-wpmn-duplicate.php';
        require_once WPMN_PRO_PATH . 'includes/admin/color-picker/class-wpmn-color-picker.php';
        require_once WPMN_PRO_PATH . 'includes/admin/settings/class-wpmn-cpt-pro.php';
    }

    /**
     * Include public files.
     */
    public function includes_public() {
    }
}

endif;
