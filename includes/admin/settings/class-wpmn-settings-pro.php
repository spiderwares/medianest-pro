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
            add_filter( 'wpmn_settings_fields', [ $this, 'settings_fields_Pro' ] );
            add_filter( 'wpmn_checkbox_field', [ $this, 'load_checkbox_field' ], 10, 4 );
        }

        public function settings_fields_Pro( $fields ) {

            unset($fields['theme_design']['disabled_options']);

            $fields['folder_count_mode'] = array(
                'title'      => esc_html__( 'Folder Count Mode', 'medianest_pro' ),
                'field_type' => 'wpmnselect',
                'default'    => 'folder_only',
                'name'       => 'wpmn_settings[folder_count_mode]',
                'options'    => array(
                    'folder_only'  => esc_html__( 'Count only files in this folder', 'medianest_pro' ),
                    'all_files'    => esc_html__( 'Count files in parent and subfolders', 'medianest_pro' ),
                ),
            );

            $fields['theme_design']['options'] = array(
                'default'  => 'default.svg',
                'windows'  => 'windows.svg',
                'dropbox'  => 'dropbox.svg',
            );

            $fields['post_type_selection'] = array(
                'title'         => esc_html__('Post Type Selection', 'medianest_pro'),
                'field_type'    => 'wpmntitle',
                'extra_class'   => 'heading',
                'default'       => '',
            );

            $fields['post_types'] = array(
                'title'      => esc_html__( 'Choose MediaNest Post Types', 'medianest_pro' ),
                'field_type' => 'wpmncheckbox',
                'default'    => array( '' ),
                'name'       => 'wpmn_settings[post_types]',
                'options'    => self::get_post_types(),
            );
        
            return $fields;
        }

        /**
         * Get all available post types for checkbox selection.
         *
         * @return array Post types array.
         */
        public static function get_post_types() {

            $args = array( 'show_ui' => true );

            $post_types = get_post_types($args, 'objects');
            $post_type_options = array();

            foreach ($post_types as $post_type) {
                if ($post_type->name !== 'attachment') {
                    $post_type_options[$post_type->name] = $post_type->label;
                }
            }

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
