"use strict";

if (typeof Cleave !== "undefined") {
    // Initalize date mask
    let datemasks = document.querySelectorAll(".date-mask");
    if (datemasks) {
        datemasks.forEach(function (el) {
            new Cleave(el, {
                date: true,
                datePattern: ["d", "m", "Y"],
            });
        });
    }

    // Initalize credit card mask
    let creditcards = document.querySelectorAll(".credit-card-mask");
    if (creditcards) {
        creditcards.forEach(function (el) {
            new Cleave(el, {
                creditCard: true,
            });
        });
    }

    // Initalize telephone mask
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

    // Initalize identification number mask
    let identifications = document.querySelectorAll(".identification-mask");
    if (identifications) {
        identifications.forEach(function (el) {
            new Cleave(el, {
                blocks: [11],
                numericOnly: true,
            });
        });
    }

    // Initalize expire date mask
    let expire = document.querySelectorAll(".expire-date-mask");
    if (expire) {
        expire.forEach(function (el) {
            new Cleave(el, {
                date: true,
                datePattern: ["m", "y"],
            });
        });
    }
}
