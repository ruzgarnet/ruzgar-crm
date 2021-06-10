// TODO
function processForm() {
    $("#speed").fadeOut();
    $("#speed_information").fadeOut();
    $("#adviced_tariffs").fadeOut();

    jQuery
        .ajax({
            url: "/admin/infrastructure/submit",
            type: "POST",
            data: jQuery("#infrastructure_statu_form").serialize(),
            dataType: "json",
        })
        .fail(function (e) {
            console.log(e);
        })
        .done(function (data) {
            console.log(data);
            if (data.error == true) {
                $.alert({
                    type: "red",
                    title: "Hata",
                    content: data.message,
                    buttons: {
                        Kapat: function () {},
                    },
                });
            } else {
                var port_statu = false,
                    port_speed = 0,
                    port_speed_number = 0;
                var message = "";

                if (data.results.adsl.statu) {
                    port_statu = true;
                    port_speed = data.results.adsl.max_speed;
                    message +=
                        "[ADSL - <span style='font-weight:bold;font-size:20px;'>" +
                        port_speed +
                        " Mbps</span>]";
                    if ($.isNumeric(data.results.adsl.max_speed))
                        port_speed_number = data.results.adsl.max_speed;
                }
                if (data.results.vdsl.statu) {
                    port_statu = true;
                    port_speed = data.results.vdsl.max_speed;
                    message +=
                        "[VDSL - <span style='font-weight:bold;font-size:20px;'>" +
                        port_speed +
                        " Mbps</span>]";
                    if ($.isNumeric(data.results.vdsl.max_speed))
                        port_speed_number = data.results.vdsl.max_speed;
                }
                if (data.results.fiber.statu) {
                    port_statu = true;
                    port_speed = 0;
                }

                if (port_statu) {
                    if (port_speed != 0) {
                        $("#speed_information").html(
                            "Binanızda RüzgarNET hizmeti bulunmaktadır."
                        );
                        if (port_speed_number != 0) {
                            $("#speed").html(
                                "Alabileceğiniz maksimum hız : " + message
                            );
                        } else {
                            if (port_speed != NaN && port_speed != "N/A") {
                                $("#speed").html(message);
                            } else {
                                $("#speed_information").html(
                                    "Binanızda port bulunmasına rağmen hız belirlenememiştir. Size yardımcı olmamız için bizimle iletişime geçiniz."
                                );
                            }
                        }
                        $("#speed").fadeIn();
                    } else {
                        $("#speed_information").html(
                            message +
                                " Binanızda <span style='font-weight:bold;font-size:20px;'>FİBER</span> altyapı vardır. RüzgarNET hizmetinden faydanalanabilirsiniz."
                        );
                    }
                    $("#adviced_tariffs").fadeIn();
                } else {
                    $("#speed_information").text(
                        "Binanızda port bulunmamaktadır."
                    );
                }

                $("#speed_information").fadeIn();
            }
        });

    return false;
}

function get_cities() {
    $("#cities").html("");
    $.ajax({
        type: "POST",
        url: "/admin/infrastructure/load",
        async: true,
        dataType: "JSON",
        data: {
            id: 0,
            type: "cities",
        },
        success: function (e) {},
        complete: function (e) {
            $("#cities").append("<option value='-1'>İL SEÇİNİZ</option>");
            console.log(e);
            $.each(
                e.responseJSON.results.IlListesiGetirReturn,
                function (index, value) {
                    var row = "";

                    row +=
                        '<option value="' +
                        value.kod +
                        '">' +
                        value.ad +
                        "</option>";
                    $("#cities").append(row);
                }
            );
        },
    });
}

function get_districts(city_id) {
    $("#districts").html("");
    $.ajax({
        type: "POST",
        url: "/admin/infrastructure/load",
        async: true,
        dataType: "JSON",
        data: {
            id: city_id,
            type: "district",
        },
        success: function (e) {},
        complete: function (e) {
            $("#districts").append("<option value='-1'>İLÇE SEÇİNİZ</option>");
            console.log(e);
            $.each(
                e.responseJSON.results.IleBagliIlceListesiGetirReturn,
                function (index, value) {
                    var row = "";

                    row +=
                        '<option value="' +
                        value.kod +
                        '">' +
                        value.ad +
                        "</option>";
                    $("#districts").append(row);
                }
            );
        },
    });
}

function get_townships(district_id) {
    $("#townships").html("");
    $.ajax({
        type: "POST",
        url: "/admin/infrastructure/load",
        async: true,
        dataType: "JSON",
        data: {
            id: district_id,
            type: "township",
        },
        success: function (e) {},
        complete: function (e) {
            if (
                $.isArray(
                    e.responseJSON.results.IlceyeBagliBucakListesiGetirReturn
                )
            ) {
                $("#townships").append(
                    "<option value='-1'>BUCAK SEÇİNİZ</option>"
                );
                $.each(
                    e.responseJSON.results.IlceyeBagliBucakListesiGetirReturn,
                    function (index, value) {
                        var row = "";

                        row +=
                            '<option value="' +
                            value.kod +
                            '">' +
                            value.ad +
                            "</option>";
                        $("#townships").append(row);
                    }
                );
            } else {
                $("#townships").append(
                    '<option value="' +
                        e.responseJSON.results
                            .IlceyeBagliBucakListesiGetirReturn.kod +
                        '">' +
                        e.responseJSON.results
                            .IlceyeBagliBucakListesiGetirReturn.ad +
                        "</option>"
                );
                get_villages(
                    e.responseJSON.results.IlceyeBagliBucakListesiGetirReturn
                        .kod
                );
            }
        },
    });
}

