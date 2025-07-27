<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../config/jwt.php';
include_once '../models/Auto.php';
include_once '../middleware/auth.php';

$database = new Database();
$db = $database->getConnection();
$auto = new Auto($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        $user_data = validateJWT();
        
        if(isset($_GET['id'])) {
            $auto->id = $_GET['id'];
            if($auto->readOne()) {
                echo json_encode(array(
                    "id" => $auto->id,
                    "marca" => $auto->marca,
                    "modelo" => $auto->modelo,
                    "año" => $auto->año,
                    "precio" => $auto->precio,
                    "color" => $auto->color,
                    "kilometraje" => $auto->kilometraje,
                    "combustible" => $auto->combustible,
                    "transmision" => $auto->transmision,
                    "descripcion" => $auto->descripcion
                ));
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Auto no encontrado"));
            }
        } else {
            $stmt = $auto->read();
            $autos = array();
            
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $autos[] = $row;
            }
            
            echo json_encode($autos);
        }
        break;

    case 'POST':
        $user_data = requireRole('admin');
        
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->marca) && !empty($data->modelo) && !empty($data->año) && !empty($data->precio)) {
            $auto->marca = $data->marca;
            $auto->modelo = $data->modelo;
            $auto->año = $data->año;
            $auto->precio = $data->precio;
            $auto->color = $data->color ?? '';
            $auto->kilometraje = $data->kilometraje ?? 0;
            $auto->combustible = $data->combustible ?? '';
            $auto->transmision = $data->transmision ?? '';
            $auto->descripcion = $data->descripcion ?? '';
            
            if($auto->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Auto creado exitosamente"));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Error al crear auto"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos"));
        }
        break;

    case 'PUT':
        $user_data = requireRole('admin');
        
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->id)) {
            $auto->id = $data->id;
            $auto->marca = $data->marca;
            $auto->modelo = $data->modelo;
            $auto->año = $data->año;
            $auto->precio = $data->precio;
            $auto->color = $data->color ?? '';
            $auto->kilometraje = $data->kilometraje ?? 0;
            $auto->combustible = $data->combustible ?? '';
            $auto->transmision = $data->transmision ?? '';
            $auto->descripcion = $data->descripcion ?? '';
            
            if($auto->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Auto actualizado exitosamente"));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Error al actualizar auto"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID requerido"));
        }
        break;

    case 'DELETE':
        $user_data = requireRole('admin');
        
        if(isset($_GET['id'])) {
            $auto->id = $_GET['id'];
            
            if($auto->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Auto eliminado exitosamente"));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Error al eliminar auto"));
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