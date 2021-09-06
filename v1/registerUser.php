<?php
require_once '../includes/DbOperations.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) and isset($_POST['password']) and isset($_POST['email']) and isset($_POST['firstname']) and isset($_POST['lastname'])) {
        $db = new DbOperations();
        $result = $db->createUser($_POST['username'], $_POST['password'], $_POST['email'], $_POST['firstname'], $_POST['lastname']);
        switch ($result) {
            case 0:
                $response['error'] = true;
                $response['message'] = "Wygląda na to, że ta nazwa użytkowniak jest już zarejestrowany, wybierz inną nazwę użytkownika";
            case 1:
                $response['error'] = true;
                $response['message'] = "Wygląda na to, że ten adres e-mail jest już zarejestrowany, wybierz inny adres e-mail";
            case 2:
                $response['error'] = false;
                $response['message'] = "Użytkownik zarejestrował się pomyślnie";
                break;
            case 3:
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
