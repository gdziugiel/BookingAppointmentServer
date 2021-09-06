<?php
require_once '../includes/DbOperations.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['sub_service']) and isset($_GET['date'])) {
        $db = new DbOperations();
        $service = $db->getServiceIdBySubservice($_GET['sub_service']);
        $result = $db->getReservedServiceByServiceAndDate($service['service_id'], $_GET['date']);
        $response['error'] = false;
        $response['reserved_services'] = $result;
    } elseif (isset($_GET['sub_service'])) {
        $db = new DbOperations();
        $result = $db->getWorkTimeBySubservice($_GET['sub_service']);
        $response['error'] = false;
        $response['work_time'] = $result;
    } elseif (isset($_GET['service'])) {
        $db = new DbOperations();
        $result = $db->getWorkTimeByService($_GET['service']);
        $response['error'] = false;
        $response['work_time'] = $result;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['service_id'])) {
        $db = new DbOperations();
        for ($i = 0; $i < 7; $i++) {
            if ($_POST['work_time_id-' . $i] == 0 and $_POST['checked-' . $i] == 1) {
                $result = $db->addWorkTime($_POST['day_id-' . $i], $_POST['time_start-' . $i], $_POST['time_end-' . $i], $_POST['service_id']);
                if ($result) {
                    $response['error'] = false;
                    $response['message'] = "Zapisano pomyślnie";
                } else {
                    $response['error'] = true;
                    $response['message'] = "Wystąpił błąd, spróbuj ponownie";
                    break;
                }
            } elseif ($_POST['work_time_id-' . $i] != 0 and $_POST['checked-' . $i] == 1) {
                $result = $db->updateWorkTime($_POST['work_time_id-' . $i], $_POST['time_start-' . $i], $_POST['time_end-' . $i]);
                if ($result) {
                    $response['error'] = false;
                    $response['message'] = "Zapisano pomyślnie";
                } else {
                    $response['error'] = true;
                    $response['message'] = "Wystąpił błąd, spróbuj ponownie";
                    break;
                }
            } elseif ($_POST['work_time_id-' . $i] != 0 and $_POST['checked-' . $i] == 0) {
                $result = $db->deleteWorkTime($_POST['work_time_id-' . $i]);
                if ($result) {
                    $response['error'] = false;
                    $response['message'] = "Zapisano pomyślnie";
                } else {
                    $response['error'] = true;
                    $response['message'] = "Wystąpił błąd, spróbuj ponownie";
                    break;
                }
            }
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
