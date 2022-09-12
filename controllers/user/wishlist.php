<?php

use JetBrains\PhpStorm\NoReturn;

function wishlist() {

    global $mysqli;

    $main = initUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/wishlist.html");

    $user_id = $_SESSION['user']['user_id'];

    // Estraggo i prodotti nella wishlist dell'utente
    $oid = $mysqli->query("SELECT p.product_id, p.product_name, p.price, pi.file_name, p.quantity_available
                                                FROM user_product_wishlist upw JOIN product p ON (p.product_id = upw.product_id) JOIN product_image pi ON (pi.product_id = p.product_id)  
                                                WHERE upw.user_id = $user_id AND pi.type = 'main'");

    if ($oid->num_rows == 0){
        $body->setContent("products", '
            <div class="content-title-section" style="margin: 100px 0 !important; text-align: center !important;">
                <h3 class="title title-center mb-3">Non ci sono articoli nella tua wishlist!</h3>
                <a href="/" class="btn btn-primary mt-10">Torna allo shopping</a>
            </div>
        ');
    }
    else {
        $wishlist_products = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/wishlist/wishlist-products.html");
        do {
            $product = $oid->fetch_assoc();
            if ($product) {
                foreach ($product as $key => $value) {
                    $wishlist_products->setContent($key, $value);
                }
                if (intval($product['quantity_available']) > 0)
                    $wishlist_products->setContent("availability", "Disponibile");
            }
        } while ($product);
        $body->setContent("products", $wishlist_products->get());
    }

    $main->setContent("content", $body->get());
    $main->close();
}

#[NoReturn] function add() {

    global $mysqli;

    $product_id = $_POST['product_id'];

    $user_id = $_SESSION['user']['user_id'];

    try {
        $mysqli->query("INSERT INTO user_product_wishlist (user_id, product_id, date) VALUES ($user_id, $product_id, NOW());");

        if ($mysqli->affected_rows == 1) {
            $response['success'] = "Prodotto aggiunto con successo";
        } elseif ($mysqli->affected_rows == 0) {
            $response['warning'] = "Nessun prodotto aggiuntao";
        } else {
            $response['error'] = "Errore nell'inserimento";
        }
    } catch (mysqli_sql_exception $e) {
        $response['error'] = $e->getMessage();
    }

    exit(json_encode($response));

}

#[NoReturn] function remove(): void
{
    $response = array();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        global $mysqli;

        $product_id = $_POST['product_id'];
        $user_id = $_SESSION['user']['user_id'];

        $mysqli->query("DELETE FROM user_product_wishlist WHERE user_id = $user_id AND product_id = $product_id");

        try {
            if ($mysqli->affected_rows == 1) {
                $response['success'] = "Elemento rimosso correttamente!";
            } else {
                $response['warning'] = "Nessuna modifica effettuata";
            }
        } catch (mysqli_sql_exception $e) {
            $response['error'] = "Errore nella rimozione";
        }
    }
    exit(json_encode($response));
}