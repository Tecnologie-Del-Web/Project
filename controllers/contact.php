<?php

function contact() {
    $main = setupUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/contact-us.html");

    $main->setContent("content", $body->get());
    $main->close();
}
