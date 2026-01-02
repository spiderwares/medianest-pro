<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPMN_Divi_Gallery_Module' ) ) :

    class WPMN_Divi_Gallery_Module extends ET_Builder_Module {

        public $slug  = 'medianest_gallery';

        public function init() {
            $this->name = esc_html__( 'MediaNest Gallery', 'medianest' );
        }

        public function get_settings_modal_toggles() {
            return array(
                'general'  => array(
                    'toggles' => array(
                        'main_content' => esc_html__( 'Gallery Settings', 'medianest' ),
                    ),
                ),
            );
        }

        public function get_fields() {
            return array(
                'folder_id' => array(
                    'label'           => esc_html__( 'Select Folder', 'medianest' ),
                    'type'            => 'select',
                    'option_category' => 'basic_option',
                    'options'         => $this->get_folder_options(),
                    'default'         => '0',
                    'description'     => esc_html__( 'Choose a folder to display images from', 'medianest' ),
                    'toggle_slug'     => 'main_content',
                ),
                'columns' => array(
                    'label'           => esc_html__( 'Columns', 'medianest' ),
                    'type'            => 'select',
                    'option_category' => 'layout',
                    'options'         => array(
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                    ),
                    'default'         => '3',
                    'description'     => esc_html__( 'Number of columns to display', 'medianest' ),
                    'toggle_slug'     => 'main_content',
                ),
                'link_to' => array(
                    'label'           => esc_html__( 'Link To', 'medianest' ),
                    'type'            => 'select',
                    'option_category' => 'configuration',
                    'options'         => array(
                        'none' => esc_html__( 'None', 'medianest' ),
                        'file' => esc_html__( 'Media File', 'medianest' ),
                        'post' => esc_html__( 'Attachment Page', 'medianest' ),
                    ),
                    'default'         => 'file',
                    'description'     => esc_html__( 'Link behavior when clicking on image', 'medianest' ),
                    'toggle_slug'     => 'main_content',
                ),
                'size' => array(
                    'label'           => esc_html__( 'Image Size', 'medianest' ),
                    'type'            => 'select',
                    'option_category' => 'configuration',
                    'options'         => array_merge(
                        array(
                            'full' => esc_html__( 'Full', 'medianest' )
                        ), 
                        $this->get_wp_image_sizes()
                    ),
                    'default'         => 'medium',
                    'description'     => esc_html__( 'Select image size', 'medianest' ),
                    'toggle_slug'     => 'main_content',
                ),
                'orderby' => array(
                    'label'           => esc_html__( 'Order By', 'medianest' ),
                    'type'            => 'select',
                    'option_category' => 'configuration',
                    'options'         => array(
                        'date'  => esc_html__( 'Date', 'medianest' ),
                        'title' => esc_html__( 'Title', 'medianest' ),
                        'rand'  => esc_html__( 'Random', 'medianest' ),
                    ),
                    'default'         => 'date',
                    'description'     => esc_html__( 'Order images by', 'medianest' ),
                    'toggle_slug'     => 'main_content',
                ),
                'order' => array(
                    'label'           => esc_html__( 'Order', 'medianest' ),
                    'type'            => 'select',
                    'option_category' => 'configuration',
                    'options'         => array(
                        'ASC'  => esc_html__( 'Ascending', 'medianest' ),
                        'DESC' => esc_html__( 'Descending', 'medianest' ),
                    ),
                    'default'         => 'DESC',
                    'description'     => esc_html__( 'Sort order', 'medianest' ),
                    'toggle_slug'     => 'main_content',
                ),
            );
        }

        private function get_folder_options() {
            $folders = \WPMN_Media_Folders::folder_tree('folder_only', 'attachment');
            $options = array(
                '0' => esc_html__( 'Select Folder', 'medianest' ),
            );
            $this->build_folder_options( $folders, $options );
            return $options;
        }

        private function build_folder_options( $folders, &$options, $prefix = '' ) {
            if ( ! is_array( $folders ) ) return;
            
            foreach ( $folders as $folder ) :
                $options['id_' . $folder['id']] = $prefix . $folder['name'];
                
                if ( ! empty( $folder['children'] ) && is_array( $folder['children'] ) ) :
                    $this->build_folder_options( $folder['children'], $options, $prefix . '— ' );
                endif;
            endforeach;
        }

        private function get_wp_image_sizes() {
            $sizes    = [];
            $wp_sizes = get_intermediate_image_sizes();
            
            foreach ( $wp_sizes as $size ) :
                $sizes[$size] = $size;
            endforeach;
            
            return $sizes;
        }

        public function render( $attrs, $content = null, $render_slug = '' ) {
            $folder_id_raw = isset( $attrs['folder_id'] ) ? $attrs['folder_id'] : '0';
            $columns       = isset( $attrs['columns'] ) ? $attrs['columns'] : '3';
            $size          = isset( $attrs['size'] ) ? $attrs['size'] : 'medium';
            $link_to       = isset( $attrs['link_to'] ) ? $attrs['link_to'] : 'file';
            $orderby       = isset( $attrs['orderby'] ) ? $attrs['orderby'] : 'date';
            $order         = isset( $attrs['order'] ) ? $attrs['order'] : 'DESC';

            $folder_id = (int) str_replace( 'id_', '', $folder_id_raw );

            if ( $folder_id <= 0 ) :
                return sprintf( '<div class="wpmn-gallery-error">%s</div>', esc_html__( 'Please select a folder', 'medianest' ) );
            endif;

            $attachment_ids = get_objects_in_term( $folder_id, 'wpmn_media_folder' );
            
            if ( empty( $attachment_ids ) || is_wp_error( $attachment_ids ) ) :
                return sprintf( '<div class="wpmn-gallery-empty">%s</div>', esc_html__( 'No images found in this folder', 'medianest' ) );
            endif;

            $args = array(
                'post_type'      => 'attachment',
                'post_status'    => 'inherit',
                'post__in'       => $attachment_ids,
                'posts_per_page' => -1,
                'orderby'        => $orderby,
                'order'          => $order,
            );

            $query = new \WP_Query( $args );
            
            if ( ! $query->have_posts() ) :
                wp_reset_postdata();
                return sprintf( '<div class="wpmn-gallery-empty">%s</div>', esc_html__( 'No images found in this folder', 'medianest' ) );
            endif;

            // Prepare settings for the template
            $settings = array(
                'columns' => $columns,
                'size'    => $size,
                'link_to' => $link_to,
            );

            ob_start();
            include WPMN_PATH . 'includes/admin/settings/PageBuilders/Elementor/views/wpmn-gallery.php';
            $output = ob_get_clean();

            wp_reset_postdata();

            return $output;
        }
    }

endif;
