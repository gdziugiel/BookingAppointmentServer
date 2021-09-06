<?php
require_once '../includes/DbOperations.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id']) and isset($_POST['username']) and isset($_POST['email']) and isset($_POST['firstname']) and isset($_POST['lastname'])) {
        $db = new DbOperations();
        $result = $db->updateProfile($_POST['id'], $_POST['username'], $_POST['email'], $_POST['firstname'], $_POST['lastname']);
        if ($result) {
            $response['error'] = false;
            $response['message'] = "Zapisano pomyślnie";
        } else {
            $response['error'] = true;
            $response['message'] = "Wystąpił błąd, spróbuj ponownie";
        }
    } else {
        $response['error'] = true;
        $response['message'] = "Brak wymaganych pól";
    }
} else {
    $response['error'] = true;
    $response['message'] = "Nieprawidłowe żądanie";
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
