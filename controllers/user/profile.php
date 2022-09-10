<?php

function profile() {

    global $mysqli;

    $main = initUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/profile.html");

    $user = $_SESSION['user'];

    foreach($user as $key => $value) {
        $body->setContent($key, $value);
    }

    findOrders($mysqli, $body, $user['user_id']);

    findAddresses($mysqli, $body, $user['user_id']);

    $main->setContent("content", $body->get());
    $main->close();
}


function findOrders($mysqli, Template $body, $user_id)
{
    // Prendo gli ordini dell'utente in questione
    $oid = $mysqli->query("SELECT * FROM `order` WHERE user_id = $user_id ORDER BY updated_at DESC");

    if ($oid->num_rows == 0) {
        $body->setContent("orders", '
            <div class="content-title-section" style="margin: 100px 0 !important; text-align: center !important;">
                <h3 class="title title-center mb-3">Non hai ancora effettuato ordini</h3>
                <a href="/" class="btn btn-primary mt-10">Torna allo shopping</a>
            </div>
        ');
    } else {
        $user_orders = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/profile/profile-orders.html");
        do {
            $order = $oid->fetch_assoc();
            if ($order) {
                foreach ($order as $key => $value) {
                    $user_orders->setContent($key, $value);
                }
            }
        } while ($order);
        $body->setContent("orders", $user_orders->get());
    }
}

function findAddresses($mysqli, Template $body, $user_id)
{
    // Prendo gli indirizzi dell'utente in questione
    $oid = $mysqli->query("SELECT * FROM shipment_address WHERE user_id = $user_id");

    if ($oid->num_rows == 0) {
        $body->setContent("addresses", '
            <div class="content-title-section" style="margin: 100px 0 !important;">
                <h3 class="title title-center mb-3">Non hai ancora un indirizzo. Inseriscine subito uno!</h3>
            </div>
        ');
    } else {
        $addresses = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/profile/profile-addresses.html");
        do {
            $address = $oid->fetch_assoc();
            if ($address) {
                foreach ($address as $key => $value) {
                    $addresses->setContent($key, $value);
                }
            }
        } while ($address);
        $body->setContent("addresses", $addresses->get());
    }
}

#[NoReturn] function addPaymentMethod(): void
{
    global $mysqli;

    $code = $_POST['code'];
    $type = $_POST['type'];
    $credentials = $_POST['credentials'];
    $validity = $_POST['validity'];

    $user_id = $_SESSION['user']['user_id'];

    try {
        $mysqli->query("INSERT INTO payment_method (payment_code, type, credentials, validity, user_id) VALUES ('$code', '$type', '$credentials', '$validity', $user_id);");

        if ($mysqli->affected_rows == 1) {
            $response['success'] = "Metodo aggiunto con successo";
        } elseif ($mysqli->affected_rows == 0) {
            $response['warning'] = "Nessuna metodo aggiunto";
        } else {
            $response['error'] = "Errore nell'inserimento";
        }
    } catch (mysqli_sql_exception $e) {
        $response['error'] = $e->getMessage();
    }

    exit(json_encode($response));

}

#[NoReturn] function addShipmentAddress(): void
{
    global $mysqli;

    $address = $_POST['address'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $country = $_POST['country'];
    $postal_code = $_POST['postal_code'];

    $user_id = $_SESSION['user']['user_id'];

    try {
        $mysqli->query("INSERT INTO shipment_address (city, address, province, country, postal_code, user_id) VALUES ('$city', '$address', '$province', '$country', '$postal_code', $user_id);");

        if ($mysqli->affected_rows == 1) {
            $response['success'] = "Indirizzo aggiunto con successo";
        } elseif ($mysqli->affected_rows == 0) {
            $response['warning'] = "Nessun indirizzo aggiunto";
        } else {
            $response['error'] = "Errore nell'inserimento";
        }
    } catch (mysqli_sql_exception $e) {
        $response['error'] = $e->getMessage();
    }

    exit(json_encode($response));

}