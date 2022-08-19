<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/include/template2.inc.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/tags/utility.inc.php";

if (!str_starts_with($_SERVER['REQUEST_URI'], "/skins/admin/")) {

    // TODO: Rendere parametrici i messaggi di errore

    $error = "404";
    $title = "Page not found";
    $description = "The page your are looking for does not exist!";

    $main = setupUser();
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/error-404.html");
    $data = array(
        "error" => $error,
        "title" => $title,
        "description" => $description,
    );

    foreach ($data as $key => $value) {
        $body->setContent($key, $value);
    }

    $main->setContent("content", $body->get());
} else {
    $main = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/404.html");
}
$main->close();
