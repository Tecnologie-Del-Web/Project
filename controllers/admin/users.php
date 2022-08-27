<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/include/template2.inc.php";

function users()
{
    global $mysqli;
    $columns = array(
        "ID",
        "Username",
        "Nome",
        "Cognome",
        "Email",
        "Telefono"
    );
    $result = $mysqli->query("SELECT user_id,  username, name,  surname, email_address, phone_number  FROM user");
    $main = initAdmin();
    $table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/table.html");
    $table->setContent("title", "Utenti");
    foreach ($columns as $column) {
        $table->setContent("column_name", $column);
    }
    $users_table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/users/users_table.html");
    while ($users = $result->fetch_assoc()) {
        foreach ($users as $key => $value) {
            $users_table->setContent($key, $value);
        }
    }
    $table->setContent("table_rows", $users_table->get());
    $main->setContent("content", $table->get());
    $main->close();
}
