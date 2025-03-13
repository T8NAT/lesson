"use strict";
var KTUsersAddRole = function () {
    const initRoleForm = () => {
        const form = document.getElementById("kt_modal_add_role_form");
        const submitButton = form.querySelector("button[type='submit']");
        const cancelButton = form.querySelector('[data-kt-roles-modal-action="cancel"]');
        const modalElement = document.getElementById("kt_modal_add_role");
        const modal = new bootstrap.Modal(modalElement);
        const closeButton = modalElement.querySelector('[data-kt-roles-modal-action="close"]');

        // مستمعات الأزرار
        submitButton.addEventListener("click", function (e) {
            e.preventDefault();  // منع إرسال النموذج مباشرة

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
                        text: response.text,
                        icon: response.icon,
                        confirmButtonText: "حسناً"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to specified URL or reload the page
                            window.location.href = form.getAttribute("data-kt-redirect");
                        }
                    });
                },
                error: function (xhr) {
                    // Re-enable the submit button
                    submitButton.removeAttribute("data-kt-indicator");
                    submitButton.disabled = false;

                    // Handle validation errors
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

        // إغلاق النافذة عند الضغط على زر الإلغاء
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
                    form.reset();  // إعادة تعيين الفورم
                    modal.hide();  // إغلاق النافذة
                }
            });
        });

        // إغلاق الـ modal عند الضغط على زر الـ close
        closeButton.addEventListener("click", (e) => {
            e.preventDefault();
            modal.hide();  // إغلاق النافذة
        });
    };

    return {
        init: function () {
            initRoleForm();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTUsersAddRole.init();
});
