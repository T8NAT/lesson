"use strict";

let messages = {};

var lang = $('html').attr('lang');

const currentLanguage = document.documentElement.lang || "ar";

function loadMessagesWithCache(lang) {
    const cachedMessages = localStorage.getItem(`messages_${lang}`);
    if (cachedMessages) {
        messages = JSON.parse(cachedMessages);
        return Promise.resolve();
    } else {
        return $.getJSON(`/assets/js/custom/apps/ecommerce/language/languages.json`, function (data) {
            messages = data[lang] || data["en"];
            localStorage.setItem(`messages_${lang}`, JSON.stringify(messages));
        });
    }
}

loadMessagesWithCache(currentLanguage).then(() => {
    console.log("Messages loaded:", messages);
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



const dataTableLanguageUrl = '/assets/js/custom/apps/ecommerce/language/ar.json';
const languageCacheKey = 'dataTableLanguage';

async function loadDataTableLanguage() {
    const cachedLanguage = localStorage.getItem(languageCacheKey);
    if (cachedLanguage) {
        return JSON.parse(cachedLanguage);
    }else {
        try {
            const response = await fetch(dataTableLanguageUrl);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const languageData =  await response.json();
            localStorage.setItem(languageCacheKey, JSON.stringify(languageData));
            return languageData;

        }catch (error) {
            console.error("Failed to load data table language:", error);
            return {}; // Handle the error by returning an empty object
        }
    }
}



var KTAppEcommercePosts = function () {
    return {
        init: async function () {
            const languageData = await loadDataTableLanguage();
            const table = $("#kt_posts_table").DataTable({
                processing: true,
                serverSide: true,
                language: languageData,
                ajax: {
                    url: '/dashboard/posts/',
                    type: "GET",
                },
                columns: [
                    // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    // {data: 'id', name: 'id'},
                    { data: "checkbox", orderable: false, searchable: false },
                    {data: 'partials', name: 'partials'},
                    // {data: 'title', name: 'title'},
                    {data: 'category', name: 'category_id'},
                    {data: 'status', name: 'status'},
                    {data: 'actions', name: 'actions', orderable: true, searchable: true},
                ],
                order: [[1, "asc"]]

            });

            // Delete functionality


            table.on("click", '[data-kt-post-filter="delete_row"]', function () {
                const postId = $(this).closest("div[data-post-id]").data("post-id");
                const postName = $(this).closest("div[data-post-title]").data("post-title");

                Swal.fire({
                    text: `${messages.Deleted}` + ' ' + postName + '?',
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText:messages.YesButtonText,
                    cancelButtonText: messages.NoButtonText,
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then((result) => {
                    if (result.value) {
                        Swal.fire({
                            text: messages.Deleting + postName + "...",
                            icon: "info",
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                        $.ajax({
                            url:  '/dashboard/posts/' + postId,
                            type: "DELETE",
                            success: function (response) {
                                Swal.fire({
                                    text: response.text,
                                    icon: response.icon,
                                    buttonsStyling: false,
                                    confirmButtonText: messages.confirmButtonText,
                                    customClass: { confirmButton: "btn fw-bold btn-primary" }
                                }).then(() => table.ajax.reload());
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
                    }else if(result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire({
                            text: postName + messages.NotDeleted,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: messages.confirmButtonText,
                            customClass: { confirmButton: "btn fw-bold btn-primary" }
                        });
                    }
                });
            });
        }
    };
}();

$('#kt_posts_table').on('draw.dt', function () {
    KTMenu.createInstances();
});
KTUtil.onDOMContentLoaded(function () {
    KTAppEcommercePosts.init();
});
