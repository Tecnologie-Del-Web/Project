<?php

function categories()
{

    global $mysqli;

    $main = initUser(false);
    // In origine categories.html
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/categories.html");

    // Estraggo le informazioni sulle categorie di cui ho bisogno
    $oid = $mysqli->query("SELECT c.category_id, c.category_name, c.category_description, c.category_image
                                                FROM category c
                                                ORDER BY c.category_id");

    do {
        $category = $oid->fetch_assoc();
        if ($category) {
            foreach ($category as $key => $value) {
                $body->setContent($key, $value);
            }
        }
    } while ($category);

    // Estraggo le informazioni sui brand di cui ho bisogno
    $oid = $mysqli->query("SELECT b.brand_id, b.brand_name, b.brand_image
                                                FROM brand b
                                                ORDER BY b.brand_id");

    do {
        $brand = $oid->fetch_assoc();
        if ($brand) {
            foreach ($brand as $key => $value) {
                $body->setContent($key, $value);
            }
        }
    } while ($brand);

    // Estraggo le informazioni di cui ho bisogno per la visualizzazione dei prodotti (in basso nella pagina)
    $oid = $mysqli->query("SELECT p.product_id, p.product_name, p.price, c.category_id, c.category_name, pv.variant_id, pi.file_name
                                                FROM product p JOIN category c ON (p.category_id = c.category_id) JOIN product_variant pv ON (p.product_id = pv.product_id) JOIN product_image pi ON (pv.variant_id = pi.variant_id)
                                                WHERE pv.default = true AND pi.type = 'main'
                                                ORDER BY p.product_id");

    do {
        $product_category = $oid->fetch_assoc();
        if ($product_category) {
            $product_id = $product_category['product_id'];
            $reviews = $mysqli->query("SELECT ROUND(AVG(pr.rating), 2) as average_rating
                                                FROM product p JOIN product_review pr ON (pr.product_id = p.product_id)
                                                WHERE p.product_id=$product_id");
            $reviews = $reviews->fetch_assoc();
            if (!$reviews['average_rating']) {
                $body->setContent("average_rating", "ND");
            } else {
                $body->setContent("average_rating", $reviews['average_rating']);
            }
            foreach ($product_category as $key => $value) {
                if (strcmp($key,"category_id") == 0 || strcmp($key,"category_name") == 0)
                    $body->setContent("product_" . $key, $value);
                else
                    $body->setContent($key, $value);
            }
        }
    } while ($product_category);


    $main->setContent("content", $body->get());
    $main->close();
}