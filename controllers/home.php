<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/include/template2.inc.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/dbms.inc.php";

function home()
{
    global $mysqli;

    $main = initUser(true);

    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/index.html");

    setupOfferOfTheDay($mysqli, $body);

    setupMostLovedCategories($mysqli, $body);

    setupHomePageCategories($mysqli, $body);

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
    $product = $mysqli->query("SELECT p.product_id, p.product_name, p.price, p.product_description, o.percentage, DATE_FORMAT(o.end_date, '%d/%m/%Y' ) as end_date, pi.file_name
                                    FROM product p JOIN offer o ON (o.product_id = p.product_id) JOIN  product_image pi ON (pi.product_id = p.product_id) 
                                    WHERE pi.type = 'main'
                                    ORDER BY o.start_date DESC
                                    LIMIT 1");

    if ($product->num_rows == 0) {
        $body->setContent("offer_of_the_day", '
            <div class="content-title-section" style="margin: 100px 0 !important; text-align: center !important;">
                <h3 class="title-center">Oggi non ci sono sconti. Torna a trovarci al più presto!</h3>
            </div>
        ');
    } else {
        $offer_of_the_day = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/home/offer-of-the-day.html");
        $product = $product->fetch_assoc();
        $product_id = $product['product_id'];
        $ratings = $mysqli->query("SELECT ROUND(AVG(pr.rating), 2) as average_rating, COUNT(pr.rating) as number_of_reviews
                                    FROM product p JOIN product_review pr ON (pr.product_id = p.product_id) 
                                    WHERE p.product_id=$product_id");
        // Gestire l'eventuale assenza di recensioni del prodotto in questione
        $ratings = $ratings->fetch_assoc();
        foreach ($ratings as $key => $value) {
            $offer_of_the_day->setContent($key, $value);
        }
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
        $most_loved_categories = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/home/most-loved-categories.html");
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
    $oid = $mysqli->query("SELECT c.category_id, c.category_name, p.product_id, p.product_name, pi.file_name
                                    FROM category c JOIN product p ON (p.category_id = c.category_id) JOIN product_image pi ON (pi.product_id = p.product_id) 
                                    WHERE category_name = 'Abbigliamento' AND pi.type = 'main';");

    if ($oid->num_rows == 0) {
        $body->setContent("clothing", '');
    } else {
        $clothing_articles = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/home/home-page-category.html");
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
    $oid = $mysqli->query("SELECT c.category_id, c.category_name, p.product_id, p.product_name, pi.file_name
                                    FROM category c JOIN product p ON (p.category_id = c.category_id) JOIN product_image pi ON (pi.product_id = p.product_id) 
                                    WHERE category_name = 'Libri' AND pi.type = 'main';");

    if ($oid->num_rows == 0) {
        $body->setContent("books", '');
    } else {
        $books = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/home/home-page-category.html");
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
    $oid = $mysqli->query("SELECT c.category_id, c.category_name, p.product_id, p.product_name, pi.file_name
                                    FROM category c JOIN product p ON (p.category_id = c.category_id) JOIN product_image pi ON (pi.product_id = p.product_id) 
                                    WHERE category_name = 'Elettronica' AND pi.type = 'main';");

    if ($oid->num_rows == 0) {
        $body->setContent("electronics", '');
    } else {
        $electronics_articles = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/home/home-page-category.html");
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
