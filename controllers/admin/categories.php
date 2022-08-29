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

/*function category()
{
    $main = initAdmin();
    $content = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/categories/category.html");

    $main->setContent("content", $content->get());
    $main->close();
}*/

function delete()
{
    global $mysqli;
    $category_id = explode('/', $_SERVER['REQUEST_URI'])[3];
    $mysqli->query("DELETE FROM category WHERE category_id = $category_id;");
    $response = array();
    if ($mysqli->affected_rows == 1) {
        $response['success'] = "Categoria eliminata con successo.";
    } else {
        $response['error'] = "Impossibile cancellare una categoria con prodotti associati.";
    }
    exit(json_encode($response));
}


function create(): void
{
    global $mysqli;
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $category_name = $_POST["category_name"];
        $category_description = $_POST["category_description"];
        $response = array();
        if ($category_name != "") {
            try {
                $mysqli->query("INSERT INTO category (category_name,category_description, category_image)
            VALUES ('" . $category_name . "', '" . $category_description . "', '" . $category_description . "');");
                if ($mysqli->affected_rows == 1) {
                    $response['success'] = "Categoria " . $category_name . " creata con successo";
                } elseif ($mysqli->affected_rows == 0) {
                    $response['warning'] = "Nessun dato modificato";
                } else {
                    $response['error'] = "Errore nella creazione della categoria";
                }
            } catch (Exception) {
                $response['error'] = "Errore nella creazione della categoria";
            }
        } else {
            $response['error'] = "Errore nella creazione della categoria";
        }
        exit(json_encode($response));
    } else {
        $main = initAdmin();
        $create = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/categories/create_category.html");
        $main->setContent("content", $create->get());
        $main->close();
    }
}

