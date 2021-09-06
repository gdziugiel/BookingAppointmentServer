<?php
require_once '../includes/DbOperations.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['category']) and isset($_GET['city'])) {
        $db = new DbOperations();
        $result = $db->getServicesByCategoryAndCity($_GET['category'], $_GET['city']);
        $response['error'] = false;
        $response['services'] = $result;
    } elseif (isset($_GET['provider'])) {
        $db = new DbOperations();
        $result = $db->getServicesByProvider($_GET['provider']);
        $response['error'] = false;
        $response['services'] = $result;
    } elseif (isset($_GET['id'])) {
        $db = new DbOperations();
        $service = $db->getServiceById($_GET['id']);
        $subservices = $db->getSubservicesByService($_GET['id']);
        $workTime = $db->getWorkTimeByService($_GET['id']);
        $rating = $db->getRatingsByService($_GET['id']);
        $response['error'] = false;
        $response['service_id'] = $service['service_id'];
        $response['service_name'] = $service['service_name'];
        $response['service_description'] = $service['service_description'];
        $response['address'] = $service['address'];
        $response['city_name'] = $service['city_name'];
        $response['service_email'] = $service['service_email'];
        $response['phone_number'] = $service['phone_number'];
        $response['category_name'] = $service['category_name'];
        $response['provider_id'] = $service['provider_id'];
        $response['provider_firstname'] = $service['provider_firstname'];
        $response['provider_lastname'] = $service['provider_lastname'];
        $response['sub_services'] = $subservices;
        $response['work_time'] = $workTime;
        $response['rating'] = $rating['avg'];
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['edit']) and isset($_POST['service_name']) and isset($_POST['service_desc']) and isset($_POST['address']) and isset($_POST['city_id']) and isset($_POST['email']) and isset($_POST['phone_number']) and isset($_POST['category_id']) and (isset($_POST['provider_id']) or isset($_POST['service_id']))) {
        $db = new DbOperations();
        if ($_POST['edit'] == false) {
            $result = $db->addService($_POST['service_name'], $_POST['service_desc'], $_POST['address'], $_POST['city_id'], $_POST['email'], $_POST['phone_number'], $_POST['category_id'], $_POST['provider_id']);
            if ($result != 0) {
                $response['error'] = false;
                $response['message'] = "Zapisano pomyślnie";
                $response['id'] = $result;
            } else {
                $response['error'] = true;
                $response['message'] = "Wystąpił błąd, spróbuj ponownie";
            }
        } else {
            if ($_POST['edit'] == true) {
                $result = $db->updateService($_POST['service_id'], $_POST['service_name'], $_POST['service_desc'], $_POST['address'], $_POST['city_id'], $_POST['email'], $_POST['phone_number'], $_POST['category_id']);
                $response['error'] = false;
                $response['message'] = "Zapisano pomyślnie";
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
