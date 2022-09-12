$(document).ready(() => {

    let reload = ($("#method-select").length + $("#address-select").length) < 2;

    let oldTotal = parseFloat($("#total-price").text());
    let couponCode = ''

    $("#place-order").click(() => {
        let selectedMethodId = parseInt($("#method-select").val());
        let selectedAddressId = parseInt($("#address-select").val());

        if (!(selectedMethodId == 0 || selectedAddressId == 0)) {
            placeOrder(selectedMethodId, selectedAddressId, couponCode);
        } else {
            window.scrollTo(0, 0);
            addAlert('error', $("#category"), "Per favore, seleziona un metodo di pagamento e un indirizzo");
        }
    });

    $("#insert-method-button").click(() => {
        let code = $("#code").val();
        let type = $("#type").val();
        let credentials = $("#credentials").val();
        let validity = $("#validity").val();
        addMethod(code, type, credentials, validity, reload);
    });

    $("#insert-address-button").click(() => {
        let address = $("#address").val();
        let city = $("#city").val();
        let province = $("#province").val();
        let country = $("#country").val();
        let postalCode = $("#postal-code").val();
        addAddress(address, city, province, country, postalCode, reload);
    });

    $("#apply-coupon-button").click(() => {
        couponCode = $("#coupon-code").val();
        applyCoupon(couponCode, oldTotal);
    });

});

function applyCoupon(couponCode, oldPrice) {
    $.ajax({
        type: "POST",
        url: "/cart/coupon/apply",
        data: {
            coupon_code: couponCode
        },
        success: (data) => {
            let response = JSON.parse(data);
            if (response['success']) {
                let newPrice = oldPrice - oldPrice * response['percentage'] / 100.00;
                newPrice = newPrice.toFixed(2);
                $("#total-price").text(newPrice);
                window.scrollTo(0, 0);
                addAlert('success', $("#category"), "Coupon aggiunto correttamente!");
            } else {
                // TODO: configurare alert
            }
        }
    });
}

function addMethod(code, type, credentials, validity, reload) {
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
                if (reload) {
                    window.location.reload();
                }
                else {
                    $('#method-select').append($('<option>', {
                        value: response['insert_id'],
                        text: code + ", " + validity
                    }));
                    $("#existing-method-a").click();
                }
            }
        }
    });
}

function addAddress(address, city, province, country, postalCode, reload) {
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
                if (reload) {
                    window.location.reload();
                }
                else {
                    $('#address-select').append($('<option>', {
                        value: response['insert_id'],
                        text: address + ", " + city + ", " + postalCode
                    }));
                    window.scrollTo(0, 300);
                    $("#existing-address-a").click();
                }
            }
        }
    });
}

function placeOrder(methodId, addressId, couponCode) {
    let total = parseFloat($("#total-price").text());
    $.ajax({
        type: "POST",
        url: "/order/place",
        data: {
            total: total,
            method_id: methodId,
            address_id: addressId,
            coupon_code: couponCode
        },
        success: (data) => {
            console.log(data);
            let response = JSON.parse(data);
            if (response['success']) {
                window.location.href = '/order?order_id=' + response['id'];
            }
        }
    });
}

// Aggiunge un alert con il messaggio passato e lo inserisce dopo il selettore parent passato.
// L'alert sparisce in automatico dopo X secondi
function addAlert(type, parent, message) {
    let color;
    switch (type) {
        case 'error':
            color = '#c02323';
            break;
        case 'success':
            color = '#23c02b';
            break;
        default:
            color = 'black';
    }

    $("body").after('' +
        '<div class="alert alert-dismissible fade show m-0 form custom-modal" role="alert" style="color:' + color + ';">'
        + '<p class="text-primary subtitle">' + message + '</p>' +
        '<button type="button" class="btn btn-primary w-100 br-sm" data-bs-dismiss="alert" aria-hidden="true" style="display: block; margin: 0 auto;" onclick="$(\'div.alert\').slideUp(200).alert(\'close\');">Ok</button>' +
        '</div>'
    );

    setTimeout(function () {
        $('div.alert').slideUp(200).alert('close');
    }, 3000);
}