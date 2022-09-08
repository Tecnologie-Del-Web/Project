<?php

function order() {
    $main = initUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/order.html");

    $main->setContent("content", $body->get());
    $main->close();
}
