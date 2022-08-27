<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/include/template2.inc.php";

function offers()
{
    global $mysqli;
    $columns = array("ID", "Percentuale", "Inizio", "Fine", "Prodotto", "Prezzo", "Prezzo Scontato");
    $result = $mysqli->query("SELECT offer_id, percentage, start_date, expiration_date, offer.product_id,product_name,price
    FROM offer JOIN product p on offer.product_id = p.product_id WHERE expiration_date> NOW()");

    $main = initAdmin();
    $table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/table.html");
    $table->setContent("title", "Offerte");

    foreach ($columns as $column) {
        $table->setContent("column_name", $column);
    }
    $offers_table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/offers/offers_table.html");

    while ($offers = $result->fetch_assoc()) {

        foreach ($offers as $key => $value) {
            $offers_table->setContent($key, $value);
        }
        $offers_table->setContent("discounted_price", $offers['price'] -= ($offers['percentage'] / 10));
    }

    $table->setContent("table_rows", $offers_table->get());
    $main->setContent("content", $table->get());
    $main->close();

}

