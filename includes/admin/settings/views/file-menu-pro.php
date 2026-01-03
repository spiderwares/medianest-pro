<?php
/**
 * Post type settings tab template.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<a href="<?php echo esc_url( admin_url( 'admin.php?page=cosmic-mddr&tab=post-type' ) ); ?>" 
   class="<?php echo esc_attr( $active_tab === 'post-type' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
    <img src="<?php echo esc_url( MDDR_PRO_URL . 'assets/img/post-type.svg'); ?>" />
    <?php echo esc_html__( 'Post Type', 'media-directory-pro' ); ?>
</a>
    