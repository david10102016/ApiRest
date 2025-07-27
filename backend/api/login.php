<?php
header("Access-Control-Allow-Origin: *"); // o el dominio que uses, ej: http://localhost:4200
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include_once '../config/database.php';
include_once '../config/jwt.php';
include_once '../models/Usuario.php';

$database = new Database();
$db = $database->getConnection();

$usuario = new Usuario($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password)) {
    $usuario->email = $data->email;
    $usuario->password = $data->password; // contraseña en texto plano

    if ($usuario->login()) {
        $jwt = new JWTHandler();
        $token = $jwt->generateToken([
            'id' => $usuario->id,
            'email' => $usuario->email,
            'rol' => $usuario->rol,
            'nombre' => $usuario->nombre
        ]);

        http_response_code(200);
        echo json_encode([
            "message" => "Login exitoso",
            "token" => $token,
            "user" => [
                "id" => $usuario->id,
                "nombre" => $usuario->nombre,
                "email" => $usuario->email,
                "rol" => $usuario->rol
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["message" => "Credenciales incorrectas"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Datos incompletos"]);
}
