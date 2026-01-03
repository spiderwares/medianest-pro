<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'MDDR_Divi_Init' ) ) :

    /**
     * Main MDDR_Divi_Init Class
     *
     * @class MDDR_Divi_Init
     * @version 1.0.0
     */
    class MDDR_Divi_Init {

        /**
         * The single instance of the class
         */
        private static $instance = null;

        /**
         * Get the single instance
         *
         * @return MDDR_Divi_Init
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
                require_once(MDDR_PRO_PATH . 'includes/admin/settings/PageBuilders/Divi/modules/class-mddr-gallery-module.php');
                
                $gallery_module = new MDDR_Divi_Gallery_Module();
            endif;
        }

        /**
         * Load frontend styles
         */
        public function frontend_styles() {
            wp_enqueue_style(
                'mddr-frontend',
                MDDR_URL . 'includes/admin/settings/PageBuilders/Elementor/assets/css/mddr-frontend.css',
                array(),
                MDDR_VERSION
            );
        }
    }

    MDDR_Divi_Init::getInstance();

endif;
