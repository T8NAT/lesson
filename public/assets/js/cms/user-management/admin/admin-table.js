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

var KTAdminsList = (function () {
    var dataTable;
    var adminsTable = document.getElementById("kt_table_admins");
    var searchInput;

    const initDataTable = async () =>{
        const languageData = await loadDataTableLanguage();
        dataTable = $(adminsTable).DataTable({
            processing: true,
            serverSide: true,
            language: languageData,
            ajax: {
                url: '/dashboard/admins/',
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
            order: [[1, "asc"]]
        });
    }
    // -------------------------------------------------------------------------
    // Setup search functionality
    // -------------------------------------------------------------------------
    const setupSearch = () => {
        searchInput = $('[data-kt-admin-table-filter="search"]');
        searchInput.on('keyup', function () {
            dataTable.search($(this).val()).draw();
        });
    };

    // -------------------------------------------------------------------------
    // Delete Admin Functionality
    // -------------------------------------------------------------------------
    const handleDeleteAdmin = () => {
        $(adminsTable).on("click", '[data-kt-admin-filter="delete_row"]', function (event) {
            event.preventDefault();

            const parentDiv = $(this).closest('[data-admin-id]');
            const adminId = parentDiv.data('admin-id');
            const adminName = parentDiv.data('admin-name');


            Swal.fire({
                text: `${messages.Deleted}` + ' ' + adminName+ "?",
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
                        text: messages.Deleting + adminName + "...",
                        icon: "info",
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                    $.ajax({
                        url: `admins/${adminId}`,
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
                        text:  adminName + messages.NotDeleted,
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
    // Initialize Function
    // -------------------------------------------------------------------------
    return {
        init: async function () {
            if (adminsTable) {
                await initDataTable();
                handleDeleteAdmin();
                setupSearch();
            }
        }
    };
})();

$('#kt_table_admins').on('draw.dt', function () {
    KTMenu.createInstances();
});
loadMessagesWithCache(currentLanguage).then(() => {
    KTUtil.onDOMContentLoaded(function () {
        KTAdminsList.init();
    });
});
