'use strict';

jQuery(function ($) {

    class WPMN_Admin_Pro {

        constructor() {
            this.init();
        }

        init() {
            if (window.wpmn_admin_media && window.wpmn_admin_media.admin) {
                this.admin = window.wpmn_admin_media.admin;
                this.bindEvents();
                this.registerHooks();
                this.applyInitialActiveStates();
            }
            // else {
            //     // Wait for core admin to be ready
            //     if (typeof wp !== 'undefined' && wp.hooks) {
            //         wp.hooks.addAction('wpmnAdminInitialized', 'medianest_pro', (admin) => {
            //             this.admin = admin;
            //             this.bindEvents();
            //             this.registerHooks();
            //             this.applyInitialActiveStates();
            //         });
            //     }
            // }
        }

        bindEvents() {
            $(document.body).on('click', '.wpmn_sort_folder_option', this.handleSortFolders.bind(this));
            $(document.body).on('click', '.wpmn_sort_files_option', this.handleSortFiles.bind(this));
            $(document.body).on('click', '.wpmn_count_mode_item', this.handleCountMode.bind(this));
            $(document.body).on('click', '.wpmn_theme_btn', this.handleThemeToggle.bind(this));
            $(document.body).on('change', 'input[name="wpmn_settings[theme_design]"]', this.handleThemeDesign.bind(this));
        }

        registerHooks() {
            if (typeof wp !== 'undefined' && wp.hooks) {
                wp.hooks.addAction('wpmnFoldersLoaded', 'medianest_pro', this.applySavedSort.bind(this));
                wp.hooks.addAction('wpmnSortFolders', 'medianest_pro', this.applySortFrom.bind(this));

                wp.hooks.addAction('wpmnFolderChanged', 'medianest_pro', (slug) => {
                    const savedFileSortBy = localStorage.getItem('wpmn_file_sort_by') || 'default';
                    const savedFileSortOrder = localStorage.getItem('wpmn_file_sort_order') || 'desc';
                    this.applySortToFiles(savedFileSortBy, savedFileSortOrder);
                });

                wp.hooks.addFilter('wpmnFoldersPayloadArgs', 'medianest_pro', (args) => {
                    const savedCountMode = localStorage.getItem('wpmn_count_mode') || 'folder_only';
                    args.folder_count_mode = savedCountMode;
                    return args;
                });

                wp.hooks.addAction('wpmnFolderDownload', 'medianest_pro', this.handleFolderDownload.bind(this));
            }
        }

        handleFolderDownload(folderId) {
            if (!folderId) return;

            // Show toast message
            if (this.admin) {
                this.admin.showToast(this.admin.getText('generatingZip'));
            }

            const form = this.admin.createForm('download_folder_zip');
            form.append($('<input>', { type: 'hidden', name: 'folder_id', value: folderId }));
            form.appendTo('body').submit().remove();
        }

        applySortFrom(sortValue) {
            if (!sortValue) return;

            let orderby = 'date';
            let wpOrder = 'DESC';

            if (sortValue !== 'default') {
                const [field, order] = sortValue.split('-');
                if (!field || !order) return;

                wpOrder = order.toUpperCase();

                if (field === 'title') orderby = 'title';
                if (field === 'date') orderby = 'date';
                if (field === 'modified') orderby = 'modified';
                if (field === 'author') orderby = 'author';
            }

            if (typeof wp !== 'undefined' && wp.media && wp.media.frame) {
                try {
                    const frame = wp.media.frame;
                    const library = frame.state().get('library');
                    if (library) {
                        library.props.set({
                            orderby: orderby,
                            order: wpOrder
                        });

                        if (library.mirroring) {
                            library.mirroring.setProps(library.props.toJSON());
                        }
                        library.fetch();
                    }
                } catch (e) {
                }
            }
        }

        applyInitialActiveStates() {
            // Folders
            const savedFolderSort = localStorage.getItem('wpmn_folder_sort') || 'default';
            this.updateSelectedBySort('folder', savedFolderSort);

            // Files
            const savedFileSortBy = localStorage.getItem('wpmn_file_sort_by') || 'default';
            const savedFileSortOrder = localStorage.getItem('wpmn_file_sort_order') || 'desc';
            this.updateSelectedBySort('file', savedFileSortBy, savedFileSortOrder);

            // Count Mode
            const savedCountMode = localStorage.getItem('wpmn_count_mode') || 'folder_only';
            const $countItem = $(`.wpmn_count_mode_item[data-mode="${savedCountMode}"]`);
            if ($countItem.length) {
                this.updateCountModeUI($countItem);
            }

            // Theme
            const settings = JSON.parse(localStorage.getItem('wpmnSettings') || '{}');
            this.applyTheme(settings.theme || (window.wpmn_media_library_pro && window.wpmn_media_library_pro.theme) || 'default');

            // Apply logic
            this.applySortToFolders(savedFolderSort);
            this.applySortToFiles(savedFileSortBy, savedFileSortOrder);
        }

        handleSortFolders(e) {
            e.preventDefault();
            const __this = $(e.currentTarget);
            const sortType = __this.data('sort');

            localStorage.setItem('wpmn_folder_sort', sortType);
            this.updateSelected(__this);
            this.applySortToFolders(sortType);
            __this.closest('.wpmn_sort_menu').prop('hidden', true);
        }

        handleSortFiles(e) {
            e.preventDefault();
            const __this = $(e.currentTarget);
            const sortBy = __this.data('sort-by');
            const sortOrder = __this.data('sort-order');

            localStorage.setItem('wpmn_file_sort_by', sortBy);
            localStorage.setItem('wpmn_file_sort_order', sortOrder);

            this.updateSelected(__this);
            this.applySortToFiles(sortBy, sortOrder);
            __this.closest('.wpmn_sort_menu').prop('hidden', true);
        }

        handleCountMode(e) {
            e.preventDefault();
            const __this = $(e.currentTarget);
            const mode = __this.data('mode') || 'folder_only';

            localStorage.setItem('wpmn_count_mode', mode);
            this.updateCountModeUI(__this);

            if (this.admin) {
                this.admin.apiCall('save_settings', { folder_count_mode: mode });
                this.admin.apiCall('get_folders', { folder_count_mode: mode })
                    .then(data => {
                        if (window.wpmn_media_folder && window.wpmn_media_folder.folder) {
                            window.wpmn_media_folder.folder.refreshState(data);
                        }
                        this.admin.triggerMediaFilter(this.admin.state.activeFolder);
                    })
                    .catch(console.error);
            }

            __this.closest('.wpmn_sort_menu').prop('hidden', true);
        }

        handleThemeToggle(e) {
            e.preventDefault();
            const __this = $(e.currentTarget);
            const theme = __this.data('theme') || 'default';

            $('.wpmn_theme_btn').removeClass('wpmn_theme_btn--active');
            __this.addClass('wpmn_theme_btn--active');

            const settings = JSON.parse(localStorage.getItem('wpmnSettings') || '{}');
            settings.theme = theme;
            localStorage.setItem('wpmnSettings', JSON.stringify(settings));

            if (this.admin) {
                this.admin.apiCall('save_settings', { theme_design: theme });
            }

            this.applyTheme(theme);
        }

        handleThemeDesign(e) {
            const val = $(e.currentTarget).val();
            const theme = val.split(' ')[0].toLowerCase();

            const settings = JSON.parse(localStorage.getItem('wpmnSettings') || '{}');
            settings.theme = theme;
            localStorage.setItem('wpmnSettings', JSON.stringify(settings));

            if (this.admin) {
                this.admin.apiCall('save_settings', { theme_design: val });
            }

            this.applyTheme(theme);
        }

        applyTheme(theme) {
            const sidebar = $('#wpmn_media_sidebar');
            if (sidebar.length) {
                sidebar.removeClass('wpmn_theme_windows wpmn_theme_dropbox');
                if (theme !== 'default') {
                    sidebar.addClass('wpmn_theme_' + theme);
                }
            }

            $('.wpmn_theme_btn').removeClass('wpmn_theme_btn--active');
            $(`.wpmn_theme_btn[data-theme="${theme}"]`).addClass('wpmn_theme_btn--active');
        }

        updateCountModeUI(__this) {
            $('.wpmn_count_mode_item').removeClass('is-active').find('.wpmn_check_icon').hide();
            __this.addClass('is-active').find('.wpmn_check_icon').show();
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
            if (typeof wp !== 'undefined' && wp.media && wp.media.frame) {
                try {
                    const frame = wp.media.frame;
                    const state = typeof frame.state === 'function' ? frame.state() : frame.state;

                    if (state) {
                        const library = state.get('library');
                        if (library) {
                            const orderbyField = sortBy === 'default' ? 'date' : this.getOrderByField(sortBy);
                            const orderDir = sortBy === 'default' ? 'DESC' : sortOrder.toUpperCase();

                            library.props.set({
                                orderby: orderbyField,
                                order: orderDir
                            });

                            if (library.mirroring) {
                                library.mirroring.setProps(library.props.toJSON());
                            }

                            library.fetch();
                        }
                    }
                } catch (err) {
                }
            }
        }

        getOrderByField(sortBy) {
            switch (sortBy) {
                case 'name': return 'title';
                case 'date': return 'date';
                case 'modified': return 'modified';
                case 'author': return 'author';
                case 'size': return 'wpmn_filesize';
                default: return 'date';
            }
        }

        applySavedSort() {
            // Folders
            const savedFolderSort = localStorage.getItem('wpmn_folder_sort') || 'default';
            this.updateSelectedBySort('folder', savedFolderSort);
            if (savedFolderSort !== 'default') {
                this.applySortToFolders(savedFolderSort);
            }

            // Files
            const savedFileSortBy = localStorage.getItem('wpmn_file_sort_by') || 'default';
            const savedFileSortOrder = localStorage.getItem('wpmn_file_sort_order') || 'desc';
            this.updateSelectedBySort('file', savedFileSortBy, savedFileSortOrder);
            this.applySortToFiles(savedFileSortBy, savedFileSortOrder);
        }

        updateSelectedBySort(type, sortVal, orderVal = null) {
            if (type === 'folder') {
                const __this = $(`.wpmn_sort_folder_option[data-sort="${sortVal}"]`);
                if (__this.length) this.updateSelected(__this);
            } else {
                const __this = $(`.wpmn_sort_files_option[data-sort-by="${sortVal}"][data-sort-order="${orderVal || 'desc'}"]`);
                if (__this.length) this.updateSelected(__this);
            }
        }

        updateSelected(__this) {
            const isFolder = __this.hasClass('wpmn_sort_folder_option'),
                categorySelector = isFolder ? '.wpmn_sort_folder_option' : '.wpmn_sort_files_option';

            $(categorySelector).removeClass('is-active').find('.wpmn_check_icon').hide();

            if (!isFolder) {
                $('.wpmn_sort_menu_subitem').removeClass('is-active');
                const $parentItem = __this.closest('.wpmn_sort_menu_subitem.has-nested');
                if ($parentItem.length) {
                    $parentItem.addClass('is-active');
                }
            }

            __this.addClass('is-active').find('.wpmn_check_icon').show();
        }

        sortFoldersRecursive(folders, order) {
            const sorted = [...folders].sort((a, b) => {
                const nameA = a.name.toLowerCase();
                const nameB = b.name.toLowerCase();
                const comparison = nameA.localeCompare(nameB);
                return order === 'asc' ? comparison : -comparison;
            });

            return sorted.map(folder => ({
                ...folder,
                children: folder.children ? this.sortFoldersRecursive(folder.children, order) : []
            }));
        }
    }

    new WPMN_Admin_Pro();
});
