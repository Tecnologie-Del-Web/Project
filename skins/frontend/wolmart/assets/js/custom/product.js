$(document).ready(() => {

    const product_id = parseInt($("#product-id").text());

    calculateSubTotal();

    $("#add-to-cart-button").click(() => {
        addToCart(product_id);
    });

    $("#add-to-wishlist-button").click(() => {
        addToWishlist(product_id);
    });

    $("#clear-cart-button").click(() => {
        clearCart();
    });

    $(".item-quantity-increase").click((event) => {
        let editedProductId = parseInt(event.target.id.split("-")[1]);
        changeProductQuantity(editedProductId, 1);
    });

    $(".item-quantity-decrease").click((event) => {
        let editedProductId = parseInt(event.target.id.split("-")[1]);
        changeProductQuantity(editedProductId, -1);
    });

    $(".remove-item-icon").click((event) => {
        let productToRemoveId = parseInt(event.target.id.split("-")[1]);
        removeProductFromCart(productToRemoveId);
    });

    $("#submit-review-button").click(() => {
        let rating = parseInt($("#rating").val());
        let review = $("#review").val();

        if (rating !== 0) {
            addReview(product_id, rating, review);
        } else {
            window.scrollTo(0, 0);
            addAlert('error', $("#category"), "Per favore, inserisci una valutazione!");
        }
    });

    $("#apply-coupon-button").click(() => {
        let couponCode = ($("#coupon-code-input").val());
        applyCoupon(couponCode);
    });

    let numberOfArticles = parseInt($("#number_of_articles").text());
    if (numberOfArticles == 0) {
        $('#proceed-to-checkout').removeAttr("href");
        $('#proceed-to-checkout').css("background", "grey").css("border", "1px solid grey");
    }

})

function calculateSubTotal() {
    let subtotal = 0;
    $(".item-subtotal").each((i, obj) => {
       subtotal += parseFloat(obj.innerHTML);
    });
    $("#subtotal").text(subtotal.toFixed(2));
    $("#total").text(subtotal.toFixed(2));
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

function addToWishlist(product_id) {
    $.ajax({
        type: 'POST',
        url: '/wishlist/add',
        data: {
            'product_id': product_id
        },
        success: function (data) {
            let response = JSON.parse(data);
            if (response['success']) {
                window.location.href = '/wishlist';
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

function changeProductQuantity(editedProductId, increment) {
    $.ajax({
        type: "POST",
        url: "/cart/quantity",
        data: {
            product_id: editedProductId,
            increment: increment
        },
        success: (data) => {
            console.log(data);
            let response = JSON.parse(data);
            if (response['success']) {
                window.location.reload();
            }
        }
    });
}

function removeProductFromCart(productToRemoveId) {
    $.ajax({
        type: "POST",
        url: "/cart/remove",
        data: {
            product_id: productToRemoveId
        },
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