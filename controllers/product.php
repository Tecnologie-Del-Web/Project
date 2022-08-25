<?php

function product()
{
    // TODO: gestire opportunamente!

    global $mysqli;

    $main = setupUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/product-default.html");

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
