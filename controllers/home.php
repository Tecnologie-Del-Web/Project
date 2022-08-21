<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/include/template2.inc.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/dbms.inc.php";

function home()
{
    $main = setupUser();

    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/index.html");
    $main->setContent("content", $body->get());
    $main->close();

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

    $main->setContent("title", "HOME");
    $main->setContent("content", $body->get());
    $main->close();
    */
}
