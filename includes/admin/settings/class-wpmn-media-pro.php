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
        }

        public function sort_menu_items( $output, $args ) {

            $map = array(
                'wpmn_sort_menu_item'      => 'menu',
                'wpmn_sort_sub_menu_item'  => 'submenu',
                'wpmn_theme_buttons'       => 'theme_buttons',
                'wpmn_folder_context_menu' => 'folder_context_menu',
            );

            $current_filter = current_filter();

            if ( ! isset( $map[ $current_filter ] ) ) {
                return $output;
            }

            ob_start();

            wpmn_get_template_pro(
                'media/library-folder-pro.php',
                array(
                    'type' => $map[ $current_filter ],
                )
            );

            return $output . ob_get_clean();
        }
        
    }

    new WPMN_Media_Pro();

endif;

// 'use strict';

// jQuery(function ($) {

//     class WPMN_Admin_Pro {

//         constructor() {
//             this.init();
//         }

//         init() {
//             this.bindEvents();
//         }

//         bindEvents() {

//             $(document).on('click', '.wpmn_color_option', this.selectColor(this));
//             $(document).on('input', '.wpmn_custom_color_input', this.inputColor(this));
//             $(document).on('click', '.wpmn_color_picker_dropdown', function (e) { e.stopPropagation(); });
//         }

//         selectColor($option) {
//             const color = $option.data('color');
//             const dropdown = $option.closest('.wpmn_color_picker_dropdown');

//             dropdown.find('.wpmn_custom_color_input').val(color);
//             dropdown.find('.wpmn_current_color_preview').css('background-color', color);

//             dropdown.find('.wpmn_color_option').removeClass('selected');
//             $option.addClass('selected');

//             $(document).trigger('wpmn_pro_date_color_selected', [color]);
//         }

//         inputColor($input) {
//             const color = $input.val();
//             if (color.length >= 4 && color.startsWith('#')) {
//                 $input.siblings('.wpmn_current_color_preview')
//                     .css('background-color', color);
//             }
//         }
//     }

//     new WPMN_Admin_Pro();

// });