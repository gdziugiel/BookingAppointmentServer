<?php
require_once '../includes/DbOperations.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $db = new DbOperations();
    $result = $db->getCities();
    $response['error'] = false;
    $response['cities'] = $result;
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['city_name'])) {
        $db = new DbOperations();
        $result = $db->addCity($_POST['city_name']);
        switch ($result) {
            case 0:
                $response['error'] = true;
                $response['message'] = "Wygląda na to, że to miasto już istnieje";
                break;
            case 1:
                $response['error'] = false;
                $response['message'] = "Dodano pomyślnie";
                break;
            case 2:
                $response['error'] = true;
                $response['message'] = "Wystąpił błąd, spróbuj ponownie";
                break;
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
