<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'MDDR_CPT_Pro' ) ) :

    /**
     * Main MDDR_CPT_Pro Class
     *
     * @class MDDR_CPT_Pro
     * @version 1.0.0
     */
    class MDDR_CPT_Pro {

        /**
         * Settings.
		 * 
         */
        public $settings;

        /**
         * Constructor for the class.
		 * 
         */
        public function __construct() {
            $this->events_handler();
        }
        
        /**
         * Initialize hooks and filters.
         * 
         */
        public function events_handler() {
            $settings   = get_option( 'mddr_settings', [] );
            add_action( 'mddr_media_library_init', array( $this, 'init_hooks' ) );
        }

        public function init_hooks() {
            add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
            add_action( 'save_post', array( $this, 'save_post_folder' ) );
        }

        public function add_meta_boxes() {
            $post_types = isset( $this->settings['post_types'] ) ? (array) $this->settings['post_types'] : [];

            foreach ( $post_types as $post_type ) :
                add_meta_box(
                    'mddr_media_folder_metabox',
                    esc_html__( 'MediaNest Folder', 'media-directory-pro' ),
                    array( $this, 'render_folder_metabox' ),
                    $post_type,
                    'side',
                    'default'
                );
            endforeach;
        }

        public function render_folder_metabox( $post ) {
            if ( ! class_exists( 'MDDR_Helper' ) ) return;

            wp_nonce_field( 'mddr_save_post_folder', 'mddr_meta_box_nonce' );
            $terms      = wp_get_object_terms( $post->ID, 'mddr_media_folder' );
            $selected   = ( isset( $terms[0] ) && isset( $terms[0]->term_id ) ) ? $terms[0]->term_id : 0;
            $labels     = MDDR_Helper::get_folder_labels();

            // Get only terms for this post type
            $include_terms = get_terms( array(
                'taxonomy'   => 'mddr_media_folder',
                'hide_empty' => false,
                'fields'     => 'ids',
                'meta_query' => array(
                    array(
                        'key'     => 'mddr_post_type',
                        'value'   => $post->post_type,
                        'compare' => '=',
                    ),
                ),
            ) );

            wp_dropdown_categories( array(
                'taxonomy'          => 'mddr_media_folder',
                'hide_empty'        => false,
                'name'              => 'mddr_media_folder_select',
                'id'                => 'mddr_media_folder_select',
                'show_option_all'   => $labels['all'],
                'show_option_none'  => $labels['uncategorized'],
                'option_none_value' => '0',
                'selected'          => $selected,
                'hierarchical'      => true,
                'include'           => ! empty( $include_terms ) ? $include_terms : array( -1 ),
            ) );
        }

        public function save_post_folder( $post_id ) {
            if ( ! isset( $_POST['mddr_meta_box_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mddr_meta_box_nonce'] ) ), 'mddr_save_post_folder' ) ) :
                return;
            endif;

            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
            if ( ! current_user_can( 'edit_post', $post_id ) ) return;

            $folder_id = isset( $_POST['mddr_media_folder_select'] ) ? absint( $_POST['mddr_media_folder_select'] ) : 0;
            
            if ( $folder_id > 0 ) :
                wp_set_object_terms( $post_id, array( $folder_id ), 'mddr_media_folder' );
            else :
                wp_set_object_terms( $post_id, array(), 'mddr_media_folder' );
            endif;
        }
    }

    new MDDR_CPT_Pro();

endif;
