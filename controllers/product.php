<?php

function product()
{
    // TODO: gestire opportunamente!

    global $mysqli;

    $main = setupUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/product-default.html");

    $id = explode('/', $_SERVER['REQUEST_URI'])[2];

    $product = $mysqli->query("SELECT p.product_id, p.product_name, p.price
                                    FROM product p
                                    WHERE p.product_id = $id;");

    if ($product->num_rows == 0) {
        // TODO: gestire!
        echo "\n" . "Ricordati di gestire questo caso!";
        // header("Location: /products");
    } else {
        $product = $product->fetch_assoc();
        foreach ($product as $key => $value) {
            $body->setContent($key, $value);
        }
    }

    $main->setContent("content", $body->get());
    $main->close();
}
