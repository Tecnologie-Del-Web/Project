$(document).ready(() => {

    $("#place-order").click(() => {
        let selectedMethodId = parseInt($("#method-select").val());
        let selectedAddressId = parseInt($("#address-select").val());

        if (!(selectedMethodId == 0 || selectedAddressId == 0)) {
            window.location.href = '/order';
        }
    });

    $("#insert-method-button").click(() => {
        let code = $("#code").val();
        let type = $("#type").val();
        let credentials = $("#credentials").val();
        let validity = $("#validity").val();
        addMethod(code, type, credentials, validity);
    });

    $("#insert-address-button").click(() => {
        let address = $("#address").val();
        let city = $("#city").val();
        let province = $("#province").val();
        let country = $("#country").val();
        let postalCode = $("#postal-code").val();
        addAddress(address, city, province, country, postalCode);
    });

});

function addMethod(code, type, credentials, validity) {
    $.ajax({
        type: "POST",
        url: "/method/create",
        data: {
            code: code,
            type: type,
            credentials: credentials,
            validity: validity
        },
        success: (data) => {
            let response = JSON.parse(data);
            if (response['success']) {
                $('#method-select').append($('<option>', {
                    value: response['insert_id'],
                    text: code + ", " + validity
                }));
            }
            $("#existing-method-a").click();
        }
    });
}

function addAddress(address, city, province, country, postalCode) {
    $.ajax({
        type: "POST",
        url: "/address/create",
        data: {
            address: address,
            city: city,
            province: province,
            country: country,
            postal_code: postalCode
        },
        success: (data) => {
            let response = JSON.parse(data);
            if (response['success']) {
                $('#address-select').append($('<option>', {
                    value: response['insert_id'],
                    text: address + ", " + city + ", " + postalCode
                }));
            }
            window.scrollTo(0, 300);
            $("#existing-address-a").click();
        }
    });
}