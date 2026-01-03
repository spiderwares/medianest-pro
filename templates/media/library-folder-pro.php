<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<?php if ( $type === 'menu' ) : ?>

    <li class="mddr_sort_menu_item has-submenu">
        <span><?php echo esc_html__( 'Sort Folders', 'media-directory-pro' ); ?></span>
        <span class="dashicons dashicons-arrow-right-alt2"></span>
        <ul class="mddr_sort_menu_submenu">
            <li class="mddr_sort_folder_option" data-sort="asc">
                <span class="dashicons dashicons-yes mddr_check_icon"></span>
                <?php echo esc_html__( 'Ascending', 'media-directory-pro' ); ?>
            </li>
            <li class="mddr_sort_folder_option" data-sort="desc">
                <span class="dashicons dashicons-yes mddr_check_icon"></span>
                <?php echo esc_html__( 'Descending', 'media-directory-pro' ); ?>
            </li>
            <li class="mddr_sort_folder_option" data-sort="default">
                <span class="dashicons dashicons-yes mddr_check_icon"></span>
                <?php echo esc_html__( 'Default', 'media-directory-pro' ); ?>
            </li>
        </ul>
    </li>

    <li class="mddr_sort_menu_item has-submenu">
        <span><?php echo esc_html__( 'Sort Files', 'media-directory-pro' ); ?></span>
        <span class="dashicons dashicons-arrow-right-alt2"></span>
        <ul class="mddr_sort_menu_submenu">
            <li class="mddr_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Name', 'media-directory-pro' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="mddr_sort_menu_nested">
                    <li class="mddr_sort_files_option" data-sort-by="name" data-sort-order="asc">
                        <span class="dashicons dashicons-yes mddr_check_icon"></span>
                        <?php echo esc_html__( 'Ascending', 'media-directory-pro' ); ?>
                    </li>
                    <li class="mddr_sort_files_option" data-sort-by="name" data-sort-order="desc">
                        <span class="dashicons dashicons-yes mddr_check_icon"></span>
                        <?php echo esc_html__( 'Descending', 'media-directory-pro' ); ?>
                    </li>
                </ul>
            </li>
            <li class="mddr_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Date', 'media-directory-pro' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="mddr_sort_menu_nested">
                    <li class="mddr_sort_files_option" data-sort-by="date" data-sort-order="asc">
                        <span class="dashicons dashicons-yes mddr_check_icon"></span>
                        <?php echo esc_html__( 'Ascending', 'media-directory-pro' ); ?>
                    </li>
                    <li class="mddr_sort_files_option" data-sort-by="date" data-sort-order="desc">
                        <span class="dashicons dashicons-yes mddr_check_icon"></span>
                        <?php echo esc_html__( 'Descending', 'media-directory-pro' ); ?>
                    </li>
                </ul>
            </li>
            <li class="mddr_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Modified', 'media-directory-pro' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="mddr_sort_menu_nested">
                    <li class="mddr_sort_files_option" data-sort-by="modified" data-sort-order="asc">
                        <span class="dashicons dashicons-yes mddr_check_icon"></span>
                        <?php echo esc_html__( 'Ascending', 'media-directory-pro' ); ?>
                    </li>
                    <li class="mddr_sort_files_option" data-sort-by="modified" data-sort-order="desc">
                        <span class="dashicons dashicons-yes mddr_check_icon"></span>
                        <?php echo esc_html__( 'Descending', 'media-directory-pro' ); ?>
                    </li>
                </ul>
            </li>
            
            <?php if ( $is_attachment ) : ?>
            <li class="mddr_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Author', 'media-directory-pro' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="mddr_sort_menu_nested">
                    <li class="mddr_sort_files_option" data-sort-by="author" data-sort-order="asc">
                        <span class="dashicons dashicons-yes mddr_check_icon"></span>
                        <?php echo esc_html__( 'Ascending', 'media-directory-pro' ); ?>
                    </li>
                    <li class="mddr_sort_files_option" data-sort-by="author" data-sort-order="desc">
                        <span class="dashicons dashicons-yes mddr_check_icon"></span>
                        <?php echo esc_html__( 'Descending', 'media-directory-pro' ); ?>
                    </li>
                </ul>
            </li>
            <li class="mddr_sort_menu_subitem has-nested">
                <span><?php echo esc_html__( 'By Size', 'media-directory-pro' ); ?></span>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
                <ul class="mddr_sort_menu_nested">
                    <li class="mddr_sort_files_option" data-sort-by="size" data-sort-order="asc">
                        <span class="dashicons dashicons-yes mddr_check_icon"></span>
                        <?php echo esc_html__( 'Ascending', 'media-directory-pro' ); ?>
                    </li>
                    <li class="mddr_sort_files_option" data-sort-by="size" data-sort-order="desc">
                        <span class="dashicons dashicons-yes mddr_check_icon"></span>
                        <?php echo esc_html__( 'Descending', 'media-directory-pro' ); ?>
                    </li>
                </ul>
            </li>
            <?php endif; ?>
            
            <hr>
            <li class="mddr_sort_files_option" data-sort-by="default" data-sort-order="desc">
                <span class="dashicons dashicons-yes mddr_check_icon"></span>
                <span><?php echo esc_html__( 'Default', 'media-directory-pro' ); ?></span>
            </li>
        </ul>
    </li>


<?php elseif ( $type === 'submenu' ) : ?>

    <li class="mddr_count_mode_item" data-mode="all_files">
        <span class="dashicons dashicons-yes mddr_check_icon"></span>
        <span><?php echo esc_html__( 'Count files in parent and subfolders', 'media-directory-pro' ); ?></span>
    </li>

