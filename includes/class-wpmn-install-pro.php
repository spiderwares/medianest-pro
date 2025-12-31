<?php

/**
 * Installation related functions and actions.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPMN_Install_Pro' ) ) :

    /**
     * WPMN_Install_Pro Class
     *
     */
    class WPMN_Install_Pro {

        /**
         * Hook into WordPress actions and filters.
         */
        public static function init() {
            add_filter( 'plugin_action_links_' . plugin_basename( WPMN_PRO_FILE ), array( __CLASS__, 'plugin_action_links' ) );
            add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 2 );
        }

        /**
         * Add link to Documentation.
         *
         * @param array $links Array of action links.
         * @param string $file Plugin file.
         * @return array Modified array of action links.
         */
        public static function plugin_row_meta( $links, $file ) {
            if ( plugin_basename( WPMN_PRO_FILE ) === $file ) :
                $doc_url   = esc_url( 'https://documentation.cosmicinfosoftware.com/medianest/documents/getting-started/introduction/' );
                $doc_label = esc_html( 'Documentation' );
        
                $new_links = array(
                    '<a href="' . $doc_url . '" target="_blank">' . $doc_label . '</a>',
                );
        
                $links = array_merge( $links, $new_links );
            endif;
        
            return $links;
        }

        /**
         * Install plugin.
         *
         * Creates tables, roles, and necessary pages on plugin activation.
         */
        public static function install() {
            if ( ! is_blog_installed() ) :
                return;
            endif;

            // Restore post types from backup if exists
            $backup = get_option( 'wpmn_pro_post_types_backup' );
            if ( $backup ) {
                $settings = get_option( 'wpmn_settings', [] );
                $settings['post_types'] = $backup;
                update_option( 'wpmn_settings', $settings );
            }
        }

        /**
         * Deactivate plugin.
         */
        public static function deactivate() {
            $settings = get_option( 'wpmn_settings', [] );
            
            // Backup and remove post types on deactivation
            if ( isset( $settings['post_types'] ) ) {
                update_option( 'wpmn_pro_post_types_backup', $settings['post_types'] );
                unset( $settings['post_types'] );
                update_option( 'wpmn_settings', $settings );
            }
        }

        /**
         * Add plugin action links.
         *
         * @param array $links Array of action links.
         * @return array Modified array of action links.
         */
        public static function plugin_action_links( $links ) {
            $action_links = array(
                'settings' => sprintf(
                    '<a href="%s" aria-label="%s">%s</a>',
                    admin_url( 'admin.php?page=cosmic-wpmn' ),
                    esc_attr__( 'Settings', 'medianest-pro' ),
                    esc_html__( 'Settings', 'medianest-pro' )
                ),
            );
            return array_merge( $action_links, $links );
        }
    }

    WPMN_Install_Pro::init();

endif;