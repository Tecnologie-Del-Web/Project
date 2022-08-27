<?php

function product()
{
    // TODO: gestire opportunamente!

    global $mysqli;

    $main = setupUser(false);
    // In origine, product-default.html
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/product-detail.html");

    $id = explode('/', $_SERVER['REQUEST_URI'])[2];
    $brand_id = '';
    $category_id = '';


    $product = $mysqli->query("SELECT p.product_id, p.product_name, p.price, p.product_description, p.brand_id, p.category_id
                                    FROM product p
                                    WHERE p.product_id = $id;");

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

    // Prendo le informazioni sul brand di cui ho bisogno
    $brand = $mysqli->query("SELECT b.brand_name, b.brand_image
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

    // Prendo le informazioni sulle recensioni del prodotto in questione
    $review = $mysqli->query("SELECT pr.text, pr.date, pr.rating, u.username
                                    FROM product p JOIN product_review pr ON (p.product_id = pr.product_id) JOIN user u ON (pr.user_id = u.user_id)
                                    WHERE p.product_id = $id;");

    if ($review->num_rows == 0) {
        // TODO: gestire!
        echo "\n" . "Ricordati di gestire questo caso!";
        // header("Location: /products");
    } else {
        $review = $review->fetch_assoc();
        foreach ($review as $key => $value) {
            $body->setContent($key, $value);
        }
    }


    // Prodotti dello stesso brand
    $oid = $mysqli->query("SELECT p.product_id, p.product_name, p.price
                                                FROM product p
                                                WHERE p.quantity_available > 0 
                                                  AND p.brand_id = $brand_id
                                                  AND p.product_id != $id
                                                ORDER BY p.product_name
                                                LIMIT 6");

    do {
        $product = $oid->fetch_assoc();
        if ($product) {
            foreach ($product as $key => $value) {
                $body->setContent("same_brand_" . $key, $value);
            }
        }
    } while ($product);


    // "Altri prodotti", ovvero prodotti della stessa categoria, ma di un altro brand
    $oid = $mysqli->query("SELECT p.product_id, p.product_name, p.price
                                                FROM product p
                                                WHERE p.quantity_available > 0 
                                                  AND p.brand_id != $brand_id
                                                  AND p.category_id = $category_id
                                                ORDER BY p.product_name
                                                LIMIT 3");

    do {
        $product = $oid->fetch_assoc();
        if ($product) {
            foreach ($product as $key => $value) {
                $body->setContent("other_products_" . $key, $value);
            }
        }
    } while ($product);

    $main->setContent("content", $body->get());
    $main->close();
}
