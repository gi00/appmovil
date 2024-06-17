<?php
// Activar el registro de errores en el archivo de salida estándar
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/php_errors.log');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/routes/authRoutes.php';

$url = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Función para registrar la petición en el archivo de log
function logRequest($url, $method) {
    $logMessage = date('Y-m-d H:i:s') . " - {$method} {$url}";
    error_log($logMessage . PHP_EOL, 3, dirname(__FILE__) . '/php_requests.log');
}

// Registrar la petición actual
logRequest($url, $method);

// Manejar la petición
handleAuthRequest($url, $method);


echo json_encode(['message' => 'No se encontró la URL solicitada']);
?>
