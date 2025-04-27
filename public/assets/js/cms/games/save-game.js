"use strict";
var KTAppSaveGame = function () {

    const initStatusToggle = () => {
        const statusEl = document.getElementById("kt_add_game_status");
        const statusSelect = document.getElementById("kt_add_game_status_select");
        const bgClasses = ["bg-success", "bg-danger"];

        $(statusSelect).on("change", (e) => {
            const value = e.target.value;
            statusEl.classList.remove(...bgClasses);
            if (value === "active") statusEl.classList.add("bg-success");
            else statusEl.classList.add("bg-danger");
        });
    };

    const initTypeSelect = () => {
        const typeSelect = document.querySelector('select[name="type_id"]');
        const mediaSection = document.getElementById('media-section');

        if (!typeSelect || !mediaSection) {
            console.error("typeSelect or mediaSection not found");
            return;
        }

        const updateMediaSectionVisibility = (selectedType) => {
            console.log("selectedType:", selectedType);
            if (selectedType === "1") {
                mediaSection.style.display = "none";
            } else {
                mediaSection.style.display = "block";
            }
        };

        // You might need to call this function after Select2 finishes loading data
        $(typeSelect).on("select2:select", function (e) {
            const selectedType = e.target.value;
            updateMediaSectionVisibility(selectedType);
        });
    };
    const initGameForm = () => {
        const form = document.getElementById("kt_add_game_form");
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

                    // console.log("DATA", data);

                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                id: item.id,
                                text: item.name,
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
    // $(document).ready(function(){
    //     initializeSelect2WithInfiniteScroll($('select[name="category_id[]"]'), categories.get);
    //     initializeSelect2WithInfiniteScroll($('select[name="type_id"]'), types.get);
    //     initTypeSelect();
    //
    // });


    $(document).ready(function() {
        $('select[name="category_id[]"]').each(function() {
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
    $(document).ready(function() {
        $('select[name="type_id"]').each(function() {
            const selectElement = $(this);
            const typeId = selectElement.data('selected-id');

            if (typeId) {
                $.ajax({
                    url: types.get,
                    dataType: 'json',
                    data: { id: typeId }
                }).then(function(data) {
                    if (data && data.data ) {
                        const selectedItem = data.data.find(item => item.id == typeId);
                        if(selectedItem){
                            const option = new Option(selectedItem.name, selectedItem.id, true, true);
                            selectElement.append(option).trigger('change');
                            initializeSelect2WithInfiniteScroll(selectElement, types.get, typeId);
                        } else {
                            initializeSelect2WithInfiniteScroll(selectElement, types.get, typeId);
                        }
                    }
                });
            }else{
                initializeSelect2WithInfiniteScroll(selectElement, types.get, typeId);
            }
        });
    });

    return {
        init: function () {
            initGameForm();
            initStatusToggle();
            initTypeSelect();

        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTAppSaveGame.init();
});
