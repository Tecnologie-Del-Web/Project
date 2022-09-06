$(document).ready(() => {

    $("#submit-review-button").click(() => {
        let rating = parseInt($("#rating").val());
        let review = $("#review").val();

        if (rating !== 0) {
            let product_id = parseInt($("#product-id").text());
            addReview(product_id, rating, review);
        } else {
            addAlert('error', $('#msg'), 'Per favore, compila tutti i campi!');
        }
    });

})

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
            console.log(data);
            let response = JSON.parse(data);
            if (response['success']) {
                window.location.reload();
            }
        }
    });
}