"use strict";
var KTAppSaveWord = function () {
    const formatInputNameForKey = (key) => {
        if (!key.includes('.')) {
            return key;
        }

        let parts = key.split('.');
        let name = parts[0];
        for (let i = 1; i < parts.length; i++) {
            name += `[${parts[i]}]`;
        }
        return name;
    };

    const displayFieldError = (input, message) => {
        const parent = input.closest('.fv-row') || input.parentNode;
        if (!parent) return; // Exit if no suitable parent found
        input.classList.add('is-invalid');
        const existingError = parent.querySelector('.fv-plugins-message-container.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
        const errorDiv = document.createElement("div");
        errorDiv.className = "fv-plugins-message-container invalid-feedback";
        errorDiv.innerText = message;

        parent.appendChild(errorDiv);
    };

    const initWordForm = () => {
        const form = document.getElementById("kt_add_word_form");
        const submitButton = form.querySelector("button[type='submit']");

        submitButton.addEventListener("click", function (e) {
            e.preventDefault();

            const formData = new FormData(form);

            submitButton.setAttribute("data-kt-indicator", "on");
            submitButton.disabled = true;

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
                            window.location.href = form.getAttribute("data-kt-redirect");
                        }
                    });
                },
                error: function (xhr) {
                    submitButton.removeAttribute("data-kt-indicator");
                    submitButton.disabled = false;
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;

                        for (const key in errors) {
                            if (errors.hasOwnProperty(key) && errors[key].length > 0) {

                                const inputName = formatInputNameForKey(key);

                                const inputs = form.querySelectorAll(`[name="${inputName}"]`);

                                if (inputs.length > 0) {
                                    const input = inputs[0];
                                    const message = errors[key][0];
                                    displayFieldError(input, message);
                                } else {
                                    console.warn(`Validation error for key "${key}", but no input found with name "${inputName}". Check form structure and input names.`);
                                }
                            }
                        }
                        Swal.fire({
                            title: "خطأ",
                            text: "يرجى تصحيح الأخطاء ثم المحاولة مرة أخرى.",
                            icon: "error",
                            confirmButtonText: "حسناً"
                        });
                    } else {
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

    $(document).ready(function() {
        $('select[name="category_id"]').each(function() {
            const selectElement = $(this);
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
                        } else {
                            initializeSelect2WithInfiniteScroll(selectElement, categories.get, categoryId);
                        }
                    }
                });
            }else{
                initializeSelect2WithInfiniteScroll(selectElement, categories.get, categoryId);
            }
        });
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
