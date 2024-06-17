// routes/authRoutes.php
<?php
require_once __DIR__ . '/../../src/controllers/AuthController.php';

use Src\Controllers\AuthController;

$authController = new AuthController();

function handleAuthRequest($url, $method) {
    global $authController;

    if (strpos($url, '/login') !== false && $method === 'POST') {
        $authController->login();
    } elseif (strpos($url, '/logout') !== false && $method === 'POST') {
        $authController->logout();
    }
}
?>
