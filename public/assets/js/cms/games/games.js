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
                    // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    // {data: 'id', name: 'id'},
                    { data: "checkbox", orderable: false, searchable: false },
                    {data: 'name', name: 'name'},
                    {data: 'type', name: 'name'},
                    {data: 'categories', name: 'name'},
                    {data: 'status', name: 'status'},
                    {data: 'actions', name: 'actions', orderable: true, searchable: true},
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
