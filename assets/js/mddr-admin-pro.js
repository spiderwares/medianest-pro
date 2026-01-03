'use strict';

jQuery(function ($) {

    class MDDR_Admin_Pro {

        constructor() {
            this.init();
        }

        init() {
            if (window.mddr_media_library && window.mddr_media_library.admin) {
                this.admin = window.mddr_media_library.admin;
                this.bindEvents();
                this.registerHooks();
                this.applyInitialActiveStates();
            }
        }

        getStorageKey(key) {
            const postType = this.admin.getPostType();
            return `mddr_${postType}_${key}`;
        }

        bindEvents() {
            $(document.body).on('click', '.mddr_sort_folder_option', this.handleSortFolders.bind(this));
            $(document.body).on('click', '.mddr_sort_files_option', this.handleSortFiles.bind(this));
            $(document.body).on('change', '#mddr_default_sort', this.handleDefaultSortChange.bind(this));
            $(document.body).on('click', '.mddr_count_mode_item', this.handleCountMode.bind(this));
            $(document.body).on('click', '.mddr_theme_btn', this.handleThemeToggle.bind(this));
            $(document.body).on('change', 'input[name="mddr_settings[theme_design]"]', this.handleThemeDesign.bind(this));
            $(document.body).on('click', '.mddr_color_option', this.handlePresetColor.bind(this));
            $(document.body).on('click', '.mddr_refresh_color', this.handleResetColor.bind(this));
            $(document.body).on('click', '.mddr_current_color_preview', this.handlePreviewClick.bind(this));
            $(document.body).on('mouseenter', '.mddr_context_menu_item.has-submenu[data-action="change_color_menu"]', this.syncColorPicker.bind(this));
            $(document.body).on('click', '.mddr_more_menu_item[data-action="collapse-all"]', this.toggleCollapseAll.bind(this));
        }

        registerHooks() {
            if (typeof wp !== 'undefined' && wp.hooks) {
                wp.hooks.addAction('mddrFoldersLoaded', 'media-directory-pro', this.applySavedSort.bind(this));
                wp.hooks.addAction('mddrSortFolders', 'media-directory-pro', this.applySortFrom.bind(this));

                wp.hooks.addAction('mddrFolderChanged', 'media-directory-pro', (slug) => {
                    const savedFileSortBy = localStorage.getItem(this.getStorageKey('file_sort_by')) || 'default',
                        savedFileSortOrder = localStorage.getItem(this.getStorageKey('file_sort_order')) || 'desc';
                    this.applySortToFiles(savedFileSortBy, savedFileSortOrder);
                });

                wp.hooks.addFilter('mddrFoldersPayloadArgs', 'media-directory-pro', (args) => {
                    const savedCountMode = localStorage.getItem(this.getStorageKey('count_mode')) || 'folder_only';
                    args.folder_count_mode = savedCountMode;
                    return args;
                });

                // Pro version specific hooks
                wp.hooks.addAction('mddrFolderDownload', 'media-directory-pro', this.handleFolderDownload.bind(this));
                wp.hooks.addAction('mddrFolderDuplicate', 'media-directory-pro', this.handleFolderDuplicate.bind(this));
                wp.hooks.addAction('mddrFolderPin', 'media-directory-pro', this.handleFolderPin.bind(this));
                wp.hooks.addAction('mddrShowContextMenu', 'media-directory-pro', this.syncContextMenu.bind(this));
            }
        }

        syncContextMenu(menu, folderId) {
            const folder = window.mddr_media_folder.folder.findFolderById(folderId, this.admin.state.folders);
            if (!folder) return;

            const pinItem = menu.find('[data-action="pin-folder"]');
            if (pinItem.length) {
                const __this = !!folder.is_pinned,
                    text = __this ? pinItem.data('text-unpin') : pinItem.data('text-pin'),
                    icon = __this ? pinItem.data('icon-unpin') : pinItem.data('icon-pin');

                pinItem.find('span').last().text(text);
                pinItem.find('.dashicons').attr('class', 'dashicons ' + icon);
            }

            // Sync Color Picker
            const __this = $(`.mddr_folder_button[data-folder-id="${folderId}"]`),
                currentColor = __this.attr('data-color') || '',
                colorDropdown = menu.find('.mddr_color_picker_dropdown');
            if (colorDropdown.length) {
                this.updateColorUI(colorDropdown, currentColor);
            }
        }

        handleFolderPin(folderId) {
            if (!folderId) return;
            const folder = window.mddr_media_folder.folder.findFolderById(folderId, this.admin.state.folders);
            if (!folder) return;
            const action = folder.is_pinned ? 'unpin_folder' : 'pin_folder';
            this.admin.apiCall(action, {
                folder_id: folderId,
                post_type: this.admin.getPostType()
            }).then(data => {
                window.mddr_media_folder.folder.refreshState(data);
                this.admin.showToast(folder.is_pinned ? 'Unpinned from top' : 'Pinned to top');
            }).catch(alert);
        }


        toggleCollapseAll(e) {
            e.preventDefault();
            const __this = $(e.currentTarget);

            if (typeof this.admin.allCollapsed === 'undefined') {
                this.admin.allCollapsed = false;
            }

            this.admin.allCollapsed = !this.admin.allCollapsed;

            const text = this.admin.allCollapsed ? __this.data('text-show') : __this.data('text-hide'),
                icon = this.admin.allCollapsed ? __this.data('icon-show') : __this.data('icon-hide');

            __this.find('span').last().text(text);
            __this.find('.dashicons').attr('class', 'dashicons ' + icon);

            const allFolders = $('#mddr_media_sidebar .mddr_folder_node'),
                allIds = {};

            if (this.admin.allCollapsed) {
                // Collapse All
                allFolders.attr('aria-expanded', 'false').children('ul').slideUp(300);
                this.admin.setStorage('mddrExpandedFolders', JSON.stringify({}));
            } else {
                // Expand All
                allFolders.attr('aria-expanded', 'true').children('ul').slideDown(300);
                allFolders.each((i, el) => {
                    const __this = $(el).find('.mddr_folder_button').first(),
                        id = __this.data('folder-id');
                    if (id) allIds[id] = true;
                });
                this.admin.setStorage('mddrExpandedFolders', JSON.stringify(allIds));
            }

            localStorage.setItem(this.getStorageKey('is_all_collapsed'), this.admin.allCollapsed ? '1' : '0');
            $('.mddr_more_menu').prop('hidden', true);
        }

        restoreCollapse() {
            const savedState = localStorage.getItem(this.getStorageKey('is_all_collapsed')),
                collapsed = savedState === '1';

            if (this.admin) {
                this.admin.allCollapsed = collapsed;
            }

            const __this = $('.mddr_more_menu_item[data-action="collapse-all"]');
            if (__this.length) {
                const text = collapsed ? __this.data('text-show') : __this.data('text-hide'),
                    icon = collapsed ? __this.data('icon-show') : __this.data('icon-hide');

                __this.find('span').last().text(text);
                __this.find('.dashicons').attr('class', 'dashicons ' + icon);
            }
        }

        handleFolderDownload(folderId) {
            if (!folderId) return;
            if (this.admin) {
                this.admin.showToast(this.admin.getText('generatingZip'));
            }
            if (typeof this.admin.createForm === 'function') {
                const form = this.admin.createForm('download_folder_zip');
                form.append($('<input>', { type: 'hidden', name: 'folder_id', value: folderId }));
                form.appendTo('body').submit().remove();
            }
        }

        handleFolderDuplicate(folderId) {
            this.admin.apiCall('duplicate_folder', {
                folder_id: folderId,
                post_type: this.admin.getPostType()
            }).then(data => {
                if (window.mddr_media_folder && window.mddr_media_folder.folder) {
                    window.mddr_media_folder.folder.refreshState(data);
                }
                this.admin.showToast(this.admin.getText('duplicated'));
            }).catch(alert);
        }

        applySortFrom(sortValue) {
            if (!sortValue) return;

            let orderby = 'date';
            let wpOrder = 'DESC';

            if (sortValue !== 'default') {
                const [field, order] = sortValue.split('-');
                if (!field || !order) return;
                wpOrder = order.toUpperCase();
                if (field === 'name') orderby = 'name';
                if (field === 'title') orderby = 'title';
                if (field === 'date') orderby = 'date';
                if (field === 'modified') orderby = 'modified';
                if (field === 'author') orderby = 'author';
            }

            if (typeof wp !== 'undefined' && wp.media && wp.media.frame) {
                try {
                    const frame = wp.media.frame,
                        library = frame.state().get('library');
                    if (library) {
                        library.props.set({ orderby: orderby, order: wpOrder });
                        if (library.mirroring) {
                            library.mirroring.setProps(library.props.toJSON());
                        }
                        library.fetch();
                    }
                } catch (e) { }
            } else {
                const sortBy = sortValue.split('-')[0] || 'default',
                    sortOrder = sortValue.split('-')[1] || 'desc';
                this.applySortToFiles(sortBy, sortOrder);
            }
        }

        applyInitialActiveStates() {
            // Folders
            const folderSort = localStorage.getItem(this.getStorageKey('folder_sort')) || 'default';
            this.updateSelectedBySort('folder', folderSort);

            // Files
            const savedFileSortBy = localStorage.getItem(this.getStorageKey('file_sort_by')) || 'default',
                savedFileSortOrder = localStorage.getItem(this.getStorageKey('file_sort_order')) || 'desc';
            this.updateSelectedBySort('file', savedFileSortBy, savedFileSortOrder);

            // Sync the Native Select Box #mddr_default_sort
            const defaultSortSelect = $('#mddr_default_sort');
            if (defaultSortSelect.length) {
                let selectVal = 'default';
                if (savedFileSortBy !== 'default') {
                    selectVal = `${savedFileSortBy}-${savedFileSortOrder.toLowerCase()}`;
                }
                defaultSortSelect.val(selectVal);
            }

            // Count Mode
            const savedCountMode = localStorage.getItem(this.getStorageKey('count_mode')) || 'folder_only',
                countItem = $(`.mddr_count_mode_item[data-mode="${savedCountMode}"]`);
            if (countItem.length) {
                this.updateCountModeUI(countItem);
            }

            // Theme
            const settings = JSON.parse(localStorage.getItem('mddrSettings') || '{}');
            this.applyTheme(settings.theme || (window.mddr_media_library_pro && window.mddr_media_library_pro.theme) || 'default');
            this.restoreCollapse();
            this.applySortToFolders(folderSort);

            if (typeof wp !== 'undefined' && wp.media && wp.media.frame) {
                this.applySortToFiles(savedFileSortBy, savedFileSortOrder);
            } else {
                const urlParams = new URLSearchParams(window.location.search);
                if (!urlParams.get('orderby') && savedFileSortBy !== 'default') {
                    this.applySortToFiles(savedFileSortBy, savedFileSortOrder);
                }
            }
        }

        handleSortFolders(e) {
            e.preventDefault();
            const __this = $(e.currentTarget),
                sortType = __this.data('sort');

            localStorage.setItem(this.getStorageKey('folder_sort'), sortType);
            this.updateSelected(__this);
            this.applySortToFolders(sortType);
            __this.closest('.mddr_sort_menu').prop('hidden', true);
        }

        handleSortFiles(e) {
            e.preventDefault();
            const __this = $(e.currentTarget),
                sortBy = __this.data('sort-by'),
                sortOrder = __this.data('sort-order');

            localStorage.setItem(this.getStorageKey('file_sort_by'), sortBy);
            localStorage.setItem(this.getStorageKey('file_sort_order'), sortOrder);

            this.updateSelected(__this);
            this.applySortToFiles(sortBy, sortOrder);
            __this.closest('.mddr_sort_menu').prop('hidden', true);

            // Sync the select box if present
            const selectVal = sortBy === 'default' ? 'default' : `${sortBy}-${sortOrder}`;
            $('#mddr_default_sort').val(selectVal);
        }

        handleDefaultSortChange(e) {
            const val = $(e.currentTarget).val();
            let sortBy = 'default';
            let sortOrder = 'desc';

            if (val !== 'default') {
                const parts = val.split('-');
                if (parts.length === 2) {
                    sortBy = parts[0];
                    sortOrder = parts[1];
                }
            }

            localStorage.setItem(this.getStorageKey('file_sort_by'), sortBy);
            localStorage.setItem(this.getStorageKey('file_sort_order'), sortOrder);

            this.updateSelectedBySort('file', sortBy, sortOrder);
        }

        handleCountMode(e) {
            e.preventDefault();
            const __this = $(e.currentTarget),
                mode = __this.data('mode') || 'folder_only';

            localStorage.setItem(this.getStorageKey('count_mode'), mode);
            this.updateCountModeUI(__this);

            if (this.admin) {
                this.admin.apiCall('save_settings', { folder_count_mode: mode });
                this.admin.apiCall('get_folders', { folder_count_mode: mode })
                    .then(data => {
                        if (window.mddr_media_folder && window.mddr_media_folder.folder) {
                            window.mddr_media_folder.folder.refreshState(data);
                        }
                        this.admin.triggerMediaFilter(this.admin.state.activeFolder);
                    })
                    .catch(console.error);
            }

            __this.closest('.mddr_sort_menu').prop('hidden', true);
        }

        handleThemeToggle(e) {
            e.preventDefault();
            const __this = $(e.currentTarget),
                theme = __this.data('theme') || 'default';

            $('.mddr_theme_btn').removeClass('mddr_theme_btn--active');
            __this.addClass('mddr_theme_btn--active');

            const settings = JSON.parse(localStorage.getItem('mddrSettings') || '{}');
            settings.theme = theme;
            localStorage.setItem('mddrSettings', JSON.stringify(settings));

            if (this.admin) {
                this.admin.apiCall('save_settings', { theme_design: theme });
            }
            this.applyTheme(theme);
        }

        handleThemeDesign(e) {
            const val = $(e.currentTarget).val(),
                theme = val.split(' ')[0].toLowerCase();

            const settings = JSON.parse(localStorage.getItem('mddrSettings') || '{}');
            settings.theme = theme;
            localStorage.setItem('mddrSettings', JSON.stringify(settings));

            if (this.admin) {
                this.admin.apiCall('save_settings', { theme_design: val });
            }
            this.applyTheme(theme);
        }

        applyTheme(theme) {
            const sidebar = $('#mddr_media_sidebar');
            if (sidebar.length) {
                sidebar.removeClass('mddr_theme_windows mddr_theme_dropbox');
                if (theme !== 'default') {
                    sidebar.addClass('mddr_theme_' + theme);
                }
            }
            $('.mddr_theme_btn').removeClass('mddr_theme_btn--active');
            $(`.mddr_theme_btn[data-theme="${theme}"]`).addClass('mddr_theme_btn--active');
        }

        updateCountModeUI(__this) {
            $('.mddr_count_mode_item').removeClass('is-active').find('.mddr_check_icon').hide();
            __this.addClass('is-active').find('.mddr_check_icon').show();
        }

        applySortToFolders(sortType) {
            const folders = (this.admin && this.admin.state) ? this.admin.state.folders : [];
            if (!folders || !folders.length) return;

            let sortedFolders;
            switch (sortType) {
                case 'asc':
                    sortedFolders = this.sortFoldersRecursive(folders, 'asc');
                    this.admin.state.folders = sortedFolders;
                    this.admin.renderSidebar();
                    break;
                case 'desc':
                    sortedFolders = this.sortFoldersRecursive(folders, 'desc');
                    this.admin.state.folders = sortedFolders;
                    this.admin.renderSidebar();
                    break;
                case 'default':
                    this.admin.fetchFolders();
                    break;
                default:
                    return;
            }
        }

        applySortToFiles(sortBy, sortOrder) {
            const gridView = typeof wp !== 'undefined' && wp.media && wp.media.frame,
                uploadList = window.location.pathname.includes('upload.php') && !window.location.search.includes('mode=grid'),
                editList = window.location.pathname.includes('edit.php');

            if (gridView) {
                try {
                    const frame = wp.media.frame,
                        state = typeof frame.state === 'function' ? frame.state() : frame.state;

                    if (state) {
                        const library = state.get('library');
                        if (library) {
                            const orderbyField = sortBy === 'default' ? 'date' : this.getOrderByField(sortBy),
                                orderDir = sortBy === 'default' ? 'DESC' : sortOrder.toUpperCase();

                            library.props.set({ orderby: orderbyField, order: orderDir });
                            if (library.mirroring) {
                                library.mirroring.setProps(library.props.toJSON());
                            }
                            library.fetch();
                        }
                    }
                } catch (err) { }
            } else if (uploadList || editList) {
                const url = new URL(window.location.href),
                    orderbyField = this.getOrderByField(sortBy),
                    orderDir = sortOrder.toUpperCase();

                if (url.searchParams.get('orderby') !== orderbyField || url.searchParams.get('order') !== orderDir) {
                    url.searchParams.set('orderby', orderbyField);
                    url.searchParams.set('order', orderDir);
                    window.location.href = url.toString();
                }
            }
        }

        getOrderByField(sortBy) {
            switch (sortBy) {
                case 'title': return 'title';
                case 'date': return 'date';
                case 'modified': return 'modified';
                case 'author': return 'author';
                default: return 'date';
            }
        }

        applySavedSort() {
            const folderSort = localStorage.getItem(this.getStorageKey('folder_sort')) || 'default';
            this.updateSelectedBySort('folder', folderSort);
            if (folderSort !== 'default') {
                this.applySortToFolders(folderSort);
            }

            const savedFileSortBy = localStorage.getItem(this.getStorageKey('file_sort_by')) || 'default',
                savedFileSortOrder = localStorage.getItem(this.getStorageKey('file_sort_order')) || 'desc';
            this.updateSelectedBySort('file', savedFileSortBy, savedFileSortOrder);

            if (typeof wp !== 'undefined' && wp.media && wp.media.frame) {
                this.applySortToFiles(savedFileSortBy, savedFileSortOrder);
            }
        }

        updateSelectedBySort(type, sortVal, orderVal = null) {
            if (type === 'folder') {
                const __this = $(`.mddr_sort_folder_option[data-sort="${sortVal}"]`);
                if (__this.length) this.updateSelected(__this);
            } else {
                const __this = $(`.mddr_sort_files_option[data-sort-by="${sortVal}"][data-sort-order="${orderVal || 'desc'}"]`);
                if (__this.length) this.updateSelected(__this);
            }
        }

        updateSelected(__this) {
            const isFolder = __this.hasClass('mddr_sort_folder_option'),
                categorySelector = isFolder ? '.mddr_sort_folder_option' : '.mddr_sort_files_option';

            $(categorySelector).removeClass('is-active').find('.mddr_check_icon').hide();

            if (!isFolder) {
                $('.mddr_sort_menu_subitem').removeClass('is-active');
                const $parentItem = __this.closest('.mddr_sort_menu_subitem.has-nested');
                if ($parentItem.length) {
                    $parentItem.addClass('is-active');
                }
            }
            __this.addClass('is-active').find('.mddr_check_icon').show();
        }

        sortFoldersRecursive(folders, order) {
            const sorted = [...folders].sort((a, b) => {
                if (a.is_pinned !== b.is_pinned) {
                    return b.is_pinned ? 1 : -1;
                }
                const nameA = a.name.toLowerCase(),
                    nameB = b.name.toLowerCase(),
                    comparison = nameA.localeCompare(nameB);
                return order === 'asc' ? comparison : -comparison;
            });
            return sorted.map(folder => ({
                ...folder,
                children: folder.children ? this.sortFoldersRecursive(folder.children, order) : []
            }));
        }

        handlePresetColor(e) {
            e.preventDefault();
            const __this = $(e.currentTarget);
            let color = __this.data('color') || __this.attr('data-color');
            const container = __this.closest('.mddr_color_picker_dropdown');
            if (color && !color.startsWith('#')) color = '#' + color;
            this.updateColorUI(container, color);
            this.saveColor(container, color);
        }

        handleResetColor(e) {
            e.preventDefault();
            const __this = $(e.currentTarget).closest('.mddr_color_picker_dropdown');
            this.updateColorUI(__this, '');
            this.saveColor(__this, '');
        }

        handlePreviewClick(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        syncColorPicker(e) {
            const __this = $(e.currentTarget).find('.mddr_color_picker_dropdown'),
                menu = $('.mddr_folder_context_menu'),
                folderId = menu.attr('data-folder-id'),
                btn = $(`.mddr_folder_button[data-folder-id="${folderId}"]`),
                currentColor = btn.attr('data-color') || '';
            this.updateColorUI(__this, currentColor);
        }

        updateColorUI(__this, color) {
            const preview = __this.find('.mddr_current_color_preview'),
                themeColor = this.getThemeColor();
            preview.css('background-color', color || themeColor);
            if (!color) preview.find('.dashicons').hide();
            else preview.find('.dashicons').show();
        }

        getThemeColor() {
            const settings = JSON.parse(localStorage.getItem('mddrSettings') || '{}'),
                theme = settings.theme || (window.mddr_media_library_pro && window.mddr_media_library_pro.theme) || 'default';
            switch (theme) {
                case 'dropbox': return '#0061ff';
                case 'windows': return '#fcd133';
                default: return '#8f8f8f';
            }
        }

        saveColor(__this, color) {
            const menu = __this.closest('.mddr_folder_context_menu'),
                folderId = menu.attr('data-folder-id');
            if (!this.admin) return;
            this.admin.apiCall('save_folder_color', { folder_id: folderId, color: color }).then(data => {
                if (window.mddr_media_folder && window.mddr_media_folder.folder) {
                    const folderObj = window.mddr_media_folder.folder,
                        btn = $(`.mddr_folder_button[data-folder-id="${folderId}"]`);
                    btn.attr('data-color', color);
                    const $icon = btn.find('.mddr_folder_icon');
                    if ($icon.length) folderObj.applyIconColor($icon, color);
                    folderObj.refreshState(data);
                }
                this.admin.showToast(this.admin.getText('colorUpdated'));
                $('.mddr_folder_context_menu').prop('hidden', true).removeClass('is-visible');
            });
        }
    }

    new MDDR_Admin_Pro();
});
