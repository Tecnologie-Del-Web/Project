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
    /*global $mysqli;
    $colnames = array(
        "Numero ordine",
        "Utente",
        "Data",
        "Stato",
        "Totale",
        //"Indirizzo di spedizione",
        //"Indirizzo di fatturazione"
    );
    $oid = $mysqli->query("SELECT ordini.id, u.email as utente, ordini.data, ordini.stato, ordini.totale, ordini.numero_ordine, CONCAT(isp.indirizzo,' ', isp.citta,' ', isp.cap,' ', isp.provincia,' ',isp.nazione) as indirizzo_spedizione,
        CONCAT(ifa.indirizzo,' ', ifa.citta,' ', ifa.cap,' ', ifa.provincia,' ', ifa.nazione) as indirizzo_fatturazione FROM tdw_db.ordini JOIN tdw_db.users as u on u.id=ordini.user_id JOIN tdw_db.indirizzi as isp on isp.id= ordini.indirizzi_spedizione JOIN tdw_db.indirizzi as ifa on ifa.id= ordini.indirizzi_fatturazione");
    $main = setupMainAdmin();
    // Creazione del contenuto
    $crud = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/views/crud.html");
    $table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/components/table.html");
    $table->setContent("title", "Orders");
    foreach ($colnames as $value) {
        $table->setContent("colname", $value);
    }
    $ordini_table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/orders/orders.html");
    do {
        $ordini = $oid->fetch_assoc();
        if ($ordini) {
            foreach ($ordini as $key => $value) {
                $ordini_table->setContent($key, $value);
            }
        }
    } while ($ordini);
    $table->setContent("sptable", $ordini_table->get());
    $crud->setContent("table", $table->get());
    $main->setContent("content", $crud->get());
    $main->close();*/
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
        "Quantità",
        "Prezzo",
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
/*
function show()
{
    global $mysqli;
    $colnames = array(
        "Nome",
        "Prezzo",
        "Quantità",
    );
    $id = explode('/', $_SERVER['REQUEST_URI'])[3];
    $ordine= $mysqli-> query("SELECT ordini.id, u.email as email_utente, u.nome as nome_utente, u.cognome as cognome_utente, u.telefono as utente_telefono, ordini.data, ordini.stato, ordini.totale, ordini.numero_ordine, ordini.motivazione, CONCAT(isp.indirizzo,' ', isp.citta,' ', isp.cap,' ', isp.provincia,' ',isp.nazione) as indirizzo_spedizione,
        CONCAT(ifa.indirizzo,' ', ifa.citta,' ', ifa.cap,' ', ifa.provincia,' ', ifa.nazione) as indirizzo_fatturazione FROM tdw_db.ordini 
            JOIN tdw_db.users as u on u.id=ordini.user_id JOIN tdw_db.indirizzi as isp on isp.id= ordini.indirizzi_spedizione JOIN tdw_db.indirizzi as ifa on ifa.id= ordini.indirizzi_fatturazione
             WHERE ordini.id=".$id);

    if ($ordine->num_rows == 0) {
        header("Location: /admin/orders");
    } else {
        $ordine = $ordine->fetch_assoc();
        $main = setupMainAdmin();
        $show = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/components/orders/show.html");
        foreach ($ordine as $key => $value) {
            $show->setContent($key, $value);
        }
        $table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/components/simple_table.html");
        $table->setContent("title", "Prodotti");
        foreach ($colnames as $value) {
            $table->setContent("colname", $value);
        }
        $prodotti_table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/components/specific_tables/prodotti_ordini.html");
        $oid= $mysqli-> query("SELECT p.nome as nome_prodotto, p.prezzo as prezzo_prodotto, op.quantita as quantita_prodotto FROM tdw_db.ordini_has_prodotti as op JOIN tdw_db.prodotti as p on p.id=op.prodotti_id WHERE op.ordini_id=".$id);
        do {
            $prodotti = $oid->fetch_assoc();
            if ($prodotti) {
                foreach ($prodotti as $key => $value) {
                    $prodotti_table->setContent($key, $value);
                }
            }
        } while ($prodotti);
        $table->setContent("sptable", $prodotti_table->get());
        $show->setContent("table", $table->get());
        $main->setContent("content", $show->get());
        $main->close();
    }
}

function accetta_ordine(){
    global $mysqli;
    $id = explode('/', $_SERVER['REQUEST_URI'])[3];
    $response = array();
    $mysqli->query("UPDATE tdw.ordini SET stato='MEMORIZZATO' WHERE id=".$id);
    if ($mysqli->affected_rows == 1) {
        $response['success'] = "Prodotto modificato con successo";
    } elseif ($mysqli->affected_rows == 0) {
        $response['warning'] = "Nessun dato modificato";
    } else {
        $response['error'] = "Errore nella modifica del prodotto";
    }
    exit(json_encode($response));
}

function edit_stato()
{
    global $mysqli;
    $id = explode('/', $_SERVER['REQUEST_URI'])[3];
    $stato = $_POST["stato"];
    if ($_POST["motivazione"] != "") {
        $motivazione = $_POST["motivazione"];
        if ($stato == "SOSPESO") {
            $mysqli->query("UPDATE tdw_db.ordini SET stato='$stato', motivazione='$motivazione' WHERE id=".$id);
            $response = array();
            if ($mysqli->affected_rows == 1) {
                $response['success'] = "Stato dell'ordine modificato con successo";
            } elseif ($mysqli->affected_rows == 0) {
                $response['warning'] = "Nessun dato modificato";
            } else {
                $response['error'] = "Errore nella modifica dello stato dell'ordine";
            }
        }if($stato=="ANNULLATO"){
            $mysqli->query("UPDATE tdw_db.ordini SET stato='$stato', motivazione='$motivazione' WHERE id=".$id);
            $oid= $mysqli-> query("SELECT p.id as id_prodotto, op.quantita as quantita_prodotto FROM tdw_db.ordini_has_prodotti as op JOIN tdw_db.prodotti as p on p.id=op.prodotti_id WHERE op.ordini_id=".$id);
            $prodotti = $oid->fetch_assoc();
            if($prodotti){
                do {
                    $mysqli->query("UPDATE tdw_db.prodotti SET quantita_disponibile=quantita_disponibile+".$prodotti['quantita_prodotto']." WHERE id=".$prodotti['id_prodotto']);
                } while ($prodotti = $oid->fetch_assoc());
            }
        }
    }else{
        $mysqli->query("UPDATE tdw_db.ordini SET stato='$stato' WHERE id = '$id'");
        $response = array();
        if ($mysqli->affected_rows == 1) {
            $response['success'] = "Stato dell'ordine modificato con successo";
        } elseif ($mysqli->affected_rows == 0) {
            $response['warning'] = "Nessun dato modificato";
        } else {
            $response['error'] = "Errore nella modifica dello stato dell'ordine";
        }
        exit(json_encode($response));
    }
}*/