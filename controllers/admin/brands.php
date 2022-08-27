<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/template2.inc.php";

function brands()
{
    global $mysqli;
    $columns = array("ID", "Nome", "Descrizione");
    $result = $mysqli->query("SELECT brand_id as id, brand_name as name, brand_description as description FROM brand");

    $main = initAdmin();
    $table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/table.html");
    $table->setContent("title", "Brand");
    foreach ($columns as $column) {
        $table->setContent("column_name", $column);
    }
    $brands_table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/brands/brands_table.html");

    while ($brands = $result->fetch_assoc()) {
        foreach ($brands as $key => $value) {
            $brands_table->setContent($key, $value);
        }
    }
    $table->setContent("table_rows", $brands_table->get());
    $main->setContent("content", $table->get());
    $main->close();

}

