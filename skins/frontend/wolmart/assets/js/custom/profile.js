$(document).ready(() => {
    $(".remove-address-button").click((event) => {
        removeAddress(parseInt(event.target.id.split("-")[1]));
    });
});

function removeAddress(addressId) {
    $.ajax({
        type: "POST",
        url: "/address/remove",
        data: {
            address_id: addressId
        },
        success: (data) => {
            let response = JSON.parse(data);
            if (response['success']) {
                window.location.reload();
            }
        }
    });
}