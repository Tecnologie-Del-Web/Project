$(document).ready(() => {
    $(".remove-icon").click(() => {
        let productId = parseInt(event.target.id.split("-")[1]);
        removeProductFromWishlist(productId);
    });
})

function removeProductFromWishlist(productId) {
    $.ajax({
        type: "POST",
        url: "/wishlist/remove",
        data: {
            product_id: productId
        },
        success: (data) => {
            let response = JSON.parse(data);
            if (response['success']) {
                window.location.reload();
            }
        }
    });
}