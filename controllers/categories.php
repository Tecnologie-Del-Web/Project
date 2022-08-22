<?php

function categories() {
    $main = setupUser(false);

    $main->setContent("header", new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/shop-banner-sidebar.html"));

    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/shop-banner-sidebar.html");
    $main->setContent("content", $body->get());
    $main->close();
}