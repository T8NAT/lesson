"use strict";
var KTAppEcommerceEditPost = function () {

    const initStatusToggle = () => {
        const statusEl = document.getElementById("kt_cms_edit_post_status");
        const statusSelect = document.getElementById("kt_cms_edit_post_status_select");
        const bgClasses = ["bg-success","bg-info", "bg-danger"];

        $(statusSelect).on("change", (e) => {
            const value = e.target.value;
            statusEl.classList.remove(...bgClasses);
            if (value === "published") statusEl.classList.add("bg-success");
            else if (value === "draft") statusEl.classList.add("bg-info");
            else if (value === "unpublished")statusEl.classList.add("bg-danger");
        });
    };
    const initPostForm = () => {
        const form = document.getElementById("kt_cms_edit_post_form");
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

    const initializeSelect2WithInfiniteScroll = (selectElement, url, ids = null) => {
        selectElement.select2({
            allowClear: true,
            search: true,
            multiple: selectElement.attr('name').endsWith('[]'),
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page || 1,
                        ids: ids,
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
                    return item.text[currentLanguage] || item.text["ar"] || item.text.en;
                }
                return item.text;

            },
            templateSelection: function (item) {
                if (item && item.text) {
                    if (typeof item.text === 'object') {
                        return item.text[currentLanguage] || item.text["ar"] || item.text.en;
                    }
                    return item.text;
                }
                return item ? item.text : item;
            },
        });
    };
    $(document).ready(function() {
        const selectElement = $('select[name="category_id"]');
        const categoryId = selectElement.data('selected-id');
            if (categoryId) {
                $.ajax({
                    url: categories.get,
                    dataType: 'json',
                    data: { id: categoryId }
                }).then(function(data) {
                    if (data && data.data ) {
                        const selectedItem = data.data.find(item => item.id == categoryId);
                        if(selectedItem){
                            const option = new Option(selectedItem.name, selectedItem.id, true, true);
                            selectElement.append(option).trigger('change');
                            initializeSelect2WithInfiniteScroll(selectElement, categories.get, categoryId);
                        }
                    }
                });
            }else{
                initializeSelect2WithInfiniteScroll(selectElement, categories.get, categoryId);
            }
    });



    $(document).ready(function() {
        $('select[name="tags[]"]').each(function() {
            const selectElement = $(this);
            const selectedTagIds = selectElement.data('selected-ids');

            if (selectedTagIds && Array.isArray(selectedTagIds) && selectedTagIds.length > 0) {
                $.ajax({
                    url: tags.get,
                    dataType: 'json',
                    data: { ids: selectedTagIds }
                }).then(function(data) {
                    if (data && data.data) {
                        data.data.forEach(function(item) {
                            const option = new Option(item.name, item.id, true, true);
                            selectElement.append(option);
                        });
                        selectElement.trigger('change');
                    }
                    initializeSelect2WithInfiniteScroll(selectElement, tags.get, selectedTagIds);
                });

            }else {
                initializeSelect2WithInfiniteScroll(selectElement, tags.get);
            }
        });
    });

    return {
        init: function () {
            initPostForm();
            initStatusToggle();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTAppEcommerceEditPost.init();
});
