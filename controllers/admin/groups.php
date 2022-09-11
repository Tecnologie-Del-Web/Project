<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/template2.inc.php";

function groups()
{
    global $mysqli;
    $columns = array(
        "ID",
        "Nome",
        "Descrizione"
    );

    $main = initAdmin();
    $result = $mysqli->query("SELECT group_id, group_name, group_description FROM `group`");
    $table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/table.html");
    $table->setContent("title", "Gruppi");

    foreach ($columns as $column) {
        $table->setContent("column_name", $column);
    }

    $groups_table = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/groups/groups_table.html");
    while ($groups = $result->fetch_assoc()) {
        foreach ($groups as $key => $value) {
            $groups_table->setContent($key, $value);
        }
    }

    $table->setContent("table_rows", $groups_table->get());

    do {
        $groups = $result->fetch_assoc();
        if ($groups) {
            if ($groups["group_name"] == "admin") {
                $groups_table->setContent("group_id", 1);
                $groups_table->setContent("group_name", "Admin");
                $groups_table->setContent("actions", "<div class='g-2 text-center'>-</div>");

            } else if ($groups["group_name"] == "user") {
                $groups_table->setContent("group_id", 2);
                $groups_table->setContent("group_name", "User");
                $groups_table->setContent("actions", "<div class='g-2 text-center'>-</div>");

            } else {
                $actions = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/groups/actions.html");
                $groups_table->setContent("id", $groups["group_id"]);
                $groups_table->setContent("group_name", $groups["group_name"]);
                $actions->setContent("id", $groups["group_id"]);
                $groups_table->setContent("actions", $actions->get());
            }
        }
    } while ($groups);
    $main->setContent("content", $table->get());
    $main->close();
}

function group()
{
    global $mysqli;
    $group_id = explode('/', $_SERVER['REQUEST_URI'])[3];
    //prende tutte le informazioni del gruppo da mostrare
    $group = $mysqli->query("SELECT DISTINCT * FROM `group` WHERE group_id = $group_id");

    if ($group->num_rows == 0) {    //se non ci sono risultati reindirizza alla index dei gruppi
        header("Location: /admin/groups");
    } else {
        $main = initAdmin();
        $content = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/groups/edit_group.html");

        $group = $group->fetch_assoc();
        $content->setContent("group_id", $group['group_id']);
        $content->setContent("group_name", $group['group_name']);
        $content->setContent("description", $group['group_description']);

        $tags = $mysqli->query("SELECT DISTINCT service.tag FROM service WHERE service.tag NOT LIKE 'Public' AND service.tag NOT LIKE 'Gestione gruppi'");

        if ($tags->num_rows > 0) {
            $powers_tmp = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/groups/powers.html");
            do {
                $group_tags = $tags->fetch_assoc();

                if ($group_tags) {
                    foreach ($group_tags as $value) { //per ogni tag seleziono tutte le operazioni associate
                        $powers = $mysqli->query("
                            SELECT service.service_id, service.service_description
                            FROM service
                            WHERE service.tag = '{$value}';"
                        );

                        $powers_tmp->setContent("tag", $group_tags['tag']); //inserisco il tag nel template come titolo
                        $power_tmp = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/groups/power.html");

                        do {
                            $group_powers = $powers->fetch_assoc();
                            if ($group_powers) {
                                //controllo se il servizio è associato al gruppo
                                $check = $mysqli->query("
                                        SELECT service.service_id
                                        FROM service
                                        JOIN service_has_group shg on service.service_id = shg.service_id
                                        WHERE service.tag = '{$value}' AND group_id = $group_id AND service.service_id = {$group_powers['service_id']};");
                                $check = $check->fetch_assoc();
                                if ($check) {
                                    $power_tmp->setContent("checked", "checked");
                                } else {
                                    $power_tmp->setContent("checked", "");
                                }
                                //inserisco i campo nella checkbox
                                $power_tmp->setContent('service_id', $group_powers['service_id']);
                                $power_tmp->setContent('description', $group_powers['service_description']);
                            }
                        } while ($group_powers);
                        //inserisco il singolo potere nella lista dei poteri del tag
                        $powers_tmp->setContent("power", $power_tmp->get());
                    }
                }
            } while ($group_tags);
            $content->setContent("powers", $powers_tmp->get());
            $main->setContent("content", $content->get());
            $main->close();
        }
    }
}

function delete()
{
    global $mysqli;
    $id = explode('/', $_SERVER['REQUEST_URI'])[3];

    if ($id != 1 && $id != 2) {
        $mysqli->query("DELETE FROM `group` WHERE group_id = $id");
        if ($mysqli->affected_rows == 1) {
            $response['success'] = "Gruppo eliminato con successo";
        } else {
            $response['error'] = "Errore nell'eliminazione del gruppo";
        }
    } else {
        $response['error'] = "Impossibile eliminare i gruppi predefiniti";
    }
    exit(json_encode($response));
}

function create()
{
    global $mysqli;
    $response = array();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['group_name'])) {
            $group_name = $_POST["group_name"];
            $oid = $mysqli->query("SELECT group_id FROM `group` WHERE group_name = '$group_name'");
            if ($oid->num_rows != 0) {
                $response['error'] = "Nome già esistente";
                exit(json_encode($response));
            }

            $group_description = $_POST["group_description"];
            $powers = $_POST["powers"] ?? null;
            $mysqli->query("INSERT INTO `group` (group_name,group_description) VALUES ('$group_name','$group_description');");
            if ($mysqli->affected_rows == 1) {
                $id = $mysqli->insert_id;
                if ($powers != null) {
                    foreach ($powers as $power) {
                        $mysqli->query("INSERT INTO service_has_group (group_id, service_id) VALUES ($id, $power)");
                    }
                }
                $response['redirect'] = "/admin/groups";
            } else {
                $response['error'] = "Errore nella creazione del gruppo";
            }
            exit(json_encode($response));
        }
    } else {
        $main = initAdmin();
        $edit = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/groups/create_group.html");

        //recupero tutti i tag dei servizi (eccetto i pubblici)
        $tags = $mysqli->query("SELECT DISTINCT service.tag FROM service WHERE service.tag NOT LIKE 'Public' AND service.tag NOT LIKE 'Gestione gruppi'");

        if ($tags->num_rows > 0) {
            $powers_template = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/groups/powers.html");
            do {
                $group_tags = $tags->fetch_assoc();
                if ($group_tags) {
                    foreach ($group_tags as $value) { //per ogni tag seleziono tutte le operazioni associate
                        $powers = $mysqli->query("
                            SELECT service.service_id, service.service_description
                            FROM service
                            WHERE service.tag = '$value';"
                        );

                        $powers_template->setContent("tag", $group_tags['tag']); //inserisco il tag nel template come titolo
                        $power_tmp = new Template($_SERVER['DOCUMENT_ROOT'] . "/skins/admin/sneat/dtml/groups/power.html");

                        do {
                            $group_powers = $powers->fetch_assoc();
                            if ($group_powers) {
                                $power_tmp->setContent("checked", "");
                                $power_tmp->setContent('service_id', $group_powers['service_id']);
                                $power_tmp->setContent('description', $group_powers['service_description']);
                            }
                        } while ($group_powers);
                        //inserisco il singolo potere nella lista dei poteri del tag
                        $powers_template->setContent("power", $power_tmp->get());
                    }
                }
            } while ($group_tags);
            $edit->setContent("powers", $powers_template->get());
            $main->setContent("content", $edit->get());
            $main->close();
        }
    }
}

