<?php

function about() {
    $main = setupUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/about.html");

    $main->setContent("content", $body->get());
    $main->close();
}