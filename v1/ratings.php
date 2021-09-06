<?php
require_once '../includes/DbOperations.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['service'])) {
        $db = new DbOperations();
        $result = $db->getRatingsByService($_GET['service']);
        $response['error'] = false;
        $response['rating'] = $result['avg'];
    } else {
        $response['error'] = true;
        $response['message'] = "Nieprawidłowe żądanie";
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['value']) and isset($_POST['service'])) {
        $db = new DbOperations();
        $result = $db->setRatingsByService($_POST['value'], $_POST['service']);
        $response['error'] = false;
        $response['message'] = "Zapisano pomyślnie";
    } else {
        $response['error'] = true;
        $response['message'] = "Nieprawidłowe żądanie";
    }
} else {
    $response['error'] = true;
    $response['message'] = "Nieprawidłowe żądanie";
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
