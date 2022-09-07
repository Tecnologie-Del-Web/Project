<?php

function category()
{
    global $mysqli;

    $main = initUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/category.html");

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


    $oid = $mysqli->query("SELECT p.product_id, p.product_name, p.price, pi.file_name
                                                FROM product p JOIN product_image pi ON (pi.product_id = p.product_id)
                                                WHERE p.quantity_available > 0 AND p.category_id = $id
                                                AND pi.type = 'main'
                                                ORDER BY p.product_name");

    if ($oid->num_rows == 0) {
        $body->setContent("products", '
            <div class="content-title-section" style="margin: 100px 0 !important;">
                <h3 class="title title-center mb-3">Nessun articolo in questa categoria</h3>
            </div>
        ');
    }
    else {
        $products = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/category/category-products.html");
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
