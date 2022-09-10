$(document).ready(() => {

    $("#place-order").click(() => {
        let selectedMethodId = parseInt($("#method-select").val());
        let selectedAddressId = parseInt($("#address-select").val());

        if (selectedMethodId == 0 || selectedAddressId == 0) {
            console.log("Non posso procedere!");
        }
    });

});