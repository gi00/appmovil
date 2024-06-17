<?php

namespace Src\Controllers;

use Src\Services\AuthService;

class AuthController {
    private $authService;

    public function __construct() {
        $this->authService = new AuthService();
    }

    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
    
        if (empty($username) || empty($password)) {
            $response = ['status' => 'error', 'message' => 'Username or password missing'];
            $this->respondJson($response, 400);
            return;
        }
    
        $result = $this->authService->login($username, $password);
    
        if ($result['status'] === 'success') {
            $this->respondJson($result, 200);
        } else {
            $this->respondJson($result, 401);
        }
    }
    
    public function logout() {
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'] ?? '';

        if (empty($username)) {
            $response = ['status' => 'error', 'message' => 'Username missing'];
            $this->respondJson($response, 400);
            return;
        }

        $result = $this->authService->logout($username);

        $this->respondJson($result, 200);
    }

    public function validateToken() {
        $headers = apache_request_headers();
        $token = $headers['Authorization'] ?? '';

        if (empty($token)) {
            $response = ['status' => 'error', 'message' => 'Token missing'];
            $this->respondJson($response, 400);
            return;
        }

        $result = $this->authService->validateToken($token);

        if ($result) {
            $this->respondJson(['status' => 'success', 'data' => $result], 200);
        } else {
            $this->respondJson(['status' => 'error', 'message' => 'Invalid token'], 401);
        }
    }

    private function respondJson($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
    }
}
?>
