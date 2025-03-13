"use strict";

var KTUsersPermissionsList = function () {
    var t, e;

    return {
        init: function () {
            e = document.querySelector("#kt_permissions_table");
            if (e) {
                // Initialize DataTable
                t = $(e).DataTable({
                    info: false,
                    order: [],
                    columnDefs: [
                        { orderable: false, targets: 1 },
                        { orderable: false, targets: 3 }
                    ]
                });

                // Search functionality
                document.querySelector('[data-kt-permissions-table-filter="search"]').addEventListener("keyup", function (e) {
                        t.search(e.target.value).draw();
                    });

                // Add click event listener to each delete button
                e.querySelectorAll('[data-kt-permissions-table-filter="delete_row"]').forEach((deleteButton) => {
                    deleteButton.addEventListener("click", function (event) {
                        event.preventDefault();
                        const row = event.target.closest("tr");
                        const permissionName = row.querySelectorAll("td")[0].innerText;
                        const deleteUrl = deleteButton.getAttribute("data-delete-url"); // Make sure this attribute contains the URL

                        Swal.fire({
                            text: "هل انت متأكد انك تريد حذف " + permissionName + "؟",
                            icon: "warning",
                            showCancelButton: true,
                            buttonsStyling: false,
                            confirmButtonText: "نعم احذف",
                            cancelButtonText: "لا،تراجع",
                            customClass: {
                                confirmButton: "btn fw-bold btn-danger",
                                cancelButton: "btn fw-bold btn-active-light-primary"
                            }
                        }).then((result) => {
                            if (result.value) {
                                // Make AJAX request to delete the permission
                                $.ajax({
                                    url: deleteUrl, // URL for deletion
                                    type: 'DELETE',
                                    data: {
                                        _method: 'DELETE',
                                        _token: $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function (response) {
                                        // Show success message
                                        Swal.fire({
                                            text: response.text,
                                            icon: response.icon,
                                            buttonsStyling: false,
                                            confirmButtonText: "حسناً،فهمت",
                                            customClass: { confirmButton: "btn fw-bold btn-primary" }
                                        }).then(() => {
                                            // Remove the row from the table without refreshing
                                            t.row($(row)).remove().draw();
                                        });
                                    },
                                    error: function () {
                                        // Show error message
                                        Swal.fire({
                                            text: "حدث خطأ اثناء محاولة حذف الصلاحية.",
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "حسناً،فهمت",
                                            customClass: { confirmButton: "btn fw-bold btn-primary" }
                                        });
                                    }
                                });
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                Swal.fire({
                                    text: permissionName + " لم يتم حذف.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "حسناً فهمت",
                                    customClass: { confirmButton: "btn fw-bold btn-primary" }
                                });
                            }
                        });
                    });
                });
            }
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTUsersPermissionsList.init();
});
