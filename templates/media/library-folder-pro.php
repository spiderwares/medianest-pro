<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<?php if ( $type === 'menu' ) : ?>

    <li class="wpmn_sort_menu_item has-submenu">
        <span><?php echo esc_html__( 'Sort Folders', 'medianest-pro' ); ?></span>
        <span class="dashicons dashicons-arrow-right-alt2"></span>
        <ul class="wpmn_sort_menu_submenu">
            <li class="wpmn_sort_folder_option" data-sort="asc">
                <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                <?php echo esc_html__( 'Ascending', 'medianest-pro' ); ?>
            </li>
            <li class="wpmn_sort_folder_option" data-sort="desc">
                <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                <?php echo esc_html__( 'Descending', 'medianest-pro' ); ?>
            </li>
            <li class="wpmn_sort_folder_option" data-sort="default">
                <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                <?php echo esc_html__( 'Default', 'medianest-pro' ); ?>
            </li>
        </ul>
    </li>

    <li class="wpmn_sort_menu_item has-submenu">
        <span><?php echo esc_html__( 'Sort Files', 'medianest-pro' ); ?></span>
        <span class="dashicons dashicons-arrow-right-alt2"></span>
        <ul class="wpmn_sort_menu_submenu">
            <li class="wpmn_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Name', 'medianest-pro' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="wpmn_sort_menu_nested">
                    <li class="wpmn_sort_files_option" data-sort-by="name" data-sort-order="asc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Ascending', 'medianest-pro' ); ?>
                    </li>
                    <li class="wpmn_sort_files_option" data-sort-by="name" data-sort-order="desc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Descending', 'medianest-pro' ); ?>
                    </li>
                </ul>
            </li>
            <li class="wpmn_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Date', 'medianest-pro' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="wpmn_sort_menu_nested">
                    <li class="wpmn_sort_files_option" data-sort-by="date" data-sort-order="asc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Ascending', 'medianest-pro' ); ?>
                    </li>
                    <li class="wpmn_sort_files_option" data-sort-by="date" data-sort-order="desc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Descending', 'medianest-pro' ); ?>
                    </li>
                </ul>
            </li>
            <li class="wpmn_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Modified', 'medianest-pro' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="wpmn_sort_menu_nested">
                    <li class="wpmn_sort_files_option" data-sort-by="modified" data-sort-order="asc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Ascending', 'medianest-pro' ); ?>
                    </li>
                    <li class="wpmn_sort_files_option" data-sort-by="modified" data-sort-order="desc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Descending', 'medianest-pro' ); ?>
                    </li>
                </ul>
            </li>
            
            <?php if ( $is_attachment ) : ?>
            <li class="wpmn_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Author', 'medianest-pro' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="wpmn_sort_menu_nested">
                    <li class="wpmn_sort_files_option" data-sort-by="author" data-sort-order="asc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Ascending', 'medianest-pro' ); ?>
                    </li>
                    <li class="wpmn_sort_files_option" data-sort-by="author" data-sort-order="desc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Descending', 'medianest-pro' ); ?>
                    </li>
                </ul>
            </li>
            <li class="wpmn_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Size', 'medianest-pro' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="wpmn_sort_menu_nested">
                    <li class="wpmn_sort_files_option" data-sort-by="size" data-sort-order="asc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Ascending', 'medianest-pro' ); ?>
                    </li>
                    <li class="wpmn_sort_files_option" data-sort-by="size" data-sort-order="desc">
                        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                        <?php echo esc_html__( 'Descending', 'medianest-pro' ); ?>
                    </li>
                </ul>
            </li>
            <?php endif; ?>
            
            <hr>
            <li class="wpmn_sort_files_option" data-sort-by="default" data-sort-order="desc">
                <span class="dashicons dashicons-yes wpmn_check_icon"></span>
                <span><?php echo esc_html__( 'Default', 'medianest-pro' ); ?></span>
            </li>
        </ul>
    </li>


<?php elseif ( $type === 'submenu' ) : ?>

    <li class="wpmn_count_mode_item" data-mode="all_files">
        <span class="dashicons dashicons-yes wpmn_check_icon"></span>
        <span><?php echo esc_html__( 'Count files in parent and subfolders', 'medianest-pro' ); ?></span>
    </li>

