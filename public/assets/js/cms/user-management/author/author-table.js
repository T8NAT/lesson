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

var KTAuthorsList = (function () {
    var dataTable;
    var authorsTable = document.getElementById("kt_table_authors");
    var searchInput;
    var toolbar;
    var selectedCount;
    var addAuthorButton;

    const initDataTable = async () =>{
        const languageData = await loadDataTableLanguage();
        dataTable = $(authorsTable).DataTable({
            processing: true,
            serverSide: true,
            language: languageData,
            ajax: {
                url: '/dashboard/authors/',
                type: "GET",
            },
            columns: [
                { data: "checkbox", orderable: false, searchable: false },
                { data: 'partials', name: 'partials' },
                { data: 'role', name: 'name' },
                { data: 'last_login', name: 'last_login' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
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
        searchInput = $('[data-kt-teacher-table-filter="search"]');
        searchInput.on('keyup', function () {
            dataTable.search($(this).val()).draw();
        });
    };
    // -------------------------------------------------------------------------
    // Toggle Toolbars
    // -------------------------------------------------------------------------
    const initToggleToolbar = () => {
        toolbar = document.querySelector('[data-kt-teacher-table-toolbar="selected"]');
        selectedCount = document.querySelector('[data-kt-teacher-table-select="selected_count"]');
        addAuthorButton = document.querySelector('[data-bs-target="#kt_modal_add_author"]').closest('.btn');
        const checkboxes = authorsTable.querySelectorAll('tbody [type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                toggleToolbars();
            });
        });

    }
    const toggleToolbars = () => {
        const checkboxes = authorsTable.querySelectorAll('tbody [type="checkbox"]:checked');
        if (checkboxes.length > 0) {
            toolbar.classList.remove('d-none');
            selectedCount.innerHTML = checkboxes.length;
            addAuthorButton.classList.add('d-none');
        } else {
            toolbar.classList.add('d-none');
            addAuthorButton.classList.remove('d-none');
            toolbar.classList.add('d-none');

        }
    }


    // -------------------------------------------------------------------------
    // Delete User Functionality
    // -------------------------------------------------------------------------
    const handleDeleteAuthor = () => {
        $(authorsTable).on("click", '[data-kt-authors-table-filter="delete_row"]', function (event) {
            event.preventDefault();

            const parentDiv = $(this).closest('[data-teacher-id]');
            const authorId = parentDiv.data('teacher-id');
            const authorName = parentDiv.data('teacher-name');
            Swal.fire({
                text: `${messages.Deleted}` + ' ' + authorName+ "?",
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
                        text: messages.Deleting + authorName + "...",
                        icon: "info",
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                    $.ajax({
                        url: `authors/${authorId}`,
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
                        text:  authorName + messages.NotDeleted,
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
    // Delete Selected Authors Functionality
    // -------------------------------------------------------------------------
    const handleDeleteSelectedAuthors = () => {
        $(document).on('click', '[data-kt-teacher-table-select="delete_selected"]', function () {
            const selectedIds = Array.from(authorsTable.querySelectorAll('tbody [type="checkbox"]:checked'))
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
                            url: "delete-selected",
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
                    text: messages.NoUsersSelected,
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
            if (authorsTable) {
                await initDataTable();
                handleDeleteAuthor();
                setupSearch();
                handleDeleteSelectedAuthors();
            }
        }
    };
})();
// -------------------------------------------------------------------------
// Initialize App
// -------------------------------------------------------------------------

$('#kt_table_authors').on('draw.dt', function () {
    KTMenu.createInstances();
});
loadMessagesWithCache(currentLanguage).then(() => {
    KTUtil.onDOMContentLoaded(function () {
        KTAuthorsList.init();
    });
});
