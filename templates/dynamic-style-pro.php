<?php
/**
 * Dynamic inline styles pro for Spin Rewards for WooCommerce.
 *
 * @package Spin_Rewards_Pro_For_WooCommerce
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( empty( $settings ) || ! is_array( $settings ) ) :
	return;
endif;