<?php

function product()
{
    global $mysqli;

    $main = initUser(false);

    // In origine, product-default.html
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/product-detail.html");

    $id = explode('/', $_SERVER['REQUEST_URI'])[2];
    $brand_id = '';
    $category_id = '';

    // Prendo il prodotto con l'id in esame
    $product = $mysqli->query("SELECT product_id, product_name, price, product_description, quantity_available, brand_id, category_id, sku
                                    FROM product
                                    WHERE product_id = $id");

    if ($product->num_rows == 0) {
        // TODO: gestire!
        echo "\n" . "Ricordati di gestire questo caso!";
        // header("Location: /products");
    } else {
        $product = $product->fetch_assoc();
        $brand_id = $product['brand_id'];
        $category_id = $product['category_id'];
        foreach ($product as $key => $value) {
            $body->setContent($key, $value);
        }
    }

    findImages($mysqli, $id, $body);

    findBrandInfo($mysqli, $brand_id, $body);

    findCategoryInfo($mysqli, $category_id, $body);

    findReviews($mysqli, $id, $body);

    findSameBrandProducts($mysqli, $brand_id, $id, $body);

    findOtherProducts($mysqli, $brand_id, $category_id, $body);

    setupAddToCart($mysqli, $id, $body);

    $main->setContent("content", $body->get());
    $main->close();
}


/**
 * @param mysqli $mysqli
 * @param mixed $brand_id
 * @param Template $body
 * @return void
 */
function findBrandInfo(mysqli $mysqli, mixed $brand_id, Template $body)
{
    // Prendo le informazioni sul brand di cui ho bisogno
    $brand = $mysqli->query("SELECT b.brand_id, b.brand_name, b.brand_image
                                    FROM brand b
                                    WHERE b.brand_id = $brand_id;");

    if ($brand->num_rows == 0) {
        // TODO: gestire!
        echo "\n" . "Ricordati di gestire questo caso!";
        // header("Location: /products");
    } else {
        $brand = $brand->fetch_assoc();
        foreach ($brand as $key => $value) {
            $body->setContent($key, $value);
        }
    }
}

/**
 * @param mysqli $mysqli
 * @param mixed $category_id
 * @param Template $body
 * @return void
 */
function findCategoryInfo(mysqli $mysqli, mixed $category_id, Template $body)
{
    // Prendo le informazioni sulla categoria di cui ho bisogno
    $category = $mysqli->query("SELECT c.category_id, c.category_name
                                    FROM category c
                                    WHERE c.category_id = $category_id;");

    if ($category->num_rows == 0) {
        // TODO: gestire!
        echo "\n" . "Ricordati di gestire questo caso!";
        // header("Location: /products");
    } else {
        $category = $category->fetch_assoc();
        foreach ($category as $key => $value) {
            $body->setContent($key, $value);
        }
    }
}

/**
 * @param mysqli $mysqli
 * @param string $id
 * @param Template $body
 * @return array
 */
function findImages(mysqli $mysqli, string $id, Template $body)
{

    // Prendo le immagini della variante specificata
    $oid = $mysqli->query("SELECT p.product_id, p.product_name, pi.image_id, pi.file_name
                                FROM product p JOIN product_image pi ON (pi.product_id = p.product_id)
                                WHERE p.product_id = $id");

    do {
        $image = $oid->fetch_assoc();
        if ($image) {
            foreach ($image as $key => $value) {
                $body->setContent("swiper_" . $key, $value);
                $body->setContent("thumb_" . $key, $value);
            }
        }
    } while ($image);
}

/**
 * @param mysqli $mysqli
 * @param string $id
 * @param Template $body
 * @return void
 */
function findReviews(mysqli $mysqli, string $id, Template $body)
{
    // Prendo le recensioni del prodotto in questione
    $oid = $mysqli->query("SELECT pr.text, pr.date, pr.rating, u.username
                                    FROM product p JOIN product_review pr ON (p.product_id = pr.product_id) JOIN user u ON (pr.user_id = u.user_id)
                                    WHERE p.product_id = $id;");

    if ($oid->num_rows == 0) {
        $body->setContent("reviews", '
            <div class="content-title-section" style="margin: 100px 0 !important;">
                <h3 class="sub-title title-center ml-3 mb-3">Non ci sono ancora recensioni di questo prodotto. Scrivine una!</h3>
            </div>
        ');
    } else {
        $reviews = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/detail/reviews.html");
        do {
            $review = $oid->fetch_assoc();
            if ($review) {
                foreach ($review as $key => $value) {
                    $reviews->setContent($key, $value);
                }
            }
        } while ($review);
        $body->setContent("reviews", $reviews->get());
    }

    if (isset($_SESSION['auth']) && $_SESSION['auth'] = true) {
        $user_id = $_SESSION['user']['user_id'];
        $previous_review = $mysqli->query("SELECT * FROM product_review WHERE product_id = $id AND user_id = $user_id;");

        if ($previous_review->num_rows > 0) {
            $body->setContent("add_review", '<div style="text-align: center; padding: 3rem;"><p class="font-weight-bold ml-3 mb-3">Hai già recensito questo prodotto!</p></div>');
        } else {
            $add_review = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/detail/add-review.html");
            $add_review->setContent("product_id", $id);
            $body->setContent("add_review", $add_review->get());
        }
    }
    else {
        $body->setContent("add_review",  '<div style="text-align: center; padding: 3rem;"><p class="font-weight-bold ml-3 mb-3">Entra per aggiungere una recensione di questo prodotto!</p></div>');
    }
}

/**
 * @param mysqli $mysqli
 * @param mixed $brand_id
 * @param string $id
 * @param Template $body
 * @return void
 */
function findSameBrandProducts(mysqli $mysqli, mixed $brand_id, string $id, Template $body)
{
    // Prodotti dello stesso brand
    $oid = $mysqli->query("SELECT p.product_id, p.product_name, p.price, pi.file_name
                                                FROM product p JOIN product_image pi ON (pi.product_id = p.product_id)
                                                WHERE p.quantity_available > 0 
                                                  AND p.brand_id = $brand_id
                                                  AND p.product_id != $id
                                                  AND pi.type = 'main' 
                                                ORDER BY p.product_name
                                                LIMIT 6");

    if ($oid->num_rows == 0) {
        $body->setContent("same_brand", '
            <div class="content-title-section" style="margin: 100px 0 !important;">
                <h3 class="title title-center mb-3">Non ci sono altri articoli di questo brand</h3>
            </div>
        ');
    } else {
        $same_brand = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/detail/same-brand-products.html");
        do {
            $product = $oid->fetch_assoc();
            if ($product) {
                $product_id = $product['product_id'];
                $reviews = $mysqli->query("SELECT ROUND(AVG(pr.rating), 2) as average_rating
                                                FROM product p JOIN product_review pr ON (pr.product_id = p.product_id)
                                                WHERE p.product_id=$product_id");
                $reviews = $reviews->fetch_assoc();
                if (!$reviews['average_rating']) {
                    $same_brand->setContent("average_rating", "ND");
                } else {
                    $same_brand->setContent("average_rating", $reviews['average_rating']);
                }
                foreach ($product as $key => $value) {
                    $same_brand->setContent($key, $value);
                }
            }
        } while ($product);
        $body->setContent("same_brand", $same_brand->get());
    }
}

/**
 * @param mysqli $mysqli
 * @param mixed $brand_id
 * @param mixed $category_id
 * @param Template $body
 * @return void
 */
function findOtherProducts(mysqli $mysqli, mixed $brand_id, mixed $category_id, Template $body)
{
    // "Altri prodotti", ovvero prodotti della stessa categoria, ma di un altro brand
    $oid = $mysqli->query("SELECT p.product_id, p.product_name, p.price, pi.file_name
                                                FROM product p JOIN product_image pi ON (pi.product_id = p.product_id)
                                                WHERE p.quantity_available > 0 
                                                  AND p.brand_id != $brand_id
                                                  AND p.category_id = $category_id
                                                  AND pi.type = 'main'
                                                ORDER BY p.product_name
                                                LIMIT 3");
    if ($oid->num_rows == 0) {
        $body->setContent("other_products", '
            <div class="content-title-section" style="margin: 100px 0 !important;">
                <h3 class="title title-center mb-3">Non ci sono altri articoli in questa categoria</h3>
            </div>
        ');
    } else {
        $other_products = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/detail/other-products.html");
        do {
            $product = $oid->fetch_assoc();
            if ($product) {
                $product_id = $product['product_id'];
                $reviews = $mysqli->query("SELECT ROUND(AVG(pr.rating), 2) as average_rating
                                                FROM product p JOIN product_review pr ON (pr.product_id = p.product_id)
                                                WHERE p.product_id=$product_id");
                $reviews = $reviews->fetch_assoc();
                if (!$reviews['average_rating']) {
                    $other_products->setContent("average_rating", "ND");
                } else {
                    $other_products->setContent("average_rating", $reviews['average_rating']);
                }
                foreach ($product as $key => $value) {
                    $other_products->setContent($key, $value);
                }
            }
        } while ($product);
        $body->setContent("other_products", $other_products->get());
    }
}

/**
 * @param mysqli $mysqli
 * @param string $id
 * @param Template $body
 * @return void
 */
function setupAddToCart(mysqli $mysqli, string $id, Template $body)
{
    if (isset($_SESSION['auth']) && $_SESSION['auth'] = true) {
        $body->setContent("add_to_cart", '
            <button id="add-to-wishlist-button" class="btn btn-secondary w-100 br-sm mb-2">
                <i class="w-icon-heart"></i>
                <span>Aggiungi alla Wishlist</span>
            </button>
            <button id="add-to-cart-button" class="btn btn-primary w-100 br-sm">
                <i class="w-icon-cart"></i>
                <span>Aggiungi al Carrello</span>
            </button>
        ');
    } else {
        $body->setContent("add_to_cart", '
            <div style="text-align: center; padding: 3rem; width: 100% !important;">
                <p class="font-weight-bold ml-3 mb-3"><a href="/sign-in?referrer=/product/' . $id . '">Entra</a> per aggiungere al carrello e alla wishlist!</p>
            </div>
        ');
    }
}
