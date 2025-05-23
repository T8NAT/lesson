"use strict";
var KTApplogInAdmin = function () {

    const initLogInForm = () => {
        const form = document.getElementById("kt_cms_login_admin_form");
        const submitButton = form.querySelector("button[type='submit']");

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
                        confirmButtonText: "حسناً ،لقد فهمت",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (response.icon === 'success' && result.isConfirmed) {
                                window.location.href = form.getAttribute("data-kt-redirect");
                            }
                        }
                    });
                },
                error: function (xhr) {
                    // Re-enable the submit button
                    submitButton.removeAttribute("data-kt-indicator");
                    submitButton.disabled = false;

                    // Check if validation errors exist
                    if (xhr.status === 422 || xhr.status === 401) {
                        if (xhr.responseJSON.text) {
                            Swal.fire({
                                title: "خطأ",
                                text: xhr.responseJSON.text,
                                icon: "error",
                                confirmButtonText: "حسناً"
                            });
                        } else {
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
                        }

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
    };

    return {
        init: function () {
            initLogInForm();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTApplogInAdmin.init();
});
