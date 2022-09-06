<?php

#[NoReturn] function add(): void
{
    global $mysqli;

    $rating = $_POST['rating'];
    $review = $_POST['review'];
    $product_id = $_POST['product_id'];

    $user_id = $_SESSION['user']['user_id'];

    try {
        $mysqli->query("INSERT INTO product_review (`text`, rating, `date`, user_id, product_id) VALUES ('$review', $rating, NOW(), $user_id, $product_id);");

        if ($mysqli->affected_rows == 1) {
            $response['success'] = "Recensione aggiunta con successo";
        } elseif ($mysqli->affected_rows == 0) {
            $response['warning'] = "Nessuna recensione aggiunta";
        } else {
            $response['error'] = "Errore nell'inserimento";
        }
    } catch (mysqli_sql_exception $e) {
        $response['error'] = $e->getMessage();
    }

    exit(json_encode($response));

}
