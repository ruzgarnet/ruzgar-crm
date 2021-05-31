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

        // Cloned form because we don't need change inputs value in client side
        let form = $(this),
            formClone = this.cloneNode(true),
            action = form.attr("action");

        // Clear masked values cloned form
        unMask(formClone);

        let formData = new FormData(formClone);

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
                        let input = form.find(
                            "[name='" + field.replace(/\.(\w*)/, "[$1]") + "']"
                        );
                        // Print invalid feedbacks
                        input.addClass("is-invalid");
                        input.parents(".form-group").append(
                            `<div class="invalid-feedback d-block">
                                ${response.errors[field][0]}
                            </div>`
                        );
                    }
                }

                // Remove disabled submits
                form.find("[type='submit']").prop("disabled", false);
            },
            success: function (result, status, xhr) {
                // Remove disabled submits
                if (!result.success || result.repeatable) {
                    form.find("[type='submit']").prop("disabled", false);
                }

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

                // If response has approve, change fields from table
                if (result.approve) {
                    let approve = result.approve,
                        modal = $(".approve-modal");

                    modal.find("form").prop("action", "");
                    modal.modal("hide");

                    let row = $("tr[data-id='" + approve.id + "']");

                    if (approve.column) {
                        row.find("." + approve.column)
                            .removeClass()
                            .addClass([
                                approve.column,
                                approve.column + "-" + approve.type,
                            ])
                            .text(approve.title);
                    }

                    if (approve.unapprove) {
                        row.removeClass("approved-row");
                        row.addClass("un-approved-row");
                    } else {
                        row.removeClass("un-approved-row");
                        row.addClass("approved-row");
                    }

                    form.find("[type='submit']").prop("disabled", false);
                }
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
                        timeout: 2000,
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

    /**
     * Open delete modal and change form action
     */
    $(document).on("click", ".get-payment-modal-btn", function () {
        let button = $(this),
            action = button.data("action"),
            price = button.data("price");

        $("#paymentForm").prop("action", action);
        $("#paymentModal").modal("show");
        $("#inpPrice").val(price);
    });

    /**
     * Open delete modal and change form action
     */
    $(document).on("input", "#paymentForm #slcType", function () {
        let form = $("#paymentForm"),
            val = $(this).val();

        form.find(".payment-types").hide();
        form.find(".payment-type-" + val).show();
    });

    /**
     * Change selected option for fix cloned selects
     */
    const selects = document.querySelectorAll("select");
    if (selects) {
        selects.forEach(function (select) {
            select.addEventListener("change", function () {
                for (let option in select.options) {
                    select.options.item(option).removeAttribute("selected");
                }
                select.options
                    .item(select.selectedIndex)
                    .setAttribute("selected", true);
            });
        });
    }

    $(document).on("click", ".approve-modal-btn", function () {
        let button = $(this),
            action = button.data("action"),
            modal = $(button.data("modal"));

        modal.find("form").prop("action", action);
        modal.modal("show");
    });

    $(document).on("input", ".slug-to-input", function () {
        if (typeof slugify !== "undefined") {
            let input = $(this),
                slug = $("#" + input.data("slug")),
                val = slugify(input.val(), { lower: true });

            slug.val(val);
            if (slug.hasClass("is-invalid")) {
                slug.trigger("input");
            }
        }
    });

    $(document).on("input", ".slug-input", function () {
        if (typeof slugify !== "undefined") {
            let input = $(this),
                val = input.val(),
                lowerCase = input.data("lower") === "off" ? false : true;

            if (
                !(
                    val.charAt(val.length - 1) === "-" &&
                    val.charAt(val.length - 2) !== "-"
                ) &&
                !(
                    val.charAt(val.length - 1) === " " &&
                    val.charAt(val.length - 2) !== " "
                )
            ) {
                input.val(slugify(val, { lower: lowerCase }));
            }
        }
    });

    let editors = document.querySelectorAll(".txt-editor");
    if (editors && typeof CKEDITOR !== "undefined") {
        editors.forEach(function (el) {
            CKEDITOR.replace(el);
        });
        CKEDITOR.dtd.$removeEmpty["span"] = false;
    }

    if (typeof $.fn.select2 !== "undefined") {
        $(".selectpicker").select2({ lang: "tr" });
        $(".selectpicker").on("change", function (e) {
            let select = this;

            for (let option in select.options) {
                select.options.item(option).removeAttribute("selected");
            }
            select.options
                .item(select.selectedIndex)
                .setAttribute("selected", true);
        });
    }
});

/**
 * Clear masked input for backend
 * @param  {object} form
 * @return {void}
 */
function unMask(form) {
    form = $(form);
    if (form.find(".date-mask").length > 0) {
        form.find(".date-mask").each(function (index, el) {
            let val = form.find(el).val();

            if (val.length === 10) {
                let split = val.split("/"),
                    date = split[2] + "-" + split[1] + "-" + split[0];

                form.find(el).val(date);
            } else {
                form.find(el).val("");
            }
        });
    }

    if (form.find(".telephone-mask").length > 0) {
        form.find(".telephone-mask").each(function (index, el) {
            let val = form.find(el).val();

            if (val.length >= 10) {
                val = val.replace(/\D/g, "");
                val = val.replace(/[\+90|0]?([1-9][0-9]{9})/g, "$1");

                form.find(el).val(val);
            } else {
                form.find(el).val("");
            }
        });
    }
}

let datemasks = document.querySelectorAll(".date-mask");
if (datemasks && typeof Cleave !== "undefined") {
    datemasks.forEach(function (el) {
        new Cleave(el, {
            date: true,
            datePattern: ["d", "m", "Y"],
        });
    });
}

let creditcards = document.querySelectorAll(".credit-card-mask");
if (creditcards && typeof Cleave !== "undefined") {
    creditcards.forEach(function (el) {
        new Cleave(el, {
            creditCard: true,
        });
    });
}

let telephones = document.querySelectorAll(".telephone-mask");
if (telephones && typeof Cleave !== "undefined") {
    telephones.forEach(function (el) {
        new Cleave(el, {
            phone: true,
            phoneRegionCode: "tr",
            prefix: "0",
        });
    });
}

let identifications = document.querySelectorAll(".identification-mask");
if (identifications && typeof Cleave !== "undefined") {
    identifications.forEach(function (el) {
        new Cleave(el, {
            blocks: [11],
            numericOnly: true,
        });
    });
}

let expire = document.querySelectorAll(".expire-date-mask");
if (expire && typeof Cleave !== "undefined") {
    expire.forEach(function (el) {
        new Cleave(el, {
            date: true,
            datePattern: ["m", "y"],
        });
    });
}
