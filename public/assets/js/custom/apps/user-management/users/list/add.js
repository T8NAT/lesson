"use strict";
var KTAppEcommerceSaveUser = function () {
    let dataTable; // Define dataTable variable

    const initUserForm = () => {
        const form = document.getElementById("kt_cms_add_user_form");
        const submitButton = form.querySelector("button[type='submit']");
        const cancelButton = form.querySelector('[data-kt-users-modal-action="cancel"]');
        const modalElement = document.getElementById("kt_modal_add_user");
        const modal = new bootstrap.Modal(modalElement);
        const closeButton = modalElement.querySelector('[data-kt-users-modal-action="close"]');

        submitButton.addEventListener("click", function (e) {
            e.preventDefault();

            // Create FormData object
            const formData = new FormData(form);

            // Disable the submit button and show loading indicator
            submitButton.setAttribute("data-kt-indicator", "on");
            submitButton.disabled = true;

            // AJAX request to submit the form data
            $.ajax({
                url: form.getAttribute("action"),
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    // Re-enable the submit button
                    submitButton.removeAttribute("data-kt-indicator");
                    submitButton.disabled = false;
                    Swal.fire({
                        title: response.title,
                        text: response.text ,
                        icon: response.icon,
                        confirmButtonText: 'حسناً،لقد فهمت!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            dataTable.ajax.reload();
                            modal.hide();
                        }
                    });
                },
                error: function (xhr) {
                    // Re-enable the submit button
                    submitButton.removeAttribute("data-kt-indicator");
                    submitButton.disabled = false;

                    // Check if validation errors exist
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;

                        // Clear previous error messages
                        form.querySelectorAll(".text-danger").forEach(el => el.remove());

                        // Display validation errors under each input
                        for (const key in errors) {
                            const input = form.querySelector(`[name="${key}"]`);
                            if (input) {
                                const errorDiv = document.createElement("div");
                                errorDiv.className = "text-danger mt-1";
                                errorDiv.innerText = errors[key][0];
                                input.parentNode.appendChild(errorDiv);
                            }
                        }

                        Swal.fire({
                            title: "خطأ",
                            text: "يرجى تصحيح الأخطاء ثم المحاولة مرة أخرى.",
                            icon: "error",
                            confirmButtonText: "حسناً"
                        });
                    } else {
                        // Handle any other errors
                        Swal.fire({
                            title: "خطأ",
                            text: "حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى.",
                            icon: "error",
                            confirmButtonText: "حسناً"
                        });
                    }
                }
            });
        });

        cancelButton.addEventListener("click", (e) => {
            e.preventDefault();
            Swal.fire({
                text: "هل أنت متأكد أنك تريد إلغاء التغييرات؟",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "نعم، إلغاء",
                cancelButtonText: "لا، عُد",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.reset();
                    modal.hide();
                }
            });
        });

        if(closeButton){
            closeButton.addEventListener("click", function(e) {
                e.preventDefault();
                modal.hide();
            });
        }
    };
    const initDataTable = () => {
        dataTable = $("#kt_table_users").DataTable();
    };


    return {
        init: function () {
            initUserForm();
            initDataTable();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTAppEcommerceSaveUser.init();
});
