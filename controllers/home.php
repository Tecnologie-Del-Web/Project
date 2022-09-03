<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/include/template2.inc.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/dbms.inc.php";

function home()
{
    global $mysqli;

    $main = setupUser(true);

    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/index.html");

    setupOfferOfTheDay($mysqli, $body);

    setupMostLovedCategories($mysqli, $body);

    setupHomePageCategories($mysqli, $body);

    /*
    $customization = $mysqli->query("SELECT about_info, personal_image FROM customization WHERE customization_id = 1")->fetch_assoc();
    if ($customization) {
        $body->setContent("about_info", $customization["about_info"]);
        if ($customization["personal_image"] != "") {
            $body->setContent("personal_image", "/uploads/".$customization["personal_image"]);
        } else {
            $body->setContent("personal_image", "https://via.placeholder.com/500");
        }
    }

    foreach ($customization as $key => $value) {
        if ($key == "personal_image" && $value != "") {
            $value = "/uploads/" . $value;
        } else {
            $value = "https://via.placeholder.com/500";
        }
        $body->setContent($key, $value);
    }

    // TODO: completare query
    $oid = $mysqli->query("SELECT p.product_id, p.product_name, p.price, o.percentuale as discount
                                FROM product p
                                LEFT JOIN discount d on p.product_id = o.prodotti_id
                                WHERE prodotti.quantita_disponibile > 0 
                                ORDER BY data_inserimento DESC, prodotti.id  
                                LIMIT 10");

    do {
        $prodotto = $oid->fetch_assoc();
        if ($prodotto) {
            $prodotto['sconto'] = $prodotto['sconto'] ? " <span class='sconto' id='new_percentuale{$prodotto['id']}'>-{$prodotto['sconto']}%</span>" : "";

            $image = $mysqli->query("SELECT nome_file FROM immagini WHERE immagini.prodotto_id = {$prodotto['id']} LIMIT 1");
            $image = $image->fetch_assoc();
            if (!$image) {
                $image = 'https://via.placeholder.com/500';
            } else {
                $image = '/uploads/' . $image["nome_file"];
            }
            $body->setContent('image', $image);

            if (isset($_SESSION["user"])) {
                $like = $mysqli->query("SELECT * FROM users_has_prodotti_preferiti WHERE users_id = " . $_SESSION["user"]["id"] . " AND prodotti_id = " . $prodotto["id"]);
                if ($like->num_rows == 0) {
                    $body->setContent("like", "
                                <div class='add-cart'>
                                    <a class='like2 heart'><i class='fa fa-heart-o' data-id='{$prodotto["id"]}'></i></a>
                                </div>");
                } else {
                    $body->setContent("like", "
                                <div class='add-cart'>
                                    <a class='like2 heart'><i class='fa fa-heart' data-id='{$prodotto["id"]}'></i></a>
                                </div>");
                }
            }
            foreach ($prodotto as $key => $value) {
                $body->setContent($key, $value);
            }
        }
    } while ($prodotto);

    $oid = $mysqli->query("SELECT prodotti.id as top_id, prodotti.nome as top_nome, prodotti.prezzo as top_prezzo, ROUND(AVG(voto), 1) as voto_medio, 
                                        COUNT(*) as numero, percentuale as top_sconto
                                    FROM prodotti
                                             JOIN recensioni r on prodotti.id = r.prodotti_id
                                             LEFT JOIN offerte o on prodotti.id = o.prodotti_id
                                    WHERE prodotti.quantita_disponibile > 0
                                    GROUP BY prodotti.id, prodotti.nome, prodotti.prezzo
                                    ORDER BY voto_medio DESC, numero DESC, prodotti.nome
                                    LIMIT 10");

    do {
        $prodotto = $oid->fetch_assoc();
        if ($prodotto) {
            $prodotto['top_sconto'] = $prodotto['top_sconto'] ? " <span class='sconto' id='rec_percentuale{$prodotto['top_id']}'>-{$prodotto['top_sconto']}%</span>" : "";

            $image = $mysqli->query("SELECT nome_file FROM immagini WHERE immagini.prodotto_id = {$prodotto['top_id']} LIMIT 1");
            $image = $image->fetch_assoc();
            if (!$image) {
                $image = 'https://via.placeholder.com/500';
            } else {
                $image = '/uploads/' . $image["nome_file"];
            }
            $body->setContent('top_image', $image);

            if (isset($_SESSION["user"])) {
                $like = $mysqli->query("SELECT * FROM users_has_prodotti_preferiti WHERE users_id = " . $_SESSION["user"]["id"] . " AND prodotti_id = " . $prodotto["top_id"]);
                if ($like->num_rows == 0) {
                    $body->setContent("top_like", "
                                <div class='add-cart'>
                                    <a class='like2 heart'><i class='fa fa-heart-o' data-id='{$prodotto["top_id"]}'></i></a>
                                </div>");
                } else {
                    $body->setContent("top_like", "
                                <div class='add-cart'>
                                    <a class='like2 heart'><i class='fa fa-heart  data-id='{$prodotto["top_id"]}'></i></a>
                                </div>");
                }
            }
            foreach ($prodotto as $key => $value) {
                $body->setContent($key, $value);
            }
        }
    } while ($prodotto);
    */

    $main->setContent("content", $body->get());
    $main->close();
}

