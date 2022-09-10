<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/template2.inc.php";

function sliders()
{
    global $mysqli;
    $columns = array("ID", "Top Titolo","Titolo","Sottotitolo");
    $result = $mysqli->query("SELECT slider_id, background_image, front_image, subtitle, title, top_title, redirect_url FROM slider");

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

function slider()
{
    global $mysqli;
    $slider_id = explode('/', $_SERVER['REQUEST_URI'])[3];
    $slider = $mysqli->query("SELECT slider_id, background_image, front_image, subtitle, title, top_title, redirect_url FROM slider WHERE slider_id = $slider_id");
    if ($slider->num_rows == 0) {
        header("Location: /admin/sliders"); //No slider found, redirect to category page
    } else {
        $slider = $slider->fetch_assoc();
        $main = initAdmin();
        $content = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/customization/edit_slider.html");
        foreach ($slider as $key => $value) {
            $content->setContent($key, $value);
        }
        $main->setContent("content", $content->get());
        $main->close();
    }
}

function delete()
{
    global $mysqli;
    $slider_id = explode('/', $_SERVER['REQUEST_URI'])[3];
    $mysqli->query("DELETE FROM slider WHERE slider_id = {$slider_id}");
    $response = array();
    if ($mysqli->affected_rows == 1) {
        $response['success'] = "Slider eliminato con successo.";
    } else {
        $response['error'] = "Impossibile cancellare lo slider.";
    }
    exit(json_encode($response));
}

function create()
{
    global $mysqli;
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $title = $_POST["title"];
        $top_title = $_POST["top_title"];
        $subtitle = $_POST["subtitle"];
        $redirect_url = $_POST["redirect_url"];
        $response = array();
        if (isset($_FILES["front_image"]) && isset($_FILES["background_image"])) {
            $front_image = $_FILES["front_image"];
            $front_image_filename = $front_image["name"];

            $background_image = $_FILES["background_image"];
            $background_image_filename = $background_image["name"];

            $mysqli->query("INSERT INTO slider(background_image, front_image, subtitle, title, top_title, redirect_url) 
VALUES ('$background_image_filename','$front_image_filename','$subtitle','$title','$top_title','$redirect_url');");
            $slider_id = $mysqli->insert_id;
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/images/slider/$slider_id")) {
                mkdir($_SERVER['DOCUMENT_ROOT'] . "/images/slider/$slider_id");
            }
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/images/slider/$slider_id/$front_image_filename")) {
                move_uploaded_file($front_image["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/images/slider/$slider_id/$front_image_filename");
            }
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/images/slider/$slider_id/$background_image_filename")) {
                move_uploaded_file($background_image["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/images/slider/$slider_id/$background_image_filename");
            }


            if ($mysqli->affected_rows == 1) {
                $response['success'] = "Slider creato con successo";
            } elseif ($mysqli->affected_rows == 0) {
                $response['warning'] = "Nessun dato modificato";
            } else {
                $response['error'] = "Errore nella creazione dello slider";
            }
        }

        exit(json_encode($response));
    } else {
        $main = initAdmin();
        $create = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/customization/create_slider.html");
        $main->setContent("content", $create->get());
        $main->close();
    }
}


function edit()
{

    global $mysqli;
    $slider_id = explode("/", $_SERVER["REQUEST_URI"])[3];
    $title = $_POST["title"];
    $top_title = $_POST["top_title"];
    $subtitle = $_POST["subtitle"];
    $redirect_url = $_POST["redirect_url"];
    $response = array();

    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/images/slider/$slider_id")) {
        mkdir($_SERVER['DOCUMENT_ROOT'] . "/images/slider/$slider_id");
    }

    if (isset($_FILES["front_image"])) {
        $front_image = $_FILES["front_image"];
        $filename = $front_image["name"];
        $mysqli->query("UPDATE slider SET front_image = '${filename}' WHERE slider_id=$slider_id;");
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/images/slider/$slider_id/$filename")) {
            move_uploaded_file($front_image["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/images/slider/$slider_id/$filename");
        }
    }
    if (isset($_FILES["background_image"])) {
        $background_image = $_FILES["background_image"];
        $filename = $background_image["name"];
        $mysqli->query("UPDATE slider SET background_image = '${filename}' WHERE slider_id=$slider_id;");
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/images/slider/$slider_id/$filename")) {
            move_uploaded_file($background_image["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/images/slider/$slider_id/$filename");
        }
    }

    $mysqli->query("UPDATE slider SET title='$title', top_title='$top_title', subtitle='$subtitle', redirect_url='$redirect_url' 
              WHERE slider_id=$slider_id;");

    if ($mysqli->affected_rows >= 0) {
        $response['success'] = "Slider modificato con successo";
    } else {
        $response['error'] = "Errore nella modifica dello slider";
    }
    exit(json_encode($response));
}