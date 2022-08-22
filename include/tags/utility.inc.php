<?php

// Richiedo il file nel quale è dichiarata e definita la classe TagLibrary
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/template2.inc.php";


// TODO: add customization
function initAdmin()
{
    $main = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/index.html");
    $header = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/header.html");
    $navbar = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/navbar.html");
    $footer = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/footer.html");

    $main->setContent("header", $header->get());
    $main->setContent("navbar", $navbar->get());
    $main->setContent("footer", $footer->get());
    return $main;
}

// TODO: completare!
function setupUser()
{
    checkSession();
    global $mysqli;

    $main = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/main.html");

    /*
    if (isset($_SESSION['user'])) {
        $logged = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/components/user/logged.html");
        if (isset($_SESSION['user']['script']['/admin'])) {
            $logged->setContent("admin", "<li><a href='/admin'>Administration</a></li>");
        }
        $main->setContent("user_bar", $logged->get());
    } else {
        $unlogged = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/wizym/dtml/components/user/unlogged.html");
        $unlogged->setContent("referrer", "?referrer=" . urlencode($_SERVER['REQUEST_URI']));
        $main->setContent("user_bar", $unlogged->get());
    }
    */

    $header = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/header.html");
    $footer = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/partials/footer.html");
    /*
    $customization = $mysqli->query("SELECT personal_image FROM customization WHERE customization_id = 1")->fetch_assoc();
    if ($customization) {
        if ($customization["logo"] != "") {
            $header->setContent("logo", "/uploads/" . $customization["logo"]);
            $footer->setContent("logo", "/uploads/" . $customization["logo"]);
        } else {
            $header->setContent("logo", "https://via.placeholder.com/150");
            $footer->setContent("logo", "https://via.placeholder.com/150");
        }
    }
    */

    $main->setContent("header", $header->get());
    $main->setContent("footer", $footer->get());
    return $main;
}

function checkSession(): void
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

