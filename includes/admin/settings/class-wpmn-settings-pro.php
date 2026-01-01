<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPMN_Settings_Pro' ) ) :

    /**
     * Main WPMN_Settings_Pro Class
     *
     * @class WPMN_Settings_Pro
     * @version 1.0.0
     */
    class WPMN_Settings_Pro {

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
            add_action( 'wpmn_settings_tabs', [ $this, 'add_pro_tabs' ] );
            add_action( 'wpmn_render_post_type_tab_content', [ $this, 'render_post_type_tab' ] );
            add_filter( 'wpmn_settings_fields', [ $this, 'settings_fields_pro' ] );
            add_filter( 'wpmn_post_type_fields', [ $this, 'post_type_field_pro' ], 10, 4 );
            add_filter( 'wpmn_checkbox_field', [ $this, 'load_checkbox_field' ], 10, 4 );
        }

        /**
         * Add Pro tabs.
         * 
         */
        public function add_pro_tabs( $active_tab ) {
            include WPMN_PRO_PATH . 'includes/admin/settings/views/file-menu-pro.php';
        }

        /**
         * Settings fields pro.
         * 
         */
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

        /**
         * Post type field pro.
         * 
         */
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
            ?>
            <td>
                <div class="wpmn_checkbox_field">
                    <?php if ( isset( $wpmn_field['options'] ) && is_array( $wpmn_field['options'] ) ) :
                        $wpmn_current_values = isset( $wpmn_field_Val ) ? $wpmn_field_Val : ( isset( $wpmn_field['default'] ) ? $wpmn_field['default'] : array() );

                        if ( ! is_array( $wpmn_current_values ) ) :
                            $wpmn_current_values = array();
                        endif;

                        foreach ( $wpmn_field['options'] as $wpmn_option_key => $wpmn_option_label ) :
                            $wpmn_input_name  = ! empty( $wpmn_field['name'] ) ? $wpmn_field['name'] . '[]' : 'wpmn_settings[' . esc_attr( $wpmn_field_Key ) . '][]';
                            $wpmn_checkbox_id = esc_attr( $wpmn_field_Key . '_' . $wpmn_option_key );
                            $wpmn_is_checked  = in_array( $wpmn_option_key, $wpmn_current_values, true );
                        ?>

                            <div class="wpmn_checkbox_item">
                                <input
                                    type="checkbox"
                                    id="<?php echo esc_attr( $wpmn_checkbox_id ); ?>"
                                    name="<?php echo esc_attr( $wpmn_input_name ); ?>"
                                    value="<?php echo esc_attr( $wpmn_option_key ); ?>"
                                    <?php checked( $wpmn_is_checked ); ?>
                                />

                                <label for="<?php echo esc_attr( $wpmn_checkbox_id ); ?>">
                                    <?php echo esc_html( $wpmn_option_label ); ?>
                                </label>
                            </div>
                        <?php endforeach;
                    endif; ?>
                </div>

                <p><?php echo isset( $wpmn_field['desc'] ) ? wp_kses_post( $wpmn_field['desc'] ) : ''; ?></p>
            </td>
            <?php
        }
    }

    new WPMN_Settings_Pro();

endif;
