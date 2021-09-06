<?php
require_once '../includes/DbOperations.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id']) and isset($_POST['old_password']) and isset($_POST['new_password'])) {
        $db = new DbOperations();
        $result = $db->updatePassword($_POST['id'], $_POST['old_password'], $_POST['new_password']);
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
