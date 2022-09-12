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
        $product_name = $_POST["product_name"];
        $product_description = $_POST["description"];
        $product_price = $_POST["price"];
        $quantity_available = $_POST["quantity_available"];
        $product_sku = $_POST["sku"];
        $category_id = $_POST["category_id"];
        $brand_id = $_POST["brand_id"];
        $response = array();
        if ($product_name != "") {
            try {
                $mysqli->query("INSERT INTO product (product_name, price, quantity_available, product_description, sku, brand_id, category_id) 
                VALUES (
                    '" . mysqli_real_escape_string($mysqli, $product_name) . "',
                   $product_price,
                   $quantity_available,                    
                   '" . mysqli_real_escape_string($mysqli, $product_description) . "',
                   '$product_sku',
                   $brand_id,
                   $category_id);");
                $product_id = $mysqli->insert_id;
                if (isset($_FILES["product_images"])) {
                    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/images/products/$product_id")) {
                        mkdir($_SERVER['DOCUMENT_ROOT'] . "/images/products/$product_id", 7777, true);
                    }

                    $product_images = $_FILES["product_images"];
                    foreach ($product_images["tmp_name"] as $key => $value) {
                        $filename = $product_images["name"][$key];
                        try {
                            if ($mysqli->query("SELECT type FROM product_image WHERE product_id=$product_id AND type='main';")->num_rows == 0) {
                                $mysqli->query("INSERT INTO product_image(file_name, type, product_id) VALUES ('$filename','main', $product_id)");
                            } else {
                                $mysqli->query("INSERT INTO product_image(file_name, type, product_id) VALUES ('$filename','standard',$product_id)");
                            }
                            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/images/products/$product_id/$filename")) {
                                move_uploaded_file($value, $_SERVER['DOCUMENT_ROOT'] . "/images/products/$product_id/$filename");
                            }
                        } catch (Exception) {
                        }
                    }
                }
                $response['success'] = "Prodotto $product_name creato con successo";
            } catch (Exception $e) {
                $response['error'] = "Errore nella creazione del prodotto " . $product_name;
            }
        } else {
            $response['error'] = "Errore nella creazione del prodotto" . $product_name;
        }
        exit(json_encode($response));
    } else {
        $main = initAdmin();
        $content = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/products/create_product.html");
        populateSelectFields($mysqli, $content);
        $main->setContent("content", $content->get());
        $main->close();
    }
}

