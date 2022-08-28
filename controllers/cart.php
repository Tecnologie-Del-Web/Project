<?php

function cart() {
    $main = setupUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/cart.html");

    $main->setContent("content", $body->get());
    $main->close();
}