<?php elseif ( $type === 'default_folder' ) : ?>    

    <div class="mddr_settings_field">
        <label for="mddr_default_sort"><?php echo esc_html__( 'Sort files automatically by', 'media-directory-pro' ); ?></label>
        <select id="mddr_default_sort" class="mddr_settings_select">
            <option value="default"><?php echo esc_html__( 'Default', 'media-directory-pro' ); ?></option>
            <option value="title-asc"><?php echo esc_html__( 'Title Ascending', 'media-directory-pro' ); ?></option>
            <option value="title-desc"><?php echo esc_html__( 'Title Descending', 'media-directory-pro' ); ?></option>
            <option value="date-asc"><?php echo esc_html__( 'Date Ascending', 'media-directory-pro' ); ?></option>
            <option value="date-desc"><?php echo esc_html__( 'Date Descending', 'media-directory-pro' ); ?></option>
            <option value="modified-asc"><?php echo esc_html__( 'Modified Ascending', 'media-directory-pro' ); ?></option>
            <option value="modified-desc"><?php echo esc_html__( 'Modified Descending', 'media-directory-pro' ); ?></option>
            <option value="author-asc"><?php echo esc_html__( 'Author Ascending', 'media-directory-pro' ); ?></option>
            <option value="author-desc"><?php echo esc_html__( 'Author Descending', 'media-directory-pro' ); ?></option>
        </select>
    </div>

<?php elseif ( $type === 'theme_buttons' ) : ?>

    <button type="button" class="mddr_theme_btn" data-theme="windows">
        <?php echo esc_html__( 'Windows', 'media-directory-pro' ); ?>
    </button>
    <button type="button" class="mddr_theme_btn" data-theme="dropbox">
        <?php echo esc_html__( 'Dropbox', 'media-directory-pro' ); ?>
    </button>

<?php elseif ( $type === 'folder_context_menu' ) : ?>
    
    <div class="mddr_context_menu_item" data-action="pin-folder"
        data-text-pin="<?php echo esc_attr__( 'Pin to Top', 'media-directory-pro' ); ?>"
        data-text-unpin="<?php echo esc_attr__( 'Unpin from Top', 'media-directory-pro' ); ?>"
        data-icon-pin="dashicons-admin-post"
        data-icon-unpin="dashicons-sticky">
        <span class="dashicons dashicons-admin-post"></span>
        <span><?php echo esc_html__( 'Pin to Top', 'media-directory-pro' ); ?></span>
    </div>
    
    <div class="mddr_context_menu_item" data-action="duplicate">
        <img src="<?php echo esc_url( MDDR_PRO_URL . 'assets/img/duplicate.svg'); ?>" alt="" class="mddr_folder_content_duplicate" />
        <span><?php echo esc_html__( 'Duplicate', 'media-directory-pro' ); ?></span>
    </div>

    <div class="mddr_context_menu_item has-submenu" data-action="change_color_menu">
        <div class="mddr_menu_label">
            <img src="<?php echo esc_url( MDDR_PRO_URL . 'assets/img/color.svg'); ?>" alt="" class="mddr_folder_content_color" />
            <span><?php echo esc_html__( 'Change Color', 'media-directory-pro' ); ?></span>
            <span class="dashicons dashicons-arrow-right-alt2 mddr_submenu_arrow"></span>
        </div>  
        <div class="mddr_color_picker_dropdown">
            <div class="mddr_color_grid">
                <?php
                $mddr_colors = array(
                    '#f44336', '#ff5722', '#ff9800', '#ffc107', '#1a237e', '#311b92',
                    '#2196f3', '#03a9f4', '#4caf50', '#8bc34a', '#673ab7', '#9c27b0', 
                    '#b39ddb', '#e91e63', '#f06292', '#3e2723', '#9e9e9e', '#000000'
                );
                foreach ( $mddr_colors as $mddr_color ) :
                    echo '<div class="mddr_color_option" data-color="' . esc_attr( $mddr_color ) . '" style="background-color:' . esc_attr( $mddr_color ) . ';"></div>';
                endforeach;
                ?>
            </div>
            <hr>
            <div class="mddr_custom_color_row">
                <div class="mddr_current_color_preview">
                    <span class="dashicons dashicons-yes"></span>
                </div>
                <span class="dashicons dashicons-update mddr_refresh_color"></span>
            </div>
        </div>
    </div>
    
    <?php if ( $is_attachment ) : ?>
    <div class="mddr_context_menu_item" data-action="download">
        <img src="<?php echo esc_url( MDDR_PRO_URL . 'assets/img/download.svg'); ?>" alt="" class="mddr_folder_content_download" />
        <span><?php echo esc_html__( 'Download', 'media-directory-pro' ); ?></span>
    </div>
    <?php endif; ?>

<?php elseif ( $type === 'collapsed_menu_item' ) : ?>

    <li class="mddr_more_menu_item" data-action="collapse-all"
        data-text-hide="<?php echo esc_attr__( 'Collapse all', 'media-directory-pro' ); ?>"
        data-text-show="<?php echo esc_attr__( 'Expand all', 'media-directory-pro' ); ?>"
        data-icon-hide="dashicons-editor-contract"
        data-icon-show="dashicons-editor-expand">
        <span class="dashicons dashicons-editor-contract"></span>
        <span><?php echo esc_html__( 'Collapse all', 'media-directory-pro' ); ?></span>
    </li>

<?php endif; ?>
