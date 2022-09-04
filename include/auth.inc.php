<?php

const ERROR_SCRIPT_PERMISSION = 100;
const ERROR_USER_NOT_LOGGED = 200;
const ERROR_OWNERSHIP = 200;

function crypto($pass): string
{
    return md5(md5(md5(md5(md5($pass)))));
}

function isOwner($resource, $key = "id"): bool
{
    global $mysqli;

    // Controlla se il possessore della username esiste
    $oid = $mysqli->query("SELECT * FROM {$resource} WHERE {$key} = '{$_REQUEST[$key]}'");

    // Se l'utente non viene trovato
    if (!$oid) {
        return false;
    }

    $data = $oid->fetch_assoc();

    if ($data['email'] != $_SESSION['user']['email']) {
        Header("Location: error.php?code=" . ERROR_OWNERSHIP);
        exit;
    } else {
        return true;
    }
}

function doSignIn(): void
{
    global $mysqli;

    // Se la post contiene email e password
    if (isset($_POST['email']) and isset($_POST['password'])) {

        //Ottiene l'utente dalla tabella users
        $oid = $mysqli->query("
            SELECT id, nome, cognome, email, telefono
            FROM user u
            WHERE email = '" . $_POST['email'] . "'
            AND password = '" . crypto($_POST['password']) . "'");


        //Se oid non è settato allora c'è un errore
        if (!$oid) {
            trigger_error("Generic error, level 21", E_USER_ERROR);
        }

        //Se viene restituito un numero di righe maggiore di 0 allora l'utente esiste
        if ($oid->num_rows > 0) {
            //Ottiene i dati dell'utente
            $user = $oid->fetch_assoc();
            createSession($user, $mysqli);
        }
    }
}

function doSignUp(): void
{
    global $mysqli;

    // Se la post contiene tutti i campi
    if (isset($_POST['name']) and
        isset($_POST['surname']) and
        isset($_POST['phone_number']) and
        isset($_POST['email']) and
        isset($_POST['username']) and
        isset($_POST['password'])) {

        // Controlla se l'email è già presente
        $oid = $mysqli->query("SELECT u.user_id FROM user u WHERE u.email_address = '{$_POST['email']}'");
        if (!$oid) {
            trigger_error("Generic error, level 21", E_USER_ERROR);
        }
        // Se oid non è settato allora un utente con questa email esiste già
        if ($oid->num_rows > 0) {
            return;
        } else {
            // Inserisce l'utente nel database
            $oid = $mysqli->query("INSERT INTO user (email_address, name, surname, phone_number, username, password) 
                VALUES ('" . $_POST['email'] . "', 
                '" . $_POST['name'] . "',
                '" . $_POST['surname'] . "',
                '" . $_POST['phone_number'] . "',
                '" . $_POST['username'] . "',
                '" . crypto($_POST['password']) . "')");

            if (!$oid) {
                trigger_error("Generic error, level 21", E_USER_ERROR);
            }

            $oid = $mysqli->query("SELECT * FROM user u WHERE u.email_address = '{$_POST['email']}'");

            if (!$oid) {
                trigger_error("Generic error, level 21", E_USER_ERROR);
            }

            if ($oid->num_rows > 0) {
                $user = $oid->fetch_assoc();
                // Do all'utente i permessi da user
                $oid = $mysqli->query("INSERT INTO user_has_group (user_id, group_id) VALUES ({$user['user_id']}, 2);");
                if (!$oid) {
                    trigger_error("Generic error, level 21", E_USER_ERROR);
                }
                createSession($user, $mysqli);
            }
        }
    }
}

function createSession($user, mysqli $mysqli): void
{
    // Crea una sessione per l'utente
    $_SESSION['auth'] = true;
    $_SESSION['user'] = $user;

    // Ottiene i permessi dell'utente
    $oid = $mysqli->query("
                SELECT DISTINCT s.script, s.url FROM user u 
                JOIN user_has_group ug ON (ug.user_id = u.user_id)
                JOIN service_has_group sg ON (sg.group_id = user_has_group.group_id) 
                JOIN service s
                ON s.service_id = sg.service_id
                WHERE u.email_address = '" . $_POST['email'] . "'");

    // Se oid non è settato allora c'è un errore
    if (!$oid) {
        trigger_error("Generic error, level 40", E_USER_ERROR);
    }

    // Imposta i permessi di accesso dell'utente
    do {
        $data = $oid->fetch_assoc();
        if ($data) {
            $scripts[$data['url']] = true;
        }
    } while ($data);

    $_SESSION['user']['script'] = $scripts;
    // if (!isset($_SESSION['user']['script'][basename($_SERVER['SCRIPT_NAME'])])) {
    if (!isset($_SESSION['user']['script'])) {
        unset($_SESSION['auth']);
        unset($_SESSION['user']);
    }
}
?>