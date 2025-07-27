<?php
function validateJWT() {
    $headers = getallheaders();
    $jwt = null;

    if (isset($headers['Authorization'])) {
        $jwt = str_replace('Bearer ', '', $headers['Authorization']);
    }

    if (!$jwt) {
        http_response_code(401);
        echo json_encode(array("message" => "Token de acceso requerido"));
        exit();
    }

    $jwtHandler = new JWTHandler();
    $user_data = $jwtHandler->validateToken($jwt);

    if (!$user_data) {
        http_response_code(401);
        echo json_encode(array("message" => "Token inválido"));
        exit();
    }

    return $user_data;
}

function requireRole($required_role) {
    $user_data = validateJWT();
    
    if ($user_data['rol'] !== $required_role && $user_data['rol'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array("message" => "Permisos insuficientes"));
        exit();
    }
    
    return $user_data;
}