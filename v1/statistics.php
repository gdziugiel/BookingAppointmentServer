<?php
require_once '../includes/DbOperations.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $db = new DbOperations();
        $countClients = $db->getCountClientsByProvider($_GET['id']);
        $mostPopularHours = $db->getMostPopularHoursByProvider($_GET['id']);
        $mostPopularServices = $db->getMostPopularServicesByProvider($_GET['id']);
        $mostPopularSubservices = $db->getMostPopularSubServicesByProvider($_GET['id']);
        $mostProfitableServices = $db->getMostProfitableServicesByProvider($_GET['id']);
        $mostProfitableSubservices = $db->getMostProfitableSubServicesByProvider($_GET['id']);
        $countReservedServices = $db->getCountReservedServicesByProvider($_GET['id']);
        $ratings = $db->getRatingsByProvider($_GET['id']);
        $newClients = 0;
        $oldClients = 0;
        $realised = 0;
        $unrealised = 0;
        $canceled = 0;
        for ($i = 0; $i < count($countClients); $i++) {
            if ($countClients[$i]['sum'] > 1) {
                $oldClients++;
            } else {
                $newClients++;
            }
        }
        for ($i = 0; $i < count($countReservedServices); $i++) {
            if ($countReservedServices[$i]['realised'] == 1) {
                $realised = $countReservedServices[$i]['sum'];
            } elseif ($countReservedServices[$i]['realised'] == 0 and $countReservedServices[$i]['canceled'] == 1) {
                $canceled = $countReservedServices[$i]['sum'];
            } else {
                $unrealised = $countReservedServices[$i]['sum'];
            }
        }
        $response['error'] = false;
        $response['new_clients'] = $newClients;
        $response['old_clients'] = $oldClients;
        $response['most_popular_hours'] = $mostPopularHours;
        $response['most_popular_services'] = $mostPopularServices;
        $response['most_popular_sub_services'] = $mostPopularSubservices;
        $response['most_profitable_services'] = $mostProfitableServices;
        $response['most_profitable_sub_services'] = $mostProfitableSubservices;
        $response['realised'] = $realised;
        $response['unrealised'] = $unrealised;
        $response['canceled'] = $canceled;
        $response['rating'] = $ratings['avg'];
    } else {
        $response['error'] = true;
        $response['message'] = "Nieprawidłowe żądanie";
    }
} else {
    $response['error'] = true;
    $response['message'] = "Nieprawidłowe żądanie";
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
