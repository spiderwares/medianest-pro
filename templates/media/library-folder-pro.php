<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<?php if ( $type === 'menu' ) : ?>

    <li class="wpmn_sort_menu_item has-submenu">
        <span><?php echo esc_html__( 'Sort Folders', 'medianest_pro' ); ?></span>
        <span class="dashicons dashicons-arrow-right-alt2"></span>
        <ul class="wpmn_sort_menu_submenu">
            <li class="wpmn_sort_folder_option" data-sort="asc">
                <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                <?php echo esc_html__( 'Ascending', 'medianest_pro' ); ?>
            </li>
            <li class="wpmn_sort_folder_option" data-sort="desc">
                <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                <?php echo esc_html__( 'Descending', 'medianest_pro' ); ?>
            </li>
            <li class="wpmn_sort_folder_option" data-sort="default">
                <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                <?php echo esc_html__( 'Default', 'medianest_pro' ); ?>
            </li>
        </ul>
    </li>

    <li class="wpmn_sort_menu_item has-submenu">
        <span><?php echo esc_html__( 'Sort Files', 'medianest_pro' ); ?></span>
        <span class="dashicons dashicons-arrow-right-alt2"></span>
        <ul class="wpmn_sort_menu_submenu">
            <li class="wpmn_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Name', 'medianest_pro' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="wpmn_sort_menu_nested">
                    <li class="wpmn_sort_files_option" data-sort-by="name" data-sort-order="asc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Ascending', 'medianest_pro' ); ?>
                    </li>
                    <li class="wpmn_sort_files_option" data-sort-by="name" data-sort-order="desc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Descending', 'medianest_pro' ); ?>
                    </li>
                </ul>
            </li>
            <li class="wpmn_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Date', 'medianest_pro' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="wpmn_sort_menu_nested">
                    <li class="wpmn_sort_files_option" data-sort-by="date" data-sort-order="asc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Ascending', 'medianest_pro' ); ?>
                    </li>
                    <li class="wpmn_sort_files_option" data-sort-by="date" data-sort-order="desc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Descending', 'medianest_pro' ); ?>
                    </li>
                </ul>
            </li>
            <li class="wpmn_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Modified', 'medianest_pro' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="wpmn_sort_menu_nested">
                    <li class="wpmn_sort_files_option" data-sort-by="modified" data-sort-order="asc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Ascending', 'medianest_pro' ); ?>
                    </li>
                    <li class="wpmn_sort_files_option" data-sort-by="modified" data-sort-order="desc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Descending', 'medianest_pro' ); ?>
                    </li>
                </ul>
            </li>
            <li class="wpmn_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Author', 'medianest_pro' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="wpmn_sort_menu_nested">
                    <li class="wpmn_sort_files_option" data-sort-by="author" data-sort-order="asc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Ascending', 'medianest_pro' ); ?>
                    </li>
                    <li class="wpmn_sort_files_option" data-sort-by="author" data-sort-order="desc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Descending', 'medianest_pro' ); ?>
                    </li>
                </ul>
            </li>
            <li class="wpmn_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Size', 'medianest_pro' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="wpmn_sort_menu_nested">
                    <li class="wpmn_sort_files_option" data-sort-by="size" data-sort-order="asc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Ascending', 'medianest_pro' ); ?>
                    </li>
                    <li class="wpmn_sort_files_option" data-sort-by="size" data-sort-order="desc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Descending', 'medianest_pro' ); ?>
                    </li>
                </ul>
            </li>
            <hr>
            <li class="wpmn_sort_files_option" data-sort-by="default" data-sort-order="desc">
                <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                <span><?php echo esc_html__( 'Default', 'medianest_pro' ); ?></span>
            </li>
        </ul>
    </li>


<?php elseif ( $type === 'submenu' ) : ?>

    <li class="wpmn_count_mode_item" data-mode="all_files">
        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
        <span><?php echo esc_html__( 'Count files in parent and subfolders', 'medianest_pro' ); ?></span>
    </li>

<?php elseif ( $type === 'default_folder' ) : ?>    

    <div class="wpmn_settings_field">
        <label for="wpmn_default_sort"><?php echo esc_html__( 'Sort files automatically by', 'medianest_pro' ); ?></label>
        <select id="wpmn_default_sort" class="wpmn_settings_select">
            <option value="default"><?php echo esc_html__( 'Default', 'medianest_pro' ); ?></option>
            <option value="title-asc"><?php echo esc_html__( 'Name Ascending', 'medianest_pro' ); ?></option>
            <option value="title-desc"><?php echo esc_html__( 'Name Descending', 'medianest_pro' ); ?></option>
            <option value="date-asc"><?php echo esc_html__( 'Date Ascending', 'medianest_pro' ); ?></option>
            <option value="date-desc"><?php echo esc_html__( 'Date Descending', 'medianest_pro' ); ?></option>
            <option value="modified-asc"><?php echo esc_html__( 'Modified Ascending', 'medianest_pro' ); ?></option>
            <option value="modified-desc"><?php echo esc_html__( 'Modified Descending', 'medianest_pro' ); ?></option>
            <option value="author-asc"><?php echo esc_html__( 'Author Ascending', 'medianest_pro' ); ?></option>
            <option value="author-desc"><?php echo esc_html__( 'Author Descending', 'medianest_pro' ); ?></option>
        </select>
    </div>

<?php elseif ( $type === 'theme_buttons' ) : ?>

    <button type="button" class="wpmn_theme_btn" data-theme="windows">
        <?php echo esc_html__( 'Windows', 'medianest_pro' ); ?>
    </button>
    <button type="button" class="wpmn_theme_btn" data-theme="dropbox">
        <?php echo esc_html__( 'Dropbox', 'medianest_pro' ); ?>
    </button>

<?php elseif ( $type === 'folder_context_menu' ) : ?>

    <div class="wpmn_context_menu_item has-submenu" data-action="change_color_menu">
        <div class="wpmn_menu_label">
            <img src="<?php echo esc_url( WPMN_URL . 'assets/img/color.svg'); ?>" alt="" class="wpmn_folder_content_color" />
            <span><?php echo esc_html__( 'Modify Color', 'medianest_pro' ); ?></span>
            <span class="dashicons dashicons-arrow-right-alt2 wpmn_submenu_arrow"></span>
        </div>
        <div class="wpmn_color_picker_dropdown">
            <div class="wpmn_color_grid">
                <?php
                $wpmn_colors = array(
                    '#f44336', '#ff5722', '#ff9800', '#ffc107', '#ffeb3b', '#cddc39',
                    '#2196f3', '#03a9f4', '#e3f2fd', '#4caf50', '#8bc34a', '#aed581',
                    '#673ab7', '#9c27b0', '#b39ddb', '#e91e63', '#f06292', '#9e9e9e'
                );
                foreach ( $wpmn_colors as $wpmn_color ) {
                    echo '<div class="wpmn_color_option" data-color="' . esc_attr( $wpmn_color ) . '" style="background-color:' . esc_attr( $wpmn_color ) . ';"></div>';
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
        <span><?php echo esc_html__( 'Download', 'medianest_pro' ); ?></span>
    </div>

<?php endif; ?>

