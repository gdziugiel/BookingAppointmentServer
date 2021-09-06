<?php
require_once '../includes/DbOperations.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['category']) and isset($_GET['city'])) {
        $db = new DbOperations();
        $result = $db->getProvidersByCategoryAndCity($_GET['category'], $_GET['city']);
        $response['error'] = false;
        $response['providers'] = $result;
    } elseif (isset($_GET['id'])) {
        $db = new DbOperations();
        $provider = $db->getProviderById($_GET['id']);
        $services = $db->getServicesByProvider($_GET['id']);
        $response['error'] = false;
        $response['provider_id'] = $provider['provider_id'];
        $response['provider_firstname'] = $provider['provider_firstname'];
        $response['provider_lastname'] = $provider['provider_lastname'];
        $response['email'] = $provider['email'];
        $response['services'] = $services;
    }
} else {
    $response['error'] = true;
    $response['message'] = "Nieprawidłowe żądanie";
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
