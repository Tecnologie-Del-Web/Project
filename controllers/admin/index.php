<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/include/template2.inc.php";

function admin(): void
{
    $main = initAdmin();
    $content = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/analytics.html");
    $main->setContent("content", $content->get());
    $main->close();
}
