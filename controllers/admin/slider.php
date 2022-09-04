<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/template2.inc.php";

function slider()
{
    global $mysqli;
    $columns = array("ID", "Immagine", "Nome", "Descrizione");
    $result = $mysqli->query("SELECT slider_id, background_image, front_image, subtitle, title, top_title, href FROM slider");

    $main = initAdmin();
    $table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/table.html");
    $table->setContent("title", "Slider");
    foreach ($columns as $column) {
        $table->setContent("column_name", $column);
    }
    $categories_table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/customization/slider_table.html");

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
    $slider_id = explode('/', $_SERVER['REQUEST_URI'])[3];
    $mysqli->query("DELETE FROM slider WHERE slider_id = {$slider_id} AND 
                           slider_id NOT IN (SELECT product.slider_id FROM product)");
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
        $slider_name = $_POST["slider_name"];
        $slider_description = $_POST["slider_description"];
        $response = array();
        if ($slider_name != "") {
            try {
                if (isset($_FILES["slider_image"])) {
                    $slider_image = $_FILES["slider_image"];
                    foreach ($slider_image["tmp_name"] as $key => $value) {
                        $filename = $slider_image["name"][$key];
                        move_uploaded_file($value, $_SERVER['DOCUMENT_ROOT'] . "/images/slider/" . $filename);
                        $mysqli->query("INSERT INTO slider (slider_name,slider_description, slider_image)
            VALUES ('" . $slider_name . "', '" . $slider_description . "', '" . $filename . "');");
                    }
                }
                if ($mysqli->affected_rows == 1) {
                    $response['success'] = "Categoria " . $slider_name . " creata con successo";
                } elseif ($mysqli->affected_rows == 0) {
                    $response['warning'] = "Nessun dato modificato";
                } else {
                    $response['error'] = "Errore nella creazione della categoria";
                }
            } catch (Exception $e) {
                $response['error'] = $e . "Errore nella creazione della categoria";
            }
        } else {
            $response['error'] = "Errore nella creazione della categoria";
        }
        exit(json_encode($response));
    } else {
        $main = initAdmin();
        $create = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/customization/create_slider.html");
        $main->setContent("content", $create->get());
        $main->close();
    }
}
/*
function slider()
{
    global $mysqli;
    $slider_id = explode('/', $_SERVER['REQUEST_URI'])[3];
    $slider = $mysqli->query("SELECT * FROM slider WHERE slider_id = $slider_id");
    if ($slider->num_rows == 0) {
        header("Location: /admin/slider"); //No slider found, redirect to slider page
    } else {
        $slider = $slider->fetch_assoc();
        $main = initAdmin();
        $content = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/slider/edit_slider.html");
        foreach ($slider as $key => $value) {
            $content->setContent($key, $value);
        }
        $main->setContent("content", $content->get());
        $main->close();
    }
}*/

function edit()
{
    global $mysqli;
    $slider_id = explode("/", $_SERVER["REQUEST_URI"])[3];
    $slider_name = $_POST["slider_name"];
    $slider_description = $_POST["slider_description"];
    $response = array();
    if ($slider_id != "" && $slider_name != "") {
        if (isset($_FILES["slider_image"])) {
            $slider_image = $_FILES["slider_image"];
            foreach ($slider_image["tmp_name"] as $key => $value) {
                $filename = $slider_image["name"][$key];

                $mysqli->query("UPDATE slider SET
                slider_name = '$slider_name', 
                slider_description = '$slider_description',
                slider_image = '$filename'
        
                WHERE slider_id = $slider_id");

                if (!file_exists("/images/slider/" . $filename)) {
                    move_uploaded_file($value, $_SERVER['DOCUMENT_ROOT'] . "/images/slider/" . $filename);
                }

            }
        } else {
            $mysqli->query("UPDATE slider SET
                slider_name = '$slider_name', 
                slider_description = '$slider_description'
                WHERE slider_id = $slider_id");
        }

        if ($mysqli->affected_rows == 1) {
            $response['success'] = "Categoria {$slider_name} modificata con successo";
        } elseif ($mysqli->affected_rows == 0) {
            $response['warning'] = "Nessun dato modificato";
        } else {
            $response['error'] = "Errore nella modifica della categoria";
        }
    } else {
        $response['error'] = "Errore nella modifica della categoria";
    }
    exit(json_encode($response));
}