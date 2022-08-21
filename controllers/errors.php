<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/include/template2.inc.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/tags/utility.inc.php";


if (!str_starts_with($_SERVER['REQUEST_URI'], "/admin/")) {

    // TODO: Rendere parametrici i messaggi di errore

    $error = "404";
    $title = "Page not found";
    $description = "The page your are looking for does not exist!";

    $main = setupUser();
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/error-404.html");
    $error_data = array(
        "error" => $error,
        "title" => $title,
        "description" => $description,
    );

    foreach ($error_data as $key => $value) {
        $body->setContent($key, $value);
    }

    $main->setContent("content", $body->get());
} else {
    // $main = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/views/404.html");
    // $main->close();
    $main = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/404.html");
}
$main->close();