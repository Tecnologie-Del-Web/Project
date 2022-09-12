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

    // setupCouponApplication($mysqli, $brand_id, $body);

    $main->setContent("content", $body->get());
    $main->close();
}

function checkout() {

    global $mysqli;

    $main = initUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/checkout.html");

    $user = $_SESSION["user"];

    setupSide($mysqli, $user["user_id"], $body);

    findMethods($mysqli, $user["user_id"], $body);

    findAddresses($mysqli, $user["user_id"], $body);

    findCheckoutProducts($mysqli, $user["user_id"], $body);

    $main->setContent("content", $body->get());
    $main->close();
}

function order() {

    $order_id = intval(explode('=', explode('?', $_SERVER['REQUEST_URI'])[1])[1]);

    global $mysqli;

    $main = initUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/order.html");

    $order = $mysqli->query("SELECT * FROM `order` o JOIN shipment_address sa ON (o.user_id = sa.user_id) WHERE order_id = $order_id");

    if ($order->num_rows == 0) {
        // TODO: gestire!
    }
    else {
        $order = $order->fetch_assoc();
        if ($order) {
            foreach ($order as $key => $value) {
                $body->setContent($key, $value);
            }
        }
    }

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
    $oid = $mysqli->query("SELECT p.product_id, p.product_name, p.price, ROUND(upc.subtotal, 2) as subtotal, upc.quantity, pi.file_name
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
                    if (strcmp($key, "price") != 0 && strcmp($key, "subtotal") != 0) {
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
                    $new_price = number_format($new_price, 2);
                    $cart_products->setContent("price", '
                        <td class="product-price">€' . $old_price . ' -' . $discount_percentage . '% = ' . '<span class="amount">€' . $new_price . '</span></td>
                    ');
                    $cart_products->setContent("subtotal", number_format($new_price * $quantity, 2));
                } else {
                    $cart_products->setContent("price", '
                        <td class="product-price"><span class="amount">€' . $old_price . '</span></td>
                    ');
                    $cart_products->setContent("subtotal", number_format($product['subtotal'], 2));
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
    $oid = $mysqli->query("SELECT COUNT(product_id) as number FROM user_product_cart WHERE user_id = {$user_id}");

    $number_of_articles = $oid->fetch_assoc();
    $body->setContent("number_of_articles", $number_of_articles['number']);
}


/**
 * @param mysqli $mysqli
 * @param $user_id
 * @param Template $body
 * @return void
 */
function findMethods(mysqli $mysqli, $user_id, Template $body): void
{
    // Prendo i metoi di pagamento dell'utente corrente
    $oid = $mysqli->query("SELECT *
                                    FROM payment_method
                                    WHERE user_id = $user_id;");

    if ($oid->num_rows == 0) {
        $body->setContent("methods", '
            <h3 class="font-weight-bold ml-3 mb-3">Non hai ancora un indirizzo di spedizione. Inseriscine uno sotto</h3>
        ');
    } else {
        $methods = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/checkout/checkout-methods.html");
        do {
            $method = $oid->fetch_assoc();
            if ($method) {
                foreach ($method as $key => $value) {
                    $methods->setContent($key, $value);
                }
            }
        } while ($method);
        $body->setContent("methods", $methods->get());
    }
}

/**
 * @param mysqli $mysqli
 * @param $user_id
 * @param Template $body
 * @return void
 */
function findAddresses(mysqli $mysqli, $user_id, Template $body): void
{
    // Prendo gli indirizzi di spedizione dell'utente corrente
    $oid = $mysqli->query("SELECT *
                                    FROM shipment_address
                                    WHERE user_id = $user_id;");

    if ($oid->num_rows == 0) {
        $body->setContent("addresses", '
            <h3 class="font-weight-bold ml-3 mb-3">Non hai ancora un indirizzo di spedizione. Inseriscine uno sotto</h3>
        ');
    } else {
        $addresses = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/checkout/checkout-addresses.html");
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

/**
 * @param mysqli $mysqli
 * @param $brand_id
 * @param Template $body
 * @return void
 */

/**
 * @param mysqli $mysqli
 * @param $user_id
 * @param Template $body
 * @return void
 */
function findCheckoutProducts(mysqli $mysqli, $user_id, Template $body)
{
    $oid = $mysqli->query("SELECT p.product_name, p.product_id, ROUND(upc.subtotal, 2) as subtotal, upc.quantity
                                            FROM user_product_cart upc JOIN product p ON (p.product_id = upc.product_id) 
                                            WHERE upc.user_id = {$user_id}");

    if ($oid->num_rows == 0) {
        Header("Location: /cart");
    } else {
        $total = 0;
        do {
            $product = $oid->fetch_assoc();
            if ($product) {
                $product_id = $product['product_id'];
                $offer = $mysqli->query("SELECT *
                                            FROM offer 
                                            WHERE product_id = $product_id");
                if ($offer->num_rows > 0) {
                    $offer = $offer->fetch_assoc();
                    $subtotal = floatval($product['subtotal']) - floatval($product['subtotal']) * floatval($offer['percentage']) / 100.00;
                    $subtotal = number_format($subtotal, 2);
                }
                else {
                    $subtotal = $product['subtotal'];
                }
                $total += $subtotal;
                foreach ($product as $key => $value) {
                    if (strcmp($key, "subtotal") != 0)
                        $body->setContent($key, $value);
                    else $body->setContent("subtotal", $subtotal);
                }
            }
        } while ($product);
        $body->setContent("total", $total);
    }
}

function setupCouponApplication(mysqli $mysqli, $brand_id, Template $body): void
{
    /*
    // Prendo le informazioni sul brand di cui ho bisogno
    $applied_coupon = $mysqli->query("SELECT b.brand_id, b.brand_name, b.brand_image
                                    FROM coupon c WHERE
                                    WHERE b.brand_id = $brand_id;");

    if ($brand->num_rows == 0) {
        // TODO: gestire!
        echo "\n" . "Ricordati di gestire questo caso!";
        // header("Location: /products");
    } else {
        $brand = $brand->fetch_assoc();
        foreach ($brand as $key => $value) {
            $body->setContent($key, $value);
        }
    }
    */
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

#[NoReturn] function editQuantity(): void
{
    $response = array();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        global $mysqli;


        $product_id = $_POST['product_id'];
        $increment = $_POST['increment'];

        $user = $mysqli->query("SELECT * FROM user WHERE email_address = '{$_SESSION["user"]["email_address"]}'");
        $user = $user->fetch_assoc();
        $user_id = $user['user_id'];

        /*

                if ($increment > 0){
                    $mysqli->query("UPDATE user_product_cart SET quantity = quantity + $increment, subtotal = subtotal + $product_price WHERE user_id = {$user["user_id"]} AND product_id = $product_id");
                }
                elseif ($increment < 0){
                    $mysqli->query("UPDATE user_product_cart SET quantity = quantity + $increment, subtotal = subtotal - $product_price WHERE user_id = {$user["user_id"]} AND product_id = $product_id");
                }

                try {
                    if ($mysqli->affected_rows != 0) {
                        $response['success'] = "Quantità modificata correttamente!";
                    } else {
                        $response['warning'] = "Nessuna modifica effettuata";
                    }
                } catch (mysqli_sql_exception $e) {
                    $response['error'] = "Errore nell'aggiornamento";
                }
                */

        try {
            $mysqli->query("CALL adjust_price($user_id, $product_id, $increment);");
            $response['success'] = "Quantità modificata correttamente";
        } catch (mysqli_sql_exception $e) {
            $response['error'] = "Si è verificato un errore durante l'aggiornamento!";
        }

    }
    exit(json_encode($response));
}

#[NoReturn] function remove(): void
{
    $response = array();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        global $mysqli;

        $product_id = $_POST['product_id'];

        $user = $mysqli->query("SELECT * FROM user WHERE email_address = '{$_SESSION["user"]["email_address"]}'");
        $user = $user->fetch_assoc();

        $mysqli->query("DELETE FROM user_product_cart WHERE user_id = {$user["user_id"]} AND product_id = $product_id");

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

#[NoReturn] function placeOrder(): void
{
    $response = array();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        global $mysqli;

        $user_id = $_SESSION['user']['user_id'];
        $total = $_POST['total'];
        $method_id = $_POST['method_id'];
        $address_id = $_POST['address_id'];
        $coupon_code = $_POST['coupon_code'];

        $order_code = $_SESSION['user']['username'] . $method_id . $coupon_code;

        if (!(strcmp($coupon_code, '') == 0)) {
            $coupon_id = $mysqli->query("SELECT coupon_id FROM coupon WHERE coupon_code = '$coupon_code'")->fetch_assoc()['coupon_id'];
            $order = $mysqli->query("SELECT add_order_coupon('$order_code', $total, $user_id, $method_id, $coupon_id, $address_id) as order_id")->fetch_assoc();
        }
        else {
            $order = $mysqli->query("SELECT add_order('$order_code', $total, $user_id, $method_id, $address_id) as order_id")->fetch_assoc();
        }

        $oid = $mysqli->query("SELECT * FROM user_product_cart WHERE user_id = $user_id");

        if ($oid->num_rows == 0) {
            // TODO: configurare alert opportunamente!
        } else {
            $order_id = $order['order_id'];
            do {
                $product = $oid->fetch_assoc();
                if ($product) {
                    $product_id = $product['product_id'];
                    $quantity = $product['quantity'];
                    $price = $product['subtotal'];
                    try {
                        $mysqli->query("INSERT INTO order_product (order_id, product_id, quantity, price) VALUES ($order_id, $product_id, $quantity, $price)");
                    } catch (mysqli_sql_exception $e) {
                        $response['error'] = "Errore nella rimozione " . $e->getMessage();
                        exit(json_encode($response));
                    }
                }
            } while ($product);
            $mysqli->query("DELETE FROM user_product_cart WHERE user_id = $user_id");
            $response['success'] = "Ordine piazzato correttamente";
            $response['id'] = $order_id;
        }
    }

    exit(json_encode($response));
}

#[NoReturn] function applyCoupon(): void
{
    $response = array();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        global $mysqli;

        $coupon_code = $_POST['coupon_code'];

        $coupon = $mysqli->query("SELECT * FROM coupon WHERE coupon_code = '$coupon_code'");

        if ($coupon->num_rows == 0) {
            $response['no_coupons'] = "Coupon non trovato!";
        }
        else {
            try {
                $coupon = $coupon->fetch_assoc();
                $response['percentage'] = $coupon['percentage'];
                $response['success'] = "Coupon applicato correttamente!";
            } catch (mysqli_sql_exception $e) {
                $response['error'] = "Errore nell'applicazione del coupon";
            }
        }

    }
    exit(json_encode($response));
}

