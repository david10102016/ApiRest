<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../config/jwt.php';
include_once '../models/Cliente.php';
include_once '../middleware/auth.php';

$database = new Database();
$db = $database->getConnection();
$cliente = new Cliente($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        $user_data = validateJWT();
        
        if(isset($_GET['id'])) {
            $cliente->id = $_GET['id'];
            if($cliente->readOne()) {
                echo json_encode(array(
                    "id" => $cliente->id,
                    "nombre" => $cliente->nombre,
                    "apellido" => $cliente->apellido,
                    "email" => $cliente->email,
                    "telefono" => $cliente->telefono,
                    "direccion" => $cliente->direccion,
                    "dni" => $cliente->dni
                ));
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Cliente no encontrado"));
            }
        } else {
            $stmt = $cliente->read();
            $clientes = array();
            
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $clientes[] = $row;
            }
            
            echo json_encode($clientes);
        }
        break;

    case 'POST':
        $user_data = validateJWT();
        
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->nombre) && !empty($data->apellido) && !empty($data->dni)) {
            $cliente->nombre = $data->nombre;
            $cliente->apellido = $data->apellido;
            $cliente->email = $data->email ?? '';
            $cliente->telefono = $data->telefono ?? '';
            $cliente->direccion = $data->direccion ?? '';
            $cliente->dni = $data->dni;
            
            if($cliente->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Cliente creado exitosamente"));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Error al crear cliente"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos"));
        }
        break;

    case 'PUT':
        $user_data = validateJWT();
        
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->id)) {
            $cliente->id = $data->id;
            $cliente->nombre = $data->nombre;
            $cliente->apellido = $data->apellido;
            $cliente->email = $data->email ?? '';
            $cliente->telefono = $data->telefono ?? '';
            $cliente->direccion = $data->direccion ?? '';
            $cliente->dni = $data->dni;
            
            if($cliente->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Cliente actualizado exitosamente"));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Error al actualizar cliente"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID requerido"));
        }
        break;

    case 'DELETE':
        $user_data = requireRole('admin');
        
        if(isset($_GET['id'])) {
            $cliente->id = $_GET['id'];
            
            if($cliente->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Cliente eliminado exitosamente"));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Error al eliminar cliente"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID requerido"));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Método no permitido"));
        break;
}
?>