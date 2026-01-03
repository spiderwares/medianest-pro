<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'MDDR_WPBakery' ) ) :

    /**
     * Main MDDR_WPBakery Class
     *
     * @class MDDR_WPBakery
     * @version 1.0.0
     */ 
    class MDDR_WPBakery {

        /**
         * The single instance of the class.
         *
         * @var MDDR_WPBakery
         */
        private static $instance = null;

        /**
         * Get the single instance
         *
         * @return MDDR_WPBakery
         */
        public static function get_instance() {
            if ( null === self::$instance ) :
                self::$instance = new self();
            endif;
            return self::$instance;
        }

        /**
         * Constructor for the class.
         */
        public function __construct() {
            $this->events_handler();
        }

        /**
         * Initialize hooks and filters.
         * 
         */
        public function events_handler() {
            if ( function_exists( 'vc_map' ) ) :
                add_action( 'vc_before_init', array( $this, 'register_vc_element' ) );
                add_shortcode( 'media_directory_gallery', array( $this, 'render_media_directory_gallery' ) );
                add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
            endif;
        }

        /**
         * Enqueue gallery styles
         */
        public function enqueue_styles() {
            wp_enqueue_style(
                'mddr-frontend',
                MDDR_URL . 'includes/admin/settings/PageBuilders/Elementor/assets/css/mddr-frontend.css',
                array(),
                MDDR_VERSION
            );
        }

        /**
         * Register WPBakery element
         * 
         */
        public function register_vc_element() {
            if ( ! class_exists( 'MDDR_Media_Folders' ) ) :
                return;
            endif;

            $folders        = \MDDR_Media_Folders::folder_tree('folder_only', 'attachment');
            $folder_options = array(
                esc_html__( 'Select Folder', 'media-directory-pro' ) => 0
            );

            // Build folder options including all children
            $this->build_folder_options( $folders, $folder_options );
            
            vc_map( array(
                'name'        => esc_html__( 'Media Directory Gallery', 'media-directory-pro' ),
                'base'        => 'media_directory_gallery',
                'category'    => esc_html__( 'Media Directory', 'media-directory-pro' ),
                'icon'        => 'icon-wpb-images-stack', 
                'description' => esc_html__( 'Display images from Media Directory folders', 'media-directory-pro' ),
                'params'      => array(
                    array(
                        'type'         => 'dropdown',
                        'heading'      => esc_html__( 'Select Folder', 'media-directory-pro' ),
                        'param_name'   => 'folder_id',
                        'value'        => $folder_options,
                        'description'  => esc_html__( 'Choose a folder to display images from', 'media-directory-pro' ),
                        'admin_label'  => true,
                    ),

                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Columns', 'media-directory-pro' ),
                        'param_name' => 'columns',
                        'value'  => array(
                            '1' => 1,
                            '2' => 2,
                            '3' => 3,
                            '4' => 4,
                            '5' => 5,
                            '6' => 6,
                        ),
                        'std' => 3,
                        'description' => esc_html__( 'Number of columns to display', 'media-directory-pro' ),
                    ),

                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Link To', 'media-directory-pro' ),
                        'param_name' => 'link_to',
                        'value'      => array(
                            esc_html__( 'None', 'media-directory-pro' )            => 'none',
                            esc_html__( 'Media File', 'media-directory-pro' )      => 'file',
                            esc_html__( 'Attachment Page', 'media-directory-pro' ) => 'post',
                        ),
                        'std'         => 'file',
                        'description' => esc_html__( 'Link behavior when clicking on image', 'media-directory-pro' ),
                    ),

                    array(
                        'type'        => 'dropdown',
                        'heading'     => esc_html__( 'Image Size', 'media-directory-pro' ),
                        'param_name'  => 'size',
                        'value'       => array_merge( array( esc_html__( 'Full', 'media-directory-pro' ) => 'full' ), $this->get_image_sizes() ),
                        'std'         => 'medium',
                        'description' => esc_html__( 'Select image size', 'media-directory-pro' ),
                    ),

                    array(
                        'type'        => 'dropdown',
                        'heading'     => esc_html__( 'Order By', 'media-directory-pro' ),
                        'param_name'  => 'orderby',
                        'value'       => array(
                            esc_html__( 'Date', 'media-directory-pro' )   => 'date',
                            esc_html__( 'Title', 'media-directory-pro' )  => 'title',
                            esc_html__( 'Random', 'media-directory-pro' ) => 'rand',
                        ),
                        'std'         => 'date',
                        'description' => esc_html__( 'Order images by', 'media-directory-pro' ),
                    ),

                    array(
                        'type'        => 'dropdown',
                        'heading'     => esc_html__( 'Order', 'media-directory-pro' ),
                        'param_name'  => 'order',
                        'value'       => array(
                            esc_html__( 'Ascending', 'media-directory-pro' ) => 'ASC',
                            esc_html__( 'Descending', 'media-directory-pro' ) => 'DESC',
                        ),
                        'std'         => 'DESC',
                        'description' => esc_html__( 'Sort order', 'media-directory-pro' ),
                    ),
                )
            ) );
        }

        /**
         * Recursively builds folder options including all children
         * 
         */
        private function build_folder_options( $folders, &$options, $prefix = '' ) {

            if ( ! is_array( $folders ) ) :
                return;
            endif;
            
            foreach ( $folders as $folder ) :
                $label = $prefix . '#' . $folder['id'] . ' ' . $folder['name'];
                $options[ $label ] = $folder['id'];
                
                if ( ! empty( $folder['children'] ) && is_array( $folder['children'] ) ) :
                    $this->build_folder_options( $folder['children'], $options, $prefix . '— ' );
                endif;
            endforeach; 
        }

        /**
         * Render MediaNest gallery shortcode
         * 
         */
        public function render_media_directory_gallery( $atts ) {
            $atts = shortcode_atts( array(
                'folder_id' => 0,
                'columns'   => 3,
                'link_to'   => 'file',
                'size'      => 'medium',
                'orderby'   => 'date',
                'order'     => 'DESC',
                'limit'     => -1,
            ),
            $atts, 'media_directory_gallery' );

            $folder_id = intval( $atts['folder_id'] );
            
            if ( $folder_id <= 0 ) :
                return '<div class="mddr-gallery-error">' . esc_html__( 'Please select a folder', 'media-directory-pro' ) . '</div>';
            endif;

            $attachment_ids = get_objects_in_term( $folder_id, 'mddr_media_folder' );
            
            if ( empty( $attachment_ids ) || is_wp_error( $attachment_ids ) ) :
                return '<div class="mddr-gallery-empty">' . esc_html__( 'No images found in this folder', 'media-directory-pro' ) . '</div>';
            endif;

            $args = array(
                'post_type'      => 'attachment',
                'post_status'    => 'inherit',
                'post__in'       => $attachment_ids,
                'posts_per_page' => intval( $atts['limit'] ),
                'orderby'        => $atts['orderby'],
                'order'          => $atts['order'],
                'post_mime_type' => 'image',
            );

            $query = new WP_Query( $args );

            if ( $query->have_posts() ) :
                $settings = array(
                    'columns' => $atts['columns'],
                    'size'    => $atts['size'],
                    'link_to' => $atts['link_to'],
                );
                
                ob_start();

                include MDDR_PATH . 'includes/admin/settings/PageBuilders/Elementor/views/mddr-gallery.php';
                wp_reset_postdata();
                return ob_get_clean();
            endif;

            return '';
        }

        /**
         * Get available image sizes
         * 
         */
        private function get_image_sizes() {
            $sizes    = array();
            $wp_sizes = get_intermediate_image_sizes();
            
            foreach ( $wp_sizes as $size ) :
                $sizes[ ucfirst( str_replace( array( '-', '_' ), ' ', $size ) ) ] = $size;
            endforeach;
            
            return $sizes;
        }
    }

endif;

MDDR_WPBakery::get_instance();
