<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<?php if ( $type === 'menu' ) : ?>

    <li class="wpmn_sort_menu_item has-submenu">
        <span><?php echo esc_html__( 'Sort Folders', 'medianest' ); ?></span>
        <span class="dashicons dashicons-arrow-right-alt2"></span>
        <ul class="wpmn_sort_menu_submenu">
            <li class="wpmn_sort_folder_option" data-sort="asc">
                <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                <?php echo esc_html__( 'Ascending', 'medianest' ); ?>
            </li>
            <li class="wpmn_sort_folder_option" data-sort="desc">
                <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                <?php echo esc_html__( 'Descending', 'medianest' ); ?>
            </li>
            <li class="wpmn_sort_folder_option is-active" data-sort="default">
                <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                <?php echo esc_html__( 'Default', 'medianest' ); ?>
            </li>
        </ul>
    </li>

    <li class="wpmn_sort_menu_item has-submenu">
        <span><?php echo esc_html__( 'Sort Files', 'medianest' ); ?></span>
        <span class="dashicons dashicons-arrow-right-alt2"></span>
        <ul class="wpmn_sort_menu_submenu">
            <li class="wpmn_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Name', 'medianest' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="wpmn_sort_menu_nested">
                    <li class="wpmn_sort_files_option" data-sort-by="name" data-sort-order="asc">
                        <span class="dashicons dashicons-yes wpmn_check_icon" style="display:none;"></span>
                        <?php echo esc_html__( 'Ascending', 'medianest' ); ?>
                    </li>
                    <li class="wpmn_sort_files_option" data-sort-by="name" data-sort-order="desc">
                        <span class="dashicons dashicons-yes wpmn_check_icon" style="display:none;"></span>
                        <?php echo esc_html__( 'Descending', 'medianest' ); ?>
                    </li>
                </ul>
            </li>
            <li class="wpmn_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Date', 'medianest' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="wpmn_sort_menu_nested">
                    <li class="wpmn_sort_files_option" data-sort-by="date" data-sort-order="asc">
                        <span class="dashicons dashicons-yes wpmn_check_icon" style="display:none;"></span>
                        <?php echo esc_html__( 'Ascending', 'medianest' ); ?>
                    </li>
                    <li class="wpmn_sort_files_option" data-sort-by="date" data-sort-order="desc">
                        <span class="dashicons dashicons-yes wpmn_check_icon" style="display:none;"></span>
                        <?php echo esc_html__( 'Descending', 'medianest' ); ?>
                    </li>
                </ul>
            </li>
            <li class="wpmn_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Modified', 'medianest' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="wpmn_sort_menu_nested">
                    <li class="wpmn_sort_files_option" data-sort-by="modified" data-sort-order="asc">
                        <span class="dashicons dashicons-yes wpmn_check_icon" style="display:none;"></span>
                        <?php echo esc_html__( 'Ascending', 'medianest' ); ?>
                    </li>
                    <li class="wpmn_sort_files_option" data-sort-by="modified" data-sort-order="desc">
                        <span class="dashicons dashicons-yes wpmn_check_icon" style="display:none;"></span>
                        <?php echo esc_html__( 'Descending', 'medianest' ); ?>
                    </li>
                </ul>
            </li>
            <li class="wpmn_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Author', 'medianest' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="wpmn_sort_menu_nested">
                    <li class="wpmn_sort_files_option" data-sort-by="author" data-sort-order="asc">
                        <span class="dashicons dashicons-yes wpmn_check_icon" style="display:none;"></span>
                        <?php echo esc_html__( 'Ascending', 'medianest' ); ?>
                    </li>
                    <li class="wpmn_sort_files_option" data-sort-by="author" data-sort-order="desc">
                        <span class="dashicons dashicons-yes wpmn_check_icon" style="display:none;"></span>
                        <?php echo esc_html__( 'Descending', 'medianest' ); ?>
                    </li>
                </ul>
            </li>
            <li class="wpmn_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Size', 'medianest' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="wpmn_sort_menu_nested">
                    <li class="wpmn_sort_files_option" data-sort-by="size" data-sort-order="asc">
                        <span class="dashicons dashicons-yes wpmn_check_icon" style="display:none;"></span>
                        <?php echo esc_html__( 'Ascending', 'medianest' ); ?>
                    </li>
                    <li class="wpmn_sort_files_option" data-sort-by="size" data-sort-order="desc">
                        <span class="dashicons dashicons-yes wpmn_check_icon" style="display:none;"></span>
                        <?php echo esc_html__( 'Descending', 'medianest' ); ?>
                    </li>
                </ul>
            </li>
            <hr>
            <li class="wpmn_sort_files_option is-active" data-sort-by="default" data-sort-order="desc">
                <span class="dashicons dashicons-yes wpmn_check_icon" style="display:none;"></span>
                <span><?php echo esc_html__( 'Default', 'medianest' ); ?></span>
            </li>
        </ul>
    </li>


<?php elseif ( $type === 'submenu' ) : ?>

    <li class="wpmn_count_mode_item">
        <span><?php echo esc_html__( 'Count files in parent and subfolders', 'medianest' ); ?></span>
    </li>

<?php elseif ( $type === 'theme_buttons' ) : ?>

    <button type="button" class="wpmn_theme_btn">
        <?php echo esc_html__( 'Windows', 'medianest' ); ?>
    </button>
    <button type="button" class="wpmn_theme_btn">
        <?php echo esc_html__( 'Dropbox', 'medianest' ); ?>
    </button>

<?php elseif ( $type === 'folder_context_menu' ) : ?>

    <div class="wpmn_context_menu_item has-submenu" data-action="change_color_menu">
        <div class="wpmn_menu_label">
            <img src="<?php echo esc_url( WPMN_URL . 'assets/img/color.svg'); ?>" alt="" class="wpmn_folder_content_color" />
            <span><?php echo esc_html__( 'Modify Color', 'medianest' ); ?></span>
            <span class="dashicons dashicons-arrow-right-alt2 wpmn_submenu_arrow"></span>
        </div>
        <div class="wpmn_color_picker_dropdown">
            <div class="wpmn_color_grid">
                <?php
                $colors = array(
                    '#f44336', '#ff5722', '#ff9800', '#ffc107', '#ffeb3b', '#cddc39',
                    '#2196f3', '#03a9f4', '#e3f2fd', '#4caf50', '#8bc34a', '#aed581',
                    '#673ab7', '#9c27b0', '#b39ddb', '#e91e63', '#f06292', '#9e9e9e'
                );
                foreach ( $colors as $color ) {
                    echo '<div class="wpmn_color_option" data-color="' . esc_attr( $color ) . '" style="background-color:' . esc_attr( $color ) . ';"></div>';
                }
                ?>
            </div>
            <hr>
            <div class="wpmn_custom_color_row">
                <span class="wpmn_current_color_preview"></span>
                <input type="text" class="wpmn_custom_color_input" placeholder="#RRGGBB" />
                <span class="dashicons dashicons-update wpmn_refresh_color"></span>
            </div>
        </div>
    </div>
    
    <div class="wpmn_context_menu_item" data-action="download">
        <img src="<?php echo esc_url( WPMN_URL . 'assets/img/download.svg'); ?>" alt="" class="wpmn_folder_content_download" />
        <span><?php echo esc_html__( 'Download', 'medianest' ); ?></span>
    </div>

<?php endif; ?>

