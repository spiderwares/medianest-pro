<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPMN_Media_Download' ) ) :

    /**
     * Main WPMN_Media_Download Class
     *
     * @class WPMN_Media_Download
     */
    class WPMN_Media_Download {
        
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
            add_action( 'wpmn_ajax_download_folder_zip', [ $this, 'handle_folder_download' ] );
            add_action( 'wpmn_ajax_nopriv_download_folder_zip', [ $this, 'handle_folder_download' ] );
        }

        public function handle_folder_download() {

            if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wpmn_media_nonce' ) ) :
                wp_die( esc_html__( 'Security check failed.', 'medianest-pro' ) );
            endif;
            
            $folder_id = isset( $_POST['folder_id'] ) ? absint( $_POST['folder_id'] ) : 0;
            if ( ! $folder_id ) :
                wp_die( esc_html__( 'Invalid folder ID.', 'medianest-pro' ) );
            endif;

            $term = get_term( $folder_id, 'wpmn_media_folder' );
            if ( ! $term || is_wp_error( $term ) ) :
                wp_die( esc_html__( 'Folder not found.', 'medianest-pro' ) );
            endif;

            $zip      = new ZipArchive();
            $zip_name = sanitize_title( $term->name ) . '-' . time() . '.zip';
            $zip_path = get_temp_dir() . $zip_name;

            if ( $zip->open( $zip_path, ZipArchive::CREATE ) !== true ) :
                wp_die( esc_html__( 'Could not create ZIP file.', 'medianest-pro' ) );
            endif;

            $this->add_folder_to_zip( $zip, $folder_id );
            $zip->close();

            if ( file_exists( $zip_path ) ) :
                header( 'Content-Type: application/zip' );
                header( 'Content-Disposition: attachment; filename="' . $zip_name . '"' );
                header( 'Content-Length: ' . filesize( $zip_path ) );
                header( 'Pragma: no-cache' );
                header( 'Expires: 0' );
                // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile
                readfile( $zip_path );
                // phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
                unlink( $zip_path );
                exit;
            endif;
        }

        public function add_folder_to_zip( &$zip, $folder_id, $path = '' ) {
            $attachment_ids = get_objects_in_term( $folder_id, 'wpmn_media_folder' );
            foreach ( (array) $attachment_ids as $id ) :
                $file = get_attached_file( $id );
                if ( $file && file_exists( $file ) ) :
                    $zip->addFile( $file, $path . basename( $file ) );
                endif;
            endforeach;

            // Subfolders
            $children = get_terms( array(
                'taxonomy'   => 'wpmn_media_folder',
                'parent'     => $folder_id,
                'hide_empty' => false,
            ) );

            foreach ( (array) $children as $child ) :
                $this->add_folder_to_zip(
                    $zip,
                    $child->term_id,
                    $path . $child->name . '/'
                );
            endforeach;
        }

    }

    new WPMN_Media_Download();

endif;