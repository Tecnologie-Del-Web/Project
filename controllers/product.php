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

    // Prendo il prodotto con l'id in esame
    // Lo sku è quello della variante di default!
    $product = $mysqli->query("SELECT p.product_id, p.product_name, p.price, p.product_description, p.brand_id, p.category_id, pv.sku
                                    FROM product p JOIN product_variant pv ON (p.product_id = pv.product_id)
                                    WHERE p.product_id = $id AND pv.default = true;");

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

    findColorVariants($mysqli, $id, $body);

    findSizeVariants($mysqli, $id, $body);

    findDefaultVariantImages($mysqli, $id, $body);

    findBrandInfo($mysqli, $brand_id, $body);

    findCategoryInfo($mysqli, $category_id, $body);

    findReviews($mysqli, $id, $body);

    findSameBrandProducts($mysqli, $brand_id, $id, $body);

    findOtherProducts($mysqli, $brand_id, $category_id, $body);


    $main->setContent("content", $body->get());
    $main->close();
}

/**
 * @param mysqli $mysqli
 * @param mixed $brand_id
 * @param Template $body
 * @return void
 */
function findBrandInfo(mysqli $mysqli, mixed $brand_id, Template $body)
{
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
}

/**
 * @param mysqli $mysqli
 * @param mixed $category_id
 * @param Template $body
 * @return void
 */
function findCategoryInfo(mysqli $mysqli, mixed $category_id, Template $body)
{
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
}

/**
 * @param mysqli $mysqli
 * @param string $id
 * @param Template $body
 * @return array
 */
function findDefaultVariantImages(mysqli $mysqli, string $id, Template $body)
{
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
}

/**
 * @param mysqli $mysqli
 * @param string $id
 * @param Template $body
 * @return void
 */
function findReviews(mysqli $mysqli, string $id, Template $body)
{
    // Prendo le recensioni del prodotto in questione
    $oid = $mysqli->query("SELECT pr.text, pr.date, pr.rating, u.username
                                    FROM product p JOIN product_review pr ON (p.product_id = pr.product_id) JOIN user u ON (pr.user_id = u.user_id)
                                    WHERE p.product_id = $id;");

    if ($oid->num_rows == 0) {
        $body->setContent("reviews", '
            <div class="content-title-section" style="margin: 100px 0 !important;">
                <h3 class="sub-title title-center ml-3 mb-3">Non ci sono ancora recensioni di questo prodotto. Scrivine una!</h3>
            </div>
        ');
    } else {
        $reviews = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/detail_reviews.html");
        do {
            $review = $oid->fetch_assoc();
            if ($review) {
                foreach ($review as $key => $value) {
                    $reviews->setContent($key, $value);
                }
            }
        } while ($review);
        $body->setContent("reviews", $reviews->get());
    }
}

/**
 * @param mysqli $mysqli
 * @param mixed $brand_id
 * @param string $id
 * @param Template $body
 * @return void
 */
function findSameBrandProducts(mysqli $mysqli, mixed $brand_id, string $id, Template $body)
{
    // Prodotti dello stesso brand
    $oid = $mysqli->query("SELECT p.product_id, p.product_name, p.price, pv.variant_id, pi.file_name
                                                FROM product p JOIN product_variant pv ON (pv.product_id = p.product_id) JOIN product_image pi ON (pi.variant_id = pv.variant_id)
                                                WHERE p.quantity_available > 0 
                                                  AND p.brand_id = $brand_id
                                                  AND p.product_id != $id
                                                  AND pv.default = true AND pi.type = 'main' 
                                                ORDER BY p.product_name
                                                LIMIT 6");

    if ($oid->num_rows == 0) {
        $body->setContent("same_brand", '
            <div class="content-title-section" style="margin: 100px 0 !important;">
                <h3 class="title title-center mb-3">Non ci sono altri articoli di questo brand</h3>
            </div>
        ');
    } else {
        $same_brand = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/same_brand_products.html");
        do {
            $product = $oid->fetch_assoc();
            if ($product) {
                foreach ($product as $key => $value) {
                    $same_brand->setContent($key, $value);
                }
            }
        } while ($product);
        $body->setContent("same_brand", $same_brand->get());
    }
}

/**
 * @param mysqli $mysqli
 * @param mixed $brand_id
 * @param mixed $category_id
 * @param Template $body
 * @return void
 */
function findOtherProducts(mysqli $mysqli, mixed $brand_id, mixed $category_id, Template $body)
{
    // "Altri prodotti", ovvero prodotti della stessa categoria, ma di un altro brand
    $oid = $mysqli->query("SELECT p.product_id, p.product_name, p.price, pv.variant_id, pi.file_name
                                                FROM product p JOIN product_variant pv ON (pv.product_id = p.product_id) JOIN product_image pi ON (pi.variant_id = pv.variant_id)
                                                WHERE p.quantity_available > 0 
                                                  AND p.brand_id != $brand_id
                                                  AND p.category_id = $category_id
                                                  AND pv.default = true AND pi.type = 'main'
                                                ORDER BY p.product_name
                                                LIMIT 3");
    if ($oid->num_rows == 0) {
        $body->setContent("other_products", '
            <div class="content-title-section" style="margin: 100px 0 !important;">
                <h3 class="title title-center mb-3">Non ci sono altri articoli in questa categoria</h3>
            </div>
        ');
    } else {
        $other_products = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/detail_other_products.html");
        do {
            $product = $oid->fetch_assoc();
            if ($product) {
                foreach ($product as $key => $value) {
                    $other_products->setContent($key, $value);
                }
            }
        } while ($product);
        $body->setContent("other_products", $other_products->get());
    }
}

/**
 * @param mysqli $mysqli
 * @param string $id
 * @param Template $body
 * @return bool|mysqli_result
 */
function findColorVariants(mysqli $mysqli, string $id, Template $body)
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
}

/**
 * @param mysqli $mysqli
 * @param string $id
 * @param Template $body
 * @return bool|mysqli_result
 */
function findSizeVariants(mysqli $mysqli, string $id, Template $body)
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
}
