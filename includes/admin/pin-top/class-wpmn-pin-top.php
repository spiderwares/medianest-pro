<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPMN_Pin_Top' ) ) :

    /**
     * Main WPMN_Pin_Top Class
     *
     * @class WPMN_Pin_Top
     */
    class WPMN_Pin_Top {
        
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
            add_action( 'wpmn_ajax_pin_folder', array( $this, 'handle_pin_folder' ) );
            add_action( 'wpmn_ajax_nopriv_pin_folder', array( $this, 'handle_pin_folder' ) );

            add_action( 'wpmn_ajax_unpin_folder', array( $this, 'handle_unpin_folder' ) );
            add_action( 'wpmn_ajax_nopriv_unpin_folder', array( $this, 'handle_unpin_folder' ) );

            add_filter( 'wpmn_prioritize_terms', array( $this, 'prioritize_pinned_folders' ), 10, 3 );
            add_filter( 'wpmn_folder_node_data', array( $this, 'add_pro_data' ), 10, 2 );
        }

        public function handle_pin_folder( $data ) {
            $folder_id = absint( $data['folder_id'] ?? 0 );
            if ( ! $folder_id ) wp_send_json_error( array( 'message' => 'Invalid folder ID' ) );

            update_term_meta( $folder_id, 'wpmn_is_pinned', '1' );
            
            $post_type = sanitize_text_field( $data['post_type'] ?? 'attachment' );
            wp_send_json_success( WPMN_Media_Folders::payload( null, $post_type ) );
        }

        public function handle_unpin_folder( $data ) {
            $folder_id = absint( $data['folder_id'] ?? 0 );
            if ( ! $folder_id ) wp_send_json_error( array( 'message' => 'Invalid folder ID' ) );

            delete_term_meta( $folder_id, 'wpmn_is_pinned' );
            
            $post_type = sanitize_text_field( $data['post_type'] ?? 'attachment' );
            wp_send_json_success( WPMN_Media_Folders::payload( null, $post_type ) );
        }

        public function prioritize_pinned_folders( $result, $a, $b ) {
            $pin_a = (int) get_term_meta( $a->term_id, 'wpmn_is_pinned', true );
            $pin_b = (int) get_term_meta( $b->term_id, 'wpmn_is_pinned', true );

            if ( $pin_a !== $pin_b ) :
                return array( 'result' => $pin_b <=> $pin_a );
            endif;
            
            return $result;
        }

        public function add_pro_data( $node, $term ) {
            $node['is_pinned'] = get_term_meta( $term->term_id, 'wpmn_is_pinned', true ) === '1';
            return $node;
        }

    }

    new WPMN_Pin_Top();

endif;