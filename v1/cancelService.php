<?php
require_once '../includes/DbOperations.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id'])) {
        $db = new DbOperations();
        if ($db->cancelReservedService($_POST['id'])) {
            $response['error'] = false;
            $response['message'] = "Anulowano pomyślnie";
        } else {
            $response['error'] = true;
            $response['message'] = "Wystąpił błąd, spróbuj ponownie";
        }
    } else {
        $response['error'] = true;
        $response['message'] = "Nieprawidłowe żądanie";
    }
} else {
    $response['error'] = true;
    $response['message'] = "Nieprawidłowe żądanie";
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
