<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPMN_Divi_Init' ) ) :

    /**
     * Main WPMN_Divi_Init Class
     *
     * @class WPMN_Divi_Init
     * @version 1.0.0
     */
    class WPMN_Divi_Init {

        /**
         * The single instance of the class
         */
        private static $instance = null;

        /**
         * Get the single instance
         *
         * @return WPMN_Divi_Init
         */
        public static function getInstance() {
            if (null == self::$instance) :
                self::$instance = new self();
            endif;
            return self::$instance;
        }

        /**
         * Constructor for the class.
         */
        public function __construct() {
            $this->events_handler();
        }

        /**
         * Initialize hooks and filters.
         */
        public function events_handler() {
            add_action('et_builder_ready', array($this, 'register_modules'));
            
            // Load scripts and styles
            add_action('wp_enqueue_scripts', array($this, 'frontend_styles'));
            add_action('et_builder_enqueue_assets', array($this, 'frontend_styles'));
        }

        /**
         * Register Divi modules 
         */
        public function register_modules() {
            if (class_exists('ET_Builder_Module')) :
                require_once(WPMN_PRO_PATH . 'includes/admin/settings/PageBuilders/Divi/modules/class-wpmn-gallery-module.php');
                
                $gallery_module = new WPMN_Divi_Gallery_Module();
            endif;
        }

        /**
         * Load frontend styles
         */
        public function frontend_styles() {
            wp_enqueue_style(
                'wpmn-frontend',
                WPMN_URL . 'includes/admin/settings/PageBuilders/Elementor/assets/css/wpmn-frontend.css',
                array(),
                WPMN_VERSION
            );
        }
    }

    WPMN_Divi_Init::getInstance();

endif;
