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



var KTAppLevels = function () {
    return {
        init: function () {
            const table = $("#kt_level_table").DataTable({
                processing: true,
                serverSide: true,
                language: {
                    url: '/assets/js/custom/apps/ecommerce/language/ar.json'
                },
                ajax: {
                    url: '/dashboard/levels/',
                    type: "GET",
                },

                columns: [
                    { data: "checkbox", orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'games', name: 'games',orderable: false, searchable: false  },
                    { data: 'category.name', name: 'category.name'},
                    { data: 'points_reward', name: 'points_reward',orderable: false, searchable: false  },
                    { data: 'is_active', name: 'is_active' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                order: [[1, "asc"]]
            });

            // Delete functionality
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            table.on("click", '[data-kt-level-filter="delete_row"]', function () {
                const levelId = $(this).closest("div[data-level-id]").data("level-id");
                const levelName = $(this).closest("div[data-level-name]").data("level-name");

                Swal.fire({
                    text: `${messages.Deleted} ${levelName}?`,
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
                            text: messages.Deleting + levelName + "...",
                            icon: "info",
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                        $.ajax({
                            url:  '/dashboard/levels/' + levelId,
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
                            text: levelName + messages.NotDeleted,
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

$('#kt_level_table').on('draw.dt', function () {
    KTMenu.createInstances();
});
KTUtil.onDOMContentLoaded(function () {
    KTAppLevels.init();
});
