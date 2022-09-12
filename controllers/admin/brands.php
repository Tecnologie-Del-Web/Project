<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/template2.inc.php";

function brands()
{
    global $mysqli;
    $columns = array("ID", "Logo", "Nome");
    $result = $mysqli->query("SELECT brand_id, brand_image, brand_name FROM brand");

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

function delete()
{
    global $mysqli;
    $brand_id = explode('/', $_SERVER['REQUEST_URI'])[3];
    $mysqli->query("DELETE FROM brand WHERE brand_id = {$brand_id} AND 
                           brand_id NOT IN (SELECT product.brand_id FROM product)");
    $response = array();
    if ($mysqli->affected_rows == 1) {
        $response['success'] = "Brand eliminato con successo.";
    } else {
        $response['error'] = "Impossibile cancellare un brand con prodotti associati.";
    }
    exit(json_encode($response));
}

function create()
{
    global $mysqli;
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $brand_name = $_POST["brand_name"];
        $response = array();
        if ($brand_name != "") {
            try {
                if (isset($_FILES["brand_image"])) {
                    $brand_image = $_FILES["brand_image"];
                    foreach ($brand_image["tmp_name"] as $key => $value) {
                        $filename = $brand_image["name"][$key];
                        move_uploaded_file($value, $_SERVER['DOCUMENT_ROOT'] . "/images/brands/" . $filename);
                        $mysqli->query("INSERT INTO brand (brand_name, brand_image)
            VALUES ('" . mysqli_real_escape_string($mysqli, $brand_name) . "', '" . $filename . "');");
                    }
                }
                if ($mysqli->affected_rows == 1) {
                    $response['success'] = "Brand " . $brand_name . " creata con successo";
                } elseif ($mysqli->affected_rows == 0) {
                    $response['warning'] = "Nessun dato modificato";
                } else {
                    $response['error'] = "Errore nella creazione del brand";
                }
            } catch (Exception $e) {
                $response['error'] = "Errore nella creazione del Brand, possibile nome duplicato";

                exit(json_encode($response));

            }
        } else {
            $response['error'] = "Errore nella creazione del brand";
        }
        exit(json_encode($response));
    } else {
        $main = initAdmin();
        $create = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/brands/create_brand.html");
        $main->setContent("content", $create->get());
        $main->close();
    }
}

function brand()
{
    global $mysqli;
    $brand_id = explode('/', $_SERVER['REQUEST_URI'])[3];
    $brand = $mysqli->query("SELECT * FROM brand WHERE brand_id = $brand_id");
    if ($brand->num_rows == 0) {
        header("Location: /admin/brands"); //No brand found, redirect to brand page
    } else {
        $brand = $brand->fetch_assoc();
        $main = initAdmin();
        $content = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/brands/edit_brand.html");
        foreach ($brand as $key => $value) {
            $content->setContent($key, $value);
        }
        $main->setContent("content", $content->get());
        $main->close();
    }
}

function edit()
{
    global $mysqli;
    $brand_id = explode("/", $_SERVER["REQUEST_URI"])[3];
    $brand_name = $_POST["brand_name"];
    $response = array();
    if ($brand_id != "" && $brand_name != "") {
        if (isset($_FILES["brand_image"])) {
            $brand_image = $_FILES["brand_image"];
            foreach ($brand_image["tmp_name"] as $key => $value) {
                $filename = $brand_image["name"][$key];
                $mysqli->query("UPDATE brand SET
                brand_name = '" . mysqli_real_escape_string($mysqli, $brand_name) . "', 
                brand_image = '$filename'
                WHERE brand_id = $brand_id");
                if (!file_exists("/images/brands/" . $filename)) {
                    move_uploaded_file($value, $_SERVER['DOCUMENT_ROOT'] . "/images/brands/" . $filename);
                }
            }
        } else {
            $mysqli->query("UPDATE brand SET
                 brand_name = '" . mysqli_real_escape_string($mysqli, $brand_name) . "'
                WHERE brand_id = $brand_id");
        }

        if ($mysqli->affected_rows == 1) {
            $response['success'] = "Brand '{$brand_name}' modificato con successo";
        } elseif ($mysqli->affected_rows == 0) {
            $response['warning'] = "Nessun dato modificato";
        } else {
            $response['error'] = "Errore nella modifica del brand";
        }
    } else {
        $response['error'] = "Errore nella modifica del brand";
    }
    exit(json_encode($response));
}