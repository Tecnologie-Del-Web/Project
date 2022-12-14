<?php


require_once $_SERVER['DOCUMENT_ROOT'] . "/include/template2.inc.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/auth.inc.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/dbms.inc.php";

global $mysqli;

startSessionIfNeeded();

function signIn()
{
    // Se non è autenticato
    if (!(isset($_SESSION['auth']) && $_SESSION['auth'] = true)) {
        // Se è una richiesta POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Eseguo il login
            doSignIn();
            if (isset($_SESSION['auth']) && $_SESSION['auth'] = true) {
                // Se l'utente è autenticato dopo la funzione di login
                redirect($_POST['referrer'] ?? "");
            } else {
                // Se l'utente non è stato autenticato
                $main = initAuth("sign-in");
                $main->close();
            }
        } else {
            // Se è una richiesta GET
            $main = initAuth("sign-in");
            $main->close();
        }
        // Se l'utente è già autenticato, reindirizza alla home
    } else {
        redirect($_POST['referrer'] ?? "");
    }
}


function signUp()
{
    if (!(isset($_SESSION['auth']) && $_SESSION['auth'] = true)) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            doSignUp();
            if (isset($_SESSION['auth']) && $_SESSION['auth'] = true) {
                redirect($_POST['referrer'] ?? "");
            } else {
                $main = initAuth("sign-up");
                $main->close();
            }
        } else {
            $main = initAuth("sign-up");
            $main->close();
        }
    } else {
        redirect($_POST['referrer'] ?? "");
    }
}


function signOut(): void
{
    if ($_SESSION['auth'] = true) {
        // Rimuovo dell'autenticazione
        unset($_SESSION['auth']);
        // Rimuovo dell'utente
        unset($_SESSION['user']);
    }
    Header("Location: /");
    exit;
}

function redirect($referrer): void
{
    // Se è stato impostato un referrer reindirizza
    if ($referrer != "") {
        unset($_SESSION['referrer']);
        header("Location: $referrer");
        exit;
    } else if (isset($_SESSION['user']['script']['/admin']) && $_SESSION['user']['script']['/admin']) {
        // Se è un admin vai alla pagina di amministrazione
        Header("Location: /admin");
        exit;
    } else {
        // Se non è admin vai alla home
        Header("Location: /");
        exit;
    }
}

function initAuth(string $page): Template
{
    startSessionIfNeeded();

    $main = initUser(false);
    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/" . $page . ".html");

    $body->setContent("referrer", $_GET['referrer'] ?? "");
    $main->setContent("content", $body->get());
    return $main;
}


