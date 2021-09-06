<?php
require_once '../includes/DbOperations.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['sub_service_id']) and isset($_POST['client_firstname']) and isset($_POST['client_lastname']) and isset($_POST['client_email']) and isset($_POST['client_phone_number']) and isset($_POST['date_time'])) {
        $db = new DbOperations();
        $result = $db->bookAppointment($_POST['sub_service_id'], $_POST['client_firstname'], $_POST['client_lastname'], $_POST['client_email'], $_POST['client_phone_number'], $_POST['date_time']);
        if ($result != 0) {
            $response['error'] = false;
            $response['message'] = "Zarezerwowano pomyślnie";
            $response['id'] = $result;
            $info = $db->getReservationInfoById($result);
            $response['service_name'] = $info['service_name'];
            $response['sub_service_name'] = $info['sub_service_name'];
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
