"use strict";

$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    /**
     * Ajax requests
     */
    $(document).on("submit", "form:not([data-ajax='false'])", function (event) {
        // Disable submit
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
                // Disable submits for prevent double submits
                form.find("[type='submit']").prop("disabled", true);
                // Remove validation feedbacks
                form.find(".invalid-feedback").remove();
                form.find(".is-invalid").removeClass("is-invalid");
            },
            error: function (xhr) {
                // 422 = validation failed
                if (xhr.status === 422) {
                    let response = xhr.responseJSON;
                    for (let field in response.errors) {
                        let input = form.find("[name='" + field + "']");

                        // Print invalid feedbacks
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
                // Init toastr
                if (result.toastr) {
                    let toastr = result.toastr;
                    iziToast[toastr.type]({
                        title: toastr.title,
                        message: toastr.message,
                        position: "topRight",
                        timeout: 3000,
                    });
                }

                // If data deleted, remove from table and close modal
                if (result.deleted) {
                    let modal = $("#deleteModal");

                    modal.find("#deleteForm").prop("action", "");
                    modal.modal("hide");
                    $("table")
                        .find("tr[data-id='" + result.deleted + "']")
                        .remove();
                }

                // If response has redirect, fly me to moon
                if (result.redirect) {
                    setTimeout(function () {
                        location.replace(result.redirect);
                    }, 3000);
                }
            },
            complete: function (xhr, status) {
                // Remove disabled submits
                form.find("[type='submit']").prop("disabled", false);
            },
        });
    });

    /**
     * Remove changed input's invalid feedback
     */
    $(document).on("input", ".is-invalid", function () {
        let input = $(this);
        // Fix, remove invalid classes for other radio inputs
        if (input.prop("type") === "radio") {
            $("input[name='" + input.prop("name") + "']").removeClass(
                "is-invalid"
            );
        }
        input.parents(".form-group").find(".invalid-feedback").remove();
        input.removeClass("is-invalid");
    });

    /**
     * Fill district options dynamically
     */
    $(document).on("input", "#slcCity", function () {
        let city = $(this),
            district = $("#slcDistrict");

        $.ajax("/getDistricts/" + city.val(), {
            type: "GET",
            dataType: "json",
            beforeSend: function () {
                // Disable for bugs
                city.prop("disabled", true);
                district.prop("disabled", true);
            },
            error: function (xhr) {
                let response = xhr.responseJSON;

                // If response has error message alert
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
                // Remove disableds
                city.prop("disabled", false);
                district.prop("disabled", false);
            },
        });
    });

    /**
     * Open delete modal and change form action
     */
    $(document).on("click", ".delete-modal-btn", function () {
        let button = $(this),
            action = button.data("action"),
            modal = $("#deleteModal");

        modal.find("#deleteForm").prop("action", action);
        modal.modal("show");
    });
});

let datemasks = document.querySelectorAll(".date-mask");
if (datemasks) {
    datemasks.forEach(function (el) {
        new Cleave(el, {
            date: true,
            datePattern: ["d", "m", "Y"],
        });
    });
}

let creditcards = document.querySelectorAll(".credit-card-mask");
if (creditcards) {
    creditcards.forEach(function (el) {
        new Cleave(el, {
            creditCard: true,
        });
    });
}

let telephones = document.querySelectorAll(".telephone-mask");
if (telephones) {
    telephones.forEach(function (el) {
        new Cleave(el, {
            phone: true,
            phoneRegionCode: "tr",
            prefix: "0",
        });
    });
}

let identifications = document.querySelectorAll(".identification-mask");
if (identifications) {
    identifications.forEach(function (el) {
        new Cleave(el, {
            blocks: [11],
            numericOnly: true,
        });
    });
}
