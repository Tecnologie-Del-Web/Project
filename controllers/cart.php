<?php

function cart() {

    global $mysqli;

    $main = setupUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/cart.html");

    // Prendo le recensioni del prodotto in questione
    $oid = $mysqli->query("SELECT upc.quantity, upc.subtotal, p.product_id, p.product_name, p.price, pv.variant_id, pi.file_name
                                    FROM user_product_cart upc JOIN product p ON (upc.product_id = p.product_id) JOIN product_variant pv ON (pv.product_id = p.product_id) JOIN product_image pi ON (pv.variant_id = pi.variant_id)
                                    WHERE pv.default = true AND pi.type = 'main';");

    if ($oid->num_rows == 0) {
        $body->setContent("products", '
            <div class="content-title-section" style="margin: 100px 0 !important;">
                <h3 class="sub-title title-center ml-3 mb-3">Il tuo carrello è vuoto!</h3>
            </div>
        ');
    } else {
        $cart_products = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/cart_products.html");
        do {
            $cart_product = $oid->fetch_assoc();
            if ($cart_product) {
                foreach ($cart_product as $key => $value) {
                    $cart_products->setContent($key, $value);
                }
            }
        } while ($cart_product);
        $body->setContent("products", $cart_products->get());
    }

    $main->setContent("content", $body->get());
    $main->close();
}
