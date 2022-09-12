$(document).ready(() => {
    let allProducts = $(".product");
    let currentProducts = $(".product");

    console.log("all products " + allProducts.length);
    console.log("current products " + currentProducts.length);

    $("#sorting-select").on('input', () => {
        let sortedProducts = sort($("#sorting-select").val(), currentProducts);
        $(".product-wrapper").empty();
        sortedProducts.forEach((item) => {
            $(".product-wrapper").append(item);
        });
    });
});

function sort(sortBy, products) {
    switch (sortBy) {
        case 'rating':
            return sortByAverageRating(products);
        case 'price-low':
            return sortByPriceLow(products);
        case 'price-high':
            return sortByPriceHigh(products);
        default:
            return sortByName(products);
    }
}

function sortByName(items) {
    return [...items].sort((a, b) => {
        return $(a).find('.product-name').text().localeCompare(($(b).find('.product-name').text()));
    });
}

function sortByAverageRating(items) {
    return [...items].sort((a, b) => {
        return parseFloat($(a).find('.review-rating').text().replace('€', '')) - parseFloat($(b).find('.review-rating').text().replace('€', ''));
    });
}

function sortByPriceLow(items) {
    return [...items].sort((a, b) => {
        return parseFloat($(a).find('.product-price').text().replace('€', '')) - parseFloat($(b).find('.product-price').text().replace('€', ''));
    });
}

function sortByPriceHigh(items) {
    return [...items].sort((a, b) => {
        return parseFloat($(b).find('.product-price').text().replace('€', '')) - parseFloat($(a).find('.product-price').text().replace('€', ''));
    });
}