function get_villages(township_id) {
    $("#villages").html("");
    $.ajax({
        type: "POST",
        url: "/admin/infrastructure/load",
        async: true,
        dataType: "JSON",
        data: {
            id: township_id,
            type: "village",
        },
        success: function (e) {},
        complete: function (e) {
            console.log(e);
            if (
                $.isArray(
                    e.responseJSON.results.BucagaBagliKoyListesiGetirReturn
                )
            ) {
                $("#villages").append(
                    "<option value='-1'>KASABA/KÖY SEÇİNİZ</option>"
                );
                $.each(
                    e.responseJSON.results.BucagaBagliKoyListesiGetirReturn,
                    function (index, value) {
                        var row = "";

                        row +=
                            '<option value="' +
                            value.kod +
                            '">' +
                            value.ad +
                            "</option>";
                        $("#villages").append(row);
                    }
                );
            } else {
                $("#villages").append(
                    '<option value="' +
                        e.responseJSON.results.BucagaBagliKoyListesiGetirReturn
                            .kod +
                        '">' +
                        e.responseJSON.results.BucagaBagliKoyListesiGetirReturn
                            .ad +
                        "</option>"
                );
                get_neighborhoods(
                    e.responseJSON.results.BucagaBagliKoyListesiGetirReturn.kod
                );
            }
        },
    });
}

function get_neighborhoods(village_id) {
    $("#neighborhoods").html("");
    $.ajax({
        type: "POST",
        url: "/admin/infrastructure/load",
        async: true,
        dataType: "JSON",
        data: {
            id: village_id,
            type: "neighborhood",
        },
        success: function (e) {},
        complete: function (e) {
            console.log(e);
            if (
                $.isArray(
                    e.responseJSON.results.KoyeBagliMahalleListesiGetirReturn
                )
            ) {
                $("#neighborhoods").append(
                    "<option value='-1'>MAHALLE SEÇİNİZ</option>"
                );
                $.each(
                    e.responseJSON.results.KoyeBagliMahalleListesiGetirReturn,
                    function (index, value) {
                        var row = "";

                        row +=
                            '<option value="' +
                            value.kod +
                            '">' +
                            value.ad +
                            "</option>";
                        $("#neighborhoods").append(row);
                    }
                );
            } else {
                $("#neighborhoods").append(
                    '<option value="' +
                        e.responseJSON.results
                            .KoyeBagliMahalleListesiGetirReturn.kod +
                        '">' +
                        e.responseJSON.results
                            .KoyeBagliMahalleListesiGetirReturn.ad +
                        "</option>"
                );
                get_streets(
                    e.responseJSON.results.KoyeBagliMahalleListesiGetirReturn
                        .kod
                );
            }
        },
    });
}

function get_streets(neighborhood_id) {
    $("#streets").html("");
    $.ajax({
        type: "POST",
        url: "/admin/infrastructure/load",
        async: true,
        dataType: "JSON",
        data: {
            id: neighborhood_id,
            type: "street",
        },
        success: function (e) {},
        complete: function (e) {
            console.log(e.responseJSON);
            if (
                $.isArray(
                    e.responseJSON.results.MahalleyeBagliCsbmListesiGetirReturn
                )
            ) {
                $("#streets").append(
                    "<option value='-1'>CADDE/SOKAK/BULVAR/MEYDAN SEÇİNİZ</option>"
                );
                $.each(
                    e.responseJSON.results.MahalleyeBagliCsbmListesiGetirReturn,
                    function (index, value) {
                        var row = "";

                        row +=
                            '<option value="' +
                            value.kod +
                            '">' +
                            value.ad +
                            "</option>";
                        $("#streets").append(row);
                    }
                );
            } else {
                $("#streets").append(
                    '<option value="' +
                        e.responseJSON.results
                            .MahalleyeBagliCsbmListesiGetirReturn.kod +
                        '">' +
                        e.responseJSON.results
                            .MahalleyeBagliCsbmListesiGetirReturn.ad +
                        "</option>"
                );
                get_buildings(
                    e.responseJSON.results.MahalleyeBagliCsbmListesiGetirReturn
                        .kod
                );
            }
        },
    });
}

