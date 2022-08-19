<?php

// Richiedo il file nel quale è dichiarata e definita la classe TagLibrary
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/template2.inc.php";

class Utility extends TagLibrary
{

    function notify($name, $data, $pars)
    {

        switch ($data) {
            case "00":
                $msg = "La transazione è andata a buon fine";
                $class = "alert alert-success";
                break;
            case "10":
                $msg = "Attenzione: Si è verificato un errore";
                $class = "alert alert-danger";
                break;
            case "11":
                $msg = "Attenzione: l'aggioramento non è andato a buon fine!";
                $class = "alert alert-danger";
                break;
            default:
                $msg = "";
                $class = "hidden_notification";
                break;

        }


        $result = "<div class=\"{$class}\"><button class=\"close\" data-dismiss=\"alert\"></button>{$msg}. </div>";

        return $result;

    }

    function show($name, $data, $pars)
    {
        global $mysqli;

        $main = new Template("skins/revision/dtml/slider.html");

        $oid = $mysqli->query("SELECT * FROM slider");

        if (!$oid) {
            echo "Error {$mysqli->errno}: {$mysqli->error}";
            exit;
        }

        $data = $oid->fetch_all(MYSQLI_ASSOC);

        foreach ($data as $slide) {

            $template = new Template("skins/revision/dtml/slide_{$slide['type']}.html");
            $template->setContent("title", $slide['title']);
            $template->setContent("subtitle", $slide['subtitle']);
            $main->setContent("item", $template->get());

        }

        return $main->get();

    }

    function report($name, $data, $pars)
    {

        global $mysqli;

        $report = new Template("skins/webarch/dtml/report.html");
        $report->setContent("name", $pars['name']);

        $oid = $mysqli->query("SELECT {$pars['fields']} FROM {$pars['table']}");
        if (!$oid) {
            // error
        }
        do {
            $data = $oid->fetch_assoc();
            if ($data) {
                foreach ($data as $key => $value) {
                    $report->setContent($key, $value);
                }
            }

        } while ($data);

        return $report->get();
    }
}

// TODO: completare!
function setupUser()
{
    checkSession();
    global $mysqli;

    $main = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/index.html");

    if (isset($_SESSION['user'])) {
        $logged = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/frontend/wolmart/components/user/logged.html");
        if (isset($_SESSION['user']['script']['/admin'])) {
            $logged->setContent("admin", "<li><a href='/admin'>Administration</a></li>");
        }
        $main->setContent("user_bar", $logged->get());
    } else {
        $unlogged = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/wizym/dtml/components/user/unlogged.html");
        $unlogged->setContent("referrer", "?referrer=" . urlencode($_SERVER['REQUEST_URI']));
        $main->setContent("user_bar", $unlogged->get());
    }

    $header = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/wizym/dtml/components/header.html");
    $footer = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/wizym/dtml/components/footer.html");
    $customization = $mysqli->query("SELECT personal_image FROM customization WHERE customization_id = 1")->fetch_assoc();
    if ($customization) {
        if ($customization["logo"] != "") {
            $header->setContent("logo", "/uploads/" . $customization["logo"]);
            $footer->setContent("logo", "/uploads/" . $customization["logo"]);
        } else {
            $header->setContent("logo", "https://via.placeholder.com/150");
            $footer->setContent("logo", "https://via.placeholder.com/150");
        }
    }

    $main->setContent("header", $header->get());
    $main->setContent("footer", $footer->get());
    return $main;
}

function checkSession(): void
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

?>