function edit()
{
    /*if (!(isset($_POST['id']) && isset($_POST['name']))) {
        Header("Location: /admin/groups");
    }*/
/*
    if ($_POST["id"] == 1 || $_POST["id"] == 2) {
        $response['error'] = "Impossibile modificare il gruppo predefinito";
        exit(json_encode($response));
    }*/

    global $mysqli;
    $response = array();

    $id = $_POST["id"];
    $name = $_POST["name"];
    $description = $_POST["description"];
    $powers = $_POST["powers"] ?? null;

    if ($id != "" && $name != "" && $description != "") {
        $oid = $mysqli->query("SELECT group_name FROM `group` WHERE group_id = $id");
        $oid = $oid->fetch_assoc();
        if ($oid['group_name'] != $name) {
            $mysqli->query("UPDATE `group` SET group_name = '$name',group_description= '$description' WHERE group_id = $id");
            if ($mysqli->affected_rows == 0) {
                $response['error'] = "Errore nella modifica del name del gruppo";
                exit(json_encode($response));
            }
        }
        $oid = $mysqli->query("SELECT service_id FROM service WHERE tag = 'Gestione gruppi'");
        $groups = array();
        do {
            $groupPowers = $oid->fetch_assoc();
            if ($groupPowers) {
                $groups[] = $groupPowers['service_id'];
            }
        } while ($groupPowers);
        $mysqli->query("DELETE FROM service_has_group
                                    WHERE group_id = $id AND service_id                                          
                                    NOT IN ($groups[0], $groups[1], 
                                            $groups[2], $groups[3], 
                                            $groups[4])");
        if ($powers != null) {
            foreach ($powers as $power) {
                $mysqli->query("INSERT INTO service_has_group(service_id, group_id) VALUES ($power, $id)");
            }
            if ($mysqli->affected_rows == 0) {
                $response['warning'] = "Nessun dato modificato";
                exit(json_encode($response));
            }
        }
        $response['success'] = "Gruppo modificato con successo";
    } else {
        $response['warning'] = "Campi mancanti";
    }
    exit(json_encode($response));
}