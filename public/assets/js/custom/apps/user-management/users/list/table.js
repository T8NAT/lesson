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

var KTUsersList = (function () {
    var dataTable;
    var usersTable = document.getElementById("kt_table_users");
    var searchInput;
    var toolbar;
    var selectedCount;
    var addUserButton;

    const initDataTable = async () =>{
        const languageData = await loadDataTableLanguage();
        dataTable = $(usersTable).DataTable({
            processing: true,
            serverSide: true,
            language: languageData,
            ajax: {
                url: '/dashboard/users/',
                type: "GET",
            },
            columns: [
                { data: "checkbox", orderable: false, searchable: false },
                { data: 'partials', name: 'partials' },
                { data: 'role', name: 'role.name' },
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
        searchInput = $('[data-kt-user-table-filter="search"]');
        searchInput.on('keyup', function () {
            dataTable.search($(this).val()).draw();
        });
    };
    // -------------------------------------------------------------------------
    // Toggle Toolbars
    // -------------------------------------------------------------------------
    const initToggleToolbar = () => {
        toolbar = document.querySelector('[data-kt-user-table-toolbar="selected"]');
        selectedCount = document.querySelector('[data-kt-user-table-select="selected_count"]');
        addUserButton = document.querySelector('[data-bs-target="#kt_modal_add_user"]').closest('.btn');
        const checkboxes = usersTable.querySelectorAll('tbody [type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                toggleToolbars();
            });
        });

    }
    const toggleToolbars = () => {
        const checkboxes = usersTable.querySelectorAll('tbody [type="checkbox"]:checked');
        if (checkboxes.length > 0) {
            toolbar.classList.remove('d-none');
            selectedCount.innerHTML = checkboxes.length;
            addUserButton.classList.add('d-none');
        } else {
            toolbar.classList.add('d-none');
            addUserButton.classList.remove('d-none');
            toolbar.classList.add('d-none');

        }
    }


    // -------------------------------------------------------------------------
    // Delete User Functionality
    // -------------------------------------------------------------------------
    const handleDeleteUser = () => {
        $(usersTable).on("click", '[data-kt-users-table-filter="delete_row"]', function (event) {
            event.preventDefault();

            const parentDiv = $(this).closest('[data-user-id]');
            const userId = parentDiv.data('user-id');
            const userName = parentDiv.data('user-name');
            Swal.fire({
                text: `${messages.Deleted}` + ' ' + userName+ "?",
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
                        text: messages.Deleting + userName + "...",
                        icon: "info",
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                    $.ajax({
                        url: `users/${userId}`,
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
                        text:  userName + messages.NotDeleted,
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
    // Delete Selected Users Functionality
    // -------------------------------------------------------------------------
    const handleDeleteSelectedUsers = () => {
        $(document).on('click', '[data-kt-user-table-select="delete_selected"]', function () {
            const selectedIds = Array.from(usersTable.querySelectorAll('tbody [type="checkbox"]:checked'))
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
            if (usersTable) {
                await initDataTable();
                handleDeleteUser();
                setupSearch();
                handleDeleteSelectedUsers();
            }
        }
    };
})();


// -------------------------------------------------------------------------
// Initialize App
// -------------------------------------------------------------------------

$('#kt_table_users').on('draw.dt', function () {
    KTMenu.createInstances();
});
loadMessagesWithCache(currentLanguage).then(() => {
    KTUtil.onDOMContentLoaded(function () {
        KTUsersList.init();
    });
});
