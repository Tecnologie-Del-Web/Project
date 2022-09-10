<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/template2.inc.php';
function customization()
{
    global $mysqli;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_FILES["logo"])) {
            $logo = $_FILES["logo"];
            $filename = $logo["name"];
            move_uploaded_file($logo["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/images/" . $filename);
            $mysqli->query("UPDATE customization SET logo = '{$filename}' WHERE customization_id = 1");
        }
        $site_name = $_POST['site_name'];
        $phone = $_POST['phone_number'];
        $email = $_POST['email_address'];
        $address = $_POST['personal_address'];
        $about = $_POST['about_info'];
        /*if (isset($_FILES["immagine_about"])){
            $filename_about = basename($_FILES["immagine_about"]["tmp_name"]). "." . substr($_FILES["immagine_about"]["name"], strpos($_FILES["immagine_about"]["name"], ".") + 1);
            move_uploaded_file($_FILES["immagine_about"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/uploads/".$filename_about);
            unlink($_SERVER['DOCUMENT_ROOT'] . "/uploads/".$mysqli->query("SELECT immagine_about FROM customization WHERE id = 1")->fetch_assoc()["immagine_about"] ?? "");
        } else {
            $filename_about = $mysqli->query("SELECT immagine_about FROM customization WHERE id = 1")->fetch_assoc()["immagine_about"];
        }*/
        $mysqli->query("UPDATE customization SET
                         phone_number = '{$phone}', 
                         email_address = '{$email}', 
                         personal_address='{$address}',
                         site_name = '{$site_name}',
                         about_info='{$about}'
                     WHERE customization_id = 1;");
        /*address = '{$address}',
        country = '{$country}',
        descrizione = '{$descrizione}',
        titolo_descrizione = '{$titolo_descrizione}',
        descrizione_estesa = '{$descrizione_estesa}',
        immagine_about = '{$filename_about}'");*/
        $response = array();
        if ($mysqli->affected_rows >= 0) {
            $response['success'] = "Personalizzazione aggiornata con successo";
        } else {
            $response['error'] = "Errore nell'aggiornamento della personalizzazione";
        }
        exit(json_encode($response));
    } else {
        $main = initAdmin();
        $customization = $mysqli->query("SELECT * FROM customization WHERE customization_id = 1")->fetch_assoc();
        $body = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/customization/customization.html");
        foreach ($customization as $key => $value) {
            $body->setContent($key, $value);
        }
        $main->setContent("content", $body->get());
        $main->close();
    }
}