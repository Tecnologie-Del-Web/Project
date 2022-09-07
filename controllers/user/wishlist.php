<?php

function wishlist() {

    global $mysqli;

    $main = initUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/wishlist.html");

    $user_id = $_SESSION['user']['user_id'];

    // Estraggo i prodotti nella wishlist dell'utente
    $oid = $mysqli->query("SELECT p.product_id, p.product_name, p.price, pi.file_name
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
            }
        } while ($product);
        $body->setContent("products", $wishlist_products->get());
    }

    $main->setContent("content", $body->get());
    $main->close();
}