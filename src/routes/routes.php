// routes.php
<?php
require_once __DIR__ . '/../src/controllers/AuthController.php';


use Src\Controllers\AuthController;


$authController = new AuthController();


function handleRequest($url, $method) {
    global $authController, $anotherController;

    if (strpos($url, '/login') !== false && $method === 'POST') {
        $authController->login();
    } elseif (strpos($url, '/logout') !== false && $method === 'POST') {
        $authController->logout();
    } else {
        echo json_encode(['message' => 'No se encontró la URL solicitada']);
    }
}
?>
