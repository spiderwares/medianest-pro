<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'MDDR_Settings_Pro' ) ) :

    /**
     * Main MDDR_Settings_Pro Class
     *
     * @class MDDR_Settings_Pro
     * @version 1.0.0
     */
    class MDDR_Settings_Pro {

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
            add_action( 'mddr_settings_tabs', [ $this, 'add_pro_tabs' ] );
            add_action( 'mddr_render_post_type_tab_content', [ $this, 'render_post_type_tab' ] );
            add_filter( 'mddr_settings_fields', [ $this, 'settings_fields_pro' ] );
            add_filter( 'mddr_post_type_fields', [ $this, 'post_type_field_pro' ], 10, 4 );
            add_filter( 'mddr_checkbox_field', [ $this, 'load_checkbox_field' ], 10, 4 );
        }

        /**
         * Add Pro tabs.
         * 
         */
        public function add_pro_tabs( $active_tab ) {
            include MDDR_PRO_PATH . 'includes/admin/settings/views/file-menu-pro.php';
        }

        /**
         * Settings fields pro.
         * 
         */
        public function settings_fields_pro( $fields ) {

            unset($fields['theme_design']['disabled_options']);

            $fields['folder_count_mode'] = array(
                'title'      => esc_html__( 'Folder Count Mode', 'media-directory-pro' ),
                'field_type' => 'mddrselect',
                'default'    => 'folder_only',
                'name'       => 'mddr_settings[folder_count_mode]',
                'options'    => array(
                    'folder_only'  => esc_html__( 'Count only files in this folder', 'media-directory-pro' ),
                    'all_files'    => esc_html__( 'Count files in parent and subfolders', 'media-directory-pro' ),
                ),
            );

            $fields['theme_design']['options'] = array(
                'default'  => 'default.svg',
                'windows'  => 'windows.svg',
                'dropbox'  => 'dropbox.svg',
            );
        
            return $fields;
        }

        /**
         * Post type field pro.
         * 
         */
        public function post_type_field_pro( $fields ) {
            // Ensure fields is an array
            $fields = is_array( $fields ) ? $fields : [];

            $fields['post_types'] = array(
                'title'      => esc_html__( 'Choose MediaNest Post Types', 'media-directory-pro' ),
                'field_type' => 'mddrcheckbox',
                'default'    => array( '' ),
                'name'       => 'mddr_settings[post_types]',
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

        public function load_checkbox_field( $mddr_html, $mddr_field, $mddr_field_Val, $mddr_field_Key ) {
            ?>
            <td>
                <div class="mddr_checkbox_field">
                    <?php if ( isset( $mddr_field['options'] ) && is_array( $mddr_field['options'] ) ) :
                        $mddr_current_values = isset( $mddr_field_Val ) ? $mddr_field_Val : ( isset( $mddr_field['default'] ) ? $mddr_field['default'] : array() );

                        if ( ! is_array( $mddr_current_values ) ) :
                            $mddr_current_values = array();
                        endif;

                        foreach ( $mddr_field['options'] as $mddr_option_key => $mddr_option_label ) :
                            $mddr_input_name  = ! empty( $mddr_field['name'] ) ? $mddr_field['name'] . '[]' : 'mddr_settings[' . esc_attr( $mddr_field_Key ) . '][]';
                            $mddr_checkbox_id = esc_attr( $mddr_field_Key . '_' . $mddr_option_key );
                            $mddr_is_checked  = in_array( $mddr_option_key, $mddr_current_values, true );
                        ?>

                            <div class="mddr_checkbox_item">
                                <input
                                    type="checkbox"
                                    id="<?php echo esc_attr( $mddr_checkbox_id ); ?>"
                                    name="<?php echo esc_attr( $mddr_input_name ); ?>"
                                    value="<?php echo esc_attr( $mddr_option_key ); ?>"
                                    <?php checked( $mddr_is_checked ); ?>
                                />

                                <label for="<?php echo esc_attr( $mddr_checkbox_id ); ?>">
                                    <?php echo esc_html( $mddr_option_label ); ?>
                                </label>
                            </div>
                        <?php endforeach;
                    endif; ?>
                </div>

                <p><?php echo isset( $mddr_field['desc'] ) ? wp_kses_post( $mddr_field['desc'] ) : ''; ?></p>
            </td>
            <?php
        }
    }

    new MDDR_Settings_Pro();

endif;
