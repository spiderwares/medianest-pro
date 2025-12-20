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
            }
        }

        bindEvents() {
            $(document.body).on('click', '.wpmn_sort_folder_option', this.handleSortFolders.bind(this));
        }

        registerHooks() {
            if (typeof wp !== 'undefined' && wp.hooks) {
                wp.hooks.addAction('wpmnFoldersLoaded', 'medianest-pro', this.applySavedSort.bind(this));
            }
        }

        handleSortFolders(e) {
            e.preventDefault();
            const $li = $(e.currentTarget);
            const sortType = $li.data('sort');

            localStorage.setItem('wpmn_folder_sort', sortType);
            this.updateSelected($li);
            this.applySortToFolders(sortType);
            $li.closest('.wpmn_sort_menu').prop('hidden', true);
        }

        applySortToFolders(sortType) {
            const folders = this.admin.state.folders;

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

        applySavedSort() {
            const savedSort = localStorage.getItem('wpmn_folder_sort');

            if (!savedSort || savedSort === 'default') {
                this.updateSelectedBySort('default');
                return;
            }

            this.applySortToFolders(savedSort);
            this.updateSelectedBySort(savedSort);
        }

        updateSelectedBySort(sortType) {
            const $li = $(`.wpmn_sort_folder_option[data-sort="${sortType}"]`);
            if ($li.length) {
                this.updateSelected($li);
            }
        }

        updateSelected($li) {
            // remove active class from all
            $('.wpmn_sort_folder_option')
                .removeClass('is-active')
                .find('.wpmn_check_icon')
                .hide();

            // add active class to current
            $li
                .addClass('is-active')
                .find('.wpmn_check_icon')
                .show();
        }


        sortFoldersRecursive(folders, order) {
            const sorted = [...folders].sort((a, b) => {
                const comparison = a.name.localeCompare(b.name);
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
