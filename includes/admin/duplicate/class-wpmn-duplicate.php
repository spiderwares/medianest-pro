<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPMN_Media_Duplicate' ) ) :

    /**
     * Main WPMN_Media_Duplicate Class
     *
     * @class WPMN_Media_Duplicate
     */
    class WPMN_Media_Duplicate {
        
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
            add_action( 'wpmn_ajax_duplicate_folder', [ $this, 'duplicate_folder_request' ] );
            add_action( 'wpmn_ajax_nopriv_duplicate_folder', [ $this, 'duplicate_folder_request' ] );
        }

        public function duplicate_folder_request() {
            if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wpmn_media_nonce' ) ) :
                wp_die( esc_html__( 'Security check failed.', 'medianest-pro' ) );
            endif;

            $folder_id = isset($_POST['folder_id']) ? absint($_POST['folder_id']) : 0;
            if (!$folder_id) :
                wp_send_json_error(['message' => 'Invalid folder.']);
            endif;

            // Get post type from the request
            $post_type = isset($_POST['post_type']) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : 'attachment';
            $result = $this->duplicate_folder($folder_id, null, $post_type);
            
            if (is_wp_error($result)) :
                wp_send_json_error(['message' => $result->get_error_message()]);
            endif;

            wp_send_json_success(WPMN_Media_Folders::payload(null, $post_type));
        }

        public function duplicate_folder($folder_id, $new_parent = null, $post_type = 'attachment') {
            $term = get_term($folder_id, 'wpmn_media_folder');
            if (!$term || is_wp_error($term)) return new WP_Error('not_found', 'Folder not found.');

            if ($new_parent === null) $new_parent = $term->parent;

            // Create the copy folder
            $new_name = $term->name . ' (Copy)';
            $new_term = WPMN_Helper::create_folder($new_name, $new_parent);

            if (is_wp_error($new_term)) return $new_term;
            $new_folder_id = $new_term['term_id'];

            // 1. Copy Color Meta
            $color = get_term_meta($folder_id, 'wpmn_color', true);
            if ($color) :
                update_term_meta($new_folder_id, 'wpmn_color', $color);
            endif;

            // 2. Set Post Type Meta
            $source_post_type = get_term_meta($folder_id, 'wpmn_post_type', true);
            $final_post_type  = $source_post_type ? $source_post_type : $post_type;
            
            update_term_meta($new_folder_id, 'wpmn_post_type', $final_post_type);
            
            $author = get_term_meta($folder_id, 'wpmn_folder_author', true);
            if ($author) update_term_meta($new_folder_id, 'wpmn_folder_author', $author);

            $direct_children = get_terms(array(
                'taxonomy' => 'wpmn_media_folder',
                'parent' => $folder_id,
                'hide_empty' => false,
                'fields' => 'ids'
            ) );

            if (!empty($direct_children) && !is_wp_error($direct_children)) :
                foreach ($direct_children as $child_id) :
                    $this->duplicate_folder($child_id, $new_folder_id, $final_post_type);
                endforeach;
            endif;

            return $new_folder_id;
        }

    }

    new WPMN_Media_Duplicate();

endif;