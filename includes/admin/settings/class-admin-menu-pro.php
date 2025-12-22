<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPMN_Admin_Menu_Pro' ) ) :

    /**
     * Main WPMN_Admin_Menu_Pro Class
     *
     * @class WPMN_Admin_Menu_Pro
     * @version 1.0.0
     */
    class WPMN_Admin_Menu_Pro {
        
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
            add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_styles' ], 15 );
        }

        public function enqueue_admin_styles() {

            wp_enqueue_style( 
                'wpmn-admin-style-pro', 
                WPMN_PRO_URL . 'assets/css/wpmn-admin-style-pro.css', 
                array(), 
                WPMN_PRO_VERSION 
            );

            wp_enqueue_script( 
                'wpmn-admin-pro', 
                WPMN_PRO_URL . 'assets/js/wpmn-admin-pro.js', 
                array( 'jquery' , 'wp-hooks' ), 
                WPMN_PRO_VERSION,
                true
            );

            $saved_theme  = isset( $this->settings['theme_design'] ) ? sanitize_key( $this->settings['theme_design'] ) : 'default';
             
            wp_localize_script( 'wpmn-admin-pro',
				'wpmn_media_library_pro', array(
					'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'wpmn_media_nonce' ),
                    'theme'    => $saved_theme,
				)
			);
        }
        
    }

    new WPMN_Admin_Menu_Pro();

endif;
