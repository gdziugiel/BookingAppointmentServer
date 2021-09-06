<?php
require_once '../includes/DbOperations.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['service'])) {
        $db = new DbOperations();
        $result = $db->getSubservicesByService($_GET['service']);
        $response['error'] = false;
        $response['sub_services'] = $result;
    } elseif (isset($_GET['id'])) {
        $db = new DbOperations();
        $subervice = $db->getSubserviceById($_GET['id']);
        $response['error'] = false;
        $response['sub_service_id'] = $subervice['sub_service_id'];
        $response['service_id'] = $subervice['service_id'];
        $response['sub_service_name'] = $subervice['sub_service_name'];
        $response['sub_service_description'] = $subervice['sub_service_description'];
        $response['price'] = $subervice['price'];
        $response['duration'] = $subervice['duration'];
        $response['service_name'] = $subervice['service_name'];
        $result = $db->getMinDurationByService($subervice['service_id']);
        $response['min_duration'] = $result['min_duration'];
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['edit']) and isset($_POST['sub_service_name']) and isset($_POST['sub_service_desc']) and isset($_POST['price']) and isset($_POST['duration']) and (isset($_POST['service_id']) or isset($_POST['sub_service_id']))) {
        $db = new DbOperations();
        if ($_POST['edit'] == false) {
            $result = $db->addSubService($_POST['sub_service_name'], $_POST['sub_service_desc'], $_POST['price'], $_POST['duration'], $_POST['service_id']);
            if ($result) {
                $response['error'] = false;
                $response['message'] = "Zapisano pomyślnie";
            } else {
                $response['error'] = true;
                $response['message'] = "Wystąpił błąd, spróbuj ponownie";
            }
        } else {
            if ($_POST['edit'] == true) {
                $result = $db->updateSubService($_POST['sub_service_id'], $_POST['sub_service_name'], $_POST['sub_service_desc'], $_POST['price'], $_POST['duration']);
                if ($result) {
                    $response['error'] = false;
                    $response['message'] = "Zapisano pomyślnie";
                } else {
                    $response['error'] = true;
                    $response['message'] = "Wystąpił błąd, spróbuj ponownie";
                }
            }
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
