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



var KTAppGames = function () {
    return {
        init: function () {
            const table = $("#kt_game_table").DataTable({
                processing: true,
                serverSide: true,
                language: {
                    url: '/assets/js/custom/apps/ecommerce/language/ar.json'
                },
                ajax: {
                    url: '/dashboard/games/',
                    type: "GET",
                },

                columns: [
                    { data: 'id', name: 'id', orderable: false, searchable: false },
                    { data: 'partials', name: 'partials', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'type.name', name: 'type.name',orderable: false, searchable: false  },
                    { data: 'categories', name: 'categories',orderable: false, searchable: false },
                    { data: 'status', name: 'status' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                columnDefs: [
                    {
                        targets: 0,
                        render: function (data, type, row) {
                            return `<div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="${data}" />
                    </div>`;
                        }
                    },
                    {
                        // Game Name (can add description if available)
                        targets: 2,
                        render: function(data, type, row) {
                            // Optional: Add description if sent from controller
                            // const description = row.description ? `<span class="text-muted d-block fs-7">${row.description}</span>` : '';
                            return `<div class="d-flex flex-column">
                        <span class="text-gray-800 text-hover-primary mb-1">${data}</span>

                    </div>`;
                        }
                    },
                    {
                        // Game Type (Using colored badge based on type_color)
                        targets: 3,
                        render: function (data, type, row) {
                            // Use the color provided by the controller, default to 'secondary' if none
                            const badgeColor = row.type_color || 'secondary';
                            return `<span class="badge badge-${badgeColor}">${data}</span>`;
                        }
                    },

                ],

                order: [[1, "asc"]]

            });

            // Delete functionality
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            table.on("click", '[data-kt-game-filter="delete_row"]', function () {
                const gameId = $(this).closest("div[data-game-id]").data("game-id");
                const gameName = $(this).closest("div[data-game-name]").data("game-name");

                Swal.fire({
                    text: `${messages.Deleted} ${gameName}?`,
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
                            text: messages.Deleting + gameName + "...",
                            icon: "info",
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                        $.ajax({
                            url:  '/dashboard/games/' + gameId,
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
                            text: gameName + messages.NotDeleted,
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

$('#kt_game_table').on('draw.dt', function () {
    KTMenu.createInstances();
});
KTUtil.onDOMContentLoaded(function () {
    KTAppGames.init();
});
