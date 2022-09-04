<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/include/template2.inc.php";

function reviews()
{
    global $mysqli;
    $columns = array(
        "Username",
        "Prodotto",
        "Valutazione",
        "Commento",
        "Data",
    );
    $result = $mysqli->query("SELECT u.username as username, p.product_name as product_name, rating, text, date FROM product_review 
    JOIN product p on p.product_id = product_review.product_id
    JOIN user u on product_review.user_id = u.user_id");
    $main = initAdmin();
    $table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/table.html");
    $table->setContent("title", "Recensioni");
    foreach ($columns as $column) {
        $table->setContent("column_name", $column);
    }
    $reviews_table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/reviews/reviews_table.html");
    while ($reviews = $result->fetch_assoc()) {
        foreach ($reviews as $key => $value) {
            $reviews_table->setContent($key, $value);
        }
    }
    $table->setContent("table_rows", $reviews_table->get());
    $main->setContent("content", $table->get());
    $main->close();
}
