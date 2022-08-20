<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/include/dbms.inc.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/template2.inc.php";

function admin(): void
{
    $main = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/index.html");
    $navbar = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/navbar.html");
    $footer = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/footer.html");

    $main->setContent("navbar", $navbar->get());
    $main->setContent("footer", $footer->get());
    $main->close();
}
