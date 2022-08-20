<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/include/template2.inc.php";

function index()
{
    $main = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/index.html");
    $navbar = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/navbar.html");

    $main->setContent("navbar", $navbar->get());

    $main->close();
}
