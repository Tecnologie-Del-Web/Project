<?php

function categories()
{

    global $mysqli;

    $main = setupUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/shop-banner-sidebar.html");

    $oid = $mysqli->query("SELECT c.category_id, c.category_name, c.category_description
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

    $oid = $mysqli->query("SELECT b.brand_name, b.brand_name
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