/**
 * @param mysqli $mysqli
 * @param Template $body
 * @return void
 */
function setupOfferOfTheDay(mysqli $mysqli, Template $body): void
{
    // Gestisco l'offerta del giorno
    $product = $mysqli->query("SELECT p.product_id, p.product_name, p.price, o.percentage, o.end_date, pv.variant_id, pi.file_name
                                    FROM product p JOIN offer o ON (o.product_id = p.product_id) JOIN product_variant pv ON (pv.product_id = p.product_id) JOIN product_image pi ON (pi.variant_id = pv.variant_id) 
                                    WHERE pv.default = true AND pi.type = 'main'
                                    ORDER BY o.start_date DESC
                                    LIMIT 1");

    if ($product->num_rows == 0) {
        $body->setContent("offer_of_the_day", '
            <div class="content-title-section" style="margin: 100px 0 !important; text-align: center !important;">
                <h3 class="title-center">Oggi non ci sono sconti. Torna a trovarci al più presto!</h3>
            </div>
        ');
    } else {
        $offer_of_the_day = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/home/offer_of_the_day.html");
        $product = $product->fetch_assoc();
        foreach ($product as $key => $value) {
            $offer_of_the_day->setContent($key, $value);
        }
        $body->setContent("offer_of_the_day", $offer_of_the_day->get());
    }
}

/**
 * @param mysqli $mysqli
 * @param Template $body
 * @return void
 */
function setupMostLovedCategories(mysqli $mysqli, Template $body)
{
    // Gestisco le categorie più amate
    $oid = $mysqli->query("SELECT c.category_id, c.category_name, c.category_image
                                    FROM category c
                                    LIMIT 6");

    if ($oid->num_rows == 0) {
        $body->setContent("most_loved_categories", '
            <div class="content-title-section" style="margin: 100px 0 !important;">
                <h3 class="sub-title title-center ml-3 mb-3">Non ci sono categorie!</h3>
            </div>
        ');
    } else {
        $most_loved_categories = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/home/most_loved_categories.html");
        do {
            $most_loved_category = $oid->fetch_assoc();
            if ($most_loved_category) {
                foreach ($most_loved_category as $key => $value) {
                    $most_loved_categories->setContent($key, $value);
                }
            }
        } while ($most_loved_category);
        $body->setContent("most_loved_categories", $most_loved_categories->get());
    }
}

/**
 * @param mysqli $mysqli
 * @param Template $body
 * @return void
 */
function setupHomePageCategories(mysqli $mysqli, Template $body): void
{
    // Seleziono Abbigliamento
    $oid = $mysqli->query("SELECT c.category_id, c.category_name, p.product_id, p.product_name, pv.variant_id, pi.file_name
                                    FROM category c JOIN product p ON (p.category_id = c.category_id) JOIN product_variant pv ON (pv.product_id = p.product_id) JOIN product_image pi ON (pi.variant_id = pv.variant_id) 
                                    WHERE category_name = 'Abbigliamento' AND pv.`default` = true AND pi.type = 'main';");

    if ($oid->num_rows == 0) {
        $body->setContent("clothing", '');
    } else {
        $clothing_articles = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/home/home_page_category.html");
        do {
            $clothing_article = $oid->fetch_assoc();
            if ($clothing_article) {
                foreach ($clothing_article as $key => $value) {
                    $clothing_articles->setContent($key, $value);
                }
            }
        } while ($clothing_article);
        $body->setContent("clothing", $clothing_articles->get());
    }

    // Seleziono Libri
    $oid = $mysqli->query("SELECT c.category_id, c.category_name, p.product_id, p.product_name, pv.variant_id, pi.file_name
                                    FROM category c JOIN product p ON (p.category_id = c.category_id) JOIN product_variant pv ON (pv.product_id = p.product_id) JOIN product_image pi ON (pi.variant_id = pv.variant_id) 
                                    WHERE category_name = 'Libri' AND pv.`default` = true AND pi.type = 'main';");

    if ($oid->num_rows == 0) {
        $body->setContent("books", '');
    } else {
        $books = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/home/home_page_category.html");
        do {
            $book = $oid->fetch_assoc();
            if ($book) {
                foreach ($book as $key => $value) {
                    $books->setContent($key, $value);
                }
            }
        } while ($book);
        $body->setContent("books", $books->get());
    }

    // Seleziono Elettronica
    $oid = $mysqli->query("SELECT c.category_id, c.category_name, p.product_id, p.product_name, pv.variant_id, pi.file_name
                                    FROM category c JOIN product p ON (p.category_id = c.category_id) JOIN product_variant pv ON (pv.product_id = p.product_id) JOIN product_image pi ON (pi.variant_id = pv.variant_id) 
                                    WHERE category_name = 'Elettronica' AND pv.`default` = true AND pi.type = 'main';");

    if ($oid->num_rows == 0) {
        $body->setContent("electronics", '');
    } else {
        $electronics_articles = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/home/home_page_category.html");
        do {
            $electronic_article = $oid->fetch_assoc();
            if ($electronic_article) {
                foreach ($electronic_article as $key => $value) {
                    $electronics_articles->setContent($key, $value);
                }
            }
        } while ($electronic_article);
        $body->setContent("electronics", $electronics_articles->get());
    }


}
