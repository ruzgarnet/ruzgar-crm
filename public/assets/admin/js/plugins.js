"use strict";

/**
 * Fill slug when .slug-to-input changed
 */
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

/**
 * Slug input
 */
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

/**
 * Initalize ckeditor
 */
let editors = document.querySelectorAll(".txt-editor");
if (editors && typeof CKEDITOR !== "undefined") {
    editors.forEach(function (el) {
        CKEDITOR.replace(el);
    });
    CKEDITOR.dtd.$removeEmpty["span"] = false;
}

/**
 * Initalize select2
 */
if (typeof $.fn.select2 !== "undefined") {
    $(".selectpicker").select2({ lang: "tr" });

    /**
     * Fix selected option for cloned forms
     */
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
