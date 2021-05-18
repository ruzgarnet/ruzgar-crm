"use strict";

$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $(document).on("submit", "form:not([data-ajax='false'])", function (event) {
        event.preventDefault();

        let form = $(this),
            action = form.attr("action"),
            formData = new FormData(this);

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

                if (result.deleted) {
                    let modal = $("#deleteModal");

                    modal.find("#deleteForm").prop("action", "");
                    modal.modal("hide");
                    $("table")
                        .find("tr[data-id='" + result.deleted + "']")
                        .remove();
                }

                if (result.redirect) {
                    setTimeout(function () {
                        location.replace(result.redirect);
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

    $(document).on("input", "#slcCity", function () {
        let city = $(this),
            district = $("#slcDistrict");

        $.ajax("/getDistricts/" + city.val(), {
            type: "GET",
            dataType: "json",
            beforeSend: function () {
                city.prop("disabled", true);
                district.prop("disabled", true);
            },
            error: function (xhr) {
                let response = xhr.responseJSON;

                if (response.message) {
                    iziToast.error({
                        message: response.message,
                        position: "topRight",
                        timeout: 3000,
                    });
                }
            },
            success: function (districts) {
                if (districts) {
                    district.find("option").remove();
                    for (let key in districts) {
                        district.append(
                            `<option value="${districts[key].id}">
                                ${districts[key].name}
                            </option>`
                        );
                    }
                }
            },
            complete: function () {
                city.prop("disabled", false);
                district.prop("disabled", false);
            },
        });
    });

    $(document).on("click", ".delete-modal-btn", function () {
        let button = $(this),
            action = button.data("action"),
            modal = $("#deleteModal");

        modal.find("#deleteForm").prop("action", action);
        modal.modal("show");
    });
});
