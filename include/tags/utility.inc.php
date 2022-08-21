<?php

// Richiedo il file nel quale è dichiarata e definita la classe TagLibrary
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/template2.inc.php";


// TODO: completare!
function setupAdmin()
{
    global $mysqli;
    $main = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sash/dtml/views/main.html");
    // Default set delle parti statiche
    $header = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sash/dtml/components/header.html");
    $customization = $mysqli->query("SELECT personal_image FROM customization WHERE customization_id = 1")->fetch_assoc();
    $header->setContent("logo", "/uploads/" . $customization["logo"] ?? "https://via.placeholder.com/150");
    $header->setContent("nome_utente", $_SESSION["user"]["nome"] . " " . $_SESSION["user"]["cognome"]);
    $main->setContent("header", $header->get());

    $sidebar = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sash/dtml/components/sidebar.html");
    $sidebar->setContent("logo", "/uploads/" . $customization["logo"] ?? "https://via.placeholder.com/150");
    $main->setContent("sidebar", $sidebar->get());
    $main->setContent("footer", (new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sash/dtml/components/footer.html"))->get());
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

?>