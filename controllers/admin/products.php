<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/template2.inc.php";

function products(): void
{
    global $mysqli;
    $columns = array("ID", "Nome", "Prezzo", "Disponibilità", "Descrizione", "Brand", "Categoria");
    $result = $mysqli->query("SELECT product.product_id, product_name, price, quantity_available, 
       product_description, b.brand_name as brand,c.category_name as category FROM product 
            JOIN brand b on b.brand_id = product.brand_id 
           JOIN category c on product.category_id = c.category_id");

    $main = initAdmin();
    $table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/table.html");
    $table->setContent("title", "Prodotti");
    foreach ($columns as $column) {
        $table->setContent("column_name", $column);
    }
    $products_table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/products/products_table.html");

    while ($products = $result->fetch_assoc()) {
        foreach ($products as $key => $value) {
            $products_table->setContent($key, $value);
        }
    }
    $table->setContent("table_rows", $products_table->get());
    $main->setContent("content", $table->get());
    $main->close();
}