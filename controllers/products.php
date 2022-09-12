<?php

function products() {

    global $mysqli;

    $main = initUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/products.html");

    $category_uri = explode( '&',(explode('?', $_SERVER['REQUEST_URI'])[1]))[0];
    $query_uri = explode( '&',(explode('?', $_SERVER['REQUEST_URI'])[1]))[1];

    $category = intval(explode( '=', $category_uri)[1]);
    $query = str_replace('+', ' ', explode( '=', $query_uri)[1]);


    if ($category == 0) {
        // Prendo tutti i prodotti compatibili
        $oid = $mysqli->query("SELECT p.product_id, p.product_name, p.price, pi.image_id, pi.file_name
                                    FROM product p JOIN product_image pi ON (pi.product_id = p.product_id)
                                    WHERE p.product_name LIKE '%$query%' AND pi.type='main';");
    }
    else {
        // Prendo solo i prodotti compatibili della categoria selezionata
        $oid = $mysqli->query("SELECT p.product_id, p.product_name, p.price, pi.image_id, pi.file_name
                                    FROM category c JOIN product p ON (p.category_id = c.category_id) JOIN product_image pi ON (p.product_id = pi.product_id)
                                    WHERE p.product_name LIKE '%$query%' AND c.category_id = $category AND pi.type='main';");
    }

    if ($oid->num_rows == 0) {
        $body->setContent("products",'
            <div class="content-title-section" style="margin: 100px 0 !important;">
                <h3 class="title title-center mb-3">Nessun articolo corrisponde alla ricerca!</h3>
            </div>
        ');
    }
    else {
        $products = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/search/search-products.html");
        do {
            $product = $oid->fetch_assoc();
            if ($product) {
                $product_id = $product['product_id'];
                $reviews = $mysqli->query("SELECT ROUND(AVG(pr.rating), 2) as average_rating
                                                FROM product p JOIN product_review pr ON (pr.product_id = p.product_id)
                                                WHERE p.product_id=$product_id");
                $reviews = $reviews->fetch_assoc();
                if (!$reviews['average_rating']) {
                    $products->setContent("average_rating", "ND");
                } else {
                    $products->setContent("average_rating", $reviews['average_rating']);
                }
                foreach ($product as $key => $value) {
                    $products->setContent($key, $value);
                }
            }
        } while ($product);
        $body->setContent("products", $products->get());
    }

    $main->setContent("content", $body->get());
    $main->close();
}
