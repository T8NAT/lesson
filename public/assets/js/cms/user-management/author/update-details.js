"use strict";
var author_id = $('meta[name="author_id"]').attr('content');
var root = window.location.protocol + '//' + window.location.host;
var baseURL = '/dashboard/authors/' + author_id;

var KTUsersUpdateDetails = function () {
    const t = document.getElementById("kt_modal_update_details"),
        e = t.querySelector("#kt_modal_update_author_form"),
        n = new bootstrap.Modal(t);

    return {
        init: function () {
            (() => {
                // Close button event listener
                t.querySelector('[data-kt-authors-modal-action="close"]').addEventListener("click", (t => {
                    t.preventDefault();
                    Swal.fire({
                        text: "هل انت متأكد من عملية الالغاء",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "نعم، الغاء",
                        cancelButtonText: "لا، تراجع",
                        customClass: { confirmButton: "btn btn-primary", cancelButton: "btn btn-active-light" }
                    }).then((function (t) {
                        if (t.value) {
                            e.reset();
                            n.hide();
                        } else if ("cancel" === t.dismiss) {
                            Swal.fire({
                                text: "لم يتم الغاء الفورم",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "حسناً،لقد فهمت",
                                customClass: { confirmButton: "btn btn-primary" }
                            });
                        }
                    }));
                }));

                // Submit button event listener
                const o = t.querySelector('[data-kt-authors-modal-action="submit"]');
                o.addEventListener("click", function (t) {
                    t.preventDefault();

                    // Show loading indicator on the button
                    o.setAttribute("data-kt-indicator", "on");
                    o.disabled = true;

                    // Create FormData object to handle file uploads
                    let formData = new FormData(e);

                    // Perform AJAX request for form submission
                    $.ajax({
                        url: baseURL, // Replace with the correct URL
                        method: 'POST',
                        data: formData,
                        contentType: false, // Prevent jQuery from setting the content type header
                        processData: false, // Prevent jQuery from processing the data
                        success: function (data) {
                            o.removeAttribute("data-kt-indicator");
                            o.disabled = false;

                            Swal.fire({
                                text: data.text,
                                icon: data.icon,
                                buttonsStyling: false,
                                confirmButtonText: 'حسناً،لقد فهمت',
                                customClass: { confirmButton: "btn btn-primary" }
                            }).then((function (result) {
                                if (result.isConfirmed) {
                                    n.hide(); // Hide the modal
                                    location.reload(); // Refresh the page or data if needed
                                }
                            }));
                        },
                        error: function (xhr) {
                            o.removeAttribute("data-kt-indicator");
                            o.disabled = false;

                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                $('.error-message').empty(); // Clear previous error messages

                                // Display validation errors
                                $.each(errors, function (key, error) {
                                    let inputKey = key.replace(/\./g, '_');
                                    $('#' + inputKey + '-error').html('<p style="color:red;">' + error[0] + '</p>');
                                });

                                Swal.fire({
                                    text: "يرجى تصحيح الاخطاء والمحاولة مرة اخرى",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "حسناً، لقد فهمت",
                                    customClass: { confirmButton: "btn btn-primary" }
                                });
                            } else {
                                Swal.fire({
                                    text: "حدث خطأ غير متوقع يرجى المحاولة لاحقاً",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "حسناً،لقد فهمت",
                                    customClass: { confirmButton: "btn btn-primary" }
                                });
                            }
                        }
                    });
                });
            })();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTUsersUpdateDetails.init();
});
