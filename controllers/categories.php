<?php

function categories()
{

    global $mysqli;

    $main = setupUser(false);
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

    // Estraggo le informazioni sulle categorie di cui ho bisogno
    $oid = $mysqli->query("SELECT b.brand_name, b.brand_image
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


    $main->setContent("content", $body->get());
    $main->close();
}