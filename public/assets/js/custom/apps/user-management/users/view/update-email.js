"use strict";
var user_id = $('meta[name="user_id"]').attr('content');
var root = window.location.protocol + '//' + window.location.host;

var URL1 = '/dashboard/user-update-email/' + user_id;

var KTUsersUpdateEmail = function () {
    const t = document.getElementById("kt_modal_update_email"), e = t.querySelector("#kt_modal_update_email_form"),
        n = new bootstrap.Modal(t);
    return {
        init: function () {
            (() => {
                var o = FormValidation.formValidation(e, {
                    fields: {profile_email: {validators: {notEmpty: {message: "Email address is required"}}}},
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger,
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row",
                            eleInvalidClass: "",
                            eleValidClass: ""
                        })
                    }
                });
                t.querySelector('[data-kt-users-modal-action="close"]').addEventListener("click", (t => {
                    t.preventDefault(), Swal.fire({
                        text: "هل انت متأكد من الالغاء؟",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "نعم، إلغاء",
                        cancelButtonText: "لا ، تراجع",
                        customClass: {confirmButton: "btn btn-primary", cancelButton: "btn btn-active-light"}
                    }).then((function (t) {
                        t.value ? (e.reset(), n.hide()) : "cancel" === t.dismiss && Swal.fire({
                            text: "تم التراجع عن الالغاء",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "حسناً، لقد فهمت",
                            customClass: {confirmButton: "btn btn-primary"}
                        })
                    }))
                })), t.querySelector('[data-kt-users-modal-action="cancel"]').addEventListener("click", (t => {
                    t.preventDefault(), Swal.fire({
                        text: "هل انت متأكد من الالغاء؟",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "نعم، إلغاء",
                        cancelButtonText: "لا، تراجع",
                        customClass: {confirmButton: "btn btn-primary", cancelButton: "btn btn-active-light"}
                    }).then((function (t) {
                        t.value ? (e.reset(), n.hide()) : "cancel" === t.dismiss && Swal.fire({
                            text: "تم التراجع عن الالغاء",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "حسناً، لقد فهمت",
                            customClass: {confirmButton: "btn btn-primary"}
                        })
                    }))
                }));
                const a = t.querySelector('[data-kt-users-modal-action="submit"]');
                a.addEventListener("click", (event) => {
                    event.preventDefault();
                    o && o.validate().then((result) => {
                        if (result === "Valid") {
                            a.setAttribute("data-kt-indicator", "on");
                            a.disabled = true;

                            // Perform AJAX request for form submission
                            $.ajax({
                                url: URL1, // Replace with the correct route
                                method: 'PUT',
                                data: $(e).serialize(),
                                success: function(data) {
                                    a.removeAttribute("data-kt-indicator");
                                    a.disabled = false;

                                    Swal.fire({
                                        text: data.text,
                                        icon: data.icon,
                                        buttonsStyling: false,
                                        confirmButtonText: 'حسناً لقد فهمت',
                                        customClass: { confirmButton: "btn btn-primary" }
                                    }).then((function (result) {
                                        if (result.isConfirmed) {
                                            n.hide(); // Hide the modal
                                            location.reload(); // Refresh the page or data if needed
                                        }
                                    }));
                                },
                                error: function(xhr) {
                                    a.removeAttribute("data-kt-indicator");
                                    a.disabled = false;

                                    if (xhr.status === 422) {
                                        let errors = xhr.responseJSON.errors;
                                        $('.error-message').empty(); // Clear previous error messages

                                        // Display validation errors under each input
                                        $.each(errors, function(key, error) {
                                            $('#' + key + '-error').html('<p style="color:red;">' + error[0] + '</p>');
                                        });

                                        Swal.fire({
                                            text: "يرجى تصحيح الاخطاء،والمحاولة مرة اخرى",
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: { confirmButton: "btn btn-primary" }
                                        });
                                    } else {
                                        Swal.fire({
                                            text: "حدث خطأ غير متوقع،يرجى المحاولة لاحقا !",
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "حسناً،لقد فهمت",
                                            customClass: { confirmButton: "btn btn-primary" }
                                        });
                                    }
                                }
                            });
                        }
                    });
                });
            })();
        }
    }
}();
KTUtil.onDOMContentLoaded((function () {
    KTUsersUpdateEmail.init()
}));
