<?php

use JetBrains\PhpStorm\NoReturn;

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


#[NoReturn] function add(): void
{
    $response = array();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['product_id'])) {

            global $mysqli;

            $product_id = $_POST['product_id'];

            $oid = $mysqli->query("SELECT product_id FROM user_product_cart WHERE user_id = {$_SESSION["user"]["user_id"]} AND product_id = {$product_id}");


            if ($oid->num_rows == 0) {
                $product_to_add = $mysqli->query("SELECT * FROM product WHERE product_id = {$product_id}")->fetch_assoc();
                $price = $product_to_add['price'];
                $mysqli->query("INSERT INTO user_product_cart (user_id, product_id, quantity, date, subtotal) VALUES ({$_SESSION["user"]["user_id"]}, {$product_id}, 1, NOW(), {$price})");
                $response['success'] = "success";
            } else {
                // Aumento la quantità del prodotto nel carrello
                $mysqli->query("UPDATE user_product_cart SET quantity = quantity + 1 WHERE user_id = {$_SESSION["user"]["user_id"]} AND product_id = {$product_id}");
                $response['success'] = "success";
            }
        } else {
            $response['error'] = "error";
        }
    }

    exit(json_encode($response));
}

#[NoReturn] function clear(): void
{
    $response = array();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        global $mysqli;

        $user = $mysqli->query("SELECT * FROM user WHERE email_address = '{$_SESSION["user"]["email_address"]}'");
        $user = $user->fetch_assoc();

        $mysqli->query("DELETE FROM user_product_cart WHERE user_id = {$user["user_id"]}");

        if ($mysqli->affected_rows != 0) {
            $response['success'] = "Carrello svuotato correttamente!";
        } else {
            $response['warning'] = "Nessun elemento rimosso (il carello era già vuoto?)";
        }
    }
    exit(json_encode($response));
}
