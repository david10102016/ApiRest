<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler {
    private $secret_key = "tu_clave_secreta_super_segura_2024";
    private $issuer = "concesionaria-api";
    private $audience = "concesionaria-users";
    private $issued_at;
    private $expiration_time;

    public function __construct() {
        $this->issued_at = time();
        $this->expiration_time = $this->issued_at + (24 * 60 * 60); // 24 horas
    }

    public function generateToken($user_data) {
        $payload = array(
            "iss" => $this->issuer,
            "aud" => $this->audience,
            "iat" => $this->issued_at,
            "exp" => $this->expiration_time,
            "data" => array(
                "id" => $user_data['id'],
                "email" => $user_data['email'],
                "rol" => $user_data['rol'],
                "nombre" => $user_data['nombre']
            )
        );

        return JWT::encode($payload, $this->secret_key, 'HS256');
    }

    public function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->secret_key, 'HS256'));
            return (array) $decoded->data;
        } catch (Exception $e) {
            return false;
        }
    }
}
