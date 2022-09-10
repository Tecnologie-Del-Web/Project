<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/template2.inc.php";

function categories()
{
    global $mysqli;
    $columns = array("ID", "Immagine", "Nome", "Descrizione");
    $result = $mysqli->query("SELECT category_id, category_image, category_name, category_description FROM category");

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


function delete()
{
    global $mysqli;
    $category_id = explode('/', $_SERVER['REQUEST_URI'])[3];
    $mysqli->query("DELETE FROM category WHERE category_id = {$category_id} AND 
                           category_id NOT IN (SELECT product.category_id FROM product);");
    $response = array();
    if ($mysqli->affected_rows == 1) {
        $response['success'] = "Coupon eliminata con successo.";
    } else {
        $response['error'] = "Impossibile cancellare una coupon con prodotti associati.";
    }
    exit(json_encode($response));
}

function create()
{
    global $mysqli;
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $category_name = $_POST["category_name"];
        $category_description = $_POST["category_description"];
        $response = array();
        if ($category_name != "") {
            try {
                if (isset($_FILES["category_image"])) {
                    $category_image = $_FILES["category_image"];
                    foreach ($category_image["tmp_name"] as $key => $value) {
                        $filename = $category_image["name"][$key];
                        move_uploaded_file($value, $_SERVER['DOCUMENT_ROOT'] . "/images/categories/" . $filename);
                        $mysqli->query("INSERT INTO category (category_name,category_description, category_image)
            VALUES ('" . $category_name . "', '" . $category_description . "', '" . $filename . "');");
                    }
                }
                if ($mysqli->affected_rows == 1) {
                    $response['success'] = "Coupon " . $category_name . " creata con successo";
                } elseif ($mysqli->affected_rows == 0) {
                    $response['warning'] = "Nessun dato modificato";
                } else {
                    $response['error'] = "Errore nella creazione della coupon";
                }
            } catch (Exception $e) {
                $response['error'] = $e . "Errore nella creazione della coupon";
            }
        } else {
            $response['error'] = "Errore nella creazione della coupon";
        }
        exit(json_encode($response));
    } else {
        $main = initAdmin();
        $create = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/categories/create_category.html");
        $main->setContent("content", $create->get());
        $main->close();
    }
}

function category()
{
    global $mysqli;
    $category_id = explode('/', $_SERVER['REQUEST_URI'])[3];
    $category = $mysqli->query("SELECT * FROM category WHERE category_id = $category_id");
    if ($category->num_rows == 0) {
        header("Location: /admin/categories"); //No category found, redirect to category page
    } else {
        $category = $category->fetch_assoc();
        $main = initAdmin();
        $content = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/categories/edit_category.html");
        foreach ($category as $key => $value) {
            $content->setContent($key, $value);
        }
        $main->setContent("content", $content->get());
        $main->close();
    }
}

function edit()
{
    global $mysqli;
    $category_id = explode("/", $_SERVER["REQUEST_URI"])[3];
    $category_name = $_POST["category_name"];
    $category_description = $_POST["category_description"];
    $response = array();
    if ($category_id != "" && $category_name != "") {
        if (isset($_FILES["category_image"])) {
            $category_image = $_FILES["category_image"];
            foreach ($category_image["tmp_name"] as $key => $value) {
                $filename = $category_image["name"][$key];

                $mysqli->query("UPDATE category SET
                category_name = '$category_name', 
                category_description = '$category_description',
                category_image = '$filename'
                WHERE category_id = $category_id");

                if (!file_exists("/images/categories/" . $filename)) {
                    move_uploaded_file($value, $_SERVER['DOCUMENT_ROOT'] . "/images/categories/" . $filename);
                }

            }
        } else {
            $mysqli->query("UPDATE category SET
                category_name = '$category_name', 
                category_description = '$category_description'
                WHERE category_id = $category_id");
        }

        if ($mysqli->affected_rows == 1) {
            $response['success'] = "Coupon {$category_name} modificata con successo";
        } elseif ($mysqli->affected_rows == 0) {
            $response['warning'] = "Nessun dato modificato";
        } else {
            $response['error'] = "Errore nella modifica della coupon";
        }
    } else {
        $response['error'] = "Errore nella modifica della categoria";
    }
    exit(json_encode($response));
}