<?php

namespace Src\Services;

use Src\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Class AuthService
 *
 * Servicio para manejar la autenticación y generación de tokens JWT.
 */
class AuthService {
    private $userModel;
    private $secretKey;

    /**
     * AuthService constructor.
     */
    public function __construct() {
        $this->userModel = new User();
        // Configurar la clave secreta directamente en el código
        $this->secretKey = 'sOlut1ons2013';

        // Verificar si la clave secreta se está cargando correctamente
        // if (!$this->secretKey) {
        //     throw new \Exception('Clave secreta no encontrada');
        // } else {
        //     echo "key secret: " . $this->secretKey . "<br>";
        // }
    }

    /**
     * Inicia sesión del usuario.
     *
     * @param string $username
     * @param string $password
     * @return array
     */
    public function login($username, $password) {
        $user = $this->userModel->findUserByUsername($username);
        // Comparar las contraseñas en texto plano
        if ($user && $password === $user['password']) {
            // Cerrar otras sesiones
            $this->userModel->logoutAllSessions($username);
            // Iniciar sesión
            $this->userModel->setLoginStatus($username, true);
            // Generar token
            $token = $this->generateToken($user);
            return ['status' => 'success', 'token' => $token, 'user' => $user];
        }
        return ['status' => 'error', 'message' => 'credentials incorrectos'];
    }

    /**
     * Cierra sesión del usuario.
     *
     * @param string $username
     * @return array
     */
    public function logout($username) {
        $this->userModel->setLoginStatus($username, false);
        return ['status' => 'success', 'message' => 'Sesión cerrada'];
    }

    /**
     * Genera un token JWT.
     *
     * @param array $user
     * @return string
     */
    private function generateToken($user) {
        $payload = [
            'iss' => 'electronicrs.com',
            'iat' => time(),
            'exp' => time() + (60 * 60),  // Token válido por 1 hora
            'sub' => $user['id']
        ];
        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    /**
     * Valida un token JWT.
     *
     * @param string $token
     * @return array|null
     */
    public function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }
}
