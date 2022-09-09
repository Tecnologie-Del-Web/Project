<?php

// Richiedo il file nel quale è dichiarata e definita la classe TagLibrary
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/template2.inc.php";


function initAdmin()
{
    startSessionIfNeeded();
    global $mysqli;

    $main = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/index.html");
    $header = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/header.html");
    $navbar = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/navbar.html");
    $footer = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/footer.html");

    $main->setContent("header", $header->get());
    $main->setContent("navbar", $navbar->get());
    $main->setContent("footer", $footer->get());
    return $main;
}

function initUser(bool $dropdown = true)
{
    startSessionIfNeeded();

    global $mysqli;

    $main = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/main.html");

    if ($dropdown)
        $header = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/header.html");
    else
        $header = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/dropdown-header.html");

    $footer = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/footer.html");

    $oid = $mysqli->query("SELECT c.category_id, c.category_name
                                FROM category c
                                ORDER BY c.category_id");

    do {
        $category = $oid->fetch_assoc();
        if ($category) {
            foreach ($category as $key => $value) {
                $header->setContent($key, $value);
                // Aggiungo la categoria alla barra di ricerca
                // Il template engine non consente di farlo con una sola variabile
                $header->setContent("bar_" . $key, $value);
            }
        }
    } while ($category);

    if (isset($_SESSION['user'])) {
        $logged_in = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/user/logged-in.html");
        if (isset($_SESSION['user']['script']['/admin'])) {
            $logged_in->setContent("admin", "<li><a href='/admin'>Amministrazione</a></li>");
        }
        $header->setContent("login_status", $logged_in->get());
    } else {
        $to_log = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/user/to-log.html");
        $to_log->setContent("referrer", "?referrer=".urlencode($_SERVER['REQUEST_URI']));
        $header->setContent("login_status", $to_log->get());
    }

    $customization = $mysqli->query("SELECT phone_number, site_name FROM customization");

    if ($customization->num_rows > 0) {
        $customization = $customization->fetch_assoc();
        foreach ($customization as $key => $value) {
            $header->setContent($key, $value);
            $main->setContent($key,$value);
            $footer->setContent($key,$value);
        }
    }

    $main->setContent("header", $header->get());
    $main->setContent("footer", $footer->get());

    return $main;
}

function startSessionIfNeeded(): void
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