function product()
{
    global $mysqli;
    $id = strtok(explode('/', $_SERVER['REQUEST_URI'])[3], '?');
    $product = $mysqli->query("SELECT product_id, 
       product_name, 
       price,
       quantity_available, 
       product_description, 
       sku, 
       b.brand_name,
       c.category_name 
       FROM product JOIN category c on product.category_id = c.category_id
       JOIN brand b on product.brand_id = b.brand_id WHERE product_id=$id");
    if ($product->num_rows === 0) {
        header("Location: /admin/products"); //No product found, redirect to offer page
    } else {
        $product = $product->fetch_assoc();
        $main = initAdmin();
        $content = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/products/edit_product.html");

        populateSelectFields($mysqli, $content);

        foreach ($product as $key => $value) {
            $content->setContent($key, $value);
        }
        $content->setContent("category_selected", $product['category_name']);
        $content->setContent("brand_selected", $product['brand_name']);

        $result = $mysqli->query("SELECT image_id, file_name FROM product_image WHERE product_id = $id LIMIT 1");
        $content->setContent("contains_images", $result->fetch_assoc());

        $result = $mysqli->query("SELECT image_id, file_name FROM product_image WHERE product_id = $id ORDER BY image_id DESC;");
        if ($result->num_rows > 0) {
            while ($images = $result->fetch_assoc()) {
                foreach ($images as $key => $value) {
                    $content->setContent($key, $value);
                }
            }
        }
        $main->setContent("content", $content->get());
        $main->close();
    }
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
    $category_id = $_POST["category_id"];
    $brand_id = $_POST["brand_id"];
    $response = array();
    if ($product_id != "" && $product_name != "") {
        $mysqli->query("DELETE FROM product_image WHERE product_id=$product_id");

        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/images/products/$product_id")) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . "/images/products/$product_id", 7777, true);
        }

        if (isset($_FILES["product_images"])) {
            $product_images = $_FILES["product_images"];
            foreach ($product_images["tmp_name"] as $key => $value) {
                $filename = $product_images["name"][$key];
                try {
                    if ($mysqli->query("SELECT type FROM product_image WHERE product_id=$product_id AND type='main';")->num_rows == 0) {
                        $mysqli->query("INSERT INTO product_image(file_name, type, product_id) VALUES ('$filename','main', $product_id)");
                    } else {
                        $mysqli->query("INSERT INTO product_image(file_name, type, product_id) VALUES ('$filename','standard',$product_id)");
                    }

                    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/images/products/$product_id/$filename")) {
                        move_uploaded_file($value, $_SERVER['DOCUMENT_ROOT'] . "/images/products/$product_id/$filename");
                    }
                } catch (Exception) {
                }
            }
        }
        if (isset($_POST["uploaded_images"])) {
            foreach ($_POST["uploaded_images"] as $value) {
                $filename = $value;
                try {
                    if ($mysqli->query("SELECT type FROM product_image WHERE product_id=$product_id AND type='main';")->num_rows == 0) {
                        $mysqli->query("INSERT INTO product_image(file_name, type, product_id) VALUES ('$filename','main', $product_id)");
                    } else {
                        $mysqli->query("INSERT INTO product_image(file_name, type, product_id) VALUES ('$filename','standard',$product_id)");
                    }

                    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/images/products/$product_id/$filename")) {
                        move_uploaded_file($value, $_SERVER['DOCUMENT_ROOT'] . "/images/products/$product_id/$filename");
                    }
                } catch (Exception) {
                }
            }
        }
        try {
            $mysqli->query("UPDATE product SET 
                   product_name = '" . mysqli_real_escape_string($mysqli, $product_name) . "',
                   product_description = '" . mysqli_real_escape_string($mysqli, $product_description) . "',
                   price = $product_price,
                   quantity_available = $quantity_available,
                   sku = '$product_sku'
                   WHERE product_id = $product_id;");
            if ($category_id != "") {
                $mysqli->query("UPDATE product SET category_id = $category_id WHERE product_id = $product_id;");
            }
            if ($brand_id != "") {
                $mysqli->query("UPDATE product SET brand_id = $brand_id WHERE product_id = $product_id;");
            }
            $response['success'] = "Prodotto $product_name modificato con successo";
        } catch (Exception $e) {
            $response['error'] = "Errore nella modifica del prodotto, nome duplicato: " . $product_name;
        }
    } else {
        $response['error'] = "Errore nella modifica del prodotto" . $product_name;
    }
    exit(json_encode($response));
}

function populateSelectFields(mysqli $mysqli, Template $template): void
{
    $result = $mysqli->query("SELECT category_id, category_name FROM category");
    do {
        $categories = $result->fetch_assoc();
        if ($categories) {
            foreach ($categories as $key => $value) {
                $template->setContent($key, $value);
            }
        }
    } while ($categories);
    $result = $mysqli->query("SELECT brand_id, brand_name FROM brand");
    do {
        $brands = $result->fetch_assoc();
        if ($brands) {
            foreach ($brands as $key => $value) {
                $template->setContent($key, $value);
            }
        }
    } while ($brands);
}

function delete()
{
    global $mysqli;
    $product_id = explode('/', $_SERVER['REQUEST_URI'])[3];
    $mysqli->query("DELETE FROM product WHERE product_id = $product_id AND product_id NOT IN (SELECT product_id FROM order_product);");
    $response = array();
    if ($mysqli->affected_rows == 1) {
        $response['success'] = "Prodotto eliminato con successo.";
    } else {
        $response['error'] = "Impossibile cancellare il prodotto.";
    }
    exit(json_encode($response));
}