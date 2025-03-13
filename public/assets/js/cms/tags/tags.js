"use strict";

// -------------------------------------------------------------------------
// Configuration
// -------------------------------------------------------------------------

const currentLanguage = document.documentElement.lang || "ar";
const languageCacheKey = 'dataTableLanguage';
const dataTableLanguageUrl = '/assets/js/custom/apps/ecommerce/language/ar.json';

let messages = {};

// -------------------------------------------------------------------------
// Utility Functions
// -------------------------------------------------------------------------

function loadMessagesWithCache(lang) {
    const cachedMessages = localStorage.getItem(`messages_${lang}`);
    if (cachedMessages) {
        messages = JSON.parse(cachedMessages);
        return Promise.resolve();
    }
    return $.getJSON(`/assets/js/custom/apps/ecommerce/language/languages.json`, function (data) {
        messages = data[lang] || data["en"];
        localStorage.setItem(`messages_${lang}`, JSON.stringify(messages));
    });
}

async function loadDataTableLanguage() {
    const cachedLanguage = localStorage.getItem(languageCacheKey);
    if (cachedLanguage) {
        return JSON.parse(cachedLanguage);
    }
    try {
        const response = await fetch(dataTableLanguageUrl);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const languageData = await response.json();
        localStorage.setItem(languageCacheKey, JSON.stringify(languageData));
        return languageData;
    } catch (error) {
        console.error("Failed to load data table language:", error);
        return {}; // Handle the error by returning an empty object
    }
}

// -------------------------------------------------------------------------
// Setup AJAX Headers
// -------------------------------------------------------------------------
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// -------------------------------------------------------------------------
// Initialize Data Table
// -------------------------------------------------------------------------

var KTTagsList = (function () {
    var dataTable;
    var tagsTable = document.getElementById("kt_cms_tag_table");
    var searchInput;
    var toolbar;
    var selectedCount;
    // var addTagButton;

    const initDataTable = async () =>{
        const languageData = await loadDataTableLanguage();
        dataTable = $(tagsTable).DataTable({
            processing: true,
            serverSide: true,
            language: languageData,
            ajax: {
                url: '/dashboard/tags/',
                type: "GET",
            },
            columns: [
                { data: "checkbox", orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'posts_count', name: 'posts.count',searchable: false},
                { data: 'actions', name: 'actions', orderable: false, searchable: false },
            ],
            order: [[1, "asc"]],
            'drawCallback': function( settings ) {
                initToggleToolbar();
                toggleToolbars();
            }
        });
    }
    // -------------------------------------------------------------------------
    // Setup search functionality
    // -------------------------------------------------------------------------
    const setupSearch = () => {
        searchInput = $('[data-kt-tag-table-filter="search"]');
        searchInput.on('keyup', function () {
            dataTable.search($(this).val()).draw();
        });
    };
    // -------------------------------------------------------------------------
    // Toggle Toolbars
    // -------------------------------------------------------------------------
    const initToggleToolbar = () => {
        toolbar = document.querySelector('[data-kt-tag-table-toolbar="selected"]');
        selectedCount = document.querySelector('[data-kt-tag-table-select="selected_count"]');
        const checkboxes = tagsTable.querySelectorAll('tbody [type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                toggleToolbars();
            });
        });

    }
    const toggleToolbars = () => {
        const checkboxes = tagsTable.querySelectorAll('tbody [type="checkbox"]:checked');
        if (checkboxes.length > 0) {
            toolbar.classList.remove('d-none');
            selectedCount.innerHTML = checkboxes.length;
        } else {
            toolbar.classList.add('d-none');
            toolbar.classList.add('d-none');

        }
    }


    // -------------------------------------------------------------------------
    // Delete Tag Functionality
    // -------------------------------------------------------------------------
    const handleDeleteTag = () => {
        $(tagsTable).on("click", '[data-kt-cms-tag-filter="delete_row"]', function (event) {
            event.preventDefault();

            const parentDiv = $(this).closest('[data-tag-id]');
            const tagId = parentDiv.data('tag-id');
            const tagName = parentDiv.data('tag-name');
            Swal.fire({
                text: `${messages.Deleted}` + ' ' + tagName+ "?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: messages.YesButtonText,
                cancelButtonText: messages.NoButtonText,
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function (result) {
                if (result.isConfirmed) {
                    Swal.fire({
                        text: messages.Deleting + tagName + "...",
                        icon: "info",
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                    $.ajax({
                        url: `tags/${tagId}`,
                        method: "DELETE",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function (response) {
                            Swal.fire({
                                text: response.text,
                                icon: response.icon,
                                buttonsStyling: false,
                                confirmButtonText: messages.confirmButtonText,
                                customClass: { confirmButton: "btn fw-bold btn-primary" }
                            }).then(function () {
                                dataTable.ajax.reload();
                            });
                        },
                        error: function () {
                            Swal.fire({
                                text: messages.genericError,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: messages.confirmButtonText,
                                customClass: { confirmButton: "btn fw-bold btn-primary" }
                            });
                        }
                    });
                }
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        text:  tagName + messages.NotDeleted,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText:  messages.confirmButtonText,
                        customClass: { confirmButton: "btn fw-bold btn-primary" }
                    });
                }
            });
        });
    }
    // -------------------------------------------------------------------------
    // Delete Selected Tags Functionality
    // -------------------------------------------------------------------------
    const handleDeleteSelectedTags = () => {
        $(document).on('click', '[data-kt-tag-table-select="delete_selected"]', function () {
            const selectedIds = Array.from(tagsTable.querySelectorAll('tbody [type="checkbox"]:checked'))
                .map(checkbox => checkbox.getAttribute('data-id'));
            if (selectedIds.length > 0) {
                Swal.fire({
                    text: `${messages.Deleted}` + '?',
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: messages.YesButtonText,
                    cancelButtonText: messages.NoButtonText,
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        Swal.fire({
                            text: messages.Deleting,
                            icon: "info",
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                        $.ajax({
                            url: "delete-tags-selected",
                            method: 'DELETE',
                            data: {
                                ids: selectedIds,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                Swal.fire({
                                    text: response.text,
                                    icon: response.icon,
                                    buttonsStyling: false,
                                    confirmButtonText: messages.confirmButtonText,
                                    customClass: { confirmButton: "btn fw-bold btn-primary" }
                                }).then(function () {
                                    dataTable.ajax.reload();
                                });

                            },
                            error: function () {
                                Swal.fire({
                                    text: messages.genericError,
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: messages.confirmButtonText,
                                    customClass: { confirmButton: "btn fw-bold btn-primary" }
                                });
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire({
                            text:  messages.NotDeleted,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: messages.confirmButtonText,
                            customClass: { confirmButton: "btn fw-bold btn-primary" }
                        });
                    }
                });
            }else{
                Swal.fire({
                    text: messages.NoTagsSelected,
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: messages.confirmButtonText,
                    customClass: { confirmButton: "btn fw-bold btn-primary" }
                });
            }
        });
    }
    // -------------------------------------------------------------------------
    // Initialize Function
    // -------------------------------------------------------------------------
    return {
        init: async function () {
            if (tagsTable) {
                await initDataTable();
                handleDeleteTag();
                setupSearch();
                handleDeleteSelectedTags();
            }
        }
    };
})();


// -------------------------------------------------------------------------
// Initialize App
// -------------------------------------------------------------------------

$('#kt_cms_tag_table').on('draw.dt', function () {
    KTMenu.createInstances();
});
loadMessagesWithCache(currentLanguage).then(() => {
    KTUtil.onDOMContentLoaded(function () {
        KTTagsList.init();
    });
});
