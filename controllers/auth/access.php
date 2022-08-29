<?php

use JetBrains\PhpStorm\NoReturn;

require_once $_SERVER['DOCUMENT_ROOT'] . "/include/template2.inc.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/auth.inc.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/dbms.inc.php";

global $mysqli;

checkSession();

function sign_in() {
    echo "Here!";
    $main = setupUser(false);

    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/login.html#sign-in");

    $main->setContent("content", $body->get());
    $main->close();
}

function sign_up() {
    $main = setupUser(false);

    $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/login.html#sign-up");

    $main->setContent("content", $body->get());
    $main->close();
}

/**
 * Metodo per la gestione della pagina di accesso.
 * @return void
 */
function login()
{
    // Se non è autenticato
    if (!(isset($_SESSION['auth']) && $_SESSION['auth'] = true)) {
        // Se è una richiesta POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Eseguo il login
            doLogin();
            if (isset($_SESSION['auth']) && $_SESSION['auth'] = true) {
                // Se l'utente è autenticato dopo la funzione di login
                redirect($_POST['referrer'] ?? "");
            } else {
                // Se l'utente non è stato autenticato
                $main = setupMainAuth("login");
                $alert = setupAlert("Username o password errati.");
                $main->setContent("alert", $alert->get());
                $main->close();
            }
        } else {
            // Se è una richiesta GET
            $main = setupMainAuth("login");
            $main->close();
        }
        // Se l'utente è già autenticato, reindirizza alla home
    } else {
        redirect($_POST['referrer'] ?? "");
    }
}

/**
 * Registrazione di un utente.
 * @return void
 */
function register()
{
    if (!(isset($_SESSION['auth']) && $_SESSION['auth'] = true)) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            doRegister();
            if (isset($_SESSION['auth']) && $_SESSION['auth'] = true) {
                redirect($_POST['referrer'] ?? "");
            } else {
                $main = setupMainAuth("register");
                $alert = setupAlert("Email non disponibile!.");
                $main->setContent("alert", $alert->get());
                $main->close();
            }
        } else {
            $main = setupMainAuth("register");
            $main->close();
        }
    } else {
        redirect($_POST['referrer'] ?? "");
    }
}

/**
 * Logout di un utente.
 * @return void
 */
#[NoReturn] function logout(): void
{
    if ($_SESSION['auth'] = true) {
        unset($_SESSION['auth']);   //rimozione dell'autenticazione
        unset($_SESSION['user']);   //rimozione dell'utente
    }
    header("Location: /");
    exit;
}


/**
 * Utility per la redirezione alla home dopo login o registrazione
 * @return void
 */
#[NoReturn] function redirect($referrer): void
{
    //se è stato impostato un referrer reindirizza
    if ($referrer != "") {
        unset($_SESSION['referrer']);
        header("Location: $referrer");
        exit;
    } else if (isset($_SESSION['user']['script']['/admin']) && $_SESSION['user']['script']['/admin']) {
        Header("Location: /admin");               //se è un admin vai alla pagina di amministrazione
        exit;
    } else {
        Header("Location: /");                    //se non è admin vai alla home
        exit;
    }
}


