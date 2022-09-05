<?php

function about() {
    $main = initUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/about.html");

    $main->setContent("content", $body->get());
    $main->close();
}
