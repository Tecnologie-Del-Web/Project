<?php

function category()
{
    global $mysqli;

    $main = setupUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/shop-fullwidth-banner.html");

    $id = explode('/', $_SERVER['REQUEST_URI'])[2];

    $category = $mysqli->query("SELECT c.category_id, c.category_name, c.category_description, c.category_image
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


    $oid = $mysqli->query("SELECT p.product_id, p.product_name, p.price, pv.variant_id, pi.file_name
                                                FROM product p JOIN product_variant pv ON (p.product_id = pv.product_id) JOIN product_image pi ON (pv.variant_id = pi.variant_id)
                                                WHERE p.quantity_available > 0 AND p.category_id = $id
                                                  AND (pv.default = true AND pi.type = 'main')
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
