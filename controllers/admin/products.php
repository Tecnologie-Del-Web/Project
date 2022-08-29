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

function create(){
    global $mysqli;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "POST";
        /*$nome = $_POST["nome"];
        $prezzo = $_POST["prezzo"];
        $dimensione = $_POST["dimensione"];
        $quantita_disponibile = $_POST["quantita_disponibile"];
        $descrizione = $_POST["descrizione"];
        $categoria = $_POST["categoria"];
        $produttore = $_POST["produttore"];
        $provenienza = $_POST["provenienza"];
        $response = array();
        if ($nome !== "" && $prezzo !== "" && $dimensione !== "" && $quantita_disponibile !== "" && $descrizione !== "" && $categoria !== "" && $produttore !== "" && $provenienza !== "") {
            if ($mysqli->affected_rows == 1) {
                $id = $mysqli->insert_id;
                foreach ($_FILES["immagini"]["tmp_name"] as $key => $value) {
                    $filename = basename($value). "." . substr($_FILES["immagini"]["name"][$key], strpos($_FILES["immagini"]["name"][$key], ".") + 1);
                    move_uploaded_file($value, $_SERVER['DOCUMENT_ROOT'] . "/uploads/".$filename);
                }
                $response['success'] = "Prodotto creato con successo";
            } elseif ($mysqli->affected_rows == 0) {
                $response['warning'] = "Nessun dato modificato";
            } else {
                $response['error'] = "Errore nella creazione del prodotto";
            }
        } else {
            $response['error'] = "Errore nella creazione del prodotto";
        }
        exit(json_encode($response));*/
    } else {
        $main = initAdmin();
        $create = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/products/create_product.html");
        populateSelectFields($mysqli, $create);
        $main->setContent("content", $create->get());
        $main->close();
    }
}

function populateSelectFields(mysqli $mysqli, Template $template): void {
    $oid = $mysqli->query("SELECT id as produttore_id, ragione_sociale as produttore FROM tdw_ecommerce.produttori ORDER BY ragione_sociale");
    do {
        $produttori = $oid->fetch_assoc();
        if ($produttori) {
            foreach ($produttori as $key => $value) {
                $template->setContent($key, $value);
            }
        }
    } while ($produttori);
    $oid = $mysqli->query("SELECT id as categoria_id, nome as categoria FROM tdw_ecommerce.categorie ORDER BY nome");
    do {
        $categorie = $oid->fetch_assoc();
        if ($categorie) {
            foreach ($categorie as $key => $value) {
                $template->setContent($key, $value);
            }
        }
    } while ($categorie);
    $oid = $mysqli->query("SELECT id as provenienza_id, nazione, regione FROM tdw_ecommerce.provenienze ORDER BY nazione, regione");
    do {
        $provenienze = $oid->fetch_assoc();
        if ($provenienze) {
            foreach ($provenienze as $key => $value) {
                $template->setContent($key, $value);
            }
        }
    } while ($provenienze);
}