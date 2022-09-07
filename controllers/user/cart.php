<?php

function cart() {

    global $mysqli;

    $main = initUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/cart.html");

    $user = $mysqli->query("SELECT * FROM user u WHERE u.email_address = '{$_SESSION["user"]["email_address"]}'");
    $user = $user->fetch_assoc();

    findCartProducts($mysqli, $user["user_id"], $body);

    setupSide($mysqli, $user["user_id"], $body);

    $main->setContent("content", $body->get());
    $main->close();
}

/**
 * @param mysqli $mysqli
 * @param $user_id
 * @param Template $body
 * @return void
 */
function findCartProducts(mysqli $mysqli, $user_id, Template $body)
{
    $oid = $mysqli->query("SELECT p.product_id, p.product_name, p.price, upc.quantity, pi.file_name
                                            FROM user_product_cart upc JOIN product p ON (p.product_id = upc.product_id) JOIN product_image pi ON (pi.product_id = p.product_id) 
                                            WHERE upc.user_id = {$user_id} AND pi.type='main'");

    if ($oid->num_rows == 0) {
        $body->setContent("products", '
            <div class="content-title-section" style="margin: 100px 0 !important;">
                <h3 class="sub-title title-center ml-3 mb-3">Il tuo carrello è vuoto!</h3>
            </div>
        ');
    } else {
        $cart_products = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/cart/cart-products.html");
        do {
            $product = $oid->fetch_assoc();
            if ($product) {
                foreach ($product as $key => $value) {
                    if (strcmp($key, "price") != 0) {
                        $cart_products->setContent($key, $value);
                    }
                }
                $old_price = $product['price'];
                $quantity = $product['quantity'];
                $product_id = $product['product_id'];
                $offer = $mysqli->query("SELECT * FROM offer o WHERE o.product_id = $product_id AND end_date > NOW() ORDER BY end_date DESC LIMIT 1;");
                if ($offer->num_rows > 0) {
                    $offer = $offer->fetch_assoc();
                    $discount_percentage = $offer['percentage'];
                    $new_price = $old_price - $old_price * ($discount_percentage / 100);
                    $cart_products->setContent("price", '
                        <td class="product-price">€' . $old_price . ' -' . $discount_percentage . '% = ' . '<span class="amount">€' . $new_price . '</span></td>
                    ');
                    $cart_products->setContent("subtotal", $new_price * $quantity);
                } else {
                    $cart_products->setContent("price", '
                        <td class="product-price"><span class="amount">€' . $old_price . '</span></td>
                    ');
                    $cart_products->setContent("subtotal", $old_price * $quantity);
                }
            }
        } while ($product);
        $body->setContent("products", $cart_products->get());
    }
}

/**
 * @param mysqli $mysqli
 * @param $user_id
 * @param Template $body
 * @return void
 */
function setupSide(mysqli $mysqli, $user_id, Template $body)
{
    $oid = $mysqli->query("SELECT * FROM shipment_address WHERE user_id = {$user_id}");

    if ($oid->num_rows == 0) {
        echo "Gestire questo caso!";
    } else {
        do {
            $address = $oid->fetch_assoc();
            if ($address) {
                foreach ($address as $key => $value) {
                    $body->setContent($key, $value);
                }
            }
        } while ($address);
    }
}