<?php elseif ( $type === 'default_folder' ) : ?>    

    <div class="wpmn_settings_field">
        <label for="wpmn_default_sort"><?php echo esc_html__( 'Sort files automatically by', 'medianest-pro' ); ?></label>
        <select id="wpmn_default_sort" class="wpmn_settings_select">
            <option value="default"><?php echo esc_html__( 'Default', 'medianest-pro' ); ?></option>
            <option value="title-asc"><?php echo esc_html__( 'Title Ascending', 'medianest-pro' ); ?></option>
            <option value="title-desc"><?php echo esc_html__( 'Title Descending', 'medianest-pro' ); ?></option>
            <option value="date-asc"><?php echo esc_html__( 'Date Ascending', 'medianest-pro' ); ?></option>
            <option value="date-desc"><?php echo esc_html__( 'Date Descending', 'medianest-pro' ); ?></option>
            <option value="modified-asc"><?php echo esc_html__( 'Modified Ascending', 'medianest-pro' ); ?></option>
            <option value="modified-desc"><?php echo esc_html__( 'Modified Descending', 'medianest-pro' ); ?></option>
            <option value="author-asc"><?php echo esc_html__( 'Author Ascending', 'medianest-pro' ); ?></option>
            <option value="author-desc"><?php echo esc_html__( 'Author Descending', 'medianest-pro' ); ?></option>
        </select>
    </div>

<?php elseif ( $type === 'theme_buttons' ) : ?>

    <button type="button" class="wpmn_theme_btn" data-theme="windows">
        <?php echo esc_html__( 'Windows', 'medianest-pro' ); ?>
    </button>
    <button type="button" class="wpmn_theme_btn" data-theme="dropbox">
        <?php echo esc_html__( 'Dropbox', 'medianest-pro' ); ?>
    </button>

<?php elseif ( $type === 'folder_context_menu' ) : ?>
    
    <div class="wpmn_context_menu_item" data-action="pin-folder"
        data-text-pin="<?php echo esc_attr__( 'Pin to Top', 'medianest-pro' ); ?>"
        data-text-unpin="<?php echo esc_attr__( 'Unpin from Top', 'medianest-pro' ); ?>"
        data-icon-pin="dashicons-admin-post"
        data-icon-unpin="dashicons-sticky">
        <span class="dashicons dashicons-admin-post"></span>
        <span><?php echo esc_html__( 'Pin to Top', 'medianest-pro' ); ?></span>
    </div>
    
    <div class="wpmn_context_menu_item" data-action="duplicate">
        <img src="<?php echo esc_url( WPMN_PRO_URL . 'assets/img/duplicate.svg'); ?>" alt="" class="wpmn_folder_content_duplicate" />
        <span><?php echo esc_html__( 'Duplicate', 'medianest-pro' ); ?></span>
    </div>

    <div class="wpmn_context_menu_item has-submenu" data-action="change_color_menu">
        <div class="wpmn_menu_label">
            <img src="<?php echo esc_url( WPMN_PRO_URL . 'assets/img/color.svg'); ?>" alt="" class="wpmn_folder_content_color" />
            <span><?php echo esc_html__( 'Change Color', 'medianest-pro' ); ?></span>
            <span class="dashicons dashicons-arrow-right-alt2 wpmn_submenu_arrow"></span>
        </div>  
        <div class="wpmn_color_picker_dropdown">
            <div class="wpmn_color_grid">
                <?php
                $wpmn_colors = array(
                    '#f44336', '#ff5722', '#ff9800', '#ffc107', '#1a237e', '#311b92',
                    '#2196f3', '#03a9f4', '#4caf50', '#8bc34a', '#673ab7', '#9c27b0', 
                    '#b39ddb', '#e91e63', '#f06292', '#3e2723', '#9e9e9e', '#000000'
                );
                foreach ( $wpmn_colors as $wpmn_color ) :
                    echo '<div class="wpmn_color_option" data-color="' . esc_attr( $wpmn_color ) . '" style="background-color:' . esc_attr( $wpmn_color ) . ';"></div>';
                endforeach;
                ?>
            </div>
            <hr>
            <div class="wpmn_custom_color_row">
                <div class="wpmn_current_color_preview">
                    <span class="dashicons dashicons-yes"></span>
                </div>
                <span class="dashicons dashicons-update wpmn_refresh_color"></span>
            </div>
        </div>
    </div>
    
    <?php if ( $is_attachment ) : ?>
    <div class="wpmn_context_menu_item" data-action="download">
        <img src="<?php echo esc_url( WPMN_PRO_URL . 'assets/img/download.svg'); ?>" alt="" class="wpmn_folder_content_download" />
        <span><?php echo esc_html__( 'Download', 'medianest-pro' ); ?></span>
    </div>
    <?php endif; ?>

<?php elseif ( $type === 'collapsed_menu_item' ) : ?>

    <li class="wpmn_more_menu_item" data-action="collapse-all"
        data-text-hide="<?php echo esc_attr__( 'Collapse all', 'medianest-pro' ); ?>"
        data-text-show="<?php echo esc_attr__( 'Expand all', 'medianest-pro' ); ?>"
        data-icon-hide="dashicons-editor-contract"
        data-icon-show="dashicons-editor-expand">
        <span class="dashicons dashicons-editor-contract"></span>
        <span><?php echo esc_html__( 'Collapse all', 'medianest-pro' ); ?></span>
    </li>

<?php endif; ?>
