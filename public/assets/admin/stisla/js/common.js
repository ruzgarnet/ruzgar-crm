"use strict";

$(function () {
    $(document).on("submit", "form:not([data-ajax='false'])", function (event) {
        event.preventDefault();

        let form = $(this),
            action = form.attr("action"),
            formData = new FormData(this);

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax(action, {
            type: "POST",
            enctype: "multipart/form-data",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            beforeSend: function () {
                form.find("[type='submit']").prop("disabled", true);
                form.find(".invalid-feedback").remove();
                form.find(".is-invalid").removeClass("is-invalid");
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let response = xhr.responseJSON;
                    for (let field in response.errors) {
                        let input = form.find("[name='" + field + "']");

                        input.addClass("is-invalid");
                        input.parents(".form-group").append(
                            `<div class="invalid-feedback d-block">
                                ${response.errors[field][0]}
                            </div>`
                        );
                    }
                }
            },
            success: function (result, status, xhr) {
                if (result.toastr) {
                    let toastr = result.toastr;
                    iziToast[toastr.type]({
                        title: toastr.title,
                        message: toastr.message,
                        position: "topRight",
                        timeout: 3000,
                    });
                }

                if (result.redirect) {
                    setTimeout(function () {
                        location.href(result.redirect);
                    }, 3000);
                }
            },
            complete: function (xhr, status) {
                form.find("[type='submit']").prop("disabled", false);
            },
        });
    });

    $(document).on("input", ".is-invalid", function () {
        let input = $(this);
        input.parents(".form-group").find(".invalid-feedback").remove();
        input.removeClass("is-invalid");
    });
});
