<?php

function product()
{
    // TODO: gestire opportunamente!

    global $mysqli;

    $main = setupUser(false);
    // In origine, product-default.html
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/product-detail.html");

    $id = explode('/', $_SERVER['REQUEST_URI'])[2];
    $brand_id = '';
    $category_id = '';


    $product = $mysqli->query("SELECT p.product_id, p.product_name, p.price, p.product_description, p.brand_id, p.category_id
                                    FROM product p
                                    WHERE p.product_id = $id;");

    if ($product->num_rows == 0) {
        // TODO: gestire!
        echo "\n" . "Ricordati di gestire questo caso!";
        // header("Location: /products");
    } else {
        $product = $product->fetch_assoc();
        $brand_id = $product['brand_id'];
        $category_id = $product['category_id'];
        foreach ($product as $key => $value) {
            $body->setContent($key, $value);
        }
    }

    $oid = findColorVariants($mysqli, $id, $body);

    $oid = findSizeVariants($mysqli, $id, $body);

    // Prendo le immagini della variante di default
    $oid = $mysqli->query("SELECT p.product_id, p.product_name, pv.variant_id, pi.image_id, pi.file_name
                                    FROM product p JOIN product_variant pv ON (pv.product_id = p.product_id) JOIN product_image pi ON (pv.variant_id = pi.variant_id)
                                    WHERE p.product_id = $id AND pv.default = true;");


    do {
        $image = $oid->fetch_assoc();
        if ($image) {
            foreach ($image as $key => $value) {
                $body->setContent("swiper_" . $key, $value);
                $body->setContent("thumb_" . $key, $value);
            }
        }
    } while ($image);


    // Prendo le informazioni sul brand di cui ho bisogno
    $brand = $mysqli->query("SELECT b.brand_name, b.brand_image
                                    FROM brand b
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

    // Prendo le informazioni sulla categoria di cui ho bisogno
    $category = $mysqli->query("SELECT c.category_id, c.category_name
                                    FROM category c
                                    WHERE c.category_id = $category_id;");

    if ($category->num_rows == 0) {
        // TODO: gestire!
        echo "\n" . "Ricordati di gestire questo caso!";
        // header("Location: /products");
    } else {
        $category = $category->fetch_assoc();
        foreach ($category as $key => $value) {
            $body->setContent($key, $value);
        }
    }

    // Prendo le recensioni del prodotto in questione
    $oid = $mysqli->query("SELECT pr.text, pr.date, pr.rating, u.username
                                    FROM product p JOIN product_review pr ON (p.product_id = pr.product_id) JOIN user u ON (pr.user_id = u.user_id)
                                    WHERE p.product_id = $id;");

    do {
        $review = $oid->fetch_assoc();
        if ($review) {
            foreach ($review as $key => $value) {
                $body->setContent($key, $value);
            }
        }
    } while ($review);


    // Prodotti dello stesso brand
    $oid = $mysqli->query("SELECT p.product_id, p.product_name, p.price
                                                FROM product p
                                                WHERE p.quantity_available > 0 
                                                  AND p.brand_id = $brand_id
                                                  AND p.product_id != $id
                                                ORDER BY p.product_name
                                                LIMIT 6");

    do {
        $product = $oid->fetch_assoc();
        if ($product) {
            foreach ($product as $key => $value) {
                $body->setContent("same_brand_" . $key, $value);
            }
        }
    } while ($product);


    // "Altri prodotti", ovvero prodotti della stessa categoria, ma di un altro brand
    $oid = $mysqli->query("SELECT p.product_id, p.product_name, p.price
                                                FROM product p
                                                WHERE p.quantity_available > 0 
                                                  AND p.brand_id != $brand_id
                                                  AND p.category_id = $category_id
                                                ORDER BY p.product_name
                                                LIMIT 3");

    do {
        $product = $oid->fetch_assoc();
        if ($product) {
            foreach ($product as $key => $value) {
                $body->setContent("other_products_" . $key, $value);
            }
        }
    } while ($product);

    $main->setContent("content", $body->get());
    $main->close();
}

/**
 * @param mysqli $mysqli
 * @param string $id
 * @param Template $body
 * @return bool|mysqli_result
 */
function findColorVariants(mysqli $mysqli, string $id, Template $body): bool|mysqli_result
{
    // Lavoro sulle varianti di colore
    $oid = $mysqli->query("SELECT pv.variant_name
                                    FROM product p JOIN product_variant pv ON (pv.product_id = p.product_id)
                                    WHERE p.product_id = $id AND pv.type = 'color';");


    if ($oid->num_rows != 0) {
        $template = '
        <div class="product-form product-variation-form product-color-swatch" style="line-height: 2 !important;">
            <label>Colori:</label>
            <div class="d-flex align-items-center product-variations">
        ';

        do {
            $color_variant = $oid->fetch_assoc();
            if ($color_variant) {
                $template = $template . '<a href="#" style="border: none !important; width: auto !important; margin: 0 1em !important; font-size: 1.4em !important;">' . $color_variant['variant_name'] . '</a>';
            }
        } while ($color_variant);

        $template = $template . "
            </div>
        </div>
        ";
        $body->setContent("color_variants", $template);
    }
    return $oid;
}

/**
 * @param mysqli $mysqli
 * @param string $id
 * @param Template $body
 * @return bool|mysqli_result
 */
function findSizeVariants(mysqli $mysqli, string $id, Template $body): bool|mysqli_result
{
    // Lavoro sulle varianti di taglia
    $oid = $mysqli->query("SELECT pv.variant_name
                                    FROM product p JOIN product_variant pv ON (pv.product_id = p.product_id)
                                    WHERE p.product_id = $id AND pv.type = 'size';");

    if ($oid->num_rows != 0) {
        $template = '
        <div class="product-form product-variation-form product-color-swatch" style="line-height: 2 !important;">
            <label>Taglie:</label>
            <div class="d-flex align-items-center product-variations">
        ';

        do {
            $size_variant = $oid->fetch_assoc();
            if ($size_variant) {
                $template = $template . '<a href="#" style="border: none !important; width: auto !important; margin: 0 1em !important; font-size: 1.4em !important;">' . $size_variant['variant_name'] . '</a>';
            }
        } while ($size_variant);

        $template = $template . "
            </div>
        </div>
        ";
        $body->setContent("size_variants", $template);
    }
    return $oid;
}
