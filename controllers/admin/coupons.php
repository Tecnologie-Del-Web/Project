<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/include/template2.inc.php";

function coupons()
{
    global $mysqli;
    $columns = array("ID", "Codice", "Percentuale", "Inizio", "Fine");
    $result = $mysqli->query("SELECT coupon_id, coupon_code, percentage, start_date, expiration_date, description FROM coupon");

    $main = initAdmin();
    $table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/table.html");
    $table->setContent("title", "Coupon");
    foreach ($columns as $column) {
        $table->setContent("column_name", $column);
    }
    $coupons_table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/coupons/coupons_table.html");

    while ($coupons = $result->fetch_assoc()) {
        foreach ($coupons as $key => $value) {
            $coupons_table->setContent($key, $value);
        }
    }
    $table->setContent("table_rows", $coupons_table->get());
    $main->setContent("content", $table->get());
    $main->close();

}

