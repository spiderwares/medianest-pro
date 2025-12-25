<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPMN_CPT_Pro' ) ) :

    class WPMN_CPT_Pro {

        public function __construct() {
            // Hook into Core initialization
            add_action( 'wpmn_media_library_init', array( $this, 'init_hooks' ) );
        }

        public function init_hooks() {
            add_action( 'add_meta_boxes', array( $this, 'wpmn_add_meta_boxes' ) );
            add_action( 'save_post', array( $this, 'wpmn_save_post_folder' ) );
        }

        public function wpmn_add_meta_boxes() {
            $settings = get_option( 'wpmn_settings', [] );
            $post_types = isset( $settings['post_types'] ) ? (array) $settings['post_types'] : [];

            foreach ( $post_types as $post_type ) :
                add_meta_box(
                    'wpmn_media_folder_metabox',
                    esc_html__( 'MediaNest Folder', 'medianest-pro' ),
                    array( $this, 'render_folder_metabox' ),
                    $post_type,
                    'side',
                    'default'
                );
            endforeach;
        }

        public function render_folder_metabox( $post ) {
            if ( ! class_exists( 'WPMN_Helper' ) ) return;

            wp_nonce_field( 'wpmn_save_post_folder', 'wpmn_meta_box_nonce' );
            $terms = wp_get_object_terms( $post->ID, 'wpmn_media_folder' );
            $selected = $terms[0]->term_id ?? 0;
            $labels = WPMN_Helper::wpmn_get_folder_labels();

            // Get only terms for this post type
            $include_terms = get_terms( array(
                'taxonomy'   => 'wpmn_media_folder',
                'hide_empty' => false,
                'fields'     => 'ids',
                'meta_query' => array(
                    array(
                        'key'     => 'wpmn_post_type',
                        'value'   => $post->post_type,
                        'compare' => '=',
                    ),
                ),
            ) );

            wp_dropdown_categories( array(
                'taxonomy'          => 'wpmn_media_folder',
                'hide_empty'        => false,
                'name'              => 'wpmn_media_folder_select',
                'id'                => 'wpmn_media_folder_select',
                'show_option_all'   => $labels['all'],
                'show_option_none'  => $labels['uncategorized'],
                'option_none_value' => '0',
                'selected'          => $selected,
                'hierarchical'      => true,
                'include'           => ! empty( $include_terms ) ? $include_terms : array( -1 ),
            ) );
        }

        public function wpmn_save_post_folder( $post_id ) {
            if ( ! isset( $_POST['wpmn_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['wpmn_meta_box_nonce'], 'wpmn_save_post_folder' ) ) :
                return;
            endif;

            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
            if ( ! current_user_can( 'edit_post', $post_id ) ) return;

            $folder_id = isset( $_POST['wpmn_media_folder_select'] ) ? absint( $_POST['wpmn_media_folder_select'] ) : 0;
            
            if ( $folder_id > 0 ) :
                wp_set_object_terms( $post_id, array( $folder_id ), 'wpmn_media_folder' );
            else :
                wp_set_object_terms( $post_id, array(), 'wpmn_media_folder' );
            endif;
        }
    }

    new WPMN_CPT_Pro();

endif;
