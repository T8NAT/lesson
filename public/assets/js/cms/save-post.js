"use strict";

var KTAppEcommerceSavePost = function () {
    // Function to initialize Tagify
    const initTagify = () => {
        const tagsInput = document.querySelector("#kt_ecommerce_add_product_tags");

        if (tagsInput) {
            console.log(tags); // Check if tags are properly passed
            new Tagify(tagsInput, {
                whitelist: tags, // Use the 'tags' variable from Blade
                dropdown: {
                    maxItems: 20,
                    classname: "tagify__inline__suggestions",
                    enabled: 0,
                    closeOnSelect: false
                }
            });
        }
    };


    // Function to initialize the Quill editor
    const initQuillEditor = () => {
        const quill = new Quill("#kt_ecommerce_add_post_description", {
            modules: {
                toolbar: [
                    [{ header: [1, 2, false] }],
                    ["bold", "italic", "underline"],
                    ["image", "code-block"]
                ]
            },
            placeholder: "Type your text here...",
            theme: "snow"
        });

        // Update the hidden input field with the Quill content on form submission
        const form = document.getElementById("kt_cms_add_post_form");
        form.addEventListener("submit", function () {
            // Set the hidden input value to the Quill content
            document.getElementById("body").value = quill.root.innerHTML;
        });
    };

    const initStatusToggle = () => {
        const statusEl = document.getElementById("kt_cms_add_post_status");
        const statusSelect = document.getElementById("kt_cms_add_post_status_select");
        const bgClasses = ["bg-success", "bg-info", "bg-danger"];

        $(statusSelect).on("change", (e) => {
            const value = e.target.value;
            statusEl.classList.remove(...bgClasses);
            if (value === "published") statusEl.classList.add("bg-success");
            else if (value === "draft") statusEl.classList.add("bg-info");
            else if (value === "unpublished") statusEl.classList.add("bg-danger");
        });
    };

    const initPostForm = () => {
        const form = document.getElementById("kt_cms_add_post_form");
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
        initializeSelect2WithInfiniteScroll($('select[name="tags[]"]'), tags.get);
    });
    return {
        init: function () {
            initPostForm();
            initStatusToggle();
            initQuillEditor();
            initTagify();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTAppEcommerceSavePost.init();
});
