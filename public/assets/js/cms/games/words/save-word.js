"use strict";
var KTAppSaveWord = function () {

    const initWordForm = () => {
        const form = document.getElementById("kt_add_word_form");
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
                        confirmButtonText: response.confirmButtonText
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
    };
    const initializeSelect2WithInfiniteScroll = (selectElement, url) => {
        selectElement.select2({
            allowClear: true,
            // multiple: true,
            search:true,
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page || 1,
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;

                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                id: item.id,
                                text: item.name
                            };
                        }),
                        pagination: {
                            more: data.current_page < data.last_page,
                        },
                    };
                },
                cache: true,
            },
            minimumInputLength: 0,
            templateResult: function (item) {
                if (item.loading) {
                    return item.text;
                }
                if (typeof item.text === 'object') {
                    return item.text;
                }
                return item.text;
            },
            templateSelection: function (item) {
                if (item && item.text) {
                    if (typeof item.text === 'object') {
                        return item.text;
                    }
                    return item.text;
                }
                return item ? item.text : item;
            },
        });
    };
    $(document).ready(function(){
        initializeSelect2WithInfiniteScroll($('select[name="category_id"]'), categories.get);
    });

    return {
        init: function () {
            initWordForm();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTAppSaveWord.init();
});
