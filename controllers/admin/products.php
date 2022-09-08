<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/template2.inc.php";

function products()
{
    global $mysqli;
    $columns = array("ID", "Immagine", "Nome", "Prezzo", "Qty", "Descrizione", "Brand", "Categoria");
    $result = $mysqli->query("SELECT p.product_id, 
        p.product_name, 
        p.price, 
        p.quantity_available, 
        p.product_description, 
        b.brand_name, 
        c.category_name,
        pi.file_name
    FROM product p
    JOIN brand b on b.brand_id = p.brand_id 
    JOIN category c on c.category_id = p.category_id
    LEFT JOIN product_image pi on p.product_id = pi.product_id GROUP BY p.product_id;"
    );
    //With left join whe allow to see also products without image
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

function create()
{
    global $mysqli;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "POST";
        /*$nome = $_POST["nome"];
        $prezzo = $_POST["prezzo"];
        $dimensione = $_POST["dimensione"];
        $quantita_disponibile = $_POST["quantita_disponibile"];
        $descrizione = $_POST["descrizione"];
        $categoria = $_POST["categoria"];
        $produttore = $_POST["brand"];
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

function product()
{
    global $mysqli;
    $id = strtok(explode('/', $_SERVER['REQUEST_URI'])[3], '?');
    $product = $mysqli->query("SELECT product_id, product_name, price, quantity_available, product_description, sku, brand_id, category_id FROM product WHERE product_id=$id");
    $main = initAdmin();
    $content = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/products/edit_product.html");
    populateSelectFields($mysqli, $main);
    foreach ($product->fetch_assoc() as $key => $value) {
        $content->setContent($key, $value);
    }
    /*$content->setContent("categoria_selected", $product['categoria_selected']);
    $content->setContent("produttore_selected", $product['produttore_selected']);
    $content->setContent("nazione_selected", $product['nazione_selected']);
    $content->setContent("regione_selected", $product['regione_selected']);*/
    $result = $mysqli->query("SELECT image_id, file_name FROM product_image WHERE product_id = $id");
    $content->setContent("images", $result->fetch_assoc());
    while ($image = $result->fetch_assoc()) {
        foreach ($image as $key => $value) {
            echo $key;
            echo $value;
            $content->setContent($key, $value);
        }
    }
    $main->setContent("content", $content->get());
    $main->close();
}

function edit()
{
    global $mysqli;
    $product_id = explode("/", $_SERVER["REQUEST_URI"])[3];
    $product_name = $_POST["product_name"];
    $product_description = $_POST["description"];
    $product_price = $_POST["price"];
    $quantity_available = $_POST["quantity_available"];
    $product_sku = $_POST["sku"];
    $uploaded_images = $_POST["uploaded_images"];
    $response = array();
    if ($product_id != "" && $product_name != "") {
        $mysqli->query("DELETE FROM product_image WHERE product_id=$product_id");
        if (isset($_FILES["product_images"])) {
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/images/products/$product_id")) {
                mkdir($_SERVER['DOCUMENT_ROOT'] . "/images/products/$product_id", 7777, true);
            }

            $product_images = $_FILES["product_images"];
            foreach ($product_images["tmp_name"] as $key => $value) {
                $filename = $product_images["name"][$key];
                try {
                    $mysqli->query("INSERT INTO product_image(file_name, type, product_id) VALUES ('$filename','main',$product_id)");
                    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/images/products/$product_id/$filename")) {
                        move_uploaded_file($value, $_SERVER['DOCUMENT_ROOT'] . "/images/products/$product_id/$filename");
                    }
                } catch (Exception) {
                }
            }

        }
        if (isset($uploaded_images)) {
            foreach ($uploaded_images as $value) {
                try {
                    $mysqli->query("INSERT INTO product_image(file_name, type, product_id) VALUES ('$value','main',$product_id)");
                } catch (Exception) {
                }
            }
        }
    } else {
        $response['error'] = "Errore nella modifica del prodotto" . $product_id . $product_name . $product_price;
    }
    exit(json_encode($response));
}

function populateSelectFields(mysqli $mysqli, Template $template): void
{
    $result = $mysqli->query("SELECT category_id, category_name FROM category ORDER BY category_name");
    do {
        $categories = $result->fetch_assoc();
        if ($categories) {
            foreach ($categories as $key => $value) {
                $template->setContent($key, $value);
            }
        }
    } while ($categories);
    $result = $mysqli->query("SELECT brand_id, brand_name FROM brand ORDER BY brand_name");
    do {
        $brands = $result->fetch_assoc();
        if ($brands) {
            foreach ($brands as $key => $value) {
                $template->setContent($key, $value);
            }
        }
    } while ($brands);
}