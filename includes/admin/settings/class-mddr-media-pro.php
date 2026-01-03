<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'MDDR_Media_Pro' ) ) :

    /**
     * Main MDDR_Media_Pro Class
     *
     * @class MDDR_Media_Pro
     */
    class MDDR_Media_Pro {
        
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
            add_filter( 'mddr_sort_menu_item', [ $this, 'sort_menu_items' ], 10, 2 );
            add_filter( 'mddr_sort_sub_menu_item', [ $this, 'sort_menu_items' ], 10, 2 );
            add_filter( 'mddr_theme_buttons', [ $this, 'sort_menu_items' ], 10, 2 );
            add_filter( 'mddr_folder_context_menu', [ $this, 'sort_menu_items' ], 10, 2 );
            add_filter( 'mddr_default_folder', [ $this, 'sort_menu_items' ], 10, 2 );
            add_filter( 'mddr_collapsed_menu_item', [ $this, 'sort_menu_items' ], 10, 2 );
        }

        /**
         * Sort menu items.
         * 
         */
        public function sort_menu_items( $output, $args ) {

            $map = array(
                'mddr_sort_menu_item'      => 'menu',
                'mddr_sort_sub_menu_item'  => 'submenu',
                'mddr_theme_buttons'       => 'theme_buttons',
                'mddr_folder_context_menu' => 'folder_context_menu',
                'mddr_default_folder'      => 'default_folder',
                'mddr_collapsed_menu_item' => 'collapsed_menu_item',
            );

            $current_filter = current_filter();

            if ( ! isset( $map[ $current_filter ] ) ) return $output;

            $screen = get_current_screen();
            // $is_attachment = ( $screen && $screen->post_type === 'attachment' );

            $is_attachment = (
                $screen &&
                (
                    $screen->post_type === 'attachment' ||
                    $screen->base === 'post'
                )
            );

            ob_start();

            mddr_get_template_pro(
                'media/library-folder-pro.php',
                array(
                    'type'          => $map[ $current_filter ],
                    'is_attachment' => $is_attachment,
                )
            );

            return $output . ob_get_clean();
        }

    }

    new MDDR_Media_Pro();

endif;