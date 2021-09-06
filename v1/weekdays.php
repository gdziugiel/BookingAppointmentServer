<?php
require_once '../includes/DbOperations.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $db = new DbOperations();
        $result = $db->getWeekdays();
        $workTime = $db->getWorkTimeByService($_GET['id']);
        $response['error'] = false;
        $response['weekdays'] = $result;
        $response['work_time'] = $workTime;
    } else {
        $db = new DbOperations();
        $result = $db->getWeekdays();
        $response['error'] = false;
        $response['weekdays'] = $result;
    }
} else {
    $response['error'] = true;
    $response['message'] = "Nieprawidłowe żądanie";
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
