<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/Project/include/dbms.inc.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/Project/include/tags/utility.inc.php";

/**
 * Routing page
 */
// TODO: sistemare
// checkSession();

$request = strtok($_SERVER["REQUEST_URI"], '?');
const __CONTROLLERS__ = __DIR__ . '/controllers/';
global $mysqli;

$query = "SELECT * FROM service where '" . $request . "' like url ORDER BY LENGTH(url) DESC LIMIT 1;";
$oid = $mysqli->query($query);

if ($oid->num_rows > 0) {
    $oid = $oid->fetch_assoc();
    // Se si accede a una pagina pubblica
    if (!(str_starts_with($oid['script'], "user") || str_starts_with($oid['script'], "admin"))) {
        // Carico il controller
        $controller = $oid['script'];
        // Carico la funzione da eseguire
        $action = $oid['callback'];
        // Carico il file del controller
        $controller = __CONTROLLERS__ . $controller;
        require $controller;
        // Eseguo la funzione
        $action();
    } else
        // Se l'utente è autenticato
        if (isset($_SESSION['auth']) && $_SESSION['auth']) {
            // Se l'utente è autorizzato
            if (isset($_SESSION['user']['script'][$oid['url']])) {
                // Carico il controller
                $controller = $oid['script'];
                // Carico la funzione da eseguire
                $action = $oid['callback'];
                // Carico il file del controller
                $controller = __CONTROLLERS__ . $controller;
                require $controller;
                // Eseguo la funzione
                $action();
            } else {
                // Se è autenticato ma non ha accesso alla pagina, reindirizzo alla home
                Header("Location: /");
                exit;
            }
        } else {
            // Se non è autenticato reindirizza alla login
            Header("Location: /login?referrer=" . urlencode($request));
            exit;
        }
} else {
    // TODO: creare controller, se opportuno
    // Se la pagina non esiste, carico il controller degli errori
    // require __CONTROLLERS__ . 'errors.php';
}
