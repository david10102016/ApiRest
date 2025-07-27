<?php
class Venta {
    private $conn;
    private $table_name = "ventas";

    public $id;
    public $cliente_id;
    public $auto_id;
    public $usuario_id;
    public $precio_venta;
    public $fecha_venta;
    public $observaciones;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT v.*, 
                         CONCAT(c.nombre, ' ', c.apellido) as cliente_nombre,
                         CONCAT(a.marca, ' ', a.modelo, ' ', a.año) as auto_descripcion,
                         u.nombre as vendedor_nombre
                  FROM " . $this->table_name . " v
                  JOIN clientes c ON v.cliente_id = c.id
                  JOIN autos a ON v.auto_id = a.id
                  JOIN usuarios u ON v.usuario_id = u.id
                  ORDER BY v.fecha_venta DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT v.*, 
                         CONCAT(c.nombre, ' ', c.apellido) as cliente_nombre,
                         CONCAT(a.marca, ' ', a.modelo, ' ', a.año) as auto_descripcion,
                         u.nombre as vendedor_nombre
                  FROM " . $this->table_name . " v
                  JOIN clientes c ON v.cliente_id = c.id
                  JOIN autos a ON v.auto_id = a.id
                  JOIN usuarios u ON v.usuario_id = u.id
                  WHERE v.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        
        return $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC) : false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET cliente_id=:cliente_id, auto_id=:auto_id, usuario_id=:usuario_id, 
                    precio_venta=:precio_venta, observaciones=:observaciones";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':cliente_id', $this->cliente_id);
        $stmt->bindParam(':auto_id', $this->auto_id);
        $stmt->bindParam(':usuario_id', $this->usuario_id);
        $stmt->bindParam(':precio_venta', $this->precio_venta);
        $stmt->bindParam(':observaciones', $this->observaciones);

        return $stmt->execute();
    }
}
