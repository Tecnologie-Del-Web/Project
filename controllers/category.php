<?php

function category()
{
    global $mysqli;

    $main = setupUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/shop-fullwidth-banner.html");

    $id = explode('/', $_SERVER['REQUEST_URI'])[2];

    $category = $mysqli->query("SELECT c.category_id, c.category_name, c.category_description
                                    FROM category c
                                    WHERE c.category_id = $id;");

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

    $oid = $mysqli->query("SELECT p.product_id, p.product_name, p.price
                                                FROM product p JOIN product_category pc ON (p.product_id = pc.product_id) JOIN category c ON pc.category_id = c.category_id
                                                WHERE p.quantity_available > 0 
                                                  AND c.category_id = $id
                                                ORDER BY p.product_name");

    do {
        $product = $oid->fetch_assoc();
        if ($product) {
            foreach ($product as $key => $value) {
                $body->setContent($key, $value);
            }
        }
    } while ($product);

    $main->setContent("content", $body->get());
    $main->close();
}