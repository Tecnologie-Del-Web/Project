<?php

function checkout() {
    $main = initUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/checkout.html");

    $main->setContent("content", $body->get());
    $main->close();
}
