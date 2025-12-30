<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPMN_Settings_Pro' ) ) :

    /**
	 * Class WPMN_Settings_Pro
     * 
	 */
    class WPMN_Settings_Pro {

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
            add_action( 'wpmn_settings_tabs', [ $this, 'add_pro_tabs' ] );
            add_action( 'wpmn_render_post_type_tab_content', [ $this, 'render_post_type_tab' ] );
            add_filter( 'wpmn_settings_fields', [ $this, 'settings_fields_pro' ] );
            add_filter( 'wpmn_post_type_fields', [ $this, 'post_type_field_pro' ], 10, 4 );
            add_filter( 'wpmn_checkbox_field', [ $this, 'load_checkbox_field' ], 10, 4 );
        }

        public function add_pro_tabs( $active_tab ) {
            include WPMN_PRO_PATH . 'includes/admin/settings/views/file-menu-pro.php';
        }

        public function settings_fields_pro( $fields ) {

            unset($fields['theme_design']['disabled_options']);

            $fields['folder_count_mode'] = array(
                'title'      => esc_html__( 'Folder Count Mode', 'medianest-pro' ),
                'field_type' => 'wpmnselect',
                'default'    => 'folder_only',
                'name'       => 'wpmn_settings[folder_count_mode]',
                'options'    => array(
                    'folder_only'  => esc_html__( 'Count only files in this folder', 'medianest-pro' ),
                    'all_files'    => esc_html__( 'Count files in parent and subfolders', 'medianest-pro' ),
                ),
            );

            $fields['theme_design']['options'] = array(
                'default'  => 'default.svg',
                'windows'  => 'windows.svg',
                'dropbox'  => 'dropbox.svg',
            );
        
            return $fields;
        }

        public function post_type_field_pro( $fields ) {
            // Ensure fields is an array
            $fields = is_array( $fields ) ? $fields : [];

            $fields['post_types'] = array(
                'title'      => esc_html__( 'Choose MediaNest Post Types', 'medianest-pro' ),
                'field_type' => 'wpmncheckbox',
                'default'    => array( '' ),
                'name'       => 'wpmn_settings[post_types]',
                'options'    => $this->get_post_types(),
            );
            
            return $fields;
        }

        /**
         * Get all available post types for checkbox selection.
         *
         * @return array Post types array.
         */
        public static function get_post_types() {

            $args = array(
                'show_ui' => true,
            );

            $exclude = array(
                'attachment',
                'wp_block',          // Patterns
                'wp_navigation',     // Navigation Menus
                'wp_template',
                'wp_template_part',
                'wp_global_styles',
                'custom_css',
                'revision',
                'nav_menu_item',
                'shop_order',
                'shop_order_refund',
            );

            $post_types = get_post_types( $args, 'objects' );
            $post_type_options = array();

            foreach ( $post_types as $post_type ) :
                if ( in_array( $post_type->name, $exclude, true ) ) continue;
                $label = ! empty( $post_type->labels->menu_name ) ? $post_type->labels->menu_name : $post_type->label;
                $post_type_options[ $post_type->name ] = $label;
            endforeach;

            return $post_type_options;
        }

        public function load_checkbox_field( $wpmn_html, $wpmn_field, $wpmn_field_Val, $wpmn_field_Key ) {
            
            wpmn_get_template_pro(
                'fields/checkbox-field.php', 
                array(
                    'field'     => $wpmn_field,
                    'field_Val' => $wpmn_field_Val,
                    'field_Key' => $wpmn_field_Key,
                ),
            );
        }
    }

    new WPMN_Settings_Pro();

endif;
