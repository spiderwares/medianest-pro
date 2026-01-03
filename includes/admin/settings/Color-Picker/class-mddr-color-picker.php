<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'MDDR_Color_Picker' ) ) :

    /**
     * Main MDDR_Color_Picker Class
     *
     * @class MDDR_Color_Picker
     */
    class MDDR_Color_Picker {
        
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
            add_action( 'mddr_ajax_save_folder_color', [ $this, 'handle_save_folder_color' ] );
            add_action( 'mddr_ajax_nopriv_save_folder_color', [ $this, 'handle_save_folder_color' ] );
        }

        public function handle_save_folder_color() {

            if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'mddr_media_nonce' ) ) :
                wp_die( esc_html__( 'Security check failed.', 'media-directory-pro' ) );
            endif;
            
            $folder_id = isset( $_POST['folder_id'] ) ? absint( $_POST['folder_id'] ) : 0;
            $color     = isset( $_POST['color'] ) ? sanitize_hex_color( wp_unslash( $_POST['color'] ) ) : '';
            $post_type = isset($_POST['post_type']) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : 'attachment';

            if ( ! $folder_id ) :
                wp_send_json_error( [ 'message' => 'Invalid folder ID' ] );
            endif;

            update_term_meta( $folder_id, 'mddr_color', $color );
            clean_term_cache( $folder_id, 'mddr_media_folder' );

            // Return full payload to refresh sidebar
            wp_send_json_success( MDDR_Media_Folders::payload(null, $post_type));
        }

    }

    new MDDR_Color_Picker();

endif;