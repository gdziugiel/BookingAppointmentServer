<?php
require_once '../includes/DbOperations.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id']) and isset($_GET['email']) and $_GET['id'] != "" and $_GET['email'] != "") {
        $db = new DbOperations();
        $result = $db->getReservedServiceByIdAndEmail($_GET['id'], $_GET['email']);
        if ($result != null) {
            $rating = $db->getRatingsByReservedService($_GET['id']);
            $response['error'] = false;
            $response['service_name'] = $result['service_name'];
            $response['sub_service_name'] = $result['sub_service_name'];
            $response['price'] = $result['price'];
            $response['duration'] = $result['duration'];
            $response['address'] = $result['address'];
            $response['city_name'] = $result['city_name'];
            $response['service_email'] = $result['service_email'];
            $response['phone_number'] = $result['phone_number'];
            $response['client_phone_number'] = $result['client_phone_number'];
            $response['provider_firstname'] = $result['provider_firstname'];
            $response['provider_lastname'] = $result['provider_lastname'];
            $response['client_firstname'] = $result['client_firstname'];
            $response['client_lastname'] = $result['client_lastname'];
            $response['date_time'] = $result['date_time'];
            $response['realised'] = $result['realised'];
            $response['canceled'] = $result['canceled'];
            $response['rating'] = $rating != null ? $rating['value'] : 0;
        } else {
            $response['error'] = true;
            $response['message'] = "Nie znaleziono usługi";
        }
    } elseif (isset($_GET['service_id']) and isset($_GET['date'])) {
        $db = new DbOperations();
        $result = $db->getReservedServicseByServiceAndDate($_GET['service_id'], $_GET['date']);
        if ($result != null) {
            $response['error'] = false;
            $response['reserved_services'] = $result;
        } else {
            $response['error'] = true;
            $response['message'] = "Nie znaleziono usługi";
        }
    } elseif (isset($_GET['id'])) {
        $db = new DbOperations();
        $result = $db->getReservationInfoById($_GET['id']);
        if ($result != null) {
            $response['error'] = false;
            $response['reserved_service'] = $result;
        } else {
            $response['error'] = true;
            $response['message'] = "Nie znaleziono usługi";
        }
    } elseif (isset($_GET['provider_id'])) {
        $db = new DbOperations();
        $result = $db->getLatestReservedServiceByProvider($_GET['provider_id']);
        $canceled = $db->getLatestCanceledServiceByProvider($_GET['provider_id']);
        if ($result != null or $canceled != null) {
            $response['error'] = false;
            $response['reserved_service'] = $result;
            $response['canceled_service'] = $canceled;
        } else {
            $response['error'] = true;
            $response['message'] = "Nie znaleziono usługi";
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
