<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'MDDR_Admin_Menu_Pro' ) ) :

    /**
     * Main MDDR_Admin_Menu_Pro Class
     *
     * @class MDDR_Admin_Menu_Pro
     * @version 1.0.0
     */
    class MDDR_Admin_Menu_Pro {

        public $settings;
        
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
            // menu
            $this->settings = get_option( 'mddr_settings', [] );
            add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_styles' ], 15 );
            add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_admin_styles' ] );
        }

        public function enqueue_admin_styles() {

            wp_enqueue_style( 
                'mddr-admin-style-pro', 
                MDDR_PRO_URL . 'assets/css/mddr-admin-style-pro.css', 
                array(), 
                MDDR_PRO_VERSION 
            );

            wp_enqueue_script( 
                'mddr-admin-pro', 
                MDDR_PRO_URL . 'assets/js/mddr-admin-pro.js', 
                array( 'jquery' , 'wp-hooks',  'mddr-media-library' ), 
                MDDR_PRO_VERSION,
                true
            );

            $saved_theme  = isset( $this->settings['theme_design'] ) ? sanitize_key( $this->settings['theme_design'] ) : 'default';
             
            wp_localize_script( 'mddr-admin-pro',
				'mddr_media_library_pro', array(
					'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'mddr_pro_media_nonce' ),
                    'theme'    => $saved_theme,
				)
			);
        }
        
    }

    new MDDR_Admin_Menu_Pro();

endif;
