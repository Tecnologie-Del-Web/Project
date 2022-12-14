<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/template2.inc.php";

function orders(): void
{
    global $mysqli;
    $columns = array("ID", "Codice", "Data", "Totale", "Stato", "Utente", "Coupon");
    $result = $mysqli->query("SELECT order_id, order_code, updated_at, total, progress_status, u.username as username, c.coupon_code
    FROM `order` JOIN user u on u.user_id = `order`.user_id LEFT JOIN coupon c on c.coupon_id = `order`.coupon_id;");

    $main = initAdmin();
    $table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/table.html");
    $table->setContent("title", "Ordini");
    foreach ($columns as $column) {
        $table->setContent("column_name", $column);
    }
    $orders_table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/orders/orders_table.html");

    while ($orders = $result->fetch_assoc()) {
        foreach ($orders as $key => $value) {
            $orders_table->setContent($key, $value);
        }
    }
    $table->setContent("table_rows", $orders_table->get());
    $main->setContent("content", $table->get());
    $main->close();
}

function order()
{
    global $mysqli;
    $order_id = explode('/', $_SERVER['REQUEST_URI'])[3];
    $main = initAdmin();
    $content = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/orders/order_overview.html");
    $order = $mysqli->query("SELECT 
        o.order_id, 
        o.order_code, 
        o.updated_at, 
        o.total, 
        o.progress_status, 
        o.coupon_id,
        u.name,
        u.surname,
        u.email_address,
        u.phone_number,
        city, 
        address, 
        province, 
        country, 
        postal_code,
        c.coupon_code
        FROM `order` as o
        JOIN user u on o.user_id = u.user_id 
        LEFT JOIN payment_method pm on o.payment_id = pm.payment_id
        LEFT JOIN coupon c on o.coupon_id = c.coupon_id
        LEFT JOIN shipment_address sa on u.user_id = sa.user_id
        WHERE o.order_id=$order_id;");

    $order = $order->fetch_assoc();
    foreach ($order as $key => $value) {
        $content->setContent($key, $value);
    }
    $result = $mysqli->query(
        "SELECT p.product_name, quantity, p.price FROM order_product JOIN product p on p.product_id = order_product.product_id WHERE order_id=$order_id");

    $column_names = array(
        "Nome",
        "Quantit??",
        "Prezzo listino",
    );
    foreach ($column_names as $value) {
        $content->setContent("column_name", $value);
    }
    do {
        $product = $result->fetch_assoc();
        if ($product) {
            foreach ($product as $key => $value) {
                $content->setContent($key, $value);
            }
        }
    } while ($product);

    $main->setContent("content", $content->get());
    $main->close();
}

function edit(){
        global $mysqli;
        $order_id = explode("/", $_SERVER["REQUEST_URI"])[3];
        $progress_status = $_POST["progress_status"];
        if ($order_id != "" && $progress_status != "") {
            $response = array();
            $mysqli->query("UPDATE `order` SET
                progress_status='$progress_status' WHERE order_id=$order_id");

            if ($mysqli->affected_rows == 1) {
                $response['success'] = "Ordine modificato con successo";
            } elseif ($mysqli->affected_rows == 0) {
                $response['warning'] = "Nessun dato modificato";
            } else {
                $response['error'] = "Errore nella modifica dell'ordine";
            }
        } else {
            $response['error'] = "Errore nella modifica dell'ordine'";
        }
        exit(json_encode($response));
}