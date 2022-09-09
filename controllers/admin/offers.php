<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/include/template2.inc.php";

function offers()
{
    global $mysqli;
    $columns = array("ID", "Percentuale", "Inizio", "Fine", "Prodotto", "Prezzo", "Prezzo Scontato");
    $result = $mysqli->query("SELECT offer_id, percentage, start_date, end_date, offer.product_id,product_name,price
    FROM offer JOIN product p on offer.product_id = p.product_id WHERE end_date > NOW()");

    $main = initAdmin();
    $table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/table.html");
    $table->setContent("title", "Offerte");

    foreach ($columns as $column) {
        $table->setContent("column_name", $column);
    }
    $offers_table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/offers/offers_table.html");

    while ($offers = $result->fetch_assoc()) {

        foreach ($offers as $key => $value) {
            $offers_table->setContent($key, $value);
        }
        $offers_table->setContent("discounted_price", round($offers['price'] -= ($offers['price'] * $offers['percentage'] / 100), 2));
    }

    $table->setContent("table_rows", $offers_table->get());
    $main->setContent("content", $table->get());
    $main->close();

}

function delete()
{
    global $mysqli;
    $offer_id = explode('/', $_SERVER['REQUEST_URI'])[3];
    $mysqli->query("DELETE FROM offer WHERE offer_id = {$offer_id};");
    $response = array();
    if ($mysqli->affected_rows == 1) {
        $response['success'] = "Offerta eliminata con successo.";
    } else {
        $response['error'] = "Impossibile cancellare una offer con prodotti associati.";
    }
    exit(json_encode($response));
}

function create(): void
{
    global $mysqli;
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $offer_percentage = $_POST["offer_percentage"];
        $offer_start_date = $_POST["offer_start_date"];
        $offer_end_date = $_POST["offer_end_date"];
        $offer_product_id = $_POST["product_id"];
        $response = array();
        if ($offer_product_id != "") {
            try {
                $mysqli->query("INSERT INTO offer (percentage, start_date, end_date, product_id)
            VALUES (
                    $offer_percentage, 
                    '$offer_start_date',
                    '$offer_end_date',
                    $offer_product_id);");

                if ($mysqli->affected_rows == 1) {
                    $response['success'] = "Offerta creata con successo";
                } elseif ($mysqli->affected_rows == 0) {
                    $response['warning'] = "Nessun dato modificato";
                } else {
                    $response['error'] = "Errore nella creazione dell'offerta";
                }
            } catch (Exception $e) {
                $response['error'] = "Errore nella creazione dell' offerta, controlla i campi";
            }
        } else {
            $response['error'] = "Errore nella creazione dell'offerta";
        }
        exit(json_encode($response));
    } else {
        $main = initAdmin();
        $content = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/offers/create_offer.html");
        $result = $mysqli->query("SELECT product_id, product_name FROM product");
        do {
            $products = $result->fetch_assoc();
            if ($products) {
                foreach ($products as $key => $value) {
                    $content->setContent($key, $value);
                }
            }
        } while ($products);

        $main->setContent("content", $content->get());
        $main->close();
    }
}

function offer()
{
    global $mysqli;
    $offer_id = explode('/', $_SERVER['REQUEST_URI'])[3];
    $offer = $mysqli->query("SELECT offer_id, percentage, start_date, end_date, p.product_id, p.product_name
    as offer_product_name
FROM offer JOIN product p on offer.product_id = p.product_id WHERE offer_id = $offer_id;");
    if ($offer->num_rows == 0) {
        header("Location: /admin/offers"); //No offer found, redirect to offer page
    } else {
        $offer = $offer->fetch_assoc();
        $main = initAdmin();
        $content = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/offers/edit_offer.html");
        foreach ($offer as $key => $value) {
            $content->setContent($key, $value);
        }

        $result = $mysqli->query("SELECT product_id, product_name FROM product");
        do {
            $product = $result->fetch_assoc();
            if ($product) {
                foreach ($product as $key => $value) {
                    $content->setContent($key, $value);
                }
            }
        } while ($product);
        $content->setContent("product_selected", $offer['offer_product_name']);
        $main->setContent("content", $content->get());
        $main->close();
    }
}

function edit()
{
    global $mysqli;
    $offer_id = explode("/", $_SERVER["REQUEST_URI"])[3];
    $offer_percentage = $_POST["offer_percentage"];
    $offer_start_date = $_POST["offer_start_date"];
    $offer_end_date = $_POST["offer_end_date"];
    $offer_product_id = $_POST["product_id"];

    $response = array();
    if ($offer_id != "") {
        try {
            $mysqli->query("UPDATE offer SET
                percentage = $offer_percentage,
                start_date = '$offer_start_date',
                end_date='$offer_end_date'  
                WHERE offer_id = $offer_id;");
            if ($mysqli->affected_rows == 1) {
                $response['success'] = "Offerta modificata con successo";
            } elseif ($mysqli->affected_rows == 0) {
                $response['warning'] = "Nessun dato modificato";
            } else {
                $response['error'] = "Errore nella modifica dell' offerta, controlla i campi";
            }
            if ($offer_product_id) {
                $mysqli->query("UPDATE offer SET product_id = $offer_product_id WHERE offer_id = $offer_id;");
            }
        } catch (Exception) {
            $response['error'] = "Errore nella modifica dell' offerta, controlla i campi";
        }
    } else {
        $response['error'] = "Errore nella modifica del offerta";
    }
    exit(json_encode($response));
}