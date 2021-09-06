<?php
require_once '../includes/DbOperations.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['sub_service']) and isset($_GET['date']) and isset($_GET['all_day'])) {
        $db = new DbOperations();
        $result = $db->getFreeTimeBySubserviceAndDate($_GET['sub_service'], $_GET['date'], $_GET['all_day']);
        $response['error'] = false;
        $response['free_time'] = $result;
    } elseif (isset($_GET['service']) and isset($_GET['date']) and isset($_GET['all_day'])) {
        $db = new DbOperations();
        $result = $db->getFreeTimeByServiceAndDate($_GET['service'], $_GET['date'], $_GET['all_day']);
        $response['error'] = false;
        $response['free_time'] = $result;
    } elseif (isset($_GET['service']) and isset($_GET['date'])) {
        $db = new DbOperations();
        $result = $db->getAllFreeTimeByServiceAndDate($_GET['service'], $_GET['date']);
        $response['error'] = false;
        $response['free_time'] = $result;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['service_id']) and isset($_POST['time_start']) and isset($_POST['time_end']) and isset($_POST['all_day'])) {
        $db = new DbOperations();
        $result = $db->addFreeTime($_POST['all_day'], $_POST['time_start'], $_POST['time_end'], $_POST['service_id']);
        if ($result) {
            $result = $db->cancelReservedServiceByDate($_POST['time_start'], $_POST['time_end']);
            if ($result) {
                $response['error'] = false;
                $response['message'] = "Zapisano pomyślnie";
            } else {
                $response['error'] = true;
                $response['message'] = "Wystąpił błąd, spróbuj ponownie";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Wystąpił błąd, spróbuj ponownie";
        }
    } elseif (isset($_POST['id'])) {
        $db = new DbOperations();
        $result = $db->deleteFreeTime($_POST['id']);
        if ($result) {
            $response['error'] = false;
            $response['message'] = "Zapisano pomyślnie";
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