function get_buildings(street_id) {
    $("#buildings").html("");
    $.ajax({
        type: "POST",
        url: "/admin/infrastructure/load",
        async: true,
        dataType: "JSON",
        data: {
            id: street_id,
            type: "building",
        },
        success: function (e) {},
        complete: function (e) {
            console.log(e.responseJSON);
            if (
                $.isArray(
                    e.responseJSON.results.CsbmyeBagliBinaListesiGetirReturn
                )
            ) {
                $("#buildings").append(
                    "<option value='-1'>BİNA SEÇİNİZ</option>"
                );
                $.each(
                    e.responseJSON.results.CsbmyeBagliBinaListesiGetirReturn,
                    function (index, value) {
                        var row = "";

                        row +=
                            '<option value="' +
                            value.kod +
                            '">' +
                            value.ad +
                            "</option>";
                        $("#buildings").append(row);
                    }
                );
            } else {
                $("#buildings").append(
                    '<option value="' +
                        e.responseJSON.results.CsbmyeBagliBinaListesiGetirReturn
                            .kod +
                        '">' +
                        e.responseJSON.results.CsbmyeBagliBinaListesiGetirReturn
                            .ad +
                        "</option>"
                );
                get_doors(
                    e.responseJSON.results.CsbmyeBagliBinaListesiGetirReturn.kod
                );
            }
        },
    });
}

function get_doors(building_id) {
    $("#doors").html("");
    $.ajax({
        type: "POST",
        url: "/admin/infrastructure/load",
        async: true,
        dataType: "JSON",
        data: {
            id: building_id,
            type: "door",
        },
        success: function (e) {},
        complete: function (e) {
            console.log(e.responseJSON);
            if (
                $.isArray(
                    e.responseJSON.results
                        .BinayaBagliBagimsizBolumListesiGetirReturn
                )
            ) {
                $("#doors").append("<option value='-1'>KAPI SEÇİNİZ</option>");
                $.each(
                    e.responseJSON.results
                        .BinayaBagliBagimsizBolumListesiGetirReturn,
                    function (index, value) {
                        var row = "";

                        row +=
                            '<option value="' +
                            value.kod +
                            '">' +
                            value.ad +
                            "</option>";
                        $("#doors").append(row);
                    }
                );
            } else {
                if (
                    e.responseJSON.results
                        .BinayaBagliBagimsizBolumListesiGetirReturn.kod == 0
                ) {
                    $.alert({
                        type: "red",
                        title: "Hata",
                        content:
                            "Binada adres kodu bulunmadığı için sorgulama yapamazsınız.",
                        buttons: {
                            Kapat: function () {},
                        },
                    });
                } else {
                    $("#doors").append(
                        '<option value="' +
                            e.responseJSON.results
                                .BinayaBagliBagimsizBolumListesiGetirReturn
                                .kod +
                            '">' +
                            e.responseJSON.results
                                .BinayaBagliBagimsizBolumListesiGetirReturn.ad +
                            "</option>"
                    );
                }
            }
        },
    });
}

var columns = [
    "districts",
    "townships",
    "villages",
    "neighborhoods",
    "streets",
    "buildings",
    "doors",
];

function reset_form(id) {
    if (id >= 8) return;

    switch (id) {
        case 1:
            $("#districts").html("");
            break;
        case 2:
            $("#townships").html("");

            break;
        case 3:
            $("#villages").html("");
            break;
        case 4:
            $("#neighborhoods").html("");
            break;
        case 5:
            $("#streets").html("");
            break;
        case 6:
            $("#buildings").html("");
            break;
        case 7:
            $("#doors").html("");
            break;
    }

    reset_form(id + 1);
}

$(document).ready(function () {
    get_cities();

    $("#cities").change(function () {
        $(this).blur();
        var city_id = $(this).val();
        if (city_id != "" && city_id != "-1") {
            get_districts(city_id);
        }
        reset_form(1);
    });

    $("#districts").change(function () {
        $(this).blur();
        var district_id = $(this).val();
        if (district_id != "" && district_id != "-1") {
            get_townships(district_id);
        }
        reset_form(2);
    });

    $("#townships").change(function () {
        $(this).blur();
        var township_id = $(this).val();
        if (township_id != "" && township_id != "-1") {
            get_villages(township_id);
        }
        reset_form(3);
    });

    $("#villages").change(function () {
        $(this).blur();
        var village_id = $(this).val();
        if (village_id != "" && village_id != "-1") {
            get_neighborhoods(village_id);
        }
        reset_form(4);
    });

    $("#neighborhoods").change(function () {
        $(this).blur();
        var neighborhood_id = $(this).val();
        if (neighborhood_id != "" && neighborhood_id != "-1") {
            get_streets(neighborhood_id);
        }
        reset_form(5);
    });

    $("#streets").change(function () {
        $(this).blur();
        var street_id = $(this).val();
        if (street_id != "" && street_id != "-1") {
            get_buildings(street_id);
        }
        reset_form(6);
    });

    $("#buildings").change(function () {
        $(this).blur();
        var building_id = $(this).val();
        if (building_id != "" && building_id != "-1") {
            get_doors(building_id);
        }
        reset_form(7);
    });

    $("#doors").change(function () {
        var door_id = $(this).val();
    });
});
