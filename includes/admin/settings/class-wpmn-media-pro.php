<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPMN_Media_Pro' ) ) :

    /**
     * Main WPMN_Media_Pro Class
     *
     * @class WPMN_Media_Pro
     */
    class WPMN_Media_Pro {
        
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
            add_filter( 'wpmn_sort_menu_item', [ $this, 'sort_menu_items' ], 10, 2 );
            add_filter( 'wpmn_sort_sub_menu_item', [ $this, 'sort_menu_items' ], 10, 2 );
            add_filter( 'wpmn_theme_buttons', [ $this, 'sort_menu_items' ], 10, 2 );
            add_filter( 'wpmn_folder_context_menu', [ $this, 'sort_menu_items' ], 10, 2 );
            add_filter( 'wpmn_default_folder', [ $this, 'sort_menu_items' ], 10, 2 );
            add_filter( 'wpmn_collapsed_menu_item', [ $this, 'sort_menu_items' ], 10, 2 );
        }

        public function sort_menu_items( $output, $args ) {

            $map = array(
                'wpmn_sort_menu_item'      => 'menu',
                'wpmn_sort_sub_menu_item'  => 'submenu',
                'wpmn_theme_buttons'       => 'theme_buttons',
                'wpmn_folder_context_menu' => 'folder_context_menu',
                'wpmn_default_folder'      => 'default_folder',
                'wpmn_collapsed_menu_item' => 'collapsed_menu_item',
            );

            $current_filter = current_filter();

            if ( ! isset( $map[ $current_filter ] ) ) return $output;

            $screen = get_current_screen();
            // Check if we are in the media library (attachment post type)
            $is_attachment = ( $screen && $screen->post_type === 'attachment' );

            ob_start();

            wpmn_get_template_pro(
                'media/library-folder-pro.php',
                array(
                    'type' => $map[ $current_filter ],
                    'is_attachment' => $is_attachment,
                )
            );

            return $output . ob_get_clean();
        }

        
    }

    new WPMN_Media_Pro();

endif;