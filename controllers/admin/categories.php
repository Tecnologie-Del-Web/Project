<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/template2.inc.php";

function categories()
{
    global $mysqli;
    $columns = array("ID", "Nome", "Descrizione");
    $result = $mysqli->query("SELECT category_id as id, category_name as name, category_description as description FROM category");

    $main = initAdmin();
    $table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/table.html");
    $table->setContent("title", "Categorie");
    foreach ($columns as $column) {
        $table->setContent("column_name", $column);
    }
    $categories_table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/categories/categories_table.html");

    while ($categories = $result->fetch_assoc()) {
        foreach ($categories as $key => $value) {
            $categories_table->setContent($key, $value);
        }
    }
    $table->setContent("table_rows", $categories_table->get());
    $main->setContent("content", $table->get());
    $main->close();

}

