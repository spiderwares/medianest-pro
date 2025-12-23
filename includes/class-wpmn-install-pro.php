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
     * Handles installation processes like creating database tables,
     * setting up roles, and creating necessary pages on plugin activation.
     */
    class WPMN_Install_Pro {

        /**
         * Hook into WordPress actions and filters.
         */
        public static function init() {
            add_filter( 'plugin_action_links_' . plugin_basename( WPMN_PRO_FILE ), array( __CLASS__, 'plugin_action_links' ) );
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