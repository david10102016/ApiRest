<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../config/jwt.php';
include_once '../models/Venta.php';
include_once '../middleware/auth.php';

$database = new Database();
$db = $database->getConnection();
$venta = new Venta($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        $user_data = validateJWT();
        
        if(isset($_GET['id'])) {
            $venta->id = $_GET['id'];
            $result = $venta->readOne();
            if($result) {
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Venta no encontrada"));
            }
        } else {
            $stmt = $venta->read();
            $ventas = array();
            
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $ventas[] = $row;
            }
            
            echo json_encode($ventas);
        }
        break;

    case 'POST':
        $user_data = validateJWT();
        
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->cliente_id) && !empty($data->auto_id) && !empty($data->precio_venta)) {
            $venta->cliente_id = $data->cliente_id;
            $venta->auto_id = $data->auto_id;
            $venta->usuario_id = $user_data['id'];
            $venta->precio_venta = $data->precio_venta;
            $venta->observaciones = $data->observaciones ?? '';
            
            if($venta->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Venta registrada exitosamente"));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Error al registrar venta"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos"));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Método no permitido"));
        break;
}
?>