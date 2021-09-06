<?php
require_once '../includes/DbOperations.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) and isset($_POST['password'])) {
        $db = new DbOperations();
        if ($db->userLogin($_POST['username'], $_POST['password'])) {
            $user = $db->getUserByUsername($_POST['username']);
            $response['error'] = false;
            $response['provider_id'] = $user['provider_id'];
            $response['username'] = $user['username'];
            $response['email'] = $user['email'];
            $response['provider_firstname'] = $user['provider_firstname'];
            $response['provider_lastname'] = $user['provider_lastname'];
        } else {
            $response['error'] = true;
            $response['message'] = "Nieprawidłowa nazwa użytkownika lub hasło";
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
