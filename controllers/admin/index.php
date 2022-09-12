<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/include/template2.inc.php";

function admin(): void
{

    global $mysqli;

    $main = initAdmin();
    $analytics = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/analytics.html");

    $sold_products = $mysqli->query("SELECT SUM(order_product.quantity) FROM order_product;");
    $analytics->setContent("sold_products", mysqli_fetch_row($sold_products)[0]);

    $orders = $mysqli->query("SELECT COUNT(order_id) FROM `order`;");
    $analytics->setContent("orders", mysqli_fetch_row($orders)[0]);

    $earnings = $mysqli->query("SELECT SUM(quantity*price) FROM order_product;");
    $analytics->setContent("earnings", mysqli_fetch_row($earnings)[0]);

    $top_categories = $mysqli->query("SELECT category_name, 
        category_image, p.category_id,
        category_description, SUM(op.quantity) as category_qty
        FROM category 
        JOIN product p on category.category_id = p.category_id
        JOIN order_product op on p.product_id = op.product_id 
        GROUP BY p.category_id
        ORDER BY category_qty
        LIMIT 5;");

    while ($top_category = $top_categories->fetch_assoc()) {
        foreach ($top_category as $key => $value) {
            $analytics->setContent($key, $value);
        }
    }


    $top_brands = $mysqli->query("SELECT brand_name, 
        brand_image,
        SUM(op.quantity) as brand_qty
        FROM brand b
        JOIN product p ON (b.brand_id = p.brand_id)
        JOIN order_product op ON (p.product_id = op.product_id)
        GROUP BY b.brand_id
        ORDER BY brand_qty
        LIMIT 5;");

    while ($top_brand = $top_brands->fetch_assoc()) {
        foreach ($top_brand as $key => $value) {
            $analytics->setContent($key, $value);
        }
    }

    $top_products = $mysqli->query("SELECT product_name, p.product_id,
        SUM(op.quantity) as product_qty
        FROM product as p
        JOIN order_product op on p.product_id = op.product_id
        GROUP BY product_id
        ORDER BY product_qty
        LIMIT 5;");

    while ($top_product = $top_products->fetch_assoc()) {
        foreach ($top_product as $key => $value) {
            $analytics->setContent($key, $value);
        }
    }

    $main->setContent("content", $analytics->get());
    $main->close();
}
