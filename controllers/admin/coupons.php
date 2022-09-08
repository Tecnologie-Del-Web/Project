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

function delete()
{
    global $mysqli;
    $coupon_id = explode('/', $_SERVER['REQUEST_URI'])[3];
    $mysqli->query("DELETE FROM coupon WHERE coupon_id = {$coupon_id};");
    $response = array();
    if ($mysqli->affected_rows == 1) {
        $response['success'] = "Coupon eliminata con successo.";
    } else {
        $response['error'] = "Impossibile cancellare una coupon con prodotti associati.";
    }
    exit(json_encode($response));
}

function create(): void
{
    global $mysqli;
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $coupon_code = $_POST["coupon_code"];
        $coupon_percentage = $_POST["coupon_percentage"];
        $coupon_start_date = $_POST["coupon_start_date"];
        $coupon_end_date = $_POST["coupon_end_date"];
        $coupon_description = $_POST["coupon_description"];

        $response = array();
        if ($coupon_code != "") {
            try {
                $mysqli->query("INSERT INTO coupon 
    (coupon_code, percentage, start_date, expiration_date, description)
            VALUES ('$coupon_code', 
                    $coupon_percentage, 
                    '$coupon_start_date',
                    '$coupon_end_date',
                    '$coupon_description');");

                if ($mysqli->affected_rows == 1) {
                    $response['success'] = "Coupon " . $coupon_code . " creata con successo";
                } elseif ($mysqli->affected_rows == 0) {
                    $response['warning'] = "Nessun dato modificato";
                } else {
                    $response['error'] = "Errore nella creazione del coupon";
                }
            } catch (Exception $e) {
                $response['error'] = "Errore nella creazione del coupon";
            }
        } else {
            $response['error'] = "Errore nella creazione del coupon";
        }
        exit(json_encode($response));
    } else {
        $main = initAdmin();
        $create = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/coupons/create_coupon.html");
        $main->setContent("content", $create->get());
        $main->close();
    }
}

function coupon()
{
    global $mysqli;
    $coupon_id = explode('/', $_SERVER['REQUEST_URI'])[3];
    $coupon = $mysqli->query("SELECT * FROM coupon WHERE coupon_id = $coupon_id;");
    if ($coupon->num_rows == 0) {
        header("Location: /admin/coupons"); //No coupon found, redirect to coupon page
    } else {
        $coupon = $coupon->fetch_assoc();
        $main = initAdmin();
        $content = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/coupons/edit_coupon.html");
        foreach ($coupon as $key => $value) {
            $content->setContent($key, $value);
        }
        $main->setContent("content", $content->get());
        $main->close();
    }
}

function edit()
{
    global $mysqli;
    $coupon_id = explode("/", $_SERVER["REQUEST_URI"])[3];
    $coupon_code = $_POST["coupon_code"];
    $coupon_percentage = $_POST["coupon_percentage"];
    $coupon_start_date = $_POST["coupon_start_date"];
    $coupon_end_date = $_POST["coupon_end_date"];
    $coupon_description = $_POST["coupon_description"];

    $response = array();
    if ($coupon_id != "" && $coupon_code != "") {
        $mysqli->query("UPDATE coupon SET
                coupon_code = '$coupon_code', 
                description = '$coupon_description',
                percentage = $coupon_percentage,
                start_date = '$coupon_start_date',
                expiration_date = '$coupon_end_date'
                WHERE coupon_id = $coupon_id");

        if ($mysqli->affected_rows == 1) {
            $response['success'] = "Coupon {$coupon_code} modificata con successo";
        } elseif ($mysqli->affected_rows == 0) {
            $response['warning'] = "Nessun dato modificato";
        } else {
            $response['error'] = "Errore nella modifica del coupon";
        }
    } else {
        $response['error'] = "Errore nella modifica del coupon";
    }
    exit(json_encode($response));
}