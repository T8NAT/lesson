"use strict";

// -------------------------------------------------------------------------
// Configuration
// -------------------------------------------------------------------------

const currentLanguage = document.documentElement.lang || "ar";
const languageCacheKey = "dataTableLanguage";
const dataTableLanguageUrl = "/assets/js/custom/apps/ecommerce/language/ar.json";

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
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

// -------------------------------------------------------------------------
// Initialize Data Table
// -------------------------------------------------------------------------

var KTSectionsList = (function () {
    var dataTable;
    var sectionsTable = document.getElementById("kt_ecommerce_section_table");
    var searchInput;

    const initDataTable = async () => {
        if (!sectionsTable) return;

        let languageData = await loadDataTableLanguage();

        dataTable = $(sectionsTable).DataTable({
            info: false,
            order: [],
            language: languageData,
            columnDefs: [
                { orderable: false, targets: 1 },
                { orderable: false, targets: 3 },
            ],
        });
    };

    // const initDataTable = async () =>{
    //     const languageData = await loadDataTableLanguage();
    //     dataTable = $(sectionsTable).DataTable({
    //         processing: true,
    //         serverSide: true,
    //         language: languageData,
    //         ajax: {
    //             url: '/dashboard/sections/',
    //             type: "GET",
    //         },
    //         columns: [
    //             { data: "checkbox", orderable: false, searchable: false },
    //             { data: 'category', name: 'category.name' },
    //             { data: 'position', name: 'position' },
    //             { data: 'actions', name: 'actions', orderable: false, searchable: false },
    //         ],
    //         order: [[1, "asc"]],
    //         'drawCallback': function( settings ) {
    //         }
    //     });
    // }


    const setupSearch = () => {
        searchInput = $('[data-kt-section-table-filter="search"]');
        searchInput.on("keyup", function () {
            dataTable.search($(this).val()).draw();
        });
    };

    return {
        init: async function () {
            if (sectionsTable) {
                await initDataTable();
                setupSearch();
            }
        },
    };
})();

// -------------------------------------------------------------------------
// Initialize App
// -------------------------------------------------------------------------

$(document).ready(function () {
    loadMessagesWithCache(currentLanguage).then(() => {
        KTSectionsList.init();
    });
});

$("#kt_ecommerce_section_table").on("draw.dt", function () {
    if (typeof KTMenu !== "undefined" && KTMenu.createInstances) {
        KTMenu.createInstances();
    }
});
