<?php

function add()
{

    echo "Eccomi!";

    global $mysqli;

    $rating = $_POST['rating'];
    $review = $_POST['review'];
    $product_id = $_POST['product_id'];

    $user_id = $_SESSION['user']['user_id'];

    $mysqli->query("INSERT INTO product_review (`text`, rating, `date`, user_id, product_id) VALUES ($review, $rating, NOW(), $user_id, $product_id);");


    if ($mysqli->affected_rows == 1) {
        // $response['success'] = "Recensione aggiunta con successo";
        exit(json_encode(array('response' => 'success')));
    } elseif ($mysqli->affected_rows == 0) {
        // $response['warning'] = "Nessuna recensione aggiunta";
        exit(json_encode(array('response' => 'warning')));
    } else {
        // $response['error'] = "Errore nell'aggiunta della recensione";
        exit(json_encode(array('response' => 'error')));
    }

}
