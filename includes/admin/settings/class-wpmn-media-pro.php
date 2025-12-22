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
            add_action( 'wpmn_ajax_download_folder_zip', [ $this, 'handle_folder_download' ] );
        }

        public function sort_menu_items( $output, $args ) {

            $map = array(
                'wpmn_sort_menu_item'      => 'menu',
                'wpmn_sort_sub_menu_item'  => 'submenu',
                'wpmn_theme_buttons'       => 'theme_buttons',
                'wpmn_folder_context_menu' => 'folder_context_menu',
                'wpmn_default_folder'      => 'default_folder',
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

         public function handle_folder_download() {
            $folder_id = isset( $_POST['folder_id'] ) ? absint( $_POST['folder_id'] ) : 0;
            if ( ! $folder_id ) {
                wp_die( __( 'Invalid folder ID.', 'medianest_pro' ) );
            }

            $term = get_term( $folder_id, 'wpmn_media_folder' );
            if ( ! $term || is_wp_error( $term ) ) {
                wp_die( __( 'Folder not found.', 'medianest_pro' ) );
            }

            if ( ! class_exists( 'ZipArchive' ) ) {
                wp_die( __( 'ZipArchive class not found on your server.', 'medianest_pro' ) );
            }

            $zip = new ZipArchive();
            $zip_name = sanitize_title( $term->name ) . '-' . time() . '.zip';
            $zip_path = get_temp_dir() . $zip_name;

            if ( $zip->open( $zip_path, ZipArchive::CREATE ) !== true ) {
                wp_die( __( 'Could not create ZIP file.', 'medianest_pro' ) );
            }

            // Recursively add folders and files
            $this->add_folder_to_zip( $zip, $folder_id, '', true );

            $zip->close();


            if ( file_exists( $zip_path ) ) {
                header( 'Content-Type: application/zip' );
                header( 'Content-Disposition: attachment; filename="' . $zip_name . '"' );
                header( 'Content-Length: ' . filesize( $zip_path ) );
                header( 'Pragma: no-cache' );
                header( 'Expires: 0' );
                readfile( $zip_path );
                unlink( $zip_path );
                exit;
            } else {
                wp_die( __( 'Failed to generate ZIP file.', 'medianest_pro' ) );
            }
        }

        private function add_folder_to_zip( &$zip, $folder_id, $parent_path = '', $is_root = false ) {
            $term = get_term( $folder_id, 'wpmn_media_folder' );
            if ( ! $term || is_wp_error( $term ) ) {
                return;
            }

            // Root folder ke liye name skip karo
            $current_path = $is_root ? $parent_path : $parent_path . $term->name . '/';

            // Files add karo
            $attachment_ids = get_objects_in_term( $folder_id, 'wpmn_media_folder' );
            if ( ! empty( $attachment_ids ) ) {
                foreach ( $attachment_ids as $id ) {
                    $file_path = get_attached_file( $id );
                    if ( $file_path && file_exists( $file_path ) ) {
                        $zip->addFile( $file_path, $current_path . basename( $file_path ) );
                    }
                }
            }

            // Subfolders
            $children = get_terms( array(
                'taxonomy'   => 'wpmn_media_folder',
                'parent'     => $folder_id,
                'hide_empty' => false,
            ) );

            if ( ! empty( $children ) && ! is_wp_error( $children ) ) {
                foreach ( $children as $child ) {
                    $this->add_folder_to_zip( $zip, $child->term_id, $current_path );
                }
            }
        }

        
    }

    new WPMN_Media_Pro();

endif;