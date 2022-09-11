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
        "Telefono",
        "Gruppo"
    );
    $result = $mysqli->query("SELECT user_id, username, name, surname, email_address, phone_number FROM user");
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

        $groups = $mysqli->query("SELECT group_name FROM `group`
                                                JOIN user_has_group uhg on `group`.group_id = uhg.group_id
                                                JOIN user u on uhg.user_id = u.user_id
                                                WHERE u.user_id = " . $users["user_id"]);
        $groups_str = "";
        do {
            $group = $groups->fetch_assoc();
            if ($group) {
                $groups_str = $groups_str . $group["group_name"] . ", "; //concatena i gruppi
            }
        } while ($group);
        $groups_str = substr($groups_str, 0, -2);
        $users_table->setContent("groups", $groups_str);
    }

    $table->setContent("table_rows", $users_table->get());
    $main->setContent("content", $table->get());
    $main->close();
}

function user(){
    global $mysqli;
    $user_id = explode('/', $_SERVER['REQUEST_URI'])[3];
    $utente = $mysqli->query("SELECT * FROM user WHERE user_id = $user_id");
    if ($utente->num_rows == 0) {
        header("Location: /admin/users");
    } else {
        $utente = $utente->fetch_assoc();
        $main = initAdmin();
        $show = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/users/edit_user.html");
        foreach ($utente as $key => $value) {
            $show->setContent($key, $value);
        }

        $groups = $mysqli->query("SELECT group_id, group_name FROM `group`");
        $user_groups = $mysqli->query("SELECT group_name FROM `group`
                                                JOIN user_has_group uhg on `group`.group_id = uhg.group_id
                                                JOIN user u on uhg.user_id = u.user_id
                                                WHERE u.user_id = " . $user_id);
        $user_groups_array = array();
        do {
            $user_group = $user_groups->fetch_assoc();
            if ($user_group) {
                $user_groups_array[] = $user_group["group_name"];
            }
        } while ($user_group);

        do {
            $group = $groups->fetch_assoc();
            if ($group) {
                if (!in_array($group["group_name"], $user_groups_array)) {
                    $show->setContent("select", "unselected");
                } else {
                    $show->setContent("select", "selected");
                }
                $show->setContent("group_id", $group["group_id"]);
                $show->setContent("group_name", $group["group_name"]);
            }
        } while ($group);
        $main->setContent("content", $show->get());
        $main->close();
    }
}

function edit(){
    global $mysqli;
    $user_id = $_POST["user_id"];
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $username = $_POST["username"];
    $email = $_POST["email_address"];
    $phone_number = $_POST["phone_number"];
    $response = array();
    if ($user_id != "" && $name != "" && $surname != "" && $email != "" && $phone_number != "" && $username != "") {
        $mysqli->query("UPDATE user SET 
                name = '$name', 
                surname = '$surname', 
                username = '$username',
                email_address = '$email', 
                phone_number = '$phone_number'
            WHERE user_id = $user_id");

        if (isset($_POST["groups"])) {
            $groups = $_POST["groups"];
            $mysqli->query("DELETE FROM user_has_group WHERE user_id = $user_id");
            foreach ($groups as $group) {
                $mysqli->query("INSERT INTO user_has_group (user_id, group_id) VALUES ($user_id, $group)");
            }
        }
        if($mysqli->affected_rows == 1){
            $response['success'] = "Utente modificato con successo";
        } elseif($mysqli->affected_rows == 0 ) {
            $response['warning'] = "Nessun dato modificato";
        } else {
            $response['error'] = "Errore nella modifica dell'utente";
        }

    } else {
        $response['error'] = "Errore nella modifica dell'utente";
    }
    exit(json_encode($response));
}