$(document).ready(() => {

    const product_id = parseInt($("#product-id").text());

    calculateSubTotal();

    $("#add-to-cart-button").click(() => {
        addToCart(product_id);
    });

    $("#clear-cart-button").click(() => {
        clearCart();
    });

    $("#submit-review-button").click(() => {
        let rating = parseInt($("#rating").val());
        let review = $("#review").val();

        if (rating !== 0) {
            addReview(product_id, rating, review);
        } else {
            addAlert('error', $('#msg'), 'Per favore, compila tutti i campi!');
        }
    });

})

function calculateSubTotal() {
    let subtotal = 0;
    $(".subtotal").each((i, obj) => {
       subtotal += parseFloat(obj.innerHTML);
    });
    $("#subtotal").text(subtotal.toFixed(2));
}

function addToCart(product_id) {
    $.ajax({
        type: 'POST',
        url: '/cart/add',
        data: {
            'product_id': product_id
        },
        success: function (data) {
            let response = JSON.parse(data);
            if (response['success']) {
                window.location.href = '/cart';
            }
        }
    });
}

function clearCart() {
    $.ajax({
        type: "POST",
        url: "/cart/clear",
        success: (data) => {
            let response = JSON.parse(data);
            if (response['success']) {
                window.location.reload();
            }
        }
    });
}

function addReview(product_id, rating, review) {
    $.ajax({
        type: "POST",
        url: "/reviews/add",
        data: {
            rating: rating,
            review: review,
            product_id: product_id
        },
        success: (data) => {
            let response = JSON.parse(data);
            if (response['success']) {
                window.location.reload();
            }
        }
    });
}